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
        Schema::create('sub_kategori', function (Blueprint $table) {
            $table->id();
            // Foreign Key untuk menghubungkan ke Kategori (Main Category)
            $table->foreignId('kategori_id')
                  ->constrained('kategori')
                  ->onDelete('cascade');
                  
            $table->string('nama_sub_kategori'); // Contoh: Informasi Daftar dan Hasil Penelitian LPP RRI
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
    }
};