@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold">Tambah Customer</h3>
            <p class="text-muted">Masukkan informasi perusahaan pemesan.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm col-md-8">
        <div class="card-body p-4">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Perusahaan Customer</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Kontak Person (PIC)</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Customer</button>
            </form>
        </div>
    </div>
</div>
@endsection