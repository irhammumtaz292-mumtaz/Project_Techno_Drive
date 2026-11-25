@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

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
                    <div class="td-section-label">[ FINANCE // VERIFIKASI PEMBAYARAN PROYEK ]</div>
                    <h4>Status Pembayaran Purchase Order</h4>

                    <div class="d-flex align-items-center gap-2 mt-2 mb-1" style="font-size:12px; color:#6B7280;">
                        <i class="bi bi-info-circle" style="color:#FF5500;"></i>
                        <span class="font-mono-tech">[ Nominal tagihan diambil dari data PO yang diinput saat registrasi proyek — READ ONLY ]</span>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">NO</th>
                                    <th>CUSTOMER</th>
                                    <th>NAMA PROYEK</th>
                                    <th>NO PO</th>
                                    <th>NOMINAL TAGIHAN</th>
                                    <th>STATUS BAYAR</th>
                                    <th class="text-end pe-3" style="min-width: 180px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $index => $project)
                                <tr>
                                    <td class="ps-3 font-mono-tech" style="font-size:12px; color:#6B7280;">
                                        {{ $projects->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <span style="font-size:13px; color:#374151;">{{ $project->customer->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.show', $project->id) }}" class="fw-bold text-dark" style="font-size:13px; text-decoration:none;">
                                            {{ $project->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech fw-bold" style="color: #FF5500; font-size:12.5px;">
                                            {{ $project->po->no_po ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech fw-bold" style="color: #111111; font-size:13px;">
                                            Rp {{ number_format($project->po->nilai_pekerjaan ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($project->status_pembayaran == 'Paid')
                                            <span class="pd-setting">PAID</span>
                                        @else
                                            <span class="ps-setting">WAITING</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($project->status_pembayaran == 'Waiting Payment')
                                        <form action="{{ route('projects.update-payment', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ubah status menjadi PAID (Sudah Bayar)?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pembayaran" value="Paid">
                                            <button type="submit" class="pd-setting-ed" style="font-size:11px; padding: 4px 10px !important;" title="Tandai Sudah Bayar">
                                                <i class="bi bi-check-circle me-1"></i>
                                                <span>Tandai Paid</span>
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('projects.update-payment', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan status menjadi WAITING PAYMENT?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pembayaran" value="Waiting Payment">
                                            <button type="submit" class="btn-action-po" style="font-size:11px; padding: 4px 10px !important;" title="Batalkan ke Waiting">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                <span>Waiting</span>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        [ BELUM ADA DATA PROYEK TERDAFTAR ]
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

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
