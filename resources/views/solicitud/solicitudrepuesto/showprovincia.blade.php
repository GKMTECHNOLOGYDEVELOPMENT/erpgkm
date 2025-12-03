<x-layout.default>
    <div x-data="{ activeTab: 'articulos' }" class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Principal para Provincia -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                                    Orden Provincia #<span class="text-purple-600">{{ $solicitud->codigo ?? 'N/A' }}</span>
                                </h1>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold border border-blue-200">
                                        {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                    </span>
                                    <span
                                        class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold border border-purple-200">
                                        Solicitud para Provincia
                                    </span>
                                    @if($solicitud->cast_nombre)
                                    <span
                                        class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold border border-green-200">
                                        {{ $solicitud->cast_nombre }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información específica de provincia -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">CAST</label>
        <p class="text-lg font-bold text-gray-900">{{ $solicitud->cast_nombre ?? 'No especificado' }}</p>
        @if($solicitud->cast_direccion)
        <p class="text-sm text-gray-600 mt-1">{{ $solicitud->cast_direccion }}</p>
        @endif
    </div>
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Ticket Manual</label>
        <p class="text-lg font-bold text-gray-900">{{ $solicitud->numeroticket ?? 'N/A' }}</p>
    </div>
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Ubicación CAST</label>
        <p class="text-lg font-bold text-gray-900">
            @if($solicitud->cast_departamento || $solicitud->cast_provincia)
                {{ $solicitud->cast_departamento ?? '' }} - {{ $solicitud->cast_provincia ?? '' }}
            @else
                No especificada
            @endif
        </p>
    </div>
</div>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4 lg:mt-0">
                        <!-- Modifica esta ruta según tu configuración -->
                        <a href="{{ route('solicitudrepuesto.index') ?? route('solicitudarticulo.index') }}"
                            class="flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver
                        </a>
                        @if (($solicitud->estado ?? '') != 'completada')
                            <!-- Modifica esta ruta si tienes edición para provincia -->
                            <a href="#"
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

            <!-- Navegación por Tabs -->
            <div class="bg-white rounded-2xl shadow-sm p-2 mb-6 border border-gray-200">
                <div class="flex space-x-1">
                    <button @click="activeTab = 'articulos'"
                        :class="activeTab === 'articulos' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-200">
                        Repuestos Solicitados
                    </button>
                    <button @click="activeTab = 'detalles'"
                        :class="activeTab === 'detalles' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-200">
                        Detalles de la Orden
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Repuestos Solicitados -->
                    <div x-show="activeTab === 'articulos'"
                        class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Repuestos Solicitados</h2>
                                    <p class="text-gray-600 text-sm">Lista completa de repuestos para provincia</p>
                                </div>
                            </div>
                            <div class="text-right bg-purple-50 rounded-xl px-4 py-3 border border-purple-200">
                                <p class="text-xs text-purple-700 font-semibold uppercase tracking-wide">Total</p>
                                <p class="text-2xl font-bold text-purple-800">{{ $articulos->count() }}</p>
                            </div>
                        </div>

                        @if($articulos->count() > 0)
                        <div class="mb-4 bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center space-x-2 text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium">
                                    Esta orden está asociada al CAST: <strong>{{ $solicitud->cast_nombre }}</strong> 
                                    con ticket manual: <strong>{{ $solicitud->numeroticket }}</strong>
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="overflow-hidden rounded-xl border border-gray-200">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Código Repuesto
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Descripción
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Cantidad
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($articulos as $articulo)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="space-y-1">
                                                    <span class="font-mono font-semibold text-blue-700 block text-sm">
                                                        {{ $articulo->codigo_repuesto ?? 'N/A' }}
                                                    </span>
                                                    @if ($articulo->codigo_barras)
                                                        <span class="text-xs text-gray-500 bg-gray-100 rounded px-2 py-1 inline-block">
                                                            Barras: {{ $articulo->codigo_barras }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div>
                                                    <span class="font-medium text-gray-900 block">
                                                        {{ $articulo->nombre_articulo ?? 'Sin descripción' }}
                                                    </span>
                                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                                        {{ $articulo->tipo_articulo ?? 'General' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-xl font-bold text-gray-900 bg-gray-100 rounded-lg px-3 py-1 inline-block">
                                                    {{ $articulo->cantidad ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
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
                                                @endphp
                                                <span class="px-3 py-1 {{ $estadoClase }} rounded-full text-xs font-semibold">
                                                    {{ $estadoTexto }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-12 text-center">
                                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <p class="mt-4 text-lg font-semibold text-gray-500">No hay repuestos registrados</p>
                                                <p class="mt-1 text-gray-400 text-sm">Esta orden no tiene repuestos solicitados</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($articulos->count() > 0)
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-200">
                                    <p class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-1">Total Repuestos</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ $articulos->count() }}</p>
                                </div>
                                <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
                                    <p class="text-xs text-green-700 font-semibold uppercase tracking-wide mb-1">Total Cantidad</p>
                                    <p class="text-2xl font-bold text-green-800">{{ $articulos->sum('cantidad') }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-xl p-4 text-center border border-purple-200">
                                    <p class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-1">Tipos Diferentes</p>
                                    <p class="text-2xl font-bold text-purple-800">
                                        {{ $articulos->unique('tipo_articulo')->count() }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Sección debajo de Artículos en 2 columnas - Adaptada para provincia -->
                        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Información CAST -->
                            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
    <h3 class="text-xl font-bold text-gray-900 mb-4">Información del CAST</h3>
    <div class="space-y-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900">{{ $solicitud->cast_nombre ?? 'No especificado' }}</p>
                <p class="text-xs text-gray-500 mt-1">Ticket: {{ $solicitud->numeroticket ?? 'N/A' }}</p>
            </div>
        </div>
        
        @if($solicitud->cast_direccion || $solicitud->cast_provincia)
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Ubicación</h4>
            <div class="space-y-1">
                @if($solicitud->cast_direccion)
                <p class="text-sm text-gray-600">{{ $solicitud->cast_direccion }}</p>
                @endif
                @if($solicitud->cast_departamento || $solicitud->cast_provincia)
                <p class="text-sm text-gray-600">
                    {{ $solicitud->cast_distrito ?? '' }} - 
                    {{ $solicitud->cast_provincia ?? '' }} - 
                    {{ $solicitud->cast_departamento ?? '' }}
                </p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

                            <!-- Solicitante -->
                            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Solicitante</h3>
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $solicitud->nombre_solicitante ?? 'No especificado' }}</p>
                                        <p class="text-sm text-gray-600">{{ $solicitud->nombre_area ?? 'Área no especificada' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de la Orden -->
                    <div x-show="activeTab === 'detalles'"
                        class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Detalles de la Orden Provincia</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Código Orden</label>
                                    <p class="text-lg font-bold text-gray-900">{{ $solicitud->codigo ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">CAST Destino</label>
                                    <p class="text-lg font-bold text-gray-900">{{ $solicitud->cast_nombre ?? 'No especificado' }}</p>
                                   @if($solicitud->cast_direccion)
                                    <p class="text-sm text-gray-600 mt-1">{{ $solicitud->cast_direccion }}</p>
                                    @endif
                                    @if($solicitud->cast_departamento || $solicitud->cast_provincia)
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $solicitud->cast_distrito ?? '' }} - 
                                        {{ $solicitud->cast_provincia ?? '' }} - 
                                        {{ $solicitud->cast_departamento ?? '' }}
                                    </p>
                                    @endif
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Ticket Manual</label>
                                    <p class="text-lg font-bold text-gray-900">{{ $solicitud->numeroticket ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Fecha Requerida</label>
                                    <p class="text-lg font-bold text-gray-900">
                                        @if ($solicitud->fecharequerida)
                                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y') }}
                                        @else
                                            No definida
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Tipo de Servicio</label>
                                    <p class="text-lg font-bold text-gray-900">{{ $solicitud->tiposervicio ?? 'No especificado' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Urgencia</label>
                                    @php
                                        $urgenciaClase = [
                                            'baja' => 'bg-green-100 text-green-800 border border-green-200',
                                            'media' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'alta' => 'bg-red-100 text-red-800 border border-red-200',
                                        ][$solicitud->urgencia ?? 'baja'] ?? 'bg-gray-100 text-gray-800 border border-gray-200';

                                        $urgenciaTexto = [
                                            'baja' => 'Baja',
                                            'media' => 'Media',
                                            'alta' => 'Alta',
                                        ][$solicitud->urgencia ?? 'baja'] ?? 'No especificado';
                                    @endphp
                                    <span class="px-3 py-1 {{ $urgenciaClase }} rounded-full text-sm font-semibold">
                                        {{ $urgenciaTexto }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        @if (!empty($solicitud->observaciones))
                            <div class="mt-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                    <p class="text-sm text-yellow-800 leading-relaxed">{{ $solicitud->observaciones }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Mejorado para Provincia -->
                <div class="space-y-6">
                    <!-- Resumen Rápido Mejorado para Provincia -->
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Resumen Provincia</h3>
                        </div>

                        <div class="space-y-4">
                            <!-- Estado -->
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-purple-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Estado</span>
                                </div>
                                <span class="px-3 py-1.5 bg-purple-100 text-purple-800 rounded-full text-xs font-bold border border-purple-200">
                                    {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                </span>
                            </div>

                            <!-- CAST -->
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-green-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">CAST</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $solicitud->cast_nombre ?? 'N/A' }}</span>
                            </div>

                            <!-- Total Repuestos -->
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Total Repuestos</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900 bg-gray-100 rounded-lg px-3 py-1.5">
                                    {{ $articulos->count() }}
                                </span>
                            </div>

                            <!-- Cantidad Total -->
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-orange-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Cantidad Total</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900 bg-gray-100 rounded-lg px-3 py-1.5">
                                    {{ $articulos->sum('cantidad') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tiempos Mejorado -->
                    <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg p-6 border border-blue-200 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Tiempos</h3>
                        </div>

                        <!-- Días Restantes -->
                        @php
                            $fechaRequerida = $solicitud->fecharequerida
                                ? \Carbon\Carbon::parse($solicitud->fecharequerida)
                                : now();

                            $diasRestantes = (int) now()->diffInDays($fechaRequerida, false);

                            $diasConfig = [
                                'vencida' => [
                                    'class' => 'bg-red-100 border-red-200 text-red-800',
                                    'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'text' => 'Vencida',
                                ],
                                'urgente' => [
                                    'class' => 'bg-orange-100 border-orange-200 text-orange-800',
                                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                                    'text' => $diasRestantes . ' días',
                                ],
                                'normal' => [
                                    'class' => 'bg-green-100 border-green-200 text-green-800',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'text' => $diasRestantes . ' días',
                                ],
                            ];

                            $diasEstado = $diasRestantes <= 0 ? 'vencida' : ($diasRestantes <= 2 ? 'urgente' : 'normal');
                        @endphp

                        <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-{{ $diasEstado === 'vencida' ? 'red' : ($diasEstado === 'urgente' ? 'orange' : 'green') }}-300 transition-colors duration-200">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 {{ $diasEstado === 'vencida' ? 'bg-red-100' : ($diasEstado === 'urgente' ? 'bg-orange-100' : 'bg-green-100') }} rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 {{ $diasEstado === 'vencida' ? 'text-red-600' : ($diasEstado === 'urgente' ? 'text-orange-600' : 'text-green-600') }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $diasConfig[$diasEstado]['icon'] }}" />
                                    </svg>
                                </div>
                                <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Días Restantes</label>
                            </div>

                            <div class="flex items-center justify-between pl-11">
                                <p class="text-3xl font-extrabold {{ $diasRestantes <= 0 ? 'text-red-600' : ($diasRestantes <= 2 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $diasConfig[$diasEstado]['text'] }}
                                </p>

                                @if ($diasRestantes > 0 && $diasRestantes <= 7)
                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-{{ $diasEstado === 'urgente' ? 'orange' : 'green' }}-500 h-2 rounded-full"
                                            style="width: {{ max(10, ((7 - $diasRestantes) / 7) * 100) }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>