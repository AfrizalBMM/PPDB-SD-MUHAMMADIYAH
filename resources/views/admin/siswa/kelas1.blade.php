@extends('layouts.admin')

@section('title','Peserta Didik Kelas 1')

@section('content')
@php
    $isScopedKelas = !empty($kelasId) || !empty($filterBelumKelas);
@endphp
<div class="mx-auto max-w-7xl space-y-4">
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 via-white to-blue-50 p-4 md:p-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-800">{{ $scopeLabel ?? 'Data Peserta Didik Aktif' }}</h1>
                <p class="mt-1 text-xs text-slate-600">
                    Tahun ajaran: <span class="font-semibold text-slate-700">{{ $tahunAktif->nama }}</span>
                </p>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <form method="GET" class="border-b border-slate-200 bg-slate-50/70 p-4 md:p-5">
            @if($isScopedKelas)
                <input type="hidden" name="kelas_id" value="{{ !empty($filterBelumKelas ?? false) ? 'belum' : (int) $kelasId }}">
            @endif
            <div x-data="{ open: false, openExport: false }" class="relative flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div class="flex w-full items-center gap-2 md:max-w-xl">
                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama peserta didik, NIK, no registrasi, nama/no HP ibu"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >
                    <button type="submit" class="rounded-xl bg-blue-600 px-3 py-2.5 text-xs font-semibold text-white transition hover:bg-blue-700">
                        Cari
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    @if($isScopedKelas)
                        <button
                            type="button"
                            onclick="openNormalizeNamaModal({
                                scopeLabel: @js($scopeLabel ?? 'Per Kelas'),
                                scopeCount: @js((int) ($scopeNormalizeCount ?? 0)),
                                kelasId: @js(!empty($filterBelumKelas ?? false) ? 'belum' : (int) $kelasId),
                                title: @js('Normalisasi Nama Siswa')
                            })"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-3 py-2.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100 md:min-w-[120px]"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Title Case</span>
                        </button>
                    @endif

                    <button
                        type="button"
                        @click="open = !open"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 md:min-w-[130px]"
                    >
                        <span>Filter Data</span>
                        @if($filterAktif > 0)
                            <span class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-blue-600 px-1.5 text-[10px] font-bold text-white">{{ $filterAktif }}</span>
                        @endif
                        <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div class="relative">
                        <button
                            type="button"
                            @click="openExport = !openExport"
                            @click.outside="openExport = false"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 md:min-w-[110px]"
                        >
                            <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Export</span>
                        </button>

                        <div
                            x-show="openExport"
                            x-transition
                            class="absolute right-0 top-full z-50 mt-2 w-40 origin-top-right rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl"
                        >
                            <div class="px-3 pt-2 pb-1 text-[10px] font-bold tracking-wider text-slate-500 uppercase">
                                Excel
                            </div>
                            <a href="{{ route('siswa.export.excel', request()->all()) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Data Siswa
                            </a>
                            <a href="{{ route('siswa.export.excel-keuangan', request()->all()) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v1h6v-1c0-1.657-1.343-3-3-3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12v6a2 2 0 002 2h8a2 2 0 002-2v-6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12V9a4 4 0 118 0v3" />
                                </svg>
                                Keuangan
                            </a>

                            <div class="my-1 border-t border-slate-100"></div>

                            <div class="px-3 pt-2 pb-1 text-[10px] font-bold tracking-wider text-slate-500 uppercase">
                                PDF
                            </div>
                            <a href="{{ route('siswa.export.pdf', request()->all()) }}" target="_blank" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-rose-50 hover:text-rose-700">
                                <svg class="h-4 w-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Data Siswa
                            </a>
                            <a href="{{ route('siswa.export.pdf-keuangan', request()->all()) }}" target="_blank" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-rose-50 hover:text-rose-700">
                                <svg class="h-4 w-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v1h6v-1c0-1.657-1.343-3-3-3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12v6a2 2 0 002 2h8a2 2 0 002-2v-6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12V9a4 4 0 118 0v3" />
                                </svg>
                                Keuangan
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    class="absolute right-0 top-full z-40 mt-2 w-full max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl"
                >
                    @if($isScopedKelas)
                        <div class="mb-3 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700">
                            Filter kelas dikunci sesuai submenu: <span class="font-semibold">{{ $scopeLabel ?? 'Per Kelas' }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-12 md:items-end">
                        @if(!$isScopedKelas)
                            <div class="md:col-span-5">
                                <label for="kelas_id" class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">Kelas</label>
                                <select id="kelas_id" name="kelas_id" class="w-full rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <option value="">Semua Kelas</option>
                                    <option value="belum" {{ ($filterBelumKelas ?? false) ? 'selected' : '' }}>Belum Masuk Kelas</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" {{ (int) ($kelasId ?? 0) === (int) $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="{{ $isScopedKelas ? 'md:col-span-6' : 'md:col-span-3' }}">
                            <label for="jenis_kelamin" class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="w-full rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="">Semua</option>
                                <option value="laki-laki" {{ ($jenisKelamin ?? null) === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ ($jenisKelamin ?? null) === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="{{ $isScopedKelas ? 'md:col-span-6' : 'md:col-span-4' }}">
                            <label for="order" class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">Urutan</label>
                            <select id="order" name="order" class="w-full rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="nama_asc" {{ ($order ?? 'nama_asc') === 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="nama_desc" {{ ($order ?? 'nama_asc') === 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                                <option value="terbaru" {{ ($order ?? 'nama_asc') === 'terbaru' ? 'selected' : '' }}>Data Terbaru</option>
                                <option value="terlama" {{ ($order ?? 'nama_asc') === 'terlama' ? 'selected' : '' }}>Data Terlama</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                        <a href="{{ route('siswa.index', $resetQuery) }}" class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-100">Reset</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">Terapkan</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama Peserta Didik</th>
                        <th class="px-4 py-3 text-left">No Registrasi</th>
                        <th class="px-4 py-3 text-left">Data Ibu</th>
                        <th class="px-4 py-3 text-left">Jenis Kelamin</th>
                        <th class="px-4 py-3 text-left">Hasil Tes</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($siswa as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">
                                <button
                                    type="button"
                                    data-copy-name="{{ $item->nama }}"
                                    onclick="copyStudentName(this)"
                                    class="text-left transition hover:text-blue-600 focus:outline-none"
                                    title="Klik untuk menyalin nama"
                                >
                                    {{ $item->nama }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ optional($item->registration)->nomor_registrasi ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                <div>{{ optional($item->ibu)->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-500">
                                    <button
                                        type="button"
                                        onclick="copyIbuPhone(@js(optional($item->ibu)->no_hp ?? ''))"
                                        class="text-left transition hover:text-blue-600 focus:outline-none"
                                        title="Klik untuk menyalin nomor HP ibu"
                                    >
                                        {{ optional($item->ibu)->no_hp ?? '-' }}
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ ui_label($item->jenis_kelamin) }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $hasilTes = strtoupper($item->hasil_tes ?? '');
                                    $badgeClass = match($hasilTes) {
                                        'SB' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'B'  => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'PB' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-lg border px-2.5 py-1 text-[11px] font-bold {{ $badgeClass }}">
                                    {{ $item->hasil_tes ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div
                                    x-data="{
                                        open: false,
                                        menuTop: 0,
                                        menuLeft: 0,
                                        openUp: false,
                                        toggleMenu(event) {
                                            if (this.open) {
                                                this.open = false;
                                                return;
                                            }

                                            const rect = event.currentTarget.getBoundingClientRect();
                                            this.open = true;

                                            this.$nextTick(() => {
                                                const menuWidth = this.$refs.actionMenu ? this.$refs.actionMenu.offsetWidth : 192;
                                                const menuHeight = this.$refs.actionMenu ? this.$refs.actionMenu.offsetHeight : 260;
                                                let left = rect.left;

                                                if (left + menuWidth > window.innerWidth - 12) {
                                                    left = window.innerWidth - menuWidth - 12;
                                                }

                                                if (left < 12) {
                                                    left = 12;
                                                }

                                                this.menuLeft = left;
                                                this.openUp = (window.innerHeight - rect.bottom) < (menuHeight + 12);
                                                this.menuTop = this.openUp
                                                    ? Math.max(12, rect.top - menuHeight - 8)
                                                    : rect.bottom + 8;
                                            });
                                        }
                                    }"
                                    @scroll.window="open = false"
                                    @resize.window="open = false"
                                    class="inline-block text-left"
                                >
                                    <button
                                        type="button"
                                        @click="toggleMenu($event)"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>

                                    <template x-teleport="body">
                                        <div
                                            x-show="open"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            @click.away="open = false"
                                            @keydown.escape.window="open = false"
                                            x-ref="actionMenu"
                                            class="fixed z-[200] w-48 origin-top-right rounded-xl border border-slate-200 bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none"
                                            :style="`top: ${menuTop}px; left: ${menuLeft}px;`"
                                            x-cloak
                                        >
                                            <div class="p-1.5">
                                                <a href="{{ route('pendaftar.show', optional($item->registration)->id ?? 1) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Detail
                                                </a>
                                                <a href="{{ route('keuangan.index', ['q' => optional($item->registration)->nomor_registrasi]) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    Keuangan
                                                </a>
                                                <button
                                                    type="button"
                                                    @click="open = false; openAssignKelasModal({
                                                        actionUrl: @js(route('siswa.assign-kelas', $item->id)),
                                                        namaSiswa: @js($item->nama),
                                                        nomorRegistrasi: @js(optional($item->registration)->nomor_registrasi ?? '-'),
                                                        currentKelasId: @js((int) ($item->kelas_siswa_id ?? 0))
                                                    })"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600"
                                                >
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                    Ubah Kelas
                                                </button>

                                                @if($item->kelasSiswa)
                                                    <div class="my-1 border-t border-slate-100"></div>
                                                    <form method="POST" action="{{ route('siswa.remove-kelas', $item->id) }}" onsubmit="return window.globalConfirmSubmit(this, 'Keluarkan peserta didik ini dari kelas?', { title: 'Konfirmasi Keluarkan Peserta Didik' })">
                                                        @csrf
                                                        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                            </svg>
                                                            Keluarkan
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                Belum ada data peserta didik untuk filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200 p-4 md:flex-row md:items-center md:justify-between">
            <div class="text-sm text-slate-600">
                Menampilkan {{ $siswa->firstItem() ?? 0 }} - {{ $siswa->lastItem() ?? 0 }} dari {{ $siswa->total() }} peserta didik
            </div>

            <div class="flex w-full flex-col items-center gap-2 md:w-auto md:items-end">
                <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                    @foreach(request()->except(['page', 'per_page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="perPageSiswa">Tampilkan</label>
                    <select id="perPageSiswa" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                        @foreach([10,20,50,100] as $size)
                            <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span>data</span>
                </form>

                <div>
                    {{ $siswa->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalAssignKelas" class="fixed inset-0 z-[110] hidden items-center justify-center bg-slate-900/55 p-4" onclick="closeAssignKelasModal()">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl" onclick="event.stopPropagation()">
        <div class="border-b border-slate-100 bg-gradient-to-r from-emerald-50 via-white to-sky-50 px-5 py-4">
            <h3 class="text-base font-semibold text-slate-800">Masukkan ke Kelas</h3>
            <p id="assignKelasSubtitle" class="mt-1 text-xs text-slate-500">Pilih kelas untuk peserta didik ini.</p>
        </div>
        <form id="formAssignKelas" method="POST" class="space-y-4 px-5 py-4">
            @csrf
            <div>
                <label for="assign_kelas_siswa_id" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Pilih Kelas</label>
                <select id="assign_kelas_siswa_id" name="kelas_siswa_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100" required>
                    <option value="">Pilih kelas...</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAssignKelasModal()" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Batal</button>
                <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">Simpan Penempatan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalNormalizeNama" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm" onclick="closeNormalizeNamaModal()">
    <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl" onclick="event.stopPropagation()">
        <div class="border-b border-slate-100 bg-gradient-to-r from-amber-50 via-white to-orange-50 px-6 py-5">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-600">Aksi Massal</p>
                    <h3 id="normalizeNamaTitle" class="mt-1 text-lg font-semibold text-slate-800">Normalisasi Nama Siswa</h3>
                    <p class="mt-1 text-sm text-slate-500">Ubah penulisan nama menjadi Title Case untuk scope yang sedang dibuka.</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5">
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Scope</div>
                    <div id="normalizeNamaScope" class="mt-1 text-sm font-semibold text-slate-800">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Jumlah Data</div>
                    <div id="normalizeNamaCount" class="mt-1 text-sm font-semibold text-slate-800">0 siswa</div>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Semua nama yang masih huruf besar/kecil campuran akan disimpan ulang ke database dalam format Title Case.
            </div>

            <form id="formNormalizeNama" method="POST" action="{{ route('siswa.normalize-nama') }}" class="mt-5" data-disable-auto-loading="true" onsubmit="return submitNormalizeNama(this)">
                @csrf
                <input id="normalizeNamaKelasId" type="hidden" name="kelas_id" value="">

                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <button id="normalizeNamaCancelBtn" type="button" onclick="closeNormalizeNamaModal()" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                        Batal
                    </button>
                    <button id="normalizeNamaSubmitBtn" type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Ya, Normalisasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

function openNormalizeNamaModal(payload)
{
    const modal = document.getElementById('modalNormalizeNama');
    const title = document.getElementById('normalizeNamaTitle');
    const scope = document.getElementById('normalizeNamaScope');
    const count = document.getElementById('normalizeNamaCount');
    const kelasIdInput = document.getElementById('normalizeNamaKelasId');

    if (!modal || !title || !scope || !count || !kelasIdInput) {
        return;
    }

    title.textContent = payload?.title || 'Normalisasi Nama Siswa';
    scope.textContent = payload?.scopeLabel || '-';
    count.textContent = `${Number(payload?.scopeCount || 0)} siswa`;
    kelasIdInput.value = payload?.kelasId ?? '';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

async function copyStudentName(button)
{
    const name = button?.dataset?.copyName || '';

    await copyTextToClipboard(
        name,
        `Nama "${name}" berhasil disalin.`,
        'Nama siswa tidak tersedia untuk disalin.'
    );
}

async function copyIbuPhone(noHp)
{
    const phone = String(noHp || '').trim();

    await copyTextToClipboard(
        phone,
        `Nomor HP ibu "${phone}".`,
        'Nomor HP ibu tidak tersedia untuk disalin.'
    );
}

async function copyTextToClipboard(value, successMessage, emptyMessage)
{
    const text = String(value || '').trim();

    if (!text) {
        if (typeof window.showGlobalToast === 'function') {
            window.showGlobalToast('warning', emptyMessage, { title: 'Gagal Menyalin' });
        }
        return;
    }

    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(text);
        } else {
            const tempInput = document.createElement('textarea');
            tempInput.value = text;
            tempInput.setAttribute('readonly', '');
            tempInput.style.position = 'absolute';
            tempInput.style.left = '-9999px';
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
        }

        if (typeof window.showGlobalToast === 'function') {
            window.showGlobalToast('success', successMessage, { title: 'Berhasil Menyalin' });
        }
    } catch (error) {
        if (typeof window.showGlobalToast === 'function') {
            window.showGlobalToast('danger', 'Data gagal disalin ke clipboard.', { title: 'Gagal Menyalin' });
        }
    }
}

function closeNormalizeNamaModal()
{
    const modal = document.getElementById('modalNormalizeNama');
    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function submitNormalizeNama(form)
{
    const submitBtn = document.getElementById('normalizeNamaSubmitBtn');
    const cancelBtn = document.getElementById('normalizeNamaCancelBtn');

    if (!form || !submitBtn) {
        return true;
    }

    if (submitBtn.dataset.submitting === '1') {
        return false;
    }

    submitBtn.dataset.submitting = '1';
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
    submitBtn.innerHTML = `
        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <span>Memproses...</span>
    `;

    if (cancelBtn) {
        cancelBtn.disabled = true;
        cancelBtn.classList.add('opacity-70', 'cursor-not-allowed');
    }

    return true;
}

function openAssignKelasModal(payload)
{
    const modal = document.getElementById('modalAssignKelas');
    const form = document.getElementById('formAssignKelas');
    const subtitle = document.getElementById('assignKelasSubtitle');
    const select = document.getElementById('assign_kelas_siswa_id');

    if (!modal || !form || !select) {
        return;
    }

    form.action = payload?.actionUrl || '';
    select.value = String(payload?.currentKelasId || '');

    if (subtitle) {
        subtitle.textContent = `Pilih kelas untuk ${payload?.namaSiswa || '-'} (No. Registrasi ${payload?.nomorRegistrasi || '-'})`;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAssignKelasModal()
{
    const modal = document.getElementById('modalAssignKelas');
    if (!modal) {
        return;
    }
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
