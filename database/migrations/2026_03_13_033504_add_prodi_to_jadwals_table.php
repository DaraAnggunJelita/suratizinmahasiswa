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
    Schema::table('jadwals', function (Blueprint $table) {
        // Tambahkan kolom prodi setelah kolom kelas
        $table->string('prodi')->after('kelas')->nullable();
    });
}

public function down(): void
{
    Schema::table('jadwals', function (Blueprint $table) {
        $table->dropColumn('prodi');
    });
}
};
