@extends('layouts.admin')

@section('page-title','Master Biaya PPDB')

@section('content')
<div class="max-w-6xl space-y-6">

    {{-- FORM TAMBAH BIAYA --}}
    <x-card>
        <h2 class="font-semibold text-lg mb-4">
            Tambah Biaya ({{ $tahunAktif->nama }})
        </h2>

        <form method="POST" action="{{ route('biaya.store') }}" class="grid md:grid-cols-3 gap-4">
            @csrf

            <x-select 
                name="jenis_biaya" 
                label="Jenis Biaya"
                :options="[
                    'pendaftaran' => 'Pendaftaran',
                    'daftar_ulang' => 'Daftar Ulang',
                    'udp' => 'UDP'
                ]"
            />

            <x-select 
                name="kategori" 
                label="Kategori"
                :options="[
                    'wajib' => 'Wajib',
                    'opsional' => 'Opsional'
                ]"
            />

            <x-select 
                name="jenis_kelamin" 
                label="Jenis Kelamin"
                :options="[
                    'semua' => 'Semua',
                    'laki-laki' => 'Laki-laki',
                    'perempuan' => 'Perempuan'
                ]"
            />

            <x-input name="nama_biaya" label="Nama Biaya" />
            <x-input name="nominal" type="number" label="Nominal" />

            <div class="md:col-span-3">
                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_acuan_status_ppdb" value="1" class="rounded border-slate-300">
                    Jadikan acuan perpindahan status PPDB (dari Calon Peserta Didik ke Peserta Didik)
                </label>
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="btn-primary">Simpan Biaya</button>
            </div>
        </form>
    </x-card>

    {{-- DAFTAR BIAYA --}}
    <x-card>
        <h2 class="font-semibold text-lg mb-4">Daftar Biaya</h2>

        <div class="overflow-x-auto">
            <table class="table-base w-full">
                <thead>
                    <tr class="bg-slate-100 text-left text-sm font-semibold text-slate-700">
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Jenis</th>
                        <th class="px-4 py-2">Kategori</th>
                        <th class="px-4 py-2">JK</th>
                        <th class="px-4 py-2">Nominal</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($biaya as $b)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-2">
                            @if($b->aktif)
                                <x-badge type="keuangan">Aktif</x-badge>
                            @else
                                <x-badge>Nonaktif</x-badge>
                            @endif

                            @if($b->is_acuan_status_ppdb)
                                <div class="mt-1">
                                    <x-badge type="success">Acuan</x-badge>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 font-medium">{{ $b->nama_biaya }}</td>
                        <td class="px-4 py-2">{{ ui_label($b->jenis_biaya) }}</td>
                        <td class="px-4 py-2">{{ ui_label($b->kategori) }}</td>
                        <td class="px-4 py-2">{{ ui_label($b->jenis_kelamin) }}</td>
                        <td class="px-4 py-2 font-semibold text-slate-800">
                            Rp {{ number_format($b->nominal,0,',','.') }}
                        </td>
                        <td class="px-4 py-2 flex gap-2">
                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('biaya.toggle',$b) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs px-2 py-1 bg-yellow-500 text-white rounded">
                                    {{ $b->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('biaya.toggle-acuan-status',$b) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs px-2 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">
                                    {{ $b->is_acuan_status_ppdb ? 'Batalkan Acuan' : 'Pilih Acuan' }}
                                </button>
                            </form>
                            <x-button type="button" onclick="openDeleteModal('{{ route('biaya.destroy',$b) }}')" class="bg-red-500 hover:bg-red-600 text-xs px-2 py-1">Hapus</x-button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-slate-500">
                            Data biaya belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col gap-3 border-t border-slate-200 pt-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                <label for="perPageBiaya">Tampilkan</label>
                <select id="perPageBiaya" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                    @foreach([10,20,50,100] as $size)
                        <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </form>

            <div>
                {{ $biaya->links() }}
            </div>
        </div>
    </x-card>

</div>

{{-- MODAL DELETE --}}
<x-modal id="deleteModal" title="Konfirmasi Hapus">
    <p>Anda yakin ingin menghapus data ini?</p>
    <form id="deleteForm" method="POST" class="mt-4 flex justify-end gap-2">
        @csrf
        @method('DELETE')
        <button type="button" onclick="closeDeleteModal()" class="btn-secondary">Batal</button>
        <button type="submit" class="btn-danger">Ya, Hapus</button>
    </form>
</x-modal>

@endsection
