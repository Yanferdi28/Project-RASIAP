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
            $table->integer('retensi_aktif')->nullable()->after('unit_pengolah_arsip_id');
            $table->integer('retensi_inaktif')->nullable()->after('retensi_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->dropColumn(['retensi_aktif', 'retensi_inaktif']);
        });
    }
};