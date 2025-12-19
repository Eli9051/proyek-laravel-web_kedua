<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tambahkan 1 Akun HRD untuk kamu Login
        User::create([
            'name' => 'HRD Manager',
            'email' => 'hrd@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'hr',
            'nik' => 'HR001',
            'position' => 'Human Resource Manager',
        ]);

        // 2. Tambahkan 5 Karyawan dengan Risiko Rendah (Hati Senang)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Karyawan Rajin $i",
                'email' => "rajin$i@perusahaan.com",
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'nik' => "202500$i",
                'position' => 'Staff Operasional',
                'resign_risk_score' => rand(5, 25), // Skor rendah
                'status' => 'active',
            ]);
        }

        // 3. Tambahkan 2 Karyawan dengan Risiko Tinggi (Sedang Galau)
        User::create([
            'name' => 'Siti Galau',
            'email' => 'siti@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'nik' => '2025099',
            'position' => 'Senior Designer',
            'resign_risk_score' => 85, // Skor tinggi!
            'status' => 'active',
        ]);
    }
}