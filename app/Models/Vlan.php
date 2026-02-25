<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Vlan extends Model
{
    protected $table = 'vlans';
    protected $fillable = [
        'vlan_id',
        'nama',
        'departemen',
        'bandwidth_allocated',
        'subnet',
        'gateway',
        'deskripsi',
        'aktif'
    ];
    protected $casts = ['aktif' => 'boolean'];
    public function detailLaporan()
    {
        return $this->hasMany(DetailVlanLaporan::class, 'id_vlan');
    }
}
