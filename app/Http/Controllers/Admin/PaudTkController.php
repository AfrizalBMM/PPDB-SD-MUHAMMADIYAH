<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\PaudTk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PaudTkImport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaudTkController extends Controller
{
    public function index()
    {
        return view('admin.paud-tk.index', [
            'data' => PaudTk::orderBy('nama')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:PAUD,TK',
            'npsn' => 'nullable|string|max:20',
            'telp' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'npsn','nama','jenis','alamat',
            'kelurahan','kecamatan','telp','akreditasi'
        ]);

        $data['aktif'] = $request->has('aktif');

        PaudTk::create($data);

        logAktivitas(
            'Kelola PAUD/TK',
            'Menambahkan PAUD/TK baru: "'.$data['nama'].'" ('.$data['jenis'].')'
        );

        // Tambahkan session sukses
        return back()->with('success', 'Data PAUD/TK berhasil disimpan!');
    }


    public function toggle(PaudTk $paudTk)
    {
        $paudTk->aktif = !$paudTk->aktif;
        $paudTk->save();
        logAktivitas(
            'Kelola PAUD/TK',
            ($paudTk->aktif ? 'Mengaktifkan' : 'Menonaktifkan').
            ' PAUD/TK #'.$paudTk->id.' "'.$paudTk->nama.'"'
        );
        return back();
    }

    public function destroy(PaudTk $paudTk)
    {
        $nama = $paudTk->nama;
        $id = $paudTk->id;

        $paudTk->delete();

        logAktivitas(
            'Kelola PAUD/TK',
            'Menghapus PAUD/TK #'.$id.' "'.$nama.'"'
        );
        return back();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Baca file Excel
        $rows = Excel::toArray(new PaudTkImport, $request->file('file'))[0];

        $countNew = 0;
        $countUpdated = 0;

        foreach($rows as $row){
            if(empty($row['nama'])) continue;

            // Merge berdasarkan nama + kelurahan + kecamatan
            $paud = PaudTk::firstOrNew([
                'nama' => $row['nama'],
                'kelurahan' => $row['kelurahan'] ?? null,
                'kecamatan' => $row['kecamatan'] ?? null
            ]);

            // Update atau set field lain
            $paud->npsn = $row['npsn'] ?? $paud->npsn;
            $paud->jenis = $row['jenis'] ?? $paud->jenis;
            $paud->alamat = $row['alamat'] ?? $paud->alamat;
            $paud->telp = $row['telp'] ?? $paud->telp;
            $paud->akreditasi = $row['akreditasi'] ?? $paud->akreditasi;
            $paud->aktif = $row['aktif'] ?? $paud->aktif;

            if($paud->exists){
                $countUpdated++;
            } else {
                $countNew++;
            }

            $paud->save();
        }

        logAktivitas(
            'Import PAUD/TK',
            "Import file Excel: {$request->file('file')->getClientOriginalName()} | ".
            "$countNew data baru, $countUpdated data diperbarui"
        );

        return back()->with('success', "Import selesai! $countNew data baru, $countUpdated data diperbarui.");
    }

    public function template(): BinaryFileResponse
    {
        $file = storage_path('app/template_paud_tk.xlsx');
        return response()->download($file, 'template-paud-tk.xlsx');
    }

    public function destroyAll()
    {
        PaudTk::truncate(); // Hapus semua data
        logAktivitas(
            'Kelola PAUD/TK',
            'Menghapus semua data PAUD/TK'
        );
        return redirect()->back()->with('success', 'Semua data PAUD / TK berhasil dihapus!');
    }

}
