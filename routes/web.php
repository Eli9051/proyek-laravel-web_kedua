<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Impor Semua Controller
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\EmployeeManagementController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AttendanceController;

// Impor Model
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\RiskLog;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // --- 1. DASHBOARD UTAMA (Redirect Berdasarkan Role) ---
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

    // --- 2. FITUR KHUSUS HR (Prefix: hr) ---
    Route::prefix('hr')->name('hr.')->group(function () {

        // Dashboard HR (Logika Statistik, AI, Peringatan Lokasi, & Grafik)
        Route::get('/dashboard', function () {
            // 1. Data Karyawan & AI
            $employees = User::where('role', 'karyawan')->paginate(10);
            foreach ($employees as $emp) {
                \App\Services\ResignationAIService::calculateRisk($emp);
            }

            // 2. Statistik Dasar
            $totalHadir = Attendance::where('date', now()->toDateString())->count();
            $pendingCuti = Leave::where('status', 'pending')->count();

            // 3. Peringatan Lokasi (Limit 3 terbaru)
            $locationWarnings = Attendance::with('user')
                ->where('is_outside', true)
                ->where('hr_reviewed', false)
                ->latest()
                ->take(3)
                ->get();

            // 4. DATA GRAFIK DINAMIS (Mengambil dari tabel RiskLog)
            $logs = RiskLog::orderBy('id', 'desc')->take(4)->get()->reverse();
            $chartData = $logs->pluck('risk_score')->toArray();
            $chartLabels = $logs->pluck('period')->toArray();

            return view('dashboard-hr', compact(
                'totalHadir', 
                'pendingCuti', 
                'employees', 
                'locationWarnings', 
                'chartData', 
                'chartLabels'
            ));
        })->name('dashboard');

        // Halaman Khusus Pelanggaran Lokasi
        Route::get('/pelanggaran-lokasi', [AttendanceController::class, 'indexHR'])->name('attendance.index');

        // Konfirmasi Semua Pelanggaran
        Route::post('/attendance/confirm-all', function () {
            Attendance::where('is_outside', true)
                ->where('hr_reviewed', false)
                ->update(['hr_reviewed' => true]);
            return back()->with('success', 'Semua pelanggaran berhasil dibersihkan!');
        })->name('attendance.confirmAll');

        // Konfirmasi Satuan
        Route::post('/attendance-confirm/{attendance}', function ($id) {
            $absen = Attendance::findOrFail($id);
            $absen->update(['hr_reviewed' => true]);
            return back()->with('success', 'Peringatan berhasil dikonfirmasi!');
        })->name('attendance.confirm');

        // Manajemen Karyawan & Fitur HR Lainnya
        Route::resource('employees', EmployeeManagementController::class);
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/process/{id}', [PayrollController::class, 'process'])->name('payroll.process');
        Route::resource('announcements', AnnouncementController::class);

        // Detail & Send Warning AI
        Route::get('/employee-detail/{user}', function (User $user) {
            $avgLate = Attendance::where('user_id', $user->id)->where('check_in', '>', '08:00:00')->count();
            $totalCuti = Leave::where('user_id', $user->id)->where('status', 'approved')->count();
            $riskHistory = RiskLog::where('user_id', $user->id)->latest()->take(6)->get()->reverse();
            return view('hr.employee-detail', compact('user', 'avgLate', 'totalCuti', 'riskHistory'));
        })->name('employee.detail');

        Route::post('/send-warning/{user}', function (User $user) {
            $user->update([
                'has_warning' => true,
                'warning_message' => 'AI mendeteksi resiko resign Anda tinggi. Mohon hubungi HR untuk diskusi retensi.'
            ]);
            return back()->with('success', 'Peringatan berhasil dikirim ke ' . $user->name);
        })->name('send.warning');
    });

    // --- 3. FITUR KARYAWAN & UMUM ---
    Route::get('/profil-saya', [KaryawanController::class, 'profile'])->name('profile.show');
    Route::get('/riwayat-absensi', [KaryawanController::class, 'riwayatAbsen'])->name('karyawan.riwayat');
    Route::get('/karyawan/slip-gaji', [PayrollController::class, 'mySlip'])->name('karyawan.slip');
    
    Route::resource('overtimes', OvertimeController::class);
    Route::resource('inventories', InventoryController::class);
    Route::get('/kpi', [KpiController::class, 'index'])->name('kpi.index');
    Route::resource('events', EventController::class);
    Route::resource('documents', DocumentController::class);

    // Fitur Cuti
    Route::get('/cuti/ajukan', function () {
        return view('cuti.ajukan', ['user' => Auth::user()]);
    })->name('cuti.ajukan');
    Route::post('/cuti/simpan', [CutiController::class, 'store'])->name('cuti.store');
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

    // --- 4. SISTEM ABSENSI ---
    Route::post('/absen/masuk', [AttendanceController::class, 'checkIn'])->name('absen.masuk');
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

    // Profile & Notifikasi AI Karyawan
    Route::post('/warning-read', function () {
        Auth::user()->update(['has_warning' => false, 'warning_message' => null]);
        return back()->with('success', 'Peringatan telah dikonfirmasi.');
    })->name('warning.read');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';