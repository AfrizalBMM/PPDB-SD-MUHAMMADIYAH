@extends('layouts.admin')

@section('page-title', 'Pendaftar')

@section('content')

@php
    $totalPendaftar = $siswa->total();
    $totalLaki = $siswa->getCollection()->where('jenis_kelamin', 'laki-laki')->count();
    $totalPerempuan = $siswa->getCollection()->where('jenis_kelamin', 'perempuan')->count();
    $totalLunas = $siswa->getCollection()->filter(function($s) {
        $tagihanAktif = $s->tagihan->filter(fn($t) => (float) $t->total > 0);
        return $tagihanAktif->isNotEmpty() && $tagihanAktif->every(fn($t) => $t->status === 'lunas');
    })->count();
@endphp

<div x-data="{
        openFilter: false,
        openExport: false,
        openQuickEdit: false,
        quickEditAction: '',
        quickEditNama: '',
        quickEditNik: '',
        quickEditNoKk: '',
        quickEditJenisKelamin: 'laki-laki',
        setQuickEdit(data) {
            this.quickEditAction = data.action;
            this.quickEditNama = data.nama || '';
            this.quickEditNik = data.nik || '';
            this.quickEditNoKk = data.noKk || '';
            this.quickEditJenisKelamin = data.jenisKelamin || 'laki-laki';
            this.openFilter = false;
            this.openExport = false;
            this.openQuickEdit = true;
        }
    }" class="mx-auto max-w-7xl space-y-6">

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 via-white to-blue-50 p-5 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Daftar Pendaftar</h2>
                <p class="mt-1 text-sm text-slate-600">Pantau seluruh pendaftar, filter data, dan lakukan aksi cepat.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">Total: {{ number_format($totalPendaftar) }}</span>
                <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Laki-laki: {{ number_format($totalLaki) }}</span>
                <span class="inline-flex items-center rounded-full border border-pink-200 bg-pink-50 px-3 py-1 text-xs font-semibold text-pink-700">Perempuan: {{ number_format($totalPerempuan) }}</span>
                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Lunas: {{ number_format($totalLunas) }}</span>
            </div>
        </div>
    </div>

    <div class="card p-0 overflow-visible">
        @php
            $activeFilterCount = (!empty($filters['status']) ? 1 : 0)
                + (!empty($filters['payment_status']) ? 1 : 0)
                + (!empty($filters['tahun_ajaran_id']) ? 1 : 0)
                + (!empty($filters['jenis_kelamin']) ? 1 : 0)
                + (!empty($filters['date_from']) ? 1 : 0)
                + (!empty($filters['date_to']) ? 1 : 0)
                + (($filters['order'] ?? 'terbaru') === 'terlama' ? 1 : 0);
        @endphp
        <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between px-5 py-4 border-b border-slate-200">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:gap-3 w-full">
                <div class="relative w-full md:w-72">
                    <input
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        class="input w-full pl-10"
                        placeholder="Cari nama, NIK, atau no registrasi">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>
                <div class="flex items-end gap-2">
                    <div class="relative">
                        <button
                            type="button"
                            @click="openFilter = !openFilter; if (openFilter) openExport = false"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100"
                        >
                            <span>Filter</span>
                            @if($activeFilterCount > 0)
                                <span class="inline-flex items-center justify-center rounded-full bg-blue-600 px-1.5 py-0.5 text-[10px] text-white">{{ $activeFilterCount }}</span>
                            @endif
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="openFilter ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="openFilter"
                            x-cloak
                            x-transition
                            @click.away="openFilter = false"
                            class="absolute right-0 mt-2 w-[340px] rounded-xl border border-slate-200 bg-white p-3 shadow-xl z-40"
                        >
                            <div class="space-y-3">
                                <div>
                                    <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Status PPDB</label>
                                    <select name="status" class="input mt-1">
                                        <option value="">Semua</option>
                                        <option value="1" {{ (int) ($filters['status'] ?? 0) === 1 ? 'selected' : '' }}>Bakal Calon</option>
                                        <option value="2" {{ (int) ($filters['status'] ?? 0) === 2 ? 'selected' : '' }}>Calon Peserta Didik</option>
                                        <option value="3" {{ (int) ($filters['status'] ?? 0) === 3 ? 'selected' : '' }}>Peserta Didik</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Status Pembayaran</label>
                                    <select name="payment_status" class="input mt-1">
                                        <option value="">Semua</option>
                                        <option value="lunas" {{ ($filters['payment_status'] ?? '') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="belum_lunas" {{ ($filters['payment_status'] ?? '') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                        <option value="belum_ada_tagihan" {{ ($filters['payment_status'] ?? '') === 'belum_ada_tagihan' ? 'selected' : '' }}>Belum Ada Tagihan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Tahun Ajaran</label>
                                    <select name="tahun_ajaran_id" class="input mt-1">
                                        <option value="">Semua</option>
                                        @foreach($tahunAjaranOptions as $tahun)
                                            <option value="{{ $tahun->id }}" {{ (int) ($filters['tahun_ajaran_id'] ?? 0) === (int) $tahun->id ? 'selected' : '' }}>
                                                {{ $tahun->nama }}{{ $tahun->aktif ? ' (Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="input mt-1">
                                        <option value="">Semua</option>
                                        <option value="laki-laki" {{ ($filters['jenis_kelamin'] ?? '') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ ($filters['jenis_kelamin'] ?? '') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Urutan</label>
                                    <select name="order" class="input mt-1">
                                        <option value="terbaru" {{ ($filters['order'] ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="terlama" {{ ($filters['order'] ?? '') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 border-t border-slate-100 pt-3">
                                    <a href="{{ route('pendaftar.index') }}" class="w-1/2 text-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100">Reset</a>
                                    <button type="submit" class="w-1/2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-32">
                        <label class="mb-1 block text-[10px] font-semibold uppercase tracking-wide text-slate-500">Mulai Dari</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="input w-full">
                    </div>
                    <div class="w-32">
                        <label class="mb-1 block text-[10px] font-semibold uppercase tracking-wide text-slate-500">Sampai</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="input w-full">
                    </div>
                </div>
            </div>
            <div class="relative mt-2 md:mt-0">
                <button
                    type="button"
                    @click="openExport = !openExport; if (openExport) openFilter = false"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100"
                >
                    <span>Export</span>
                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="openExport ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div
                    x-show="openExport"
                    x-cloak
                    x-transition
                    @click.away="openExport = false"
                    class="absolute right-0 mt-2 w-48 rounded-xl border border-slate-200 bg-white p-2 shadow-xl z-40"
                >
                    <a href="{{ route('pendaftar.export', array_merge(request()->query(), ['format' => 'excel'])) }}" class="flex items-center gap-2 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-8m0 8l-3-3m3 3l3-3M4 6h16M7 4h10a1 1 0 011 1v14a1 1 0 01-1 1H7a1 1 0 01-1-1V5a1 1 0 011-1z" />
                            </svg>
                        </span>
                        <span class="flex-1">Export Excel</span>
                    </a>
                    <a href="{{ route('pendaftar.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="mt-1 flex items-center gap-2 rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-500/15 text-rose-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h6l4 4v14a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3v5h5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13h8M8 17h6" />
                            </svg>
                        </span>
                        <span class="flex-1">Export PDF</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

        <div class="card p-0 overflow-hidden">
            <div class="overflow-x-auto overflow-y-visible">
            <table class="table min-w-full overflow-visible text-xs">
                <thead>
                    <tr>
                        <th class="text-[11px]">No</th>
                        <th class="text-[11px]">Tanggal Daftar</th>
                        <th class="text-[11px]">Nama</th>
                        <th class="text-[11px]">Jenis Kelamin</th>
                        <th class="text-[11px]">Data Ibu</th>
                        <th class="text-[11px]">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($siswa as $i => $s)
                        @php
                            $status = (int) ($s->registration->status ?? \App\Models\Registration::STATUS_BAKAL_CALON);
                            $statusLabel = \App\Models\Registration::statusLabel($status);
                            $statusBadge = $status === \App\Models\Registration::STATUS_PESERTA_DIDIK
                                ? 'badge-success'
                                : ($status === \App\Models\Registration::STATUS_CALON ? 'badge-info' : 'badge-warning');
                            $tagihanAktif = $s->tagihan->filter(fn($t) => (float) $t->total > 0);

                            if ($tagihanAktif->isEmpty()) {
                                $paymentLabel = 'Belum Ada Tagihan';
                                $paymentClass = 'badge-info';
                            } elseif ($tagihanAktif->every(fn($t) => $t->status === 'lunas')) {
                                $paymentLabel = 'Lunas';
                                $paymentClass = 'badge-success';
                            } else {
                                $paymentLabel = 'Belum Lunas';
                                $paymentClass = 'badge-warning';
                            }

                            $ibuNama = optional($s->ibu)->nama ?: '-';
                            $ibuWaRaw = optional($s->ibu)->no_hp;
                        @endphp

                        <tr>
                            <td>{{ $siswa->firstItem() + $i }}</td>

                            <td class="text-textSecondary whitespace-nowrap">
                                {{ optional($s->registration?->tanggal_daftar)->format('d M Y') ?? '-' }}
                            </td>

                            <td class="font-medium text-textPrimary whitespace-nowrap align-middle">
                                <div class="text-[10px] text-slate-500 font-mono whitespace-nowrap">{{ $s->registration->nomor_registrasi ?? '-' }}</div>
                                <div class="whitespace-nowrap">{{ $s->nama }}</div>
                                <div class="mt-0.5 flex items-center gap-1.5 whitespace-nowrap text-[9px] leading-none">
                                    <span class="{{ $statusBadge }} inline-flex items-center px-1.5 py-0.5">
                                        {{ $statusLabel }}
                                    </span>
                                    <span class="text-slate-400">-</span>
                                    <span class="{{ $paymentClass }} inline-flex items-center px-1.5 py-0.5">
                                        {{ $paymentLabel }}
                                    </span>
                                </div>
                            </td>

                            <td class="text-textSecondary whitespace-nowrap align-middle">
                                <span class="text-sm font-medium text-slate-700 whitespace-nowrap">
                                    {{ ui_label($s->jenis_kelamin ?? '-') }}
                                </span>
                            </td>

                            <td class="text-textSecondary">
                                <div class="text-xs text-textPrimary font-medium">{{ $ibuNama }}</div>
                                @if(!empty($ibuWaRaw))
                                    <button
                                        type="button"
                                        onclick="copyIbuPhone(@js($ibuWaRaw))"
                                        class="mt-0.5 text-xs text-emerald-700 hover:text-emerald-800 hover:underline cursor-pointer"
                                        title="Klik untuk salin nomor"
                                    >
                                        {{ $ibuWaRaw }}
                                    </button>
                                @else
                                    <div class="text-xs text-slate-400">-</div>
                                @endif
                            </td>

                            <td>
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
                                                const menuWidth = this.$refs.actionMenu ? this.$refs.actionMenu.offsetWidth : 208;
                                                const menuHeight = this.$refs.actionMenu ? this.$refs.actionMenu.offsetHeight : 280;
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
                                            x-transition
                                            @click.away="open = false"
                                            @keydown.escape.window="open = false"
                                            x-ref="actionMenu"
                                            class="fixed w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-xl z-[200]"
                                            :style="`top: ${menuTop}px; left: ${menuLeft}px;`"
                                            x-cloak
                                        >
                                        <a href="{{ route('pendaftar.show', $s->id) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Detail Pendaftar</span>
                                        </a>
                                        <a href="{{ route('keuangan.index',['siswa'=>$s->id]) }}" class="mt-1 flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                                            </svg>
                                            <span>Lihat Keuangan</span>
                                        </a>
                                        <button
                                            type="button"
                                            class="mt-1 flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-700 hover:bg-slate-100"
                                            @click='setQuickEdit({
                                                action: @js(route('pendaftar.quick-update', $s->id)),
                                                nama: @js($s->nama),
                                                nik: @js($s->nik),
                                                noKk: @js($s->no_kk),
                                                jenisKelamin: @js($s->jenis_kelamin)
                                            }); open = false'
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Quick Edit</span>
                                        </button>
                                        <a href="{{ route('pendaftar.activity', $s->id) }}" class="mt-1 flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Riwayat Aktivitas</span>
                                        </a>

                                        @if($status !== \App\Models\Registration::STATUS_PESERTA_DIDIK)
                                            <form method="POST" action="{{ route('pendaftar.jadikan-peserta-didik', $s->id) }}" class="mt-1" onsubmit="return window.globalConfirmSubmit(this, 'Jadikan data ini sebagai Peserta Didik?', { title: 'Konfirmasi Status' })">
                                                @csrf
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-xs font-medium text-emerald-700 hover:bg-emerald-50">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Jadikan Peserta Didik</span>
                                                </button>
                                            </form>
                                        @endif
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                Data pendaftar belum tersedia untuk filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswa->hasPages())
            <div class="sticky bottom-0 left-0 z-20 flex flex-wrap items-center gap-3 border-t border-slate-200 bg-white px-4 py-3 text-xs text-slate-600 shadow-sm">
                <form method="GET" class="flex items-center gap-2">
                    @foreach(request()->except(['page', 'per_page']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label for="perPagePendaftar">Tampilkan</label>
                    <select id="perPagePendaftar" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                        @foreach([10,20,50,100] as $size)
                            <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span>data</span>
                </form>
                <span class="text-slate-500">
                    Menampilkan {{ number_format($siswa->firstItem()) }} - {{ number_format($siswa->lastItem()) }} dari {{ number_format($siswa->total()) }} data
                </span>
                <span>
                    {{ $siswa->links() }}
                </span>
            </div>
        @endif

    </div>
    </div>

    <script>
    async function copyIbuPhone(noHp)
    {
        const phone = String(noHp || '').trim();

        await copyTextToClipboard(
            phone,
            `Nomor HP ibu "${phone}" berhasil disalin.`,
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
                tempInput.style.top = '0';
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
    </script>

    <div
        x-show="openQuickEdit"
        x-transition
        x-cloak
        @click.self="openQuickEdit = false"
        @keydown.escape.window="openQuickEdit = false"
        class="fixed inset-0 z-[230] flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm"
    >
        <div class="w-full max-w-lg rounded-xl bg-white p-5 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-800">Quick Edit Pendaftar</h3>
                <button type="button" @click="openQuickEdit = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <form :action="quickEditAction" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-medium text-slate-600">Nama</label>
                    <input type="text" name="nama" x-model="quickEditNama" required class="input mt-1">
                </div>
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-medium text-slate-600">NIK</label>
                        <input type="text" name="nik" x-model="quickEditNik" class="input mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-600">No KK</label>
                        <input type="text" name="no_kk" x-model="quickEditNoKk" class="input mt-1">
                    </div>
                </div>
                <div>
                    <div>
                        <label class="text-xs font-medium text-slate-600">Jenis Kelamin</label>
                        <select name="jenis_kelamin" x-model="quickEditJenisKelamin" class="input mt-1" required>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openQuickEdit = false" class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
