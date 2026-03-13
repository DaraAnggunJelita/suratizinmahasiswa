@extends('layouts.app')

@section('title', 'Data Prodi')

@section('content')
<div class="container animate__animated animate__fadeIn py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-800 text-dark mb-0">Data Program Studi</h4>
            <p class="text-muted small mb-0">Kelola daftar prodi di sistem</p>
        </div>
        <button class="btn btn-dark rounded-pill px-4 btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProdi">
            <i class="fas fa-plus me-2"></i>Tambah Prodi
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 x-small fw-bold">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted fw-800 x-small" style="width: 80px;">NO</th>
                        <th class="text-muted fw-800 x-small">KODE</th>
                        <th class="text-muted fw-800 x-small">NAMA PROGRAM STUDI</th>
                        <th class="pe-4 text-muted fw-800 x-small text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prodi as $index => $p)
                    <tr>
                        <td class="ps-4 text-muted fw-bold small">{{ $index + 1 }}</td>
                        <td><span class="badge bg-soft-primary text-primary px-2 py-1 fw-bold">{{ $p->kode }}</span></td>
                        <td class="fw-bold text-dark small">{{ strtoupper($p->nama) }}</td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-light border rounded-pill px-3 fw-bold x-small text-primary shadow-xs"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditProdi{{ $p->id }}">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>

                                <form action="{{ route('admin.prodi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus prodi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold x-small text-danger shadow-xs">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted small">Belum ada data prodi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PINDAHKAN SEMUA MODAL KE SINI (DI LUAR CONTAINER/TABLE) --}}
@foreach($prodi as $p)
    <div class="modal fade" id="modalEditProdi{{ $p->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="z-index: 1060;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #fff !important;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="fw-800 mb-0 text-dark">Edit Program Studi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.prodi.update', $p->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4 text-start">
                        <div class="mb-3">
                            <label class="form-label x-small fw-bold text-muted text-uppercase">Kode Prodi</label>
                            <input type="text" name="kode" value="{{ $p->kode }}" class="form-control custom-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label x-small fw-bold text-muted text-uppercase">Nama Program Studi</label>
                            <input type="text" name="nama" value="{{ $p->nama }}" class="form-control custom-input" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold x-small" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold x-small shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- MODAL TAMBAH PRODI --}}
<div class="modal fade" id="modalTambahProdi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #fff !important;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-800 mb-0 text-dark">Tambah Prodi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.prodi.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 text-start">
                    <div class="mb-3">
                        <label class="form-label x-small fw-bold text-muted text-uppercase">Kode Prodi</label>
                        <input type="text" name="kode" class="form-control custom-input" placeholder="MI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label x-small fw-bold text-muted text-uppercase">Nama Program Studi</label>
                        <input type="text" name="nama" class="form-control custom-input" placeholder="Manajemen Informatika" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold x-small" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold x-small shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.65rem; letter-spacing: 0.5px; }
    .bg-soft-primary { background-color: #eff6ff; }

    /* CSS untuk memastikan input TERANG dan BISA DIKLIK */
    .custom-input {
        background-color: #ffffff !important;
        color: #000000 !important;
        border: 2px solid #eaebed !important;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
        pointer-events: auto !important; /* Memaksa agar bisa diklik */
    }

    .custom-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
    }

    /* Perbaikan Modal yang Gelap */
    .modal-backdrop {
        z-index: 1040 !important;
    }
    .modal {
        z-index: 1050 !important;
    }
</style>
@endsection
