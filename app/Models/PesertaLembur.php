<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaLembur extends Model
{
    protected $table = 'peserta_lembur';

    protected $guarded = [];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
