<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanSiswa;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tagihan_siswa_id' => 'required|exists:tagihan_siswa,id',
            'nominal_bayar' => 'required|numeric|min:1'
        ]);

        $tagihan = TagihanSiswa::findOrFail($request->tagihan_siswa_id);

        if ($request->nominal_bayar > $tagihan->sisa) {
            return back()->with('error','Nominal melebihi sisa tagihan');
        }

        Pembayaran::create([
            'tagihan_siswa_id' => $tagihan->id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'nominal_bayar' => $request->nominal_bayar,
            'metode' => $request->metode,
            'keterangan' => $request->keterangan,
            'admin_penerima' => $request->admin_penerima,
        ]);

        $tagihan->sisa -= $request->nominal_bayar;

        if ($tagihan->sisa <= 0) {
            $tagihan->is_lunas = 1;
            $tagihan->sisa = 0;
        }

        $tagihan->save();

        return redirect()
        ->route('pendaftaran.biaya', $tagihan->siswa_id)
        ->with('success','Pembayaran berhasil disimpan');
    }

    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

        if ($angka < 12) {
            return " " . $baca[$angka];
        } elseif ($angka < 20) {
            return $this->terbilang($angka - 10) . " belas";
        } elseif ($angka < 100) {
            return $this->terbilang($angka / 10) . " puluh" . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            return " seratus" . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $this->terbilang($angka / 100) . " ratus" . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return " seribu" . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return $this->terbilang($angka / 1000) . " ribu" . $this->terbilang($angka % 1000);
        }

        return "";
    }

    public function nota($id)
    {
        $pembayaran = Pembayaran::with([
            'tagihan.biaya',
            'tagihan.siswa'
        ])->findOrFail($id);

        $terbilang = ucwords($this->terbilang($pembayaran->nominal_bayar))." Rupiah";

        $pdf = Pdf::loadView(
            'pendaftaran.cetak.nota',
            compact('pembayaran','terbilang')
        );

        return $pdf->stream('kwitansi.pdf');
    }

}