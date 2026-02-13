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
                        <td class="p-3 font-medium">Tempat / Tgl Lahir</td>
                        <td class="p-3">
                            {{ $siswa->tempat_lahir }},
                            {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') }}
                        </td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Nama Ibu</td>
                        <td class="p-3">{{ optional($siswa->ibu)->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">No HP Ibu</td>
                        <td class="p-3">{{ optional($siswa->ibu)->no_hp ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-3 font-medium">Alamat</td>
                        <td class="p-3">{{ optional($siswa->alamat)->alamat ?? '-' }}</td>
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
        <a href="{{ route('cetak.formulir', $siswa->id) }}" target="_blank" class="btn-primary w-full text-center">
            🖨️ Cetak Formulir Pendaftaran
        </a>

        <button onclick="document.getElementById('modalNota').classList.remove('hidden')"
                class="btn-primary w-full bg-green-600 hover:bg-green-700">
            💰 Input & Cetak Nota Pendaftaran
        </button>

        <a href="{{ route('pendaftaran.public') }}" class="btn-primary w-full text-center bg-slate-600 hover:bg-slate-700">
            ➕ Daftarkan Siswa Lain
        </a>
    </div>

</div>

@include('pendaftaran.modal-nota')
@endsection
