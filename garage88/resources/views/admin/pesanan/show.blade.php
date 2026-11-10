@extends('layouts.app')
@section('title', 'Pesanan #'.$pesanan->id)
@push('styles')
<style>
    .premium-detail-shell {
        max-width: 1200px;
        margin: 0 auto;
    }

    .premium-page-header {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .premium-page-header h1 {
        margin: 0;
        font-size: clamp(1.8rem, 2vw, 2.3rem);
        color: #13253f;
    }

    .premium-card {
        padding: 0.35rem;
    }

    .premium-card .card-body {
        padding: 1.25rem 1.3rem;
    }

    .section-kicker {
        display: inline-block;
        margin-bottom: 0.8rem;
        color: #5d6d82;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
</style>
@endpush
@section('content')
<div class="premium-detail-shell">
    <div class="premium-page-header">
        <h1>Pesanan #{{ $pesanan->id }} <x-status-badge :status="$pesanan->status" /></h1>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card premium-card mb-3"><div class="card-body">
                <div class="section-kicker">Pelanggan &amp; Motor</div>
                <div class="fw-semibold">{{ $pesanan->pelanggan->user->name }}</div>
                <div class="text-secondary small mb-2">{{ $pesanan->pelanggan->no_hp }} · {{ $pesanan->pelanggan->alamat }}</div>
                <div>{{ $pesanan->motor->nama_lengkap }}</div>
                <div class="text-secondary small">Plat Nomor: {{ $pesanan->motor->plat_nomor ?? '-' }}</div>
                <div class="text-secondary small">Tanggal: {{ $pesanan->tanggal->translatedFormat('d F Y') }}</div>
                @if($pesanan->estimasi_selesai)
                    <div class="text-secondary small">Estimasi selesai: {{ $pesanan->estimasi_selesai->translatedFormat('d F Y') }}</div>
                @endif
                @if($pesanan->bagian_direpaint || $pesanan->warna_cat || $pesanan->jenis_cat)
                    <div class="mt-2 small"><strong>Detail repaint:</strong> {{ trim(implode(' · ', array_filter([$pesanan->bagian_direpaint, $pesanan->warna_cat, $pesanan->jenis_cat]))) }}</div>
                @endif
                @if($pesanan->catatan)
                    <div class="small mt-2"><strong>Catatan:</strong> {{ $pesanan->catatan }}</div>
                @endif
            </div></div>
            <div class="card premium-card"><div class="card-body">
                <div class="section-kicker">Layanan</div>
                <table class="table mb-0">
                    @foreach($pesanan->detail as $d)
                        <tr><td>{{ $d->layanan->nama_layanan }}</td><td class="text-end">Rp {{ number_format($d->harga, 0, ',', '.') }}</td></tr>
                    @endforeach
                    <tr class="fw-bold"><td>Total</td><td class="text-end text-accent">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td></tr>
                </table>
            </div></div>
        </div>
        <div class="col-lg-5">
            <div class="card premium-card mb-3"><div class="card-body">
                <div class="section-kicker">Ubah Status</div>
                @if($daftarStatus)
                    <form method="POST" action="{{ route('teknisi.progress.store', $pesanan) }}">@csrf
                        <select name="status" class="form-select mb-2">
                            @foreach($daftarStatus as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
                        </select>
                        <textarea name="keterangan" rows="2" class="form-control mb-2" placeholder="Keterangan (opsional)"></textarea>
                        <button class="btn btn-accent w-100">Simpan Status</button>
                    </form>
                @else
                    <p class="text-secondary small mb-0">Tidak ada konfirmasi atau penyelesaian yang perlu dilakukan pada tahap ini.</p>
                @endif
            </div></div>
            <div class="card premium-card mb-3"><div class="card-body">
                <div class="section-kicker">Pembayaran</div>
                <p class="text-secondary small">Terbayar: Rp {{ number_format($pesanan->verifiedPaymentTotal(), 0, ',', '.') }} dari Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                @if($pesanan->payments->isNotEmpty())
                    @foreach($pesanan->payments as $payment)
                        <div class="border-top py-2">
                            <div class="d-flex justify-content-between gap-2"><strong>{{ $payment->type === 'dp' ? 'DP' : 'Pelunasan' }} · {{ $payment->status }}</strong><span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></div>
                            <div class="text-secondary small mb-2">{{ match ($payment->method) { 'transfer' => 'Transfer bank', 'dana' => 'DANA', default => 'Tunai di bengkel' } }}</div>
                            @if($payment->proof_path)
                                <a class="btn btn-sm btn-outline-secondary mb-2" href="{{ route('admin.pesanan.bukti-pembayaran', [$pesanan, $payment]) }}"><i class="bi bi-paperclip"></i> Unduh bukti</a>
                            @endif
                            @if($payment->status === 'Menunggu verifikasi')
                                <form method="POST" action="{{ route('admin.pesanan.pembayaran', [$pesanan, $payment]) }}">
                                    @csrf
                                    <select class="form-select mb-2" name="status" aria-label="Hasil verifikasi">
                                        <option value="Terverifikasi">Verifikasi pembayaran</option>
                                        <option value="Ditolak">Tolak pembayaran</option>
                                    </select>
                                    <textarea class="form-control mb-2" name="admin_note" rows="2" maxlength="500" placeholder="Catatan (opsional)"></textarea>
                                    <button class="btn btn-accent w-100">Simpan verifikasi</button>
                                </form>
                            @endif
                            @if($payment->admin_note)<p class="small mt-2 mb-0">Catatan: {{ $payment->admin_note }}</p>@endif
                        </div>
                    @endforeach
                @else
                    <p class="text-secondary small mb-0">Pelanggan belum mengirim pembayaran.</p>
                @endif
            </div></div>
            <div class="card premium-card"><div class="card-body">
                <div class="section-kicker">Riwayat Progress</div>
                <x-timeline :progress="$pesanan->progress" />
            </div></div>
        </div>
    </div>
</div>
@endsection
