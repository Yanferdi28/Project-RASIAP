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

            $table->foreignId('kategori_id')->nullable()->constrained('kategori');
            $table->foreignId('sub_kategori_id')->nullable()->constrained('sub_kategori');
            



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_aktif', function (Blueprint $table) {

            if (Schema::hasColumn('arsip_aktif', 'sub_kategori_id')) {
                $table->dropForeign(['sub_kategori_id']);
            }
            if (Schema::hasColumn('arsip_aktif', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
            }
            

            $table->dropColumn(['sub_kategori_id', 'kategori_id']);
        });
    }
};
