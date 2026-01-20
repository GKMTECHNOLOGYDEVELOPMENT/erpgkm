<x-layout.default>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div x-data="{ activeTab: 'articulos' }" class="min-h-screen bg-gray-50 py-4 sm:py-6 lg:py-8">
        <div class="mx-auto px-3 sm:px-4 lg:px-6 w-full">

            <!-- Breadcrumb responsive -->
            <div class="mb-4 sm:mb-6">
                <ul class="flex flex-wrap gap-1 sm:gap-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline text-xs sm:text-sm">
                            <i class="fas fa-list mr-1"></i>
                            Solicitudes
                        </a>
                    </li>
                    <li class="text-xs sm:text-sm text-gray-500 before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Ver Solicitud Provincia</span>
                    </li>
                </ul>
            </div>

            <!-- Header Principal para Provincia - Responsive -->
            <div
                class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-5 lg:p-6 mb-4 sm:mb-6 lg:mb-8 border border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div
                            class="flex items-start sm:items-center flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 lg:space-x-4 mb-4">

                            <div class="flex-1 min-w-0">
                                <h1
                                    class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-bold text-gray-900 mb-2 truncate">
                                    <i class="fas fa-truck text-purple-500 mr-2"></i>
                                    Orden Provincia #<span
                                        class="text-purple-600">{{ $solicitud->codigo ?? 'N/A' }}</span>
                                </h1>
                                <div class="flex flex-wrap gap-1 sm:gap-2">
                                    <span
                                        class="px-2 sm:px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs sm:text-sm font-semibold border border-blue-200 whitespace-nowrap">
                                        <i class="fas fa-circle text-blue-500 mr-1 text-xs"></i>
                                        {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                    </span>
                                    <span
                                        class="px-2 sm:px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs sm:text-sm font-semibold border border-purple-200 whitespace-nowrap">
                                        <i class="fas fa-truck mr-1 text-xs"></i>
                                        Solicitud Provincia
                                    </span>

                                    <!-- Indicador de tiempo -->
                                    @php
                                        $fechaRequerida = $solicitud->fecharequerida
                                            ? \Carbon\Carbon::parse($solicitud->fecharequerida)->startOfDay()
                                            : now()->startOfDay();

                                        $diasRestantes = now()->startOfDay()->diffInDays($fechaRequerida, false);

                                        // Ajustar para mostrar más intuitivamente
                                        if ($diasRestantes < 0) {
                                            // Ya pasó la fecha
                                            $diasMostrar = 'Vencida';
                                            $diasNumero = $diasRestantes;
                                            $estadoTiempo = 'vencida';
                                        } elseif ($diasRestantes == 0) {
                                            // Es hoy
                                            $diasMostrar = 'Hoy';
                                            $diasNumero = 0;
                                            $estadoTiempo = 'hoy';
                                        } elseif ($diasRestantes == 1) {
                                            // Es mañana
                                            $diasMostrar = 'Mañana';
                                            $diasNumero = 1;
                                            $estadoTiempo = 'mañana';
                                        } elseif ($diasRestantes <= 2) {
                                            // 2 días
                                            $diasMostrar = $diasRestantes . ' días';
                                            $diasNumero = $diasRestantes;
                                            $estadoTiempo = 'urgente';
                                        } elseif ($diasRestantes <= 7) {
                                            // 3-7 días
                                            $diasMostrar = $diasRestantes . ' días';
                                            $diasNumero = $diasRestantes;
                                            $estadoTiempo = 'normal';
                                        } else {
                                            // Más de 7 días
                                            $diasMostrar = $diasRestantes . ' días';
                                            $diasNumero = $diasRestantes;
                                            $estadoTiempo = 'lejano';
                                        }

                                        // Configurar clases y tooltips
                                        $configTiempo = [
                                            'vencida' => [
                                                'class' => 'bg-red-100 text-red-800 border-red-200',
                                                'icon' => 'fas fa-exclamation-triangle',
                                                'text' => $diasMostrar,
                                                'tooltip' =>
                                                    'Vencida: ' .
                                                    ($fechaRequerida
                                                        ? $fechaRequerida->format('d/m/Y')
                                                        : 'Fecha no especificada'),
                                            ],
                                            'hoy' => [
                                                'class' => 'bg-red-100 text-red-800 border-red-200',
                                                'icon' => 'fas fa-exclamation-circle',
                                                'text' => $diasMostrar,
                                                'tooltip' =>
                                                    'Vence hoy: ' .
                                                    ($fechaRequerida
                                                        ? $fechaRequerida->format('d/m/Y')
                                                        : 'Fecha no especificada'),
                                            ],
                                            'mañana' => [
                                                'class' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                'icon' => 'fas fa-clock',
                                                'text' => $diasMostrar,
                                                'tooltip' =>
                                                    'Vence mañana: ' .
                                                    ($fechaRequerida
                                                        ? $fechaRequerida->format('d/m/Y')
                                                        : 'Fecha no especificada'),
                                            ],
                                            'urgente' => [
                                                'class' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                'icon' => 'fas fa-clock',
                                                'text' => $diasMostrar,
                                                'tooltip' =>
                                                    'Vence en ' .
                                                    $diasNumero .
                                                    ' días: ' .
                                                    ($fechaRequerida
                                                        ? $fechaRequerida->format('d/m/Y')
                                                        : 'Fecha no especificada'),
                                            ],
                                            'normal' => [
                                                'class' => 'bg-green-100 text-green-800 border-green-200',
                                                'icon' => 'fas fa-calendar-check',
                                                'text' => $diasMostrar,
                                                'tooltip' =>
                                                    'Vence en ' .
                                                    $diasNumero .
                                                    ' días: ' .
                                                    ($fechaRequerida
                                                        ? $fechaRequerida->format('d/m/Y')
                                                        : 'Fecha no especificada'),
                                            ],
                                            'lejano' => [
                                                'class' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'icon' => 'fas fa-calendar',
                                                'text' => $diasMostrar,
                                                'tooltip' =>
                                                    'Vence en ' .
                                                    $diasNumero .
                                                    ' días: ' .
                                                    ($fechaRequerida
                                                        ? $fechaRequerida->format('d/m/Y')
                                                        : 'Fecha no especificada'),
                                            ],
                                        ];
                                    @endphp

                                    <span
                                        class="px-2 sm:px-3 py-1 {{ $configTiempo[$estadoTiempo]['class'] }} rounded-full text-xs sm:text-sm font-semibold border flex items-center gap-1 whitespace-nowrap group relative"
                                        title="{{ $configTiempo[$estadoTiempo]['tooltip'] }}">
                                        <i class="{{ $configTiempo[$estadoTiempo]['icon'] }} mr-1 text-xs"></i>
                                        {{ $configTiempo[$estadoTiempo]['text'] }}

                                        <!-- Tooltip -->
                                        <span
                                            class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-800 text-white text-xs rounded-lg whitespace-nowrap z-10">
                                            {{ $configTiempo[$estadoTiempo]['tooltip'] }}
                                            <span
                                                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></span>
                                        </span>
                                    </span>

                                    @if ($solicitud->cast_nombre)
                                        <span
                                            class="px-2 sm:px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-semibold border border-green-200 truncate max-w-[200px]">
                                            <i class="fas fa-store mr-1 text-xs"></i>
                                            {{ $solicitud->cast_nombre }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Botones responsive -->
                            <div class="flex flex-wrap gap-2 sm:gap-3 mt-4 lg:mt-0">
                                <a href="{{ route('solicitudarticulo.index') ?? route('solicitudarticulo.index') }}"
                                    class="flex items-center justify-center px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 lg:py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm text-xs sm:text-sm flex-1 sm:flex-none">
                                    <i class="fas fa-arrow-left mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                    <span class="hidden sm:inline">Volver</span>
                                    <span class="sm:hidden">←</span>
                                </a>
                                @if (($solicitud->estado ?? '') != 'completada')
                                    <a href="{{ route('solicitudrepuestoprovincia.edit', $solicitud->idsolicitudesordenes) }}"
                                        class="flex items-center justify-center px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 lg:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm text-xs sm:text-sm flex-1 sm:flex-none">
                                        <i class="fas fa-edit mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                        <span class="hidden sm:inline">Editar</span>
                                        <span class="sm:hidden">✎</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Información específica de provincia - Responsive -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mt-4">
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                <label
                                    class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide truncate">
                                    <i class="fas fa-store mr-1"></i>
                                    CAST
                                </label>
                                <p class="text-base sm:text-lg font-bold text-gray-900 truncate">
                                    {{ $solicitud->cast_nombre ?? 'No especificado' }}</p>
                                @if ($solicitud->cast_direccion)
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1 truncate">
                                        <i class="fas fa-location-dot mr-1 text-xs"></i>
                                        {{ $solicitud->cast_direccion }}
                                    </p>
                                @endif
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                <label
                                    class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide truncate">
                                    <i class="fas fa-ticket-alt mr-1"></i>
                                    Ticket Manual
                                </label>
                                <p class="text-base sm:text-lg font-bold text-gray-900">
                                    {{ $solicitud->numeroticket ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                <label
                                    class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide truncate">
                                    <i class="fas fa-map-pin mr-1"></i>
                                    Ubicación
                                </label>
                                <p class="text-base sm:text-lg font-bold text-gray-900 truncate">
                                    @if ($solicitud->cast_departamento || $solicitud->cast_provincia)
                                        {{ $solicitud->cast_provincia ?? '' }} -
                                        {{ $solicitud->cast_departamento ?? '' }}
                                    @else
                                        No especificada
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                <label
                                    class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide truncate">
                                    <i class="fas fa-calendar-day mr-1"></i>
                                    Fecha Requerida
                                </label>
                                <p class="text-base sm:text-lg font-bold text-gray-900">
                                    @if ($solicitud->fecharequerida)
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->startOfDay()->format('d/m/Y') }}
                                    @else
                                        <span class="text-gray-400">No definida</span>
                                    @endif
                                </p>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                    @if ($solicitud->fecharequerida && $estadoTiempo == 'vencida')
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i>
                                        <span class="text-red-600 font-semibold">Vencida</span>
                                    @elseif($solicitud->fecharequerida && $estadoTiempo == 'hoy')
                                        <i class="fas fa-exclamation-circle text-red-500 mr-1"></i>
                                        <span class="text-red-600 font-semibold">Vence hoy</span>
                                    @elseif($solicitud->fecharequerida && $diasRestantes > 0)
                                        <i class="fas fa-clock text-blue-500 mr-1"></i>
                                        <span class="text-blue-600">en {{ $diasRestantes }} día(s)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navegación por Tabs responsive -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-1 sm:p-2 mb-4 sm:mb-6 border border-gray-200">
                <div class="flex space-x-1">
                    <button @click="activeTab = 'articulos'"
                        :class="activeTab === 'articulos' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-2 sm:py-3 px-2 sm:px-4 rounded-lg font-semibold text-xs sm:text-sm transition-all duration-200 truncate">
                        <i class="fas fa-boxes mr-2 hidden sm:inline"></i>
                        Repuestos Solicitados
                    </button>
                    <button @click="activeTab = 'detalles'"
                        :class="activeTab === 'detalles' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-2 sm:py-3 px-2 sm:px-4 rounded-lg font-semibold text-xs sm:text-sm transition-all duration-200 truncate">
                        <i class="fas fa-info-circle mr-2 hidden sm:inline"></i>
                        Detalles de la Orden
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
                <!-- Columna Principal -->
                <div class="xl:col-span-2 space-y-4 sm:space-y-6">
                    <!-- Repuestos Solicitados -->
                    <div x-show="activeTab === 'articulos'"
                        class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-5 lg:p-6 border border-gray-200">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-0">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div
                                    class="w-10 h-10 sm:w-11 sm:h-11 lg:w-12 lg:h-12 bg-green-500 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                    <i class="fas fa-box-open text-white text-lg sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 truncate">

                                        Repuestos Solicitados
                                    </h2>
                                    <p class="text-gray-600 text-xs sm:text-sm truncate">Lista completa de repuestos
                                        para provincia</p>
                                </div>
                            </div>
                            <div
                                class="text-right bg-purple-50 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-3 border border-purple-200">
                                <p class="text-xs text-purple-700 font-semibold uppercase tracking-wide truncate">
                                    <i class="fas fa-calculator mr-1"></i>
                                    Total
                                </p>
                                <p class="text-xl sm:text-2xl font-bold text-purple-800">{{ $articulos->count() }}</p>
                            </div>
                        </div>

                        @if ($articulos->count() > 0)
                            <div class="mb-4 bg-blue-50 rounded-lg p-3 sm:p-4 border border-blue-200">
                                <div class="flex items-center space-x-2 text-blue-800">
                                    <i class="fas fa-info-circle text-blue-600 flex-shrink-0"></i>
                                    <span class="text-xs sm:text-sm font-medium truncate">
                                        CAST: <strong>{{ $solicitud->cast_nombre }}</strong> • Ticket:
                                        <strong>{{ $solicitud->numeroticket }}</strong>
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Tabla responsive con scroll horizontal -->
                        <div class="overflow-x-auto rounded-lg sm:rounded-xl border border-gray-200">
                            <table class="w-full min-w-[640px]">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                            <i class="fas fa-barcode mr-1"></i>
                                            Código
                                        </th>
                                        <th
                                            class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            Descripción
                                        </th>
                                        <th
                                            class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                            <i class="fas fa-hashtag mr-1"></i>
                                            Cantidad
                                        </th>
                                        <th
                                            class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                            <i class="fas fa-tasks mr-1"></i>
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($articulos as $articulo)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                                                <div class="space-y-1">
                                                    <span
                                                        class="font-mono font-semibold text-blue-700 block text-xs sm:text-sm truncate">
                                                        <i class="fas fa-qrcode mr-1 text-xs text-blue-500"></i>
                                                        {{ $articulo->codigo_repuesto ?? 'N/A' }}
                                                    </span>
                                                    @if ($articulo->codigo_barras)
                                                        <span
                                                            class="text-xs text-gray-500 bg-gray-100 rounded px-1.5 sm:px-2 py-0.5 sm:py-1 inline-block truncate max-w-[120px] sm:max-w-none">
                                                            <i class="fas fa-barcode mr-1 text-xs"></i>
                                                            {{ $articulo->codigo_barras }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-2 sm:py-3">
                                                <div>
                                                    <span
                                                        class="font-medium text-gray-900 block text-xs sm:text-sm truncate">

                                                        {{ $articulo->nombre_articulo ?? 'Sin descripción' }}
                                                    </span>
                                                    <span
                                                        class="px-2 py-0.5 sm:py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold whitespace-nowrap">
                                                        <i class="fas fa-tag mr-1 text-xs"></i>
                                                        {{ $articulo->tipo_articulo ?? 'General' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-2 sm:py-3">
                                                <span
                                                    class="text-base sm:text-xl font-bold text-gray-900 bg-gray-100 rounded-lg px-2 sm:px-3 py-0.5 sm:py-1 inline-block whitespace-nowrap">
                                                    <i class="fas fa-layer-group mr-1 text-gray-500 text-xs"></i>
                                                    {{ $articulo->cantidad ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-3 sm:px-4 py-2 sm:py-3">
                                                @php
                                                    $estadoClase =
                                                        [
                                                            '0' =>
                                                                'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                            '1' =>
                                                                'bg-green-100 text-green-800 border border-green-200',
                                                            '2' => 'bg-red-100 text-red-800 border border-red-200',
                                                        ][$articulo->estado ?? 0] ??
                                                        'bg-gray-100 text-gray-800 border border-gray-200';

                                                    $estadoTexto =
                                                        [
                                                            '0' => 'Pendiente',
                                                            '1' => 'Completado',
                                                            '2' => 'Rechazado',
                                                        ][$articulo->estado ?? 0] ?? 'Desconocido';

                                                    $estadoIcono =
                                                        [
                                                            '0' => 'fa-clock',
                                                            '1' => 'fa-check-circle',
                                                            '2' => 'fa-times-circle',
                                                        ][$articulo->estado ?? 0] ?? 'fa-question-circle';
                                                @endphp
                                                <span
                                                    class="px-2 sm:px-3 py-0.5 sm:py-1 {{ $estadoClase }} rounded-full text-xs font-semibold whitespace-nowrap">
                                                    <i class="fas {{ $estadoIcono }} mr-1 text-xs"></i>
                                                    {{ $estadoTexto }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 sm:py-12 text-center">
                                                <i
                                                    class="fas fa-box-open text-gray-300 text-4xl sm:text-5xl mb-3 sm:mb-4"></i>
                                                <p class="mt-2 sm:mt-4 text-sm sm:text-lg font-semibold text-gray-500">
                                                    No hay repuestos registrados</p>
                                                <p class="mt-1 text-gray-400 text-xs sm:text-sm">Esta orden no tiene
                                                    repuestos solicitados</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($articulos->count() > 0)
                            <div class="mt-4 sm:mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                                <div
                                    class="bg-blue-50 rounded-lg sm:rounded-xl p-3 sm:p-4 text-center border border-blue-200">
                                    <p
                                        class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-1 truncate">
                                        <i class="fas fa-boxes mr-1"></i>
                                        Total Repuestos
                                    </p>
                                    <p class="text-xl sm:text-2xl font-bold text-blue-800">{{ $articulos->count() }}
                                    </p>
                                </div>
                                <div
                                    class="bg-green-50 rounded-lg sm:rounded-xl p-3 sm:p-4 text-center border border-green-200">
                                    <p
                                        class="text-xs text-green-700 font-semibold uppercase tracking-wide mb-1 truncate">
                                        <i class="fas fa-layer-group mr-1"></i>
                                        Total Cantidad
                                    </p>
                                    <p class="text-xl sm:text-2xl font-bold text-green-800">
                                        {{ $articulos->sum('cantidad') }}</p>
                                </div>
                                <div
                                    class="bg-purple-50 rounded-lg sm:rounded-xl p-3 sm:p-4 text-center border border-purple-200">
                                    <p
                                        class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-1 truncate">
                                        <i class="fas fa-tags mr-1"></i>
                                        Tipos Diferentes
                                    </p>
                                    <p class="text-xl sm:text-2xl font-bold text-purple-800">
                                        {{ $articulos->unique('tipo_articulo')->count() }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Sección debajo de Artículos en 2 columnas - Responsive -->
                        <div class="mt-6 sm:mt-8 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Información CAST -->
                            <div
                                class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-5 lg:p-6 border border-gray-200">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">
                                    <i class="fas fa-store mr-2 text-green-600"></i>
                                    Información del CAST
                                </h3>
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 sm:w-11 sm:h-11 lg:w-12 lg:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-store text-green-600 text-lg sm:text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                                {{ $solicitud->cast_nombre ?? 'No especificado' }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                <i class="fas fa-ticket-alt mr-1"></i>
                                                Ticket: {{ $solicitud->numeroticket ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($solicitud->cast_direccion || $solicitud->cast_provincia)
                                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                            <h4 class="text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">
                                                <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                                Ubicación
                                            </h4>
                                            <div class="space-y-1">
                                                @if ($solicitud->cast_direccion)
                                                    <p class="text-xs sm:text-sm text-gray-600 truncate">
                                                        <i class="fas fa-location-dot mr-1 text-xs"></i>
                                                        {{ $solicitud->cast_direccion }}
                                                    </p>
                                                @endif
                                                @if ($solicitud->cast_departamento || $solicitud->cast_provincia)
                                                    <p class="text-xs sm:text-sm text-gray-600 truncate">
                                                        <i class="fas fa-map mr-1 text-xs"></i>
                                                        {{ $solicitud->cast_distrito ?? '' }} -
                                                        {{ $solicitud->cast_provincia ?? '' }} -
                                                        {{ $solicitud->cast_departamento ?? '' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Solicitante -->
                            <div
                                class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-5 lg:p-6 border border-gray-200">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">
                                    <i class="fas fa-user-tie mr-2 text-primary"></i>
                                    Solicitante
                                </h3>
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 sm:w-11 sm:h-11 lg:w-12 lg:h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-tie text-primary text-lg sm:text-xl"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                            {{ $solicitud->nombre_solicitante ?? 'No especificado' }}</p>
                                        <p class="text-xs sm:text-sm text-gray-600 truncate">
                                            <i class="fas fa-building mr-1 text-xs"></i>
                                            {{ $solicitud->nombre_area ?? 'Área no especificada' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de la Orden -->
                    <div x-show="activeTab === 'detalles'"
                        class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-5 lg:p-6 border border-gray-200">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">
                            <i class="fas fa-clipboard-list mr-2 text-purple-600"></i>
                            Detalles de la Orden Provincia
                        </h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <div class="space-y-3 sm:space-y-4">
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 sm:mb-2 uppercase tracking-wide">
                                        <i class="fas fa-hashtag mr-1"></i>
                                        Código Orden
                                    </label>
                                    <p class="text-base sm:text-lg font-bold text-gray-900">
                                        {{ $solicitud->codigo ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 sm:mb-2 uppercase tracking-wide">
                                        <i class="fas fa-store mr-1"></i>
                                        CAST Destino
                                    </label>
                                    <p class="text-base sm:text-lg font-bold text-gray-900 truncate">
                                        {{ $solicitud->cast_nombre ?? 'No especificado' }}</p>
                                    @if ($solicitud->cast_direccion)
                                        <p class="text-xs sm:text-sm text-gray-600 mt-1 truncate">
                                            <i class="fas fa-location-dot mr-1 text-xs"></i>
                                            {{ $solicitud->cast_direccion }}
                                        </p>
                                    @endif
                                    @if ($solicitud->cast_departamento || $solicitud->cast_provincia)
                                        <p class="text-xs text-gray-500 mt-1 truncate">
                                            <i class="fas fa-map mr-1 text-xs"></i>
                                            {{ $solicitud->cast_distrito ?? '' }} -
                                            {{ $solicitud->cast_provincia ?? '' }} -
                                            {{ $solicitud->cast_departamento ?? '' }}
                                        </p>
                                    @endif
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 sm:mb-2 uppercase tracking-wide">
                                        <i class="fas fa-ticket-alt mr-1"></i>
                                        Ticket Manual
                                    </label>
                                    <p class="text-base sm:text-lg font-bold text-gray-900">
                                        {{ $solicitud->numeroticket ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="space-y-3 sm:space-y-4">
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 sm:mb-2 uppercase tracking-wide">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Fecha Requerida
                                    </label>
                                    <p class="text-base sm:text-lg font-bold text-gray-900">
                                        @if ($solicitud->fecharequerida)
                                            <i class="fas fa-clock mr-1 text-gray-400"></i>
                                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y') }}
                                        @else
                                            No definida
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 sm:mb-2 uppercase tracking-wide">
                                        <i class="fas fa-cogs mr-1"></i>
                                        Tipo de Servicio
                                    </label>
                                    <p class="text-base sm:text-lg font-bold text-gray-900">
                                        {{ $solicitud->tiposervicio ?? 'No especificado' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 sm:mb-2 uppercase tracking-wide">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Urgencia
                                    </label>
                                    @php
                                        $urgenciaClase =
                                            [
                                                'baja' => 'bg-green-100 text-green-800 border border-green-200',
                                                'media' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                'alta' => 'bg-red-100 text-red-800 border border-red-200',
                                            ][$solicitud->urgencia ?? 'baja'] ??
                                            'bg-gray-100 text-gray-800 border border-gray-200';

                                        $urgenciaTexto =
                                            [
                                                'baja' => 'Baja',
                                                'media' => 'Media',
                                                'alta' => 'Alta',
                                            ][$solicitud->urgencia ?? 'baja'] ?? 'No especificado';

                                        $urgenciaIcono =
                                            [
                                                'baja' => 'fa-check-circle',
                                                'media' => 'fa-exclamation-circle',
                                                'alta' => 'fa-exclamation-triangle',
                                            ][$solicitud->urgencia ?? 'baja'] ?? 'fa-question-circle';
                                    @endphp
                                    <span
                                        class="px-2 sm:px-3 py-0.5 sm:py-1 {{ $urgenciaClase }} rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                        <i class="fas {{ $urgenciaIcono }} mr-1 text-xs"></i>
                                        {{ $urgenciaTexto }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        @if (!empty($solicitud->observaciones))
                            <div class="mt-4 sm:mt-6">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-comment-alt mr-1"></i>
                                    Observaciones
                                </label>
                                <div class="bg-yellow-50 rounded-lg p-3 sm:p-4 border border-yellow-200">
                                    <p class="text-xs sm:text-sm text-yellow-800 leading-relaxed">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        {{ $solicitud->observaciones }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Mejorado para Provincia - Responsive -->
                <div class="space-y-4 sm:space-y-6">
                    <!-- Resumen Rápido Mejorado para Provincia -->
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-5 lg:p-6 border border-gray-200 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-2 sm:space-x-3 mb-3 sm:mb-4 lg:mb-5">
                            <div
                                class="w-8 h-8 sm:w-9 sm:h-9 lg:w-10 lg:h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                <i class="fas fa-chart-pie text-white text-sm sm:text-base lg:text-lg"></i>
                            </div>
                            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 truncate">Resumen
                                Provincia</h3>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            <!-- Estado -->
                            <div
                                class="flex items-center justify-between p-2 sm:p-3 bg-white rounded-lg sm:rounded-xl border border-gray-200 hover:border-purple-300 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-circle text-purple-600 text-xs sm:text-sm"></i>
                                    </div>
                                    <span
                                        class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Estado</span>
                                </div>
                                <span
                                    class="px-2 sm:px-3 py-0.5 sm:py-1.5 bg-purple-100 text-purple-800 rounded-full text-xs font-bold border border-purple-200 whitespace-nowrap">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i>
                                    {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                </span>
                            </div>

                            <!-- CAST -->
                            <div
                                class="flex items-center justify-between p-2 sm:p-3 bg-white rounded-lg sm:rounded-xl border border-gray-200 hover:border-green-300 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-store text-green-600 text-xs sm:text-sm"></i>
                                    </div>
                                    <span
                                        class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">CAST</span>
                                </div>
                                <span
                                    class="text-xs sm:text-sm font-bold text-gray-900 truncate max-w-[120px] sm:max-w-none">
                                    <i class="fas fa-store mr-1 text-xs"></i>
                                    {{ $solicitud->cast_nombre ?? 'N/A' }}
                                </span>
                            </div>

                            <!-- Total Repuestos -->
                            <div
                                class="flex items-center justify-between p-2 sm:p-3 bg-white rounded-lg sm:rounded-xl border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-boxes text-blue-600 text-xs sm:text-sm"></i>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Total
                                        Repuestos</span>
                                </div>
                                <span
                                    class="text-base sm:text-lg font-bold text-gray-900 bg-gray-100 rounded-lg px-2 sm:px-3 py-0.5 sm:py-1.5 whitespace-nowrap">
                                    <i class="fas fa-hashtag mr-1 text-xs text-gray-500"></i>
                                    {{ $articulos->count() }}
                                </span>
                            </div>

                            <!-- Cantidad Total -->
                            <div
                                class="flex items-center justify-between p-2 sm:p-3 bg-white rounded-lg sm:rounded-xl border border-gray-200 hover:border-orange-300 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-layer-group text-orange-600 text-xs sm:text-sm"></i>
                                    </div>
                                    <span
                                        class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Cantidad
                                        Total</span>
                                </div>
                                <span
                                    class="text-base sm:text-lg font-bold text-gray-900 bg-gray-100 rounded-lg px-2 sm:px-3 py-0.5 sm:py-1.5 whitespace-nowrap">
                                    <i class="fas fa-sort-amount-up mr-1 text-xs text-gray-500"></i>
                                    {{ $articulos->sum('cantidad') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</x-layout.default>
