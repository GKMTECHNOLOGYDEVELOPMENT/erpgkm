<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div class="mx-auto w-full px-4 py-6">
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudcompra.index') }}" class="text-primary hover:underline">
                        Solicitudes Compra
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Evaluación Compra</span>
                </li>
            </ul>
        </div>
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-5">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex items-center space-x-4">
                    <div
                        class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white"
                            viewBox="0 0 16 16">
                            <path
                                d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-13z" />
                            <path
                                d="M3.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5h-5zm3 5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Evaluación de Solicitud de Compra</h1>
                        <p class="text-gray-600">Información completa de la solicitud {{ $solicitud->codigo_solicitud }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('solicitudcompra.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16" class="mr-2">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                        </svg>
                        Volver al Listado
                    </a>

                   @if ($estadisticas['puede_avanzar_estado'] && count($estadisticas['estados_siguientes']) > 0)
    <div class="flex flex-wrap gap-2">
        @foreach ($estadisticas['estados_siguientes'] as $estado => $label)
            @if ($estado == 'cancelada')
                <button onclick="cancelarSolicitud({{ $solicitud->idSolicitudCompra }})"
                    class="inline-flex items-center px-4 py-2 bg-danger hover:bg-red-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                    {{ $label }}
                </button>
            @elseif($estado == 'observado') <!-- NUEVA CONDICIÓN -->
                <button onclick="abrirModalObservado({{ $solicitud->idSolicitudCompra }})"
                    class="inline-flex items-center px-4 py-2 bg-info hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                    </svg>
                    {{ $label }}
                </button>
            @else
                <button onclick="cambiarEstado({{ $solicitud->idSolicitudCompra }}, '{{ $estado }}')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    {{ $label }}
                </button>
            @endif
        @endforeach
    </div>
@endif

                    @if ($solicitud->estado == 'pendiente')
                        <a href="{{ route('solicitudcompra.edit', $solicitud->idSolicitudCompra) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16" class="mr-2">
                                <path
                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                            </svg>
                            Editar Solicitud
                        </a>
                    @endif
                </div>
            </div>
        </div>

  <!-- Mapa de Estados -->
<div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">Flujo de Estados</h3>
    </div>
    <div class="px-6 py-4">
        <!-- Primero definimos las variables PHP para usar en toda la sección -->
        @php
            // Definir $colorClasses PRIMERO, fuera del bucle
            $colorClasses = [
                'warning' => [
                    'bg' => 'bg-warning',
                    'bg-light' => 'bg-warning/20',
                    'text' => 'text-yellow-800',
                    'border' => 'border-warning',
                ],
                'primary' => [
                    'bg' => 'bg-primary',
                    'bg-light' => 'bg-primary/20',
                    'text' => 'text-blue-800',
                    'border' => 'border-primary',
                ],
                'secondary' => [
                    'bg' => 'bg-secondary',
                    'bg-light' => 'bg-secondary/20',
                    'text' => 'text-purple-800',
                    'border' => 'border-secondary',
                ],
                'warning-light' => [
                    'bg' => 'bg-warning',
                    'bg-light' => 'bg-warning-light',
                    'text' => 'text-warning',
                    'border' => 'border-warning',
                ],
                'info' => [
                    'bg' => 'bg-info',
                    'bg-light' => 'bg-info/20',
                    'text' => 'text-blue-600',
                    'border' => 'border-info',
                ],
                'dark' => [
                    'bg' => 'bg-dark',
                    'bg-light' => 'bg-dark/20',
                    'text' => 'text-gray-800',
                    'border' => 'border-dark',
                ],
                'danger' => [
                    'bg' => 'bg-danger',
                    'bg-light' => 'bg-danger/20',
                    'text' => 'text-red-800',
                    'border' => 'border-danger',
                ],
            ];
            
            // Luego definimos los estados EN EL ORDEN CORRECTO - SIN 'en_proceso'
            $estados = [
                'pendiente' => ['icon' => 'fas fa-clock', 'color' => 'warning', 'label' => 'Pendiente'],
                'completada' => [
                    'icon' => 'fas fa-check-circle',
                    'color' => 'secondary',
                    'label' => 'Completada',
                ],
                'observado' => [
                    'icon' => 'fas fa-eye', 
                    'color' => 'info',
                    'label' => 'Observado',
                ],
                'presupuesto_aprobado' => [
                    'icon' => 'fas fa-file-invoice-dollar',
                    'color' => 'warning-light',
                    'label' => 'Presupuesto Aprobado',
                ],
                'pagado' => ['icon' => 'fas fa-credit-card', 'color' => 'info', 'label' => 'Pagado'],
                'finalizado' => [
                    'icon' => 'fas fa-flag-checkered',
                    'color' => 'dark',
                    'label' => 'Finalizado',
                ],
                'cancelada' => ['icon' => 'fas fa-times-circle', 'color' => 'dark', 'label' => 'Cancelada'],
                'rechazada' => ['icon' => 'fas fa-ban', 'color' => 'danger', 'label' => 'Rechazada'],
            ];

            $estadoActual = $solicitud->estado;
            $estadosKeys = array_keys($estados);
            $indiceActual = array_search($estadoActual, $estadosKeys);
        @endphp

        <div class="flex items-center justify-between overflow-x-auto py-4">
            @foreach ($estados as $estado => $info)
                @php
                    $colorInfo = $colorClasses[$info['color']] ?? $colorClasses['warning'];
                    $estadoIndex = array_search($estado, $estadosKeys);
                @endphp

                <div class="flex flex-col items-center mx-2 min-w-20">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold
                        @if ($estado == $estadoActual) {{ $colorInfo['bg'] }} text-white border-2 {{ $colorInfo['border'] }} shadow-lg
                        @elseif($estadoIndex < $indiceActual)
                            {{ $colorInfo['bg-light'] }} {{ $colorInfo['text'] }} border-2 {{ $colorInfo['border'] }}
                        @else
                            bg-gray-100 text-gray-400 border-2 border-gray-300 @endif">
                        <i class="{{ $info['icon'] }}"></i>
                    </div>
                    <span
                        class="mt-2 text-xs font-medium text-center 
                        @if ($estado == $estadoActual) {{ $colorInfo['text'] }} font-bold
                        @elseif($estadoIndex < $indiceActual)
                            {{ $colorInfo['text'] }}
                        @else
                            text-gray-400 @endif">
                        {{ $info['label'] }}
                    </span>
                    @if ($estado == $estadoActual)
                        <div class="w-2 h-2 {{ $colorInfo['bg'] }} rounded-full mt-1 animate-pulse"></div>
                    @endif
                </div>

                @if (!$loop->last)
                    @php
                        // Determinar el color de la línea
                        $siguienteEstadoIndex = $estadoIndex + 1;
                        $siguienteEstadoKey = $estadosKeys[$siguienteEstadoIndex] ?? null;
                        $siguienteColorInfo = $siguienteEstadoKey
                            ? $colorClasses[$estados[$siguienteEstadoKey]['color']]
                            : null;
                    @endphp

                    <div class="flex-1 h-1 bg-gray-200 mx-1 mt-5">
                        <div
                            class="h-1 transition-all duration-500
                            @if ($siguienteEstadoIndex <= $indiceActual) {{ $siguienteColorInfo['bg'] ?? 'bg-gray-400' }} w-full
                            @else
                                bg-transparent w-0 @endif">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Leyenda de flujos -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                <div class="font-semibold text-blue-800 mb-1 flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i>
                    Flujo Principal (Compra)
                </div>
                <div class="text-blue-600 text-xs">
                    Pendiente → Completada → Observado → Presupuesto Aprobado → Pagado → Finalizado
                </div>
            </div>
            <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                <div class="font-semibold text-red-800 mb-1 flex items-center gap-2">
                    <i class="fas fa-times-circle"></i>
                    Flujo de Cancelación
                </div>
                <div class="text-red-600 text-xs">
                    Puede cancelarse en cualquier estado después de "Completada"
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Panel de Estado de Aprobación -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <!-- Cabecera -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Estado de Aprobación</h3>
                            <p class="text-sm text-gray-500">Resumen de productos y progreso</p>
                        </div>
                    </div>

              <!-- Estado general -->
<div class="text-right">
    <div class="text-xs text-gray-500 font-medium mb-1">Estado General</div>
    <span
        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
        @if ($solicitud->estado == 'pendiente') bg-warning/10 text-warning-dark border border-warning
        @elseif($solicitud->estado == 'en_proceso') bg-primary/10 text-primary-dark border border-primary
        @elseif($solicitud->estado == 'observado') bg-info/10 text-info-dark border border-info
        @elseif($solicitud->estado == 'completada') bg-secondary/10 text-secondary-dark border border-secondary
        @elseif($solicitud->estado == 'presupuesto_aprobado') bg-warning-light/10 text-warning-light-dark border border-warning-light
        @elseif($solicitud->estado == 'pagado') bg-info/10 text-info-dark border border-info
        @elseif($solicitud->estado == 'finalizado') bg-dark/10 text-gray-800 border border-dark
        @elseif($solicitud->estado == 'cancelada') bg-dark/10 text-gray-800 border border-dark
        @elseif($solicitud->estado == 'rechazada') bg-danger/10 text-danger-dark border border-danger
        @else bg-gray-100 text-gray-700 border border-gray-300 @endif">
        <i
            class="fas fa-circle text-xs mr-2
            @if ($solicitud->estado == 'pendiente') text-warning
            @elseif($solicitud->estado == 'en_proceso') text-primary
            @elseif($solicitud->estado == 'observado') text-info
            @elseif($solicitud->estado == 'completada') text-secondary
            @elseif($solicitud->estado == 'presupuesto_aprobado') text-warning-light
            @elseif($solicitud->estado == 'pagado') text-info
            @elseif($solicitud->estado == 'finalizado') text-dark
            @elseif($solicitud->estado == 'cancelada') text-dark
            @elseif($solicitud->estado == 'rechazada') text-danger
            @else text-gray-500 @endif"></i>
        {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
    </span>
</div>

            <!-- Contenido -->
            <div class="px-6 py-5">
                <!-- Estadísticas en tarjetas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- Total Productos -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-primary hover:shadow-sm transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-8 h-8 bg-blue-200 rounded flex items-center justify-center">
                                <i class="fas fa-boxes text-blue-600"></i>
                            </div>
                            <span class="text-xs font-medium text-blue-500 bg-white px-2 py-1 rounded">Total</span>
                        </div>
                        <div class="text-2xl font-bold text-blue-900 mb-1">{{ $estadisticas['total_productos'] }}</div>
                        <div class="text-xs text-blue-500">Productos en la solicitud</div>
                    </div>

                    <!-- Aprobados -->
                    <div class="bg-green-50 p-4 rounded-lg border border-success hover:shadow-sm transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <span class="text-xs font-medium text-green-600 bg-white px-2 py-1 rounded">Aprobado</span>
                        </div>
                        <div class="text-2xl font-bold text-green-700 mb-1">{{ $estadisticas['detalles_aprobados'] }}
                        </div>
                        <div class="text-xs text-green-600">
                            {{ $estadisticas['total_productos'] > 0 ? number_format(($estadisticas['detalles_aprobados'] / $estadisticas['total_productos']) * 100, 1) : 0 }}%
                        </div>
                    </div>

                    <!-- Rechazados -->
                    <div class="bg-red-50 p-4 rounded-lg border border-danger hover:shadow-sm transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-8 h-8 bg-red-100 rounded flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                            <span class="text-xs font-medium text-red-600 bg-white px-2 py-1 rounded">Rechazado</span>
                        </div>
                        <div class="text-2xl font-bold text-red-700 mb-1">{{ $estadisticas['detalles_rechazados'] }}
                        </div>
                        <div class="text-xs text-red-600">
                            {{ $estadisticas['total_productos'] > 0 ? number_format(($estadisticas['detalles_rechazados'] / $estadisticas['total_productos']) * 100, 1) : 0 }}%
                        </div>
                    </div>

                    <!-- Pendientes -->
                    <div class="bg-yellow-50 p-4 rounded-lg border border-warning hover:shadow-sm transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-8 h-8 bg-yellow-100 rounded flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <span
                                class="text-xs font-medium text-yellow-600 bg-white px-2 py-1 rounded">Pendiente</span>
                        </div>
                        <div class="text-2xl font-bold text-yellow-700 mb-1">
                            {{ $estadisticas['detalles_pendientes'] }}</div>
                        <div class="text-xs text-yellow-600">
                            {{ $estadisticas['total_productos'] > 0 ? number_format(($estadisticas['detalles_pendientes'] / $estadisticas['total_productos']) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>

                <!-- Barra de progreso mejorada -->
                @if ($estadisticas['total_productos'] > 0)
                    @php
                        $porcentaje =
                            (($estadisticas['detalles_aprobados'] + $estadisticas['detalles_rechazados']) /
                                $estadisticas['total_productos']) *
                            100;
                    @endphp
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-tasks text-gray-400 text-sm"></i>
                                <span class="text-sm font-medium text-gray-700">Progreso de aprobación</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-sm font-bold text-gray-900">{{ number_format($porcentaje, 1) }}%</span>
                                <span class="text-xs text-gray-500">
                                    ({{ $estadisticas['detalles_aprobados'] + $estadisticas['detalles_rechazados'] }}/{{ $estadisticas['total_productos'] }})
                                </span>
                            </div>
                        </div>

                        <!-- Barra principal -->
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-1 overflow-hidden">
                            <div class="h-3 flex">
                                @if ($estadisticas['detalles_aprobados'] > 0)
                                    <div class="bg-green-500 transition-all duration-700 ease-out"
                                        style="width: {{ ($estadisticas['detalles_aprobados'] / $estadisticas['total_productos']) * 100 }}%">
                                    </div>
                                @endif
                                @if ($estadisticas['detalles_rechazados'] > 0)
                                    <div class="bg-red-500 transition-all duration-700 ease-out delay-100"
                                        style="width: {{ ($estadisticas['detalles_rechazados'] / $estadisticas['total_productos']) * 100 }}%">
                                    </div>
                                @endif
                                @if ($estadisticas['detalles_pendientes'] > 0)
                                    <div class="bg-yellow-400 transition-all duration-700 ease-out delay-200"
                                        style="width: {{ ($estadisticas['detalles_pendientes'] / $estadisticas['total_productos']) * 100 }}%">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Leyenda -->
                        <div class="flex flex-wrap gap-3 mt-3 text-xs">
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 bg-green-500 rounded"></div>
                                <span class="text-gray-600">Aprobados
                                    ({{ $estadisticas['detalles_aprobados'] }})</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 bg-red-500 rounded"></div>
                                <span class="text-gray-600">Rechazados
                                    ({{ $estadisticas['detalles_rechazados'] }})</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 bg-yellow-400 rounded"></div>
                                <span class="text-gray-600">Pendientes
                                    ({{ $estadisticas['detalles_pendientes'] }})</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pie del panel -->
                <div class="pt-4 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <!-- Fecha de aprobación -->
                        @if ($solicitud->fecha_aprobacion)
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-calendar-check text-gray-400"></i>
                                <div>
                                    <span
                                        class="ml-1">{{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="fas fa-clock text-gray-400"></i>
                                <span>Sin fecha de aprobación registrada</span>
                            </div>
                        @endif

                        <!-- Botón de acción -->
                        @if ($estadisticas['puede_avanzar_estado'] && $solicitud->estado == 'en_proceso')
                            <button onclick="cambiarEstado({{ $solicitud->idSolicitudCompra }}, 'completada')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                <i class="fas fa-check-circle"></i>
                                <span>Completar Evaluación</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Compacto de Información -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Columna Principal (3/3) - Detalles + Resumen + Estado -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Cabecera Única -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-500">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-invoice text-gray-700"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Detalles de la Solicitud</h3>
                                    <p class="text-sm text-white">Información general y resumen financiero</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido en tres columnas -->
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-0">
                        <!-- Sección de Detalles (2/4) -->
                        <div class="lg:col-span-2 p-6 border-r border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Código -->
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Código</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-hashtag text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->codigo_solicitud }}</p>
                                    </div>
                                </div>

                                <!-- Solicitante -->
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Solicitante</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">{{ $solicitud->solicitante }}</p>
                                    </div>
                                </div>

                                <!-- Área -->
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Área</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-building text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->tipoArea->nombre ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Prioridad -->
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Prioridad</label>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-2 h-2 rounded-full 
                                    @if (($solicitud->idPrioridad ?? 0) == 1) bg-green-500
                                    @elseif(($solicitud->idPrioridad ?? 0) == 2) bg-warning
                                    @elseif(($solicitud->idPrioridad ?? 0) == 3) bg-red-500
                                    @else bg-gray-400 @endif">
                                        </div>
                                        <p
                                            class="text-sm font-medium 
                                    @if (($solicitud->idPrioridad ?? 0) == 1) text-green-600
                                    @elseif(($solicitud->idPrioridad ?? 0) == 2) text-yellow-600
                                    @elseif(($solicitud->idPrioridad ?? 0) == 3) text-red-600
                                    @else text-gray-600 @endif">
                                            {{ $solicitud->prioridad->nombre ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Fecha Requerida -->
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha
                                        Requerida</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->fecha_requerida ? \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('d/m/Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Centro de Costo -->
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Centro
                                        de Costo</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-money-bill-alt text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->centroCosto->nombre ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Proyecto Asociado -->
                                <div class="md:col-span-2 space-y-1">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Proyecto
                                        Asociado</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-project-diagram text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->proyecto_asociado ?? 'No especificado' }}
                                        </p>
                                        @if ($solicitud->proyecto_asociado)
                                            <span
                                                class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded">Asignado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sección de Información de Estado (1/4) -->
                        <div class="p-6 border-r border-gray-200 bg-gradient-to-b from-blue-50/50 to-white">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                Información de Estado
                            </h4>

                            <div class="space-y-4">
                                <!-- Estado Actual -->
<div
    class="text-center p-4 rounded-lg bg-gradient-to-r from-gray-50 to-white border border-gray-200">
    <div class="text-xs text-gray-500 font-medium mb-2">Estado Actual</div>
    <div class="flex items-center justify-center gap-2">
        <i
            class="
            @if ($solicitud->estado == 'completada') fas fa-check-circle text-green-500
            @elseif($solicitud->estado == 'rechazada') fas fa-times-circle text-red-500
            @elseif($solicitud->estado == 'en_proceso') fas fa-sync-alt text-blue-500
            @elseif($solicitud->estado == 'observado') fas fa-eye text-blue-500
            @elseif($solicitud->estado == 'presupuesto_aprobado') fas fa-file-invoice-dollar text-purple-500
            @elseif($solicitud->estado == 'pagado') fas fa-credit-card text-indigo-500
            @elseif($solicitud->estado == 'finalizado') fas fa-flag-checkered text-green-500
            @elseif($solicitud->estado == 'cancelada') fas fa-times-circle text-red-500
            @else fas fa-clock text-yellow-500 @endif text-xl">
        </i>
        <span class="text-lg font-bold text-gray-900">
            {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
        </span>
    </div>
</div>

                                <!-- Fecha de Cambio -->
                                @if ($solicitud->fecha_aprobacion)
                                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                                        <div class="text-xs text-gray-500 font-medium mb-2">Última Actualización</div>
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('H:i') }}
                                                    hrs
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Motivo de Rechazo -->
                                @if ($solicitud->motivo_rechazo)
                                    <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                                            <div>
                                                <div class="text-sm font-medium text-red-700 mb-1">Motivo de Rechazo
                                                </div>
                                                <p class="text-sm text-red-600">{{ $solicitud->motivo_rechazo }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

            @if ($solicitud->estado == 'observado')
                                    <div class="p-4 rounded-lg bg-blue-50 border border-blue-200">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-eye text-blue-500 mt-1"></i>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-blue-700 mb-3">Detalles de la Observación</div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <!-- Fecha de Observación -->
                                                    <div>
                                                        <div class="text-xs text-blue-600 font-medium">Fecha de Observación:</div>
                                                        <p class="text-sm text-blue-800 mt-1">
                                                            {{ $solicitud->fecha_observacion ? \Carbon\Carbon::parse($solicitud->fecha_observacion)->format('d/m/Y') : 'No especificada' }}
                                                        </p>
                                                    </div>
                                                    
                                                    <!-- Fecha de Reprogramación -->
                                                    <div>
                                                        <div class="text-xs text-blue-600 font-medium">Fecha de Reprogramación:</div>
                                                        <p class="text-sm text-blue-800 mt-1">
                                                            {{ $solicitud->fecha_reprogramacion ? \Carbon\Carbon::parse($solicitud->fecha_reprogramacion)->format('d/m/Y') : 'No especificada' }}
                                                        </p>
                                                    </div>
                                                    
                                                    <!-- Comentario de Observación -->
                                                    <div class="md:col-span-2">
                                                        <div class="text-xs text-blue-600 font-medium">Comentario / Motivo:</div>
                                                        <div class="mt-1 p-3 bg-white rounded border border-blue-100">
                                                            <p class="text-sm text-blue-900">{{ $solicitud->comentario_observacion ?? $solicitud->motivo_rechazo ?? 'No especificado' }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Observado por (si existe el campo) -->
                                                    @if ($solicitud->observado_por)
                                                        <div class="md:col-span-2">
                                                            <div class="text-xs text-blue-600 font-medium">Observado por:</div>
                                                            <p class="text-sm text-blue-800 mt-1">
                                                                @php
                                                                    // Función para obtener nombre del usuario
                                                                    $observadorNombre = 'Usuario ID: ' . $solicitud->observado_por;
                                                                    try {
                                                                        $usuario = \App\Models\Usuario::find($solicitud->observado_por);
                                                                        if ($usuario) {
                                                                            $observadorNombre = trim(
                                                                                ($usuario->Nombre ?? '') . ' ' . 
                                                                                ($usuario->apellidoPaterno ?? '') . ' ' . 
                                                                                ($usuario->apellidoMaterno ?? '')
                                                                            );
                                                                            if (empty(trim($observadorNombre))) {
                                                                                $observadorNombre = $usuario->correo ?? $usuario->usuario ?? 'Usuario ID: ' . $solicitud->observado_por;
                                                                            }
                                                                        }
                                                                    } catch (\Exception $e) {
                                                                        // Si hay error, mostrar solo el ID
                                                                    }
                                                                @endphp
                                                                {{ $observadorNombre }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Días restantes hasta reprogramación -->
                                                    @if ($solicitud->fecha_reprogramacion)
                                                        @php
                                                            $hoy = \Carbon\Carbon::now();
                                                            $fechaReprog = \Carbon\Carbon::parse($solicitud->fecha_reprogramacion);
                                                            $diasRestantes = $hoy->diffInDays($fechaReprog, false);
                                                        @endphp
                                                        <div class="md:col-span-2">
                                                         
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif



                            </div>
                        </div>
                        <!-- Sección de Resumen Financiero (1/4) -->
                        <div class="p-6 ">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-chart-pie text-blue-500"></i>
                                Resumen Financiero
                            </h4>

                            <div class="space-y-4">
                                <!-- Total General (Destacado) -->
                                <div class="bg-white p-4 rounded-lg border border-blue-200 shadow-sm">
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 font-medium mb-1">Total General</div>
                                        <div class="text-2xl font-bold text-blue-600 mb-1">
                                            {{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->total, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-400">Incluye todos los impuestos</div>
                                    </div>
                                </div>

                                <!-- Desglose -->
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <span class="text-sm text-gray-600">Subtotal</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->subtotal, 2) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                            <span class="text-sm text-gray-600">IGV (18%)</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->iva, 2) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                            <span class="text-sm text-gray-600">Unidades</span>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $solicitud->total_unidades }}
                                            unid.</span>
                                    </div>

                                    @if ($solicitud->multiple_monedas)
                                        <div
                                            class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border border-blue-100">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-globe text-blue-500 text-sm"></i>
                                                <span class="text-xs font-medium text-blue-700">Múltiples
                                                    Monedas</span>
                                                <span
                                                    class="text-xs text-blue-600 ml-auto">{{ $solicitud->monedas_utilizadas }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <!-- Justificación y Observaciones -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Justificación -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-info text-white">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-alt"></i>
                        <h3 class="text-lg font-semibold">Justificación</h3>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $solicitud->justificacion }}</p>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            @if ($solicitud->observaciones)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-info text-white">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-sticky-note"></i>
                            <h3 class="text-lg font-semibold">Observaciones</h3>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $solicitud->observaciones }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Detalles de Productos -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <!-- Cabecera -->
            <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-boxes text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Productos Solicitados</h3>
                            <p class="text-sm text-blue-100">Aprobación Individual por Producto</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-white bg-white/20 px-3 py-1 rounded-full">
                        {{ $estadisticas['total_productos'] }} productos
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Encabezados -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Producto</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Categoría</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Cantidad</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Unidad</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Precio Unit.</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Moneda</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Estado</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>

                    <!-- Cuerpo de la tabla -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($solicitud->detalles as $detalle)
                            <!-- Fila principal del producto -->
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Producto -->
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $detalle->descripcion_producto }}
                                        </div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @if ($detalle->codigo_producto)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-barcode text-xs mr-1"></i>
                                                    {{ $detalle->codigo_producto }}
                                                </span>
                                            @endif
                                            @if ($detalle->marca)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-tag text-xs mr-1"></i>
                                                    {{ $detalle->marca }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Categoría -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center h-full">
                                        <span class="text-sm text-gray-600">{{ $detalle->categoria ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <!-- Cantidad -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center justify-center h-full">
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $detalle->cantidad }}</span>
                                        @if ($detalle->cantidad_aprobada && $detalle->cantidad_aprobada != $detalle->cantidad)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                <i class="fas fa-check text-xs mr-1"></i>
                                                {{ $detalle->cantidad_aprobada }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Unidad -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-sm text-gray-600">{{ $detalle->unidad ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <!-- Precio Unitario -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->precio_unitario_estimado, 2) }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->total_producto, 2) }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Moneda -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center h-full">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fas fa-money-bill-wave text-xs mr-1"></i>
                                            {{ $detalle->moneda->nombre ?? 'PEN' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center h-full">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                            @if ($detalle->estado == 'Aprobado por administración') bg-green-100 text-green-800 border border-green-200
                            @elseif($detalle->estado == 'Rechazado por administración') bg-red-100 text-red-800 border border-red-200
                            @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                                            <i
                                                class="fas fa-circle text-[8px] mr-2
                                @if ($detalle->estado == 'Aprobado por administración') text-green-500
                                @elseif($detalle->estado == 'Rechazado por administración') text-red-500
                                @else text-yellow-500 @endif"></i>
                                            {{ $detalle->estado }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center h-full">
                                        @if ($detalle->estado == 'pendiente' && in_array($solicitud->estado, ['pendiente', 'en_proceso']))
                                            <div class="flex flex-col gap-2">
                                                <button
                                                    onclick="abrirModalAprobar(
                                {{ $solicitud->idSolicitudCompra }}, 
                                {{ $detalle->idSolicitudCompraDetalle }},
                                '{{ $detalle->descripcion_producto }}',
                                {{ $detalle->cantidad }}
                            )"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-colors">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Aprobar
                                                </button>
                                                <button
                                                    onclick="abrirModalRechazar(
                                {{ $solicitud->idSolicitudCompra }}, 
                                {{ $detalle->idSolicitudCompraDetalle }},
                                '{{ $detalle->descripcion_producto }}'
                            )"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition-colors">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Rechazar
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <span class="text-xs text-gray-500 italic">Acción realizada</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Botón para detalles adicionales (en el otro extremo) -->
                            @if (
                                $detalle->justificacion_producto ||
                                    $detalle->especificaciones_tecnicas ||
                                    $detalle->proveedor_sugerido ||
                                    $detalle->observaciones_detalle)
                                <tr class="bg-gray-50">
                                    <td colspan="9" class="px-6 py-3">
                                        <div class="flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                                Este producto tiene información adicional
                                            </div>
                                            <button type="button"
                                                onclick="toggleDetalles({{ $detalle->idSolicitudCompraDetalle }})"
                                                class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                                <span>Ver detalles adicionales</span>
                                                <i class="fas fa-chevron-down text-xs transition-transform duration-300"
                                                    id="icon-{{ $detalle->idSolicitudCompraDetalle }}"></i>
                                            </button>
                                        </div>

                                        <!-- Detalles adicionales (ocultos por defecto) -->
                                        <div id="detalles-{{ $detalle->idSolicitudCompraDetalle }}"
                                            class="hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-300">
                                            <div class="space-y-3">
                                                @if ($detalle->justificacion_producto)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-file-alt text-green-600 text-sm"></i>
                                                            <h4
                                                                class="text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Justificación</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">
                                                            {{ $detalle->justificacion_producto }}</p>
                                                    </div>
                                                @endif

                                                @if ($detalle->especificaciones_tecnicas)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-cogs text-blue-600 text-sm"></i>
                                                            <h4
                                                                class="text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Especificaciones</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">
                                                            {{ $detalle->especificaciones_tecnicas }}</p>
                                                    </div>
                                                @endif

                                                @if ($detalle->proveedor_sugerido)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-truck text-purple-600 text-sm"></i>
                                                            <h4
                                                                class="text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Proveedor Sugerido</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">
                                                            {{ $detalle->proveedor_sugerido }}</p>
                                                    </div>
                                                @endif

                                                @if ($detalle->observaciones_detalle)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-sticky-note text-amber-600 text-sm"></i>
                                                            <h4
                                                                class="text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Observaciones</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">
                                                            {{ $detalle->observaciones_detalle }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                    <!-- Pie de tabla -->
                    <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Total
                                General:</td>
                            <td class="px-6 py-4">
                                <div class="text-lg font-bold text-blue-700 text-center">
                                    {{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->detalles->sum('total_producto'), 2) }}
                                </div>
                            </td>
                            <td colspan="3" class="px-6 py-4">
                                <div class="text-xs text-gray-500 text-center">
                                    {{ $estadisticas['detalles_aprobados'] }} aprobados •
                                    {{ $estadisticas['detalles_rechazados'] }} rechazados •
                                    {{ $estadisticas['detalles_pendientes'] }} pendientes
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Archivos Adjuntos -->
        @if ($solicitud->archivos && $solicitud->archivos->count() > 0)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Archivos Adjuntos</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($solicitud->archivos as $archivo)
                            <div
                                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $archivo->nombre_archivo }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ number_format($archivo->tamaño / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div x-data="modalAprobacion()" x-cloak>
        <!-- Overlay -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">

            <!-- Modal -->
            <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.away="close"
                class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Aprobar Producto</h3>
                                <p class="text-sm text-white">Complete los detalles de aprobación</p>
                            </div>
                        </div>
                        <button @click="close" class="text-white hover:text-green-100 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <!-- Información del producto -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Producto seleccionado:</h4>
                        <p class="text-gray-900 font-medium" x-text="productInfo"></p>
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="font-medium">Cantidad solicitada:</span>
                            <span x-text="cantidadSolicitada" class="ml-1"></span>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="aprobarArticuloSubmit">
                        <!-- Cantidad a aprobar -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-box text-gray-400 mr-1"></i>
                                Cantidad a aprobar
                            </label>
                            <div class="relative">
                                <input type="number" x-model="cantidadAprobada" :min="0"
                                    :max="cantidadSolicitada" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    placeholder="Ingrese la cantidad">
                                <div class="absolute right-3 top-2.5 text-sm text-gray-500">
                                    / <span x-text="cantidadSolicitada"></span>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                <span x-text="porcentajeAprobacion" class="font-medium text-green-600"></span>% de la
                                cantidad solicitada
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-sticky-note text-gray-400 mr-1"></i>
                                Observaciones (opcional)
                            </label>
                            <textarea x-model="observaciones" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                                placeholder="Agregue comentarios o detalles sobre esta aprobación"></textarea>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" @click="close"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="isLoading"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-lg shadow-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <template x-if="!isLoading">
                                    <i class="fas fa-check"></i>
                                </template>
                                <template x-if="isLoading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </template>
                                <span>Aprobar Producto</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>






    
    <!-- Modal para Rechazar Artículo -->
    <div x-data="modalRechazo()" x-cloak>
        <!-- Overlay -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">

            <!-- Modal -->
            <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.away="close"
                class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-red-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Rechazar Producto</h3>
                                <p class="text-sm text-white">Especifique el motivo del rechazo</p>
                            </div>
                        </div>
                        <button @click="close" class="text-white hover:text-red-100 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <!-- Información del producto -->
                    <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200">
                        <h4 class="text-sm font-semibold text-red-700 mb-2">Producto a rechazar:</h4>
                        <p class="text-gray-900 font-medium" x-text="productInfo"></p>
                        <div class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Esta acción no se puede deshacer
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="rechazarArticuloSubmit">
                        <!-- Motivo del rechazo -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-alt text-gray-400 mr-1"></i>
                                Motivo del rechazo *
                            </label>
                            <textarea x-model="motivo" required rows="4"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                                placeholder="Describa el motivo por el cual rechaza este producto..."></textarea>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" @click="close"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="isLoading"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-lg shadow-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <template x-if="!isLoading">
                                    <i class="fas fa-times"></i>
                                </template>
                                <template x-if="isLoading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </template>
                                <span>Rechazar Producto</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal para Estado Observado -->
<div x-data="modalObservado()" x-cloak>
    <!-- Overlay -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        
        <!-- Modal -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95" @click.away="close"
             class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
            
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Marcar como Observado</h3>
                            <p class="text-sm text-white">Complete los detalles de la observación</p>
                        </div>
                    </div>
                    <button @click="close" class="text-white hover:text-blue-100 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- Contenido -->
            <div class="p-6">
                <!-- Formulario -->
                <form @submit.prevent="observarSolicitudSubmit">
                    <!-- Fecha de Observación -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-gray-400 mr-1"></i>
                            Fecha de Observación *
                        </label>
                        <input type="date" x-model="fechaObservacion" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               :min="minDate">
                        <div class="mt-1 text-xs text-gray-500">
                            La fecha no puede ser anterior a hoy
                        </div>
                    </div>
                    
                    <!-- Comentario -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment-alt text-gray-400 mr-1"></i>
                            Comentario / Motivo de Observación *
                        </label>
                        <textarea x-model="comentario" required rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                  placeholder="Describa el motivo por el cual se observa esta solicitud..."></textarea>
                    </div>
                    
                    <!-- Fecha de Reprogramación -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-check text-gray-400 mr-1"></i>
                            Fecha de Reprogramación *
                        </label>
                        <input type="date" x-model="fechaReprogramacion" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               :min="minDate">
                        <div class="mt-1 text-xs text-gray-500">
                            Fecha cuando se reprogramará esta compra
                        </div>
                    </div>
                    
                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="close"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="isLoading"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg shadow-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <template x-if="!isLoading">
                                <i class="fas fa-check"></i>
                            </template>
                            <template x-if="isLoading">
                                <i class="fas fa-spinner fa-spin"></i>
                            </template>
                            <span>Marcar como Observado</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

<script>
// Almacenar las instancias de los modales
let modalAprobacionInstance = null;
let modalRechazoInstance = null;
let modalObservadoInstance = null;

// Funciones Alpine.js para los modales
function modalAprobacion() {
    return {
        isOpen: false,
        isLoading: false,
        idSolicitud: null,
        idDetalle: null,
        productInfo: '',
        cantidadSolicitada: 0,
        cantidadAprobada: 0,
        observaciones: '',

        get porcentajeAprobacion() {
            if (this.cantidadSolicitada === 0) return 0;
            return Math.round((this.cantidadAprobada / this.cantidadSolicitada) * 100);
        },

        init() {
            // Guardar referencia a esta instancia
            modalAprobacionInstance = this;
        },

        open(idSolicitud, idDetalle, productInfo, cantidadSolicitada) {
            this.idSolicitud = idSolicitud;
            this.idDetalle = idDetalle;
            this.productInfo = productInfo;
            this.cantidadSolicitada = parseInt(cantidadSolicitada);
            this.cantidadAprobada = this.cantidadSolicitada;
            this.observaciones = '';
            this.isOpen = true;
            document.body.classList.add('modal-open');
        },

        close() {
            this.isOpen = false;
            this.isLoading = false;
            document.body.classList.remove('modal-open');
        },

        async aprobarArticuloSubmit() {
            if (this.cantidadAprobada < 0 || this.cantidadAprobada > this.cantidadSolicitada) {
                toastr.error('La cantidad aprobada debe estar entre 0 y ' + this.cantidadSolicitada);
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch(
                    `/solicitudcompra/${this.idSolicitud}/articulo/${this.idDetalle}/aprobar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cantidad_aprobada: this.cantidadAprobada,
                            observaciones: this.observaciones
                        })
                    });

                const data = await response.json();

                if (data.success) {
                    toastr.success('Producto aprobado correctamente');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error('Error: ' + data.message);
                    this.isLoading = false;
                }
            } catch (error) {
                toastr.error('Error de conexión: ' + error);
                this.isLoading = false;
            }
        }
    };
}

function modalRechazo() {
    return {
        isOpen: false,
        isLoading: false,
        idSolicitud: null,
        idDetalle: null,
        productInfo: '',
        motivo: '',

        init() {
            // Guardar referencia a esta instancia
            modalRechazoInstance = this;
        },

        open(idSolicitud, idDetalle, productInfo) {
            this.idSolicitud = idSolicitud;
            this.idDetalle = idDetalle;
            this.productInfo = productInfo;
            this.motivo = '';
            this.isOpen = true;
            document.body.classList.add('modal-open');
        },

        close() {
            this.isOpen = false;
            this.isLoading = false;
            document.body.classList.remove('modal-open');
        },

        async rechazarArticuloSubmit() {
            if (!this.motivo.trim()) {
                toastr.error('Por favor, ingrese el motivo del rechazo');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch(
                    `/solicitudcompra/${this.idSolicitud}/articulo/${this.idDetalle}/rechazar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            observaciones: this.motivo
                        })
                    });

                const data = await response.json();

                if (data.success) {
                    toastr.success('Producto rechazado correctamente');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error('Error: ' + data.message);
                    this.isLoading = false;
                }
            } catch (error) {
                toastr.error('Error de conexión: ' + error);
                this.isLoading = false;
            }
        }
    };
}
// Modal para Observado - VERSIÓN OPTIMIZADA
function modalObservado() {
    return {
        isOpen: false,
        isLoading: false,
        idSolicitud: null,
        fechaObservacion: '',
        comentario: '',
        fechaReprogramacion: '',
        
        init() {
            // Inicializar fechas por defecto
            const today = this.getToday();
            this.fechaObservacion = today;
            
            // Calcular 7 días después
            const nextWeek = new Date(today);
            nextWeek.setDate(nextWeek.getDate() + 7);
            this.fechaReprogramacion = this.formatDate(nextWeek);
            
            // Guardar referencia
            modalObservadoInstance = this;
        },
        
        getToday() {
            const today = new Date();
            return today.toISOString().split('T')[0];
        },
        
        formatDate(date) {
            return date.toISOString().split('T')[0];
        },
        
        get minDate() {
            return this.getToday();
        },
        
        open(idSolicitud) {
            this.idSolicitud = idSolicitud;
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
            
            // Re-inicializar fechas cada vez que se abre
            setTimeout(() => {
                const today = this.getToday();
                if (!this.fechaObservacion) this.fechaObservacion = today;
                
                if (!this.fechaReprogramacion) {
                    const nextWeek = new Date(today);
                    nextWeek.setDate(nextWeek.getDate() + 7);
                    this.fechaReprogramacion = this.formatDate(nextWeek);
                }
            }, 50);
        },
        
        close() {
            this.isOpen = false;
            this.isLoading = false;
            document.body.style.overflow = '';
            
            // Reset después de 300ms (cuando la animación termina)
            setTimeout(() => {
                this.idSolicitud = null;
                this.comentario = '';
            }, 300);
        },
        
        async observarSolicitudSubmit() {
            // Validaciones
            if (!this.validarFormulario()) {
                return;
            }
            
            this.isLoading = true;
            
            try {
                const formData = {
                    fecha_observacion: this.fechaObservacion,
                    comentario: this.comentario,
                    fecha_reprogramacion: this.fechaReprogramacion
                };
                
                console.log('Enviando datos:', formData);
                
                const response = await fetch(`/solicitudcompra/${this.idSolicitud}/observar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    toastr.success('Solicitud marcada como observada correctamente');
                    this.close();
                    
                    // Recargar después de 1 segundo
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error('Error: ' + data.message);
                    this.isLoading = false;
                }
            } catch (error) {
                console.error('Error de conexión:', error);
                toastr.error('Error de conexión: ' + error.message);
                this.isLoading = false;
            }
        },
        
        validarFormulario() {
            // Validar fecha de observación
            if (!this.fechaObservacion) {
                toastr.error('Por favor, seleccione la fecha de observación');
                return false;
            }
            
            // Validar comentario
            if (!this.comentario.trim()) {
                toastr.error('Por favor, ingrese el comentario/motivo');
                return false;
            }
            
            if (this.comentario.trim().length < 10) {
                toastr.error('El comentario debe tener al menos 10 caracteres');
                return false;
            }
            
            // Validar fecha de reprogramación
            if (!this.fechaReprogramacion) {
                toastr.error('Por favor, seleccione la fecha de reprogramación');
                return false;
            }
            
            // Validar que la fecha de reprogramación no sea anterior a hoy
            const today = this.getToday();
            if (this.fechaReprogramacion < today) {
                toastr.error('La fecha de reprogramación no puede ser anterior a hoy');
                return false;
            }
            
            return true;
        }
    };
}
function abrirModalObservado(idSolicitud) {
    console.log('=== ABRIR MODAL OBSERVADO ===');
    console.log('ID recibido:', idSolicitud);
    console.log('Tipo:', typeof idSolicitud);
    
    // Si es undefined o null, intentar obtenerlo del contexto
    if (!idSolicitud && window.currentSolicitudId) {
        console.log('Usando ID de contexto:', window.currentSolicitudId);
        idSolicitud = window.currentSolicitudId;
    }
    
    // Validar que el ID sea válido
    if (!idSolicitud || isNaN(parseInt(idSolicitud))) {
        console.error('ID inválido:', idSolicitud);
        
        // Intentar obtener de la URL o datos de la página
        const urlMatch = window.location.pathname.match(/\/solicitudcompra\/(\d+)/);
        if (urlMatch && urlMatch[1]) {
            idSolicitud = urlMatch[1];
            console.log('ID obtenido de URL:', idSolicitud);
        } else {
            toastr.error('No se pudo obtener el ID de la solicitud');
            return;
        }
    }
    
    console.log('ID final a usar:', idSolicitud);
    
    // Usar instancia global si existe
    if (modalObservadoInstance) {
        modalObservadoInstance.open(idSolicitud);
        return;
    }
    
    // Buscar en el DOM
    setTimeout(() => {
        const alpineElement = document.querySelector('[x-data="modalObservado()"]');
        if (alpineElement && alpineElement.__x) {
            const modalInstance = alpineElement.__x.$data;
            modalInstance.open(idSolicitud);
            modalObservadoInstance = modalInstance;
        } else {
            console.warn('Modal no inicializado, usando SweetAlert');
            observarSolicitudFallback(idSolicitud);
        }
    }, 50);
}
// Fallback simplificado
function observarSolicitudFallback(idSolicitud) {
    Swal.fire({
        title: 'Marcar como Observado',
        html: `
            <div class="text-left">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Observación</label>
                    <input type="date" id="fechaObs" class="w-full px-3 py-2 border rounded" 
                           value="${new Date().toISOString().split('T')[0]}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                    <textarea id="comentarioObs" rows="3" class="w-full px-3 py-2 border rounded" 
                              placeholder="Motivo de la observación..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Reprogramación</label>
                    <input type="date" id="fechaReprog" class="w-full px-3 py-2 border rounded" 
                           value="${new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0]}">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Marcar como Observado',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const fechaObs = document.getElementById('fechaObs').value;
            const comentario = document.getElementById('comentarioObs').value;
            const fechaReprog = document.getElementById('fechaReprog').value;
            
            if (!fechaObs || !comentario || !fechaReprog) {
                Swal.showValidationMessage('Complete todos los campos');
                return false;
            }
            
            if (comentario.length < 10) {
                Swal.showValidationMessage('El comentario debe tener al menos 10 caracteres');
                return false;
            }
            
            return { fecha_observacion: fechaObs, comentario, fecha_reprogramacion: fechaReprog };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/solicitudcompra/${idSolicitud}/observar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(result.value)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error de conexión', 'error');
            });
        }
    });
}

// Función de fallback
function observarSolicitudAntiguo(idSolicitud) {
    const fechaObservacion = prompt('Fecha de observación (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
    const comentario = prompt('Comentario/Motivo de observación:');
    const fechaReprogramacion = prompt('Fecha de reprogramación (YYYY-MM-DD):', 
        new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0]);
    
    if (fechaObservacion && comentario && fechaReprogramacion) {
        fetch(`/solicitudcompra/${idSolicitud}/observar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                fecha_observacion: fechaObservacion,
                comentario: comentario,
                fecha_reprogramacion: fechaReprogramacion
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error de conexión: ' + error);
        });
    }
}

// Funciones globales para abrir los otros modales
function abrirModalAprobar(idSolicitud, idDetalle, productInfo, cantidadSolicitada) {
    // Esperar a que Alpine.js esté listo
    setTimeout(() => {
        if (modalAprobacionInstance) {
            modalAprobacionInstance.open(idSolicitud, idDetalle, productInfo, cantidadSolicitada);
        } else {
            // Si aún no está disponible, buscar en el DOM
            const alpineElement = document.querySelector('[x-data="modalAprobacion()"]');
            if (alpineElement && alpineElement.__x) {
                modalAprobacionInstance = alpineElement.__x.$data;
                modalAprobacionInstance.open(idSolicitud, idDetalle, productInfo, cantidadSolicitada);
            } else {
                console.error('No se encontró el modal de aprobación');
                // Fallback: usar el método antiguo
                aprobarArticuloAntiguo(idSolicitud, idDetalle);
            }
        }
    }, 100);
}

function abrirModalRechazar(idSolicitud, idDetalle, productInfo) {
    // Esperar a que Alpine.js esté listo
    setTimeout(() => {
        if (modalRechazoInstance) {
            modalRechazoInstance.open(idSolicitud, idDetalle, productInfo);
        } else {
            // Si aún no está disponible, buscar en el DOM
            const alpineElement = document.querySelector('[x-data="modalRechazo()"]');
            if (alpineElement && alpineElement.__x) {
                modalRechazoInstance = alpineElement.__x.$data;
                modalRechazoInstance.open(idSolicitud, idDetalle, productInfo);
            } else {
                console.error('No se encontró el modal de rechazo');
                // Fallback: usar el método antiguo
                rechazarArticuloAntiguo(idSolicitud, idDetalle);
            }
        }
    }, 100);
}

// Funciones de fallback (métodos antiguos)
function aprobarArticuloAntiguo(idSolicitud, idDetalle) {
    const cantidadSolicitada = obtenerCantidadSolicitada(idDetalle);
    const cantidad = prompt(`Cantidad a aprobar (solicitada: ${cantidadSolicitada}):`, cantidadSolicitada);
    const observaciones = prompt('Observaciones (opcional):');

    if (cantidad !== null) {
        fetch(`/solicitudcompra/${idSolicitud}/articulo/${idDetalle}/aprobar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cantidad_aprobada: cantidad,
                    observaciones: observaciones
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error);
            });
    }
}

function rechazarArticuloAntiguo(idSolicitud, idDetalle) {
    const observaciones = prompt('Motivo del rechazo:');

    if (observaciones !== null && observaciones.trim() !== '') {
        fetch(`/solicitudcompra/${idSolicitud}/articulo/${idDetalle}/rechazar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    observaciones: observaciones
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error);
            });
    }
}

// Funciones para gestión de estados
function cambiarEstado(idSolicitud, nuevoEstado) {
    const estadosLabels = {
        'completada': 'Completada',
        'observado': 'Observado',
        'presupuesto_aprobado': 'Presupuesto Aprobado',
        'pagado': 'Pagado',
        'finalizado': 'Finalizado',
        'en_proceso': 'En Proceso',
        'pendiente': 'Pendiente',
        'cancelada': 'Cancelada',
        'rechazada': 'Rechazada'
    };

    const label = estadosLabels[nuevoEstado] || nuevoEstado;

    // Mapear iconos según el estado
    const estadoIconos = {
        'completada': 'fas fa-check-circle',
        'observado': 'fas fa-eye',
        'presupuesto_aprobado': 'fas fa-file-invoice-dollar',
        'pagado': 'fas fa-credit-card',
        'finalizado': 'fas fa-flag-checkered',
        'en_proceso': 'fas fa-sync-alt',
        'pendiente': 'fas fa-clock',
        'cancelada': 'fas fa-times-circle',
        'rechazada': 'fas fa-ban'
    };

    const icono = estadoIconos[nuevoEstado] || 'fas fa-exchange-alt';

    // Mapear colores según el estado
    const estadoColores = {
        'completada': '#10b981',
        'observado': '#3b82f6',
        'presupuesto_aprobado': '#f59e0b',
        'pagado': '#3b82f6',
        'finalizado': '#6b7280',
        'en_proceso': '#8b5cf6',
        'pendiente': '#f97316',
        'cancelada': '#ef4444',
        'rechazada': '#dc2626'
    };

    const color = estadoColores[nuevoEstado] || '#3b82f6';

    Swal.fire({
        title: 'Cambiar Estado',
        html: `
    <div class="text-center">
        <div class="mb-4">
            <i class="${icono} text-4xl" style="color: ${color};"></i>
        </div>
        <p class="text-gray-700 mb-2">¿Está seguro de que desea cambiar el estado a:</p>
        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4" 
             style="background-color: ${color}20; color: ${color}; border: 1px solid ${color}50;">
            <i class="fas fa-circle text-xs mr-2" style="color: ${color};"></i>
            <strong>${label}</strong>
        </div>
        <p class="text-sm text-gray-500">Esta acción actualizará el estado de toda la solicitud.</p>
    </div>
`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-exchange-alt mr-2"></i>Sí, cambiar estado',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#ef4444',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl',
            title: 'text-xl font-semibold text-gray-900',
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger',
            actions: 'gap-3'
        },
        buttonsStyling: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`/solicitudcompra/${idSolicitud}/cambiar-estado`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        estado: nuevoEstado
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Error al cambiar el estado');
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            toastr.success('Estado cambiado exitosamente', '¡Éxito!', {
                timeOut: 3000,
                progressBar: true,
                closeButton: true,
                positionClass: 'toast-top-right',
                showMethod: 'slideDown',
                hideMethod: 'slideUp'
            });

            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    });
}

function cancelarSolicitud(idSolicitud) {
    Swal.fire({
        title: 'Cancelar Solicitud',
        html: `
    <div class="text-center">
        <p class="text-gray-700 mb-2">Por favor, ingrese el motivo de la cancelación:</p>
        <textarea id="motivo-cancelacion" 
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                  rows="4"
                  placeholder="Describa el motivo de la cancelación..."
                  required></textarea>
        <p class="text-xs text-gray-500 mt-2 text-left">Mínimo 10 caracteres</p>
    </div>
`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-ban mr-2"></i>Sí, cancelar solicitud',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>No, mantener',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl',
            title: 'text-xl font-semibold text-red-600',
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary',
            actions: 'gap-3'
        },
        buttonsStyling: false,
        focusConfirm: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const motivo = document.getElementById('motivo-cancelacion').value;

            if (!motivo.trim()) {
                Swal.showValidationMessage('Por favor, ingrese el motivo de la cancelación');
                return false;
            }

            if (motivo.length < 10) {
                Swal.showValidationMessage('El motivo debe tener al menos 10 caracteres');
                return false;
            }

            return fetch(`/solicitudcompra/${idSolicitud}/cancelar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        motivo: motivo
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Error al cancelar la solicitud');
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            toastr.warning('Solicitud cancelada exitosamente', 'Cancelación', {
                timeOut: 3000,
                progressBar: true,
                closeButton: true,
                positionClass: 'toast-top-right',
                showMethod: 'slideDown',
                hideMethod: 'slideUp'
            });

            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    });
}

function toggleDetalles(id) {
    const detalles = document.getElementById('detalles-' + id);
    const icon = document.getElementById('icon-' + id);

    if (detalles.classList.contains('hidden')) {
        detalles.classList.remove('hidden');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        detalles.classList.add('hidden');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

function obtenerCantidadSolicitada(idDetalle) {
    const elemento = document.querySelector(`[data-cantidad="${idDetalle}"]`);
    if (elemento) {
        const texto = elemento.textContent.trim();
        const match = texto.match(/^(\d+)/);
        return match ? match[1] : 0;
    }
    return 0;
}

// Configuración de toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};

// Inicialización cuando Alpine.js está listo
document.addEventListener('alpine:init', () => {
    console.log('Alpine.js inicializado');
    
    // Inicializar las instancias globales
    setTimeout(() => {
        const modalAprobacionEl = document.querySelector('[x-data="modalAprobacion()"]');
        const modalRechazoEl = document.querySelector('[x-data="modalRechazo()"]');
        const modalObservadoEl = document.querySelector('[x-data="modalObservado()"]');
        
        if (modalAprobacionEl && modalAprobacionEl.__x) {
            modalAprobacionInstance = modalAprobacionEl.__x.$data;
        }
        if (modalRechazoEl && modalRechazoEl.__x) {
            modalRechazoInstance = modalRechazoEl.__x.$data;
        }
        if (modalObservadoEl && modalObservadoEl.__x) {
            modalObservadoInstance = modalObservadoEl.__x.$data;
        }
        
        console.log('Instancias de modales inicializadas:', {
            aprobacion: !!modalAprobacionInstance,
            rechazo: !!modalRechazoInstance,
            observado: !!modalObservadoInstance
        });
    }, 500);
});

// Inicialización cuando DOM está cargado
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de evaluación de compras cargado correctamente');

    // Agregar estilos CSS para Alpine.js
    if (!document.querySelector('[data-alpine-styles]')) {
        const style = document.createElement('style');
        style.setAttribute('data-alpine-styles', '');
        style.textContent = `
            [x-cloak] {
                display: none !important;
            }
            
            /* Estilos para el input number */
            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            
            input[type="number"] {
                -moz-appearance: textfield;
            }
            
            /* Estilos para cuando hay un modal abierto */
            body.modal-open {
                overflow: hidden !important;
            }
        `;
        document.head.appendChild(style);
    }
});
</script>

</x-layout.default>
