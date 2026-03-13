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
    $prodiAktif = $request->get('prodi', 'Manajemen Informatika');

    // Logika penentuan default kelas jika ganti prodi
    $alias = str_contains(strtolower($prodiAktif), 'manajemen') ? 'MI' : 'TRPL';
    $kelasAktif = $request->get('kelas', $alias . ' 3A');

    $prodis = \App\Models\Prodi::all();

    $jadwals = \App\Models\Jadwal::where('prodi', $prodiAktif)
                ->where('kelas', $kelasAktif)
                ->orderBy('jam_mulai', 'asc')
                ->get()
                ->groupBy('hari');

    return view('jadwal.index', compact('jadwals', 'kelasAktif', 'prodiAktif', 'prodis'));
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
