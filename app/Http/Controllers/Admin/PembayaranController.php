<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\TagihanSiswa;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tagihan_siswa_id' => 'required|exists:tagihan_siswa,id',
            'nominal_bayar'    => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, &$pembayaran) {

            $tagihan = TagihanSiswa::with('pembayaran')
                ->lockForUpdate()
                ->findOrFail($request->tagihan_siswa_id);

            // gunakan accessor model
            $sisa = $tagihan->sisa;

            if ($request->nominal_bayar > $sisa) {
                throw new \Exception('Nominal melebihi sisa tagihan.');
            }

            $pembayaran = Pembayaran::create([
                'tagihan_siswa_id' => $tagihan->id,
                'nominal_bayar'    => $request->nominal_bayar,
                'tanggal_bayar'    => now(),
                'admin_id'         => auth()->id(),
            ]);

            // update status via helper model
            $tagihan->refreshStatus();

            logAktivitas(
                'Pembayaran',
                'Pembayaran #'.$pembayaran->id.' '.
                $tagihan->biaya->nama_biaya.' untuk siswa '.$tagihan->siswa->nama.
                ' sebesar Rp '.number_format($request->nominal_bayar).
                ' oleh admin '.auth()->user()->name
            );
        });

        return redirect()
            ->route('pembayaran.nota', $pembayaran->id)
            ->with('success','Pembayaran berhasil');
    }
}