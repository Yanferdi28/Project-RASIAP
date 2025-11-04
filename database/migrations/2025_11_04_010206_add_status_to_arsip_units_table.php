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
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending')->after('keterangan');
            $table->text('verifikasi_keterangan')->nullable()->after('status');
            $table->foreignId('verifikasi_oleh')->nullable()->constrained('users')->after('verifikasi_keterangan');
            $table->timestamp('verifikasi_tanggal')->nullable()->after('verifikasi_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->dropForeign(['verifikasi_oleh']);
            $table->dropColumn(['verifikasi_tanggal', 'verifikasi_oleh', 'verifikasi_keterangan', 'status']);
        });
    }
};
