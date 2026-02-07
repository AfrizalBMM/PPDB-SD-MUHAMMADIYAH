@extends('layouts.admin')

@section('title','Master Voucher')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- FORM TAMBAH --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Tambah Voucher
        </h2>

        <form
            method="POST"
            action="{{ route('voucher.store') }}"
            class="grid md:grid-cols-3 gap-4">
            @csrf

            <div>
                <label class="label">Nama Voucher</label>
                <input
                    name="nama"
                    class="input"
                    placeholder="Nama Voucher"
                    required>
            </div>

            <div>
                <label class="label">Jenis Biaya</label>
                <select name="jenis_biaya" class="input" required>
                    <option value="">Pilih</option>
                    <option value="pendaftaran">Pendaftaran</option>
                    <option value="daftar_ulang">Daftar Ulang</option>
                    <option value="udp">UDP</option>
                </select>
            </div>

            <div>
                <label class="label">Diskon Nominal (Rp)</label>
                <input
                    type="number"
                    name="diskon_nominal"
                    class="input"
                    placeholder="Contoh: 50000"
                    min="0"
                    required>
            </div>

            <div>
                <label class="label">Maksimal Penggunaan</label>
                <input
                    type="number"
                    name="maks_penggunaan"
                    class="input"
                    placeholder="Contoh: 10"
                    min="1"
                    required>
            </div>

            <div>
                <label class="label">Tanggal Mulai</label>
                <input
                    type="date"
                    name="tanggal_mulai"
                    class="input"
                    required>
            </div>

            <div>
                <label class="label">Tanggal Selesai</label>
                <input
                    type="date"
                    name="tanggal_selesai"
                    class="input"
                    required>
            </div>

            <div class="md:col-span-3">
                <button class="btn-primary">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Daftar Voucher
        </h2>

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
                            <td class="px-4 py-3 font-mono">
                                {{ $v->kode }}
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $v->nama }}
                            </td>
                            <td class="px-4 py-3">
                                {{ ucfirst($v->jenis_biaya) }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                Rp {{ number_format($v->diskon_nominal,0,',','.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{ $v->digunakan }} / {{ $v->maks_penggunaan }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($v->masihBerlaku())
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-warning">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-3">
                                    <form
                                        method="POST"
                                        action="{{ route('voucher.toggle',$v) }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="text-xs text-blue-700 hover:underline">
                                            Toggle
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('voucher.destroy',$v) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-xs text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="px-4 py-6 text-center text-slate-500">
                                Data voucher belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
