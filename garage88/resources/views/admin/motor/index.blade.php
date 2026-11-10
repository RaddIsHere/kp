@extends('layouts.app')
@section('title', 'Motor')
@push('styles')
<style>
    .list-shell { max-width: 1400px; margin: 0 auto; }
    .list-toolbar {
        display: flex; align-items: end; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .list-kicker {
        display: inline-flex; align-items: center; gap: 0.45rem;
        padding: 0.45rem 0.75rem; border-radius: 999px;
        background: rgba(47,111,232,0.08); border: 1px solid rgba(47,111,232,0.12);
        color: #1d4fc0; font-size: 0.72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        margin-bottom: 0.45rem;
    }
    .list-toolbar h1 {
        margin: 0; font-size: clamp(1.7rem, 2vw, 2.3rem); color: #13253f;
    }
    .search-panel {
        margin-bottom: 1rem; padding: 0.9rem 1rem; border-radius: 18px;
        background: linear-gradient(135deg, rgba(255,255,255,0.55), rgba(229,238,250,0.8));
        border: 1px solid rgba(148,163,184,0.35); box-shadow: 0 12px 24px rgba(15,23,42,0.04);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    }
    .glass-card {
        border-radius: 22px; overflow: hidden;
        background: linear-gradient(135deg, rgba(255,255,255,0.62), rgba(233,240,249,0.82));
        border: 1px solid rgba(148, 163, 184, 0.38);
        box-shadow: 0 18px 34px rgba(15,23,42,0.08);
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    }
    .table-wrap { overflow-x: auto; }
    .table thead th { background: rgba(255,255,255,0.28); }
    .action-cell { white-space: nowrap; }
    .btn-outline-danger { border-color: rgba(220, 53, 69, 0.26); }
</style>
@endpush
@section('content')
<div class="list-shell">
    <div class="list-toolbar">
        <div>
            <div class="list-kicker"><i class="bi bi-bicycle"></i> Data</div>
            <h1>Data Motor</h1>
        </div>
    </div>

    <form class="search-panel" method="GET">
        <div class="input-group">
            <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari plat nomor, merk, tipe, warna, atau nama pemilik...">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <div class="glass-card">
        <div class="table-wrap">
            <table class="table align-middle mb-0">
                <thead><tr><th>Pemilik</th><th>Plat</th><th>Merk</th><th>Tipe</th><th>Tahun</th><th>Warna</th><th></th></tr></thead>
                <tbody>
                @forelse($items as $m)
                    <tr>
                        <td>{{ $m->pelanggan->user->name }}</td><td>{{ $m->plat_nomor ?? '-' }}</td><td>{{ $m->merk }}</td><td>{{ $m->tipe }}</td><td>{{ $m->tahun }}</td><td>{{ $m->warna }}</td>
                        <td class="text-end action-cell">
                            <a href="{{ route('admin.motor.edit', $m) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form class="d-inline" method="POST" action="{{ route('admin.motor.destroy', $m) }}" onsubmit="return confirm('Hapus motor ini beserta pesanannya?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data motor.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
