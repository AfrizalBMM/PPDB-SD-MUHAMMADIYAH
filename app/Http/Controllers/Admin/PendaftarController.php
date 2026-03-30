<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PendaftarExport;
use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Registration;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PendaftarController extends Controller
{
    private function ensureRegistration(Siswa $siswa)
    {
        if (!$siswa->registration) {
            abort(422, 'Data registrasi siswa tidak ditemukan.');
        }

        if (empty($siswa->registration->status)) {
            $siswa->registration->status = Registration::STATUS_BAKAL_CALON;
            $siswa->registration->save();
        }

        return $siswa->registration;
    }

    private function resolveFilterContext(Request $request): array
    {
        $validStatuses = [
            Registration::STATUS_BAKAL_CALON,
            Registration::STATUS_CALON,
            Registration::STATUS_PESERTA_DIDIK,
        ];
        $validJenisKelamin = ['laki-laki', 'perempuan'];
        $validPaymentStatus = ['lunas', 'belum_lunas', 'belum_ada_tagihan'];

        $status = in_array((int) $request->input('status'), $validStatuses, true)
            ? (int) $request->input('status')
            : null;
        $jenisKelamin = in_array($request->input('jenis_kelamin'), $validJenisKelamin, true)
            ? $request->input('jenis_kelamin')
            : null;
        $paymentStatus = in_array($request->input('payment_status'), $validPaymentStatus, true)
            ? $request->input('payment_status')
            : null;
        $tahunAjaranId = $request->filled('tahun_ajaran_id') && is_numeric($request->tahun_ajaran_id)
            ? (int) $request->tahun_ajaran_id
            : null;
        $order = $request->input('order') === 'terlama' ? 'terlama' : 'terbaru';

        $query = Siswa::with([
            'registration.tahunAjaran',
            'ibu',
            'tagihan.biaya',
            'tagihan.pembayaran',
        ]);

        if ($request->filled('q')) {
            $search = trim((string) $request->q);

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhereHas('registration', function ($q2) use ($search) {
                      $q2->where('nomor_registrasi', 'like', "%{$search}%");
                  });
            });
        }

        if (!is_null($status)) {
            $query->whereHas('registration', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        if (!empty($jenisKelamin)) {
            $query->where('jenis_kelamin', $jenisKelamin);
        }

        if (!empty($tahunAjaranId)) {
            $query->whereHas('registration', function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            });
        }

        if ($paymentStatus === 'lunas') {
            $query->whereHas('tagihan', function ($q) {
                $q->where('total', '>', 0);
            })->whereDoesntHave('tagihan', function ($q) {
                $q->where('total', '>', 0)
                    ->where('status', '!=', 'lunas');
            });
        } elseif ($paymentStatus === 'belum_lunas') {
            $query->whereHas('tagihan', function ($q) {
                $q->where('total', '>', 0)
                    ->where('status', '!=', 'lunas');
            });
        } elseif ($paymentStatus === 'belum_ada_tagihan') {
            $query->whereDoesntHave('tagihan', function ($q) {
                $q->where('total', '>', 0);
            });
        }

        if ($order === 'terbaru') {
            $query->orderByDesc('created_at')->orderByDesc('id');
        } else {
            $query->orderBy('created_at')->orderBy('id');
        }

        return [
            'query' => $query,
            'filters' => [
                'q' => (string) $request->input('q', ''),
                'status' => $status,
                'jenis_kelamin' => $jenisKelamin,
                'payment_status' => $paymentStatus,
                'tahun_ajaran_id' => $tahunAjaranId,
                'order' => $order,
            ],
            'tahunAjaranOptions' => TahunAjaran::query()
                ->orderByDesc('aktif')
                ->orderByDesc('id')
                ->get(['id', 'nama', 'aktif']),
        ];
    }

    public function index(Request $request)
    {
        $context = $this->resolveFilterContext($request);
        $query = $context['query'];
        $perPage = (int) $request->input('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100], true)) {
            $perPage = 20;
        }

        return view('admin.pendaftar.index', [
            'siswa' => $query
                ->paginate($perPage)
                ->withQueryString(),
            'filters' => $context['filters'],
            'tahunAjaranOptions' => $context['tahunAjaranOptions'],
            'isArsipPage' => false,
            'perPage' => $perPage,
        ]);
    }

    public function arsip(Request $request)
    {
        return redirect()
            ->route('pendaftar.index')
            ->with('error', 'Mode arsip sudah tidak digunakan pada flow status PPDB baru.');
    }

    public function export(Request $request)
    {
        $format = $request->input('format');
        if (!in_array($format, ['excel', 'pdf'], true)) {
            return redirect()->route('pendaftar.index')->with('error', 'Format export tidak valid.');
        }

        $context = $this->resolveFilterContext($request);
        $rows = $context['query']->get();

        if ($format === 'excel') {
            logAktivitas('Export Pendaftar', 'Export data pendaftar ke Excel (' . $rows->count() . ' baris).');
            return Excel::download(new PendaftarExport($rows), 'pendaftar-' . now()->format('Ymd-His') . '.xlsx');
        }

        logAktivitas('Export Pendaftar', 'Export data pendaftar ke PDF (' . $rows->count() . ' baris).');
        $pdf = Pdf::loadView('admin.pendaftar.export-pdf', ['rows' => $rows]);

        return $pdf->download('pendaftar-' . now()->format('Ymd-His') . '.pdf');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load([
            // ================= REGISTRATION =================
            'registration.tahunAjaran',

            // ================= IDENTITAS TAMBAHAN =================
            'alamat',

            // ================= ORANG TUA =================
            'ibu',
            'ayah',
            'wali',

            // ================= DATA PENDUKUNG =================
            'dataPendukung.paudTk',

            // ================= KEUANGAN =================
            'tagihan.biaya',
            'tagihan.pembayaran',
            'tagihan.voucher',
        ]);

        $aktivitas = LogAktivitas::query()
            ->where(function ($q) use ($siswa) {
                $q->where('keterangan', 'like', '%Siswa ID: ' . $siswa->id . '%')
                    ->orWhere('keterangan', 'like', '%siswa ID ' . $siswa->id . '%')
                    ->orWhere('keterangan', 'like', '%(ID: ' . $siswa->id . '%')
                    ->orWhere('keterangan', 'like', '%ID ' . $siswa->id . '%')
                    ->orWhere('aksi', 'like', '%' . $siswa->nama . '%');
            })
            ->latest()
            ->limit(30)
            ->get();

        return view('admin.pendaftar.show', compact('siswa', 'aktivitas'));
    }

    public function quickUpdate(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'no_kk' => 'required|digits:16',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        $before = [
            'nama' => $siswa->nama,
            'nik' => $siswa->nik,
            'no_kk' => $siswa->no_kk,
            'jenis_kelamin' => $siswa->jenis_kelamin,
        ];

        $siswa->update([
            'nama' => $validated['nama'],
            'nik' => $validated['nik'],
            'no_kk' => $validated['no_kk'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
        ]);

        logAktivitas(
            'Admin - Quick Edit Pendaftar',
            'Siswa ID: ' . $siswa->id
            . ' | Nama: ' . $before['nama'] . ' -> ' . $validated['nama']
            . ' | NIK: ' . ($before['nik'] ?: '-') . ' -> ' . $validated['nik']
            . ' | No KK: ' . ($before['no_kk'] ?: '-') . ' -> ' . $validated['no_kk']
            . ' | JK: ' . ($before['jenis_kelamin'] ?: '-') . ' -> ' . $validated['jenis_kelamin']
        );

        return back()->with('success', 'Data pendaftar berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Siswa $siswa)
    {
        return $this->jadikanPesertaDidik($siswa);
    }

    public function jadikanPesertaDidik(Siswa $siswa)
    {
        $registration = $this->ensureRegistration($siswa);
        $beforeStatus = (int) ($registration->status ?: Registration::STATUS_BAKAL_CALON);

        if ($beforeStatus === Registration::STATUS_PESERTA_DIDIK) {
            return back()->with('success', 'Data sudah berstatus Peserta Didik.');
        }

        $registration->status = Registration::STATUS_PESERTA_DIDIK;
        $registration->save();

        logAktivitas(
            'Admin - Jadikan Peserta Didik',
            'Siswa ID: ' . $siswa->id
            . ' | No Registrasi: ' . ($registration->nomor_registrasi ?? '-')
            . ' | Status PPDB: ' . Registration::statusLabel($beforeStatus)
            . ' -> ' . Registration::statusLabel(Registration::STATUS_PESERTA_DIDIK)
        );

        return back()->with('success', 'Status berhasil diperbarui menjadi Peserta Didik.');
    }

    public function toggleArsip(Siswa $siswa)
    {
        return back()->with('error', 'Fitur arsip dinonaktifkan pada flow status PPDB baru.');
    }

    public function activity(Siswa $siswa)
    {
        return redirect()
            ->route('pendaftar.show', $siswa)
            ->with('scroll_to_activity', true);
    }
}