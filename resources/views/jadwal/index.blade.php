@extends('layouts.app')

@section('title', 'Jadwal ' . ($kelasAktif ?? ''))

@section('content')
<div class="container animate__animated animate__fadeIn">
    {{-- 1. ALERT NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-minimal mb-3 d-flex align-items-center py-2 px-3 shadow-sm border-0 bg-success-light" role="alert">
        <i class="fas fa-check-circle me-2 text-success"></i>
        <div class="small fw-bold text-success">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto small" data-bs-dismiss="alert" style="font-size: 0.5rem;"></button>
    </div>
    @endif

    {{-- 2. ALERT ERROR (PENTING: Untuk melihat kenapa data gagal simpan) --}}
    @if ($errors->any())
    <div class="alert alert-minimal mb-3 shadow-sm border-0 bg-danger-light" role="alert">
        <div class="d-flex align-items-center mb-1">
            <i class="fas fa-exclamation-circle me-2 text-danger"></i>
            <div class="small fw-bold text-danger">Terjadi Kesalahan:</div>
        </div>
        <ul class="mb-0 x-small text-danger ps-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- 3. HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Jadwal Perkuliahan</h4>
            <p class="text-muted x-small mb-0">Kelas Aktif: <span class="text-primary fw-bold">{{ $kelasAktif }}</span></p>
        </div>

        @if(Auth::user()->role == 'admin')
        <button class="btn btn-primary-compact rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
            <i class="fas fa-plus me-1 small"></i> Tambah
        </button>
        @endif
    </div>

    {{-- 4. NAVIGASI TAB KELAS --}}
    <div class="mb-4 text-center text-md-start">
        <div class="d-flex gap-1 p-1 bg-light rounded-pill d-inline-flex border shadow-sm">
            @foreach(['MI 3A', 'MI 3B', 'MI 3C'] as $kls)
                <a href="{{ route('jadwal.index', ['kelas' => $kls]) }}"
                   class="btn-tab {{ $kelasAktif == $kls ? 'active' : '' }}">
                    {{ $kls }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- 5. GRID JADWAL PER HARI --}}
    <div class="row g-3">
        @php $daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp

        @foreach($daftar_hari as $hari)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100 day-card">
                <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center border-0 bg-transparent">
                    <span class="fw-bold text-dark small">{{ $hari }}</span>
                    <span class="count-badge-mini">{{ isset($jadwals[$hari]) ? $jadwals[$hari]->count() : 0 }} Sesi</span>
                </div>

                <div class="card-body p-3 pt-0">
                    <hr class="mt-0 mb-2 opacity-5">

                    @if(isset($jadwals[$hari]) && $jadwals[$hari]->count() > 0)
                        @foreach($jadwals[$hari] as $item)
                        <div class="session-item p-2 mb-2 rounded-2 transition-all position-relative">
                            @if(Auth::user()->role == 'admin')
                            <div class="position-absolute top-0 end-0 mt-1 me-1 opacity-0 action-btns">
                                <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-mini"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                            @endif

                            <div class="d-flex align-items-center gap-2">
                                <div class="time-tag">{{ date('H:i', strtotime($item->jam_mulai)) }}</div>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.8rem;">{{ $item->mata_kuliah }}</h6>
                                    <p class="x-small text-muted mb-0 text-truncate">{{ $item->dosen_pengajar }}</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top border-faint">
                                <span class="x-small text-muted"><i class="fas fa-door-open me-1"></i>{{ $item->ruangan }}</span>
                                <span class="badge-class">{{ $item->kelas }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-moon mb-1 text-muted opacity-25"></i>
                            <p class="x-small text-muted mb-0">Tidak ada jadwal</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- 6. MODAL TAMBAH JADWAL --}}
@if(Auth::user()->role == 'admin')
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header py-2 px-3 bg-light border-0">
                <h6 class="fw-bold mb-0">Tambah Jadwal</h6>
                <button type="button" class="btn-close small" data-bs-dismiss="modal" style="transform: scale(0.7);"></button>
            </div>
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-12 text-center mb-2">
                            <span class="badge bg-primary-light text-primary x-small px-3 rounded-pill">Input Data Baru</span>
                        </div>
                        <div class="col-12">
                            <label class="x-small fw-bold text-muted mb-1">Mata Kuliah</label>
                            <input type="text" name="mata_kuliah" class="form-control form-control-sm" placeholder="Contoh: Pemrograman Web" required>
                        </div>
                        <div class="col-12">
                            <label class="x-small fw-bold text-muted mb-1">Dosen Pengajar</label>
                            <input type="text" name="dosen_pengajar" class="form-control form-control-sm" placeholder="Contoh: Dr. Budi Santoso" required>
                        </div>
                        <div class="col-6">
                            <label class="x-small fw-bold text-muted mb-1">Hari</label>
                            <select name="hari" class="form-select form-select-sm" required>
                                @foreach($daftar_hari as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="x-small fw-bold text-muted mb-1">Kelas</label>
                            <input type="text" name="kelas" value="{{ $kelasAktif }}" class="form-control form-control-sm bg-light-gray" readonly>
                        </div>
                        <div class="col-6">
                            <label class="x-small fw-bold text-muted mb-1">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="x-small fw-bold text-muted mb-1">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="x-small fw-bold text-muted mb-1">Ruangan</label>
                            <input type="text" name="ruangan" class="form-control form-control-sm" placeholder="Contoh: Lab 01 / R. 302" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2 border-0">
                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill shadow-sm">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.7rem; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .bg-light-gray { background-color: #f8fafc; }
    .bg-success-light { background: #f0fdf4; }
    .bg-danger-light { background: #fef2f2; }
    .bg-primary-light { background: #eff6ff; }

    /* Compact Buttons & Badges */
    .btn-primary-compact { background: #2563eb; color: white; border: none; font-size: 0.75rem; font-weight: 600; padding: 6px 15px; }
    .btn-tab { padding: 5px 15px; border-radius: 50px; text-decoration: none; color: #64748b; font-size: 0.75rem; font-weight: 700; transition: 0.2s; }
    .btn-tab.active { background: #2563eb; color: white; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); }
    .btn-tab:not(.active):hover { background: #fff; color: #2563eb; }

    /* Card & List Item */
    .day-card { background: #fff; border: 1px solid rgba(0,0,0,0.03) !important; }
    .count-badge-mini { font-size: 0.6rem; background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 10px; font-weight: 700; }
    .session-item { background: #fcfdfe; border: 1px solid #f1f5f9; }
    .session-item:hover { border-color: #2563eb; transform: translateY(-2px); box-shadow: 0 5px 10px rgba(0,0,0,0.05); }
    .session-item:hover .action-btns { opacity: 1; }

    .time-tag { background: #2563eb; color: white; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 5px; }
    .badge-class { font-size: 0.6rem; font-weight: 800; color: #94a3b8; background: #f8fafc; padding: 1px 6px; border-radius: 4px; border: 1px solid #e2e8f0; }

    .btn-delete-mini { background: #fff; border: 1px solid #fee2e2; color: #ef4444; font-size: 0.6rem; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .btn-delete-mini:hover { background: #ef4444; color: #fff; }

    .border-faint { border-color: #f1f5f9 !important; }
    .form-control-sm, .form-select-sm { font-size: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.5rem; }
    .form-control-sm:focus { border-color: #2563eb; box-shadow: none; }
    .alert-minimal { border-radius: 12px; }
</style>
@endsection
