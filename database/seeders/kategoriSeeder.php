<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kategori_kode' => 'K001',
                'kategori_nama' => 'Makanan',
            ],
            [
                'kategori_kode' => 'K002',
                'kategori_nama' => 'Minuman',
            ],
            [
                'kategori_kode' => 'K003',
                'kategori_nama' => 'Kebutuhan pokok',
            ],
            [
                'kategori_kode' => 'K004',
                'kategori_nama' => 'Kesehatan',
            ],
            [
                'kategori_kode' => 'K005',
                'kategori_nama' => 'Kebutuhan rumah tangga',
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}