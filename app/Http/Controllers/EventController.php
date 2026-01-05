<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function index()
    {
        $events = \App\Models\Event::orderBy('event_date', 'asc')->get();
        return view('hr.events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        // Hanya masukkan data yang sudah divalidasi (tidak termasuk _token)
        Event::create($validated);

        return back()->with('success', 'Event berhasil ditambahkan ke kalender!');
    }
}
