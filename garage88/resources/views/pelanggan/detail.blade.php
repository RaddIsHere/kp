@extends('layouts.app')
@section('title', 'Pesanan #'.$pesanan->id)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Pesanan #{{ $pesanan->id }} <x-status-badge :status="$pesanan->status" /></h1>
    <a href="{{ route('pelanggan.riwayat') }}" class="btn btn-outline-secondary btn-sm">&larr; Riwayat</a>
</div>
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card mb-3"><div class="card-body">
            <h2 class="h6 text-secondary text-uppercase mb-3">Detail Motor &amp; Repaint</h2>
            <div class="fs-5">{{ $pesanan->motor->nama_lengkap }}</div>
            <div class="text-secondary small mb-3">Tanggal masuk: {{ $pesanan->tanggal->translatedFormat('d F Y') }}</div>

            <div class="row g-2 small">
                <div class="col-6"><span class="text-secondary">Plat nomor</span><div class="fw-semibold">{{ $pesanan->motor->plat_nomor ?? '-' }}</div></div>
                <div class="col-6"><span class="text-secondary">Estimasi selesai</span><div class="fw-semibold">{{ $pesanan->estimasi_selesai ? $pesanan->estimasi_selesai->translatedFormat('d F Y') : '-' }}</div></div>
                <div class="col-6"><span class="text-secondary">Bagian repaint</span><div class="fw-semibold">{{ $pesanan->bagian_direpaint ?? '-' }}</div></div>
                <div class="col-6"><span class="text-secondary">Warna cat</span><div class="fw-semibold">{{ $pesanan->warna_cat ?? '-' }}</div></div>
                <div class="col-6"><span class="text-secondary">Jenis cat</span><div class="fw-semibold">{{ $pesanan->jenis_cat ?? '-' }}</div></div>
                <div class="col-12"><span class="text-secondary">Catatan</span><div class="fw-semibold">{{ $pesanan->catatan ?? '-' }}</div></div>
            </div>
        </div></div>

        <div class="card"><div class="card-body">
            <h2 class="h6 text-secondary text-uppercase mb-3">Layanan</h2>
            <table class="table mb-0">
                @foreach($pesanan->detail as $d)
                    <tr><td>{{ $d->layanan->nama_layanan }}</td><td class="text-end">Rp {{ number_format($d->harga, 0, ',', '.') }}</td></tr>
                @endforeach
                <tr class="fw-bold"><td>Total</td><td class="text-end text-accent">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td></tr>
            </table>
        </div></div>
        <div class="card mt-3"><div class="card-body">
            <h2 class="h6 text-secondary text-uppercase mb-3">Pembayaran</h2>
            @php($dibayar = $pesanan->verifiedPaymentTotal())
            @php($sisaTagihan = max(0, (float) $pesanan->total - $dibayar))
            <p>Status pembayaran: <strong>{{ $pesanan->paymentStatusLabel() }}</strong></p>
            <p class="mb-1">Total tagihan: <strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></p>
            <p class="text-secondary small">Terbayar: Rp {{ number_format($dibayar, 0, ',', '.') }} · Sisa: Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</p>
            @if($pesanan->payments->isNotEmpty())
                <div class="table-responsive"><table class="table table-sm align-middle">
                    <thead><tr><th>Jenis</th><th>Nominal</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @foreach($pesanan->payments as $payment)
                        <tr>
                            <td>{{ $payment->type === 'dp' ? 'DP' : 'Pelunasan' }}<div class="text-secondary small">{{ match ($payment->method) { 'transfer' => 'Transfer bank', 'dana' => 'DANA', default => 'Tunai' } }}</div></td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->status }}</td>
                            <td class="text-end">
                                @if($payment->proof_path)<a class="btn btn-sm btn-outline-secondary" href="{{ route('pelanggan.bukti-pembayaran', [$pesanan, $payment]) }}" aria-label="Lihat bukti pembayaran"><i class="bi bi-paperclip"></i></a>@endif
                                @if($payment->admin_note)<div class="small text-secondary">{{ $payment->admin_note }}</div>@endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            @endif

            @if($sisaTagihan > 0 && $pesanan->status !== 'Dibatalkan')
                @if($pesanan->payments->contains('status', 'Menunggu verifikasi'))
                    <p class="text-secondary small mb-0">Pembayaran menunggu verifikasi admin.</p>
                @else
                <form method="POST" action="{{ route('pelanggan.pembayaran', $pesanan) }}" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label" for="payment-type">Jenis pembayaran</label>
                    <select class="form-select mb-2" id="payment-type" name="type" required>
                        <option value="dp" @selected(old('type') === 'dp')>DP</option>
                        <option value="pelunasan" @selected(old('type') === 'pelunasan')>Pelunasan sisa</option>
                    </select>
                    <label class="form-label" for="payment-amount">Nominal (Rp)</label>
                    <input class="form-control mb-2" id="payment-amount" name="amount" type="number" min="1" max="{{ $sisaTagihan }}" step="1" value="{{ old('amount') }}" required>
                    <label class="form-label" for="payment-method">Metode pembayaran</label>
                    <select class="form-select mb-2" id="payment-method" name="method" required>
                        <option value="transfer" @selected(old('method') === 'transfer')>Transfer bank</option>
                        <option value="dana" @selected(old('method') === 'dana')>DANA</option>
                        <option value="tunai" @selected(old('method', $pesanan->payment?->method) === 'tunai')>Tunai di bengkel</option>
                    </select>
                    <div class="alert alert-info small mb-2">
                        Transfer ke rekening <strong>Seabank 901489932428</strong> atau <strong>DANA 088210152017</strong>, lalu unggah bukti pembayaran di bawah.
                    </div>
                    <label class="form-label" for="payment-proof">Bukti transfer/DANA (foto JPG, PNG, atau PDF; maks. 5 MB)</label>
                    <input class="form-control mb-3" id="payment-proof" name="proof" type="file" accept="image/jpeg,image/png,application/pdf">
                    <button class="btn btn-accent w-100">Kirim pembayaran</button>
                </form>
                @endif
            @elseif($sisaTagihan <= 0)
                <p class="text-success small mb-0">Pembayaran lunas.</p>
            @else
                <p class="text-secondary small mb-0">Pesanan dibatalkan; pembayaran tidak dapat ditambahkan.</p>
            @endif
        </div></div>
    </div>
    <div class="col-lg-6"><div class="card"><div class="card-body">
        <h2 class="h6 text-secondary text-uppercase mb-3">Progress Pengerjaan</h2>
        <x-timeline :progress="$pesanan->progress" />
    </div></div></div>
</div>
@endsection
