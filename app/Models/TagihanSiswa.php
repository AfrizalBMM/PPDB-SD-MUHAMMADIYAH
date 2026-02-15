<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        'voucher_id',
        'kode_voucher',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'diskon'  => 'integer',
        'total'   => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
        return $this->hasMany(Pembayaran::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeBelumLunas(Builder $query)
    {
        return $query->where('status', 'belum_lunas');
    }

    public function scopeLunas(Builder $query)
    {
        return $query->where('status', 'lunas');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getTotalDibayarAttribute()
    {
        if ($this->relationLoaded('pembayaran')) {
            return $this->pembayaran->sum('nominal_bayar');
        }

        return $this->pembayaran()->sum('nominal_bayar');
    }

    public function getSisaAttribute()
    {
        return max(0, $this->total - $this->total_dibayar);
    }

    public function getIsLunasAttribute()
    {
        return $this->sisa <= 0;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function refreshStatus()
    {
        $this->status = $this->is_lunas ? 'lunas' : 'belum_lunas';
        $this->save();
    }
}