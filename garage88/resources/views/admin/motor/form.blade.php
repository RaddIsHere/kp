@extends('layouts.app')
@section('title', 'Edit Motor')
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

    @media (min-width: 768px) {
        .premium-form-grid.two-col {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endpush
@section('content')
<div class="premium-form-shell">
    <div class="premium-page-header">
        <div>
            <div class="premium-page-kicker"><i class="bi bi-bicycle"></i> Edit</div>
            <h1>Edit Motor</h1>
        </div>
        <a href="{{ route('admin.motor.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    <div class="card premium-form-card"><div class="card-body">
        <form method="POST" action="{{ route('admin.motor.update', $item) }}">
            @csrf @method('PUT')
            <div class="premium-form-grid">
                <div class="mb-0"><label class="form-label">Pemilik</label>
                    <select name="pelanggan_id" class="form-select" required>
                        <option value="">— Pilih pelanggan —</option>
                        @foreach($pelanggan as $p)
                            <option value="{{ $p->id }}" @selected(old('pelanggan_id', $item?->pelanggan_id) == $p->id)>{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="premium-form-grid two-col mt-3">
                <div class="mb-0"><label class="form-label">Plat Nomor</label><input name="plat_nomor" class="form-control" value="{{ old('plat_nomor', $item?->plat_nomor) }}" placeholder="B 1234 ABC"></div>
                <div class="mb-0"><label class="form-label">Merk</label><input name="merk" class="form-control" value="{{ old('merk', $item?->merk) }}" required></div>
                <div class="mb-0"><label class="form-label">Tipe</label><input name="tipe" class="form-control" value="{{ old('tipe', $item?->tipe) }}" required></div>
                <div class="mb-0"><label class="form-label">Tahun</label><input name="tahun" maxlength="4" class="form-control" value="{{ old('tahun', $item?->tahun) }}" required></div>
                <div class="mb-0"><label class="form-label">Warna</label><input name="warna" class="form-control" value="{{ old('warna', $item?->warna) }}" required></div>
            </div>
            <div class="d-flex gap-2 mt-4"><button class="btn btn-accent">Simpan</button><a href="{{ route('admin.motor.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </form>
    </div></div>
</div>
@endsection
