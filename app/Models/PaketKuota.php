<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketKuota extends Model
{
    protected $fillable = [
        'nama_paket',
        'jumlah_pertemuan',
        'harga',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'harga' => 'decimal:2',
    ];
}
