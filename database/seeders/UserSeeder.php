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
        $recap = [];

        $admin = User::create([
            'name' => 'Admin BPR',
            'email' => 'admin@bpr.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $recap[] = ['Admin', $admin->email, 'password', $admin->name];

        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
        ]);

        Nasabah::create([
            'user_id' => $user1->id,
            'nama' => $user1->name,
            'nik' => '3201012001850001',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-01-20',
            'no_hp' => '081234567890',
            'foto_ktp' => 'ktp_budi.jpg',
        ]);

        $recap[] = ['Nasabah', $user1->email, 'password', $user1->name];

        $user2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
        ]);

        Nasabah::create([
            'user_id' => $user2->id,
            'nama' => $user2->name,
            'nik' => '3201012002900002',
            'alamat' => 'Jl. Sudirman No. 456, Jakarta Selatan',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-02-15',
            'no_hp' => '082345678901',
            'foto_ktp' => 'ktp_siti.jpg',
        ]);

        $recap[] = ['Nasabah', $user2->email, 'password', $user2->name];


        $this->command->info("Seeder berhasil dijalankan:");
        
        $this->command->table(
            ['Role', 'Email', 'Password', 'Nama User'],
            $recap
        );        
    }
}