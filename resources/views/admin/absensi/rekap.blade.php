@extends('layouts.app')

@section('title', 'Rekap Absensi ' . $kelas)

@section('content')
<div class="container animate__animated animate__fadeIn py-4">

    {{-- HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-soft-primary text-primary px-3 rounded-pill fw-bold" style="font-size: 0.6rem;">ADMIN MONITORING</span>
                <span class="text-muted" style="font-size: 0.6rem;"><i class="fas fa-chevron-right"></i></span>
                <span class="text-muted fw-bold" style="font-size: 0.6rem;">KELAS {{ $kelas }}</span>
            </div>
            <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -1.2px;">Rekapitulasi Absensi</h3>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-dark rounded-pill px-4 btn-sm fw-bold shadow-sm" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i> Ekspor Data
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4 mt-2">
                    <li><a class="dropdown-item rounded-3 py-2 fw-bold" href="{{ route('admin.absensi.pdf', $kelas) }}"><i class="fas fa-file-pdf text-danger me-2"></i> Format PDF</a></li>
                    <li><a class="dropdown-item rounded-3 py-2 fw-bold" href="{{ route('admin.absensi.excel', $kelas) }}"><i class="fas fa-file-excel text-success me-2"></i> Format Excel</a></li>
                </ul>
            </div>
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>

    @php
        $groupedAbsensi = $absensi->sortBy('tanggal')->groupBy('tanggal');
        $pertemuanKe = 1;
    @endphp

    @forelse($groupedAbsensi as $tanggal => $dataAbsensi)
    <div class="mb-5">
        {{-- JUDUL PERTEMUAN (TANPA BOX STATISTIK) --}}
        <div class="d-flex align-items-center mb-3">
            <div class="line-decorator me-3"></div>
            <h5 class="fw-800 text-dark mb-0">
                Pertemuan {{ $pertemuanKe++ }}
                <span class="text-muted fw-normal ms-2" style="font-size: 0.9rem;">— {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
            </h5>
        </div>

        {{-- TABEL MINIMALIS --}}
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="bg-faint">
                            <th class="ps-4 py-3 text-muted fw-800 x-small" style="width: 80px;">NO</th>
                            <th class="text-muted fw-800 x-small">NAMA LENGKAP MAHASISWA</th>
                            <th class="text-muted fw-800 x-small">NOMOR INDUK (NIM)</th>
                            <th class="pe-4 text-muted fw-800 x-small text-end">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataAbsensi as $index => $item)
                        <tr>
                            <td class="ps-4 text-muted fw-bold small">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="initial-avatar me-3">{{ substr($item->nama_mahasiswa, 0, 1) }}</div>
                                    <span class="fw-bold text-dark small" style="letter-spacing: 0.2px;">{{ strtoupper($item->nama_mahasiswa) }}</span>
                                </div>
                            </td>
                            <td>
                                <code class="text-primary fw-bold" style="font-size: 0.75rem;">{{ $item->nim_mahasiswa }}</code>
                            </td>
                            <td class="pe-4 text-end">
                                @php
                                    $st = strtolower($item->status);
                                    $badgeStyle = match($st) {
                                        'hadir' => 'status-hadir',
                                        'izin'  => 'status-izin',
                                        'sakit' => 'status-sakit',
                                        'alpa'  => 'status-alpa',
                                        default => 'status-default'
                                    };
                                @endphp
                                <span class="custom-badge {{ $badgeStyle }}">
                                    {{ strtoupper($st) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <img src="https://illustrations.popsy.co/gray/data-report.svg" alt="No Data" style="width: 200px; opacity: 0.5;">
        <h5 class="fw-800 text-muted mt-4">Belum Ada Data Absensi</h5>
    </div>
    @endforelse
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

    body { font-family: 'Inter', sans-serif; background-color: #fcfdfe; color: #334155; }
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.65rem; letter-spacing: 0.8px; }

    /* Decorative Line */
    .line-decorator { width: 4px; height: 24px; background: #0d6efd; border-radius: 10px; }

    /* Avatar Minimalis */
    .initial-avatar {
        width: 30px; height: 30px; background: #f1f5f9; color: #64748b;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px; font-weight: 800; font-size: 0.75rem; border: 1px solid #e2e8f0;
    }

    /* Table UI */
    .bg-faint { background-color: #f8fafc; }
    .table td { padding-top: 16px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9; }
    .table thead th { border: none; }
    .table tbody tr:hover { background-color: #fbfcfe; transition: 0.2s; }

    /* Custom Badge Minimalis */
    .custom-badge {
        font-size: 0.6rem; font-weight: 800; padding: 4px 12px; border-radius: 6px;
        display: inline-block; min-width: 70px; text-align: center;
    }
    .status-hadir { background: #dcfce7; color: #15803d; }
    .status-izin { background: #e0f2fe; color: #0369a1; }
    .status-sakit { background: #fef9c3; color: #a16207; }
    .status-alpa { background: #fee2e2; color: #b91c1c; }
    .status-default { background: #f1f5f9; color: #475569; }

    /* Utility */
    .bg-soft-primary { background-color: #eff6ff; }
    .shadow-sm { box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.04) !important; }
</style>
@endsection
