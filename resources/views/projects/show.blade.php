@extends('layouts.app')

@section('title', 'Detail Proyek')
@section('page-title', 'Detail Proyek')

@section('breadcrumb-actions')
    <a href="{{ route('projects.index') }}" class="btn-td-dark">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Daftar Proyek</span>
    </a>
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

        <!-- Technical Project ID Banner (Black Surface with Orange Accent) -->
        <div class="white-box mb-4 p-3 d-flex flex-wrap align-items-center justify-content-between" style="background: #111111 !important; border-color: #1F1F1F !important;">
            <div class="d-flex align-items-center gap-3">
                <span class="font-mono-tech" style="font-size:11px; color:#888888; letter-spacing:0.08em;">DOSSIER-ID</span>
                <span class="font-mono-tech fw-bold" style="color: #FF5500; font-size:16px;">
                    {{ $project->po->no_po ?? ('PRJ-' . str_pad($project->id, 4, '0', STR_PAD_LEFT)) }}
                </span>
                <div class="d-inline-flex gap-2">
                    @if($project->status_pekerjaan == 'Completed')
                        <span class="pd-setting">COMPLETED</span>
                    @else
                        <span class="badge-status-active">ON PROGRESS</span>
                    @endif

                    @if($project->status_pembayaran == 'Paid')
                        <span class="pd-setting">PAID</span>
                    @else
                        <span class="ps-setting">WAITING PAYMENT</span>
                    @endif
                </div>
            </div>
            <div class="mt-2 mt-md-0 font-mono-tech" style="font-size:11.5px; color:#888888;">
                <span>DIDAFTARKAN: <strong style="color:#FFFFFF;">{{ $project->created_at ? $project->created_at->format('d/m/Y H:i') : '-' }}</strong></span>
            </div>
        </div>

        <div class="row g-4">

            <!-- ==============================================================
                 KOLOM KIRI (8 Kolom): Data Registrasi Lengkap
                 ============================================================== -->
            <div class="col-xl-8">

                <!-- SECTION A: INFORMASI PEKERJAAN -->
                <div class="product-status-wrap mb-4">
                    <div class="td-section-label">[ A // INFORMASI PEKERJAAN ]</div>
                    <h4>Spesifikasi &amp; Entitas Pekerjaan</h4>
                    <div class="table-responsive">
                        <table class="table-td-nalika">
                            <tbody>
                                <tr>
                                    <th style="width: 32%; background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Nama / Judul Pekerjaan
                                    </th>
                                    <td class="fw-bold text-dark" style="font-size:14px;">{{ $project->name }}</td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Customer (Perusahaan Pemesan)
                                    </th>
                                    <td>
                                        <strong class="text-dark">{{ $project->customer->name ?? '-' }}</strong>
                                        @if($project->customer && $project->customer->contact_person)
                                            <br><small class="text-muted"><i class="bi bi-person me-1"></i>Kontak: {{ $project->customer->contact_person }}</small>
                                        @endif
                                        @if($project->customer && $project->customer->address)
                                            <br><small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $project->customer->address }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        End User
                                    </th>
                                    <td><strong class="text-dark">{{ $project->endUser->name ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Lokasi Pekerjaan
                                    </th>
                                    <td>
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $project->endUser->location ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        PIC End User &amp; No. HP
                                    </th>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $project->endUser->pic_name ?? '-' }}</span>
                                        @if($project->endUser && $project->endUser->pic_phone)
                                            <span class="text-muted ms-2">(<i class="bi bi-telephone me-1"></i>{{ $project->endUser->pic_phone }})</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        PIC Techno Drive
                                    </th>
                                    <td>
                                        <strong class="text-dark">{{ $project->pic->name ?? '-' }}</strong>
                                        @if($project->pic)
                                            <span class="badge bg-secondary ms-1">{{ $project->pic->role }}</span>
                                            <br><small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $project->pic->email }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important;">
                                        Deskripsi / Lingkup
                                    </th>
                                    <td>{{ $project->description ?: 'Tidak ada catatan deskripsi tambahan.' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION B: DATA PO -->
                <div class="product-status-wrap mb-4">
                    <div class="td-section-label">[ B // DATA PURCHASE ORDER ]</div>
                    <h4>Kontrak &amp; Purchase Order (PO)</h4>
                    <div class="table-responsive">
                        <table class="table-td-nalika">
                            <tbody>
                                <tr>
                                    <th style="width: 32%; background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        No. Kontrak / PO
                                    </th>
                                    <td>
                                        <span class="font-mono-tech fw-bold px-3 py-1 d-inline-block" style="background: #111111; color: #FF5500; font-size:13px; border-radius:3px;">
                                            {{ $project->po->no_po ?? '-' }}
                                        </span>
                                        <small class="text-success ms-2 font-mono-tech"><i class="bi bi-check-circle-fill me-1"></i>VERIFIED PO</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Tanggal Kontrak / PO
                                    </th>
                                    <td class="font-mono-tech">
                                        <i class="bi bi-calendar-event me-1 text-muted"></i>
                                        {{ $project->po->tgl_po ? \Carbon\Carbon::parse($project->po->tgl_po)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important;">
                                        Nilai Pekerjaan
                                    </th>
                                    <td>
                                        <span class="fw-bold font-mono-tech" style="color: #10B981; font-size:16px;">
                                            Rp {{ number_format($project->po->nilai_pekerjaan ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION C: JADWAL & DEADLINE -->
                <div class="product-status-wrap mb-4">
                    <div class="td-section-label">[ C // JADWAL &amp; CATATAN PEKERJAAN ]</div>
                    <h4>Timeline &amp; Tenggat Waktu Pelaporan</h4>
                    <div class="table-responsive">
                        <table class="table-td-nalika">
                            <tbody>
                                <tr>
                                    <th style="width: 32%; background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Waktu Mulai Pekerjaan
                                    </th>
                                    <td class="font-mono-tech">
                                        <i class="bi bi-calendar-check text-primary me-1"></i>
                                        {{ $project->tgl_mulai ? \Carbon\Carbon::parse($project->tgl_mulai)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Waktu Selesai Pekerjaan
                                    </th>
                                    <td class="font-mono-tech">
                                        <i class="bi bi-calendar-x text-warning me-1"></i>
                                        {{ $project->tgl_selesai ? \Carbon\Carbon::parse($project->tgl_selesai)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Durasi Pelaksanaan
                                    </th>
                                    <td>
                                        @if($project->tgl_mulai && $project->tgl_selesai)
                                            @php
                                                $d1 = \Carbon\Carbon::parse($project->tgl_mulai);
                                                $d2 = \Carbon\Carbon::parse($project->tgl_selesai);
                                                $diff = $d1->diffInDays($d2);
                                            @endphp
                                            <span class="badge bg-light text-dark border font-mono-tech">
                                                <i class="bi bi-stopwatch me-1"></i>{{ $diff }} Hari Kalender
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Batas Jatuh Tempo Kontrak
                                    </th>
                                    <td class="font-mono-tech">
                                        {{ $project->tgl_jatuh_tempo_kontrak ? \Carbon\Carbon::parse($project->tgl_jatuh_tempo_kontrak)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #EF4444 !important; font-weight:700 !important; border-bottom: 1px solid #E5E7EB !important;">
                                        Deadline Final Report
                                    </th>
                                    <td>
                                        <span class="fw-bold text-danger font-mono-tech">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            {{ $project->deadline_laporan ? \Carbon\Carbon::parse($project->deadline_laporan)->translatedFormat('d F Y') : '-' }}
                                        </span>
                                        @php
                                            $deadline = \Carbon\Carbon::parse($project->deadline_laporan);
                                            $today = \Carbon\Carbon::today();
                                            $sisaHari = $today->diffInDays($deadline, false);
                                        @endphp
                                        @if($project->status_pekerjaan != 'Completed' && $project->deadline_laporan)
                                            @if($sisaHari > 0)
                                                <span class="ps-setting ms-2">Tersisa {{ $sisaHari }} Hari</span>
                                            @elseif($sisaHari == 0)
                                                <span class="ds-setting ms-2">JATUH TEMPO HARI INI</span>
                                            @else
                                                <span class="ds-setting ms-2">TERLAMBAT {{ abs($sisaHari) }} HARI</span>
                                            @endif
                                        @elseif($project->status_pekerjaan == 'Completed')
                                            <span class="pd-setting ms-2">SELESAI</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: #F8F9FA !important; color: #4B5563 !important;">
                                        Catatan Progres
                                    </th>
                                    <td>{{ $project->progress_notes ?: 'Belum ada catatan progres pekerjaan tambahan.' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION D & E: PELAKSANA & ALAT UJI -->
                <div class="row g-4 mb-4">
                    <!-- D. Pelaksana Pekerjaan (Staff) -->
                    <div class="col-md-6">
                        <div class="product-status-wrap h-100 mb-0">
                            <div class="td-section-label">[ D // PELAKSANA ]</div>
                            <h4>Staff Ditugaskan</h4>
                            <ul class="list-group list-group-flush mt-2">
                                @forelse($project->staff as $stf)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center" style="border-color:#F1F1F0;">
                                    <div>
                                        <i class="bi bi-person-circle text-primary me-2"></i>
                                        <strong class="text-dark">{{ $stf->name }}</strong>
                                        <br><small class="text-muted ms-4">{{ $stf->email }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark border font-mono-tech" style="font-size:10px;">{{ $stf->role }}</span>
                                </li>
                                @empty
                                <li class="list-group-item px-0 text-muted font-mono-tech" style="font-size:12px;">Tidak ada staf yang ditugaskan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- E. Alat Uji -->
                    <div class="col-md-6">
                        <div class="product-status-wrap h-100 mb-0">
                            <div class="td-section-label">[ E // ALAT UJI ]</div>
                            <h4>Peralatan Digunakan</h4>
                            <ul class="list-group list-group-flush mt-2">
                                @forelse($project->equipments as $eq)
                                <li class="list-group-item px-0" style="border-color:#F1F1F0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-tools text-secondary me-2"></i>{{ $eq->name }}</strong>
                                        <span class="badge bg-light text-dark border font-mono-tech" style="font-size:10px;">SN: {{ $eq->serial_number }}</span>
                                    </div>
                                    <small class="text-muted ms-4 d-block">
                                        Status: <span class="badge bg-secondary" style="font-size:10px;">{{ $eq->status }}</span>
                                        @if($eq->calibration_expiry)
                                            | Kalibrasi s/d: {{ \Carbon\Carbon::parse($eq->calibration_expiry)->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </li>
                                @empty
                                <li class="list-group-item px-0 text-muted font-mono-tech" style="font-size:12px;">Tidak ada alat uji yang dipinjam.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div><!-- /col-xl-8 -->

            <!-- ==============================================================
                 KOLOM KANAN (4 Kolom): Unduhan PO, Kontrol Status & Dokumen
                 ============================================================== -->
            <div class="col-xl-4">

                <!-- KARTU 1: UNDUH PURCHASE ORDER (PO) -->
                <div class="product-status-wrap mb-4" style="border-top: 3px solid #EF4444 !important;">
                    <div class="td-section-label" style="color:#EF4444 !important;">[ DOKUMEN RESMI ]</div>
                    <h4>Purchase Order (PO) PDF</h4>
                    <p class="text-muted mb-3" style="font-size:12.5px;">
                        Dokumen Purchase Order resmi yang digenerate otomatis berdasarkan data registrasi proyek ini.
                    </p>
                    <a href="{{ route('projects.download-po', $project->id) }}" class="btn btn-danger w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2 mb-2" style="background:#EF4444 !important; border-radius:4px;">
                        <i class="bi bi-file-earmark-pdf fs-5"></i>
                        <span>Download PO (PDF)</span>
                    </a>
                    <div class="text-center">
                        <small class="text-muted font-mono-tech" style="font-size:10.5px;">
                            <i class="bi bi-shield-check me-1"></i>Format Dokumen Resmi Standar Sulzer
                        </small>
                    </div>
                </div>

                <!-- KARTU 2: STATUS & KONTROL PROYEK -->
                <div class="product-status-wrap mb-4" style="border-top: 3px solid #FF5500 !important;">
                    <div class="td-section-label">[ KONTROL OPERASIONAL ]</div>
                    <h4>Status &amp; Verifikasi</h4>

                    <div class="mb-3">
                        <label class="font-mono-tech text-muted d-block mb-1" style="font-size:11px;">STATUS PENGERJAAN</label>
                        @if($project->status_pekerjaan == 'Completed')
                            <span class="pd-setting px-3 py-2"><i class="bi bi-check2-all me-1"></i>COMPLETED</span>
                        @else
                            <span class="badge-status-active px-3 py-2"><i class="bi bi-arrow-repeat me-1"></i>ON PROGRESS</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="font-mono-tech text-muted d-block mb-1" style="font-size:11px;">STATUS PEMBAYARAN PO</label>
                        @if($project->status_pembayaran == 'Paid')
                            <span class="pd-setting px-3 py-2"><i class="bi bi-check-circle me-1"></i>PAID (LUNAS)</span>
                        @else
                            <span class="ps-setting px-3 py-2"><i class="bi bi-clock me-1"></i>WAITING PAYMENT</span>
                        @endif
                    </div>

                    <hr style="border-color: #E5E7EB;">

                    @if(Auth::user()->role == 'Finance' && $project->status_pembayaran == 'Waiting Payment')
                        <div class="alert alert-warning py-2 px-3 small mb-3 border-0 border-start border-3 border-warning" style="background:#FFFBEB; font-size:12px;">
                            <i class="bi bi-info-circle me-1"></i>
                            Proyek belum tampil di dashboard teknisi sampai pembayaran diverifikasi.
                        </div>
                        <form action="{{ route('projects.update-payment', $project->id) }}" method="POST" onsubmit="return confirm('Ubah status pembayaran menjadi Paid? Proyek akan langsung terbuka untuk Staf teknisi.')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pembayaran" value="Paid">
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background:#10B981 !important; border:none; border-radius:4px;">
                                <i class="bi bi-cash-stack"></i>
                                <span>Tandai Sudah Dibayar (Paid)</span>
                            </button>
                        </form>
                    @elseif(Auth::user()->role == 'Finance' && $project->status_pembayaran == 'Paid')
                        <div class="alert alert-info py-2 px-3 small mb-3 border-0 border-start border-3 border-info" style="background:#EFF6FF; color:#1E40AF; font-size:12px;">
                            <i class="bi bi-check-circle-fill me-1" style="color:#3B82F6;"></i>Pembayaran telah diverifikasi (Paid).
                        </div>
                        <form action="{{ route('projects.update-payment', $project->id) }}" method="POST" onsubmit="return confirm('Kembalikan status menjadi Waiting Payment?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pembayaran" value="Waiting Payment">
                            <button type="submit" class="btn btn-outline-warning w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2" style="border-radius:4px;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Batalkan Verifikasi (Waiting Payment)</span>
                            </button>
                        </form>
                    @elseif($project->status_pembayaran == 'Paid')
                        <div class="alert alert-success py-2 px-3 small mb-0 border-0 border-start border-3 border-success" style="background:#F0FDF4; color:#166534; font-size:12px;">
                            <i class="bi bi-check-circle-fill me-1" style="color:#10B981;"></i>Pembayaran telah diverifikasi (Paid).
                        </div>
                    @else
                        <div class="alert alert-warning py-2 px-3 small mb-0 border-0 border-start border-3 border-warning" style="background:#FFFBEB; font-size:12px;">
                            <i class="bi bi-clock me-1"></i>Menunggu verifikasi pembayaran dari Tim Finance.
                        </div>
                    @endif
                </div>

                <!-- KARTU 3: DOKUMEN TERKAIT (SPK & FINAL REPORT) -->
                <div class="product-status-wrap mb-4">
                    <div class="td-section-label">[ ARSIP DOKUMEN ]</div>
                    <h4>SPK &amp; Final Report</h4>

                    <div class="mb-3">
                        <label class="font-mono-tech text-muted d-block mb-1" style="font-size:11px;">SURAT PERINTAH KERJA (SPK)</label>
                        @if($project->spks && $project->spks->count() > 0)
                            @foreach($project->spks as $spk)
                                <div class="p-2 border rounded mb-2 bg-light d-flex justify-content-between align-items-center">
                                    <div class="fw-bold font-mono-tech" style="font-size:12px;">{{ $spk->no_spk }}</div>
                                    <span class="badge bg-secondary font-mono-tech" style="font-size:10px;">{{ $spk->status_verifikasi }}</span>
                                </div>
                            @endforeach
                        @else
                            <small class="text-muted font-mono-tech" style="font-size:11.5px;">Belum ada SPK diajukan.</small>
                        @endif
                    </div>

                    <div>
                        <label class="font-mono-tech text-muted d-block mb-1" style="font-size:11px;">FINAL REPORT</label>
                        @if($project->finalReports && $project->finalReports->count() > 0)
                            @foreach($project->finalReports as $fr)
                                <div class="p-2 border rounded mb-2 bg-light d-flex justify-content-between align-items-center">
                                    <div class="fw-bold font-mono-tech" style="font-size:12px;">{{ $fr->file_name }}</div>
                                    <span class="badge bg-info font-mono-tech" style="font-size:10px;">{{ $fr->status }}</span>
                                </div>
                            @endforeach
                        @else
                            <small class="text-muted font-mono-tech" style="font-size:11.5px;">Belum ada final report diunggah.</small>
                        @endif
                    </div>
                </div>

            </div><!-- /col-xl-4 -->

        </div><!-- /row -->

    </div>
</div>

@endsection
