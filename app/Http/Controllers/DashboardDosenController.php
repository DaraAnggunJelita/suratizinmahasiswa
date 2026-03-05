<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\SuratIzin;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardDosenController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userName = trim($user->name);
        $kelas = $request->query('kelas', 'MI 3B');

        $jadwals = Jadwal::where('dosen_pengajar', 'LIKE', "%{$userName}%")
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $pengumumans = Pengumuman::latest()->get();

        $daftarKelasDiajar = Jadwal::where('dosen_pengajar', 'LIKE', "%{$userName}%")
            ->pluck('kelas')->unique()->toArray();

        // Ambil semua surat izin dari mahasiswa di kelas yang diajar dosen ini
        $suratIzin = SuratIzin::with('user')
            ->whereHas('user', function ($query) use ($daftarKelasDiajar) {
                $query->whereIn('kelas', $daftarKelasDiajar);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $absensi = Absensi::where('kelas', $kelas)
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalHadir = Absensi::where('kelas', $kelas)->where('status', 'Hadir')->count();
        $totalIzin  = Absensi::where('kelas', $kelas)->whereIn('status', ['Izin', 'Sakit'])->count();
        $totalAlpa  = Absensi::where('kelas', $kelas)->where('status', 'Alpa')->count();

        return view('dosen.dashboard', compact(
            'jadwals', 'suratIzin', 'absensi', 'kelas', 'totalHadir', 'totalIzin', 'totalAlpa', 'pengumumans'
        ));
    }

    // --- FITUR SURAT IZIN ---
    public function suratDetail($id)
    {
        $surat = SuratIzin::with('user')->findOrFail($id);
        return view('dosen.surat_detail', compact('surat'));
    }

    public function setujuiSurat($id)
    {
        $surat = SuratIzin::findOrFail($id);
        $surat->update(['status' => 'disetujui']);
        return redirect()->back()->with('success', 'Surat izin mahasiswa berhasil disetujui!');
    }

    // FUNGSI TOLAK SURAT (DITAMBAHKAN)
    public function tolakSurat($id)
    {
        $surat = SuratIzin::findOrFail($id);
        $surat->update(['status' => 'ditolak']);
        return redirect()->back()->with('success', 'Surat izin mahasiswa telah ditolak.');
    }

    // --- FITUR REKAP ABSENSI PER KELAS ---
    public function absensiByKelas(Request $request, $kelas)
    {
        $query = Absensi::where('kelas', $kelas);

        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->get();
        return view('dosen.absensi', compact('absensi', 'kelas'));
    }

    // --- FITUR INPUT ABSENSI BARU ---
    public function createAbsen($kelas)
    {
        $mahasiswa = User::where('role', 'mahasiswa')
                    ->where('kelas', $kelas)
                    ->orderBy('name', 'asc')
                    ->get();

        $izinHariIni = SuratIzin::where('status', 'disetujui')
                    ->whereDate('created_at', Carbon::today())
                    ->pluck('user_id')
                    ->toArray();

        return view('dosen.create_absen', compact('mahasiswa', 'kelas', 'izinHariIni'));
    }

    public function storeAbsen(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|array',
            'nim_mahasiswa' => 'required|array',
            'status' => 'required|array',
            'kelas' => 'required',
            'tanggal' => 'required'
        ]);

        foreach ($request->nama_mahasiswa as $key => $nama) {
            Absensi::create([
                'nama_mahasiswa' => $nama,
                'nim_mahasiswa'  => $request->nim_mahasiswa[$key],
                'status'         => $request->status[$key],
                'kelas'          => $request->kelas,
                'tanggal'        => $request->tanggal,
            ]);
        }

        return redirect()->route('dosen.absensi', $request->kelas)
                         ->with('success', 'Absensi kelas ' . $request->kelas . ' berhasil disimpan!');
    }

    // --- FITUR EDIT & HAPUS ---
    public function editAbsen($id)
    {
        $absen = Absensi::findOrFail($id);
        return view('dosen.absensi_edit', compact('absen'));
    }

    public function updateAbsen(Request $request, $id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->update($request->all());

        return redirect()->route('dosen.absensi', ['kelas' => $absen->kelas])
            ->with('success', 'Data absensi berhasil diperbarui!');
    }

    public function hapusAbsen($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return redirect()->back()->with('success', 'Data absensi telah dihapus.');
    }
}
