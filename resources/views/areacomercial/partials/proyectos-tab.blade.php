<div class="space-y-6">

    @if (count($proyectos) > 0)
        <div class="bg-gray-50 p-4 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Servicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($proyectos as $proyecto)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->servicio->nombre }}</td>
                            <td class="px-6 py-4">{{ $proyecto->descripcion }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $proyecto->estado == 'aprobado'
                                        ? 'bg-green-100 text-green-800'
                                        : ($proyecto->estado == 'pendiente'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($proyecto->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-600 hover:text-blue-900 mr-2">Editar</button>
                                <button class="text-red-600 hover:text-red-900">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h4 class="mt-2 text-sm font-medium text-gray-900">No hay proyectos/servicios registrados</h4>
            <p class="mt-1 text-sm text-gray-500">Comienza agregando un nuevo proyecto o servicio.</p>
            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Agregar Proyecto/Servicio
            </button>
        </div>
    @endif
</div>
