@extends('layouts.app')

@section('title', 'Admin Console')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --primary: #4361ee;
        --bg-body: #f8fafc;
        --border-color: #f1f5f9;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        color: #334155;
    }

    .main-container { padding: 1.25rem 2rem; }

    /* Header & Ticker */
    .header-box { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .page-title { font-weight: 800; font-size: 1.5rem; color: #0f172a; margin: 0; }

    .news-pill {
        background: white; border: 1px solid #e2e8f0; border-radius: 12px;
        padding: 6px 15px; display: flex; align-items: center; max-width: 450px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    /* Stats Grid */
    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-item {
        background: white; padding: 1rem; border-radius: 12px;
        border: 1px solid var(--border-color); display: flex; align-items: center;
    }
    .stat-icon {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; margin-right: 12px;
    }

    /* Table Design */
    .content-card {
        background: white; border-radius: 16px; border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden;
    }

    .table-header {
        padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color);
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 10px;
    }

    .table-compact { width: 100%; border-collapse: collapse; }
    .table-compact thead th {
        background: #f8fafc; color: #64748b; font-weight: 700; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.05em; padding: 12px 1.5rem;
    }

    .table-compact tbody td {
        padding: 12px 1.5rem; border-bottom: 1px solid #f8fafc;
        font-size: 13px; vertical-align: middle;
    }

    .table-compact tbody tr:hover { background-color: #fbfcfd; }

    /* Status Pills */
    .status-pill {
        font-weight: 700; font-size: 10px; padding: 4px 10px; border-radius: 6px;
        display: inline-flex; align-items: center;
    }
    .status-kuning { background: #fffbeb; color: #b45309; }
    .status-hijau { background: #f0fdf4; color: #15803d; }
    .status-merah { background: #fef2f2; color: #b91c1c; }

    .btn-action-sm {
        width: 30px; height: 30px; border-radius: 6px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        transition: 0.2s; font-size: 12px;
    }
    .btn-acc { background: #22c55e; color: white; }
    .btn-rej { background: #f1f5f9; color: #ef4444; }

    .btn-view {
        text-decoration: none; font-weight: 700; color: var(--primary); font-size: 12px;
    }

    /* Highlight matching text */
    .bg-yellow-100 { background-color: #fef9c3; }
</style>

<div class="main-container">

    {{-- Header --}}
    <div class="header-box">
        <h1 class="page-title">Dashboard Admin</h1>

        <div class="news-pill d-none d-lg-flex">
            <span class="badge bg-primary me-2" style="font-size: 9px; border-radius: 4px;">PENGUMUMAN</span>
            <marquee class="small text-muted fw-bold" scrollamount="4">
                @if($pengumumans->count() > 0)
                    {{ $pengumumans->first()->judul }}: {{ $pengumumans->first()->pesan }}
                @else
                    Selamat datang di sistem manajemen perizinan pusat.
                @endif
            </marquee>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="stat-grid">
        @php
            $stats = [
                ['label' => 'Total Izin', 'val' => $totalSurat, 'icon' => 'fa-folder', 'bg' => '#eef2ff', 'c' => '#4361ee'],
                ['label' => 'Menunggu', 'val' => $menunggu, 'icon' => 'fa-clock', 'bg' => '#fffbeb', 'c' => '#f59e0b'],
                ['label' => 'Disetujui', 'val' => $disetujui, 'icon' => 'fa-check', 'bg' => '#f0fdf4', 'c' => '#22c55e'],
                ['label' => 'Ditolak', 'val' => $ditolak, 'icon' => 'fa-times', 'bg' => '#fef2f2', 'c' => '#ef4444'],
            ];
        @endphp
        @foreach($stats as $s)
        <div class="stat-item">
            <div class="stat-icon" style="background: {{ $s['bg'] }}; color: {{ $s['c'] }}">
                <i class="fas {{ $s['icon'] }} small"></i>
            </div>
            <div>
                <div class="text-muted fw-bold" style="font-size: 10px; text-transform: uppercase;">{{ $s['label'] }}</div>
                <div class="fw-800 fs-5 mb-0" style="line-height: 1;">{{ $s['val'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabel Utama --}}
    <div class="content-card">
        <div class="table-header">
            <h6 class="fw-800 mb-0" style="font-size: 14px;">Data Pengajuan Mahasiswa</h6>
            <div class="d-flex gap-2">
                {{-- Input Pencarian --}}
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted" style="font-size: 10px;"></i>
                    <input type="text" id="searchInput" class="form-control form-control-sm ps-4" placeholder="Cari Nama atau NIM..." style="width: 200px; font-size: 12px; border-radius: 8px;">
                </div>

                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm fw-bold px-3" style="font-size: 11px; border-radius: 8px;">
                    <i class="fas fa-users-cog me-1"></i> User
                </a>
                <a href="{{ route('pengumuman.index') }}" class="btn btn-dark btn-sm fw-bold px-3" style="font-size: 11px; border-radius: 8px;">
                    <i class="fas fa-bullhorn me-1"></i> Pengumuman
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-compact">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>NIM & Kelas</th>
                        <th>Jenis Izin</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-end pe-4">Verifikasi</th>
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    @forelse($suratIzins as $surat)
                    <tr>
                        <td class="fw-bold text-dark searchable-name" style="font-size: 14px;">{{ $surat->user->name }}</td>
                        <td class="searchable-nim">
                            <span class="fw-bold">{{ $surat->user->nim_nip }}</span>
                            <span class="text-muted ms-1">({{ $surat->user->kelas }})</span>
                        </td>
                        <td><span class="fw-800 text-primary" style="font-size: 11px;">{{ strtoupper($surat->jenis_izin) }}</span></td>
                        <td class="text-muted fw-medium">
                            {{ $surat->tanggal_mulai }} <i class="fas fa-arrow-right mx-1" style="font-size: 9px;"></i> {{ $surat->tanggal_selesai }}
                        </td>
                        <td>
                            @if($surat->status == 'menunggu')
                                <span class="status-pill status-kuning">MENUNGGU</span>
                            @elseif($surat->status == 'disetujui')
                                <span class="status-pill status-hijau">DISETUJUI</span>
                            @else
                                <span class="status-pill status-merah">DITOLAK</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.surat_izin.show', $surat->id) }}" class="btn-view">
                                <i class="fas fa-external-link-alt me-1"></i> Lihat
                            </a>
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.surat_izin.verifikasi', $surat->id) }}" method="POST" class="d-inline-flex gap-2">
                                @csrf @method('PUT')
                                <button type="submit" name="status" value="disetujui" class="btn-action-sm btn-acc" title="Setujui"><i class="fas fa-check"></i></button>
                                <button type="submit" name="status" value="ditolak" class="btn-action-sm btn-rej" title="Tolak"><i class="fas fa-times"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow"><td colspan="7" class="text-center py-5 text-muted small fw-bold">Belum ada pengajuan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script Pencarian --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('dataTableBody');
    const rows = tableBody.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase();
        let matchFound = false;

        // Mulai looping dari baris data
        for (let i = 0; i < rows.length; i++) {
            // Lewati jika ini adalah baris pesan "Data tidak ditemukan"
            if (rows[i].id === 'noResultsRow' || rows[i].id === 'emptyRow') continue;

            const nameElement = rows[i].querySelector('.searchable-name');
            const nimElement = rows[i].querySelector('.searchable-nim');

            if (nameElement && nimElement) {
                const nameText = nameElement.textContent.toLowerCase();
                const nimText = nimElement.textContent.toLowerCase();

                if (nameText.includes(query) || nimText.includes(query)) {
                    rows[i].style.display = "";
                    matchFound = true;
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

        // Tampilkan pesan jika hasil nol
        let noResultsRow = document.getElementById('noResultsRow');
        if (!matchFound && query !== "") {
            if (!noResultsRow) {
                const newRow = tableBody.insertRow();
                newRow.id = 'noResultsRow';
                newRow.innerHTML = `<td colspan="7" class="text-center py-5 text-muted fw-bold">Pencarian untuk "${searchInput.value}" tidak ditemukan.</td>`;
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    });
});
</script>

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div class="bg-dark text-white rounded-3 px-3 py-2 small shadow-lg animate__animated animate__fadeInUp">
        <i class="fas fa-check-circle text-success me-2"></i> {{ session('success') }}
    </div>
</div>
@endif

@endsection
