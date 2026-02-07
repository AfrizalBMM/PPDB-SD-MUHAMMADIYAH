<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Admin PPDB')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-100 font-sans">

<div class="flex min-h-screen">

    {{-- OVERLAY --}}
    <div id="sidebarOverlay"
         onclick="toggleSidebar()"
         class="fixed inset-0 bg-black/40 z-30 hidden md:hidden">
    </div>

    {{-- SIDEBAR WRAPPER --}}
    <aside
        id="sidebar"
        class="fixed md:static inset-y-0 left-0 z-40
               w-64 bg-primary text-white
               transform -translate-x-full md:translate-x-0
               transition-transform duration-200 ease-in-out">

        @include('layouts.partials.sidebar')
    </aside>

    {{-- CONTENT --}}
    <div class="flex-1 flex flex-col md:ml-64">

        <header class="bg-white shadow px-4 py-3 flex items-center justify-between">
            <button onclick="toggleSidebar()" class="md:hidden text-lg">☰</button>

            <h1 class="font-semibold text-lg hidden md:block">
                @yield('page-title','Dashboard')
            </h1>

            <span class="hidden md:block text-sm">
                {{ auth()->user()->name }}
            </span>
        </header>

        <main class="p-4 md:p-6 flex-1">
            @yield('content')
        </main>
    </div>

</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>

</body>
</html>
