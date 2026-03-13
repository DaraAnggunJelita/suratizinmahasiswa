<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIMAH') - Sistem Izin Mahasiswa</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary: #2563eb;
            --sidebar-bg: #0f172a;
            --body-bg: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            margin: 0;
        }

        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 280px;
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            padding: 20px 15px;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px 25px;
            font-size: 1.4rem;
            font-weight: 800;
            color: #ffffff;
        }

        .sidebar-nav-container {
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
        }

        .nav-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            color: #475569;
            margin: 15px 15px 8px;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            color: #94a3b8;
            font-weight: 600;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 2px;
            transition: 0.2s;
            font-size: 0.88rem;
        }

        .sidebar nav a:hover { background: rgba(255, 255, 255, 0.05); color: #ffffff; }
        .sidebar nav a.active { background: rgba(37, 99, 235, 0.15); color: #ffffff; border-left: 3px solid var(--primary); }

        .nav-prodi {
            font-size: 0.8rem !important;
            padding-left: 20px !important;
        }

        .topbar {
            margin-left: 280px;
            height: 70px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            position: fixed;
            top: 0; right: 0; left: 0;
            z-index: 1000;
            border-bottom: 1px solid #e2e8f0;
        }

        .content { margin-left: 280px; padding: 95px 30px 30px; min-height: 100vh; }

        @media (max-width: 992px) {
            .sidebar { left: -280px; }
            .sidebar.show { left: 0; }
            .topbar, .content { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-envelope-open-text text-primary"></i> SIMAH
    </div>

    <div class="sidebar-nav-container">
        <nav>
            @auth
                <div class="nav-label">MENU UTAMA</div>
                @if(Auth::user()->role == 'dosen')
                    <a href="{{ route('dosen.dashboard') }}" class="{{ request()->is('dosen/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> Dashboard Dosen
                    </a>
                @elseif(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('mahasiswa.surat_izin.index') }}" class="{{ request()->is('mahasiswa/surat-izin*') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> Dashboard Mahasiswa
                    </a>
                @endif

                <div class="nav-label">AKADEMIK</div>
                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'dosen')
                    <a href="{{ route('admin.pengumuman.index') }}" class="{{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i> Pengumuman
                    </a>
                @endif

                <a href="{{ route('jadwal.index') }}" class="{{ request()->is('jadwal*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Jadwal Kuliah
                </a>

                {{-- MONITORING KHUSUS DOSEN --}}
                @if(Auth::user()->role == 'dosen')
                    <div class="nav-label">MONITORING ABSENSI</div>
                    @foreach(\App\Models\Prodi::all() as $prodi)
                        {{-- Menggunakan route dosen.absensi sesuai name yang terdaftar --}}
                    <a href="{{ route('dosen.absensi', ['prodi' => $prodi->nama]) }}"
                               class="nav-prodi {{ request()->query('prodi') == $prodi->nama ? 'active' : '' }}">
                            <i class="fas fa-university" style="font-size: 0.7rem;"></i> {{ strtoupper($prodi->nama) }}
                        </a>
                    @endforeach
                @endif

                <div class="nav-label">SISTEM</div>
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.absensi.index') }}" class="{{ request()->is('admin/absensi*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i> Monitoring Absensi
                    </a>
                    <a href="{{ route('admin.prodi.index') }}" class="{{ request()->is('admin/prodi*') ? 'active' : '' }}">
                        <i class="fas fa-university"></i> Data Prodi
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i> Manajemen User
                    </a>
                @endif

                <a href="{{ route('profile.index') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Profil Saya
                </a>
            @endauth
        </nav>
    </div>
</div>

@auth
<div class="topbar">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-light d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="fw-semibold text-muted d-none d-md-block" style="font-size: 0.9rem;">
            Sistem Informasi Izin Mahasiswa
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="text-end d-none d-sm-block">
            <div class="fw-bold mb-0" style="font-size: 0.85rem; line-height: 1;">{{ Auth::user()->name }}</div>
            <small class="text-primary fw-bold" style="font-size: 0.7rem;">{{ strtoupper(Auth::user()->role) }}</small>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm rounded-3 px-3">
                <i class="fas fa-power-off me-1"></i> <span class="d-none d-sm-inline">Keluar</span>
            </button>
        </form>
    </div>
</div>
@endauth

<div class="content">
    @yield('content')
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
