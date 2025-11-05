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

            if (!Schema::hasColumn('arsip_units', 'arsip_aktif_id')) {
                $table->foreignId('arsip_aktif_id')
                      ->nullable()
                      ->constrained('arsip_aktif', 'nomor_berkas')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            } else {

                $table->dropForeign(['arsip_aktif_id']);
                $table->foreign('arsip_aktif_id')
                      ->references('nomor_berkas')
                      ->on('arsip_aktif')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->dropForeign(['arsip_aktif_id']);
            $table->dropColumn('arsip_aktif_id');
        });
    }
};