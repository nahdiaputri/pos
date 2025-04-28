<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class penjualanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'John Doe',
                'penjualan_kode' => 'FJ-20210101-001',
                'penjualan_tanggal' => '2021-01-01 10:00:00',
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 2,
                'pembeli' => 'Jane Smith',
                'penjualan_kode' => 'FJ-20210102-002',
                'penjualan_tanggal' => '2021-01-02 11:00:00',
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 2,
                'pembeli' => 'Alice Johnson',
                'penjualan_kode' => 'FJ-20210103-003',
                'penjualan_tanggal' => '2021-01-03 12:00:00',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'Bob Brown',
                'penjualan_kode' => 'FJ-20210104-004',
                'penjualan_tanggal' => '2021-01-04 13:00:00',
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'Pembeli' => 'Ahmad Ramadhan',
                'penjualan_kode' => 'FJ-20210104-005',
                'penjualan_tanggal' => '2021-01-04 14:00:00'
            ]
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
