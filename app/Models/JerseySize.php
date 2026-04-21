<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JerseySize extends Model
{
    protected $fillable = [
        'nama_size',
        'lebar_dada',
        'panjang_badan',
        'panjang_lengan',
        'tinggi_badan_min',
        'tinggi_badan_max',
        'berat_badan_min',
        'berat_badan_max',
        'umur_min',
        'umur_max',
        'stok',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(JerseyOrder::class);
    }
}
