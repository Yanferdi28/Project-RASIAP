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

            $table->id('id_berkas');


            $table->foreignId('kode_klasifikasi_id')
                  ->nullable()
                  ->constrained('kode_klasifikasis')
                  ->onDelete('set null');


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