<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JerseyOrder extends Model
{
    protected $fillable = [
        'siswa_id',
        'jersey_size_id',
        'nomor_punggung',
        'nama_punggung',
        'tanggal_pesan',
        'status',
        'status_bayar',
        'tanggal_bayar',
        'metode_pembayaran',
        'harga',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pesan' => 'date',
        'tanggal_bayar' => 'date',
        'harga' => 'decimal:2',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jerseySize(): BelongsTo
    {
        return $this->belongsTo(JerseySize::class);
    }
}
