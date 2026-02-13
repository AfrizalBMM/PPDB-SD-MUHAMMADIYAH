<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPendukung extends Model
{
    protected $table = 'data_pendukung';

    protected $fillable = [
        'siswa_id',
        'tinggi',
        'berat',
        'jarak',
        'jumlah_saudara',
        'paud_tk_id',
        'hobi',
        'cita_cita'
    ];

    public function paudTk()
    {
        return $this->belongsTo(\App\Models\PaudTk::class, 'paud_tk_id');
    }
}
