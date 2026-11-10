@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="auth-shell">
    <div class="auth-layout">
        <aside class="auth-story">
            <div class="auth-story-brand"><span class="auth-story-mark">88</span> Garage88</div>
            <div class="auth-story-copy">
                <p class="auth-story-eyebrow">Repaint · Restoration · Service</p>
                <h2>Rawat motor dengan standar terbaik.</h2>
                <p>Ajukan layanan dan pantau progres pengerjaan melalui satu akun.</p>
            </div>
            <div class="auth-story-foot">
                <span><i class="bi bi-shield-check"></i>Progres transparan</span>
                <span><i class="bi bi-wrench-adjustable"></i>Teknisi berpengalaman</span>
            </div>
        </aside>
        <section class="auth-card">
            <div class="auth-header">
                <p class="auth-form-eyebrow">Akun pelanggan</p>
                <h1>Masuk ke Garage88</h1>
                <p>Gunakan akun Anda untuk mengelola layanan dan melihat progres.</p>
            </div>
            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">{{ $errors->first() }}</div>
            @endif
            <form class="auth-form" method="POST" action="{{ route('login') }}">@csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-accent w-100">Masuk</button>
            </form>
            <p class="auth-meta mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-accent">Daftar sebagai pelanggan</a></p>
        </section>
    </div>
</div>
@endsection
