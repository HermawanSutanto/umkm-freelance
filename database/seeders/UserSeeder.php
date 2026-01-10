<?php

namespace Database\Seeders;

use App\Models\AdminProfile;
use App\Models\FreelancerProfile;
use App\Models\MitraProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- AKUN 1: Super Admin ---
        $admin1 = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);
        
        // Jangan lupa buat profilnya
        AdminProfile::create([
            'user_id' => $admin1->id,
            'phone_number' => '081234567890',
        ]);

    // --- AKUN 2: Admin Cadangan / Staff ---
        $admin2 = User::create([
            'name' => 'Admin umkm',
            'email' => 'mitra@example.com', // Email HARUS beda 
            'password' => Hash::make('mitra123'),
            'email_verified_at' => now(),
            'role' => 'mitra',
        ]);

        

        // 2. Buat Admin Profile yang terhubung
        MitraProfile::create([
            'user_id' => $admin2->id,
            'shop_name' => 'Toko Online', // Sesuaikan jika ada kolom ini
        ]);
        $admin3 = User::create([
            'name' => 'Admin umkm',
            'email' => 'freelancer@example.com', // Email HARUS beda
            'password' => Hash::make('freelancer123'),
            'email_verified_at' => now(),
            'role' => 'freelancer',
        ]);
            // 2. Buat Admin Profile yang terhubung
        FreelancerProfile::create([
            'user_id' => $admin3->id,
            'skills' => 'Graphics Designer', // Sesuaikan jika ada kolom ini
        ]);
        //
    }
}
