<?php

namespace App\Http\Controllers\Mahasiswa; // Wajib ada \Mahasiswa

use App\Http\Controllers\Controller; // Wajib diimpor karena beda folder
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'mahasiswa') {
            $kelasUser = trim($user->kelas);
            $jadwals = Jadwal::where('kelas', 'LIKE', '%' . $kelasUser . '%')
                        ->orderBy('jam_mulai', 'asc')
                        ->get()
                        ->groupBy('hari');
        } else {
            $jadwals = Jadwal::orderBy('jam_mulai', 'asc')->get()->groupBy('hari');
        }

        return view('jadwal.index', compact('jadwals'));
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
