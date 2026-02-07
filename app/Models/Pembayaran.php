<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_siswa_id',
        'tanggal_bayar',
        'nominal_bayar',
        'metode',
        'keterangan',
        'admin_id',
    ];

    public function tagihan()
    {
        return $this->belongsTo(TagihanSiswa::class, 'tagihan_siswa_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
