<?php

namespace App\Http\Controllers;

use App\Notifications\PesananStatusNotification;
use App\Models\Pesanan;
use App\Models\Progress;
use App\Services\PesananNotificationComposer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Teknisi mengubah tahapan pengerjaan; admin hanya mengonfirmasi atau menyelesaikan pesanan. */
class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $tampil = $request->query('tampil', 'aktif');

        $items = Pesanan::with(['pelanggan.user', 'motor', 'detail.layanan'])
            ->when($tampil === 'aktif', fn ($q) => $q->whereNotIn('status', ['Selesai', 'Dibatalkan']))
            ->when($tampil === 'selesai', fn ($q) => $q->where('status', 'Selesai'))
            ->orderBy('tanggal')->paginate(10)->withQueryString();

        return view('teknisi.pekerjaan.index', compact('items', 'tampil'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan.user', 'motor', 'detail.layanan', 'progress']);

        return view('teknisi.pekerjaan.show', ['pesanan' => $pesanan, 'daftarStatus' => $pesanan->allowedStatusesFor(false)]);
    }

    /** Update status: data valid -> berhasil. */
    public function store(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in($pesanan->allowedStatusesFor($request->user()->isAdmin()))],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        return $this->updateStatus($request, $pesanan, $data['status'], $data['keterangan'] ?? null);
    }

    private function updateStatus(Request $request, Pesanan $pesanan, string $status, ?string $keterangan): RedirectResponse
    {
        $pesanan->loadMissing(['pelanggan.user', 'motor']);
        $previousStatus = $pesanan->status;
        $pesanan->update(['status' => $status]);
        Progress::create([
            'pesanan_id' => $pesanan->id,
            'status' => $status,
            'keterangan' => $keterangan,
            'tanggal_update' => now(),
        ]);

        $message = app(PesananNotificationComposer::class)->compose(
            $pesanan,
            $previousStatus,
            $status,
            $keterangan,
        );

        $pesanan->pelanggan->user->notify(new PesananStatusNotification([
            'title' => "Pembaruan pesanan #{$pesanan->id}",
            'message' => $message['message'],
            'status' => $status,
            'delay_risk' => $message['delay_risk'],
            'ai_generated' => $message['ai_generated'],
            'pesanan_id' => $pesanan->id,
            'url' => route('pelanggan.detail', $pesanan),
            'created_at' => now()->toIso8601String(),
        ]));

        return back()
            ->with('success', "Status pesanan #{$pesanan->id} diperbarui menjadi {$status}.")
            ->with('progress_updated', true);
    }
}
