<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayah extends Model
{
    protected $table = 'ayah';

    protected $fillable = [
        'siswa_id',
        'nama',
        'nik',
        'tahun_lahir',
        'pendidikan',
        'pekerjaan',
        'pekerjaan_lainnya',
        'penghasilan',
        'no_hp',
    ];
}
