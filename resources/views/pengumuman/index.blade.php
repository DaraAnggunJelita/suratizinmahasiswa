@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="main-container animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="header-wrapper mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="icon-box-header">
                        <i class="fas fa-bullhorn text-primary small"></i>
                    </div>
                    <h3 class="fw-800 text-dark mb-0 h6">Manajemen Pengumuman</h3>
                </div>
                <p class="text-muted fw-medium mb-0 ms-md-4 ps-md-2 x-small">Publikasikan informasi penting kepada mahasiswa dan dosen.</p>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <button class="btn-create-info" data-bs-toggle="modal" data-bs-target="#modalBuatPengumuman">
                    <i class="fas fa-plus-circle me-1"></i> Buat Pengumuman
                </button>
            </div>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show x-small fw-bold rounded-3 mb-3" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.8rem;"></button>
        </div>
    @endif

    {{-- Content Card --}}
    <div class="content-card">
        <div class="card-header-custom p-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-800 text-dark mb-0 small">Riwayat Publikasi</h6>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-custom" id="pengumumanTable">
                <thead>
                    <tr>
                        <th class="ps-3">NO</th>
                        <th>DETAIL INFORMASI</th>
                        <th>TARGET</th>
                        <th class="text-center">PUBLIKATOR</th>
                        <th>WAKTU</th>
                        <th class="text-center pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengumumans as $index => $p)
                    <tr>
                        <td class="ps-3 fw-bold text-muted small">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-800 text-dark mb-0 small">{{ $p->judul }}</div>
                            <div class="text-muted x-small text-truncate-custom">{{ $p->pesan }}</div>
                        </td>
                        <td>
                            <span class="badge-target">
                                {{-- PERBAIKAN: Menggunakan $p->kelas --}}
                                <i class="fas fa-users me-1"></i> {{ $p->kelas ?? 'Semua' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <div class="avatar-mini">
                                    {{ strtoupper(substr($p->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="fw-700 text-dark x-small">{{ $p->user->name ?? 'Admin' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-dark fw-bold x-small">{{ $p->created_at->translatedFormat('d M Y') }}</div>
                            <div class="text-muted" style="font-size: 9px;">{{ $p->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="pe-3">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <p class="text-muted x-small mb-0">Belum ada pengumuman.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalBuatPengumuman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm-custom">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pt-3 px-3 pb-0">
                <h6 class="fw-800 text-dark mb-0">Buat Pengumuman Baru</h6>
                <button type="button" class="btn-close small" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="label-custom">Subjek Pengumuman</label>
                        <input type="text" name="judul" class="form-control-compact" placeholder="Contoh: Perubahan Jam Kuliah" required>
                    </div>
                    <div class="mb-2">
                        <label class="label-custom">Kelas</label>
                        <select name="kelas" class="form-select-compact">
                            <option value="Semua">Semua Kelas</option>
                            <option value="MI 3A">MI 3A</option>
                            <option value="MI 3B">MI 3B</option>
                            <option value="MI 3C">MI 3C</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="label-custom">Pesan</label>
                        <textarea name="pesan" rows="3" class="form-control-compact" placeholder="Tulis informasi detail di sini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 pt-0">
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm">Publikasikan <i class="fas fa-paper-plane ms-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
@foreach($pengumumans as $p)
<div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered modal-sm-custom">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pt-3 px-3 pb-0">
                <h6 class="fw-800 text-dark mb-0">Edit Pengumuman</h6>
                <button type="button" class="btn-close small" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengumuman.update', $p->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="label-custom">Subjek Pengumuman</label>
                        <input type="text" name="judul" class="form-control-compact" value="{{ $p->judul }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="label-custom">Kelas</label>
                        <select name="kelas" class="form-select-compact">
                            {{-- PERBAIKAN: Cek menggunakan $p->kelas --}}
                            <option value="Semua" {{ $p->kelas == 'Semua' ? 'selected' : '' }}>Semua Kelas</option>
                            <option value="MI 3A" {{ $p->kelas == 'MI 3A' ? 'selected' : '' }}>MI 3A</option>
                            <option value="MI 3B" {{ $p->kelas == 'MI 3B' ? 'selected' : '' }}>MI 3B</option>
                            <option value="MI 3C" {{ $p->kelas == 'MI 3C' ? 'selected' : '' }}>MI 3C</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="label-custom">Pesan</label>
                        <textarea name="pesan" rows="3" class="form-control-compact" required>{{ $p->pesan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 pt-0">
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    :root { --primary: #2563eb; --border: #e2e8f0; }
    .main-container { padding: 1.25rem; }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .x-small { font-size: 0.75rem; }

    /* Header */
    .icon-box-header { width: 32px; height: 32px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .btn-create-info { background: var(--primary); color: white; border: none; padding: 6px 14px; border-radius: 8px; font-weight: 700; font-size: 0.75rem; transition: 0.2s; }
    .btn-create-info:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* Card & Table */
    .content-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead th { background: #f8fafc; padding: 10px 12px; color: #64748b; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
    .table-custom tbody td { padding: 10px 12px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 0.8rem; }
    .table-custom tbody tr:hover td { background-color: #f8fafc; }

    /* UI Elements */
    .text-truncate-custom { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .badge-target { background: #eff6ff; color: var(--primary); padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; border: 1px solid #dbeafe; }
    .avatar-mini { width: 24px; height: 24px; background: #3b82f6; color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; }

    /* Actions */
    .btn-action { width: 28px; height: 28px; border-radius: 6px; border: none; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; transition: 0.2s; }
    .btn-edit { background: #f0fdf4; color: #16a34a; }
    .btn-edit:hover { background: #16a34a; color: white; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

    /* Form & Modal Compact */
    .search-box { position: relative; width: 180px; }
    .search-box i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.75rem; }
    .search-box input { padding-left: 30px; font-size: 0.75rem; height: 32px; border-radius: 8px; border: 1px solid var(--border); }

    .modal-sm-custom { max-width: 360px; }
    .form-control-compact, .form-select-compact { width: 100%; padding: 6px 10px; font-size: 0.8rem; border-radius: 8px; border: 1px solid var(--border); background: #f8fafc; }
    .form-control-compact:focus { background: white !important; border-color: var(--primary); outline: none; }
    .label-custom { font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 2px; display: block; }
</style>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('#pengumumanTable tbody tr');
        rows.forEach(row => {
            if (row.innerText.toLowerCase().includes(val)) row.style.display = '';
            else row.style.display = 'none';
        });
    });
</script>
@endsection
