

{{-- Este lo separas completamente para que no se oculte --}}
@if($showCreateButton)
    <div id="create-button-wrapper" class="text-center mt-4">
        <button onclick="createNew('{{ $type }}')" 
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Crear Nuevo {{ $title }}
        </button>
    </div>
@endif

<div id="create-form-container" class="mt-6 hidden"></div>

<div id="data-list" class="mt-6 space-y-4"></div>
