<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AngsuranCicilan extends Model
{
    protected $fillable = [
        'angsuran_id',
        'cicilan_ke',
        'jumlah',
        'tanggal_bayar',
        'metode_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function angsuran(): BelongsTo
    {
        return $this->belongsTo(Angsuran::class);
    }
}
