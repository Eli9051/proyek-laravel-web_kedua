<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Leave::create([
            'user_id' => 2, //ID karyawan yang mau cuti
            'reason' => 'Urusan keluarga',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(7),
            'status' => 'pending',
        ]);
    }
}
