@extends('layouts.app')

@section('title', 'Rekap Absensi ' . $kelas)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Rekap Absensi {{ $kelas }}</h2>
        <p class="text-muted small">Laporan riwayat kehadiran mahasiswa kelas <span class="badge bg-primary-subtle text-primary">{{ $kelas }}</span></p>
    </div>
    <a href="{{ route('dosen.createAbsen', $kelas) }}" class="btn btn-navy-grad rounded-pill px-4 shadow-sm">
        <i class="fas fa-plus-circle me-2"></i> Tambah Absensi
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="py-3 ps-4 text-muted small fw-bold" style="width: 60px;">NO</th>
                        <th class="text-muted small fw-bold">NAMA MAHASISWA</th>
                        <th class="text-muted small fw-bold">NIM</th>
                        <th class="text-muted small fw-bold">STATUS</th>
                        <th class="text-muted small fw-bold">TANGGAL</th>
                        <th class="text-muted small fw-bold text-center pe-4" style="width: 180px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $index => $a)
                    <tr class="border-bottom">
                        <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $a->nama_mahasiswa }}</div>
                        </td>
                        <td class="text-secondary small">{{ $a->nim_mahasiswa }}</td>
                        <td>
                            @php
                                $statusClass = [
                                    'Hadir' => 'bg-success-subtle text-success border-success',
                                    'Izin' => 'bg-warning-subtle text-warning border-warning',
                                    'Sakit' => 'bg-info-subtle text-info border-info',
                                    'Alpa' => 'bg-danger-subtle text-danger border-danger',
                                ];
                                $color = $statusClass[$a->status] ?? 'bg-light text-dark border-secondary';
                            @endphp
                            <span class="badge {{ $color }} border px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                {{ strtoupper($a->status) }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}
                        </td>
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('dosen.editAbsen', $a->id) }}" class="btn btn-sm btn-outline-warning rounded-3 px-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('dosen.hapusAbsen', $a->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-outline-danger rounded-3 px-3">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-0">Belum ada rekaman absensi untuk kelas ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Gradient Button */
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-navy-grad:hover {
        background: #415A77;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 27, 42, 0.2);
    }

    /* Subtle Badge Colors */
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .bg-info-subtle { background-color: #f0f9ff !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }

    /* Action Buttons */
    .btn-outline-warning:hover { color: #fff !important; }

    .table-hover tbody tr:hover {
        background-color: #fbfcfe;
    }
</style>
@endsection
