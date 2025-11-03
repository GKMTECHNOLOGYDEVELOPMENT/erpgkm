<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Combinaciones</h1>
            <div class="space-x-3">
                <a href="{{ route('permisos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Permisos
                </a>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Formulario para crear combinación -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Nueva Combinación</h2>
            <form action="{{ route('permisos.store-combinacion') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="idRol" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar Rol</option>
                        @foreach($roles as $rol)
                        <option value="{{ $rol->idRol }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Usuario</label>
                    <select name="idTipoUsuario" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar Tipo</option>
                        @foreach($tiposUsuario as $tipo)
                        <option value="{{ $tipo->idTipoUsuario }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Área</label>
                    <select name="idTipoArea" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar Área</option>
                        @foreach($tiposArea as $area)
                        <option value="{{ $area->idTipoArea }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Personalizado</label>
                    <input type="text" name="nombre_combinacion" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Opcional">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg w-full">
                        Crear Combinación
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Combinaciones -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Combinaciones Existentes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Área</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permisos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($combinaciones as $combinacion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $combinacion->nombre_combinacion ?: $combinacion->rol->nombre . ' - ' . $combinacion->tipoUsuario->nombre . ' - ' . $combinacion->tipoArea->nombre }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $combinacion->rol->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $combinacion->tipoUsuario->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $combinacion->tipoArea->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    {{ $combinacion->permisos->count() }} permisos
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('permisos.asignar-permisos', $combinacion->idCombinacion) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Gestionar Permisos
                                </a>
                                <form action="{{ route('permisos.destroy-combinacion', $combinacion->idCombinacion) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Está seguro de eliminar esta combinación?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout.default>