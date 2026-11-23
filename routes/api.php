<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DiscussionController;

// --- AUTENTIKASI KELOMPOK ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- ENDPOINT PUBLIK (TANPA LOGIN) ---
// 1. Berlian
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
// 2. Dira
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
// 3. Lecha
Route::get('/vouchers', [VoucherController::class, 'index']);
Route::get('/vouchers/{id}', [VoucherController::class, 'show']);
// 4. Intan
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
// 5. Nasywa
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
// 6. Hana
Route::get('/discussions', [DiscussionController::class, 'index']);
Route::get('/discussions/{id}', [DiscussionController::class, 'show']);

// --- ENDPOINT TERPROTEKSI (WAJIB LOGIN TOKEN) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 1. Berlian (Products)
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // 2. Dira (Categories)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // 3. Lecha (Vouchers)
    Route::post('/vouchers', [VoucherController::class, 'store']);
    Route::put('/vouchers/{id}', [VoucherController::class, 'update']);
    Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy']);

    // 4. Intan (Articles)
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

    // 5. Nasywa (Reviews)
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // 6. Hana (Discussions)
    Route::post('/discussions', [DiscussionController::class, 'store']);
    Route::put('/discussions/{id}', [DiscussionController::class, 'update']);
    Route::delete('/discussions/{id}', [DiscussionController::class, 'destroy']);
});