<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Cek dan tambahkan kolom hanya jika belum ada
            if (!Schema::hasColumn('surats', 'file_ttd')) {
                $table->string('file_ttd')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('surats', 'nomor_surat')) {
                $table->string('nomor_surat')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('surats', 'tanggal_surat')) {
                $table->date('tanggal_surat')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Hapus kolom jika ada
            if (Schema::hasColumn('surats', 'file_ttd')) {
                $table->dropColumn('file_ttd');
            }
            
            if (Schema::hasColumn('surats', 'nomor_surat')) {
                $table->dropColumn('nomor_surat');
            }
            
            if (Schema::hasColumn('surats', 'tanggal_surat')) {
                $table->dropColumn('tanggal_surat');
            }
        });
    }
};