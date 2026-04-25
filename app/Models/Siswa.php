<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'kelas', // Gunakan 'kelas' bukan 'kelas_id'
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

    public function jerseyOrders(): HasMany
    {
        return $this->hasMany(JerseyOrder::class);
    }

    public function catatanWaktuLatihans(): HasMany
    {
        return $this->hasMany(CatatanWaktuLatihan::class);
    }

    public function angsurans(): HasMany
    {
        return $this->hasMany(Angsuran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas', 'nama_kelas');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
