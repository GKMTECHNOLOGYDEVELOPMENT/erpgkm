<div class="text-center p-8" id="contactosContainer">
    <div id="initialMessageContainer">
        {!! $icon !!}
        <h3 class="text-xl font-semibold mt-4 text-gray-700">{{ $title }}</h3>
        <p class="text-gray-500 mt-2">{{ $message }}</p>
        
        @if($showCreateButton)
            <button onclick="showContactoForm()" 
                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Agregar Contacto
            </button>
        @endif
    </div>

    <!-- Formulario oculto para agregar/editar contacto -->
    <div id="contactoFormContainer" class="hidden mt-6">
        <!-- Aquí irá el formulario dinámicamente -->
    </div>

    <!-- Lista de contactos -->
    <div id="contactosList" class="mt-8 space-y-4">
        <!-- Los contactos se cargarán aquí dinámicamente -->
    </div>
</div>


