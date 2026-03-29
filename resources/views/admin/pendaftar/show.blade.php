@extends('layouts.admin')

@section('page-title','Detail Pendaftar')

@section('content')
<div class="grid md:grid-cols-3 gap-6">

    {{-- LEFT --}}
    <div class="md:col-span-2 space-y-6">

        {{-- REGISTRATION --}}
        <div class="card">
            <h3 class="font-heading font-bold text-lg text-primary mb-4 border-b border-border pb-2">Data Registrasi</h3>

            <div class="space-y-1 text-sm">
                <p>
                    <span class="text-xs font-bold text-textSecondary uppercase tracking-wider block mb-0.5">Nomor Registrasi</span><br>
                    {{ $siswa->registration->nomor_registrasi ?? '-' }}
                </p>

                <p>
                    <span class="text-xs font-bold text-textSecondary uppercase tracking-wider block mb-0.5">Tanggal Daftar</span><br>
                    {{ $siswa->registration->tanggal_daftar ?? '-' }}
                </p>

                <p>
                    <span class="text-xs font-bold text-textSecondary uppercase tracking-wider block mb-0.5">Tahun Ajaran</span><br>
                    {{ $siswa->registration->tahunAjaran->nama ?? '-' }}
                </p>

                <p>
                    <span class="text-xs font-bold text-textSecondary uppercase tracking-wider block mb-0.5">Status</span><br>
                    @php $regStatus = $siswa->registration->status ?? 'pending'; @endphp
                    <span class="{{ $regStatus === 'diterima' ? 'badge-success' : ($regStatus === 'ditolak' ? 'badge-danger' : ($regStatus === 'arsip' ? 'badge-info' : 'badge-warning')) }}">
                        {{ ui_label($regStatus) }}
                    </span>
                </p>
            </div>
        </div>

        {{-- DATA SISWA --}}
        <div class="card">
            <h3 class="font-heading font-bold text-lg text-primary mb-4 border-b border-border pb-2">Data Siswa</h3>

            <div class="space-y-1 text-sm">
                <p><strong>Nama</strong><br>{{ $siswa->nama }}</p>
                <p><strong>NIK</strong><br>{{ $siswa->nik }}</p>
                <p><strong>No KK</strong><br>{{ $siswa->no_kk }}</p>
                <p><strong>Jenis Kelamin</strong><br>{{ ui_label($siswa->jenis_kelamin) }}</p>
                <p><strong>Tempat, Tanggal Lahir</strong><br>
                    {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir }}
                </p>
                <p><strong>Transportasi</strong><br>{{ $siswa->transportasi ?? '-' }}</p>
                <p><strong>Tinggal Bersama</strong><br>{{ ui_label($siswa->tinggal_bersama ?? '-') }}</p>
                <p><strong>Hasil Tes</strong><br>{{ $siswa->hasil_tes }}</p>
            </div>
        </div>

        {{-- ALAMAT --}}
        <div class="card">
            <h3 class="font-heading font-bold text-lg text-primary mb-4 border-b border-border pb-2">Alamat</h3>

            <div class="text-sm space-y-1">
                <p>{{ optional($siswa->alamat)->alamat ?? '-' }}</p>
                <p>
                    {{ optional($siswa->alamat)->kelurahan ?? '-' }},
                    {{ optional($siswa->alamat)->kecamatan ?? '-' }},
                    {{ optional($siswa->alamat)->kabupaten ?? '-' }},
                    {{ optional($siswa->alamat)->provinsi ?? '-' }}
                </p>
                <p>
                    RT {{ optional($siswa->alamat)->rt ?? '-' }} /
                    RW {{ optional($siswa->alamat)->rw ?? '-' }}
                </p>
            </div>
        </div>

        {{-- ORANG TUA --}}
        <div class="card">
            <h3 class="font-heading font-bold text-lg text-primary mb-4 border-b border-border pb-2">Data Orang Tua</h3>

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
            <h3 class="font-heading font-bold text-lg text-primary mb-4 border-b border-border pb-2">Data Pendukung</h3>

            <div class="text-sm space-y-1">
                <p>Tinggi: {{ optional($siswa->dataPendukung)->tinggi ?? '-' }} cm</p>
                <p>Berat: {{ optional($siswa->dataPendukung)->berat ?? '-' }} kg</p>
                <p>Jarak: {{ optional($siswa->dataPendukung)->jarak ?? '-' }} km</p>
                <p>Jumlah Saudara: {{ optional($siswa->dataPendukung)->jumlah_saudara ?? '-' }}</p>
                <p>Anak Ke (berdasarkan KK): {{ optional($siswa->dataPendukung)->anak_ke ?? '-' }}</p>
                <p>Hobi: {{ optional($siswa->dataPendukung)->hobi ?? '-' }}</p>
                <p>Cita-cita: {{ optional($siswa->dataPendukung)->cita_cita ?? '-' }}</p>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="space-y-4">

        <div class="card space-y-2">
            <a
                href="{{ route('cetak.formulir.preview',$siswa) }}"
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
            <h3 class="font-heading font-bold text-lg text-primary mb-4 border-b border-border pb-2">Ringkasan Tagihan</h3>

            @foreach($siswa->tagihan as $tagihan)
                <div class="text-sm border-b py-2">
                    {{ $tagihan->biaya->nama_biaya }}<br>
                    Total: Rp {{ number_format($tagihan->total) }}<br>
                    Status:
                    {{ $tagihan->is_lunas ? 'Lunas' : 'Belum Lunas' }}
                </div>
            @endforeach
        </div>

        <div id="riwayat-aktivitas" class="card">
            <h3 class="font-heading font-bold text-lg text-primary mb-4 border-b border-border pb-2">Riwayat Aktivitas</h3>

            @if(!empty($aktivitas) && $aktivitas->count())
                <div class="space-y-3">
                    @foreach($aktivitas as $log)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                            <div class="text-xs font-semibold text-slate-700">{{ $log->aksi }}</div>
                            <div class="mt-1 text-[11px] text-slate-500">
                                {{ optional($log->created_at)->format('d M Y H:i') }}
                                • {{ $log->nama ?? '-' }}
                                • {{ $log->role ?? '-' }}
                            </div>
                            <div class="mt-1 text-xs text-slate-600 break-words">{{ $log->keterangan ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500">Belum ada aktivitas tercatat untuk data ini.</p>
            @endif
        </div>

    </div>

</div>

@if(session('scroll_to_activity'))
    <script>
        window.addEventListener('load', function () {
            var el = document.getElementById('riwayat-aktivitas');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    </script>
@endif
@endsection