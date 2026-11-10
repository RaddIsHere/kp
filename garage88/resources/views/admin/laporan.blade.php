@extends('layouts.app')
@section('title', 'Laporan')
@push('styles')
<style>
    .report-actions {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .report-action-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        min-height: 44px;
        padding: .8rem 1.1rem;
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.4);
        font-weight: 700;
        line-height: 1;
        letter-spacing: .01em;
        white-space: nowrap;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, filter .2s ease;
        text-decoration: none;
    }

    .report-action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.02);
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.12);
    }

    .report-action-btn:focus-visible {
        outline: 3px solid rgba(47, 111, 232, 0.2);
        outline-offset: 2px;
    }

    .report-action-btn i {
        font-size: 1rem;
    }

    .report-action-btn.export-csv {
        background: linear-gradient(135deg, rgba(255,255,255,0.7), rgba(230,236,247,0.75));
        border-color: rgba(148, 163, 184, 0.42);
        color: #24364f;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.9), 0 16px 30px rgba(148, 163, 184, 0.14);
    }

    .report-action-btn.export-pdf {
        background: linear-gradient(135deg, #fdf2f2 0%, #f7d1d1 30%, #efb8b8 100%);
        border-color: rgba(220, 122, 122, 0.5);
        color: #7a2d2d;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.9), 0 16px 30px rgba(255, 140, 140, 0.2);
    }

    .report-action-btn.print-report {
        background: linear-gradient(135deg, #5d8dff 0%, #3e6fe9 30%, #274dc7 100%);
        border-color: rgba(130, 164, 255, 0.7);
        color: #fff;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.28), 0 18px 32px rgba(47, 111, 232, 0.28);
    }

    @media (max-width: 575.98px) {
        .report-actions {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
        }

        .report-action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h1 class="h3 mb-0">Laporan Pesanan</h1>
    <div class="report-actions">
        <a href="{{ route('admin.laporan.export', ['dari' => $dari, 'sampai' => $sampai, 'status' => $status, 'q' => $q]) }}" class="report-action-btn export-csv"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
        <a href="{{ route('admin.laporan.pdf', ['dari' => $dari, 'sampai' => $sampai, 'status' => $status, 'q' => $q]) }}" class="report-action-btn export-pdf" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
        <button onclick="window.print()" class="report-action-btn print-report"><i class="bi bi-printer"></i> Cetak Laporan</button>
    </div>
</div>
<form class="row g-2 mb-4 no-print" method="GET">
    <div class="col-md-3"><label class="form-label small">Dari</label><input type="date" name="dari" value="{{ $dari }}" class="form-control"></div>
    <div class="col-md-3"><label class="form-label small">Sampai</label><input type="date" name="sampai" value="{{ $sampai }}" class="form-control"></div>
    <div class="col-md-3"><label class="form-label small">Status</label>
        <select name="status" class="form-select"><option value="">Semua</option>
            @foreach($daftarStatus as $s)<option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>@endforeach
        </select></div>
    <div class="col-md-3"><label class="form-label small">Cari</label><input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Nama atau plat"></div>
    <div class="col-md-12 d-flex align-items-end justify-content-end"><button class="btn btn-outline-accent">Tampilkan</button></div>
</form>

<div class="card"><div class="card-body">
    <div class="text-center mb-4">
        <h2 class="h4 mb-0">GARAGE88</h2>
        <div class="text-secondary">Laporan Pelayanan Repaint</div>
        <div class="small">Periode {{ \Illuminate\Support\Carbon::parse($dari)->translatedFormat('d F Y') }} – {{ \Illuminate\Support\Carbon::parse($sampai)->translatedFormat('d F Y') }}</div>
    </div>
    <div class="table-responsive"><table class="table table-sm align-middle">
        <thead><tr><th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Motor</th><th>Layanan</th><th>Status</th><th>Pembayaran</th><th class="text-end">Total</th></tr></thead>
        <tbody>
        @forelse($items as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                <td>{{ $p->pelanggan->user->name }}</td>
                <td>{{ $p->motor->merk }} {{ $p->motor->tipe }}</td>
                <td class="small">{{ $p->detail->pluck('layanan.nama_layanan')->join(', ') }}</td>
                <td>{{ $p->status }}</td>
                <td>{{ $p->paymentStatusLabel() }}</td>
                <td class="text-end">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-secondary py-4">Tidak ada data pada periode ini.</td></tr>
        @endforelse
        </tbody>
        <tfoot>
            <tr><th colspan="7" class="text-end">Jumlah pesanan</th><th class="text-end">{{ $items->count() }}</th></tr>
            <tr><th colspan="7" class="text-end">Pendapatan (pembayaran lunas)</th><th class="text-end">Rp {{ number_format($pendapatan, 0, ',', '.') }}</th></tr>
        </tfoot>
    </table></div>
    <div class="small text-secondary">Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</div>
</div></div>
@endsection
