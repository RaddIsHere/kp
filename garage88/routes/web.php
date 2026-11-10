<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProgressController;
use App\Models\Layanan;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home', ['layanan' => Layanan::orderBy('nama_layanan')->get()]))->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('pelanggan', PelangganController::class)->except(['show', 'create', 'store']);
        Route::resource('motor', MotorController::class)->except(['show', 'create', 'store']);
        Route::resource('layanan', LayananController::class)->except('show');
        Route::resource('pesanan', PesananController::class)->only(['index', 'show', 'destroy']);
        Route::post('pesanan/{pesanan}/pembayaran/{payment}', [PaymentController::class, 'verify'])->name('pesanan.pembayaran');
        Route::get('pesanan/{pesanan}/bukti-pembayaran/{payment}', [PaymentController::class, 'proof'])->name('pesanan.bukti-pembayaran');
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
        Route::get('laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('chat', [ChatController::class, 'adminIndex'])->name('chat');
        Route::get('chat/{pelanggan}', [ChatController::class, 'adminIndex'])->name('chat.show');
        Route::post('chat/{pelanggan}', [ChatController::class, 'adminStore'])->name('chat.store');
    });

    // Admin shares the progress endpoint, with narrower state transitions enforced in the controller.
    Route::middleware('role:teknisi,admin')->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('pekerjaan', [ProgressController::class, 'index'])->name('pekerjaan');
        Route::get('pekerjaan/{pesanan}', [ProgressController::class, 'show'])->name('pekerjaan.show');
        Route::post('pekerjaan/{pesanan}/status', [ProgressController::class, 'store'])->name('progress.store');
    });

    // PELANGGAN
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('ajukan', [PortalController::class, 'ajukan'])->name('ajukan');
        Route::post('ajukan', [PortalController::class, 'simpan'])->name('simpan');
        Route::get('notifikasi', [NotificationController::class, 'index'])->name('notifikasi');
        Route::post('notifikasi/baca-semua', [NotificationController::class, 'markAllRead'])->name('notifikasi.baca-semua');
        Route::post('notifikasi/{id}/baca', [NotificationController::class, 'markRead'])->name('notifikasi.baca');
        Route::get('status', [PortalController::class, 'status'])->name('status');
        Route::get('riwayat', [PortalController::class, 'riwayat'])->name('riwayat');
        Route::get('pesanan/{pesanan}', [PortalController::class, 'detail'])->name('detail');
        Route::post('pesanan/{pesanan}/pembayaran', [PaymentController::class, 'store'])->name('pembayaran');
        Route::get('pesanan/{pesanan}/bukti-pembayaran/{payment}', [PaymentController::class, 'proof'])->name('bukti-pembayaran');
        Route::get('chat', [ChatController::class, 'customerIndex'])->name('chat');
        Route::post('chat', [ChatController::class, 'customerStore'])->name('chat.store');
    });
});
