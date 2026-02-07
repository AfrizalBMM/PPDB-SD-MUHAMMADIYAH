<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login • PPDB SD Muhammadiyah Wonorejo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6">

    {{-- Header --}}
    <div class="text-center mb-6">
        <img
            src="{{ asset('logo.png') }}"
            alt="Logo SD Muhammadiyah Wonorejo"
            class="h-16 mx-auto mb-3">
        <h1 class="text-xl font-bold text-slate-800">
            PPDB SD Muhammadiyah Wonorejo
        </h1>
        <p class="text-sm text-slate-500">
            Silakan login untuk melanjutkan
        </p>
    </div>

    {{-- Card --}}
    <div class="card">
        <form method="POST" action="{{ route('login.proses') }}" class="space-y-4">
            @csrf

            <div>
                <label class="label">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="email@contoh.com"
                    class="input"
                    required>
                @error('email')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="label">Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    class="input"
                    required>
                @error('password')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button class="btn-primary w-full py-2">
                Login
            </button>
        </form>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-slate-400 mt-6">
        © {{ date('Y') }} SD Muhammadiyah Wonorejo
    </p>

</div>

</body>
</html>
