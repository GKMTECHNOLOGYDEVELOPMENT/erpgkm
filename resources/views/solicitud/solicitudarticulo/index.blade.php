<x-layout.default title="Solicitud Articulos - ERP Solutions Force">
    <div class="container mx-auto px-4 py-8">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Solicitudes</h1>

            @if(\App\Helpers\PermisoHelper::tienePermiso('NUEVA SOLICITUD ARTICULO'))
            <button id="openModalBtn"
                class="flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Solicitud
            </button>
            @endif

        </div>

        <!-- Modal para seleccionar tipo de solicitud -->
        <div id="solicitudModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <!-- Header del Modal -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800">Crear Nueva Solicitud</h3>
                    <p class="text-sm text-gray-600 mt-1">Selecciona el tipo de solicitud que deseas crear</p>
                </div>
                
                <!-- Opciones del Modal -->
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">

                        @if(\App\Helpers\PermisoHelper::tienePermiso('CREAR SOLICITUD ARTICULO'))
                        <!-- Opción para solicitud de artículo -->
                        <a href="{{ route('solicitudarticulo.create') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Solicitud de Artículo</h4>
                                <p class="text-sm text-gray-600 mt-1">Crear una solicitud para un artículo general</p>
                            </div>
                        </a>
                        @endif

                        @if(\App\Helpers\PermisoHelper::tienePermiso('CREAR SOLICITUD REPUESTO'))
                        <!-- Opción para solicitud de repuesto -->
                        <a href="{{ route('solicitudrepuesto.create') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Solicitud de Repuesto</h4>
                                <p class="text-sm text-gray-600 mt-1">Crear una solicitud para un repuesto específico</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
                
                <!-- Footer del Modal -->
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end">
                    <button id="closeModalBtn" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda - SOLUCIÓN SIMPLIFICADA -->
        <form method="GET" action="{{ route('solicitudarticulo.index') }}" class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <!-- Inputs hidden para mantener todos los filtros -->
            <input type="hidden" name="tipo" value="{{ request('tipo') }}">
            <input type="hidden" name="estado" value="{{ request('estado') }}">
            <input type="hidden" name="urgencia" value="{{ request('urgencia') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-wrap gap-2">
                    <!-- Filtro por tipo de solicitud -->
                    <button type="submit" name="tipo" value=""
                        class="px-4 py-2 {{ empty(request('tipo')) ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600 transition">
                        Todas
                    </button>
                    <button type="submit" name="tipo" value="solicitud_articulo"
                        class="px-4 py-2 {{ request('tipo') == 'solicitud_articulo' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600 transition">
                        Artículos
                    </button>
                    <button type="submit" name="tipo" value="solicitud_repuesto"
                        class="px-4 py-2 {{ request('tipo') == 'solicitud_repuesto' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600 transition">
                        Repuestos
                    </button>

                    <!-- Filtro por estado -->
                    <button type="submit" name="estado" value=""
                        class="px-4 py-2 {{ empty(request('estado')) ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-purple-600 transition">
                        Todos
                    </button>
                    <button type="submit" name="estado" value="pendiente"
                        class="px-4 py-2 {{ request('estado') == 'pendiente' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-purple-600 transition">
                        Pendientes
                    </button>
                    <button type="submit" name="estado" value="aprobada"
                        class="px-4 py-2 {{ request('estado') == 'aprobada' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-purple-600 transition">
                        Aprobadas
                    </button>
                    <button type="submit" name="estado" value="rechazada"
                        class="px-4 py-2 {{ request('estado') == 'rechazada' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-purple-600 transition">
                        Rechazadas
                    </button>

                    <!-- Filtros por urgencia -->
                    <button type="submit" name="urgencia" value=""
                        class="px-4 py-2 {{ empty(request('urgencia')) ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-orange-600 transition">
                        Todas
                    </button>
                    <button type="submit" name="urgencia" value="baja"
                        class="px-4 py-2 {{ request('urgencia') == 'baja' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-green-600 transition">
                        Baja
                    </button>
                    <button type="submit" name="urgencia" value="media"
                        class="px-4 py-2 {{ request('urgencia') == 'media' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-yellow-600 transition">
                        Media
                    </button>
                    <button type="submit" name="urgencia" value="alta"
                        class="px-4 py-2 {{ request('urgencia') == 'alta' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-red-600 transition">
                        Alta
                    </button>
                </div>
                
                <!-- Búsqueda -->
                <div class="flex gap-2">
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" placeholder="Buscar por código..."
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Buscar
                    </button>
                    @if(request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                    <a href="{{ route('solicitudarticulo.index') }}" 
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Limpiar
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Mostrar filtros activos -->
            @if(request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="font-medium">Filtros activos:</span>
                    <div class="flex flex-wrap gap-2">
                        @if(request('tipo'))
                            <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                Tipo: {{ request('tipo') == 'solicitud_articulo' ? 'Artículos' : 'Repuestos' }}
                                <a href="{{ request()->fullUrlWithoutQuery('tipo') }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                            </span>
                        @endif
                        @if(request('estado'))
                            <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                Estado: {{ ucfirst(request('estado')) }}
                                <a href="{{ request()->fullUrlWithoutQuery('estado') }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                            </span>
                        @endif
                        @if(request('urgencia'))
                            <span class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">
                                Urgencia: {{ ucfirst(request('urgencia')) }}
                                <a href="{{ request()->fullUrlWithoutQuery('urgencia') }}" class="ml-1 text-orange-600 hover:text-orange-800">×</a>
                            </span>
                        @endif
                        @if(request('search'))
                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                Búsqueda: "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithoutQuery('search') }}" class="ml-1 text-gray-600 hover:text-gray-800">×</a>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </form>

        <!-- Resto del código permanece igual -->
        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($solicitudes as $solicitud)
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 
                    @if ($solicitud->tipoorden == 'solicitud_articulo') border-blue-500
                    @else border-green-500 @endif
                    transition transform hover:scale-[1.02] hover:shadow-lg">

                    <!-- Header de la Card -->
                    <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-start">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <!-- Badge de tipo de solicitud -->
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if ($solicitud->tipoorden == 'solicitud_articulo') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if ($solicitud->tipoorden == 'solicitud_articulo')
                                        📦 Artículo
                                    @else
                                        🔧 Repuesto
                                    @endif
                                </span>
                                
                                <!-- Badge de urgencia -->
                                @if ($solicitud->niveldeurgencia == 'alta')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        🔴 Alta
                                    </span>
                                @elseif($solicitud->niveldeurgencia == 'media')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        🟡 Media
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="font-bold text-lg text-gray-800">
                                {{ $solicitud->codigo ?? 'Sin código' }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-medium">Solicitante:</span>
                                {{ $solicitud->nombre_solicitante ?? 'No especificado' }}
                            </p>
                        </div>
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if ($solicitud->estado == 'aprobada') bg-green-100 text-green-800 
                            @elseif($solicitud->estado == 'rechazada') bg-red-100 text-red-800 
                            @elseif($solicitud->estado == 'pendiente') bg-yellow-100 text-yellow-800 
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                        </span>
                    </div>

                    <!-- Contenido de la Card -->
                    <div class="px-6 py-4">
                        <!-- Información de Productos -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">
                                @if ($solicitud->tipoorden == 'solicitud_articulo')
                                    Artículos
                                @else
                                    Repuestos
                                @endif
                            </p>
                            <p class="font-medium text-gray-800">
                                {{ $solicitud->total_productos ?? 0 }} productos 
                                ({{ $solicitud->totalcantidadproductos ?? 0 }} unidades)
                            </p>
                        </div>

                        <!-- Código de Cotización -->
                        @if($solicitud->codigo_cotizacion ?? false)
                        <div class="mb-3">
                            <p class="text-sm text-gray-500 font-medium">Cotización</p>
                            <p class="font-medium text-purple-600">
                                📋 {{ $solicitud->codigo_cotizacion }}
                            </p>
                        </div>
                        @endif

                        <!-- Tipo de Servicio -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Tipo de Servicio</p>
                            <p class="font-medium text-gray-800">
                                @switch($solicitud->tiposervicio)
                                    @case('solicitud_articulo')
                                        📦 Solicitud de Artículo
                                    @break
                                    @case('solicitud_repuesto')
                                        🔧 Solicitud de Repuesto
                                    @break
                                    @case('mantenimiento')
                                        🛠️ Mantenimiento
                                    @break
                                    @case('reparacion')
                                        🔧 Reparación
                                    @break
                                    @case('instalacion')
                                        ⚡ Instalación
                                    @break
                                    @case('garantia')
                                        📋 Garantía
                                    @break
                                    @default
                                        {{ $solicitud->tiposervicio ?? 'No especificado' }}
                                @endswitch
                            </p>
                        </div>

                        <!-- Fecha de Creación -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Fecha de Creación</p>
                            <p class="font-medium text-gray-800">
                                @if ($solicitud->fechacreacion)
                                    {{ \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d M Y, h:i A') }}
                                @else
                                    No especificada
                                @endif
                            </p>
                        </div>

                        <!-- Fecha Requerida -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Fecha Requerida</p>
                            <p class="font-medium text-gray-800">
                                @if ($solicitud->fecharequerida)
                                    {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                                @else
                                    No especificada
                                @endif
                            </p>
                        </div>

                        @php
                            $fechaCreacion = $solicitud->fechacreacion
                                ? \Carbon\Carbon::parse($solicitud->fechacreacion)
                                : now();
                            $fechaRequerida = $solicitud->fecharequerida
                                ? \Carbon\Carbon::parse($solicitud->fecharequerida)
                                : now()->addDays(7);

                            $diasRestantes = intval(now()->diffInDays($fechaRequerida, false));
                            
                            $totalDias = intval($fechaCreacion->diffInDays($fechaRequerida, false));
                            $diasTranscurridos = intval($fechaCreacion->diffInDays(now(), false));
                            
                            $progreso = 0;
                            if ($totalDias > 0) {
                                $progreso = intval(round(($diasTranscurridos / $totalDias) * 100));
                            }
                            
                            $progreso = max(0, min(100, $progreso));
                        @endphp

                        <!-- Progreso de Tiempo -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-sm text-gray-500 font-medium">Tiempo Restante</p>
                                <p
                                    class="text-xs font-semibold 
                                    @if ($diasRestantes <= 0) text-red-500
                                    @elseif($diasRestantes <= 2) text-red-500 
                                    @elseif($diasRestantes <= 5) text-yellow-500 
                                    @else text-green-500 @endif">
                                    @if ($diasRestantes > 0)
                                        {{ $diasRestantes }} día{{ $diasRestantes != 1 ? 's' : '' }}
                                    @else
                                        Vencida
                                    @endif
                                </p>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 ease-in-out
                                    @if ($diasRestantes <= 0) bg-red-500 
                                    @elseif($diasRestantes <= 2) bg-red-500 
                                    @elseif($diasRestantes <= 5) bg-yellow-500 
                                    @else bg-green-500 @endif"
                                    style="width: {{ $progreso }}%;">
                                </div>
                            </div>
                        </div>

                        @if (!empty($solicitud->observaciones))
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 font-medium">Observaciones</p>
                                <p class="text-sm text-gray-700">{{ Str::limit($solicitud->observaciones, 100) }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer de la Card -->
                    <div class="px-6 py-3 bg-gray-50 border-t flex justify-end space-x-2">
                        <!-- Botones para Artículos (AZUL) -->
                        @if ($solicitud->tipoorden == 'solicitud_articulo')
                            <!-- Botón Ver -->
                            
                            @if(\App\Helpers\PermisoHelper::tienePermiso('VER SOLICITUD ARTICULO DETALLE'))
                            <a href="{{ route('solicitudarticulo.show', $solicitud->idsolicitudesordenes) }}"
                                class="flex items-center px-3 py-1.5 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Ver
                            </a>
                            @endif

                             @if(\App\Helpers\PermisoHelper::tienePermiso('OPCIONES SOLICITUD ARTICULO'))
                            <!-- Botón Opciones -->
                            <a href="{{ route('solicitudarticulo.opciones', $solicitud->idsolicitudesordenes) }}"
                                class="flex items-center px-3 py-1.5 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                                Opciones
                            </a>
                            @endif
                            @if(\App\Helpers\PermisoHelper::tienePermiso('GESTIONAR SOLICITUD ARTICULO'))
                            <!-- NUEVO BOTÓN GESTIONAR -->
        <a href="{{ route('solicitudarticulo.gestionar', $solicitud->idsolicitudesordenes) }}"
            class="flex items-center px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Gestionar
        </a>
        @endif

                            @if(\App\Helpers\PermisoHelper::tienePermiso('EDITAR SOLICITUD ARTICULO'))
                            <!-- Botón Editar - solo para pendientes -->
                            @if ($solicitud->estado == 'pendiente')
                                <a href="{{ route('solicitudarticulo.edit', $solicitud->idsolicitudesordenes) }}"
                                    class="flex items-center px-3 py-1.5 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar
                                </a>
                            @endif

                            @endif

                        <!-- Botones para Repuestos (VERDE) -->
                        @else

                         @if(\App\Helpers\PermisoHelper::tienePermiso('VER SOLICITUD REPUESTO'))
                            <!-- Botón Ver -->
                            <a href="{{ route('solicitudrepuesto.show', $solicitud->idsolicitudesordenes) }}"
                                class="flex items-center px-3 py-1.5 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Ver
                            </a>
                            @endif
                            @if(\App\Helpers\PermisoHelper::tienePermiso('VER OPCIONES REPUESTO'))
                            <!-- Botón Opciones -->
                            <a href="{{ route('solicitudrepuesto.opciones', $solicitud->idsolicitudesordenes) }}"
                                class="flex items-center px-3 py-1.5 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                                Opciones
                            </a>
                            @endif

                            @if(\App\Helpers\PermisoHelper::tienePermiso('GESTIONAR OPCIONES REPUESTO'))
                             <!-- NUEVO BOTÓN GESTIONAR -->
        <a href="{{ route('solicitudrepuesto.gestionar', $solicitud->idsolicitudesordenes) }}"
            class="flex items-center px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Gestionar
        </a>
                            @endif

                            <!-- Botón Editar - solo para pendientes -->
                            @if ($solicitud->estado == 'pendiente')
                                <a href="{{ route('solicitudrepuesto.edit', $solicitud->idsolicitudesordenes) }}"
                                    class="flex items-center px-3 py-1.5 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">
                        @if(request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                            No se encontraron solicitudes con los filtros aplicados
                        @else
                            No hay solicitudes registradas
                        @endif
                    </h3>
                    <p class="mt-1 text-gray-500 mb-4">
                        @if(request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                            Intenta ajustar los filtros de búsqueda
                        @else
                            Parece que no hay ninguna solicitud en el sistema.
                        @endif
                    </p>
                    <button id="openModalBtnEmpty"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear primera solicitud
                    </button>
                    @if(request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                    <a href="{{ route('solicitudarticulo.index') }}" 
                        class="ml-2 inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Limpiar filtros
                    </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Paginación con parámetros de filtro -->
        @if ($solicitudes->hasPages())
            <div class="mt-8">
                {{ $solicitudes->appends(request()->query())->links() }}
            </div>
        @endif

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
    </div>

    <!-- JavaScript para manejar el modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('solicitudModal');
            const openModalBtn = document.getElementById('openModalBtn');
            const openModalBtnEmpty = document.getElementById('openModalBtnEmpty');
            const closeModalBtn = document.getElementById('closeModalBtn');
            
            // Abrir modal desde el botón principal
            if (openModalBtn) {
                openModalBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                });
            }
            
            // Abrir modal desde el botón cuando no hay solicitudes
            if (openModalBtnEmpty) {
                openModalBtnEmpty.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                });
            }
            
            // Cerrar modal con el botón cancelar
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });
            }
            
            // Cerrar modal al hacer clic fuera del contenido
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }
            
            // Cerrar modal con la tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</x-layout.default>

