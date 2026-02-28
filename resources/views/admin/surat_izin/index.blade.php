@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h2 class="fw-bold text-dark mb-1">Dashboard Admin</h2>
    <p class="text-muted small">Kelola data dan verifikasi surat izin dengan mudah.</p>
</div>

{{-- Statistik Ringkasan --}}
<div class="row mb-4 g-3">
    @php
        $cards = [
            ['title' => 'Total Surat', 'count' => $totalSurat ?? 0, 'color' => '#6c757d'],
            ['title' => 'Menunggu', 'count' => $menunggu ?? 0, 'color' => '#ffc107'],
            ['title' => 'Disetujui', 'count' => $disetujui ?? 0, 'color' => '#198754'],
            ['title' => 'Ditolak', 'count' => $ditolak ?? 0, 'color' => '#dc3545'],
        ];
    @endphp

    @foreach($cards as $card)
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 h-100"
                 style="border-left: 5px solid {{ $card['color'] }} !important; border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted fw-bold small text-uppercase mb-2">{{ $card['title'] }}</h6>
                    <p class="display-6 fw-bold mb-0" style="color: {{ $card['color'] }}">{{ $card['count'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Alert Success --}}
@if(session('success'))
<div class="alert alert-success border-0 shadow-sm rounded-3">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
</div>
@endif

{{-- Tabel Surat Izin --}}
<div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small fw-bold">
                        <th class="py-3 ps-4">#</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Kelas</th>
                        <th>Jenis Izin</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratIzins as $index => $surat)
                        <tr class="border-bottom">
                            <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark">{{ optional($surat->user)->name ?? '-' }}</td>
                            <td class="text-secondary small">{{ optional($surat->user)->nim_nip ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border-0 fw-normal px-2 py-1">{{ optional($surat->user)->kelas ?? '-' }}</span></td>
                            <td><span class="text-primary fw-semibold">{{ ucfirst($surat->jenis_izin) }}</span></td>
                            <td class="text-muted small">{{ $surat->tanggal_mulai }}</td>
                            <td class="text-muted small">{{ $surat->tanggal_selesai }}</td>
                            <td>
                                @if($surat->status == 'menunggu')
                                    <span class="badge bg-warning text-dark px-3 rounded-pill" style="font-size: 0.75rem;">Menunggu</span>
                                @elseif($surat->status == 'disetujui')
                                    <span class="badge bg-success px-3 rounded-pill" style="font-size: 0.75rem;">Disetujui</span>
                                @else
                                    <span class="badge bg-danger px-3 rounded-pill" style="font-size: 0.75rem;">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($surat->bukti_file)
                                    <a href="{{ asset('storage/'.$surat->bukti_file) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                       <i class="fas fa-eye me-1"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3">
                                <form action="{{ url('/admin/surat-izin/'.$surat->id.'/verifikasi') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="d-flex flex-column gap-1 mx-auto" style="max-width: 100px;">
                                        <button type="submit"
                                                name="status"
                                                value="disetujui"
                                                class="btn btn-sm btn-success fw-bold rounded-2 py-1"
                                                style="font-size: 0.7rem;">
                                            Disetujui
                                        </button>

                                        <button type="submit"
                                                name="status"
                                                value="ditolak"
                                                class="btn btn-sm btn-danger fw-bold rounded-2 py-1"
                                                style="font-size: 0.7rem;">
                                            Ditolak
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted italic">
                                Belum ada surat izin yang diajukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Halus-halus visual */
    .table-hover tbody tr:hover {
        background-color: #fbfcfe;
    }
    .badge {
        font-weight: 500;
    }
    .card {
        transition: transform 0.2s ease;
    }
    /* Menjaga teks agar tidak berantakan di layar kecil */
    th {
        white-space: nowrap;
    }
</style>
@endsection
