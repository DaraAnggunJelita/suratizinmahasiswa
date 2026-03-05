@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- HEADER SECTION --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-1">Welcome Back, {{ Auth::user()->name }}!</h2>
            <p class="text-muted small mb-0">
                <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                <span class="mx-2">|</span>
                <i class="far fa-clock me-1"></i> <span id="clock"></span>
            </p>
        </div>
        <a href="{{ url('/mahasiswa/surat-izin/create') }}" class="btn btn-navy-grad rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Buat Surat Izin
        </a>
    </div>

    {{-- ================= FITUR PENGUMUMAN (BARU) ================= --}}
    @php
        $pengumumans = \App\Models\Pengumuman::whereIn('kelas', [Auth::user()->kelas, 'Semua'])
                        ->latest()
                        ->take(3)
                        ->get();
    @endphp

    @if($pengumumans->count() > 0)
    <div class="mb-4">
        <div class="d-flex align-items-center mb-3">
            <h6 class="fw-bold text-dark mb-0 text-uppercase small"><i class="fas fa-bullhorn me-2 text-primary"></i> Pengumuman Terbaru</h6>
            <div class="flex-grow-1 ms-3"><hr class="my-0 opacity-25"></div>
        </div>

        <div class="row g-3">
            @foreach($pengumumans as $info)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden border-start border-primary border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary-subtle text-primary x-small rounded-pill px-2">
                                {{ $info->kelas == 'Semua' ? 'UMUM' : $info->kelas }}
                            </span>
                            <small class="text-muted" style="font-size: 0.65rem;">{{ $info->created_at->diffForHumans() }}</small>
                        </div>
                        <h6 class="fw-bold text-dark small mb-1">{{ $info->judul }}</h6>
                        <p class="text-muted mb-2 x-small text-truncate-2">{{ $info->pesan }}</p>
                        <div class="d-flex align-items-center mt-auto pt-2 border-top border-light">
                            <i class="fas fa-user-circle text-secondary me-1" style="font-size: 0.8rem;"></i>
                            <span class="text-muted fw-bold" style="font-size: 0.65rem;">{{ $info->user->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    {{-- ========================================================== --}}

    {{-- SMART ATTENDANCE ALERT --}}
    @php
        $persentase = 85; // Idealnya ini diambil dari data kehadiran di database
    @endphp
    @if($persentase < 80)
    <div class="alert border-0 shadow-sm rounded-4 d-flex align-items-center p-3 mb-4"
         style="background: linear-gradient(90deg, #fff5f5 0%, #ffffff 100%); border-left: 5px solid #dc3545 !important;">
        <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3">
            <i class="fas fa-exclamation-triangle text-danger"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold text-danger">Peringatan Kehadiran!</h6>
            <p class="mb-0 small text-muted">Kehadiran Anda <strong>{{ $persentase }}%</strong>. Segera tingkatkan kehadiran agar dapat mengikuti UAS.</p>
        </div>
        <button type="button" class="btn-close small" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- SISI KIRI: Statistik & Tabel --}}
        <div class="col-lg-8">

            {{-- SUMMARY CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-file-alt text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="small text-muted mb-0">Total Izin</h6>
                                <h4 class="fw-bold mb-0">{{ $suratIzins->count() }}</h4>
                                <small class="text-muted x-small">pengajuan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="small text-muted mb-0">Disetujui</h6>
                                <h4 class="fw-bold mb-0 text-success">
                                    {{ $suratIzins->filter(fn($item) => strtolower($item->status) == 'disetujui')->count() }}
                                </h4>
                                <small class="text-muted x-small">berhasil</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                            <div>
                                <h6 class="small text-muted mb-0">Menunggu</h6>
                                <h4 class="fw-bold mb-0 text-warning">
                                    {{ $suratIzins->filter(fn($item) => strtolower($item->status) == 'menunggu')->count() }}
                                </h4>
                                <small class="text-muted x-small">proses</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL RIWAYAT TERAKHIR --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h6 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i>Daftar Surat Izin Terakhir</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light-subtle">
                                <tr class="small text-muted fw-bold">
                                    <th class="ps-4 py-3">JENIS</th>
                                    <th>TANGGAL</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suratIzins->take(5) as $surat)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ ucfirst($surat->jenis_izin) }}</div>
                                        <div class="x-small text-muted">#{{ str_pad($surat->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $st = strtolower($surat->status);
                                            $cls = match($st) {
                                                'disetujui' => 'success',
                                                'menunggu' => 'warning',
                                                'ditolak' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $cls }}-subtle text-{{ $cls }} rounded-pill px-3 py-2 fw-bold x-small border border-{{ $cls }} border-opacity-10">
                                            {{ strtoupper($surat->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ url('/mahasiswa/surat-izin/'.$surat->id.'/edit') }}" class="btn btn-sm btn-light border p-1 px-2 rounded-2 shadow-none"><i class="fas fa-edit text-warning"></i></a>
                                            @if($surat->bukti_file)
                                                <a href="{{ asset('storage/'.$surat->bukti_file) }}" target="_blank" class="btn btn-sm btn-light border p-1 px-2 rounded-2 shadow-none"><i class="fas fa-file-pdf text-primary"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted small">Belum ada riwayat pengajuan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: Gauge Kehadiran & Fitur Jadwal --}}
        <div class="col-lg-4">

            {{-- ATTENDANCE GAUGE --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h6 class="fw-bold text-dark mb-4 small text-uppercase">RIWAYAT KEHADIRAN</h6>
                <div class="text-center mb-4 position-relative">
                    <div class="attendance-gauge shadow-sm mx-auto d-flex align-items-center justify-content-center"
                         style="background: conic-gradient(#0D1B2A {{ $persentase }}%, #e2e8f0 0);">
                        <div class="gauge-inner bg-white d-flex align-items-center justify-content-center shadow-inner">
                            <h3 class="fw-bold mb-0 text-navy">{{ $persentase }}%</h3>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge {{ $persentase < 80 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} rounded-pill px-3 py-2 fw-bold x-small">
                            {{ $persentase < 80 ? 'KEHADIRAN MINIM' : 'KEHADIRAN AMAN' }}
                        </span>
                    </div>
                </div>
                <div class="row g-2 text-center x-small text-muted fw-bold border-top pt-3">
                    <div class="col-3"><i class="fas fa-circle text-success me-1"></i>Hadir</div>
                    <div class="col-3"><i class="fas fa-circle text-warning me-1"></i>Izin</div>
                    <div class="col-3"><i class="fas fa-circle text-danger me-1"></i>Sakit</div>
                    <div class="col-3"><i class="fas fa-circle text-dark me-1"></i>Alpa</div>
                </div>
            </div>

            {{-- JADWAL KULIAH HARI INI --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase">Jadwal Hari Ini</h6>
                    <span class="badge bg-navy text-white rounded-pill x-small px-3">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</span>
                </div>

                <div class="schedule-list mt-3">
                    @forelse($jadwalHariIni as $j)
                        <div class="d-flex align-items-center mb-3 p-2 rounded-3 hover-effect" style="background-color: #f8fafc;">
                            <div class="text-center me-3 px-2 border-end border-2" style="min-width: 65px;">
                                <span class="d-block fw-bold small text-navy">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</span>
                                <span class="text-muted x-small">WIB</span>
                            </div>
                            <div class="flex-grow-1 ps-1">
                                <h6 class="mb-0 small fw-bold text-dark">{{ $j->mata_kuliah }}</h6>
                                <p class="x-small text-muted mb-0"><i class="fas fa-door-open me-1"></i>{{ $j->ruangan }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-day text-muted fa-2x mb-2"></i>
                            <p class="text-muted small mb-0">Tidak ada jadwal kuliah hari ini.</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('jadwal.index') }}" class="btn btn-light w-100 rounded-pill x-small fw-bold text-muted border mt-2 shadow-none">Lihat Semua Jadwal</a>
            </div>

        </div>
    </div>
</div>

<style>
    /* CUSTOM STYLE DASHBOARD */
    .bg-navy { background-color: #0D1B2A !important; }
    .text-navy { color: #0D1B2A !important; }
    .x-small { font-size: 0.7rem; }

    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white; border: none; transition: 0.3s;
    }
    .btn-navy-grad:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 27, 42, 0.2); color: #fff; }

    /* GAUGE CHART */
    .attendance-gauge {
        width: 140px; height: 140px; border-radius: 50%;
        transition: 0.5s;
    }
    .gauge-inner {
        width: 110px; height: 110px; border-radius: 50%;
    }

    /* INTERACTIONS */
    .hover-effect { transition: 0.3s ease; cursor: pointer; }
    .hover-effect:hover { background-color: #f1f5f9 !important; transform: translateX(5px); }

    .bg-light-subtle { background-color: #fcfdfe; }

    /* Status Badges */
    .bg-success-subtle { background-color: #f0fdf4 !important; color: #15803d !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; color: #b45309 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; color: #b91c1c !important; }

    .bg-primary-subtle { background-color: #eef2ff !important; color: #4338ca !important; }
</style>

<script>
    function updateClock() {
        let now = new Date();
        let options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', options) + " WIB";
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection
