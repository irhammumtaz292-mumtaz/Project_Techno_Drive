@extends('layouts.app')

@section('content')
{{-- ===================================================
     REGISTRASI PROYEK & PO BARU
     Format mengikuti form Jasa_Pengujian (Excel)
     Kolom: No, Nama Pekerjaan, Customer, End User,
            No.Kontrak/PO (auto), Tanggal Kontrak/PO,
            Lokasi, Batas Kontrak, PIC End User,
            No. HP, Estimasi Waktu (auto), Waktu Mulai,
            Waktu Selesai, Alat Uji, Status, PIC, Pelaksana,
            Progress Pekerjaan
     =================================================== --}}
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1a2a3a;">
                <i class="fa fa-file-text-o me-2" style="color: #fd5f00;"></i>Registrasi Proyek & PO Baru
            </h4>
            <p class="text-muted mb-0 small">Isi seluruh data berikut untuk mendaftarkan proyek baru. Nomor PO akan digenerate otomatis oleh sistem.</p>
        </div>
        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar Proyek
        </a>
    </div>

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i><strong>Mohon periksa kembali isian form:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST" id="formRegistrasiProyek">
        @csrf

        <div class="row g-4">

            {{-- =============================================
                 KOLOM UTAMA (8/12)
                 ============================================= --}}
            <div class="col-xl-8">

                {{-- ---- SECTION A: INFORMASI PEKERJAAN ---- --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                        <h6 class="mb-0 fw-bold text-white">
                            <i class="fa fa-briefcase me-2" style="color: #fd5f00;"></i>A. Informasi Pekerjaan
                        </h6>
                    </div>
                    <div class="card-body">

                        {{-- Nama Pekerjaan --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                Nama / Judul Pekerjaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Pengujian Instalasi Listrik Gedung A"
                                   required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Customer & End User --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    Customer (Perusahaan Pemesan) <span class="text-danger">*</span>
                                </label>
                                <select name="customer_id"
                                        class="form-select select2 @error('customer_id') is-invalid @enderror"
                                        data-placeholder="-- Cari & Pilih Customer --"
                                        required>
                                    <option value=""></option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    End User (Lokasi Pekerjaan) <span class="text-danger">*</span>
                                </label>
                                <select name="end_user_id"
                                        class="form-select select2 @error('end_user_id') is-invalid @enderror"
                                        id="endUserSelect"
                                        data-placeholder="-- Cari & Pilih End User --"
                                        required>
                                    <option value=""></option>
                                    @foreach($endUsers as $e)
                                        <option value="{{ $e->id }}"
                                                data-pic="{{ $e->pic_name ?? '' }}"
                                                data-phone="{{ $e->pic_phone ?? '' }}"
                                                data-location="{{ $e->location ?? '' }}"
                                                {{ old('end_user_id') == $e->id ? 'selected' : '' }}>
                                            {{ $e->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('end_user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Lokasi & PIC End User (auto-filled dari End User yang dipilih) --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    Lokasi Pekerjaan
                                </label>
                                <input type="text" id="infoLokasi"
                                       class="form-control bg-light" readonly
                                       placeholder="Otomatis dari End User...">
                                <div class="form-text"><i class="fa fa-info-circle"></i> Diisi otomatis dari data End User</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    PIC End User
                                </label>
                                <input type="text" id="infoPicEndUser"
                                       class="form-control bg-light" readonly
                                       placeholder="Otomatis dari End User...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    No. Handphone PIC
                                </label>
                                <input type="text" id="infoPicPhone"
                                       class="form-control bg-light" readonly
                                       placeholder="Otomatis dari End User...">
                            </div>
                        </div>

                        {{-- PIC Techno Drive --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                PIC / Penanggung Jawab (dari Techno Drive) <span class="text-danger">*</span>
                            </label>
                            <select name="pic_id"
                                    class="form-select select2 @error('pic_id') is-invalid @enderror"
                                    data-placeholder="-- Pilih PIC --"
                                    required>
                                <option value=""></option>
                                @foreach($pics as $pic)
                                    <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>
                                        {{ $pic->name }} ({{ $pic->role }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Deskripsi Pekerjaan --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                Deskripsi / Lingkup Pekerjaan
                            </label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Jelaskan lingkup pekerjaan secara singkat...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                {{-- ---- SECTION B: DATA PURCHASE ORDER (PO) ---- --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                        <h6 class="mb-0 fw-bold text-white">
                            <i class="fa fa-file-o me-2" style="color: #fd5f00;"></i>B. Data Kontrak / Purchase Order (PO)
                        </h6>
                    </div>
                    <div class="card-body">

                        {{-- Nomor PO (auto) --}}
                        <div class="alert py-2 px-3 mb-3" style="background:#fff8f0; border-left: 4px solid #fd5f00;">
                            <small class="text-muted">
                                <i class="fa fa-magic me-1" style="color:#fd5f00;"></i>
                                <strong>No. Kontrak / PO</strong> akan di-generate otomatis oleh sistem dengan format
                                <code>001/TD/PO/[Bulan Romawi]/[Tahun]</code>
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    Tanggal Kontrak / PO <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="tgl_po"
                                       class="form-control @error('tgl_po') is-invalid @enderror"
                                       value="{{ old('tgl_po') }}"
                                       required>
                                @error('tgl_po') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    Nilai Pekerjaan (Rp) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold">Rp</span>
                                    <input type="number" step="0.01" name="nilai_pekerjaan"
                                           class="form-control @error('nilai_pekerjaan') is-invalid @enderror"
                                           value="{{ old('nilai_pekerjaan') }}"
                                           placeholder="0"
                                           required>
                                    @error('nilai_pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ---- SECTION C: JADWAL & DEADLINE ---- --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                        <h6 class="mb-0 fw-bold text-white">
                            <i class="fa fa-calendar me-2" style="color: #fd5f00;"></i>C. Jadwal Pelaksanaan & Deadline
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    Waktu Mulai Pekerjaan <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="tgl_mulai"
                                       class="form-control @error('tgl_mulai') is-invalid @enderror"
                                       value="{{ old('tgl_mulai') }}"
                                       id="tglMulai"
                                       required>
                                @error('tgl_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    Waktu Selesai Pekerjaan <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="tgl_selesai"
                                       class="form-control @error('tgl_selesai') is-invalid @enderror"
                                       value="{{ old('tgl_selesai') }}"
                                       id="tglSelesai"
                                       required>
                                @error('tgl_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Estimasi Lama Waktu (auto-hitung) --}}
                        <div class="alert py-2 px-3 mb-3" style="background:#f0f8ff; border-left: 4px solid #17a2b8;">
                            <small>
                                <i class="fa fa-clock-o me-1" style="color:#17a2b8;"></i>
                                <strong>Estimasi Lama Waktu Pekerjaan:</strong>
                                <span id="estimasiWaktu" class="fw-bold" style="color:#17a2b8;">
                                    — Pilih tanggal mulai dan selesai
                                </span>
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                    Tanggal Batas Kontrak <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="tgl_jatuh_tempo_kontrak"
                                       class="form-control @error('tgl_jatuh_tempo_kontrak') is-invalid @enderror"
                                       value="{{ old('tgl_jatuh_tempo_kontrak') }}"
                                       required>
                                @error('tgl_jatuh_tempo_kontrak') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em; color:#dc3545 !important;">
                                    <i class="fa fa-warning" style="color:#dc3545;"></i>
                                    Deadline Upload Final Report <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="deadline_laporan"
                                       class="form-control border-danger @error('deadline_laporan') is-invalid @enderror"
                                       value="{{ old('deadline_laporan') }}"
                                       required>
                                <div class="form-text text-danger">
                                    <i class="fa fa-info-circle"></i> Sistem akan mengirim pengingat otomatis H-3, H-1, dan Hari-H ke staf pelaksana.
                                </div>
                                @error('deadline_laporan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Progress Pekerjaan --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-uppercase text-muted" style="letter-spacing:.05em;">
                                Progress / Catatan Pekerjaan
                            </label>
                            <textarea name="progress_notes"
                                      class="form-control @error('progress_notes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Catatan progres awal, kondisi lapangan, atau informasi tambahan...">{{ old('progress_notes') }}</textarea>
                            @error('progress_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

            </div>{{-- /col-xl-8 --}}

            {{-- =============================================
                 KOLOM SAMPING (4/12)
                 ============================================= --}}
            <div class="col-xl-4">

                {{-- ---- SECTION D: PELAKSANA PEKERJAAN ---- --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                        <h6 class="mb-0 fw-bold text-white">
                            <i class="fa fa-users me-2" style="color: #fd5f00;"></i>D. Pelaksana Pekerjaan (Staff)
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i class="fa fa-info-circle me-1"></i>
                            Pilih satu atau lebih teknisi yang akan mengerjakan proyek ini.
                        </p>
                        <select name="staffs[]"
                                class="form-select select2 @error('staffs') is-invalid @enderror"
                                multiple="multiple"
                                required
                                data-placeholder="Cari nama pelaksana...">
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                        @error('staffs') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- ---- SECTION E: ALAT UJI YANG DIGUNAKAN ---- --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #1a2a3a, #2d4057); border-bottom: 3px solid #fd5f00;">
                        <h6 class="mb-0 fw-bold text-white">
                            <i class="fa fa-wrench me-2" style="color: #fd5f00;"></i>E. Alat Uji Yang Digunakan <span class="text-danger">*</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i class="fa fa-info-circle me-1"></i>
                            Wajib pilih minimal 1 alat uji. Hanya alat berstatus <strong>Tersedia</strong> yang ditampilkan. Sistem akan memvalidasi kalibrasi & jadwal bentrok secara otomatis.
                        </p>
                        <select name="equipments[]"
                                class="form-select select2 @error('equipments') is-invalid @enderror"
                                multiple="multiple"
                                required
                                data-placeholder="Cari nama atau nomor seri alat...">
                            @forelse($equipments as $eq)
                                <option value="{{ $eq->id }}">
                                    {{ $eq->name }} <small>(SN: {{ $eq->serial_number }})</small>
                                    @if($eq->calibration_expiry)
                                        — Kalibrasi s/d {{ \Carbon\Carbon::parse($eq->calibration_expiry)->format('d/m/Y') }}
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada alat yang tersedia saat ini</option>
                            @endforelse
                        </select>
                        @error('equipments') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- ---- RINGKASAN SEBELUM SUBMIT ---- --}}
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid #fd5f00 !important;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color:#1a2a3a;">
                            <i class="fa fa-check-square-o me-1" style="color:#fd5f00;"></i> Status Registrasi
                        </h6>
                        <ul class="list-unstyled small text-muted mb-3">
                            <li class="mb-1"><i class="fa fa-circle-o me-2" style="color:#fd5f00;"></i>Nomor PO digenerate <strong>otomatis</strong></li>
                            <li class="mb-1"><i class="fa fa-circle-o me-2" style="color:#fd5f00;"></i>Status awal proyek: <strong>Waiting Payment</strong></li>
                            <li class="mb-1"><i class="fa fa-circle-o me-2" style="color:#fd5f00;"></i>Proyek belum tampil di dashboard Staff hingga status <strong>Paid</strong></li>
                            <li class="mb-1"><i class="fa fa-circle-o me-2" style="color:#fd5f00;"></i>Reminder laporan otomatis aktif pada H-3, H-1, Hari-H</li>
                        </ul>
                        <button type="submit" class="btn w-100 fw-bold py-2 shadow-sm"
                                style="background:#fd5f00; color:#fff; border:none;"
                                onclick="this.disabled=true; this.innerHTML='<i class=\'fa fa-spinner fa-spin me-1\'></i> Menyimpan...'; this.form.submit();">
                            <i class="fa fa-check-circle me-1"></i> Simpan & Registrasikan Proyek
                        </button>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                            Batal
                        </a>
                    </div>
                </div>

            </div>{{-- /col-xl-4 --}}
        </div>{{-- /row --}}
    </form>
</div>
@endsection

@section('scripts')
{{-- Select2 CSS & JS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Select2 styling untuk tema Industrial */
.select2-container--classic .select2-selection--multiple,
.select2-container--classic .select2-selection--single {
    border-color: #ced4da;
    border-radius: 6px;
    min-height: 38px;
}
.select2-container--classic .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
}
.select2-container--classic.select2-container--open .select2-selection--single,
.select2-container--classic.select2-container--open .select2-selection--multiple {
    border-color: #fd5f00;
    box-shadow: 0 0 0 0.2rem rgba(253, 95, 0, 0.15);
}
.select2-container--classic .select2-results__option--highlighted {
    background-color: #fd5f00 !important;
}
.select2-container--classic .select2-selection--multiple .select2-selection__choice {
    background-color: #1a2a3a;
    border-color: #1a2a3a;
    color: #fff;
}
.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove {
    color: #fd5f00;
}
/* Info panel styling */
.form-control[readonly] {
    background-color: #f8f9fa;
    color: #6c757d;
    font-style: italic;
}
</style>

<script>
$(document).ready(function() {

    // ---- Inisialisasi Select2 ----
    $('.select2').select2({
        theme: 'classic',
        width: '100%',
        allowClear: true
    });

    // ---- Auto-fill Lokasi & PIC dari End User yang dipilih ----
    $('#endUserSelect').on('change', function() {
        var selected = $(this).find(':selected');
        var pic     = selected.data('pic')      || '';
        var phone   = selected.data('phone')    || '';
        var location = selected.data('location') || '';

        $('#infoLokasi').val(location      || 'Data lokasi tidak tersedia');
        $('#infoPicEndUser').val(pic       || 'Data PIC tidak tersedia');
        $('#infoPicPhone').val(phone       || 'Data nomor HP tidak tersedia');
    });

    // Trigger jika ada nilai old() (setelah validasi gagal)
    if ($('#endUserSelect').val()) {
        $('#endUserSelect').trigger('change');
    }

    // ---- Hitung Estimasi Lama Waktu Pekerjaan ----
    function hitungEstimasi() {
        var mulai   = $('#tglMulai').val();
        var selesai = $('#tglSelesai').val();
        if (mulai && selesai) {
            var d1 = new Date(mulai);
            var d2 = new Date(selesai);
            var diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
            if (diff >= 0) {
                $('#estimasiWaktu').text(diff + ' hari (' + mulai + ' s/d ' + selesai + ')');
            } else {
                $('#estimasiWaktu').text('⚠ Tanggal selesai harus setelah tanggal mulai!');
            }
        } else {
            $('#estimasiWaktu').text('— Pilih tanggal mulai dan selesai');
        }
    }

    $('#tglMulai, #tglSelesai').on('change', hitungEstimasi);

    // Trigger untuk old values
    hitungEstimasi();

});
</script>
@endsection
