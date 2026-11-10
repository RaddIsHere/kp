<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(15);

        return view('pelanggan.notifikasi', compact('notifications'));
    }

    public function markRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $pesananId = $notification->data['pesanan_id'] ?? null;

        return $pesananId
            ? redirect()->route('pelanggan.detail', $pesananId)
            : redirect()->route('pelanggan.notifikasi');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->route('pelanggan.notifikasi')->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }
}