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

            // Relasi
            $table->foreignId('kode_klasifikasi_id')->nullable()->constrained('kode_klasifikasi'); 
            $table->foreignId('unit_pengolah_arsip_id')->nullable()->constrained('unit_pengolah'); 

            // Relasi ke berkas_arsip.nomor_berkas (sekarang bigint unsigned)
            $table->foreignId('berkas_arsip_id')->nullable()->constrained('berkas_arsip', 'nomor_berkas');

            // Kolom untuk fitur verifikasi
            $table->string('publish_status')->default('draft');
            $table->foreignId('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('verifikasi_oleh')->nullable();
            $table->timestamp('verifikasi_tanggal')->nullable();

            // Kolom-kolom lainnya sesuai kebutuhan aplikasi
            $table->integer('retensi_aktif')->nullable();
            $table->integer('retensi_inaktif')->nullable();
            $table->string('indeks')->nullable();
            $table->string('no_item_arsip')->nullable();
            $table->text('uraian_informasi')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('jumlah_nilai');
            $table->string('jumlah_satuan');
            $table->string('tingkat_perkembangan')->nullable();
            $table->string('skkaad')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('no_filling')->nullable();
            $table->string('no_laci')->nullable();
            $table->string('no_folder')->nullable();
            $table->string('no_box')->nullable();
            $table->string('dokumen')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('verifikasi_keterangan')->nullable();
            
            // Kolom kategori (ini adalah kolom yang tetap diperlukan di level arsip_unit, bukan di berkas_arsip)
            $table->foreignId('kategori_id')->nullable()->constrained('kategori');
            $table->foreignId('sub_kategori_id')->nullable()->constrained('sub_kategori');

            $table->timestamps();

            // Index untuk kolom-kolom yang sering digunakan dalam query
            $table->index('kode_klasifikasi_id');
            $table->index('unit_pengolah_arsip_id');
            $table->index('status');
            $table->index('created_at');
            $table->index('publish_status');
            $table->index('verified_by');
            $table->index('verifikasi_oleh');
            $table->index('kategori_id');
            $table->index('sub_kategori_id');
            $table->index('berkas_arsip_id');
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