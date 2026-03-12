
@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- PAGE CARD -->
    <div class="bg-white shadow-lg rounded-xl border border-slate-200 min-h-screen flex flex-col">

        <!-- HEADER -->
        <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">📋 Daftar Pendaftar PPDB</h1>
                <p class="text-sm text-slate-500 mt-1">Manajemen data calon peserta didik baru</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pendaftaran.public') }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700">
                    + Daftar Siswa Baru
                </a>
            </div>
        </div>

        <!-- TOOLBAR -->
        <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-slate-50">
            <!-- SEARCH -->
            <form method="GET" class="relative w-full md:w-72">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama siswa..."
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            </form>

            <!-- FILTER STATUS -->
            <div class="flex gap-2">
                <button class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">Diterima</button>
                <button class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</button>
                <button class="px-3 py-1 bg-slate-100 text-slate-600 text-xs rounded-full">Semua</button>
            </div>
        </div>

        <!-- TABLE WRAPPER -->
        <div class="flex-1 overflow-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700 sticky top-0 z-10">
                    <tr>
                        <th class="p-3 border">No</th>
                        <th class="p-2 border">Tanggal Daftar</th>
                        <th class="p-3 border text-left">Data Siswa</th>
                        <th class="p-3 border">JK</th>
                        <th class="p-3 border text-left">Data Ibu</th>
                        <th class="p-3 border">Voucher</th>
                        <th class="p-3 border">Biaya</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white">
                    @forelse($siswa as $item)
                    <tr class="hover:bg-slate-50 transition">
                        
                        <!-- NO -->
                        <td class="p-2 border text-center">
                            {{ $loop->iteration }}
                        </td>

                        <!-- TANGGAL DAFTAR -->
                        <td class="p-2 border text-center text-xs">
                            {{ $item->created_at->format('d M Y') }}
                        </td>

                        <!-- NAMA + NO REGISTRASI -->
                        <td class="p-2 border">
                            <div class="font-semibold text-slate-800">
                                {{ $item->nama }}
                            </div>

                            <div class="text-xs mt-1">
                                <span class="px-2 py-1 bg-green-600 text-white rounded-full text-[10px]">
                                    {{ optional($item->registration)->nomor_registrasi ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- JK -->
                        <td class="p-2 border text-center capitalize">
                            {{ $item->jenis_kelamin }}
                        </td>

                        <!-- NAMA IBU + NO HP -->
                        <td class="p-2 border">
                            <div class="font-medium">
                                {{ optional($item->ibu)->nama ?? '-' }}
                            </div>

                            <div class="text-xs text-slate-500">
                                {{ optional($item->ibu)->no_hp ?? '-' }}
                            </div>
                        </td>

                        <!-- VOUCHER -->
                        @php
                        $voucher = $item->tagihan->firstWhere('kode_voucher','!=',null);
                        @endphp

                        <td class="p-2 border text-center">
                        @if($voucher)
                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">
                                {{ $voucher->kode_voucher }}
                            </span>
                        @else
                            -
                        @endif
                        </td>

                        <!-- BIAYA -->
                        <td class="p-2 border text-center font-semibold text-slate-700">
                            Rp {{ number_format($item->tagihan_sum_total ?? 0, 0, ',', '.') }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-2 border text-center">
                            @if($item->status == 'diterima')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    Diterima
                                </span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                                    Pending
                                </span>
                            @endif
                        </td>

                        <!-- AKSI -->
                        <td class="p-2 border text-center">
                            <div x-data="{ open: false }" class="relative inline-block">

                                <!-- BUTTON -->
                                <button @click="open = !open"
                                    class="px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 flex items-center gap-1">
                                    Aksi
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- DROPDOWN -->
                                <div x-show="open"
                                    x-transition
                                    @click.away="open = false"
                                    class="absolute right-0 mt-2 w-40 bg-white border border-slate-200 rounded shadow-lg text-xs z-50">

                                    <!-- CETAK -->
                                    <button 
                                        @click="open = false; openModalPetugas({{ $item->id }})"
                                        class="block w-full text-left px-4 py-2 hover:bg-slate-100">
                                        📄 Cetak Formulir
                                    </button>

                                    <!-- BIAYA -->
                                    <a href="#"
                                    onclick="event.preventDefault(); bukaPasswordModal('{{ route('pendaftaran.biaya', $item) }}')"
                                    class="block px-4 py-2 hover:bg-slate-100">
                                    💰 Rincian Biaya
                                    </a>

                                </div>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-6 text-center text-slate-500">
                            Belum ada pendaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- FOOTER PAGINATION -->
        <div class="p-4 border-t border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-sm text-slate-600">
                Menampilkan {{ $siswa->firstItem() }} - {{ $siswa->lastItem() }} dari {{ $siswa->total() }} data
            </div>

            <div class="flex justify-center md:justify-end w-full md:w-auto">
                {{ $siswa->links() }}
            </div>
        </div>

    </div>

</div>

{{-- MODAL CETAK FORMULIR --}}
<div id="modalPetugas" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">🖨️ Cetak Formulir Pendaftaran</h3>

        <form method="POST" action="{{ route('cetak.formulir.post') }}" target="_blank">
            @csrf
            <input type="hidden" name="siswa_id" id="modalSiswaId">

            <div class="mb-4">
                <label class="text-sm font-medium">Nama Petugas</label>
                <input type="text" name="nama_petugas" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalPetugas()"
                    class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Cetak
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalPassword"
     class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center">

    <div class="bg-white p-6 rounded w-80">

        <h3 class="font-semibold mb-3">
            Password Panitia
        </h3>

        <form method="POST" action="{{ route('verifikasi.password.panitia') }}">
            @csrf

            <input type="hidden" name="redirect_url" id="redirectUrl">

            <input
                type="password"
                name="password"
                class="w-full border rounded px-3 py-2 mb-3"
                placeholder="Masukkan password"
                required
            >

            <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Verifikasi
            </button>
        </form>

    </div>

</div>

<script>
function openModalPetugas(id) {
    document.getElementById('modalSiswaId').value = id;
    document.getElementById('modalPetugas').classList.remove('hidden');
}

function closeModalPetugas() {
    document.getElementById('modalPetugas').classList.add('hidden');
}

function bukaPasswordModal(url)
{
    document.getElementById('redirectUrl').value = url;

    const modal = document.getElementById('modalPassword');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
</script>

@endsection