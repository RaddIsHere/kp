<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/** Admin: kelola pelanggan. */
class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $items = Pelanggan::with('user')->withCount(['motor', 'pesanan'])
            ->when($q, fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('no_hp', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
            }))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.pelanggan.index', compact('items', 'q'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.pelanggan.form', ['item' => $pelanggan->load('user')]);
    }

    public function update(Request $request, Pelanggan $pelanggan): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($pelanggan->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($data, $pelanggan) {
            $user = $pelanggan->user;
            $user->name = $data['name'];
            $user->email = $data['email'];
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            $pelanggan->update(['alamat' => $data['alamat'], 'no_hp' => $data['no_hp']]);
        });

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan): RedirectResponse
    {
        // Menghapus user ikut menghapus pelanggan, motor, dan pesanan (cascade).
        $pelanggan->user->delete();

        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan beserta data motor & pesanannya dihapus.');
    }
}
