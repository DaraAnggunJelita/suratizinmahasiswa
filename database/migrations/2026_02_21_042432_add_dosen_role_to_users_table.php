<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum role untuk menambahkan 'dosen'
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','mahasiswa','dosen') NOT NULL DEFAULT 'mahasiswa'");
    }

    public function down(): void
    {
        // Kembalikan ke enum lama
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','mahasiswa') NOT NULL DEFAULT 'mahasiswa'");
    }
};
