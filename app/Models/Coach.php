<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Coach extends Model
{
    protected $fillable = [
        'nama', 'spesialisasi', 'pengalaman', 'telepon', 'email', 'bio', 'status',
        'current_password', 'password_updated_at'
    ];

    protected $casts = [
        'password_updated_at' => 'datetime',
    ];

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
