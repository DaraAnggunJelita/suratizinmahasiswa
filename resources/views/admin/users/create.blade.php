@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        {{-- Header Form --}}
        <div class="mb-4 d-flex align-items-center">
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light border rounded-circle me-3 shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold text-dark mb-0">Tambah Dosen</h2>
                <p class="text-muted small mb-0">Masukkan data dosen baru ke dalam sistem SIMAH</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-lg-5">

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <ul class="mb-0 small fw-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label fw-bold text-dark small">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" id="name" class="form-control bg-light border-start-0 ps-0" placeholder="Contoh: Dr. Budi Santoso" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="nim_nip" class="form-label fw-bold text-dark small">NIP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-id-badge"></i></span>
                                <input type="text" name="nim_nip" id="nim_nip" class="form-control bg-light border-start-0 ps-0" placeholder="Masukkan NIP resmi" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold text-dark small">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control bg-light border-start-0 ps-0" placeholder="email@kampus.ac.id" required>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label fw-bold text-dark small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 ps-0" placeholder="Minimal 8 karakter" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label fw-bold text-dark small">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-shield-alt"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-start-0 ps-0" placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4 fw-bold text-muted border">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-navy px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Data Dosen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling khusus Form */
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 0.9rem;
        border-color: #e2e8f0;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #647ACB;
        background-color: #fff !important;
    }

    .input-group-text {
        border-radius: 8px;
        border-color: #e2e8f0;
        font-size: 0.9rem;
    }

    /* Tombol Navy Aksen */
    .btn-navy {
        background: linear-gradient(90deg, #0D1B2A, #1B263B);
        color: #fff;
        border: none;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .btn-navy:hover {
        background: #415A77;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 27, 42, 0.3);
    }

    .btn-light:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }
</style>
@endsection
