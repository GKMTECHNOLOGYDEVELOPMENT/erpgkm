<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">KARDEX GENERAL</h1>

        <div class="mb-6">
            <p class="text-gray-700">
                En el módulo KARDEX puede ver todos los movimientos y costos de entradas - salidas de todos los
                productos.
                Además, puede filtrar la información por producto o por rango de fechas.
            </p>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-blue-800">Total de Artículos</h3>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($totalArticulos) }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-green-800">Total de Movimientos</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($totalMovimientos) }}</p>
            </div>
            <div class="bg-purple-100 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-purple-800">Movimientos Filtrados</h3>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($movimientosFiltrados) }}</p>
            </div>
        </div>

        <!-- Formulario de filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Filtros de Búsqueda</h2>
            <form method="GET" action="{{ route('kardex.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar producto</label>
                        <input type="text" name="search" id="search" value="{{ $search }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nombre o código de producto">
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
                        Filtrar
                    </button>
                    <a href="{{ route('kardex.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
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

                <!-- Botones de acción adicionales -->
                <div class="flex space-x-2">
                    <button
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Exportar
                    </button>
                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filtros
                    </button>
                </div>
            </div>

            @if ($movimientos->count() > 0)
                <!-- Estadísticas rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-sm opacity-80">Total Movimientos</p>
                                <p class="text-xl font-bold">{{ number_format($movimientos->total()) }}</p>
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
                                <p class="text-sm opacity-80">Total Entradas</p>
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
                                <p class="text-sm opacity-80">Total Salidas</p>
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
                                <p class="text-sm opacity-80">Valor Inventario</p>
                                <p class="text-xl font-bold">
                                    ${{ number_format($movimientos->sum('costo_inventario'), 2) }}</p>
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
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <span>Fecha</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z">
                                                </path>
                                            </svg>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Producto</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Código</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-1">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Entradas</span>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        C.U. Entrada</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-1">
                                            <svg class="w-4 h-4 text-red-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 7.414V11a1 1 0 102 0V7.414l1.293 1.293a1 1 0 001.414-1.414z"
                                                    clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                                            </svg>
                                            <span>Salidas</span>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        C.U. Salida</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Inv. Inicial</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Inv. Actual</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Costo Inv.</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($movimientos as $index => $movimiento)
                                    <tr
                                        class="hover:bg-blue-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $movimiento->fecha->format('d/m/Y') }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $movimiento->fecha->format('H:i') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $movimiento->nombre }}
                                            </div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ Str::limit($movimiento->descripcion ?? 'Sin descripción', 30) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $movimiento->codigo_barras }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($movimiento->unidades_entrada > 0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ number_format($movimiento->unidades_entrada, 0) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            @if ($movimiento->costo_unitario_entrada > 0)
                                                <span
                                                    class="font-medium">${{ number_format($movimiento->costo_unitario_entrada, 2) }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($movimiento->unidades_salida > 0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 7.414V11a1 1 0 102 0V7.414l1.293 1.293a1 1 0 001.414-1.414z"
                                                            clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                                                    </svg>
                                                    {{ number_format($movimiento->unidades_salida, 0) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            @if ($movimiento->costo_unitario_salida > 0)
                                                <span
                                                    class="font-medium">${{ number_format($movimiento->costo_unitario_salida, 2) }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ number_format($movimiento->inventario_inicial, 0) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold 
                                    @if ($movimiento->inventario_actual > 0) bg-blue-100 text-blue-800 
                                    @elseif($movimiento->inventario_actual == 0) bg-yellow-100 text-yellow-800 
                                    @else bg-red-100 text-red-800 @endif">
                                                {{ number_format($movimiento->inventario_actual, 0) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="text-sm font-bold text-green-600">${{ number_format($movimiento->costo_inventario, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ url('/kardex/producto/' . $movimiento->idArticulo) }}"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                                    title="Ver kardex del producto">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Ver
                                                </a>

                                                <!-- Botón adicional para editar (opcional) -->
                                                <button
                                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                                    title="Editar movimiento">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
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
                        {{ $movimientos->appends(request()->input())->links('custom.pagination') }}
                    </div>
                </div>
            @else
                <!-- Estado vacío mejorado -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron movimientos</h3>
                    <p class="text-gray-500 mb-6">No hay movimientos que coincidan con los filtros aplicados.</p>
                    <div class="flex justify-center space-x-4">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Limpiar filtros
                        </button>
                        <button
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Crear movimiento
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <div class="text-gray-600">
                @if ($movimientos->count() > 0)
                    Mostrando {{ $movimientos->firstItem() }} al {{ $movimientos->lastItem() }} de
                    {{ $movimientos->total() }} registros
                @endif
            </div>

            <div class="text-gray-500 text-sm">
                Copyright {{ now()->year }} {{ config('app.name') }} - Todos los Derechos Reservados.
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Script para mejorar la experiencia de usuario
            document.addEventListener('DOMContentLoaded', function() {
                // Establecer fecha máxima como hoy para los date inputs
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('end_date').max = today;
                document.getElementById('start_date').max = today;

                // Validar que la fecha inicial no sea mayor a la final
                const startDate = document.getElementById('start_date');
                const endDate = document.getElementById('end_date');

                startDate.addEventListener('change', function() {
                    endDate.min = this.value;
                });

                endDate.addEventListener('change', function() {
                    startDate.max = this.value;
                });
            });
        </script>
    @endpush
</x-layout.default>
