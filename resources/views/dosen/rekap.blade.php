@extends('layouts.app')

@section('title', 'Rekap Absensi ' . $kelas)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Rekap Absensi {{ $kelas }}</h2>
        <p class="text-muted small">Laporan riwayat kehadiran mahasiswa kelas <span class="badge bg-primary-subtle text-primary fw-bold">{{ $kelas }}</span></p>
    </div>
    <a href="{{ route('dosen.create_absen', $kelas) }}" class="btn btn-navy-grad rounded-pill px-4 shadow-sm">
        <i class="fas fa-plus-circle me-1"></i> Tambah Absensi
    </a>
</div>

{{-- Form Filter Tanggal --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Filter Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm border-0 bg-light rounded-3" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            @if(request('tanggal'))
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('dosen.absensi', $kelas) }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 w-100">Reset</a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Tabel Rekap --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="py-3 ps-4 text-muted small fw-bold" style="width: 80px;">NO</th>
                        <th class="text-muted small fw-bold">NAMA MAHASISWA</th>
                        <th class="text-muted small fw-bold">NIM</th>
                        <th class="text-muted small fw-bold">STATUS</th>
                        <th class="text-muted small fw-bold">TANGGAL</th>
                        <th class="text-muted small fw-bold text-center" style="width: 150px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $index => $abs)
                    <tr class="border-bottom">
                        <td class="ps-4 fw-medium text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-bold text-dark d-block text-capitalize">{{ $abs->nama_mahasiswa ?? $abs->mahasiswa->nama }}</span>
                        </td>
                        <td><span class="text-muted">{{ $abs->nim_mahasiswa ?? $abs->mahasiswa->nim }}</span></td>
                        <td>
                            @php
                                $statusClass = [
                                    'Hadir' => 'border-success text-success',
                                    'Izin' => 'border-warning text-warning',
                                    'Sakit' => 'border-info text-info',
                                    'Alfa' => 'border-danger text-danger'
                                ][$abs->status] ?? 'border-secondary text-secondary';
                            @endphp
                            <span class="badge border {{ $statusClass }} px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                {{ strtoupper($abs->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="small text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($abs->tanggal)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('dosen.edit_absen', $abs->id) }}" class="btn btn-sm btn-outline-warning border-2 rounded-3 px-3">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('dosen.destroy_absen', $abs->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-2 rounded-3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://illustrations.popsy.co/slate/empty-folder.svg" alt="empty" style="width: 120px;" class="mb-3">
                            <p class="text-muted fw-medium">Tidak ada data absensi ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white; border: none; transition: 0.3s;
    }
    .btn-navy-grad:hover {
        background: #415A77; color: white; transform: translateY(-2px);
    }
    .badge.border {
        background-color: transparent !important;
        border-width: 1.5px !important;
    }
</style>
@endsection
