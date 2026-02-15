<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    protected $casts = [
        'nominal' => 'integer',
        'aktif'   => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAktif(Builder $query)
    {
        return $query->where('aktif', true);
    }

    public function scopeUntukTahun(Builder $query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    public function scopeUntukJenisKelamin(Builder $query, $jenisKelamin)
    {
        return $query->where(function ($q) use ($jenisKelamin) {
            $q->where('jenis_kelamin', $jenisKelamin)
              ->orWhere('jenis_kelamin', 'semua');
        });
    }
}