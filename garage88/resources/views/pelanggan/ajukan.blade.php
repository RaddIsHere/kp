@extends('layouts.app')
@section('title', 'Ajukan Layanan')
@push('styles')
<style>
    .premium-form-shell {
        max-width: 860px;
        margin: 0 auto;
    }

    .premium-page-header {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .premium-page-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.7rem;
        border-radius: 999px;
        background: rgba(47,111,232,0.08);
        border: 1px solid rgba(47,111,232,0.12);
        color: #1d4fc0;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .premium-page-header h1 {
        margin: 0;
        font-size: clamp(1.8rem, 2vw, 2.4rem);
        color: #13253f;
    }

    .premium-form-card {
        padding: 0.35rem;
    }

    .premium-form-card .card-body {
        padding: 1.5rem;
    }

    .premium-form-grid {
        display: grid;
        gap: 1rem;
    }

    .section-tag {
        color: #5d6d82;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin: 0 0 0.9rem;
    }

    @media (min-width: 768px) {
        .premium-form-grid.two-col {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endpush
@section('content')
<div class="premium-form-shell">
    <div class="premium-page-header">
        <div>
            <div class="premium-page-kicker"><i class="bi bi-plus-circle"></i> Baru</div>
            <h1>Ajukan Layanan</h1>
        </div>
        <a href="{{ route('pelanggan.riwayat') }}" class="btn btn-outline-secondary btn-sm">Riwayat</a>
    </div>

    <div class="card premium-form-card"><div class="card-body">
        <form method="POST" action="{{ route('pelanggan.simpan') }}">@csrf
            <div class="section-tag">1. Pilih Layanan</div>
            <div class="mb-4">
                @foreach($layanan as $l)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="layanan[]" value="{{ $l->id }}" id="l{{ $l->id }}" data-harga="{{ (int) $l->harga }}" @checked(in_array($l->id, old('layanan', [])))>
                        <label class="form-check-label" for="l{{ $l->id }}">{{ $l->nama_layanan }} — <span class="text-secondary">Rp {{ number_format($l->harga, 0, ',', '.') }}</span></label>
                    </div>
                @endforeach
                <div class="mt-2 fw-semibold">Estimasi total: <span class="text-accent" id="total">Rp 0</span></div>
            </div>

            <div class="section-tag">2. Data Motor</div>
            <div class="premium-form-grid">
                <div class="mb-0">
                    <select name="motor_id" id="motor_id" class="form-select" required>
                        @foreach($motor as $m)<option value="{{ $m->id }}" @selected(old('motor_id') == $m->id)>{{ $m->nama_lengkap }}</option>@endforeach
                        <option value="baru" @selected(old('motor_id', $motor->isEmpty() ? 'baru' : null) === 'baru')>+ Motor baru</option>
                    </select>
                </div>
            </div>
            <div id="motor-baru" class="premium-form-grid two-col mt-3 d-none">
                <div class="mb-0"><label class="form-label">Plat Nomor</label><input name="plat_nomor" value="{{ old('plat_nomor') }}" class="form-control" placeholder="B 1234 ABC"></div>
                <div class="mb-0"><label class="form-label">Merk</label><input name="merk" value="{{ old('merk') }}" class="form-control"></div>
                <div class="mb-0"><label class="form-label">Tipe</label><input name="tipe" value="{{ old('tipe') }}" class="form-control"></div>
                <div class="mb-0"><label class="form-label">Tahun</label><input name="tahun" maxlength="4" value="{{ old('tahun') }}" class="form-control"></div>
                <div class="mb-0"><label class="form-label">Warna</label><input name="warna" value="{{ old('warna') }}" class="form-control"></div>
            </div>

            <div class="section-tag mt-3">3. Detail Repaint</div>
            <div class="premium-form-grid two-col mb-4">
                <div class="mb-0"><label class="form-label">Tanggal Kedatangan</label><input type="date" name="tanggal" min="{{ now()->addWeek()->toDateString() }}" max="{{ now()->addMonth()->toDateString() }}" value="{{ old('tanggal') }}" class="form-control" required></div>
                <div class="mb-0"><label class="form-label">Estimasi Selesai</label><input type="date" name="estimasi_selesai" min="{{ now()->addWeek()->toDateString() }}" value="{{ old('estimasi_selesai') }}" class="form-control"></div>
                <div class="mb-0"><label class="form-label">Bagian yang di-repaint</label><input name="bagian_direpaint" value="{{ old('bagian_direpaint') }}" class="form-control" placeholder="Body depan & fairing"></div>
                <div class="mb-0"><label class="form-label">Warna Cat</label><input name="warna_cat" value="{{ old('warna_cat') }}" class="form-control" placeholder="Hitam doff"></div>
                <div class="mb-0"><label class="form-label">Jenis Cat</label><input name="jenis_cat" value="{{ old('jenis_cat') }}" class="form-control" placeholder="Acrylic / Candy"></div>
                <div class="mb-0"><label class="form-label">Catatan Pengerjaan</label><textarea name="catatan" rows="3" class="form-control" placeholder="Catatan teknis dan kebutuhan khusus...">{{ old('catatan') }}</textarea></div>
            </div>

            <button class="btn btn-accent w-100">Kirim Pengajuan</button>
            <div class="text-secondary small mt-2">Setelah dikirim, status pesanan akan menjadi <em>Menunggu Konfirmasi</em>.</div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>
    const boxes = document.querySelectorAll('input[name="layanan[]"]');
    const totalEl = document.getElementById('total');
    const motorSel = document.getElementById('motor_id');
    const motorBaru = document.getElementById('motor-baru');
    function hitung() {
        let t = 0; boxes.forEach(b => { if (b.checked) t += parseInt(b.dataset.harga, 10); });
        totalEl.textContent = 'Rp ' + t.toLocaleString('id-ID');
    }
    function toggleMotor() { motorBaru.classList.toggle('d-none', motorSel.value !== 'baru'); }
    boxes.forEach(b => b.addEventListener('change', hitung));
    motorSel.addEventListener('change', toggleMotor);
    hitung(); toggleMotor();
</script>
@endpush
