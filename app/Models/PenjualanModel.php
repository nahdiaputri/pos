<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class PenjualanModel extends Model
 {
     use HasFactory;
 
     protected $table = 't_penjualan';
     protected $primaryKey = 'penjualan_id';
 
     protected $fillable = [
         'user_id',
         'pembeli',
         'penjualan_kode',
         'penjualan_tanggal',
     ];
 
     // Relasi ke model User
     public function user()
     {
         return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
     }
 
     // Relasi ke detail penjualan (jika ada)
     public function detailPenjualan()
     {
         return $this->hasMany(DetailPenjualanModel::class, 'penjualan_id', 'penjualan_id');
     }
 }