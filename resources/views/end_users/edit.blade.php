@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold">Edit End User</h3>
            <p class="text-muted">Perbarui data lokasi pelaksanaan proyek.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('end_users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm col-md-8">
        <div class="card-body p-4">
            <form action="{{ route('end_users.update', $endUser->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Perusahaan / Lokasi End User</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $endUser->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">PIC di Lapangan</label>
                    <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name', $endUser->pic_name) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nomor Telepon PIC</label>
                    <input type="text" name="pic_phone" class="form-control" value="{{ old('pic_phone', $endUser->pic_phone) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Lokasi Pekerjaan</label>
                    <textarea name="location" class="form-control" rows="3">{{ old('location', $endUser->location) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui Data</button>
            </form>
        </div>
    </div>
</div>
@endsection