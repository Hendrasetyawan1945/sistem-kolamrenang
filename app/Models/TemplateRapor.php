<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateRapor extends Model
{
    protected $fillable = [
        'nama_template', 'kelas', 'deskripsi', 'komponen', 'template_catatan', 'aktif',
    ];

    protected $casts = [
        'komponen' => 'array',
        'aktif' => 'boolean',
    ];

    public function rapors(): HasMany
    {
        return $this->hasMany(Rapor::class);
    }
}
