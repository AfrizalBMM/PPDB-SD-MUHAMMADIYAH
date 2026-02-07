@extends('layouts.admin')

@section('content')
<div class="card max-w-4xl">

    <h2 class="font-semibold text-lg text-slate-800 mb-4">
        Registrasi Akun
    </h2>

    {{-- FORM --}}
    <form method="POST" class="grid md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="label">Nama</label>
            <input
                name="name"
                value="{{ old('name') }}"
                placeholder="Nama"
                class="input">
        </div>

        <div>
            <label class="label">Email</label>
            <input
                name="email"
                type="email"
                value="{{ old('email') }}"
                placeholder="Email"
                class="input">
        </div>

        <div>
            <label class="label">Role</label>
            <select name="role" class="input">
                <option value="">Pilih Role</option>
                <option value="superadmin" @selected(old('role') === 'superadmin')>
                    Superadmin
                </option>
                <option value="admin" @selected(old('role') === 'admin')>
                    Admin
                </option>
                <option value="keuangan" @selected(old('role') === 'keuangan')>
                    Keuangan
                </option>
            </select>
        </div>

        <div>
            <label class="label">Password</label>
            <input
                name="password"
                type="password"
                placeholder="Password"
                class="input">
        </div>

        <div class="md:col-span-2 text-right">
            <button class="btn-primary">
                Simpan
            </button>
        </div>
    </form>

    <hr class="my-6">

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
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
                        <span class="badge-success">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                        Belum ada akun terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
