<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\SuratIzin;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function absensiByKelas(Request $request, $kelas = null)
    {
        $prodi = $request->query('prodi');
        $tanggalHariIni = date('Y-m-d');
        $mahasiswa = collect();

        if ($kelas) {
            $absensiExisting = Absensi::where('kelas', $kelas)
                                ->where('tanggal', $tanggalHariIni)
                                ->get();

            if ($absensiExisting->isNotEmpty()) {
                $mahasiswa = $absensiExisting->map(function($item) {
                    return (object)[
                        'nim_nip' => $item->nim_mahasiswa,
                        'name' => $item->nama_mahasiswa,
                        'status' => $item->status
                    ];
                });
            } else {
                $mahasiswa = User::where('role', 'mahasiswa')
                                ->where('kelas', $kelas)
                                ->where('prodi', $prodi)
                                ->orderBy('name', 'asc')
                                ->get();
            }
        }

        return view('dosen.absensi', compact('kelas', 'mahasiswa', 'prodi'));
    }

   public function storeAbsen(Request $request)
{
    // 1. Validasi Input
    if (!$request->has('nim')) {
        return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
    }

    // 2. Standarisasi Tanggal (Menggunakan PHP Native)
    // Jika $request->tanggal kosong, otomatis gunakan tanggal hari ini
    $tanggalInput = $request->tanggal ? date('Y-m-d', strtotime($request->tanggal)) : date('Y-m-d');

    try {
        // Gunakan Full Namespace untuk DB agar tidak perlu import di atas
        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $tanggalInput) {
            foreach ($request->nim as $key => $nim) {
                // updateOrCreate: Jika NIM & Tanggal SAMA -> Update. Jika BEDA -> Buat Baru.
                \App\Models\Absensi::updateOrCreate(
                    [
                        'nim_mahasiswa' => $nim,
                        'tanggal'       => $tanggalInput,
                    ],
                    [
                        'nama_mahasiswa' => $request->nama[$key],
                        'kelas'          => $request->kelas,
                        'status'         => $request->status[$key] ?? 'Hadir',
                    ]
                );
            }
        });

        // 3. Redirect ke Route (PENTING untuk mencegah data ganda saat Refresh/F5)
        return redirect()->route('dosen.absensi', [
            'kelas' => $request->kelas,
            'prodi' => $request->prodi
        ])->with('success', 'Presensi berhasil diperbarui!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
    }
}

    // Fungsi lain tetap dipertahankan sesuai aslinya
    public function suratDetail($id) { $surat = SuratIzin::with('user')->findOrFail($id); return view('dosen.surat_detail', compact('surat')); }
    public function setujuiSurat($id) { SuratIzin::findOrFail($id)->update(['status' => 'disetujui']); return redirect()->back()->with('success', 'Disetujui!'); }
    public function tolakSurat($id) { SuratIzin::findOrFail($id)->update(['status' => 'ditolak']); return redirect()->back()->with('success', 'Ditolak!'); }
    public function hapusAbsen($id) { Absensi::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Dihapus.'); }
}
