<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        'aktif' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    public function masihBerlaku(): bool
    {
        if (!$this->aktif) {
            return false;
        }

        if (!$this->dalamPeriode()) {
            return false;
        }

        if (!$this->masihAdaKuota()) {
            return false;
        }

        return true;
    }

    public function dalamPeriode(): bool
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return false;
        }

        return now()->between(
            $this->tanggal_mulai->startOfDay(),
            $this->tanggal_selesai->endOfDay()
        );
    }

    public function masihAdaKuota(): bool
    {
        // jika maks_penggunaan null → unlimited
        if (is_null($this->maks_penggunaan)) {
            return true;
        }

        return $this->digunakan < $this->maks_penggunaan;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAktif(Builder $query)
    {
        return $query->where('aktif', true);
    }

    public function scopeDalamPeriode(Builder $query)
    {
        return $query->whereDate('tanggal_mulai', '<=', now())
                     ->whereDate('tanggal_selesai', '>=', now());
    }
}