@extends('layouts.admin')
@section('title','Log Aktivitas')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- SEARCH + FILTER + HAPUS SEMUA --}}
    <div class="card">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
            <h2 class="font-semibold text-slate-800 text-center md:text-left md:w-1/8">
                Log Aktivitas Sistem
            </h2>

            {{-- Pencarian --}}
            <input type="text" id="searchInput" placeholder="Cari User / Aksi / Keterangan"
                class="input w-full md:w-1/3 text-sm text-center md:text-left" 
                oninput="applyFilter()" />

            {{-- Filter Role --}}
            <select id="roleFilter" class="input w-full md:w-1/6 text-sm text-center md:text-left" onchange="applyFilter()">
                <option value="">Semua Role</option>
                <option value="superadmin">Superadmin</option>
                <option value="admin">Admin</option>
                <option value="keuangan">Keuangan</option>
                <option value="guest">Public</option>
            </select>

            {{-- Hapus Semua --}}
            <button onclick="document.getElementById('modalDeleteAll').classList.remove('hidden')"
                    class="btn-danger text-sm md:w-1/8">
                🗑 Hapus Semua
            </button>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-left">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-xs">{{ $log->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-3 font-medium">{{ optional($log->user)->name ?? 'Public' }}</td>
                        <td class="px-4 py-3">
                            @php $role = $log->role ?? 'guest'; @endphp
                            @if($role == 'superadmin')
                                <span class="badge-danger">Superadmin</span>
                            @elseif($role == 'admin')
                                <span class="badge-info">Admin</span>
                            @elseif($role == 'keuangan')
                                <span class="badge-warning">Keuangan</span>
                            @else
                                <span class="badge-success">Public</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold text-primary">{{ $log->aksi }}</td>
                        <td class="px-4 py-3">{{ $log->keterangan }}</td>
                        <td class="px-4 py-3 text-xs">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                            Log aktivitas belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>

    {{-- MODAL HAPUS SEMUA --}}
    <div id="modalDeleteAll" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">
            <h3 class="text-lg font-semibold text-red-600">Konfirmasi Hapus Semua Log</h3>
            <p>Apakah Anda yakin ingin menghapus <strong>semua log aktivitas</strong>? Aksi ini tidak bisa dibatalkan.</p>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" 
                    onclick="document.getElementById('modalDeleteAll').classList.add('hidden')"
                    class="btn-secondary">
                    Batal
                </button>

                <form method="POST" action="{{ route('logs.destroyAll') }}">
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

<script>
function applyFilter() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        if(row.querySelector('td')) {
            const user = row.cells[1].textContent.toLowerCase();
            const role = row.cells[2].textContent.toLowerCase();
            const aksi = row.cells[3].textContent.toLowerCase();
            const keterangan = row.cells[4].textContent.toLowerCase();

            if(
                (user.includes(search) || aksi.includes(search) || keterangan.includes(search)) &&
                (roleFilter === '' || role === roleFilter)
            ){
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}
</script>

@endsection
