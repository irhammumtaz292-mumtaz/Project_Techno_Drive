@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold">Tambah Akun Pengguna</h3>
            <p class="text-muted">Buat akun untuk staf, direktur, atau bagian lainnya.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('staffs.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm col-md-8">
        <div class="card-body p-4">
            <form action="{{ route('staffs.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Email (Untuk Login)</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Hak Akses (Role)</label>
                    <select name="role" class="form-select" required>
                        <option value="Staff">Staff (Teknisi)</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Director">Director</option>
                        <option value="Finance">Finance</option>
                        <option value="Logistics">Logistics</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Akun</button>
            </form>
        </div>
    </div>
</div>
@endsection
