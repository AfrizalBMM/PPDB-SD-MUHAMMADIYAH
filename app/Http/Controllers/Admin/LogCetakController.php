<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\LogCetak;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LogCetakController extends Controller
{
    // ================== CETAK FORMULIR ==================
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

    // ================== LIST LOG ==================
    public function index()
    {
        $logs = LogCetak::with('siswa')->latest()->get();

        return view('admin.log-cetak.index', compact('logs'));
    }
}
