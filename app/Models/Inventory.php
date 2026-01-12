<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['user_id', 'item_name', 'item_code', 'loan_date','return_date', 'condition'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}