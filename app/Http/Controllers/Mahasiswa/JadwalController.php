<?php

namespace App\Http\Controllers\Mahasiswa; // Wajib ada \Mahasiswa

use App\Http\Controllers\Controller; // Wajib diimpor karena beda folder
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
   public function index(Request $request)
{
    // Mengambil parameter kelas dari URL, default ke 'MI 3A' jika tidak ada
    $kelasAktif = $request->query('kelas', 'MI 3A');

    // Mengambil jadwal hanya untuk kelas yang dipilih, dikelompokkan berdasarkan hari
    $jadwals = Jadwal::where('kelas', $kelasAktif)
        ->orderBy('jam_mulai', 'asc')
        ->get()
        ->groupBy('hari');

    return view('jadwal.index', compact('jadwals', 'kelasAktif'));
}

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah' => 'required|string|max:255',
            'dosen_pengajar' => 'required|string|max:255',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required',
            'kelas' => 'required',
        ]);

        // Simpan data ke database menggunakan mass assignment
        Jadwal::create($request->all());

        return redirect()->back()->with('success', 'Jadwal kuliah berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
