@extends('layouts.app')
@section('title', 'Dashboard Admin')
@push('styles')
<style>
    .admin-dashboard {
        max-width: 1500px;
        margin: 0 auto;
    }

    .admin-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.1rem;
    }

    .admin-heading h1 {
        margin: 0;
        font-size: 1.9rem;
        color: #13253f;
    }

    .admin-kicker,
    .console-overline,
    .dispatch-kicker {
        margin: 0 0 .35rem;
        color: #63748b;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .13em;
        text-transform: uppercase;
    }

    .admin-heading .badge-soft {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem .75rem;
        border-radius: 999px;
        background: #e8eef7;
        border: 1px solid #d5deeb;
        color: #40536d;
        font-weight: 700;
        font-size: .69rem;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .workshop-console {
        position: relative;
        overflow: hidden;
        margin-bottom: 1rem;
        padding: 1.35rem 1.5rem 0;
        border: 1px solid rgba(148, 163, 184, .4);
        border-radius: 18px;
        color: #f7f9fc;
        background: linear-gradient(120deg, #111d31 0%, #1b3150 72%, #243f60 100%);
        box-shadow: 0 18px 34px rgba(15, 23, 42, .14);
    }

    .console-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding-bottom: .8rem;
        border-bottom: 1px solid rgba(223, 233, 247, .17);
    }

    .console-overline {
        margin: 0;
        color: #bac8da;
    }

    .console-mark {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        color: #d8e2f0;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .console-mark i { color: #8bd0ac; }

    .console-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        padding: 1.25rem 0 1.4rem;
    }

    .console-copy h2 {
        margin: 0 0 .4rem;
        color: #fff;
        font-size: 1.45rem;
        font-weight: 750;
    }

    .console-copy p {
        max-width: 420px;
        margin: 0;
        color: #bac8da;
        font-size: .88rem;
        line-height: 1.55;
    }

    .console-focus {
        display: flex;
        align-items: center;
        gap: .8rem;
        min-width: 190px;
        padding-left: 1rem;
        border-left: 2px solid #e8b65f;
    }

    .console-focus strong { color: #fff; font-size: 2.65rem; line-height: 1; }
    .console-focus span { max-width: 85px; color: #d3dce8; font-size: .66rem; font-weight: 750; letter-spacing: .1em; line-height: 1.45; text-transform: uppercase; }

    .console-stages {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin: 0 -1.5rem;
        border-top: 1px solid rgba(223, 233, 247, .17);
        background: rgba(7, 15, 27, .18);
    }

    .console-stage {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        min-width: 0;
        padding: .9rem 1.5rem;
    }

    .console-stage + .console-stage { border-left: 1px solid rgba(223, 233, 247, .17); }
    .console-stage span { color: #c4d0df; font-size: .76rem; }
    .console-stage strong { font-size: 1.25rem; }
    .console-stage.waiting strong { color: #f0c46e; }
    .console-stage.working strong { color: #9cc5ff; }
    .console-stage.completed strong { color: #8bd0ac; }

    .registry-strip {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-bottom: 1.2rem;
        overflow: hidden;
        border: 1px solid #d7e0eb;
        border-radius: 14px;
        background: rgba(255, 255, 255, .54);
    }

    .registry-item {
        display: flex;
        align-items: center;
        gap: .85rem;
        min-width: 0;
        padding: .85rem 1rem;
    }

    .registry-item + .registry-item { border-left: 1px solid #d7e0eb; }
    .registry-index { color: #8291a5; font-size: .65rem; font-weight: 800; letter-spacing: .08em; }
    .registry-item i { color: #426ea9; font-size: 1rem; }
    .registry-label { display: block; color: #62738a; font-size: .7rem; }
    .registry-item strong { display: block; color: #17283e; font-size: 1.15rem; line-height: 1.2; }

    .dispatch-panel {
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, .38);
        border-radius: 16px;
        background: linear-gradient(145deg, rgba(255,255,255,.76), rgba(233,240,249,.88));
        box-shadow: 0 14px 30px rgba(15, 23, 42, .07);
    }

    .dispatch-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #dbe3ed;
    }

    .dispatch-kicker { margin-bottom: .2rem; color: #697b92; }
    .dispatch-header h2 { margin: 0; color: #17283e; font-size: 1.05rem; font-weight: 750; }

    .dispatch-empty {
        display: grid;
        justify-items: center;
        gap: .5rem;
        padding: 2.25rem 1rem;
        text-align: center;
    }

    .dispatch-empty i { color: #557aa8; font-size: 1.5rem; }
    .dispatch-empty strong { color: #25364d; font-size: .95rem; }
    .dispatch-empty span { color: #66778d; font-size: .82rem; }

    @media (max-width: 767.98px) {
        .admin-heading { align-items: flex-start; }
        .admin-heading h1 { font-size: 1.55rem; }
        .workshop-console { padding: 1rem 1rem 0; }
        .console-main { align-items: flex-start; flex-direction: column; gap: 1rem; }
        .console-focus { min-width: 0; padding-left: .75rem; }
        .console-focus strong { font-size: 2rem; }
        .console-stages { margin: 0 -1rem; }
        .console-stage { align-items: flex-start; flex-direction: column-reverse; gap: .25rem; padding: .7rem .8rem; }
        .console-stage span { font-size: .66rem; }
        .registry-item { align-items: flex-start; flex-direction: column; gap: .35rem; padding: .7rem; }
        .registry-index { display: none; }
        .dispatch-header { align-items: flex-start; flex-direction: column; }
    }
</style>
@endpush
@section('content')
<div class="admin-dashboard">
<div class="admin-heading">
    <div>
        <p class="admin-kicker">Garage88 / Ruang kendali</p>
        <h1>Dashboard Admin</h1>
    </div>
    <span class="badge-soft"><i class="bi bi-shield-check"></i> Admin Panel</span>
</div>

<section class="workshop-console" aria-labelledby="workshop-console-title">
    <div class="console-topline">
        <p class="console-overline">Workshop / Operations</p>
        <span class="console-mark"><i class="bi bi-circle-fill"></i> Ringkasan sistem</span>
    </div>
    <div class="console-main">
        <div class="console-copy">
            <h2 id="workshop-console-title">Alur kerja bengkel</h2>
            <p>Pantau antrean dan pekerjaan yang sedang berjalan dari satu ruang kendali.</p>
        </div>
        <div class="console-focus">
            <strong>{{ $diproses }}</strong>
            <span>Sedang dikerjakan</span>
        </div>
    </div>
    <div class="console-stages" aria-label="Status pesanan">
        <div class="console-stage waiting"><span>Menunggu</span><strong>{{ $menunggu }}</strong></div>
        <div class="console-stage working"><span>Dikerjakan</span><strong>{{ $diproses }}</strong></div>
        <div class="console-stage completed"><span>Selesai</span><strong>{{ $selesai }}</strong></div>
    </div>
</section>

<section class="registry-strip" aria-label="Data bengkel">
    @foreach([['Pelanggan', $jumlahPelanggan, 'bi-people'], ['Motor', $jumlahMotor, 'bi-bicycle'], ['Layanan', $jumlahLayanan, 'bi-wrench-adjustable']] as $index => $item)
        <div class="registry-item">
            <span class="registry-index">0{{ $index + 1 }}</span>
            <i class="bi {{ $item[2] }}" aria-hidden="true"></i>
            <div><span class="registry-label">{{ $item[0] }}</span><strong>{{ $item[1] }}</strong></div>
        </div>
    @endforeach
</section>

<section class="dispatch-panel">
    <div class="dispatch-header">
        <div>
            <p class="dispatch-kicker">Dispatch log / 01</p>
            <h2>Pesanan terbaru</h2>
        </div>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-outline-accent"><i class="bi bi-arrow-up-right"></i> Semua pesanan</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>#</th><th>Pelanggan</th><th>Motor</th><th>Tanggal</th><th>Status</th><th class="text-end">Total</th></tr></thead>
            <tbody>
            @forelse($terbaru as $p)
                <tr>
                    <td><a class="text-accent" href="{{ route('admin.pesanan.show', $p) }}">#{{ $p->id }}</a></td>
                    <td>{{ $p->pelanggan->user->name }}</td>
                    <td>{{ $p->motor->merk }} {{ $p->motor->tipe }}</td>
                    <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                    <td><x-status-badge :status="$p->status" /></td>
                    <td class="text-end">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="dispatch-empty"><i class="bi bi-inbox"></i><strong>Belum ada pesanan</strong><span>Pesanan baru akan muncul di sini.</span></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
</div>
@endsection
