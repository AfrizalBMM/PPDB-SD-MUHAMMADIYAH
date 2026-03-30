@extends('layouts.admin')
@section('title','PAUD / TK')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- FORM TAMBAH --}}
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-slate-800">
                Tambah PAUD / TK
            </h2>

            <button onclick="document.getElementById('modalImport').classList.remove('hidden')"
                class="btn-success text-sm">
                📥 Import Excel
            </button>
        </div>

        <form method="POST" action="{{ route('paud-tk.store') }}" class="grid md:grid-cols-6 gap-4">
            @csrf

            {{-- NPSN --}}
            <div>
                <label class="label">NPSN</label>
                <input type="text" name="npsn" class="input" placeholder="NPSN" value="{{ old('npsn') }}">
                @error('npsn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nama --}}
            <div class="md:col-span-2">
                <label class="label">Nama PAUD / TK</label>
                <input type="text" name="nama" class="input" placeholder="Nama PAUD / TK" value="{{ old('nama') }}" required>
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Jenis --}}
            <div>
                <label class="label">Jenis</label>
                <select name="jenis" class="input" required>
                    <option value="">Pilih</option>
                    <option value="PAUD" {{ old('jenis')=='PAUD'?'selected':'' }}>PAUD</option>
                    <option value="TK" {{ old('jenis')=='TK'?'selected':'' }}>TK</option>
                </select>
                @error('jenis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Akreditasi --}}
            <div>
                <label class="label">Akreditasi</label>
                <select name="akreditasi" class="input">
                    <option value="">-</option>
                    <option value="A" {{ old('akreditasi')=='A'?'selected':'' }}>A</option>
                    <option value="B" {{ old('akreditasi')=='B'?'selected':'' }}>B</option>
                    <option value="C" {{ old('akreditasi')=='C'?'selected':'' }}>C</option>
                    <option value="Belum" {{ old('akreditasi')=='Belum'?'selected':'' }}>Belum</option>
                </select>
            </div>

            {{-- Telp --}}
            <div>
                <label class="label">Telepon</label>
                <input type="text" name="telp" class="input" placeholder="No Telepon" value="{{ old('telp') }}">
            </div>

            {{-- Kelurahan --}}
            <div>
                <label class="label">Kelurahan</label>
                <input type="text" name="kelurahan" class="input" placeholder="Kelurahan" value="{{ old('kelurahan') }}">
            </div>

            {{-- Kecamatan --}}
            <div>
                <label class="label">Kecamatan</label>
                <input type="text" name="kecamatan" class="input" placeholder="Kecamatan" value="{{ old('kecamatan') }}">
            </div>

            {{-- Alamat --}}
            <div class="md:col-span-4">
                <label class="label">Alamat Lengkap</label>
                <input type="text" name="alamat" class="input" placeholder="Alamat lengkap">{{ old('alamat') }}</input>
            </div>

            {{-- Status Aktif + Button Simpan --}}
            <div class="md:col-span-6 flex items-center justify-between mt-4">
                {{-- Aktifkan Sekolah --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="aktif" value="1" checked class="rounded border-gray-300">
                    <span class="text-sm text-slate-700">Aktifkan sekolah ini</span>
                </div>

                {{-- Tombol Simpan --}}
                <div>
                    <button class="btn-primary">
                        Simpan
                    </button>
                </div>
            </div>

        </form>

    </div>

    {{-- TABEL DATA --}}
    <div class="card">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
            <!-- Judul -->
            <h2 class="font-semibold text-slate-800 text-center md:text-left md:w-1/8">
                Daftar PAUD / TK
            </h2>

            <!-- Pencarian -->
            <input type="text" id="searchInput" placeholder="Cari Nama / Kelurahan / Kecamatan"
                class="input w-full md:w-1/3 text-sm text-center md:text-left" 
                oninput="applyFilter()" />

            <!-- Status Filter -->
            <select id="statusFilter" class="input w-full md:w-1/6 text-sm text-center md:text-left" onchange="applyFilter()">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>

            <!-- Hapus Semua -->
            <button onclick="document.getElementById('modalDeleteAll').classList.remove('hidden')"
                    class="btn-danger text-sm md:w-1/8">
                🗑 Hapus Semua
            </button>
        </div>

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
                    <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="toggleDetail(this)">
                        <td class="px-4 py-3 font-medium flex items-center gap-2 cursor-pointer" onclick="toggleDetail(this)">
                        
                            <span class="expand-icon">▶</span>
                            {{ $item->nama }}
                        </td>
                        <td class="px-4 py-3">{{ $item->jenis }}</td>
                        <td class="px-4 py-3">{{ $item->kelurahan ?? '-' }} / {{ $item->kecamatan ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($item->aktif)
                                <span class="badge-success">Aktif</span>
                            @else
                                <span class="badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-3">

                                <!-- Toggle Aktif/Nonaktif -->
                                <form method="POST" action="{{ route('paud-tk.toggle', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-700 hover:underline">
                                        Toggle
                                    </button>
                                </form>

                                <!-- Hapus -->
                                <form method="POST" action="{{ route('paud-tk.destroy', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline"
                                        onclick="return window.globalConfirmSubmit(this.form, 'Hapus data ini?', { title: 'Konfirmasi Hapus' })">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    <tr class="detail-row hidden bg-slate-50">
                        <td colspan="5" class="px-4 py-3 text-sm text-slate-600">
                            <strong>NPSN:</strong> {{ $item->npsn ?? '-' }} <br>
                            <strong>Akreditasi:</strong> {{ $item->akreditasi ?? '-' }} <br>
                            <strong>Alamat:</strong> {{ $item->alamat ?? '-' }} <br>
                            <strong>Telepon:</strong> {{ $item->telp ?? '-' }}
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

        <div class="mt-4 flex flex-col gap-3 border-t border-slate-200 pt-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                <label for="perPagePaudTk">Tampilkan</label>
                <select id="perPagePaudTk" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                    @foreach([10,20,50,100] as $size)
                        <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 30) === $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </form>

            <div>
                {{ $data->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS SEMUA --}}
    <div id="modalDeleteAll" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">
            <h3 class="text-lg font-semibold text-red-600">Konfirmasi Hapus Semua Data</h3>
            <p>Apakah Anda yakin ingin menghapus <strong>semua data PAUD / TK</strong>? Aksi ini tidak bisa dibatalkan.</p>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" 
                    onclick="document.getElementById('modalDeleteAll').classList.add('hidden')"
                    class="btn-secondary">
                    Batal
                </button>

                <form method="POST" action="{{ route('paud-tk.destroyAll') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">
                        Hapus Semua
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>

{{-- MODAL IMPORT --}}
<div id="modalImport" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">
        <h3 class="text-lg font-semibold">Import Data PAUD / TK</h3>

        <form action="{{ route('paud-tk.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="text-sm font-medium">Upload File Excel</label>
                <input type="file" name="file" class="input mt-2" required accept=".xlsx,.xls">
            </div>

            <div class="text-sm text-slate-500">
                Gunakan template excel agar format sesuai sistem.
                <br>
                <a href="{{ route('paud-tk.template') }}" class="text-blue-600 hover:underline">
                    Download Template Excel
                </a>
                <br>
                Catatan: pada kolom status, 1 = aktif, 0 = tidak aktif.
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button"
                    onclick="document.getElementById('modalImport').classList.add('hidden')"
                    class="btn-secondary">
                    Batal
                </button>

                <button type="submit" class="btn-primary">
                    Import
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle detail row
function toggleDetail(row){
    let next = row.nextElementSibling;
    if(next && next.classList.contains('detail-row')){
        next.classList.toggle('hidden');
        let icon = row.querySelector('.expand-icon');
        if(icon){
            icon.textContent = next.classList.contains('hidden') ? '▶' : '▼';
        }
    }
}

// Tunggu DOM siap
document.addEventListener("DOMContentLoaded", function(){

    // Filter pencarian
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        let showNext = false;

        rows.forEach(row => {
            if(row.classList.contains('detail-row')) {
                row.style.display = showNext ? '' : 'none';
                return;
            }

            const nama = row.cells[0].textContent.toLowerCase();
            const wilayah = row.cells[2].textContent.toLowerCase();

            if(nama.includes(filter) || wilayah.includes(filter)){
                row.style.display = '';
                showNext = true; 
            } else {
                row.style.display = 'none';
                showNext = false;
                let next = row.nextElementSibling;
                if(next && next.classList.contains('detail-row') && !next.classList.contains('hidden')){
                    next.classList.add('hidden');
                    let icon = row.querySelector('.expand-icon');
                    if(icon) icon.textContent = '▶';
                }
            }
        });

        if(filter === ""){
            document.querySelectorAll('tr.detail-row').forEach(r => r.classList.add('hidden'));
            document.querySelectorAll('.expand-icon').forEach(i => i.textContent = '▶');
        }
    });

    // Filter status
    const statusFilter = document.getElementById('statusFilter');
    if(statusFilter){
        statusFilter.addEventListener('change', applyFilter);
    }

    // ===== LOADING BUTTON SIMPAN PAUD/TK =====
    const paudForm = document.querySelector('form[action*="paud-tk.store"]');
    if(paudForm){
        const submitBtn = paudForm.querySelector('button[type="submit"]');
        paudForm.addEventListener('submit', function(){
            submitBtn.disabled = true;
            submitBtn.textContent = 'Loading...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        });
    }

    // ===== LOADING BUTTON HAPUS INDIVIDUAL =====
    const deleteForms = document.querySelectorAll('form[action*="paud-tk.destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(){
            const btn = form.querySelector('button[type="submit"]');
            if(btn){
                btn.disabled = true;
                btn.textContent = 'Menghapus...';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    });

    // ===== LOADING BUTTON HAPUS SEMUA =====
    const deleteAllForm = document.querySelector('form[action*="paud-tk.destroyAll"]');
    if(deleteAllForm){
        deleteAllForm.addEventListener('submit', function(){
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
