@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Kelola Proyek PT Techno Drive</h3>
            <p class="text-muted">Daftar seluruh pekerjaan dan pemantauan status.</p>
        </div>
      
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No PO / Nama Proyek</th>
                            <th>Customer & End User</th>
                            <th>Jadwal & Deadline</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pekerjaan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $index => $project)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary">{{ $project->po->no_po ?? '-' }}</span><br>
                                <span class="text-dark fw-semibold">{{ $project->name }}</span>
                            </td>
                            <td>
                                <small class="text-muted">Cust:</small> {{ $project->customer->name ?? '-' }}<br>
                                <small class="text-muted">End User:</small> {{ $project->endUser->name ?? '-' }}
                            </td>
                            <td>
                                <small class="text-muted">Mulai:</small> {{ $project->tgl_mulai }}<br>
                                <small class="text-danger fw-bold">Deadline Laporan:</small> {{ $project->deadline_laporan }}
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
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('projects.download-po', $project->id) }}" class="btn btn-outline-danger" title="Download Dokumen PO (PDF)">
                                        <i class="fa fa-file-pdf-o"></i> PO
                                    </a>
                                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary" title="Lihat Detail Proyek">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                </div>
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
    <div class="mt-3">
        {{ $projects->links() }}
    </div>
</div>
@endsection