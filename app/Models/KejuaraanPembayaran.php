<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KejuaraanPembayaran extends Model
{
    protected $fillable = [
        'kejuaraan_id',
        'siswa_id',
        'jumlah',
        'status',
        'tanggal_bayar',
        'metode_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah' => 'decimal:0',
    ];

    public function kejuaraan(): BelongsTo
    {
        return $this->belongsTo(Kejuaraan::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
