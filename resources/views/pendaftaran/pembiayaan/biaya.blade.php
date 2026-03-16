@extends('layouts.public')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-8">

@if(session('success'))
<div id="alertSuccess"
     class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">
    ✅ {{ session('success') }}
</div>

<script>
setTimeout(function(){
    document.getElementById('alertSuccess').style.display='none';
},3000);
</script>
@endif
<div class="md:col-span-2 card">

    <h2 class="font-semibold text-lg text-slate-800 mb-4">
        Rincian Pembiayaan Calon Siswa
    </h2>

    <div class="bg-yellow-50 border border-yellow-200 p-3 text-xs rounded mb-6">
        ⚠️ Informasi pembiayaan ini bersifat RAHASIA, hanya untuk panitia dan orang tua/wali siswa.
    </div>


    {{-- ===============================
    DATA SISWA
    =============================== --}}
    <div class="overflow-x-auto mb-8">

        <h3 class="font-semibold mb-3">
            Informasi Siswa
        </h3>

        <table class="w-full text-sm border border-slate-200 rounded-lg">

            <tbody>

                <tr class="bg-slate-50">
                    <td class="p-3 w-48 font-medium">
                        Nama Siswa
                    </td>

                    <td class="p-3">
                        {{ $siswa->nama }}
                    </td>
                </tr>

                <tr>
                    <td class="p-3 font-medium">
                        No Registrasi
                    </td>

                    <td class="p-3">
                        {{ optional($siswa->registration)->nomor_registrasi ?? '-' }}
                    </td>
                </tr>

                <tr class="bg-slate-50">
                    <td class="p-3 font-medium">
                        Nama Ibu
                    </td>

                    <td class="p-3">
                        {{ optional($siswa->ibu)->nama ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="p-3 font-medium">
                        No HP Ibu
                    </td>

                    <td class="p-3">
                        {{ optional($siswa->ibu)->no_hp ?? '-' }}
                    </td>
                </tr>

            </tbody>

        </table>

    </div>



    {{-- ===============================
    RINCIAN BIAYA
    =============================== --}}
    <div class="overflow-x-auto">

        <h3 class="font-semibold mb-3">
            Rincian Biaya
        </h3>

        <table class="w-full text-sm border border-slate-200 rounded-lg">

            <thead class="bg-slate-100">

                <tr>
                    <th class="p-3 border text-left">
                        Jenis Biaya
                    </th>

                    <th class="p-3 border text-right">
                        Nominal
                    </th>

                    <th class="p-3 border text-right">
                        Diskon
                    </th>

                    <th class="p-3 border text-right">
                        Total
                    </th>

                    <th class="p-3 border text-right">
                        Kekurangan
                    </th>

                    <th class="p-3 border text-center">
                        Status
                    </th>

                    <th class="p-3 border text-center">
                        Aksi
                    </th>
                </tr>

            </thead>


            <tbody>

                @php
                    $totalBiaya = 0;
                    $totalKekurangan = 0;
                @endphp

                @foreach($siswa->tagihan as $tagihan)

                    @php
                        $totalBiaya += $tagihan->total;
                        $totalKekurangan += $tagihan->sisa;
                    @endphp


                    {{-- ROW TAGIHAN --}}
                    <tr class="{{ $loop->even ? 'bg-slate-50' : '' }}">

                        <td class="p-3 border">
                            {{ $tagihan->biaya->jenis_biaya ?? '-' }}
                        </td>

                        <td class="p-3 border text-right">
                            Rp {{ number_format($tagihan->nominal,0,',','.') }}
                        </td>

                        <td class="p-3 border text-right text-green-600">
                            Rp {{ number_format($tagihan->diskon,0,',','.') }}
                        </td>

                        <td class="p-3 border text-right font-medium">
                            Rp {{ number_format($tagihan->total,0,',','.') }}
                        </td>

                        <td class="p-3 border text-right">
                            Rp {{ number_format($tagihan->sisa,0,',','.') }}
                        </td>

                        <td class="p-3 border text-center">

                            @if($tagihan->is_lunas)

                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    Lunas
                                </span>

                            @else

                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                                    Belum Lunas
                                </span>

                            @endif

                        </td>

                        <td class="p-3 border text-center">

                            @if(!$tagihan->is_lunas)

                                <button
                                    onclick="openBayarModal({{ $tagihan->id }}, {{ $tagihan->sisa }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded"
                                >
                                    Bayar
                                </button>

                            @else

                                <button
                                    class="bg-gray-300 text-gray-600 text-xs px-3 py-1 rounded cursor-not-allowed"
                                    disabled
                                >
                                    Lunas
                                </button>

                            @endif

                        </td>

                    </tr>

                    {{-- BREAKDOWN CICILAN --}}
                    @if($tagihan->pembayaran->count())

                        <tr>

                            <td colspan="7" class="border bg-slate-50 p-3">

                                <div class="text-xs font-semibold mb-2">
                                    Riwayat Cicilan
                                </div>

                                <table class="w-full text-xs">

                                    <thead>
                                    <tr class="text-slate-600">

                                        <th class="text-left py-2">
                                            Tanggal Bayar
                                        </th>

                                        <th class="text-left py-1">
                                            Nominal
                                        </th>

                                        <th class="text-left py-1">
                                            Penerima
                                        </th>

                                        <th class="text-left py-1">
                                            Keterangan
                                        </th>

                                        <th class="text-center py-1">
                                            Nota
                                        </th>

                                    </tr>
                                    </thead>


                                    <tbody>

                                        @foreach($tagihan->pembayaran as $bayar)

                                            <tr class="border-t">

                                            <td class="py-1">
                                                {{ $bayar->tanggal_bayar->format('d M Y') }}
                                            </td>

                                            <td class="py-1 text-left">
                                                Rp {{ number_format($bayar->nominal_bayar,0,',','.') }}
                                            </td>

                                            <td class="py-1">
                                                {{ $bayar->admin_penerima ?? '-' }}
                                            </td>

                                            <td class="py-1">
                                                {{ $bayar->keterangan ?? '-' }}
                                            </td>

                                            <td class="py-1 text-center">

                                            <a
                                                href="{{ route('pembayaran.public.nota',$bayar->id) }}"
                                                target="_blank"
                                                class="text-blue-600 hover:underline"
                                            >
                                                Cetak
                                            </a>

                                            </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </td>

                        </tr>

                    @endif


                @endforeach

            </tbody>



            <tfoot>

                <tr class="bg-slate-100 font-semibold">

                <td class="p-3 border text-right" colspan="3">
                    Total Biaya
                </td>

                <td class="p-3 border text-right">
                    Rp {{ number_format($totalBiaya,0,',','.') }}
                </td>

                <td colspan="3" class="border"></td>

                </tr>

                <tr class="bg-red-50 font-semibold">

                <td class="p-3 border text-right" colspan="4">
                    Total Kekurangan
                </td>

                <td class="p-3 border text-right text-red-600">
                    Rp {{ number_format($totalKekurangan,0,',','.') }}
                </td>

                <td colspan="2" class="border"></td>

                </tr>

            </tfoot>

        </table>

    </div>



    <div class="mt-6">

        <a
            href="{{ route('pendaftaran.list') }}"
            class="btn-primary inline-block"
        >
            Kembali
        </a>

    </div>

</div>

</div>

{{-- ===============================
MODAL PEMBAYARAN
=============================== --}}
<div
    id="modalBayar"
    class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50"
>

    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

        <h3 class="font-semibold text-lg mb-4">
            Pembayaran Cicilan
        </h3>

        <form method="POST" action="{{ route('pembayaran.store') }}">
            @csrf

            <input type="hidden" name="tagihan_siswa_id" id="tagihan_id">

            {{-- SISA TAGIHAN --}}
            <div class="mb-3">

                <label class="block text-sm font-medium mb-1">
                    Sisa Tagihan
                </label>

                <input
                    type="text"
                    id="sisa_tagihan"
                    class="w-full border rounded px-3 py-2 bg-gray-100"
                    readonly
                >

            </div>

            {{-- TANGGAL BAYAR --}}
            <div class="mb-3">

                <label class="block text-sm font-medium mb-1">
                    Tanggal Bayar
                </label>

                <input
                    type="date"
                    name="tanggal_bayar"
                    value="{{ date('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >

            </div>
            
            {{-- NOMINAL BAYAR --}}
            <div class="mb-3">

                <label class="block text-sm font-medium mb-1">
                    Nominal Bayar
                </label>

                <div class="flex">

                    {{-- PREFIX RP --}}
                    <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-gray-100 rounded-l">
                        Rp.
                    </span>

                    {{-- INPUT DISPLAY --}}
                    <input
                        type="text"
                        id="nominal_display"
                        class="w-full border border-gray-300 rounded-r px-3 py-2 focus:outline-none"
                        placeholder="0"
                        onkeyup="formatRupiah(this)"
                        required
                    >

                </div>

                {{-- INPUT ANGKA ASLI --}}
                <input
                    type="hidden"
                    name="nominal_bayar"
                    id="nominal_bayar"
                >

            </div>

            {{-- METODE --}}
            <div class="mb-3">

                <label class="block text-sm font-medium mb-1">
                    Metode Pembayaran
                </label>

                <select
                    name="metode"
                    class="w-full border rounded px-3 py-2"
                >
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                </select>

            </div>

            {{-- KETERANGAN --}}
            <div class="mb-4">

                <label class="block text-sm font-medium mb-1">
                    Keterangan
                </label>

                <input
                    type="text"
                    name="keterangan"
                    class="w-full border rounded px-3 py-2"
                    placeholder="opsional"
                >

            </div>

            <div class="mb-3">

                <label class="block text-sm font-medium mb-1">
                    Penerima Pembayaran (Wajib)
                </label>

                <input
                    type="text"
                    name="admin_penerima"
                    class="w-full border rounded px-3 py-2"
                    placeholder="Nama petugas"
                    required
                >

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="closeBayarModal()"
                    class="px-4 py-2 bg-gray-300 rounded"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Simpan Pembayaran
                </button>

            </div>

        </form>

    </div>

</div>

<script>

function openBayarModal(tagihanId, sisa)
{
    document.getElementById('modalBayar').classList.remove('hidden');
    document.getElementById('modalBayar').classList.add('flex');

    document.getElementById('tagihan_id').value = tagihanId;

    document.getElementById('sisa_tagihan').value =
        'Rp ' + new Intl.NumberFormat('id-ID').format(sisa);
    
    document.getElementById('nominal_bayar').max = sisa;
}

function closeBayarModal()
{
    document.getElementById('modalBayar').classList.add('hidden');
    document.getElementById('modalBayar').classList.remove('flex');
}

function formatRupiah(input)
{
    let angka = input.value.replace(/\D/g,'');

    document.getElementById('nominal_bayar').value = angka;

    let formatted = new Intl.NumberFormat('id-ID').format(angka);

    input.value = formatted;
}

</script>

@endsection
