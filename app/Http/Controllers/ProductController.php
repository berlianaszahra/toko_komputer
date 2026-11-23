<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. READ ALL (Publik - Tanpa Login)
    public function index()
    {
        $products = Product::with('user:id,name,email')->get();

        return response()->json([
            'message' => 'Daftar produk berhasil diambil',
            'data' => $products,
        ], 200);
    }

    // 2. READ DETAIL (Publik - Tanpa Login)
    public function show($id)
    {
        $product = Product::with('user:id,name,email')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail produk berhasil diambil',
            'data' => $product,
        ], 200);
    }

    // 3. CREATE (Login: Admin & User)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // user_id otomatis diambil dari akun yang sedang login
        $product = Product::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product,
        ], 201);
    }

    // 4. UPDATE (Login: Admin bisa semua | User hanya milik sendiri)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $user = $request->user();

        // Cek Ownership: jika bukan admin DAN user_id tidak cocok, tolak!
        if ($user->role !== 'admin' && $product->user_id !== $user->id) {
            return response()->json([
                'message' => 'Akses ditolak: Anda hanya dapat mengubah produk milik sendiri.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|integer|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data' => $product,
        ], 200);
    }

    // 5. DELETE (Login: Hanya Admin)
    public function destroy(Request $request, $id)
    {
        // Cek Role: selain admin dilarang menghapus
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak: Hanya admin yang dapat menghapus produk.',
            ], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus',
        ], 200);
    }
}