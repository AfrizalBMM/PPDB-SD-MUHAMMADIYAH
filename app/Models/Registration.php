<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'nomor_registrasi',
        'tanggal_daftar',
        'tahun_ajaran_id',
        'voucher_id',
        'status',
        'input_by',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    // ================= RELATIONS =================

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}