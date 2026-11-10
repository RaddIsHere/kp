@props(['status'])
@php
    $warna = match ($status) {
        'Menunggu Konfirmasi' => 'warning text-dark',
        'Dikonfirmasi' => 'info text-dark',
        'Selesai' => 'success',
        'Dibatalkan' => 'danger',
        default => 'primary',
    };
@endphp
<span class="badge text-bg-{{ $warna }}">{{ $status }}</span>
