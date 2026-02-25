<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LaporanFailover extends Model
{
    protected $table = 'laporan_failover';
    protected $fillable = [
        'id_laporan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'provider_dari',
        'provider_ke',
        'penyebab',
        'durasi_menit',
        'status',
        'keterangan',
        'id_user'
    ];
    protected $casts = ['tanggal' => 'date'];
    public function laporan()
    {
        return $this->belongsTo(LaporanHarian::class, 'id_laporan');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
