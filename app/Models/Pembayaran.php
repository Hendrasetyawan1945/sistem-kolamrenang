<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $fillable = [
        'siswa_id',
        'jenis_pembayaran',
        'tahun',
        'bulan',
        'jumlah',
        'tanggal_bayar',
        'metode_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
