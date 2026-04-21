<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kejuaraan extends Model
{
    protected $table = 'kejuaraaans';
    
    protected $fillable = [
        'nama_kejuaraan',
        'penyelenggara',
        'tanggal_kejuaraan',
        'lokasi',
        'biaya_pendaftaran',
        'batas_pendaftaran',
        'deskripsi',
        'kategori',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'kategori' => 'array',
        'tanggal_kejuaraan' => 'date',
        'batas_pendaftaran' => 'date',
        'biaya_pendaftaran' => 'decimal:0',
    ];

    public function getStatusLabelAttribute()
    {
        $labels = [
            'akan_datang' => 'Akan Datang',
            'pendaftaran' => 'Pendaftaran Dibuka',
            'pendaftaran_tutup' => 'Pendaftaran Ditutup',
            'berlangsung' => 'Sedang Berlangsung',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusClassAttribute()
    {
        $classes = [
            'akan_datang' => 'status-akan-datang',
            'pendaftaran' => 'status-pendaftaran',
            'pendaftaran_tutup' => 'status-pendaftaran-tutup',
            'berlangsung' => 'status-berlangsung',
            'selesai' => 'status-selesai',
            'dibatalkan' => 'status-dibatalkan'
        ];

        return $classes[$this->status] ?? 'status-akan-datang';
    }

    public function getKategoriStringAttribute()
    {
        return is_array($this->kategori) ? implode(', ', $this->kategori) : $this->kategori;
    }

    public function pembayarans()
    {
        return $this->hasMany(KejuaraanPembayaran::class);
    }

    public function getPesertaCountAttribute()
    {
        return $this->pembayarans()->count();
    }

    public function getLunasCountAttribute()
    {
        return $this->pembayarans()->where('status', 'lunas')->count();
    }

    public function getBelumBayarCountAttribute()
    {
        return $this->pembayarans()->where('status', 'belum_bayar')->count();
    }
}
