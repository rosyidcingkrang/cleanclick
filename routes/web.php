<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ComplaintController;

/*
|--------------------------------------------------------------------------
| 1. JALUR UTAMA PUBLIK (LANDING PAGE & TRACKING)
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'landingPage'])->name('landing');

// Menambahkan alias nama route 'cek.progres' dan 'laundry.cek' agar kompatibel dengan berbagai view
Route::post('/cek-laundry', [DashboardController::class, 'cekProgresCucian'])->name('laundry.cek');
Route::post('/cek-progres', [DashboardController::class, 'cekProgresCucian'])->name('cek.progres');


/*
|--------------------------------------------------------------------------
| 2. GERBANG AUTENTIKASI ADMIN
|--------------------------------------------------------------------------
*/
Route::get('admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

Route::get('admin/register', [AuthController::class, 'showAdminRegister'])->name('admin.register');
Route::post('admin/register', [AuthController::class, 'adminRegister'])->name('admin.register.submit');


/*
|--------------------------------------------------------------------------
| 3. JALUR AUTENTIKASI USER / PELANGGAN
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout utama (Wajib POST untuk keamanan CSRF)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


/*
|--------------------------------------------------------------------------
| 4. RUTE LUPA & RESET PASSWORD
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');


/*
|--------------------------------------------------------------------------
| 5. JALUR DASHBOARD & FITUR ADMIN (TERPROTEKSI)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Manajemen Transaksi & Pelanggan
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::post('/transaksi', [DashboardController::class, 'storeTransaksi'])->name('transaksi.store');
    
    // Menerima POST, PUT, dan PATCH untuk update status agar tidak Mismatch Method
    Route::match(['post', 'put', 'patch'], '/transaksi/update/{id}', [DashboardController::class, 'update'])->name('transaksi.update');
    
    Route::get('/transaksi/email/{id}', [DashboardController::class, 'kirimEmail'])->name('transaksi.email');
    Route::delete('/transaksi/delete/{id}', [DashboardController::class, 'destroy'])->name('transaksi.destroy');
    Route::post('/pelanggan/store', [DashboardController::class, 'storePelanggan'])->name('pelanggan.store');

    // Manajemen Komplain (Sisi Admin)
    Route::get('/komplain', [ComplaintController::class, 'indexAdmin'])->name('komplain.index');
    Route::get('/komplain/chat/{userId}', [ComplaintController::class, 'detailAdmin'])->name('komplain.detail');
    Route::post('/komplain/chat/{userId}/balas', [ComplaintController::class, 'storeAdmin'])->name('komplain.store');
});


/*
|--------------------------------------------------------------------------
| 6. JALUR DASHBOARD & FITUR PELANGGAN / USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    Route::post('/transaksi/store', [DashboardController::class, 'storeTransaksi'])->name('transaksi.store');
    
    // Fitur Komplain Pelanggan
    Route::get('/komplain', [ComplaintController::class, 'indexUser'])->name('komplain');
    Route::post('/komplain/kirim', [ComplaintController::class, 'storeUser'])->name('komplain.store');
});