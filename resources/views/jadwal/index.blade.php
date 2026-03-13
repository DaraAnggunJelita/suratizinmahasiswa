@extends('layouts.app')

@section('title', 'Jadwal ' . ($prodiAktif ?? ''))

@section('content')
<div class="container animate__animated animate__fadeIn py-3">
    {{-- 1. ALERT NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-custom alert-success border-0 shadow-sm d-flex align-items-center mb-4">
        <i class="fas fa-check-circle me-3 fs-5 text-success"></i>
        <div class="small fw-600">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- 2. HEADER --}}
    <div class="row align-items-end mb-4 g-3">
        <div class="col-md">
            <h3 class="fw-800 text-dark mb-1 tracking-tight">Jadwal Perkuliahan</h3>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-soft-primary text-primary border border-primary-subtle px-3 py-2 rounded-pill x-small fw-800">
                    <i class="fas fa-university me-1"></i> {{ strtoupper($prodiAktif) }}
                </span>
                <span class="text-muted small fw-500">
                    <i class="fas fa-users me-1"></i> Kelas {{ $kelasAktif }}
                </span>
            </div>
        </div>
        @if(Auth::user()->role == 'admin')
        <div class="col-md-auto">
            <button class="btn btn-dark rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                <i class="fas fa-plus-circle"></i> Tambah Jadwal
            </button>
        </div>
        @endif
    </div>

    {{-- 3. NAVIGASI PRODI & KELAS DINAMIS --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden filter-bar">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-lg-3 col-md-4">
                    <form action="{{ route('jadwal.index') }}" method="GET" id="formFilter">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-0 text-muted fw-bold x-small">PRODI</span>
                            <select name="prodi" id="prodiSelect" class="form-select border-0 bg-light rounded-pill fw-700 text-dark shadow-none">
                                @foreach($prodis as $p)
                                    <option value="{{ $p->nama }}" {{ $prodiAktif == $p->nama ? 'selected' : '' }}>
                                        {{ strtoupper($p->nama) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="kelas" id="inputKelas" value="{{ $kelasAktif }}">
                    </form>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div id="kelasContainer" class="d-flex gap-2 justify-content-md-start scroll-hide overflow-auto">
                        {{-- Rendered via JS --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. GRID JADWAL PER HARI --}}
    <div class="row g-4">
        @php $daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp

        @foreach($daftar_hari as $hari)
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 day-container">
                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-0">
                    <h5 class="fw-800 text-dark mb-0">{{ $hari }}</h5>
                    <span class="badge bg-light text-muted rounded-pill px-3 py-2 fw-700 x-small border">
                        {{ isset($jadwals[$hari]) ? $jadwals[$hari]->count() : 0 }} Sesi
                    </span>
                </div>

                <div class="card-body px-4 pb-4 pt-0">
                    <div class="timeline-border mt-1 mb-3"></div>

                    @if(isset($jadwals[$hari]) && $jadwals[$hari]->count() > 0)
                        @foreach($jadwals[$hari] as $item)
                        <div class="schedule-item mb-3 p-3 rounded-4 border position-relative transition-all bg-white">
                            @if(Auth::user()->role == 'admin')
                            <div class="action-overlay position-absolute top-0 end-0 p-2 opacity-0">
                                <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs rounded-circle shadow-sm"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                            @endif

                            <div class="d-flex align-items-start gap-3">
                                <div class="time-box text-center">
                                    <span class="d-block fw-800 text-primary mb-0">{{ date('H:i', strtotime($item->jam_mulai)) }}</span>
                                    <small class="text-muted fw-bold x-small">MULAI</small>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="fw-800 text-dark mb-1 text-truncate" style="font-size: 0.85rem;">{{ $item->mata_kuliah }}</h6>
                                    <p class="text-muted x-small mb-2 d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher me-1 text-primary"></i>
                                        <span class="text-truncate">{{ $item->dosen_pengajar }}</span>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="badge bg-light text-dark-50 border fw-700 px-2 py-1" style="font-size: 0.65rem;">
                                            <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $item->ruangan }}
                                        </span>
                                        <span class="fw-bold text-muted x-small opacity-50">#{{ $item->kelas }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5 opacity-50">
                            <div class="mb-2 fs-3">☕</div>
                            <p class="x-small fw-bold text-muted text-uppercase ls-1">Tidak ada jadwal</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- 5. MODAL TAMBAH JADWAL (ADMIN ONLY) --}}
@if(Auth::user()->role == 'admin')
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-dark text-white p-4">
                <div>
                    <h5 class="fw-800 mb-0">Tambah Jadwal Kuliah</h5>
                    <p class="text-white-50 x-small mb-0 mt-1">Menginput untuk {{ $prodiAktif }} - {{ $kelasAktif }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <input type="hidden" name="prodi" value="{{ $prodiAktif }}">
                        <input type="hidden" name="kelas" value="{{ $kelasAktif }}">
                        <div class="col-12">
                            <label class="x-small fw-800 text-muted mb-1 text-uppercase">Mata Kuliah</label>
                            <input type="text" name="mata_kuliah" class="form-control custom-input" placeholder="Nama mata kuliah..." required>
                        </div>
                        <div class="col-12">
                            <label class="x-small fw-800 text-muted mb-1 text-uppercase">Dosen</label>
                            <input type="text" name="dosen_pengajar" class="form-control custom-input" placeholder="Nama dosen..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="x-small fw-800 text-muted mb-1 text-uppercase">Hari</label>
                            <select name="hari" class="form-select custom-input" required>
                                @foreach($daftar_hari as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="x-small fw-800 text-muted mb-1 text-uppercase">Ruangan</label>
                            <input type="text" name="ruangan" class="form-control custom-input" placeholder="Contoh: Lab 01" required>
                        </div>
                        <div class="col-6">
                            <label class="x-small fw-800 text-muted mb-1 text-uppercase">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control custom-input" required>
                        </div>
                        <div class="col-6">
                            <label class="x-small fw-800 text-muted mb-1 text-uppercase">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control custom-input" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-800 shadow-sm transition-all hover-up">SIMPAN JADWAL</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.7rem; }

    .scroll-hide { display: flex; overflow-x: auto; white-space: nowrap; padding-bottom: 5px; -webkit-overflow-scrolling: touch; }
    .scroll-hide::-webkit-scrollbar { display: none; }

    .btn-nav {
        padding: 6px 16px; border-radius: 50px; text-decoration: none; color: #64748b;
        font-size: 0.7rem; font-weight: 800; background: #ffffff; border: 1px solid #e2e8f0;
        transition: all 0.2s; white-space: nowrap;
    }
    .btn-nav.active { background: #1e293b; color: #ffffff; border-color: #1e293b; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .btn-nav:hover:not(.active) { border-color: #2563eb; color: #2563eb; }

    .schedule-item { border-color: #f1f5f9; transition: all 0.2s; }
    .schedule-item:hover { border-color: #2563eb; transform: translateX(4px); }
    .schedule-item:hover .action-overlay { opacity: 1; }
    .time-box { min-width: 60px; padding: 8px 5px; background: #eff6ff; border-radius: 12px; border: 1px solid #dbeafe; }
    .custom-input { padding: 10px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.8rem; font-weight: 600; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prodiSelect = document.getElementById('prodiSelect');
    const kelasContainer = document.getElementById('kelasContainer');
    const kelasAktif = "{{ $kelasAktif }}";

    function getAlias(prodi) {
        let p = prodi.toUpperCase();
        if (p.includes('MANAJEMEN INFORMATIKA')) return "MI";
        if (p.includes('TEKNOLOGI REKAYASA PERANGKAT LUNAK')) return "TRPL";
        if (p.includes('TEKNIK KOMPUTER')) return "TK";
        if (p.includes('ANIMASI')) return "ANM";
        return prodi.split(' ').map(word => word[0]).join('').toUpperCase();
    }

    function renderKelas(selectedProdi) {
        const alias = getAlias(selectedProdi);
        const tingkat = ['1', '2', '3'];
        const sub = ['A', 'B', 'C'];
        kelasContainer.innerHTML = '';

        tingkat.forEach(t => {
            sub.forEach(s => {
                const namaKelas = `${alias} ${t}${s}`;
                const isActive = namaKelas === kelasAktif;
                const btn = document.createElement('a');
                btn.href = `{{ route('jadwal.index') }}?prodi=${encodeURIComponent(selectedProdi)}&kelas=${encodeURIComponent(namaKelas)}`;
                btn.className = `btn-nav ${isActive ? 'active' : ''}`;
                btn.innerText = namaKelas;
                kelasContainer.appendChild(btn);
            });
        });
    }

    renderKelas(prodiSelect.value);

    prodiSelect.addEventListener('change', function() {
        const selectedProdi = this.value;
        const alias = getAlias(selectedProdi);
        window.location.href = `{{ route('jadwal.index') }}?prodi=${encodeURIComponent(selectedProdi)}&kelas=${encodeURIComponent(alias + ' 1A')}`;
    });
});
</script>
@endsection
