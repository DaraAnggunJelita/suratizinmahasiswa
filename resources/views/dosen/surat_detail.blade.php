@extends('layouts.app')

@section('title', 'Detail Surat Izin')

@section('content')
<h2>Detail Surat Izin Mahasiswa</h2>

<div class="card p-4">
    <p><strong>Nama:</strong> {{ $surat->user->name }}</p>
    <p><strong>NIM:</strong> {{ $surat->user->nim_nip }}</p>
    <p><strong>Jenis Izin:</strong> {{ $surat->jenis_izin }}</p>
    <p><strong>Tanggal Mulai:</strong> {{ $surat->tanggal_mulai }}</p>
    <p><strong>Tanggal Selesai:</strong> {{ $surat->tanggal_selesai }}</p>
    <p><strong>Status:</strong> {{ $surat->status }}</p>
    <p><strong>Alasan:</strong> {{ $surat->alasan ?? '-' }}</p>

    <p><strong>Bukti:</strong>
        @if($surat->bukti_file)
            <a href="{{ asset('storage/' . $surat->bukti_file) }}" target="_blank">
                Lihat Bukti
            </a>
        @else
            Tidak ada bukti
        @endif
    </p>

    <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>
@endsection
