@extends('layouts.app')

@section('title', 'Jadwal Kuliah')

@section('content')
<div class="container-fluid animate__animated animate__fadeIn">
    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-minimal mb-4 d-flex align-items-center" role="alert">
        <i class="fas fa-check-circle me-3 fs-5 text-success"></i>
        <div class="fw-semibold text-success">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1" style="letter-spacing: -1px;">Jadwal Kuliah</h2>
            <p class="text-muted small mb-0 d-flex align-items-center gap-2">
                <i class="fas fa-id-badge text-primary opacity-75"></i>
                @if(Auth::user()->role == 'mahasiswa')
                    Jadwal aktif untuk Kelas <span class="badge-outline">{{ Auth::user()->kelas ?? 'Belum Diatur' }}</span>
                @else
                    Mode Pengelola ({{ ucfirst(Auth::user()->role) }})
                @endif
            </p>
        </div>

        @if(Auth::user()->role == 'admin')
        <button class="btn btn-primary-clean rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
            <i class="fas fa-plus me-2"></i> Tambah Jadwal
        </button>
        @endif
    </div>

    {{-- GRID JADWAL PER HARI --}}
    <div class="row g-4">
        @php
            $daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        @endphp

        @foreach($daftar_hari as $hari)
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 day-card">
                <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center border-0 bg-transparent">
                    <h6 class="fw-800 mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-calendar-alt text-primary opacity-50"></i>
                        {{ $hari }}
                    </h6>
                    <span class="count-badge">
                        {{ isset($jadwals[$hari]) ? $jadwals[$hari]->count() : 0 }} Sesi
                    </span>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="border-top-faint mb-3"></div>

                    @if(isset($jadwals[$hari]) && $jadwals[$hari]->count() > 0)
                        @foreach($jadwals[$hari] as $item)
                        <div class="session-item p-3 mb-2 rounded-3 transition-all hover-light position-relative">

                            {{-- Action Buttons (Admin Only) --}}
                            @if(Auth::user()->role == 'admin')
                            <div class="position-absolute top-0 end-0 mt-2 me-2 opacity-0 action-btns transition-all">
                                <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-xs text-danger rounded-circle bg-white shadow-sm border">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                            @endif

                            <div class="d-flex gap-3 align-items-center mb-2">
                                <div class="time-box text-center rounded-2 px-2 py-1">
                                    <span class="d-block fw-bold text-dark small">{{ date('H:i', strtotime($item->jam_mulai)) }}</span>
                                    <span class="x-small text-muted opacity-75 fw-medium">WIB</span>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small" style="line-height: 1.3;">{{ $item->mata_kuliah }}</h6>
                                    <span class="x-small text-muted fw-medium d-block mt-1">
                                        <i class="fas fa-chalkboard-teacher me-1 opacity-50"></i>{{ $item->dosen_pengajar }}
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top-faint">
                                <div class="d-flex align-items-center gap-2 text-muted x-smallfw-medium">
                                    <i class="fas fa-map-marker-alt opacity-50"></i> {{ $item->ruangan }}
                                </div>
                                <span class="badge bg-light text-muted border x-small fw-bold px-2 rounded-pill">{{ $item->kelas }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5 opacity-40">
                            <i class="fas fa-coffee fa-2x mb-2 text-muted"></i>
                            <p class="x-small fw-bold text-muted text-uppercase tracking-wider mb-0">Tidak Ada Jadwal</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- MODAL TAMBAH JADWAL (ADMIN ONLY) --}}
@if(Auth::user()->role == 'admin')
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pt-4 px-4 bg-light">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Mata Kuliah</label>
                            <input type="text" name="mata_kuliah" class="form-control-minimal" placeholder="Contoh: Desain Antarmuka" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Dosen</label>
                            <input type="text" name="dosen_pengajar" class="form-control-minimal" placeholder="Nama Lengkap Dosen" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Hari</label>
                            <select name="hari" class="form-select-minimal" required>
                                <option value="" selected disabled>Pilih</option>
                                @foreach($daftar_hari as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Kelas</label>
                            <select name="kelas" class="form-select-minimal" required>
                                <option value="" selected disabled>Pilih</option>
                                <option value="MI 3A">MI 3A</option>
                                <option value="MI 3B">MI 3B</option>
                                <option value="MI 3B">MI 3C</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control-minimal" step="60" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control-minimal" step="60" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Ruangan</label>
                            <input type="text" name="ruangan" class="form-control-minimal" placeholder="Contoh: Lab 02" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold x-small text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold x-small text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    /* Global Helpers */
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.75rem; }
    .border-top-faint { border-top: 1px solid #f1f5f9; }

    /* Header Styles */
    .badge-outline {
        border: 1px solid #e2e8f0;
        background: white;
        color: #1e293b;
        padding: 2px 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.7rem;
    }
    .btn-primary-clean {
        background: #2563eb;
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    .btn-primary-clean:hover { background: #1e40af; }

    /* Card Day Styles */
    .day-card {
        background: #ffffff;
        border: 1px solid #f1f5f9 !important;
    }
    .count-badge {
        font-size: 0.7rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #64748b;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* Session Item Styles */
    .session-item {
        border: 1px solid #f8fafc;
    }
    .session-item:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .session-item:hover .action-btns { opacity: 1; }

    .time-box {
        background: #f1f5f9;
        width: 60px;
        border: 1px solid #e2e8f0;
    }

    /* Modal Styling */
    .modal-content { border: none; }
    .form-control-minimal, .form-select-minimal {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 9px 15px;
        font-size: 0.9rem;
        border-radius: 10px;
        width: 100%;
    }
    .form-control-minimal:focus, .form-select-minimal:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
</style>
@endsection
