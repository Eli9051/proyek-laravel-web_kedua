<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\EmployeeManagementController;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\RiskLog;
use App\Models\User;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DocumentController;


/*
|--------------------------------------------------------------------------
| Web Routes - HR & Employee System
|--------------------------------------------------------------------------
*/


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('documents', DocumentController::class);

    // --- 1. DASHBOARD UTAMA (Logika Redirect) ---
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'hr') {
            return redirect()->route('hr.dashboard');
        }

        $userId = Auth::id();
        $hariIni = now()->format('Y-m-d');

        $sedangCuti = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $hariIni)
            ->whereDate('end_date', '>=', $hariIni)
            ->exists();

        $attendances = Attendance::where('user_id', $userId)->latest()->take(5)->get();

        return view('dashboard', compact('sedangCuti', 'attendances'));
    })->name('dashboard');

    // --- 2. MANAJEMEN KARYAWAN & HR DASHBOARD ---
    // Mengelompokkan rute HR agar lebih rapi
     Route::resource('events', EventController::class);
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::resource('announcements', AnnouncementController::class);

        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/process/{id}', [PayrollController::class, 'process'])->name('payroll.process');

        // Dashboard HR & Analisis Resiko
        Route::get('/dashboard', function () {
            $employees = User::where('role', 'karyawan')->get();
            $dataResiko = [];

            foreach ($employees as $emp) {
                $skor = 0;
                $attendances = Attendance::where('user_id', $emp->id)
                    ->whereMonth('date', now()->month)
                    ->get();

                $terlambat = $attendances->where('check_in', '>', '08:00:00')->count();
                $skor += ($terlambat * 10);

                $skorFinal = min($skor, 100);
                $status = $skorFinal > 70 ? 'Tinggi' : ($skorFinal > 40 ? 'Sedang' : 'Rendah');

                RiskLog::updateOrCreate(
                    ['user_id' => $emp->id, 'period' => now()->format('F Y')],
                    ['score' => $skorFinal, 'status' => $status, 'reason' => "Terlambat $terlambat kali"]
                );

                $dataResiko[] = [
                    'id' => $emp->id,
                    'nama' => $emp->name,
                    'skor' => $skorFinal,
                    'status' => $status
                ];
            }

            $totalHadir = Attendance::where('date', now()->toDateString())->count();
            $pendingCuti = Leave::where('status', 'pending')->count();

            return view('dashboard-hr', compact('totalHadir', 'pendingCuti', 'dataResiko'));
        })->name('dashboard');

        // CRUD Karyawan (Laravel otomatis membuat route hr.employees.index, dll)
        Route::resource('employees', EmployeeManagementController::class);
       

        // Fitur Payroll (Disederhanakan karena sudah ada prefix 'hr' dan name 'hr.')
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/process/{id}', [PayrollController::class, 'process'])->name('payroll.process');

        // Detail Karyawan
        Route::get('/employee/{user}', function (User $user) {
            $avgLate = Attendance::where('user_id', $user->id)
                ->where('check_in', '>', '08:00:00')->count();

            $totalCuti = Leave::where('user_id', $user->id)
                ->where('status', 'approved')->count();

            $riskHistory = RiskLog::where('user_id', $user->id)
                ->latest()->take(6)->get()->reverse();

            return view('hr.employee-detail', compact('user', 'avgLate', 'totalCuti', 'riskHistory'));
        })->name('employee.detail');

        // Kirim Peringatan
        Route::post('/send-warning/{user}', function (User $user) {
            $user->update([
                'has_warning' => true,
                'warning_message' => 'AI mendeteksi resiko resign Anda tinggi. Mohon hubungi HR untuk diskusi retensi.'
            ]);
            return back()->with('success', 'Peringatan berhasil dikirim ke ' . $user->name);
        })->name('send.warning');
    });

    // --- 3. FITUR CUTI ---
    Route::controller(CutiController::class)->group(function () {
        Route::get('/cuti/ajukan', function () {
            return view('cuti.ajukan', ['user' => Auth::user()]);
        })->name('cuti.ajukan');

        Route::post('/cuti/simpan', 'store')->name('cuti.store');
    });

    Route::get('/persetujuan-cuti', function () {
        $leaves = Leave::with('user')->where('status', 'pending')->get();
        return view('leaves.index', compact('leaves'));
    })->name('leaves.index');

    Route::post('/leaves/{leave}/action', function (Request $request, Leave $leave) {
        if ($request->action === 'rejected') {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);
            $durasi = $start->diffInDays($end) + 1;
            $leave->user->increment('kuota_cuti', $durasi);
        }
        $leave->update(['status' => $request->action]);
        return back()->with('success', 'Status cuti diperbarui!');
    })->name('leaves.action');

    // --- 4. FITUR ABSENSI ---
    Route::post('/absen/masuk', function (Request $request) {
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'check_in' => now()->toTimeString(),
            'latitude' => $request->lat,
            'longitude' => $request->long,
            'status' => 'Hadir'
        ]);
        return back()->with('success', 'Berhasil! Lokasi Anda tervalidasi.');
    })->name('absen.masuk');

    Route::post('/absen/pulang', function () {
        $attendance = Attendance::where('user_id', Auth::id())
            ->where('date', now()->toDateString())
            ->whereNull('check_out')
            ->first();

        if ($attendance) {
            $attendance->update(['check_out' => now()->toTimeString()]);
            return back()->with('success', 'Berhasil! Selamat beristirahat.');
        }
        return back()->with('error', 'Gagal! Anda belum absen masuk atau sudah absen pulang.');
    })->name('absen.pulang');

    // --- 5. SISTEM KONFIRMASI PERINGATAN (User) ---
    Route::post('/warning-read', function () {
        Auth::user()->update([
            'has_warning' => false, // Di database biasanya boolean (0/1)
            'warning_message' => null
        ]);
        return back()->with('success', 'Peringatan telah dikonfirmasi.');
    })->name('warning.read');

    // --- 6. PROFILE ---
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
    //slip gaji 
    Route::get('/karyawan/slip-gaji', [PayrollController::class, 'mySlip'])->name('karyawan.slip');
});

require __DIR__ . '/auth.php';
