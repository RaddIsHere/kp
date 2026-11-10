@extends('layouts.app')
@section('title', 'Status Pengerjaan')
@section('content')
<h1 class="h3 mb-3">Status Pengerjaan</h1>
@forelse($items as $p)
    <div class="card mb-3"><div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <div class="fw-semibold">#{{ $p->id }} · {{ $p->motor->merk }} {{ $p->motor->tipe }} <span class="text-secondary">({{ $p->motor->warna }})</span></div>
                <div class="text-secondary small">{{ $p->detail->pluck('layanan.nama_layanan')->join(', ') }}</div>
            </div>
            <x-status-badge :status="$p->status" />
        </div>
        <x-timeline :progress="$p->progress" />
    </div></div>
@empty
    <div class="card"><div class="card-body text-center text-secondary py-5">
        Tidak ada pesanan yang sedang dikerjakan.<br>
        <a href="{{ route('pelanggan.ajukan') }}" class="btn btn-accent mt-3">Ajukan Layanan</a>
    </div></div>
@endforelse
@endsection
