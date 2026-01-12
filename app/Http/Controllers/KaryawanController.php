<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    // Menampilkan profil lengkap karyawan
    public function profile()
    {
        $user = Auth::user();
        return view('karyawan.profile', compact('user'));
    }

    // Menampilkan riwayat absen mandiri
    public function riwayatAbsen()
    {
        $riwayat = Attendance::where('user_id', Auth::id())
                    ->orderBy('date', 'desc')
                    ->paginate(10);
        return view('karyawan.riwayat', compact('riwayat'));
    }
}