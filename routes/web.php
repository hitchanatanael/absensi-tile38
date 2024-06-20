<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
Route::post('/absensi', [AbsensiController::class, 'create'])->name('absen');
