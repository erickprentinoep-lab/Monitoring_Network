<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Manager;

// ─── Auth ──────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ─── Admin ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Konfigurasi Jaringan
    Route::get('/konfigurasi', [Admin\KonfigurasiController::class, 'index'])->name('konfigurasi');
    Route::get('/konfigurasi/edit', [Admin\KonfigurasiController::class, 'edit'])->name('konfigurasi.edit');
    Route::put('/konfigurasi', [Admin\KonfigurasiController::class, 'update'])->name('konfigurasi.update');

    // VLAN
    Route::resource('vlan', Admin\VlanController::class);

    // Laporan Harian
    Route::resource('laporan', Admin\LaporanHarianController::class);

    // Laporan Failover
    Route::resource('failover', Admin\FailoverController::class);
});

// ─── Manager ───────────────────────────────────────────
Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:manager'])->group(function () {

    Route::get('/dashboard', [Manager\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [Manager\DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/{laporan}', [Manager\DashboardController::class, 'showLaporan'])->name('laporan.show');
    Route::get('/failover', [Manager\DashboardController::class, 'failover'])->name('failover');
    Route::get('/konfigurasi', [Manager\DashboardController::class, 'konfigurasi'])->name('konfigurasi');
});
