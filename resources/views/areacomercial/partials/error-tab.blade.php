<div class="text-center p-8 text-red-500">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="text-xl font-semibold mt-4">Error</h3>
    <p class="mt-2">{{ $message }}</p>
    <button onclick="window.location.reload()" 
            class="mt-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
        Recargar Página
    </button>
</div>