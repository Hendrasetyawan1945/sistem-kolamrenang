<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas', 'level', 'kapasitas', 'harga', 'deskripsi', 'aktif', 'coach_id', 'jadwal',
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

    public function getJadwalStringAttribute(): string
    {
        if (empty($this->jadwal)) return '-';
        return collect($this->jadwal)->map(fn($j) => $j['hari'] . ' ' . $j['jam_mulai'] . '-' . $j['jam_selesai'])->implode(', ');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function jumlahSiswaAktif(): int
    {
        return Siswa::where('status', 'aktif')->where('kelas', $this->nama_kelas)->count();
    }
}
