@extends('layouts.app')

@section('title', 'Verifikasi SPK')
@section('page-title', 'Verifikasi Surat Perintah Kerja (SPK)')

@section('breadcrumb-actions')
    @can('create', \App\Models\Spk::class)
    <a href="{{ route('spks.create') }}" class="btn-td-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Ajukan Draft SPK</span>
    </a>
    @endcan
@endsection

@section('content')

<div class="product-status mg-b-30">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 border-start border-4 border-success mb-3" role="alert" style="border-radius:4px; background:#F0FDF4; color:#166534;">
            <i class="bi bi-check-circle-fill me-2" style="color:#10B981;"></i><strong>[ BERHASIL ]</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 border-start border-4 border-danger mb-3" role="alert" style="border-radius:4px; background:#FEF2F2; color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill me-2" style="color:#EF4444;"></i><strong>[ PERHATIAN ]</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 border-start border-4 border-danger mb-3" role="alert" style="border-radius:4px; background:#FEF2F2; color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill me-2" style="color:#EF4444;"></i><strong>[ VALIDASI GAGAL ]</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="product-status-wrap">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <div class="td-section-label">[ WORKFLOW APPROVAL BERJENJANG // LOGISTIK → KEUANGAN → DIREKTUR ]</div>
                            <h4 class="mb-1">Daftar &amp; Antrean Verifikasi SPK</h4>
                            <small class="text-muted">
                                Alur pengesahan: <strong>Draft</strong> → <strong>Verifikasi Logistik</strong> → <strong>Verifikasi Keuangan</strong> → <strong>Terbit</strong>
                            </small>
                        </div>

                        @if($isVerifier)
                        <div class="btn-group" role="group" aria-label="Filter antrean SPK">
                            <a href="{{ route('spks.index', ['scope' => 'queue']) }}"
                               class="btn btn-sm {{ $scope === 'queue' ? 'btn-dark fw-bold' : 'btn-outline-secondary' }}">
                                <i class="bi bi-inbox me-1"></i>Perlu Verifikasi Saya
                                <span class="badge ms-1" style="background:#FF5500; color:#FFFFFF;">{{ $queueCount }}</span>
                            </a>
                            <a href="{{ route('spks.index', ['scope' => 'all']) }}"
                               class="btn btn-sm {{ $scope === 'all' ? 'btn-dark fw-bold' : 'btn-outline-secondary' }}">
                                <i class="bi bi-list-ul me-1"></i>Semua SPK ({{ $statusCounts['all'] }})
                            </a>
                        </div>
                        @endif
                    </div>

                    @if($scope === 'all')
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @php
                            $activeStatus = request('status', 'all');
                            $statusFilters = [
                                'all'                 => 'Semua (' . $statusCounts['all'] . ')',
                                'Draft'               => 'Draft (' . $statusCounts['Draft'] . ')',
                                'Verifikasi Logistik' => 'Verifikasi Logistik (' . $statusCounts['Verifikasi Logistik'] . ')',
                                'Verifikasi Keuangan' => 'Verifikasi Keuangan (' . $statusCounts['Verifikasi Keuangan'] . ')',
                                'Terbit'              => 'Terbit (' . $statusCounts['Terbit'] . ')',
                                'Dibatalkan'          => 'Dibatalkan (' . $statusCounts['Dibatalkan'] . ')',
                            ];
                        @endphp
                        @foreach($statusFilters as $stKey => $stLabel)
                            <a href="{{ route('spks.index', ['scope' => 'all', 'status' => $stKey]) }}"
                               class="btn btn-sm font-mono-tech {{ $activeStatus === $stKey ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}"
                               style="font-size:11px;">
                                {{ $stLabel }}
                            </a>
                        @endforeach
                    </div>
                    @endif

                    <div class="table-responsive mt-2">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">NO</th>
                                    <th>NO. SPK &amp; TANGGAL</th>
                                    <th>PROYEK &amp; CUSTOMER</th>
                                    <th>NILAI PO</th>
                                    <th>STATUS VERIFIKASI</th>
                                    <th>JEJAK AUDIT</th>
                                    <th class="text-end pe-3" style="min-width: 240px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spks as $index => $spk)
                                <tr>
                                    <td class="ps-3 font-mono-tech" style="font-size:12px; color:#6B7280;">
                                        {{ $spks->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <span class="font-mono-tech fw-bold d-block" style="color: #FF5500; font-size:12.5px;">
                                            {{ $spk->no_spk }}
                                        </span>
                                        <small class="text-muted font-mono-tech">
                                            {{ $spk->tgl_spk ? $spk->tgl_spk->format('d/m/Y') : '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.show', $spk->project_id) }}" class="fw-bold text-dark d-block text-decoration-none" style="font-size:13px;">
                                            {{ $spk->project->name ?? '-' }}
                                        </a>
                                        <small class="text-muted">
                                            {{ $spk->project->customer->name ?? '-' }}
                                            @if($spk->project && $spk->project->endUser)
                                                &bull; {{ $spk->project->endUser->name }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech fw-bold text-dark" style="font-size:12.5px;">
                                            Rp {{ number_format($spk->project->po->nilai_pekerjaan ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @include('spks._status_badge', ['status' => $spk->status_verifikasi])
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1 font-mono-tech" style="font-size:10.5px;">
                                            <span class="{{ $spk->logistics_at ? 'text-success' : 'text-muted' }}">
                                                <i class="bi {{ $spk->logistics_at ? 'bi-check-circle-fill' : 'bi-circle' }} me-1"></i>
                                                Logistik: {{ $spk->logisticsVerifier->name ?? '-' }}
                                            </span>
                                            <span class="{{ $spk->finance_at ? 'text-success' : 'text-muted' }}">
                                                <i class="bi {{ $spk->finance_at ? 'bi-check-circle-fill' : 'bi-circle' }} me-1"></i>
                                                Keuangan: {{ $spk->financeVerifier->name ?? '-' }}
                                            </span>
                                            <span class="{{ $spk->director_at ? 'text-success' : 'text-muted' }}">
                                                <i class="bi {{ $spk->director_at ? 'bi-check-circle-fill' : 'bi-circle' }} me-1"></i>
                                                Direktur: {{ $spk->directorApprover->name ?? '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                            @can('approve', $spk)
                                            <form action="{{ route('spks.approve', $spk) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Setujui verifikasi SPK {{ $spk->no_spk }}? Aksi ini tercatat dalam jejak audit.')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success fw-semibold" style="font-size:11px;" title="Setujui / Approve SPK">
                                                    <i class="bi bi-check-lg me-1"></i>Approve
                                                </button>
                                            </form>
                                            @endcan

                                            @can('reject', $spk)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger fw-semibold btn-open-reject-modal"
                                                    style="font-size:11px;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectSpkModal"
                                                    data-action="{{ route('spks.reject', $spk) }}"
                                                    data-nospk="{{ $spk->no_spk }}">
                                                <i class="bi bi-x-lg me-1"></i>Reject
                                            </button>
                                            @endcan

                                            <a href="{{ route('spks.show', $spk) }}" class="pd-setting-ed" title="Lihat Dossier &amp; Jejak Audit SPK">
                                                <span>Detail</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        @if($isVerifier && $scope === 'queue')
                                            [ TIDAK ADA SPK YANG MENUNGGU VERIFIKASI PERAN ANDA SAAT INI ]
                                        @else
                                            [ BELUM ADA DATA SPK TERDAFTAR ]
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($spks->hasPages())
                    <div class="custom-pagination mt-4 d-flex justify-content-end">
                        {{ $spks->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Tolak / Batalkan SPK (Reusable) -->
<div class="modal fade" id="rejectSpkModal" tabindex="-1" aria-labelledby="rejectSpkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="rejectSpkForm" class="modal-content border-0 shadow">
            @csrf
            @method('PATCH')
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="rejectSpkModalLabel" style="font-size:15px;">
                    <i class="bi bi-x-octagon text-danger me-2"></i>Tolak / Batalkan SPK
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-2">
                    Anda akan menolak dan membatalkan SPK <strong id="rejectSpkNumber" class="text-dark font-mono-tech">-</strong>. Status SPK akan berubah menjadi <strong>Dibatalkan</strong>.
                </p>
                <div class="mb-0">
                    <label for="alasan_batal" class="form-label fw-semibold small text-uppercase text-muted">
                        Alasan Penolakan <span class="text-danger">*</span>
                    </label>
                    <textarea name="alasan_batal" id="alasan_batal" rows="3" class="form-control"
                              placeholder="Tuliskan alasan penolakan (misal: alat uji sedang rusak / anggaran perlu revisi)..."
                              required minlength="5" maxlength="1000"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-danger fw-bold">
                    <i class="bi bi-x-circle me-1"></i>Konfirmasi Tolak SPK
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-open-reject-modal', function() {
        const actionUrl = $(this).data('action');
        const noSpk = $(this).data('nospk');

        $('#rejectSpkForm').attr('action', actionUrl);
        $('#rejectSpkNumber').text(noSpk);
        $('#alasan_batal').val('');
    });
});
</script>
@endpush
