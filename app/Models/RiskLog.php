<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskLog extends Model
{
    // Kolom yang boleh diisi sesuai migrasi yang kita buat sebelumnya
    protected $fillable = [
        'user_id',
        'score',
        'status',
        'period',
        'reason'
    ];

    // Relasi kembali ke User (Karyawan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}