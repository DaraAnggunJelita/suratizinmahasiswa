@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Edit Absensi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('dosen.updateAbsen', $absensi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Mahasiswa</label>
            <input type="text" class="form-control" value="{{ $absensi->nama_mahasiswa }}" readonly>
        </div>

        <div class="mb-3">
            <label>NIM</label>
            <input type="text" class="form-control" value="{{ $absensi->nim_mahasiswa }}" readonly>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Hadir" {{ $absensi->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="Izin" {{ $absensi->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                <option value="Sakit" {{ $absensi->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="Alfa" {{ $absensi->status == 'Alfa' ? 'selected' : '' }}>Alfa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Absensi</button>
        <a href="{{ route('dosen.absensi', $absensi->kelas) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
