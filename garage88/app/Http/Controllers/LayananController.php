<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Admin: kelola layanan (katalog & harga). */
class LayananController extends Controller
{
    private function rules(): array
    {
        return [
            'nama_layanan' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ];
    }

    public function index()
    {
        return view('admin.layanan.index', ['items' => Layanan::withCount('detail')->orderBy('nama_layanan')->get()]);
    }

    public function create()
    {
        return view('admin.layanan.form', ['item' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        Layanan::create($request->validate($this->rules()));

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.form', ['item' => $layanan]);
    }

    public function update(Request $request, Layanan $layanan): RedirectResponse
    {
        $layanan->update($request->validate($this->rules()));

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan diperbarui.');
    }

    public function destroy(Layanan $layanan): RedirectResponse
    {
        if ($layanan->detail()->exists()) {
            return back()->with('error', 'Layanan sudah dipakai pada pesanan dan tidak dapat dihapus.');
        }

        $layanan->delete();

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan dihapus.');
    }
}
