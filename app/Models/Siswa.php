<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'registration_id', 'nama', 'jenis_kelamin',
        'nik', 'no_kk', 'tempat_lahir',
        'tanggal_lahir', 'akta_no', 'hasil_tes'
    ];

    public function ayah()
    {
        return $this->hasOne(\App\Models\Ayah::class);
    }

    public function ibu()
    {
        return $this->hasOne(\App\Models\Ibu::class);
    }

    public function wali()
    {
        return $this->hasOne(\App\Models\Wali::class);
    }

    public function tagihan()
    {
        return $this->hasMany(\App\Models\TagihanSiswa::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function alamat()
    {
        return $this->hasOne(\App\Models\AlamatSiswa::class);
    }

    public function dataPendukung()
    {
        return $this->hasOne(\App\Models\DataPendukung::class);
    }

    public function semuaTagihanLunas(): bool
    {
        return $this->tagihan()->where('status','!=','lunas')->count() === 0;
    }
}
