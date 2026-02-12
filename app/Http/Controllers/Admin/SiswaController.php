<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function kelas1()
    {
        $siswa = Siswa::with('registration')
            ->whereHas('registration', function ($q) {
                $q->where('status', 'diterima');
            })
            ->orderBy('nama')
            ->paginate(20);

        return view('admin.siswa.kelas1', compact('siswa'));
    }
}
