<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'current_password', 'role', 'siswa_id', 'coach_id'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isSiswa(): bool  { return $this->role === 'siswa'; }
    public function isCoach(): bool  { return $this->role === 'coach'; }
    public function isGuru(): bool   { return $this->role === 'coach'; } // Alias untuk guru

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }
}
