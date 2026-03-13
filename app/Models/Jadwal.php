<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
    'mata_kuliah', 'dosen_pengajar','prodi', 'hari',
    'jam_mulai', 'jam_selesai', 'ruangan', 'kelas'
];
}
