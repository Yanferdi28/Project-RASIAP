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
        // Perintah ini membuat tabel dengan nama 'kategori'
        Schema::create('kategori', function (Blueprint $table) {
            $table->id(); // Membuat kolom 'id' auto-increment
            $table->string('nama_kategori'); // Kolom untuk nama kategori
            $table->text('deskripsi')->nullable(); // Kolom untuk deskripsi (opsional)
            $table->timestamps(); // Membuat kolom 'created_at' dan 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};