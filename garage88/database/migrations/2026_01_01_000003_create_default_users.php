<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $users = [
            ['name' => 'Admin Garage88', 'email' => 'admin@garage88.test', 'role' => 'admin'],
            ['name' => 'Teknisi Garage88', 'email' => 'teknisi@garage88.test', 'role' => 'teknisi'],
            ['name' => 'Budi Santoso', 'email' => 'budi@garage88.test', 'role' => 'pelanggan'],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('users')
            ->whereIn('email', [
                'admin@garage88.test',
                'teknisi@garage88.test',
                'budi@garage88.test',
            ])
            ->delete();
    }
};
