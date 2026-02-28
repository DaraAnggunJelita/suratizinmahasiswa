<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mahasiswa');   // Nama mahasiswa
            $table->string('nim_mahasiswa');    // NIM mahasiswa
            $table->string('kelas');            // MI 3A / MI 3B / MI 3C
            $table->enum('status', ['Hadir','Izin','Sakit','Alfa']); // Status absensi
            $table->date('tanggal');            // Tanggal absensi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
