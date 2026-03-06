<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Absensi;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim_nip' => 'required|string|unique:users,nim_nip',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nim_nip' => $request->nim_nip, // tetap sesuai input
            'role' => 'dosen',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Dosen berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nim_nip' => 'required|string|unique:users,nim_nip,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nim_nip' => $request->nim_nip,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dihapus!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function listKelas()
{
    // Mengambil daftar kelas unik dari tabel users yang rolenya mahasiswa
    $daftar_kelas = User::where('role', 'mahasiswa')
                        ->whereNotNull('kelas')
                        ->distinct()
                        ->orderBy('kelas', 'asc') // Tambahkan ini agar MI 3A di atas
                        ->pluck('kelas');

    return view('admin.absensi.index', compact('daftar_kelas'));
}

public function rekapAbsen($kelas)
{
    // Mengambil data absensi langsung dari tabel absensi berdasarkan kolom kelas
    // Kita tetap pakai with('user') agar bisa mengambil foto profil atau data lain jika dibutuhkan
    $absensi = Absensi::where('kelas', $kelas)
                      ->orderBy('tanggal', 'desc')
                      ->get();

    return view('admin.absensi.rekap', compact('absensi', 'kelas'));
}
}
