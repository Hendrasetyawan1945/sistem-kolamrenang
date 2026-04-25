<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama', 'nama_kelas', 'level', 'kapasitas', 'harga', 'deskripsi', 'aktif', 'coach_id', 'jadwal',
        'hari', 'jam_mulai', 'jam_selesai', 'kolam_id'
    ];

    protected $casts = [
        'harga'  => 'decimal:0',
        'aktif'  => 'boolean',
        'jadwal' => 'array',
    ];

    public static $levelLabels = [
        'pemula'   => 'Pemula',
        'menengah' => 'Menengah',
        'lanjut'   => 'Lanjut',
        'prestasi' => 'Prestasi',
    ];

    public static $levelColors = [
        'pemula'   => ['#e3f2fd', '#1565c0'],
        'menengah' => ['#e8f5e9', '#2e7d32'],
        'lanjut'   => ['#fff3e0', '#e65100'],
        'prestasi' => ['#fce4ec', '#880e4f'],
    ];

    public function getLevelLabelAttribute(): string
    {
        return self::$levelLabels[$this->level] ?? $this->level;
    }

    public function getNamaAttribute(): string
    {
        return $this->nama_kelas;
    }

    public function getJadwalStringAttribute(): string
    {
        if (empty($this->jadwal)) return '-';
        return collect($this->jadwal)->map(fn($j) => $j['hari'] . ' ' . $j['jam_mulai'] . '-' . $j['jam_selesai'])->implode(', ');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function kolam(): BelongsTo
    {
        return $this->belongsTo(Kolam::class);
    }

    public function siswas(): HasMany
    {
        // Kolom di tabel siswas adalah 'kelas' (nama kelas), bukan 'kelas_id'
        return $this->hasMany(Siswa::class, 'kelas', 'nama_kelas');
    }

    public function jumlahSiswaAktif(): int
    {
        return $this->siswas()->where('status', 'aktif')->count();
    }
}
