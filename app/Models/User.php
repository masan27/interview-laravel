<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pegawai_id');
    }
}
