<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class PriceListSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [
            'Beat / Mio' => ['Body Halus' => 700000, 'Body Kasar' => 400000, 'Full Restorasi' => 1550000],
            'Scoopy / Genio / Vario' => ['Body Halus' => 850000, 'Body Kasar' => 400000, 'Full Restorasi' => 1650000],
            'N-Max / Aerox' => ['Body Halus' => 1000000, 'Body Kasar' => 400000, 'Full Restorasi' => 1650000],
            'PCX / ADV / Stylo / Fazzio' => ['Body Halus' => 1100000, 'Body Kasar' => 400000, 'Full Restorasi' => 1850000],
            'X-Max' => ['Body Halus' => 1250000, 'Body Kasar' => 400000, 'Full Restorasi' => 2100000],
            'RX-King' => ['Body Halus' => 850000],
            'Supra / Karisma' => ['Body Halus' => 850000, 'Body Kasar' => 300000, 'Full Restorasi' => 1400000],
            'Jupiter MX / Ninja SS' => ['Body Halus' => 950000, 'Body Kasar' => 300000, 'Full Restorasi' => 1500000],
        ];

        foreach ($prices as $motor => $services) {
            foreach ($services as $service => $price) {
                Layanan::updateOrCreate(
                    ['nama_layanan' => "{$motor} - {$service}"],
                    ['harga' => $price],
                );
            }
        }
    }
}