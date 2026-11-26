@php
    $status = $status ?? 'Draft';
@endphp

@switch($status)
    @case('Draft')
        <span class="badge font-mono-tech px-2 py-1" style="background:#FEF3C7; color:#92400E; border:1px solid #F59E0B; font-size:10.5px;">
            DRAFT (MENUNGGU LOGISTIK)
        </span>
        @break

    @case('Verifikasi Logistik')
        <span class="badge font-mono-tech px-2 py-1" style="background:#DBEAFE; color:#1E40AF; border:1px solid #60A5FA; font-size:10.5px;">
            VERIFIKASI LOGISTIK (MENUNGGU KEUANGAN)
        </span>
        @break

    @case('Verifikasi Keuangan')
    @case('Persetujuan Direktur')
        <span class="badge font-mono-tech px-2 py-1" style="background:#EDE9FE; color:#5B21B6; border:1px solid #A78BFA; font-size:10.5px;">
            VERIFIKASI KEUANGAN (MENUNGGU DIREKTUR)
        </span>
        @break

    @case('Terbit')
        <span class="pd-setting">TERBIT</span>
        @break

    @case('Dibatalkan')
        <span class="ds-setting">DIBATALKAN</span>
        @break

    @default
        <span class="badge bg-secondary font-mono-tech" style="font-size:10.5px;">{{ strtoupper($status) }}</span>
@endswitch
