@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold">Dashboard {{ $user->role }}</h3>
            <p class="text-muted">Ringkasan aktivitas dan status proyek saat ini.</p>
        </div>
    </div>

    <div class="row">
        @if(in_array($user->role, ['Supervisor', 'Director', 'Finance', 'Logistics']))
            <!-- Statistik untuk Supervisor, Director, Finance, Logistics -->
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm border-start border-primary border-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Proyek Berjalan</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ $totalProyekBerjalan }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm border-start border-danger border-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Pending Reports</div>
                        <div class="h2 mb-0 fw-bold text-danger">{{ $pendingReports }}</div>
                        <small class="text-danger"><i class="bi bi-exclamation-circle"></i> Melewati deadline</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm border-start border-warning border-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Waiting Payment</div>
                        <div class="h2 mb-0 fw-bold text-warning">{{ $waitingPayment }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm border-start border-success border-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Paid (Selesai Bayar)</div>
                        <div class="h2 mb-0 fw-bold text-success">{{ $paid }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if($user->role == 'Staff')
            <!-- Statistik Khusus Staff -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm border-start border-primary border-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Tugas Saya (Aktif)</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ $tugasSaya }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-bell text-warning"></i> Pengingat Mendatang</h6>
                        @if($laporanMendekatiDeadline > 0)
                            <p class="mb-0 text-muted">Anda memiliki <strong class="text-danger">{{ $laporanMendekatiDeadline }}</strong> laporan yang mendekati deadline (H-3 atau kurang).</p>
                        @else
                            <p class="mb-0 text-muted">Tidak ada laporan yang mendekati deadline.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Tabel Aktivitas Terbaru yang Sinkron dengan Kelola Proyek -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Aktivitas Terbaru</h6>
            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No PO / Nama Proyek</th>
                            <th>Customer & End User</th>
                            <th>Jadwal & Deadline</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pekerjaan</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProjects as $project)
                        <tr>
                            <td class="ps-3">
                                <span class="fw-bold text-primary">{{ $project->po->no_po ?? 'PRJ-' . $project->id }}</span><br>
                                <span class="text-dark fw-semibold">{{ $project->name }}</span>
                            </td>
                            <td>
                                <small class="text-muted">Cust:</small> {{ $project->customer->name ?? '-' }}<br>
                                <small class="text-muted">End User:</small> {{ $project->endUser->name ?? '-' }}
                            </td>
                            <td>
                                <small class="text-muted">Mulai:</small> {{ $project->tgl_mulai ? \Carbon\Carbon::parse($project->tgl_mulai)->format('d/m/Y') : '-' }}<br>
                                <small class="text-danger fw-bold">Deadline:</small> {{ $project->deadline_laporan ? \Carbon\Carbon::parse($project->deadline_laporan)->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                @if($project->status_pembayaran == 'Paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning text-dark">Waiting Payment</span>
                                @endif
                            </td>
                            <td>
                                @if($project->status_pekerjaan == 'Completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-primary">On Progress</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data proyek terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
