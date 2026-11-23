<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Daftar kategori', 'data' => Category::with('user:id,name')->get()], 200);
    }

    public function show($id)
    {
        $data = Category::with('user:id,name')->find($id);
        if (!$data) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        return response()->json(['message' => 'Detail kategori', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = Category::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['message' => 'Kategori berhasil dibuat', 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Category::find($id);
        if (!$data) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        $user = $request->user();
        if ($user->role !== 'admin' && $data->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak: Bukan pemilik data'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data->update($validated);
        return response()->json(['message' => 'Kategori berhasil diperbarui', 'data' => $data], 200);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak: Hanya admin'], 403);
        }

        $data = Category::find($id);
        if (!$data) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        $data->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
    }
}