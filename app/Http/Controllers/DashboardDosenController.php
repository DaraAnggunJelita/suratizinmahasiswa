<?php

namespace App\Http\Controllers; // <--- Cek apakah ini sama dengan di Route

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\SuratIzin;
use Illuminate\Support\Facades\Auth;

class DashboardDosenController extends Controller // <--- Nama Class harus sama dengan Nama File
{
    public function index()
{
    $user = Auth::user();

    // 1. Ambil nama dosen dan bersihkan spasi
    $userName = trim($user->name);

    // 2. Cari kelas yang diajar oleh dosen ini di tabel Jadwal
    // Gunakan LIKE untuk mengantisipasi jika satu jadwal ditulis lebih dari satu dosen
    $daftarKelasDiajar = Jadwal::where('dosen_pengajar', 'LIKE', "%{$userName}%")
        ->pluck('kelas')
        ->unique()
        ->toArray();

    // 3. Ambil data Surat Izin hanya untuk mahasiswa yang ada di kelas tersebut
    if (!empty($daftarKelasDiajar)) {
        $suratIzin = SuratIzin::with('user')
            ->whereHas('user', function ($query) use ($daftarKelasDiajar) {
                $query->whereIn('kelas', $daftarKelasDiajar);
            })
            ->get();
    } else {
        // Jika dosen tidak punya jadwal, kirim koleksi kosong agar tidak error
        $suratIzin = collect();
    }

    // 4. Ambil Jadwal untuk ditampilkan di kalender dashboard
    $jadwals = Jadwal::where('dosen_pengajar', 'LIKE', "%{$userName}%")
        ->orderBy('jam_mulai', 'asc')
        ->get()
        ->groupBy('hari');

    return view('dosen.dashboard', compact('jadwals', 'suratIzin'));
}
    public function suratDetail($id)
    {
        $surat = SuratIzin::with('user')->findOrFail($id);
        return view('dosen.surat_detail', compact('surat'));
    }

    public function verifikasi(Request $request, $id)
{
    // Cari data surat
    $surat = SuratIzin::find($id);

    if (!$surat) {
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
    }

    // Ambil status dari request (sudah huruf kecil dari JS)
    $surat->status = $request->status;

    // Opsional: Jika ingin menambahkan catatan dosen bisa di sini
    // $surat->catatan_dosen = $request->catatan;

    if ($surat->save()) {
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Gagal menyimpan data']);
}

    public function hapusSurat($id) // <--- Sesuaikan nama method dengan Route
    {
        $surat = SuratIzin::findOrFail($id);
        $surat->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
