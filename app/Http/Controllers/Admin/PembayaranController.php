<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\TagihanSiswa;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required',
            'nominal_bayar' => 'required|numeric|min:1',
        ]);

        $tagihan = TagihanSiswa::with('pembayaran')->findOrFail($request->tagihan_id);

        $dibayar = $tagihan->pembayaran->sum('nominal_bayar');
        $sisa = $tagihan->total - $dibayar;

        if ($request->nominal_bayar > $sisa) {
            return back()->withErrors('Nominal melebihi sisa tagihan');
        }

        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'nominal_bayar' => $request->nominal_bayar,
            'tanggal_bayar' => now(),
            'admin_id' => auth()->id(),
        ]);

        logAktivitas(
            'Pembayaran',
            'Pembayaran '.$tagihan->biaya->nama.
            ' siswa '.$tagihan->siswa->nama.
            ' sebesar Rp '.number_format($request->nominal_bayar)
        );

        // update status tagihan
        if (($dibayar + $request->nominal_bayar) >= $tagihan->total) {
            $tagihan->update(['status' => 'lunas']);
        }

        return redirect()
            ->route('pembayaran.nota', $pembayaran->id)
            ->with('success','Pembayaran berhasil');
    }
}
