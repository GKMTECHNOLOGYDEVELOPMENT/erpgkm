<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold text-gray-700">Observaciones Comerciales</h3>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Nueva Observación
        </button>
    </div>

    @if(count($observaciones) > 0)
        <div class="space-y-4">
            @foreach($observaciones as $observacion)
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex justify-between">
                    <div class="flex items-center">
                        <span class="inline-block h-8 w-8 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center font-bold">
                            {{ substr($observacion->usuario->name, 0, 1) }}
                        </span>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $observacion->usuario->name }}</p>
                            <p class="text-sm text-gray-500">{{ $observacion->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-900">Editar</button>
                        <button class="text-red-600 hover:text-red-900">Eliminar</button>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-700">
                    {{ $observacion->contenido }}
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <h4 class="mt-2 text-sm font-medium text-gray-900">No hay observaciones registradas</h4>
            <p class="mt-1 text-sm text-gray-500">Agrega observaciones relevantes para este seguimiento.</p>
        </div>
    @endif
</div>