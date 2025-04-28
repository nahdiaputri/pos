<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    protected $table = 't_penjualan'; // sesuaikan dengan nama tabel transaksi
    protected $primaryKey = 'penjualan_id';
    public $timestamps = false;

    protected $fillable = [
        'barang_id', 'user_id', 'penjualan_tanggal', 'jumlah', 'total_harga', 'created_at', 'updated_at'
    ];

    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id');
    }
}
