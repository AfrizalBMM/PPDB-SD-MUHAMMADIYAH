<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaudTk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaudTkController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 30);
        if (!in_array($perPage, [10, 20, 50, 100], true)) {
            $perPage = 30;
        }

        return view('admin.paud-tk.index', [
            'data' => PaudTk::orderBy('nama')->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
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

        foreach ($rows as $index => $row) {

            // skip header
            if ($index == 0) continue;

            if (empty($row[1])) continue; // kolom nama (B)

            $paud = PaudTk::firstOrNew([
                'nama'       => $row[1],
                'kelurahan'  => $row[4] ?? null,
                'kecamatan'  => $row[5] ?? null
            ]);

            $isNew = !$paud->exists;

            $paud->npsn       = $row[0] ?? null;
            $paud->jenis      = $row[2] ?? null;
            $paud->alamat     = $row[3] ?? null;
            $paud->kelurahan  = $row[4] ?? null;
            $paud->kecamatan  = $row[5] ?? null;
            $paud->telp       = $row[6] ?? null;
            $paud->akreditasi = $row[7] ?? null;
            $paud->aktif      = isset($row[8]) ? (bool)$row[8] : true;

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