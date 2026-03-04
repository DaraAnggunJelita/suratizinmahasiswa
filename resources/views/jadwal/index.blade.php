@extends('layouts.app')

@section('title', 'Jadwal Kuliah')

@section('content')
<div class="container-fluid">
    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Jadwal Kuliah</h2>
            <p class="text-muted small mb-0">
                <i class="fas fa-info-circle me-1"></i>
                @if(Auth::user()->role == 'mahasiswa')
                    Menampilkan jadwal untuk kelas <strong>{{ Auth::user()->kelas ?? 'Belum Diatur' }}</strong>
                @else
                    Kelola jadwal perkuliahan (Login sebagai: <strong>{{ Auth::user()->role }}</strong>)
                @endif
            </p>
        </div>

        @if(Auth::user()->role == 'admin')
        <button class="btn btn-navy-grad rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
            <i class="fas fa-plus me-2"></i> Tambah Jadwal
        </button>
        @endif
    </div>

    {{-- GRID JADWAL PER HARI --}}
    <div class="row g-4">
        @php
            $daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
        @endphp

        @foreach($daftar_hari as $key => $hari)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="fw-bold mb-0 text-{{ $colors[$key] }}">
                        <i class="fas fa-calendar-day me-2"></i>{{ $hari }}
                    </h6>
                    <span class="badge bg-{{ $colors[$key] }}-subtle text-{{ $colors[$key] }} rounded-pill x-small">
                        {{ isset($jadwals[$hari]) ? $jadwals[$hari]->count() : 0 }} Sesi
                    </span>
                </div>
                <div class="card-body p-3">
                    @if(isset($jadwals[$hari]) && $jadwals[$hari]->count() > 0)
                        @foreach($jadwals[$hari] as $item)
                        <div class="p-3 rounded-4 mb-3 border border-light shadow-sm hover-effect position-relative" style="background-color: #ffffff;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0 small text-dark pe-4">{{ $item->mata_kuliah }}</h6>

                                @if(Auth::user()->role == 'admin')
                                <form action="{{ route('jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 border-0 bg-transparent">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>

                            <p class="x-small text-muted mb-3">
                                <i class="fas fa-user-tie me-1 text-{{ $colors[$key] }}"></i> {{ $item->dosen_pengajar }}
                            </p>

                            {{-- DETAIL JAM DAN DURASI --}}
                            <div class="p-2 rounded-3 bg-light border-start border-{{ $colors[$key] }} border-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-3">
                                        <div>
                                            <span class="d-block fw-bold text-dark small">{{ date('H:i', strtotime($item->jam_mulai)) }}</span>
                                            <span class="x-small text-muted" style="font-size: 0.6rem;">Mulai</span>
                                        </div>
                                        <div class="border-start ps-3">
                                            <span class="d-block fw-bold text-dark small">{{ date('H:i', strtotime($item->jam_selesai)) }}</span>
                                            <span class="x-small text-muted" style="font-size: 0.6rem;">Selesai</span>
                                        </div>
                                    </div>

                                    @php
                                        $awal = strtotime($item->jam_mulai);
                                        $akhir = strtotime($item->jam_selesai);
                                        $diff = abs($akhir - $awal);
                                        $jam_durasi = floor($diff / 3600);
                                        $menit_durasi = floor(($diff / 60) % 60);
                                    @endphp

                                    <div class="text-end">
                                        <span class="badge rounded-pill bg-{{ $colors[$key] }} px-2" style="font-size: 0.65rem;">
                                            @if($jam_durasi > 0) {{ $jam_durasi }}j @endif {{ $menit_durasi }}m
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="x-small text-muted"><i class="fas fa-door-open me-1"></i>{{ $item->ruangan }}</span>
                                <span class="badge bg-{{ $colors[$key] }}-subtle text-{{ $colors[$key] }} x-small px-2">{{ $item->kelas }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-mug-hot fa-2x mb-2 opacity-25"></i>
                            <p class="x-small text-muted mb-0">Tidak ada jadwal</p>
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
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0 text-navy"><i class="fas fa-calendar-plus me-2"></i>Tambah Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Mata Kuliah</label>
                        <input type="text" name="mata_kuliah" class="form-control rounded-3" placeholder="Contoh: Pemrograman Mobile" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Dosen Pengajar</label>
                        <input type="text" name="dosen_pengajar" class="form-control rounded-3" placeholder="Nama Lengkap Dosen" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Hari</label>
                            <select name="hari" class="form-select rounded-3" required>
                                <option value="" selected disabled>Pilih Hari</option>
                                @foreach($daftar_hari as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Kelas</label>
                            <select name="kelas" class="form-select rounded-3" required>
                                <option value="" selected disabled>Pilih Kelas</option>
                                <option value="MI 3A">MI 3A</option>
                                <option value="MI 3B">MI 3B</option>
                                <option value="MI 3C">MI 3C</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Jam Mulai</label>
                            {{-- Step 60 memungkinkan input menit 00-59 --}}
                            <input type="time" name="jam_mulai" class="form-control rounded-3" step="60" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control rounded-3" step="60" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Ruangan / Lab</label>
                        <input type="text" name="ruangan" class="form-control rounded-3" placeholder="Contoh: Lab 02" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-navy-grad rounded-pill px-4 fw-bold text-white">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .text-navy { color: #0D1B2A; }
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%) !important;
        color: white !important;
        border: none;
    }
    .btn-navy-grad:hover { opacity: 0.9; color: #fff; }

    .hover-effect {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border-color: #647ACB !important;
    }

    .x-small { font-size: 0.72rem; }
    .bg-primary-subtle { background-color: #e7f1ff; color: #0d6efd; }
    .bg-success-subtle { background-color: #e1f7e3; color: #198754; }
    .bg-info-subtle { background-color: #e1f5fe; color: #0dcaf0; }
    .bg-warning-subtle { background-color: #fff9db; color: #ffc107; }
    .bg-danger-subtle { background-color: #ffeef0; color: #dc3545; }
    .bg-secondary-subtle { background-color: #f8f9fa; color: #6c757d; }
</style>
@endsection
