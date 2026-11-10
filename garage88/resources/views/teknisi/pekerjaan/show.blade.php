@extends('layouts.app')
@section('title', 'Pekerjaan #'.$pesanan->id)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Pekerjaan #{{ $pesanan->id }} <x-status-badge :status="$pesanan->status" /></h1>
    <a href="{{ route('teknisi.pekerjaan') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
</div>
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card mb-3"><div class="card-body">
            <h2 class="h6 text-secondary text-uppercase">Motor</h2>
            <div class="fs-5">{{ $pesanan->motor->nama_lengkap }}</div>
            <div class="text-secondary small">Pemilik: {{ $pesanan->pelanggan->user->name }} · {{ $pesanan->pelanggan->no_hp }}</div>
            <hr>
            <h2 class="h6 text-secondary text-uppercase">Layanan</h2>
            <ul class="mb-0">@foreach($pesanan->detail as $d)<li>{{ $d->layanan->nama_layanan }}</li>@endforeach</ul>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="h6 text-secondary text-uppercase">Update Status Pengerjaan</h2>
            @if($daftarStatus)
                <form method="POST" action="{{ route('teknisi.progress.store', $pesanan) }}">@csrf
                    <select name="status" class="form-select mb-2">
                        @foreach($daftarStatus as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
                    </select>
                    <textarea name="keterangan" rows="2" class="form-control mb-2" placeholder="Keterangan pengerjaan (opsional)"></textarea>
                    <button class="btn btn-accent w-100">Simpan</button>
                </form>
            @else
                <p class="text-secondary small mb-0">Tidak ada tahapan yang dapat diperbarui.</p>
            @endif
        </div></div>
    </div>
    <div class="col-lg-6"><div class="card"><div class="card-body">
        <h2 class="h6 text-secondary text-uppercase mb-3">Riwayat Progress</h2>
        <x-timeline :progress="$pesanan->progress" :highlight="session()->has('progress_updated')" />
    </div></div></div>
</div>
@endsection
