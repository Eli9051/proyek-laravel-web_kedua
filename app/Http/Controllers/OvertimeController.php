<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function index()
    {
        // Jika User adalah HR
        if (strtolower(auth()->user()->role) === 'hr') {
            // Mengambil semua data lembur beserta data User-nya
            $overtimes = \App\Models\Overtime::with('user')->latest()->paginate(10);
            return view('overtimes.index', compact('overtimes'));
        }

        // Jika User adalah Karyawan
        $overtimes = \App\Models\Overtime::where('user_id', auth()->id())->latest()->get();
        return view('overtimes.index', compact('overtimes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string|min:10',
        ]);

        Overtime::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan lembur berhasil dikirim!');
    }

    // Fungsi khusus HR untuk menyetujui/menolak
    public function update(Request $request, Overtime $overtime)
    {
        if (strtolower(Auth::user()->role) !== 'hr') {
            abort(403);
        }

        $overtime->update(['status' => $request->status]);
        return back()->with('success', 'Status lembur berhasil diperbarui!');
    }
}
