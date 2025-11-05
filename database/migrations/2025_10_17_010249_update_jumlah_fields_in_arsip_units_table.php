<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->dropColumn('jumlah');
            $table->integer('jumlah_nilai')->after('tanggal');
            $table->string('jumlah_satuan')->after('jumlah_nilai');
        });
    }

    public function down(): void
    {
        Schema::table('arsip_units', function (Blueprint $table) {
            $table->integer('jumlah')->nullable();
            $table->dropColumn(['jumlah_nilai', 'jumlah_satuan']);
        });
    }
};
