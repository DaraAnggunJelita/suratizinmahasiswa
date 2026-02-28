<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIMAH</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        /* Card Styling */
        .card {
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(13, 27, 42, 0.1);
            overflow: hidden;
            background: #ffffff; /* Mengubah background card jadi putih agar input lebih terlihat profesional */
            position: relative;
        }

        .card-header-navy {
            background: linear-gradient(135deg, #0D1B2A 0%, #1B263B 100%);
            padding: 2.5rem 1.5rem;
            text-align: center;
            color: #fff;
        }

        .card-header-navy i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #647ACB;
            display: block;
        }

        .card-header-navy h4 {
            font-weight: 700;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 2rem 2.5rem;
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: #647ACB;
            box-shadow: 0 0 0 4px rgba(100, 122, 203, 0.1);
        }

        /* Button */
        .btn-register {
            background: linear-gradient(90deg, #0D1B2A, #1B263B);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 27, 42, 0.2);
            color: #fff;
            background: #415A77;
        }

        /* Login Link */
        .login-link {
            font-weight: 600;
            color: #647ACB;
            text-decoration: none;
        }

        .login-link:hover {
            color: #0D1B2A;
            text-decoration: underline;
        }

        /* Decoration */
        .bg-decoration {
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(100,122,203,0.1) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
        }

        @media (max-width: 576px) {
            .card-body { padding: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="bg-decoration"></div>
                <div class="card-header-navy">
                    <i class="fas fa-user-circle"></i>
                    <h4>Daftar Akun Baru</h4>
                    <p class="small opacity-75 mb-0">Silakan lengkapi data mahasiswa Anda</p>
                </div>

                <div class="card-body">
                    {{-- Error Validation --}}
                    @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nama sesuai KTM" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Mahasiswa</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="nama@student.com" required>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nim_nip" class="form-label">NIM</label>
                                <input type="text" id="nim_nip" name="nim_nip" class="form-control" placeholder="Masukkan NIM Anda" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="prodi" class="form-label">Program Studi</label>
                            <select id="prodi" name="prodi" class="form-select" required>
                                <option value="" disabled selected>Pilih Program Studi</option>
                                <option value="Manajemen Informatika">Manajemen Informatika</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select id="kelas" name="kelas" class="form-select" required>
                                <option value="" disabled selected>Pilih Kelas</option>
                                <option value="MI 3A">MI 3A</option>
                                <option value="MI 3B">MI 3B</option>
                                <option value="MI 3C">MI 3C</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="********" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-register w-100 mt-2">
                            <i class="fas fa-paper-plane me-2"></i> Buat Akun
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="small text-muted">Sudah punya akun? <a href="{{ route('login.form') }}" class="login-link">Login Sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
