<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @php
        $mostrarPrecios = $cliente_id == 8; // SOLO cliente ID 8 ve precios
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
        <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
            <!-- Header mejorado - Responsive -->
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                    <div class="p-1 sm:p-2 bg-primary rounded-lg sm:rounded-xl">
                        <i class="fas fa-chart-line text-white text-sm sm:text-base"></i>
                    </div>
                    <h1
                        class="text-xl sm:text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        KARDEX POR PRODUCTO - {{ $cliente->descripcion ?? 'Cliente' }}
                    </h1>
                </div>

                <p class="text-gray-600 text-sm sm:text-base md:text-lg max-w-3xl leading-relaxed">
                    Consulta los <span class="font-semibold text-indigo-600">movimientos</span> y
                    @if ($mostrarPrecios)
                        <span class="font-semibold text-indigo-600">costos</span> de entradas y salidas de productos
                    @else
                        <span class="font-semibold text-indigo-600">cantidades</span> de entradas y salidas de productos
                    @endif
                    para el cliente seleccionado.
                </p>
            </div>
            <!-- Botón de volver - Responsive -->
            <div class="mb-4 sm:mt-6 flex justify-end">
                <a href="{{ url()->previous() }}"
                    class="btn btn-danger inline-flex items-center text-sm sm:text-base px-3 py-2 sm:px-4 sm:py-2">
                    <i class="fas fa-arrow-left mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                    Volver Atrás
                </a>
            </div>
            <!-- Card del producto rediseñada - Responsive -->
            <div
                class="panel shadow-lg sm:shadow-xl rounded-2xl sm:rounded-3xl p-4 sm:p-6 mb-6 sm:mb-8 border border-white/20">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-6">
                    <!-- Info principal del producto -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                            <!-- Icono principal -->
                            <div
                                class="w-8 h-8 sm:w-12 sm:h-12 bg-primary rounded-xl sm:rounded-2xl flex items-center justify-center">
                                <i class="fas fa-box text-white text-sm sm:text-base"></i>
                            </div>
                            <!-- Texto y estado -->
                            <div>
                                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800">
                                    {{ $articulo->nombre ?: $articulo->codigo_repuesto }}</h2>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs font-semibold {{ $articulo->estado ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        <span
                                            class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-white rounded-full mr-1 sm:mr-2 {{ $articulo->estado ? 'animate-ping' : '' }}"></span>
                                        {{ $articulo->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Métricas del producto - Responsive -->
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-blue-100">
                            <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                                <i class="fas fa-barcode text-blue-600 text-xs sm:text-sm"></i>
                                <span class="text-xs font-medium text-blue-700 uppercase tracking-wide">Código</span>
                            </div>
                            <p class="text-sm sm:text-lg font-bold text-blue-900 truncate">
                                {{ $articulo->codigo_barras }}</p>
                        </div>

                        <div
                            class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-emerald-100">
                            <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                                <i class="fas fa-boxes text-emerald-600 text-xs sm:text-sm"></i>
                                <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Stock
                                    Actual</span>
                            </div>
                            <p class="text-sm sm:text-lg font-bold text-emerald-900">{{ $articulo->stock_total }}</p>
                        </div>

                        <div
                            class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-purple-100">
                            <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                                <i class="fas fa-user-tie text-purple-600 text-xs sm:text-sm"></i>
                                <span class="text-xs font-medium text-purple-700 uppercase tracking-wide">Cliente</span>
                            </div>
                            <p class="text-sm sm:text-lg font-bold text-purple-900 truncate">
                                {{ $cliente->descripcion ?? 'N/A' }}</p>
                        </div>

                        <div
                            class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-orange-100">
                            <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                                <i class="fas fa-tag text-orange-600 text-xs sm:text-sm"></i>
                                <span class="text-xs font-medium text-orange-700 uppercase tracking-wide">SKU</span>
                            </div>
                            <p class="text-sm sm:text-lg font-bold text-orange-900">{{ $articulo->sku ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla mejorada - Responsive -->
            <div
                class="panel backdrop-blur-sm rounded-2xl sm:rounded-3xl shadow-lg sm:shadow-xl overflow-hidden border border-white/20">
                <!-- Header de la tabla -->
                <div class="px-4 sm:px-6 py-3 sm:py-5">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="p-1 sm:p-2 bg-white/10 rounded-lg sm:rounded-xl">
                            <i class="fas fa-history text-primary text-sm sm:text-base"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-primary">MOVIMIENTOS DEL KARDEX</h2>
                    </div>
                </div>

                <!-- Tabla responsive mejorada -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                            <tr>
                                <th
                                    class="px-2 sm:px-4 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    <div class="flex items-center gap-1 sm:gap-2">
                                        <i class="fas fa-calendar-alt text-gray-600 text-xs"></i>
                                        Fecha
                                    </div>
                                </th>
                                <th
                                    class="px-2 sm:px-4 py-3 sm:py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-green-50">
                                    <div class="text-green-700">Entrada</div>
                                    <div
                                        class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 mt-1 text-[10px]">
                                        <span>Unidades</span>
                                        @if ($mostrarPrecios)
                                            <span>C.U.</span>
                                        @endif
                                    </div>
                                </th>
                                <th
                                    class="px-2 sm:px-4 py-3 sm:py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-red-50">
                                    <div class="text-red-700">Salida</div>
                                    <div
                                        class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 mt-1 text-[10px]">
                                        <span>Unidades</span>
                                        @if ($mostrarPrecios)
                                            <span>C.U.</span>
                                        @endif
                                    </div>
                                </th>
                                <th
                                    class="px-2 sm:px-4 py-3 sm:py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-blue-50">
                                    <div class="text-blue-700">Inventario</div>
                                    <div
                                        class="grid {{ $mostrarPrecios ? 'grid-cols-3' : 'grid-cols-2' }} gap-1 mt-1 text-[10px]">
                                        <span>Inicial</span>
                                        <span>Actual</span>
                                        @if ($mostrarPrecios)
                                            <span>Costo</span>
                                        @endif
                                    </div>
                                </th>

                                <th
                                    class="px-2 sm:px-4 py-3 sm:py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-gray-50">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($movimientos as $movimiento)
                                <tr
                                    class="hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/50 transition-all duration-200 bg-gray-50/30">
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1 sm:gap-2">
                                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-blue-500 rounded-full"></div>
                                            <span
                                                class="text-xs sm:text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</span>
                                        </div>
                                    </td>

                                    <!-- COLUMNA ENTRADA -->
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 bg-green-50/50">
                                        <div
                                            class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 sm:gap-2 text-center">
                                            <div class="bg-green-100 rounded py-1 px-1 sm:px-2">
                                                <span
                                                    class="text-xs sm:text-sm font-bold text-green-800">{{ $movimiento->unidades_entrada ?? 0 }}</span>
                                            </div>
                                            @if ($mostrarPrecios)
                                                <div class="bg-green-100 rounded py-1 px-1 sm:px-2">
                                                    <span class="text-xs sm:text-sm font-bold text-green-800">S/
                                                        {{ number_format($movimiento->costo_unitario_entrada ?? 0, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- COLUMNA SALIDA -->
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 bg-red-50/50">
                                        <div
                                            class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 sm:gap-2 text-center">
                                            <div class="bg-red-100 rounded py-1 px-1 sm:px-2">
                                                <span
                                                    class="text-xs sm:text-sm font-bold text-red-800">{{ $movimiento->unidades_salida ?? 0 }}</span>
                                            </div>
                                            @if ($mostrarPrecios)
                                                <div class="bg-red-100 rounded py-1 px-1 sm:px-2">
                                                    <span class="text-xs sm:text-sm font-bold text-red-800">S/
                                                        {{ number_format($movimiento->costo_unitario_salida ?? 0, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- COLUMNA INVENTARIO -->
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 bg-blue-50/50">
                                        <div
                                            class="grid {{ $mostrarPrecios ? 'grid-cols-3' : 'grid-cols-2' }} gap-1 sm:gap-2 text-center">
                                            <div class="bg-blue-100 rounded py-1 px-1 sm:px-2">
                                                <span
                                                    class="text-xs sm:text-sm font-bold text-blue-800">{{ $movimiento->inventario_inicial ?? 0 }}</span>
                                            </div>
                                            <div class="bg-blue-100 rounded py-1 px-1 sm:px-2">
                                                <span
                                                    class="text-xs sm:text-sm font-bold text-blue-800">{{ $movimiento->inventario_actual ?? 0 }}</span>
                                            </div>
                                            @if ($mostrarPrecios)
                                                <div class="bg-purple-100 rounded py-1 px-1 sm:px-2">
                                                    <span class="text-xs sm:text-sm font-bold text-purple-800">S/
                                                        {{ number_format($movimiento->costo_inventario ?? 0, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-2 sm:px-4 py-3 sm:py-4 text-center">
                                        <div class="flex justify-center">
                                            @if (\App\Helpers\PermisoHelper::tienePermiso('VER MOVIMIENTOS KARDEX'))
                                                <a href="{{ route('kardex.detalle-movimientos', $movimiento->id) }}"
                                                    class="btn btn-sm btn-primary flex items-center gap-1 sm:gap-2 text-xs sm:text-sm px-2 py-1 sm:px-3 sm:py-2">
                                                    <i class="fas fa-eye text-xs sm:text-sm"></i>
                                                    <span class="hidden sm:inline">Ver Movimientos</span>
                                                    <span class="sm:hidden">Ver</span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 sm:py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-gray-300 text-3xl sm:text-4xl mb-2 sm:mb-4"></i>
                                            <p class="text-base sm:text-lg font-medium">No hay movimientos registrados
                                            </p>
                                            <p class="text-xs sm:text-sm">No se encontraron movimientos para este
                                                producto y cliente</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación mejorada - Solo Tailwind CSS -->
                @if ($movimientos->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50/50">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-0">
                            <div class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-0">
                                <i class="fas fa-list-ol mr-1"></i>
                                Mostrando <span class="font-semibold">{{ $movimientos->firstItem() }}</span> -
                                <span class="font-semibold">{{ $movimientos->lastItem() }}</span> de
                                <span class="font-semibold">{{ $movimientos->total() }}</span> resultados
                            </div>
                            
                            <!-- Paginación personalizada con Tailwind -->
                            <nav class="flex items-center space-x-1">
                                <!-- Primera página -->
                                <a href="{{ $movimientos->url(1) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-blue-50 hover:border-blue-500 hover:text-blue-700 transition-all duration-200 {{ $movimientos->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                    <i class="fas fa-angle-double-left text-xs sm:text-sm"></i>
                                </a>

                                <!-- Página anterior -->
                                <a href="{{ $movimientos->previousPageUrl() }}"
                                    class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-blue-50 hover:border-blue-500 hover:text-blue-700 transition-all duration-200 {{ $movimientos->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                    <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
                                </a>

                                <!-- Números de página -->
                                @php
                                    $current = $movimientos->currentPage();
                                    $last = $movimientos->lastPage();
                                    $start = max(1, $current - 2);
                                    $end = min($last, $current + 2);
                                    
                                    // Asegurar que siempre se muestren 5 páginas si es posible
                                    if ($end - $start < 4) {
                                        if ($start == 1) {
                                            $end = min($last, $start + 4);
                                        } elseif ($end == $last) {
                                            $start = max(1, $end - 4);
                                        }
                                    }
                                @endphp

                                <!-- Mostrar primera página si no está en el rango -->
                                @if ($start > 1)
                                    <a href="{{ $movimientos->url(1) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-500 hover:text-primary transition-all duration-200">
                                        1
                                    </a>
                                    @if ($start > 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-gray-400">
                                            ...
                                        </span>
                                    @endif
                                @endif

                                <!-- Páginas del rango -->
                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $movimientos->currentPage())
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-primary border border-primary text-white font-semibold transition-all duration-200">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $movimientos->url($page) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-500 hover:text-primary transition-all duration-200">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                <!-- Mostrar última página si no está en el rango -->
                                @if ($end < $last)
                                    @if ($end < $last - 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-gray-400">
                                            ...
                                        </span>
                                    @endif
                                    <a href="{{ $movimientos->url($last) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-500 hover:text-primary transition-all duration-200">
                                        {{ $last }}
                                    </a>
                                @endif

                                <!-- Página siguiente -->
                                <a href="{{ $movimientos->nextPageUrl() }}"
                                    class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-blue-50 hover:border-blue-500 hover:text-blue-700 transition-all duration-200 {{ !$movimientos->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                    <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                                </a>

                                <!-- Última página -->
                                <a href="{{ $movimientos->url($movimientos->lastPage()) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-blue-50 hover:border-blue-500 hover:text-blue-700 transition-all duration-200 {{ !$movimientos->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                    <i class="fas fa-angle-double-right text-xs sm:text-sm"></i>
                                </a>
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout.default>