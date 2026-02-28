@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        {{-- Header Profil --}}
        <div class="mb-4 text-center">
            <div class="mx-auto mb-3 bg-navy-gradient rounded-circle d-flex align-items-center justify-content-center shadow"
                 style="width: 80px; height: 80px; color: white; font-size: 2rem;">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2 class="fw-bold text-dark mb-1">Profil Saya</h2>
            <p class="text-muted small">Kelola informasi akun dan keamanan password Anda</p>
        </div>

        {{-- Profile Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5">

                {{-- Alert Notifications --}}
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div class="small fw-bold">{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error') || $errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                            <ul class="mb-0 small fw-bold">
                                @if(session('error')) <li>{{ session('error') }}</li> @endif
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Profile Form --}}
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-dark">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control bg-light border-start-0 ps-0" name="name"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label small fw-bold text-dark">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control bg-light border-start-0 ps-0" name="email"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-4" style="background-color: #f8fafc; border: 1px dashed #e2e8f0;">
                        <p class="small fw-bold text-primary mb-3"><i class="fas fa-key me-1"></i> Ganti Password</p>

                        <div class="mb-3">
                            <label for="password" class="form-label x-small fw-bold text-muted">Password Baru</label>
                            <input type="password" class="form-control border-0 shadow-sm" name="password" placeholder="Kosongkan jika tidak diganti">
                        </div>

                        <div class="mb-0">
                            <label for="password_confirmation" class="form-label x-small fw-bold text-muted">Konfirmasi Password</label>
                            <input type="password" class="form-control border-0 shadow-sm" name="password_confirmation" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy-action w-100 rounded-3 py-2 shadow-sm">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; }

    .bg-navy-gradient {
        background: linear-gradient(135deg, #0D1B2A 0%, #1B263B 100%);
    }

    .form-control {
        font-size: 0.9rem;
        padding: 10px 15px;
        border-radius: 8px;
    }

    .form-control:focus {
        background-color: #fff !important;
        border-color: #647ACB;
        box-shadow: 0 0 0 0.25rem rgba(100, 122, 203, 0.1);
    }

    .input-group-text {
        border-radius: 8px;
        border-color: #dee2e6;
    }

    .btn-navy-action {
        background: linear-gradient(90deg, #647ACB, #415A77);
        color: white;
        border: none;
        font-weight: 700;
        transition: all 0.3s;
    }

    .btn-navy-action:hover {
        background: #0D1B2A;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection
