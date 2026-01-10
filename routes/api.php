<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ProfileApiController;

use App\Http\Controllers\Api\ShopApiController;
use App\Http\Controllers\Api\TransactionApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





/*
|--------------------------------------------------------------------------
| Public Routes (Tidak butuh Token)
|--------------------------------------------------------------------------
*/

// Registrasi & Login (Controller yang sudah kita bersihkan tadi)
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::prefix('products')->group(function () {
    
    // GET /api/products
    Route::get('/categories', [ProductApiController::class, 'getCategories']);
    // Support params: ?page=1, ?search=kripik, ?category=makanan
    Route::get('/', [ProductApiController::class, 'index']);

    // GET /api/products/{slug}
    // Contoh: /api/products/keripik-singkong-balado-xh51z
    Route::get('/{slug}', [ProductApiController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart/add', [CartApiController::class, 'store']);
    Route::put('/cart/update/{cartItemId}', [CartApiController::class, 'updateQuantity']);
    Route::delete('/cart/remove/{cartItemId}', [CartApiController::class, 'destroy']);
    Route::delete('/cart', [CartApiController::class, 'clearCart']);
    Route::post('/checkout', [CartApiController::class, 'checkout']);
    Route::get('/transactions', [TransactionApiController::class, 'index']);
    Route::get('/transactions/{code}', [TransactionApiController::class, 'show']);
    Route::post('/transactions/{code}/upload-proof', [TransactionApiController::class, 'uploadProof']);


    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::post('/profile', [ProfileApiController::class, 'update']);
});
Route::prefix('shops')->group(function () {
    Route::get('/', [ShopApiController::class, 'index']); // List Toko
    Route::get('/{id}', [ShopApiController::class, 'show']); // Detail Toko
});
/*
|--------------------------------------------------------------------------
| Protected Routes (Harus ada Token Bearer)
|--------------------------------------------------------------------------
*/
// Route::middleware('auth:sanctum')->group(function () {
    
//     // 1. Logout
//     Route::post('/logout', [AuthApiController::class, 'logout']);

//     // 2. Get Current User Profile (SMART ROUTE)
//     // Route ini otomatis mengecek role dan mengambil data profil dari tabel yang benar
//     Route::get('/user', function (Request $request) {
//         $user = $request->user();
        
//         // Logika Dinamis: Cek role, lalu ambil data dari tabel profil yang sesuai
//         if ($user->role === 'mitra') {
//             $user->load('mitraProfile'); // Ambil data dari tabel mitra_profiles
//         } elseif ($user->role === 'freelancer') {
//             $user->load('freelancerProfile'); // Ambil data dari tabel freelancer_profiles
//         }
        
//         return $user;
//     });



// });