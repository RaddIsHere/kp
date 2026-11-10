<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    public function store(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $pelanggan = $request->user()->pelanggan()->firstOrCreate([]);
        abort_unless($pesanan->pelanggan_id === $pelanggan->id, 403);
        abort_unless($pesanan->status !== 'Dibatalkan', 403);

        $remaining = (int) $pesanan->total - (int) $pesanan->verifiedPaymentTotal();
        if ($remaining <= 0) {
            return back()->with('error', 'Pesanan ini sudah lunas.');
        }
        if ($pesanan->payments()->where('status', 'Menunggu verifikasi')->exists()) {
            return back()->with('error', 'Tunggu verifikasi pembayaran sebelumnya sebelum mengirim pembayaran berikutnya.');
        }

        $data = $request->validate([
            'type' => ['required', 'in:dp,pelunasan'],
            'amount' => ['required', 'numeric', 'min:1', 'max:'.$remaining],
            'method' => ['required', 'in:transfer,dana,tunai'],
            'proof' => ['nullable', 'required_if:method,transfer,dana', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'amount.max' => 'Nominal pembayaran melebihi sisa tagihan.',
            'proof.required_if' => 'Unggah foto atau file bukti untuk pembayaran transfer bank atau DANA.',
            'proof.mimes' => 'Bukti harus berupa JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran bukti maksimal 5 MB.',
        ]);

        if ($data['type'] === 'dp' && (float) $data['amount'] >= $remaining) {
            return back()->withErrors(['amount' => 'Nominal pelunasan penuh harus dipilih sebagai pelunasan.'])->withInput();
        }

        if ($data['type'] === 'pelunasan' && (float) $data['amount'] !== (float) $remaining) {
            return back()->withErrors(['amount' => 'Nominal pelunasan harus sama dengan sisa tagihan.'])->withInput();
        }

        $proofPath = $request->file('proof')?->store('payment-proofs', 'local');

        Payment::create([
            'pesanan_id' => $pesanan->id,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'method' => $data['method'],
            'status' => 'Menunggu verifikasi',
            'proof_path' => $proofPath,
        ]);

        return back()->with('success', 'Pengajuan pembayaran dikirim dan menunggu verifikasi admin.');
    }

    public function verify(Request $request, Pesanan $pesanan, Payment $payment): RedirectResponse
    {
        abort_unless($payment->pesanan_id === $pesanan->id, 404);
        abort_unless($payment->status === 'Menunggu verifikasi', 403);

        $data = $request->validate([
            'status' => ['required', 'in:Terverifikasi,Ditolak'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $payment->update([
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? null,
            'verified_at' => $data['status'] === 'Terverifikasi' ? now() : null,
        ]);

        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    public function proof(Request $request, Pesanan $pesanan, Payment $payment): StreamedResponse
    {
        abort_unless($payment->pesanan_id === $pesanan->id, 404);
        if ($request->user()->isPelanggan()) {
            abort_unless($pesanan->pelanggan_id === $request->user()->pelanggan?->id, 403);
        }

        $path = $payment->proof_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }
}