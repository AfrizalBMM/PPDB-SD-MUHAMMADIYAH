<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaudTk extends Model
{
    protected $table = 'paud_tk';
    protected $fillable = [
        'npsn','nama','jenis','alamat',
        'kelurahan','kecamatan','telp',
        'akreditasi','aktif'
    ];
}
