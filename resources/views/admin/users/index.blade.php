@extends('layouts.app')

@section('title', 'Daftar Pengguna')

@section('content')
<div class="container-fluid py-3 animate__animated animate__fadeIn">

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Manajemen Pengguna</h5>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Menampilkan {{ count($users) }} total akun aktif dalam sistem.</p>
        </div>
        <div class="search-compact shadow-sm bg-white">
            <i class="fas fa-search me-2 text-muted" style="font-size: 12px;"></i>
            {{-- Input Search dengan ID searchInput --}}
            <input type="text" id="searchInput" placeholder="Cari nama atau email..." autocomplete="off" style="font-size: 13px; border: none; outline: none; background: transparent; width: 200px;">
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableUser">
                <thead style="background-color: #fafbfc;">
                    <tr>
                        <th class="ps-4 py-3 text-secondary fw-bold text-uppercase" style="font-size: 0.65rem; width: 80px;">ID</th>
                        <th class="py-3 text-secondary fw-bold text-uppercase" style="font-size: 0.65rem;">Informasi Pengguna</th>
                        <th class="py-3 text-secondary fw-bold text-uppercase" style="font-size: 0.65rem;">Alamat Email</th>
                        <th class="py-3 text-secondary fw-bold text-uppercase text-center" style="font-size: 0.65rem;">Hak Akses</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($users as $user)
                    <tr class="user-row" style="border-bottom: 1px solid #f8f9fa;">
                        <td class="ps-4 py-3">
                            <span class="text-muted fw-medium" style="font-size: 0.8rem;">{{ $user->id }}</span>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-clean me-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold text-dark user-name" style="font-size: 0.85rem;">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="text-secondary user-email" style="font-size: 0.8rem;">{{ $user->email }}</span>
                        </td>
                        <td class="py-3 text-center">
                            @php
                                $roleBadge = [
                                    'admin' => 'badge-admin',
                                    'dosen' => 'badge-dosen',
                                    'mahasiswa' => 'badge-mhs'
                                ][strtolower($user->role)] ?? 'badge-default';
                            @endphp
                            <span class="role-pill {{ $roleBadge }} user-role">
                                {{ $user->role }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr id="noDataStatic">
                        <td colspan="4" class="text-center py-5 text-muted small">Tidak ada data pengguna tersedia.</td>
                    </tr>
                    @endforelse
                    {{-- Row tambahan untuk pesan "Data Tidak Ditemukan" saat pencarian --}}
                    <tr id="noResultsFound" style="display: none;">
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-search fa-2x mb-3 text-light"></i>
                            <p class="text-muted mb-0 small">Pengguna tidak ditemukan.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    body { background-color: #fcfdfe; font-family: 'Inter', sans-serif; }
    .search-compact { border: 1px solid #edf2f7; padding: 6px 15px; border-radius: 8px; display: flex; align-items: center; transition: 0.2s; }
    .search-compact:focus-within { border-color: #cbd5e0; box-shadow: 0 0 0 3px rgba(203, 213, 224, 0.1); }
    .avatar-clean { width: 32px; height: 32px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: 700; font-size: 11px; border: 1px solid #e2e8f0; }
    .role-pill { font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 6px; display: inline-block; text-transform: uppercase; }
    .badge-admin { background: #fff1f2; color: #e11d48; }
    .badge-dosen { background: #f0fdf4; color: #16a34a; }
    .badge-mhs { background: #eff6ff; color: #2563eb; }
    .badge-default { background: #f8fafc; color: #64748b; }
    .table-hover tbody tr:hover { background-color: #fbfcfd; }
</style>

{{-- Pastikan jQuery Terpanggil --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var rowCount = 0;

        // Melakukan filter pada setiap baris di tbody
        $("#userTableBody .user-row").filter(function() {
            var match = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(match);

            if (match) rowCount++;
        });

        // Menampilkan pesan jika hasil pencarian kosong
        if (value !== "" && rowCount === 0) {
            $("#noResultsFound").show();
        } else {
            $("#noResultsFound").hide();
        }
    });
});
</script>
@endsection
