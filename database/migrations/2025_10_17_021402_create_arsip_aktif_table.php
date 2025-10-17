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
        Schema::create('arsip_aktif', function (Blueprint $table) {
            // Kolom Nomor Berkas sebagai Primary Key
            $table->id('nomor_berkas');

            // Kolom sesuai urutan di gambar
            $table->string('nama_berkas');

            // Asumsi 'Klasifikasi' berelasi dengan tabel lain
            $table->foreignId('klasifikasi_id')->constrained('kode_klasifikasis');

            // Kolom Retensi (dibuat nullable karena bisa diisi otomatis)
            $table->integer('retensi_aktif')->nullable();
            $table->integer('retensi_inaktif')->nullable();
            
            // Kolom Penyusutan Akhir (dibuat nullable)
            $table->string('penyusutan_akhir')->nullable();

            // Kolom sisi kanan
            $table->string('lokasi_fisik')->nullable();
            $table->text('uraian')->nullable();
            $table->string('kategori_berkas');
            
            // Timestamps standar Laravel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_aktif');
    }
};