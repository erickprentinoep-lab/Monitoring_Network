<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['nama', 'username', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed'];
    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'id_user');
    }
    public function laporanFailover()
    {
        return $this->hasMany(LaporanFailover::class, 'id_user');
    }
}
