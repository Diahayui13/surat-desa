<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanda_tangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pejabat');
            $table->string('nip')->nullable();
            $table->string('jabatan');
            $table->string('file_ttd');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanda_tangan');
    }
};