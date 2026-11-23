<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Daftar artikel', 'data' => Article::with('user:id,name')->get()], 200);
    }

    public function show($id)
    {
        $data = Article::with('user:id,name')->find($id);
        if (!$data) return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        return response()->json(['message' => 'Detail artikel', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data = Article::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return response()->json(['message' => 'Artikel berhasil dibuat', 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Article::find($id);
        if (!$data) return response()->json(['message' => 'Artikel tidak ditemukan'], 404);

        $user = $request->user();
        if ($user->role !== 'admin' && $data->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak: Bukan pemilik data'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $data->update($validated);
        return response()->json(['message' => 'Artikel berhasil diperbarui', 'data' => $data], 200);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak: Hanya admin'], 403);
        }

        $data = Article::find($id);
        if (!$data) return response()->json(['message' => 'Artikel tidak ditemukan'], 404);

        $data->delete();
        return response()->json(['message' => 'Artikel berhasil dihapus'], 200);
    }
}