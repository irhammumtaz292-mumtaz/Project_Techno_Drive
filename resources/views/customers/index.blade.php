@extends('layouts.app')

@section('title', 'Kelola Customer')
@section('page-title', 'Kelola Customer')

@section('breadcrumb-actions')
    <a href="{{ route('customers.create') }}" class="btn-td-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Customer Baru</span>
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

        <div class="row">
            <div class="col-12">
                <div class="product-status-wrap">
                    <div class="td-section-label">[ MASTER DATA // ENTITAS KLIEN ]</div>
                    <h4>Daftar Perusahaan Pemesan Jasa</h4>
                    

                    <div class="table-responsive mt-3">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">NO</th>
                                    <th>NAMA PERUSAHAAN</th>
                                    <th>KONTAK PERSON</th>
                                    <th>ALAMAT PERUSAHAAN</th>
                                    <th class="text-end pe-3" style="width: 140px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $index => $customer)
                                <tr>
                                    <td class="ps-3 font-mono-tech" style="font-size:12px; color:#6B7280;">
                                        {{ $customers->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <strong class="text-dark" style="font-size:13.5px;">{{ $customer->name }}</strong>
                                    </td>
                                    <td>
                                        <span style="font-size:13px; color:#374151;">{{ $customer->contact_person ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size:13px;">{{ Str::limit($customer->address, 50) ?? '-' }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('customers.edit', $customer->id) }}" class="pd-setting-ed" title="Edit Data Customer">
                                                <i class="bi bi-pencil-square"></i>
                                                <span>Edit</span>
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-po" style="padding: 4px 8px !important;" title="Hapus Customer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        [ BELUM ADA DATA CUSTOMER TERDAFTAR ]
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($customers->hasPages())
                    <div class="custom-pagination mt-4 d-flex justify-content-end">
                        {{ $customers->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
