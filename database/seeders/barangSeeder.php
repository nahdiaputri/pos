<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Barang dari Supplier 1
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'B001', 'barang_nama' => 'Beras', 'harga_beli' => 10000, 'harga_jual' => 12000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'B002', 'barang_nama' => 'Gula', 'harga_beli' => 13000, 'harga_jual' => 15000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'B003', 'barang_nama' => 'Teh Botol', 'harga_beli' => 5000, 'harga_jual' => 7000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'B004', 'barang_nama' => 'Air Mineral', 'harga_beli' => 3000, 'harga_jual' => 5000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'B005', 'barang_nama' => 'Minyak Goreng', 'harga_beli' => 14000, 'harga_jual' => 16000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Barang dari Supplier 2
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'B006', 'barang_nama' => 'Garam', 'harga_beli' => 2000, 'harga_jual' => 4000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'B007', 'barang_nama' => 'Sabun Mandi', 'harga_beli' => 5000, 'harga_jual' => 7000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'B008', 'barang_nama' => 'Shampoo', 'harga_beli' => 15000, 'harga_jual' => 17000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'B009', 'barang_nama' => 'Detergen', 'harga_beli' => 12000, 'harga_jual' => 14000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'B010', 'barang_nama' => 'Pewangi Pakaian', 'harga_beli' => 8000, 'harga_jual' => 10000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Barang dari Supplier 3
            ['barang_id' => 11, 'kategori_id' => 1, 'barang_kode' => 'B011', 'barang_nama' => 'Susu', 'harga_beli' => 15000, 'harga_jual' => 17000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 12, 'kategori_id' => 2, 'barang_kode' => 'B012', 'barang_nama' => 'Kopi Instan', 'harga_beli' => 5000, 'harga_jual' => 7000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 13, 'kategori_id' => 3, 'barang_kode' => 'B013', 'barang_nama' => 'Kecap Manis', 'harga_beli' => 8000, 'harga_jual' => 10000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 14, 'kategori_id' => 4, 'barang_kode' => 'B014', 'barang_nama' => 'Obat Flu', 'harga_beli' => 20000, 'harga_jual' => 22000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['barang_id' => 15, 'kategori_id' => 5, 'barang_kode' => 'B015', 'barang_nama' => 'Tisu', 'harga_beli' => 6000, 'harga_jual' => 8000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('m_barang')->insert($data);
    }
}
