<x-layout.default>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Principal Mejorado -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-200 relative overflow-hidden">
                <!-- Elemento decorativo -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16"></div>

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between relative z-10">
                    <div class="flex-1">
                        <div class="flex items-start space-x-4 mb-4">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center gap-3 mb-3">
                                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">
                                        Orden #<span class="text-blue-600">{{ $solicitud->codigo ?? 'N/A' }}</span>
                                    </h1>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold border border-blue-200 shadow-sm flex items-center">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                            {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                        </span>
                                        <span
                                            class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold border border-purple-200 shadow-sm">
                                            Solicitud de Artículo
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
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
                                        <span>{{ $solicitud->nombre_solicitante ?? 'Solicitante no especificado' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4 lg:mt-0">
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver
                        </a>
                        @if (($solicitud->estado ?? '') != 'completada')
                            <a href="{{ route('solicitudarticulo.edit', $solicitud->idsolicitudesordenes) }}"
                                class="flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
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
                    <!-- Artículos Solicitados - VISTA MEJORADA -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-box text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Artículos Solicitados</h2>
                                    <p class="text-white/80 text-sm">Lista completa de artículos requeridos</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-900">Detalle de Artículos</h3>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600">
                                        {{ $articulos->count() }} artículo{{ $articulos->count() !== 1 ? 's' : '' }}
                                    </span>
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"
                                        x-show="{{ $articulos->count() > 0 }}"></div>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-blue-100">
                                <table class="w-full">
                                    <thead class="bg-blue-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                Artículo</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                Código</th>
                                            <th class="px-6 py-4 text-center text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                Cantidad</th>
                                            <th class="px-6 py-4 text-center text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-blue-100">
                                        @forelse($articulos as $articulo)
                                            <tr class="product-row hover:bg-blue-50 transition-all duration-200">
                                                <!-- Columna Artículo con información completa -->
                                                <td class="px-6 py-4">
                                                    <div class="space-y-1">
                                                        <!-- Nombre principal -->
                                                        <div class="text-base font-semibold text-gray-900">
                                                            {{ $articulo->nombre_articulo ?? 'Artículo no especificado' }}
                                                        </div>
                                                        
                                                        <!-- Información adicional -->
                                                        <div class="text-xs text-gray-500 space-y-0.5">
                                                            <!-- Tipo de artículo -->
                                                            @if($articulo->tipo_articulo)
                                                            <div class="flex items-center space-x-1">
                                                                <span class="font-medium">Tipo:</span>
                                                                <span>{{ $articulo->tipo_articulo }}</span>
                                                            </div>
                                                            @endif
                                                            
                                                            <!-- Modelo -->
                                                            @if($articulo->modelo)
                                                            <div class="flex items-center space-x-1">
                                                                <span class="font-medium">Modelo:</span>
                                                                <span>{{ $articulo->modelo }}</span>
                                                            </div>
                                                            @endif
                                                            
                                                            <!-- Marca -->
                                                            @if($articulo->marca)
                                                            <div class="flex items-center space-x-1">
                                                                <span class="font-medium">Marca:</span>
                                                                <span>{{ $articulo->marca }}</span>
                                                            </div>
                                                            @endif
                                                            
                                                            <!-- Subcategoría -->
                                                            @if($articulo->subcategoria)
                                                            <div class="flex items-center space-x-1">
                                                                <span class="font-medium">Categoría:</span>
                                                                <span>{{ $articulo->subcategoria }}</span>
                                                            </div>
                                                            @endif
                                                        </div>

                                                        <!-- Descripción adicional si existe -->
                                                        @if($articulo->descripcion)
                                                        <div class="mt-2 p-2 bg-gray-50 rounded border border-gray-200">
                                                            <p class="text-xs text-gray-600">
                                                                <span class="font-medium">Nota:</span> {{ $articulo->descripcion }}
                                                            </p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                
                                                <!-- Columna Código -->
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-base text-blue-600 font-mono font-bold">
                                                        {{ $articulo->codigo_barras ?: $articulo->codigo_repuesto }}
                                                    </div>
                                                    <div class="text-xs text-gray-400 mt-1">
                                                        {{ $articulo->codigo_barras ? 'Cód. Barras' : 'Cód. Repuesto' }}
                                                    </div>
                                                </td>
                                                
                                                <!-- Columna Cantidad -->
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <span class="font-bold text-lg bg-blue-100 text-blue-800 rounded-lg px-4 py-2 border border-blue-200">
                                                            {{ $articulo->cantidad ?? 0 }}
                                                        </span>
                                                    </div>
                                                </td>
                                                
                                                <!-- Columna Estado -->
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $estadoClase = [
                                                            '0' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                            '1' => 'bg-green-100 text-green-800 border border-green-200',
                                                            '2' => 'bg-red-100 text-red-800 border border-red-200',
                                                        ][$articulo->estado ?? 0] ?? 'bg-gray-100 text-gray-800 border border-gray-200';

                                                        $estadoTexto = [
                                                            '0' => 'Pendiente',
                                                            '1' => 'Completado',
                                                            '2' => 'Rechazado',
                                                        ][$articulo->estado ?? 0] ?? 'Desconocido';

                                                        $estadoIcono = [
                                                            '0' => '⏳',
                                                            '1' => '✅',
                                                            '2' => '❌',
                                                        ][$articulo->estado ?? 0] ?? '❓';
                                                    @endphp
                                                    <div class="flex justify-center">
                                                        <span class="px-3 py-2 {{ $estadoClase }} rounded-full text-sm font-semibold flex items-center gap-2">
                                                            <span>{{ $estadoIcono }}</span>
                                                            {{ $estadoTexto }}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <p class="mt-4 text-lg font-medium text-gray-900">No hay
                                                        artículos registrados</p>
                                                    <p class="text-sm mt-2">No se han agregado artículos a esta solicitud</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($articulos->count() > 0)
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-200">
                                    <p class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-1">Total Artículos</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ $articulos->count() }}</p>
                                </div>
                                <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
                                    <p class="text-xs text-green-700 font-semibold uppercase tracking-wide mb-1">Total Cantidad</p>
                                    <p class="text-2xl font-bold text-green-800">{{ $articulos->sum('cantidad') }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-xl p-4 text-center border border-purple-200">
                                    <p class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-1">Tipos Diferentes</p>
                                    <p class="text-2xl font-bold text-purple-800">
                                        {{ $articulos->unique('tipo_articulo')->count() }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información de la Orden -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            INFORMACIÓN DE LA ORDEN
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                                    <label
                                        class="block text-xs font-semibold text-blue-700 mb-2 uppercase tracking-wide">Código
                                        Orden</label>
                                    <p class="text-lg font-bold text-blue-800">{{ $solicitud->codigo ?? 'N/A' }}</p>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                                    <label
                                        class="block text-xs font-semibold text-green-700 mb-2 uppercase tracking-wide">Tipo
                                        de Servicio</label>
                                    <p class="text-lg font-bold text-green-800">
                                        {{ $solicitud->tiposervicio ?? 'No especificado' }}</p>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                                    <label
                                        class="block text-xs font-semibold text-purple-700 mb-2 uppercase tracking-wide">Fecha
                                        de Creación</label>
                                    <p class="text-lg font-bold text-purple-800">
                                        @if ($solicitud->fechacreacion)
                                            {{ \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d M Y, h:i A') }}
                                        @else
                                            No especificada
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div
                                    class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                                    <label
                                        class="block text-xs font-semibold text-orange-700 mb-2 uppercase tracking-wide">Fecha
                                        Requerida</label>
                                    <p class="text-lg font-bold text-orange-800">
                                        @if ($solicitud->fecharequerida)
                                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                                        @else
                                            No definida
                                        @endif
                                    </p>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                                    <label
                                        class="block text-xs font-semibold text-red-700 mb-2 uppercase tracking-wide">Urgencia</label>
                                    @php
                                        $urgenciaClase =
                                            [
                                                'baja' => 'bg-green-100 text-green-800 border border-green-200',
                                                'media' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                'alta' => 'bg-red-100 text-red-800 border border-red-200',
                                            ][$solicitud->urgencia ?? 'baja'] ??
                                            'bg-gray-100 text-gray-800 border border-gray-200';

                                        $urgenciaTexto =
                                            [
                                                'baja' => 'Baja',
                                                'media' => 'Media',
                                                'alta' => 'Alta',
                                            ][$solicitud->urgencia ?? 'baja'] ?? 'No especificado';
                                    @endphp
                                    <span
                                        class="px-3 py-1 {{ $urgenciaClase }} rounded-full text-sm font-semibold flex items-center w-fit">
                                        <span
                                            class="w-2 h-2 rounded-full mr-2 
                                            @if (($solicitud->urgencia ?? 'baja') == 'baja') bg-green-500
                                            @elseif(($solicitud->urgencia ?? 'baja') == 'media') bg-yellow-500
                                            @else bg-red-500 @endif">
                                        </span>
                                        {{ $urgenciaTexto }}
                                    </span>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Productos
                                        Únicos</label>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $solicitud->productos_unicos ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Mejorado -->
                <div class="space-y-6">
                    <!-- Información del Solicitante -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            SOLICITANTE
                        </h3>
                        <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $solicitud->nombre_solicitante ?? 'No especificado' }}</p>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $solicitud->nombre_area ?? 'Área no especificada' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de la Orden -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            RESUMEN DE LA ORDEN
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Estado:
                                </span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                    {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Total Artículos:
                                </span>
                                <span class="font-semibold text-gray-900">{{ $articulos->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                    Cantidad Total:
                                </span>
                                <span class="font-semibold text-gray-900">{{ $articulos->sum('cantidad') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Urgencia:
                                </span>
                                @php
                                    $urgenciaColor =
                                        [
                                            'baja' => 'text-green-600 bg-green-100',
                                            'media' => 'text-yellow-600 bg-yellow-100',
                                            'alta' => 'text-red-600 bg-red-100',
                                        ][$solicitud->urgencia ?? 'baja'] ?? 'text-gray-600 bg-gray-100';
                                @endphp
                                <span class="px-2 py-1 {{ $urgenciaColor }} rounded text-xs font-semibold">
                                    {{ ucfirst($solicitud->urgencia ?? 'baja') }}
                                </span>
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