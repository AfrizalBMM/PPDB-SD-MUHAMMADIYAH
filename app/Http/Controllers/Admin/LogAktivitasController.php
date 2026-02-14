<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\LogAktivitas::query();

        // SEARCH KEYWORD (user, aksi, keterangan)
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('aksi', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orWhereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                });
            });
        }

        // FILTER ROLE
        if ($request->role) {
            $query->where('role', $request->role);
        }

        $logs = $query->latest()->paginate(50);

        // Jika request AJAX (dari live search), kembalikan partial table saja
        if ($request->ajax()) {
            return view('admin.log.logs-table', compact('logs'))->render();
        }

        return view('admin.log.log-aktivitas', compact('logs'));
    }

    public function destroyAll()
    {
        \App\Models\LogAktivitas::truncate(); // hapus semua log
        return redirect()->route('log.aktivitas')->with('success', 'Semua log berhasil dihapus!');
    }
        

}
