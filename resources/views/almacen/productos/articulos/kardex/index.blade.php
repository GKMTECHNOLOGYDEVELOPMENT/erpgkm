<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">KARDEX POR PRODUCTO</h1>
        
        <div class="flex justify-between items-center mb-6">
            <p>
                En el módulo KARDEX puede ver los movimientos y costos de entradas - salidas de productos. 
                Además, puede ver información detallada de los movimientos específicos de un producto por cada mes.
            </p>
            
            <div class="bg-blue-100 p-3 rounded-lg">
                <h3 class="font-semibold">Producto: {{ $articulo->nombre }}</h3>
                <p>Código: {{ $articulo->codigo_barras }} | Stock Actual: {{ $articulo->stock_total }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">KARDEX GENERAL</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border">FECHA</th>
                            <th class="py-2 px-4 border">PRODUCTO</th>
                            <th class="py-2 px-4 border">U. ENTRADA</th>
                            <th class="py-2 px-4 border">C.U. ENTRADA</th>
                            <th class="py-2 px-4 border">U. SALIDA</th>
                            <th class="py-2 px-4 border">C.U. SALIDA</th>
                            <th class="py-2 px-4 border">INVENTARIO INICIAL</th>
                            <th class="py-2 px-4 border">INVENTARIO AC.</th>
                            <th class="py-2 px-4 border">C. INVENTARIO</th>
                            <th class="py-2 px-4 border">DET.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border text-center">{{ $movimiento->fecha->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border">{{ $articulo->nombre }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->unidades_entrada, 0) }}</td>
                            <td class="py-2 px-4 border text-right">${{ number_format($movimiento->costo_unitario_entrada, 2) }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->unidades_salida, 0) }}</td>
                            <td class="py-2 px-4 border text-right">${{ number_format($movimiento->costo_unitario_salida, 2) }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->inventario_inicial, 0) }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->inventario_actual, 0) }}</td>
                            <td class="py-2 px-4 border text-right">${{ number_format($movimiento->costo_inventario, 2) }}</td>
                            <td class="py-2 px-4 border text-center">
                                <button class="text-blue-500 hover:text-blue-700" title="Ver detalles">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="py-4 text-center text-gray-500">No hay movimientos registrados para este producto</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $movimientos->links() }}
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-gray-600">
                Mostrando {{ $movimientos->firstItem() }} al {{ $movimientos->lastItem() }} de {{ $movimientos->total() }} registros
            </div>
            
            <div class="text-gray-500 text-sm">
                Copyright {{ now()->year }} {{ config('app.name') }} - Todos los Derechos Reservados.
            </div>
        </div>
    </div>
</x-layout.default>