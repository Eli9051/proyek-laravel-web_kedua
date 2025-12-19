<?php
use App\Models\User;
use App\Models\Attendance;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    // Ambil semua user untuk tabel HR
    $employees = User::where('role', 'karyawan')->get();

    // Ambil data absen hari ini untuk user yang sedang login
    $attendance = Attendance::where('user_id', auth()->id())
                    ->where('date', now()->toDateString())
                    ->first();

    return view('dashboard', compact('employees', 'attendance'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
