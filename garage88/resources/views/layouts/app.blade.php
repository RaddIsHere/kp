<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Garage88') — Garage88</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --g88-accent: #2f6fe8;
            --g88-accent-strong: #1d4fc0;
            --g88-bg: #e8edf3;
            --g88-panel: #f5f7fb;
            --g88-card: #ffffff;
            --g88-line: #c7d2e0;
            --g88-text: #152033;
            --g88-muted: #5c6f85;
            --g88-silver: #cfd8e3;
            --g88-navbar: #0f172a;
        }

        body {
            background: linear-gradient(180deg, #edf3fa 0%, #dfe9f5 100%);
            color: var(--g88-text);
        }

        .navbar-g88 {
            background: linear-gradient(135deg, var(--g88-navbar) 0%, #1c2d4d 100%);
            border-bottom: 1px solid var(--g88-line);
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.12);
        }

        .navbar-nav {
            align-items: center;
        }

        .navbar-g88 .navbar-toggler-icon {
            filter: invert(1);
        }

        .navbar-nav .nav-link {
            padding: .55rem .8rem;
            border-radius: 10px;
            transition: all .2s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background: rgba(157, 184, 255, 0.12);
        }

        .notification-action {
            position: relative;
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            border: 1px solid rgba(191, 209, 255, .28);
            border-radius: 12px;
            color: #e5edfa;
            text-decoration: none;
            transition: background-color .2s ease, border-color .2s ease;
        }

        .notification-action:hover { border-color: rgba(191, 209, 255, .55); background: rgba(157, 184, 255, .12); color: #fff; }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            display: grid;
            min-width: 18px;
            height: 18px;
            place-items: center;
            padding: 0 4px;
            border: 2px solid #152743;
            border-radius: 999px;
            color: #fff;
            background: #d75050;
            font-size: .62rem;
            font-weight: 800;
            line-height: 1;
        }

        .brand {
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
        }

        .brand span {
            color: #dfe9f7;
        }

        .card {
            background: linear-gradient(135deg, rgba(255,255,255,0.7), rgba(229,237,247,0.82));
            border: 1px solid rgba(148, 163, 184, 0.4);
            border-radius: 22px;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: box-shadow .24s ease, border-color .24s ease;
        }

        .card:hover,
        .glass-card:hover {
            border-color: rgba(100, 145, 220, .48);
            box-shadow: 0 22px 42px rgba(15, 35, 68, .12);
        }

        .mini-stat,
        .service-card {
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .mini-stat:hover,
        .service-card:hover {
            transform: translateY(-3px);
        }

        .card-body {
            padding: 1.25rem;
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        .btn:active {
            transform: translateY(0) scale(.98);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--g88-accent) 0%, var(--g88-accent-strong) 100%);
            border-color: var(--g88-accent);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 12px 22px rgba(47, 111, 232, 0.22);
        }

        .btn-accent:hover {
            background: linear-gradient(135deg, var(--g88-accent-strong) 0%, #173d9c 100%);
            border-color: var(--g88-accent-strong);
            color: #fff;
        }

        .btn-outline-accent {
            border-color: rgba(47, 111, 232, 0.4);
            color: var(--g88-accent);
            background: rgba(255, 255, 255, 0.45);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
        }

        .btn-outline-accent:hover {
            background: var(--g88-accent);
            color: #fff;
        }

        .btn-outline-secondary {
            background: rgba(255, 255, 255, 0.5);
            border-color: rgba(148, 163, 184, 0.42);
            color: var(--g88-text);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
        }

        .text-accent {
            color: var(--g88-accent) !important;
        }

        .nav-link.active {
            color: #dfe9f7 !important;
            font-weight: 600;
        }

        .nav-link {
            color: #dfe8f5 !important;
        }

        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            color: var(--g88-accent-strong);
        }

        .table {
            --bs-table-bg: transparent;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th,
        .table td {
            border-color: rgba(148, 163, 184, 0.3);
            vertical-align: middle;
        }

        .table tbody tr {
            animation: app-row-in .38s ease-out both;
        }

        .table tbody tr:nth-child(2) { animation-delay: .04s; }
        .table tbody tr:nth-child(3) { animation-delay: .08s; }
        .table tbody tr:nth-child(4) { animation-delay: .12s; }
        .table tbody tr:nth-child(5) { animation-delay: .16s; }
        .table tbody tr:nth-child(6) { animation-delay: .2s; }

        .table thead th {
            background: rgba(255,255,255,0.28);
            color: #334155;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .form-control,
        .form-select,
        .form-check-input,
        .input-group-text {
            background: rgba(255,255,255,0.58);
            border: 1px solid rgba(148, 163, 184, 0.4);
            border-radius: 12px;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.03);
            color: var(--g88-text);
            transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255,255,255,0.82);
            border-color: rgba(47, 111, 232, 0.7);
            box-shadow: 0 0 0 0.2rem rgba(47, 111, 232, 0.12);
        }

        .timeline {
            list-style: none;
            padding-left: 0;
            margin: 0;
            border-left: 2px solid var(--g88-line);
            margin-left: .5rem;
        }

        .timeline li {
            position: relative;
            padding: 0 0 1.1rem 1.4rem;
        }

        .timeline li::before {
            content: "";
            position: absolute;
            left: -7px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--g88-silver);
        }

        .timeline li.timeline-current::before {
            background: var(--g88-accent);
            box-shadow: 0 0 0 4px rgba(47, 111, 232, 0.15);
        }

        .timeline-highlight::before {
            animation: timeline-pulse 1.15s ease-out both;
        }

        .timeline-current > .fw-semibold {
            color: var(--g88-accent-strong);
        }

        .timeline-highlight > .fw-semibold {
            animation: timeline-current-in .42s ease-out both;
        }

        .app-shell {
            display: block;
            min-height: calc(100vh - 76px);
        }

        .content-area {
            width: 100%;
            padding: 1.25rem 1.4rem 2rem;
            animation: app-page-in .45s ease-out both;
        }

        .auth-shell {
            min-height: calc(100vh - 180px);
            display: grid;
            place-items: center;
            padding: 2rem 0;
        }

        .auth-layout {
            display: grid;
            grid-template-columns: minmax(0, .88fr) minmax(0, 1.12fr);
            width: min(100%, 1020px);
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, .42);
            border-radius: 22px;
            background: rgba(255, 255, 255, .94);
            box-shadow: 0 24px 60px rgba(15, 35, 68, .16);
        }

        .auth-story {
            position: relative;
            isolation: isolate;
            display: flex;
            min-height: 570px;
            flex-direction: column;
            justify-content: space-between;
            gap: 2rem;
            overflow: hidden;
            padding: 2.25rem;
            color: #fff;
            background: #10213f;
            animation: auth-enter .7s cubic-bezier(.2, .7, .2, 1) both;
        }

        .auth-story::before {
            position: absolute;
            z-index: -1;
            inset: 0;
            content: "";
            background: linear-gradient(180deg, rgba(10, 24, 48, .52), rgba(10, 24, 48, .88)), url('{{ asset('images/hero-motor.svg') }}') center / cover;
            animation: auth-photo-drift 22s ease-in-out infinite alternate;
        }

        .auth-story-brand {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            font-size: .9rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            animation: auth-enter .7s .08s cubic-bezier(.2, .7, .2, 1) both;
        }

        .auth-story-mark {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .45);
            border-radius: 11px;
            background: rgba(255, 255, 255, .15);
            font-size: .85rem;
        }

        .auth-story-copy {
            max-width: 350px;
            animation: auth-enter .7s .16s cubic-bezier(.2, .7, .2, 1) both;
        }

        .auth-story-eyebrow,
        .auth-form-eyebrow {
            margin: 0 0 .75rem;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .auth-story-eyebrow { color: #c7dafb; }

        .auth-story-copy h2 {
            margin: 0 0 .9rem;
            font-size: 2.35rem;
            font-weight: 750;
            line-height: 1.08;
        }

        .auth-story-copy p:last-child {
            margin: 0;
            color: rgba(255, 255, 255, .82);
            line-height: 1.7;
        }

        .auth-story-foot {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, .24);
            color: rgba(255, 255, 255, .88);
            font-size: .82rem;
            animation: auth-enter .7s .24s cubic-bezier(.2, .7, .2, 1) both;
        }

        .auth-story-foot i { margin-right: .35rem; color: #c7dafb; }

        .auth-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            background: linear-gradient(155deg, #fff 0%, #f4f7fb 100%);
            animation: auth-enter .75s .12s cubic-bezier(.2, .7, .2, 1) both;
        }

        .auth-header { margin-bottom: 1.75rem; }

        .auth-form-eyebrow { color: var(--g88-accent-strong); }

        .auth-header h1 {
            margin: 0 0 .55rem;
            color: #13253f;
            font-size: 1.9rem;
            font-weight: 750;
        }

        .auth-header p:last-child {
            margin: 0;
            color: var(--g88-muted);
            line-height: 1.6;
        }

        .auth-form .form-label {
            color: #405067;
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .auth-form .form-control,
        .auth-form textarea {
            min-height: 48px;
            padding: .75rem .9rem;
            border-color: #cdd8ea;
            border-radius: 12px;
            background: rgba(255, 255, 255, .78);
        }

        .auth-form textarea { min-height: auto; }

        .auth-form .btn-accent {
            min-height: 48px;
            margin-top: .25rem;
            border-radius: 12px;
            font-weight: 700;
        }

        .auth-meta {
            margin: 1.25rem 0 0;
            padding-top: 1.1rem;
            border-top: 1px solid #dfe7f2;
            color: #53657b;
            font-size: .9rem;
        }

        @keyframes auth-enter {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes auth-photo-drift {
            from { transform: scale(1.04); }
            to { transform: scale(1.1); }
        }

        @keyframes app-page-in {
            from { opacity: 0; transform: translateY(9px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes app-row-in {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes timeline-current-in {
            from { opacity: 0; transform: translateX(-6px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes timeline-pulse {
            0% { box-shadow: 0 0 0 0 rgba(47, 111, 232, .38); }
            65% { box-shadow: 0 0 0 8px rgba(47, 111, 232, .04); }
            100% { box-shadow: 0 0 0 4px rgba(47, 111, 232, .15); }
        }

        @media (max-width: 767.98px) {
            .auth-shell { min-height: auto; padding: 1rem 0; }
            .auth-layout { grid-template-columns: 1fr; }
            .auth-story { min-height: 245px; gap: 1.25rem; padding: 1.5rem; }
            .auth-story-copy h2 { max-width: 360px; font-size: 1.7rem; }
            .auth-story-copy p:last-child { font-size: .92rem; }
            .auth-card { padding: 1.75rem 1.5rem; }
            .auth-header h1 { font-size: 1.65rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .content-area,
            .card,
            .glass-card,
            .mini-stat,
            .service-card,
            .table tbody tr,
            .sidebar-link,
            .sidebar-link i,
            .btn,
            .form-control,
            .form-select,
            .form-check-input,
            .input-group-text,
            .alert,
            .timeline-current,
            .timeline-current::before,
            .timeline-highlight,
            .timeline-highlight::before,
            .timeline-highlight > .fw-semibold,
            .auth-story,
            .auth-story::before,
            .auth-story-brand,
            .auth-story-copy,
            .auth-story-foot,
            .auth-card {
                animation: none !important;
                transition: none !important;
            }

            .card:hover,
            .glass-card:hover,
            .mini-stat:hover,
            .service-card:hover,
            .sidebar-link:hover,
            .sidebar-link:hover i,
            .btn:hover,
            .btn:active {
                transform: none !important;
            }
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(15, 23, 42, 0.72);
                border-radius: 0 0 16px 16px;
                margin-top: .75rem;
                padding: .5rem .75rem .75rem;
            }

            .navbar-nav {
                gap: .25rem;
                align-items: stretch;
            }

            .navbar-nav .nav-link {
                padding: .7rem .9rem;
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.03);
            }

            .content-area {
                padding-top: 1rem;
            }

            .navbar-toggler {
                border-color: rgba(255, 255, 255, 0.28);
                padding: .45rem .6rem;
            }

            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.12);
            }
        }

        @media print {
            .no-print, .navbar, .alert { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .card { border: 0 !important; background: #fff !important; }
            [data-bs-theme=dark] { --bs-body-color: #000; --bs-emphasis-color: #000; --bs-border-color: #999; --bs-secondary-color: #333; }
            .table, .table * { color: #000 !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php($user = auth()->user())
@php($unreadNotificationCount = $user?->isPelanggan() ? $user->unreadNotifications()->count() : 0)
<nav class="navbar navbar-expand-lg navbar-g88 sticky-top">
    <div class="container">
        <a class="brand navbar-brand" href="{{ $user ? route('dashboard') : route('home') }}">Garage<span>88</span></a>
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                    @if($user->isAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}" href="{{ route('admin.pelanggan.index') }}">Pelanggan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.motor.*') ? 'active' : '' }}" href="{{ route('admin.motor.index') }}">Motor</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.layanan.*') ? 'active' : '' }}" href="{{ route('admin.layanan.index') }}">Layanan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}" href="{{ route('admin.pesanan.index') }}">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.chat*') ? 'active' : '' }}" href="{{ route('admin.chat') }}">Chat</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" href="{{ route('admin.laporan') }}">Laporan</a></li>
                    @elseif($user->isTeknisi())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('teknisi.*') ? 'active' : '' }}" href="{{ route('teknisi.pekerjaan') }}">Daftar Pekerjaan</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('pelanggan.ajukan') ? 'active' : '' }}" href="{{ route('pelanggan.ajukan') }}">Ajukan Layanan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('pelanggan.status') ? 'active' : '' }}" href="{{ route('pelanggan.status') }}">Status Pengerjaan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('pelanggan.riwayat') ? 'active' : '' }}" href="{{ route('pelanggan.riwayat') }}">Riwayat</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('pelanggan.chat*') ? 'active' : '' }}" href="{{ route('pelanggan.chat') }}">Chat Admin</a></li>
                    @endif
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-2">
                @auth
                    @if($user->isPelanggan())
                        <a href="{{ route('pelanggan.notifikasi') }}" class="notification-action" aria-label="Notifikasi" title="Notifikasi">
                            <i class="bi bi-bell" aria-hidden="true"></i>
                            @if($unreadNotificationCount > 0)<span class="notification-count">{{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}</span>@endif
                        </a>
                    @endif
                    <span class="text-secondary small d-none d-lg-inline">{{ $user->name }} · <span class="text-uppercase">{{ $user->role }}</span></span>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-accent">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-accent">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@if($user)
    <div class="app-shell">
        <main class="content-area">
            @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if($errors->any() && ! request()->routeIs('login'))
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
    </div>
@else
    <main class="container py-4">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
        @if($errors->any() && ! request()->routeIs('login'))
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
@endif

<footer class="container py-4 text-center text-secondary small no-print">&copy; {{ date('Y') }} Garage88 — Sistem Informasi Pelayanan Repaint</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
