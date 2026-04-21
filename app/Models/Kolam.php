<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kolam extends Model
{
    protected $fillable = ['nama', 'ukuran', 'lokasi', 'keterangan', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function getLabelAttribute(): string
    {
        return "{$this->nama} ({$this->ukuran})";
    }
}
