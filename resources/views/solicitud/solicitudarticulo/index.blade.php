<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <!-- Debug temporal -->
        <!-- @if(env('APP_DEBUG'))
        <div class="bg-blue-50 p-4 mb-6 rounded-lg border border-blue-200">
            <p class="font-bold text-blue-800">Debug Info:</p>
            <p class="text-sm text-blue-700">Total solicitudes: {{ $solicitudes->total() }}</p>
            <p class="text-sm text-blue-700">Filtros activos: {{ json_encode(request()->all()) }}</p>
        </div>
        @endif -->

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Solicitudes de Artículos</h1>
            <a href="{{ route('solicitudarticulo.create') }}" 
               class="flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Solicitud
            </a>
        </div>

        <!-- Filtros y búsqueda FUNCIONALES -->
        <form method="GET" action="{{ route('solicitudarticulo.index') }}" class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-wrap gap-2">
    <button 
        type="submit"
        name="estado"
        value=""
        class="px-4 py-2 {{ empty(request('estado')) && empty(request('urgencia')) ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600 transition">
        Todas
    </button>
    <button 
        type="submit"
        name="estado"
        value="pendiente"
        class="px-4 py-2 {{ request('estado') == 'pendiente' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600 transition">
        Pendientes
    </button>
    <button 
        type="submit"
        name="estado"
        value="completada"
        class="px-4 py-2 {{ request('estado') == 'completada' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600 transition">
        Completadas
    </button>

    <!-- Filtros por urgencia -->
    <button 
        type="submit"
        name="urgencia"
        value="1"
        class="px-4 py-2 {{ request('urgencia') == '1' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-green-600 transition">
        Baja
    </button>
    <button 
        type="submit"
        name="urgencia"
        value="2"
        class="px-4 py-2 {{ request('urgencia') == '2' ? 'bg-green -500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-green -600 transition">
        Media
    </button>
    <button 
        type="submit"
        name="urgencia"
        value="3"
        class="px-4 py-2 {{ request('urgencia') == '3' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-red-600 transition">
        Alta
    </button>
</div>
                <div class="relative w-full md:w-64">
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Buscar solicitud..." 
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </form>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($solicitudes as $solicitud)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 
                    @if($solicitud->estado == 'pendiente') border-green-500 
                    @elseif($solicitud->nivelUrgencia == 1) border-red-500 
                    @elseif($solicitud->nivelUrgencia == 2) border-yellow-500 
                    @else border-blue-500 @endif transition transform hover:scale-[1.02] hover:shadow-lg">
                    
                    <!-- Header de la Card -->
                    <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 flex items-center">
                                @if($solicitud->nivelUrgencia == 3)
                                    <svg class="w-5 h-5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                @endif
                                {{ $solicitud->codigoSolicitud ?? 'Sin código' }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-medium">Solicitante:</span> {{ $solicitud->nombre_solicitante ?? 'No especificado' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($solicitud->estado == 'completada') bg-green-100 text-green-800 
                            @elseif($solicitud->nivelUrgencia == 1) bg-red-100 text-red-800 
                            @elseif($solicitud->nivelUrgencia == 2) bg-yellow-100 text-yellow-800 
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                        </span>
                    </div>
                    
                    <!-- Contenido de la Card -->
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Encargado</p>
                            <p class="font-medium text-gray-800">{{ $solicitud->nombre_encargado ?? 'No asignado' }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Fecha Requerida</p>
                            <p class="font-medium text-gray-800">
                                @if($solicitud->fecharequerida)
                                    {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                                @else
                                    No especificada
                                @endif
                            </p>
                        </div>
                        
@php
    $fechaSolicitud = $solicitud->fechasolicitud ? \Carbon\Carbon::parse($solicitud->fechasolicitud) : now();
    $fechaRequerida = $solicitud->fecharequerida ? \Carbon\Carbon::parse($solicitud->fecharequerida) : now();

    $totalDias = intval($fechaSolicitud->diffInDays($fechaRequerida, false));
    $diasRestantes = intval(now()->diffInDays($fechaRequerida, false));

    $progreso = 0;
    if ($totalDias > 0) {
        $progreso = intval(round(100 - ($diasRestantes / max($totalDias, 1) * 100)));
    } elseif ($diasRestantes <= 0) {
        $progreso = 100;
    }

    $progreso = max(0, min(100, $progreso));
@endphp



<div class="mb-4">
    <div class="flex justify-between items-center mb-1">
        <p class="text-sm text-gray-500 font-medium">Días Restantes</p>
        <p class="text-xs font-semibold 
            @if($diasRestantes <= 0) text-gray-500
            @elseif($diasRestantes <= 2) text-red-500 
            @elseif($diasRestantes <= 5) text-red-500 
            @else text-green-500 
            @endif">
            {{ $diasRestantes > 0 ? $diasRestantes . ' días' : 'Vencida' }}
        </p>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2">
        <div class="h-2 rounded-full transition-all duration-500 ease-in-out
            @if($diasRestantes <= 0) bg-gray-400 
            @elseif($diasRestantes <= 2) bg-red-500 
            @elseif($diasRestantes <= 5) bg-red-500 
            @else bg-green-500 
            @endif" 
            style="width: {{ $progreso }}%;">
        </div>
    </div>
</div>




                        
        <!-- Nivel de urgencia -->
        <div class="mb-4">
            <p class="text-sm text-gray-500 font-medium">Nivel de Urgencia</p>
            <p class="font-medium 
                @if($solicitud->nivelUrgencia == 3) text-red-600
                @elseif($solicitud->nivelUrgencia == 2) text-yellow-600
                @else text-green-600
                @endif">
                @switch($solicitud->nivelUrgencia)
                    @case(1)
                        Baja
                        @break
                    @case(2)
                        Media
                        @break
                    @case(3)
                        Alta
                        @break
                    @default
                        No especificado
                @endswitch
            </p>
        </div>
                        
                        @if(!empty($solicitud->comentario))
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Comentario</p>
                            <p class="text-sm text-gray-700">{{ Str::limit($solicitud->comentario, 100) }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Footer de la Card -->
                    <div class="px-6 py-3 bg-gray-50 border-t flex justify-end space-x-2">
                        <a href="{{ route('solicitudarticulo.show', $solicitud->idSolicitud) }}" 
                           class="flex items-center px-3 py-1.5 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Ver
                        </a>

                         <a href="{{ route('solicitudarticulo.opciones', $solicitud->idSolicitud) }}" 
                           class="flex items-center px-3 py-1.5 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Opciones
                        </a>
                        @if(($solicitud->estado ?? '') != 'completada')
                        <a href="{{ route('solicitudarticulo.edit', $solicitud->idSolicitud) }}" 
                           class="flex items-center px-3 py-1.5 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No hay solicitudes registradas</h3>
                    <p class="mt-1 text-gray-500 mb-4">Parece que no hay ninguna solicitud en el sistema.</p>
                    <a href="{{ route('solicitudarticulo.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear primera solicitud
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Paginación con parámetros de filtro -->
        @if($solicitudes->hasPages())
        <div class="mt-8">
            {{ $solicitudes->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- Estilos adicionales para la paginación -->
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }
        .page-item {
            margin: 0 4px;
        }
        .page-link {
            display: block;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #4b5563;
            text-decoration: none;
        }
        .page-item.active .page-link {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .page-link:hover:not(.active) {
            background-color: #f3f4f6;
        }
    </style>

</x-layout.default>