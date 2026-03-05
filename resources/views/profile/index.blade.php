@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-lg-6 col-md-8">
        {{-- Header Profil --}}
        <div class="text-center mb-5">
            <div class="profile-avatar-wrapper mx-auto mb-3">
                <div class="profile-avatar shadow-lg d-flex align-items-center justify-content-center">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="avatar-badge" title="Status Akun Aktif">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <h2 class="fw-800 text-dark mb-1" style="letter-spacing: -1px;">Profil Saya</h2>
            <p class="text-muted small px-4">Kelola informasi identitas dan tingkatkan keamanan akun Anda secara berkala.</p>
        </div>

        {{-- Profile Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 p-md-5">

                {{-- Alert Notifications --}}
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 animate__animated animate__shakeX" role="alert" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-check-circle me-3 fs-5"></i>
                        <div class="small fw-bold">{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error') || $errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 animate__animated animate__headShake" role="alert" style="background: #fef2f2; color: #dc2626;">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle me-3 mt-1 fs-5"></i>
                            <ul class="mb-0 small fw-bold list-unstyled">
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

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <div class="input-group-custom">
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control-custom" name="name"
                                       placeholder="Masukkan nama lengkap" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Alamat Email</label>
                            <div class="input-group-custom">
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control-custom" name="email"
                                       placeholder="nama@kampus.id" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="password-section p-4 rounded-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-box-sm me-3">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h6 class="mb-0 fw-800 text-dark">Keamanan Password</h6>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label x-small-label">Password Baru</label>
                                    <input type="password" class="form-control-custom shadow-sm border-0" name="password"
                                           placeholder="Kosongkan jika tidak ingin ganti">
                                </div>

                                <div class="mb-0">
                                    <label class="form-label x-small-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control-custom shadow-sm border-0" name="password_confirmation"
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn-modern-primary w-100">
                                <span>Simpan Perubahan Akun</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted x-small-label">Terdaftar sejak: <span class="text-dark fw-bold">{{ $user->created_at->format('d M Y') }}</span></p>
        </div>
    </div>
</div>

<style>
    /* Typography & General */
    .fw-800 { font-weight: 800; }
    .x-small-label { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.2px; font-weight: 700; color: #94a3b8; }

    /* Avatar Design */
    .profile-avatar-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #2563eb, #1e3a8a);
        color: white;
        font-size: 3rem;
        border-radius: 35% 65% 61% 39% / 37% 33% 67% 63%; /* Organic shape */
    }

    .avatar-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #10b981;
        color: white;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        border: 3px solid #ffffff;
    }

    /* Form Customization */
    .form-label-custom {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .input-group-custom {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 12px;
        padding: 4px 15px;
        transition: 0.3s;
        border: 2px solid transparent;
    }

    .input-group-custom:focus-within {
        background: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
    }

    .input-icon {
        color: #64748b;
        margin-right: 12px;
        font-size: 1rem;
    }

    .form-control-custom {
        border: none;
        background: transparent;
        width: 100%;
        padding: 10px 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        outline: none !important;
    }

    /* Password Section */
    .password-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .icon-box-sm {
        width: 32px;
        height: 32px;
        background: #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    /* Button Design */
    .btn-modern-primary {
        background: #2563eb;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .btn-modern-primary:hover {
        background: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
    }
</style>
@endsection
