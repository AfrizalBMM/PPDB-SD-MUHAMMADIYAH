<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Siswa;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('registration');

        if ($request->q) {
            $query->where('nama','like','%'.$request->q.'%')
                  ->orWhere('nik','like','%'.$request->q.'%');
        }

        return view('admin.pendaftar.index', [
            'siswa' => $query->latest()->paginate(20)
        ]);
    }

    public function show(Siswa $siswa)
    {
        $siswa->load([
            'registration.tahunAjaran',
            'ibu','ayah','wali','dataPendukung'
        ]);

        return view('admin.pendaftar.show', compact('siswa'));
    }
}
