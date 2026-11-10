<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Admin: kelola pesanan. */
class PesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $q = $request->query('q');

        $items = Pesanan::with(['pelanggan.user', 'motor'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q, fn ($query) => $query->where(function ($w) use ($q) {
                $w->whereHas('pelanggan.user', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('motor', fn ($m) => $m->where('plat_nomor', 'like', "%{$q}%"))->orWhereHas('motor', fn ($m) => $m->where('merk', 'like', "%{$q}%"));
            }))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.pesanan.index', ['items' => $items, 'status' => $status, 'q' => $q, 'daftarStatus' => Pesanan::STATUS]);
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan.user', 'motor', 'detail.layanan', 'progress', 'payment', 'payments']);

        return view('admin.pesanan.show', ['pesanan' => $pesanan, 'daftarStatus' => $pesanan->allowedStatusesFor(true)]);
    }

    public function destroy(Pesanan $pesanan): RedirectResponse
    {
        $pesanan->delete();

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan dihapus.');
    }
}
