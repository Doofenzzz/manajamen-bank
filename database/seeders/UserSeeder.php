<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin BPR',
            'email' => 'admin@bpr.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Nasabah 1
        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
        ]);

        Nasabah::create([
            'user_id' => $user1->id,
            'nik' => '3201012001850001',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'tanggal_lahir' => '1985-01-20',
            'no_hp' => '081234567890',
        ]);

        // Nasabah 2
        $user2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
        ]);

        Nasabah::create([
            'user_id' => $user2->id,
            'nik' => '3201012002900002',
            'alamat' => 'Jl. Sudirman No. 456, Jakarta Selatan',
            'tanggal_lahir' => '1990-02-15',
            'no_hp' => '082345678901',
        ]);
    }
}