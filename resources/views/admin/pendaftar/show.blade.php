@extends('layouts.admin')

@section('page-title','Detail Pendaftar')

@section('content')
<div class="grid md:grid-cols-3 gap-6">

    {{-- LEFT --}}
    <div class="md:col-span-2 space-y-6">

        {{-- REGISTRATION --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">Data Registrasi</h3>

            <div class="space-y-1 text-sm">
                <p>
                    <span class="font-medium text-slate-600">Nomor Registrasi</span><br>
                    {{ $siswa->registration->nomor_registrasi ?? '-' }}
                </p>

                <p>
                    <span class="font-medium text-slate-600">Tanggal Daftar</span><br>
                    {{ $siswa->registration->tanggal_daftar ?? '-' }}
                </p>

                <p>
                    <span class="font-medium text-slate-600">Tahun Ajaran</span><br>
                    {{ $siswa->registration->tahunAjaran->nama ?? '-' }}
                </p>

                <p>
                    <span class="font-medium text-slate-600">Status</span><br>
                    <span class="badge-warning">
                        {{ ucfirst($siswa->registration->status ?? 'pending') }}
                    </span>
                </p>
            </div>
        </div>

        {{-- DATA SISWA --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">Data Siswa</h3>

            <div class="space-y-1 text-sm">
                <p><strong>Nama</strong><br>{{ $siswa->nama }}</p>
                <p><strong>NIK</strong><br>{{ $siswa->nik }}</p>
                <p><strong>No KK</strong><br>{{ $siswa->no_kk }}</p>
                <p><strong>Jenis Kelamin</strong><br>{{ $siswa->jenis_kelamin }}</p>
                <p><strong>Tempat, Tanggal Lahir</strong><br>
                    {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir }}
                </p>
                <p><strong>Transportasi</strong><br>{{ $siswa->transportasi ?? '-' }}</p>
                <p><strong>Tinggal Bersama</strong><br>{{ $siswa->tinggal_bersama ?? '-' }}</p>
                <p><strong>Hasil Tes</strong><br>{{ $siswa->hasil_tes }}</p>
            </div>
        </div>

        {{-- ALAMAT --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">Alamat</h3>

            <div class="text-sm space-y-1">
                <p>{{ optional($siswa->alamatSiswa)->alamat ?? '-' }}</p>
                <p>
                    {{ optional($siswa->alamatSiswa)->kelurahan ?? '-' }},
                    {{ optional($siswa->alamatSiswa)->kecamatan ?? '-' }},
                    {{ optional($siswa->alamatSiswa)->kabupaten ?? '-' }},
                    {{ optional($siswa->alamatSiswa)->provinsi ?? '-' }}
                </p>
                <p>
                    RT {{ optional($siswa->alamatSiswa)->rt ?? '-' }} /
                    RW {{ optional($siswa->alamatSiswa)->rw ?? '-' }}
                </p>
            </div>
        </div>

        {{-- ORANG TUA --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">Data Orang Tua</h3>

            <div class="text-sm space-y-3">

                <div>
                    <strong>Ibu</strong><br>
                    {{ optional($siswa->ibu)->nama ?? '-' }} |
                    {{ optional($siswa->ibu)->no_hp ?? '-' }}
                </div>

                @if($siswa->ayah)
                <div>
                    <strong>Ayah</strong><br>
                    {{ $siswa->ayah->nama }} |
                    {{ $siswa->ayah->no_hp ?? '-' }}
                </div>
                @endif

                @if($siswa->wali)
                <div>
                    <strong>Wali</strong><br>
                    {{ $siswa->wali->nama }} ({{ $siswa->wali->hubungan }}) |
                    {{ $siswa->wali->no_hp }}
                </div>
                @endif

            </div>
        </div>

        {{-- DATA PENDUKUNG --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">Data Pendukung</h3>

            <div class="text-sm space-y-1">
                <p>Tinggi: {{ optional($siswa->dataPendukung)->tinggi ?? '-' }} cm</p>
                <p>Berat: {{ optional($siswa->dataPendukung)->berat ?? '-' }} kg</p>
                <p>Jarak: {{ optional($siswa->dataPendukung)->jarak ?? '-' }} km</p>
                <p>Jumlah Saudara: {{ optional($siswa->dataPendukung)->jumlah_saudara ?? '-' }}</p>
                <p>Hobi: {{ optional($siswa->dataPendukung)->hobi ?? '-' }}</p>
                <p>Cita-cita: {{ optional($siswa->dataPendukung)->cita_cita ?? '-' }}</p>
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

        {{-- RINGKASAN TAGIHAN --}}
        <div class="card">
            <h3 class="font-semibold text-slate-800 mb-3">Ringkasan Tagihan</h3>

            @foreach($siswa->tagihan as $tagihan)
                <div class="text-sm border-b py-2">
                    {{ $tagihan->biaya->nama_biaya }}<br>
                    Total: Rp {{ number_format($tagihan->total) }}<br>
                    Status:
                    {{ $tagihan->is_lunas ? 'Lunas' : 'Belum Lunas' }}
                </div>
            @endforeach
        </div>

    </div>

</div>
@endsection