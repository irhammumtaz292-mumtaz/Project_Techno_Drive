@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0" style="color: #1a2a3a;">
                    <i class="fa fa-folder-open-o me-2" style="color: #fd5f00;"></i>Detail Proyek & Purchase Order (PO)
                </h4>
                @if($project->status_pekerjaan == 'Completed')
                    <span class="badge bg-success">Completed</span>
                @else
                    <span class="badge bg-primary">On Progress</span>
                @endif

                @if($project->status_pembayaran == 'Paid')
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-warning text-dark">Waiting Payment</span>
                @endif
            </div>
            <p class="text-muted mb-0 small">
                No. PO: <strong class="text-primary">{{ $project->po->no_po ?? '-' }}</strong> |
                Didaftarkan pada: {{ $project->created_at ? $project->created_at->format('d/m/Y H:i') : '-' }}
            </p>
        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Alert Success / Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- =============================================
             KOLOM KIRI (8/12): Data Lengkap Sesuai Register
             ============================================= --}}
        <div class="col-xl-8">

            {{-- ---- SECTION A: INFORMASI PEKERJAAN ---- --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                    <h6 class="mb-0 fw-bold text-white">
                        <i class="fa fa-briefcase me-2" style="color: #fd5f00;"></i>A. Informasi Pekerjaan
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light text-muted" style="width: 32%;">Nama / Judul Pekerjaan</th>
                                <td class="fw-bold" style="color: #1a2a3a;">{{ $project->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Customer (Perusahaan Pemesan)</th>
                                <td>
                                    <strong>{{ $project->customer->name ?? '-' }}</strong>
                                    @if($project->customer && $project->customer->contact_person)
                                        <br><small class="text-muted"><i class="fa fa-user me-1"></i>Kontak: {{ $project->customer->contact_person }}</small>
                                    @endif
                                    @if($project->customer && $project->customer->address)
                                        <br><small class="text-muted"><i class="fa fa-map-marker me-1"></i>Alamat: {{ $project->customer->address }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">End User</th>
                                <td><strong>{{ $project->endUser->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Lokasi Pekerjaan</th>
                                <td>
                                    <i class="fa fa-map-pin text-danger me-1"></i>
                                    {{ $project->endUser->location ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">PIC End User & No. Handphone</th>
                                <td>
                                    <span class="fw-semibold">{{ $project->endUser->pic_name ?? '-' }}</span>
                                    @if($project->endUser && $project->endUser->pic_phone)
                                        <span class="text-muted ms-2">(<i class="fa fa-phone me-1"></i>{{ $project->endUser->pic_phone }})</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">PIC Techno Drive</th>
                                <td>
                                    <strong>{{ $project->pic->name ?? '-' }}</strong>
                                    @if($project->pic)
                                        <span class="badge bg-secondary ms-1">{{ $project->pic->role }}</span>
                                        <br><small class="text-muted"><i class="fa fa-envelope me-1"></i>{{ $project->pic->email }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Deskripsi / Lingkup Pekerjaan</th>
                                <td>{{ $project->description ?: 'Tidak ada deskripsi pekerjaan.' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ---- SECTION B: DATA PURCHASE ORDER (PO) ---- --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                    <h6 class="mb-0 fw-bold text-white">
                        <i class="fa fa-file-text-o me-2" style="color: #fd5f00;"></i>B. Data Kontrak / Purchase Order (PO)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light text-muted" style="width: 32%;">No. Kontrak / PO</th>
                                <td>
                                    <span class="badge px-3 py-2 fs-6" style="background: #1a2a3a; color: #fff;">
                                        {{ $project->po->no_po ?? '-' }}
                                    </span>
                                    <small class="text-success ms-2"><i class="fa fa-check-circle"></i> Terverifikasi otomatis</small>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Tanggal Kontrak / PO</th>
                                <td>
                                    <i class="fa fa-calendar me-1 text-muted"></i>
                                    {{ $project->po->tgl_po ? \Carbon\Carbon::parse($project->po->tgl_po)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Nilai Pekerjaan</th>
                                <td>
                                    <span class="fs-5 fw-bold text-success">
                                        Rp {{ number_format($project->po->nilai_pekerjaan ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ---- SECTION C: JADWAL & DEADLINE ---- --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                    <h6 class="mb-0 fw-bold text-white">
                        <i class="fa fa-calendar me-2" style="color: #fd5f00;"></i>C. Jadwal Pelaksanaan & Catatan Pekerjaan
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light text-muted" style="width: 32%;">Waktu Mulai Pekerjaan</th>
                                <td>
                                    <i class="fa fa-calendar-check-o text-primary me-1"></i>
                                    {{ $project->tgl_mulai ? \Carbon\Carbon::parse($project->tgl_mulai)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Waktu Selesai Pekerjaan</th>
                                <td>
                                    <i class="fa fa-calendar-times-o text-warning me-1"></i>
                                    {{ $project->tgl_selesai ? \Carbon\Carbon::parse($project->tgl_selesai)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Estimasi Lama Waktu</th>
                                <td>
                                    @if($project->tgl_mulai && $project->tgl_selesai)
                                        @php
                                            $d1 = \Carbon\Carbon::parse($project->tgl_mulai);
                                            $d2 = \Carbon\Carbon::parse($project->tgl_selesai);
                                            $diff = $d1->diffInDays($d2);
                                        @endphp
                                        <span class="badge bg-info text-dark">
                                            <i class="fa fa-clock-o me-1"></i>{{ $diff }} Hari Kalender
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Tanggal Batas Kontrak</th>
                                <td>
                                    {{ $project->tgl_jatuh_tempo_kontrak ? \Carbon\Carbon::parse($project->tgl_jatuh_tempo_kontrak)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted text-danger fw-bold">Deadline Final Report</th>
                                <td>
                                    <span class="fw-bold text-danger">
                                        <i class="fa fa-exclamation-triangle me-1"></i>
                                        {{ $project->deadline_laporan ? \Carbon\Carbon::parse($project->deadline_laporan)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                    @php
                                        $deadline = \Carbon\Carbon::parse($project->deadline_laporan);
                                        $today = \Carbon\Carbon::today();
                                        $sisaHari = $today->diffInDays($deadline, false);
                                    @endphp
                                    @if($project->status_pekerjaan != 'Completed')
                                        @if($sisaHari > 0)
                                            <span class="badge bg-warning text-dark ms-2">Tersisa {{ $sisaHari }} hari</span>
                                        @elseif($sisaHari == 0)
                                            <span class="badge bg-danger ms-2">HARI INI JATUH TEMPO</span>
                                        @else
                                            <span class="badge bg-danger ms-2">TERLAMBAT {{ abs($sisaHari) }} HARI</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success ms-2">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">Progress / Catatan Pekerjaan</th>
                                <td>
                                    {{ $project->progress_notes ?: 'Belum ada catatan progres tambahan.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ---- SECTION D: PELAKSANA & SECTION E: ALAT UJI ---- --}}
            <div class="row g-4 mb-4">
                {{-- D. Pelaksana Pekerjaan (Staff) --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                            <h6 class="mb-0 fw-bold text-white">
                                <i class="fa fa-users me-2" style="color: #fd5f00;"></i>D. Pelaksana Pekerjaan (Staff)
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse($project->staff as $stf)
                                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fa fa-user-circle text-primary me-2"></i>
                                            <strong>{{ $stf->name }}</strong><br>
                                            <small class="text-muted ms-4">{{ $stf->email }}</small>
                                        </div>
                                        <span class="badge bg-light text-dark border">{{ $stf->role }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item px-0 text-muted">Tidak ada staf yang ditugaskan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- E. Alat Uji Yang Digunakan --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                            <h6 class="mb-0 fw-bold text-white">
                                <i class="fa fa-wrench me-2" style="color: #fd5f00;"></i>E. Alat Uji Yang Digunakan
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse($project->equipments as $eq)
                                    <li class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong><i class="fa fa-cog text-secondary me-2"></i>{{ $eq->name }}</strong>
                                            <span class="badge bg-info text-dark">SN: {{ $eq->serial_number }}</span>
                                        </div>
                                        <small class="text-muted ms-4 d-block">
                                            Status: <span class="badge bg-secondary">{{ $eq->status }}</span>
                                            @if($eq->calibration_expiry)
                                                | Kalibrasi s/d: {{ \Carbon\Carbon::parse($eq->calibration_expiry)->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </li>
                                @empty
                                    <li class="list-group-item px-0 text-muted">Tidak ada alat uji yang dipinjam.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /col-xl-8 --}}

        {{-- =============================================
             KOLOM KANAN (4/12): Status, Unduhan & Kontrol
             ============================================= --}}
        <div class="col-xl-4">

            {{-- Kartu Download Dokumen Resmi --}}
            <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid #dc3545 !important;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color: #1a2a3a;">
                        <i class="fa fa-file-pdf-o me-2" style="color: #dc3545;"></i>Dokumen Purchase Order (PO)
                    </h6>
                    <p class="small text-muted mb-3">
                        Dokumen Purchase Order resmi yang telah digenerate otomatis berdasarkan form registrasi proyek ini.
                    </p>
                    <a href="{{ route('projects.download-po', $project->id) }}" class="btn btn-danger w-100 fw-bold py-2 shadow-sm mb-2">
                        <i class="fa fa-download me-1"></i> Download PO (PDF)
                    </a>
                    <div class="text-center">
                        <small class="text-muted"><i class="fa fa-shield me-1"></i>Format standar 2 halaman Sulzer</small>
                    </div>
                </div>
            </div>

            {{-- Kartu Status & Kontrol Pembayaran --}}
            <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid #fd5f00 !important;">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="color: #1a2a3a;">
                        <i class="fa fa-tasks me-2" style="color: #fd5f00;"></i>Status & Kontrol Proyek
                    </h6>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="text-muted small d-block">Status Pekerjaan</label>
                        @if($project->status_pekerjaan == 'Completed')
                            <span class="badge bg-success fs-6"><i class="fa fa-check me-1"></i>Completed</span>
                        @else
                            <span class="badge bg-primary fs-6"><i class="fa fa-spinner fa-spin me-1"></i>On Progress</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small d-block">Status Pembayaran</label>
                        @if($project->status_pembayaran == 'Paid')
                            <span class="badge bg-success fs-6"><i class="fa fa-check-circle me-1"></i>Paid</span>
                        @else
                            <span class="badge bg-warning text-dark fs-6"><i class="fa fa-clock-o me-1"></i>Waiting Payment</span>
                        @endif
                    </div>

                    <hr>

                    {{-- Tombol Tandai Sudah Dibayar (Hanya Supervisor) --}}
                    @if(Auth::user()->role == 'Supervisor' && $project->status_pembayaran == 'Waiting Payment')
                        <div class="alert alert-warning py-2 px-3 small mb-3">
                            <i class="fa fa-info-circle me-1"></i>
                            Proyek belum tampil di dashboard teknisi sampai pembayaran diverifikasi.
                        </div>
                        <form action="{{ route('projects.update-payment', $project->id) }}" method="POST" onsubmit="return confirm('Ubah status pembayaran menjadi Paid? Proyek akan langsung terbuka untuk Staf teknisi.')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pembayaran" value="Paid">
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                                <i class="fa fa-money me-1"></i> Tandai Sudah Dibayar (Paid)
                            </button>
                        </form>
                    @elseif($project->status_pembayaran == 'Paid')
                        <div class="alert alert-success py-2 px-3 small mb-0">
                            <i class="fa fa-check-circle me-1"></i> Pembayaran telah diverifikasi (Paid).
                        </div>
                    @endif

                </div>
            </div>

            {{-- Kartu Riwayat SPK & Final Report --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="color: #1a2a3a;">
                        <i class="fa fa-history me-2" style="color: #fd5f00;"></i>Dokumen Terkait
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Surat Perintah Kerja (SPK)</label>
                        @if($project->spks && $project->spks->count() > 0)
                            @foreach($project->spks as $spk)
                                <div class="p-2 border rounded mb-2 bg-light">
                                    <div class="fw-bold">{{ $spk->no_spk }}</div>
                                    <small class="badge bg-secondary">{{ $spk->status_verifikasi }}</small>
                                </div>
                            @endforeach
                        @else
                            <small class="text-muted">Belum ada SPK yang diajukan.</small>
                        @endif
                    </div>

                    <div>
                        <label class="text-muted small d-block">Final Report</label>
                        @if($project->finalReports && $project->finalReports->count() > 0)
                            @foreach($project->finalReports as $fr)
                                <div class="p-2 border rounded mb-2 bg-light">
                                    <div class="fw-bold">{{ $fr->file_name }}</div>
                                    <small class="badge bg-info">{{ $fr->status }}</small>
                                </div>
                            @endforeach
                        @else
                            <small class="text-muted">Belum ada final report diunggah.</small>
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- /col-xl-4 --}}

    </div>{{-- /row --}}

</div>
@endsection
