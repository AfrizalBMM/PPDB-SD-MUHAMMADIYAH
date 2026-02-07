<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\TagihanSiswa;

class KeuanganController extends Controller
{
    public function index()
    {
        $tagihan = TagihanSiswa::with([
            'siswa.registration',
            'biaya',
            'pembayaran'
        ])->orderBy('status')->get();

        return view('keuangan.index', compact('tagihan'));
    }
}
