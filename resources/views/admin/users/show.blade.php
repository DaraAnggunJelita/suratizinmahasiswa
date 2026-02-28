@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="card">
    <div class="card-header">
        Detail User
    </div>
    <div class="card-body">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        @if($user->role == 'mahasiswa' || $user->role == 'dosen')
            <p><strong>NIM/NIP:</strong> {{ $user->nim_nip ?? '-' }}</p>
        @endif
        @if($user->role == 'mahasiswa')
            <p><strong>Program Studi:</strong> {{ $user->prodi ?? '-' }}</p>
            <p><strong>Kelas:</strong> {{ $user->kelas ?? '-' }}</p>
        @endif
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection
