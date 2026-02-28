@extends('layouts.app')

@section('title', 'Absensi MI 3A')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Absensi Mahasiswa</h2>
        <p class="text-muted small">Kelas: <span class="badge bg-primary-subtle text-primary fw-bold">MI 3A</span> | Tanggal: {{ date('d M Y') }}</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
        <i class="fas fa-check-circle me-2"></i>
        <div class="fw-bold small">{{ session('success') }}</div>
    </div>
@endif

@if($mahasiswa->isEmpty())
    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
        <div class="card-body">
            <i class="fas fa-user-slash fa-3x text-muted opacity-25 mb-3"></i>
            <h5 class="text-muted">Belum ada mahasiswa di kelas MI 3A.</h5>
        </div>
    </div>
@else
    <form action="{{ route('dosen.storeAbsen') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="py-3 ps-4 text-muted small fw-bold" style="width: 70px;">NO</th>
                                <th class="text-muted small fw-bold">NIM</th>
                                <th class="text-muted small fw-bold">NAMA LENGKAP</th>
                                <th class="text-muted small fw-bold text-center" style="width: 250px;">STATUS KEHADIRAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahasiswa as $key => $mhs)
                                <tr class="border-bottom">
                                    <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                                    <td class="fw-medium text-secondary">{{ $mhs->nim ?? '-' }}</td>
                                    <td class="fw-bold text-dark">{{ $mhs->name }}</td>
                                    <td class="pe-4">
                                        <input type="hidden" name="mahasiswa_id[]" value="{{ $mhs->id }}">
                                        <select name="status[]" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 fw-semibold text-navy shadow-sm-focus" required>
                                            <option value="Hadir" class="text-success">Hadir</option>
                                            <option value="Izin" class="text-warning">Izin</option>
                                            <option value="Sakit" class="text-info">Sakit</option>
                                            <option value="Alpa" class="text-danger">Alpa</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Footer Card untuk Tombol Simpan --}}
            <div class="card-footer bg-white border-0 p-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-navy-grad px-5 py-2 rounded-pill shadow-sm fw-bold">
                    <i class="fas fa-save me-2"></i> Simpan Absensi
                </button>
            </div>
        </div>
    </form>
@endif

<style>
    /* Styling Tabel & Hover */
    .table-hover tbody tr:hover {
        background-color: #fbfcfe;
    }

    /* Select Dropdown Styling */
    .form-select {
        height: 38px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .form-select:focus {
        background-color: #ffffff !important;
        border: 1px solid #647ACB !important;
        box-shadow: 0 0 0 4px rgba(100, 122, 203, 0.1);
    }

    /* Navy Gradient Button */
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white;
        border: none;
        transition: all 0.3s;
    }
    .btn-navy-grad:hover {
        background: #415A77;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 27, 42, 0.2);
    }

    /* Badge Custom */
    .bg-primary-subtle { background-color: #eff6ff !important; }
    .text-navy { color: #0D1B2A; }

    /* Menghilangkan garis table terakhir */
    .table tr:last-child {
        border-bottom: none !important;
    }
</style>
@endsection
