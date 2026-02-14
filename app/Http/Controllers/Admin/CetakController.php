<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Siswa;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CetakController extends Controller
{
    /**
     * CETAK FORMULIR PENDAFTARAN (F4)
     */
    public function formulir(Siswa $siswa)
    {
        $siswa->load([
            'registration.tahunAjaran',
            'ibu',
            'ayah',
            'wali',
            'dataPendukung',
        ]);

        logAktivitas(
            'Cetak Formulir',
            'Cetak formulir pendaftaran #'.$siswa->id.' '.$siswa->nama.
            ' ('.$siswa->registration->nomor_registrasi.')'
        );

        $pdf = Pdf::loadView('cetak.formulir', compact('siswa'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait'); // F4 REAL

        return $pdf->stream(
            'Formulir-'.$siswa->registration->nomor_registrasi.'.pdf'
        );
    }

    /**
     * CETAK NOTA PEMBAYARAN (2 PER A4)
     * NAMA ADMIN DIINPUT SAAT CETAK
     */
    public function nota(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'nama_admin' => 'required'
        ]);

        $pembayaran->load([
            'tagihan.siswa.registration',
            'tagihan.biaya',
        ]);

        $namaAdmin = $request->nama_admin;

        $pdf = Pdf::loadView(
            'cetak.nota-2x-a4',
            compact('pembayaran','namaAdmin')
        )->setPaper('a4', 'portrait');

        logAktivitas(
            'Cetak Nota',
            'Cetak nota pembayaran #'.$pembayaran->id.
            ' untuk siswa '.$pembayaran->tagihan->siswa->nama.
            ' oleh admin '.$request->nama_admin
        );

        return $pdf->stream(
            'Kwitansi-'.$pembayaran->id.'.pdf'
        );
    }
}
