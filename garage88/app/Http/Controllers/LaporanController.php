<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/** Admin: laporan pesanan (filter periode, cetak). */
class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->query('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->query('sampai', now()->toDateString());
        $status = $request->query('status');
        $q = $request->query('q');

        $items = $this->buildQuery($request)->get();

        return view('admin.laporan', [
            'items' => $items,
            'dari' => $dari,
            'sampai' => $sampai,
            'status' => $status,
            'q' => $q,
            'daftarStatus' => Pesanan::STATUS,
            'pendapatan' => $this->pendapatan($items),
        ]);
    }

    public function export(Request $request)
    {
        $items = $this->buildQuery($request)->get();

        $filename = 'laporan_garage88_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Tanggal', 'Pelanggan', 'Motor', 'Plat Nomor', 'Status', 'Pembayaran', 'Total']);

            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->tanggal->format('d/m/Y'),
                    $item->pelanggan->user->name,
                    $item->motor->merk . ' ' . $item->motor->tipe,
                    $item->motor->plat_nomor ?? '-',
                    $item->status,
                    $item->paymentStatusLabel(),
                    number_format($item->total, 0, ',', '.'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportPdf(Request $request)
    {
        $dari = $request->query('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->query('sampai', now()->toDateString());
        $status = $request->query('status');
        $q = $request->query('q');

        $items = $this->buildQuery($request)->get();

        $pdf = Pdf::loadView('admin.laporan-pdf', [
            'items' => $items,
            'dari' => $dari,
            'sampai' => $sampai,
            'status' => $status,
            'q' => $q,
            'pendapatan' => $this->pendapatan($items),
        ])->setPaper('A4', 'landscape');

        return $pdf->download('laporan_garage88_' . now()->format('Ymd_His') . '.pdf');
    }

    protected function buildQuery(Request $request)
    {
        $dari = $request->query('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->query('sampai', now()->toDateString());
        $status = $request->query('status');
        $q = $request->query('q');

        return Pesanan::with(['pelanggan.user', 'motor', 'detail.layanan', 'payment', 'payments'])
            ->whereBetween('tanggal', [$dari, $sampai])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q, fn ($query) => $query->where(function ($w) use ($q) {
                $w->whereHas('pelanggan.user', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('motor', fn ($m) => $m->where('plat_nomor', 'like', "%{$q}%"))
                    ->orWhereHas('motor', fn ($m) => $m->where('merk', 'like', "%{$q}%"));
            }))
            ->orderBy('tanggal');
    }

    private function pendapatan($items): float
    {
        return (float) $items->sum(fn (Pesanan $pesanan) => $pesanan->verifiedPaymentTotal());
    }
}
