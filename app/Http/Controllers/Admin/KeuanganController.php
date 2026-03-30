<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 30);
        if (!in_array($perPage, [10, 20, 50, 100], true)) {
            $perPage = 30;
        }

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
            ->paginate($perPage)
            ->withQueryString();

        return view('keuangan.index', compact('siswa_list', 'perPage'));
    }
}