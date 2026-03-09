@extends('layouts.app')

@section('title', 'Rekap Absensi ' . $kelas)

@section('content')
<div class="container animate__animated animate__fadeIn">
    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-minimal mb-3 d-flex align-items-center py-2 px-3 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2 text-success"></i>
        <div class="small fw-bold text-success">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto small" data-bs-dismiss="alert" style="font-size: 0.5rem;"></button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Rekap Absensi</h4>
            <p class="text-muted x-small mb-0">Kelas: <span class="badge-class-sm">{{ $kelas }}</span></p>
        </div>
        <div class="col-auto">
            <a href="{{ route('dosen.createAbsen', $kelas) }}" class="btn btn-primary-compact rounded-pill px-3 shadow-sm">
                <i class="fas fa-plus-circle me-1 small"></i> Tambah Absen
            </a>
        </div>
    </div>

    {{-- TABEL REKAP --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-2 text-muted fw-bold x-small" style="width: 50px;">NO</th>
                            <th class="text-muted fw-bold x-small">MAHASISWA</th>
                            <th class="text-muted fw-bold x-small">NIM</th>
                            <th class="text-muted fw-bold x-small">STATUS</th>
                            <th class="text-muted fw-bold x-small">TANGGAL</th>
                            <th class="text-muted fw-bold x-small text-center" style="width: 140px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $index => $row)
                        <tr class="transition-all">
                            <td class="ps-3 x-small fw-medium text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.8rem;">{{ $row->nama_mahasiswa }}</div>
                            </td>
                            <td class="x-small text-muted">{{ $row->nim_mahasiswa }}</td>
                            <td>
                                @php
                                    $statusStyle = [
                                        'Hadir' => 'bg-success-light text-success',
                                        'Izin'  => 'bg-warning-light text-warning',
                                        'Sakit' => 'bg-info-light text-info',
                                        'Alfa'  => 'bg-danger-light text-danger'
                                    ][$row->status] ?? 'bg-light text-secondary';
                                @endphp
                                <span class="badge-status {{ $statusStyle }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center x-small text-muted">
                                    <i class="far fa-calendar-alt me-1 opacity-50"></i>
                                    {{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/y') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('dosen.editAbsen', $row->id) }}" class="btn-action-edit" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('dosen.destroyAbsen', $row->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-delete" onclick="return confirm('Hapus data?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-inbox mb-2 text-muted opacity-25 d-block" style="font-size: 1.5rem;"></i>
                                <p class="x-small text-muted mb-0">Belum ada data absensi.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Global Helpers */
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.7rem; }
    .transition-all { transition: all 0.2s ease; }

    /* Buttons */
    .btn-primary-compact {
        background: #0d1b2a; color: white; border: none; font-size: 0.75rem; font-weight: 600; padding: 6px 15px; transition: 0.2s;
    }
    .btn-primary-compact:hover { background: #1b263b; transform: translateY(-1px); color: white; }

    /* Badges */
    .badge-class-sm {
        background: #eff6ff; color: #2563eb; padding: 2px 8px; border-radius: 5px; font-weight: 700; font-size: 0.65rem; text-transform: uppercase;
    }

    .badge-status {
        font-size: 0.6rem; font-weight: 800; padding: 3px 10px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.3px;
    }

    /* Soft Colors for Status */
    .bg-success-light { background: #dcfce7; }
    .bg-warning-light { background: #fef9c3; }
    .bg-info-light { background: #e0f2fe; }
    .bg-danger-light { background: #fee2e2; }

    /* Action Buttons (Mini) */
    .btn-action-edit, .btn-action-delete {
        width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 0.65rem; border: none; transition: 0.2s; text-decoration: none;
    }
    .btn-action-edit { background: #fef9c3; color: #a16207; }
    .btn-action-edit:hover { background: #fde047; }
    .btn-action-delete { background: #fee2e2; color: #b91c1c; }
    .btn-action-delete:hover { background: #fecaca; }

    /* Table Styles */
    .table thead th {
        background: #f8fafc; border-bottom: 1px solid #f1f5f9; letter-spacing: 0.5px;
    }
    .table tbody tr:hover { background: #fcfdfe; }

    .alert-minimal {
        background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
    }
</style>
@endsection
