@extends('layouts.app')

@section('title', 'Rekap Absensi ' . $kelas)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold text-dark">Rekap Absensi {{ $kelas }}</h2>
            <p class="text-muted">Laporan riwayat kehadiran mahasiswa kelas <span class="badge bg-primary text-uppercase">{{ $kelas }}</span></p>
        </div>
        <div class="col-auto">
            <a href="{{ route('dosen.createAbsen', $kelas) }}" class="btn btn-navy-dark rounded-pill px-4 py-2 shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Tambah Absensi
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold small" style="width: 80px;">NO</th>
                            <th class="text-muted fw-bold small">NAMA MAHASISWA</th>
                            <th class="text-muted fw-bold small">NIM</th>
                            <th class="text-muted fw-bold small">STATUS</th>
                            <th class="text-muted fw-bold small">TANGGAL</th>
                            <th class="text-muted fw-bold small text-center" style="width: 180px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $index => $row)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $index + 1 }}</td>
                            <td><span class="fw-bold text-dark">{{ $row->nama_mahasiswa }}</span></td>
                            <td class="text-muted">{{ $row->nim_mahasiswa }}</td>
                            <td>
                                @php
                                    $badgeColor = [
                                        'Hadir' => 'border-success text-success',
                                        'Izin' => 'border-warning text-warning',
                                        'Sakit' => 'border-info text-info',
                                        'Alfa' => 'border-danger text-danger'
                                    ][$row->status] ?? 'border-secondary text-secondary';
                                @endphp
                                <span class="badge border {{ $badgeColor }} px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 0.7rem;">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="far fa-calendar-alt me-2"></i>
                                    {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('dosen.editAbsen', $row->id) }}" class="btn btn-sm btn-outline-warning border-2 rounded-3 me-1">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('dosen.destroyAbsen', $row->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-2 rounded-3" onclick="return confirm('Hapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data absensi untuk kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-navy-dark {
        background-color: #0d1b2a;
        color: white;
        transition: 0.3s;
    }
    .btn-navy-dark:hover {
        background-color: #1b263b;
        color: #ffffff;
        transform: translateY(-1px);
    }
    .table thead th {
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f0f2f5;
    }
    .badge.border {
        background-color: transparent !important;
        border-width: 1.5px !important;
    }
</style>
@endsection
