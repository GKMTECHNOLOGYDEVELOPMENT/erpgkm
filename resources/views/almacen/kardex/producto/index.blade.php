<x-layout.default>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 py-6">
        <div class="container mx-auto px-4">
            <!-- Header mejorado -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-gradient-to-r from-primary to-primary/80 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                            KARDEX POR PRODUCTO
                        </h1>
                        <div class="w-20 h-1 bg-gradient-to-r from-primary to-primary/60 rounded-full mt-2"></div>
                    </div>
                </div>

                <p class="text-gray-600 text-lg max-w-3xl leading-relaxed bg-white/50 rounded-2xl p-4 shadow-sm">
                    Consulta los <span class="font-semibold text-primary">movimientos</span> y
                    <span class="font-semibold text-primary">costos</span> de entradas y salidas de productos
                    con información detallada para una mejor toma de decisiones.
                </p>
            </div>

            <!-- Card del producto rediseñada -->
            <div class="panel rounded-3xl shadow-2xl p-8 mb-8 border border-white/50 backdrop-blur-sm">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <!-- Info principal del producto -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <!-- Icono principal -->
                            <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary/80 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                    </path>
                                </svg>
                            </div>

                            <!-- Texto y estado -->
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $articulo->nombre }}</h2>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-success to-success/80 text-white shadow-sm">
                                        <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                        Activo
                                    </span>
                                    <span class="text-sm text-gray-500">• Última actualización: {{ now()->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Métricas del producto -->
                    <div class="grid grid-cols-2 gap-4 lg:gap-6 min-w-max">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-5 border border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Código</span>
                            </div>
                            <p class="text-xl font-bold text-blue-900">{{ $articulo->codigo_barras ?? '—' }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-5 border border-emerald-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Stock Actual</span>
                            </div>
                            <p class="text-xl font-bold text-emerald-900">
                                {{ number_format($articulo->stock_total ?? 0) }} unidades
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla mejorada -->
            <div class="panel rounded-3xl shadow-2xl overflow-hidden border border-white/50">
                <!-- Header de la tabla -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Kardex General</h2>
                                <p class="text-slate-300 text-sm mt-1">Historial completo de movimientos del producto</p>
                            </div>
                        </div>
                        <div class="text-white/70">
                            <span class="text-sm">Total: {{ $movimientos->count() }} movimientos</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla responsive mejorada -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Fecha
                                    </div>
                                </th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                                    Producto
                                </th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200 bg-green-50/80">
                                    <div class="text-green-700 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Entrada
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-2 text-[11px] font-medium">
                                        <span class="text-green-600">Unidades</span>
                                        <span class="text-green-600">Costo U.</span>
                                    </div>
                                </th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200 bg-red-50/80">
                                    <div class="text-red-700 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                        Salida
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-2 text-[11px] font-medium">
                                        <span class="text-red-600">Unidades</span>
                                        <span class="text-red-600">Costo U.</span>
                                    </div>
                                </th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200 bg-blue-50/80">
                                    <div class="text-blue-700 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Inventario
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mt-2 text-[11px] font-medium">
                                        <span class="text-blue-600">Inicial</span>
                                        <span class="text-blue-600">Actual</span>
                                        <span class="text-purple-600">Costo</span>
                                    </div>
                                </th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($movimientos as $index => $movimiento)
                                <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/30 transition-all duration-200 group {{ $index % 2 == 0 ? 'bg-slate-50/30' : 'bg-white' }}">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-3 h-3 bg-primary rounded-full group-hover:scale-150 transition-transform"></div>
                                            <div>
                                                <span class="text-sm font-semibold text-slate-900">{{ $movimiento->fecha->format('d/m/Y') }}</span>
                                                <div class="text-xs text-slate-500">{{ $movimiento->fecha->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-semibold text-slate-800 truncate max-w-xs">{{ $articulo->nombre }}</div>
                                        <div class="text-xs text-slate-500 mt-1">Ref: {{ $articulo->codigo_barras ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-5 bg-green-50/30">
                                        <div class="grid grid-cols-2 gap-3 text-center">
                                            <div class="bg-green-100 rounded-xl py-2 px-3 group-hover:bg-green-200 transition-colors">
                                                <span class="text-sm font-bold text-green-900">{{ number_format($movimiento->unidades_entrada, 0) }}</span>
                                            </div>
                                            <div class="bg-green-100 rounded-xl py-2 px-3 group-hover:bg-green-200 transition-colors">
                                                <span class="text-sm font-bold text-green-900">S/ {{ number_format($movimiento->costo_unitario_entrada, 2) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 bg-red-50/30">
                                        <div class="grid grid-cols-2 gap-3 text-center">
                                            <div class="bg-red-100 rounded-xl py-2 px-3 group-hover:bg-red-200 transition-colors">
                                                <span class="text-sm font-bold text-red-900">{{ number_format($movimiento->unidades_salida, 0) }}</span>
                                            </div>
                                            <div class="bg-red-100 rounded-xl py-2 px-3 group-hover:bg-red-200 transition-colors">
                                                <span class="text-sm font-bold text-red-900">S/ {{ number_format($movimiento->costo_unitario_salida, 2) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 bg-blue-50/30">
                                        <div class="grid grid-cols-3 gap-3 text-center">
                                            <div class="bg-blue-100 rounded-xl py-2 px-3 group-hover:bg-blue-200 transition-colors">
                                                <span class="text-sm font-bold text-blue-900">{{ number_format($movimiento->inventario_inicial, 0) }}</span>
                                            </div>
                                            <div class="bg-blue-100 rounded-xl py-2 px-3 group-hover:bg-blue-200 transition-colors">
                                                <span class="text-sm font-bold text-blue-900">{{ number_format($movimiento->inventario_actual, 0) }}</span>
                                            </div>
                                            <div class="bg-purple-100 rounded-xl py-2 px-3 group-hover:bg-purple-200 transition-colors">
                                                <span class="text-sm font-bold text-purple-900">S/ {{ number_format($movimiento->costo_inventario, 2) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ url('/kardex/producto/' . $articulo->idArticulos . '/detalles/' . $movimiento->id) }}"
                                            class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary transition-all duration-200 shadow-sm hover:shadow-md group/btn"
                                            title="Ver detalles">
                                            <svg class="w-5 h-5 text-white group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-4 text-slate-400">
                                            <div class="w-20 h-20 rounded-full flex items-center justify-center bg-slate-100">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-700 mb-2">No hay movimientos registrados</h3>
                                                <p class="text-slate-500">No se encontraron movimientos para este producto</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación mejorada -->
                <div class="px-8 py-6 border-t border-slate-200 bg-slate-50/50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-600">
                            Mostrando <span class="font-semibold">{{ $movimientos->count() }}</span> de <span class="font-semibold">{{ $movimientos->total() }}</span> movimientos
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