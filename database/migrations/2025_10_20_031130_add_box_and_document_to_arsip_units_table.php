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
    Schema::table('arsip_units', function (Blueprint $table) {
        $table->string('no_folder')->nullable()->after('no_laci');
        $table->string('no_box')->nullable()->after('no_folder');
        $table->string('dokumen')->nullable()->after('no_box');
    });
}

public function down(): void
{
    Schema::table('arsip_units', function (Blueprint $table) {
        $table->dropColumn(['no_box', 'dokumen']);
    });
}
};
