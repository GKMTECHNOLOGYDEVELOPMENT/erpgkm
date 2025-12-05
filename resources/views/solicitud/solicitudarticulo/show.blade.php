<x-layout.default>
    <!-- Solo iconos sólidos (la mayoría de iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class=" mx-auto px-4 w-full">

            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline">Solicitudes</a>
                    </li>

                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Ver Solicitud Artículo</span>
                    </li>
                </ul>
            </div>
            <!-- Header Principal Mejorado -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex-1">
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                                    Orden #<span class="text-blue-600">{{ $solicitud->codigo ?? 'N/A' }}</span>
                                </h1>
                                <div class="flex flex-wrap items-center gap-2 mb-4">
                                    <span
                                        class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold border border-blue-200">
                                        {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                    </span>
                                    <span
                                        class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold border border-purple-200">
                                        Solicitud de Artículo
                                    </span>
                                    @if ($solicitud->urgencia)
                                        <span
                                            class="px-4 py-2 
                            @if ($solicitud->urgencia == 'alta') bg-red-100 text-red-800 border-red-200
                            @elseif($solicitud->urgencia == 'media') bg-yellow-100 text-yellow-800 border-yellow-200
                            @else bg-green-100 text-green-800 border-green-200 @endif
                            rounded-full text-sm font-semibold border">
                                            Urgencia: {{ ucfirst($solicitud->urgencia) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Información adicional debajo de los badges -->
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mt-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Creada:
                                            {{ $solicitud->fechacreacion ? \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d M Y, h:i A') : 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>Solicitante:
                                            {{ $solicitud->nombre_solicitante ?? 'No especificado' }}</span>
                                    </div>

                                    <!-- Área Destino -->
                                    @if ($solicitud->nombre_area_destino)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span>Área Destino: {{ $solicitud->nombre_area_destino }}</span>
                                        </div>
                                    @endif

                                    <!-- Usuario Destino -->
                                    @if ($solicitud->usuario_destino_nombre)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-purple-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span>Destinatario: {{ $solicitud->usuario_destino_nombre }}
                                                {{ $solicitud->usuario_destino_apellido }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4 lg:mt-0">
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver
                        </a>
                        @if (($solicitud->estado ?? '') != 'completada')
                            <a href="{{ route('solicitudarticulo.edit', $solicitud->idsolicitudesordenes) }}"
                                class="flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Artículos Solicitados - Mejorado con Font Awesome -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100">
                        <!-- Header con gradiente -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full shadow-lg border border-white/30">
                                    <i class="fas fa-boxes text-white text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h2 class="text-lg sm:text-xl font-bold text-white truncate">Artículos Solicitados
                                    </h2>
                                    <p class="text-white/80 text-xs sm:text-sm truncate">Lista completa de artículos
                                        requeridos</p>
                                </div>
                                <div class="hidden sm:flex items-center space-x-2">
                                    <span class="text-sm font-semibold text-white bg-white/20 px-3 py-1 rounded-full">
                                        {{ $articulos->count() }} item{{ $articulos->count() !== 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <!-- Encabezado de la tabla -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-6">
                                <div>
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Detalle de Artículos</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                        Total de unidades: <span
                                            class="font-semibold text-blue-600">{{ $articulos->sum('cantidad') }}</span>
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2 sm:space-x-4">
                                    <span class="text-xs sm:text-sm text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                                        <i class="fas fa-layer-group mr-1.5 text-blue-500"></i>
                                        {{ $articulos->count() }} artículo{{ $articulos->count() !== 1 ? 's' : '' }}
                                    </span>
                                    @if ($articulos->count() > 0)
                                        <div class="hidden sm:block w-2 h-2 bg-green-500 rounded-full animate-pulse">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tabla Responsive -->
                            <div class="overflow-x-auto rounded-xl border border-blue-100">
                                <table class="w-full min-w-[640px]">
                                    <thead class="bg-blue-50">
                                        <tr>
                                            <th
                                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-blue-600 uppercase tracking-wider">
                                                <i class="fas fa-cube mr-2"></i>Artículo
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-blue-600 uppercase tracking-wider">
                                                <i class="fas fa-barcode mr-2"></i>Código
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-blue-600 uppercase tracking-wider">
                                                <i class="fas fa-hashtag mr-2"></i>Cantidad
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-blue-600 uppercase tracking-wider">
                                                <i class="fas fa-info-circle mr-2"></i>Estado
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-blue-100">
                                        @forelse($articulos as $articulo)
                                            <tr class="hover:bg-blue-50 transition-all duration-200">
                                                <!-- Columna Artículo - Responsive -->
                                                <td class="px-4 sm:px-6 py-4">
                                                    <div class="space-y-2">
                                                        <!-- Nombre principal -->
                                                        <div class="flex items-start">
                                                            <div class="flex-shrink-0 mr-3 mt-1">
                                                                <div
                                                                    class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                                    <i class="fas fa-box text-blue-600 text-sm"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <h4
                                                                    class="text-sm sm:text-base font-semibold text-gray-900 truncate">
                                                                    {{ $articulo->nombre_articulo ?? 'Artículo no especificado' }}
                                                                </h4>

                                                                <!-- Información adicional en móvil (compacta) -->
                                                                <div class="sm:hidden mt-2 space-y-1">
                                                                    @if ($articulo->tipo_articulo)
                                                                        <div
                                                                            class="flex items-center text-xs text-gray-500">
                                                                            <i
                                                                                class="fas fa-tag mr-1.5 text-gray-400"></i>
                                                                            <span
                                                                                class="truncate">{{ $articulo->tipo_articulo }}</span>
                                                                        </div>
                                                                    @endif
                                                                    @if ($articulo->modelo || $articulo->marca)
                                                                        <div
                                                                            class="flex items-center text-xs text-gray-500">
                                                                            <i
                                                                                class="fas fa-info-circle mr-1.5 text-gray-400"></i>
                                                                            <span class="truncate">
                                                                                @if ($articulo->marca)
                                                                                    {{ $articulo->marca }}
                                                                                @endif
                                                                                @if ($articulo->modelo && $articulo->marca)
                                                                                    -
                                                                                @endif
                                                                                @if ($articulo->modelo)
                                                                                    {{ $articulo->modelo }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <!-- Información adicional en desktop -->
                                                                <div class="hidden sm:block mt-2 space-y-1">
                                                                    <div class="flex flex-wrap gap-2">
                                                                        @if ($articulo->tipo_articulo)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                                                <i class="fas fa-tag mr-1 text-xs"></i>
                                                                                {{ $articulo->tipo_articulo }}
                                                                            </span>
                                                                        @endif
                                                                        @if ($articulo->marca)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                                <i
                                                                                    class="fas fa-industry mr-1 text-xs"></i>
                                                                                {{ $articulo->marca }}
                                                                            </span>
                                                                        @endif
                                                                        @if ($articulo->modelo)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                                <i class="fas fa-cog mr-1 text-xs"></i>
                                                                                {{ $articulo->modelo }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    @if ($articulo->subcategoria)
                                                                        <p class="text-xs text-gray-500">
                                                                            <i class="fas fa-folder mr-1"></i>
                                                                            Categoría: {{ $articulo->subcategoria }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Descripción (si existe) -->
                                                        @if ($articulo->descripcion)
                                                            <div
                                                                class="mt-2 sm:mt-3 p-2 sm:p-3 bg-gray-50 rounded border border-gray-200">
                                                                <div class="flex items-start">
                                                                    <i
                                                                        class="fas fa-sticky-note text-gray-400 mt-0.5 mr-2 flex-shrink-0"></i>
                                                                    <p class="text-xs text-gray-600 flex-1">
                                                                        <span class="font-medium">Nota:</span>
                                                                        {{ $articulo->descripcion }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Columna Código - Responsive -->
                                                <td class="px-4 sm:px-6 py-4">
                                                    <div class="text-center sm:text-left">
                                                        <div
                                                            class="font-mono font-bold text-blue-600 text-sm sm:text-base break-all">
                                                            {{ $articulo->codigo_barras ?: $articulo->codigo_repuesto }}
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Columna Cantidad - Responsive -->
                                                <td class="px-4 sm:px-6 py-4">
                                                    <div class="flex items-center justify-center">
                                                        <div class="flex flex-col items-center">
                                                            <span
                                                                class="font-bold text-lg sm:text-xl bg-blue-100 text-blue-800 rounded-lg px-3 sm:px-4 py-2 border border-blue-200">
                                                                {{ $articulo->cantidad ?? 0 }}
                                                            </span>
                                                            <span
                                                                class="text-xs text-gray-500 mt-1 hidden sm:block">unidades</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Columna Estado - Responsive -->
                                                <td class="px-4 sm:px-6 py-4">
                                                    @php
                                                        $estadoConfig = [
                                                            '0' => [
                                                                'class' =>
                                                                    'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                                'text' => 'Pendiente',
                                                                'icon' => 'fas fa-clock',
                                                                'iconColor' => 'text-yellow-600',
                                                            ],
                                                            '1' => [
                                                                'class' =>
                                                                    'bg-green-100 text-green-800 border border-green-200',
                                                                'text' => 'Completado',
                                                                'icon' => 'fas fa-check-circle',
                                                                'iconColor' => 'text-green-600',
                                                            ],
                                                            '2' => [
                                                                'class' =>
                                                                    'bg-red-100 text-red-800 border border-red-200',
                                                                'text' => 'Rechazado',
                                                                'icon' => 'fas fa-times-circle',
                                                                'iconColor' => 'text-red-600',
                                                            ],
                                                        ][$articulo->estado ?? 0] ?? [
                                                            'class' =>
                                                                'bg-gray-100 text-gray-800 border border-gray-200',
                                                            'text' => 'Desconocido',
                                                            'icon' => 'fas fa-question-circle',
                                                            'iconColor' => 'text-gray-600',
                                                        ];
                                                    @endphp

                                                    <div class="flex justify-center">
                                                        <span
                                                            class="inline-flex items-center px-3 py-2 {{ $estadoConfig['class'] }} rounded-full text-xs sm:text-sm font-semibold">
                                                            <i
                                                                class="{{ $estadoConfig['icon'] }} {{ $estadoConfig['iconColor'] }} mr-2"></i>
                                                            <span
                                                                class="hidden sm:inline">{{ $estadoConfig['text'] }}</span>
                                                            <span
                                                                class="sm:hidden">{{ substr($estadoConfig['text'], 0, 3) }}</span>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div
                                                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i
                                                                class="fas fa-box-open text-gray-400 text-2xl sm:text-3xl"></i>
                                                        </div>
                                                        <p class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                                                            No hay artículos registrados
                                                        </p>
                                                        <p class="text-xs sm:text-sm text-gray-500 max-w-sm mx-auto">
                                                            No se han agregado artículos a esta solicitud
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Resumen de estadísticas -->
                            @if ($articulos->count() > 0)
                                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                                    <div class="bg-blue-50 rounded-xl p-3 sm:p-4 text-center border border-blue-200">
                                        <p
                                            class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-1 sm:mb-2">
                                            <i class="fas fa-box mr-1"></i>Total Artículos
                                        </p>
                                        <p class="text-xl sm:text-2xl font-bold text-blue-800">
                                            {{ $articulos->count() }}</p>
                                    </div>
                                    <div class="bg-green-50 rounded-xl p-3 sm:p-4 text-center border border-green-200">
                                        <p
                                            class="text-xs text-green-700 font-semibold uppercase tracking-wide mb-1 sm:mb-2">
                                            <i class="fas fa-layer-group mr-1"></i>Total Cantidad
                                        </p>
                                        <p class="text-xl sm:text-2xl font-bold text-green-800">
                                            {{ $articulos->sum('cantidad') }}</p>
                                    </div>
                                    <div
                                        class="bg-purple-50 rounded-xl p-3 sm:p-4 text-center border border-purple-200">
                                        <p
                                            class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-1 sm:mb-2">
                                            <i class="fas fa-tags mr-1"></i>Tipos Diferentes
                                        </p>
                                        <p class="text-xl sm:text-2xl font-bold text-purple-800">
                                            {{ $articulos->unique('tipo_articulo')->count() }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información de la Orden - Completamente Responsive con Font Awesome -->
                    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-gray-200">
                        <!-- Header Responsive -->
                        <div class="flex items-start sm:items-center mb-4 sm:mb-6">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 text-base sm:text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 truncate">INFORMACIÓN
                                    DE LA ORDEN</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1 truncate">Detalles completos de la
                                    solicitud</p>
                            </div>
                        </div>

                        <!-- Grid Responsive -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                            <!-- Columna Izquierda -->
                            <div class="space-y-3 sm:space-y-4">
                                <!-- Código Orden -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3 sm:p-4 border border-blue-200">
                                    <div class="flex items-center mb-1 sm:mb-2">
                                        <i
                                            class="fas fa-hashtag text-blue-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                        <label
                                            class="block text-xs font-semibold text-blue-700 uppercase tracking-wide truncate">
                                            Código Orden
                                        </label>
                                    </div>
                                    <p class="text-base sm:text-lg font-bold text-blue-800 truncate">
                                        {{ $solicitud->codigo ?? 'N/A' }}</p>
                                </div>

                                <!-- Tipo de Servicio -->
                                <div
                                    class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 sm:p-4 border border-green-200">
                                    <div class="flex items-center mb-1 sm:mb-2">
                                        <i class="fas fa-tools text-green-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                        <label
                                            class="block text-xs font-semibold text-green-700 uppercase tracking-wide truncate">
                                            Tipo de Servicio
                                        </label>
                                    </div>
                                    <p class="text-base sm:text-lg font-bold text-green-800 truncate">
                                        {{ $solicitud->tiposervicio ?? 'No especificado' }}
                                    </p>
                                </div>

                                <!-- Área Destino -->
                                @if ($solicitud->nombre_area_destino)
                                    <div
                                        class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg p-3 sm:p-4 border border-emerald-200">
                                        <div class="flex items-center mb-1 sm:mb-2">
                                            <i
                                                class="fas fa-building text-emerald-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                            <label
                                                class="block text-xs font-semibold text-emerald-700 uppercase tracking-wide truncate">
                                                Área Destino
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="text-base sm:text-lg font-bold text-emerald-800 truncate">
                                                {{ $solicitud->nombre_area_destino }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Fecha de Creación -->
                                <div
                                    class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-3 sm:p-4 border border-purple-200">
                                    <div class="flex items-center mb-1 sm:mb-2">
                                        <i
                                            class="fas fa-calendar-plus text-purple-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                        <label
                                            class="block text-xs font-semibold text-purple-700 uppercase tracking-wide truncate">
                                            Fecha de Creación
                                        </label>
                                    </div>
                                    <p class="text-base sm:text-lg font-bold text-purple-800 truncate">
                                        @if ($solicitud->fechacreacion)
                                            <span
                                                class="block">{{ \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d M Y') }}</span>
                                            <span class="text-xs sm:text-sm text-purple-600 block mt-0.5">
                                                {{ \Carbon\Carbon::parse($solicitud->fechacreacion)->format('h:i A') }}
                                            </span>
                                        @else
                                            No especificada
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="space-y-3 sm:space-y-4">
                                <!-- Usuario Destino -->
                                @if ($solicitud->usuario_destino_nombre)
                                    <div
                                        class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg p-3 sm:p-4 border border-indigo-200">
                                        <div class="flex items-center mb-1 sm:mb-2">
                                            <i
                                                class="fas fa-user-tag text-indigo-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                            <label
                                                class="block text-xs font-semibold text-indigo-700 uppercase tracking-wide truncate">
                                                Usuario Destino
                                            </label>
                                        </div>
                                        <div class="space-y-1.5 sm:space-y-2">
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-user-circle text-indigo-500 text-sm sm:text-base mr-1.5 sm:mr-2 flex-shrink-0"></i>
                                                <p class="text-base sm:text-lg font-bold text-indigo-800 truncate">
                                                    {{ $solicitud->usuario_destino_nombre }}
                                                    {{ $solicitud->usuario_destino_apellido }}
                                                </p>
                                            </div>
                                            @if ($solicitud->usuario_destino_correo)
                                                <div
                                                    class="flex items-center text-xs sm:text-sm text-indigo-600 ml-4 sm:ml-6">
                                                    <i class="fas fa-envelope mr-1 sm:mr-1.5 flex-shrink-0"></i>
                                                    <span
                                                        class="truncate">{{ $solicitud->usuario_destino_correo }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Fecha Requerida -->
                                <div
                                    class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-3 sm:p-4 border border-orange-200">
                                    <div class="flex items-center mb-1 sm:mb-2">
                                        <i
                                            class="fas fa-calendar-check text-orange-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                        <label
                                            class="block text-xs font-semibold text-orange-700 uppercase tracking-wide truncate">
                                            Fecha Requerida
                                        </label>
                                    </div>
                                    <p class="text-base sm:text-lg font-bold text-orange-800">
                                        @if ($solicitud->fecharequerida)
                                            <span
                                                class="block">{{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y') }}</span>
                                        @else
                                            <span class="text-red-500">No definida</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Urgencia -->
                                <div
                                    class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-3 sm:p-4 border border-red-200">
                                    <div class="flex items-center mb-1 sm:mb-2">
                                        <i
                                            class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                        <label
                                            class="block text-xs font-semibold text-red-700 uppercase tracking-wide truncate">
                                            Urgencia
                                        </label>
                                    </div>
                                    @php
                                        $urgenciaConfig = [
                                            'baja' => [
                                                'class' => 'bg-green-100 text-green-800 border border-green-200',
                                                'icon' => 'fas fa-check-circle',
                                                'iconColor' => 'text-green-500',
                                                'short' => 'Baja',
                                            ],
                                            'media' => [
                                                'class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                'icon' => 'fas fa-exclamation-circle',
                                                'iconColor' => 'text-yellow-500',
                                                'short' => 'Media',
                                            ],
                                            'alta' => [
                                                'class' => 'bg-red-100 text-red-800 border border-red-200',
                                                'icon' => 'fas fa-exclamation-triangle',
                                                'iconColor' => 'text-red-500',
                                                'short' => 'Alta',
                                            ],
                                        ][$solicitud->urgencia ?? 'baja'] ?? [
                                            'class' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                            'icon' => 'fas fa-question-circle',
                                            'iconColor' => 'text-gray-500',
                                            'short' => 'N/A',
                                        ];
                                    @endphp
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <div class="flex items-center">
                                            <i
                                                class="{{ $urgenciaConfig['icon'] }} {{ $urgenciaConfig['iconColor'] }} mr-1.5 sm:mr-2 text-base sm:text-lg"></i>
                                            <span
                                                class="px-2 sm:px-3 py-1 {{ $urgenciaConfig['class'] }} rounded-full text-xs sm:text-sm font-semibold flex items-center">
                                                <span
                                                    class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full mr-1.5 sm:mr-2 {{ $urgenciaConfig['iconColor'] }}"></span>
                                                <span
                                                    class="hidden sm:inline">{{ ucfirst($solicitud->urgencia ?? 'baja') }}</span>
                                                <span class="sm:hidden">{{ $urgenciaConfig['short'] }}</span>
                                            </span>
                                        </div>

                                        <!-- Indicador visual para urgencia alta en móvil -->
                                        @if (($solicitud->urgencia ?? 'baja') == 'alta')
                                            <div
                                                class="sm:hidden flex items-center text-xs text-red-600 bg-red-50 px-2 py-1 rounded">
                                                <i class="fas fa-bolt mr-1"></i>
                                                <span>Urgencia máxima</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Productos Únicos -->
                                <div
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <div class="flex items-center mb-1 sm:mb-2">
                                        <i class="fas fa-cubes text-gray-600 text-sm sm:text-base mr-1.5 sm:mr-2"></i>
                                        <label
                                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wide truncate">
                                            Productos Únicos
                                        </label>
                                    </div>
                                    <p class="text-base sm:text-lg font-bold text-gray-900">
                                        {{ $solicitud->productos_unicos ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional para móviles -->
                        <div class="mt-4 pt-4 border-t border-gray-200 sm:hidden">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Tipo Solicitud</p>
                                    <p class="text-sm font-semibold text-blue-700">Artículos</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Estado Actual</p>
                                    <p class="text-sm font-semibold text-blue-700">
                                        {{ ucfirst($solicitud->estado ?? 'pendiente') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Mejorado -->
                <div class="space-y-6">
                    <!-- En el Sidebar, después de "Información del Solicitante" -->
                    @if ($solicitud->nombre_area_destino || $solicitud->usuario_destino_nombre)
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                DESTINO FINAL
                            </h3>

                            @if ($solicitud->nombre_area_destino)
                                <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-green-700 font-semibold uppercase">Área Destino</p>
                                            <p class="text-sm font-bold text-green-800">
                                                {{ $solicitud->nombre_area_destino }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($solicitud->usuario_destino_nombre)
                                <div class="p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-indigo-700 font-semibold uppercase">Usuario Destino
                                            </p>
                                            <p class="text-sm font-bold text-indigo-800">
                                                {{ $solicitud->usuario_destino_nombre }}
                                                {{ $solicitud->usuario_destino_apellido }}
                                            </p>
                                            @if ($solicitud->usuario_destino_correo)
                                                <p class="text-xs text-indigo-600 mt-1">
                                                    {{ $solicitud->usuario_destino_correo }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Resumen de la Orden - Mejorado con Font Awesome y Responsive -->
                    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-gray-200">
                        <div class="flex items-center mb-4 sm:mb-5">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-chart-pie text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900">RESUMEN DE LA ORDEN</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">Resumen general de la solicitud</p>
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-3">
                            <!-- Estado -->
                            <div class="flex justify-between items-center py-2 sm:py-2.5 border-b border-gray-100">
                                <span class="text-xs sm:text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-circle-info text-blue-500 mr-2 text-sm sm:text-base"></i>
                                    <span class="hidden sm:inline">Estado:</span>
                                    <span class="sm:hidden">Estado</span>
                                </span>
                                <span
                                    class="px-2 sm:px-3 py-1 bg-blue-100 text-blue-800 rounded text-xs sm:text-sm font-semibold">
                                    {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                </span>
                            </div>

                            <!-- Área Destino -->
                            @if ($solicitud->nombre_area_destino)
                                <div class="flex justify-between items-center py-2 sm:py-2.5 border-b border-gray-100">
                                    <span class="text-xs sm:text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-building text-emerald-500 mr-2 text-sm sm:text-base"></i>
                                        <span class="hidden sm:inline">Área Destino:</span>
                                        <span class="sm:hidden">Destino</span>
                                    </span>
                                    <span
                                        class="text-xs sm:text-sm font-semibold text-emerald-700 text-right max-w-[120px] sm:max-w-none truncate">
                                        {{ $solicitud->nombre_area_destino }}
                                    </span>
                                </div>
                            @endif

                            <!-- Usuario Destino -->
                            @if ($solicitud->usuario_destino_nombre)
                                <div
                                    class="flex justify-between items-center py-2 sm:py-2.5 @if (!$solicitud->nombre_area_destino) border-b border-gray-100 @endif">
                                    <span class="text-xs sm:text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-user-tag text-indigo-500 mr-2 text-sm sm:text-base"></i>
                                        <span class="hidden sm:inline">Destinatario:</span>
                                        <span class="sm:hidden">Para</span>
                                    </span>
                                    <span
                                        class="text-xs sm:text-sm font-semibold text-indigo-700 text-right max-w-[130px] sm:max-w-none">
                                        <span class="block truncate">{{ $solicitud->usuario_destino_nombre }}</span>
                                        <span
                                            class="text-xs text-indigo-600 hidden sm:block">{{ $solicitud->usuario_destino_apellido }}</span>
                                    </span>
                                </div>
                            @endif

                            <!-- Total Artículos -->
                            <div class="flex justify-between items-center py-2 sm:py-2.5 border-b border-gray-100">
                                <span class="text-xs sm:text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-box text-blue-500 mr-2 text-sm sm:text-base"></i>
                                    <span class="hidden sm:inline">Total Artículos:</span>
                                    <span class="sm:hidden">Artículos</span>
                                </span>
                                <span class="font-semibold text-gray-900 text-sm sm:text-base">
                                    {{ $articulos->count() }}
                                </span>
                            </div>

                            <!-- Cantidad Total -->
                            <div class="flex justify-between items-center py-2 sm:py-2.5 border-b border-gray-100">
                                <span class="text-xs sm:text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-layer-group text-purple-500 mr-2 text-sm sm:text-base"></i>
                                    <span class="hidden sm:inline">Cantidad Total:</span>
                                    <span class="sm:hidden">Cantidad</span>
                                </span>
                                <span class="font-semibold text-gray-900 text-sm sm:text-base">
                                    {{ $articulos->sum('cantidad') }}
                                </span>
                            </div>

                            <!-- Urgencia -->
                            <div class="flex justify-between items-center py-2 sm:py-2.5">
                                <span class="text-xs sm:text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-sm sm:text-base"></i>
                                    <span class="hidden sm:inline">Urgencia:</span>
                                    <span class="sm:hidden">Urgencia</span>
                                </span>
                                @php
                                    $urgenciaConfig = [
                                        'baja' => [
                                            'text' => 'text-green-600',
                                            'bg' => 'bg-green-100',
                                            'icon' => 'fas fa-check-circle text-green-500',
                                            'short' => 'Baja',
                                        ],
                                        'media' => [
                                            'text' => 'text-yellow-600',
                                            'bg' => 'bg-yellow-100',
                                            'icon' => 'fas fa-exclamation-circle text-yellow-500',
                                            'short' => 'Media',
                                        ],
                                        'alta' => [
                                            'text' => 'text-red-600',
                                            'bg' => 'bg-red-100',
                                            'icon' => 'fas fa-exclamation-triangle text-red-500',
                                            'short' => 'Alta',
                                        ],
                                    ][$solicitud->urgencia ?? 'baja'] ?? [
                                        'text' => 'text-gray-600',
                                        'bg' => 'bg-gray-100',
                                        'icon' => 'fas fa-question-circle text-gray-500',
                                        'short' => 'N/A',
                                    ];
                                @endphp
                                <span
                                    class="px-2 sm:px-3 py-1 {{ $urgenciaConfig['bg'] }} {{ $urgenciaConfig['text'] }} rounded text-xs sm:text-sm font-semibold inline-flex items-center">
                                    <i class="{{ $urgenciaConfig['icon'] }} mr-1.5 text-xs sm:text-sm"></i>
                                    <span
                                        class="hidden sm:inline">{{ ucfirst($solicitud->urgencia ?? 'baja') }}</span>
                                    <span class="sm:hidden">{{ $urgenciaConfig['short'] }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Información adicional para móviles -->
                        @if ($solicitud->usuario_destino_correo)
                            <div class="mt-4 pt-4 border-t border-gray-200 sm:hidden">
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-envelope text-indigo-400 mr-2"></i>
                                    <span class="truncate">{{ $solicitud->usuario_destino_correo }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Badge de estado general -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Estado General:</span>
                                <div class="flex items-center space-x-2">
                                    @if ($articulos->count() > 0)
                                        @php
                                            $completados = $articulos->where('estado', '1')->count();
                                            $porcentaje =
                                                $articulos->count() > 0
                                                    ? round(($completados / $articulos->count()) * 100)
                                                    : 0;
                                        @endphp
                                        <div class="w-16 sm:w-20 bg-gray-200 rounded-full h-1.5 sm:h-2">
                                            <div class="bg-green-500 h-1.5 sm:h-2 rounded-full"
                                                style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700">{{ $porcentaje }}%</span>
                                    @else
                                        <span class="text-xs text-gray-500">Sin artículos</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if (!empty($solicitud->observaciones))
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Observaciones
                            </h3>
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <p class="text-sm text-yellow-800 leading-relaxed">
                                    {{ $solicitud->observaciones }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Tiempos -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tiempos
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <label
                                    class="block text-xs font-semibold text-blue-700 mb-2 uppercase tracking-wide">Fecha
                                    Requerida</label>
                                <p class="text-sm font-semibold text-gray-900">
                                    @if ($solicitud->fecharequerida)
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                                    @else
                                        <span class="text-red-500">No definida</span>
                                    @endif
                                </p>
                            </div>
                            @php
                                $fechaRequerida = $solicitud->fecharequerida
                                    ? \Carbon\Carbon::parse($solicitud->fecharequerida)
                                    : now();
                                $diasRestantes = now()->diffInDays($fechaRequerida, false);

                                $diasColor =
                                    $diasRestantes <= 0
                                        ? 'text-red-600 bg-red-50 border-red-200'
                                        : ($diasRestantes <= 2
                                            ? 'text-orange-600 bg-orange-50 border-orange-200'
                                            : 'text-green-600 bg-green-50 border-green-200');
                            @endphp
                            <div class="rounded-lg p-4 border {{ $diasColor }}">
                                <label
                                    class="block text-xs font-semibold mb-2 uppercase tracking-wide
                                    @if ($diasRestantes <= 0) text-red-700
                                    @elseif($diasRestantes <= 2) text-orange-700
                                    @else text-green-700 @endif">
                                    Días Restantes
                                </label>
                                <p class="text-2xl font-bold">
                                    {{ $diasRestantes > 0 ? floor($diasRestantes) . ' días' : 'Vencida' }}
                                </p>
                                @if ($diasRestantes <= 2 && $diasRestantes > 0)
                                    <p
                                        class="text-xs mt-1 
                                        @if ($diasRestantes <= 2) text-orange-600 @endif">
                                        ¡Quedan pocos días!
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-layout.default>
