<?php
namespace App\Services;

class ResignationAIService {
    public static function calculateRisk($user) {
        $score = 0;
        
        // 1. Cek jumlah cuti (Data Lunak)
        $jumlahCuti = $user->leaves()->count();
        $score += ($jumlahCuti * 10); 

        // 2. Cek keterlambatan (Data Keras)
        // Kita asumsikan jika absen di atas jam 08:00
        $lateCount = $user->attendances()->where('check_in', '>', '08:00:00')->count();
        $score += ($lateCount * 15);

        // Batasi skor maksimal 100
        $finalScore = min($score, 100);
        
        $status = 'Aman';
        if ($finalScore > 70) $status = 'Bahaya';
        elseif ($finalScore > 40) $status = 'Waspada';

        return ['score' => $finalScore, 'status' => $status];
    }
}