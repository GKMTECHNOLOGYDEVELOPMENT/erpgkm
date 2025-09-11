<x-layout.default>
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Encabezado con acciones -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detalles de Solicitud</h1>
                <div class="flex items-center mt-2">
                    <span class="text-gray-600 mr-3">Código: {{ $solicitud->codigoSolicitud }}</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($solicitud->estado == 'completada') bg-green-100 text-green-800 
                        @elseif($solicitud->nivelUrgencia == 1) bg-red-100 text-red-800 
                        @elseif($solicitud->nivelUrgencia == 2) bg-yellow-100 text-yellow-800 
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ ucfirst($solicitud->estado) }}
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <a href="{{ route('solicitudarticulo.index') }}" 
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all flex items-center shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
                @if($solicitud->estado != 'completada')
                <a href="{{ route('solicitudarticulo.edit', $solicitud->idSolicitud) }}" 
                   class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                @endif
            </div>
        </div>

        <!-- Tarjeta de información principal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 transition-all hover:shadow-md">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Información General
                </h2>
            </div>
            
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha de Solicitud</p>
                    <p class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                    </p>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Requerida</p>
                    <p class="font-medium text-gray-900">
                        @if($solicitud->fecharequerida)
                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                        @else
                            <span class="text-gray-400">No especificada</span>
                        @endif
                    </p>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Días Restantes</p>
                    <p class="font-medium text-lg
                        @if($solicitud->diasrestantes <= 2) text-red-600 
                        @elseif($solicitud->diasrestantes <= 5) text-amber-500 
                        @else text-green-600 @endif">
                        {{ $solicitud->diasrestantes ?? 0 }} días
                    </p>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nivel de Urgencia</p>
                    <div class="flex items-center">
                        @switch($solicitud->nivelUrgencia)
                            @case(1)
                                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                <span class="font-medium text-gray-900">Alta</span>
                                @break
                            @case(2)
                                <span class="w-3 h-3 rounded-full bg-amber-400 mr-2"></span>
                                <span class="font-medium text-gray-900">Media</span>
                                @break
                            @case(3)
                                <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                                <span class="font-medium text-gray-900">Baja</span>
                                @break
                            @default
                                <span class="font-medium text-gray-400">No especificado</span>
                        @endswitch
                    </div>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Solicitante</p>
                    <p class="font-medium text-gray-900 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $solicitud->solicitante->Nombre ?? 'No especificado' }}
                    </p>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Encargado</p>
                    <p class="font-medium text-gray-900 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $solicitud->encargado->Nombre ?? 'No asignado' }}
                    </p>
                </div>
                
                @if($solicitud->comentario)
                <div class="md:col-span-2 lg:col-span-3 space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Comentario</p>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-gray-700 whitespace-pre-line">{{ $solicitud->comentario }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Tarjeta de artículos solicitados -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 transition-all hover:shadow-md">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Artículos Solicitados
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Artículo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descripción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($solicitud->articulos as $articulo)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $articulo->codigo_barras ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $articulo->nombre ?? 'No especificado' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $articulo->pivot->cantidad }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $articulo->pivot->descripcion }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="mt-2">No hay artículos registrados para esta solicitud</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección de acciones -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Acciones
                </h2>
            </div>
            <div class="px-6 py-4 flex flex-wrap justify-end gap-3">
                @if($solicitud->estado != 'completada')
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Marcar como Completada
                    </button>
                </form>
                @endif
                
                <form action="{{ route('solicitudarticulo.destroy', $solicitud->idSolicitud) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary" onclick="return confirm('¿Estás seguro de eliminar esta solicitud?')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout.default>