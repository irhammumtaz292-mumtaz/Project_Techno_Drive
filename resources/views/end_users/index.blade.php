@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Kelola End User</h3>
            <p class="text-muted">Daftar lokasi pelaksanaan proyek / penerima manfaat.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('end_users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah End User Baru
            </a>
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
                            <th class="ps-4">No</th>
                            <th>Nama Perusahaan / Lokasi</th>
                            <th>Alamat Lokasi</th>
                            <th>PIC Lapangan</th>
                            <th>No Telepon PIC</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($endUsers as $index => $endUser)
                        <tr>
                            <td class="ps-4">{{ $endUsers->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $endUser->name }}</td>
                            <td>{{ Str::limit($endUser->location, 30) ?? '-' }}</td>
                            <td>{{ $endUser->pic_name ?? '-' }}</td>
                            <td>{{ $endUser->pic_phone ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('end_users.edit', $endUser->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('end_users.destroy', $endUser->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus end user ini?')">
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
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data end user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $endUsers->links() }}
    </div>
</div>
@endsection