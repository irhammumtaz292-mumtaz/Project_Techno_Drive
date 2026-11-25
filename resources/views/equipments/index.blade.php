@extends('layouts.app')

@section('title', 'Kelola Alat Uji')
@section('page-title', 'Kelola Alat Uji')

@section('breadcrumb-actions')
    @php
        $isLogistik = in_array(strtolower(trim(Auth::user()->role ?? '')), ['logistik', 'logistics']);
    @endphp
    @if($isLogistik)
    <a href="{{ route('equipments.create') }}" class="btn-td-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Alat Uji Baru</span>
    </a>
    @endif
@endsection

@section('content')

@php
    $isLogistik = in_array(strtolower(trim(Auth::user()->role ?? '')), ['logistik', 'logistics']);
@endphp

<div class="product-status mg-b-30">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 border-start border-4 border-success mb-3" role="alert" style="border-radius:4px; background:#F0FDF4; color:#166534;">
            <i class="bi bi-check-circle-fill me-2" style="color:#10B981;"></i><strong>[ BERHASIL ]</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 border-start border-4 border-danger mb-3" role="alert" style="border-radius:4px;">
            <i class="bi bi-x-circle-fill me-2"></i><strong>[ GAGAL ]</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="product-status-wrap">
                    <div class="td-section-label">[ LOGISTIK // INVENTORI PERALATAN ]</div>
                    <h4>Daftar Perangkat &amp; Alat Uji Teknik</h4>

                    {{-- Info akses untuk non-Logistik --}}
                    @if(!$isLogistik)
                    <div class="d-flex align-items-center gap-2 mt-2 mb-1" style="font-size:12px; color:#6B7280;">
                        <i class="bi bi-eye" style="color:#FF5500;"></i>
                        <span class="font-mono-tech">[ MODE BACA — Penambahan dan perubahan status ketersediaan dikelola oleh Logistik ]</span>
                    </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table-td-nalika">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">NO</th>
                                    <th>NAMA &amp; JENIS ALAT</th>
                                    <th>MEREK &amp; MODEL</th>
                                    <th>JUMLAH UNIT</th>
                                    <th>STATUS KETERSEDIAAN</th>
                                    <th class="text-end pe-3" style="width: 200px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($equipments as $index => $item)
                                <tr>
                                    <td class="ps-3 font-mono-tech" style="font-size:12px; color:#6B7280;">
                                        {{ $equipments->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <strong class="text-dark" style="font-size:13.5px;">{{ $item->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech px-2 py-1 bg-light border rounded" style="font-size:11.5px; color:#111111;">
                                            {{ $item->brand_model ?? ($item->serial_number ?? '-') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-mono-tech fw-bold" style="font-size:12.5px; color:#111111;">
                                            {{ $item->unit_count ?? 1 }} Unit
                                        </span>
                                    </td>
                                    <td>
                                        @if(in_array(strtolower($item->status), ['tersedia']))
                                            <span class="pd-setting">TERSEDIA</span>
                                        @elseif(in_array(strtolower($item->status), ['sedang dipinjam', 'sedang_dipinjam']))
                                            <span class="ds-setting">SEDANG DIPINJAM</span>
                                        @elseif(strtolower($item->status) === 'sedang dipakai')
                                            <span class="ds-setting">SEDANG DIPAKAI</span>
                                        @else
                                            <span class="ds-setting">{{ strtoupper($item->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($isLogistik)
                                        {{-- Logistics: Toggle status + Edit + Hapus --}}
                                        <div class="d-inline-flex gap-1 align-items-center">
                                            {{-- Toggle Status Ketersediaan --}}
                                            <form action="{{ route('equipments.toggle-status', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="pd-setting-ed"
                                                    title="{{ in_array(strtolower($item->status), ['tersedia']) ? 'Ubah status ke Sedang Dipinjam' : 'Ubah status ke Tersedia' }}"
                                                    style="font-size:11px; padding: 4px 8px !important;">
                                                    <i class="bi bi-arrow-left-right me-1"></i>
                                                    <span>{{ in_array(strtolower($item->status), ['tersedia']) ? 'Pinjam' : 'Tersedia' }}</span>
                                                </button>
                                            </form>
                                            {{-- Edit Data --}}
                                            <a href="{{ route('equipments.edit', $item->id) }}" class="pd-setting-ed" title="Edit Data Alat Uji" style="font-size:11px; padding: 4px 8px !important;">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            {{-- Hapus --}}
                                            <form action="{{ route('equipments.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-po" style="padding: 4px 8px !important;" title="Hapus Alat">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        {{-- Non-Logistics: Read-only label --}}
                                        <span class="font-mono-tech" style="font-size:10.5px; color:#9CA3AF; letter-spacing:.5px;">READ ONLY</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 font-mono-tech text-muted" style="font-size:12px;">
                                        [ BELUM ADA DATA PERALATAN TERDAFTAR ]
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($equipments->hasPages())
                    <div class="custom-pagination mt-4 d-flex justify-content-end">
                        {{ $equipments->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
