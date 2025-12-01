<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @php
        // Funciones auxiliares para manejar monedas
        function getResumenMoneda($solicitud)
        {
            if ($solicitud->detalles->isEmpty()) {
                return 'S/';
            }

            $currencyCount = [];
            foreach ($solicitud->detalles as $detalle) {
                if ($detalle->moneda) {
                    $currencyId = $detalle->moneda->idMonedas;
                    $currencyCount[$currencyId] = ($currencyCount[$currencyId] ?? 0) + 1;
                }
            }

            if (empty($currencyCount)) {
                return 'S/';
            }

            $mostCommonCurrency = array_keys($currencyCount)[0];
            foreach ($solicitud->detalles as $detalle) {
                if ($detalle->moneda && $detalle->moneda->idMonedas == $mostCommonCurrency) {
                    return $detalle->moneda->simbolo ?? 'S/';
                }
            }

            return 'S/';
        }

        function hasMultipleCurrencies($solicitud)
        {
            $currencies = [];
            foreach ($solicitud->detalles as $detalle) {
                if ($detalle->moneda) {
                    $currencyId = $detalle->moneda->idMonedas;
                    if (!in_array($currencyId, $currencies)) {
                        $currencies[] = $currencyId;
                    }
                }
            }
            return count($currencies) > 1;
        }

        function getMonedasUtilizadas($solicitud)
        {
            $currencies = [];
            foreach ($solicitud->detalles as $detalle) {
                if ($detalle->moneda && !in_array($detalle->moneda->nombre, $currencies)) {
                    $currencies[] = $detalle->moneda->nombre;
                }
            }
            return implode(', ', $currencies);
        }

        // Calcular valores una sola vez
        $monedaResumen = getResumenMoneda($solicitud);
        $multipleMonedas = hasMultipleCurrencies($solicitud);
        $monedasUtilizadas = getMonedasUtilizadas($solicitud);
    @endphp

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li><a href="{{ route('solicitudcompra.index') }}" class="text-primary hover:underline">Solicitud
                        Compra</a></li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Ver Solicitud Compra</span></li>
            </ul>
        </div>

        <!-- Header -->
        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="mb-4 lg:mb-0">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-500 rounded-lg shadow-sm">
                        <i class="fas fa-file-invoice-dollar text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">Detalles de Solicitud</h1>
                        <div class="flex items-center space-x-2 mt-1">
                            <p class="text-sm text-gray-600">Código:</p>
                            <span class="text-sm font-semibold text-blue-600">{{ $solicitud->codigo_solicitud }}</span>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                        @if ($solicitud->estado == 'pendiente') bg-yellow-100 text-yellow-800
                        @elseif($solicitud->estado == 'aprobada') bg-green-100 text-green-800
                        @elseif($solicitud->estado == 'rechazada') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                                {{ $solicitud->estado_texto }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('solicitudcompra.index') }}"
                    class="inline-flex items-center px-3 py-2 bg-gray-500 text-white rounded-lg text-sm font-medium hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-gray-500 transition-colors duration-150">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i>
                    Volver
                </a>
                @if ($solicitud->estado == 'pendiente')
                    <a href="{{ route('solicitudcompra.edit', $solicitud->idSolicitudCompra) }}"
                        class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                        <i class="fas fa-edit mr-1.5 text-xs"></i>
                        Editar
                    </a>
                @endif
            </div>
        </div>

        <!-- Información Principal Mejorada -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Información Básica -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Header con icono -->
                    <div class="px-5 py-4 border-b border-gray-100 bg-primary-light">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Información de la Solicitud</h3>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase
    @if ($solicitud->estado == 'pendiente') bg-warning text-white
    @elseif($solicitud->estado == 'aprobada') bg-success text-white
    @elseif($solicitud->estado == 'rechazada') bg-danger text-white
    @elseif($solicitud->estado == 'en_proceso') bg-primary text-white
    @elseif($solicitud->estado == 'completada') bg-secondary text-white
    @elseif($solicitud->estado == 'cancelada') bg-dark text-white
    @elseif($solicitud->estado == 'presupuesto_aprobado') bg-warning-light text-white
    @elseif($solicitud->estado == 'pagado') bg-info text-white
    @elseif($solicitud->estado == 'finalizado') bg-[#888ea8] text-white
    @else bg-dark text-white @endif">
                                <i class="fas fa-circle mr-1.5 text-[6px]"></i>
                                {{ $solicitud->estado }}
                            </span>
                        </div>
                    </div>
                    <!-- Contenido -->
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Fila 1 -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-hashtag text-blue-500 text-sm"></i>
                                    <label
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Código</label>
                                </div>
                                <p class="text-base font-bold text-gray-900">{{ $solicitud->codigo_solicitud }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-user-tie text-green-500 text-sm"></i>
                                    <label
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Solicitante
                                        Compra</label>
                                </div>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $solicitud->solicitante_compra ?: 'Sin usuario asignado' }}
                                </p>
                            </div>

                            <!-- Fila 2 -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-warehouse text-warning text-sm"></i>
                                    <label
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Solicitante
                                        Almacén</label>
                                </div>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $solicitud->solicitante_almacen ?: 'Sin usuario asignado' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-building text-purple-500 text-sm"></i>
                                    <label
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Área</label>
                                </div>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $solicitud->tipoArea->nombre ?? 'N/A' }}</p>
                            </div>

                            <!-- Fila 3 -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-flag text-danger text-sm"></i>
                                    <label
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Prioridad</label>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold 
        @if (($solicitud->prioridad->nivel ?? 'medium') == 'high') bg-success text-white
        @elseif(($solicitud->prioridad->nivel ?? 'medium') == 'medium') bg-warning text-white
        @else bg-danger text-white @endif">
                                    <i class="fas fa-flag mr-1.5 text-xs"></i>
                                    {{ $solicitud->prioridad->nombre ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-calendar-day text-indigo-500 text-sm"></i>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Fecha
                                        Requerida</label>
                                </div>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $solicitud->fecha_requerida ? \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>

                            <!-- Fila 4 -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-money-bill-wave text-success text-sm"></i>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Centro de
                                        Costo</label>
                                </div>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $solicitud->centroCosto->nombre ?? 'N/A' }}</p>
                            </div>

                            <!-- Proyecto Asociado -->
                            <div class="md:col-span-2 bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-project-diagram text-blue-600 text-sm"></i>
                                    <label class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Proyecto
                                        Asociado</label>
                                </div>
                                <p class="text-base font-bold text-blue-900">
                                    {{ $solicitud->proyecto_asociado ?? 'No especificado' }}</p>
                            </div>

                            <!-- Solicitud Almacén Relacionada -->
                            @if ($solicitud->solicitudAlmacen)
                                <div class="md:col-span-2 bg-orange-50 rounded-lg p-4 border border-warning">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <i class="fas fa-link text-orange-600 text-sm"></i>
                                        <label
                                            class="text-xs font-semibold text-orange-600 uppercase tracking-wide">Solicitud
                                            de Almacén Relacionada</label>
                                    </div>
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                                        <span
                                            class="text-sm font-mono bg-white px-2 py-1 rounded border text-center sm:text-left">{{ $solicitud->solicitudAlmacen->codigo_solicitud }}</span>
                                        <span class="text-base font-semibold text-orange-900 text-center sm:text-left">-
                                            {{ $solicitud->solicitudAlmacen->titulo }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen Financiero y Aprobación -->
            <div class="space-y-5">
                <!-- Resumen Financiero -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-green-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-bar text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Resumen Financiero</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-receipt text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">Subtotal</span>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-900">{{ $monedaResumen }}{{ number_format($solicitud->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-percentage text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">IGV (18%)</span>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-900">{{ $monedaResumen }}{{ number_format($solicitud->iva, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-3 border-t border-gray-200 bg-gray-50 -mx-5 px-5">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calculator text-blue-500 text-sm"></i>
                                    <span class="text-base font-bold text-gray-900">Total</span>
                                </div>
                                <span
                                    class="text-lg font-bold text-blue-600">{{ $monedaResumen }}{{ number_format($solicitud->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-cubes text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">Total Unidades</span>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-900">{{ $solicitud->total_unidades }}</span>
                            </div>
                            @if ($multipleMonedas)
                                <div class="flex justify-between items-center py-2 border-t border-gray-200 pt-3">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-coins text-yellow-500 text-sm"></i>
                                        <span class="text-sm text-gray-600">Monedas</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $monedasUtilizadas }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Información de Aprobación -->
                @if ($solicitud->estado == 'aprobada' || $solicitud->estado == 'rechazada')
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-check text-white text-sm"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Información de Aprobación</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <i class="fas fa-flag text-gray-500 text-sm"></i>
                                        <label
                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado
                                            Final</label>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold 
                            @if ($solicitud->estado == 'aprobada') bg-emerald-100 text-emerald-800 border border-emerald-200
                            @else bg-red-100 text-red-800 border border-red-200 @endif">
                                        <i
                                            class="fas fa-{{ $solicitud->estado == 'aprobada' ? 'check' : 'times' }}-circle mr-1.5 text-xs"></i>
                                        {{ $solicitud->estado_texto }}
                                    </span>
                                </div>

                                @if ($solicitud->fecha_aprobacion)
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <i class="fas fa-calendar-check text-gray-500 text-sm"></i>
                                            <label
                                                class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Fecha
                                                Aprobación</label>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                @endif

                                @if ($solicitud->motivo_rechazo)
                                    <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <i class="fas fa-exclamation-triangle text-red-500 text-sm"></i>
                                            <label
                                                class="text-xs font-semibold text-red-600 uppercase tracking-wide">Motivo
                                                de Rechazo</label>
                                        </div>
                                        <p class="text-sm text-red-700 font-medium">{{ $solicitud->motivo_rechazo }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Justificación -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-warning-light">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-warning rounded-lg flex items-center justify-center">
                                <i class="fas fa-comment-alt text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Justificación</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="bg-warning-light rounded-lg p-4 border border-warning">
                            <p class="text-gray-700 leading-relaxed text-sm md:text-base">
                                {{ $solicitud->justificacion }}</p>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                @if ($solicitud->observaciones)
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-secondary-light
">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-secondary rounded-lg flex items-center justify-center">
                                    <i class="fas fa-sticky-note text-white text-sm"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Observaciones</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="bg-secondary-light
 rounded-lg p-4 border border-secondary">
                                <p class="text-gray-700 leading-relaxed text-sm md:text-base">
                                    {{ $solicitud->observaciones }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detalles de Productos Mejorado -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mb-6">
            <!-- Header con icono -->
            <div class="px-5 py-4 border-b border-gray-100 bg-primary-light">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Productos Solicitados</h3>
                            <p class="text-sm text-gray-600">Detalle completo de los productos</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span
                            class="inline-flex items-center px-3 py-1.5 bg-primary text-white rounded-full text-xs font-bold">
                            <i class="fas fa-cube mr-1.5 text-xs"></i>
                            {{ $estadisticas['total_productos'] }} productos
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1.5 bg-success text-white rounded-full text-xs font-bold">
                            <i class="fas fa-calculator mr-1.5 text-xs"></i>
                            {{ $monedaResumen }}{{ number_format($solicitud->detalles->sum('total_producto'), 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-box text-gray-400 text-xs"></i>
                                    <span>Producto</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-tag text-gray-400 text-xs"></i>
                                    <span>Categoría</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-hashtag text-gray-400 text-xs"></i>
                                    <span>Cantidad</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-ruler text-gray-400 text-xs"></i>
                                    <span>Unidad</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-money-bill-wave text-gray-400 text-xs"></i>
                                    <span>Precio Unit.</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-coins text-gray-400 text-xs"></i>
                                    <span>Moneda</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-calculator text-gray-400 text-xs"></i>
                                    <span>Total</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-flag text-gray-400 text-xs"></i>
                                    <span>Estado</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" x-data="{ expanded: [] }">
                        @foreach ($solicitud->detalles as $index => $detalle)
                            <tr class="hover:bg-blue-50 transition-colors duration-150 group">
                                <td class="px-4 py-4">
                                    <div class="min-w-0">
                                        <div
                                            class="text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                            {{ $detalle->descripcion_producto }}
                                        </div>
                                        <div class="flex flex-col space-y-1 mt-1">
                                            @if ($detalle->codigo_producto)
                                                <div class="flex items-center space-x-1">
                                                    <i class="fas fa-barcode text-gray-400 text-xs"></i>
                                                    <span class="text-xs text-gray-500">Código:
                                                        {{ $detalle->codigo_producto }}</span>
                                                </div>
                                            @endif
                                            @if ($detalle->marca)
                                                <div class="flex items-center space-x-1">
                                                    <i class="fas fa-trademark text-gray-400 text-xs"></i>
                                                    <span class="text-xs text-gray-500">Marca:
                                                        {{ $detalle->marca }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                                        {{ $detalle->categoria ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-bold text-gray-900 bg-blue-100 px-2 py-1 rounded">
                                            {{ $detalle->cantidad }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-600 font-medium">
                                        {{ $detalle->unidad ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->precio_unitario_estimado, 2) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-info text-white">
                                        <i class="fas fa-coins mr-1 text-[10px]"></i>
                                        {{ $detalle->moneda->nombre ?? 'PEN' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-bold text-success">
                                        {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->total_producto, 2) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase 
                            @if ($detalle->estado == 'aprobado') bg-success text-white
                            @elseif($detalle->estado == 'rechazado') bg-danger text-white
                            @else bg-warning text-white @endif">
                                        <i class="fas fa-circle mr-1.5 text-[6px]"></i>
                                        {{ $detalle->estado }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if (
                                        $detalle->justificacion_producto ||
                                            $detalle->especificaciones_tecnicas ||
                                            $detalle->proveedor_sugerido ||
                                            $detalle->observaciones_detalle)
                                        <button
                                            @click="expanded[{{ $index }}] = !expanded[{{ $index }}]"
                                            class="inline-flex items-center px-3 py-1.5 bg-primary text-white rounded-lg text-xs font-medium hover:bg-primary-dark transition-colors duration-200"
                                            :class="expanded[{{ $index }}] ? 'bg-secondary' : 'bg-primary'">
                                            <i class="fas"
                                                :class="expanded[{{ $index }}] ? 'fa-eye-slash' : 'fa-eye'"
                                                class="mr-1.5"></i>
                                            <span
                                                x-text="expanded[{{ $index }}] ? 'Ocultar' : 'Ver más'"></span>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">Sin info adicional</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Fila expandible con información adicional -->
                            @if (
                                $detalle->justificacion_producto ||
                                    $detalle->especificaciones_tecnicas ||
                                    $detalle->proveedor_sugerido ||
                                    $detalle->observaciones_detalle)
                                <tr x-show="expanded[{{ $index }}]"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2">
                                    <td colspan="9" class="px-4 py-4 bg-blue-50 border-t border-blue-200">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            @if ($detalle->justificacion_producto)
                                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <i class="fas fa-comment text-warning text-sm"></i>
                                                        <h4 class="text-sm font-bold text-gray-800">Justificación</h4>
                                                    </div>
                                                    <p class="text-xs text-gray-600 leading-relaxed line-clamp-3 hover:line-clamp-none transition-all cursor-help"
                                                        title="{{ $detalle->justificacion_producto }}">
                                                        {{ $detalle->justificacion_producto }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($detalle->especificaciones_tecnicas)
                                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <i class="fas fa-list-alt text-primary text-sm"></i>
                                                        <h4 class="text-sm font-bold text-gray-800">Especificaciones
                                                        </h4>
                                                    </div>
                                                    <p class="text-xs text-gray-600 leading-relaxed line-clamp-3 hover:line-clamp-none transition-all cursor-help"
                                                        title="{{ $detalle->especificaciones_tecnicas }}">
                                                        {{ $detalle->especificaciones_tecnicas }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($detalle->proveedor_sugerido)
                                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <i class="fas fa-truck text-success text-sm"></i>
                                                        <h4 class="text-sm font-bold text-gray-800">Proveedor</h4>
                                                    </div>
                                                    <p class="text-sm text-gray-700 font-medium truncate"
                                                        title="{{ $detalle->proveedor_sugerido }}">
                                                        {{ $detalle->proveedor_sugerido }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($detalle->observaciones_detalle)
                                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <i class="fas fa-sticky-note text-secondary text-sm"></i>
                                                        <h4 class="text-sm font-bold text-gray-800">Observaciones</h4>
                                                    </div>
                                                    <p class="text-xs text-gray-600 leading-relaxed line-clamp-3 hover:line-clamp-none transition-all cursor-help"
                                                        title="{{ $detalle->observaciones_detalle }}">
                                                        {{ $detalle->observaciones_detalle }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-right text-sm font-bold text-gray-700">
                                Total General:
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-lg font-bold text-success">
                                    {{ $monedaResumen }}{{ number_format($solicitud->detalles->sum('total_producto'), 2) }}
                                </div>
                            </td>
                            <td class="px-4 py-4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Archivos Adjuntos -->
        @if ($solicitud->archivos && $solicitud->archivos->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Archivos Adjuntos</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($solicitud->archivos as $archivo)
                            <div
                                class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-blue-300 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file text-gray-400 text-xl"></i>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $archivo->nombre_archivo }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ number_format($archivo->tamaño / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank"
                                    class="text-blue-500 hover:text-blue-700 transition-colors duration-200">
                                    <i class="fas fa-download text-lg"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>


</x-layout.default>
