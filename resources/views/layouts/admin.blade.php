<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Admin PPDB')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-muted">

<div class="flex min-h-screen">

    {{-- OVERLAY MOBILE --}}
    <div id="sidebarOverlay"
         onclick="toggleSidebar()"
         class="fixed inset-0 bg-black/40 z-30 hidden md:hidden">
    </div>

    {{-- SIDEBAR --}}
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-primary text-white
               transform -translate-x-full md:translate-x-0
               transition-transform duration-200">

        @include('layouts.partials.sidebar')
    </aside>

    {{-- CONTENT --}}
    <div class="flex-1 flex flex-col md:ml-64">

        {{-- HEADER --}}
        <header class="bg-white shadow px-4 py-3 flex items-center justify-between sticky top-0 z-20">
            <button onclick="toggleSidebar()" class="md:hidden text-xl">☰</button>

            <h1 class="font-semibold text-lg">
                @yield('page-title','Dashboard')
            </h1>

            <span class="text-sm text-slate-600">
                {{ auth()->user()->name }}
            </span>
        </header>

        {{-- MAIN --}}
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

function openDeleteModal(action){
    const modal = document.getElementById('deleteModal');
    const form  = document.getElementById('deleteForm');
    form.action = action;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function openConfirmModal(){
    document.getElementById('confirmModal').classList.remove('hidden');
    document.getElementById('confirmModal').classList.add('flex');
}

function closeConfirmModal(){
    document.getElementById('confirmModal').classList.add('hidden');
}


function closeDeleteModal(){
    document.getElementById('deleteModal').classList.add('hidden');
}

document.querySelectorAll('input[name="nominal"]').forEach(el => {
    el.addEventListener('input', function(){
        this.value = this.value.replace(/\D/g,'');
    });
});
</script>

</body>
</html>
