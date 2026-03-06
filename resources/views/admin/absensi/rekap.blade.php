@extends('layouts.app')

@section('title', 'Rekap Absensi Kelas ' . $kelas)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Rekap Absensi Kelas {{ $kelas }}</h4>
            <p class="text-muted small mb-0">Memantau kehadiran mahasiswa secara real-time</p>
        </div>
        <a href="{{ route('admin.absensi.index') }}" class="btn btn-light border rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <small class="text-muted d-block">Total Data</small>
                <span class="h4 fw-bold mb-0">{{ $absensi->count() }}</span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm animate__animated animate__fadeIn">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold">Tanggal</th>
                            <th class="py-3 text-uppercase small fw-bold">Mahasiswa</th>
                            <th class="py-3 text-uppercase small fw-bold">NIM</th>
                            <th class="py-3 text-uppercase small fw-bold">Status</th>
                            <th class="py-3 text-uppercase small fw-bold pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $item)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr($item->nama_mahasiswa, 0, 1) }}
                                    </div>
                                    <span class="fw-600">{{ $item->nama_mahasiswa }}</span>
                                </div>
                            </td>
                            <td><code class="text-dark">{{ $item->nim_mahasiswa }}</code></td>
                            <td>
                                @php
                                    $badge = [
                                        'hadir' => 'bg-success',
                                        'izin'  => 'bg-info text-dark',
                                        'sakit' => 'bg-warning text-dark',
                                        'alpa'  => 'bg-danger'
                                    ];
                                @endphp
                                <span class="badge {{ $badge[strtolower($item->status)] ?? 'bg-secondary' }} rounded-pill px-3">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <button class="btn btn-sm btn-light border rounded-3" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                <p class="text-muted">Belum ada data absensi untuk kelas ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
