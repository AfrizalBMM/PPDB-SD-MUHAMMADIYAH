<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Biaya;
use App\Models\Siswa;
use Illuminate\Http\Request;


class PendaftaranController extends Controller
{
    private function formatFilterList(array $values): string
    {
        return empty($values) ? '-' : implode(', ', $values);
    }

    public function sukses(Siswa $siswa)
    {
        $siswa->load([
            'registration',
            'ibu',
            'ayah',
            'wali',
            'alamat',
            'dataPendukung.paudTk',
            'tagihan.biaya',
        ]);

        logAktivitas(
            'Panitia Public - Lihat Halaman Sukses',
            'Membuka halaman sukses pendaftaran siswa ' . ($siswa->nama ?? '-') .
            ' (ID: ' . $siswa->id . ', No Registrasi: ' . ($siswa->registration->nomor_registrasi ?? '-') . ').'
        );

        return view('pendaftaran.sukses', compact('siswa'));
    }

    public function list(Request $request)
    {
        if (!$request->has('order')) {
            return redirect()->route('pendaftaran.list', array_merge($request->query(), [
                'order' => 'terbaru',
            ]));
        }

        $search = $request->search;
        $paymentStatuses = collect((array) $request->input('payment_statuses', []))
            ->filter(fn ($status) => in_array($status, ['lunas', 'belum_lunas'], true))
            ->unique()
            ->values()
            ->all();
        $biayaIds = collect((array) $request->input('biaya_ids', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
        $jenisKelamins = collect((array) $request->input('jenis_kelamins', []))
            ->filter(fn ($jenisKelamin) => in_array($jenisKelamin, ['laki-laki', 'perempuan'], true))
            ->unique()
            ->values()
            ->all();

        $validatedDateRange = validator($request->only(['date_from', 'date_to']), [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ])->validate();

        $dateFrom = $validatedDateRange['date_from'] ?? null;
        $dateTo = $validatedDateRange['date_to'] ?? null;
        $order = $request->order === 'terbaru' ? 'terbaru' : 'terlama';

        $siswa = Siswa::with([
                'registration',
                'ibu',
                'tagihan.biaya',
            ])
            ->withSum('tagihan', 'total')
            ->when(!empty($paymentStatuses), function ($q) use ($paymentStatuses, $biayaIds) {
                $q->where(function ($q1) use ($paymentStatuses, $biayaIds) {
                    if (in_array('lunas', $paymentStatuses, true)) {
                        $q1->orWhere(function ($q2) use ($biayaIds) {
                            // Jika jenis biaya dipilih, status lunas dihitung pada biaya terpilih saja.
                            if (!empty($biayaIds)) {
                                $q2->whereHas('tagihan', function ($q3) use ($biayaIds) {
                                    $q3->whereIn('biaya_id', $biayaIds);
                                })->whereDoesntHave('tagihan', function ($q3) use ($biayaIds) {
                                    $q3->whereIn('biaya_id', $biayaIds)
                                        ->where('status', '!=', 'lunas')
                                        ->where('total', '>', 0);
                                });
                                return;
                            }

                            $q2->whereHas('tagihan')
                                ->whereDoesntHave('tagihan', function ($q3) {
                                    $q3->where('status', '!=', 'lunas')
                                        ->where('total', '>', 0);
                                });
                        });
                    }

                    if (in_array('belum_lunas', $paymentStatuses, true)) {
                        $q1->orWhere(function ($q2) use ($biayaIds) {
                            // Jika jenis biaya dipilih, status belum lunas dihitung pada biaya terpilih saja.
                            if (!empty($biayaIds)) {
                                $q2->whereHas('tagihan', function ($q3) use ($biayaIds) {
                                    $q3->whereIn('biaya_id', $biayaIds)
                                        ->where('status', '!=', 'lunas')
                                        ->where('total', '>', 0);
                                });
                                return;
                            }

                            $q2->whereDoesntHave('tagihan')
                                ->orWhereHas('tagihan', function ($q3) {
                                    $q3->where('status', '!=', 'lunas')
                                        ->where('total', '>', 0);
                                });
                        });
                    }
                });
            })
            ->when(!empty($biayaIds), function ($q) use ($biayaIds) {
                $q->whereHas('tagihan', function ($q2) use ($biayaIds) {
                    $q2->whereIn('biaya_id', $biayaIds);
                });
            })
            ->when(!empty($jenisKelamins), function ($q) use ($jenisKelamins) {
                $q->whereIn('jenis_kelamin', $jenisKelamins);
            })
            ->when(!empty($dateFrom), function ($q) use ($dateFrom) {
                $q->whereHas('registration', function ($q2) use ($dateFrom) {
                    $q2->whereDate('tanggal_daftar', '>=', $dateFrom);
                });
            })
            ->when(!empty($dateTo), function ($q) use ($dateTo) {
                $q->whereHas('registration', function ($q2) use ($dateTo) {
                    $q2->whereDate('tanggal_daftar', '<=', $dateTo);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                        ->orWhereHas('registration', function ($q3) use ($search) {
                            $q3->where('nomor_registrasi', 'like', "%$search%");
                        })
                        ->orWhereHas('ibu', function ($q4) use ($search) {
                            $q4->where('nama', 'like', "%$search%")
                                ->orWhere('no_hp', 'like', "%$search%");
                        });
                });
            })
            ->when($order === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc');
            }, function ($q) {
                $q->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc');
            })
            ->paginate(10)
            ->withQueryString();

        $biayaOptions = Biaya::orderBy('nama_biaya')->get(['id', 'nama_biaya']);

        if ($search || !empty($paymentStatuses) || !empty($biayaIds) || !empty($jenisKelamins) || !empty($dateFrom) || !empty($dateTo) || (int) $request->input('page', 1) > 1) {
            logAktivitas(
                'Panitia Public - Lihat Daftar Pendaftaran',
                'Melihat daftar pendaftar dengan filter: '
                . 'kata kunci "' . ($search ?: '-') . '", '
                . 'status pembayaran ' . $this->formatFilterList($paymentStatuses) . ', '
                . 'biaya ID ' . $this->formatFilterList($biayaIds) . ', '
                . 'jenis kelamin ' . $this->formatFilterList($jenisKelamins) . ', '
                . 'rentang tanggal ' . ($dateFrom ?: '-') . ' s/d ' . ($dateTo ?: '-') . ', '
                . 'urutan ' . $order . ', '
                . 'halaman ' . (int) $request->input('page', 1) . '.'
            );
        }

        return view('pendaftaran.list', compact('siswa', 'search', 'paymentStatuses', 'biayaIds', 'jenisKelamins', 'dateFrom', 'dateTo', 'order', 'biayaOptions'));
    }

    public function show($id)
    {
        $siswa = Siswa::with([
            'registration',
            'alamat',
            'ibu',
            'ayah',
            'wali',
            'dataPendukung.paudTk',
            'tagihan.biaya'
        ])->findOrFail($id);

        $showNik = (bool) session()->pull('show_nik_once_' . $id, false);

        logAktivitas(
            'Panitia Public - Lihat Detail Pendaftaran',
            'Membuka detail pendaftar ' . ($siswa->nama ?? '-')
            . ' (ID: ' . $siswa->id
            . ', No Registrasi: ' . ($siswa->registration->nomor_registrasi ?? '-')
            . ', NIK terlihat: ' . ($showNik ? 'ya' : 'tidak') . ').'
        );

        return view('pendaftaran.detail', compact('siswa', 'showNik'));
    }

    public function showNik($id)
    {
        session(['show_nik_once_' . $id => true]);

        logAktivitas('Panitia Public - Tampilkan NIK', 'Meminta menampilkan NIK untuk siswa ID ' . $id . '.');

        return redirect()->route('pendaftaran.detail', [
            'id' => $id,
        ]);
    }

    public function edit($id)
    {
        $siswa = Siswa::with([
            'registration',
            'alamat',
            'ibu',
            'ayah',
            'wali',
            'dataPendukung.paudTk',
            'tagihan.biaya'
        ])->findOrFail($id);

        logAktivitas(
            'Panitia Public - Buka Form Edit',
            'Membuka form edit data pendaftar ' . ($siswa->nama ?? '-')
            . ' (ID: ' . $siswa->id
            . ', No Registrasi: ' . ($siswa->registration->nomor_registrasi ?? '-') . ').'
        );

        return view('pendaftaran.detail-edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::with(['alamat', 'ibu', 'ayah', 'dataPendukung'])->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'nik' => 'nullable|string|max:20',
            'no_kk' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'akta_no' => 'nullable|string|max:50',
            'agama' => 'nullable|string|max:30',
            'kewarganegaraan' => 'nullable|string|max:30',
            'berkebutuhan_khusus' => 'nullable|string|max:100',
            'tinggal_bersama' => 'nullable|string|max:30',
            'transportasi' => 'nullable|string|max:50',

            'alamat' => 'nullable|string|max:500',
            'provinsi' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kode_pos' => 'nullable|string|max:10',

            'ibu_nama' => 'nullable|string|max:255',
            'ibu_nik' => 'nullable|string|max:20',
            'ibu_no_hp' => 'nullable|string|max:20',

            'ayah_nama' => 'nullable|string|max:255',
            'ayah_nik' => 'nullable|string|max:20',

            'tinggi' => 'nullable|string|max:10',
            'berat' => 'nullable|string|max:10',
            'jarak' => 'nullable|string|max:20',
            'jumlah_saudara' => 'nullable|string|max:10',
            'anak_ke' => 'nullable|integer|min:1|max:99',
            'hobi' => 'nullable|string|max:255',
            'cita_cita' => 'nullable|string|max:255',
            'alamat_tk' => 'nullable|string|max:255',
        ]);

        $siswa->update([
            'nama' => $validated['nama'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'nik' => $validated['nik'] ?? null,
            'no_kk' => $validated['no_kk'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'akta_no' => $validated['akta_no'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'kewarganegaraan' => $validated['kewarganegaraan'] ?? null,
            'berkebutuhan_khusus' => $validated['berkebutuhan_khusus'] ?? null,
            'tinggal_bersama' => $validated['tinggal_bersama'] ?? null,
            'transportasi' => $validated['transportasi'] ?? null,
        ]);

        $siswa->alamat()->updateOrCreate(
            ['siswa_id' => $siswa->id],
            [
                'alamat' => $validated['alamat'] ?? null,
                'provinsi' => $validated['provinsi'] ?? null,
                'kabupaten' => $validated['kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kelurahan' => $validated['kelurahan'] ?? null,
                'rt' => $validated['rt'] ?? null,
                'rw' => $validated['rw'] ?? null,
                'kode_pos' => $validated['kode_pos'] ?? null,
            ]
        );

        $siswa->ibu()->updateOrCreate(
            ['siswa_id' => $siswa->id],
            [
                'nama' => $validated['ibu_nama'] ?? null,
                'nik' => $validated['ibu_nik'] ?? null,
                'no_hp' => $validated['ibu_no_hp'] ?? null,
            ]
        );

        $siswa->ayah()->updateOrCreate(
            ['siswa_id' => $siswa->id],
            [
                'nama' => $validated['ayah_nama'] ?? null,
                'nik' => $validated['ayah_nik'] ?? null,
            ]
        );

        $siswa->dataPendukung()->updateOrCreate(
            ['siswa_id' => $siswa->id],
            [
                'tinggi' => $validated['tinggi'] ?? null,
                'berat' => $validated['berat'] ?? null,
                'jarak' => $validated['jarak'] ?? null,
                'jumlah_saudara' => $validated['jumlah_saudara'] ?? null,
                'anak_ke' => $validated['anak_ke'] ?? null,
                'hobi' => $validated['hobi'] ?? null,
                'cita_cita' => $validated['cita_cita'] ?? null,
                'alamat_tk' => $validated['alamat_tk'] ?? null,
            ]
        );

        logAktivitas(
            'Panitia Public - Update Data Pendaftaran',
            'Menyimpan perubahan data pendaftar ' . ($siswa->nama ?? '-')
            . ' (ID: ' . $siswa->id
            . ', No Registrasi: ' . ($siswa->registration->nomor_registrasi ?? '-') . ').'
        );

        return redirect()
            ->route('pendaftaran.detail', ['id' => $siswa->id])
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function showBiaya(Siswa $siswa)
    {
        $siswa->load([
            'registration.voucher',
            'ibu',
            'tagihan.biaya'
        ]);

        logAktivitas(
            'Panitia Public - Lihat Pembiayaan',
            'Membuka ringkasan pembiayaan siswa ' . ($siswa->nama ?? '-')
            . ' (ID: ' . $siswa->id
            . ', No Registrasi: ' . ($siswa->registration->nomor_registrasi ?? '-') . ').'
        );

        return view('pendaftaran.pembiayaan.biaya', compact('siswa'));
    }

}
