<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detalles de Solicitud</h1>
                <p class="text-gray-600">Código: {{ $solicitud->codigoSolicitud }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('solicitudarticulo.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
                @if($solicitud->estado != 'completada')
                <a href="{{ route('solicitudarticulo.edit', $solicitud->idSolicitud) }}" 
                   class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                @endif
            </div>
        </div>

        <!-- Tarjeta de información principal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Información General</h2>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($solicitud->estado == 'completada') bg-green-100 text-green-800 
                        @elseif($solicitud->nivelUrgencia == 1) bg-red-100 text-red-800 
                        @elseif($solicitud->nivelUrgencia == 2) bg-yellow-100 text-yellow-800 
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ ucfirst($solicitud->estado) }}
                    </span>
                </div>
            </div>
            
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Fecha de Solicitud</p>
                    <p class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 font-medium">Fecha Requerida</p>
                    <p class="font-medium text-gray-800">
                        @if($solicitud->fecharequerida)
                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                        @else
                            No especificada
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 font-medium">Días Restantes</p>
                    <p class="font-medium 
                        @if($solicitud->diasrestantes <= 2) text-red-500 
                        @elseif($solicitud->diasrestantes <= 5) text-yellow-500 
                        @else text-green-500 @endif">
                        {{ $solicitud->diasrestantes ?? 0 }} días
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 font-medium">Nivel de Urgencia</p>
                    <p class="font-medium text-gray-800">
                        @switch($solicitud->nivelUrgencia)
                            @case(1)
                                Alta
                                @break
                            @case(2)
                                Media
                                @break
                            @case(3)
                                Baja
                                @break
                            @default
                                No especificado
                        @endswitch
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 font-medium">Solicitante</p>
                    <p class="font-medium text-gray-800">{{ $solicitud->solicitante->Nombre ?? 'No especificado' }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 font-medium">Encargado</p>
                    <p class="font-medium text-gray-800">{{ $solicitud->encargado->Nombre ?? 'No asignado' }}</p>
                </div>
                
                @if($solicitud->comentario)
                <div class="md:col-span-2 lg:col-span-3">
                    <p class="text-sm text-gray-500 font-medium">Comentario</p>
                    <p class="text-gray-700 whitespace-pre-line">{{ $solicitud->comentario }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Tarjeta de artículos solicitados -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Artículos Solicitados</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($solicitud->articulos as $articulo)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $articulo->codigo_barras ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $articulo->nombre ?? 'No especificado' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $articulo->pivot->cantidad }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $articulo->pivot->descripcion }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No hay artículos registrados para esta solicitud</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección de acciones adicionales (opcional) -->
        <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Acciones</h2>
            </div>
            <div class="px-6 py-4 flex justify-end space-x-4">
                @if($solicitud->estado != 'completada')
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Marcar como Completada
                    </button>
                </form>
                @endif
                
                <form action="{{ route('solicitudarticulo.destroy', $solicitud->idSolicitud) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition flex items-center" onclick="return confirm('¿Estás seguro de eliminar esta solicitud?')">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout.default>