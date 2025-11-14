<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita gunakan raw SQL untuk menghapus foreign key constraint
        DB::statement('ALTER TABLE berkas_arsip DROP FOREIGN KEY arsip_aktif_kategori_id_foreign');
        DB::statement('ALTER TABLE berkas_arsip DROP FOREIGN KEY arsip_aktif_sub_kategori_id_foreign');
        
        // Lalu drop kolomnya
        DB::statement('ALTER TABLE berkas_arsip DROP COLUMN sub_kategori_id, DROP COLUMN kategori_id, DROP COLUMN kategori_berkas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kita tambahkan kolom dan constraint kembali secara manual
        DB::statement('ALTER TABLE berkas_arsip ADD COLUMN kategori_berkas VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE berkas_arsip ADD COLUMN kategori_id BIGINT UNSIGNED NULL, ADD COLUMN sub_kategori_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE berkas_arsip ADD CONSTRAINT arsip_aktif_kategori_id_foreign FOREIGN KEY (kategori_id) REFERENCES kategori(id)');
        DB::statement('ALTER TABLE berkas_arsip ADD CONSTRAINT arsip_aktif_sub_kategori_id_foreign FOREIGN KEY (sub_kategori_id) REFERENCES sub_kategori(id)');
    }
};
