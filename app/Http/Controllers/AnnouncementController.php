<?php

// app/Http/Controllers/AttendanceController.php

namespace App\Http\Controllers;

use App\Models\Attendance; // Pastikan ini diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Koordinat Kantor (Sesuaikan dengan koordinat kantor Anda)
    const OFFICE_LAT = -6.200000; 
    const OFFICE_LONG = 106.816666;
    const MAX_RADIUS_KM = 0.1; // 100 Meter

    public function checkIn(Request $request)
    {
        $userLat = $request->lat;
        $userLong = $request->long;

        // Rumus Haversine
        $earthRadius = 6371;
        $dLat = deg2rad($userLat - self::OFFICE_LAT);
        $dLon = deg2rad($userLong - self::OFFICE_LONG);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad(self::OFFICE_LAT)) * cos(deg2rad($userLat)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        $isOutside = $distance > self::MAX_RADIUS_KM;

        // Simpan Data
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'check_in' => now()->toTimeString(),
            'latitude' => $userLat,
            'longitude' => $userLong,
            'is_outside' => $isOutside,
            'status' => $isOutside ? 'Luar Kantor' : 'Hadir'
        ]);

        $message = $isOutside 
            ? 'Absen berhasil, namun Anda berada di luar jangkauan kantor. HRD telah dinotifikasi.' 
            : 'Berhasil! Lokasi Anda tervalidasi di area kantor.';

        return back()->with($isOutside ? 'warning' : 'success', $message);
    }
} // Pastikan penutup ini ada di baris 47