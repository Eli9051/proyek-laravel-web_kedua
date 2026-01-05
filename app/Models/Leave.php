<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    // Tambahkan baris ini:
    protected $table = 'leaves'; 

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}