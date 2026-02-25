<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LaporanHarian extends Model
{
    protected $table = 'laporan_harian';
    protected $fillable = [
        'tanggal',
        'total_bandwidth_tersedia',
        'total_bandwidth_terpakai',
        'persentase_bandwidth',
        'grade',
        'status_jaringan',
        'isp_aktif',
        'insiden',
        'tindakan',
        'catatan',
        'id_user'
    ];
    protected $casts = ['tanggal' => 'date'];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function detailVlan()
    {
        return $this->hasMany(DetailVlanLaporan::class, 'id_laporan');
    }
    public function failover()
    {
        return $this->hasMany(LaporanFailover::class, 'id_laporan');
    }
    public function getBadgeGradeAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'success', 'B' => 'info', 'C' => 'warning', 'D' => 'danger', default => 'secondary'
        };
    }
    public function getLabelGradeAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Kurang Baik', default => '-'
        };
    }
}
