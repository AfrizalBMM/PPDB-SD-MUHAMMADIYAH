<?php

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

if (!function_exists('logAktivitas')) {
    function logAktivitas(string $aksi, string $keterangan = null)
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::check() ? Auth::user()->role : 'public',
            'aksi' => $aksi,
            'keterangan' => $keterangan,
            'ip_address' => Request::ip(),
        ]);
    }
}
