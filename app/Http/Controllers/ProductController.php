<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products (Publik - Tanpa Login)
    public function index()
    {
        $products = Product::with('user:id,name,email,role')->get();

        return response()->json([
            'message' => 'Daftar produk berhasil diambil',
            'data' => $products,
        ], 200);
    }

    // GET /api/products/{id} (Publik - Tanpa Login)
    public function show($id)
    {
        $product = Product::with('user:id,name,email,role')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail produk berhasil diambil',
            'data' => $product,
        ], 200);
    }

    // POST /api/products (Login: Admin & User)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ]);

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

    // PUT /api/products/{id} (Login: Admin semua data | User hanya milik sendiri)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        $user = $request->user();

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

    // DELETE /api/products/{id} (Login: Hanya Admin)
    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak: Hanya admin yang dapat menghapus produk.',
            ], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus',
        ], 200);
    }
}