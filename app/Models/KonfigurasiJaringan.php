<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KonfigurasiJaringan extends Model
{
    protected $table = 'konfigurasi_jaringan';
    protected $fillable = [
        'nama_perusahaan',
        'isp_utama',
        'isp_backup',
        'bandwidth_utama',
        'bandwidth_backup',
        'perangkat_utama',
        'konfigurasi_qos',
        'konfigurasi_failover',
        'konfigurasi_vlan',
        'catatan',
        'updated_by'
    ];
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
