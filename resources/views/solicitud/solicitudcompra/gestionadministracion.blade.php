<x-layout.default>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-wrapper {
            display: block !important;
            /* Cambiar inline-block por block */
            width: 100% !important;
            /* Asegurar que ocupe todo el ancho */
        }
    </style>
    <div class="mx-auto w-full px-4 py-6">
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li> <a href="{{ route('solicitudcompra.index') }}" class="text-primary hover:underline">
                        Solicitudes Compra
                    </a></li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Gestión Administración</span>
                </li>
            </ul>
        </div>
        <!-- Header con icono y estilo elegante -->
        <div class="mb-6 p-5 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-4">
                <div
                    class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Solicitudes de Compra</h1>
                    <div class="flex items-center space-x-3 mt-1">
                        <p class="text-gray-600">Administración - Revisión de solicitudes</p>
                        <span
                            class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Activo
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros con AJAX - VERSIÓN MEJORADA Y LIMPIA -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
            <!-- Título de la sección con botón de limpiar -->
            <div
                class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 pb-4 border-b border-gray-100 gap-3">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800">Filtrar Solicitudes</h3>
                    <p class="text-sm text-gray-500 mt-1">Selecciona los criterios de búsqueda</p>
                </div>

                <!-- Botón de limpiar en el header -->
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="limpiarFiltros()"
                        class="inline-flex items-center px-3.5 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg transition-all duration-200 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1">
                        <i class="fas fa-redo mr-1.5 text-sm"></i>
                        Limpiar Filtros
                    </button>
                </div>
            </div>

            <!-- Contenedor de filtros - Ajustado para inputs más anchos -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Estado -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-tag mr-1.5 text-gray-400"></i>
                        Estado
                    </label>
                    <select id="filtroEstado" name="estado"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 hover:bg-white focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-300 transition-all duration-200 filtro-input">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                            Pendiente
                        </option>
                        <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>
                            Aprobada
                        </option>
                        <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>
                            Rechazada
                        </option>
                        <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>
                            En Proceso
                        </option>
                        <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>
                            Completada
                        </option>
                        <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                            Cancelada
                        </option>
                        <option value="presupuesto_aprobado"
                            {{ request('estado') == 'presupuesto_aprobado' ? 'selected' : '' }}>
                            Presupuesto Aprobado
                        </option>
                        <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>
                            Pagado
                        </option>
                        <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>
                            Finalizado
                        </option>
                    </select>
                </div>

                <!-- Prioridad -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-exclamation-circle mr-1.5 text-gray-400"></i>
                        Prioridad
                    </label>
                    <select id="filtroPrioridad" name="prioridad"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 hover:bg-white focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-300 transition-all duration-200 filtro-input">
                        <option value="">Todas</option>
                        <option value="1" {{ request('prioridad') == '1' ? 'selected' : '' }}>
                            Alta
                        </option>
                        <option value="2" {{ request('prioridad') == '2' ? 'selected' : '' }}>
                            Media
                        </option>
                        <option value="3" {{ request('prioridad') == '3' ? 'selected' : '' }}>
                            Baja
                        </option>
                    </select>
                </div>

                <!-- Fecha desde - MÁS ANCHO -->
                <div class="space-y-1.5 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                        Fecha desde
                    </label>
                    <div class="relative">
                        <input type="text" id="filtroFechaDesde" name="fecha_desde" placeholder="Seleccionar fecha"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 hover:bg-white focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-300 transition-all duration-200 filtro-input cursor-pointer text-sm"
                            value="{{ request('fecha_desde') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Fecha hasta - MÁS ANCHO -->
                <div class="space-y-1.5 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar-check mr-1.5 text-gray-400"></i>
                        Fecha hasta
                    </label>
                    <div class="relative">
                        <input type="text" id="filtroFechaHasta" name="fecha_hasta" placeholder="Seleccionar fecha"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 hover:bg-white focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-300 transition-all duration-200 filtro-input cursor-pointer text-sm"
                            value="{{ request('fecha_hasta') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Cards Mejorado con Scroll para Productos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="solicitudes-container">
            @if (isset($solicitudes) && count($solicitudes) > 0)
                @foreach ($solicitudes as $solicitud)
                    <!-- Card Container -->
                    <div
                        class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-xl hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Card Header -->
                        <div class="px-5 pt-5">
                            <div class="flex justify-between items-start mb-4">
                                <!-- Código de solicitud -->
                                <div class="flex-1">
                                    <div
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-gray-50 border border-gray-200 mb-2">
                                        <svg class="w-3 h-3 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                        <span
                                            class="text-xs font-semibold text-gray-700">{{ $solicitud->codigo_solicitud }}</span>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-lg line-clamp-1">
                                        {{ $solicitud->proyecto_asociado ?? 'Sin proyecto asociado' }}</h3>
                                </div>

                                <!-- Estado con icono -->
                                <div class="flex flex-col items-end">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm
                @if ($solicitud->estado == 'pendiente') bg-warning/10 text-warning border border-warning/20
                @elseif($solicitud->estado == 'en_proceso') bg-primary/10 text-primary-dark border border-primary/20
                @elseif($solicitud->estado == 'completada') bg-secondary-light text-secondary border border-secondary
                @elseif($solicitud->estado == 'presupuesto_aprobado') bg-yellow-100 text-yellow-800 border border-yellow-300
                @elseif($solicitud->estado == 'pagado') bg-blue-100 text-blue-800 border border-blue-300
                @elseif($solicitud->estado == 'finalizado') bg-gray-100 text-gray-800 border border-gray-400
                @elseif($solicitud->estado == 'cancelada') bg-gray-100 text-gray-800 border border-gray-400
                @elseif($solicitud->estado == 'rechazada') bg-danger/10 text-danger-dark border border-danger/20
                @elseif($solicitud->estado == 'aprobada') bg-green-100 text-green-800 border border-green-300
                @else bg-gray-100 text-gray-700 border border-gray-300 @endif">

                                        @switch($solicitud->estado)
                                            @case('pendiente')
                                                <svg class="w-3 h-3 mr-1 text-warning" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('en_proceso')
                                                <svg class="w-3 h-3 mr-1 text-primary" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('completada')
                                                <svg class="w-3 h-3 mr-1 text-secondary" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('presupuesto_aprobado')
                                                <svg class="w-3 h-3 mr-1 text-yellow-600" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2h6a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a1 1 0 11-2 0 1 1 0 012 0zm11-1a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('pagado')
                                                <svg class="w-3 h-3 mr-1 text-blue-600" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2h6a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a1 1 0 11-2 0 1 1 0 012 0zm11-1a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('finalizado')
                                                <svg class="w-3 h-3 mr-1 text-gray-600" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('cancelada')
                                                <svg class="w-3 h-3 mr-1 text-gray-600" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('rechazada')
                                                <svg class="w-3 h-3 mr-1 text-danger" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('aprobada')
                                                <svg class="w-3 h-3 mr-1 text-green-600" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @default
                                                <svg class="w-3 h-3 mr-1 text-gray-500" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                        @endswitch

                                        {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="px-5 pb-5">
                            <!-- Solicitante y Fecha -->
                            <div class="flex items-center mb-4 p-3 bg-white rounded-lg border border-gray-200">
                                <div class="relative">
                                    <!-- Avatar compacto -->
                                    <div
                                        class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-white text-xs font-bold">
                                            {{ substr($solicitud->solicitante_compra ?? $solicitud->solicitante, 0, 1) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="ml-3 flex-1 min-w-0">
                                    <!-- Nombre y fecha en línea -->
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-gray-900 truncate pr-2">
                                            {{ $solicitud->solicitante_compra ?? $solicitud->solicitante }}
                                        </p>
                                    </div>

                                    <!-- Fecha compacta -->
                                    <div class="flex items-center mt-1">
                                        <svg class="w-3 h-3 text-gray-400 mr-1 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-xs text-gray-500 truncate">
                                            {{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Detalles Principales Compactos -->

                            <div class="grid grid-cols-2 gap-2">
                                <!-- Fecha Requerida -->
                                <div
                                    class="flex items-center p-2 rounded-lg bg-blue-100 hover:bg-blue-100/50 transition-colors">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 rounded-lg mr-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-gray-500 truncate">Fecha Req.</p>
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Monto Total -->
                                <div
                                    class="flex items-center p-2 rounded-lg bg-emerald-100  hover:bg-emerald-100/50 transition-colors">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-emerald-100 rounded-lg mr-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-gray-500 truncate">Monto Total</p>
                                        <p class="text-sm font-semibold text-emerald-700 truncate">
                                            {{ $solicitud->resumen_moneda }}{{ number_format($solicitud->total, 2) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Unidades -->
                                <div
                                    class="flex items-center p-2 rounded-lg bg-gray-100 hover:bg-gray-100/50 transition-colors">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg mr-2">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-gray-500 truncate">Unidades</p>
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $solicitud->total_unidades }}</p>
                                    </div>
                                </div>

                                <!-- Monedas (condicional) -->
                                @if ($solicitud->multiple_currencies)
                                    <div
                                        class="flex items-center p-2 rounded-lg bg-violet-50/50 hover:bg-violet-100/50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-violet-100 rounded-lg mr-2">
                                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs text-gray-500 truncate">Monedas</p>
                                            <p class="text-sm font-semibold text-violet-700 truncate">
                                                {{ $solicitud->monedas_utilizadas }}</p>
                                        </div>
                                    </div>
                                @else
                                    <!-- Espaciador para mantener el grid -->
                                    <div class="p-2"></div>
                                @endif
                            </div>


                            <!-- Productos Solicitados - CON SCROLL (Mostrando 2) -->
                            <div class="mt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Productos solicitados</span>
                                    </div>
                                    @if (isset($solicitud->detalles) && count($solicitud->detalles) > 0)
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            {{ count($solicitud->detalles) }} items
                                        </span>
                                    @endif
                                </div>

                                <!-- Contenedor con scroll condicional -->
                                @if (isset($solicitud->detalles) && count($solicitud->detalles) > 0)
                                    <div class="relative">
                                        <div
                                            class="space-y-2 {{ count($solicitud->detalles) > 2 ? 'max-h-40 overflow-y-auto pr-2' : '' }}">
                                            @foreach ($solicitud->detalles as $detalle)
                                                <div
                                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-100">
                                                    <div class="flex items-center flex-1 min-w-0 mr-3">
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="w-8 h-8 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-50 rounded-md border border-blue-200">
                                                                <svg class="w-4 h-4 text-blue-600" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $detalle->descripcion_producto }}</p>
                                                            @if ($detalle->observaciones)
                                                                <p class="text-xs text-gray-500 truncate mt-1">
                                                                    {{ $detalle->observaciones }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="inline-flex flex-col items-end bg-white px-3 py-2 rounded-md border border-gray-200">
                                                            <span
                                                                class="text-sm font-bold text-gray-900">{{ $detalle->cantidad }}</span>
                                                            <span
                                                                class="text-xs text-gray-500">{{ $detalle->unidad }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Indicador de scroll (solo se muestra si hay más de 2 productos) -->
                                        @if (count($solicitud->detalles) > 2)
                                            <div
                                                class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-white to-transparent pointer-events-none rounded-b-lg">
                                            </div>
                                            <div class="text-center pt-2 mt-2 border-t border-gray-100">
                                                <span class="inline-flex items-center text-xs text-gray-500">
                                                    <svg class="w-3 h-3 mr-1 animate-bounce" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                    </svg>
                                                    {{ count($solicitud->detalles) - 2 }} productos más
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div
                                        class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-500">No hay productos registrados</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div
                            class="px-5 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-b-xl">
                            <div class="flex items-center justify-between">
                                <!-- Botón de acción principal -->
                                <button onclick="verDetalle({{ $solicitud->idSolicitudCompra }})"
                                    class="group/btn inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow">
                                    <svg class="w-4 h-4 mr-2 group-hover/btn:rotate-12 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Ver Detalles
                                </button>

                                <!-- Indicadores -->
                                <div class="flex items-center space-x-3">
                                    <!-- Prioridad -->
                                    <div class="flex flex-col items-end">
                                        <span class="text-xs text-gray-500 mb-1">Prioridad</span>
                                        <div class="flex items-center">
                                            @if ($solicitud->idPrioridad == 1)
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 bg-danger rounded-full mr-1 animate-pulse">
                                                    </div>
                                                    <span class="text-xs font-bold text-red-600">Alta</span>
                                                </div>
                                            @elseif($solicitud->idPrioridad == 2)
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 bg-warning rounded-full mr-1"></div>
                                                    <span class="text-xs font-bold text-orange-600">Media</span>
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-1"></div>
                                                    <span class="text-xs font-bold text-green-600">Baja</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Estado vacío mejorado -->
                <div class="col-span-full">
                    <div
                        class="bg-gradient-to-br from-gray-50 to-white rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center">
                        <div
                            class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-50 to-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">No hay solicitudes disponibles</h3>
                        <p class="text-gray-600 max-w-md mx-auto mb-6">Actualmente no existen solicitudes de compra
                            registradas en el sistema.</p>
                        <button
                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear primera solicitud
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Paginación -->
        @if (isset($solicitudes) && $solicitudes->hasPages())
            <div class="mt-6">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Configurar Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Variables para controlar el debounce
        let timeoutId;
        let isLoading = false;
        let currentRequest = null;

        // Variables para Flatpickr
        let flatpickrDesde = null;
        let flatpickrHasta = null;

        // Configuración de filtros con AJAX
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Flatpickr para fechas
            inicializarFlatpickr();

            // Añadir event listeners a todos los inputs de filtro
            const filtros = document.querySelectorAll('.filtro-input');
            filtros.forEach(filtro => {
                // Para selects
                if (filtro.tagName === 'SELECT') {
                    filtro.addEventListener('change', function() {
                        aplicarFiltrosConDebounce();
                    });
                }

                // Para inputs de fecha con Flatpickr
                if (filtro.id === 'filtroFechaDesde' || filtro.id === 'filtroFechaHasta') {
                    // El cambio se maneja en la configuración de Flatpickr
                }
            });

            // Función con debounce para evitar múltiples requests rápidos
            function aplicarFiltrosConDebounce() {
                clearTimeout(timeoutId);

                // Mostrar toast de cargando
                if (!isLoading) {
                    toastr.info('Aplicando filtros...', 'Cargando', {
                        timeOut: 2000
                    });
                }

                timeoutId = setTimeout(() => {
                    aplicarFiltrosAJAX();
                }, 800); // 800ms de delay
            }

            function inicializarFlatpickr() {
                // Configuración común
                const commonOptions = {
                    dateFormat: "Y-m-d",
                    locale: "es",
                    allowInput: false,
                    clickOpens: true,
                    static: true,
                    disableMobile: true,
                    // SOLO onChange
                    onChange: function(selectedDates, dateStr, instance) {
                        // Pequeño delay para asegurar que el valor se actualizó
                        setTimeout(() => {
                            if (validarFechas()) {
                                aplicarFiltrosConDebounce();
                            }
                        }, 50);
                    }
                    // QUITAR onClose
                };

                flatpickrDesde = flatpickr("#filtroFechaDesde", commonOptions);
                flatpickrHasta = flatpickr("#filtroFechaHasta", commonOptions);
            }
            // Validación de fechas - VERSIÓN SIMPLE
            function validarFechas() {
                const fechaDesde = document.getElementById('filtroFechaDesde');
                const fechaHasta = document.getElementById('filtroFechaHasta');

                // Solo validar si AMBAS fechas están seleccionadas
                if (fechaDesde && fechaHasta && fechaDesde.value && fechaHasta.value) {
                    const desde = new Date(fechaDesde.value);
                    const hasta = new Date(fechaHasta.value);

                    // Solo verificar que desde no sea mayor que hasta
                    if (desde > hasta) {
                        toastr.error('La fecha "desde" no puede ser mayor que la fecha "hasta"',
                            'Error de validación');
                        return false;
                    }
                }
                return true;
            }
        });

        // Función principal para aplicar filtros con AJAX
        function aplicarFiltrosAJAX() {
            if (isLoading) {
                if (currentRequest) {
                    currentRequest.abort();
                }
            }

            // Obtener elementos por ID
            const filtroEstado = document.getElementById('filtroEstado');
            const filtroPrioridad = document.getElementById('filtroPrioridad');
            const filtroFechaDesde = document.getElementById('filtroFechaDesde');
            const filtroFechaHasta = document.getElementById('filtroFechaHasta');

            // Verificar que los elementos existan
            if (!filtroEstado || !filtroPrioridad || !filtroFechaDesde || !filtroFechaHasta) {
                console.error('No se encontraron todos los elementos de filtro');
                toastr.error('Error en los filtros', 'Error');
                return;
            }

            // Obtener valores de los filtros
            const filtros = {
                estado: filtroEstado.value,
                prioridad: filtroPrioridad.value,
                fecha_desde: filtroFechaDesde.value,
                fecha_hasta: filtroFechaHasta.value
            };

            // Mostrar indicador de carga
            isLoading = true;
            mostrarLoading(true);

            // Crear URL con parámetros
            let url = '{{ route('solicitudcompra.gestionadministracion') }}?ajax=true';
            Object.keys(filtros).forEach(key => {
                if (filtros[key]) {
                    url += `&${key}=${encodeURIComponent(filtros[key])}`;
                }
            });

            console.log('URL de filtro:', url);

            // Hacer la petición AJAX con fetch (más moderno)
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('solicitudes-container');
                        if (container) {
                            container.innerHTML = data.html;

                            // Mostrar toast de éxito solo si hubo interacción del usuario
                            const totalFiltros = Object.values(filtros).filter(val => val).length;
                            const urlParams = new URLSearchParams(window.location.search);

                            // Si NO es la carga inicial (hay parámetros en la URL o filtros activos)
                            if (totalFiltros > 0 || urlParams.toString() !== '') {
                                if (totalFiltros > 0) {
                                    toastr.success(`Filtros aplicados. Se encontraron ${data.total} solicitudes`,
                                        'Éxito');
                                } else {
                                    toastr.info('Mostrando todas las solicitudes', 'Información');
                                }
                            }
                            // Si es carga inicial sin filtros, NO mostrar toast
                        }
                    }
                })
                .catch(error => {
                    console.error('Error en filtro AJAX:', error);
                    if (error.name !== 'AbortError') {
                        toastr.error('Error de conexión: ' + error.message, 'Error');
                    }
                })
                .finally(() => {
                    isLoading = false;
                    mostrarLoading(false);
                    currentRequest = null;
                });
        }

        // Función para limpiar filtros - VERSIÓN SIMPLE
        function limpiarFiltros() {
            // Limpiar selects
            document.getElementById('filtroEstado').value = '';
            document.getElementById('filtroPrioridad').value = '';

            // Limpiar Flatpickr (forma más simple)
            if (flatpickrDesde) flatpickrDesde.clear();
            if (flatpickrHasta) flatpickrHasta.clear();

            // Mostrar mensaje y aplicar
            toastr.info('Filtros limpiados', 'Éxito');
            aplicarFiltrosAJAX();
        }

        // Función para mostrar/ocultar loading
        function mostrarLoading(mostrar) {
            const container = document.getElementById('solicitudes-container');
            const limpiarBtn = document.querySelector('button[onclick="limpiarFiltros()"]');

            if (mostrar && container) {
                // Guardar contenido original si es la primera vez
                if (!container.dataset.originalContent) {
                    container.dataset.originalContent = container.innerHTML;
                }

                // Mostrar skeleton loader
                container.innerHTML = `
            <div class="col-span-full">
                <div class="animate-pulse">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        ${Array(6).fill().map(() => `
                                                                            <div class="bg-gray-200 rounded-xl h-96"></div>
                                                                        `).join('')}
                    </div>
                </div>
            </div>
        `;

                // Deshabilitar botón
                if (limpiarBtn) {
                    limpiarBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Filtrando...';
                    limpiarBtn.disabled = true;
                }
            } else {
                // Habilitar botón
                if (limpiarBtn) {
                    limpiarBtn.innerHTML = '<i class="fas fa-redo mr-2"></i> Limpiar Filtros';
                    limpiarBtn.disabled = false;
                }
            }
        }

        // Funciones para las cards
        function verDetalle(idSolicitud) {
            window.location.href = `/solicitudcompra/${idSolicitud}/evaluacion`;
        }

        function aprobarSolicitud(idSolicitud) {
            if (confirm('¿Estás seguro de que deseas aprobar esta solicitud?')) {
                // Lógica AJAX para aprobar
            }
        }

        function rechazarSolicitud(idSolicitud) {
            const motivo = prompt('Ingrese el motivo del rechazo:');
            if (motivo !== null && motivo.trim() !== '') {
                // Lógica AJAX para rechazar
            }
        }

        // Función para seleccionar rangos de fecha comunes
        function seleccionarRangoFechas(rango) {
            const hoy = new Date();
            let fechaDesde = new Date();
            let fechaHasta = new Date();

            switch (rango) {
                case 'hoy':
                    fechaDesde = new Date(hoy);
                    fechaHasta = new Date(hoy);
                    break;
                case 'ayer':
                    fechaDesde = new Date(hoy);
                    fechaDesde.setDate(hoy.getDate() - 1);
                    fechaHasta = new Date(fechaDesde);
                    break;
                case 'esta_semana':
                    fechaDesde = new Date(hoy);
                    fechaDesde.setDate(hoy.getDate() - hoy.getDay()); // Domingo
                    fechaHasta = new Date(hoy);
                    break;
                case 'semana_pasada':
                    fechaHasta = new Date(hoy);
                    fechaHasta.setDate(hoy.getDate() - hoy.getDay() - 1); // Sábado pasado
                    fechaDesde = new Date(fechaHasta);
                    fechaDesde.setDate(fechaDesde.getDate() - 6); // Domingo pasado
                    break;
                case 'este_mes':
                    fechaDesde = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    fechaHasta = new Date(hoy);
                    break;
                case 'mes_pasado':
                    fechaDesde = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
                    fechaHasta = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
                    break;
            }

            // Formatear fechas a YYYY-MM-DD
            const formatDate = (date) => date.toISOString().split('T')[0];

            // Establecer fechas en Flatpickr
            if (flatpickrDesde) {
                flatpickrDesde.setDate(formatDate(fechaDesde));
            }
            if (flatpickrHasta) {
                flatpickrHasta.setDate(formatDate(fechaHasta));
            }

            // Aplicar filtros
            aplicarFiltrosConDebounce();
        }
    </script>
</x-layout.default>
