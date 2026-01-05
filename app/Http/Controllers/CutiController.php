<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave; // <--- GANTI INI
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CutiController extends Controller
{
    public function create()
    {
        return view('cuti.ajukan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $start = new \DateTime($request->start_date);
        $end = new \DateTime($request->end_date);
        $durasi = $start->diff($end)->days + 1;

        // CEK KUOTA
        if ($user->kuota_cuti < $durasi) {
            return redirect()->back()->with('error', "Gagal! Jatah cuti Anda tidak mencukupi (Sisa: {$user->kuota_cuti} hari).");
        }

        // SIMPAN DATA
        DB::transaction(function () use ($user, $request, $durasi) {
            // Kurangi jatah di tabel users
            $user->decrement('kuota_cuti', $durasi);

            // Gunakan Model Leave, bukan Cuti
            Leave::create([
                'user_id'    => $user->id,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'reason'     => $request->reason,
                'status'     => 'pending',
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Pengajuan cuti berhasil dikirim!');
    }
}