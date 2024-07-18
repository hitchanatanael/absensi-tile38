<?php

use App\Http\Controllers\AbsenDosenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redis;
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
    Route::get('/absensi/dosen', [AbsenDosenController::class, 'index'])->name('absensi.dosen');
});

//Admin Data Dosen
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/data/dosen', [DosenController::class, 'index'])->name('data.dosen');
    Route::post('/data/dosen/store', [DosenController::class, 'store'])->name('data.dosen.store');
    Route::put('/data-dosen/{id}', [DosenController::class, 'update'])->name('data.dosen.update');
    Route::post('/data/dosen', [DosenController::class, 'destroy'])->name('data.dosen.destroy');
});

// Admin Akun User
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/akun/user', [UserController::class, 'index'])->name('akun.user');
    Route::post('/akun/user/store', [UserController::class, 'store'])->name('akun.user.store');
    Route::put('/akun/user/{id}', [UserController::class, 'update'])->name('akun.user.update');
    Route::post('/akun/user', [UserController::class, 'destroy'])->name('akun.user.destroy');
});

// Route User
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home/clockIn', [HomeController::class, 'clockIn'])->name('absensi.clock-in');
    Route::post('/home/clockOut', [HomeController::class, 'clockOut'])->name('absensi.clock-out');
});

// cek koneksi redis tile38
Route::get('/test-redis', function () {
    try {
        $pingResponse = Redis::connection('tile38')->ping();
        if ($pingResponse === 'PONG') {
            return "Koneksi ke Tile38 berhasil.";
        } else {
            return "Tidak bisa terhubung ke Tile38.";
        }
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
