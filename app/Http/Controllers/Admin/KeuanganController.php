<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;

class KeuanganController extends Controller
{
    public function index()
    {
        $siswa_list = Siswa::with([
                'registration.tahunAjaran',
                'tagihan.biaya',
                'tagihan.pembayaran' => function ($query) {
                    $query->orderByDesc('tanggal_bayar')->orderByDesc('created_at');
                },
                'tagihan.voucher',
            ])
            ->whereHas('tagihan')
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('keuangan.index', compact('siswa_list'));
    }
}