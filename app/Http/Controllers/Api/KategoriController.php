<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KategoriController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(KategoriModel::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:255',
        ]);

        $kategori = KategoriModel::create($validated);
        return response()->json($kategori, 201);
    }

    public function show(KategoriModel $kategori): JsonResponse
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriModel $kategori): JsonResponse
    {
        $validated = $request->validate([
            'kategori_kode' => 'sometimes|required|string|max:10|unique:m_kategori,kategori_kode,' . $kategori->kategori_id . ',kategori_id',
            'kategori_nama' => 'sometimes|required|string|max:255',
        ]);

        $kategori->update($validated);
        return response()->json($kategori, 200);
    }

    public function destroy(KategoriModel $kategori): JsonResponse
    {
        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully',
        ]);
    }
}

