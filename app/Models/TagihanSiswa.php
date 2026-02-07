<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSiswa extends Model
{
    protected $table = 'tagihan_siswa';

    protected $fillable = [
        'siswa_id',
        'biaya_id',
        'nominal',
        'diskon',
        'total',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(\App\Models\Pembayaran::class);
    }

    public function getTotalDibayarAttribute()
    {
        return $this->pembayaran()->sum('nominal_bayar');
    }

    public function getSisaAttribute()
    {
        return max(0, $this->total - $this->total_dibayar);
    }

}
