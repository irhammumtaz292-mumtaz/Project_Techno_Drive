@extends('layouts.app')

@section('title', 'Kelola Proyek')
@section('page-title', 'Kelola Proyek')

@section('breadcrumb-actions')
    @php
        $userRoleNorm = strtolower(trim(Auth::user()->role ?? ''));
    @endphp
    @if(in_array($userRoleNorm, ['supervisor']))
    <a href="{{ route('projects.create') }}" class="btn-td-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Proyek Baru</span>
    </a>
    @endif
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

        <div class="row">
            <div class="col-12">
                <div class="product-status-wrap">
                    <h4>Daftar Seluruh Proyek &amp; Pekerjaan Teknik</h4>


                    <div class="table-responsive mt-3">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">NO</th>
                                    <th>NO PO</th>
                                    <th>NAMA PROYEK</th>
                                    <th>CUSTOMER</th>
                                    <th>END USER</th>
                                    <th>JADWAL MULAI</th>
                                    <th>DEADLINE LAPORAN</th>
                                    <th>STATUS BAYAR</th>
                                    <th>STATUS KERJA</th>
                                    <th class="text-end pe-3" style="min-width: 140px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $index => $project)
                                <tr>
                                    <td class="ps-3 font-mono-tech" style="font-size:12px; color:#6B7280;">
                                        {{ $projects->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <span class="font-mono-tech fw-bold" style="color: #FF5500; font-size:12.5px;">
                                            {{ $project->po->no_po ?? '-' }}
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
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('projects.download-po', $project->id) }}" class="btn-action-po" title="Unduh Dokumen Purchase Order PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                                <span>PO</span>
                                            </a>
                                            <a href="{{ route('projects.show', $project->id) }}" class="pd-setting-ed" title="Buka Detail Lengkap Proyek">
                                                <span>Detail</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        [ BELUM ADA DATA PROYEK TERDAFTAR DALAM INDEKS ]
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Custom Pagination -->
                    @if($projects->hasPages())
                    <div class="custom-pagination mt-4 d-flex justify-content-end">
                        {{ $projects->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
