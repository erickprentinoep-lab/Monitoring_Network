<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterQos extends Model
{
    protected $table = 'parameter_qos';
    protected $primaryKey = 'id_qos';

    protected $fillable = [
        'id_pengujian',
        'throughput',
        'delay',
        'jitter',
        'packet_loss',
        'kategori',
        'kesimpulan'
    ];

    public function pengujian()
    {
        return $this->belongsTo(Pengujian::class, 'id_pengujian', 'id_pengujian');
    }
}
