<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rapor extends Model
{
    protected $fillable = [
        'siswa_id', 'template_rapor_id', 'periode', 'bulan', 'tahun',
        'nilai', 'kehadiran', 'total_pertemuan', 'catatan_coach', 'catatan_umum', 'status',
    ];

    protected $casts = [
        'nilai' => 'array',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function templateRapor(): BelongsTo
    {
        return $this->belongsTo(TemplateRapor::class);
    }

    public function getNilaiRataRataAttribute(): float
    {
        if (empty($this->nilai)) return 0;
        $total = collect($this->nilai)->sum('nilai');
        return round($total / count($this->nilai), 1);
    }
}
