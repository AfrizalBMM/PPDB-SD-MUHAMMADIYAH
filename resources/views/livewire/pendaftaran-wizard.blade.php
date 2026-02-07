<div class="max-w-6xl mx-auto px-6 pb-10">

@if ($errors->any())
<div
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    x-data="{ open: true }"
    x-show="open">

    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
        <h2 class="text-lg font-semibold text-red-600 mb-3">
            Data Belum Lengkap
        </h2>

        <p class="text-sm text-slate-600 mb-4">
            Silakan periksa kembali form pendaftaran. Beberapa data wajib belum diisi dengan benar.
        </p>

        <ul class="list-disc list-inside text-sm text-red-600 space-y-1 mb-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <div class="text-right">
            <button
                type="button"
                @click="open = false"
                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                OK
            </button>
        </div>
    </div>
</div>
@endif


<form wire:submit.prevent="simpan" class="space-y-8">

    {{-- HEADER --}}
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Formulir Pendaftaran Calon Siswa
        </h1>
        <p class="text-sm text-slate-500">
            Diisi oleh panitia PPDB
        </p>
    </div>

    {{-- A. DATA UMUM --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">A. Data Umum</h2>

        <div class="grid md:grid-cols-3 gap-5">

            {{-- TANGGAL DAFTAR --}}
            <div>
                <label class="label">
                    Tanggal Daftar <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    wire:model="tanggal_daftar"
                    class="input @error('tanggal_daftar') border-red-500 @enderror">
                @error('tanggal_daftar')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TAHUN AJARAN (READONLY) --}}
            <div>
                <label class="label">
                    Tahun Ajaran
                </label>
                <input
                    type="text"
                    value="{{ $tahun_ajaran_nama ?? 'belum ditentukan admin' }}"
                    readonly
                    class="input bg-slate-100 cursor-not-allowed">
            </div>

            {{-- VOUCHER --}}
            <div>
                <label class="label">Voucher</label>

                <select
                    wire:model="voucher_id"
                    class="input
                        @error('voucher_id') border-red-500 @enderror
                        {{ $voucher_expired ? 'bg-slate-100 cursor-not-allowed' : '' }}"
                    {{ $voucher_expired ? 'disabled' : '' }}>

                    @if($vouchers->count() === 0)
                        <option value="">Voucher tidak tersedia</option>
                    @else
                        <option value="">Pilih Voucher (Opsional)</option>

                        @foreach($vouchers as $v)
                            @php
                                $expired = $v->expired_at && \Carbon\Carbon::parse($v->expired_at)->isPast();
                            @endphp

                            <option value="{{ $v->id }}" @disabled($expired)>
                                {{ $v->kode }} —
                                {{ $v->nama }}
                                @if($expired)
                                    (Periode habis)
                                @endif
                            </option>
                        @endforeach
                    @endif
                </select>

                {{-- INFO DISKON --}}
                @if($voucher_label)
                    <div class="mt-2 p-3 rounded-lg
                        {{ $voucher_expired ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                        <p class="text-sm font-medium">
                            {{ $voucher_label }}
                        </p>
                    </div>
                @endif

                @error('voucher_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- B. IDENTITAS SISWA --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">B. Identitas Siswa</h2>

        <div class="grid md:grid-cols-2 gap-5">

            {{-- NAMA LENGKAP --}}
            <div class="md:col-span-2">
                <label class="label">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="nama_siswa"
                    class="input @error('nama_siswa') border-red-500 @enderror">
                @error('nama_siswa')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- JENIS KELAMIN --}}
            <div>
                <label class="label">
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="jenis_kelamin"
                    class="input @error('jenis_kelamin') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="laki-laki">Laki-laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIK --}}
            <div>
                <label class="label">
                    NIK <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="nik"
                    type="text"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="16 digit"
                    class="input @error('nik') border-red-500 @enderror">
                @error('nik')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NO KK --}}
            <div>
                <label class="label">
                    No KK <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="no_kk"
                    type="text"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="16 digit"
                    class="input @error('no_kk') border-red-500 @enderror">
                @error('no_kk')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TEMPAT LAHIR --}}
            <div>
                <label class="label">
                    Tempat Lahir <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="tempat_lahir"
                    class="input @error('tempat_lahir') border-red-500 @enderror">
                @error('tempat_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TANGGAL LAHIR --}}
            <div>
                <label class="label">
                    Tanggal Lahir <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    wire:model="tanggal_lahir"
                    class="input @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- AKTA --}}
            <div>
                <label class="label">No Akta Lahir</label>
                <input wire:model="akta_no" class="input">
            </div>

            {{-- AGAMA --}}
            <div>
                <label class="label">Agama</label>
                <input value="Islam" disabled class="input bg-slate-100 cursor-not-allowed">
            </div>

            {{-- KEWARGANEGARAAN --}}
            <div>
                <label class="label">Kewarganegaraan</label>
                <input value="Indonesia" disabled class="input bg-slate-100 cursor-not-allowed">
            </div>

            {{-- BERKEBUTUHAN KHUSUS --}}
            <div>
                <label class="label">Berkebutuhan Khusus</label>
                <select wire:model="berkebutuhan_khusus" class="input">
                    <option value="Tidak">Tidak</option>
                    <option value="Ya">Ya</option>
                </select>
            </div>

            {{-- TINGGAL BERSAMA --}}
            <div>
                <label class="label">
                    Tinggal Bersama <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="tinggal_bersama"
                    class="input @error('tinggal_bersama') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="orang_tua">Orang Tua</option>
                    <option value="wali">Wali</option>
                </select>
                @error('tinggal_bersama')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- WALI --}}
            @if($tinggal_bersama === 'wali')
            <div>
                <label class="label">
                    No HP Wali <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="hp_wali"
                    class="input @error('hp_wali') border-red-500 @enderror">
                @error('hp_wali')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">
                    Nama Wali <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="wali_nama"
                    class="input @error('wali_nama') border-red-500 @enderror">
                @error('wali_nama')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">
                    Hubungan dengan Siswa <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="wali_hubungan"
                    placeholder="Paman / Bibi / Kakek / Nenek"
                    class="input @error('wali_hubungan') border-red-500 @enderror">
                @error('wali_hubungan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- TRANSPORTASI --}}
            <div>
                <label class="label">
                    Moda Transportasi <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="transportasi"
                    class="input @error('transportasi') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="jalan_kaki">Jalan Kaki</option>
                    <option value="sepeda">Sepeda</option>
                    <option value="antar_jemput">Antar Jemput</option>
                </select>
                @error('transportasi')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- OPSIONAL --}}
            <div>
                <label class="label">No KKS (Opsional)</label>
                <input wire:model="no_kks" class="input">
            </div>

            <div>
                <label class="label">KPS (Opsional)</label>
                <input wire:model="kps" class="input">
            </div>

            <div>
                <label class="label">KIP (Opsional)</label>
                <input wire:model="kip" class="input">
            </div>

        </div>
    </div>


    {{-- C. ALAMAT --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">C. Alamat</h2>

        {{-- ALAMAT LENGKAP --}}
        <div class="mb-4">
            <label class="label">
                Alamat Lengkap <span class="text-red-500">*</span>
            </label>
            <textarea
                wire:model="alamat"
                class="input @error('alamat') border-red-500 @enderror"></textarea>
            @error('alamat')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid md:grid-cols-4 gap-5">

            {{-- PROVINSI --}}
            <div>
                <label class="label">
                    Provinsi <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="provinsi"
                    class="input @error('provinsi') border-red-500 @enderror">
                @error('provinsi')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KABUPATEN --}}
            <div>
                <label class="label">
                    Kabupaten <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="kabupaten"
                    class="input @error('kabupaten') border-red-500 @enderror">
                @error('kabupaten')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KECAMATAN --}}
            <div>
                <label class="label">
                    Kecamatan <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="kecamatan"
                    class="input @error('kecamatan') border-red-500 @enderror">
                @error('kecamatan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KELURAHAN --}}
            <div>
                <label class="label">
                    Kelurahan <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="kelurahan"
                    class="input @error('kelurahan') border-red-500 @enderror">
                @error('kelurahan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- RT --}}
            <div>
                <label class="label">RT</label>
                <input
                    wire:model="rt"
                    type="text"
                    inputmode="numeric"
                    maxlength="4"
                    placeholder="Maks 4 digit"
                    class="input @error('rt') border-red-500 @enderror">
                @error('rt')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- RW --}}
            <div>
                <label class="label">RW</label>
                <input
                    wire:model="rw"
                    type="text"
                    inputmode="numeric"
                    maxlength="4"
                    placeholder="Maks 4 digit"
                    class="input @error('rw') border-red-500 @enderror">
                @error('rw')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KODE POS --}}
            <div>
                <label class="label">Kode Pos</label>
                <input
                    wire:model="kode_pos"
                    class="input @error('kode_pos') border-red-500 @enderror">
                @error('kode_pos')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>


   {{-- D. DATA ORANG TUA --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">D. Data Orang Tua</h2>

        {{-- ================= DATA IBU ================= --}}
        <div class="grid md:grid-cols-2 gap-5 mb-4">

            <div>
                <label class="label">
                    Nama Ibu <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="ibu_nama"
                    class="input @error('ibu_nama') border-red-500 @enderror">
                @error('ibu_nama')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">
                    No HP Ibu <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="ibu_hp"
                    class="input @error('ibu_hp') border-red-500 @enderror">
                @error('ibu_hp')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="grid md:grid-cols-2 gap-5 mt-4">

            <div>
                <label class="label">NIK Ibu</label>
                <input
                    wire:model="ibu_nik"
                    type="text"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="16 digit"
                    class="input @error('ibu_nik') border-red-500 @enderror">
                @error('ibu_nik')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Tahun Lahir Ibu</label>
                <input
                    type="number"
                    min="1945"
                    wire:model="ibu_tahun_lahir"
                    class="input @error('ibu_tahun_lahir') border-red-500 @enderror">
                @error('ibu_tahun_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PENDIDIKAN IBU --}}
            <div>
                <label class="label">Pendidikan Terakhir Ibu</label>
                <select
                    wire:model="ibu_pendidikan"
                    class="input @error('ibu_pendidikan') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option>Tidak Sekolah</option>
                    <option>SD</option>
                    <option>SMP</option>
                    <option>SMA/K</option>
                    <option>D3</option>
                    <option>S1</option>
                    <option>S2</option>
                    <option>S3</option>
                </select>
                @error('ibu_pendidikan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PEKERJAAN IBU --}}
            <div>
                <label class="label">Pekerjaan Ibu</label>
                <select
                    wire:model="ibu_pekerjaan"
                    class="input @error('ibu_pekerjaan') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option>Tidak Bekerja</option>
                    <option>Karyawan</option>
                    <option>Pegawai</option>
                    <option>Wiraswasta</option>
                    <option>Serabutan</option>
                    <option>Lainnya</option>
                </select>
                @error('ibu_pekerjaan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PEKERJAAN IBU LAINNYA --}}
            @if($ibu_pekerjaan === 'Lainnya')
            <div>
                <label class="label">
                    Pekerjaan Ibu (Lainnya) <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="ibu_pekerjaan_lainnya"
                    class="input @error('ibu_pekerjaan_lainnya') border-red-500 @enderror">
                @error('ibu_pekerjaan_lainnya')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- PENGHASILAN IBU --}}
            <div>
                <label class="label">Penghasilan Ibu</label>
                <select
                    wire:model="ibu_penghasilan"
                    class="input @error('ibu_penghasilan') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="<500k">&lt; Rp 500.000</option>
                    <option value="500-1jt">Rp 501.000 – Rp 1.000.000</option>
                    <option value="1-2jt">Rp 1.001.000 – Rp 2.000.000</option>
                    <option value="2-4jt">Rp 2.001.000 – Rp 4.000.000</option>
                    <option value="4-5jt">Rp 4.001.000 – Rp 5.000.000</option>
                    <option value="5-10jt">Rp 5.001.000 – Rp 10.000.000</option>
                    <option value=">10jt">&gt; Rp 10.000.000</option>
                </select>
                @error('ibu_penghasilan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <hr class="my-6">

        {{-- ================= DATA AYAH ================= --}}
        <h3 class="font-semibold text-slate-700 mb-3">Data Ayah Kandung</h3>

        <div class="grid md:grid-cols-2 gap-5">

            <div>
                <label class="label">Nama Ayah</label>
                <input
                    wire:model="ayah_nama"
                    class="input @error('ayah_nama') border-red-500 @enderror">
                @error('ayah_nama')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">NIK Ayah</label>
                <input
                    wire:model="ayah_nik"
                    type="text"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="16 digit"
                    class="input @error('ayah_nik') border-red-500 @enderror">
                @error('ayah_nik')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Tahun Lahir Ayah</label>
                <input
                    type="number"
                    min="1945"
                    wire:model="ayah_tahun_lahir"
                    class="input @error('ayah_tahun_lahir') border-red-500 @enderror">
                @error('ayah_tahun_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PENDIDIKAN AYAH --}}
            <div>
                <label class="label">Pendidikan Terakhir Ayah</label>
                <select
                    wire:model="ayah_pendidikan"
                    class="input @error('ayah_pendidikan') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option>Tidak Sekolah</option>
                    <option>SD</option>
                    <option>SMP</option>
                    <option>SMA/K</option>
                    <option>D3</option>
                    <option>S1</option>
                    <option>S2</option>
                    <option>S3</option>
                </select>
                @error('ayah_pendidikan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PEKERJAAN AYAH --}}
            <div>
                <label class="label">Pekerjaan Ayah</label>
                <select
                    wire:model="ayah_pekerjaan"
                    class="input @error('ayah_pekerjaan') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option>Tidak Bekerja</option>
                    <option>Karyawan</option>
                    <option>Pegawai</option>
                    <option>Wiraswasta</option>
                    <option>Serabutan</option>
                    <option>Lainnya</option>
                </select>
                @error('ayah_pekerjaan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PEKERJAAN AYAH LAINNYA --}}
            @if($ayah_pekerjaan === 'Lainnya')
            <div>
                <label class="label">
                    Pekerjaan Ayah (Lainnya) <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="ayah_pekerjaan_lainnya"
                    class="input @error('ayah_pekerjaan_lainnya') border-red-500 @enderror">
                @error('ayah_pekerjaan_lainnya')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- PENGHASILAN AYAH --}}
            <div>
                <label class="label">Penghasilan Ayah</label>
                <select
                    wire:model="ayah_penghasilan"
                    class="input @error('ayah_penghasilan') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="<500k">&lt; Rp 500.000</option>
                    <option value="500-1jt">Rp 501.000 – Rp 1.000.000</option>
                    <option value="1-2jt">Rp 1.001.000 – Rp 2.000.000</option>
                    <option value="2-4jt">Rp 2.001.000 – Rp 4.000.000</option>
                    <option value="4-5jt">Rp 4.001.000 – Rp 5.000.000</option>
                    <option value="5-10jt">Rp 5.001.000 – Rp 10.000.000</option>
                    <option value=">10jt">&gt; Rp 10.000.000</option>
                </select>
                @error('ayah_penghasilan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>


    {{-- E. DATA PENDUKUNG --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">E. Data Pendukung</h2>

        <div class="grid md:grid-cols-3 gap-5">

            {{-- TINGGI BADAN --}}
            <div>
                <label class="label">Tinggi Badan (cm)</label>
                <input
                    wire:model="tinggi"
                    type="number"
                    min="0"
                    max="999"
                    placeholder="Maks 3 digit"
                    class="input @error('tinggi') border-red-500 @enderror">
                @error('tinggi')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BERAT BADAN --}}
            <div>
                <label class="label">Berat Badan (kg)</label>
                <input
                    wire:model="berat"
                    type="number"
                    min="0"
                    max="999"
                    placeholder="Maks 3 digit"
                    class="input @error('berat') border-red-500 @enderror">
                @error('berat')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- JARAK --}}
            <div>
                <label class="label">Jarak ke Sekolah (km)</label>
                <input
                    wire:model="jarak"
                    type="number"
                    min="0"
                    max="9999"
                    placeholder="Maks 4 digit"
                    class="input @error('jarak') border-red-500 @enderror">
                @error('jarak')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- JUMLAH SAUDARA --}}
            <div>
                <label class="label">Jumlah Saudara</label>
                <input
                    wire:model="jumlah_saudara"
                    type="number"
                    min="0"
                    max="999"
                    placeholder="Maks 3 digit"
                    class="input @error('jumlah_saudara') border-red-500 @enderror">
                @error('jumlah_saudara')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ASAL PAUD / TK --}}
            <div>
                <label class="label">
                    Asal PAUD / TK
                </label>
                <select
                    wire:model.live="paud_tk_id"
                    class="input @error('paud_tk_id') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    @foreach($paud as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
                @error('paud_tk_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ALAMAT TK --}}
            <div class="md:col-span-3">
                <label class="label">Alamat TK</label>
                <textarea
                    wire:model="alamat_tk"
                    readonly
                    class="input bg-slate-100 cursor-not-allowed">
                </textarea>
            </div>

            {{-- HOBI --}}
            <div>
                <label class="label">Hobi</label>
                <textarea
                    wire:model="hobi"
                    class="input @error('hobi') border-red-500 @enderror"></textarea>
                @error('hobi')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CITA-CITA --}}
            <div>
                <label class="label">Cita-cita</label>
                <input
                    wire:model="cita_cita"
                    class="input @error('cita_cita') border-red-500 @enderror">
                @error('cita_cita')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- HASIL TES --}}
            <div>
                <label class="label">
                    Hasil Tes <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="hasil_tes"
                    class="input @error('hasil_tes') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="SB">Sangat Baik</option>
                    <option value="B">Baik</option>
                    <option value="PB">Perlu Bantuan</option>
                </select>
                @error('hasil_tes')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- SUBMIT --}}
    <div class="flex justify-end pt-4">
        <button class="btn-primary px-10 py-3 text-base">
            Simpan Pendaftaran
        </button>
    </div>


</form>
</div>
