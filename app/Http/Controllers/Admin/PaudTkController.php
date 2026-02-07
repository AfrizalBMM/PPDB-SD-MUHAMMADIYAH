<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\PaudTk;
use Illuminate\Http\Request;

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
            'nama' => 'required',
            'jenis' => 'required'
        ]);

        PaudTk::create($request->all());
        return back();
    }

    public function toggle(PaudTk $paudTk)
    {
        $paudTk->update(['aktif'=>!$paudTk->aktif]);
        return back();
    }

    public function destroy(PaudTk $paudTk)
    {
        $paudTk->delete();
        return back();
    }
}
