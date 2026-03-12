<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AksesPembayaran
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('akses_pembayaran')) {

            return redirect()->back()
                ->with('error','Masukkan password panitia terlebih dahulu');

        }

        return $next($request);
    }
}