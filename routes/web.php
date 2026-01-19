<?php

use App\Http\Controllers\AdminPromotionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;

use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsFreelance;
use App\Http\Middleware\IsMitra;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/fix-autoload', function() {
    // Membersihkan cache
    Artisan::call('optimize:clear');
    
    // Mencoba dump-autoload (mungkin tidak jalan di beberapa shared hosting)
    // Tapi kita coba trigger autoloader composer
    exec('composer dump-autoload');
    
    return 'Autoload fixed & Cache cleared!';
});



Route::redirect('/', '/dashboard');
// --- GUEST ROUTES (Hanya bisa diakses jika belum login) ---
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- AUTH ROUTES (Hanya bisa diakses jika sudah login) ---
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Halaman Lihat Profil

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show'); // Halaman Lihat Profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // Halaman Form Edit
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Proses Update
    Route::middleware(IsMitra::class)->prefix('mitra')->name('mitra.')->group(function () {
        Route::resource('products', ProductController::class);
        Route::delete('/product-images/{id}', [ProductController::class, 'deleteImage'])->name('product-images.destroy');
        Route::get('/products/{id}/promote', [ProductController::class, 'promote'])
            ->name('products.promote');
            
        Route::post('/products/{id}/promote', [ProductController::class, 'submit_promotion'])
            ->name('products.submit_promotion');
    });
    
    Route::middleware(IsFreelance::class)->prefix('freelancer')->name('freelancer.')->group(function () {
        Route::resource('portfolios', PortfolioController::class);
    });

    Route::middleware(IsAdmin::class)->prefix('admin')->name('admin.')->group(function () {
        // 1. Tampilkan Daftar Request Pending (GET)
        Route::get('/promotions', [AdminPromotionController::class, 'index'])
            ->name('promotions.index');
        
        // 2. Aksi Approve Request (POST)
        // Menggunakan ID dari tabel product_promotions, bukan ID produk
        Route::post('/promotions/{id}/approve', [AdminPromotionController::class, 'approve'])
            ->name('promotions.approve');
        
        // 3. Aksi Reject Request (POST)
        // Halaman List
        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])
            ->name('payment.index');

        // Halaman Form Tambah
        Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])
            ->name('payment.create');

        // Proses Simpan Data Baru (POST)
        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])
            ->name('payment.store');

        // Halaman Form Edit (Butuh ID)
        Route::get('/payment-methods/{id}/edit', [PaymentMethodController::class, 'edit'])
            ->name('payment.edit');

        // Proses Update Data (PUT, Butuh ID)
        Route::put('/payment-methods/{id}', [PaymentMethodController::class, 'update'])
            ->name('payment.update');

        // Proses Hapus Data (DELETE, Butuh ID)
        Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'destroy'])
            ->name('payment.destroy');
    });
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{invoice}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    // --- MANAJEMEN PROMOSI ---
    
    

});
