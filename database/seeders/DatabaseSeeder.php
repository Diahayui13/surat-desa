<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Permohonan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin otomatis
        $admin = User::updateOrCreate(
            ['email' => 'admin@desa.com'],
            [
                'name' => 'Admin Desa',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );

        // Akun warga untuk testing
        $warga = User::updateOrCreate(
            ['email' => 'warga@desa.com'],
            [
                'name' => 'Diah Warga',
                'password' => Hash::make('warga123'),
                'role' => 'warga',
            ]
        );

        // Buat data dummy permohonan
        $jenisJenisSurat = [
            'Surat Keterangan Tidak Mampu',
            'Surat Keterangan Domisili',
            'Surat Keterangan Usaha',
            'Surat Keterangan Ahli Waris',
            'Surat Pengantar KTP',
        ];

        $namaNama = [
            'Muhammad Andri',
            'Siti Aminah',
            'Rudi Hartono',
            'Dewi Sinta',
            'Andi Pratama',
        ];

        $statuses = ['pending', 'diproses', 'disetujui'];

        for ($i = 0; $i < 5; $i++) {
            Permohonan::create([
                'user_id' => $warga->id,
                'nama' => $namaNama[$i],
                'nik' => '352206' . str_pad($i + 1, 10, '0', STR_PAD_LEFT),
                'no_kk' => '352206' . str_pad($i + 1, 10, '0', STR_PAD_LEFT),
                'alamat' => 'Ds. Sumbertlaseh RT.0' . ($i + 1) . ' RW.01 Bojonegoro',
                'no_hp' => '0821' . rand(10000000, 99999999),
                'jenis_surat' => $jenisJenisSurat[$i],
                'keperluan' => 'Untuk keperluan administrasi',
                'tempat_lahir' => 'Bojonegoro',
                'tanggal_lahir' => now()->subYears(rand(20, 50)),
                'jenis_kelamin' => $i % 2 == 0 ? 'Laki-laki' : 'Perempuan',
                'pekerjaan' => $i % 2 == 0 ? 'Petani' : 'Ibu Rumah Tangga',
                'status' => $statuses[$i % 3],
                'created_at' => now()->subDays(rand(1, 10)),
            ]);
        }
    }
}