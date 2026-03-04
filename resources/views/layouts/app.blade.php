<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIMAH') - Sistem Izin Mahasiswa</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --navy-dark: #0D1B2A;
            --navy-medium: #1B263B;
            --navy-light: #415A77;
            --accent-blue: #647ACB;
            --body-bg: #F0F2F5;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            margin: 0;
            overflow-x: hidden;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--navy-dark) 0%, var(--navy-medium) 100%);
            padding: 30px 20px;
            z-index: 1050;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px 30px;
            font-size: 1.8rem;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 20px;
        }
        .sidebar-brand i { color: var(--accent-blue); }

        .sidebar nav .nav-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: var(--navy-light);
            margin: 20px 10px 10px;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #B0C4DE;
            font-weight: 500;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .sidebar nav a i { font-size: 1.1rem; width: 20px; }

        .sidebar nav a:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #FFFFFF;
        }

        .sidebar nav a.active {
            background: linear-gradient(90deg, var(--accent-blue), var(--navy-light));
            color: #FFFFFF;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(100, 122, 203, 0.3);
        }

        .sidebar-footer {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        /* ================= TOPBAR ================= */
        .topbar {
            margin-left: 260px;
            height: 75px;
            background: #FFFFFF;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 35px;
            position: fixed;
            top: 0; right: 0; left: 0;
            z-index: 1000;
            border-bottom: 1px solid #E2E8F0;
        }

        .user-greeting {
            color: var(--navy-dark);
            font-weight: 600;
            font-size: 1rem;
        }

        .logout-btn {
            background: var(--navy-dark);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: var(--accent-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* ================= CONTENT AREA ================= */
        .content {
            margin-left: 260px;
            padding: 110px 35px 35px;
            min-height: 100vh;
        }

        .card-content {
            background: #ffffff;
            border-radius: 20px;
            padding: 35px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }

        @media (max-width: 992px) {
            .sidebar { left: -260px; transition: 0.3s; }
            .sidebar.show { left: 0; }
            .topbar, .content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>

<body>

<div class="sidebar" id="sidebar">
    <div>
        <div class="sidebar-brand">
            <i class="fas fa-envelope-open-text"></i> SIMAH
        </div>

        <nav>
            @auth
                <div class="nav-label">Menu Utama</div>

                {{-- DASHBOARD BERDASARKAN ROLE --}}
                @if(Auth::user()->role == 'mahasiswa')
                    <a href="{{ route('mahasiswa.surat_izin.index') }}" class="{{ request()->is('mahasiswa*') ? 'active' : '' }}">
                        <i class="fas fa-columns"></i> Dashboard
                    </a>
                @elseif(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.surat_izin.index') }}" class="{{ request()->is('admin/surat-izin*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Dashboard Admin
                    </a>
                @elseif(Auth::user()->role == 'dosen')
                    <a href="{{ route('dosen.dashboard') }}" class="{{ request()->is('dosen/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-user"></i> Dashboard Dosen
                    </a>
                @endif

                {{-- MENU JADWAL KULIAH (BARU) --}}
                <div class="nav-label">Akademik</div>
                <a href="{{ route('jadwal.index') }}" class="{{ request()->is('jadwal*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Jadwal Kuliah
                </a>

                {{-- FITUR KHUSUS ADMIN --}}
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-user-gear"></i> Manajemen User
                    </a>
                @endif

                {{-- FITUR KHUSUS DOSEN --}}
                @if(Auth::user()->role == 'dosen')
                    <div class="nav-label">Monitoring Absensi</div>
                    @foreach(['MI 3A', 'MI 3B', 'MI 3C'] as $kelas)
                        <a href="{{ route('dosen.absensi', $kelas) }}" class="{{ request()->is('dosen/absensi/'.$kelas) ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i> Kelas {{ $kelas }}
                        </a>
                    @endforeach
                @endif

                <div class="nav-label">Pengaturan</div>
                <a href="{{ route('profile.index') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Profil Saya
                </a>
            @else
                <a href="{{ route('login.form') }}"><i class="fas fa-lock"></i> Login</a>
            @endauth
        </nav>
    </div>

    <div class="sidebar-footer">
        &copy; {{ date('Y') }} SIMAH Team
    </div>
</div>

@auth
<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none text-dark p-0" id="sidebarToggle">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <div class="user-greeting">
            <span class="d-none d-sm-inline text-muted fw-normal">Selamat datang,</span>
            {{ Auth::user()->name }}
            @if(Auth::user()->role == 'mahasiswa')
                <span class="badge bg-primary-subtle text-primary x-small ms-1">{{ Auth::user()->kelas }}</span>
            @endif
        </div>
    </div>

    <div class="topbar-actions">
        <div class="badge bg-light text-primary px-3 py-2 rounded-pill d-none d-md-block border fw-semibold">
            <i class="fas fa-user-shield me-1"></i> {{ ucfirst(Auth::user()->role) }}
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-power-off"></i> Keluar
            </button>
        </form>
    </div>
</div>
@endauth

<div class="content {{ !Auth::check() ? 'm-0 p-0' : '' }}">
    <div class="card-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    }
</script>
@stack('scripts')
</body>
</html>
