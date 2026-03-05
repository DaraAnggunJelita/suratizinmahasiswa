<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratIzin;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratIzinController extends Controller
{
    /**
     * Tampilkan semua surat izin & Dashboard Admin
     * Mengambil data statistik, daftar surat, dan pengumuman.
     */
    public function index()
    {
        // 1. Statistik Surat (Untuk Card di Dashboard)
        $totalSurat = SuratIzin::count();
        $menunggu = SuratIzin::where('status', 'menunggu')->count();
        $disetujui = SuratIzin::where('status', 'disetujui')->count();
        $ditolak = SuratIzin::where('status', 'ditolak')->count();

        // 2. Ambil daftar surat izin untuk tabel (Lengkap dengan relasi user)
        $suratIzins = SuratIzin::with('user')->latest()->get();

        // 3. AMBIL DATA PENGUMUMAN UNTUK DASHBOARD
        // Diambil 5 terbaru agar tampilan kolom kanan tetap rapi
        $pengumumans = Pengumuman::latest()->take(5)->get();

        // Mengirimkan semua variabel ke view admin.surat_izin.index
        return view('admin.surat_izin.index', compact(
            'totalSurat',
            'menunggu',
            'disetujui',
            'ditolak',
            'suratIzins',
            'pengumumans'
        ));
    }

    /**
     * Fungsi untuk menyimpan pengumuman dari Quick Broadcast di Dashboard
     */
    public function storePengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil disiarkan!');
    }

    /**
     * Lihat detail surat izin
     */
    public function show($id)
    {
        $suratIzin = SuratIzin::with('user')->findOrFail($id);
        return view('admin.surat_izin.show', compact('suratIzin'));
    }

    /**
     * Verifikasi surat izin (Disetujui / Ditolak)
     * Digunakan oleh tombol Ceklis dan Silang di Dashboard Admin
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $suratIzin = SuratIzin::findOrFail($id);
        $suratIzin->update([
            'status' => $request->status,
        ]);

        // Jika disetujui, Anda bisa menambahkan logika otomatis absensi di sini jika diperlukan

        return redirect()->back()->with('success', 'Status surat izin berhasil diperbarui menjadi ' . $request->status . '!');
    }

    /**
     * Menghapus data surat izin (Jika diperlukan fitur hapus di tabel)
     */
    public function destroy($id)
    {
        $surat = SuratIzin::findOrFail($id);
        $surat->delete();

        return redirect()->back()->with('success', 'Data surat izin berhasil dihapus!');
    }
}
