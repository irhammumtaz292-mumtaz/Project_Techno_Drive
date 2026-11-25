@extends('layouts.app')

@section('title', 'Kelola Akun & Staff')
@section('page-title', 'Kelola Akun & Staff')

@section('breadcrumb-actions')
    <a href="{{ route('staffs.create') }}" class="btn-td-primary">
        <i class="bi bi-person-plus"></i>
        <span>Tambah Akun Baru</span>
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

        <div class="row">
            <div class="col-12">
                <div class="product-status-wrap">
                    <div class="td-section-label">[ MANAJEMEN SDM // PENGGUNA SISTEM ]</div>
                    <h4>Daftar Seluruh Pengguna &amp; Staff</h4>
                   

                    <div class="table-responsive mt-3">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">NO</th>
                                    <th>NAMA LENGKAP</th>
                                    <th>EMAIL</th>
                                    <th>ROLE / JABATAN</th>
                                    <th class="text-end pe-3" style="width: 140px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffs as $index => $u)
                                <tr>
                                    <td class="ps-3 font-mono-tech" style="font-size:12px; color:#6B7280;">
                                        {{ $staffs->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <strong class="text-dark" style="font-size:13.5px;">{{ $u->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech" style="font-size:12.5px; color:#4B5563;">{{ $u->email }}</span>
                                    </td>
                                    <td>
                                        <span class="badge font-mono-tech" style="font-size:10.5px; 
                                            @if($u->role == 'Supervisor') background:#111111; color:#FFFFFF;
                                            @elseif($u->role == 'Director') background:#FF5500; color:#FFFFFF;
                                            @elseif($u->role == 'Staff') background:#3B82F6; color:#FFFFFF;
                                            @elseif($u->role == 'Finance') background:#10B981; color:#FFFFFF;
                                            @else background:#6B7280; color:#FFFFFF; @endif">
                                            {{ strtoupper($u->role) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('staffs.edit', $u->id) }}" class="pd-setting-ed" title="Edit Akun Staff">
                                                <i class="bi bi-pencil-square"></i>
                                                <span>Edit</span>
                                            </a>
                                            <form action="{{ route('staffs.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-po" style="padding: 4px 8px !important;" title="Hapus Akun">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        [ BELUM ADA DATA PENGGUNA TERDAFTAR ]
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($staffs->hasPages())
                    <div class="custom-pagination mt-4 d-flex justify-content-end">
                        {{ $staffs->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
