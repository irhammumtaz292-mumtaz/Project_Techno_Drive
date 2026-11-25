@extends('layouts.app')

@section('title', 'Tambah Alat Uji')
@section('page-title', 'Tambah Alat Uji Baru')

@section('breadcrumb-actions')
<a href="{{ route('equipments.index') }}" class="btn-td-secondary">
    <i class="bi bi-arrow-left"></i>
    <span>Kembali ke Daftar</span>
</a>
@endsection

@section('content')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="product-status-wrap">
                    <div class="td-section-label">[ LOGISTIK // REGISTRASI ALAT BARU ]</div>
                    <h4>Form Tambah Alat Uji</h4>

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 border-start border-4 border-danger mb-3 mt-3" role="alert" style="border-radius:4px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>[ PERIKSA INPUT ]</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('equipments.store') }}" method="POST" class="mt-4">
                        @csrf

                        {{-- 1. Nama & Jenis Alat (Wajib) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="font-size:12px; letter-spacing:.5px; color:#374151;">
                                NAMA &amp; JENIS ALAT <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Digital Multimeter / Insulation Tester"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. Merek & Model (Wajib) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="font-size:12px; letter-spacing:.5px; color:#374151;">
                                MEREK &amp; MODEL <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="brand_model"
                                class="form-control @error('brand_model') is-invalid @enderror"
                                value="{{ old('brand_model') }}"
                                placeholder="Contoh: Fluke 87V / Megger MIT515"
                                required>
                            @error('brand_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. Jumlah Unit (Wajib, Min 1) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="font-size:12px; letter-spacing:.5px; color:#374151;">
                                JUMLAH UNIT <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="unit_count" min="1"
                                class="form-control font-mono-tech @error('unit_count') is-invalid @enderror"
                                value="{{ old('unit_count', 1) }}"
                                required>
                            @error('unit_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 4. Status Ketersediaan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size:12px; letter-spacing:.5px; color:#374151;">
                                STATUS KETERSEDIAAN
                            </label>
                            <select name="status" class="form-select" required>
                                <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="Sedang Dipinjam" {{ old('status') == 'Sedang Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-td-primary">
                                <i class="bi bi-floppy"></i>
                                <span>Simpan Alat Uji</span>
                            </button>
                            <a href="{{ route('equipments.index') }}" class="btn-td-secondary">
                                <span>Batal</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
