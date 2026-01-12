<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    public function index()
    {
        $employees = \App\Models\User::where('role', 'karyawan')->get();
        $performanceData = [];

        foreach ($employees as $emp) {
            // 1. Hitung Kedisiplinan (Hadir tepat waktu)
            $totalAttendance = \App\Models\Attendance::where('user_id', $emp->id)->whereMonth('date', now()->month)->count();
            $onTime = \App\Models\Attendance::where('user_id', $emp->id)
                ->whereMonth('date', now()->month)
                ->where('check_in', '<=', '08:00:00')->count();

            $disciplineScore = $totalAttendance > 0 ? ($onTime / $totalAttendance) * 100 : 0;

            // 2. Hitung Produktivitas (Total Jam Lembur Approved)
            $overtimeHours = \App\Models\Overtime::where('user_id', $emp->id)
                ->where('status', 'approved')
                ->whereMonth('date', now()->month)
                ->sum(\DB::raw('TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600'));

            $productivityScore = min(($overtimeHours / 20) * 100, 100); // Target 20 jam lembur/bulan untuk skor 100

            // 3. Nilai Akhir
            $finalScore = ($disciplineScore * 0.6) + ($productivityScore * 0.4);

            $performanceData[] = [
                'name' => $emp->name,
                'discipline' => $disciplineScore,
                'productivity' => $productivityScore,
                'final' => $finalScore
            ];
        }

        return view('hr.kpi_index', compact('performanceData'));
    } //
}
