<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratIzinController extends Controller
{
    // Tampilkan daftar surat izin mahasiswa
    public function index()
    {
        $suratIzins = SuratIzin::where('user_id', Auth::id())->latest()->get();
        return view('mahasiswa.surat_izin.index', compact('suratIzins'));
    }

    public function create()
    {
        return view('mahasiswa.surat_izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_izin' => 'required|in:sakit,izin',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_file')) {
            $buktiPath = $request->file('bukti_file')->store('bukti', 'public');
        }

        SuratIzin::create([
            'user_id' => Auth::id(),
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'bukti_file' => $buktiPath,
        ]);

        return redirect('/mahasiswa/surat-izin')->with('success', 'Surat izin berhasil dibuat!');
    }

    public function edit($id)
    {
        $suratIzin = SuratIzin::where('user_id', Auth::id())->findOrFail($id);
        return view('mahasiswa.surat_izin.edit', compact('suratIzin'));
    }

    public function update(Request $request, $id)
    {
        $suratIzin = SuratIzin::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'jenis_izin' => 'required|in:sakit,izin',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('bukti_file')) {
            if ($suratIzin->bukti_file) {
                Storage::disk('public')->delete($suratIzin->bukti_file);
            }
            $suratIzin->bukti_file = $request->file('bukti_file')->store('bukti', 'public');
        }

        $suratIzin->update([
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
        ]);

        return redirect('/mahasiswa/surat-izin')->with('success', 'Surat izin berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $suratIzin = SuratIzin::where('user_id', Auth::id())->findOrFail($id);

        if ($suratIzin->bukti_file) {
            Storage::disk('public')->delete($suratIzin->bukti_file);
        }

        $suratIzin->delete();

        return redirect('/mahasiswa/surat-izin')->with('success', 'Surat izin berhasil dihapus!');
    }
}
