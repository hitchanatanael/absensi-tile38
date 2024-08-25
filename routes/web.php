<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\IzinDosenController;
use App\Http\Controllers\KehadiranDosenController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Admin Absensi Dosen
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/kehadiran/dosen', [KehadiranDosenController::class, 'index'])->name('kehadiran.dosen');
    Route::post('/kehadiran/dosen/{id}', [KehadiranDosenController::class, 'destroy'])->name('kehadiran.dosen.destroy');
});

// Admin Izin Dosen
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/izin/dosen', [IzinDosenController::class, 'index'])->name('izin.dosen');
    Route::get('/izin/dosen/setuju/{id}', [IzinDosenController::class, 'setuju'])->name('izin.setuju');
    Route::get('/izin/dosen/tolak/{id}', [IzinDosenController::class, 'tolak'])->name('izin.tolak');
});

//Admin Data Dosen
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/data/dosen', [DosenController::class, 'index'])->name('data.dosen');
    Route::post('/data/dosen/store', [DosenController::class, 'store'])->name('data.dosen.store');
    Route::put('/data-dosen/{id}', [DosenController::class, 'update'])->name('data.dosen.update');
    Route::post('/data/dosen/{id}', [DosenController::class, 'destroy'])->name('data.dosen.destroy');
});

// Admin Akun User
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/akun/user', [UserController::class, 'index'])->name('akun.user');
    Route::post('/akun/user/store', [UserController::class, 'store'])->name('akun.user.store');
    Route::put('/akun/user/{id}', [UserController::class, 'update'])->name('akun.user.update');
    Route::post('/akun/user/{id}', [UserController::class, 'destroy'])->name('akun.user.destroy');
});

// User
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [AbsensiController::class, 'index'])->name('home');
    Route::post('/home/clockIn', [AbsensiController::class, 'clockIn'])->name('absensi.clock-in');
    Route::post('/home/clockOut', [AbsensiController::class, 'clockOut'])->name('absensi.clock-out');
    Route::get('/izin', [IzinController::class, 'index'])->name('izin');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
    Route::post('/izin/{id}', [IzinController::class, 'destroy'])->name('izin.destroy');
});

// User Profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profil/{id}', [ProfilController::class, 'index'])->name('profil');
    Route::post('/profil/update/{id}', [ProfilController::class, 'updateProfil'])->name('profil.update');
});
