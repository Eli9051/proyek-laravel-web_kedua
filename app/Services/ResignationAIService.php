<?php

namespace App\Services;

use App\Models\User;
use App\Models\Attendance;
use App\Models\RiskLog;

class ResignationAIService
{
    /**
     * Menghitung skor resiko resign berdasarkan absensi dan menyimpan lognya.
     */
    public static function calculateRisk(User $user)
    {
        $skor = 0;
        
        // Ambil data absensi bulan ini
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', now()->month)
            ->get();

        // Logika keterlambatan (Setiap telat > 08:00 tambah 10 poin)
        $terlambat = $attendances->where('check_in', '>', '08:00:00')->count();
        $skor += ($terlambat * 10);

        // Pastikan skor maksimal 100
        $skorFinal = min($skor, 100);
        
        // Tentukan status berdasarkan skor
        $status = $skorFinal > 70 ? 'Tinggi' : ($skorFinal > 40 ? 'Sedang' : 'Rendah');

        // Simpan atau update ke database RiskLog
        RiskLog::updateOrCreate(
            ['user_id' => $user->id, 'period' => now()->format('F Y')],
            [
                'score' => $skorFinal, 
                'status' => $status, 
                'reason' => "Terlambat sebanyak $terlambat kali bulan ini"
            ]
        );

        return [
            'score' => $skorFinal,
            'status' => $status,
            'late_count' => $terlambat
        ];
    }
}