<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;

    protected $table = 'permohonan';

protected $fillable = [
    'user_id',
    'nama',
    'nik',
    'no_kk',
    'alamat',
    'no_hp',
    'jenis_surat',
    'keperluan',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'pekerjaan',
    'status',
    'nomor_surat',
    'tanggal_surat',
    'file_surat',
    'tanda_tangan',
    'tanggal_diproses',  // ✨ TAMBAHKAN
    'tanggal_selesai',   // ✨ TAMBAHKAN
];

protected $casts = [
    'tanggal_lahir' => 'date',
    'tanggal_surat' => 'date',
    'tanggal_diproses' => 'datetime',  // ✨ TAMBAHKAN
    'tanggal_selesai' => 'datetime',   // ✨ TAMBAHKAN
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}