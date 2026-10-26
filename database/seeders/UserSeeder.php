<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Akun
        User::updateOrCreate(
            ['email' => 'admin@pamekasan-gis.id'],
            [
                'name' => 'Administrator WebGIS Pamekasan',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Pengunjung Terdaftar / Operator Publik
        User::updateOrCreate(
            ['email' => 'pengunjung@pamekasan-gis.id'],
            [
                'name' => 'Pengunjung Terdaftar',
                'password' => Hash::make('pengunjung123'),
                'role' => 'pengunjung',
                'email_verified_at' => now(),
            ]
        );
    }
}
