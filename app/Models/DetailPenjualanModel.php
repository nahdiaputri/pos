<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class DetailPenjualanModel extends Model
 {
     use HasFactory;
 
     protected $table = 't_penjualan_detail';
     protected $primaryKey = 'detail_id';
     public $timestamps = false;
 
     protected $fillable = [
         'penjualan_id',
         'barang_id',
         'harga',
         'jumlah',
         'created_at',
         'updated_at'
     ];
 
     public function penjualan()
     {
         return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
     }
 
     // Relasi ke model Barang
     public function barang()
     {
         return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
     }
 
     // Method untuk mendapatkan subtotal
     public function getSubtotalAttribute()
     {
         return $this->harga * $this->jumlah;
     }
 }