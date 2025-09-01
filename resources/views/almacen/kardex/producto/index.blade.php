<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">KARDEX POR PRODUCTO</h1>

        <div class="panel flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-6">

            <!-- Descripción -->
            <div class="max-w-2xl">
                <p class="text-gray-600 leading-relaxed">
                    En el módulo <span class="font-semibold text-gray-800">Kardex</span> puedes consultar los
                    <span class="font-medium">movimientos</span> y <span class="font-medium">costos</span> de entradas y
                    salidas de productos.
                    Además, accede a información detallada de los movimientos de un producto por cada mes para una mejor
                    toma de decisiones.
                </p>
            </div>

            <!-- Card del producto -->
            <div class="bg-white shadow rounded-2xl px-5 py-4 w-full md:w-auto">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Producto</h3>

                <div class="flex items-center gap-2">
                    <p class="text-lg font-semibold text-gray-800">{{ $articulo->nombre }}</p>
                    <!-- Badge outline -->
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border border-blue-500 text-blue-600">
                        Activo
                    </span>
                </div>

                <dl class="mt-3 space-y-1 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <dt class="font-medium">Código</dt>
                        <dd>{{ $articulo->codigo_barras ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium">Stock actual</dt>
                        <dd class="text-emerald-600 font-semibold">{{ number_format($articulo->stock_total ?? 0) }}</dd>
                    </div>
                </dl>
            </div>

        </div>


        <div class="panel rounded-xl shadow-lg overflow-hidden mb-8">
            <!-- Título -->
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-lg md:text-xl font-semibold text-gray-800">📊 Kardex General</h2>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3 border text-center">Fecha</th>
                            <th class="px-4 py-3 border text-center">Producto</th>
                            <th class="px-4 py-3 border text-center">U. Entrada</th>
                            <th class="px-4 py-3 border text-center">C.U. Entrada</th>
                            <th class="px-4 py-3 border text-center">U. Salida</th>
                            <th class="px-4 py-3 border text-center">C.U. Salida</th>
                            <th class="px-4 py-3 border text-center">Inventario Inicial</th>
                            <th class="px-4 py-3 border text-center">Inventario Act.</th>
                            <th class="px-4 py-3 border text-center">Costo Inv.</th>
                            <th class="px-4 py-3 border text-center">Det.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($movimientos as $movimiento)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border text-center">{{ $movimiento->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 border text-center font-medium text-gray-800">
                                    {{ $articulo->nombre }}</td>
                                <td class="px-4 py-2 border text-center">
                                    {{ number_format($movimiento->unidades_entrada, 0) }}</td>
                                <td class="px-4 py-2 border text-center">S/
                                    {{ number_format($movimiento->costo_unitario_entrada, 2) }}</td>
                                <td class="px-4 py-2 border text-center">
                                    {{ number_format($movimiento->unidades_salida, 0) }}</td>
                                <td class="px-4 py-2 border text-center">S/
                                    {{ number_format($movimiento->costo_unitario_salida, 2) }}</td>
                                <td class="px-4 py-2 border text-center">
                                    {{ number_format($movimiento->inventario_inicial, 0) }}</td>
                                <td class="px-4 py-2 border text-center font-semibold text-emerald-700">
                                    {{ number_format($movimiento->inventario_actual, 0) }}
                                </td>
                                <td class="px-4 py-2 border text-center">S/
                                    {{ number_format($movimiento->costo_inventario, 2) }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <a href="{{ url('/kardex/producto/' . $articulo->idArticulos . '/detalles/' . $movimiento->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100"
                                        title="Ver detalles">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943
                                      9.542 7c-1.274 4.057-5.064 7-9.542
                                      7S1.732 14.057.458 10zM14 10a4
                                      4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-6 text-gray-500 text-center">
                                    No hay movimientos registrados para este producto
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>



            <!-- Paginación -->
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $movimientos->links() }}
            </div>
        </div>
    </div>
</x-layout.default>
