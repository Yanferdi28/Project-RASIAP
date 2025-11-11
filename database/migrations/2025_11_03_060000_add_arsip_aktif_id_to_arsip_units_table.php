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

            if (!Schema::hasColumn('arsip_units', 'berkas_arsip_id')) {
                $table->foreignId('berkas_arsip_id')
                      ->nullable()
                      ->constrained('berkas_arsip', 'nomor_berkas')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            } else {

                $table->dropForeign(['berkas_arsip_id']);
                $table->foreign('berkas_arsip_id')
                      ->references('nomor_berkas')
                      ->on('berkas_arsip')
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
            $table->dropForeign(['berkas_arsip_id']);
            $table->dropColumn('berkas_arsip_id');
        });
    }
};