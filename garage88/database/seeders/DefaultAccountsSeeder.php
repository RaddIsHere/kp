<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultAccountsSeeder extends Seeder
{
    /**
     * Jalankan akun demo untuk login ke aplikasi.
     * Password semua akun: password
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin Garage88',
                'email' => 'admin@garage88.test',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'name' => 'Teknisi Garage88',
                'email' => 'teknisi@garage88.test',
                'password' => 'password',
                'role' => 'teknisi',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@garage88.test',
                'password' => 'password',
                'role' => 'pelanggan',
                'alamat' => 'Jl. Raya Cianjur No. 10',
                'no_hp' => '081234567890',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $account['password'],
                    'role' => $account['role'],
                ]
            );

            if (($account['role'] ?? null) === 'pelanggan') {
                Pelanggan::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'alamat' => $account['alamat'] ?? 'Alamat belum diisi',
                        'no_hp' => $account['no_hp'] ?? '000000000000',
                    ]
                );
            }
        }
    }
}
