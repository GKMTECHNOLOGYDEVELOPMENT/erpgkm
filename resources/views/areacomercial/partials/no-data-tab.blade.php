<div class="text-center p-8">
    {!! $icon !!}
    <h3 class="text-xl font-semibold mt-4 text-gray-700">{{ $title }}</h3>
    <p class="text-gray-500 mt-2">{{ $message }}</p>
    
    @if($showCreateButton)
        <button onclick="createNew('{{ $type }}')" 
                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Crear Nuevo {{ $title }}
        </button>
    @endif
</div>