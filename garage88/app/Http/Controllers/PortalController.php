<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Layanan;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/** Portal pelanggan: ajukan layanan, lihat status, riwayat. */
class PortalController extends Controller
{
    private function pelanggan()
    {
        // Akun pelanggan yang dibuat manual tanpa profil dilengkapi otomatis.
        return auth()->user()->pelanggan()->firstOrCreate([]);
    }

    public function ajukan()
    {
        $pelanggan = $this->pelanggan();

        return view('pelanggan.ajukan', [
            'motor' => $pelanggan->motor()->get(),
            'layanan' => Layanan::orderBy('nama_layanan')->get(),
        ]);
    }

    /** Alur activity diagram: pilih layanan -> input data motor -> simpan pesanan -> menunggu konfirmasi. */
    public function simpan(Request $request): RedirectResponse
    {
        $pelanggan = $this->pelanggan();

        $data = $request->validate([
            'motor_id' => ['required', Rule::when($request->input('motor_id') !== 'baru', [
                Rule::exists('motor', 'id')->where('pelanggan_id', $pelanggan->id),
            ])],
            'plat_nomor' => ['nullable', 'string', 'max:15'],
            'merk' => ['required_if:motor_id,baru', 'nullable', 'string', 'max:100'],
            'tipe' => ['required_if:motor_id,baru', 'nullable', 'string', 'max:100'],
            'tahun' => ['required_if:motor_id,baru', 'nullable', 'digits:4'],
            'warna' => ['required_if:motor_id,baru', 'nullable', 'string', 'max:50'],
            'tanggal' => ['required', 'date', 'after_or_equal:'.now()->addWeek()->toDateString(), 'before_or_equal:'.now()->addMonth()->toDateString()],
            'estimasi_selesai' => ['nullable', 'date', 'after_or_equal:tanggal'],
            'bagian_direpaint' => ['nullable', 'string', 'max:255'],
            'warna_cat' => ['nullable', 'string', 'max:100'],
            'jenis_cat' => ['nullable', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'layanan' => ['required', 'array', 'min:1'],
            'layanan.*' => ['exists:layanan,id'],
        ], [
            'layanan.required' => 'Pilih minimal satu layanan.',
            'tanggal.after_or_equal' => 'Tanggal kedatangan minimal 1 minggu dari hari ini.',
            'tanggal.before_or_equal' => 'Tanggal kedatangan maksimal 1 bulan dari hari ini.',
            'estimasi_selesai.after_or_equal' => 'Estimasi selesai tidak boleh sebelum tanggal kedatangan.',
        ]);

        $pesanan = DB::transaction(function () use ($data, $pelanggan) {
            $motorId = $data['motor_id'] === 'baru'
                ? Motor::create([
                    'pelanggan_id' => $pelanggan->id,
                    'plat_nomor' => $data['plat_nomor'] ?? null,
                    'merk' => $data['merk'], 'tipe' => $data['tipe'],
                    'tahun' => $data['tahun'], 'warna' => $data['warna'],
                ])->id
                : (int) $data['motor_id'];

            return Pesanan::buat($pelanggan->id, $motorId, $data['tanggal'], $data['layanan'], [
                'estimasi_selesai' => $data['estimasi_selesai'] ?? null,
                'bagian_direpaint' => $data['bagian_direpaint'] ?? null,
                'warna_cat' => $data['warna_cat'] ?? null,
                'jenis_cat' => $data['jenis_cat'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);
        });

        return redirect()->route('pelanggan.detail', $pesanan)
            ->with('success', 'Pesanan berhasil diajukan. Menunggu konfirmasi dari Garage88.');
    }

    public function status()
    {
        $items = $this->pelanggan()->pesanan()
            ->with(['motor', 'detail.layanan', 'progress'])
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->latest()->get();

        return view('pelanggan.status', compact('items'));
    }

    public function riwayat(Request $request)
    {
        $q = $request->query('q');

        $items = $this->pelanggan()->pesanan()
            ->with('motor')
            ->when($q, fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('status', 'like', "%{$q}%")
                    ->orWhereHas('motor', fn ($motor) => $motor->where('plat_nomor', 'like', "%{$q}%")
                        ->orWhere('merk', 'like', "%{$q}%")
                        ->orWhere('tipe', 'like', "%{$q}%"));
            }))
            ->latest()->paginate(10)->withQueryString();

        return view('pelanggan.riwayat', compact('items', 'q'));
    }

    public function detail(Pesanan $pesanan)
    {
        abort_unless($pesanan->pelanggan_id === $this->pelanggan()->id, 403);

        $pesanan->load(['motor', 'detail.layanan', 'progress', 'payment', 'payments']);

        return view('pelanggan.detail', compact('pesanan'));
    }
}
