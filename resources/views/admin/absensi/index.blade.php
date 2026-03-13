@extends('layouts.app')
@section('title', 'Monitoring Absensi')

@section('content')
<div class="container py-3 animate__animated animate__fadeIn">
    {{-- 1. HEADER: Lebih Ramping --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Monitoring Absensi</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge-prodi">
                    <i class="fas fa-university me-1"></i> {{ strtoupper($prodiAktif) }}
                </span>
                <p class="text-muted mb-0" style="font-size: 0.75rem;">Pilih kelas untuk melihat riwayat.</p>
            </div>
        </div>

        {{-- 2. FILTER PRODI: Lebih Rapi & Compact --}}
        <div class="col-md-6 mt-3 mt-md-0">
            <form action="{{ route('admin.absensi.index') }}" method="GET" id="formFilter" class="d-flex justify-content-md-end">
                <div class="search-compact shadow-sm bg-white">
                    <i class="fas fa-filter me-2 text-muted" style="font-size: 11px;"></i>
                    <select name="prodi" id="prodiSelect" class="select-clean" onchange="this.form.submit()">
                        @foreach($prodis as $p)
                            <option value="{{ $p->nama }}" {{ $prodiAktif == $p->nama ? 'selected' : '' }}>
                                {{ strtoupper($p->nama) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. GRID KELAS: Tanpa Kata 'Rekap' --}}
    <div class="row g-3">
        @php
            $alias = "CL";
            $p = strtoupper($prodiAktif);
            if (str_contains($p, 'MANAJEMEN INFORMATIKA')) $alias = "MI";
            elseif (str_contains($p, 'TEKNOLOGI REKAYASA PERANGKAT LUNAK')) $alias = "TRPL";
            elseif (str_contains($p, 'TEKNIK KOMPUTER')) $alias = "TK";
            elseif (str_contains($p, 'ANIMASI')) $alias = "ANM";
            else $alias = collect(explode(' ', $p))->map(fn($w) => $w[0])->implode('');

            $tingkat = ['1', '2', '3'];
            $sub = ['A', 'B', 'C'];
            $indexDelay = 0;
        @endphp

        @foreach($tingkat as $t)
            @foreach($sub as $s)
                @php
                    $namaKelas = $alias . ' ' . $t . $s;
                    $indexDelay++;
                @endphp
                <div class="col-xl-2 col-lg-3 col-md-4 col-6 animate-up" style="animation-delay: {{ $indexDelay * 0.03 }}s">
                    {{-- Card sekarang berfungsi sebagai link utuh --}}
                    <a href="{{ route('admin.absensi.rekap', ['kelas' => $namaKelas, 'prodi' => $prodiAktif]) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 class-card-modern transition-all">
                            <div class="card-body p-3 text-center">
                                <div class="icon-circle mb-2">
                                    <i class="fas fa-users text-navy"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $namaKelas }}</h6>
                                <span class="text-muted" style="font-size: 0.65rem;">Tingkat {{ $t }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endforeach
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

    /* Header & Badges */
    .badge-prodi {
        background: #eef2f7;
        color: #344767;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid #dee2e6;
    }

    /* Filter Compact */
    .search-compact {
        border: 1px solid #dee2e6;
        padding: 4px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
    }
    .select-clean {
        border: none;
        outline: none;
        font-size: 12px;
        font-weight: 600;
        color: #344767;
        cursor: pointer;
        background: transparent;
    }

    /* Modern Class Card */
    .class-card-modern {
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid transparent !important;
        transition: all 0.2s ease-in-out;
    }
    .class-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05) !important;
        border-color: #344767 !important;
        background: #fcfdfe;
    }

    .icon-circle {
        width: 36px;
        height: 36px;
        background-color: #f1f3f5;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: 0.2s;
        font-size: 14px;
    }
    .text-navy { color: #344767; }
    .class-card-modern:hover .icon-circle {
        background-color: #344767;
    }
    .class-card-modern:hover .icon-circle i {
        color: white !important;
    }

    /* Animations */
    .animate-up { animation: fadeInUp 0.3s ease forwards; opacity: 0; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
