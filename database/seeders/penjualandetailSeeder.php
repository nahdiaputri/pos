<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        $penjualan = range(1, 10); // 10 transaksi penjualan
        $barang = range(1, 15); // 15 barang tersedia
        $index_barang = 0;

        foreach ($penjualan as $penjualan_id) {
            for ($i = 0; $i < 3; $i++) { // 3 barang per transaksi
                $barang_id = $barang[$index_barang % count($barang)]; // Rotasi barang agar tetap 1-15
                $harga = rand(5000, 50000); // Harga random antara 5rb - 50rb
                $jumlah = rand(1, 5); // Jumlah antara 1 - 5
                
                $data[] = [
                    'penjualan_id' => $penjualan_id,
                    'barang_id' => $barang_id,
                    'harga' => $harga,
                    'jumlah' => $jumlah,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                $index_barang++;
            }
        }

        // Memastikan total data yang dimasukkan adalah 30
        if (count($data) === 30) {
            DB::table('t_penjualan_detail')->insert($data);
        } else {
            echo "Error: Data yang di-generate tidak mencapai 30 baris!";
        }
    }
}
