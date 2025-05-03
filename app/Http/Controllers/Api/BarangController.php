<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;

class BarangController extends Controller
{
    public function index()
    {
        $data = BarangModel::with('kategori')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|unique:m_barang,barang_kode',
            'barang_nama' => 'required',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
        ]);

        $barang = BarangModel::create($request->all());
        return response()->json([
            'message' => 'Data barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    public function show($id)
    {
        $barang = BarangModel::with('kategori')->find($id);

        if (!$barang) {
            return response()->json(['message' => 'Data barang tidak ditemukan'], 404);
        }

        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $barang = BarangModel::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Data barang tidak ditemukan'], 404);
        }

        $request->validate([
            'kategori_id' => 'sometimes|exists:m_kategori,kategori_id',
            'barang_kode' => 'sometimes|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'harga_beli' => 'sometimes|integer',
            'harga_jual' => 'sometimes|integer',
        ]);

        $barang->update($request->all());

        return response()->json([
            'message' => 'Data barang berhasil diperbarui',
            'data' => $barang
        ]);
    }

    public function destroy($id)
    {
        $barang = BarangModel::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Data barang tidak ditemukan'], 404);
        }

        $barang->delete();

        return response()->json(['message' => 'Data barang berhasil dihapus']);
    }
}
