@extends('layouts.app')

@section('title', 'Detail Absensi ' . $kelas)

@section('content')
<div class="container animate__animated animate__fadeIn">
    {{-- BREADCRUMB & HEADER --}}
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.absensi.index') }}" class="text-primary text-decoration-none">Monitoring</a></li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">Kelas {{ $kelas }}</li>
                </ol>
            </nav>
            <h4 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Detail Kehadiran</h4>
            <p class="text-muted x-small mb-0">Manajemen data absensi mahasiswa kelas <span class="badge-class-sm">{{ $kelas }}</span></p>
        </div>
        <a href="{{ route('admin.absensi.index') }}" class="btn btn-light border rounded-pill px-3 btn-sm x-small fw-bold text-muted">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- STATS CARD MINI --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body py-2 px-3 d-flex align-items-center">
                    <div class="bg-primary-light text-primary rounded-2 p-2 me-3">
                        <i class="fas fa-users small"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">Total Baris</div>
                        <div class="fw-800 text-dark h6 mb-0">{{ $absensi->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE DETAIL --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-2 text-muted x-small fw-bold" style="width: 20%;">TANGGAL</th>
                            <th class="text-muted x-small fw-bold">MAHASISWA</th>
                            <th class="text-muted x-small fw-bold" style="width: 15%;">NIM</th>
                            <th class="text-muted x-small fw-bold pe-3" style="width: 15%;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $item)
                        <tr class="transition-all">
                            <td class="ps-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark x-small">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                    <span class="text-muted" style="font-size: 0.6rem;"><i class="far fa-clock me-1"></i>{{ $item->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-mini me-2">{{ strtoupper(substr($item->nama_mahasiswa, 0, 1)) }}</div>
                                    <span class="fw-bold text-dark x-small">{{ $item->nama_mahasiswa }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-muted border-0 fw-bold" style="font-size: 0.65rem;">{{ $item->nim_mahasiswa }}</span>
                            </td>
                            <td class="pe-3">
                                @php
                                    $status = strtolower($item->status);
                                    $style = [
                                        'hadir' => 'bg-success-light text-success',
                                        'izin'  => 'bg-info-light text-info',
                                        'sakit' => 'bg-warning-light text-warning',
                                        'alpa'  => 'bg-danger-light text-danger'
                                    ][$status] ?? 'bg-light text-secondary';
                                @endphp
                                <span class="badge-status {{ $style }}">
                                    <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i> {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-folder-open mb-2 text-muted opacity-25 d-block" style="font-size: 2rem;"></i>
                                <p class="x-small text-muted mb-0">Data absensi belum tersedia.</p>
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
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.75rem; }
    .transition-all { transition: all 0.2s ease; }

    /* Stats & Badges */
    .bg-primary-light { background: #eff6ff; }
    .badge-class-sm { background: #eff6ff; color: #2563eb; padding: 2px 8px; border-radius: 5px; font-weight: 700; font-size: 0.65rem; }

    .badge-status {
        font-size: 0.65rem; font-weight: 800; padding: 4px 12px; border-radius: 50px; text-transform: uppercase; display: inline-flex; align-items: center;
    }

    /* Soft Colors */
    .bg-success-light { background: #dcfce7; }
    .bg-info-light { background: #e0f2fe; }
    .bg-warning-light { background: #fef9c3; }
    .bg-danger-light { background: #fee2e2; }

    /* Avatar Mini */
    .avatar-mini {
        width: 28px; height: 28px; background: #f1f5f9; color: #64748b;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px; font-size: 0.7rem; font-weight: 800; border: 1px solid #e2e8f0;
    }

    /* Table Custom */
    .table thead th { background: #f8fafc; border-bottom: 1px solid #f1f5f9; letter-spacing: 0.5px; }
    .table tbody tr:hover { background: #fcfdfe; }

    /* Breadcrumb Icon Fix */
    .breadcrumb-item + .breadcrumb-item::before {
        content: "\f105"; font-family: "Font Awesome 6 Free"; font-weight: 900; font-size: 0.6rem; color: #cbd5e1;
    }
</style>
@endsection
