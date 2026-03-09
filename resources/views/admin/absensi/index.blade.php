@extends('layouts.app')
@section('title', 'Monitoring Absensi')

@section('content')
<div class="container animate__animated animate__fadeIn">
    {{-- HEADER --}}
    <div class="row mb-3 align-items-center">
        <div class="col">
            <h4 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Monitoring Absensi</h4>
            <p class="text-muted x-small mb-0">Pilih kelas untuk meninjau riwayat kehadiran.</p>
        </div>
    </div>

    {{-- GRID KELAS --}}
    <div class="row g-3">
        @foreach($daftar_kelas as $kelas)
        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100 class-card transition-all">
                <div class="card-body p-3 text-center">
                    {{-- Ikon Mini --}}
                    <div class="mx-auto mb-2 d-flex align-items-center justify-content-center icon-box">
                        <i class="fas fa-graduation-cap text-primary"></i>
                    </div>

                    <h6 class="fw-bold text-dark mb-1">{{ $kelas }}</h6>
                    <p class="text-muted mb-3 x-small">Manajemen Informatika</p>

                    <a href="{{ route('admin.absensi.rekap', $kelas) }}"
                       class="btn btn-primary-compact rounded-pill w-100 x-small fw-bold">
                        Rekap <i class="fas fa-chevron-right ms-1" style="font-size: 0.6rem;"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    /* Global Styles */
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.7rem; }
    .transition-all { transition: all 0.25s ease-in-out; }

    /* Class Card */
    .class-card {
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.02) !important;
    }

    .class-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05) !important;
        border-color: #2563eb !important;
    }

    /* Icon Box */
    .icon-box {
        width: 40px;
        height: 40px;
        background-color: #f0f7ff;
        border-radius: 10px;
        font-size: 1.1rem;
    }

    /* Primary Compact Button */
    .btn-primary-compact {
        background: #2563eb;
        color: white;
        border: none;
        padding: 5px 10px;
        transition: 0.2s;
    }

    .btn-primary-compact:hover {
        background: #1e40af;
        color: white;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
    }

    /* Card Animations Delay */
    @foreach($daftar_kelas as $index => $kelas)
    .col-xl-2:nth-child({{ $index + 1 }}) .class-card {
        animation: fadeInUp 0.3s ease forwards;
        animation-delay: {{ $index * 0.05 }}s;
        opacity: 0;
    }
    @endforeach

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
