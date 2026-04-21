<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanWaktuLatihan extends Model
{
    protected $fillable = [
        'siswa_id', 'tanggal', 'jenis_latihan', 'nomor_lomba',
        'set_jarak', 'waktu', 'waktu_detik', 'kelas', 'catatan',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public static $jenisLabels = [
        'teknik'    => 'Teknik',
        'speed'     => 'Speed',
        'endurance' => 'Endurance',
        'test_set'  => 'Test Set',
        'drill'     => 'Drill',
    ];

    public static $jenisColors = [
        'teknik'    => ['#e3f2fd', '#1565c0'],
        'speed'     => ['#fce4ec', '#880e4f'],
        'endurance' => ['#e8f5e9', '#2e7d32'],
        'test_set'  => ['#fff3e0', '#e65100'],
        'drill'     => ['#f3e5f5', '#6a1b9a'],
    ];
}
