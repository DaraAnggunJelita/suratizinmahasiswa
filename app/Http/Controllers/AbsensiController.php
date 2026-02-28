<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Mahasiswa;

class AbsensiController extends Controller
{
    // Tampilkan daftar absensi per kelas
    public function index($kelas)
    {
        $absensi = Absensi::where('kelas', $kelas)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dosen.absensi', compact('absensi', 'kelas'));
    }

    // Form tambah absensi
    public function create($kelas)
    {
        $mahasiswa = Mahasiswa::where('kelas', $kelas)->get();
        return view('dosen.create_absen', compact('kelas', 'mahasiswa'));
    }

    // Simpan absensi
    public function store(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|array',
            'nim_mahasiswa' => 'required|array',
            'status' => 'required|array',
            'kelas' => 'required|string',
        ]);

        foreach ($request->nama_mahasiswa as $index => $nama) {
    Absensi::create([
        'nama_mahasiswa' => $nama,
        'nim_mahasiswa' => $request->nim_mahasiswa[$index],
        'kelas' => $request->kelas,
        'status' => $request->status[$index],
        'tanggal' => now()->format('Y-m-d'),
    ]);
}

        return redirect()->route('dosen.absensi', $request->kelas)
                         ->with('success', 'Absensi berhasil disimpan!');
    }

    // Form edit absensi
    public function edit($id)
{
    $absensi = Absensi::findOrFail($id);
    return view('dosen.edit_absen', compact('absensi')); // kirim $absensi ke blade
}

    // Update absensi
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil diperbarui!');
    }

    // Hapus absensi
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->back()->with('success', 'Absensi berhasil dihapus!');
    }
}
