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
            if (Schema::hasColumn('arsip_units', 'arsip_aktif_id')) {
                $table->renameColumn('arsip_aktif_id', 'berkas_arsip_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            if (Schema::hasColumn('arsip_units', 'berkas_arsip_id')) {
                $table->renameColumn('berkas_arsip_id', 'arsip_aktif_id');
            }
        });
    }
};