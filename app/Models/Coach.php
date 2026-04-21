<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = [
        'nama', 'spesialisasi', 'pengalaman', 'telepon', 'email', 'bio', 'status',
    ];
}
