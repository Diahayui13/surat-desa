<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('nik', 16);
            $table->string('no_kk', 16);
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->string('jenis_surat');
            $table->text('keperluan');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('pekerjaan');
            $table->enum('status', ['pending', 'diproses', 'disetujui', 'ditolak'])->default('pending');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->string('file_surat')->nullable();
            $table->string('tanda_tangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan');
    }
};