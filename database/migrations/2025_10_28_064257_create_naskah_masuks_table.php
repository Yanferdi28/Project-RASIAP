<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naskah_masuks', function (Blueprint $table) {
            $table->id();
            
            $table->string('nomor_naskah')->unique()->nullable();
            $table->string('nama_pengirim')->nullable();
            $table->string('jabatan_pengirim')->nullable();
            $table->string('instansi_pengirim')->nullable();
            $table->string('jenis_naskah')->nullable();
            $table->string('sifat_naskah')->nullable();
            $table->date('tanggal_naskah');
            $table->date('tanggal_diterima');
            $table->text('hal'); 
            $table->text('isi_ringkas')->nullable();
            $table->string('file_naskah')->nullable(); 
            $table->json('lampiran')->nullable(); 
            
            // Cara singkat dengan foreignId
            $table->foreignId('arsip_aktif_id')
                  ->nullable()
                  ->constrained('arsip_aktif', 'nomor_berkas')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naskah_masuks');
    }
};