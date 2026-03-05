<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    // Tampilkan Daftar Pengumuman (Gambar 1 yang Anda kirim)
    public function index()
    {
        $pengumumans = Pengumuman::with('user')->latest()->get();
        return view('pengumuman.index', compact('pengumumans'));
    }

    // Simpan Pengumuman baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
            'target_kelas' => 'nullable|string' // Jika ada field target kelas seperti di gambar
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'target_kelas' => $request->target_kelas,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dipublikasikan!');
    }

    // Update Pengumuman (Fungsi Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update($request->all());

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    // Hapus Pengumuman (Tombol Sampah di Gambar)
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus!');
    }
}
