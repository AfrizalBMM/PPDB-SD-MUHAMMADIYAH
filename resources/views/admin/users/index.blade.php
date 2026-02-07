@extends('layouts.admin')

@section('page-title','Manajemen User')

@section('content')
<div class="grid md:grid-cols-2 gap-6">

    {{-- Form Tambah User --}}
    <div class="card">
        <h3 class="font-semibold text-slate-800 mb-4">
            Tambah User
        </h3>

        <form method="POST" action="{{ route('users.store') }}" class="space-y-3">
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

            <button class="btn-primary w-fit">
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
                            {{ ucfirst($u->role) }}
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
    </div>

</div>
@endsection
