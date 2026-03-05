<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    // Tambahkan baris ini untuk menetapkan nama tabel secara manual
    protected $table = 'pengumumans';

    protected $fillable = ['user_id', 'judul', 'kelas', 'pesan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
