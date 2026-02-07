<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $mulai = $request->mulai;
        $selesai = $request->selesai;

        $query = Pembayaran::with('tagihan.biaya');

        if ($mulai && $selesai) {
            $query->whereBetween('tanggal_bayar', [$mulai, $selesai]);
        }

        $pembayaran = $query->get();

        $total = $pembayaran->sum('nominal_bayar');

        return view('laporan.keuangan', compact(
            'pembayaran','total','mulai','selesai'
        ));
    }
}
