<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index() {
        $documents = Document::latest()->get();
        return view('documents.index', compact('documents'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'file' => 'required|mimes:pdf|max:2048', // Batas 2MB PDF
        ]);

        $path = $request->file('file')->store('company_documents', 'public');

        Document::create([
            'title' => $request->title,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Dokumen SOP berhasil diunggah!');
    }
}