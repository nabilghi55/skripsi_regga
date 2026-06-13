<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KhatibController as AdminKhatibController;
use App\Http\Controllers\Admin\MasjidController as AdminMasjidController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Khatib\DashboardController as KhatibDashboardController;

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pengurus CMM (Admin) Routes
Route::middleware(['auth', 'role:pengurus'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('khatib', AdminKhatibController::class)->names([
        'index' => 'admin.khatib.index',
        'create' => 'admin.khatib.create',
        'store' => 'admin.khatib.store',
        'edit' => 'admin.khatib.edit',
        'update' => 'admin.khatib.update',
        'destroy' => 'admin.khatib.destroy',
    ]);
    Route::resource('masjid', AdminMasjidController::class)->names([
        'index' => 'admin.masjid.index',
        'create' => 'admin.masjid.create',
        'store' => 'admin.masjid.store',
        'edit' => 'admin.masjid.edit',
        'update' => 'admin.masjid.update',
        'destroy' => 'admin.masjid.destroy',
    ]);
    Route::resource('jadwal', AdminJadwalController::class)->names([
        'index' => 'admin.jadwal.index',
        'create' => 'admin.jadwal.create',
        'store' => 'admin.jadwal.store',
        'edit' => 'admin.jadwal.edit',
        'update' => 'admin.jadwal.update',
        'destroy' => 'admin.jadwal.destroy',
    ]);
    
    Route::get('/notification', [AdminNotificationController::class, 'index'])->name('admin.notification.index');
    Route::post('/notification', [AdminNotificationController::class, 'store'])->name('admin.notification.store');
});

// Khatib Routes
Route::middleware(['auth', 'role:khatib'])->prefix('khatib')->group(function () {
    Route::get('/dashboard', [KhatibDashboardController::class, 'index'])->name('khatib.dashboard');
    Route::get('/jadwal', [KhatibDashboardController::class, 'jadwalSaya'])->name('khatib.jadwalSaya');
    Route::get('/jadwal/{jadwal}', [KhatibDashboardController::class, 'detailJadwal'])->name('khatib.detailJadwal');
    Route::get('/jadwal/{jadwal}/perubahan', [KhatibDashboardController::class, 'formPerubahan'])->name('khatib.formPerubahan');
    Route::post('/jadwal/{jadwal}/perubahan', [KhatibDashboardController::class, 'kirimPerubahan'])->name('khatib.kirimPerubahan');
    Route::post('/notification/{notification}/read', [KhatibDashboardController::class, 'markNotifRead'])->name('khatib.markNotifRead');
});

// Home fallback
Route::get('/home', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'pengurus' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('khatib.dashboard');
    }
    return redirect()->route('login');
});
