<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\SuratIzin;
use App\Models\Mahasiswa;

class DashboardDosenController extends Controller
{
    /**
     * Dashboard utama dosen
     * Menampilkan semua surat izin, dengan filter kelas opsional
     */
    public function index(Request $request)
{
    $kelas = $request->query('kelas');

    // Ambil surat izin beserta user
    $query = SuratIzin::with('user')->orderBy('tanggal_mulai', 'desc');

    if ($kelas) {
        $query->whereHas('user', function ($q) use ($kelas) {
            $q->where('kelas', $kelas);
        });
    }

    $suratIzin = $query->get();

    return view('dosen.dashboard', compact('suratIzin', 'kelas'));
}
    /**
     * Daftar absensi
     * Bisa filter per kelas jika query kelas dikirim
     */
    public function absen(Request $request)
    {
        $kelas = $request->query('kelas');

        $query = Absensi::orderBy('tanggal','desc');

        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        $absensi = $query->get();

        return view('dosen.absensi', compact('absensi','kelas'));
    }

    /**
     * Form input absensi manual
     */
    public function createAbsen()
    {
        $mahasiswa = Mahasiswa::orderBy('nama')->get();
        return view('dosen.create_absen', compact('mahasiswa'));
    }

    /**
     * Simpan absensi manual
     */
    public function storeAbsen(Request $request)
    {
        $request->validate([
            'nama_mahasiswa.*' => 'required|string|max:255',
            'nim_mahasiswa.*' => 'required|string|max:255',
            'kelas' => 'required|string',
            'status.*' => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        $dosen_id = Auth::id();

        foreach ($request->nama_mahasiswa as $index => $nama) {
            Absensi::create([
                'nama_mahasiswa' => $nama,
                'nim_mahasiswa' => $request->nim_mahasiswa[$index],
                'kelas' => $request->kelas,
                'dosen_id' => $dosen_id,
                'status' => $request->status[$index],
                'tanggal' => now(),
            ]);
        }

        return redirect()->route('dosen.absensi')->with('success', 'Absensi berhasil ditambahkan!');
    }

    /**
     * Form edit absensi
     */
    public function editAbsen($id)
    {
        $absen = Absensi::findOrFail($id);
        $statusOptions = ['Hadir','Izin','Sakit','Alfa'];
        return view('dosen.edit_absen', compact('absen','statusOptions'));
    }

    /**
     * Update absensi
     */
    public function updateAbsen(Request $request, $id)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'nim_mahasiswa' => 'required|string|max:255',
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'nim_mahasiswa' => $request->nim_mahasiswa,
            'status' => $request->status,
        ]);

        return redirect()->route('dosen.absensi')->with('success','Absen berhasil diupdate!');
    }

    /**
     * Hapus absensi
     */
    public function hapusAbsen($id)
    {
        Absensi::findOrFail($id)->delete();
        return redirect()->route('dosen.absensi')->with('success','Absen berhasil dihapus!');
    }

    /**
     * Daftar surat izin
     */
    public function surat()
    {
        $suratIzin = SuratIzin::latest()->get();
        return view('dosen.surat', compact('suratIzin'));
    }

    /**
     * Hapus surat izin
     */
    public function hapusSurat($id)
    {
        SuratIzin::findOrFail($id)->delete();
        return redirect()->route('dosen.dashboard')->with('success','Surat izin berhasil dihapus!');
    }

    /**
     * Lihat detail surat izin
     */
    public function suratDetail($id)
    {
        $surat = SuratIzin::findOrFail($id);
        return view('dosen.surat_detail', compact('surat'));
    }

    /**
     * Verifikasi surat izin
     */
    public function suratVerifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $surat = SuratIzin::findOrFail($id);
        $surat->status = $request->status;
        $surat->save();

        return redirect()->route('dosen.dashboard')->with('success', 'Surat berhasil diperbarui!');
    }
}
