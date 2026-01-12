<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    // INI IZINNYA: Daftarkan kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'latitude', // Tambahkan ini
        'longitude', // Tambahkan ini
        'is_outside', // Tambahkan ini
        'status',
        'hr_reviewed',
    ];

    // Tambahkan juga hubungan ke User agar Eli bisa dikenali
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
