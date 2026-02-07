<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    protected $table = 'wali';

    protected $fillable = [
        'siswa_id',
        'nama',
        'nik',
        'hubungan',
        'no_hp',
        'alamat',
    ];
}
