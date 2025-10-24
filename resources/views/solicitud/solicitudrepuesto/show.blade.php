<x-layout.default>
    <div x-data="{ activeTab: 'articulos' }" class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Principal -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                                    Orden #<span class="text-blue-600">{{ $solicitud->codigo ?? 'N/A' }}</span>
                                </h1>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold border border-blue-200">
                                        {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                    </span>
                                    <span
                                        class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold border border-purple-200">
                                        Solicitud de Repuesto
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4 lg:mt-0">
                        <a href="{{ route('solicitudrepuesto.index') }}"
                            class="flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver
                        </a>
                        @if (($solicitud->estado ?? '') != 'completada')
                            <a href="{{ route('solicitudrepuesto.edit', $solicitud->idsolicitudesordenes) }}"
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
                        Artículos Solicitados
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
                    <!-- Artículos Solicitados -->
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
                                    <h2 class="text-2xl font-bold text-gray-900">Artículos Solicitados</h2>
                                    <p class="text-gray-600 text-sm">Lista completa de repuestos requeridos</p>
                                </div>
                            </div>
                            <div class="text-right bg-blue-50 rounded-xl px-4 py-3 border border-blue-200">
                                <p class="text-xs text-blue-700 font-semibold uppercase tracking-wide">Total</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $articulos->count() }}</p>
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-xl border border-gray-200">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Código</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Artículo</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Tipo</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Cantidad</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($articulos as $articulo)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="space-y-1">
                                                    <span class="font-mono font-semibold text-blue-700 block text-sm">
                                                        {{ $articulo->codigo_repuesto ?? ($articulo->codigo_barras ?? 'N/A') }}
                                                    </span>
                                                    @if ($articulo->codigo_barras && $articulo->codigo_repuesto)
                                                        <span
                                                            class="text-xs text-gray-500 bg-gray-100 rounded px-2 py-1 inline-block">
                                                            Barras: {{ $articulo->codigo_barras }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <p class="font-semibold text-gray-900 text-sm">
                                                    {{ $articulo->nombre_articulo ?? 'Artículo no especificado' }}</p>
                                                @if ($articulo->precio_compra)
                                                    <p class="text-xs text-green-600 font-medium mt-1">
                                                        ${{ number_format($articulo->precio_compra, 2) }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                                    {{ $articulo->tipo_articulo ?? 'General' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="text-xl font-bold text-gray-900 bg-gray-100 rounded-lg px-3 py-1 inline-block">
                                                    {{ $articulo->cantidad ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $estadoClase =
                                                        [
                                                            '0' =>
                                                                'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                            '1' =>
                                                                'bg-green-100 text-green-800 border border-green-200',
                                                            '2' => 'bg-red-100 text-red-800 border border-red-200',
                                                        ][$articulo->estado ?? 0] ??
                                                        'bg-gray-100 text-gray-800 border border-gray-200';

                                                    $estadoTexto =
                                                        [
                                                            '0' => 'Pendiente',
                                                            '1' => 'Completado',
                                                            '2' => 'Rechazado',
                                                        ][$articulo->estado ?? 0] ?? 'Desconocido';
                                                @endphp
                                                <span
                                                    class="px-3 py-1 {{ $estadoClase }} rounded-full text-xs font-semibold">
                                                    {{ $estadoTexto }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-12 text-center">
                                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <p class="mt-4 text-lg font-semibold text-gray-500">No hay artículos
                                                    registrados</p>
                                                <p class="mt-1 text-gray-400 text-sm">Agrega artículos para comenzar
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($articulos->count() > 0)
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-200">
                                    <p class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-1">Total
                                        Artículos</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ $articulos->count() }}</p>
                                </div>
                                <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
                                    <p class="text-xs text-green-700 font-semibold uppercase tracking-wide mb-1">Total
                                        Cantidad</p>
                                    <p class="text-2xl font-bold text-green-800">{{ $articulos->sum('cantidad') }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-xl p-4 text-center border border-purple-200">
                                    <p class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-1">Tipos
                                        Diferentes</p>
                                    <p class="text-2xl font-bold text-purple-800">
                                        {{ $articulos->unique('tipo_articulo')->count() }}</p>
                                </div>
                            </div>
                        @endif
                        <!-- Sección debajo de Artículos en 2 columnas -->
                        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Solicitante -->
                            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Solicitante</h3>
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $solicitud->nombre_solicitante ?? 'No especificado' }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $solicitud->nombre_area ?? 'Área no especificada' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            @if (!empty($solicitud->observaciones))
                                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">Observaciones</h3>
                                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                        <p class="text-sm text-yellow-800 leading-relaxed">
                                            {{ $solicitud->observaciones }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detalles de la Orden -->
                    <div x-show="activeTab === 'detalles'"
                        class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Detalles de la Orden</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Código
                                        Orden</label>
                                    <p class="text-lg font-bold text-gray-900">{{ $solicitud->codigo ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Tipo
                                        de Servicio</label>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $solicitud->tiposervicio ?? 'No especificado' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Fecha
                                        Requerida</label>
                                    <p class="text-lg font-bold text-gray-900">
                                        @if ($solicitud->fecharequerida)
                                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y') }}
                                        @else
                                            No definida
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Urgencia</label>
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
                                    <span class="px-3 py-1 {{ $urgenciaClase }} rounded-full text-sm font-semibold">
                                        {{ $urgenciaTexto }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar Mejorado -->
                <div class="space-y-6">
                    <!-- Resumen Rápido Mejorado -->
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-3 mb-5">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Resumen Rápido</h3>
                        </div>

                        <div class="space-y-4">
                            <!-- Estado -->
                            <div
                                class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Estado</span>
                                </div>
                                <span
                                    class="px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-xs font-bold border border-blue-200">
                                    {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                </span>
                            </div>

                            <!-- Total Artículos -->
                            <div
                                class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-green-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Total Artículos</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900 bg-gray-100 rounded-lg px-3 py-1.5">
                                    {{ $articulos->count() }}
                                </span>
                            </div>

                            <!-- Cantidad Total -->
                            <div
                                class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-purple-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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

                            <!-- Urgencia -->
                            <div
                                class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-orange-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Urgencia</span>
                                </div>
                                @php
                                    $urgenciaConfig = [
                                        'baja' => [
                                            'class' => 'bg-green-100 text-green-800 border border-green-200',
                                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        ],
                                        'media' => [
                                            'class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'icon' =>
                                                'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                                        ],
                                        'alta' => [
                                            'class' => 'bg-red-100 text-red-800 border border-red-200',
                                            'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                        ],
                                    ][$solicitud->urgencia ?? 'baja'] ?? [
                                        'class' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                        'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    ];
                                @endphp
                                <span
                                    class="flex items-center space-x-2 px-3 py-1.5 {{ $urgenciaConfig['class'] }} rounded-full text-xs font-bold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $urgenciaConfig['icon'] }}" />
                                    </svg>
                                    <span>{{ ucfirst($solicitud->urgencia ?? 'baja') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tiempos Mejorado -->
                    <div
                        class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg p-6 border border-blue-200 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-3 mb-5">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Tiempos</h3>
                        </div>

                        <div class="space-y-5">
                            <!-- Fecha Requerida -->
                            <div
                                class="bg-white rounded-xl p-4 border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Fecha
                                        Requerida</label>
                                </div>
                                <p class="text-lg font-bold text-gray-900 pl-11">
                                    @if ($solicitud->fecharequerida)
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                                    @else
                                        <span class="text-red-500 flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>No definida</span>
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <!-- Días Restantes -->
                            @php
                                $fechaRequerida = $solicitud->fecharequerida
                                    ? \Carbon\Carbon::parse($solicitud->fecharequerida)
                                    : now();
                                $diasRestantes = now()->diffInDays($fechaRequerida, false);

                                $diasConfig = [
                                    'vencida' => [
                                        'class' => 'bg-red-100 border-red-200 text-red-800',
                                        'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'text' => 'Vencida',
                                    ],
                                    'urgente' => [
                                        'class' => 'bg-orange-100 border-orange-200 text-orange-800',
                                        'icon' =>
                                            'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                                        'text' => $diasRestantes . ' días',
                                    ],
                                    'normal' => [
                                        'class' => 'bg-green-100 border-green-200 text-green-800',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'text' => $diasRestantes . ' días',
                                    ],
                                ];

                                $diasEstado =
                                    $diasRestantes <= 0 ? 'vencida' : ($diasRestantes <= 2 ? 'urgente' : 'normal');
                            @endphp

                            <div
                                class="bg-white rounded-xl p-4 border border-gray-200 hover:border-{{ $diasEstado === 'vencida' ? 'red' : ($diasEstado === 'urgente' ? 'orange' : 'green') }}-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div
                                        class="w-8 h-8 {{ $diasEstado === 'vencida' ? 'bg-red-100' : ($diasEstado === 'urgente' ? 'bg-orange-100' : 'bg-green-100') }} rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 {{ $diasEstado === 'vencida' ? 'text-red-600' : ($diasEstado === 'urgente' ? 'text-orange-600' : 'text-green-600') }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $diasConfig[$diasEstado]['icon'] }}" />
                                        </svg>
                                    </div>
                                    <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Días
                                        Restantes</label>
                                </div>
                                <div class="flex items-center justify-between pl-11">
                                    <p
                                        class="text-3xl font-extrabold {{ $diasRestantes <= 0 ? 'text-red-600' : ($diasRestantes <= 2 ? 'text-orange-600' : 'text-green-600') }}">
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
    </div>
</x-layout.default>
