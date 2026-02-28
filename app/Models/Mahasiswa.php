<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['nim','nama','prodi','kelas'];

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class);
    }
}
