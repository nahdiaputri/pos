<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; //Implementasi class Authenticatable
 //Implementasi JWTSubject

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this -> getKey();
    }
 
     public function getJWTCustomClaims()
     {
         return [];
     }
 
    protected $table = 'm_user'; // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['username', 'password', 'nama', 'level_id', 'image', 'created_at','updated_at'];
     protected $hidden = ['password'];
     protected $casts = ['password' => 'hashed'];
 
     public function level():BelongsTo
     {
         return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
     }

     protected function image(): Attribute
     {
         return Attribute::make(
             get: fn ($image) => url('/storage/posts/' . $image),
         );
     }
 
     public function getRoleName(): string
     {
         return $this -> level -> level_nama;
     }
 
     public function hasRole($role): bool
     {
         return $this -> level -> level_kode == $role;
     }
 
     public function getRole()
     {
         return $this -> level -> level_kode;
     }

 }