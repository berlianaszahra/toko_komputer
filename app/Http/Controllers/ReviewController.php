<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Daftar ulasan', 'data' => Review::with('user:id,name')->get()], 200);
    }

    public function show($id)
    {
        $data = Review::with('user:id,name')->find($id);
        if (!$data) return response()->json(['message' => 'Ulasan tidak ditemukan'], 404);
        return response()->json(['message' => 'Detail ulasan', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $data = Review::create([
            'user_id' => $request->user()->id,
            'product_name' => $validated['product_name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json(['message' => 'Ulasan berhasil ditambahkan', 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Review::find($id);
        if (!$data) return response()->json(['message' => 'Ulasan tidak ditemukan'], 404);

        $user = $request->user();
        if ($user->role !== 'admin' && $data->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak: Bukan pemilik data'], 403);
        }

        $validated = $request->validate([
            'product_name' => 'sometimes|required|string|max:255',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'sometimes|required|string',
        ]);

        $data->update($validated);
        return response()->json(['message' => 'Ulasan berhasil diperbarui', 'data' => $data], 200);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak: Hanya admin'], 403);
        }

        $data = Review::find($id);
        if (!$data) return response()->json(['message' => 'Ulasan tidak ditemukan'], 404);

        $data->delete();
        return response()->json(['message' => 'Ulasan berhasil dihapus'], 200);
    }
}