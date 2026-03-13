@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="main-container animate__animated animate__fadeIn">
    {{-- Header --}}
    <div class="header-wrapper mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="icon-box-header">
                        <i class="fas fa-bullhorn text-primary small"></i>
                    </div>
                    <h3 class="fw-800 text-dark mb-0 h6">Manajemen Pengumuman</h3>
                </div>
                <p class="text-muted fw-medium mb-0 ms-md-4 ps-md-2 x-small">Publikasikan informasi kepada mahasiswa dan dosen.</p>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <button class="btn-create-info" data-bs-toggle="modal" data-bs-target="#modalBuatPengumuman">
                    <i class="fas fa-plus-circle me-1"></i> Buat Pengumuman
                </button>
            </div>
        </div>
    </div>

    {{-- Search Only (Tanpa Filter Prodi) --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-2 px-3">
            <div class="search-box w-100">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari judul atau isi pengumuman...">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="content-card">
        <div class="table-responsive">
            <table class="table-custom" id="pengumumanTable">
                <thead>
                    <tr>
                        <th class="ps-3">NO</th>
                        <th>INFORMASI</th>
                        <th>TARGET</th>
                        <th class="text-center">PUBLIKATOR</th>
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
                        <td><span class="badge-target"><i class="fas fa-users me-1"></i> {{ $p->kelas }}</span></td>
                        <td class="text-center">
                            <span class="fw-700 text-dark x-small">{{ $p->user->name ?? 'Admin' }}</span>
                        </td>
                        <td class="pe-3">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted x-small">Belum ada pengumuman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
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
                        <label class="label-custom">Prodi</label>
                        <select name="prodi" class="form-select-compact prodi-selector" required>
                            @foreach($prodis as $p)
                                <option value="{{ $p->nama }}">{{ strtoupper($p->nama) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="label-custom">Kelas</label>
                        <select name="kelas" class="form-select-compact kelas-selector" required></select>
                    </div>
                    <div class="mb-2">
                        <label class="label-custom">Subjek</label>
                        <input type="text" name="judul" class="form-control-compact" placeholder="Judul..." required>
                    </div>
                    <div class="mb-0">
                        <label class="label-custom">Pesan</label>
                        <textarea name="pesan" rows="3" class="form-control-compact" placeholder="Isi pesan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 pt-0">
                    <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill fw-bold py-2">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($pengumumans as $p)
<div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" aria-hidden="true">
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
                        <label class="label-custom">Prodi</label>
                        <select name="prodi" class="form-select-compact prodi-selector" required>
                            @foreach($prodis as $pr)
                                <option value="{{ $pr->nama }}" {{ (isset($p->prodi) && $p->prodi == $pr->nama) ? 'selected' : '' }}>{{ strtoupper($pr->nama) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="label-custom">Kelas (Saat ini: {{ $p->kelas }})</label>
                        <select name="kelas" class="form-select-compact kelas-selector" data-selected="{{ $p->kelas }}" required></select>
                    </div>
                    <div class="mb-2">
                        <label class="label-custom">Subjek</label>
                        <input type="text" name="judul" class="form-control-compact" value="{{ $p->judul }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="label-custom">Pesan</label>
                        <textarea name="pesan" rows="4" class="form-control-compact" required>{{ $p->pesan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 pt-0">
                    <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill fw-bold">Simpan Perubahan</button>
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
    .x-small { font-size: 0.75rem; }
    .icon-box-header { width: 32px; height: 32px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .btn-create-info { background: var(--primary); color: white; border: none; padding: 6px 14px; border-radius: 8px; font-weight: 700; font-size: 0.75rem; }
    .content-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead th { background: #f8fafc; padding: 10px 12px; color: #64748b; font-size: 0.65rem; font-weight: 800; border-bottom: 1px solid var(--border); text-transform: uppercase; }
    .table-custom tbody td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.8rem; }
    .badge-target { background: #eff6ff; color: var(--primary); padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }
    .btn-action { width: 28px; height: 28px; border-radius: 6px; border: none; display: flex; align-items: center; justify-content: center; }
    .btn-edit { background: #f0fdf4; color: #16a34a; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .modal-sm-custom { max-width: 360px; }
    .form-control-compact, .form-select-compact { width: 100%; padding: 6px 10px; font-size: 0.8rem; border-radius: 8px; border: 1px solid var(--border); background: #f8fafc; }
    .label-custom { font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 2px; display: block; }
    .search-box { position: relative; }
    .search-box i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.75rem; }
    .search-box input { padding-left: 30px; font-size: 0.75rem; height: 32px; border-radius: 8px; border: 1px solid var(--border); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function getAlias(prodiName) {
        let name = prodiName.toUpperCase();
        if (name.includes("TEKNOLOGI") || name.includes("TRPL")) return "TRPL";
        if (name.includes("TEKNIK KOMPUTER") || name.includes("TK")) return "TK";
        if (name.includes("ANIMASI")) return "ANM";
        return "MI";
    }

    function populateKelas(prodiSelect, kelasSelect) {
        let alias = getAlias(prodiSelect.value);
        let selectedValue = kelasSelect.getAttribute('data-selected');

        let html = `<option value="Semua ${alias}">Semua ${alias}</option>`;
        ['1', '2', '3'].forEach(tingkat => {
            ['A', 'B', 'C'].forEach(sub => {
                let val = `${alias} ${tingkat}${sub}`;
                let isSelected = (val === selectedValue) ? 'selected' : '';
                html += `<option value="${val}" ${isSelected}>${val}</option>`;
            });
        });
        kelasSelect.innerHTML = html;
    }

    function initDynamicDropdowns() {
        const prodiSelectors = document.querySelectorAll('.prodi-selector');
        const kelasSelectors = document.querySelectorAll('.kelas-selector');

        prodiSelectors.forEach((pSelect, index) => {
            const kSelect = kelasSelectors[index];
            if (pSelect && kSelect) {
                populateKelas(pSelect, kSelect);
                pSelect.addEventListener('change', function() {
                    kSelect.removeAttribute('data-selected');
                    populateKelas(pSelect, kSelect);
                });
            }
        });
    }

    initDynamicDropdowns();

    document.getElementById('searchInput').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('#pengumumanTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });
});
</script>
@endsection
