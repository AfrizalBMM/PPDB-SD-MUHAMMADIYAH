@extends('layouts.admin')
@section('title','PAUD / TK')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- FORM TAMBAH --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Tambah PAUD / TK
        </h2>

        <form method="POST" action="{{ route('paud-tk.store') }}" class="grid md:grid-cols-6 gap-4">
            @csrf

            <div>
                <label class="label">NPSN</label>
                <input type="text" name="npsn" class="input" placeholder="NPSN" value="{{ old('npsn') }}">
                @error('npsn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="label">Nama PAUD / TK</label>
                <input type="text" name="nama" class="input" placeholder="Nama PAUD / TK" value="{{ old('nama') }}" required>
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Jenis</label>
                <select name="jenis" class="input" required>
                    <option value="">Pilih</option>
                    <option value="PAUD" {{ old('jenis')=='PAUD'?'selected':'' }}>PAUD</option>
                    <option value="TK" {{ old('jenis')=='TK'?'selected':'' }}>TK</option>
                </select>
                @error('jenis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Kelurahan</label>
                <input type="text" name="kelurahan" class="input" placeholder="Kelurahan" value="{{ old('kelurahan') }}">
                @error('kelurahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Kecamatan</label>
                <input type="text" name="kecamatan" class="input" placeholder="Kecamatan" value="{{ old('kecamatan') }}">
                @error('kecamatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-6">
                <button class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Daftar PAUD / TK
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Wilayah</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($data as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                            <td class="px-4 py-3">{{ $item->jenis }}</td>
                            <td class="px-4 py-3">
                                {{ $item->kelurahan ?? '-' }} / {{ $item->kecamatan ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if($item->aktif)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-danger">Nonaktif</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-3">

                                    <form method="POST" action="{{ route('paud-tk.toggle', $item->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-700 hover:underline">
                                            Toggle
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('paud-tk.destroy', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:underline"
                                            onclick="return confirm('Hapus data ini?')">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                Data PAUD / TK belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
