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
    
    // Khatib Extra Routes
    Route::get('khatib/export', [AdminKhatibController::class, 'export'])->name('admin.khatib.export');
    Route::get('khatib/cetak', [AdminKhatibController::class, 'cetak'])->name('admin.khatib.cetak');
    Route::resource('khatib', AdminKhatibController::class)->names([
        'index' => 'admin.khatib.index',
        'create' => 'admin.khatib.create',
        'store' => 'admin.khatib.store',
        'show' => 'admin.khatib.show',
        'edit' => 'admin.khatib.edit',
        'update' => 'admin.khatib.update',
        'destroy' => 'admin.khatib.destroy',
    ]);
    
    // Masjid Extra Routes
    Route::get('masjid/export', [AdminMasjidController::class, 'export'])->name('admin.masjid.export');
    Route::get('masjid/cetak', [AdminMasjidController::class, 'cetak'])->name('admin.masjid.cetak');
    Route::post('masjid/{masjid}/takmir', [AdminMasjidController::class, 'updateOrCreateTakmir'])->name('admin.masjid.takmir.store');
    Route::delete('masjid/{masjid}/takmir', [AdminMasjidController::class, 'destroyTakmir'])->name('admin.masjid.takmir.destroy');
    Route::resource('masjid', AdminMasjidController::class)->names([
        'index' => 'admin.masjid.index',
        'create' => 'admin.masjid.create',
        'store' => 'admin.masjid.store',
        'edit' => 'admin.masjid.edit',
        'update' => 'admin.masjid.update',
        'destroy' => 'admin.masjid.destroy',
    ]);
    
    // Jadwal Extra Routes & Badal
    Route::get('jadwal/export', [AdminJadwalController::class, 'export'])->name('admin.jadwal.export');
    Route::get('jadwal/cetak', [AdminJadwalController::class, 'cetak'])->name('admin.jadwal.cetak');
    Route::post('jadwal/{jadwal}/acc', [AdminJadwalController::class, 'accPerubahan'])->name('admin.jadwal.acc');
    
    Route::get('badal', [AdminJadwalController::class, 'badalIndex'])->name('admin.badal.index');
    Route::get('badal/tambah-jadwal', [AdminJadwalController::class, 'badalTambahJadwalForm'])->name('admin.badal.tambahJadwalForm');
    Route::post('badal/tambah-jadwal', [AdminJadwalController::class, 'badalTambahJadwalStore'])->name('admin.badal.tambahJadwalStore');
    
    Route::resource('jadwal', AdminJadwalController::class)->names([
        'index' => 'admin.jadwal.index',
        'create' => 'admin.jadwal.create',
        'store' => 'admin.jadwal.store',
        'edit' => 'admin.jadwal.edit',
        'update' => 'admin.jadwal.update',
        'destroy' => 'admin.jadwal.destroy',
    ]);

    // Riwayat Badal Routes
    Route::resource('riwayat-badal', \App\Http\Controllers\Admin\RiwayatBadalController::class)->names([
        'index' => 'admin.riwayatBadal.index',
        'edit' => 'admin.riwayatBadal.edit',
        'update' => 'admin.riwayatBadal.update',
        'destroy' => 'admin.riwayatBadal.destroy',
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
    
    // Profile Routes
    Route::get('/profile', [KhatibDashboardController::class, 'profile'])->name('khatib.profile');
    Route::post('/profile', [KhatibDashboardController::class, 'updateProfile'])->name('khatib.profile.update');
    Route::post('/profile/password', [KhatibDashboardController::class, 'changePassword'])->name('khatib.profile.password');
});

// Takmir Routes
Route::middleware(['auth', 'role:takmir'])->prefix('takmir')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Takmir\DashboardController::class, 'index'])->name('takmir.dashboard');
    Route::get('/jadwal', [\App\Http\Controllers\Takmir\DashboardController::class, 'jadwalMasjid'])->name('takmir.jadwal');
    Route::post('/jadwal/{jadwal}/saran', [\App\Http\Controllers\Takmir\DashboardController::class, 'updateSaran'])->name('takmir.jadwal.saran');
    Route::get('/jadwal/cetak', [\App\Http\Controllers\Takmir\DashboardController::class, 'cetakJadwal'])->name('takmir.jadwal.cetak');
    Route::get('/profile', [\App\Http\Controllers\Takmir\DashboardController::class, 'profile'])->name('takmir.profile');
    Route::post('/profile', [\App\Http\Controllers\Takmir\DashboardController::class, 'updateProfile'])->name('takmir.profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Takmir\DashboardController::class, 'changePassword'])->name('takmir.profile.password');
});

// Home fallback
Route::get('/home', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'pengurus') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'khatib') {
            return redirect()->route('khatib.dashboard');
        } elseif ($role === 'takmir') {
            return redirect()->route('takmir.dashboard');
        }
    }
    return redirect()->route('login');
});

// Temporary Route for SSH-less Deployment Initialization
Route::get('/deploy-init', function () {
    try {
        // Run migration
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrationOutput = \Illuminate\Support\Facades\Artisan::output();

        // Run seeder if admin user doesn't exist yet
        $seederOutput = '';
        if (\App\Models\User::where('username', 'admin')->count() === 0) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            $seederOutput = "\nSeeder Output:\n" . \Illuminate\Support\Facades\Artisan::output();
        } else {
            $seederOutput = "\nSeeder skipped (admin user already exists).";
        }

        return "<pre>Migration Output:\n" . $migrationOutput . $seederOutput . "\n\nInitialization success!</pre>";
    } catch (\Exception $e) {
        return "<pre>Error during deployment initialization:\n" . $e->getMessage() . "</pre>";
    }
});

