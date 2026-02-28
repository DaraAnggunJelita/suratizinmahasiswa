@extends('layouts.app')

@section('title', 'Verifikasi Surat Izin')

@section('content')
<h2>Verifikasi Surat Izin</h2>

<table class="table">
    <tr><th>Nama Mahasiswa</th><td>{{ $suratIzin->user->name }}</td></tr>
    <tr><th>Jenis Izin</th><td>{{ ucfirst($suratIzin->jenis_izin) }}</td></tr>
    <tr><th>Tanggal</th><td>{{ $suratIzin->tanggal_mulai }} s/d {{ $suratIzin->tanggal_selesai }}</td></tr>
    <tr><th>Alasan</th><td>{{ $suratIzin->alasan }}</td></tr>
    <tr><th>Bukti</th>
        <td>
            @if($suratIzin->bukti_file)
                <a href="{{ asset('storage/'.$suratIzin->bukti_file) }}" target="_blank">Lihat</a>
            @else
                -
            @endif
        </td>
    </tr>
</table>

<form action="{{ url('/admin/surat-izin/'.$suratIzin->id.'/verifikasi') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="disetujui" {{ $suratIzin->status=='disetujui'?'selected':'' }}>Disetujui</option>
            <option value="ditolak" {{ $suratIzin->status=='ditolak'?'selected':'' }}>Ditolak</option>
        </select>
    </div>

    {{-- <div class="mb-3">
        <label>Catatan Admin</label>
        <textarea name="catatan_admin" class="form-control" rows="3">{{ $suratIzin->catatan_admin }}</textarea>
    </div> --}}

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ url('/admin/surat-izin') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
