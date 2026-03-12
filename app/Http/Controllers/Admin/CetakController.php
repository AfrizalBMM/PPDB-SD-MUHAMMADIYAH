<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\LogCetak;    
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
            'alamat',
            'ibu',
            'ayah',
            'wali',
            'dataPendukung',
            'tagihan.biaya',
            'tagihan.voucher',
        ]);

        logAktivitas(
            'Cetak Formulir',
            'Cetak formulir pendaftaran #' . $siswa->id . ' ' . $siswa->nama .
            ' (' . $siswa->registration->nomor_registrasi . ')'
        );

        $pdf = Pdf::loadView('cetak.formulir', compact('siswa'))
            ->setPaper('f4', 'portrait'); // PALING AMAN

        return $pdf->stream(
            'Formulir-' . $siswa->registration->nomor_registrasi . '.pdf'
        );
    }

    public function cetakFormulir(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'nama_petugas' => 'required|string|max:255'
        ]);

        $siswa = Siswa::with([
            'ibu','ayah','alamat','dataPendukung','registration'
        ])->findOrFail($request->siswa_id);

        // SIMPAN LOG CETAK
        LogCetak::create([
            'siswa_id' => $siswa->id,
            'jenis_dokumen' => 'formulir',
            'nama_petugas' => $request->nama_petugas
        ]);

        $pdf = Pdf::loadView('pdf.formulir', [
            'siswa'   => $siswa,
            'petugas' => $request->nama_petugas
        ]);

        // F4 custom size
        $pdf->setPaper([0, 0, 595, 935], 'portrait');

        return $pdf->stream("FORMULIR-{$siswa->nama}.pdf");
    }

    /**
     * CETAK NOTA PEMBAYARAN (2 PER A4)
     * NAMA ADMIN DIINPUT SAAT CETAK
     */
    public function nota(Request $request, Pembayaran $pembayaran)
{
    $request->validate([
        'nama_admin' => 'required|string|max:100'
    ]);

    $pembayaran->load([
        'tagihan.siswa.registration.tahunAjaran',
        'tagihan.siswa.alamat',
        'tagihan.biaya',
        'tagihan.voucher',
    ]);

    $namaAdmin = $request->nama_admin;

    logAktivitas(
        'Cetak Nota',
        'Cetak nota pembayaran #'.$pembayaran->id.
        ' untuk siswa '.$pembayaran->tagihan->siswa->nama.
        ' oleh admin '.$namaAdmin
    );

    $pdf = Pdf::loadView(
        'cetak.nota-2x-a4',
        compact('pembayaran','namaAdmin')
    )->setPaper('a4', 'portrait');

    return $pdf->stream(
        'Kwitansi-'.$pembayaran->id.'.pdf'
    );
}
}
