@extends('layouts.app')

@section('title', 'Kelola End User')
@section('page-title', 'Kelola End User')

@section('breadcrumb-actions')
    <a href="{{ route('end_users.create') }}" class="btn-td-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah End User Baru</span>
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
                    <div class="td-section-label">[ MASTER DATA // ENTITAS END USER ]</div>
                    <h4>Daftar Lokasi &amp; Penerima Manfaat Proyek</h4>
                    
                    <div class="table-responsive mt-3">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">NO</th>
                                    <th>NAMA END USER / LOKASI</th>
                                    <th>ALAMAT LOKASI</th>
                                    <th>PIC LAPANGAN</th>
                                    <th>NO TELEPON PIC</th>
                                    <th class="text-end pe-3" style="width: 140px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($endUsers as $index => $endUser)
                                <tr>
                                    <td class="ps-3 font-mono-tech" style="font-size:12px; color:#6B7280;">
                                        {{ $endUsers->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <strong class="text-dark" style="font-size:13.5px;">{{ $endUser->name }}</strong>
                                    </td>
                                    <td>
                                        <span style="font-size:13px; color:#4B5563;">{{ Str::limit($endUser->location, 40) ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span style="font-size:13px; color:#374151;">{{ $endUser->pic_name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech" style="font-size:12px; color:#4B5563;">{{ $endUser->pic_phone ?? '-' }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('end_users.edit', $endUser->id) }}" class="pd-setting-ed" title="Edit Data End User">
                                                <i class="bi bi-pencil-square"></i>
                                                <span>Edit</span>
                                            </a>
                                            <form action="{{ route('end_users.destroy', $endUser->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus end user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-po" style="padding: 4px 8px !important;" title="Hapus End User">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        [ BELUM ADA DATA END USER TERDAFTAR ]
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($endUsers->hasPages())
                    <div class="custom-pagination mt-4 d-flex justify-content-end">
                        {{ $endUsers->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
