@extends('layouts.admin')

@section('page-title','Manajemen User')

@section('content')
<div class="grid md:grid-cols-2 gap-6">

    {{-- Form Tambah User --}}
    <div class="card">
        <h3 class="font-semibold text-slate-800 mb-4">
            Tambah User
        </h3>

        <form id="createUserForm" method="POST" action="{{ route('users.store') }}" class="space-y-3">
            @csrf

            <div>
                <label class="label">Nama</label>
                <input
                    name="name"
                    value="{{ old('name') }}"
                    class="input"
                    placeholder="Nama user">
            </div>

            <div>
                <label class="label">Email</label>
                <input
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="input"
                    placeholder="Email user">
            </div>

            <div>
                <label class="label">Role</label>
                <select name="role" class="input">
                    <option value="">Pilih Role</option>
                    <option value="admin" @selected(old('role') === 'admin')>
                        Admin
                    </option>
                    <option value="keuangan" @selected(old('role') === 'keuangan')>
                        Keuangan
                    </option>
                </select>
            </div>

            <button 
                type="button"
                onclick="openModal('confirmUserModal')"
                class="btn-primary w-fit">
                Simpan
            </button>
            
        </form>
    </div>

    {{-- Daftar User --}}
    <div class="card">
        <h3 class="font-semibold text-slate-800 mb-4">
            Daftar User
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr class="border-b">
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-medium">
                            {{ $u->name }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $u->email }}
                        </td>
                        <td class="px-4 py-3">
                            {{ ui_label($u->role) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                            Belum ada user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col gap-3 border-t border-slate-200 pt-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                <label for="perPageUsers">Tampilkan</label>
                <select id="perPageUsers" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                    @foreach([10,20,50,100] as $size)
                        <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </form>

            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>

</div>

{{-- MODAL KONFIRMASI --}}
<x-modal id="confirmUserModal" title="Konfirmasi Simpan">
    <p>Yakin ingin menambahkan user baru?</p>

    <div class="flex justify-end gap-2 mt-4">
        <button 
            type="button"
            onclick="closeModal('confirmUserModal')" 
            class="btn-secondary">
            Batal
        </button>

        <button 
            type="button"
            onclick="document.getElementById('createUserForm').submit()" 
            class="btn-primary">
            Ya, Simpan
        </button>
    </div>
</x-modal>


@endsection
