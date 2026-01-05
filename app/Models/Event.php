<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Daftarkan kolom yang boleh diisi secara massal
    protected $fillable = [
        'title',
        'event_date',
        'location',
        'color',
    ];
}