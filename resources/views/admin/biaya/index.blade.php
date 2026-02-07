@extends('layouts.admin')

@section('title','Master Biaya PPDB')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- FORM TAMBAH BIAYA --}}
    <div class="card">
        <h2 class="text-lg font-semibold mb-4">
            Tambah Biaya ({{ $tahunAktif->nama }})
        </h2>

        <form
            method="POST"
            action="{{ route('biaya.store') }}"
            class="grid md:grid-cols-3 gap-4">
            @csrf

            <div>
                <label class="label">Jenis Biaya</label>
                <select name="jenis_biaya" class="input">
                    <option value="">Pilih Jenis</option>
                    <option value="pendaftaran" @selected(old('jenis_biaya')==='pendaftaran')>
                        Pendaftaran
                    </option>
                    <option value="daftar_ulang" @selected(old('jenis_biaya')==='daftar_ulang')>
                        Daftar Ulang
                    </option>
                    <option value="udp" @selected(old('jenis_biaya')==='udp')>
                        UDP
                    </option>
                </select>
            </div>

            <div>
                <label class="label">Kategori</label>
                <select name="kategori" class="input">
                    <option value="">Pilih Kategori</option>
                    <option value="wajib" @selected(old('kategori')==='wajib')>
                        Wajib
                    </option>
                    <option value="opsional" @selected(old('kategori')==='opsional')>
                        Opsional
                    </option>
                </select>
            </div>

            <div>
                <label class="label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="input">
                    <option value="semua">Semua</option>
                    <option value="laki-laki" @selected(old('jenis_kelamin')==='laki-laki')>
                        Laki-laki
                    </option>
                    <option value="perempuan" @selected(old('jenis_kelamin')==='perempuan')>
                        Perempuan
                    </option>
                </select>
            </div>

            <div>
                <label class="label">Nama Biaya</label>
                <input
                    name="nama_biaya"
                    value="{{ old('nama_biaya') }}"
                    placeholder="Nama Biaya"
                    class="input">
            </div>

            <div>
                <label class="label">Nominal</label>
                <input
                    name="nominal"
                    type="number"
                    value="{{ old('nominal') }}"
                    placeholder="Nominal"
                    class="input">
            </div>

            <div class="md:col-span-3">
                <button class="btn-primary">
                    Simpan Biaya
                </button>
            </div>
        </form>
    </div>

    {{-- DAFTAR BIAYA --}}
    <div class="card">
        <h2 class="text-lg font-semibold mb-4">
            Daftar Biaya
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">JK</th>
                        <th class="px-4 py-3 text-left">Nominal</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($biaya as $b)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-medium">
                            {{ $b->nama_biaya }}
                        </td>
                        <td class="px-4 py-3">
                            {{ ucfirst($b->jenis_biaya) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ ucfirst($b->jenis_kelamin) }}
                        </td>
                        <td class="px-4 py-3">
                            Rp {{ number_format($b->nominal,0,',','.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($b->aktif)
                                <span class="badge-success">Aktif</span>
                            @else
                                <span class="badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 flex gap-3">
                            <form method="POST" action="{{ route('biaya.toggle',$b) }}">
                                @csrf
                                <button class="text-primary text-xs hover:underline">
                                    Toggle
                                </button>
                            </form>
                            <form
                                method="POST"
                                action="{{ route('biaya.destroy',$b) }}"
                                onsubmit="return confirm('Hapus biaya ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 text-xs hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                            Data biaya belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
