@props(['id'])

<div id="{{ $id }}" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        
        <div class="text-lg font-semibold mb-3">
            {{ $title ?? 'Konfirmasi' }}
        </div>

        <div class="text-sm text-gray-600 mb-5">
            {{ $slot }}
        </div>

        <div class="flex justify-end gap-2">
            {{ $footer ?? '' }}
        </div>
    </div>
</div>
