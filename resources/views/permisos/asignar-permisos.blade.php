<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Asignar Permisos a Combinación</h1>
            <div class="space-x-3">
                <a href="{{ route('permisos.combinaciones') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Combinaciones
                </a>
            </div>
        </div>

        <!-- Información de la Combinación -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Combinación: {{ $combinacion->nombre_completo }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-700">
                <div><strong>Rol:</strong> {{ $combinacion->rol->nombre }}</div>
                <div><strong>Tipo Usuario:</strong> {{ $combinacion->tipoUsuario->nombre }}</div>
                <div><strong>Tipo Área:</strong> {{ $combinacion->tipoArea->nombre }}</div>
            </div>
        </div>

        <!-- Formulario de Permisos -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <form action="{{ route('permisos.guardar-permisos-combinacion', $combinacion->idCombinacion) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach($permisos as $permiso)
                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="permisos[]" value="{{ $permiso->idPermiso }}" 
                               {{ in_array($permiso->idPermiso, $permisosAsignados) ? 'checked' : '' }}
                               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div class="ml-3">
                            <span class="text-sm font-medium text-gray-900">{{ $permiso->nombre }}</span>
                            <p class="text-xs text-gray-500">{{ $permiso->descripcion }}</p>
                            <span class="inline-block mt-1 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                                {{ $permiso->modulo }}
                            </span>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('permisos.combinaciones') }}" class="px-6 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-lg">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Guardar Permisos
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout.default>