@extends('layouts.app')

@section('title', 'Detail SPK ' . $spk->no_spk)
@section('page-title', 'Detail & Verifikasi SPK')

@section('breadcrumb-actions')
    <div class="d-inline-flex gap-2">
        @if(Auth::user()->hasRole('Supervisor', 'Logistics', 'Finance', 'Director'))
        <a href="{{ route('spks.index') }}" class="btn-td-dark">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar SPK</span>
        </a>
        @endif
        <a href="{{ route('projects.show', $spk->project_id) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <i class="bi bi-folder2-open"></i>
            <span>Lihat Proyek</span>
        </a>
    </div>
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

        <!-- Technical SPK Banner -->
        <div class="white-box mb-4 p-3 d-flex flex-wrap align-items-center justify-content-between" style="background: #111111 !important; border-color: #1F1F1F !important;">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="font-mono-tech" style="font-size:11px; color:#888888; letter-spacing:0.08em;">SPK-NUMBER</span>
                <span class="font-mono-tech fw-bold" style="color: #FF5500; font-size:16px;">
                    {{ $spk->no_spk }}
                </span>
                @include('spks._status_badge', ['status' => $spk->status_verifikasi])
            </div>
            <div class="mt-2 mt-md-0 font-mono-tech" style="font-size:11.5px; color:#888888;">
                <span>TANGGAL SPK: <strong style="color:#FFFFFF;">{{ $spk->tgl_spk ? $spk->tgl_spk->format('d/m/Y') : '-' }}</strong></span>
            </div>
        </div>

        @if($spk->status_verifikasi === 'Dibatalkan')
        <div class="alert alert-danger border-0 border-start border-4 border-danger mb-4" style="background:#FEF2F2; color:#991B1B;">
            <div class="fw-bold mb-1"><i class="bi bi-x-octagon-fill me-2"></i>SPK INI TELAH DIBATALKAN / DITOLAK</div>
            <div class="small"><strong>Alasan Penolakan:</strong> {{ $spk->alasan_batal ?: '-' }}</div>
        </div>
        @endif

        <div class="row g-4">

            <!-- KOLOM KIRI: Stepper Audit & Data Proyek -->
            <div class="col-xl-8">

                <!-- SECTION A: STEPPER APPROVAL BERJENJANG & JEJAK AUDIT -->
                <div class="product-status-wrap mb-4">
                    <div class="td-section-label">[ A // ALUR PERSETUJUAN BERJENJANG &amp; JEJAK AUDIT ]</div>
                    <h4>Status Tahapan Verifikasi SPK</h4>

                    @php
                        $isCancelled = $spk->status_verifikasi === 'Dibatalkan';
                        $stepLogisticsDone = $spk->logistics_at !== null && in_array($spk->status_verifikasi, ['Verifikasi Logistik', 'Verifikasi Keuangan', 'Persetujuan Direktur', 'Terbit'], true);
                        $stepFinanceDone   = $spk->finance_at !== null && in_array($spk->status_verifikasi, ['Verifikasi Keuangan', 'Persetujuan Direktur', 'Terbit'], true);
                        $stepDirectorDone  = $spk->director_at !== null && $spk->status_verifikasi === 'Terbit';
                    @endphp

                    <div class="row g-3 mt-2">
                        <!-- Step 1: Draft -->
                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100 {{ !$isCancelled ? 'border-success bg-light' : 'border-secondary bg-light' }}">
                                <div class="font-mono-tech text-muted mb-1" style="font-size:10.5px;">TAHAP 01</div>
                                <div class="fw-bold text-dark mb-1">Draft Diajukan</div>
                                <span class="badge bg-success mb-2">Selesai</span>
                                <div class="small text-muted">
                                    Dibuat: {{ $spk->created_at ? $spk->created_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Logistics -->
                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100 {{ $stepLogisticsDone ? 'border-success bg-light' : ($spk->status_verifikasi === 'Draft' ? 'border-warning' : 'border-secondary') }}">
                                <div class="font-mono-tech text-muted mb-1" style="font-size:10.5px;">TAHAP 02</div>
                                <div class="fw-bold text-dark mb-1">Verifikasi Logistik</div>
                                @if($stepLogisticsDone)
                                    <span class="badge bg-success mb-2">Terverifikasi</span>
                                @elseif($spk->status_verifikasi === 'Draft')
                                    <span class="badge bg-warning text-dark mb-2">Menunggu Aksi</span>
                                @elseif($isCancelled && $spk->logistics_at)
                                    <span class="badge bg-danger mb-2">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary mb-2">Menunggu</span>
                                @endif
                                <div class="small text-muted">
                                    Oleh: <strong>{{ $spk->logisticsVerifier->name ?? '-' }}</strong><br>
                                    Waktu: {{ $spk->logistics_at ? $spk->logistics_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Finance -->
                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100 {{ $stepFinanceDone ? 'border-success bg-light' : ($spk->status_verifikasi === 'Verifikasi Logistik' ? 'border-warning' : 'border-secondary') }}">
                                <div class="font-mono-tech text-muted mb-1" style="font-size:10.5px;">TAHAP 03</div>
                                <div class="fw-bold text-dark mb-1">Verifikasi Keuangan</div>
                                @if($stepFinanceDone)
                                    <span class="badge bg-success mb-2">Terverifikasi</span>
                                @elseif($spk->status_verifikasi === 'Verifikasi Logistik')
                                    <span class="badge bg-warning text-dark mb-2">Menunggu Aksi</span>
                                @elseif($isCancelled && $spk->finance_at)
                                    <span class="badge bg-danger mb-2">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary mb-2">Menunggu</span>
                                @endif
                                <div class="small text-muted">
                                    Oleh: <strong>{{ $spk->financeVerifier->name ?? '-' }}</strong><br>
                                    Waktu: {{ $spk->finance_at ? $spk->finance_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Director -->
                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100 {{ $stepDirectorDone ? 'border-success bg-light' : (in_array($spk->status_verifikasi, ['Verifikasi Keuangan', 'Persetujuan Direktur'], true) ? 'border-warning' : 'border-secondary') }}">
                                <div class="font-mono-tech text-muted mb-1" style="font-size:10.5px;">TAHAP 04</div>
                                <div class="fw-bold text-dark mb-1">Persetujuan Direktur</div>
                                @if($stepDirectorDone)
                                    <span class="badge bg-success mb-2">Terbit</span>
                                @elseif(in_array($spk->status_verifikasi, ['Verifikasi Keuangan', 'Persetujuan Direktur'], true))
                                    <span class="badge bg-warning text-dark mb-2">Menunggu Aksi</span>
                                @elseif($isCancelled && $spk->director_at)
                                    <span class="badge bg-danger mb-2">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary mb-2">Menunggu</span>
                                @endif
                                <div class="small text-muted">
                                    Oleh: <strong>{{ $spk->directorApprover->name ?? '-' }}</strong><br>
                                    Waktu: {{ $spk->director_at ? $spk->director_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION B: RINGKASAN PROYEK & ANGGARAN -->
                <div class="product-status-wrap mb-4">
                    <div class="td-section-label">[ B // INFORMASI PROYEK &amp; PO TERKAIT ]</div>
                    <h4>Data Proyek Pelaksanaan</h4>
                    <div class="table-responsive">
                        <table class="table-td-nalika">
                            <tbody>
                                <tr>
                                    <th style="width:32%; background:#F8F9FA !important; color:#4B5563 !important;">Nama Proyek</th>
                                    <td class="fw-bold text-dark">{{ $spk->project->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th style="background:#F8F9FA !important; color:#4B5563 !important;">Customer / End User</th>
                                    <td>
                                        <strong>{{ $spk->project->customer->name ?? '-' }}</strong>
                                        <br><small class="text-muted">Lokasi: {{ $spk->project->endUser->name ?? '-' }} ({{ $spk->project->endUser->location ?? '-' }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background:#F8F9FA !important; color:#4B5563 !important;">No. PO &amp; Nilai Pekerjaan</th>
                                    <td>
                                        <span class="font-mono-tech fw-bold" style="color:#FF5500;">{{ $spk->project->po->no_po ?? '-' }}</span>
                                        <span class="ms-2 fw-bold text-success font-mono-tech">
                                            Rp {{ number_format($spk->project->po->nilai_pekerjaan ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background:#F8F9FA !important; color:#4B5563 !important;">Jadwal Pelaksanaan</th>
                                    <td class="font-mono-tech">
                                        {{ $spk->project->tgl_mulai ? \Carbon\Carbon::parse($spk->project->tgl_mulai)->format('d/m/Y') : '-' }}
                                        s/d
                                        {{ $spk->project->tgl_selesai ? \Carbon\Carbon::parse($spk->project->tgl_selesai)->format('d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background:#F8F9FA !important; color:#4B5563 !important;">PIC Techno Drive</th>
                                    <td>{{ $spk->project->pic->name ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION C: ALAT UJI & TEKNISI (BAHAN VERIFIKASI LOGISTIK) -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="product-status-wrap h-100 mb-0">
                            <div class="td-section-label">[ C // ALAT UJI PROYEK ]</div>
                            <h4>Daftar Alat Uji</h4>
                            <ul class="list-group list-group-flush mt-2">
                                @forelse($spk->project->equipments ?? [] as $eq)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark">{{ $eq->name }}</strong>
                                        <span class="badge bg-light text-dark border font-mono-tech" style="font-size:10px;">SN: {{ $eq->serial_number }}</span>
                                    </div>
                                    <small class="text-muted">
                                        Status: <span class="badge bg-secondary" style="font-size:10px;">{{ $eq->status }}</span>
                                        @if($eq->calibration_expiry)
                                            | Kalibrasi s/d: {{ \Carbon\Carbon::parse($eq->calibration_expiry)->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </li>
                                @empty
                                <li class="list-group-item px-0 text-muted small">Tidak ada alat uji terlampir.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="product-status-wrap h-100 mb-0">
                            <div class="td-section-label">[ D // TEKNISI LAPANGAN ]</div>
                            <h4>Staff Ditugaskan</h4>
                            <ul class="list-group list-group-flush mt-2">
                                @forelse($spk->project->staff ?? [] as $stf)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-dark">{{ $stf->name }}</strong>
                                        <br><small class="text-muted">{{ $stf->email }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark border font-mono-tech" style="font-size:10px;">{{ $stf->role }}</span>
                                </li>
                                @empty
                                <li class="list-group-item px-0 text-muted small">Tidak ada staf ditugaskan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: PANEL AKSI APPROVAL / REJECT -->
            <div class="col-xl-4">
                <div class="product-status-wrap mb-4" style="border-top: 3px solid #FF5500 !important;">
                    <div class="td-section-label">[ PANEL VERIFIKASI SPK ]</div>
                    <h4>Tindakan Otorisasi</h4>

                    <div class="mb-3">
                        <label class="font-mono-tech text-muted d-block mb-1" style="font-size:11px;">STATUS SAAT INI</label>
                        @include('spks._status_badge', ['status' => $spk->status_verifikasi])
                    </div>

                    @can('approve', $spk)
                        <div class="alert alert-warning py-2 px-3 small mb-3 border-0 border-start border-3 border-warning" style="background:#FFFBEB; color:#92400E;">
                            <i class="bi bi-shield-exclamation me-1"></i>
                            SPK ini sedang menunggu verifikasi dari peran Anda (<strong>{{ Auth::user()->role }}</strong>). Pastikan data alat uji, anggaran, dan jadwal sudah sesuai sebelum menyetujui.
                        </div>

                        <form action="{{ route('spks.approve', $spk) }}" method="POST" class="mb-2"
                              onsubmit="return confirm('Konfirmasi persetujuan SPK {{ $spk->no_spk }}?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2" style="background:#10B981 !important; border:none;">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>
                                    @if(Auth::user()->hasRole('Director'))
                                        Setujui &amp; Terbitkan SPK
                                    @else
                                        Approve &amp; Lanjutkan Tahap
                                    @endif
                                </span>
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#rejectSpkDetailModal">
                            <i class="bi bi-x-circle"></i>
                            <span>Reject / Batalkan SPK</span>
                        </button>
                    @else
                        @if($spk->status_verifikasi === 'Terbit')
                            <div class="alert alert-success py-2 px-3 small mb-0 border-0 border-start border-3 border-success" style="background:#F0FDF4; color:#166534;">
                                <i class="bi bi-patch-check-fill me-1"></i>
                                SPK telah disetujui penuh oleh Logistik, Keuangan, dan Direktur, serta resmi berstatus <strong>Terbit</strong>.
                            </div>
                        @elseif($spk->status_verifikasi === 'Dibatalkan')
                            <div class="alert alert-danger py-2 px-3 small mb-0 border-0 border-start border-3 border-danger" style="background:#FEF2F2; color:#991B1B;">
                                <i class="bi bi-x-octagon me-1"></i>
                                SPK ini sudah dibatalkan dan tidak dapat diproses lebih lanjut.
                            </div>
                        @else
                            <div class="alert alert-info py-2 px-3 small mb-0 border-0 border-start border-3 border-info" style="background:#EFF6FF; color:#1E40AF;">
                                <i class="bi bi-hourglass-split me-1"></i>
                                Saat ini bukan giliran verifikasi untuk peran Anda (<strong>{{ Auth::user()->role }}</strong>).
                            </div>
                        @endif
                    @endcan
                </div>
            </div>

        </div>

    </div>
</div>

@can('reject', $spk)
<!-- Modal Reject SPK -->
<div class="modal fade" id="rejectSpkDetailModal" tabindex="-1" aria-labelledby="rejectSpkDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('spks.reject', $spk) }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            @method('PATCH')
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="rejectSpkDetailModalLabel" style="font-size:15px;">
                    <i class="bi bi-x-octagon text-danger me-2"></i>Tolak / Batalkan SPK
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-2">
                    Anda akan menolak SPK <strong class="text-dark font-mono-tech">{{ $spk->no_spk }}</strong>. Status akan menjadi <strong>Dibatalkan</strong>.
                </p>
                <div class="mb-0">
                    <label for="alasan_batal_detail" class="form-label fw-semibold small text-uppercase text-muted">
                        Alasan Penolakan <span class="text-danger">*</span>
                    </label>
                    <textarea name="alasan_batal" id="alasan_batal_detail" rows="3" class="form-control"
                              placeholder="Tuliskan alasan penolakan SPK..." required minlength="5" maxlength="1000">{{ old('alasan_batal') }}</textarea>
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
@endcan

@endsection
