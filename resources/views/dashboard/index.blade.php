@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Stat Cards (Nalika .section-admin pattern) -->
<div class="section-admin container-fluid">
    <div class="row admin text-center">
        <div class="col-md-12">
            <div class="row">

                @if(in_array($user->role, ['Supervisor', 'Director', 'Finance', 'Logistics']))
                <!-- Card 1: Total Proyek Aktif -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    <div class="admin-content analysis-progrebar-ctn">
                        <div class="td-section-label">TOTAL PROYEK AKTIF</div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="badge bg-green">
                                <i class="bi bi-arrow-up" aria-hidden="true"></i> ON-PROGRESS
                            </span>
                            <h2>{{ $totalProyekBerjalan }}</h2>
                        </div>
                        <div class="progress progress-mini">
                            @php $pct1 = $totalProyekBerjalan > 0 ? min(100, $totalProyekBerjalan * 10) : 10; @endphp
                            <div style="width: {{ $pct1 }}%;" class="progress-bar bg-green"></div>
                        </div>
                        <small class="text-muted" style="font-size:11px;">Status pengerjaan On-Progress</small>
                    </div>
                </div>

                <!-- Card 2: Pending Reports -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    <div class="admin-content analysis-progrebar-ctn">
                        <div class="td-section-label" style="color:#EF4444 !important;">PENDING LAPORAN (OVERDUE)</div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="badge bg-red">
                                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i> OVERDUE
                            </span>
                            <h2 style="color:#EF4444 !important;">{{ $pendingReports }}</h2>
                        </div>
                        <div class="progress progress-mini">
                            @php $pct2 = $pendingReports > 0 ? min(100, $pendingReports * 15) : 5; @endphp
                            <div style="width: {{ $pct2 }}%;" class="progress-bar bg-red"></div>
                        </div>
                        <small class="text-danger" style="font-size:11px;"><i class="bi bi-exclamation-circle"></i> Melewati batas deadline</small>
                    </div>
                </div>

                <!-- Card 3: Waiting Payment -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    <div class="admin-content analysis-progrebar-ctn">
                        <div class="td-section-label" style="color:#3B82F6 !important;">WAITING PAYMENT (PO)</div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="badge bg-blue">
                                <i class="bi bi-clock" aria-hidden="true"></i> MENUNGGU
                            </span>
                            <h2>{{ $waitingPayment }}</h2>
                        </div>
                        <div class="progress progress-mini">
                            @php $pct3 = $waitingPayment > 0 ? min(100, $waitingPayment * 12) : 20; @endphp
                            <div style="width: {{ $pct3 }}%;" class="progress-bar bg-blue"></div>
                        </div>
                        <small class="text-muted" style="font-size:11px;">Menunggu verifikasi dana PO</small>
                    </div>
                </div>

                <!-- Card 4: Paid -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    <div class="admin-content analysis-progrebar-ctn">
                        <div class="td-section-label" style="color:#8B5CF6 !important;">PEMBAYARAN LUNAS (PAID)</div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="badge bg-purple">
                                <i class="bi bi-check2-circle" aria-hidden="true"></i> LUNAS
                            </span>
                            <h2>{{ $paid }}</h2>
                        </div>
                        <div class="progress progress-mini">
                            @php $pct4 = $paid > 0 ? min(100, $paid * 8) : 60; @endphp
                            <div style="width: {{ $pct4 }}%;" class="progress-bar bg-purple"></div>
                        </div>
                        <small class="text-muted" style="font-size:11px;">Pembayaran PO terkonfirmasi</small>
                    </div>
                </div>
                @endif

                @if($user->role == 'Staff')
                <!-- Staff Cards -->
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
                    <div class="admin-content analysis-progrebar-ctn">
                        <div class="td-section-label">PENUGASAN AKTIF SAYA</div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="badge bg-green"><i class="bi bi-person-check"></i> AKTIF</span>
                            <h2>{{ $tugasSaya }}</h2>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 70%;" class="progress-bar bg-green"></div>
                        </div>
                        <small class="text-muted" style="font-size:11px;">Proyek penugasan teknisi lapangan berjalan</small>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
                    <div class="admin-content analysis-progrebar-ctn">
                        <div class="td-section-label" style="color: {{ $laporanMendekatiDeadline > 0 ? '#EF4444' : '#10B981' }} !important;">
                            STATUS DEADLINE LAPORAN
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            @if($laporanMendekatiDeadline > 0)
                            <span class="badge bg-red"><i class="bi bi-exclamation-triangle"></i> PERHATIAN</span>
                            <h2 style="color:#EF4444 !important;">{{ $laporanMendekatiDeadline }}</h2>
                            @else
                            <span class="badge bg-green"><i class="bi bi-check2-all"></i> ON TRACK</span>
                            <h2>0</h2>
                            @endif
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: {{ $laporanMendekatiDeadline > 0 ? '80' : '15' }}%;" class="progress-bar {{ $laporanMendekatiDeadline > 0 ? 'bg-red' : 'bg-green' }}"></div>
                        </div>
                        <small class="{{ $laporanMendekatiDeadline > 0 ? 'text-danger' : 'text-muted' }}" style="font-size:11px;">
                            {{ $laporanMendekatiDeadline > 0 ? 'Terdapat laporan mendekati atau melewati deadline!' : 'Seluruh jadwal laporan proyek aman.' }}
                        </small>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Recent Projects Table (Nalika product-status-wrap pattern) -->
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="product-status-wrap">
                    <div class="td-section-label">[ 02 // BUKU BESAR PROYEK TERBARU ]</div>
                    <h4>Aktivitas Proyek &amp; Status Terkini</h4>
                    <div class="add-product">
                        <a href="{{ route('projects.index') }}" class="btn-td-primary text-white" style="color: #FFFFFF !important;">
                            <span style="color: #FFFFFF !important;">Lihat Semua Proyek</span>
                            <i class="bi bi-arrow-up-right" style="color: #FFFFFF !important;"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3">NO PO</th>
                                    <th>NAMA PROYEK</th>
                                    <th>CUSTOMER</th>
                                    <th>END USER</th>
                                    <th>TGL MULAI</th>
                                    <th>DEADLINE LAPORAN</th>
                                    <th>STATUS BAYAR</th>
                                    <th>STATUS KERJA</th>
                                    <th class="text-end pe-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProjects as $project)
                                <tr>
                                    <td class="ps-3">
                                        <span class="font-mono-tech fw-bold" style="color: #FF5500; font-size:12px;">
                                            {{ $project->po->no_po ?? ('PRJ-' . str_pad($project->id, 4, '0', STR_PAD_LEFT)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block" style="font-size:13px;">{{ $project->name }}</span>
                                    </td>
                                    <td>
                                        <span style="font-size:13px; color:#374151;">{{ $project->customer->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size:13px;">{{ $project->endUser->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech" style="font-size:12px; color:#4B5563;">
                                            {{ $project->tgl_mulai ? \Carbon\Carbon::parse($project->tgl_mulai)->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech fw-bold text-danger" style="font-size:12px;">
                                            {{ $project->deadline_laporan ? \Carbon\Carbon::parse($project->deadline_laporan)->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($project->status_pembayaran == 'Paid')
                                            <span class="pd-setting">PAID</span>
                                        @else
                                            <span class="ps-setting">WAITING</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->status_pekerjaan == 'Completed')
                                            <span class="pd-setting">COMPLETED</span>
                                        @else
                                            <span class="badge-status-active">ON PROGRESS</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('projects.show', $project->id) }}" class="pd-setting-ed" title="Buka Detail Proyek">
                                            <span>Detail</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        [ BELUM ADA CATATAN PROYEK DALAM REGISTER SISTEM ]
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
