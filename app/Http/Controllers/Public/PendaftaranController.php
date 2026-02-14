<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Siswa;

class PendaftaranController extends Controller
{
    public function sukses(Siswa $siswa)
    {
        $siswa->load([
            'registration',
            'ibu',
            'ayah',
            'wali',
            'alamat',
            'dataPendukung.paudTk',
            'tagihan.biaya',
        ]);

        return view('pendaftaran.sukses', compact('siswa'));
    }
}
