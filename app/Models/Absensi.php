<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function pesertaLembur()
    {
        return $this->hasMany(PesertaLembur::class, 'absensi_id');
    }
}
