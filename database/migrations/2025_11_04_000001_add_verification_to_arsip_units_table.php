<?php
// database/migrations/2025_11_04_000001_add_verification_to_arsip_units_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->string('publish_status')->default('draft')->index(); // draft|submitted|approved|rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->dropColumn(['publish_status','verified_by','verified_at','verification_notes','submitted_at']);
        });
    }
};