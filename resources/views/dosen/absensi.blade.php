@extends('layouts.app')

@section('title', $kelas ? 'Presensi ' . $kelas : 'Pilih Kelas')

@section('content')
<div class="container-fluid py-3 animate__animated animate__fadeIn">

    {{-- HEADER: Lebih Bersih & Modern --}}
    <div class="d-flex justify-content-between align-items-center mb-3 bg-white p-3 rounded-3 shadow-sm border-0">
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">
                {{ $kelas ? 'Presensi Kelas ' . $kelas : 'Pilih Kelas Terlebih Dahulu' }}
            </h5>
            <div class="d-flex align-items-center mt-1">
                <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.65rem;">
                    <i class="fas fa-university me-1"></i> {{ request('prodi') }}
                </span>
                <span class="mx-2 text-silver" style="font-size: 0.7rem;">|</span>
                <span class="text-muted" style="font-size: 0.7rem;">
                    <i class="far fa-calendar-alt me-1"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>
        <div>
            @if($kelas)
                <button type="button" class="btn btn-navy-sm rounded-pill px-3 fw-bold me-2" onclick="addRow()">
                    <i class="fas fa-plus me-1"></i> Tambah Manual
                </button>
                <a href="{{ route('dosen.absensi', ['prodi' => request('prodi')]) }}" class="btn btn-outline-light-sm rounded-circle shadow-none">
                    <i class="fas fa-sync-alt"></i>
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-soft-success border-0 py-2 px-3 small shadow-sm mb-3 d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- MAIN TABLE --}}
    @if($kelas)
    <form action="{{ route('dosen.storeAbsen') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelas }}">
        <input type="hidden" name="prodi" value="{{ request('prodi') }}">
        <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 compact-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-xxs fw-bold text-uppercase" style="width: 60px;">NO</th>
                            <th class="py-3 text-secondary text-xxs fw-bold text-uppercase" style="width: 140px;">NIM</th>
                            <th class="py-3 text-secondary text-xxs fw-bold text-uppercase">Mahasiswa</th>
                            <th class="py-3 text-secondary text-xxs fw-bold text-uppercase text-center" style="width: 250px;">Status Presensi</th>
                            <th class="py-3 text-center pe-4" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTable">
                        @forelse($mahasiswa as $key => $mhs)
                        <tr class="user-row">
                            <td class="ps-4">
                                <span class="text-muted small fw-bold">{{ $key + 1 }}</span>
                            </td>
                            <td>
                                <input type="text" name="nim[]" value="{{ $mhs->nim_nip }}" class="form-control-plaintext small py-0 text-dark" readonly>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">{{ substr($mhs->name, 0, 1) }}</div>
                                    <input type="text" name="nama[]" value="{{ $mhs->name }}" class="form-control-plaintext fw-bold small py-0 text-dark" readonly>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="status-group d-flex justify-content-center gap-1">
                                    <input type="radio" class="btn-check" name="status[{{$key}}]" id="h-{{$key}}" value="Hadir" checked>
                                    <label class="btn btn-status" for="h-{{$key}}">H</label>

                                    <input type="radio" class="btn-check" name="status[{{$key}}]" id="i-{{$key}}" value="Izin">
                                    <label class="btn btn-status" for="i-{{$key}}">I</label>

                                    <input type="radio" class="btn-check" name="status[{{$key}}]" id="s-{{$key}}" value="Sakit">
                                    <label class="btn btn-status" for="s-{{$key}}">S</label>

                                    <input type="radio" class="btn-check" name="status[{{$key}}]" id="a-{{$key}}" value="Alpa">
                                    <label class="btn btn-status" for="a-{{$key}}">A</label>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn-del" onclick="this.closest('tr').remove()">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyPlaceholder">
                            <td colspan="5" class="py-5 text-center text-muted small">
                                <i class="fas fa-user-slash d-block mb-2 opacity-25" style="font-size: 2rem;"></i>
                                Belum ada data mahasiswa untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0 py-3 text-end px-4">
                <button type="submit" class="btn btn-navy px-4 rounded-pill fw-bold shadow-sm">
                    Simpan Presensi <i class="fas fa-check-circle ms-1"></i>
                </button>
            </div>
        </div>
    </form>

    {{-- PILIHAN KELAS: Dibuat Lebih Rapih --}}
    @else
    <div class="row g-2">
        @php
            $prodiName = request('prodi');
            $daftarKelas = \App\Models\Jadwal::where('prodi', $prodiName)->distinct()->orderBy('kelas', 'asc')->pluck('kelas');
        @endphp

        @forelse($daftarKelas as $kls)
        <div class="col-6 col-md-3">
            <a href="{{ route('dosen.absensi', ['kelas' => $kls, 'prodi' => $prodiName]) }}" class="text-decoration-none">
                <div class="p-2 px-3 bg-white border rounded-3 shadow-sm d-flex align-items-center hover-card">
                    <div class="bg-navy text-white rounded-2 px-2 py-1 me-3 small fw-bold" style="font-size: 0.7rem;">{{ $kls }}</div>
                    <div class="text-dark fw-bold" style="font-size: 0.75rem;">Mulai Absen</div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="opacity-25 mb-2"><i class="fas fa-layer-group fa-3x"></i></div>
            <p class="text-muted small">Pilih Program Studi untuk melihat daftar kelas.</p>
        </div>
        @endforelse
    </div>
    @endif
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    body { background-color: #fcfcfd; font-family: 'Inter', sans-serif; }
    .text-xxs { font-size: 0.65rem; letter-spacing: 0.05rem; }
    .text-silver { color: #bdc3c7; }

    /* Compact Table */
    .compact-table thead th { border: none; font-weight: 700; background: #fafbfc; }
    .user-row { border-bottom: 1px solid #f1f3f5; transition: 0.2s; }
    .user-row:hover { background-color: #fbfcfd; }

    /* Avatar Minimalist */
    .avatar-sm {
        width: 28px; height: 28px; background: #f1f5f9; color: #475569;
        display: flex; align-items: center; justify-content: center;
        border-radius: 6px; font-weight: 700; font-size: 11px; border: 1px solid #e2e8f0;
    }

    /* Clean Status Buttons */
    .status-btn {
        width: 32px; height: 32px; padding: 0; line-height: 30px;
        font-size: 0.7rem; font-weight: 700; border-radius: 6px;
        border: 1px solid #dee2e6; background-color: #fff;
        color: #adb5bd; transition: all 0.2s ease;
    }
    .status-btn:hover { background-color: #f8f9fa; color: #6c757d; }

    /* Checked States - Custom Colors but not too bright */
    .btn-check:checked + .btn-status[for^="h"] { background-color: #0d1b2a; border-color: #0d1b2a; color: white; }
    .btn-check:checked + .btn-status[for^="i"] { background-color: #0d1b2a; border-color: #0d1b2a; color: white; }
    .btn-check:checked + .btn-status[for^="s"] { background-color: #0d1b2a; border-color: #0d1b2a; color: white; }
    .btn-check:checked + .btn-status[for^="a"] { background-color: #0d1b2a; border-color: #0d1b2a; color: white; }

    /* Modern Buttons */
    .btn-navy { background: #0d1b2a; color: #fff; border: none; font-size: 0.8rem; }
    .btn-navy:hover { background: #1b263b; color: #fff; }
    .btn-navy-sm { background: #0d1b2a; color: #fff; border: none; font-size: 0.75rem; padding: 6px 15px; }
    .btn-outline-light-sm { border: 1px solid #dee2e6; color: #adb5bd; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; }
    .btn-del { border: none; background: none; color: #dee2e6; transition: 0.2s; font-size: 0.85rem; }
    .btn-del:hover { color: #e11d48; }

    /* Card Utility */
    .alert-soft-success { background-color: #ecfdf5; color: #065f46; font-weight: 600; }
    .hover-card { transition: 0.2s; border: 1px solid #edf2f7 !important; }
    .hover-card:hover { border-color: #0d1b2a !important; background: #fbfcfd !important; transform: translateY(-1px); }
    .bg-navy { background: #0d1b2a; }
</style>

<script>
function addRow() {
    const placeholder = document.getElementById('emptyPlaceholder');
    if (placeholder) placeholder.remove();

    const tbody = document.getElementById('attendanceTable');
    const index = tbody.rows.length;

    const newRow = document.createElement('tr');
    newRow.className = "animate__animated animate__fadeIn user-row";
    newRow.innerHTML = `
        <td class="ps-4 text-muted small fw-bold">${index + 1}</td>
        <td><input type="text" name="nim[]" class="form-control form-control-sm border-0 bg-light" placeholder="NIM" style="font-size: 0.75rem;" required></td>
        <td><input type="text" name="nama[]" class="form-control form-control-sm border-0 bg-light fw-bold" placeholder="Nama Mahasiswa" style="font-size: 0.75rem;" required></td>
        <td class="text-center">
            <div class="status-group d-flex justify-content-center gap-1">
                <input type="radio" class="btn-check" name="status[${index}]" id="h-${index}" value="Hadir" checked>
                <label class="btn btn-status" for="h-${index}">H</label>
                <input type="radio" class="btn-check" name="status[${index}]" id="i-${index}" value="Izin">
                <label class="btn btn-status" for="i-${index}">I</label>
                <input type="radio" class="btn-check" name="status[${index}]" id="s-${index}" value="Sakit">
                <label class="btn btn-status" for="s-${index}">S</label>
                <input type="radio" class="btn-check" name="status[${index}]" id="a-${index}" value="Alpa">
                <label class="btn btn-status" for="a-${index}">A</label>
            </div>
        </td>
        <td class="text-center pe-4">
            <button type="button" class="btn-del" onclick="this.closest('tr').remove()"><i class="far fa-trash-alt"></i></button>
        </td>
    `;
    tbody.appendChild(newRow);
}
</script>
@endsection
