<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Motor;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DefaultAccountsSeeder::class);

        // Akun demo (password semua: password)
        $userBudi = User::firstOrCreate(
            ['email' => 'budi@garage88.test'],
            ['name' => 'Budi Santoso', 'password' => 'password', 'role' => 'pelanggan']
        );
        $budi = Pelanggan::firstOrCreate(
            ['user_id' => $userBudi->id],
            ['alamat' => 'Jl. Raya Cianjur No. 10', 'no_hp' => '081234567890']
        );

        $this->call(PriceListSeeder::class);
        $layananBody = Layanan::where('nama_layanan', 'Beat / Mio - Body Halus')->firstOrFail();
        $layananRestorasi = Layanan::where('nama_layanan', 'Scoopy / Genio / Vario - Full Restorasi')->firstOrFail();

        // Contoh data motor & pesanan
        $motor = Motor::firstOrCreate(
            ['pelanggan_id' => $budi->id, 'plat_nomor' => 'B 1234 ABC'],
            ['merk' => 'Honda', 'tipe' => 'CB150R', 'tahun' => '2019', 'warna' => 'Hitam Doff']
        );

        $pesanan = Pesanan::query()->where('motor_id', $motor->id)->first();
        if (! $pesanan) {
            $pesanan = Pesanan::buat($budi->id, $motor->id, now()->toDateString(), [$layananBody->id, $layananRestorasi->id], [
                'estimasi_selesai' => now()->addDays(7)->toDateString(),
                'bagian_direpaint' => 'Body depan & fairing',
                'warna_cat' => 'Hitam doff',
                'jenis_cat' => 'Acrylic',
                'catatan' => 'Periksa bagian bawah fairing dan body depan.',
            ]);
        }

        if ($pesanan->progress()->count() === 0) {
            Progress::updateStatus($pesanan, 'Dikonfirmasi', 'Pesanan dikonfirmasi, silakan antar motor ke bengkel.');
            Progress::updateStatus($pesanan, 'Pengampelasan', 'Body sedang diampelas dan didempul.');
        }
    }
}
