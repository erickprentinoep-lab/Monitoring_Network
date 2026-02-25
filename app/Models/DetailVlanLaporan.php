<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DetailVlanLaporan extends Model
{
    protected $table = 'detail_vlan_laporan';
    protected $fillable = [
        'id_laporan',
        'id_vlan',
        'bandwidth_terpakai',
        'persentase',
        'packet_loss',
        'delay',
        'jitter',
        'status',
        'catatan'
    ];
    public function laporan()
    {
        return $this->belongsTo(LaporanHarian::class, 'id_laporan');
    }
    public function vlan()
    {
        return $this->belongsTo(Vlan::class, 'id_vlan');
    }
}
