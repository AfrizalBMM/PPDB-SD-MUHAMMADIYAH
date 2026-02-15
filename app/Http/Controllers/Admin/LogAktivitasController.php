<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        // ================= SEARCH =================
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('aksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // ================= FILTER ROLE =================
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // ================= FILTER TANGGAL =================
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs = $query
            ->orderByDesc('created_at')
            ->paginate(50);

        if ($request->ajax()) {
            return view('admin.log.logs-table', compact('logs'))->render();
        }

        return view('admin.log.log-aktivitas', compact('logs'));
    }

    public function destroyAll()
    {
        // ❗ Hanya boleh admin tertentu (opsional)
        if (!auth()->user()->role === 'superadmin') {
            abort(403);
        }

        $count = LogAktivitas::count();

        LogAktivitas::truncate();

        logAktivitas(
            'Kelola Log',
            "Menghapus semua log aktivitas ($count data)"
        );

        return redirect()
            ->route('log.aktivitas')
            ->with('success', 'Semua log berhasil dihapus!');
    }
}