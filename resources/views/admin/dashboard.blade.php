@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Pusat Kendali Admin</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item text-muted">Dashboard</li>
                    <li class="breadcrumb-item active fw-medium" aria-current="page text-primary">Manajemen Izin</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="btn-group shadow-sm">
                <button class="btn btn-white border-0 py-2"><i class="fas fa-calendar-day me-2"></i>{{ date('d M Y') }}</button>
                <button class="btn btn-primary px-4 py-2"><i class="fas fa-sync-alt me-2"></i>Refresh Data</button>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        @php
            $stats = [
                ['label' => 'Total Pengajuan', 'val' => $totalSurat ?? 0, 'icon' => 'fa-file-invoice', 'color' => '#6366f1', 'bg' => '#eef2ff'],
                ['label' => 'Perlu Review', 'val' => $menunggu ?? 0, 'icon' => 'fa-spinner', 'color' => '#f59e0b', 'bg' => '#fffbeb'],
                ['label' => 'Disetujui', 'val' => $disetujui ?? 0, 'icon' => 'fa-check-circle', 'color' => '#10b981', 'bg' => '#ecfdf5'],
                ['label' => 'Ditolak', 'val' => $ditolak ?? 0, 'icon' => 'fa-times-octagon', 'color' => '#ef4444', 'bg' => '#fef2f2']
            ];
        @endphp

        @foreach($stats as $s)
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden card-stat">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted fw-semibold small mb-1 uppercase tracking-wider">{{ $s['label'] }}</p>
                            <h2 class="fw-bold mb-0" style="color: #1e293b;">{{ $s['val'] }}</h2>
                        </div>
                        <div class="stat-icon-new" style="background: {{ $s['bg'] }}; color: {{ $s['color'] }};">
                            <i class="fas {{ $s['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-success small fw-medium"><i class="fas fa-arrow-up me-1"></i>Data Terbaru</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-4 px-4">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="fw-bold text-dark mb-0">Daftar Surat Izin Mahasiswa</h5>
                </div>
                <div class="col-md-8 text-md-end mt-2 mt-md-0">
                    <div class="d-inline-flex gap-2">
                        <div class="input-group input-group-sm rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-0 px-2" placeholder="Cari Mahasiswa..." style="width: 200px;">
                        </div>
                        <button class="btn btn-sm btn-outline-secondary rounded-3"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-modern-table">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Mahasiswa</th>
                        <th>Alasan & Durasi</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi Strategis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratIzins as $index => $surat)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    <span class="avatar-initial">{{ substr($surat->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $surat->user->name }}</h6>
                                    <p class="mb-0 text-muted x-small">NIM: {{ $surat->user->nim ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-medium text-dark">{{ ucfirst($surat->jenis_izin) }}</span>
                                <span class="text-muted x-small mt-1">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->format('d M Y') }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($surat->bukti_file)
                                <a href="{{ asset('storage/'.$surat->bukti_file) }}" target="_blank" class="btn-view-doc">
                                    <i class="fas fa-paperclip me-1"></i> Dokumen
                                </a>
                            @else
                                <span class="text-muted small">Tanpa Bukti</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'menunggu' => ['class' => 'st-warning', 'icon' => 'fa-clock', 'label' => 'Pending'],
                                    'disetujui' => ['class' => 'st-success', 'icon' => 'fa-check', 'label' => 'Approved'],
                                    'ditolak' => ['class' => 'st-danger', 'icon' => 'fa-times', 'label' => 'Rejected']
                                ];
                                $st = $statusMap[$surat->status] ?? $statusMap['menunggu'];
                            @endphp
                            <span class="modern-badge {{ $st['class'] }}">
                                <i class="fas {{ $st['icon'] }} me-1"></i> {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            @if($surat->status == 'menunggu')
                            <div class="d-inline-flex gap-2">
                                <form action="{{ route('admin.surat_izin.verifikasi', $surat->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="disetujui">
                                    <button class="btn btn-action btn-acc" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.surat_izin.verifikasi', $surat->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button class="btn btn-action btn-rej" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                                <button class="btn btn-sm btn-light rounded-pill px-3 text-muted disabled">Ulasan Selesai</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                            <p class="text-muted">Belum ada pengajuan izin masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary: #4f46e5;
        --secondary: #64748b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
    }

    body { background-color: #f1f5f9; color: #334155; }
    .x-small { font-size: 0.75rem; }

    /* Stat Icon Design */
    .stat-icon-new {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }

    .card-stat { transition: all 0.3s cubic-bezier(.25,.8,.25,1); border: 1px solid rgba(0,0,0,0.02) !important; }
    .card-stat:hover { transform: translateY(-4px); shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }

    /* Modern Table Design */
    .custom-modern-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.1em;
        padding-top: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .custom-modern-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: 0.2s; }
    .custom-modern-table tbody tr:hover { background-color: #f9fafb; }

    /* Avatar Initial */
    .avatar-circle {
        width: 42px; height: 42px;
        background: linear-gradient(135deg, #4f46e5, #818cf8);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: bold;
    }

    /* Badge & Action Buttons */
    .modern-badge {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }
    .st-warning { background: #fffbeb; color: #b45309; }
    .st-success { background: #ecfdf5; color: #047857; }
    .st-danger { background: #fef2f2; color: #b91c1c; }

    .btn-view-doc {
        text-decoration: none;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.8rem;
        background: #eef2ff;
        padding: 4px 10px;
        border-radius: 6px;
        transition: 0.2s;
    }
    .btn-view-doc:hover { background: var(--primary); color: white; }

    .btn-action {
        width: 34px; height: 34px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        border: none; transition: 0.2s;
    }
    .btn-acc { background-color: #ecfdf5; color: #10b981; }
    .btn-rej { background-color: #fef2f2; color: #ef4444; }
    .btn-acc:hover { background-color: #10b981; color: white; transform: scale(1.1); }
    .btn-rej:hover { background-color: #ef4444; color: white; transform: scale(1.1); }
</style>
@endpush
@endsection
