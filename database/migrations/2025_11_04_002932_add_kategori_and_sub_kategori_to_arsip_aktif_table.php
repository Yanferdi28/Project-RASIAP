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
        Schema::table('arsip_aktif', function (Blueprint $table) {
            // Add foreign key columns for category and subcategory
            $table->foreignId('kategori_id')->nullable()->constrained('kategori');
            $table->foreignId('sub_kategori_id')->nullable()->constrained('sub_kategori');
            
            // Make the old kategori_berkas column nullable (to be removed later if needed)
            // Note: To modify column properties, we'd need doctrine/dbal package, 
            // but for now we'll just leave it as is since we're adding new category system
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_aktif', function (Blueprint $table) {
            // Drop foreign key constraints first
            if (Schema::hasColumn('arsip_aktif', 'sub_kategori_id')) {
                $table->dropForeign(['sub_kategori_id']);
            }
            if (Schema::hasColumn('arsip_aktif', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
            }
            
            // Drop the columns
            $table->dropColumn(['sub_kategori_id', 'kategori_id']);
        });
    }
};
