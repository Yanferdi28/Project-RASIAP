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
        Schema::create('kode_klasifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klasifikasi')->unique();
            $table->string('kode_klasifikasi_induk')->nullable();
            $table->string('uraian');
            $table->integer('retensi_aktif');
            $table->integer('retensi_inaktif');
            $table->enum('status_akhir', ['Musnah', 'Permanen', 'Dinilai Kembali'])->default('Dinilai Kembali');
            $table->enum('klasifikasi_keamanan', ['Biasa', 'Rahasia', 'Terbatas'])->default('Biasa');
            $table->timestamps();
        });

        Schema::create('unit_pengolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit');
        });

        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('sub_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori');
            $table->string('nama_sub_kategori');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kategori');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('unit_pengolah');
        Schema::dropIfExists('kode_klasifikasi');
    }
};