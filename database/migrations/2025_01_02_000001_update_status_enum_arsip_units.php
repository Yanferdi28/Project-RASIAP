<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data first
        DB::table('arsip_units')->where('status', 'pending')->update(['status' => 'menunggu']);
        DB::table('arsip_units')->where('status', 'diterima')->update(['status' => 'disetujui']);
        // 'ditolak' tetap sama

        // Alter enum column
        DB::statement("ALTER TABLE arsip_units MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data
        DB::table('arsip_units')->where('status', 'menunggu')->update(['status' => 'pending']);
        DB::table('arsip_units')->where('status', 'disetujui')->update(['status' => 'diterima']);

        // Revert enum
        DB::statement("ALTER TABLE arsip_units MODIFY COLUMN status ENUM('pending', 'diterima', 'ditolak') DEFAULT 'pending'");
    }
};
