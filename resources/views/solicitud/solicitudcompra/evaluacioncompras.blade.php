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
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                            <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-13z"/>
                            <path d="M3.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5h-5zm3 5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Evaluación de Solicitud de Compra</h1>
                        <p class="text-gray-600">Información completa de la solicitud {{ $solicitud->codigo_solicitud }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('solicitudcompra.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Volver al Listado
                    </a>

                    <!-- Botones de gestión de estado -->
                    @if (count($estadisticas['estados_siguientes']) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach ($estadisticas['estados_siguientes'] as $estado => $label)
                                @if ($estado == 'cancelada')
                                    <button onclick="cancelarSolicitud({{ $solicitud->idSolicitudCompra }})" class="inline-flex items-center px-4 py-2 bg-danger hover:bg-red-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                        {{ $label }}
                                    </button>
                                @elseif($estado == 'observador')
                                    <button onclick="marcarObservador({{ $solicitud->idSolicitudCompra }})" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 4zm0 5a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0v-.5A.5.5 0 0 1 8 9z"/>
                                        </svg>
                                        {{ $label }}
                                    </button>
                                @else
                                    <button onclick="cambiarEstado({{ $solicitud->idSolicitudCompra }}, '{{ $estado }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
                        <a href="{{ route('solicitudcompra.edit', $solicitud->idSolicitudCompra) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                            Editar Solicitud
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sección de Información de Observador (si aplica) -->
        @if ($solicitud->estado == 'observador')
        <div class="bg-white rounded-xl border border-yellow-200 shadow-sm mb-6 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Estado: Observador</h3>
                            <p class="text-sm text-yellow-100">Proceso detenido para revisión</p>
                        </div>
                    </div>
                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ \Carbon\Carbon::parse($solicitud->observador_fecha_aprobacion)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gradient-to-b from-yellow-50 to-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-comment text-yellow-600"></i>
                            Comentario del Observador
                        </h4>
                        <div class="bg-white p-4 rounded-lg border border-yellow-100 shadow-sm">
                            <p class="text-gray-700">{{ $solicitud->observador_comentario }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-question-circle text-yellow-600"></i>
                            Motivo de la Observación
                        </h4>
                        <div class="bg-white p-4 rounded-lg border border-yellow-100 shadow-sm">
                            <p class="text-gray-700">{{ $solicitud->observador_motivo }}</p>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-lg border border-yellow-100 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Fecha Programada para Aprobación</p>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($solicitud->observador_fecha_aprobacion)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg border border-yellow-100 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Fecha de Observación</p>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($solicitud->observador_fecha)->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg border border-yellow-100 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-history text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Tiempo Restante</p>
                                        <p class="text-sm font-semibold text-blue-600">
                                            {{ \Carbon\Carbon::parse($solicitud->observador_fecha_aprobacion)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-yellow-100 text-center">
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                        El proceso continuará automáticamente después de la fecha programada, o puede reactivarlo manualmente.
                    </p>
                    <button onclick="reactivarSolicitud({{ $solicitud->idSolicitudCompra }})" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                        <i class="fas fa-play mr-2"></i>
                        Reactivar Proceso Ahora
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Mapa de Estados -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Flujo de Estados</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center justify-between overflow-x-auto py-4">
                    @php
                        $estados = [
                            'pendiente' => ['icon' => 'fas fa-clock', 'color' => 'warning', 'label' => 'Pendiente'],
                            'en_proceso' => ['icon' => 'fas fa-sync-alt', 'color' => 'primary', 'label' => 'En Proceso'],
                            'observador' => ['icon' => 'fas fa-eye', 'color' => 'yellow', 'label' => 'Observador'],
                            'completada' => ['icon' => 'fas fa-check-circle', 'color' => 'secondary', 'label' => 'Completada'],
                            'presupuesto_aprobado' => ['icon' => 'fas fa-file-invoice-dollar', 'color' => 'warning-light', 'label' => 'Presupuesto Aprobado'],
                            'pagado' => ['icon' => 'fas fa-credit-card', 'color' => 'info', 'label' => 'Pagado'],
                            'finalizado' => ['icon' => 'fas fa-flag-checkered', 'color' => 'dark', 'label' => 'Finalizado'],
                            'cancelada' => ['icon' => 'fas fa-times-circle', 'color' => 'dark', 'label' => 'Cancelada'],
                            'rechazada' => ['icon' => 'fas fa-ban', 'color' => 'danger', 'label' => 'Rechazada'],
                        ];

                        $estadoActual = $solicitud->estado;
                        $estadosKeys = array_keys($estados);
                        $indiceActual = array_search($estadoActual, $estadosKeys);

                        $colorClasses = [
                            'warning' => ['bg' => 'bg-warning', 'bg-light' => 'bg-warning/20', 'text' => 'text-yellow-800', 'border' => 'border-warning'],
                            'primary' => ['bg' => 'bg-primary', 'bg-light' => 'bg-primary/20', 'text' => 'text-blue-800', 'border' => 'border-primary'],
                            'secondary' => ['bg' => 'bg-secondary', 'bg-light' => 'bg-secondary/20', 'text' => 'text-purple-800', 'border' => 'border-secondary'],
                            'warning-light' => ['bg' => 'bg-warning', 'bg-light' => 'bg-warning-light', 'text' => 'text-warning', 'border' => 'border-warning'],
                            'info' => ['bg' => 'bg-info', 'bg-light' => 'bg-info/20', 'text' => 'text-blue-600', 'border' => 'border-info'],
                            'dark' => ['bg' => 'bg-dark', 'bg-light' => 'bg-dark/20', 'text' => 'text-gray-800', 'border' => 'border-dark'],
                            'danger' => ['bg' => 'bg-danger', 'bg-light' => 'bg-danger/20', 'text' => 'text-red-800', 'border' => 'border-danger'],
                            'yellow' => ['bg' => 'bg-yellow-500', 'bg-light' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-500'],
                        ];
                    @endphp

                    @foreach ($estados as $estado => $info)
                        @php
                            $colorInfo = $colorClasses[$info['color']] ?? $colorClasses['warning'];
                            $estadoIndex = array_search($estado, $estadosKeys);
                        @endphp

                        <div class="flex flex-col items-center mx-2 min-w-20">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold
                                @if ($estado == $estadoActual) {{ $colorInfo['bg'] }} text-white border-2 {{ $colorInfo['border'] }} shadow-lg
                                @elseif($estadoIndex < $indiceActual)
                                    {{ $colorInfo['bg-light'] }} {{ $colorInfo['text'] }} border-2 {{ $colorInfo['border'] }}
                                @else
                                    bg-gray-100 text-gray-400 border-2 border-gray-300 @endif">
                                <i class="{{ $info['icon'] }}"></i>
                            </div>
                            <span class="mt-2 text-xs font-medium text-center 
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
                                $siguienteEstadoIndex = $estadoIndex + 1;
                                $siguienteEstadoKey = $estadosKeys[$siguienteEstadoIndex] ?? null;
                                $siguienteColorInfo = $siguienteEstadoKey ? $colorClasses[$estados[$siguienteEstadoKey]['color']] : null;
                            @endphp

                            <div class="flex-1 h-1 bg-gray-200 mx-1 mt-5">
                                <div class="h-1 transition-all duration-500
                                    @if ($siguienteEstadoIndex <= $indiceActual) {{ $siguienteColorInfo['bg'] ?? 'bg-gray-400' }} w-full
                                    @else
                                        bg-transparent w-0 @endif">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Leyenda de flujos -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                        <div class="font-semibold text-blue-800 mb-1 flex items-center gap-2">
                            <i class="fas fa-clipboard-list"></i>
                            Flujo Principal (Compra)
                        </div>
                        <div class="text-blue-600 text-xs">
                            Pendiente → En Proceso → Completada → Presupuesto Aprobado → Pagado → Finalizado
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                        <div class="font-semibold text-yellow-800 mb-1 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            Flujo de Observación
                        </div>
                        <div class="text-yellow-600 text-xs">
                            Puede observarse en cualquier estado para revisión posterior
                        </div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                        <div class="font-semibold text-red-800 mb-1 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i>
                            Flujo de Cancelación
                        </div>
                        <div class="text-red-600 text-xs">
                            Puede cancelarse en cualquier estado
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
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                            @if ($solicitud->estado == 'pendiente') bg-warning/10 text-warning-dark border border-warning
                            @elseif($solicitud->estado == 'en_proceso') bg-primary/10 text-primary-dark border border-primary
                            @elseif($solicitud->estado == 'observador') bg-yellow-100 text-yellow-800 border border-yellow-300
                            @elseif($solicitud->estado == 'completada') bg-secondary/10 text-secondary-dark border border-secondary
                            @elseif($solicitud->estado == 'presupuesto_aprobado') bg-warning-light/10 text-warning-light-dark border border-warning-light
                            @elseif($solicitud->estado == 'pagado') bg-info/10 text-info-dark border border-info
                            @elseif($solicitud->estado == 'finalizado') bg-dark/10 text-gray-800 border border-dark
                            @elseif($solicitud->estado == 'cancelada') bg-dark/10 text-gray-800 border border-dark
                            @elseif($solicitud->estado == 'rechazada') bg-danger/10 text-danger-dark border border-danger
                            @else bg-gray-100 text-gray-700 border border-gray-300 @endif">
                            <i class="fas fa-circle text-xs mr-2
                                @if ($solicitud->estado == 'pendiente') text-warning
                                @elseif($solicitud->estado == 'en_proceso') text-primary
                                @elseif($solicitud->estado == 'observador') text-yellow-500
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
                </div>
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
                        <div class="text-2xl font-bold text-green-700 mb-1">{{ $estadisticas['detalles_aprobados'] }}</div>
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
                        <div class="text-2xl font-bold text-red-700 mb-1">{{ $estadisticas['detalles_rechazados'] }}</div>
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
                            <span class="text-xs font-medium text-yellow-600 bg-white px-2 py-1 rounded">Pendiente</span>
                        </div>
                        <div class="text-2xl font-bold text-yellow-700 mb-1">{{ $estadisticas['detalles_pendientes'] }}</div>
                        <div class="text-xs text-yellow-600">
                            {{ $estadisticas['total_productos'] > 0 ? number_format(($estadisticas['detalles_pendientes'] / $estadisticas['total_productos']) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>

                <!-- Barra de progreso mejorada -->
                @if ($estadisticas['total_productos'] > 0)
                    @php
                        $porcentaje = (($estadisticas['detalles_aprobados'] + $estadisticas['detalles_rechazados']) / $estadisticas['total_productos']) * 100;
                    @endphp
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-tasks text-gray-400 text-sm"></i>
                                <span class="text-sm font-medium text-gray-700">Progreso de aprobación</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($porcentaje, 1) }}%</span>
                                <span class="text-xs text-gray-500">
                                    ({{ $estadisticas['detalles_aprobados'] + $estadisticas['detalles_rechazados'] }}/{{ $estadisticas['total_productos'] }})
                                </span>
                            </div>
                        </div>

                        <!-- Barra principal -->
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-1 overflow-hidden">
                            <div class="h-3 flex">
                                @if ($estadisticas['detalles_aprobados'] > 0)
                                    <div class="bg-green-500 transition-all duration-700 ease-out" style="width: {{ ($estadisticas['detalles_aprobados'] / $estadisticas['total_productos']) * 100 }}%"></div>
                                @endif
                                @if ($estadisticas['detalles_rechazados'] > 0)
                                    <div class="bg-red-500 transition-all duration-700 ease-out delay-100" style="width: {{ ($estadisticas['detalles_rechazados'] / $estadisticas['total_productos']) * 100 }}%"></div>
                                @endif
                                @if ($estadisticas['detalles_pendientes'] > 0)
                                    <div class="bg-yellow-400 transition-all duration-700 ease-out delay-200" style="width: {{ ($estadisticas['detalles_pendientes'] / $estadisticas['total_productos']) * 100 }}%"></div>
                                @endif
                            </div>
                        </div>

                        <!-- Leyenda -->
                        <div class="flex flex-wrap gap-3 mt-3 text-xs">
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 bg-green-500 rounded"></div>
                                <span class="text-gray-600">Aprobados ({{ $estadisticas['detalles_aprobados'] }})</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 bg-red-500 rounded"></div>
                                <span class="text-gray-600">Rechazados ({{ $estadisticas['detalles_rechazados'] }})</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 bg-yellow-400 rounded"></div>
                                <span class="text-gray-600">Pendientes ({{ $estadisticas['detalles_pendientes'] }})</span>
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
                                    <span class="ml-1">{{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i') }}</span>
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
                            <button onclick="cambiarEstado({{ $solicitud->idSolicitudCompra }}, 'completada')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                <i class="fas fa-check-circle"></i>
                                <span>Completar Evaluación</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Resto de tu blade (mantén todo igual desde aquí) -->
        <!-- Dashboard Compacto de Información -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Columna Principal (3/3) - Detalles + Resumen + Estado -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Cabecera Única -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-500">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
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
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Código</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-hashtag text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">{{ $solicitud->codigo_solicitud }}</p>
                                    </div>
                                </div>

                                <!-- Solicitante -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Solicitante</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">{{ $solicitud->solicitante }}</p>
                                    </div>
                                </div>

                                <!-- Área -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Área</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-building text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">{{ $solicitud->tipoArea->nombre ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Prioridad -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Prioridad</label>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full 
                                            @if (($solicitud->idPrioridad ?? 0) == 1) bg-green-500
                                            @elseif(($solicitud->idPrioridad ?? 0) == 2) bg-warning
                                            @elseif(($solicitud->idPrioridad ?? 0) == 3) bg-red-500
                                            @else bg-gray-400 @endif">
                                        </div>
                                        <p class="text-sm font-medium 
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
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha Requerida</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->fecha_requerida ? \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('d/m/Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Centro de Costo -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Centro de Costo</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-money-bill-alt text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">{{ $solicitud->centroCosto->nombre ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Proyecto Asociado -->
                                <div class="md:col-span-2 space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Proyecto Asociado</label>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-project-diagram text-gray-400 text-sm"></i>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $solicitud->proyecto_asociado ?? 'No especificado' }}
                                        </p>
                                        @if ($solicitud->proyecto_asociado)
                                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded">Asignado</span>
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
                                <div class="text-center p-4 rounded-lg bg-gradient-to-r from-gray-50 to-white border border-gray-200">
                                    <div class="text-xs text-gray-500 font-medium mb-2">Estado Actual</div>
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="
                                            @if ($solicitud->estado == 'completada') fas fa-check-circle text-green-500
                                            @elseif($solicitud->estado == 'rechazada') fas fa-times-circle text-red-500
                                            @elseif($solicitud->estado == 'en_proceso') fas fa-sync-alt text-blue-500
                                            @elseif($solicitud->estado == 'observador') fas fa-eye text-yellow-500
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
                                @if ($solicitud->fecha_aprobacion || $solicitud->observador_fecha)
                                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                                        <div class="text-xs text-gray-500 font-medium mb-2">
                                            @if($solicitud->estado == 'observador')
                                                Fecha de Observación
                                            @else
                                                Última Actualización
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($solicitud->estado == 'observador')
                                                        {{ \Carbon\Carbon::parse($solicitud->observador_fecha)->format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y') }}
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    @if($solicitud->estado == 'observador')
                                                        {{ \Carbon\Carbon::parse($solicitud->observador_fecha)->format('H:i') }} hrs
                                                    @else
                                                        {{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('H:i') }} hrs
                                                    @endif
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
                                                <div class="text-sm font-medium text-red-700 mb-1">Motivo de Rechazo</div>
                                                <p class="text-sm text-red-600">{{ $solicitud->motivo_rechazo }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Sección de Resumen Financiero (1/4) -->
                        <div class="p-6">
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
                                            <span class="text-sm text-gray-600">IVA (19%)</span>
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
                                        <span class="text-sm font-medium text-gray-900">{{ $solicitud->total_unidades }} unid.</span>
                                    </div>

                                    @if ($solicitud->multiple_monedas)
                                        <div class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border border-blue-100">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-globe text-blue-500 text-sm"></i>
                                                <span class="text-xs font-medium text-blue-700">Múltiples Monedas</span>
                                                <span class="text-xs text-blue-600 ml-auto">{{ $solicitud->monedas_utilizadas }}</span>
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
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Unidad</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Precio Unit.</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Moneda</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
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
                                        <div class="font-medium text-gray-900">{{ $detalle->descripcion_producto }}</div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @if ($detalle->codigo_producto)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-barcode text-xs mr-1"></i>
                                                    {{ $detalle->codigo_producto }}
                                                </span>
                                            @endif
                                            @if ($detalle->marca)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
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
                                        <span class="text-sm font-medium text-gray-900">{{ $detalle->cantidad }}</span>
                                        @if ($detalle->cantidad_aprobada && $detalle->cantidad_aprobada != $detalle->cantidad)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fas fa-money-bill-wave text-xs mr-1"></i>
                                            {{ $detalle->moneda->nombre ?? 'PEN' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center h-full">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                            @if ($detalle->estado == 'Aprobado por administración') bg-green-100 text-green-800 border border-green-200
                                            @elseif($detalle->estado == 'Rechazado por administración') bg-red-100 text-red-800 border border-red-200
                                            @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                                            <i class="fas fa-circle text-[8px] mr-2
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
                                                <button onclick="abrirModalAprobar({{ $solicitud->idSolicitudCompra }}, {{ $detalle->idSolicitudCompraDetalle }}, '{{ $detalle->descripcion_producto }}', {{ $detalle->cantidad }})" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-colors">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Aprobar
                                                </button>
                                                <button onclick="abrirModalRechazar({{ $solicitud->idSolicitudCompra }}, {{ $detalle->idSolicitudCompraDetalle }}, '{{ $detalle->descripcion_producto }}')" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition-colors">
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

                            <!-- Botón para detalles adicionales -->
                            @if ($detalle->justificacion_producto || $detalle->especificaciones_tecnicas || $detalle->proveedor_sugerido || $detalle->observaciones_detalle)
                                <tr class="bg-gray-50">
                                    <td colspan="9" class="px-6 py-3">
                                        <div class="flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                                Este producto tiene información adicional
                                            </div>
                                            <button type="button" onclick="toggleDetalles({{ $detalle->idSolicitudCompraDetalle }})" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                                <span>Ver detalles adicionales</span>
                                                <i class="fas fa-chevron-down text-xs transition-transform duration-300" id="icon-{{ $detalle->idSolicitudCompraDetalle }}"></i>
                                            </button>
                                        </div>

                                        <!-- Detalles adicionales (ocultos por defecto) -->
                                        <div id="detalles-{{ $detalle->idSolicitudCompraDetalle }}" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-300">
                                            <div class="space-y-3">
                                                @if ($detalle->justificacion_producto)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-file-alt text-green-600 text-sm"></i>
                                                            <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Justificación</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">{{ $detalle->justificacion_producto }}</p>
                                                    </div>
                                                @endif

                                                @if ($detalle->especificaciones_tecnicas)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-cogs text-blue-600 text-sm"></i>
                                                            <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Especificaciones</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">{{ $detalle->especificaciones_tecnicas }}</p>
                                                    </div>
                                                @endif

                                                @if ($detalle->proveedor_sugerido)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-truck text-purple-600 text-sm"></i>
                                                            <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Proveedor Sugerido</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">{{ $detalle->proveedor_sugerido }}</p>
                                                    </div>
                                                @endif

                                                @if ($detalle->observaciones_detalle)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <i class="fas fa-sticky-note text-amber-600 text-sm"></i>
                                                            <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Observaciones</h4>
                                                        </div>
                                                        <p class="text-xs text-gray-600 ml-6">{{ $detalle->observaciones_detalle }}</p>
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
                            <td colspan="5" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Total General:</td>
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
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $archivo->nombre_archivo }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($archivo->tamaño / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal para Marcar como Observador -->
    <div x-data="modalObservador()" x-cloak>
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.away="close" class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-eye text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Marcar como Observador</h3>
                                <p class="text-sm text-yellow-100">Detener el proceso para revisión posterior</p>
                            </div>
                        </div>
                        <button @click="close" class="text-white hover:text-yellow-100 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <!-- Información de la solicitud -->
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <h4 class="text-sm font-semibold text-yellow-700 mb-2">Solicitud seleccionada:</h4>
                        <p class="text-gray-900 font-medium">{{ $solicitud->codigo_solicitud }}</p>
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="font-medium">Solicitante:</span> {{ $solicitud->solicitante }}
                        </div>
                        <div class="mt-1 text-sm text-gray-600">
                            <span class="font-medium">Total:</span> {{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->total, 2) }}
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="marcarObservadorSubmit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Comentario -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-comment text-yellow-500 mr-1"></i>
                                    Comentario del Observador *
                                </label>
                                <textarea x-model="comentario" required rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors resize-none" placeholder="Describa por qué se marca como observador..."></textarea>
                                <div class="mt-1 text-xs text-gray-500">Mínimo 10 caracteres</div>
                            </div>

                            <!-- Fecha de Aprobación Programada -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-check text-yellow-500 mr-1"></i>
                                    Fecha de Aprobación Programada *
                                </label>
                                <input type="date" x-model="fechaAprobacion" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                <div class="mt-1 text-xs text-gray-500">Seleccione una fecha futura</div>
                            </div>

                            <!-- Motivo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-question-circle text-yellow-500 mr-1"></i>
                                    Motivo de la Observación *
                                </label>
                                <select x-model="motivo" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                    <option value="">Seleccione un motivo</option>
                                    <option value="Falta de presupuesto">Falta de presupuesto</option>
                                    <option value="Esperando aprobación superior">Esperando aprobación superior</option>
                                    <option value="Necesita más información">Necesita más información</option>
                                    <option value="Esperando cotizaciones">Esperando cotizaciones</option>
                                    <option value="Revisión de especificaciones">Revisión de especificaciones</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <!-- Si selecciona "Otro" -->
                            <template x-if="motivo === 'Otro'">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-edit text-yellow-500 mr-1"></i>
                                        Especifique el motivo *
                                    </label>
                                    <input type="text" x-model="motivoOtro" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" placeholder="Describa el motivo específico...">
                                </div>
                            </template>
                        </div>

                        <!-- Resumen -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Resumen de la acción:</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Estado actual:</span>
                                    <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nuevo estado:</span>
                                    <span class="font-medium text-yellow-600">Observador</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Fecha de reactivación:</span>
                                    <span class="font-medium text-gray-900" x-text="formattedDate"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Días de espera:</span>
                                    <span class="font-medium text-gray-900" x-text="diasEspera"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Advertencia -->
                        <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                                <div>
                                    <p class="text-sm text-red-700 font-medium">Esta acción detendrá todo el proceso de compra hasta la fecha programada.</p>
                                    <p class="text-xs text-red-600 mt-1">No se podrán aprobar/rechazar artículos mientras esté en estado observador.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                            <button type="button" @click="close" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="isLoading" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 rounded-lg shadow-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <template x-if="!isLoading">
                                    <i class="fas fa-eye"></i>
                                </template>
                                <template x-if="isLoading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </template>
                                <span>Marcar como Observador</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales de Aprobación y Rechazo (mantén los que ya tienes) -->
    <div x-data="modalAprobacion()" x-cloak>
        <!-- Mantén tu modal de aprobación actual -->
    </div>
    
    <div x-data="modalRechazo()" x-cloak>
        <!-- Mantén tu modal de rechazo actual -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Funciones Alpine.js para los modales
        function modalObservador() {
            return {
                isOpen: false,
                isLoading: false,
                idSolicitud: null,
                comentario: '',
                fechaAprobacion: '',
                motivo: '',
                motivoOtro: '',

                get formattedDate() {
                    if (!this.fechaAprobacion) return 'No seleccionada';
                    const date = new Date(this.fechaAprobacion);
                    return date.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                get diasEspera() {
                    if (!this.fechaAprobacion) return '0 días';
                    const hoy = new Date();
                    const fecha = new Date(this.fechaAprobacion);
                    const diffTime = fecha - hoy;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    return diffDays + ' día(s)';
                },

                get motivoFinal() {
                    return this.motivo === 'Otro' ? this.motivoOtro : this.motivo;
                },

                init() {
                    // Configurar fecha mínima (mañana)
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    this.fechaAprobacion = tomorrow.toISOString().split('T')[0];
                },

                open(idSolicitud) {
                    this.idSolicitud = idSolicitud;
                    this.comentario = '';
                    this.motivo = '';
                    this.motivoOtro = '';
                    
                    // Configurar fecha mínima (mañana)
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    this.fechaAprobacion = tomorrow.toISOString().split('T')[0];
                    
                    this.isOpen = true;
                    document.body.classList.add('modal-open');
                },

                close() {
                    this.isOpen = false;
                    this.isLoading = false;
                    document.body.classList.remove('modal-open');
                },

                async marcarObservadorSubmit() {
                    // Validaciones
                    if (!this.comentario.trim() || this.comentario.length < 10) {
                        toastr.error('El comentario debe tener al menos 10 caracteres');
                        return;
                    }

                    if (!this.fechaAprobacion) {
                        toastr.error('Seleccione una fecha de aprobación programada');
                        return;
                    }

                    if (!this.motivo) {
                        toastr.error('Seleccione un motivo de observación');
                        return;
                    }

                    if (this.motivo === 'Otro' && !this.motivoOtro.trim()) {
                        toastr.error('Especifique el motivo de observación');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const response = await fetch(`/solicitudcompra/${this.idSolicitud}/marcar-observador`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                observador_comentario: this.comentario,
                                observador_fecha_aprobacion: this.fechaAprobacion,
                                observador_motivo: this.motivoFinal
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success('Solicitud marcada como observador correctamente');
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

        // Mantén tus funciones globales actuales y agrega esta nueva
        function marcarObservador(idSolicitud) {
            const modalElement = document.querySelector('[x-data="modalObservador()"]');
            if (modalElement && modalElement.__x) {
                modalElement.__x.$data.open(idSolicitud);
            } else {
                // Fallback usando SweetAlert2
                Swal.fire({
                    title: 'Marcar como Observador',
                    html: `
                        <div class="text-left">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comentario *</label>
                                <textarea id="comentario" class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="3" required placeholder="Describa por qué se marca como observador..."></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Aprobación Programada *</label>
                                <input type="date" id="fechaAprobacion" class="w-full px-3 py-2 border border-gray-300 rounded-md" min="${new Date(Date.now() + 86400000).toISOString().split('T')[0]}" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo *</label>
                                <select id="motivo" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                                    <option value="">Seleccione un motivo</option>
                                    <option value="Falta de presupuesto">Falta de presupuesto</option>
                                    <option value="Esperando aprobación superior">Esperando aprobación superior</option>
                                    <option value="Necesita más información">Necesita más información</option>
                                    <option value="Esperando cotizaciones">Esperando cotizaciones</option>
                                    <option value="Revisión de especificaciones">Revisión de especificaciones</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div id="otroMotivoContainer" class="hidden mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Especifique el motivo *</label>
                                <input type="text" id="motivoOtro" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Describa el motivo específico...">
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Marcar como Observador',
                    cancelButtonText: 'Cancelar',
                    preConfirm: () => {
                        const comentario = document.getElementById('comentario').value;
                        const fechaAprobacion = document.getElementById('fechaAprobacion').value;
                        const motivo = document.getElementById('motivo').value;
                        const motivoOtro = motivo === 'Otro' ? document.getElementById('motivoOtro').value : motivo;

                        if (!comentario || comentario.length < 10) {
                            Swal.showValidationMessage('El comentario debe tener al menos 10 caracteres');
                            return false;
                        }

                        if (!fechaAprobacion) {
                            Swal.showValidationMessage('Seleccione una fecha de aprobación programada');
                            return false;
                        }

                        if (!motivo) {
                            Swal.showValidationMessage('Seleccione un motivo de observación');
                            return false;
                        }

                        if (motivo === 'Otro' && !motivoOtro.trim()) {
                            Swal.showValidationMessage('Especifique el motivo de observación');
                            return false;
                        }

                        return {
                            observador_comentario: comentario,
                            observador_fecha_aprobacion: fechaAprobacion,
                            observador_motivo: motivo === 'Otro' ? motivoOtro : motivo
                        };
                    },
                    didOpen: () => {
                        const motivoSelect = document.getElementById('motivo');
                        motivoSelect.addEventListener('change', function() {
                            const otroContainer = document.getElementById('otroMotivoContainer');
                            if (this.value === 'Otro') {
                                otroContainer.classList.remove('hidden');
                            } else {
                                otroContainer.classList.add('hidden');
                            }
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/solicitudcompra/${idSolicitud}/marcar-observador`, {
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
                                toastr.success('Solicitud marcada como observador correctamente');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr.error('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            toastr.error('Error de conexión: ' + error);
                        });
                    }
                });
            }
        }

        function reactivarSolicitud(idSolicitud) {
            Swal.fire({
                title: 'Reactivar Solicitud',
                html: `
                    <div class="text-center">
                        <i class="fas fa-play-circle text-blue-500 text-4xl mb-4"></i>
                        <p class="text-gray-700 mb-2">¿Está seguro de que desea reactivar esta solicitud?</p>
                        <p class="text-sm text-gray-500">El proceso continuará desde donde se detuvo.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-play mr-2"></i>Sí, reactivar',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                confirmButtonColor: '#3b82f6',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-xl font-semibold text-gray-900'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Usar la función cambiarEstado existente para volver a en_proceso
                    cambiarEstado(idSolicitud, 'en_proceso');
                }
            });
        }

        // Mantén todas tus funciones JavaScript existentes
        // (abrirModalAprobar, abrirModalRechazar, cambiarEstado, cancelarSolicitud, etc.)

        // Actualizar la función cambiarEstado para manejar el estado observador
        function cambiarEstado(idSolicitud, nuevoEstado) {
            const estadosLabels = {
                'completada': 'Completada',
                'presupuesto_aprobado': 'Presupuesto Aprobado',
                'pagado': 'Pagado',
                'finalizado': 'Finalizado',
                'en_proceso': 'En Proceso',
                'observador': 'Observador',
                'pendiente': 'Pendiente',
                'cancelada': 'Cancelada',
                'rechazada': 'Rechazada'
            };

            const label = estadosLabels[nuevoEstado] || nuevoEstado;

            // Si es observador, usar el modal especial
            if (nuevoEstado === 'observador') {
                marcarObservador(idSolicitud);
                return;
            }

            // Para otros estados, continuar con el proceso normal
            const estadoIconos = {
                'completada': 'fas fa-check-circle',
                'presupuesto_aprobado': 'fas fa-file-invoice-dollar',
                'pagado': 'fas fa-credit-card',
                'finalizado': 'fas fa-flag-checkered',
                'en_proceso': 'fas fa-sync-alt',
                'observador': 'fas fa-eye',
                'pendiente': 'fas fa-clock',
                'cancelada': 'fas fa-times-circle',
                'rechazada': 'fas fa-ban'
            };

            const icono = estadoIconos[nuevoEstado] || 'fas fa-exchange-alt';

            const estadoColores = {
                'completada': '#10b981',
                'presupuesto_aprobado': '#f59e0b',
                'pagado': '#3b82f6',
                'finalizado': '#6b7280',
                'en_proceso': '#8b5cf6',
                'observador': '#f59e0b',
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
                    title: 'text-xl font-semibold text-gray-900'
                },
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/solicitudcompra/${idSolicitud}/cambiar-estado`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ estado: nuevoEstado })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Error en la respuesta del servidor');
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) throw new Error(data.message || 'Error al cambiar el estado');
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Error: ${error.message}`);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.success('Estado cambiado exitosamente');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            });
        }

        // Configuración de toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Sistema de evaluación de compras con estado observador cargado correctamente');

            // Agregar estilos CSS para Alpine.js si no existen
            if (!document.querySelector('[data-alpine-styles]')) {
                const style = document.createElement('style');
                style.setAttribute('data-alpine-styles', '');
                style.textContent = `
                    [x-cloak] { display: none !important; }
                    body.modal-open { overflow: hidden !important; }
                    input[type="number"]::-webkit-inner-spin-button,
                    input[type="number"]::-webkit-outer-spin-button {
                        -webkit-appearance: none;
                        margin: 0;
                    }
                    input[type="number"] {
                        -moz-appearance: textfield;
                    }
                `;
                document.head.appendChild(style);
            }
        });
    </script>

</x-layout.default>