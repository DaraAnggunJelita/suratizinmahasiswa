@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
<div class="mb-4">
    <div class="row align-items-center g-3">
        <div class="col-md-4">
            <h2 class="fw-bold text-dark mb-1">Daftar User</h2>
            <p class="text-muted small mb-0">Manajemen seluruh pengguna sistem SIMAH</p>
        </div>

        <div class="col-md-5">
            <div class="input-group shadow-sm border rounded-pill overflow-hidden bg-white">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-0 py-2 shadow-none" placeholder="Cari berdasarkan nama, email, atau role...">
            </div>
        </div>

        <div class="col-md-3 text-md-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm w-100 w-md-auto">
                <i class="fas fa-user-plus me-2"></i> Tambah User
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="tableUser">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="py-3 ps-4 text-muted small fw-bold" style="width:70px;">NO</th>
                        <th class="text-muted small fw-bold">NAMA LENGKAP</th>
                        <th class="text-muted small fw-bold">EMAIL</th>
                        <th class="text-muted small fw-bold">ROLE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key => $user)
                    <tr class="border-bottom">
                        <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                        </td>
                        <td class="text-secondary">{{ $user->email }}</td>
                        <td>
                            @php
                                $roleColor = [
                                    'admin' => 'bg-danger-subtle text-danger border-danger',
                                    'mahasiswa' => 'bg-primary-subtle text-primary border-primary',
                                    'dosen' => 'bg-success-subtle text-success border-success'
                                ];
                                $color = $roleColor[strtolower($user->role)] ?? 'bg-info-subtle text-info border-info';
                            @endphp
                            <span class="badge {{ $color }} border px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.7rem;">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr id="no-data">
                        <td colspan="4" class="text-center py-5 text-muted fst-italic">
                            <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">Belum ada user yang terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Reset style agar hover lebih smooth */
.table-hover tbody tr:hover {
    background-color: #f8fafc;
}

.btn-primary {
    background: linear-gradient(90deg, #647ACB, #415A77);
    border: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(100, 122, 203, 0.4);
}

.bg-danger-subtle { background-color: #fef2f2 !important; }
.bg-primary-subtle { background-color: #eff6ff !important; }
.bg-success-subtle { background-color: #f0fdf4 !important; }
.bg-info-subtle { background-color: #ecfeff !important; }

.table td, .table th {
    border-top: none;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tableUser tbody tr").not('#no-data').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
@endsection
