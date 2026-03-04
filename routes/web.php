<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SuratIzinController as AdminSurat;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Mahasiswa\SuratIzinController as MahasiswaSurat;
use App\Http\Controllers\DashboardDosenController;
use App\Http\Controllers\AbsensiController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Mahasiswa\JadwalController;

/*
|--------------------------------------------------------------------------
| AUTH & PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Jadwal dipindahkan ke sini agar Mahasiswa, Admin, & Dosen bisa akses halaman yang sama
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});

/*
|--------------------------------------------------------------------------
| MAHASISWA ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')->middleware(['auth', RoleMiddleware::class . ':mahasiswa'])->group(function () {
    Route::get('/surat-izin', [MahasiswaSurat::class, 'index'])->name('mahasiswa.surat_izin.index');
    Route::get('/surat-izin/create', [MahasiswaSurat::class, 'create'])->name('mahasiswa.surat_izin.create');
    Route::post('/surat-izin', [MahasiswaSurat::class, 'store'])->name('mahasiswa.surat_izin.store');
    Route::get('/surat-izin/{id}/edit', [MahasiswaSurat::class, 'edit'])->name('mahasiswa.surat_izin.edit');
    Route::put('/surat-izin/{id}', [MahasiswaSurat::class, 'update'])->name('mahasiswa.surat_izin.update');
    Route::delete('/surat-izin/{id}', [MahasiswaSurat::class, 'destroy'])->name('mahasiswa.surat_izin.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/surat-izin', [AdminSurat::class, 'index'])->name('admin.surat_izin.index');
    Route::get('/surat-izin/{id}', [AdminSurat::class, 'show'])->name('admin.surat_izin.show');
    Route::put('/surat-izin/{id}/verifikasi', [AdminSurat::class, 'verifikasi'])->name('admin.surat_izin.verifikasi');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::post('/jadwal/store', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
});

/*
|--------------------------------------------------------------------------
| DOSEN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')->middleware(['auth', RoleMiddleware::class . ':dosen'])->group(function () {

    // Dashboard Dosen (Pastikan rute ini masuk dalam prefix 'dosen' dan role 'dosen')
    Route::get('/dashboard', [DashboardDosenController::class, 'index'])->name('dosen.dashboard');

    // Surat Izin Management
    Route::get('/surat/detail/{id}', [DashboardDosenController::class, 'suratDetail'])->name('dosen.surat_detail');
    Route::post('/surat/verifikasi/{id}', [DashboardDosenController::class, 'verifikasi'])->name('dosen.verifikasi');    Route::delete('/surat/delete/{id}', [DashboardDosenController::class, 'hapusSurat'])->name('dosen.surat_hapus');

    // Absensi Management
    Route::get('/absensi/{kelas}', [AbsensiController::class, 'index'])->name('dosen.absensi');
    Route::get('/absensi/create/{kelas}', [AbsensiController::class, 'create'])->name('dosen.createAbsen');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('dosen.storeAbsen');
    Route::get('/absensi/edit/{id}', [AbsensiController::class, 'edit'])->name('dosen.editAbsen');
    Route::put('/absensi/update/{id}', [AbsensiController::class, 'update'])->name('dosen.updateAbsen');
    Route::delete('/absensi/delete/{id}', [AbsensiController::class, 'destroy'])->name('dosen.hapusAbsen');

    // Redirect Shortcut (opsional)
    Route::get('/absen', function () {
        return redirect()->route('dosen.absensi', ['kelas' => 'MI 3A']);
    })->name('dosen.absen');
});
