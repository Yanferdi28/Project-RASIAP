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
        Schema::create('kode_klasifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klasifikasi')->unique();
            $table->string('kode_klasifikasi_induk')->nullable();
            $table->string('fungsi');
            $table->string('uraian');
            $table->integer('retensi_aktif');
            $table->integer('retensi_inaktif');

            // enum untuk status akhir
            $table->enum('status_akhir', [
                'Musnah',
                'Permanen',
                'Dinilai Kembali',
            ])->default('Dinilai Kembali');

            // enum untuk klasifikasi keamanan
            $table->enum('klasifikasi_keamanan', [
                'Biasa',
                'Rahasia',
                'Terbatas',
            ])->default('Biasa');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_klasifikasis');
    }
};
