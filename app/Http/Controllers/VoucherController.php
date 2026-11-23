<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Daftar voucher', 'data' => Voucher::with('user:id,name')->get()], 200);
    }

    public function show($id)
    {
        $data = Voucher::with('user:id,name')->find($id);
        if (!$data) return response()->json(['message' => 'Voucher tidak ditemukan'], 404);
        return response()->json(['message' => 'Detail voucher', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'discount_percent' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        $data = Voucher::create([
            'user_id' => $request->user()->id,
            'code' => $validated['code'],
            'discount_percent' => $validated['discount_percent'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['message' => 'Voucher berhasil dibuat', 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Voucher::find($id);
        if (!$data) return response()->json(['message' => 'Voucher tidak ditemukan'], 404);

        $user = $request->user();
        if ($user->role !== 'admin' && $data->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak: Bukan pemilik data'], 403);
        }

        $validated = $request->validate([
            'code' => 'sometimes|required|string|max:50',
            'discount_percent' => 'sometimes|required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        $data->update($validated);
        return response()->json(['message' => 'Voucher berhasil diperbarui', 'data' => $data], 200);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak: Hanya admin'], 403);
        }

        $data = Voucher::find($id);
        if (!$data) return response()->json(['message' => 'Voucher tidak ditemukan'], 404);

        $data->delete();
        return response()->json(['message' => 'Voucher berhasil dihapus'], 200);
    }
}