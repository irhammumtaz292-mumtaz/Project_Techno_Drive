@extends('layouts.app')

@section('title', 'Ajukan Draft SPK')
@section('page-title', 'Ajukan Draft SPK Baru')

@section('breadcrumb-actions')
    <a href="{{ route('spks.index') }}" class="btn-td-dark">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Daftar SPK</span>
    </a>
@endsection

@section('content')

<div class="product-status mg-b-30">
    <div class="container-fluid">

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 border-start border-4 border-danger mb-3" role="alert" style="border-radius:4px; background:#FEF2F2; color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill me-2" style="color:#EF4444;"></i><strong>[ PERHATIAN ]</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 border-start border-4 border-danger mb-3" role="alert" style="border-radius:4px; background:#FEF2F2; color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill me-2" style="color:#EF4444;"></i><strong>[ MOHON PERIKSA ISIAN FORM ]</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="product-status-wrap">
                    <div class="td-section-label">[ SUPERVISOR // PENGAJUAN SURAT PERINTAH KERJA ]</div>
                    <h4>Form Pengajuan Draft SPK</h4>

                    <div class="alert alert-info py-2 px-3 small mt-3 mb-4 border-0 border-start border-3 border-info" style="background:#EFF6FF; color:#1E40AF;">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Nomor SPK akan digenerate <strong>otomatis</strong> oleh sistem dengan format <code>[Urut]/TD/SPK/[Bulan Romawi]/[Tahun]</code>. Setelah diajukan, SPK berstatus <strong>Draft</strong> dan masuk ke antrean verifikasi <strong>Logistik</strong>.
                    </div>

                    @if($projects->isEmpty())
                        <div class="alert alert-warning border-0 border-start border-4 border-warning" style="background:#FFFBEB; color:#92400E;">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            Semua proyek yang ada saat ini sudah memiliki SPK aktif. Silakan daftarkan proyek baru terlebih dahulu atau batalkan SPK sebelumnya jika ingin mengajukan ulang.
                        </div>
                    @else
                        <form action="{{ route('spks.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="project_id" class="form-label fw-semibold small text-uppercase text-muted">
                                    Pilih Proyek <span class="text-danger">*</span>
                                </label>
                                <select name="project_id" id="project_id"
                                        class="form-select select2 @error('project_id') is-invalid @enderror"
                                        data-placeholder="-- Cari & Pilih Proyek --"
                                        required>
                                    <option value=""></option>
                                    @foreach($projects as $project)
                                        @php
                                            $isSelected = (string) old('project_id', $selectedProjectId) === (string) $project->id;
                                        @endphp
                                        <option value="{{ $project->id }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ $project->po->no_po ?? 'NO-PO' }} — {{ $project->name }} ({{ $project->customer->name ?? '-' }} / {{ $project->endUser->name ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tgl_spk" class="form-label fw-semibold small text-uppercase text-muted">
                                    Tanggal SPK <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="tgl_spk" id="tgl_spk"
                                       class="form-control @error('tgl_spk') is-invalid @enderror"
                                       value="{{ old('tgl_spk', date('Y-m-d')) }}"
                                       required>
                                @error('tgl_spk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('spks.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn-td-primary">
                                    <i class="bi bi-send-check"></i>
                                    <span>Ajukan Draft SPK</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--classic .select2-selection--single {
    border-color: #ced4da;
    border-radius: 6px;
    min-height: 38px;
}
.select2-container--classic .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
}
.select2-container--classic.select2-container--open .select2-selection--single {
    border-color: #FF5500;
    box-shadow: 0 0 0 0.2rem rgba(255, 85, 0, 0.15);
}
.select2-container--classic .select2-results__option--highlighted {
    background-color: #FF5500 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'classic',
        width: '100%',
        allowClear: true
    });
});
</script>
@endpush
