@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Kelola Customer</h3>
            <p class="text-muted">Daftar perusahaan pemesan jasa PT Techno Drive.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Customer Baru
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
                            <th>Nama Perusahaan</th>
                            <th>Kontak Person</th>
                            <th>Alamat</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                        <tr>
                            <td class="ps-4">{{ $customers->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $customer->name }}</td>
                            <td>{{ $customer->contact_person ?? '-' }}</td>
                            <td>{{ Str::limit($customer->address, 40) ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
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
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data customer.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $customers->links() }}
    </div>
</div>
@endsection