@extends('layouts.app')

@section('title', isset($kelas) ? 'Input Absensi ' . $kelas : 'Pilih Kelas')

@section('content')

{{-- LOGIKA 1: JIKA KELAS SUDAH DIPILIH (TAMPILKAN TABEL INPUT) --}}
@if(isset($kelas) && $kelas != null)
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-1">Input Absensi Mahasiswa</h2>
            <p class="text-muted small">
                Kelas: <span class="badge bg-primary-subtle text-primary fw-bold">{{ $kelas }}</span>
                | Tanggal: <span class="fw-bold text-navy">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
            </p>
        </div>
        {{-- Kembali ke pilihan prodi (atau dashboard) --}}
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('dosen.storeAbsen') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelas }}">
        <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="py-3 ps-4 text-muted small fw-bold" style="width: 70px;">NO</th>
                                <th class="text-muted small fw-bold">NIM</th>
                                <th class="text-muted small fw-bold">NAMA MAHASISWA</th>
                                <th class="text-muted small fw-bold text-center" style="width: 280px;">STATUS KEHADIRAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswa as $key => $mhs)
                                <tr class="border-bottom">
                                    <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                                    <td class="fw-medium text-secondary small">{{ $mhs->nim_nip ?? '-' }}</td>
                                    <td class="fw-bold text-dark">{{ $mhs->name }}</td>
                                    <td class="pe-4">
                                        <input type="hidden" name="mahasiswa_id[]" value="{{ $mhs->id }}">
                                        @php $isIzin = isset($izinHariIni) && in_array($mhs->id, $izinHariIni); @endphp
                                        <div class="d-flex justify-content-center gap-2">
                                            <select name="status[]" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 fw-bold shadow-sm" style="max-width: 150px;">
                                                <option value="Hadir" {{ !$isIzin ? 'selected' : '' }}>🟢 HADIR</option>
                                                <option value="Izin" {{ $isIzin ? 'selected' : '' }}>🟡 IZIN</option>
                                                <option value="Sakit">🔵 SAKIT</option>
                                                <option value="Alpa">🔴 ALPA</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <p class="text-muted">Tidak ada mahasiswa di kelas ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-navy-grad px-5 py-2 rounded-pill shadow fw-bold">
                    <i class="fas fa-save me-2"></i> Simpan Absensi Kelas {{ $kelas }}
                </button>
            </div>
        </div>
    </form>

{{-- LOGIKA 2: JIKA KELAS BELUM DIPILIH (TAMPILKAN DAFTAR KARTU KELAS) --}}
@else
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Pilih Kelas</h2>
        <p class="text-muted">Program Studi: <span class="fw-bold text-primary">{{ request('prodi') }}</span></p>
    </div>

    <div class="row g-3">
        @php
            // Mengambil data kelas secara langsung di blade agar tidak perlu buat controller baru
            $prodiName = request('prodi');
            $daftarKelas = \App\Models\Jadwal::where('prodi', $prodiName)->distinct()->pluck('kelas');
        @endphp

        @forelse($daftarKelas as $kls)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-primary-subtle"></i>
                        </div>
                        <h4 class="fw-bold mb-1">Kelas {{ $kls }}</h4>
                        <p class="text-muted small mb-4">Input absensi untuk kelas ini</p>

                        {{-- Link diarahkan kembali ke halaman ini sendiri tapi dengan parameter kelas --}}
                        <a href="{{ route('dosen.absensi', ['kelas' => $kls]) }}" class="btn btn-primary w-100 rounded-pill fw-bold">
                            Pilih Kelas
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-3 opacity-50">
                <p class="text-muted">Tidak ada kelas yang ditemukan untuk prodi ini.</p>
            </div>
        @endforelse
    </div>
@endif

<style>
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white; border: none; transition: 0.3s;
    }
    .btn-navy-grad:hover {
        background: #415A77; color: white; transform: translateY(-2px);
    }
    .card-hover { transition: 0.3s; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .text-navy { color: #0D1B2A; }
    .bg-primary-subtle { background-color: #eef2ff !important; }
</style>
@endsection
