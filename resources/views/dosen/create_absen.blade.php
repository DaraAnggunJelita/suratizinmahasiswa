@extends('layouts.app')

@section('title', 'Input Absen ' . $kelas)

@section('content')
<div class="container animate__animated animate__fadeIn">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Input Absensi Manual</h4>
            <p class="text-muted x-small mb-0">Kelas: <span class="badge-class-sm">{{ $kelas }}</span></p>
        </div>
        <a href="{{ route('dosen.absensi', $kelas) }}" class="btn btn-light border rounded-pill px-3 btn-sm x-small fw-bold text-muted">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-3">
            <ul class="mb-0 x-small fw-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dosen.storeAbsen') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelas }}">

        {{-- SETTING TANGGAL --}}
        <div class="row mb-3">
            <div class="col-6 col-md-3 col-lg-2">
                <label class="x-small fw-800 text-muted mb-1 text-uppercase">Tanggal Absensi</label>
                <input type="date" name="tanggal" class="form-control form-control-sm border-0 bg-white shadow-sm rounded-3" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        {{-- TABLE INPUT --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 py-2 text-muted x-small fw-bold" style="width: 60px;">NO</th>
                                <th class="text-muted x-small fw-bold">NAMA MAHASISWA</th>
                                <th class="text-muted x-small fw-bold" style="width: 200px;">NIM</th>
                                <th class="text-muted x-small fw-bold" style="width: 150px;">STATUS</th>
                                <th class="text-muted x-small fw-bold text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody id="absenBody">
                            <tr class="border-bottom transition-all">
                                <td class="ps-3">
                                    <input type="text" name="no[]" class="form-control-no" value="1" readonly>
                                </td>
                                <td>
                                    <input type="hidden" name="mahasiswa_id[]" value="0">
                                    <input type="text" name="nama_mahasiswa[]" class="form-input-minimal" placeholder="Ketik nama..." required>
                                </td>
                                <td>
                                    <input type="text" name="nim_mahasiswa[]" class="form-input-minimal" placeholder="Ketik NIM..." required>
                                </td>
                                <td>
                                    <select name="status[]" class="form-select-minimal status-select" required>
                                        <option value="Hadir">Hadir</option>
                                        <option value="Izin">Izin</option>
                                        <option value="Sakit">Sakit</option>
                                        <option value="Alfa">Alfa</option>
                                    </select>
                                </td>
                                <td class="text-center pe-3">
                                    <button type="button" class="btn-delete-mini disabled opacity-25">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top-0 p-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 x-small fw-bold" id="tambahBaris">
                    <i class="fas fa-plus-circle me-1"></i> Baris Baru
                </button>
                <button type="submit" class="btn btn-navy-dark btn-sm rounded-pill px-4 x-small fw-bold shadow-sm">
                    <i class="fas fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.7rem; }

    /* Input Styling */
    .form-input-minimal {
        width: 100%; border: 1px solid transparent; background: #f8fafc; padding: 4px 8px; font-size: 0.8rem; border-radius: 6px; transition: 0.2s;
    }
    .form-input-minimal:focus { background: #fff; border-color: #2563eb; outline: none; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

    .form-control-no {
        width: 35px; border: none; background: transparent; font-size: 0.75rem; font-weight: 700; color: #64748b; text-align: center;
    }

    .form-select-minimal {
        width: 100%; border: 1px solid #e2e8f0; background: #fff; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 50px; color: #1e293b; cursor: pointer;
    }

    /* Buttons */
    .btn-navy-dark { background: #0d1b2a; color: white; border: none; transition: 0.2s; }
    .btn-navy-dark:hover { background: #1b263b; transform: translateY(-1px); color: white; }

    .btn-delete-mini {
        background: #fee2e2; border: none; color: #ef4444; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; transition: 0.2s;
    }
    .btn-delete-mini:not(.disabled):hover { background: #ef4444; color: white; }

    .badge-class-sm { background: #eff6ff; color: #2563eb; padding: 2px 8px; border-radius: 5px; font-weight: 700; font-size: 0.65rem; }

    .table thead th { background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
</style>

<script>
document.getElementById('tambahBaris').addEventListener('click', function(){
    let tbody = document.getElementById('absenBody');
    let rowCount = tbody.rows.length + 1;
    let row = `<tr class="border-bottom animate__animated animate__fadeIn">
        <td class="ps-3">
            <input type="text" name="no[]" class="form-control-no" value="${rowCount}" readonly>
        </td>
        <td>
            <input type="hidden" name="mahasiswa_id[]" value="0">
            <input type="text" name="nama_mahasiswa[]" class="form-input-minimal" placeholder="Ketik nama..." required>
        </td>
        <td>
            <input type="text" name="nim_mahasiswa[]" class="form-input-minimal" placeholder="Ketik NIM..." required>
        </td>
        <td>
            <select name="status[]" class="form-select-minimal status-select" required>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alfa">Alfa</option>
            </select>
        </td>
        <td class="text-center pe-3">
            <button type="button" class="btn-delete-mini remove-row">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>`;
    tbody.insertAdjacentHTML('beforeend', row);
});

document.getElementById('absenBody').addEventListener('click', function(e){
    if(e.target.closest('.remove-row')){
        e.target.closest('tr').remove();
        let rows = document.querySelectorAll('#absenBody tr');
        rows.forEach((row, index) => {
            row.querySelector('.form-control-no').value = index + 1;
        });
    }
});
</script>
@endsection
