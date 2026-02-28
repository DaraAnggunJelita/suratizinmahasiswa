<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAH (Sistem Izin Mahasiswa)</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #1e293b;
            --accent-blue: #3b82f6;
            --soft-bg: #f8fafc;
        }

        body {
            background: radial-gradient(circle at top right, #e2e8f0, #f8fafc);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
        }

        /* Container Styling */
        .login-container {
            max-width: 1000px;
            width: 100%;
            display: flex;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        /* Branding Side (Left) */
        .brand-side {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            padding: 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 45%;
        }

        .brand-side h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .brand-side p {
            color: #cbd5e1;
            font-size: 1rem;
            line-height: 1.6;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }

        .feature-list li {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        /* Form Side (Right) */
        .form-side {
            padding: 50px;
            flex: 1;
            background: white;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h2 {
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        /* Form Inputs */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: transparent;
            border-right: none;
            color: #94a3b8;
        }

        .form-control {
            border-left: none;
            padding: 12px;
            font-size: 0.95rem;
            border-radius: 0 10px 10px 0;
            border-color: #e2e8f0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--accent-blue);
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
        }

        /* Button */
        .btn-login {
            background: var(--primary-dark);
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .brand-side { display: none; }
            .login-container { max-width: 450px; }
            .form-side { padding: 30px; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="brand-side">
        <div class="mb-4">
            <i class="fas fa-file-signature fa-3x text-info"></i>
        </div>
        <h1>SIMAH</h1>
        <p>Sistem Informasi Izin Mahasiswa Digital. Urus perizinan kuliah lebih cepat, transparan, dan terintegrasi.</p>

        <ul class="feature-list">
            <li><i class="fas fa-check-circle text-success"></i> Pengajuan Izin Sakit/Kegiatan</li>
            {{-- <li><i class="fas fa-check-circle text-success"></i> Tracking Status Real-time</li>
            <li><i class="fas fa-check-circle text-success"></i> Notifikasi via WhatsApp/Email</li> --}}
        </ul>
    </div>

    <div class="form-side">
        <div class="login-header text-center text-md-start">
            <h2>Selamat Datang</h2>
            <p class="text-muted">Silakan masuk menggunakan akun akademik Anda</p>
        </div>

        {{-- Pesan Error/Success --}}
        @if(session('error'))
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-4">
                <label for="login_id" class="form-label">Email / NIM / NIP</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" id="login_id" name="login_id" class="form-control" placeholder="Contoh: 2100123" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">Password</label>
                    {{-- <a href="#" class="text-decoration-none" style="font-size: 0.8rem; color: var(--accent-blue);">Lupa Password?</a> --}}
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-4">
                Masuk ke Sistem <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted small">Belum punya akun?
                <a href="{{ route('register.form') }}" class="fw-bold text-decoration-none" style="color: var(--accent-blue);">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
