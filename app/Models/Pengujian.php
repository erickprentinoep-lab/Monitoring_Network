<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengujian extends Model
{
    protected $table = 'pengujian';
    protected $primaryKey = 'id_pengujian';

    protected $fillable = ['tanggal', 'lokasi', 'periode', 'keterangan', 'id_user'];

    protected $casts = ['tanggal' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function parameterQos()
    {
        return $this->hasOne(ParameterQos::class, 'id_pengujian', 'id_pengujian');
    }
}
