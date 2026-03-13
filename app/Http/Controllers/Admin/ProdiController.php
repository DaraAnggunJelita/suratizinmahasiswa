<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;

class ProdiController extends Controller
{

public function index()
{
    $prodi = Prodi::all(); // Mengambil data asli dari database
    return view('admin.prodi.index', compact('prodi'));
}

public function store(Request $request)
{
    $request->validate([
        'kode' => 'required|unique:prodis,kode',
        'nama' => 'required',
    ]);

    Prodi::create($request->all());

    return redirect()->back()->with('success', 'Prodi berhasil ditambahkan!');
}
public function update(Request $request, $id)
{
    $request->validate([
        'kode' => 'required|unique:prodis,kode,' . $id,
        'nama' => 'required',
    ]);

    $prodi = \App\Models\Prodi::findOrFail($id);
    $prodi->update($request->all());

    return redirect()->back()->with('success', 'Data Prodi berhasil diperbarui!');
}

public function destroy($id)
{
    $prodi = \App\Models\Prodi::findOrFail($id);
    $prodi->delete();

    return redirect()->back()->with('success', 'Data Prodi berhasil dihapus!');
}
}
