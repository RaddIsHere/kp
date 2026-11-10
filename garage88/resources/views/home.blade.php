@extends('layouts.app')
@section('title', 'Beranda')
@push('styles')
<style>
    .home-page {
        --home-line: rgba(148, 163, 184, 0.45);
    }

    .home-hero {
        position: relative;
        min-height: 470px;
        display: grid;
        grid-template-columns: 1fr .82fr;
        align-items: center;
        gap: clamp(2rem, 5vw, 5rem);
        padding: clamp(2rem, 6vw, 5rem);
        overflow: hidden;
        border: 1px solid var(--home-line);
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(16, 33, 63, 0.98) 0%, rgba(49, 92, 176, 0.94) 45%, rgba(202, 216, 232, 0.85) 100%);
        box-shadow: 0 18px 40px rgba(30, 64, 175, 0.12);
        animation: home-rise .7s ease-out both;
    }

    .home-hero::before {
        content: "88";
        position: absolute;
        right: 32%;
        bottom: -5.7rem;
        color: rgba(255,255,255,0.08);
        font-size: 23rem;
        font-weight: 900;
        line-height: 1;
        pointer-events: none;
    }

    .home-copy, .home-photo { position: relative; z-index: 1; }

    .home-kicker {
        display: flex;
        align-items: center;
        gap: .65rem;
        color: #dfe7f6;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .17em;
        text-transform: uppercase;
    }

    .home-kicker::before {
        content: "";
        width: 28px;
        height: 2px;
        background: #dfe7f6;
    }

    .home-title {
        max-width: 610px;
        margin: 1.2rem 0;
        font-size: clamp(2.7rem, 5.7vw, 5rem);
        font-weight: 800;
        line-height: .99;
        letter-spacing: -.045em;
        color: #fff;
    }

    .home-title span {
        color: #dfe9f7;
    }

    .home-description {
        max-width: 490px;
        color: rgba(255,255,255,0.85);
        font-size: 1.05rem;
        line-height: 1.75;
    }

    .home-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: 1.8rem;
    }

    .home-actions .btn { padding: .8rem 1.1rem; font-size: .95rem; }

    .home-proof {
        display: flex;
        flex-wrap: wrap;
        gap: 1.35rem;
        margin-top: 2.3rem;
        color: rgba(255,255,255,0.85);
        font-size: .82rem;
    }

    .home-proof i { margin-right: .4rem; color: #dfe9f7; }

    .home-photo {
        width: min(100%, 250px);
        aspect-ratio: 9 / 16;
        overflow: hidden;
        border-radius: 18px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.22);
        justify-self: center;
    }

    .home-photo video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: saturate(.9) contrast(1.06);
    }

    .home-photo::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 52%, rgba(15, 23, 42, 0.7)), linear-gradient(90deg, rgba(16, 33, 63, 0.18), transparent);
    }

    .home-photo-label {
        position: absolute;
        z-index: 1;
        right: 1rem;
        bottom: 1rem;
        left: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: end;
        color: #fff;
    }

    .home-photo-label strong { display: block; font-size: 1rem; }
    .home-photo-label span { color: rgba(255,255,255,.8); font-size: .75rem; }

    .home-section {
        padding: 4rem 0 1rem;
    }

    .home-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .home-section-heading h2 {
        margin: .35rem 0 0;
        font-size: 1.8rem;
        font-weight: 750;
        color: #13253f;
    }

    .home-section-heading p {
        max-width: 390px;
        margin: 0;
        color: #53657b;
        font-size: .9rem;
    }

    .service-card {
        position: relative;
        min-height: 175px;
        padding: 1.35rem;
        overflow: hidden;
        border: 1px solid var(--home-line);
        border-radius: 18px;
        background: linear-gradient(145deg, #f8fbff, #edf3fd);
        transition: border-color .2s ease, transform .2s ease, box-shadow .2s ease;
        box-shadow: 0 8px 20px rgba(30, 64, 175, 0.05);
    }

    .service-card:hover {
        transform: translateY(-3px);
        border-color: rgba(47,111,232,.6);
        box-shadow: 0 16px 24px rgba(30, 64, 175, 0.08);
    }

    .service-card::after {
        content: attr(data-number);
        position: absolute;
        right: .7rem;
        bottom: -1.1rem;
        color: rgba(47,111,232,.08);
        font-size: 6rem;
        font-weight: 800;
        line-height: 1;
    }

    .service-icon {
        display: grid;
        width: 42px;
        height: 42px;
        place-items: center;
        margin-bottom: 1.2rem;
        border: 1px solid rgba(47,111,232,.22);
        color: #2f6fe8;
        background: rgba(47,111,232,.08);
        border-radius: 12px;
    }

    .service-card h3 {
        position: relative;
        z-index: 1;
        margin-bottom: .45rem;
        font-size: 1rem;
        font-weight: 650;
        color: #13253f;
    }

    .service-price {
        position: relative;
        z-index: 1;
        color: #1d4fc0;
        font-size: .86rem;
        font-weight: 650;
    }

    .showcase-section { padding-top: 3.5rem; }

    .showcase-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 1.25rem;
    }

    .showcase-heading h2 {
        max-width: 600px;
        margin: .35rem 0 0;
        color: #13253f;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .showcase-intro { max-width: 350px; margin: 0; color: #53657b; font-size: .9rem; line-height: 1.6; }

    .finish-grid {
        display: grid;
        grid-template-columns: 1.15fr .85fr;
        grid-template-rows: repeat(2, minmax(190px, 1fr));
        gap: .85rem;
    }

    .finish-card {
        position: relative;
        min-width: 0;
        min-height: 210px;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, .42);
        border-radius: 16px;
        background: #13253f;
        isolation: isolate;
    }

    .finish-feature { grid-row: span 2; min-height: 430px; }

    .finish-card video {
        position: absolute;
        z-index: -2;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .55s cubic-bezier(.2, .7, .2, 1);
    }

    .finish-card:hover video { transform: scale(1.045); }

    .finish-card::after {
        position: absolute;
        z-index: -1;
        inset: 0;
        content: "";
        background: linear-gradient(180deg, rgba(9, 20, 36, .05) 20%, rgba(9, 20, 36, .82) 100%);
    }

    .finish-index {
        position: absolute;
        top: .9rem;
        left: .9rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .6rem;
        border: 1px solid rgba(255,255,255,.34);
        border-radius: 999px;
        color: #fff;
        background: rgba(16, 33, 63, .45);
        font-size: .63rem;
        font-weight: 750;
        letter-spacing: .08em;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
    }

    .finish-copy {
        position: absolute;
        right: 1.1rem;
        bottom: 1rem;
        left: 1.1rem;
        color: #fff;
    }

    .finish-copy h3 { margin: 0 0 .3rem; color: #fff; font-size: 1.2rem; font-weight: 750; }
    .finish-copy p { margin: 0 0 .55rem; color: rgba(255,255,255,.82); font-size: .8rem; }
    .finish-credit { color: rgba(255,255,255,.75); font-size: .68rem; text-decoration-color: rgba(255,255,255,.45); }
    .finish-credit:hover { color: #fff; }

    .showcase-disclaimer {
        display: flex;
        align-items: flex-start;
        gap: .5rem;
        margin: .75rem 0 0;
        color: #64768b;
        font-size: .74rem;
        line-height: 1.5;
    }

    .showcase-disclaimer i { color: #426ea9; }

    .steps-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid var(--home-line);
        border-bottom: 1px solid var(--home-line);
        background: rgba(255,255,255,.32);
        border-radius: 18px;
        overflow: hidden;
    }

    .step-item {
        display: flex;
        gap: .9rem;
        padding: 1.35rem 1rem;
    }

    .step-item + .step-item { border-left: 1px solid var(--home-line); }

    .step-number {
        color: #2f6fe8;
        font-size: .75rem;
        font-weight: 700;
    }

    .step-item strong {
        display: block;
        margin-bottom: .25rem;
        font-size: .9rem;
        color: #13253f;
    }

    .step-item span:last-child {
        color: #53657b;
        font-size: .78rem;
        line-height: 1.5;
    }

    @keyframes home-rise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 767.98px) {
        .home-hero { min-height: 0; grid-template-columns: 1fr; gap: 2rem; padding: 2rem 1.4rem; }
        .home-hero::before { right: -2rem; bottom: 8rem; font-size: 15rem; }
        .home-title { font-size: clamp(2.7rem, 12vw, 4rem); }
        .home-photo { width: min(100%, 220px); }
        .home-section { padding-top: 3rem; }
        .home-section-heading { align-items: start; flex-direction: column; }
        .showcase-heading { align-items: flex-start; flex-direction: column; gap: .7rem; }
        .showcase-heading h2 { font-size: 1.65rem; }
        .finish-grid { grid-template-columns: 1fr; grid-template-rows: none; }
        .finish-feature { grid-row: auto; min-height: 320px; }
        .finish-card { min-height: 235px; }
        .steps-row { grid-template-columns: repeat(2, 1fr); }
        .step-item:nth-child(3) { border-left: 0; border-top: 1px solid var(--home-line); }
        .step-item:nth-child(4) { border-top: 1px solid var(--home-line); }
    }

    @media (prefers-reduced-motion: reduce) {
        .home-hero { animation: none; }
        .service-card,
        .finish-card video { transition: none; }
        .finish-card:hover video { transform: none; }
    }
</style>
@endpush
@section('content')
<div class="home-page">
    <section class="home-hero">
        <div class="home-copy">
            <div class="home-kicker">Repaint · Restoration · Service</div>
            <h1 class="home-title">Motor Anda,<br><span>naik kelas.</span></h1>
            <p class="home-description">Perawatan dan pengecatan motor dengan pengerjaan teliti. Ajukan layanan, lalu pantau progresnya langsung dari akun Anda.</p>
            <div class="home-actions">
                <a href="{{ route('register') }}" class="btn btn-accent">Mulai Ajukan Layanan <i class="bi bi-arrow-up-right ms-1"></i></a>
                <a href="{{ route('login') }}" class="btn btn-outline-light">Masuk akun</a>
            </div>
            <div class="home-proof">
                <span><i class="bi bi-check-circle-fill"></i>Pengerjaan terpantau</span>
                <span><i class="bi bi-wrench-adjustable"></i>Teknisi berpengalaman</span>
            </div>
        </div>
        <div class="home-photo">
            <video autoplay muted loop playsinline preload="metadata" aria-label="Video suasana bengkel Garage88">
                <source src="{{ asset('videos/garage88-hero.mp4') }}" type="video/mp4">
            </video>
            <div class="home-photo-label"><div><strong>Detail yang terasa.</strong><span>Perawatan dengan perhatian penuh.</span></div><i class="bi bi-arrow-down-right fs-4"></i></div>
        </div>
    </section>

    <section class="home-section showcase-section" aria-labelledby="showcase-title">
        <div class="showcase-heading">
            <div>
                <div class="home-kicker">Inspirasi repaint</div>
                <h2 id="showcase-title">Warna baru, karakter berbeda.</h2>
            </div>
            <p class="showcase-intro">Lihat beberapa arah warna dan finishing untuk inspirasi sebelum menentukan gaya motor Anda.</p>
        </div>

        <div class="finish-grid">
            <article class="finish-card finish-feature">
                <video autoplay muted loop playsinline preload="none" aria-label="Video referensi detail motor">
                    <source src="{{ asset('videos/referensi-motor-1.mp4') }}" type="video/mp4">
                </video>
                <span class="finish-index"><i class="bi bi-palette2"></i> Referensi / 01</span>
                <div class="finish-copy">
                    <h3>Detail motor</h3>
                    <p>Cuplikan visual dari video referensi.</p>
                </div>
            </article>
            <article class="finish-card">
                <video autoplay muted loop playsinline preload="none" aria-label="Video referensi tampilan bodi">
                    <source src="{{ asset('videos/referensi-motor-2.mp4') }}" type="video/mp4">
                </video>
                <span class="finish-index"><i class="bi bi-palette2"></i> Referensi / 02</span>
                <div class="finish-copy">
                    <h3>Tampilan bodi</h3>
                    <p>Cuplikan visual dari video referensi.</p>
                </div>
            </article>
            <article class="finish-card">
                <video autoplay muted loop playsinline preload="none" aria-label="Video referensi tampilan motor">
                    <source src="{{ asset('videos/referensi-motor-3.mp4') }}" type="video/mp4">
                </video>
                <span class="finish-index"><i class="bi bi-palette2"></i> Referensi / 03</span>
                <div class="finish-copy">
                    <h3>Suasana motor</h3>
                    <p>Cuplikan visual dari video referensi.</p>
                </div>
            </article>
        </div>
        <p class="showcase-disclaimer"><i class="bi bi-info-circle"></i>Video referensi ditampilkan sebagai materi visual untuk inspirasi.</p>
    </section>

    <section class="home-section">
        <div class="home-section-heading">
            <div><div class="home-kicker">Yang kami kerjakan</div><h2>Layanan Garage88</h2></div>
            <p>Dari perbaikan ringan hingga restorasi menyeluruh, pilih layanan sesuai kebutuhan motor Anda.</p>
        </div>
        <div class="row g-3">
            @forelse($layanan as $index => $l)
                <div class="col-md-4 col-sm-6">
                    <article class="service-card h-100" data-number="{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}">
                        <div class="service-icon"><i class="bi {{ ['bi-palette2', 'bi-tools', 'bi-stars'][$index % 3] }}"></i></div>
                        <h3>{{ $l->nama_layanan }}</h3>
                        <div class="service-price">Mulai Rp {{ number_format($l->harga, 0, ',', '.') }}</div>
                    </article>
                </div>
            @empty
                <div class="col-12"><p class="text-secondary mb-0">Daftar layanan belum tersedia.</p></div>
            @endforelse
        </div>
    </section>

    <section class="home-section pt-5">
        <div class="home-section-heading"><div><div class="home-kicker">Mudah dan transparan</div><h2>Mulai dari sini</h2></div></div>
        <div class="steps-row">
            @foreach([['01','Daftar','Buat akun pelanggan.'],['02','Ajukan layanan','Pilih layanan dan motor.'],['03','Pantau progres','Lihat pembaruan dari teknisi.'],['04','Ambil motor','Selesaikan pesanan Anda.']] as $step)
                <div class="step-item"><span class="step-number">{{ $step[0] }}</span><div><strong>{{ $step[1] }}</strong><span>{{ $step[2] }}</span></div></div>
            @endforeach
        </div>
    </section>
</div>
@endsection
@push('scripts')
<script>
    // Video pada card referensi sudah masing-masing, tidak perlu pemotongan segmen.
</script>
@endpush
