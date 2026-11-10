@extends('layouts.app')
@section('title', 'Layanan')
@push('styles')
<style>
    .list-shell { max-width: 1200px; margin: 0 auto; }
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
            <div class="list-kicker"><i class="bi bi-wrench-adjustable"></i> Data</div>
            <h1>Data Layanan</h1>
        </div>
        <a href="{{ route('admin.layanan.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> Tambah Layanan</a>
    </div>

    <div class="glass-card">
        <div class="table-wrap">
            <table class="table align-middle mb-0">
                <thead><tr><th>Nama Layanan</th><th class="text-end">Harga</th><th class="text-center">Dipakai</th><th></th></tr></thead>
                <tbody>
                @forelse($items as $l)
                    <tr>
                        <td>{{ $l->nama_layanan }}</td>
                        <td class="text-end">Rp {{ number_format($l->harga, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $l->detail_count }}×</td>
                        <td class="text-end action-cell">
                            <a href="{{ route('admin.layanan.edit', $l) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form class="d-inline" method="POST" action="{{ route('admin.layanan.destroy', $l) }}" onsubmit="return confirm('Hapus layanan ini?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada layanan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
