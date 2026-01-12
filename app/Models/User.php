<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'basic_salary',
        'risk_score',
        'risk_status',
        'kuota_cuti',
        'has_warning',       // Tambahkan ini jika belum ada
        'warning_message',   // Tambahkan ini jika belum ada
        'hr_reviewed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Absen
    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }

    // Relasi ke Cuti
    public function leaves()
    {
        return $this->hasMany(\App\Models\Leave::class);
    }

    // Relasi ke Lembur (Overtime)
    public function overtimes()
    {
        return $this->hasMany(\App\Models\Overtime::class);
    }

    // Relasi ke Inventaris
    public function inventories()
    {
        return $this->hasMany(\App\Models\Inventory::class);
    }
}