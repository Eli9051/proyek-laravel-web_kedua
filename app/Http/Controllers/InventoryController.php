<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        // Jika HR, tampilkan semua aset yang dipinjamkan
        if (strtolower(auth()->user()->role) === 'hr') {
            $inventories = Inventory::with('user')->latest()->paginate(10);
            $employees = User::where('role', 'karyawan')->get();
            return view('inventories.index', compact('inventories', 'employees'));
        }

        // Jika Karyawan, hanya tampilkan aset milik mereka
        $inventories = Inventory::where('user_id', auth()->id())->get();
        return view('inventories.index', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_name' => 'required|string',
            'item_code' => 'required|string|unique:inventories,item_code',
            'loan_date' => 'required|date',
        ]);

        Inventory::create($request->all());

        return back()->with('success', 'Aset berhasil ditambahkan ke karyawan!');
    }
    public function update(Request $request, Inventory $inventory)
    {
        if (strtolower(auth()->user()->role) !== 'hr') {
            abort(403);
        }

        $inventory->update([
            'return_date' => now(),
            'condition' => $request->condition ?? 'good',
        ]);

        return redirect()->route('inventories.index')->with('success', 'Aset berhasil ditandai kembali!');
    }
}
