<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SuratIzin;
use App\Models\Jadwal; // Pastikan Model Jadwal sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SuratIzinController extends Controller
{
    /**
     * Menampilkan Dashboard Mahasiswa
     */
    public function index()
    {
        // 1. Mengambil data surat izin milik mahasiswa yang sedang login
        $suratIzins = SuratIzin::where('user_id', Auth::id())
                                ->latest()
                                ->get();

        // 2. Logika Jadwal Kuliah Hari Ini
        // Set locale ke Indonesia agar format hari menjadi "Senin", "Selasa", dsb.
        Carbon::setLocale('id');
        $hariIni = Carbon::now()->translatedFormat('l');

        // Ambil jadwal berdasarkan kelas mahasiswa dan hari ini
        $jadwalHariIni = Jadwal::where('kelas', Auth::user()->kelas)
                                ->where('hari', $hariIni)
                                ->orderBy('jam_mulai', 'asc')
                                ->get();

        // 3. Mengirimkan semua data ke view index mahasiswa
        return view('mahasiswa.surat_izin.index', compact('suratIzins', 'jadwalHariIni'));
    }

    /**
     * Halaman form buat surat izin
     */
    public function create()
    {
        return view('mahasiswa.surat_izin.create');
    }

    /**
     * Menyimpan data surat izin baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_izin' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'alasan' => 'required',
            'bukti_file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'menunggu'; // Status default saat baru buat

        if ($request->hasFile('bukti_file')) {
            $data['bukti_file'] = $request->file('bukti_file')->store('bukti_izin', 'public');
        }

        SuratIzin::create($data);

        return redirect()->route('mahasiswa.surat_izin.index')
                         ->with('success', 'Surat izin berhasil diajukan.');
    }

    /**
     * Menampilkan detail surat atau form edit jika diperlukan
     */
    public function edit($id)
    {
        $suratIzin = SuratIzin::where('user_id', Auth::id())->findOrFail($id);
        return view('mahasiswa.surat_izin.edit', compact('suratIzin'));
    }
}
