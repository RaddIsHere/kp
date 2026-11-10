@extends('layouts.app')
@section('title', 'Notifikasi')
@push('styles')
<style>
    .notification-page { max-width: 980px; margin: 0 auto; }
    .notification-heading { display: flex; align-items: end; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
    .notification-kicker { margin: 0 0 .35rem; color: #63748b; font-size: .68rem; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
    .notification-heading h1 { margin: 0; color: #13253f; font-size: 1.9rem; }
    .notification-list { overflow: hidden; border: 1px solid rgba(148, 163, 184, .4); border-radius: 16px; background: rgba(255, 255, 255, .72); box-shadow: 0 14px 30px rgba(15, 23, 42, .07); }
    .notification-item { position: relative; padding: 1.15rem 1.25rem; border-bottom: 1px solid #dbe3ed; }
    .notification-item:last-child { border-bottom: 0; }
    .notification-item.is-unread { background: linear-gradient(105deg, rgba(47,111,232,.08), rgba(255,255,255,.4)); }
    .notification-item.is-unread::before { position: absolute; inset: 0 auto 0 0; width: 3px; background: #2f6fe8; content: ""; }
    .notification-item-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: .4rem; }
    .notification-source { color: #55708e; font-size: .65rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .notification-date { color: #76869a; font-size: .72rem; white-space: nowrap; }
    .notification-item h2 { margin: 0 0 .35rem; color: #1c2d43; font-size: 1rem; font-weight: 750; }
    .notification-message { max-width: 720px; margin: 0; color: #53657b; font-size: .88rem; line-height: 1.6; }
    .notification-risk { display: inline-flex; align-items: center; gap: .35rem; margin-top: .65rem; color: #8a5a12; font-size: .75rem; font-weight: 700; }
    .notification-item form { margin-top: .8rem; }
    .notification-empty { display: grid; justify-items: center; gap: .55rem; padding: 3rem 1rem; color: #64768b; text-align: center; }
    .notification-empty i { color: #557aa8; font-size: 1.8rem; }
    .notification-empty strong { color: #25364d; }
    .notification-pagination { padding: 1rem 1.2rem; border-top: 1px solid #dbe3ed; }

    @media (max-width: 575.98px) {
        .notification-heading { align-items: flex-start; flex-direction: column; }
        .notification-heading h1 { font-size: 1.6rem; }
        .notification-item { padding: 1rem; }
        .notification-item-head { align-items: flex-start; flex-direction: column; gap: .25rem; }
    }
</style>
@endpush
@section('content')
<div class="notification-page">
    <header class="notification-heading">
        <div>
            <p class="notification-kicker">Garage88 / Pusat informasi</p>
            <h1>Notifikasi</h1>
        </div>
        @if(auth()->user()->unreadNotifications()->exists())
            <form method="POST" action="{{ route('pelanggan.notifikasi.baca-semua') }}">@csrf
                <button class="btn btn-sm btn-outline-accent"><i class="bi bi-check2-all me-1"></i> Tandai semua dibaca</button>
            </form>
        @endif
    </header>

    <section class="notification-list" aria-label="Daftar notifikasi">
        @forelse($notifications as $notification)
            @php($data = $notification->data)
            <article class="notification-item {{ $notification->read_at ? '' : 'is-unread' }}">
                <div class="notification-item-head">
                    <span class="notification-source">{{ ($data['ai_generated'] ?? false) ? 'Ringkasan AI Garage88' : 'Pembaruan Garage88' }}</span>
                    <time class="notification-date" datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->translatedFormat('d M Y, H:i') }}</time>
                </div>
                <h2>{{ $data['title'] ?? 'Pembaruan pesanan' }} · {{ $data['status'] ?? '' }}</h2>
                <p class="notification-message">{{ $data['message'] ?? '' }}</p>
                @if($data['delay_risk'] ?? false)
                    <span class="notification-risk"><i class="bi bi-exclamation-triangle-fill"></i> Estimasi perlu perhatian</span>
                @endif
                @if(! $notification->read_at && ! empty($data['pesanan_id']))
                    <form method="POST" action="{{ route('pelanggan.notifikasi.baca', $notification->id) }}">@csrf
                        <button class="btn btn-sm btn-outline-accent">Lihat pesanan <i class="bi bi-arrow-up-right ms-1"></i></button>
                    </form>
                @endif
            </article>
        @empty
            <div class="notification-empty">
                <i class="bi bi-bell-slash"></i>
                <strong>Belum ada notifikasi</strong>
                <span>Pembaruan status pesanan akan muncul di sini.</span>
            </div>
        @endforelse
        @if($notifications->hasPages())
            <div class="notification-pagination">{{ $notifications->links('pagination::bootstrap-5') }}</div>
        @endif
    </section>
</div>
@endsection