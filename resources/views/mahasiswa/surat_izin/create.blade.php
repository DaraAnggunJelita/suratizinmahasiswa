@extends('layouts.app')

@section('title', 'Buat Surat Izin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Buat Surat Izin</h2>
                <p class="text-muted">Lengkapi formulir di bawah ini untuk mengajukan izin ketidakhadiran.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-2">
                <div class="card-body p-4">

                    {{-- Error Validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <ul class="mb-0 small fw-bold">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div class="small fw-bold">{{ session('success') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('mahasiswa.surat_izin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- Jenis Izin --}}
                            <div class="col-md-12 mb-3">
                                <label for="jenis_izin" class="form-label fw-bold text-dark small">JENIS IZIN</label>
                                <select name="jenis_izin" id="jenis_izin" class="form-select bg-light border-0 py-2 rounded-3" required>
                                    <option value="" disabled selected>Pilih jenis izin...</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="izin">Izin</option>
                                </select>
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label fw-bold text-dark small">TANGGAL MULAI</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 rounded-start-3"><i class="far fa-calendar-alt text-muted"></i></span>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control bg-light border-0 py-2 rounded-end-3" required>
                                </div>
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label fw-bold text-dark small">TANGGAL SELESAI</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 rounded-start-3"><i class="far fa-calendar-check text-muted"></i></span>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control bg-light border-0 py-2 rounded-end-3" required>
                                </div>
                            </div>

                            {{-- Alasan --}}
                            <div class="col-md-12 mb-3">
                                <label for="alasan" class="form-label fw-bold text-dark small">ALASAN / KETERANGAN</label>
                                <textarea name="alasan" id="alasan" class="form-control bg-light border-0 rounded-3" rows="4" placeholder="Jelaskan alasan detail izin Anda..." required></textarea>
                            </div>

                            {{-- Bukti File --}}
                            <div class="col-md-12 mb-4">
                                <label for="bukti_file" class="form-label fw-bold text-dark small">DOKUMEN PENDUKUNG (OPSIONAL)</label>
                                <div class="p-3 border-2 border-dashed rounded-4 text-center bg-light position-relative">
                                    <input type="file" name="bukti_file" id="bukti_file" class="form-control opacity-0 position-absolute w-100 h-100 top-0 start-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png">
                                    <div id="file-label">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-primary opacity-50 mb-2"></i>
                                        <p class="mb-0 small text-muted">Klik untuk upload atau drag PDF/JPG/PNG</p>
                                        <small class="x-small text-muted">Maksimal ukuran file: 2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-navy-grad px-5 rounded-pill shadow-sm fw-bold">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                            </button>
                            <a href="{{ route('mahasiswa.surat_izin.index') }}" class="btn btn-light border px-4 rounded-pill fw-bold text-muted">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light { background-color: #f8fafc !important; }

    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white; border: none; transition: 0.3s;
    }
    .btn-navy-grad:hover {
        background: #415A77; color: white; transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 27, 42, 0.2);
    }

    .border-dashed { border: 2px dashed #e2e8f0; transition: 0.3s; }
    .border-dashed:hover { border-color: #647ACB; background-color: #f1f5f9 !important; }

    .cursor-pointer { cursor: pointer; }
    .x-small { font-size: 0.7rem; }

    /* Input focus styling */
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(100, 122, 203, 0.1);
        border: 1px solid #647ACB !important;
    }
</style>

<script>
    // Script sederhana untuk menampilkan nama file yang dipilih
    document.getElementById('bukti_file').addEventListener('change', function(e) {
        let fileName = e.target.files[0].name;
        document.getElementById('file-label').innerHTML = `
            <i class="fas fa-file-alt fa-2x text-success mb-2"></i>
            <p class="mb-0 small fw-bold text-dark">${fileName}</p>
            <small class="x-small text-muted">File siap diupload</small>
        `;
    });
</script>
@endsection
