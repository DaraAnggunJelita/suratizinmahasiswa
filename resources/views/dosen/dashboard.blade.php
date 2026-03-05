@extends('layouts.app')

@section('title', 'Dosen Console')

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

    .main-container { padding: 1.5rem 2rem; }

    /* Stats Grid */
    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
    .stat-item {
        background: white; padding: 1.5rem; border-radius: 16px;
        border: 1px solid var(--border-color); display: flex; align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; margin-right: 15px;
        font-size: 1.25rem;
    }

    /* Layout Wrapper Baru untuk Jadwal & Pengumuman */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }

    .info-card {
        background: white; border-radius: 20px; border: 1px solid var(--border-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02); padding: 1.25rem;
    }

    .info-card-header { display: flex; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
    .info-card-header i { margin-right: 10px; color: var(--primary); }

    /* List Item untuk Jadwal & Pengumuman */
    .list-item-custom {
        display: flex; align-items: center; padding: 10px; border-radius: 12px;
        border: 1px solid transparent; transition: 0.2s; margin-bottom: 5px;
    }
    .list-item-custom:hover { background: #f0f4ff; border-color: #e0e7ff; }

    .time-badge {
        background: var(--primary); color: white; padding: 4px 10px;
        border-radius: 8px; font-weight: 700; font-size: 11px; margin-right: 12px;
    }

    .ann-badge { background: #fff9db; color: #fab005; padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 800; }

    /* Content Card (Tabel Verifikasi) */
    .content-card {
        background: white; border-radius: 20px; border: 1px solid var(--border-color);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden;
    }

    .table-header {
        padding: 1.5rem; border-bottom: 1px solid var(--border-color);
        display: flex; justify-content: space-between; align-items: center;
    }

    /* Table Design */
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead th {
        background: #ffffff; color: #94a3b8; font-weight: 700; font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; padding: 15px 1.5rem;
        border-bottom: 1px solid #f8fafc;
    }

    .table-custom tbody td {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #f8fafc;
        font-size: 14px; vertical-align: middle;
    }

    /* Badge & Button (Tetap Sama) */
    .badge-status { font-weight: 700; font-size: 11px; padding: 6px 14px; border-radius: 8px; text-transform: uppercase; }
    .status-ditolak { background: #fff5f5; color: #fa5252; }
    .status-disetujui { background: #ebfbee; color: #40c057; }
    .status-menunggu { background: #fff9db; color: #fab005; }

    .btn-verif-group { display: flex; gap: 10px; justify-content: flex-end; }
    .btn-action { width: 40px; height: 40px; border-radius: 10px; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; }
    .btn-approve { background-color: #2ecc71; color: white; }
    .btn-reject { background-color: #f1f5f9; color: #ff4757; }
    .btn-action:hover { transform: scale(1.1); }

    .search-container { position: relative; }
    .search-container i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-input { padding-left: 35px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 13px; width: 250px; }
</style>

<div class="main-container">
    <h2 class="fw-bold mb-4">Dashboard Dosen</h2>

    {{-- Statistik Row (Tetap di Atas) --}}
    <div class="stat-grid">
        <div class="stat-item">
            <div class="stat-icon" style="background: #eef2ff; color: #4361ee;"><i class="fas fa-folder"></i></div>
            <div>
                <div class="text-muted fw-bold" style="font-size: 11px;">TOTAL IZIN</div>
                <div class="fw-bold fs-4">{{ $suratIzin->count() }}</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" style="background: #fff9db; color: #fab005;"><i class="fas fa-clock"></i></div>
            <div>
                <div class="text-muted fw-bold" style="font-size: 11px;">MENUNGGU</div>
                <div class="fw-bold fs-4">{{ $suratIzin->where('status', 'pending')->count() }}</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" style="background: #ebfbee; color: #40c057;"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="text-muted fw-bold" style="font-size: 11px;">DISETUJUI</div>
                <div class="fw-bold fs-4">{{ $suratIzin->where('status', 'disetujui')->count() }}</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" style="background: #fff5f5; color: #fa5252;"><i class="fas fa-times-circle"></i></div>
            <div>
                <div class="text-muted fw-bold" style="font-size: 11px;">DITOLAK</div>
                <div class="fw-bold fs-4">{{ $suratIzin->where('status', 'ditolak')->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Info Grid: Jadwal & Pengumuman (Ditambahkan di sini agar rapi) --}}
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-calendar-alt"></i>
                <h6 class="fw-bold mb-0">Jadwal Mengajar Hari Ini</h6>
            </div>
            <div class="info-body">
                @forelse($jadwals as $j)
                <div class="list-item-custom">
                    <span class="time-badge">{{ $j->jam_mulai }}</span>
                    <div>
                        <div class="fw-bold small">{{ $j->mata_kuliah }}</div>
                        <div class="text-muted" style="font-size: 11px;">Kelas: {{ $j->kelas }} | Ruangan: {{ $j->ruangan }}</div>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted small py-3">Tidak ada jadwal mengajar.</p>
                @endforelse
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-bullhorn" style="color: #fab005;"></i>
                <h6 class="fw-bold mb-0">Pengumuman Terbaru</h6>
            </div>
            <div class="info-body">
                @forelse($pengumumans as $p)
                <div class="list-item-custom">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small">{{ Str::limit($p->judul, 30) }}</span>
                            <span class="ann-badge">INFO</span>
                        </div>
                        <div class="text-muted" style="font-size: 11px;">{{ $p->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted small py-3">Belum ada pengumuman.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tabel Utama (Tetap Seperti Semula) --}}
    <div class="content-card">
        <div class="table-header">
            <h6 class="fw-bold mb-0">Data Pengajuan Mahasiswa</h6>
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari Nama atau NIM...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>NIM & Kelas</th>
                        <th>Jenis Izin</th>
                        <th>Status</th>
                        <th>Dokumen</th>
                        <th class="text-end pe-4">Verifikasi</th>
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    @forelse($suratIzin as $si)
                    <tr>
                        <td><strong class="searchable-name">{{ $si->user->name }}</strong></td>
                        <td>
                            <span class="fw-bold text-dark">{{ $si->user->nim_nip }}</span>
                            <span class="text-muted">({{ $si->user->kelas }})</span>
                        </td>
                        <td><span class="text-primary fw-bold" style="font-size: 12px;">{{ strtoupper($si->jenis_izin) }}</span></td>
                        <td>
                            @if($si->status == 'pending')
                                <span class="badge-status status-menunggu">MENUNGGU</span>
                            @elseif($si->status == 'disetujui')
                                <span class="badge-status status-disetujui">DISETUJUI</span>
                            @else
                                <span class="badge-status status-ditolak">DITOLAK</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('dosen.suratDetail', $si->id) }}" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 13px;">
                                <i class="fas fa-external-link-alt me-1"></i> Lihat
                            </a>
                        </td>
                        <td class="pe-4">
                            <div class="btn-verif-group">
                                <form action="{{ route('dosen.setujuiSurat', $si->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('dosen.tolakSurat', $si->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data izin masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let q = this.value.toLowerCase();
        let rows = document.querySelectorAll('#dataTableBody tr');
        rows.forEach(row => {
            let name = row.querySelector('.searchable-name').innerText.toLowerCase();
            row.style.display = name.includes(q) ? '' : 'none';
        });
    });
</script>

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <div class="alert alert-dark shadow-lg border-0 text-white" style="border-radius: 12px;">
        <i class="fas fa-check-circle text-success me-2"></i> {{ session('success') }}
    </div>
</div>
@endif
@endsection
