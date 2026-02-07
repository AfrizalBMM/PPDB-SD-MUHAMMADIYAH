<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'jenis_biaya',
        'diskon_nominal',
        'maks_penggunaan',
        'digunakan',
        'aktif',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function masihBerlaku(): bool
    {
        return $this->aktif
            && $this->digunakan < $this->maks_penggunaan
            && now()->between($this->tanggal_mulai, $this->tanggal_selesai);
    }
}
