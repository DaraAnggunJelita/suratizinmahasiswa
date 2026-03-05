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
use App\Http\Controllers\PengumumanController;

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
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    // PERBAIKAN: Semua Role yang login bisa melihat Pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');
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
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class . ':admin'])->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminSurat::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', AdminUserController::class);

    // Pengumuman Management (Hanya Create, Edit, Delete untuk Admin)
    Route::resource('pengumuman', PengumumanController::class)->except(['index', 'show']);
    Route::post('/quick-broadcast', [AdminSurat::class, 'storePengumuman'])->name('pengumuman.quick-store');

    // Surat Izin Management (Admin)
    Route::get('/surat-izin', [AdminSurat::class, 'index'])->name('surat_izin.index');
    Route::get('/surat-izin/{id}', [AdminSurat::class, 'show'])->name('surat_izin.show');
    Route::put('/surat-izin/{id}/verifikasi', [AdminSurat::class, 'verifikasi'])->name('surat_izin.verifikasi');

    // Jadwal Management
    Route::post('/jadwal/store', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
});

/*
|--------------------------------------------------------------------------
| DOSEN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')->middleware(['auth', RoleMiddleware::class . ':dosen'])->group(function () {

    // Dashboard Dosen
    Route::get('/dashboard', [DashboardDosenController::class, 'index'])->name('dosen.dashboard');

    // Surat Izin Management (Dosen)
    Route::get('/surat-izin/{id}', [DashboardDosenController::class, 'suratDetail'])->name('dosen.suratDetail');
    Route::post('/surat-izin/{id}/setujui', [DashboardDosenController::class, 'setujuiSurat'])->name('dosen.setujuiSurat');
    Route::post('/surat/{id}/tolak', [DashboardDosenController::class, 'tolakSurat'])->name('dosen.tolakSurat');

    // Absensi Management
    Route::get('/absensi/rekap/{kelas}', [DashboardDosenController::class, 'absensiByKelas'])->name('dosen.absensi');
    Route::get('/absensi/create/{kelas}', [DashboardDosenController::class, 'createAbsen'])->name('dosen.createAbsen');
    Route::post('/absensi/store', [DashboardDosenController::class, 'storeAbsen'])->name('dosen.storeAbsen');
    Route::get('/absensi/edit/{id}', [DashboardDosenController::class, 'editAbsen'])->name('dosen.editAbsen');
    Route::put('/absensi/update/{id}', [DashboardDosenController::class, 'updateAbsen'])->name('dosen.updateAbsen');
Route::delete('/dosen/absensi/delete/{id}', [DashboardDosenController::class, 'destroyAbsen'])->name('dosen.destroyAbsen');
    // Shortcut
    Route::get('/absen', function () {
        return redirect()->route('dosen.absensi', ['kelas' => 'MI 3A']);
    })->name('dosen.absen');
});
