<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Garage88</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111;
            font-size: 12px;
            margin: 24px;
        }
        .title {
            text-align: center;
            margin-bottom: 16px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 0.08em;
        }
        .subtitle {
            margin-top: 4px;
            color: #444;
        }
        .meta {
            margin-bottom: 12px;
            text-align: center;
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f2f2f2;
        }
        .total-row th, .total-row td {
            font-weight: bold;
            background: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 14px;
            font-size: 11px;
            color: #444;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="title">
        <h1>GARAGE88</h1>
        <div class="subtitle">Laporan Pelayanan Repaint</div>
    </div>

    <div class="meta">
        Periode {{ \Illuminate\Support\Carbon::parse($dari)->translatedFormat('d F Y') }} – {{ \Illuminate\Support\Carbon::parse($sampai)->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Motor</th>
                <th>Layanan</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $pesanan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pesanan->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $pesanan->pelanggan->user->name }}</td>
                    <td>{{ $pesanan->motor->merk }} {{ $pesanan->motor->tipe }}</td>
                    <td>{{ $pesanan->detail->pluck('layanan.nama_layanan')->join(', ') ?: '-' }}</td>
                    <td>{{ $pesanan->status }}</td>
                    <td>{{ $pesanan->paymentStatusLabel() }}</td>
                    <td class="text-right">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-right">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="7" class="text-right">Jumlah pesanan</th>
                <td class="text-right">{{ $items->count() }}</td>
            </tr>
            <tr class="total-row">
                <th colspan="7" class="text-right">Pendapatan (pembayaran lunas)</th>
                <td class="text-right">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</div>
</body>
</html>
