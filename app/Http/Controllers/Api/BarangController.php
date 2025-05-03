<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Upload file gambar
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        // Simpan data barang
        $barang = BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'image' => $image->hashName()
        ]);

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
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Jika ada file gambar baru
        if ($request->hasFile('image')) {
            // Hapus file gambar lama
            Storage::delete('public/posts/' . $barang->image);

            // Upload gambar baru
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            $barang->image = $image->hashName();
        }

        // Update field lainnya
        $barang->update($request->except('image'));

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

        // Hapus file gambar
        Storage::delete('public/posts/' . $barang->image);

        $barang->delete();

        return response()->json(['message' => 'Data barang berhasil dihapus']);
    }
}
