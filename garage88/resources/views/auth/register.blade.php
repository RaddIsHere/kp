@extends('layouts.app')
@section('title', 'Registrasi')
@section('content')
<div class="auth-shell">
    <div class="auth-layout">
        <aside class="auth-story">
            <div class="auth-story-brand"><span class="auth-story-mark">88</span> Garage88</div>
            <div class="auth-story-copy">
                <p class="auth-story-eyebrow">Repaint · Restoration · Service</p>
                <h2>Mulai perjalanan motor Anda.</h2>
                <p>Buat akun untuk memilih layanan dan mengikuti setiap tahap pengerjaan.</p>
            </div>
            <div class="auth-story-foot">
                <span><i class="bi bi-shield-check"></i>Progres transparan</span>
                <span><i class="bi bi-wrench-adjustable"></i>Teknisi berpengalaman</span>
            </div>
        </aside>
        <section class="auth-card">
        <div class="auth-header">
            <p class="auth-form-eyebrow">Mulai sekarang</p>
            <h1>Registrasi Pelanggan</h1>
            <p>Lengkapi data Anda untuk membuat akun Garage88.</p>
        </div>
            <form class="auth-form" method="POST" action="{{ route('register') }}">@csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ulangi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP / WhatsApp</label>
                    <input name="no_hp" value="{{ old('no_hp') }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="2" class="form-control" required>{{ old('alamat') }}</textarea>
                </div>
                <button class="btn btn-accent w-100">Daftar</button>
            </form>
            <p class="auth-meta mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-accent">Masuk</a></p>
        </section>
    </div>
</div>
@endsection
