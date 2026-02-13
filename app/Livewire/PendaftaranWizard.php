<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TahunAjaran;
use App\Models\PaudTk;

use App\Models\Registration;
use App\Models\Siswa;
use Illuminate\Support\Str;

use App\Models\DataPendukung;
use App\Models\Ayah;
use App\Models\Ibu;
use App\Models\Wali;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\AlamatSiswa;

use App\Services\GenerateTagihanService;

class PendaftaranWizard extends Component
{
    // STEP MODAL
    public $errorsTriggered = false;
    public $showConfirm = false;

    // STEP A – UMUM
    public $tanggal_daftar;
    public $kelas = 1;
    public $tahun_ajaran_id;
    public $tahun_ajaran_nama;
    public $voucher_id;
    public $voucher_diskon = 0;
    public $voucher_label;
    public $voucher_expired = false;

    // STEP B – SISWA
    public $nama_siswa;
    public $jenis_kelamin;
    public $nik;
    public $no_kk;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $akta_no;
    public $agama = 'Islam';
    public $kewarganegaraan = 'Indonesia';
    public $berkebutuhan_khusus = 'Tidak';
    public $tinggal_bersama;
    public $hp_wali;
    public $transportasi;
    public $no_kks;
    public $kps;
    public $kip;

    // STEP B – WALI
    public $wali_nama;
    public $wali_hubungan;

    // STEP C – ALAMAT
    public $alamat;
    public $provinsi;
    public $kabupaten;
    public $kecamatan;
    public $kelurahan;
    public $rt;
    public $rw;
    public $kode_pos;

    // STEP D – AYAH
    public $ayah_nama;
    public $ayah_nik;
    public $ayah_hp;
    public $ayah_tahun_lahir;
    public $ayah_pendidikan;
    public $ayah_pekerjaan;
    public $ayah_pekerjaan_lainnya;
    public $ayah_penghasilan;

    // STEP E – IBU
    public $ibu_nama;
    public $ibu_nik;
    public $ibu_tahun_lahir;
    public $ibu_pendidikan;
    public $ibu_pekerjaan;
    public $ibu_hp;
    public $ibu_pekerjaan_lainnya;
    public $ibu_penghasilan;

    // STEP F – PENDUKUNG
    public $tinggi;
    public $berat;
    public $jarak;
    public $jumlah_saudara;
    public $paud_tk_id;
    public $hasil_tes;
    public $alamat_tk;
    public $hobi;
    public $cita_cita;

    public function mount()
    {
        $this->tanggal_daftar = now()->format('Y-m-d');

        $tahun = TahunAjaran::where('aktif', true)->first();

        if ($tahun) {
            $this->tahun_ajaran_id   = $tahun->id;
            $this->tahun_ajaran_nama = $tahun->nama;
        }
    }

    public function updatedVoucherId($id)
    {
        $this->voucher_diskon = 0;
        $this->voucher_label = null;
        $this->voucher_expired = false;

        if (!$id) return;

        $voucher = Voucher::find($id);
        if (!$voucher) return;

        // cek expired
        if ($voucher->expired_at && Carbon::parse($voucher->expired_at)->isPast()) {
            $this->voucher_expired = true;
            $this->voucher_label = 'Voucher periode habis';
            return;
        }

        // hitung diskon
        if ($voucher->tipe === 'nominal') {
            $this->voucher_diskon = (int) $voucher->nilai;
            $this->voucher_label = 'Diskon Rp ' . number_format($voucher->nilai, 0, ',', '.');
        }

        if ($voucher->tipe === 'persen') {
            $this->voucher_diskon = (int) $voucher->nilai;
            $this->voucher_label = 'Diskon ' . $voucher->nilai . '%';
        }
    }

    public function prepareSubmit()
    {
        $this->resetErrorBag();
        $this->errorsTriggered = false;

        if ($this->tinggal_bersama !== 'wali') {
            $this->hp_wali = null;
            $this->wali_nama = null;
            $this->wali_hubungan = null;
        }

        try {
            $this->validate(); // validasi default semua rules
            $this->showConfirm = true; // munculkan modal konfirmasi
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorsTriggered = true; // munculkan modal error
            dd('prepareSubmit ERROR', $e->errors());
        }
    }

    public function submitForm()
    {
        $this->showConfirm = false; // tutup modal konfirmasi

        // panggil simpan, ambil ID siswa untuk redirect
        $siswaId = $this->simpan();

        if ($siswaId) {
            return redirect()->route('pendaftaran.sukses', $siswaId);
        }
    }


    public function simpan()
    {
        $this->resetErrorBag();
        $this->errorsTriggered = false;

        // 1. Buat rules validasi lengkap
        $rules = [
            'tanggal_daftar' => 'required|date',
            'nama_siswa' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'nik' => 'required|string|size:16',
            'no_kk' => 'required|string|size:16',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'transportasi' => 'required|string',
            'hasil_tes' => 'required|string',
        ];

        // validasi Wali jika tinggal dengan wali
        if ($this->tinggal_bersama === 'wali') {
            $rules['wali_nama'] = 'required|string';
            $rules['wali_hubungan'] = 'required|string';
            $rules['hp_wali'] = 'required|string|digits_between:10,14';
        } else {
            $rules['hp_wali'] = 'nullable|string|digits_between:10,14';
        }

        try {
            $validatedData = $this->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorsTriggered = true;
            return null; // stop jika ada error
        }

        $siswa = null; // inisialisasi sebelum transaction

        DB::transaction(function () use (&$siswa) {
            $reg = Registration::create([
                'nomor_registrasi' => 'REG-'.date('Y').'-'.Str::upper(Str::random(6)),
                'tanggal_daftar' => $this->tanggal_daftar,
                'tahun_ajaran_id' => $this->tahun_ajaran_id,
                'status' => 'pending',
                'input_by' => auth()->check() ? auth()->id() : null
            ]);

            $siswa = Siswa::create([
                'registration_id' => $reg->id,
                'nama' => $this->nama_siswa,
                'jenis_kelamin' => $this->jenis_kelamin,
                'nik' => $this->nik,
                'no_kk' => $this->no_kk,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'akta_no' => $this->akta_no,
                'agama' => $this->agama,
                'kewarganegaraan' => $this->kewarganegaraan,
                'berkebutuhan_khusus' => $this->berkebutuhan_khusus,
                'tinggal_bersama' => $this->tinggal_bersama,
                'hp_wali' => $this->hp_wali,
                'transportasi' => $this->transportasi,
                'no_kks' => $this->no_kks,
                'kps' => $this->kps,
                'kip' => $this->kip,
                'hasil_tes' => $this->hasil_tes,
            ]);

            AlamatSiswa::create([
                'siswa_id' => $siswa->id,
                'alamat' => $this->alamat,
                'provinsi' => $this->provinsi,
                'kabupaten' => $this->kabupaten,
                'kecamatan' => $this->kecamatan,
                'kelurahan' => $this->kelurahan,
                'rt' => $this->rt ? (int) $this->rt : null,
                'rw' => $this->rw ? (int) $this->rw : null,
                'kode_pos' => $this->kode_pos,
            ]);

            Ibu::create([
                'siswa_id' => $siswa->id,
                'nama' => $this->ibu_nama,
                'nik' => $this->ibu_nik,
                'tahun_lahir' => $this->ibu_tahun_lahir,
                'pendidikan' => $this->ibu_pendidikan,
                'pekerjaan' => $this->ibu_pekerjaan,
                'pekerjaan_lainnya' => $this->ibu_pekerjaan_lainnya,
                'penghasilan' => $this->ibu_penghasilan,
                'no_hp' => $this->ibu_hp,
            ]);

            if ($this->ayah_nama) {
                Ayah::create([
                    'siswa_id' => $siswa->id,
                    'nama' => $this->ayah_nama,
                    'nik' => $this->ayah_nik,
                    'tahun_lahir' => $this->ayah_tahun_lahir,
                    'pendidikan' => $this->ayah_pendidikan,
                    'pekerjaan' => $this->ayah_pekerjaan,
                    'pekerjaan_lainnya' => $this->ayah_pekerjaan_lainnya,
                    'penghasilan' => $this->ayah_penghasilan,
                    'no_hp' => $this->ayah_hp,
                ]);
            }

            if ($this->tinggal_bersama === 'wali') {
                Wali::create([
                    'siswa_id' => $siswa->id,
                    'nama' => $this->wali_nama,
                    'hubungan' => $this->wali_hubungan,
                    'no_hp' => $this->hp_wali,
                ]);
            }

            DataPendukung::create([
                'siswa_id' => $siswa->id,
                'tinggi' => $this->tinggi ? (int) $this->tinggi : null,
                'berat' => $this->berat ? (int) $this->berat : null,
                'jarak' => $this->jarak ? (int) $this->jarak : null,
                'jumlah_saudara' => $this->jumlah_saudara ? (int) $this->jumlah_saudara : null,
                'paud_tk_id' => $this->paud_tk_id,
                'alamat_tk' => $this->alamat_tk,
                'hobi' => $this->hobi,
                'cita_cita' => $this->cita_cita,
            ]);

            GenerateTagihanService::generate($siswa, $this->voucher_id);

            logAktivitas(
                'Pendaftaran Siswa',
                'Pendaftaran siswa '.$siswa->nama.' ('.$reg->nomor_registrasi.')'
            );
        });

        // return ID siswa untuk redirect
        return $siswa ? $siswa->id : null;
    }

    protected function messages()
    {
        return [

            // ================= DATA UMUM =================
            'tanggal_daftar.required' => 'Tanggal daftar wajib diisi.',
            'tanggal_daftar.date'     => 'Format tanggal daftar tidak valid.',
            'tahun_ajaran_id.required' => 'Tahun ajaran belum ditentukan oleh admin.',
            'tahun_ajaran_id.exists'   => 'Tahun ajaran tidak valid.',
            'voucher_id.exists' => 'Voucher tidak valid.',


            // ================= SISWA =================
            'nama_siswa.required'    => 'Nama siswa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'       => 'Jenis kelamin tidak valid.',

            'nik.required' => 'NIK wajib diisi.',
            'nik.digits'   => 'NIK harus terdiri dari 16 digit.',

            'no_kk.required' => 'Nomor KK wajib diisi.',
            'no_kk.digits'   => 'Nomor KK harus terdiri dari 16 digit.',

            'tempat_lahir.required'  => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'     => 'Format tanggal lahir tidak valid.',

            'tinggal_bersama.required' => 'Status tinggal bersama wajib dipilih.',
            'tinggal_bersama.in'       => 'Status tinggal bersama tidak valid.',

            'transportasi.required' => 'Moda transportasi wajib dipilih.',

            // ================= ALAMAT =================
            'alamat.required'    => 'Alamat lengkap wajib diisi.',
            'provinsi.required'  => 'Provinsi wajib diisi.',
            'kabupaten.required' => 'Kabupaten wajib diisi.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'kelurahan.required' => 'Kelurahan wajib diisi.',

            'rt.digits_between' => 'RT harus 1 sampai 4 digit.',
            'rw.digits_between' => 'RW harus 1 sampai 4 digit.',
            'kode_pos.digits_between' => 'Kode pos tidak valid.',

            // ================= IBU =================
            'ibu_nama.required' => 'Nama ibu wajib diisi.',

            'ibu_nik.required' => 'NIK ibu wajib diisi.',
            'ibu_nik.digits'   => 'NIK ibu harus 16 digit.',

            'ibu_hp.required'        => 'Nomor HP ibu wajib diisi.',
            'ibu_hp.digits_between' => 'Nomor HP ibu harus 10 sampai 14 digit.',

            'ibu_pekerjaan_lainnya.required_if' =>
                'Pekerjaan ibu wajib diisi jika memilih "Lainnya".',

            // ================= AYAH =================
            'ayah_nik.digits' => 'NIK ayah harus 16 digit.',
            'ayah_hp.digits_between' => 'Nomor HP ayah harus 10 sampai 14 digit.',

            'ayah_pekerjaan_lainnya.required_if' =>
                'Pekerjaan ayah wajib diisi jika memilih "Lainnya".',

            // ================= WALI =================
            'wali_nama.required_if' =>
                'Nama wali wajib diisi jika siswa tinggal bersama wali.',

            'wali_hubungan.required_if' =>
                'Hubungan dengan wali wajib diisi.',

            'hp_wali.required_if' =>
                'Nomor HP wali wajib diisi.',

            'hp_wali.digits_between' =>
                'Nomor HP wali harus 10 sampai 14 digit.',

            // ================= DATA PENDUKUNG =================
            'paud_tk_id.exists' => 'Asal PAUD / TK tidak valid.',

            'hasil_tes.required' => 'Hasil tes wajib dipilih.',
            'hasil_tes.in'       => 'Hasil tes tidak valid.',
        ];
    }

    protected function rules()
    {
        return [

            // DATA UMUM
            'tanggal_daftar' => 'required|date',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'voucher_id' => 'nullable|exists:vouchers,id',

            // SISWA
            'nama_siswa'        => 'required|string|max:100',
            'jenis_kelamin'     => 'required|in:laki-laki,perempuan',
            'nik'               => 'required|digits:16',
            'no_kk'             => 'required|digits:16',
            'tanggal_lahir'     => 'required|date',
            'tempat_lahir'      => 'required|string|max:50',

            'tinggal_bersama'   => 'required|in:orang_tua,wali',
            'transportasi'      => 'required',

            // ALAMAT
            'alamat'            => 'required|string',
            'provinsi'          => 'required|string|max:50',
            'kabupaten'         => 'required|string|max:50',
            'kecamatan'         => 'required|string|max:50',
            'kelurahan'         => 'required|string|max:50',
            'rt'                => 'nullable|digits_between:1,4',
            'rw'                => 'nullable|digits_between:1,4',
            'kode_pos'          => 'nullable|digits_between:3,6',

            // IBU
            'ibu_nama'          => 'required|string|max:100',
            'ibu_nik'           => 'required|digits:16',
            'ibu_hp'            => 'required|digits_between:10,14',
            'ibu_tahun_lahir'   => 'nullable|integer|min:1945|max:' . date('Y'),
            'ibu_pendidikan'    => 'nullable|string',
            'ibu_pekerjaan'     => 'nullable|string',
            'ibu_pekerjaan_lainnya'  => 'required_if:ibu_pekerjaan,Lainnya',
            'ibu_penghasilan'   => 'nullable|string',

            // AYAH
            'ayah_nik'          => 'nullable|digits:16',
            'ayah_hp'           => 'nullable|digits_between:10,14',
            'ayah_pekerjaan_lainnya' => 'required_if:ayah_pekerjaan,Lainnya',
            'ayah_tahun_lahir'  => 'nullable|integer|min:1945|max:' . date('Y'),

            // WALI
            'wali_nama'         => 'required_if:tinggal_bersama,wali',
            'wali_hubungan'     => 'required_if:tinggal_bersama,wali',
            'hp_wali' => $this->tinggal_bersama === 'wali'
            ? 'required|digits_between:10,14'
            : 'nullable|digits_between:10,14',

            // DATA PENDUKUNG
            'tinggi'            => 'nullable|integer|max:999',
            'berat'             => 'nullable|integer|max:999',
            'jarak'             => 'nullable|integer|max:9999',
            'jumlah_saudara'    => 'nullable|integer|max:999',

            'paud_tk_id'        => 'nullable|exists:paud_tk,id',
            'alamat_tk' => 'nullable|string|max:255',
            'hasil_tes'         => 'required|in:SB,B,PB',
        ];
    }

    public function updatedPaudTkId($id)
    {
        if (!$id) {
            $this->alamat_tk = null;
            return;
        }

        $paud = PaudTk::find($id);

        if ($paud) {
            $this->alamat_tk = "{$paud->kelurahan} - {$paud->kecamatan}";
        }
    }

    public function render()
    {
        return view('livewire.pendaftaran-wizard', [
            'paud' => PaudTk::where('aktif', true)->get(),
            'vouchers' => Voucher::orderBy('nama')->get(),
        ])->layout('layouts.public');
    }


}
