<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Panggil surat izinnya
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory; // 2. Pakai surat izinnya di sini!

    protected $table = 'users';
    // Tambahkan ini juga supaya datanya bisa diisi otomatis
    protected $fillable = ['name', 'position', 'resign_risk_score', 'status'];
}