@extends('layouts.admin')

@section('page-title','Detail Pendaftar')

@section('content')
<div class="grid md:grid-cols-3 gap-6">

    {{-- LEFT --}}
    <div class="md:col-span-2 space-y-4">

        {{-- Data Siswa --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">
                Data Siswa
            </h3>

            <div class="space-y-1 text-sm">
                <p>
                    <span class="font-medium text-slate-600">Nama</span><br>
                    {{ $siswa->nama }}
                </p>
                <p>
                    <span class="font-medium text-slate-600">NIK</span><br>
                    {{ $siswa->nik }}
                </p>
                <p>
                    <span class="font-medium text-slate-600">Jenis Kelamin</span><br>
                    {{ $siswa->jenis_kelamin }}
                </p>
            </div>
        </div>

        {{-- Orang Tua --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">
                Data Orang Tua
            </h3>

            <div class="space-y-1 text-sm">
                <p>
                    <span class="font-medium text-slate-600">Nama Ibu</span><br>
                    {{ optional($siswa->ibu)->nama ?? '-' }}
                </p>
                <p>
                    <span class="font-medium text-slate-600">No. HP</span><br>
                    {{ optional($siswa->ibu)->no_hp ?? '-' }}
                </p>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="space-y-4">

        <div class="card space-y-2">
            <a
                href="{{ route('cetak.formulir',$siswa) }}"
                target="_blank"
                class="btn-primary w-full text-center">
                Cetak Formulir
            </a>

            <a
                href="{{ route('keuangan.index',['siswa'=>$siswa->id]) }}"
                class="btn-primary w-full text-center">
                Keuangan
            </a>
        </div>

    </div>

</div>
@endsection
