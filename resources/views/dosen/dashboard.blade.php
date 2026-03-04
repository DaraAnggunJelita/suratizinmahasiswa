@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Selamat Datang, {{ Auth::user()->name }}</h4>
            <p class="text-muted small mb-0">Kelola perizinan mahasiswa dan pantau jadwal mengajar Anda.</p>
        </div>
        <div class="badge bg-white text-dark shadow-sm border px-3 py-2 rounded-pill">
            <i class="fas fa-calendar-day me-1 text-primary"></i> {{ date('l, d F Y') }}
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden card-stat">
                <div class="card-body p-4 border-start border-primary border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-bold text-uppercase small mb-1">Total Surat</h6>
                            <h2 class="fw-bold mb-0" id="count-total">{{ $suratIzin->count() }}</h2>
                        </div>
                        <i class="fas fa-envelope-open-text fa-2x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden card-stat">
                <div class="card-body p-4 border-start border-warning border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-bold text-uppercase small mb-1">Menunggu</h6>
                            <h2 class="fw-bold mb-0 text-warning" id="count-menunggu">{{ $suratIzin->where('status', 'menunggu')->count() }}</h2>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden card-stat">
                <div class="card-body p-4 border-start border-success border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-bold text-uppercase small mb-1">Disetujui</h6>
                            <h2 class="fw-bold mb-0 text-success" id="count-disetujui">{{ $suratIzin->where('status', 'disetujui')->count() }}</h2>
                        </div>
                        <i class="fas fa-check-double fa-2x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden card-stat">
                <div class="card-body p-4 border-start border-danger border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-bold text-uppercase small mb-1">Ditolak</h6>
                            <h2 class="fw-bold mb-0 text-danger" id="count-ditolak">{{ $suratIzin->where('status', 'ditolak')->count() }}</h2>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list me-2 text-primary"></i>Daftar Pengajuan Izin</h5>
                <span class="badge bg-primary-soft text-primary px-3 rounded-pill">Data Real-time</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="table-izin">
                    <thead class="bg-light">
                        <tr class="text-secondary small">
                            <th class="ps-4 py-3">MAHASISWA</th>
                            <th>KELAS</th>
                            <th>JENIS IZIN</th>
                            <th>PERIODE</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratIzin as $surat)
                        @php $st = strtolower($surat->status); @endphp
                        <tr id="row-{{ $surat->id }}" class="border-bottom">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $surat->user->name ?? '-' }}</div>
                                <div class="text-muted x-small">NIM: {{ $surat->user->nim_nip ?? '-' }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border px-2 py-1">{{ $surat->user->kelas ?? '-' }}</span></td>
                            <td><span class="text-primary fw-semibold">{{ $surat->jenis_izin }}</span></td>
                            <td class="small text-muted">
                                {{ $surat->tanggal_mulai }} <br>
                                <span class="x-small">s/d {{ $surat->tanggal_selesai }}</span>
                            </td>
                            <td class="status-label-cell text-center">
                                @if($st == 'disetujui')
                                    <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill w-100 status-text">Disetujui</span>
                                @elseif($st == 'ditolak')
                                    <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill w-100 status-text">Ditolak</span>
                                @else
                                    <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill w-100 text-dark status-text">Menunggu</span>
                                @endif
                            </td>
                            <td class="text-center px-4">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden" role="group">
                                    <a href="{{ route('dosen.surat_detail', $surat->id) }}" class="btn btn-sm btn-white border" title="Detail">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                    <button onclick="updateStatus({{ $surat->id }}, 'disetujui')"
                                        class="btn btn-sm btn-setujui {{ $st == 'disetujui' ? 'btn-success' : 'btn-outline-success' }}"
                                        {{ $st == 'disetujui' ? 'disabled' : '' }}>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="updateStatus({{ $surat->id }}, 'ditolak')"
                                        class="btn btn-sm btn-tolak {{ $st == 'ditolak' ? 'btn-danger' : 'btn-outline-danger' }}"
                                        {{ $st == 'ditolak' ? 'disabled' : '' }}>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                                <p class="text-muted mt-2">Tidak ada pengajuan izin dari mahasiswa Anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-calendar-alt me-2 text-primary"></i>Jadwal Mengajar</h5>
    <div class="row g-3 mb-4">
        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
        <div class="col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-2 text-center border-bottom-0">
                    <span class="fw-bold small {{ isset($jadwals[$hari]) ? 'text-primary' : 'text-muted opacity-50' }}">{{ $hari }}</span>
                </div>
                <div class="card-body p-2 pt-0">
                    @if(isset($jadwals[$hari]))
                        @foreach($jadwals[$hari] as $j)
                            <div class="p-2 bg-primary-soft rounded-3 mb-2 border-start border-primary border-3">
                                <div class="fw-bold text-dark" style="font-size: 0.75rem; line-height: 1.2;">{{ $j->mata_kuliah }}</div>
                                <div class="text-muted x-small mt-1">
                                    <i class="far fa-clock"></i> {{ substr($j->jam_mulai, 0, 5) }}<br>
                                    <i class="fas fa-map-marker-alt"></i> <strong>{{ $j->kelas }}</strong>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <span class="badge rounded-pill bg-light text-muted x-small fw-normal italic">Libur</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .bg-success-soft { background-color: #ecfdf5; }
    .bg-danger-soft { background-color: #fef2f2; }
    .bg-warning-soft { background-color: #fffbeb; }
    .bg-primary-soft { background-color: #eff6ff; }
    .card-stat { transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.02); }
    .card-stat:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .x-small { font-size: 0.7rem; }
    .btn-white { background: #fff; }
    .table thead th { border: none; letter-spacing: 0.5px; text-transform: uppercase; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateStatus(id, statusBaru) {
    let row = $('#row-' + id);
    let cellStatus = row.find('.status-label-cell');
    let btnSetujui = row.find('.btn-setujui');
    let btnTolak = row.find('.btn-tolak');

    $.ajax({
        url: "{{ url('dosen/surat/verifikasi') }}/" + id,
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            status: statusBaru // Ini mengirim 'disetujui' atau 'ditolak'
        },
        beforeSend: function() {
            row.css('opacity', '0.6');
        },
        success: function(response) {
            row.css('opacity', '1');
            if(response.success) {
                let st = statusBaru.toLowerCase();

                // 1. Update Badge Status
                let badgeHTML = '';
                if(st === 'disetujui') {
                    badgeHTML = '<span class="badge bg-success-soft text-success px-3 py-2 rounded-pill w-100 status-text shadow-sm">Disetujui</span>';
                    btnSetujui.removeClass('btn-outline-success').addClass('btn-success').prop('disabled', true);
                    btnTolak.removeClass('btn-danger').addClass('btn-outline-danger').prop('disabled', false);
                } else if(st === 'ditolak') {
                    badgeHTML = '<span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill w-100 status-text shadow-sm">Ditolak</span>';
                    btnTolak.removeClass('btn-outline-danger').addClass('btn-danger').prop('disabled', true);
                    btnSetujui.removeClass('btn-success').addClass('btn-outline-success').prop('disabled', false);
                }

                cellStatus.fadeOut(200, function() {
                    $(this).html(badgeHTML).fadeIn(200);
                    refreshStatistics();
                });
            }
        },
        error: function() {
            row.css('opacity', '1');
            alert('Gagal memperbarui status. Coba lagi.');
        }
    });
}

function refreshStatistics() {
    let jmlMenunggu = 0, jmlDisetujui = 0, jmlDitolak = 0;

    $('.status-text').each(function() {
        let text = $(this).text().trim().toLowerCase();
        if(text === 'menunggu') jmlMenunggu++;
        if(text === 'disetujui') jmlDisetujui++;
        if(text === 'ditolak') jmlDitolak++;
    });

    updateCounter('#count-menunggu', jmlMenunggu);
    updateCounter('#count-disetujui', jmlDisetujui);
    updateCounter('#count-ditolak', jmlDitolak);
    updateCounter('#count-total', (jmlMenunggu + jmlDisetujui + jmlDitolak));
}

function updateCounter(selector, value) {
    $(selector).fadeOut(150, function() {
        $(this).text(value).fadeIn(150);
    });
}
</script>
@endsection
