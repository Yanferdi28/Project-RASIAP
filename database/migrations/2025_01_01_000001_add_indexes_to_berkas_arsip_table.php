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
        Schema::table('berkas_arsip', function (Blueprint $table) {
            // Index untuk kolom-kolom yang sering digunakan dalam query
            $table->index('klasifikasi_id');
            $table->index('created_at');
            $table->index('nama_berkas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berkas_arsip', function (Blueprint $table) {
            $table->dropIndex(['klasifikasi_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['nama_berkas']);
        });
    }
};
