@extends('layouts.admin')

@section('title','Master Voucher')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">
    @php
        $totalVoucher = $vouchers->total();
        $aktifVoucher = $vouchers->getCollection()->filter(fn($item) => $item->masihBerlaku())->count();
        $nonAktifVoucher = $vouchers->getCollection()->filter(fn($item) => !$item->masihBerlaku())->count();
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 via-white to-emerald-50 p-5 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Master Voucher</h2>
                <p class="mt-1 text-sm text-slate-600">Kelola voucher potongan biaya PPDB secara terpusat.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">Total: {{ $totalVoucher }}</span>
                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif: {{ $aktifVoucher }}</span>
                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Tidak Aktif: {{ $nonAktifVoucher }}</span>
            </div>
        </div>
    </div>

    {{-- FORM TAMBAH --}}
    <div class="card">
        <h2 class="text-base font-semibold text-slate-800">Tambah Voucher</h2>
        <p class="mt-1 text-xs text-slate-500">Susun detail voucher dalam dua baris input agar lebih cepat diisi.</p>

        <form
            method="POST"
            action="{{ route('voucher.store') }}"
            class="mt-5 grid gap-4"
            id="voucherForm">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="label">Nama Voucher</label>
                    <input
                        name="nama"
                        class="input"
                        placeholder="Nama Voucher"
                        value="{{ old('nama') }}"
                        required>
                </div>

                <div>
                    <label class="label">Jenis Biaya</label>
                    <select name="jenis_biaya" class="input" required>
                        <option value="">Pilih</option>
                        <option value="pendaftaran" {{ old('jenis_biaya') === 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                        <option value="daftar_ulang" {{ old('jenis_biaya') === 'daftar_ulang' ? 'selected' : '' }}>Daftar Ulang</option>
                        <option value="udp" {{ old('jenis_biaya') === 'udp' ? 'selected' : '' }}>UDP</option>
                    </select>
                </div>

                <div x-data="{
                    diskonRaw: '{{ old('diskon_nominal') }}',
                    diskonFormatted: '',
                    init() {
                        const raw = (this.diskonRaw || '').toString().replace(/\D/g, '');
                        this.diskonRaw = raw;
                        this.diskonFormatted = this.formatDiskon(raw);
                    },
                    formatDiskon(value) {
                        if (!value) return '';
                        return new Intl.NumberFormat('id-ID').format(Number(value));
                    },
                    onDiskonInput(event) {
                        const raw = event.target.value.replace(/\D/g, '');
                        this.diskonRaw = raw;
                        this.diskonFormatted = this.formatDiskon(raw);
                    }
                }">
                    <label class="label">Diskon Nominal</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-600">Rp.</span>
                        <input
                            type="text"
                            inputmode="numeric"
                            :value="diskonFormatted"
                            @input="onDiskonInput($event)"
                            class="w-full rounded-lg border border-gray-300 py-2 pl-12 pr-3 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                            placeholder="Contoh: 50.000"
                            required>
                        <input type="hidden" name="diskon_nominal" :value="diskonRaw">
                    </div>
                    @error('diskon_nominal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="label">Maksimal Penggunaan</label>
                    <input
                        type="number"
                        name="maks_penggunaan"
                        class="input"
                        placeholder="Contoh: 10"
                        value="{{ old('maks_penggunaan') }}"
                        min="1"
                        required>
                </div>

                <div>
                    <label class="label">Tanggal Mulai</label>
                    <input
                        type="date"
                        name="tanggal_mulai"
                        class="input"
                        value="{{ old('tanggal_mulai') }}"
                        required>
                </div>

                <div>
                    <label class="label">Tanggal Selesai</label>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        class="input"
                        value="{{ old('tanggal_selesai') }}"
                        required>
                </div>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    id="submitVoucherBtn">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card p-0 overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <!-- Judul -->
            <h2 class="text-base font-semibold text-slate-800">
                Daftar Voucher
            </h2>

            <!-- Hapus Semua -->
            <button
                onclick="document.getElementById('modalDeleteAll').classList.remove('hidden')"
                class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                Hapus Semua Voucher
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-right">Diskon</th>
                        <th class="px-4 py-3 text-center">Dipakai</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($vouchers as $v)
                <tr class="hover:bg-slate-50 transition">
                    <!-- Kode -->
                    <td class="px-4 py-3 font-mono">
                        {{ $v->kode }}
                    </td>

                    <!-- Nama + Tanggal Mulai & Selesai -->
                    <td class="px-4 py-3 font-medium">
                        {{ $v->nama }}
                        <div class="mt-1 text-xs text-slate-500 flex flex-wrap gap-1">
                            <span class="badge-info">{{ date('d M Y', strtotime($v->tanggal_mulai)) }}</span>
                            -
                            <span class="badge-danger">{{ date('d M Y', strtotime($v->tanggal_selesai)) }}</span>
                        </div>
                    </td>

                    <!-- Jenis Biaya -->
                    <td class="px-4 py-3">
                        {{ ui_label($v->jenis_biaya) }}
                    </td>

                    <!-- Diskon -->
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        Rp {{ number_format($v->diskon_nominal,0,',','.') }}
                    </td>

                    <!-- Maks Penggunaan -->
                    <td class="px-4 py-3 text-center">
                        {{ $v->digunakan }} / {{ $v->maks_penggunaan }}
                    </td>

                    <!-- Status -->
                    <td class="px-4 py-3 text-center">
                        @if($v->masihBerlaku())
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-warning">Tidak Aktif</span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-4 py-3 text-center">
                        <div
                            x-data="{
                                open: false,
                                menuTop: 0,
                                menuLeft: 0,
                                toggleMenu(event) {
                                    if (this.open) {
                                        this.open = false;
                                        return;
                                    }

                                    const rect = event.currentTarget.getBoundingClientRect();
                                    this.open = true;

                                    this.$nextTick(() => {
                                        const menuWidth = this.$refs.actionMenu ? this.$refs.actionMenu.offsetWidth : 180;
                                        const menuHeight = this.$refs.actionMenu ? this.$refs.actionMenu.offsetHeight : 140;
                                        let left = rect.left;

                                        if (left + menuWidth > window.innerWidth - 12) {
                                            left = window.innerWidth - menuWidth - 12;
                                        }

                                        if (left < 12) {
                                            left = 12;
                                        }

                                        this.menuLeft = left;

                                        const openUp = (window.innerHeight - rect.bottom) < (menuHeight + 12);
                                        this.menuTop = openUp
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
                                    x-cloak
                                    @click.away="open = false"
                                    @keydown.escape.window="open = false"
                                    x-ref="actionMenu"
                                    class="fixed z-[200] w-44 rounded-lg border border-slate-200 bg-white p-2 shadow-lg"
                                    :style="`top: ${menuTop}px; left: ${menuLeft}px;`"
                                >
                                    <form method="POST" action="{{ route('voucher.toggle',$v) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="flex w-full items-center gap-2 rounded-md px-2.5 py-2 text-left text-xs font-semibold text-blue-700 hover:bg-blue-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            {{ $v->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('voucher.destroy',$v) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 rounded-md px-2.5 py-2 text-left text-xs font-semibold text-red-700 hover:bg-red-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 0V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 4v6m4-6v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </template>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                        Data voucher belum tersedia
                    </td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200 px-4 py-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                <label for="perPageVoucher">Tampilkan</label>
                <select id="perPageVoucher" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                    @foreach([10,20,50,100] as $size)
                        <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </form>

            <div>
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>

</div>


{{-- MODAL HAPUS SEMUA VOUCHER --}}
<div id="modalDeleteAll" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">
        <h3 class="text-lg font-semibold text-red-600">Konfirmasi Hapus Semua Voucher</h3>
        <p>Apakah Anda yakin ingin menghapus <strong>semua data voucher</strong>? Aksi ini tidak bisa dibatalkan.</p>

        <div class="flex justify-end gap-3 mt-4">
            <button type="button" 
                onclick="document.getElementById('modalDeleteAll').classList.add('hidden')"
                class="btn-secondary">
                Batal
            </button>

            <form method="POST" action="{{ route('voucher.destroyAll') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    Hapus Semua
                </button>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // FORM SIMPAN VOUCHER
    const voucherForm = document.getElementById('voucherForm');
    const submitBtn = document.getElementById('submitVoucherBtn');

    voucherForm.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Loading...';
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    });

    // SEMUA FORM HAPUS VOUCHER
    const deleteForms = document.querySelectorAll('form[action*="voucher.destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = form.querySelector('button[type="submit"]');
            if(btn){
                btn.disabled = true;
                btn.textContent = 'Menghapus...';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    });

    // FORM HAPUS SEMUA VOUCHER
    const deleteAllForm = document.querySelector('form[action="{{ route("voucher.destroyAll") }}"]');
    if(deleteAllForm){
        deleteAllForm.addEventListener('submit', function() {
            const btn = deleteAllForm.querySelector('button[type="submit"]');
            if(btn){
                btn.disabled = true;
                btn.textContent = 'Menghapus semua...';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    }
});

</script>

@endsection
