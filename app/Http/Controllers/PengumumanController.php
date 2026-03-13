<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    // Tampilkan Daftar Pengumuman (Gambar 1 yang Anda kirim)
    public function index(Request $request)
    {
        // 1. Ambil semua data prodi untuk dropdown filter dan modal
        $prodis = Prodi::all();

        // 2. Logika pencarian/filter (opsional)
        $query = Pengumuman::with('user')->orderBy('created_at', 'desc');

        if ($request->has('prodi') && $request->prodi != '') {
            $query->where('prodi', $request->prodi);
        }

        $pengumumans = $query->get();

        // 3. Kirim kedua variabel ke view
        return view('pengumuman.index', compact('pengumumans', 'prodis'));
    }

    // Simpan Pengumuman baru
    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'pesan' => 'required',
        'kelas' => 'required' // Ini nama dari input form
    ]);

    Pengumuman::create([
        'judul' => $request->judul,
        'pesan' => $request->pesan,
        'kelas' => $request->kelas, // Disimpan ke kolom 'kelas'
        'user_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Pengumuman berhasil dipublikasikan!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'kelas' => 'required', // Nama dari input form
        'pesan' => 'required',
    ]);

    $pengumuman = Pengumuman::findOrFail($id);

    $pengumuman->update([
        'judul' => $request->judul,
        'kelas' => $request->kelas, // Diupdate ke kolom 'kelas'
        'pesan' => $request->pesan,
        'user_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Pengumuman berhasil diperbarui!');
}
    // Hapus Pengumuman (Tombol Sampah di Gambar)
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus!');
    }
}
