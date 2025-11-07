<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            // Add indexes for foreign key columns to improve query performance
            $table->index('kode_klasifikasi_id');
            $table->index('unit_pengolah_arsip_id');
            $table->index('kategori_id');
            $table->index('sub_kategori_id');
            $table->index('arsip_aktif_id');
            $table->index('verifikasi_oleh');
            
            // Add index for status column which is frequently used in queries
            $table->index('status');
            
            // Add index for created_at for sorting
            $table->index('created_at');
        });
        
        // Add composite indexes using raw SQL
        DB::statement('CREATE INDEX idx_status_unit_pengolah ON arsip_units (status, unit_pengolah_arsip_id)');
        DB::statement('CREATE INDEX idx_unit_pengolah_status ON arsip_units (unit_pengolah_arsip_id, status)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->dropIndex(['kode_klasifikasi_id']);
            $table->dropIndex(['unit_pengolah_arsip_id']);
            $table->dropIndex(['kategori_id']);
            $table->dropIndex(['sub_kategori_id']);
            $table->dropIndex(['arsip_aktif_id']);
            $table->dropIndex(['verifikasi_oleh']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            
            // Drop composite indexes using raw SQL since Blueprint doesn't support named composite indexes as easily
        });
        
        // Drop composite indexes using raw SQL
        DB::statement('DROP INDEX IF EXISTS idx_status_unit_pengolah ON arsip_units');
        DB::statement('DROP INDEX IF EXISTS idx_unit_pengolah_status ON arsip_units');
    }
};
