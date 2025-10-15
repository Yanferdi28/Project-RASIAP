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
        Schema::create('arsip_units', function (Blueprint $table) {
            // Primary key kustom
            $table->id('id_berkas');

            // Foreign Key ke tabel kode_klasifikasis
            $table->foreignId('kode_klasifikasi_id')
                  ->nullable()
                  ->constrained('kode_klasifikasis')
                  ->onDelete('set null');

            // Foreign Key ke tabel unit_pengolahs
            $table->foreignId('unit_pengolah_arsip_id')
                  ->nullable()
                  ->constrained('unit_pengolahs')
                  ->onDelete('set null');

            $table->string('indeks')->nullable();
            $table->string('no_item_arsip')->nullable();
            $table->text('uraian_informasi')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('tingkat_perkembangan')->nullable();
            $table->string('skkaad')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('no_filling')->nullable();
            $table->string('no_laci')->nullable();
            $table->text('keterangan')->nullable();
            
            // Timestamps standar Laravel (created_at & updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_units');
    }
};