<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Mengecek apakah user berada dalam radius kantor.
     */
    public function checkLocation(Request $request)
    {
        // 1. Validasi Input: Pastikan lat dan long dikirim
        $request->validate([
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        // 2. Koordinat Kantor (Contoh: Poltek BH)
        $latOffice = -7.0483; 
        $longOffice = 110.4410;
        $radiusKm = 0.1; // 100 Meter

        // 3. Ambil data dari request
        $latUser = $request->lat;
        $longUser = $request->long;

        // 4. Rumus Haversine untuk hitung jarak
        $earthRadius = 6371; // Radius bumi dalam kilometer
        
        $dLat = deg2rad($latUser - $latOffice);
        $dLong = deg2rad($longUser - $longOffice);
        
        $a = sin($dLat / 2) * sin($dLat / 2) + 
             cos(deg2rad($latOffice)) * cos(deg2rad($latUser)) * sin($dLong / 2) * sin($dLong / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        // 5. Logika Pengecekan
        if ($distance <= $radiusKm) {
            return response()->json([
                'status' => 'success',
                'distance_meters' => round($distance * 1000, 2),
                'message' => 'Anda berada di dalam radius kantor (Absensi Berhasil)!'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'distance_meters' => round($distance * 1000, 2),
                'message' => 'Anda berada di luar radius! Jarak Anda: ' . round($distance * 1000) . ' meter.'
            ], 403);
        }
    }
        public function index()
        {
            $userId = auth()->id();
            $today = now()->toDateString();

            // Cari apakah sudah ada catatan absen hari ini
            $attendance = \App\Models\Attendance::where('user_id', $userId)
                            ->where('date', $today)
                            ->first();

            return view('karyawan.dashboard', compact('attendance'));
        }

};