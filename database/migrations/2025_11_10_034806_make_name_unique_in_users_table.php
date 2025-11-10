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
        // First, handle duplicate names by appending a number to them
        $duplicateNames = DB::table('users')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('name');

        foreach ($duplicateNames as $name) {
            $users = DB::table('users')
                ->where('name', $name)
                ->orderBy('created_at')
                ->get();

            $counter = 2;
            foreach ($users as $index => $user) {
                if ($index > 0) { // Skip the first user (keep original name)
                    $newName = $name . '_' . $counter;
                    $counter++;
                    
                    // Make sure the new name doesn't already exist
                    while (DB::table('users')->where('name', $newName)->exists()) {
                        $newName = $name . '_' . $counter;
                        $counter++;
                    }
                    
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['name' => $newName]);
                }
            }
        }

        // Now add the unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
