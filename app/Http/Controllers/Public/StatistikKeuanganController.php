<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Exports\StatistikKeuanganExport;
use App\Models\Pembayaran;
use App\Models\TagihanSiswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StatistikKeuanganController extends Controller
{
    public function index(Request $request)
    {
        [$dateFrom, $dateTo] = $this->resolveDateFilter($request);
        $statistikData = $this->buildStatistikData($dateFrom, $dateTo);

        $namaPetugas = (string) session('nama_petugas_keuangan_public', '-');

        logAktivitas(
            'Petugas Keuangan Public - Lihat Statistik Keuangan',
            'Membuka halaman statistik keuangan oleh petugas ' . $namaPetugas
            . ' (total biaya: Rp ' . number_format((int) $statistikData['jumlahBiayaKeseluruhan'], 0, ',', '.')
            . ', lunas nominal: Rp ' . number_format((int) $statistikData['jumlahLunasNominal'], 0, ',', '.')
            . ', uang masuk periode: Rp ' . number_format((int) $statistikData['jumlahUangMasukPeriode'], 0, ',', '.') . ').'
        );

        return view('pendaftaran.statistik-keuangan', array_merge($statistikData, [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'namaPetugas' => $namaPetugas,
        ]));
    }

    public function exportExcel(Request $request)
    {
        [$dateFrom, $dateTo] = $this->resolveDateFilter($request);
        $statistikData = $this->buildStatistikData($dateFrom, $dateTo);

        $namaPetugas = (string) session('nama_petugas_keuangan_public', '-');
        logAktivitas(
            'Petugas Keuangan Public - Export Statistik Keuangan Excel',
            'Export statistik keuangan ke Excel oleh petugas ' . $namaPetugas
            . ' (' . $statistikData['periodeLabel'] . ').'
        );

        return Excel::download(
            new StatistikKeuanganExport($statistikData),
            $this->buildExportFilename($dateFrom, $dateTo, 'xlsx')
        );
    }

    public function exportPdf(Request $request)
    {
        [$dateFrom, $dateTo] = $this->resolveDateFilter($request);
        $statistikData = $this->buildStatistikData($dateFrom, $dateTo);
        $namaPetugas = (string) session('nama_petugas_keuangan_public', '-');

        logAktivitas(
            'Petugas Keuangan Public - Export Statistik Keuangan PDF',
            'Export statistik keuangan ke PDF oleh petugas ' . $namaPetugas
            . ' (' . $statistikData['periodeLabel'] . ').'
        );

        $pdf = Pdf::loadView('pendaftaran.statistik-keuangan-export-pdf', array_merge($statistikData, [
            'namaPetugas' => $namaPetugas,
        ]));

        return $pdf->download($this->buildExportFilename($dateFrom, $dateTo, 'pdf'));
    }

    public function logout()
    {
        $namaPetugas = (string) session('nama_petugas_keuangan_public', '-');

        session()->forget([
            'akses_keuangan_public',
            'nama_petugas_keuangan_public',
        ]);

        logAktivitas(
            'Petugas Keuangan Public - Logout Akses Keuangan',
            'Petugas ' . $namaPetugas . ' keluar dari akses statistik keuangan public.'
        );

        return redirect()
            ->route('pendaftaran.list')
            ->with('success', 'Logout akses keuangan berhasil.');
    }

    private function resolveDateFilter(Request $request): array
    {
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        return [
            $validated['date_from'] ?? null,
            $validated['date_to'] ?? null,
        ];
    }

    private function buildStatistikData(?string $dateFrom, ?string $dateTo): array
    {
        $tagihanBase = TagihanSiswa::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('tagihan_siswa.created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('tagihan_siswa.created_at', '<=', $dateTo));

        $jumlahBiayaKeseluruhan = (int) (clone $tagihanBase)->sum('total');
        $jumlahSisaPiutang = (int) (clone $tagihanBase)->sum('sisa');
        $jumlahPendaftar = (int) (clone $tagihanBase)->distinct('siswa_id')->count('siswa_id');

        $jumlahBiayaPerJenis = TagihanSiswa::query()
            ->join('biaya', 'biaya.id', '=', 'tagihan_siswa.biaya_id')
            ->when($dateFrom, fn ($q) => $q->whereDate('tagihan_siswa.created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('tagihan_siswa.created_at', '<=', $dateTo))
            ->selectRaw('biaya.jenis_biaya as jenis_biaya, SUM(tagihan_siswa.total) as total_jenis')
            ->groupBy('biaya.jenis_biaya')
            ->orderBy('biaya.jenis_biaya')
            ->get();

        $totalTagihanCount = (int) (clone $tagihanBase)->count();
        $jumlahLunasNominal = (int) (clone $tagihanBase)->where('status', 'lunas')->sum('total');
        $jumlahLunasCount = (int) (clone $tagihanBase)->where('status', 'lunas')->count();
        $persentasePelunasan = $totalTagihanCount > 0
            ? round(($jumlahLunasCount / $totalTagihanCount) * 100, 1)
            : 0;

        $pembayaranBase = Pembayaran::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('tanggal_bayar', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('tanggal_bayar', '<=', $dateTo));

        $jumlahUangMasukPeriode = (int) (clone $pembayaranBase)->sum('nominal_bayar');

        $riwayatPembayaranHariIni = Pembayaran::with([
            'tagihan.siswa:id,nama,registration_id',
            'tagihan.siswa.registration:id,nomor_registrasi',
            'tagihan.biaya:id,jenis_biaya',
        ])
            ->whereDate('tanggal_bayar', today())
            ->orderByDesc('tanggal_bayar')
            ->orderByDesc('id')
            ->get();

        $updatedAt = now();

        $tahunAjaranAktif = TahunAjaran::where('aktif', 1)->first();
        if ($dateFrom || $dateTo) {
            $periodeLabel = 'Rentang '
                . ($dateFrom ?: '...')
                . ' s/d '
                . ($dateTo ?: '...');
        } else {
            $periodeLabel = $tahunAjaranAktif
                ? 'Tahun Ajaran ' . $tahunAjaranAktif->nama
                : 'Semua Data';
        }

        return [
            'jumlahBiayaKeseluruhan' => $jumlahBiayaKeseluruhan,
            'jumlahSisaPiutang' => $jumlahSisaPiutang,
            'jumlahPendaftar' => $jumlahPendaftar,
            'jumlahBiayaPerJenis' => $jumlahBiayaPerJenis,
            'totalTagihanCount' => $totalTagihanCount,
            'jumlahLunasNominal' => $jumlahLunasNominal,
            'jumlahLunasCount' => $jumlahLunasCount,
            'persentasePelunasan' => $persentasePelunasan,
            'jumlahUangMasukPeriode' => $jumlahUangMasukPeriode,
            'riwayatPembayaranHariIni' => $riwayatPembayaranHariIni,
            'updatedAt' => $updatedAt,
            'periodeLabel' => $periodeLabel,
        ];
    }

    private function buildExportFilename(?string $dateFrom, ?string $dateTo, string $extension): string
    {
        $timestamp = now()->format('Ymd-His');

        if ($dateFrom && $dateTo) {
            return 'statistik-keuangan-' . $dateFrom . '_sampai_' . $dateTo . '-' . $timestamp . '.' . $extension;
        }

        if ($dateFrom) {
            return 'statistik-keuangan-dari_' . $dateFrom . '-' . $timestamp . '.' . $extension;
        }

        if ($dateTo) {
            return 'statistik-keuangan-sampai_' . $dateTo . '-' . $timestamp . '.' . $extension;
        }

        return 'statistik-keuangan-semua-data-' . $timestamp . '.' . $extension;
    }
}
