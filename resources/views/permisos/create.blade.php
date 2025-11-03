<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Crear Nuevo Permiso</h1>
            <a href="{{ route('permisos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <!-- Formulario -->
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
            <form action="{{ route('permisos.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Permiso</label>
                        <input type="text" name="nombre" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Módulo</label>
                        <input type="text" name="modulo" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('permisos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Crear Permiso
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout.default>