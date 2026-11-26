<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectSpkRequest;
use App\Http\Requests\StoreSpkRequest;
use App\Models\Project;
use App\Models\Spk;
use App\Services\LetterNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SpkController extends Controller
{
    /**
     * Menampilkan daftar SPK sesuai peran user yang login.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Spk::class);

        $user = Auth::user();
        $verifierRole = Spk::normalizeVerifierRole($user->role);
        $isVerifier = $verifierRole !== '';

        // Default tab: verifikator melihat antrean tugasnya ('queue'), Supervisor melihat semua ('all')
        $defaultScope = $isVerifier ? 'queue' : 'all';
        $scope = $request->query('scope', $defaultScope);
        if (! in_array($scope, ['queue', 'all'], true)) {
            $scope = $defaultScope;
        }

        $queueStatuses = $this->queueStatusesForRole($verifierRole);

        $query = Spk::with([
            'project.customer',
            'project.endUser',
            'project.po',
            'logisticsVerifier',
            'financeVerifier',
            'directorApprover',
        ])->latest();

        if ($scope === 'queue' && $isVerifier) {
            $query->whereIn('status_verifikasi', $queueStatuses);
        } elseif ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status_verifikasi', $request->query('status'));
        }

        $spks = $query->paginate(12)->withQueryString();

        $queueCount = $isVerifier
            ? Spk::whereIn('status_verifikasi', $queueStatuses)->count()
            : 0;

        $statusCounts = [
            'all'                 => Spk::count(),
            'Draft'               => Spk::where('status_verifikasi', 'Draft')->count(),
            'Verifikasi Logistik' => Spk::where('status_verifikasi', 'Verifikasi Logistik')->count(),
            'Verifikasi Keuangan' => Spk::where('status_verifikasi', 'Verifikasi Keuangan')->count(),
            'Terbit'              => Spk::where('status_verifikasi', 'Terbit')->count(),
            'Dibatalkan'          => Spk::where('status_verifikasi', 'Dibatalkan')->count(),
        ];

        return view('spks.index', compact(
            'spks',
            'scope',
            'isVerifier',
            'verifierRole',
            'queueCount',
            'statusCounts'
        ));
    }

    /**
     * Form pengajuan Draft SPK baru (khusus Supervisor).
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Spk::class);

        $projects = Project::with(['customer', 'endUser', 'po'])
            ->whereDoesntHave('spks', function ($q) {
                $q->where('status_verifikasi', '!=', 'Dibatalkan');
            })
            ->latest()
            ->get();

        $selectedProjectId = $request->query('project_id');

        return view('spks.create', compact('projects', 'selectedProjectId'));
    }

    /**
     * Menyimpan Draft SPK baru dengan nomor SPK otomatis dari LetterNumberService.
     */
    public function store(StoreSpkRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $spk = DB::transaction(function () use ($validated) {
                // Pastikan tidak ada SPK aktif selain yang sudah dibatalkan pada proyek ini
                $hasActiveSpk = Spk::where('project_id', $validated['project_id'])
                    ->where('status_verifikasi', '!=', 'Dibatalkan')
                    ->lockForUpdate()
                    ->exists();

                if ($hasActiveSpk) {
                    throw new \RuntimeException('Proyek ini sudah memiliki SPK aktif. Batalkan SPK sebelumnya terlebih dahulu jika ingin mengajukan SPK baru.');
                }

                return Spk::create([
                    'project_id'        => $validated['project_id'],
                    'no_spk'            => LetterNumberService::generateSpkNumber(),
                    'tgl_spk'           => $validated['tgl_spk'],
                    'status_verifikasi' => 'Draft',
                ]);
            });

            return redirect()
                ->route('spks.show', $spk)
                ->with('success', 'Draft SPK berhasil diajukan dengan nomor otomatis: ' . $spk->no_spk . '. Menunggu verifikasi Logistik.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal mengajukan SPK: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail SPK, status berjenjang, dan jejak audit.
     */
    public function show(Spk $spk): View
    {
        Gate::authorize('view', $spk);

        $spk->load([
            'project.customer',
            'project.endUser',
            'project.pic',
            'project.po',
            'project.staff',
            'project.equipments',
            'logisticsVerifier',
            'financeVerifier',
            'directorApprover',
        ]);

        return view('spks.show', compact('spk'));
    }

    /**
     * Menyetujui (Approve) SPK pada tahap verifikasi sesuai role yang login.
     */
    public function approve(Spk $spk): RedirectResponse
    {
        Gate::authorize('approve', $spk);

        $user = Auth::user();
        $role = (string) $user->role;

        try {
            $updatedSpk = DB::transaction(function () use ($spk, $user, $role) {
                $lockedSpk = Spk::whereKey($spk->id)->lockForUpdate()->firstOrFail();

                if (! $lockedSpk->canBeVerifiedBy($role)) {
                    throw new \RuntimeException('SPK tidak berada pada tahap verifikasi untuk peran Anda atau sudah diproses.');
                }

                $nextStatus = $lockedSpk->nextStatusForRole($role);
                $prefix = Spk::auditPrefixForRole($role);

                if ($nextStatus === null || $prefix === null) {
                    throw new \RuntimeException('Transisi status SPK tidak valid.');
                }

                $lockedSpk->update([
                    'status_verifikasi' => $nextStatus,
                    "{$prefix}_by"      => $user->id,
                    "{$prefix}_at"      => now(),
                ]);

                return $lockedSpk;
            });

            $message = $updatedSpk->status_verifikasi === 'Terbit'
                ? 'SPK ' . $updatedSpk->no_spk . ' telah disetujui Direktur dan resmi berstatus TERBIT.'
                : 'SPK ' . $updatedSpk->no_spk . ' berhasil diverifikasi. Status sekarang: ' . $updatedSpk->status_verifikasi . '.';

            return back()->with('success', $message);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menolak / membatalkan SPK pada tahap verifikasi aktif dengan alasan wajib.
     */
    public function reject(RejectSpkRequest $request, Spk $spk): RedirectResponse
    {
        $user = Auth::user();
        $role = (string) $user->role;
        $validated = $request->validated();

        try {
            $updatedSpk = DB::transaction(function () use ($spk, $user, $role, $validated) {
                $lockedSpk = Spk::whereKey($spk->id)->lockForUpdate()->firstOrFail();

                if (! $lockedSpk->canBeVerifiedBy($role)) {
                    throw new \RuntimeException('SPK tidak dapat dibatalkan karena tidak berada pada tahap verifikasi Anda.');
                }

                $prefix = Spk::auditPrefixForRole($role);
                $payload = [
                    'status_verifikasi' => 'Dibatalkan',
                    'alasan_batal'      => $validated['alasan_batal'],
                ];

                if ($prefix !== null) {
                    $payload["{$prefix}_by"] = $user->id;
                    $payload["{$prefix}_at"] = now();
                }

                $lockedSpk->update($payload);

                return $lockedSpk;
            });

            return back()->with('success', 'SPK ' . $updatedSpk->no_spk . ' telah ditolak / dibatalkan.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Daftar status yang menjadi antrean verifikasi role tertentu.
     */
    private function queueStatusesForRole(string $verifierRole): array
    {
        return match ($verifierRole) {
            'Logistics' => ['Draft'],
            'Finance'   => ['Verifikasi Logistik'],
            'Director'  => ['Verifikasi Keuangan', 'Persetujuan Direktur'],
            default     => [],
        };
    }
}
