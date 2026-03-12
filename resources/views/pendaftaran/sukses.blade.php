@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 grid md:grid-cols-3 gap-6">

    {{-- DATA PENDAFTAR --}}
    <div class="md:col-span-2 card">
        <h2 class="font-semibold text-lg text-slate-800 mb-4">
            ✅ Pendaftaran Berhasil
        </h2>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-5 text-sm text-green-800">
            Data calon siswa berhasil disimpan.
            Silakan lakukan pembayaran biaya pendaftaran.
        </div>

        {{-- Data Siswa --}}
        <div class="overflow-x-auto mb-5">
            <h3 class="font-semibold mb-2">Data Siswa</h3>
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <tbody>
                    <tr class="bg-slate-50">
                        <td class="p-3 w-48 font-medium">Nama Siswa</td>
                        <td class="p-3">{{ $siswa->nama }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">No Registrasi</td>
                        <td class="p-3">{{ optional($siswa->registration)->nomor_registrasi ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Jenis Kelamin</td>
                        <td class="p-3">{{ ucfirst($siswa->jenis_kelamin) }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">NIK</td>
                        <td class="p-3">{{ $siswa->nik ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">No KK</td>
                        <td class="p-3">{{ $siswa->no_kk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Tempat / Tgl Lahir</td>
                        <td class="p-3">
                            {{ $siswa->tempat_lahir }},
                            {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') }}
                        </td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">No Akta Lahir</td>
                        <td class="p-3">{{ $siswa->akta_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Agama</td>
                        <td class="p-3">{{ $siswa->agama ?? 'Islam' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Kewarganegaraan</td>
                        <td class="p-3">{{ $siswa->kewarganegaraan ?? 'Indonesia' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Berkebutuhan Khusus</td>
                        <td class="p-3">{{ $siswa->berkebutuhan_khusus ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Tinggal Bersama</td>
                        <td class="p-3">{{ $siswa->tinggal_bersama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">NO KKS</td>
                        <td class="p-3">{{ $siswa->no_kks ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">NO KIP</td>
                        <td class="p-3">{{ $siswa->kip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">NO KPS</td>
                        <td class="p-3">{{ $siswa->kps ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Transportasi</td>
                        <td class="p-3">{{ $siswa->transportasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Hasil Tes</td>
                        <td class="p-3">
                            <span class="badge-success">{{ $siswa->hasil_tes }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Data Alamat --}}
        <div class="overflow-x-auto mb-5">
            <h3 class="font-semibold mb-2">Data Alamat</h3>
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <tbody>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Alamat Lengkap</td>
                        <td class="p-3">{{ optional($siswa->alamat)->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Provinsi</td>
                        <td class="p-3">{{ optional($siswa->alamat)->provinsi ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Kabupaten</td>
                        <td class="p-3">{{ optional($siswa->alamat)->kabupaten ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Kecamatan</td>
                        <td class="p-3">{{ optional($siswa->alamat)->kecamatan ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Kelurahan</td>
                        <td class="p-3">{{ optional($siswa->alamat)->kelurahan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">RT / RW</td>
                        <td class="p-3">{{ optional($siswa->alamat)->rt ?? '-' }} / {{ optional($siswa->alamat)->rw ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Kode Pos</td>
                        <td class="p-3">{{ optional($siswa->alamat)->kode_pos ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Data Ibu --}}
        <div class="overflow-x-auto mb-5">
            <h3 class="font-semibold mb-2">Data Ibu</h3>
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <tbody>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Nama Ibu</td>
                        <td class="p-3">{{ optional($siswa->ibu)->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">No HP</td>
                        <td class="p-3">{{ optional($siswa->ibu)->no_hp ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">NIK</td>
                        <td class="p-3">{{ optional($siswa->ibu)->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Tahun Lahir</td>
                        <td class="p-3">{{ optional($siswa->ibu)->tahun_lahir ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Pendidikan</td>
                        <td class="p-3">{{ optional($siswa->ibu)->pendidikan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Pekerjaan</td>
                        <td class="p-3">{{ optional($siswa->ibu)->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Penghasilan</td>
                        <td class="p-3">{{ optional($siswa->ibu)->penghasilan ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Data Ayah --}}
        <div class="overflow-x-auto mb-5">
            <h3 class="font-semibold mb-2">Data Ayah</h3>
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <tbody>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Nama Ayah</td>
                        <td class="p-3">{{ optional($siswa->ayah)->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">NIK</td>
                        <td class="p-3">{{ optional($siswa->ayah)->nik ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Tahun Lahir</td>
                        <td class="p-3">{{ optional($siswa->ayah)->tahun_lahir ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Pendidikan</td>
                        <td class="p-3">{{ optional($siswa->ayah)->pendidikan ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Pekerjaan</td>
                        <td class="p-3">{{ optional($siswa->ayah)->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Penghasilan</td>
                        <td class="p-3">{{ optional($siswa->ayah)->penghasilan ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($siswa->tinggal_bersama === 'wali')
        <div class="overflow-x-auto mb-5">
            <h3 class="font-semibold mb-2">Data Wali</h3>
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <tbody>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Nama Wali</td>
                        <td class="p-3">{{ $siswa->wali_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">No HP Wali</td>
                        <td class="p-3">{{ $siswa->hp_wali ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Hubungan</td>
                        <td class="p-3">{{ $siswa->wali_hubungan ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        {{-- Data Pendukung --}}
        <div class="overflow-x-auto mb-5">
            <h3 class="font-semibold mb-2">Data Pendukung</h3>
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <tbody>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Tinggi / Berat</td>
                        <td class="p-3 text-right">{{ optional($siswa->dataPendukung)->tinggi ?? '-' }} cm / {{ optional($siswa->dataPendukung)->berat ?? '-' }} kg</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Jarak Rumah</td>
                        <td class="p-3 text-right">{{ optional($siswa->dataPendukung)->jarak ?? '-' }} km</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Jumlah Saudara</td>
                        <td class="p-3 text-right">{{ optional($siswa->dataPendukung)->jumlah_saudara ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Asal PAUD / TK</td>
                        <td class="p-3 text-right">{{ optional($siswa->dataPendukung)->paudTk->nama ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Alamat TK</td>
                        <td class="p-3 text-right">{{ optional($siswa->dataPendukung)->paudTk->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Hobi / Cita-cita</td>
                        <td class="p-3 text-right">{{ optional($siswa->dataPendukung)->hobi ?? '-' }} / {{ optional($siswa->dataPendukung)->cita_cita ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- PANEL AKSI --}}
    <div class="card space-y-3">

        {{-- Rincian Biaya Pendaftaran --}}
        <div class="overflow-x-auto mb-3">
            <h3 class="font-semibold mb-2">Rincian Biaya Pendaftaran</h3>
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-3 text-left">Jenis Biaya</th>
                        <th class="p-3 text-right">Nominal</th>
                        <th class="p-3 text-right">Diskon</th>
                        <th class="p-3 text-right">Total</th>
                        <th class="p-3 text-left">Voucher</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($siswa->tagihan as $t)
                        <tr class="{{ $loop->even ? 'bg-slate-50' : '' }}">
                            <td class="p-3">{{ $t->biaya->nama_biaya ?? '-' }}</td>
                            <td class="p-3 text-right">{{ number_format($t->nominal,0,',','.') }}</td>
                            <td class="p-3 text-right">{{ number_format($t->diskon,0,',','.') }}</td>
                            <td class="p-3 text-right">{{ number_format($t->total,0,',','.') }}</td>
                            <td class="p-3">{{ $t->kode_voucher ?? '-' }}</td>
                            <td class="p-3">{{ ucfirst($t->status) }}</td>
                        </tr>
                        @php $grandTotal += $t->total; @endphp
                    @endforeach
                    <tr class="font-semibold bg-slate-100">
                        <td class="p-3 text-right" colspan="3">Total Bayar</td>
                        <td class="p-3 text-right">{{ number_format($grandTotal,0,',','.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Tombol Aksi --}}
        <button onclick="openModalPetugas({{ $siswa->id }})"
    class="btn-primary w-full text-center">
    🖨️ Cetak Formulir Pendaftaran
</button>

        @include('pendaftaran.modal-cetak-formulir')

        <button onclick="document.getElementById('modalNota').classList.remove('hidden')"
            class="btn-primary w-full bg-green-600 hover:bg-green-700">
            💰 Input & Cetak Nota Pendaftaran
        </button>

        <button onclick="window.location='{{ route('pendaftaran.public') }}'"
            class="btn-primary w-full text-center bg-slate-600 hover:bg-slate-700">
            ➕ Daftarkan Siswa Lain
        </button>

    </div>

</div>

@include('pendaftaran.modal-nota')
@endsection
