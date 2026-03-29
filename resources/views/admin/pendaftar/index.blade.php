@extends('layouts.admin')

@section('page-title', ($isArsipPage ?? false) ? 'Arsip Pendaftar' : 'Pendaftar')

@section('content')
<div x-data="{
        openFilter: false,
        openExport: false,
        openQuickEdit: false,
        quickEditAction: '',
        quickEditNama: '',
        quickEditNik: '',
        quickEditNoKk: '',
        quickEditJenisKelamin: 'laki-laki',
        openStatusModal: false,
        statusAction: '',
        statusValue: 'pending',
        catatanStatus: '',
        showCopyToast: false,
        copyToastText: '',
        setQuickEdit(data) {
            this.quickEditAction = data.action;
            this.quickEditNama = data.nama || '';
            this.quickEditNik = data.nik || '';
            this.quickEditNoKk = data.noKk || '';
            this.quickEditJenisKelamin = data.jenisKelamin || 'laki-laki';
            this.openQuickEdit = true;
        },
        setStatus(data) {
            this.statusAction = data.action;
            this.statusValue = data.status || 'pending';
            this.catatanStatus = '';
            this.openStatusModal = true;
        },
        copyPhone(value) {
            if (!value) {
                return;
            }

            const text = String(value).trim();
            if (!text) {
                return;
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text);
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }

            this.copyToastText = 'Nomor HP berhasil disalin';
            this.showCopyToast = true;
            setTimeout(() => {
                this.showCopyToast = false;
            }, 1400);
        }
    }" class="space-y-6">

    <div class="card relative z-30 overflow-visible">
        @php
            $activeFilterCount = (!empty($filters['status']) ? 1 : 0)
                + (!empty($filters['payment_status']) ? 1 : 0)
                + (!empty($filters['tahun_ajaran_id']) ? 1 : 0)
                + (!empty($filters['jenis_kelamin']) ? 1 : 0)
                + (($filters['order'] ?? 'terbaru') === 'terlama' ? 1 : 0);
        @endphp

        <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div class="w-full md:max-w-xl">
                <input
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    class="input"
                    placeholder="Cari nama, NIK, atau no registrasi">
            </div>

            <div class="flex items-stretch gap-2 w-full md:w-auto md:shrink-0">
                <div class="inline-flex rounded-lg border border-slate-200 p-1 bg-slate-50 w-full md:w-auto">
                    <a
                        href="{{ route('pendaftar.index') }}"
                        class="w-1/2 md:w-auto text-center rounded-md px-3 py-1.5 text-xs font-medium transition-colors {{ ($isArsipPage ?? false) ? 'text-slate-600 hover:bg-white' : 'bg-white text-blue-700 shadow-sm' }}"
                    >
                        Aktif
                    </a>
                    <a
                        href="{{ route('pendaftar.arsip') }}"
                        class="w-1/2 md:w-auto text-center rounded-md px-3 py-1.5 text-xs font-medium transition-colors {{ ($isArsipPage ?? false) ? 'bg-white text-amber-700 shadow-sm' : 'text-slate-600 hover:bg-white' }}"
                    >
                        Arsip
                    </a>
                </div>

                <div class="relative w-full md:w-auto">
                    <button
                        type="button"
                        @click="openFilter = !openFilter; if (openFilter) openExport = false"
                        class="w-full md:w-auto inline-flex items-center justify-between gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 transition-colors"
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
                        x-transition
                        @click.away="openFilter = false"
                        class="absolute right-0 mt-2 w-full md:w-[360px] rounded-xl border border-slate-200 bg-white p-3 shadow-xl z-40"
                    >
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Status Seleksi</label>
                                <select name="status" class="input mt-1">
                                    <option value="">Semua</option>
                                    <option value="diterima" {{ ($filters['status'] ?? '') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="ditolak" {{ ($filters['status'] ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="belum_diproses" {{ ($filters['status'] ?? '') === 'belum_diproses' ? 'selected' : '' }}>Belum Diproses</option>
                                    <option value="arsip" {{ ($filters['status'] ?? '') === 'arsip' ? 'selected' : '' }}>Arsip</option>
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
                                <a href="{{ ($isArsipPage ?? false) ? route('pendaftar.arsip') : route('pendaftar.index') }}" class="w-1/2 text-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100">Reset</a>
                                <button type="submit" class="w-1/2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700">Terapkan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative w-full md:w-auto">
                    <button
                        type="button"
                        @click="openExport = !openExport; if (openExport) openFilter = false"
                        class="w-full md:w-auto inline-flex items-center justify-between gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 transition-colors"
                    >
                        <span>Export</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="openExport ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="openExport"
                        x-transition
                        @click.away="openExport = false"
                        class="absolute right-0 mt-2 w-full md:w-44 rounded-xl border border-slate-200 bg-white p-2 shadow-xl z-40"
                    >
                        <a href="{{ route('pendaftar.export', array_merge(request()->query(), ['format' => 'excel'])) }}" class="block rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100">Export Excel</a>
                        <a href="{{ route('pendaftar.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="mt-1 block rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100">Export PDF</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card relative z-[60] p-0 overflow-visible">
        <div class="overflow-x-auto overflow-y-visible">
            <table class="table min-w-full overflow-visible">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Registrasi</th>
                        <th>Nama</th>
                        <th>Data Ibu</th>
                        <th>Tanggal Daftar</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($siswa as $i => $s)
                        @php
                            $status = $s->registration->status ?? 'belum_diproses';
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

                            <td>
                                <span class="badge-info font-mono">{{ $s->registration->nomor_registrasi ?? '-' }}</span>
                            </td>

                            <td class="font-medium text-textPrimary">
                                <div>{{ $s->nama }}</div>
                                <div class="text-xs font-normal text-textSecondary mt-0.5">{{ ui_label($s->jenis_kelamin ?? '-') }}</div>
                            </td>

                            <td class="text-textSecondary">
                                <div class="text-xs text-textPrimary font-medium">{{ $ibuNama }}</div>
                                @if(!empty($ibuWaRaw))
                                    <button
                                        type="button"
                                        @click="copyPhone(@js($ibuWaRaw))"
                                        class="mt-0.5 text-xs text-emerald-700 hover:text-emerald-800 hover:underline cursor-pointer"
                                        title="Klik untuk salin nomor"
                                    >
                                        {{ $ibuWaRaw }}
                                    </button>
                                @else
                                    <div class="text-xs text-slate-400">-</div>
                                @endif
                            </td>

                            <td class="text-textSecondary">
                                {{ optional($s->registration?->tanggal_daftar)->format('d M Y') ?? '-' }}
                            </td>

                            <td>
                                <span class="{{ $paymentClass }}">{{ $paymentLabel }}</span>
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
                                        class="btn-ghost px-3 py-1.5 text-xs inline-flex items-center gap-1"
                                    >
                                        Aksi
                                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                                        <button
                                            type="button"
                                            class="mt-1 flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-700 hover:bg-slate-100"
                                            @click='setStatus({
                                                action: @js(route('pendaftar.update-status', $s->id)),
                                                status: @js($status === 'arsip' ? 'pending' : ($status ?: 'pending'))
                                            }); open = false'
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Ubah Status Seleksi</span>
                                        </button>
                                        <a href="{{ route('pendaftar.activity', $s->id) }}" class="mt-1 flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Riwayat Aktivitas</span>
                                        </a>

                                        <form method="POST" action="{{ route('pendaftar.toggle-arsip', $s->id) }}" class="mt-1" onsubmit="return confirm('Yakin melanjutkan aksi ini?')">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-xs font-medium {{ $status === 'arsip' ? 'text-emerald-700 hover:bg-emerald-50' : 'text-amber-700 hover:bg-amber-50' }}">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($status === 'arsip')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l2-3h10l2 3M5 8v11a2 2 0 002 2h10a2 2 0 002-2V8M9 12h6" />
                                                    @endif
                                                </svg>
                                                <span>{{ $status === 'arsip' ? 'Pulihkan dari Arsip' : 'Arsipkan Data' }}</span>
                                            </button>
                                        </form>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                                Data pendaftar belum tersedia untuk filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-border bg-background/40">
            {{ $siswa->links() }}
        </div>
    </div>

    <div
        x-show="openQuickEdit"
        x-transition
        class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-900/50 p-4"
        style="display: none;"
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

    <div
        x-show="openStatusModal"
        x-transition
        class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-900/50 p-4"
        style="display: none;"
    >
        <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-800">Ubah Status Seleksi</h3>
                <button type="button" @click="openStatusModal = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <form :action="statusAction" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-medium text-slate-600">Status</label>
                    <select name="status" x-model="statusValue" class="input mt-1" required>
                        <option value="pending">Pending</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-slate-600">Catatan (wajib saat Ditolak)</label>
                    <textarea name="catatan_status" x-model="catatanStatus" rows="3" class="input mt-1"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openStatusModal = false" class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>

    <div
        x-show="showCopyToast"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed right-4 bottom-4 z-[120]"
        style="display: none;"
    >
        <div class="rounded-lg border border-emerald-200 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-lg">
            <span x-text="copyToastText"></span>
        </div>
    </div>

</div>
@endsection
