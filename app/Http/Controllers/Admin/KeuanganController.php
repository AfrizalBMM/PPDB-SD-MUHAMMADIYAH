<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagihanSiswa;

class KeuanganController extends Controller
{
    public function index()
    {
        $tagihan = TagihanSiswa::with([
                'siswa.registration.tahunAjaran',
                'biaya',
                'pembayaran',
                'voucher',
            ])
            ->orderByRaw("status = 'belum_lunas' DESC")
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('keuangan.index', compact('tagihan'));
    }
}