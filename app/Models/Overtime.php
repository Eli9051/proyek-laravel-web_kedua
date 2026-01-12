<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    // Mengizinkan kolom-kolom ini diisi
    protected $fillable = ['user_id', 'date', 'start_time', 'end_time', 'reason', 'status'];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}