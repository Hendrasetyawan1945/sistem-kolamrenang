<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'kelas',
        'alamat',
        'nama_ortu',
        'telepon',
        'email',
        'paket',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function rapors(): HasMany
    {
        return $this->hasMany(Rapor::class);
    }

    public function kejuaraanPembayarans(): HasMany
    {
        return $this->hasMany(KejuaraanPembayaran::class);
    }
}
