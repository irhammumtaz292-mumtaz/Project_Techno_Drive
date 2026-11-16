@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold">Edit Akun Pengguna</h3>
            <p class="text-muted">Perbarui data atau hak akses pengguna.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('staffs.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm col-md-8">
        <div class="card-body p-4">
            <form action="{{ route('staffs.update', $staff->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $staff->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $staff->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengubah password)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Hak Akses (Role)</label>
                    <select name="role" class="form-select" required>
                        <option value="Staff" {{ $staff->role == 'Staff' ? 'selected' : '' }}>Staff (Teknisi)</option>
                        <option value="Supervisor" {{ $staff->role == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Director" {{ $staff->role == 'Director' ? 'selected' : '' }}>Director</option>
                        <option value="Finance" {{ $staff->role == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Logistics" {{ $staff->role == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui Akun</button>
            </form>
        </div>
    </div>
</div>
@endsection