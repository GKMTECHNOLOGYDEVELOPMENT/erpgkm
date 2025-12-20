<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">KARDEX GENERAL</h1>

        <div class="mb-6">
            <p class="text-gray-700">
                En el módulo KARDEX puede ver todos los movimientos de entradas - salidas de todos los productos.
                Además, puede filtrar la información por producto, código, tipo, modelo, marca, categoría, CAS, región o número de orden.
            </p>
        </div>

        <!-- Tarjetas de estadísticas FILTRADAS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-blue-800">Movimientos Filtrados</h3>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($movimientosFiltrados) }}</p>
                <p class="text-xs text-blue-600 mt-1">
                    @if($search || $startDate || $endDate)
                        <span class="inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                            </svg>
                            Filtros aplicados
                        </span>
                    @else
                        <span class="text-gray-500">Total general: {{ number_format($totalMovimientos) }}</span>
                    @endif
                </p>
            </div>
            
            <div class="bg-green-100 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-green-800">Entradas Filtradas</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($totalEntradasFiltradas) }}</p>
                <p class="text-xs text-green-600 mt-1">
                    @if($totalEntradasFiltradas > 0)
                        <span class="inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Unidades
                        </span>
                    @else
                        <span class="text-gray-500">Sin entradas</span>
                    @endif
                </p>
            </div>
            
            <div class="bg-red-100 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-red-800">Salidas Filtradas</h3>
                <p class="text-2xl font-bold text-red-600">{{ number_format($totalSalidasFiltradas) }}</p>
                <p class="text-xs text-red-600 mt-1">
                    @if($totalSalidasFiltradas > 0)
                        <span class="inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 7.414V11a1 1 0 102 0V7.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                            </svg>
                            Unidades
                        </span>
                    @else
                        <span class="text-gray-500">Sin salidas</span>
                    @endif
                </p>
            </div>
            
            <div class="bg-purple-100 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-purple-800">Inventario Filtrado</h3>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($totalInventarioActualFiltrado, 0) }}</p>
                <p class="text-xs text-purple-600 mt-1">
                    @if($totalInventarioActualFiltrado > 0)
                        <span class="inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                            Total actual
                        </span>
                    @else
                        <span class="text-gray-500">Sin inventario</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Tarjeta informativa cuando hay filtros -->
        @if($search || $startDate || $endDate)
        <div class="mb-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">
                            Mostrando resultados filtrados
                            @if($search)
                                <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded">
                                    Búsqueda: "{{ $search }}"
                                </span>
                            @endif
                        </p>
                        @if($startDate || $endDate)
                        <p class="text-xs text-yellow-600 mt-1">
                            @if($startDate && $endDate)
                                Período: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            @elseif($startDate)
                                Desde: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                            @elseif($endDate)
                                Hasta: {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            @endif
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Formulario de filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Filtros de Búsqueda</h2>
            <form method="GET" action="{{ route('kardex.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ $search }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Producto, código, CAS, región, modelo, Nº orden...">
                        <p class="mt-1 text-xs text-gray-500">
                            Buscar por: nombre, código, CAS, región (LIMA/PROVINCIA), modelo, marca, categoría, tipo o número de orden
                        </p>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha inicial</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha final</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar
                    </button>
                    <a href="{{ route('kardex.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de movimientos -->
        <div class="panel rounded-xl shadow-lg p-6 mb-8 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    MOVIMIENTOS GENERALES
                </h2>
                
                <!-- Mostrar información del filtro actual -->
                @if($search || $startDate || $endDate)
                <div class="text-sm text-gray-600">
                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                        Resultados filtrados
                    </span>
                </div>
                @endif
            </div>

            @if ($movimientos->count() > 0)
                <!-- Estadísticas RÁPIDAS de los resultados PAGINADOS (solo esta página) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-sm opacity-80">En esta página</p>
                                <p class="text-xl font-bold">{{ number_format($movimientos->count()) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm opacity-80">Entradas página</p>
                                <p class="text-xl font-bold">{{ number_format($movimientos->sum('unidades_entrada')) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 7.414V11a1 1 0 102 0V7.414l1.293 1.293a1 1 0 001.414-1.414z"
                                    clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                            </svg>
                            <div>
                                <p class="text-sm opacity-80">Salidas página</p>
                                <p class="text-xl font-bold">{{ number_format($movimientos->sum('unidades_salida')) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-sm opacity-80">Inventario página</p>
                                <p class="text-xl font-bold">
                                    {{ number_format($movimientos->sum('inventario_actual'), 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla responsiva mejorada -->
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <span>Fecha</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z"></path>
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Producto 
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Tipo / Características
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        CAS
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-1">
                                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>REGIÓN</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-1">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Entradas</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-1">
                                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 7.414V11a1 1 0 102 0V7.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                                            </svg>
                                            <span>Salidas</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-1">
                                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.2 6.5 10.266a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Ticket</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Inv. Inicial
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Inv. Actual
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($movimientos as $index => $movimiento)
                                    @php
                                        $tieneSalidas = $movimiento->unidades_salida > 0;
                                        $tieneEntradas = $movimiento->unidades_entrada > 0;
                                    @endphp
                                    <tr class="hover:bg-blue-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $movimiento->fecha->format('d/m/Y') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $movimiento->fecha->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="mb-1">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $movimiento->nombre_producto ?? 'Sin nombre' }}
                                                </div>
                                                @if($movimiento->codigo_barras)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $movimiento->codigo_barras }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <!-- Información del Tipo -->
                                            <div class="mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($movimiento->idTipoArticulo == 1) bg-green-100 text-green-800
                                                    @elseif($movimiento->idTipoArticulo == 2) bg-blue-100 text-blue-800
                                                    @elseif($movimiento->idTipoArticulo == 3) bg-yellow-100 text-yellow-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    {{ $movimiento->tipo_articulo_nombre ?? 'Sin tipo' }}
                                                </span>
                                            </div>
                                            
                                            <!-- Información para Tipo 1, 3, 4 -->
                                            @if(in_array($movimiento->idTipoArticulo, [1, 3, 4]))
                                                @if($movimiento->modelo_nombre || $movimiento->marca_nombre || $movimiento->categoria_nombre)
                                                    <div class="space-y-1">
                                                        @if($movimiento->modelo_nombre)
                                                            <div class="text-xs text-gray-600">
                                                                <span class="font-medium">Modelo:</span> {{ $movimiento->modelo_nombre }}
                                                            </div>
                                                        @endif
                                                        @if($movimiento->marca_nombre)
                                                            <div class="text-xs text-gray-600">
                                                                <span class="font-medium">Marca:</span> {{ $movimiento->marca_nombre }}
                                                            </div>
                                                        @endif
                                                        @if($movimiento->categoria_nombre)
                                                            <div class="text-xs text-gray-600">
                                                                <span class="font-medium">Categoría:</span> {{ $movimiento->categoria_nombre }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Sin características</span>
                                                @endif
                                            
                                            <!-- Información para Tipo 2 (Repuestos) -->
                                            @elseif($movimiento->idTipoArticulo == 2)
                                                <div class="space-y-1">
                                                    @if($movimiento->subcategoria_nombre)
                                                        <div class="text-xs text-gray-600">
                                                            <span class="font-medium">Subcategoría:</span> {{ $movimiento->subcategoria_nombre }}
                                                        </div>
                                                    @endif
                                                    @if($movimiento->modelo_nombre)
                                                        <div class="text-xs text-gray-600">
                                                            <span class="font-medium">Modelo:</span> {{ $movimiento->modelo_nombre }}
                                                        </div>
                                                    @endif
                                                    @if($movimiento->marca_nombre)
                                                        <div class="text-xs text-gray-600">
                                                            <span class="font-medium">Marca:</span> {{ $movimiento->marca_nombre }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $movimiento->cas ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(isset($movimiento->region))
                                                @if($movimiento->region == 'LIMA')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        LIMA
                                                    </span>
                                                @elseif($movimiento->region == 'PROVINCIA')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        PROVINCIA
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        SIN REGISTRO
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 text-sm italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($tieneEntradas)
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ number_format($movimiento->unidades_entrada, 0) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($tieneSalidas)
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 7.414V11a1 1 0 102 0V7.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                                                    </svg>
                                                    {{ number_format($movimiento->unidades_salida, 0) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($tieneSalidas && !empty($movimiento->numeros_orden))
                                                <div class="flex flex-col items-center gap-1">
                                                    @php
                                                        $numeros = array_unique(explode(', ', $movimiento->numeros_orden));
                                                        $primerNumero = $numeros[0];
                                                        $totalNumeros = count($numeros);
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.2 6.5 10.266a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $primerNumero }}
                                                    </span>
                                                    @if ($totalNumeros > 1)
                                                        <span class="text-xs text-gray-500">
                                                            +{{ $totalNumeros - 1 }} más
                                                        </span>
                                                    @endif
                                                </div>
                                            @elseif ($tieneSalidas && empty($movimiento->numeros_orden))
                                                <span class="text-xs text-yellow-600 italic bg-yellow-50 px-2 py-1 rounded">
                                                    Sin ticket
                                                </span>
                                            @elseif ($tieneEntradas)
                                                <span class="text-gray-400 text-sm italic">N/A</span>
                                            @else
                                                <span class="text-gray-400 text-sm italic">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ number_format($movimiento->inventario_inicial, 0) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold 
                                                @if ($movimiento->inventario_actual > 0) bg-blue-100 text-blue-800 
                                                @elseif($movimiento->inventario_actual == 0) bg-yellow-100 text-yellow-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ number_format($movimiento->inventario_actual, 0) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación mejorada -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <span>Mostrando {{ $movimientos->firstItem() }} - {{ $movimientos->lastItem() }} de
                            {{ $movimientos->total() }} resultados</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $movimientos->appends(request()->input())->links() }}
                    </div>
                </div>
            @else
                <!-- Estado vacío mejorado -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron movimientos</h3>
                    <p class="text-gray-500 mb-6">No hay movimientos que coincidan con los filtros aplicados.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('kardex.index') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Resumen final -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center">
                <div class="text-gray-600">
                    @if ($movimientos->count() > 0)
                        Mostrando {{ $movimientos->firstItem() }} al {{ $movimientos->lastItem() }} de
                        {{ $movimientos->total() }} registros filtrados
                    @else
                        No hay registros que mostrar
                    @endif
                </div>

                <div class="text-gray-500 text-sm">
                    Copyright {{ now()->year }} {{ config('app.name') }} - Todos los Derechos Reservados.
                </div>
            </div>
            
            <!-- Resumen de estadísticas -->
            @if($movimientos->count() > 0 && ($search || $startDate || $endDate))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Resumen de filtros aplicados:</span>
                    Se encontraron <span class="font-bold text-blue-600">{{ number_format($movimientosFiltrados) }}</span> movimientos,
                    con <span class="font-bold text-green-600">{{ number_format($totalEntradasFiltradas) }}</span> entradas,
                    <span class="font-bold text-red-600">{{ number_format($totalSalidasFiltradas) }}</span> salidas y
                    <span class="font-bold text-purple-600">{{ number_format($totalInventarioActualFiltrado, 0) }}</span> unidades en inventario actual.
                </p>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('end_date').max = today;
                document.getElementById('start_date').max = today;

                const startDate = document.getElementById('start_date');
                const endDate = document.getElementById('end_date');

                startDate.addEventListener('change', function() {
                    endDate.min = this.value;
                });

                endDate.addEventListener('change', function() {
                    startDate.max = this.value;
                });

                const searchInput = document.getElementById('search');
                if (searchInput && !searchInput.value) {
                    searchInput.focus();
                }
            });
        </script>
    @endpush
</x-layout.default>