<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('naskah_masuks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We're not providing a down method as this is a destructive operation
        // and it would require recreating the table with all its columns and relationships
        // which is not recommended for data safety
        throw new \Exception('Cannot rollback naskah_masuks table deletion.');
    }
};