<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model 
{
    use HasFactory;

    // Nama tabel di database (opsional jika nama tabelnya 'cutis')
    protected $table = 'cutis'; 

    // Daftar kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status'
    ];
}