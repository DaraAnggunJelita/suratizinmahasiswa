@extends('layouts.app')

@section('title', 'Input Absensi ' . $kelas)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Input Absensi Manual</h2>
        <p class="text-muted small">Kelas: <span class="badge bg-primary-subtle text-primary fw-bold">{{ $kelas }}</span></p>
    </div>
    <a href="{{ route('dosen.absensi', $kelas) }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

{{-- Menampilkan Error Validasi jika ada --}}
@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('dosen.storeAbsen') }}" method="POST">
    @csrf
    <input type="hidden" name="kelas" value="{{ $kelas }}">

    {{-- INPUT TANGGAL (WAJIB ADA karena di Controller di-validate 'required') --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Tanggal Absensi</label>
            <input type="date" name="tanggal" class="form-control border-0 bg-light rounded-3 shadow-sm" value="{{ date('Y-m-d') }}" required>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="py-3 ps-4 text-muted small fw-bold" style="width: 80px;">NO</th>
                            <th class="text-muted small fw-bold">NAMA MAHASISWA</th>
                            <th class="text-muted small fw-bold" style="width: 250px;">NIM</th>
                            <th class="text-muted small fw-bold" style="width: 200px;">STATUS</th>
                            <th class="text-muted small fw-bold text-center" style="width: 80px;">HAPUS</th>
                        </tr>
                    </thead>
                    <tbody id="absenBody">
                        {{-- Row Default Pertama --}}
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <input type="number" name="no[]" class="form-control form-control-sm bg-light border-0 text-center rounded-3" value="1" readonly>
                            </td>
                            <td>
                                {{-- Tambahkan mahasiswa_id hidden (kosongkan untuk input manual) --}}
                                <input type="hidden" name="mahasiswa_id[]" value="0">
                                <input type="text" name="nama_mahasiswa[]" class="form-control form-control-sm border-0 bg-light rounded-3" placeholder="Masukkan Nama..." required>
                            </td>
                            <td>
                                <input type="text" name="nim_mahasiswa[]" class="form-control form-control-sm border-0 bg-light rounded-3" placeholder="Masukkan NIM..." required>
                            </td>
                            <td>
                                <select name="status[]" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 fw-semibold text-navy shadow-sm-focus" required>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Alfa">Alfa</option>
                                </select>
                            </td>
                            <td class="text-center pe-3">
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle disabled opacity-25">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top p-4 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border shadow-sm text-primary" id="tambahBaris">
                <i class="fas fa-plus-circle me-2"></i> Tambah Baris
            </button>
            <button type="submit" class="btn btn-navy-grad rounded-pill px-5 fw-bold shadow">
                <i class="fas fa-save me-2"></i> Simpan Absensi
            </button>
        </div>
    </div>
</form>

<style>
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white; border: none; transition: 0.3s;
    }
    .btn-navy-grad:hover {
        background: #415A77; color: white; transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 27, 42, 0.2);
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border: 1px solid #647ACB !important;
        box-shadow: 0 0 0 4px rgba(100, 122, 203, 0.1);
    }
    .text-navy { color: #0D1B2A; }
    .animated { animation-duration: 0.3s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .fadeIn { animation-name: fadeIn; }
</style>

<script>
document.getElementById('tambahBaris').addEventListener('click', function(){
    let tbody = document.getElementById('absenBody');
    let rowCount = tbody.rows.length + 1;
    let row = `<tr class="border-bottom animated fadeIn">
        <td class="ps-4">
            <input type="number" name="no[]" class="form-control form-control-sm bg-light border-0 text-center rounded-3" value="${rowCount}" readonly>
        </td>
        <td>
            <input type="hidden" name="mahasiswa_id[]" value="0">
            <input type="text" name="nama_mahasiswa[]" class="form-control form-control-sm border-0 bg-light rounded-3" placeholder="Masukkan Nama..." required>
        </td>
        <td>
            <input type="text" name="nim_mahasiswa[]" class="form-control form-control-sm border-0 bg-light rounded-3" placeholder="Masukkan NIM..." required>
        </td>
        <td>
            <select name="status[]" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 fw-semibold text-navy shadow-sm-focus" required>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alfa">Alfa</option>
            </select>
        </td>
        <td class="text-center pe-3">
            <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-row">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>`;
    tbody.insertAdjacentHTML('beforeend', row);
});

document.getElementById('absenBody').addEventListener('click', function(e){
    if(e.target.closest('.remove-row')){
        e.target.closest('tr').remove();
        // Update penomoran jika baris dihapus
        let rows = document.querySelectorAll('#absenBody tr');
        rows.forEach((row, index) => {
            row.querySelector('input[name="no[]"]').value = index + 1;
        });
    }
});
</script>
@endsection
