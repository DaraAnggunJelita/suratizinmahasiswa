@extends('layouts.app')

@section('title', 'Rekap Absensi ' . $kelas)

@section('content')
<div class="container animate__animated animate__fadeIn">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-1">Rekap Absensi Per Pertemuan</h2>
            <p class="text-muted small">Kelas: <span class="text-primary fw-bold">{{ $kelas }}</span></p>
        </div>
        <a href="{{ route('dosen.createAbsen', $kelas) }}" class="btn btn-navy-grad rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Tambah Absen Baru
        </a>
    </div>

    {{-- Mengelompokkan Absensi Berdasarkan Tanggal --}}
    @php
        $groupedAbsensi = $absensi->groupBy('tanggal');
        $pertemuanKe = 1;
    @endphp

    @forelse($groupedAbsensi as $tanggal => $items)
    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-primary px-3 py-2 rounded-pill me-2">PERTEMUAN {{ $pertemuanKe++ }}</span>
                <span class="fw-bold text-dark"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
            </div>
            <span class="text-muted small fw-bold">{{ $items->count() }} Mahasiswa Terdata</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th class="ps-4 text-muted small fw-bold" style="width: 50px;">NO</th>
                            <th class="text-muted small fw-bold">NAMA MAHASISWA</th>
                            <th class="text-muted small fw-bold">NIM</th>
                            <th class="text-muted small fw-bold text-center">STATUS</th>
                            <th class="text-muted small fw-bold text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $abs)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold text-dark d-block">{{ strtoupper($abs->nama_mahasiswa) }}</span>
                            </td>
                            <td><span class="text-muted small">{{ $abs->nim_mahasiswa }}</span></td>
                            <td class="text-center">
                                @php
                                    $statusClass = match($abs->status) {
                                        'Hadir' => 'bg-success-subtle text-success border-success',
                                        'Izin'  => 'bg-warning-subtle text-warning border-warning',
                                        'Sakit' => 'bg-info-subtle text-info border-info',
                                        'Alfa'  => 'bg-danger-subtle text-danger border-danger',
                                        default => 'bg-secondary-subtle text-secondary border-secondary'
                                    };
                                @endphp
                                <span class="badge border {{ $statusClass }} px-3 py-1 rounded-pill" style="font-size: 0.65rem; min-width: 70px;">
                                    {{ strtoupper($abs->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('dosen.editAbsen', $abs->id) }}" class="btn-action edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dosen.destroyAbsen', $abs->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5 bg-white rounded-4 shadow-sm">
        <img src="https://illustrations.popsy.co/slate/empty-folder.svg" alt="empty" style="width: 150px;" class="mb-3 opacity-50">
        <p class="text-muted">Belum ada data absensi untuk kelas ini.</p>
    </div>
    @endforelse
</div>

<style>
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white; border: none; font-size: 0.85rem; font-weight: 600; transition: 0.3s;
    }
    .btn-navy-grad:hover { transform: translateY(-2px); color: #fff; opacity: 0.9; }

    .bg-success-subtle { background-color: #e8f5e9 !important; }
    .bg-warning-subtle { background-color: #fff8e1 !important; }
    .bg-info-subtle { background-color: #e1f5fe !important; }
    .bg-danger-subtle { background-color: #ffebee !important; }

    .btn-action {
        width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
        border-radius: 6px; transition: 0.2s; border: none; font-size: 0.75rem;
    }
    .btn-action.edit { background: #fff3cd; color: #856404; }
    .btn-action.delete { background: #f8d7da; color: #721c24; }
    .btn-action:hover { transform: scale(1.1); }

    .fw-800 { font-weight: 800; }
</style>
@endsection
