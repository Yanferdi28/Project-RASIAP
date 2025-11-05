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



        throw new \Exception('Cannot rollback naskah_masuks table deletion.');
    }
};