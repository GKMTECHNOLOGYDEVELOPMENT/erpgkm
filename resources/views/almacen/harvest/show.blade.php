@if (isset($articulo))
    <div class="bg-white rounded-lg">

        <!-- Contenido principal -->
        <div class="p-6">
            <!-- Cards de información - Diseño sencillo y elegante -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Card de información básica -->
                <div
                    class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow transition-all duration-200">
                    <!-- Encabezado -->
                    <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-50 rounded-lg mr-3">
                                    <i class="fas fa-box text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Información del Repuesto</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Detalles principales</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full">
                                ID: {{ $articulo->idArticulos ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-5 space-y-4">
                        <!-- Código -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-1.5 bg-blue-50 rounded mr-3">
                                    <i class="fas fa-barcode text-blue-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Código</p>
                                </div>
                            </div>
                            <p class="font-mono font-semibold text-blue-700">{{ $articulo->codigo_repuesto }}</p>
                        </div>

                        <!-- Subcategoría -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-1.5 bg-gray-50 rounded mr-3">
                                    <i class="fas fa-tags text-gray-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Subcategoría</p>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-gray-800">
                                {{ $articulo->subcategoria->nombre ?? 'N/A' }}
                            </span>
                        </div>

                        <!-- Stock -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-1.5 bg-green-50 rounded mr-3">
                                    <i class="fas fa-boxes text-green-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Stock General</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-semibold text-gray-800">{{ $articulo->stock_total ?? 0 }}
                                    unidades</span>
                                @php
                                    $stock = $articulo->stock_total ?? 0;
                                    if ($stock == 0) {
                                        $statusClass = 'bg-red-100 text-red-700';
                                        $statusIcon = 'fa-times-circle';
                                    } elseif ($stock <= 5) {
                                        $statusClass = 'bg-amber-100 text-amber-700';
                                        $statusIcon = 'fa-exclamation-triangle';
                                    } elseif ($stock <= 15) {
                                        $statusClass = 'bg-blue-100 text-blue-700';
                                        $statusIcon = 'fa-info-circle';
                                    } else {
                                        $statusClass = 'bg-green-100 text-green-700';
                                        $statusIcon = 'fa-check-circle';
                                    }
                                @endphp
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }} mr-1 text-xs"></i>
                                    @if ($stock == 0)
                                        Sin stock
                                    @elseif($stock <= 5)
                                        Bajo
                                    @elseif($stock <= 15)
                                        Moderado
                                    @else
                                        Suficiente
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de estadísticas -->
                <div
                    class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow transition-all duration-200">
                    <!-- Encabezado -->
                    <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="p-2 bg-amber-50 rounded-lg mr-3">
                                <i class="fas fa-chart-bar text-amber-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Estadísticas de Retiros</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Resumen de movimientos</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-5">
                        <div class="text-center mb-5">
                            <div class="text-4xl font-bold text-gray-800 mb-1">{{ $totalRetirado }}</div>
                            <p class="text-sm text-gray-500">Total unidades retiradas</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-lg font-semibold text-gray-800 mb-1">{{ $retiros->total() }}</div>
                                <p class="text-xs text-gray-500">Retiros registrados</p>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-lg font-semibold text-gray-800 mb-1">{{ $retiros->perPage() }}</div>
                                <p class="text-xs text-gray-500">Por página</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modelos compatibles -->
            @if ($modelos && $modelos->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        Modelos Compatibles
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($modelos as $modelo)
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $modelo->nombre }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Historial de retiros -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Historial de Retiros
                    </h3>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Custodia</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cliente</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Responsable</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($retiros as $retiro)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $retiro->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $retiro->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($retiro->custodia)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $retiro->custodia->codigocustodias }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-900">
                                                {{ $retiro->custodia->cliente->nombre ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $retiro->cantidad_retirada }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $retiro->responsable->Nombre ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-600">
                                                @if ($retiro->observaciones)
                                                    {{ Str::limit($retiro->observaciones, 50) }}
                                                    @if (strlen($retiro->observaciones) > 50)
                                                        <button
                                                            onclick="alert('{{ addslashes($retiro->observaciones) }}')"
                                                            class="text-blue-600 hover:text-blue-800 ml-1 text-xs">
                                                            Ver más
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">Sin observaciones</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <p>No hay registros de retiros</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if ($retiros->hasPages())
                        <div class="px-4 py-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Mostrando {{ $retiros->firstItem() }} a {{ $retiros->lastItem() }} de
                                    {{ $retiros->total() }} resultados
                                </div>
                                <div class="flex space-x-2">
                                    {{ $retiros->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function closeModal() {
            const modal = document.getElementById('detalleModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
    </script>
@else
    <div class="p-8 text-center">
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 max-w-md mx-auto">
            <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                </path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Repuesto no encontrado</h3>
            <p class="text-gray-600">El repuesto solicitado no existe o ha sido eliminado.</p>
            <button onclick="closeModal()"
                class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                Cerrar
            </button>
        </div>
    </div>
@endif
