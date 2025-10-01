<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Kardex por Repuesto</h1>
            <p class="text-gray-600">
                Consulta los movimientos y costos de entradas/salidas de productos con información detallada mensual.
            </p>
        </div>
        
        <!-- Info Card del Producto -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold mb-2">{{ $articulo->nombre }}</h3>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Código: {{ $articulo->codigo_barras }}
                        </span>
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Stock Actual: {{ number_format($articulo->stock_total, 0) }} unidades
                        </span>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Kardex -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Kardex General</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Código</th>
                            <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex flex-col items-end">
                                    <span>Entrada</span>
                                    <span class="text-[10px] text-gray-500 normal-case">Unidades</span>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex flex-col items-end">
                                    <span>C.U.</span>
                                    <span class="text-[10px] text-gray-500 normal-case">Entrada</span>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex flex-col items-end">
                                    <span>Salida</span>
                                    <span class="text-[10px] text-gray-500 normal-case">Unidades</span>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex flex-col items-end">
                                    <span>C.U.</span>
                                    <span class="text-[10px] text-gray-500 normal-case">Salida</span>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex flex-col items-end">
                                    <span>Inv.</span>
                                    <span class="text-[10px] text-gray-500 normal-case">Inicial</span>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex flex-col items-end">
                                    <span>Inv.</span>
                                    <span class="text-[10px] text-gray-500 normal-case">Actual</span>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex flex-col items-end">
                                    <span>Costo</span>
                                    <span class="text-[10px] text-gray-500 normal-case">Inventario</span>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movimiento->fecha->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                {{ $articulo->codigo_repuesto }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-right">
                                @if($movimiento->unidades_entrada > 0)
                                    <span class="text-green-600 font-semibold">{{ number_format($movimiento->unidades_entrada, 0) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-right text-gray-700">
                                @if($movimiento->costo_unitario_entrada > 0)
                                    ${{ number_format($movimiento->costo_unitario_entrada, 2) }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-right">
                                @if($movimiento->unidades_salida > 0)
                                    <span class="text-red-600 font-semibold">{{ number_format($movimiento->unidades_salida, 0) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-right text-gray-700">
                                @if($movimiento->costo_unitario_salida > 0)
                                    ${{ number_format($movimiento->costo_unitario_salida, 2) }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-right text-gray-700">
                                {{ number_format($movimiento->inventario_inicial, 0) }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-right">
                                <span class="font-semibold text-blue-600">{{ number_format($movimiento->inventario_actual, 0) }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                                ${{ number_format($movimiento->costo_inventario, 2) }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-center">
                                <button class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200" title="Ver detalles">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-lg font-medium">No hay movimientos registrados</p>
                                    <p class="text-sm mt-1">Este producto aún no tiene movimientos en el kardex</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación y Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando <span class="font-semibold">{{ $movimientos->firstItem() ?? 0 }}</span> 
                        al <span class="font-semibold">{{ $movimientos->lastItem() ?? 0 }}</span> 
                        de <span class="font-semibold">{{ $movimientos->total() }}</span> registros
                    </div>
                    
                    <div>
                        {{ $movimientos->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layout.default>