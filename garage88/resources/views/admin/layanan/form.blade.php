@extends('layouts.app')
@section('title', $item ? 'Edit Layanan' : 'Tambah Layanan')
@push('styles')
<style>
    .premium-form-shell {
        max-width: 760px;
        margin: 0 auto;
    }

    .premium-page-header {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .premium-page-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.7rem;
        border-radius: 999px;
        background: rgba(47,111,232,0.08);
        border: 1px solid rgba(47,111,232,0.12);
        color: #1d4fc0;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .premium-page-header h1 {
        margin: 0;
        font-size: clamp(1.7rem, 2vw, 2.3rem);
        color: #13253f;
    }

    .premium-form-card {
        padding: 0.35rem;
    }

    .premium-form-card .card-body {
        padding: 1.5rem;
    }

    .premium-form-grid {
        display: grid;
        gap: 1rem;
    }
</style>
@endpush
@section('content')
<div class="premium-form-shell">
    <div class="premium-page-header">
        <div>
            <div class="premium-page-kicker"><i class="bi bi-wrench-adjustable"></i> {{ $item ? 'Edit' : 'Tambah' }}</div>
            <h1>{{ $item ? 'Edit Layanan' : 'Tambah Layanan' }}</h1>
        </div>
        <a href="{{ route('admin.layanan.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    <div class="card premium-form-card"><div class="card-body">
        <form method="POST" action="{{ $item ? route('admin.layanan.update', $item) : route('admin.layanan.store') }}">
            @csrf @if($item) @method('PUT') @endif
            <div class="premium-form-grid">
                <div class="mb-0"><label class="form-label">Nama Layanan</label><input name="nama_layanan" class="form-control" value="{{ old('nama_layanan', $item?->nama_layanan) }}" required></div>
                <div class="mb-0"><label class="form-label">Harga (Rp)</label><input type="number" min="0" name="harga" class="form-control" value="{{ old('harga', $item ? (int) $item->harga : '') }}" required></div>
            </div>
            <div class="d-flex gap-2 mt-4"><button class="btn btn-accent">Simpan</button><a href="{{ route('admin.layanan.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </form>
    </div></div>
</div>
@endsection
