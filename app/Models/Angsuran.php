<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Angsuran extends Model
{
    protected $fillable = [
        'siswa_id',
        'jenis_tagihan',
        'total_tagihan',
        'total_dibayar',
        'sisa_tagihan',
        'jumlah_cicilan',
        'status',
        'keterangan',
        'tanggal_tagihan',
    ];

    protected $casts = [
        'total_tagihan' => 'decimal:2',
        'total_dibayar' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
        'tanggal_tagihan' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function cicilans(): HasMany
    {
        return $this->hasMany(AngsuranCicilan::class);
    }
}
