<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;

class PenjualanController extends Controller
{
    public function index()
    {
        $data = PenjualanModel::with(['user', 'detailPenjualan.barang'])->get();

        // Tambahkan image_url pada setiap barang
        $data->each(function ($penjualan) {
            $penjualan->detailPenjualan->each(function ($detail) {
                if ($detail->barang && $detail->barang->image) {
                    $detail->barang->image_url = asset('storage/barang/' . $detail->barang->image);
                }
            });
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:m_user,user_id',
            'pembeli' => 'required',
            'penjualan_kode' => 'required|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date',
            'detail' => 'required|array|min:1',
            'detail.*.barang_id' => 'required|exists:m_barang,barang_id',
            'detail.*.harga' => 'required|integer',
            'detail.*.jumlah' => 'required|integer',
        ]);

        $penjualan = PenjualanModel::create($request->only([
            'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal'
        ]));

        foreach ($request->detail as $item) {
            DetailPenjualanModel::create([
                'penjualan_id' => $penjualan->penjualan_id,
                'barang_id' => $item['barang_id'],
                'harga' => $item['harga'],
                'jumlah' => $item['jumlah'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Muat relasi dan tambahkan image_url
        $penjualan->load('detailPenjualan.barang');
        $penjualan->detailPenjualan->each(function ($detail) {
            if ($detail->barang && $detail->barang->image) {
                $detail->barang->image_url = asset('storage/barang/' . $detail->barang->image);
            }
        });

        return response()->json([
            'message' => 'Transaksi penjualan berhasil ditambahkan',
            'data' => $penjualan
        ], 201);
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with(['user', 'detailPenjualan.barang'])->find($id);

        if (!$penjualan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Tambahkan image_url pada barang
        $penjualan->detailPenjualan->each(function ($detail) {
            if ($detail->barang && $detail->barang->image) {
                $detail->barang->image_url = asset('storage/barang/' . $detail->barang->image);
            }
        });

        return response()->json($penjualan);
    }
}
