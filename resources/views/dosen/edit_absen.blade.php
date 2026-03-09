@extends('layouts.app')

@section('title', 'Edit Absensi - ' . $absen->nama_mahasiswa)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center animate__animated animate__fadeIn">
        <div class="col-md-5">
            <div class="mb-4">
                <a href="{{ route('dosen.absensi', $absen->kelas) }}" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Rekap
                </a>
                <h4 class="fw-bold text-dark mt-2">Ubah Data Kehadiran</h4>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="{{ route('dosen.updateAbsen', $absen->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 p-3 rounded-3" style="background-color: #f8faff; border: 1px dashed #cbd5e1;">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                <span class="fw-bold text-dark">{{ $absen->nama_mahasiswa }}</span>
                            </div>
                            <div class="text-muted small">
                                NIM: {{ $absen->nim_mahasiswa }} | Kelas: {{ $absen->kelas }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                            <select name="status" class="form-select border-2 shadow-none py-2" style="border-radius: 12px;">
                                @foreach(['Hadir', 'Izin', 'Sakit', 'Alpa'] as $st)
                                    <option value="{{ $st }}" {{ $absen->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal" class="form-label small fw-bold text-muted text-uppercase">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control border-2 shadow-none py-2"
                                   value="{{ $absen->tanggal }}" style="border-radius: 12px;">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
