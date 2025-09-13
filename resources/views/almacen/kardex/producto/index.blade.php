<x-layout.default>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
        <div class="container mx-auto px-4 py-8">
            <!-- Header mejorado -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-primary rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        KARDEX POR PRODUCTO
                    </h1>
                </div>

                <p class="text-gray-600 text-lg max-w-3xl leading-relaxed">
                    Consulta los <span class="font-semibold text-indigo-600">movimientos</span> y
                    <span class="font-semibold text-indigo-600">costos</span> de entradas y salidas de productos
                    con información detallada para una mejor toma de decisiones.
                </p>
            </div>

            <!-- Card del producto rediseñada -->
            <div class="panel shadow-xl rounded-3xl p-6 mb-8 border border-white/20">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                    <!-- Info principal del producto -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <!-- Icono principal -->
                            <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                    </path>
                                </svg>
                            </div>

                            <!-- Texto y estado -->
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">{{ $articulo->nombre }}</h2>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-success text-white">
                                        <span class="w-2 h-2 bg-white rounded-full mr-2 animate-ping"></span>
                                        Activo
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Métricas del producto -->
                    <div class="grid grid-cols-2 gap-4 lg:gap-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-100">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                <span class="text-xs font-medium text-blue-700 uppercase tracking-wide">Código</span>
                            </div>
                            <p class="text-lg font-bold text-blue-900">{{ $articulo->codigo_barras ?? '—' }}</p>
                        </div>

                        <div
                            class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-4 border border-emerald-100">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                    </path>
                                </svg>
                                <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Stock
                                    Actual</span>
                            </div>
                            <p class="text-lg font-bold text-emerald-900">
                                {{ number_format($articulo->stock_total ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla mejorada -->
            <div class="panel backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20">
                <!-- Header de la tabla -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-xl">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-black">Kardex General</h2>
                    </div>
                </div>

                <!-- Tabla responsive mejorada -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-4 text-left text-xs font-bold text-black uppercase tracking-wider border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Fecha
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-4 text-left text-xs font-bold text-black uppercase tracking-wider border-b border-gray-200">
                                    Producto</th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-bold text-black uppercase tracking-wider border-b border-gray-200 bg-green-50">
                                    <div class="text-green-700">Entrada</div>
                                    <div class="grid grid-cols-2 gap-1 mt-1 text-[10px]">
                                        <span>Unidades</span>
                                        <span>C.U.</span>
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-bold text-black uppercase tracking-wider border-b border-gray-200 bg-red-50">
                                    <div class="text-red-700">Salida</div>
                                    <div class="grid grid-cols-2 gap-1 mt-1 text-[10px]">
                                        <span>Unidades</span>
                                        <span>C.U.</span>
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-bold text-black uppercase tracking-wider border-b border-gray-200 bg-blue-50">
                                    <div class="text-blue-700">Inventario</div>
                                    <div class="grid grid-cols-3 gap-1 mt-1 text-[10px]">
                                        <span>Inicial</span>
                                        <span>Actual</span>
                                        <span>Costo</span>
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-bold text-black uppercase tracking-wider border-b border-gray-200">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($movimientos as $index => $movimiento)
                                <tr
                                    class="hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/50 transition-all duration-200 {{ $index % 2 == 0 ? 'bg-gray-50/30' : 'bg-white' }}">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $movimiento->fecha->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-semibold text-gray-800 truncate max-w-xs">
                                            {{ $articulo->nombre }}</div>
                                    </td>
                                    <td class="px-4 py-4 bg-green-50/50">
                                        <div class="grid grid-cols-2 gap-2 text-center">
                                            <div class="bg-green-100 rounded-lg py-1 px-2">
                                                <span
                                                    class="text-sm font-bold text-green-800">{{ number_format($movimiento->unidades_entrada, 0) }}</span>
                                            </div>
                                            <div class="bg-green-100 rounded-lg py-1 px-2">
                                                <span class="text-sm font-bold text-green-800">S/
                                                    {{ number_format($movimiento->costo_unitario_entrada, 2) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 bg-red-50/50">
                                        <div class="grid grid-cols-2 gap-2 text-center">
                                            <div class="bg-red-100 rounded-lg py-1 px-2">
                                                <span
                                                    class="text-sm font-bold text-red-800">{{ number_format($movimiento->unidades_salida, 0) }}</span>
                                            </div>
                                            <div class="bg-red-100 rounded-lg py-1 px-2">
                                                <span class="text-sm font-bold text-red-800">S/
                                                    {{ number_format($movimiento->costo_unitario_salida, 2) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 bg-blue-50/50">
                                        <div class="grid grid-cols-3 gap-2 text-center">
                                            <div class="bg-blue-100 rounded-lg py-1 px-2">
                                                <span
                                                    class="text-sm font-bold text-blue-800">{{ number_format($movimiento->inventario_inicial, 0) }}</span>
                                            </div>
                                            <div class="bg-blue-100 rounded-lg py-1 px-2">
                                                <span
                                                    class="text-sm font-bold text-blue-800">{{ number_format($movimiento->inventario_actual, 0) }}</span>
                                            </div>
                                            <div class="bg-purple-100 rounded-lg py-1 px-2">
                                                <span class="text-sm font-bold text-purple-800">S/
                                                    {{ number_format($movimiento->costo_inventario, 2) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ url('/kardex/producto/' . $articulo->idArticulos . '/detalles/' . $movimiento->id) }}"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary"
                                            title="Ver detalles">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div
                                                class="w-16 h-16 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-700 mb-1">No hay movimientos
                                                </h3>
                                                <p class="text-gray-500">No hay movimientos registrados para este
                                                    producto</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación mejorada -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Mostrando resultados de movimientos del producto
                        </div>
                        <div class="pagination-wrapper">
                            {{ $movimientos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>
