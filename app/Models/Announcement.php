<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Tambahkan baris sakti ini di bawah:
    protected $fillable = ['title', 'content']; 
}