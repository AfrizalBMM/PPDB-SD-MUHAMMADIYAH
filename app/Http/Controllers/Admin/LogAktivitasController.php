<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        return view('admin.log.log-aktivitas', [
            'logs' => \App\Models\LogAktivitas::latest()->paginate(50)
        ]);
    }

}
