<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanWaktu extends Model
{
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'nomor_lomba',
        'jenis_kolam',
        'waktu',
        'waktu_detik',
        'lokasi',
        'jenis_event',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    // Helper untuk konversi waktu ke detik
    public static function waktuKeDetik($waktu)
    {
        // Format: MM:SS.MS atau SS.MS
        $parts = explode(':', $waktu);
        
        if (count($parts) == 2) {
            // Format MM:SS.MS
            $menit = (int)$parts[0];
            $detikMs = explode('.', $parts[1]);
            $detik = (int)$detikMs[0];
            $milidetik = isset($detikMs[1]) ? (int)$detikMs[1] : 0;
            
            return ($menit * 60) + $detik + ($milidetik / 100);
        } else {
            // Format SS.MS
            $detikMs = explode('.', $waktu);
            $detik = (int)$detikMs[0];
            $milidetik = isset($detikMs[1]) ? (int)$detikMs[1] : 0;
            
            return $detik + ($milidetik / 100);
        }
    }
}
