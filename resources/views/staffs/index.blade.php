@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Kelola Akun & Staff</h3>
            <p class="text-muted">Daftar seluruh pengguna sistem PT Techno Drive.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('staffs.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Tambah Akun Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role / Jabatan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffs as $index => $user)
                        <tr>
                            <td class="ps-4">{{ $staffs->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge 
                                    @if($user->role == 'Supervisor') bg-dark 
                                    @elseif($user->role == 'Director') bg-primary 
                                    @elseif($user->role == 'Staff') bg-secondary 
                                    @else bg-info @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('staffs.edit', $user->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('staffs.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $staffs->links() }}
    </div>
</div>
@endsection