@extends('layouts.app')
@section('title', 'Edit Pelanggan')
@push('styles')
<style>
    .premium-form-shell {
        max-width: 860px;
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
            <div class="premium-page-kicker"><i class="bi bi-people-fill"></i> Edit</div>
            <h1>Edit Pelanggan</h1>
        </div>
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    <div class="card premium-form-card"><div class="card-body">
        <form method="POST" action="{{ route('admin.pelanggan.update', $item) }}">
            @csrf @method('PUT')
            <div class="premium-form-grid">
                <div class="mb-0"><label class="form-label">Nama</label><input name="name" class="form-control" value="{{ old('name', $item?->user->name) }}" required></div>
                <div class="mb-0"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $item?->user->email) }}" required></div>
                <div class="mb-0"><label class="form-label">Password (kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-control"></div>
                <div class="mb-0"><label class="form-label">No. HP</label><input name="no_hp" class="form-control" value="{{ old('no_hp', $item?->no_hp) }}" required></div>
                <div class="mb-0"><label class="form-label">Alamat</label><textarea name="alamat" rows="3" class="form-control" required>{{ old('alamat', $item?->alamat) }}</textarea></div>
            </div>
            <div class="d-flex gap-2 mt-4"><button class="btn btn-accent">Simpan</button><a href="{{ route('admin.pelanggan.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </form>
    </div></div>
</div>
@endsection
