@extends('layouts.app')
@section('title', 'Daftar Pekerjaan')
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
    .btn-group .btn { min-width: 100px; }
</style>
@endpush
@section('content')
<div class="list-shell">
    <div class="list-toolbar">
        <div>
            <div class="list-kicker"><i class="bi bi-tools"></i> Pekerjaan</div>
            <h1>Daftar Pekerjaan</h1>
        </div>
        <div class="btn-group">
            @foreach(['aktif' => 'Aktif', 'selesai' => 'Selesai', 'semua' => 'Semua'] as $k => $label)
                <a href="{{ route('teknisi.pekerjaan', ['tampil' => $k]) }}" class="btn btn-sm {{ $tampil === $k ? 'btn-accent' : 'btn-outline-secondary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="glass-card">
        <div class="table-wrap">
            <table class="table align-middle mb-0">
                <thead><tr><th>#</th><th>Motor</th><th>Layanan</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($items as $p)
                    <tr>
                        <td>#{{ $p->id }}</td>
                        <td>{{ $p->motor->merk }} {{ $p->motor->tipe }}<div class="text-secondary small">{{ $p->motor->warna }} · {{ $p->pelanggan->user->name }}</div></td>
                        <td class="small">{{ $p->detail->pluck('layanan.nama_layanan')->join(', ') }}</td>
                        <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                        <td><x-status-badge :status="$p->status" /></td>
                        <td class="text-end action-cell"><a href="{{ route('teknisi.pekerjaan.show', $p) }}" class="btn btn-sm btn-accent">Update Status</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Tidak ada pekerjaan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
