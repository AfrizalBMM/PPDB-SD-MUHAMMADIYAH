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

            <div>
                <x-label>Jenis Biaya</x-label>
                <x-select name="jenis_biaya">
                    <option value="">Pilih Jenis</option>
                    <option value="pendaftaran" @selected(old('jenis_biaya')=='pendaftaran')>Pendaftaran</option>
                    <option value="daftar_ulang" @selected(old('jenis_biaya')=='daftar_ulang')>Daftar Ulang</option>
                    <option value="udp" @selected(old('jenis_biaya')=='udp')>UDP</option>
                </x-select>
            </div>

            <div>
                <x-label>Kategori</x-label>
                <x-select name="kategori">
                    <option value="">Pilih Kategori</option>
                    <option value="wajib" @selected(old('kategori')=='wajib')>Wajib</option>
                    <option value="opsional" @selected(old('kategori')=='opsional')>Opsional</option>
                </x-select>
            </div>

            <div>
                <x-label>Jenis Kelamin</x-label>
                <x-select name="jenis_kelamin">
                    <option value="semua">Semua</option>
                    <option value="laki-laki" @selected(old('jenis_kelamin')=='laki-laki')>Laki-laki</option>
                    <option value="perempuan" @selected(old('jenis_kelamin')=='perempuan')>Perempuan</option>
                </x-select>
            </div>

            <div>
                <x-label>Nama Biaya</x-label>
                <x-input name="nama_biaya" value="{{ old('nama_biaya') }}" placeholder="Nama Biaya"/>
            </div>

            <div>
                <x-label>Nominal</x-label>
                <x-input name="nominal" type="number" value="{{ old('nominal') }}" placeholder="Nominal"/>
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button class="btn-primary">Simpan Biaya</button>
            </div>

        </form>

    </x-card>


    {{-- DAFTAR BIAYA --}}
    <x-card>

        <h2 class="font-semibold text-lg mb-4">Daftar Biaya</h2>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>JK</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($biaya as $b)
                    <tr>
                        <td class="font-medium">{{ $b->nama_biaya }}</td>
                        <td>{{ ucfirst($b->jenis_biaya) }}</td>
                        <td>{{ ucfirst($b->jenis_kelamin) }}</td>
                        <td class="font-semibold text-slate-800">
                            Rp {{ number_format($b->nominal,0,',','.') }}
                        </td>

                        <td>
                            @if($b->aktif)
                                <x-badge type="keuangan">Aktif</x-badge>
                            @else
                                <x-badge>Nonaktif</x-badge>
                            @endif
                        </td>

                        <td class="flex gap-3 text-sm">

                            {{-- TOGGLE --}}
                            <form method="POST" action="{{ route('biaya.toggle',$b) }}">
                                @csrf
                                <button class="text-blue-600 hover:underline">
                                    Toggle
                                </button>
                            </form>

                            {{-- DELETE --}}
                            <button onclick="openDeleteModal('{{ route('biaya.destroy',$b) }}')" class="text-red-600 hover:underline">
                                Hapus
                            </button>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-500">
                            Data biaya belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </x-card>

</div>

{{-- MODAL DELETE --}}
<x-modal id="deleteModal" title="Konfirmasi Hapus">
    <p>Anda yakin ingin menghapus biaya ini?</p>

    <form id="deleteForm" method="POST" class="mt-4 flex justify-end gap-2">
        @csrf
        @method('DELETE')

        <button type="button" onclick="closeDeleteModal()" class="btn-secondary">
            Batal
        </button>

        <button type="submit" class="btn-danger">
            Ya, Hapus
        </button>
    </form>
</x-modal>

@endsection
