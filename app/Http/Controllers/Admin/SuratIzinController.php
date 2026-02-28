<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratIzin;
use Illuminate\Http\Request;

class SuratIzinController extends Controller
{
    // Tampilkan semua surat izin
    public function index()
    {
        $totalSurat = SuratIzin::count();
        $menunggu = SuratIzin::where('status','menunggu')->count();
        $disetujui = SuratIzin::where('status','disetujui')->count();
        $ditolak = SuratIzin::where('status','ditolak')->count();

        $suratIzins = SuratIzin::with('user')->latest()->get();

        return view('admin.surat_izin.index', compact(
            'totalSurat', 'menunggu', 'disetujui', 'ditolak', 'suratIzins'
        ));
    }

    // Lihat detail surat izin
    public function show($id)
    {
        $suratIzin = SuratIzin::with('user')->findOrFail($id);
        return view('admin.surat_izin.show', compact('suratIzin'));
    }

    // Verifikasi surat izin (Disetujui / Ditolak)
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $suratIzin = SuratIzin::findOrFail($id);
        $suratIzin->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status surat izin berhasil diperbarui!');
    }
}
