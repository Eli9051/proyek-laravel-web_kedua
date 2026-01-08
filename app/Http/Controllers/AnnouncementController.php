<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Menampilkan daftar pengumuman di sisi HR
     */
    public function index()
    {
        // Mengambil semua pengumuman, urutkan dari yang terbaru
        $announcements = Announcement::latest()->paginate(10);
        
        return view('hr.announcements.index', compact('announcements'));
    }

    /**
     * Menampilkan form tambah pengumuman
     */
    public function create()
    {
        return view('hr.announcements.create');
    }

    /**
     * Menyimpan pengumuman baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'type' => 'required|in:info,warning,danger',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'user_id' => Auth::id(), // HR yang membuat
        ]);

        return redirect()->route('hr.announcements.index')
                         ->with('success', 'Pengumuman berhasil diterbitkan!');
    }

    /**
     * Menghapus pengumuman
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}