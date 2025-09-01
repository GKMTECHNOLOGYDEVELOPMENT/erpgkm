<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">KARDEX GENERAL</h1>
        
        <div class="mb-6">
            <p class="text-gray-700">
                En el módulo KARDEX puede ver todos los movimientos y costos de entradas - salidas de todos los productos. 
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
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Filtrar
                    </button>
                    <a href="{{ route('kardex.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de movimientos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">MOVIMIENTOS GENERALES</h2>
            
            @if($movimientos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-4 border text-left">FECHA</th>
                            <th class="py-3 px-4 border text-left">PRODUCTO</th>
                            <th class="py-3 px-4 border text-left">CÓDIGO</th>
                            <th class="py-3 px-4 border text-center">U. ENTRADA</th>
                            <th class="py-3 px-4 border text-center">C.U. ENTRADA</th>
                            <th class="py-3 px-4 border text-center">U. SALIDA</th>
                            <th class="py-3 px-4 border text-center">C.U. SALIDA</th>
                            <th class="py-3 px-4 border text-center">INV. INICIAL</th>
                            <th class="py-3 px-4 border text-center">INV. ACTUAL</th>
                            <th class="py-3 px-4 border text-center">C. INVENTARIO</th>
                            <th class="py-3 px-4 border text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border">{{ $movimiento->fecha->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border">{{ $movimiento->nombre }}</td>
                            <td class="py-2 px-4 border">{{ $movimiento->codigo_barras }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->unidades_entrada, 0) }}</td>
                            <td class="py-2 px-4 border text-right">${{ number_format($movimiento->costo_unitario_entrada, 2) }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->unidades_salida, 0) }}</td>
                            <td class="py-2 px-4 border text-right">${{ number_format($movimiento->costo_unitario_salida, 2) }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->inventario_inicial, 0) }}</td>
                            <td class="py-2 px-4 border text-right">{{ number_format($movimiento->inventario_actual, 0) }}</td>
                            <td class="py-2 px-4 border text-right">${{ number_format($movimiento->costo_inventario, 2) }}</td>
                            <td class="py-2 px-4 border text-center">
                                <a href="{{ url('/kardex/producto/' . $movimiento->idArticulo) }}" 
                                   class="text-blue-500 hover:text-blue-700 inline-block mr-2"
                                   title="Ver kardex del producto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $movimientos->appends(request()->input())->links() }}
            </div>
            @else
            <div class="py-8 text-center text-gray-500">
                No se encontraron movimientos con los filtros aplicados.
            </div>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <div class="text-gray-600">
                @if($movimientos->count() > 0)
                Mostrando {{ $movimientos->firstItem() }} al {{ $movimientos->lastItem() }} de {{ $movimientos->total() }} registros
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