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

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200 rounded-lg">
                <tbody>
                    <tr class="bg-slate-50">
                        <td class="p-3 w-48 font-medium">Nama Siswa</td>
                        <td class="p-3">{{ $siswa->nama }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">No Registrasi</td>
                        <td class="p-3">
                            {{ optional($siswa->registration)->nomor_registrasi ?? '-' }}
                        </td>
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
                        <td class="p-3">
                            {{ optional($siswa->alamat)->alamat ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium">Hasil Tes</td>
                        <td class="p-3">
                            <span class="badge-success">
                                {{ $siswa->hasil_tes }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- PANEL AKSI --}}
    <div class="card space-y-3">

        <a
            href="{{ route('cetak.formulir', $siswa->id) }}"
            target="_blank"
            class="btn-primary w-full text-center">
            🖨️ Cetak Formulir Pendaftaran
        </a>

        <button
            onclick="document.getElementById('modalNota').classList.remove('hidden')"
            class="btn-primary w-full bg-green-600 hover:bg-green-700">
            💰 Input & Cetak Nota Pendaftaran
        </button>

        <a
            href="{{ route('pendaftaran.public') }}"
            class="btn-primary w-full text-center bg-slate-600 hover:bg-slate-700">
            ➕ Daftarkan Siswa Lain
        </a>

    </div>

</div>

@include('pendaftaran.modal-nota')
@endsection
