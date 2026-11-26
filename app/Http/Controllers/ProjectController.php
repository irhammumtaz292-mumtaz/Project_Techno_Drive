<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Customer;
use App\Models\EndUser;
use App\Models\User;
use App\Models\TestEquipment;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\LetterNumberService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectController extends Controller
{
    // Menampilkan daftar proyek (Supervisor & Director lihat semua; Staff lihat proyek yang ditugaskan)
    public function index()
    {
        $user = Auth::user();
        $query = Project::with(['customer', 'endUser', 'po', 'staff'])->latest();

        if ($user && $user->hasRole('Staff') && ! $user->hasRole('Supervisor', 'Director', 'Finance', 'Logistics')) {
            $query->whereHas('staff', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        $projects = $query->paginate(10);
        return view('projects.index', compact('projects'));
    }

    // Menampilkan form registrasi proyek terpadu (Supervisor)
    public function create()
    {
        $customers = Customer::all();
        $endUsers = EndUser::all();
        // Hanya ambil user dengan role Staff untuk ditugaskan sebagai pelaksana
        $staffs = User::where('role', 'Staff')->get();
        // Hanya ambil user dengan role Staff & Supervisor sebagai pilihan PIC Techno Drive
        $pics = User::whereIn('role', ['Supervisor', 'Staff'])->get();
        // Hanya ambil alat uji yang statusnya 'Tersedia'
        $equipments = TestEquipment::where('status', 'Tersedia')->get();

        return view('projects.create', compact('customers', 'endUsers', 'staffs', 'pics', 'equipments'));
    }

    // Menyimpan data registrasi proyek dan PO secara transaksional
    public function store(Request $request)
    {
        // 1. Validasi input form dasar (tanpa file upload lampiran PO)
        $request->validate([
            'name'                    => 'required|string|max:255',
            'customer_id'             => 'required|exists:customers,id',
            'end_user_id'             => 'required|exists:end_users,id',
            'pic_id'                  => 'required|exists:users,id',
            'tgl_mulai'               => 'required|date',
            'tgl_selesai'             => 'required|date|after_or_equal:tgl_mulai',
            'tgl_jatuh_tempo_kontrak' => 'required|date',
            'deadline_laporan'        => 'required|date',
            'description'             => 'nullable|string',
            'progress_notes'          => 'nullable|string',
            // Validasi PO
            'tgl_po'                  => 'required|date',
            'nilai_pekerjaan'         => 'required|numeric|min:0',
            // Penugasan & Alat
            'staffs'                  => 'required|array|min:1',
            'staffs.*'                => 'integer|exists:users,id',
            'equipments'              => 'required|array|min:1',
            'equipments.*'            => 'integer|exists:test_equipments,id',
        ], [
            'equipments.required' => 'Minimal pilih 1 alat uji yang digunakan untuk proyek ini.',
            'equipments.min'      => 'Minimal pilih 1 alat uji yang digunakan untuk proyek ini.',
        ]);

        // 2. VALIDASI KALIBRASI & BENTROK JADWAL ALAT UJI
        if ($request->has('equipments') && is_array($request->equipments)) {
            $today = Carbon::today();

            foreach ($request->equipments as $eqId) {
                $equipment = TestEquipment::find($eqId);
                if (! $equipment) {
                    return back()->withInput()->with('error', 'Alat uji yang dipilih tidak ditemukan.');
                }

                // Cek Kalibrasi (kedaluwarsa jika tanggal kalibrasi sudah lewat dari hari ini)
                if ($equipment->calibration_expiry && Carbon::parse($equipment->calibration_expiry)->startOfDay()->lt($today)) {
                    return back()->withInput()->with('error', "Alat uji {$equipment->name} (SN: {$equipment->serial_number}) tidak dapat dipilih karena masa berlaku kalibrasinya sudah kedaluwarsa.");
                }

                // Cek Bentrok Jadwal dengan Proyek lain yang masih aktif (overlap dua rentang tanggal)
                $isConflict = Project::where('status_pekerjaan', '!=', 'Completed')
                    ->whereHas('equipments', function ($q) use ($eqId) {
                        $q->where('test_equipments.id', $eqId);
                    })
                    ->whereDate('tgl_mulai', '<=', $request->tgl_selesai)
                    ->whereDate('tgl_selesai', '>=', $request->tgl_mulai)
                    ->exists();

                if ($isConflict) {
                    return back()->withInput()->with('error', "Alat uji {$equipment->name} sedang terjadwal pada rentang tanggal proyek lain yang bersinggungan.");
                }
            }
        }

        DB::beginTransaction();
        try {
            // 3. Simpan data utama proyek
            $project = Project::create([
                'customer_id'             => $request->customer_id,
                'end_user_id'             => $request->end_user_id,
                'pic_id'                  => $request->pic_id,
                'name'                    => $request->name,
                'description'             => $request->description,
                'progress_notes'          => $request->progress_notes,
                'tgl_mulai'               => $request->tgl_mulai,
                'tgl_selesai'             => $request->tgl_selesai,
                'tgl_jatuh_tempo_kontrak' => $request->tgl_jatuh_tempo_kontrak,
                'deadline_laporan'        => $request->deadline_laporan,
                'status_pekerjaan'        => 'On Progress',
                'status_pembayaran'       => 'Waiting Payment',
            ]);

            // 4. GENERATE NOMOR PO OTOMATIS
            $noPoOtomatis = LetterNumberService::generatePoNumber();

            // 5. Simpan data Purchase Order (PO)
            PurchaseOrder::create([
                'project_id'      => $project->id,
                'no_po'           => $noPoOtomatis,
                'tgl_po'          => $request->tgl_po,
                'nilai_pekerjaan' => $request->nilai_pekerjaan,
                'file_path'       => null,
            ]);

            // 6. Hubungkan Staf yang ditugaskan (tabel pivot project_user)
            $project->staff()->attach($request->staffs);

            // 7. Hubungkan Alat Uji (wajib) & ubah status alat
            $project->equipments()->attach($request->equipments);
            TestEquipment::whereIn('id', $request->equipments)->update(['status' => 'Sedang dipakai']);

            DB::commit();
            return redirect()->route('projects.show', $project->id)->with('success', 'Proyek berhasil diregistrasikan dengan Nomor PO Otomatis: ' . $noPoOtomatis . '. Anda dapat mengunduh dokumen PO (PDF) di halaman ini.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // Menampilkan detail proyek
    public function show(Project $project)
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Staff') && ! $user->hasRole('Supervisor', 'Director', 'Finance', 'Logistics')) {
            abort_unless(
                $project->staff()->where('users.id', $user->id)->exists(),
                403,
                'Anda tidak ditugaskan pada proyek ini.'
            );
        }

        $project->load(['customer', 'endUser', 'pic', 'po', 'staff', 'equipments', 'spks', 'finalReports']);
        return view('projects.show', compact('project'));
    }

    // Halaman Verifikasi Pembayaran (Supervisor & Finance)
    public function paymentIndex()
    {
        abort_unless(Auth::user()->hasRole('Supervisor', 'Finance'), 403, 'Akses ditolak.');
        $projects = Project::with(['customer', 'po'])->latest()->paginate(15);
        return view('projects.payment_index', compact('projects'));
    }

    // Memperbarui status pembayaran (Supervisor / Finance: Waiting Payment <-> Paid)
    public function updatePaymentStatus(Request $request, Project $project)
    {
        abort_unless(Auth::user()->hasRole('Supervisor', 'Finance'), 403, 'Akses ditolak. Hanya Supervisor atau Finance yang dapat mengubah status pembayaran.');

        $request->validate([
            'status_pembayaran' => 'required|in:Waiting Payment,Paid'
        ]);

        $project->update([
            'status_pembayaran' => $request->status_pembayaran
        ]);

        return back()->with('success', 'Status pembayaran proyek "' . $project->name . '" berhasil diubah menjadi ' . $request->status_pembayaran . '.');
    }

    // Download / Export Purchase Order (PO) dalam format PDF sesuai template
    public function downloadPoPdf(Project $project)
    {
        $project->load(['customer', 'endUser', 'pic', 'po', 'staff', 'equipments']);

        $nilaiPekerjaan = (float) ($project->po->nilai_pekerjaan ?? 0);
        $discount = 0.00;
        $totalBeforeVat = $nilaiPekerjaan - $discount;
        $vat = $totalBeforeVat * 0.11;
        $grandTotal = $totalBeforeVat + $vat;

        $amountInWords = $this->numberToWords((int) round($grandTotal)) . ' Rupiah.';

        $pdf = Pdf::loadView('projects.po_pdf', compact(
            'project',
            'nilaiPekerjaan',
            'discount',
            'totalBeforeVat',
            'vat',
            'grandTotal',
            'amountInWords'
        ))->setPaper('a4', 'portrait');

        $safeNoPo = str_replace(['/', '\\', ' '], '_', $project->po->no_po ?? 'PO-' . $project->id);
        return $pdf->download('Purchase_Order_' . $safeNoPo . '.pdf');
    }

    // Konversi angka ke kalimat bahasa Inggris (sesuai template Sulzer PDF)
    private function numberToWords($number)
    {
        $number = (int) $number;
        if ($number === 0) {
            return 'Zero';
        }

        $units = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'
        ];
        $tens = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        ];

        if ($number < 20) {
            return $units[$number];
        }

        if ($number < 100) {
            return $tens[(int)($number / 10)] . ($number % 10 ? ' ' . $units[$number % 10] : '');
        }

        if ($number < 1000) {
            return $units[(int)($number / 100)] . ' Hundred' . ($number % 100 ? ' ' . $this->numberToWords($number % 100) : '');
        }

        if ($number < 1000000) {
            return $this->numberToWords((int)($number / 1000)) . ' Thousand' . ($number % 1000 ? ' ' . $this->numberToWords($number % 1000) : '');
        }

        if ($number < 1000000000) {
            return $this->numberToWords((int)($number / 1000000)) . ' Million' . ($number % 1000000 ? ' ' . $this->numberToWords($number % 1000000) : '');
        }

        if ($number < 1000000000000) {
            return $this->numberToWords((int)($number / 1000000000)) . ' Billion' . ($number % 1000000000 ? ' ' . $this->numberToWords($number % 1000000000) : '');
        }

        return (string) $number;
    }
}
