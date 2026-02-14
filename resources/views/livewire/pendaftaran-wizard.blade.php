<div class="max-w-6xl mx-auto px-6 pb-10">

@if ($errorsTriggered && $errors->any())
<div
    x-data="{ open: @entangle('errorsTriggered') }"
    x-show="open"
    x-transition
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
>
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
        <h2 class="text-lg font-semibold text-red-600 mb-3">Data Pendaftaran Belum Lengkap</h2>

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
                @click="open = false; $wire.errorsTriggered = false"
                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90"
            >
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

    {{-- ================= A. DATA UMUM ================= --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">A. Data Umum</h2>

        <div class="grid md:grid-cols-3 gap-5">

            {{-- TANGGAL DAFTAR --}}
            <div>
                <label class="label">
                    Tanggal Daftar <span class="text-red-500">*</span>
                </label>
                {{-- Tidak perlu request tiap ketikan, pakai defer --}}
                <input type="date"
                    wire:model.defer="tanggal_daftar"
                    class="input @error('tanggal_daftar') border-red-500 @enderror">
                @error('tanggal_daftar')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TAHUN AJARAN --}}
            <div>
                <label class="label">Tahun Ajaran</label>
                {{-- Readonly, tidak perlu update ke server --}}
                <input type="text"
                    value="{{ $tahun_ajaran_nama ?? 'belum ditentukan admin' }}"
                    readonly
                    class="input bg-slate-100 cursor-not-allowed">
            </div>

            {{-- VOUCHER --}}
            <div>
                <label class="label">Voucher</label>
                {{-- Live supaya ketika voucher dipilih langsung cek expired / diskon --}}
                <select wire:model.live="voucher_id"
                        class="input @error('voucher_id') border-red-500 @enderror
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
                                {{ $v->kode }} — {{ $v->nama }}
                                @if($expired) (Periode habis) @endif
                            </option>
                        @endforeach
                    @endif
                </select>

                {{-- Info Diskon --}}
                @if($voucher_label)
                    <div class="mt-2 p-3 rounded-lg
                                {{ $voucher_expired ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                        <p class="text-sm font-medium">{{ $voucher_label }}</p>
                    </div>
                @endif

                @error('voucher_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- ================= B. IDENTITAS SISWA ================= --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">B. Identitas Siswa</h2>

        <div class="grid md:grid-cols-2 gap-5">

            {{-- NAMA LENGKAP --}}
            <div class="md:col-span-2">
                <label class="label">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                {{-- Tidak perlu update tiap ketikan, pakai defer --}}
                <input wire:model.defer="nama_siswa"
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
                <select wire:model.defer="jenis_kelamin"
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
                <label class="label">NIK <span class="text-red-500">*</span></label>
                <input wire:model.defer="nik"
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
                <label class="label">No KK <span class="text-red-500">*</span></label>
                <input wire:model.defer="no_kk"
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
                <label class="label">Tempat Lahir <span class="text-red-500">*</span></label>
                <input wire:model.defer="tempat_lahir"
                    class="input @error('tempat_lahir') border-red-500 @enderror">
                @error('tempat_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TANGGAL LAHIR --}}
            <div>
                <label class="label">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date"
                    wire:model.defer="tanggal_lahir"
                    class="input @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- AKTA --}}
            <div>
                <label class="label">No Akta Lahir</label>
                <input wire:model.defer="akta_no" class="input">
            </div>

            {{-- AGAMA --}}
            <div>
                <label class="label">Agama</label>
                {{-- Static, readonly --}}
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
                <select wire:model.defer="berkebutuhan_khusus" class="input">
                    <option value="Tidak">Tidak</option>
                    <option value="Ya">Ya</option>
                </select>
            </div>

            {{-- TINGGAL BERSAMA --}}
            <div>
                <label class="label">Tinggal Bersama <span class="text-red-500">*</span></label>
                {{-- Live karena mempengaruhi rendering field wali --}}
                <select wire:model.live="tinggal_bersama"
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
                    <label class="label">No HP Wali <span class="text-red-500">*</span></label>
                    <input wire:model.defer="hp_wali"
                        class="input @error('hp_wali') border-red-500 @enderror">
                    @error('hp_wali')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Nama Wali <span class="text-red-500">*</span></label>
                    <input wire:model.defer="wali_nama"
                        class="input @error('wali_nama') border-red-500 @enderror">
                    @error('wali_nama')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Hubungan dengan Siswa <span class="text-red-500">*</span></label>
                    <input wire:model.defer="wali_hubungan"
                        placeholder="Paman / Bibi / Kakek / Nenek"
                        class="input @error('wali_hubungan') border-red-500 @enderror">
                    @error('wali_hubungan')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            {{-- TRANSPORTASI --}}
            <div>
                <label class="label">Moda Transportasi <span class="text-red-500">*</span></label>
                <select wire:model.defer="transportasi"
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
                <input wire:model.defer="no_kks" class="input">
            </div>

            <div>
                <label class="label">KPS (Opsional)</label>
                <input wire:model.defer="kps" class="input">
            </div>

            <div>
                <label class="label">KIP (Opsional)</label>
                <input wire:model.defer="kip" class="input">
            </div>

        </div>
    </div>

    {{-- ================= C. ALAMAT ================= --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">C. Alamat</h2>

        {{-- ALAMAT LENGKAP --}}
        <div class="mb-4">
            <label class="label">Alamat Lengkap <span class="text-red-500">*</span></label>
            {{-- Pakai defer, tidak perlu update server tiap ketikan --}}
            <textarea wire:model.defer="alamat"
                    class="input @error('alamat') border-red-500 @enderror"></textarea>
            @error('alamat')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid md:grid-cols-4 gap-5">

            {{-- PROVINSI --}}
            <div>
                <label class="label">Provinsi <span class="text-red-500">*</span></label>
                <input wire:model.defer="provinsi"
                    class="input @error('provinsi') border-red-500 @enderror">
                @error('provinsi')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KABUPATEN --}}
            <div>
                <label class="label">Kabupaten <span class="text-red-500">*</span></label>
                <input wire:model.defer="kabupaten"
                    class="input @error('kabupaten') border-red-500 @enderror">
                @error('kabupaten')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KECAMATAN --}}
            <div>
                <label class="label">Kecamatan <span class="text-red-500">*</span></label>
                <input wire:model.defer="kecamatan"
                    class="input @error('kecamatan') border-red-500 @enderror">
                @error('kecamatan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KELURAHAN --}}
            <div>
                <label class="label">Kelurahan <span class="text-red-500">*</span></label>
                <input wire:model.defer="kelurahan"
                    class="input @error('kelurahan') border-red-500 @enderror">
                @error('kelurahan')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- RT --}}
            <div>
                <label class="label">RT</label>
                <input wire:model.defer="rt"
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
                <input wire:model.defer="rw"
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
                <input wire:model.defer="kode_pos"
                    class="input @error('kode_pos') border-red-500 @enderror">
                @error('kode_pos')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- ================= D. DATA ORANG TUA ================= --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">D. Data Orang Tua</h2>

        {{-- ================= DATA IBU ================= --}}
        <div class="grid md:grid-cols-2 gap-5 mb-4">

            {{-- Nama Ibu --}}
            <div>
                <label class="label">Nama Ibu <span class="text-red-500">*</span></label>
                <input wire:model.defer="ibu_nama"
                    class="input @error('ibu_nama') border-red-500 @enderror">
                @error('ibu_nama')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No HP Ibu --}}
            <div>
                <label class="label">No HP Ibu <span class="text-red-500">*</span></label>
                <input wire:model.defer="ibu_hp"
                    class="input @error('ibu_hp') border-red-500 @enderror">
                @error('ibu_hp')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="grid md:grid-cols-2 gap-5 mt-4">

            {{-- NIK Ibu --}}
            <div>
                <label class="label">NIK Ibu</label>
                <input wire:model.defer="ibu_nik"
                    type="text"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="16 digit"
                    class="input @error('ibu_nik') border-red-500 @enderror">
                @error('ibu_nik')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tahun Lahir Ibu --}}
            <div>
                <label class="label">Tahun Lahir Ibu</label>
                <input wire:model.defer="ibu_tahun_lahir"
                    type="number"
                    min="1945"
                    class="input @error('ibu_tahun_lahir') border-red-500 @enderror">
                @error('ibu_tahun_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pendidikan Ibu --}}
            <div>
                <label class="label">Pendidikan Terakhir Ibu</label>
                <select wire:model.defer="ibu_pendidikan"
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

            {{-- Pekerjaan Ibu --}}
            <div>
                <label class="label">Pekerjaan Ibu</label>
                <select wire:model.defer="ibu_pekerjaan"
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

            {{-- Pekerjaan Ibu Lainnya --}}
            @if($ibu_pekerjaan === 'Lainnya')
            <div>
                <label class="label">Pekerjaan Ibu (Lainnya) <span class="text-red-500">*</span></label>
                <input wire:model.defer="ibu_pekerjaan_lainnya"
                    class="input @error('ibu_pekerjaan_lainnya') border-red-500 @enderror">
                @error('ibu_pekerjaan_lainnya')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Penghasilan Ibu --}}
            <div>
                <label class="label">Penghasilan Ibu</label>
                <select wire:model.defer="ibu_penghasilan"
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

            {{-- Nama Ayah --}}
            <div>
                <label class="label">Nama Ayah</label>
                <input wire:model.defer="ayah_nama"
                    class="input @error('ayah_nama') border-red-500 @enderror">
                @error('ayah_nama')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIK Ayah --}}
            <div>
                <label class="label">NIK Ayah</label>
                <input wire:model.defer="ayah_nik"
                    type="text"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="16 digit"
                    class="input @error('ayah_nik') border-red-500 @enderror">
                @error('ayah_nik')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tahun Lahir Ayah --}}
            <div>
                <label class="label">Tahun Lahir Ayah</label>
                <input wire:model.defer="ayah_tahun_lahir"
                    type="number"
                    min="1945"
                    class="input @error('ayah_tahun_lahir') border-red-500 @enderror">
                @error('ayah_tahun_lahir')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pendidikan Ayah --}}
            <div>
                <label class="label">Pendidikan Terakhir Ayah</label>
                <select wire:model.defer="ayah_pendidikan"
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

            {{-- Pekerjaan Ayah --}}
            <div>
                <label class="label">Pekerjaan Ayah</label>
                <select wire:model.defer="ayah_pekerjaan"
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

            {{-- Pekerjaan Ayah Lainnya --}}
            @if($ayah_pekerjaan === 'Lainnya')
            <div>
                <label class="label">Pekerjaan Ayah (Lainnya) <span class="text-red-500">*</span></label>
                <input wire:model.defer="ayah_pekerjaan_lainnya"
                    class="input @error('ayah_pekerjaan_lainnya') border-red-500 @enderror">
                @error('ayah_pekerjaan_lainnya')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Penghasilan Ayah --}}
            <div>
                <label class="label">Penghasilan Ayah</label>
                <select wire:model.defer="ayah_penghasilan"
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

    {{-- ================= E. DATA PENDUKUNG ================= --}}
    <div class="card">
        <h2 class="font-semibold text-primary mb-4">E. Data Pendukung</h2>

        <div class="grid md:grid-cols-3 gap-5">

            {{-- Tinggi Badan --}}
            <div>
                <label class="label">Tinggi Badan (cm)</label>
                <input wire:model.defer="tinggi"
                    type="number"
                    min="0"
                    max="999"
                    placeholder="Maks 3 digit"
                    class="input @error('tinggi') border-red-500 @enderror">
                @error('tinggi')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Berat Badan --}}
            <div>
                <label class="label">Berat Badan (kg)</label>
                <input wire:model.defer="berat"
                    type="number"
                    min="0"
                    max="999"
                    placeholder="Maks 3 digit"
                    class="input @error('berat') border-red-500 @enderror">
                @error('berat')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jarak ke Sekolah --}}
            <div>
                <label class="label">Jarak ke Sekolah (km)</label>
                <input wire:model.defer="jarak"
                    type="number"
                    min="0"
                    max="9999"
                    placeholder="Maks 4 digit"
                    class="input @error('jarak') border-red-500 @enderror">
                @error('jarak')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah Saudara --}}
            <div>
                <label class="label">Jumlah Saudara</label>
                <input wire:model.defer="jumlah_saudara"
                    type="number"
                    min="0"
                    max="999"
                    placeholder="Maks 3 digit"
                    class="input @error('jumlah_saudara') border-red-500 @enderror">
                @error('jumlah_saudara')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Asal PAUD / TK --}}
            <div>
                <label class="label">Asal PAUD / TK</label>
                <select wire:model.live="paud_tk_id"
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

            {{-- Alamat TK --}}
            <div class="md:col-span-3">
                <label class="label">Alamat TK</label>
                <textarea wire:model.defer="alamat_tk"
                        readonly
                        class="input bg-slate-100 cursor-not-allowed"></textarea>
            </div>

            {{-- Hobi --}}
            <div>
                <label class="label">Hobi</label>
                <textarea wire:model.defer="hobi"
                        class="input @error('hobi') border-red-500 @enderror"></textarea>
                @error('hobi')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cita-cita --}}
            <div>
                <label class="label">Cita-cita</label>
                <input wire:model.defer="cita_cita"
                    class="input @error('cita_cita') border-red-500 @enderror">
                @error('cita_cita')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Hasil Tes --}}
            <div>
                <label class="label">Hasil Tes <span class="text-red-500">*</span></label>
                <select wire:model.defer="hasil_tes"
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


    {{-- Tombol Submit Pendaftaran --}}
    <button 
        wire:click="prepareSubmit" 
        wire:loading.attr="disabled"
        class="btn-primary px-10 py-3 text-base flex items-center gap-2">

        {{-- Normal --}}
        <span wire:loading.remove wire:target="prepareSubmit">
            Simpan Pendaftaran
        </span>

        {{-- Loading --}}
        <span wire:loading wire:target="prepareSubmit" class="flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            Menyimpan...
        </span>

    </button>


    {{-- Modal Konfirmasi --}}
    <div x-data="{ open: @entangle('showConfirm') }" x-show="open" x-transition
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

        <h3 class="text-lg font-semibold mb-4">Konfirmasi Pendaftaran</h3>
        <p class="mb-6">Apakah Anda yakin ingin mengirimkan pendaftaran ini?</p>

        <div class="flex justify-end space-x-3">
            <button @click="open = false" class="btn-secondary px-4 py-2">
                Batal
            </button>

            <button wire:click="submitForm" wire:loading.attr="disabled" class="btn-primary px-4 py-2">
                <span wire:loading.remove>Ya, Kirim</span>
                <span wire:loading>Loading...</span>
            </button>
        </div>

    </div>
</div>


    {{-- Script Alpine untuk halaman publik --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('confirmModal', () => ({
                open: false,
                init() {
                    window.addEventListener('open-confirm-modal', () => {
                        this.open = true;
                    });
                }
            }))
        })
    </script>



</form>
</div>
