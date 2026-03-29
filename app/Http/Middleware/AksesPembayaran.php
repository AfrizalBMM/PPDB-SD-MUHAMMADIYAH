<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AksesPembayaran
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('akses_pembayaran')) {

            // Untuk AJAX request, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses ditolak. Silakan verifikasi password panitia terlebih dahulu.',
                    'status' => 'unauthorized'
                ], 403);
            }

            // Untuk regular request, redirect ke halaman yang diminta
            return redirect()
                ->back()
                ->with('error', 'Masukkan password panitia terlebih dahulu');

        }

        return $next($request);
    }
}