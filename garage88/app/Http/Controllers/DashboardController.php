<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Motor;
use App\Models\Pelanggan;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin', [
                'jumlahPelanggan' => Pelanggan::count(),
                'jumlahMotor' => Motor::count(),
                'jumlahLayanan' => Layanan::count(),
                'menunggu' => Pesanan::where('status', Pesanan::AWAL)->count(),
                'diproses' => Pesanan::whereNotIn('status', [Pesanan::AWAL, 'Selesai', 'Dibatalkan'])->count(),
                'selesai' => Pesanan::where('status', 'Selesai')->count(),
                'terbaru' => Pesanan::with(['pelanggan.user', 'motor'])->latest()->take(5)->get(),
            ]);
        }

        if ($user->isTeknisi()) {
            return view('dashboard.teknisi', [
                'aktif' => Pesanan::with(['pelanggan.user', 'motor'])
                    ->whereNotIn('status', [Pesanan::AWAL, 'Selesai', 'Dibatalkan'])
                    ->latest()->take(5)->get(),
                'jumlahAktif' => Pesanan::whereNotIn('status', [Pesanan::AWAL, 'Selesai', 'Dibatalkan'])->count(),
                'selesai' => Pesanan::where('status', 'Selesai')->count(),
            ]);
        }

        $pelanggan = $user->pelanggan;

        return view('dashboard.pelanggan', [
            'pelanggan' => $pelanggan,
            'jumlahMotor' => $pelanggan?->motor()->count() ?? 0,
            'aktif' => $pelanggan
                ? $pelanggan->pesanan()->with('motor')->whereNotIn('status', ['Selesai', 'Dibatalkan'])->latest()->get()
                : collect(),
            'totalPesanan' => $pelanggan?->pesanan()->count() ?? 0,
        ]);
    }
}
