<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Daftar diskusi', 'data' => Discussion::with('user:id,name')->get()], 200);
    }

    public function show($id)
    {
        $data = Discussion::with('user:id,name')->find($id);
        if (!$data) return response()->json(['message' => 'Diskusi tidak ditemukan'], 404);
        return response()->json(['message' => 'Detail diskusi', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'question' => 'required|string',
        ]);

        $data = Discussion::create([
            'user_id' => $request->user()->id,
            'topic' => $validated['topic'],
            'question' => $validated['question'],
        ]);

        return response()->json(['message' => 'Diskusi berhasil dibuat', 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Discussion::find($id);
        if (!$data) return response()->json(['message' => 'Diskusi tidak ditemukan'], 404);

        $user = $request->user();
        if ($user->role !== 'admin' && $data->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak: Bukan pemilik data'], 403);
        }

        $validated = $request->validate([
            'topic' => 'sometimes|required|string|max:255',
            'question' => 'sometimes|required|string',
        ]);

        $data->update($validated);
        return response()->json(['message' => 'Diskusi berhasil diperbarui', 'data' => $data], 200);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak: Hanya admin'], 403);
        }

        $data = Discussion::find($id);
        if (!$data) return response()->json(['message' => 'Diskusi tidak ditemukan'], 404);

        $data->delete();
        return response()->json(['message' => 'Diskusi berhasil dihapus'], 200);
    }
}