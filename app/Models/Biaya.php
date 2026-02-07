<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    protected $table = 'biaya';

    protected $fillable = [
        'tahun_ajaran_id',
        'jenis_biaya',
        'kategori',
        'jenis_kelamin',
        'nama_biaya',
        'nominal',
        'aktif',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
