@extends('layouts.admin')

@section('content')

<div class="max-w-md mx-auto bg-white shadow rounded-lg p-6">

    <h2 class="text-lg font-semibold mb-4">
        🔐 Password Panitia
    </h2>

    <form method="POST" action="{{ route('admin.password.panitia.store') }}">
        @csrf

        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">
                Password
            </label>

            <div class="relative">

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan Password baru"
                    class="w-full border rounded px-3 py-2 pr-10"
                    required
                >

                <button
                    type="button"
                    onclick="togglePassword()"
                    class="absolute right-2 top-2 text-gray-500"
                >
                    👁
                </button>

            </div>

        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
            Simpan
        </button>

    </form>


    {{-- tampilkan password yang baru dibuat --}}
    @if(session('password_plain'))

    <div class="mt-4 text-center">

        <p class="text-sm text-gray-500 mb-2">
            Password Panitia Aktif
        </p>

        <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
            {{ session('password_plain') }}
        </span>

    </div>

    @endif


</div>


<script>
function togglePassword(){
    const input = document.getElementById('password');

    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

@endsection