@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold">Tambah Alat Uji</h3>
            <p class="text-muted">Daftarkan perangkat ukur baru.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('equipments.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm col-md-8">
        <div class="card-body p-4">
            <form action="{{ route('equipments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Alat Uji</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nomor Seri / Serial Number</label>
                    <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number') }}" required>
                    @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Sedang Dipinjam">Sedang Dipinjam</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Alat Uji</button>
            </form>
        </div>
    </div>
</div>
@endsection