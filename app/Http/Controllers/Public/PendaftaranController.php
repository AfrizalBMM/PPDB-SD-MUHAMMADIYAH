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

    public function list()
    {
        $siswa = Siswa::latest()->get();

        return view('pendaftaran.list', compact('siswa'));
    }

    public function show($id)
    {
        $siswa = Siswa::with([
            'registration',
            'alamat',
            'ibu',
            'ayah',
            'dataPendukung.paudTk',
            'tagihan.biaya'
        ])->findOrFail($id);

        return view('pendaftaran.detail', compact('siswa'));
    }

}
