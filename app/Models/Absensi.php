<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mahasiswa',
        'nim_mahasiswa',
        'kelas',
        'status',
        'tanggal',
        'absensi'
    ];

    // TAMBAHKAN INI: Menghubungkan Absensi ke User berdasarkan NIM
    public function user()
    {
        return $this->belongsTo(User::class, 'nim_mahasiswa', 'nim_nip');
    }
}
