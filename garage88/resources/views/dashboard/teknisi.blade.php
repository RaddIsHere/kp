@extends('layouts.app')
@section('title', 'Dashboard Teknisi')
@push('styles')
<style>
    .technician-dashboard { max-width: 1500px; margin: 0 auto; }

    .technician-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.1rem;
    }

    .technician-heading h1 { margin: 0; color: #13253f; font-size: 1.9rem; }

    .tech-kicker,
    .workbench-overline,
    .queue-kicker {
        margin: 0 0 .35rem;
        color: #63748b;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .13em;
        text-transform: uppercase;
    }

    .technician-heading .badge-soft {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .5rem .75rem;
        border-radius: 999px;
        border: 1px solid #d5deeb;
        background: #e8eef7;
        color: #40536d;
        font-weight: 700;
        font-size: .68rem;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .workbench {
        overflow: hidden;
        margin-bottom: 1.2rem;
        border: 1px solid rgba(148, 163, 184, .4);
        border-radius: 18px;
        color: #f7f9fc;
        background: linear-gradient(120deg, #101c2d, #1c3553 74%, #294668);
        box-shadow: 0 18px 34px rgba(15, 23, 42, .14);
    }

    .workbench-topline,
    .queue-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .workbench-topline { padding: 1rem 1.25rem; border-bottom: 1px solid rgba(223,233,247,.17); }
    .workbench-overline { margin: 0; color: #bac8da; }
    .bench-status { display: inline-flex; align-items: center; gap: .45rem; color: #cbd8e7; font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .bench-status i { color: #8bd0ac; font-size: .55rem; }

    .workbench-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        padding: 1.35rem 1.5rem;
    }

    .workbench-copy h2 { margin: 0 0 .4rem; color: #fff; font-size: 1.4rem; font-weight: 750; }
    .workbench-copy p { max-width: 420px; margin: 0; color: #bac8da; font-size: .87rem; line-height: 1.55; }

    .workbench-focus {
        display: flex;
        align-items: center;
        gap: .85rem;
        min-width: 190px;
        padding-left: 1rem;
        border-left: 2px solid #8bd0ac;
    }

    .workbench-focus strong { color: #fff; font-size: 2.6rem; line-height: 1; }
    .workbench-focus span { max-width: 90px; color: #cbd8e7; font-size: .66rem; font-weight: 750; letter-spacing: .1em; line-height: 1.4; text-transform: uppercase; }

    .workbench-foot {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: .8rem 1.5rem;
        border-top: 1px solid rgba(223,233,247,.17);
        background: rgba(7, 15, 27, .18);
    }

    .workbench-foot span { color: #c4d0df; font-size: .76rem; }
    .workbench-foot strong { margin-left: .35rem; color: #8bd0ac; }
    .workbench-foot .finished strong { color: #f0c46e; }

    .queue-panel {
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, .38);
        border-radius: 16px;
        background: linear-gradient(145deg, rgba(255,255,255,.76), rgba(233,240,249,.88));
        box-shadow: 0 14px 30px rgba(15, 23, 42, .07);
    }

    .queue-header { padding: 1rem 1.2rem; border-bottom: 1px solid #dbe3ed; }
    .queue-kicker { margin-bottom: .2rem; color: #697b92; }
    .queue-header h2 { margin: 0; color: #17283e; font-size: 1.05rem; font-weight: 750; }

    .queue-empty {
        display: grid;
        justify-items: center;
        gap: .5rem;
        padding: 2.25rem 1rem;
        text-align: center;
    }

    .queue-empty i { color: #557aa8; font-size: 1.5rem; }
    .queue-empty strong { color: #25364d; font-size: .95rem; }
    .queue-empty span { color: #66778d; font-size: .82rem; }

    @media (max-width: 767.98px) {
        .technician-heading { align-items: flex-start; }
        .technician-heading h1 { font-size: 1.55rem; }
        .workbench-topline { padding: .85rem 1rem; }
        .workbench-main { align-items: flex-start; flex-direction: column; gap: 1rem; padding: 1.1rem 1rem; }
        .workbench-focus { min-width: 0; padding-left: .75rem; }
        .workbench-focus strong { font-size: 2rem; }
        .workbench-foot { padding: .75rem 1rem; }
        .queue-header { align-items: flex-start; flex-direction: column; }
    }
</style>
@endpush
@section('content')
<div class="technician-dashboard">
<div class="technician-heading">
    <div>
        <p class="tech-kicker">Garage88 / Technician bay</p>
        <h1>Dashboard Teknisi</h1>
    </div>
    <span class="badge-soft"><i class="bi bi-tools"></i> Teknisi</span>
</div>

<section class="workbench" aria-labelledby="workbench-title">
    <div class="workbench-topline">
        <p class="workbench-overline">Work order / technician view</p>
        <span class="bench-status"><i class="bi bi-circle-fill"></i> Area kerja</span>
    </div>
    <div class="workbench-main">
        <div class="workbench-copy">
            <h2 id="workbench-title">Fokus pengerjaan</h2>
            <p>Daftar tugas aktif dan pembaruan status pekerjaan bengkel.</p>
        </div>
        <div class="workbench-focus">
            <strong>{{ $jumlahAktif }}</strong>
            <span>Pekerjaan aktif</span>
        </div>
    </div>
    <div class="workbench-foot">
        <span>Dalam antrean<strong>{{ $jumlahAktif }}</strong></span>
        <span class="finished">Tuntas<strong>{{ $selesai }}</strong></span>
    </div>
</section>

<section class="queue-panel">
    <div class="queue-header">
        <div>
            <p class="queue-kicker">Bay queue / 01</p>
            <h2>Sedang dikerjakan</h2>
        </div>
        <a href="{{ route('teknisi.pekerjaan') }}" class="btn btn-sm btn-outline-accent"><i class="bi bi-arrow-up-right"></i> Daftar pekerjaan</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>#</th><th>Motor</th><th>Pelanggan</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($aktif as $p)
                <tr>
                    <td><span class="text-accent fw-semibold">#{{ $p->id }}</span></td>
                    <td>{{ $p->motor->merk }} {{ $p->motor->tipe }} · {{ $p->motor->warna }}</td>
                    <td>{{ $p->pelanggan->user->name }}</td>
                    <td><x-status-badge :status="$p->status" /></td>
                    <td class="text-end"><a class="btn btn-sm btn-accent" href="{{ route('teknisi.pekerjaan.show', $p) }}">Update</a></td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="queue-empty"><i class="bi bi-wrench-adjustable-circle"></i><strong>Belum ada tugas aktif</strong><span>Pekerjaan baru akan masuk ke antrean ini.</span><a href="{{ route('teknisi.pekerjaan') }}" class="btn btn-sm btn-outline-accent mt-1">Buka daftar pekerjaan</a></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
</div>
@endsection
