<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne; 
 class BarangModel extends Model
 {
     use HasFactory;
 
     protected $table = 'm_barang';
     protected $primaryKey = 'barang_id';
     protected $fillable = [
         'barang_id',
         'kategori_id',
         'barang_kode',
         'barang_nama',
         'harga_beli',
         'harga_jual',
         'image',
     ];
 
     public function kategori():BelongsTo
     {
         return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
     }
     public function getAllBarang()
    {
        return $this->all(); // Mengambil semua data dari tabel barang
    }
    public function stok():BelongsTo
     {
         return $this->belongsTo(StokModel::class, 'barang_id', 'barang_id');
     }

     protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? url('/storage/posts/' . $value) : null
        );
    }
 }
