@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')
<div x-data="{ 
    showDeleteAll: false,
    search: '{{ request('search') }}',
    role: '{{ request('role') }}',
    kategori: '{{ request('kategori') }}',
    date: '{{ request('tanggal') }}'
}" class="space-y-6">

    {{-- Header & Stats (Optional) --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-textPrimary text-center md:text-left">Aktivitas Sistem</h2>
            <p class="text-textSecondary text-sm">Pantau jejak aktivitas panitia public dan user internal secara real-time.</p>
        </div>
        @if(auth()->user()->role === 'superadmin')
        <button @click="showDeleteAll = true" class="btn-danger flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Bersihkan Log
        </button>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="card !p-4">
            <p class="text-xs text-textSecondary">Total Log</p>
            <p class="mt-1 text-2xl font-extrabold text-textPrimary">{{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="card !p-4">
            <p class="text-xs text-textSecondary">Log Hari Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-primary">{{ number_format($stats['today'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="card !p-4">
            <p class="text-xs text-textSecondary">Aktivitas Public</p>
            <p class="mt-1 text-2xl font-extrabold text-slate-700">{{ number_format($stats['public'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="card !p-4">
            <p class="text-xs text-textSecondary">Aktivitas Internal</p>
            <p class="mt-1 text-2xl font-extrabold text-amber-700">{{ number_format($stats['staff'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card p-4">
        <form action="{{ route('log.aktivitas') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full space-y-1.5">
                <label class="label">Pencarian</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" x-model="search" placeholder="Cari user, aksi, atau keterangan..." class="input pl-10" value="{{ request('search') }}">
                </div>
            </div>
            <div class="w-full md:w-48 space-y-1.5">
                <label class="label">Role</label>
                <select name="role" x-model="role" class="input">
                    <option value="">Semua Role</option>
                    <option value="superadmin">Superadmin</option>
                    <option value="admin">Admin</option>
                    <option value="keuangan">Keuangan</option>
                    <option value="public">Public</option>
                </select>
            </div>
            <div class="w-full md:w-56 space-y-1.5">
                <label class="label">Kategori Aksi</label>
                <select name="kategori" x-model="kategori" class="input">
                    <option value="">Semua Kategori</option>
                    <option value="pendaftaran">Pendaftaran</option>
                    <option value="pembayaran">Pembayaran</option>
                    <option value="verifikasi">Verifikasi Password</option>
                    <option value="manajemen-log">Manajemen Log</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="w-full md:w-48 space-y-1.5">
                <label class="label">Tanggal</label>
                <input type="date" name="tanggal" x-model="date" class="input" value="{{ request('tanggal') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary px-6">Filter</button>
                <a href="{{ route('log.aktivitas') }}" class="btn-secondary px-4" title="Reset">
                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden !p-0">
        <div class="overflow-x-auto w-full">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="w-32">Waktu</th>
                        <th class="w-48">Pengguna</th>
                        <th class="w-48">Aksi</th>
                        <th>Keterangan</th>
                        <th class="w-32 text-right">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <div class="text-textSecondary text-[13px] whitespace-nowrap">
                                {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                                <div class="text-[11px] opacity-60">{{ $log->created_at->timezone('Asia/Jakarta')->format('H:i:s') }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex-shrink-0 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase">
                                    {{ substr(optional($log->user)->name ?? 'P', 0, 1) }}
                                </div>
                                <span class="font-medium text-textPrimary truncate">{{ optional($log->user)->name ?? 'Public / Guest' }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $aksi = (string) $log->aksi;
                                $aksiLower = strtolower($aksi);

                                $kategoriLabel = 'Lainnya';
                                $kategoriClass = 'bg-slate-100 text-slate-700 border-slate-200';

                                if (str_contains($aksiLower, 'pendaftaran') || str_contains($aksiLower, 'form edit') || str_contains($aksiLower, 'nik')) {
                                    $kategoriLabel = 'Pendaftaran';
                                    $kategoriClass = 'bg-cyan-100 text-cyan-700 border-cyan-200';
                                } elseif (str_contains($aksiLower, 'pembayaran') || str_contains($aksiLower, 'cicilan') || str_contains($aksiLower, 'nota') || str_contains($aksiLower, 'pembiayaan')) {
                                    $kategoriLabel = 'Pembayaran';
                                    $kategoriClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                } elseif (str_contains($aksiLower, 'verifikasi password')) {
                                    $kategoriLabel = 'Verifikasi';
                                    $kategoriClass = 'bg-violet-100 text-violet-700 border-violet-200';
                                } elseif (str_contains($aksiLower, 'kelola log') || str_contains($aksiLower, 'monitoring aktivitas')) {
                                    $kategoriLabel = 'Manajemen Log';
                                    $kategoriClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                }
                            @endphp

                            <div class="space-y-1">
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md border {{ $kategoriClass }}">
                                    {{ $kategoriLabel }}
                                </span>
                                <div class="font-bold text-primary leading-snug">{{ $aksi }}</div>
                            </div>
                        </td>
                        <td class="max-w-xs md:max-w-md lg:max-w-lg">
                            @php
                                $keterangan = trim((string) $log->keterangan);
                                $keterangan = $keterangan !== '' ? $keterangan : '-';
                                $keteranganRows = preg_split('/\s*\|\s*/', $keterangan) ?: [];
                            @endphp

                            @if(count($keteranganRows) > 1)
                                <ul class="text-textSecondary text-xs leading-relaxed space-y-1">
                                    @foreach($keteranganRows as $row)
                                        @if(trim($row) !== '')
                                            <li>• {{ trim($row) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-textSecondary text-sm leading-relaxed" title="{{ $keterangan }}">
                                    {{ $keterangan }}
                                </p>
                            @endif
                        </td>
                        <td class="text-right text-textSecondary text-xs font-mono tracking-tighter">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3 opacity-40">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm font-medium">Belum ada jejak aktivitas yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="p-6 border-t border-border bg-background/30">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                    @foreach(request()->except(['page', 'per_page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="perPageLogs">Tampilkan</label>
                    <select id="perPageLogs" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                        @foreach([10,20,50,100] as $size)
                            <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 50) === $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span>data</span>
                </form>
                {{ $logs->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- Modal Delete All --}}
    <div x-show="showDeleteAll" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteAll" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/50" @click="showDeleteAll = false"></div>

            <div x-show="showDeleteAll" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-textPrimary mb-2">Konfirmasi Hapus Semua Log</h3>
                    <p class="text-textSecondary text-sm mb-8">Apakah Anda yakin ingin menghapus seluruh jejak aktivitas sistem? Tindakan ini bersifat permanen dan tidak dapat dibatalkan.</p>
                </div>

                <div class="flex justify-center gap-3">
                    <button type="button" @click="showDeleteAll = false" class="btn-secondary">Batalkan</button>
                    <form method="POST" action="{{ route('logs.destroyAll') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">Hapus Permanen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
