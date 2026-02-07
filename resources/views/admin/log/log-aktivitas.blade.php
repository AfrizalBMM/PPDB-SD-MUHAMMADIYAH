@extends('layouts.admin')

@section('page-title','Log Aktivitas')

@section('content')
<div class="card">

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
                @forelse($logs as $l)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $l->created_at->format('d-m-Y H:i') }}
                    </td>
                    <td class="px-4 py-3 font-medium">
                        {{ optional($l->user)->name ?? 'Public' }}
                    </td>
                    <td class="px-4 py-3">
                        {{ ucfirst($l->role) }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $l->aksi }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $l->keterangan }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $l->ip_address }}
                    </td>
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

    <div class="mt-4">
        {{ $logs->links() }}
    </div>

</div>
@endsection
