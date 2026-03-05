@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
<div class="container-fluid py-4 px-md-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Manajemen Pengumuman</h2>
            <p class="text-muted">Kelola informasi berdasarkan kelas mahasiswa.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus me-2"></i>Buat Pengumuman Baru
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    {{-- DAFTAR PENGUMUMAN --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-modern-table">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Judul & Pesan</th>
                        <th>Target Kelas</th>
                        <th>Pengirim</th>
                        <th>Tanggal</th>
                        <th class="text-end pe-4">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengumumans as $index => $p)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->judul }}</div>
                            <div class="text-muted x-small text-truncate" style="max-width: 350px;">{{ $p->pesan }}</div>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill small">
                                <i class="fas fa-users me-1"></i> {{ $p->kelas }}
                            </span>
                        </td>
                        <td class="small text-dark fw-medium">
                            {{ $p->user->name ?? 'Admin' }}
                        </td>
                        <td class="small text-muted">
                            {{ $p->created_at->format('d M Y') }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-2">
                                {{-- Tombol Edit --}}
                                <button class="btn btn-action btn-acc"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $p->id }}"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-action btn-rej" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header border-0 pt-4 px-4">
                                    <h5 class="fw-bold">Edit Pengumuman</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('pengumuman.update', $p->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body px-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Judul</label>
                                            <input type="text" name="judul" class="form-control bg-light border-0" value="{{ $p->judul }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Target Kelas</label>
                                            <select name="kelas" class="form-select bg-light border-0" required>
                                                <option value="Semua" {{ $p->kelas == 'Semua' ? 'selected' : '' }}>Semua Kelas</option>
                                                <option value="MI 3A" {{ $p->kelas == 'MI 3A' ? 'selected' : '' }}>MI 3A</option>
                                                <option value="MI 3B" {{ $p->kelas == 'MI 3B' ? 'selected' : '' }}>MI 3B</option>
                                                <option value="MI 3C" {{ $p->kelas == 'MI 3C' ? 'selected' : '' }}>MI 3C</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Isi Pesan</label>
                                            <textarea name="pesan" rows="4" class="form-control bg-light border-0" required>{{ $p->pesan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pb-4 px-4">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3 px-4">Update Data</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-bullhorn fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada pengumuman untuk kelas manapun.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold text-primary">Kirim Pengumuman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pengumuman.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Pengumuman</label>
                        <input type="text" name="judul" class="form-control bg-light border-0" placeholder="Contoh: Perubahan Jam Kuliah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Target Kelas</label>
                        <select name="kelas" class="form-select bg-light border-0" required>
                            <option value="" disabled selected>Pilih Kelas...</option>
                            <option value="Semua">Semua Kelas</option>
                            <option value="MI 3A">MI 3A</option>
                            <option value="MI 3B">MI 3B</option>
                            <option value="MI 3C">MI 3C</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Isi Pesan</label>
                        <textarea name="pesan" rows="4" class="form-control bg-light border-0" placeholder="Tulis instruksi lengkap di sini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling select agar match dengan input lainnya */
    .form-select {
        cursor: pointer;
    }

    .x-small { font-size: 0.75rem; }
    .btn-action {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        border: none; transition: 0.2s;
    }
    .btn-acc { background-color: #eef2ff; color: #4f46e5; }
    .btn-rej { background-color: #fef2f2; color: #ef4444; }
    .btn-acc:hover { background-color: #4f46e5; color: white; }
    .btn-rej:hover { background-color: #ef4444; color: white; }

    .custom-modern-table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        padding: 15px;
        background-color: #f8fafc;
    }
</style>
@endsection
