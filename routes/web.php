<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\EmployeeManagementController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DocumentController;

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

    // --- 1. DASHBOARD UTAMA (Logika Redirect Berdasarkan Role) ---
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'hr') {
            return redirect()->route('hr.dashboard');
        }

        // Logika Dashboard Karyawan
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
        
        // Dashboard HR & Analisis Resiko AI
        Route::get('/dashboard', function () {
            // Ambil data dengan pagination agar muncul nomor halaman
            $employees = User::where('role', 'karyawan')->paginate(10);

            // Jalankan kalkulasi risiko lewat Service untuk data yang tampil
            foreach ($employees as $emp) {
                \App\Services\ResignationAIService::calculateRisk($emp);
            }

            $totalHadir = Attendance::where('date', now()->toDateString())->count();
            $pendingCuti = Leave::where('status', 'pending')->count();

            return view('dashboard-hr', compact('totalHadir', 'pendingCuti', 'employees'));
        })->name('dashboard');

        // Manajemen Karyawan (CRUD)
        Route::resource('employees', EmployeeManagementController::class);

        // Payroll
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/process/{id}', [PayrollController::class, 'process'])->name('payroll.process');

        // Pengumuman
        Route::resource('announcements', AnnouncementController::class);

        // Detail & Analisis Individu Karyawan
        Route::get('/employee-detail/{user}', function (User $user) {
            $avgLate = Attendance::where('user_id', $user->id)
                ->where('check_in', '>', '08:00:00')->count();

            $totalCuti = Leave::where('user_id', $user->id)
                ->where('status', 'approved')->count();

            $riskHistory = RiskLog::where('user_id', $user->id)
                ->latest()->take(6)->get()->reverse();

            return view('hr.employee-detail', compact('user', 'avgLate', 'totalCuti', 'riskHistory'));
        })->name('employee.detail');

        // Fitur Kirim Peringatan AI
        Route::post('/send-warning/{user}', function (User $user) {
            $user->update([
                'has_warning' => true,
                'warning_message' => 'AI mendeteksi resiko resign Anda tinggi. Mohon hubungi HR untuk diskusi retensi.'
            ]);
            return back()->with('success', 'Peringatan berhasil dikirim ke ' . $user->name);
        })->name('send.warning');
    });


    // --- 3. FITUR UMUM & LAYANAN (Event, Dokumen, Cuti) ---
    Route::resource('events', EventController::class);
    Route::resource('documents', DocumentController::class);

    // Fitur Cuti (Pengajuan & Persetujuan)
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


    // --- 5. FITUR PROFILE & NOTIFIKASI USER ---
    Route::post('/warning-read', function () {
        Auth::user()->update([
            'has_warning' => false,
            'warning_message' => null
        ]);
        return back()->with('success', 'Peringatan telah dikonfirmasi.');
    })->name('warning.read');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::get('/karyawan/slip-gaji', [PayrollController::class, 'mySlip'])->name('karyawan.slip');
});

require __DIR__ . '/auth.php';