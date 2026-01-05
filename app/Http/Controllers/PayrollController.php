<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PayrollController extends Controller
{
	// app/Http/Controllers/PayrollController.php

	// app/Http/Controllers/PayrollController.php

	public function index()
	{
		$employees = User::where('role', 'karyawan')
			->with(['attendances' => function ($query) {
				// Gunakan kolom 'date' sesuai dengan struktur database Anda
				$query->whereMonth('date', now()->month);
			}])->get();

		// Pastikan pemanggilan view sesuai dengan folder: hr -> employees -> payroll_index
		return view('hr.payroll_index', compact('employees'));
	}

	public function process($id)
	{
		$user = \App\Models\User::findOrFail($id);

		// Logika perhitungan keterlambatan
		$lateCount = \App\Models\Attendance::where('user_id', $user->id)
			->whereMonth('date', now()->month)
			->where('check_in', '>', '08:00:00')
			->count();

		$latePenalty = $lateCount * 25000;

		// Potongan Disiplin (Warning)
		$warningPenalty = $user->has_warning ? ($user->basic_salary * 0.1) : 0;

		$totalPenalty = $latePenalty + $warningPenalty;
		$netSalary = $user->basic_salary - $totalPenalty;

		// PASTIKAN BARIS INI ADA: Mengembalikan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success', "Payroll {$user->name} berhasil dihitung. Gaji Bersih: Rp " . number_format($netSalary, 0, ',', '.'));
	}

	public function mySlip()
	{
		$user = \Illuminate\Support\Facades\Auth::user();

		// Kita hitung telat berdasarkan waktu check_in > 08:00:00 (Sesuai logika dashboard HR kamu)
		$lateCount = \App\Models\Attendance::where('user_id', $user->id)
			->whereMonth('date', now()->month) // Gunakan kolom 'date' sesuai migrasi kamu
			->where('check_in', '>', '08:00:00')
			->count();

		$latePenalty = $lateCount * 25000;

		// Potongan Warning (Sinkron dengan has_warning)
		$warningPenalty = $user->has_warning ? ($user->basic_salary * 0.1) : 0;

		$netSalary = $user->basic_salary - ($latePenalty + $warningPenalty);

		return view('karyawan.slip_gaji', compact('user', 'lateCount', 'latePenalty', 'warningPenalty', 'netSalary'));
	}
}
