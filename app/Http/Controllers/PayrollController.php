<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Menampilkan daftar karyawan untuk diproses payroll oleh HR.
     */
    public function index()
    {
        $employees = User::where('role', 'karyawan')
            ->with(['attendances' => function ($query) {
                $query->whereMonth('date', now()->month);
            }])->get();

        return view('hr.payroll_index', compact('employees'));
    }

    /**
     * Memproses gaji karyawan termasuk hitungan lembur, keterlambatan, dan denda.
     */
    public function process($id)
    {
        $user = User::findOrFail($id);
        $month = now()->month;
        $year = now()->year;

        // 1. Hitung Lembur (Hanya yang berstatus 'approved')
        $approvedOvertimes = Overtime::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $totalOvertimeHours = 0;
        foreach ($approvedOvertimes as $ot) {
            $start = Carbon::parse($ot->start_time);
            $end = Carbon::parse($ot->end_time);
            $totalOvertimeHours += $start->diffInMinutes($end) / 60;
        }

        // Rumus Upah Lembur (Gaji Pokok / 173)
        $overtimeRate = $user->basic_salary / 173;
        $overtimePay = $totalOvertimeHours * $overtimeRate;

        // 2. Hitung Potongan Keterlambatan (> 08:00:00)
        $lateCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->where('check_in', '>', '08:00:00')
            ->count();
        $latePenalty = $lateCount * 25000;

        // 3. Potongan Disiplin (Jika ada warning)
        $warningPenalty = $user->has_warning ? ($user->basic_salary * 0.1) : 0;

        // 4. Kalkulasi Gaji Bersih
        $netSalary = ($user->basic_salary + $overtimePay) - ($latePenalty + $warningPenalty);

        return redirect()->back()->with('success', 
            "Payroll {$user->name} berhasil dihitung! Lembur: " . number_format($totalOvertimeHours, 1) . 
            " Jam (Rp " . number_format($overtimePay, 0, ',', '.') . "). Gaji Bersih: Rp " . number_format($netSalary, 0, ',', '.')
        );
    }

    /**
     * Menampilkan slip gaji untuk sisi karyawan.
     */
    public function mySlip()
    {
        $user = auth()->user();
        $month = now()->month;

        // Hitung ulang data untuk tampilan slip
        $overtimes = Overtime::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereMonth('date', $month)
            ->get();

        $totalHours = 0;
        foreach ($overtimes as $ot) {
            $totalHours += Carbon::parse($ot->start_time)->diffInMinutes(Carbon::parse($ot->end_time)) / 60;
        }

        $overtimePay = $totalHours * ($user->basic_salary / 173);
        $lateCount = Attendance::where('user_id', $user->id)->whereMonth('date', $month)->where('check_in', '>', '08:00:00')->count();
        $latePenalty = $lateCount * 25000;
        $warningPenalty = $user->has_warning ? ($user->basic_salary * 0.1) : 0;
        
        $netSalary = ($user->basic_salary + $overtimePay) - ($latePenalty + $warningPenalty);

        return view('karyawan.slip_gaji', compact('user', 'totalHours', 'overtimePay', 'lateCount', 'latePenalty', 'warningPenalty', 'netSalary'));
    }
}