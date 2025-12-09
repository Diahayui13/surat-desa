<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('surat', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('jenis_surat')->nullable();
        $table->text('keterangan')->nullable();
        $table->string('file_surat')->nullable();
        $table->string('status')->default('Menunggu');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Data Pemohon
        $table->string('nama_pemohon')->nullable();
        $table->string('nik_pemohon')->nullable();
        $table->string('tempat_lahir')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
        $table->string('pekerjaan')->nullable();
        $table->text('alamat')->nullable();
        $table->string('keperluan')->nullable();
        
        // Data Anak (SKTM)
        $table->string('nama_anak')->nullable();
        $table->string('nis_nim')->nullable();
        $table->string('tempat_lahir_anak')->nullable();
        $table->date('tanggal_lahir_anak')->nullable();
        $table->string('jenis_kelamin_anak')->nullable();
        $table->string('pendidikan')->nullable();
        $table->string('jurusan_kelas')->nullable();
        
        // File Uploads
        $table->string('file_ktp')->nullable();
        $table->string('file_kk')->nullable();
        $table->string('file_surat_pernyataan')->nullable();
        $table->string('file_foto_rumah')->nullable();
        
        // TTD & Nomor
        $table->string('tanda_tangan')->nullable();
        $table->string('nomor_surat')->nullable();
        $table->string('pejabat_penanda_tangan')->default('Kepala Desa');
        $table->string('jabatan_penanda_tangan')->default('Kepala Desa');
        
        // Timestamps
        $table->timestamp('tanggal_pengajuan')->nullable();
        $table->timestamp('tanggal_diproses')->nullable();
        $table->timestamp('tanggal_selesai')->nullable();
        $table->timestamps();
    });
}
};
