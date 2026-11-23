<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// --- 0. ENDPOINT AUTENTIKASI (KELOMPOK) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- 1. ENDPOINT BERLIAN (PRODUCTS) ---
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// --- ENDPOINT PUBLIK ANGGOTA LAIN (DIRA, LECHA, INTAN, NASYWA, HANA) ---
// Taruh route GET publik milik anggota lain di sini


// --- ENDPOINT TERPROTEKSI (WAJIB LOGIN TOKEN) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Berlian (Products)
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Taruh route POST, PUT, DELETE milik Dira, Lecha, Intan, Nasywa, dan Hana di sini
});