<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\AdminAuthController; // ◄ DIUBAH: Sesuaikan dengan letak subfolder Auth
use App\Http\Controllers\ComplaintController;

// ==========================================
// 1. JALUR UTAMA PUBLIK (LANDING PAGE & TRACKING)
// ==========================================
Route::get('/', [DashboardController::class, 'landingPage'])->name('landing');
Route::post('/cek-laundry', [DashboardController::class, 'cekProgresCucian'])->name('laundry.cek');


// ==========================================
// 2. GERBANG KHUSUS ADMIN (TERPISAH & BEBAS MENTAL)
// ==========================================
Route::get('admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::get('admin/register', [AdminAuthController::class, 'showRegister'])->name('admin.register');
Route::post('admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');


// ==========================================
// 3. JALUR AUTENTIKASI USER / PELANGGAN
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Jalur Keluar (Logout) - Berlaku universal bagi yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth'); // Jalur darurat via URL


// ==========================================
// 4. RUTE LUPA & RESET PASSWORD
// ==========================================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');


// ==========================================
// 5. JALUR DASHBOARD ADMIN (TERPROTEKSI MIDDLEWARE)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/transaksi', [DashboardController::class, 'storeTransaksi'])->name('admin.transaksi.store');
    Route::post('/transaksi/update/{id}', [DashboardController::class, 'update'])->name('admin.transaksi.update');
    Route::get('/transaksi/email/{id}', [DashboardController::class, 'kirimEmail'])->name('admin.transaksi.email');
    Route::delete('/transaksi/delete/{id}', [DashboardController::class, 'destroy'])->name('admin.transaksi.destroy');
    Route::post('/pelanggan/store', [DashboardController::class, 'storePelanggan'])->name('admin.pelanggan.store');
});


// ==========================================
// 6. JALUR DASHBOARD PELANGGAN / USER
// ==========================================
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    Route::post('/transaksi/store', [DashboardController::class, 'storeTransaksi'])->name('user.transaksi.store');
});


// Route untuk Halaman User
Route::middleware(['auth'])->group(function () {
    Route::get('/komplain', [ComplaintController::class, 'indexUser'])->name('user.komplain');
    Route::post('/komplain/kirim', [ComplaintController::class, 'storeUser'])->name('user.komplain.store');
});

// Route untuk Halaman Admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/komplain', [ComplaintController::class, 'indexAdmin'])->name('komplain.index');
    Route::get('/komplain/chat/{userId}', [ComplaintController::class, 'detailAdmin'])->name('komplain.detail');
    Route::post('/komplain/chat/{userId}/balas', [ComplaintController::class, 'storeAdmin'])->name('komplain.store');
});


// Pastikan route komplain ada di DALAM grup middleware auth ini
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route komplain harus di sini agar tahu SIAPA user yang sedang chat
    Route::get('/komplain', [ComplaintController::class, 'indexUser'])->name('user.komplain');
    Route::post('/komplain/kirim', [ComplaintController::class, 'storeUser'])->name('user.komplain.store');
});