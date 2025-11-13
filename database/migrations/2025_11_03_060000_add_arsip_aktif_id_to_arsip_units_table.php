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
            // Check if the column exists
            if (!Schema::hasColumn('arsip_units', 'berkas_arsip_id')) {
                // Add the column if it doesn't exist
                $table->foreignId('berkas_arsip_id')
                      ->nullable()
                      ->constrained('arsip_aktif', 'nomor_berkas')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            } else {
                // Check if there's an existing foreign key constraint on this column
                // If there is one and it's not pointing to the right table, we need to handle it carefully
                // Just add the constraint if it doesn't exist yet
                $table->foreign('berkas_arsip_id')
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
            $table->dropForeign(['berkas_arsip_id']);
            $table->dropColumn('berkas_arsip_id');
        });
    }
};