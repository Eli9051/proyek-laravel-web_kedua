<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmployeeManagementController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'karyawan')->get();
        return view('hr.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('hr.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'karyawan',
            'status' => 'active',
            'basic_salary' => $request->basic_salary,
        ]);

        return redirect()->route('hr.employees.index')->with('success', 'Karyawan baru berhasil didaftarkan.');
    }

    // PASTIKAN METHOD EDIT INI ADA
    public function edit($id)
    {
        $employee = \App\Models\User::findOrFail($id);
        return view('hr.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'basic_salary' => 'required|numeric|min:0', // Tambahkan ini
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->basic_salary = $request->basic_salary; // Simpan ke database

        if ($request->filled('password')) {
            $employee->password = bcrypt($request->password);
        }

        $employee->save();

        return redirect()->route('hr.employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();
        return redirect()->route('hr.employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
