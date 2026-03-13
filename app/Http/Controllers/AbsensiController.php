<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Mahasiswa;
// Library tambahan untuk fitur ekspor
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    /**
     * Tampilkan daftar absensi per kelas (Tampilan Dosen)
     * Data diurutkan ASC agar Pertemuan 1 berada di atas.
     */
    public function index(Request $request, $kelas = null)
{
    $prodi = $request->query('prodi');
    $mahasiswa = collect(); // Default kosong

    // JIKA ada parameter kelas, ambil data mahasiswa untuk ditampilkan di form
    if ($kelas && $kelas !== 'pilih') {
        $mahasiswa = \App\Models\Mahasiswa::where('kelas', $kelas)
                        ->where('prodi', $prodi)
                        ->orderBy('name', 'asc')
                        ->get();
    }

    return view('dosen.absensi', compact('kelas', 'mahasiswa'));
}

    /**
     * Form tambah absensi
     */
    public function create($kelas)
{
    // Ambil prodi dari URL (misal: ?prodi=Manajemen Informatika)
    $prodi = request('prodi');

    // Pastikan query mencari mahasiswa berdasarkan kelas DAN prodi agar tidak tertukar
    $mahasiswa = \App\Models\Mahasiswa::where('kelas', $kelas)
                ->where('prodi', $prodi)
                ->orderBy('name', 'asc')
                ->get();

    // Kirim data ke view
    return view('dosen.create_absen', compact('kelas', 'mahasiswa'));
}

    /**
     * Simpan absensi mahasiswa ke database
     */
   public function store(Request $request)
{
    // 1. Validasi input yang benar-benar dikirim dari form
    $request->validate([
        'mahasiswa_id' => 'required|array',
        'status' => 'required|array',
        'kelas' => 'required|string',
    ]);

    // 2. Loop berdasarkan mahasiswa_id
    foreach ($request->mahasiswa_id as $index => $id) {
        // Ambil data mahasiswa asli dari DB agar datanya akurat
        $mhs = \App\Models\Mahasiswa::find($id);

        if ($mhs) {
            Absensi::create([
                'nama_mahasiswa' => $mhs->name, // Mengambil nama dari model Mahasiswa
                'nim_mahasiswa'  => $mhs->nim_nip, // Mengambil NIM dari model Mahasiswa
                'kelas'          => $request->kelas,
                'status'         => $request->status[$index],
                'tanggal'        => now()->format('Y-m-d'),
            ]);
        }
    }

    return redirect()->route('dosen.absensi', ['kelas' => $request->kelas, 'prodi' => $request->prodi])
                     ->with('success', 'Absensi berhasil disimpan!');
}

    /**
     * Hapus data absensi
     */
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->back()->with('success', 'Absensi berhasil dihapus!');
    }

    /**
     * Tampilan Rekap Matriks Mingguan (Horizontal)
     */
    public function rekapMingguan($kelas)
    {
        $mahasiswa = Mahasiswa::where('kelas', $kelas)->orderBy('nama', 'asc')->get();

        $daftarPertemuan = Absensi::where('kelas', $kelas)
            ->select('tanggal')
            ->distinct()
            ->orderBy('tanggal', 'asc')
            ->get();

        $absensiRaw = Absensi::where('kelas', $kelas)->get();

        return view('dosen.rekap_mingguan', compact('mahasiswa', 'daftarPertemuan', 'absensiRaw', 'kelas'));
    }

    /**
     * --- FITUR ADMIN: EXPORT REKAP PER PERTEMUAN ---
     */

    /**
     * Unduh Rekap Absensi Format PDF
     */
    public function exportPdf($kelas)
    {
        $absensi = Absensi::where('kelas', $kelas)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mengelompokkan data berdasarkan tanggal untuk tampilan pertemuan di PDF
        $groupedAbsensi = $absensi->groupBy('tanggal');
        $pertemuanKe = 1;

        $pdf = Pdf::loadView('admin.absensi.export_pdf', compact('groupedAbsensi', 'kelas', 'pertemuanKe'));

        return $pdf->setPaper('a4', 'portrait')->download("Rekap_Absensi_{$kelas}.pdf");
    }

    /**
     * Unduh Rekap Absensi Format Excel (.xls)
     */
    public function exportExcel($kelas)
    {
        $absensi = Absensi::where('kelas', $kelas)
            ->orderBy('tanggal', 'asc')
            ->get();

        $groupedAbsensi = $absensi->groupBy('tanggal');

        return response()->view('admin.absensi.export_excel', compact('groupedAbsensi', 'kelas'))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', "attachment; filename=Rekap_Absensi_{$kelas}.xls");
    }
}
