<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nim_nip')) {
                $table->string('nim_nip')->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'prodi')) {
                $table->string('prodi')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'kelas')) {
                $table->string('kelas')->nullable()->after('prodi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nim_nip')) {
                $table->dropColumn('nim_nip');
            }
            if (Schema::hasColumn('users', 'prodi')) {
                $table->dropColumn('prodi');
            }
            if (Schema::hasColumn('users', 'kelas')) {
                $table->dropColumn('kelas');
            }
        });
    }
};
