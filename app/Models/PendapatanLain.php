<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendapatanLain extends Model
{
    protected $fillable = [
        'tanggal', 'kategori', 'deskripsi', 'sumber',
        'jumlah', 'metode_pembayaran', 'status', 'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:0',
    ];

    public static $kategoriLabels = [
        'penjualan_jersey' => 'Penjualan Jersey',
        'sewa_kolam'       => 'Sewa Kolam',
        'sponsor'          => 'Sponsor',
        'donasi'           => 'Donasi',
        'lainnya'          => 'Lainnya',
    ];

    public function getKategoriLabelAttribute(): string
    {
        return self::$kategoriLabels[$this->kategori] ?? $this->kategori;
    }
}
