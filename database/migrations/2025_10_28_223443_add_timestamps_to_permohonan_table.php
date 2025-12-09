<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->timestamp('tanggal_diproses')->nullable()->after('status');
            $table->timestamp('tanggal_selesai')->nullable()->after('tanggal_diproses');
        });
    }

    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_diproses', 'tanggal_selesai']);
        });
    }
};