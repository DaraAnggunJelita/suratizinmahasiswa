@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="container-fluid px-4 mt-4">
    {{-- Header Selamat Datang --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold" style="color: #1B263B;">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-muted mb-0">Tinjau dan kelola surat izin mahasiswa Anda dengan cepat.</p>
        </div>
        <div class="bg-white p-2 px-3 rounded-3 shadow-sm border">
            <i class="far fa-calendar-alt me-2 text-primary"></i>
            <span class="small fw-bold text-dark">{{ date('d M Y') }}</span>
        </div>
    </div>

    {{-- Card Utama --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold" style="color:#1B263B;">
                <i class="fas fa-list me-2"></i>Daftar Surat Izin Mahasiswa
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #F8F9FA; border-top: 1px solid #eee;">
                        <tr class="text-muted small">
                            <th class="text-center py-3">No</th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Kelas</th>
                            <th>Jenis Izin</th>
                            <th>Masa Izin</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Bukti</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratIzin as $key => $surat)
                            <tr class="border-bottom">
                                <td class="text-center py-3 text-muted small">{{ $key + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $surat->user->name ?? '-' }}</td>
                                <td class="text-secondary small">{{ $surat->user->nim_nip ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark border fw-medium">{{ $surat->user->kelas ?? '-' }}</span></td>
                                <td><span class="text-primary fw-600">{{ ucfirst($surat->jenis_izin) }}</span></td>
                                <td class="small">
                                    {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->format('d/m/y') }} s/d
                                    {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->format('d/m/y') }}
                                </td>
                                <td class="text-center">
                                    @if(strtolower($surat->status) == 'disetujui')
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success border-opacity-25 px-3">Disetujui</span>
                                    @elseif(strtolower($surat->status) == 'ditolak')
                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger border-opacity-25 px-3">Ditolak</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning border-opacity-25 px-3">Menunggu</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($surat->bukti_file)
                                        <a href="{{ asset('storage/' . $surat->bukti_file) }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary py-0 px-2 rounded-2" style="font-size: 0.75rem;">
                                            <i class="fas fa-file-pdf me-1"></i>File
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Detail --}}
                                        <a href="{{ route('dosen.surat_detail', $surat->id) }}"
                                           class="btn btn-sm btn-light border shadow-none p-1 px-2" title="Detail">
                                            <i class="fas fa-eye text-primary small"></i>
                                        </a>

                                        {{-- Verifikasi Setuju --}}
                                        <form action="{{ route('dosen.surat_verifikasi', $surat->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="btn btn-sm btn-success p-1 px-2" title="Setujui">
                                                <i class="fas fa-check small"></i>
                                            </button>
                                        </form>

                                        {{-- Verifikasi Tolak --}}
                                        <form action="{{ route('dosen.surat_verifikasi', $surat->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-sm btn-danger p-1 px-2" title="Tolak">
                                                <i class="fas fa-times small"></i>
                                            </button>
                                        </form>

                                        {{-- Hapus --}}
                                        <form action="{{ route('dosen.surat_hapus', $surat->id) }}" method="POST" onsubmit="return confirm('Hapus surat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary p-1 px-2" title="Hapus">
                                                <i class="fas fa-trash small"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted fst-italic">Belum ada pengajuan surat izin</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Status Badge Modern */
    .bg-success-subtle { background-color: #e8f5e9 !important; }
    .bg-danger-subtle { background-color: #ffebee !important; }
    .bg-warning-subtle { background-color: #fff8e1 !important; }

    /* Hover Row */
    .table-hover tbody tr:hover {
        background-color: #fcfdfe !important;
    }

    .fw-600 { font-weight: 600; }
    .small { font-size: 0.85rem; }

    /* Button Custom */
    .btn-sm {
        transition: all 0.2s;
    }
    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection
