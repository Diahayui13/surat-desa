<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surat extends Model
{
    use HasFactory;
    
    protected $table = 'surats';
    
protected $fillable = [
    'user_id',
    'jenis_surat',
    'judul',
    'nama_pemohon',
    'nik_pemohon',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'pekerjaan',
    'alamat',
    'keperluan',
    'status',
    'tanggal_pengajuan',
    'tanggal_diproses',
    'tanggal_selesai',
    'file_ktp',
    'file_kk',
    'file_pendukung',
    'file_ttd',
    'nomor_surat',
    'tanggal_surat',
];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_lahir' => 'date',
        'tanggal_lahir_anak' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($surat) {
            if (!$surat->tanggal_pengajuan) {
                $surat->tanggal_pengajuan = now();
            }
            if (!$surat->status) {
                $surat->status = 'Menunggu';
            }
            // Auto generate nomor surat
            if (!$surat->nomor_surat) {
                $surat->nomor_surat = $surat->generateNomorSurat();
            }
        });
    }
    
    // Generate nomor surat otomatis
    public function generateNomorSurat()
    {
        $prefix = match($this->jenis_surat) {
            'Surat Keterangan Tidak Mampu' => 'SKTM',
            'Surat Keterangan Domisili' => 'SKD',
            'Surat Keterangan Usaha' => 'SKU',
            'Surat Keterangan Menikah' => 'SKM',
            default => 'SK'
        };
        
        $count = Surat::whereYear('created_at', date('Y'))->count() + 1;
        $year = date('Y');
        
        return sprintf('%s/%03d/DS/%s', $prefix, $count, $year);
    }
}