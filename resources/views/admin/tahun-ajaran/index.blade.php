@extends('layouts.admin')
@section('title','Tahun Ajaran')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- FORM TAMBAH --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Tambah Tahun Ajaran
        </h2>

        <form
            method="POST"
            action="{{ route('tahun-ajaran.store') }}"
            class="flex flex-wrap items-end gap-4">
            @csrf

            <div>
                <label class="label">Nama Tahun Ajaran</label>
                <input
                    type="text"
                    name="nama"
                    placeholder=""
                    class="input w-48"
                    required>
                <p class="text-xs text-slate-500 mt-1">
                    Contoh: <span class="font-medium">2025/2026</span>
                </p>
            </div>

            {{-- WAJIB: default tidak aktif --}}
            <input type="hidden" name="aktif" value="0">

            <div class="flex items-center gap-2 mt-1">
                <input
                    type="checkbox"
                    name="aktif"
                    id="aktif"
                    value="1"
                    class="rounded border-slate-300">
                <label for="aktif" class="text-sm text-slate-700">
                    Aktifkan
                </label>
            </div>

            <div>
                <button class="btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Daftar Tahun Ajaran
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($data as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-medium">
                                {{ $item->nama }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($item->aktif)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-warning">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">

                                {{-- AKTIFKAN --}}
                                @if(!$item->aktif)
                                    <form
                                        method="POST"
                                        action="{{ route('tahun-ajaran.aktifkan',$item) }}"
                                        class="inline">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="text-xs text-blue-700 hover:underline">
                                            Aktifkan
                                        </button>
                                    </form>
                                @endif

                                {{-- HAPUS --}}
                                <form
                                    method="POST"
                                    action="{{ route('tahun-ajaran.destroy',$item) }}"
                                    class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="text-xs text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="3"
                                class="px-4 py-6 text-center text-slate-500">
                                Data tahun ajaran belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
