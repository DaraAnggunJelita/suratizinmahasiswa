@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
<div class="container-fluid animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-800 text-dark mb-1">Manajemen Pengumuman</h2>
            <p class="text-muted fw-medium mb-0">Publikasikan informasi penting kepada mahasiswa dan dosen.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-primary btn-lg rounded-4 shadow-sm fw-800 px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalBuatPengumuman">
                <i class="fas fa-plus-circle me-2"></i> Buat Pengumuman
            </button>
        </div>
    </div>

    {{-- Riwayat Publikasi --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-800 text-dark mb-0">Riwayat Publikasi</h5>
            <div class="dropdown">
                <button class="btn btn-light btn-sm rounded-3 border fw-bold" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                    <li><a class="dropdown-item fw-medium" href="#">Terbaru</a></li>
                    <li><a class="dropdown-item fw-medium" href="#">Terlama</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted fw-bold small">
                            <th class="ps-4 py-3 uppercase" style="letter-spacing: 1px;">NO</th>
                            <th class="py-3 uppercase" style="letter-spacing: 1px;">DETAIL INFORMASI</th>
                            <th class="py-3 uppercase" style="letter-spacing: 1px;">TARGET</th>
                            <th class="py-3 uppercase text-center" style="letter-spacing: 1px;">PUBLIKATOR</th>
                            <th class="py-3 uppercase" style="letter-spacing: 1px;">WAKTU</th>
                            <th class="py-3 text-center pe-4 uppercase" style="letter-spacing: 1px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengumumans as $index => $p)
                        <tr class="transition-all">
                            <td class="ps-4 fw-medium text-muted">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="fw-800 text-dark mb-0">{{ $p->judul }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 300px;">{{ $p->pesan }}</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-bold">
                                    <i class="fas fa-users me-1"></i> {{ $p->target_kelas ?? 'Semua' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="avatar-circle me-2 bg-info text-white fw-bold">
                                        {{ strtoupper(substr($p->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="fw-700 text-dark">{{ $p->user->name ?? 'Admin' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium small">{{ $p->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-muted x-small">{{ $p->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-action-edit" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/no-messages.svg" alt="No data" style="width: 150px;" class="mb-3">
                                <p class="text-muted fw-medium">Belum ada pengumuman yang dipublikasikan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalBuatPengumuman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-800 text-dark mb-0">Buat Pengumuman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-700 small text-muted text-uppercase">Subjek Pengumuman</label>
                        <input type="text" name="judul" class="form-control rounded-3 py-2 border-light bg-light shadow-none" placeholder="Contoh: Perubahan Jam Kuliah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-700 small text-muted text-uppercase">Target Kelas</label>
                        <select name="target_kelas" class="form-select rounded-3 py-2 border-light bg-light shadow-none">
                            <option value="Semua">Semua Kelas</option>
                            <option value="MI 3A">MI 3A</option>
                            <option value="MI 3B">MI 3B</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-700 small text-muted text-uppercase">Pesan</label>
                        <textarea name="pesan" rows="4" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Tulis informasi detail di sini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4 shadow">Publikasikan <i class="fas fa-paper-plane ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .x-small { font-size: 0.7rem; }

    .bg-primary-subtle { background-color: #e0e7ff !important; }

    .avatar-circle {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
    }

    .btn-action-edit {
        background-color: #f0fdf4; color: #16a34a;
        border: none; width: 35px; height: 35px;
        border-radius: 10px; transition: all 0.3s ease;
    }
    .btn-action-edit:hover { background-color: #16a34a; color: white; }

    .btn-action-delete {
        background-color: #fef2f2; color: #dc2626;
        border: none; width: 35px; height: 35px;
        border-radius: 10px; transition: all 0.3s ease;
    }
    .btn-action-delete:hover { background-color: #dc2626; color: white; }

    .transition-all { transition: all 0.2s ease; cursor: default; }
    .table-hover tbody tr:hover { background-color: #f1f5f9; transform: scale(1.002); }

    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border-color: var(--bs-primary) !important;
    }
</style>
@endsection
