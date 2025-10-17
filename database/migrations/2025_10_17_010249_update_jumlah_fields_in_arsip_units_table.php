<?php

// database/migrations/xxxx_xx_xx_xxxxxx_update_jumlah_fields_in_arsip_units_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->dropColumn('jumlah'); // Hapus kolom 'jumlah' yang lama
            $table->integer('jumlah_nilai')->after('tanggal'); // Tambah kolom baru untuk angka
            $table->string('jumlah_satuan')->after('jumlah_nilai'); // Tambah kolom baru untuk satuan
        });
    }

    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->integer('jumlah')->nullable(); // Kembalikan kolom 'jumlah' jika rollback
            $table->dropColumn(['jumlah_nilai', 'jumlah_satuan']); // Hapus kolom baru
        });
    }
};
