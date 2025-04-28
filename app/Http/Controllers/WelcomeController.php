<?php
namespace App\Http\Controllers;
use App\Models\BarangModel;
use App\Models\StokModel;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list'  => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        $barang = BarangModel::all();
        $stok = StokModel::all(); // Ambil semua barang beserta stoknya
    // Atau jika hanya ingin total stok semua barang:
    // $totalStok = BarangModel::sum('barang_stok');

    return view('welcome', [
        'breadcrumb' => $breadcrumb,
        'activeMenu' => $activeMenu,
        'barang' => $barang,
        'stok' => $stok
        // 'totalStok' => $totalStok
    ]);
}


}
