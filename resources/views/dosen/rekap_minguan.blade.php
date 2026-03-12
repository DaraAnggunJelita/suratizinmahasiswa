@extends('layouts.app')

@section('title', 'Rekap Mingguan ' . $kelas)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Rekap Matriks {{ $kelas }}</h2>
        <p class="text-muted small">Laporan absensi mahasiswa per pertemuan (Mingguan)</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dosen.absensi', $kelas) }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm small">
            <i class="fas fa-list me-1"></i> Mode List
        </a>
        <button onclick="window.print()" class="btn btn-navy-grad rounded-pill px-4 shadow-sm">
            <i class="fas fa-print me-1"></i> Cetak Laporan
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0 text-center">
            <thead class="bg-light">
                <tr>
                    <th rowspan="2" class="py-3 ps-4 text-start align-middle" style="min-width: 200px; color: #64748b; font-size: 0.75rem;">NAMA MAHASISWA</th>
                    <th colspan="{{ $daftarPertemuan->count() }}" class="small fw-bold py-2 text-muted">PERTEMUAN / TANGGAL</th>
                    <th rowspan="2" class="align-middle bg-primary text-white small" style="width: 60px;">%</th>
                </tr>
                <tr>
                    @foreach($daftarPertemuan as $index => $tgl)
                        <th class="small p-2" style="min-width: 50px;">
                            <span class="d-block text-primary">P-{{ $index + 1 }}</span>
                            <div style="font-size: 0.6rem;" class="text-muted fw-normal">{{ \Carbon\Carbon::parse($tgl->tanggal)->format('d/m') }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa as $mhs)
                <tr>
                    <td class="text-start ps-4 fw-bold text-dark small">{{ strtoupper($mhs->nama) }}</td>

                    @php $hadirCount = 0; @endphp
                    @foreach($daftarPertemuan as $tgl)
                        @php
                            // Cari data absen mahasiswa ini di tanggal ini
                            $absen = $absensiRaw->where('nim_mahasiswa', $mhs->nim)->where('tanggal', $tgl->tanggal)->first();
                            $status = $absen ? substr($absen->status, 0, 1) : '-';
                            if($absen && $absen->status == 'Hadir') $hadirCount++;

                            $color = match($status) {
                                'H' => 'text-success',
                                'I' => 'text-warning',
                                'S' => 'text-info',
                                'A' => 'text-danger',
                                default => 'text-muted opacity-25'
                            };
                        @endphp
                        <td class="fw-bold {{ $color }} small">{{ $status }}</td>
                    @endforeach

                    @php
                        $totalPertemuan = $daftarPertemuan->count();
                        $persentase = $totalPertemuan > 0 ? round(($hadirCount / $totalPertemuan) * 100) : 0;
                    @endphp
                    <td class="fw-bold small {{ $persentase < 75 ? 'text-danger' : 'text-dark' }} bg-light">
                        {{ $persentase }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 p-3 bg-white rounded-4 shadow-sm d-flex gap-4 justify-content-center x-small fw-bold border">
    <span class="text-success"><i class="fas fa-check-circle me-1"></i> H: HADIR</span>
    <span class="text-warning"><i class="fas fa-info-circle me-1"></i> I: IZIN</span>
    <span class="text-info"><i class="fas fa-plus-circle me-1"></i> S: SAKIT</span>
    <span class="text-danger"><i class="fas fa-times-circle me-1"></i> A: ALFA</span>
</div>

<style>
    .btn-navy-grad {
        background: linear-gradient(90deg, #0D1B2A 0%, #1B263B 100%);
        color: white; border: none; transition: 0.3s;
    }
    .btn-navy-grad:hover { background: #415A77; color: white; }
    .x-small { font-size: 0.75rem; }
    @media print {
        .btn, .mb-4, .d-flex.gap-2 { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd; }
    }
</style>
@endsection
