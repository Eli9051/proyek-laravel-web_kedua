<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        //tong ambil semua data karyawan dari database
        $employees = \App\Models\Karyawan::where('role', 'karyawan')->paginate(5);


        // ini kode untuk kirim atau kode pengiriman ke file blade 
        return view('dashboard-hr-employees', compact('employees'))
    }
}
