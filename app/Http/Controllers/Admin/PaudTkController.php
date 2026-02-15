<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaudTk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaudTkController extends Controller
{
    public function index()
    {
        return view('admin.paud-tk.index', [
            'data' => PaudTk::orderBy('nama')->paginate(30)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'jenis' => 'required|in:PAUD,TK',
            'npsn'  => 'nullable|string|max:20',
            'telp'  => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'npsn','nama','jenis','alamat',
            'kelurahan','kecamatan','telp','akreditasi'
        ]);

        $data['aktif'] = $request->boolean('aktif');

        $paud = PaudTk::create($data);

        logAktivitas(
            'Kelola PAUD/TK',
            'Menambahkan PAUD/TK baru: "'.$paud->nama.'" ('.$paud->jenis.')'
        );

        return back()->with('success', 'Data PAUD/TK berhasil disimpan!');
    }

    public function toggle(PaudTk $paudTk)
    {
        $paudTk->update([
            'aktif' => !$paudTk->aktif
        ]);

        logAktivitas(
            'Kelola PAUD/TK',
            ($paudTk->aktif ? 'Mengaktifkan' : 'Menonaktifkan').
            ' PAUD/TK #'.$paudTk->id.' "'.$paudTk->nama.'"'
        );

        return back();
    }

    public function destroy(PaudTk $paudTk)
    {
        // ❗ Jangan hapus jika sudah dipakai siswa
        if ($paudTk->dataPendukung()->exists()) {
            return back()->with('error','PAUD/TK sudah digunakan oleh siswa dan tidak dapat dihapus.');
        }

        $nama = $paudTk->nama;
        $id   = $paudTk->id;

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

        $rows = Excel::toArray([], $request->file('file'))[0] ?? [];

        $countNew = 0;
        $countUpdated = 0;

        foreach ($rows as $row) {

            if (empty($row['nama'])) continue;

            $paud = PaudTk::firstOrNew([
                'nama'       => $row['nama'],
                'kelurahan'  => $row['kelurahan'] ?? null,
                'kecamatan'  => $row['kecamatan'] ?? null
            ]);

            $isNew = !$paud->exists;

            $paud->npsn       = $row['npsn'] ?? $paud->npsn;
            $paud->jenis      = $row['jenis'] ?? $paud->jenis;
            $paud->alamat     = $row['alamat'] ?? $paud->alamat;
            $paud->telp       = $row['telp'] ?? $paud->telp;
            $paud->akreditasi = $row['akreditasi'] ?? $paud->akreditasi;
            $paud->aktif      = isset($row['aktif']) ? (bool)$row['aktif'] : $paud->aktif;

            $paud->save();

            $isNew ? $countNew++ : $countUpdated++;
        }

        logAktivitas(
            'Import PAUD/TK',
            "Import file Excel: {$request->file('file')->getClientOriginalName()} | ".
            "$countNew data baru, $countUpdated data diperbarui"
        );

        return back()->with(
            'success',
            "Import selesai! $countNew data baru, $countUpdated data diperbarui."
        );
    }

    public function template(): BinaryFileResponse
    {
        $file = storage_path('app/template_paud_tk.xlsx');

        if (!file_exists($file)) {
            abort(404, 'Template tidak ditemukan.');
        }

        return response()->download($file, 'template-paud-tk.xlsx');
    }

    public function destroyAll()
    {
        // ❗ Hanya hapus yang belum pernah dipakai
        $count = PaudTk::doesntHave('dataPendukung')->count();

        PaudTk::doesntHave('dataPendukung')->delete();

        logAktivitas(
            'Kelola PAUD/TK',
            "Menghapus $count data PAUD/TK yang belum digunakan"
        );

        return back()->with('success','Data yang belum digunakan berhasil dihapus.');
    }
}