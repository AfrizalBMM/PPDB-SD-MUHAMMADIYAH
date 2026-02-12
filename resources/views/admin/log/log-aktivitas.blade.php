@extends('layouts.admin')

@section('page-title','Log Aktivitas')

@section('content')
<div class="space-y-6">

    <div class="card">

        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-lg text-slate-800">
                Log Aktivitas Sistem
            </h2>

            <span class="text-xs text-slate-500">
                Total: {{ $logs->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto max-h-[70vh]">
            <table class="table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
                        <th>IP</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $l)
                    <tr>
                        <td class="whitespace-nowrap text-xs text-slate-500">
                            {{ $l->created_at->format('d-m-Y H:i') }}
                        </td>

                        <td class="font-medium">
                            {{ optional($l->user)->name ?? 'Public' }}
                        </td>

                        <td>
                            @php $role = $l->role ?? 'guest'; @endphp

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

                        <td class="font-semibold text-primary">
                            {{ $l->aksi }}
                        </td>

                        <td class="text-slate-700">
                            {{ $l->keterangan }}
                        </td>

                        <td class="text-xs text-slate-500 whitespace-nowrap">
                            {{ $l->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-500">
                            Log aktivitas belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>

</div>
@endsection
