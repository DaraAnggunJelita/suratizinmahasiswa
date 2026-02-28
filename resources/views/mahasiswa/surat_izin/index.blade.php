@extends('layouts.app')

@section('title', 'Daftar Surat Izin')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Riwayat Surat Izin</h2>
        <p class="text-muted small mb-0">Kelola dan pantau status pengajuan izin Anda di sini.</p>
    </div>
    <a href="{{ url('/mahasiswa/surat-izin/create') }}" class="btn btn-navy-grad rounded-pill px-4 shadow-sm">
        <i class="fas fa-plus-circle me-2"></i> Buat Surat Izin
    </a>
</div>

@if(session('success'))
<div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
    <i class="fas fa-check-circle me-2"></i>
    <div class="fw-bold small">{{ session('success') }}</div>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="py-3 ps-4 text-muted small fw-bold" style="width: 60px;">NO</th>
                        <th class="text-muted small fw-bold">JENIS IZIN</th>
                        <th class="text-muted small fw-bold">TANGGAL PELAKSANAAN</th>
                        <th class="text-muted small fw-bold text-center">STATUS</th>
                        <th class="text-muted small fw-bold text-center">BUKTI</th>
                        <th class="text-muted small fw-bold text-center pe-4" style="width: 160px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratIzins as $key => $surat)
                    <tr class="border-bottom">
                        <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ ucfirst($surat->jenis_izin) }}</div>
                            <div class="text-muted x-small">ID: #{{ str_pad($surat->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td>
                            <div class="small fw-medium text-secondary">
                                <i class="far fa-calendar-alt me-1 text-primary"></i>
                                {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->format('d M Y') }}
                                <span class="mx-1 text-muted">→</span>
                                {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $status = strtolower($surat->status);
                                if(in_array($status, ['approved', 'disetujui'])) {
                                    $badge = 'bg-success-subtle text-success border-success';
                                    $label = 'DISETUJUI';
                                } elseif(in_array($status, ['pending', 'menunggu'])) {
                                    $badge = 'bg-warning-subtle text-warning border-warning';
                                    $label = 'MENUNGGU';
                                } else {
                                    $badge = 'bg-danger-subtle text-danger border-danger';
                                    $label = 'DITOLAK';
                                }
                            @endphp
                            <span class="badge {{ $badge }} border px-3 py-2 rounded-pill fw-bold" style="font-size: 0.65rem;">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($surat->bukti_file)
                                <a href="{{ asset('storage/'.$surat->bukti_file) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem;">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="pe-4 text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ url('/mahasiswa/surat-izin/'.$surat->id.'/edit') }}" class="btn btn-sm btn-light border shadow-sm rounded-3 px-2" title="Edit">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                <form action="{{ url('/mahasiswa/surat-izin/'.$surat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light border shadow-sm rounded-3 px-2" onclick="return confirm('Hapus surat izin ini?')" title="Hapus">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-envelope-open fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-0">Anda belum pernah mengajukan surat izin.</p>
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
        color: white; border: none; transition: all 0.3s;
    }
    .btn-navy-grad:hover {
        background: #415A77; color: white; transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 27, 42, 0.2);
    }

    /* Subtle Badge Styling */
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }

    .x-small { font-size: 0.7rem; }
    .table-hover tbody tr:hover { background-color: #fbfcfe; }

    /* Action Buttons Hover */
    .btn-light:hover { background-color: #fff; transform: scale(1.1); }
</style>
@endsection
