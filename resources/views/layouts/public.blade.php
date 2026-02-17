<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','PPDB SD Muhammadiyah Wonorejo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-100 text-slate-800 font-sans flex flex-col min-h-screen">

{{-- HEADER --}}
<header class="bg-primary text-white">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <div class="flex items-center gap-4">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo SD Muhammadiyah Wonorejo"
                class="h-12 w-auto">

            <div>
                <h1 class="font-bold text-lg leading-tight">
                    PPDB SD Muhammadiyah Wonorejo
                </h1>
                <p class="text-xs opacity-80">
                    Pendaftaran Calon Siswa
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">

            {{-- DAFTAR PENDAFTAR --}}
            <a
                href="{{ route('pendaftaran.public') }}"
                class="bg-secondary text-white px-4 py-2 rounded-lg
                    text-sm font-semibold hover:bg-blue-700 transition">
                📋 Daftar Calon Siswa
            </a>

            {{-- LOGIN --}}
            <a
                href="{{ route('login') }}"
                class="bg-white text-primary px-4 py-2 rounded-lg
                    text-sm font-semibold hover:bg-slate-100 transition">
                Login
            </a>

        </div>


    </div>
</header>

{{-- MAIN --}}
<main class="flex-1 py-8">
    @isset($slot)
        {{ $slot }}
    @endisset

    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="text-center text-xs text-slate-400 py-4">
    © {{ date('Y') }} SD Muhammadiyah Wonorejo
</footer>

@livewireScripts
</body>
</html>
