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
        Schema::create('berkas_arsip', function (Blueprint $table) {
            $table->id('nomor_berkas');
            $table->string('nama_berkas');
            $table->foreignId('klasifikasi_id')->constrained('kode_klasifikasis');
            $table->integer('retensi_aktif')->nullable();
            $table->integer('retensi_inaktif')->nullable();
            $table->string('penyusutan_akhir')->nullable();
            $table->string('lokasi_fisik')->nullable();
            $table->text('uraian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_arsip');
    }
};