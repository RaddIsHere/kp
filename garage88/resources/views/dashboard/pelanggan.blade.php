@extends('layouts.app')
@section('title', 'Dashboard')
@push('styles')
<style>
    .customer-dashboard { max-width: 1280px; margin: 0 auto; }

    .customer-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.1rem;
    }

    .customer-kicker,
    .book-overline,
    .orders-kicker { margin: 0 0 .35rem; color: #63748b; font-size: .68rem; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
    .customer-heading h1 { margin: 0; color: #13253f; font-size: 1.9rem; }

    .service-book {
        overflow: hidden;
        margin-bottom: 1.2rem;
        border: 1px solid #d1dce9;
        border-left: 5px solid #3e78bc;
        border-radius: 16px;
        background: linear-gradient(115deg, #f8fafc, #e5edf6 68%, #d9e3ef);
        box-shadow: 0 14px 30px rgba(23, 43, 68, .08);
    }

    .service-book-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem 1.4rem;
    }

    .book-overline { color: #516a87; }
    .service-book-copy h2 { margin: 0 0 .4rem; color: #17283e; font-size: 1.35rem; font-weight: 750; }
    .service-book-copy p { max-width: 480px; margin: 0; color: #5c6f85; font-size: .86rem; line-height: 1.55; }

    .active-count {
        display: flex;
        align-items: center;
        gap: .7rem;
        padding-left: 1rem;
        border-left: 1px solid #bbc9d9;
    }

    .active-count strong { color: #1e4f83; font-size: 2.4rem; line-height: 1; }
    .active-count span { max-width: 85px; color: #516a87; font-size: .66rem; font-weight: 800; letter-spacing: .08em; line-height: 1.4; text-transform: uppercase; }

    .customer-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border-top: 1px solid #cbd6e3;
        background: rgba(255,255,255,.4);
    }

    .customer-stat { display: flex; align-items: center; gap: .65rem; min-width: 0; padding: .8rem 1rem; }
    .customer-stat + .customer-stat { border-left: 1px solid #cbd6e3; }
    .customer-stat i { color: #4773a5; font-size: .95rem; }
    .customer-stat span { display: block; color: #64768b; font-size: .67rem; }
    .customer-stat strong { display: block; color: #1a2e46; font-size: 1.05rem; line-height: 1.2; }

    .orders-panel {
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, .38);
        border-radius: 16px;
        background: linear-gradient(145deg, rgba(255,255,255,.76), rgba(233,240,249,.88));
        box-shadow: 0 14px 30px rgba(15, 23, 42, .07);
    }

    .orders-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.2rem; border-bottom: 1px solid #dbe3ed; }
    .orders-kicker { margin-bottom: .2rem; color: #697b92; }
    .orders-header h2 { margin: 0; color: #17283e; font-size: 1.05rem; font-weight: 750; }

    .order-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin: 0 1.2rem;
        padding: 1rem 0;
        border-top: 1px solid rgba(148,163,184,0.35);
        text-decoration: none;
        color: inherit;
    }

    .orders-header + .order-item { border-top: 0; }
    .order-main { min-width: 0; }
    .order-title { color: #20344d; font-weight: 700; }
    .order-meta { margin-top: .25rem; color: #64768b; font-size: .78rem; line-height: 1.5; }
    .order-side { display: flex; align-items: center; gap: .8rem; flex-shrink: 0; }
    .order-arrow { color: #557aa8; transition: transform .2s ease; }
    .order-item:hover .order-arrow { transform: translateX(3px); }

    .orders-empty {
        display: grid;
        justify-items: center;
        gap: .5rem;
        padding: 2.25rem 1rem;
        text-align: center;
    }

    .orders-empty i { color: #557aa8; font-size: 1.5rem; }
    .orders-empty strong { color: #25364d; font-size: .95rem; }
    .orders-empty span { color: #66778d; font-size: .82rem; }

    @media (max-width: 767.98px) {
        .customer-heading { align-items: flex-start; flex-direction: column; }
        .customer-heading h1 { font-size: 1.55rem; }
        .service-book-main { align-items: flex-start; flex-direction: column; padding: 1rem; }
        .active-count { min-width: 0; padding-left: .75rem; }
        .active-count strong { font-size: 2rem; }
        .customer-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .customer-stat { padding: .7rem; }
        .customer-stat:nth-child(3) { border-left: 0; border-top: 1px solid #cbd6e3; }
        .customer-stat:nth-child(4) { border-top: 1px solid #cbd6e3; }
        .orders-header { align-items: flex-start; flex-direction: column; }
        .order-item { align-items: flex-start; flex-direction: column; margin: 0 .9rem; }
        .order-side { width: 100%; justify-content: space-between; }
    }
</style>
@endpush
@section('content')
<div class="customer-dashboard">
<div class="customer-heading">
    <div>
        <p class="customer-kicker">Garage88 / Service book</p>
        <h1>Halo, {{ auth()->user()->name }}</h1>
    </div>
    <a href="{{ route('pelanggan.ajukan') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> Ajukan Layanan</a>
</div>

<section class="service-book" aria-labelledby="service-book-title">
    <div class="service-book-main">
        <div class="service-book-copy">
            <p class="book-overline">Catatan layanan pribadi</p>
            <h2 id="service-book-title">Riwayat motor Anda, tertata.</h2>
            <p>Pantau pekerjaan yang berjalan dan simpan semua informasi layanan di satu tempat.</p>
        </div>
        <div class="active-count">
            <strong>{{ $aktif->count() }}</strong>
            <span>Pesanan aktif</span>
        </div>
    </div>
    <div class="customer-stats" aria-label="Ringkasan akun">
        <div class="customer-stat"><i class="bi bi-bicycle" aria-hidden="true"></i><div><span>Motor terdaftar</span><strong>{{ $jumlahMotor }}</strong></div></div>
        <div class="customer-stat"><i class="bi bi-receipt" aria-hidden="true"></i><div><span>Total pesanan</span><strong>{{ $totalPesanan }}</strong></div></div>
        <div class="customer-stat"><i class="bi bi-calendar-check" aria-hidden="true"></i><div><span>Dengan estimasi</span><strong>{{ $aktif->filter(fn ($p) => $p->estimasi_selesai)->count() }}</strong></div></div>
        <div class="customer-stat"><i class="bi bi-check2-circle" aria-hidden="true"></i><div><span>Dalam proses</span><strong>{{ $aktif->count() }}</strong></div></div>
    </div>
</section>

<section class="orders-panel">
    <div class="orders-header">
        <div>
            <p class="orders-kicker">Service records / 01</p>
            <h2>Pesanan aktif</h2>
        </div>
        <a href="{{ route('pelanggan.riwayat') }}" class="btn btn-sm btn-outline-accent"><i class="bi bi-journal-text"></i> Lihat riwayat</a>
    </div>
    @forelse($aktif as $p)
        <a href="{{ route('pelanggan.detail', $p) }}" class="order-item">
            <div class="order-main">
                <div class="order-title">#{{ $p->id }} · {{ $p->motor->merk }} {{ $p->motor->tipe }}</div>
                <div class="order-meta">Plat: {{ $p->motor->plat_nomor ?? '-' }} · {{ $p->bagian_direpaint ?? 'Repaint umum' }}</div>
            </div>
            <div class="order-side">
                <div class="d-flex flex-column align-items-md-end gap-2">
                    <x-status-badge :status="$p->status" />
                    @if($p->estimasi_selesai)
                        <span class="small text-secondary">Estimasi: {{ $p->estimasi_selesai->translatedFormat('d F Y') }}</span>
                    @endif
                </div>
                <i class="bi bi-arrow-right order-arrow" aria-hidden="true"></i>
            </div>
        </a>
    @empty
        <div class="orders-empty"><i class="bi bi-journal-plus"></i><strong>Belum ada pesanan aktif</strong><span>Ajukan layanan untuk mulai mencatat servis motor Anda.</span><a href="{{ route('pelanggan.ajukan') }}" class="btn btn-sm btn-accent mt-1">Ajukan layanan</a></div>
    @endforelse
</section>
@endsection
