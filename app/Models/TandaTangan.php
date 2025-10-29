<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TandaTangan extends Model
{
    use HasFactory;

    protected $table = 'tanda_tangan';

    protected $fillable = [
        'nama_pejabat',
        'nip',
        'jabatan',
        'file_ttd',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}