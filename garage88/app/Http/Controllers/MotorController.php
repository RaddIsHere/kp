<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Admin: kelola motor. */
class MotorController extends Controller
{
    private function rules(): array
    {
        return [
            'pelanggan_id' => ['required', 'exists:pelanggan,id'],
            'plat_nomor' => ['nullable', 'string', 'max:15'],
            'merk' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'string', 'max:100'],
            'tahun' => ['required', 'digits:4'],
            'warna' => ['required', 'string', 'max:50'],
        ];
    }

    public function index(Request $request)
    {
        $q = $request->query('q');

        $items = Motor::with('pelanggan.user')
            ->when($q, fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('plat_nomor', 'like', "%{$q}%")
                    ->orWhere('merk', 'like', "%{$q}%")
                    ->orWhere('tipe', 'like', "%{$q}%")
                    ->orWhere('warna', 'like', "%{$q}%")
                    ->orWhereHas('pelanggan.user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            }))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.motor.index', compact('items', 'q'));
    }

    public function edit(Motor $motor)
    {
        return view('admin.motor.form', ['item' => $motor, 'pelanggan' => Pelanggan::with('user')->get()]);
    }

    public function update(Request $request, Motor $motor): RedirectResponse
    {
        $motor->update($request->validate($this->rules()));

        return redirect()->route('admin.motor.index')->with('success', 'Data motor diperbarui.');
    }

    public function destroy(Motor $motor): RedirectResponse
    {
        $motor->delete();

        return redirect()->route('admin.motor.index')->with('success', 'Motor dihapus.');
    }
}
