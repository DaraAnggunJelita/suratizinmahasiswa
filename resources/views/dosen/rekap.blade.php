@extends('layouts.app')

@section('title', 'Rekap Absensi Mahasiswa MI 3A')

@section('content')
<h2 class="mb-4">Rekap Absensi Mahasiswa MI 3A</h2>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Status Kehadiran</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($absensi as $index => $abs)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $abs->mahasiswa->nim }}</td>
            <td>{{ $abs->mahasiswa->nama }}</td>
            <td>{{ $abs->status }}</td>
            <td>{{ $abs->tanggal }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Tidak ada data absensi</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
