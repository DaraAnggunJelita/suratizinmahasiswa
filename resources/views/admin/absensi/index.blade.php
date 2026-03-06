@extends('layouts.app')
@section('title', 'Manajemen Absensi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Pilih Kelas untuk Absensi</h4>
    </div>

    <div class="row">
        @foreach($daftar_kelas as $kelas)
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm animate__animated animate__fadeIn">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <h5 class="fw-bold">{{ $kelas }}</h5>
                    <p class="text-muted small">Lihat riwayat kehadiran mahasiswa kelas {{ $kelas }}</p>
                    <a href="{{ route('admin.absensi.rekap', $kelas) }}" class="btn btn-outline-primary w-100 rounded-pill">
                        Lihat Absensi
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
