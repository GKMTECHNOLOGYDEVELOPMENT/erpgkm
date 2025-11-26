<x-layout.default>

<link rel="stylesheet" href="{{ asset('assets/css/solicitudcompra.css') }}">

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="title-section">
            <div class="title-with-icon">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-13z"/>
                        <path d="M3.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5h-5zm3 5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2z"/>
                    </svg>
                </div>
                <div>
                    <h1>Evaluación de Solicitud de Compra</h1>
                    <p>Información completa de la solicitud {{ $solicitud->codigo_solicitud }}</p>
                </div>
            </div>
        </div>
        <div class="action-buttons">
            <a href="{{ route('solicitudcompra.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Volver al Listado
            </a>
            
            <!-- Botones de gestión de estado -->
            @if($estadisticas['puede_avanzar_estado'] && count($estadisticas['estados_siguientes']) > 0)
                <div class="flex space-x-2">
                    @foreach($estadisticas['estados_siguientes'] as $estado => $label)
                        @if($estado == 'cancelada')
                            <button onclick="cancelarSolicitud({{ $solicitud->idSolicitudCompra }})" 
                                    class="btn btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                                {{ $label }}
                            </button>
                        @else
                            <button onclick="cambiarEstado({{ $solicitud->idSolicitudCompra }}, '{{ $estado }}')" 
                                    class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                                {{ $label }}
                            </button>
                        @endif
                    @endforeach
                </div>
            @endif
            
            @if($solicitud->estado == 'pendiente')
                <a href="{{ route('solicitudcompra.edit', $solicitud->idSolicitudCompra) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                    Editar Solicitud
                </a>
            @endif
        </div>
    </div>

    <!-- Mapa de Estados -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">Flujo de Estados</h3>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between overflow-x-auto py-4">
                @php
                    $estados = [
                        'pendiente' => ['icon' => '⏳', 'color' => 'yellow', 'label' => 'Pendiente'],
                        'en_proceso' => ['icon' => '🔄', 'color' => 'blue', 'label' => 'En Proceso'], 
                        'completada' => ['icon' => '✅', 'color' => 'green', 'label' => 'Completada'],
                        'presupuesto_aprobado' => ['icon' => '💰', 'color' => 'purple', 'label' => 'Presupuesto Aprobado'],
                        'pagado' => ['icon' => '💳', 'color' => 'indigo', 'label' => 'Pagado'],
                        'finalizado' => ['icon' => '🏁', 'color' => 'green', 'label' => 'Finalizado'],
                        'cancelada' => ['icon' => '❌', 'color' => 'red', 'label' => 'Cancelada'],
                        'rechazada' => ['icon' => '🚫', 'color' => 'red', 'label' => 'Rechazada']
                    ];
                    
                    $estadoActual = $solicitud->estado;
                    $estadosKeys = array_keys($estados);
                    $indiceActual = array_search($estadoActual, $estadosKeys);
                @endphp
                
                @foreach($estados as $estado => $info)
                    <div class="flex flex-col items-center mx-2 min-w-20">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold
                            @if($estado == $estadoActual) 
                                bg-{{ $info['color'] }}-500 text-white border-2 border-{{ $info['color'] }}-600 shadow-lg
                            @elseif(array_search($estado, $estadosKeys) < $indiceActual) 
                                bg-{{ $info['color'] }}-100 text-{{ $info['color'] }}-800 border-2 border-{{ $info['color'] }}-300
                            @else 
                                bg-gray-100 text-gray-400 border-2 border-gray-300
                            @endif">
                            {{ $info['icon'] }}
                        </div>
                        <span class="mt-2 text-xs font-medium text-center 
                            @if($estado == $estadoActual) 
                                text-{{ $info['color'] }}-600 font-bold
                            @elseif(array_search($estado, $estadosKeys) < $indiceActual) 
                                text-{{ $info['color'] }}-600
                            @else 
                                text-gray-400
                            @endif">
                            {{ $info['label'] }}
                        </span>
                        @if($estado == $estadoActual)
                        <div class="w-2 h-2 bg-{{ $info['color'] }}-500 rounded-full mt-1 animate-pulse"></div>
                        @endif
                    </div>
                    
                    @if(!$loop->last)
                        <div class="flex-1 h-1 bg-gray-200 mx-1 mt-5">
                            <div class="h-1 bg-{{ $info['color'] }}-500 transition-all duration-500
                                @if(array_search($estado, $estadosKeys) < $indiceActual) 
                                    w-full
                                @else 
                                    w-0
                                @endif">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            <!-- Leyenda de flujos -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                    <div class="font-semibold text-blue-800 mb-1">📋 Flujo Principal (Compra)</div>
                    <div class="text-blue-600 text-xs">
                        Pendiente → En Proceso → Completada → Presupuesto Aprobado → Pagado → Finalizado
                    </div>
                </div>
                <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                    <div class="font-semibold text-red-800 mb-1">🚫 Flujo de Cancelación</div>
                    <div class="text-red-600 text-xs">
                        Puede cancelarse en cualquier estado después de "Completada"
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Estado -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">Estado de Aprobación</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $estadisticas['total_productos'] }}</div>
                    <div class="text-sm text-gray-600">Total Productos</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $estadisticas['detalles_aprobados'] }}</div>
                    <div class="text-sm text-gray-600">Aprobados</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $estadisticas['detalles_rechazados'] }}</div>
                    <div class="text-sm text-gray-600">Rechazados</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $estadisticas['detalles_pendientes'] }}</div>
                    <div class="text-sm text-gray-600">Pendientes</div>
                </div>
            </div>
            
            <!-- Barra de progreso -->
            @if($estadisticas['total_productos'] > 0)
            <div class="mt-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Progreso de aprobación</span>
                    <span>{{ number_format(($estadisticas['detalles_aprobados'] + $estadisticas['detalles_rechazados']) / $estadisticas['total_productos'] * 100, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ ($estadisticas['detalles_aprobados'] + $estadisticas['detalles_rechazados']) / $estadisticas['total_productos'] * 100 }}%">
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Estado general y acciones -->
            <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        @if($solicitud->estado == 'completada') bg-green-100 text-green-800
                        @elseif($solicitud->estado == 'rechazada') bg-red-100 text-red-800
                        @elseif($solicitud->estado == 'en_proceso') bg-blue-100 text-blue-800
                        @elseif($solicitud->estado == 'presupuesto_aprobado') bg-purple-100 text-purple-800
                        @elseif($solicitud->estado == 'pagado') bg-indigo-100 text-indigo-800
                        @elseif($solicitud->estado == 'finalizado') bg-green-100 text-green-800
                        @elseif($solicitud->estado == 'cancelada') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        Estado Actual: {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                    </span>
                    
                    @if($solicitud->fecha_aprobacion)
                    <span class="text-xs text-gray-500">
                        📅 {{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i') }}
                    </span>
                    @endif
                </div>
                
                @if($estadisticas['puede_avanzar_estado'] && $solicitud->estado == 'en_proceso')
                <button onclick="cambiarEstado({{ $solicitud->idSolicitudCompra }}, 'completada')" 
                        class="btn btn-success text-sm">
                    ✅ Completar Evaluación
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Información Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Información Básica -->
        <div class="col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información de la Solicitud</h3>
                    <div class="card-status status-{{ $solicitud->estado }}">
                        {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="detail-group">
                            <label class="detail-label">Código de Solicitud</label>
                            <p class="detail-value">{{ $solicitud->codigo_solicitud }}</p>
                        </div>
                        <div class="detail-group">
                            <label class="detail-label">Solicitante</label>
                            <p class="detail-value">{{ $solicitud->solicitante }}</p>
                        </div>
                        <div class="detail-group">
                            <label class="detail-label">Área</label>
                            <p class="detail-value">{{ $solicitud->tipoArea->nombre ?? 'N/A' }}</p>
                        </div>
                        <div class="detail-group">
                            <label class="detail-label">Prioridad</label>
                            <p class="detail-value">
                                <span class="priority-badge priority-{{ $solicitud->prioridad->nivel ?? 'medium' }}">
                                    {{ $solicitud->prioridad->nombre ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div class="detail-group">
                            <label class="detail-label">Fecha Requerida</label>
                            <p class="detail-value">{{ $solicitud->fecha_requerida ? \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="detail-group">
                            <label class="detail-label">Centro de Costo</label>
                            <p class="detail-value">{{ $solicitud->centroCosto->nombre ?? 'N/A' }}</p>
                        </div>
                        <div class="detail-group col-span-2">
                            <label class="detail-label">Proyecto Asociado</label>
                            <p class="detail-value">{{ $solicitud->proyecto_asociado ?? 'No especificado' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="col-span-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Resumen Financiero</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">{{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">IVA (19%):</span>
                            <span class="font-semibold">{{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->iva, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t pt-2">
                            <span class="text-gray-800 font-bold">Total:</span>
                            <span class="text-lg font-bold text-primary">{{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Unidades:</span>
                            <span class="font-semibold">{{ $solicitud->total_unidades }}</span>
                        </div>
                        @if($solicitud->multiple_monedas)
                        <div class="flex justify-between items-center bg-blue-50 p-2 rounded">
                            <span class="text-gray-600 text-sm">Monedas utilizadas:</span>
                            <span class="font-semibold text-blue-600 text-sm">{{ $solicitud->monedas_utilizadas }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información de Aprobación -->
            @if(in_array($solicitud->estado, ['aprobada', 'rechazada', 'cancelada', 'completada', 'presupuesto_aprobado', 'pagado', 'finalizado']))
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Información de Estado</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-2">
                        <div class="detail-group">
                            <label class="detail-label">Estado</label>
                            <p class="detail-value">
                                <span class="status-{{ $solicitud->estado }}">
                                    {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                                </span>
                            </p>
                        </div>
                        @if($solicitud->fecha_aprobacion)
                        <div class="detail-group">
                            <label class="detail-label">Fecha de Cambio</label>
                            <p class="detail-value">{{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        @if($solicitud->motivo_rechazo)
                        <div class="detail-group">
                            <label class="detail-label">Motivo</label>
                            <p class="detail-value text-red-600">{{ $solicitud->motivo_rechazo }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Justificación y Observaciones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Justificación</h3>
            </div>
            <div class="card-body">
                <p class="text-gray-700">{{ $solicitud->justificacion }}</p>
            </div>
        </div>

        @if($solicitud->observaciones)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Observaciones</h3>
            </div>
            <div class="card-body">
                <p class="text-gray-700">{{ $solicitud->observaciones }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Detalles de Productos -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">Productos Solicitados - Aprobación Individual</h3>
            <span class="text-sm text-gray-500">{{ $estadisticas['total_productos'] }} productos</span>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moneda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($solicitud->detalles as $detalle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $detalle->descripcion_producto }}</div>
                                    @if($detalle->codigo_producto)
                                    <div class="text-sm text-gray-500">Código: {{ $detalle->codigo_producto }}</div>
                                    @endif
                                    @if($detalle->marca)
                                    <div class="text-sm text-gray-500">Marca: {{ $detalle->marca }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $detalle->categoria ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-cantidad="{{ $detalle->idSolicitudCompraDetalle }}">
                                {{ $detalle->cantidad }}
                                @if($detalle->cantidad_aprobada && $detalle->cantidad_aprobada != $detalle->cantidad)
                                <br><span class="text-xs text-green-600">(Aprobado: {{ $detalle->cantidad_aprobada }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $detalle->unidad ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->precio_unitario_estimado, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->total_producto, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $detalle->moneda->nombre ?? 'PEN' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($detalle->estado == 'Aprobado por administración') bg-green-100 text-green-800
                                    @elseif($detalle->estado == 'Rechazado por administración') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $detalle->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($detalle->estado == 'pendiente' && in_array($solicitud->estado, ['pendiente', 'en_proceso']))
<div class="flex space-x-2">
    <button onclick="aprobarArticulo({{ $solicitud->idSolicitudCompra }}, {{ $detalle->idSolicitudCompraDetalle }})" 
            class="text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded border border-green-200 hover:bg-green-100 transition-colors">
        Aprobar por Administración
    </button>
    <button onclick="rechazarArticulo({{ $solicitud->idSolicitudCompra }}, {{ $detalle->idSolicitudCompraDetalle }})" 
            class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded border border-red-200 hover:bg-red-100 transition-colors">
        Rechazar por Administración
    </button>
</div>
@else
<span class="text-gray-500 text-xs">Acción realizada</span>
@if($detalle->observaciones_detalle)
<br><span class="text-xs text-gray-600" title="{{ $detalle->observaciones_detalle }}">📝</span>
@endif
@endif
                            </td>
                        </tr>
                        @if($detalle->justificacion_producto || $detalle->especificaciones_tecnicas)
                        <tr>
                            <td colspan="9" class="px-6 py-3 bg-gray-50 text-sm text-gray-600">
                                @if($detalle->justificacion_producto)
                                <div><strong>Justificación:</strong> {{ $detalle->justificacion_producto }}</div>
                                @endif
                                @if($detalle->especificaciones_tecnicas)
                                <div class="mt-1"><strong>Especificaciones:</strong> {{ $detalle->especificaciones_tecnicas }}</div>
                                @endif
                                @if($detalle->proveedor_sugerido)
                                <div class="mt-1"><strong>Proveedor Sugerido:</strong> {{ $detalle->proveedor_sugerido }}</div>
                                @endif
                                @if($detalle->observaciones_detalle)
                                <div class="mt-1"><strong>Observaciones:</strong> {{ $detalle->observaciones_detalle }}</div>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Totales:</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                {{ $solicitud->moneda_simbolo ?? 'S/' }}{{ number_format($solicitud->detalles->sum('total_producto'), 2) }}
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Archivos Adjuntos -->
    @if($solicitud->archivos && $solicitud->archivos->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Archivos Adjuntos</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($solicitud->archivos as $archivo)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $archivo->nombre_archivo }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($archivo->tamaño / 1024, 2) }} KB</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" 
                       target="_blank" 
                       class="text-primary hover:text-primary-dark transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Funciones existentes para aprobar/rechazar artículos
function aprobarArticulo(idSolicitud, idDetalle) {
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

function rechazarArticulo(idSolicitud, idDetalle) {
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

// Nuevas funciones para gestión de estados
function cambiarEstado(idSolicitud, nuevoEstado) {
    const estadosLabels = {
        'completada': 'Completada',
        'presupuesto_aprobado': 'Presupuesto Aprobado', 
        'pagado': 'Pagado',
        'finalizado': 'Finalizado'
    };
    
    const label = estadosLabels[nuevoEstado] || nuevoEstado;
    
    if (confirm(`¿Está seguro de que desea cambiar el estado a "${label}"?`)) {
        fetch(`/solicitudcompra/${idSolicitud}/cambiar-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                estado: nuevoEstado
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

function cancelarSolicitud(idSolicitud) {
    const motivo = prompt('Por favor, ingrese el motivo de la cancelación:');
    
    if (motivo !== null && motivo.trim() !== '') {
        fetch(`/solicitudcompra/${idSolicitud}/cancelar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                motivo: motivo
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

function obtenerCantidadSolicitada(idDetalle) {
    const elemento = document.querySelector(`[data-cantidad="${idDetalle}"]`);
    if (elemento) {
        const texto = elemento.textContent.trim();
        const match = texto.match(/^(\d+)/);
        return match ? match[1] : 0;
    }
    return 0;
}

// Mostrar mensaje de carga durante las peticiones
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de evaluación de compras cargado correctamente');
});
</script>

<style>
/* Estilos para los nuevos estados */
.status-presupuesto_aprobado { @apply bg-purple-100 text-purple-800; }
.status-pagado { @apply bg-indigo-100 text-indigo-800; }
.status-finalizado { @apply bg-green-100 text-green-800; }

.btn-success {
    @apply bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors;
}

.btn-danger {
    @apply bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors;
}

/* Mantén los estilos existentes */
.priority-badge {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.priority-1 { @apply bg-green-100 text-green-800; }
.priority-2 { @apply bg-yellow-100 text-yellow-800; }
.priority-3 { @apply bg-red-100 text-red-800; }

.status-pendiente { @apply bg-yellow-100 text-yellow-800; }
.status-aprobada { @apply bg-green-100 text-green-800; }
.status-rechazada { @apply bg-red-100 text-red-800; }
.status-en_proceso { @apply bg-blue-100 text-blue-800; }
.status-completada { @apply bg-green-100 text-green-800; }
.status-cancelada { @apply bg-gray-100 text-gray-800; }

.detail-group {
    @apply mb-4;
}

.detail-label {
    @apply block text-sm font-medium text-gray-700 mb-1;
}

.detail-value {
    @apply text-sm text-gray-900;
}

.action-buttons {
    @apply flex space-x-3 items-center;
}

.btn {
    @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors;
}

.btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 focus:ring-blue-500;
}

.btn-secondary {
    @apply bg-gray-600 hover:bg-gray-700 focus:ring-gray-500;
}

.card {
    @apply bg-white rounded-lg shadow-md border border-gray-200 transition-shadow hover:shadow-lg;
}

.card-header {
    @apply px-6 py-4 border-b border-gray-200 flex justify-between items-center;
}

.card-title {
    @apply text-lg font-semibold text-gray-900;
}

.card-status {
    @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-medium;
}

.card-body {
    @apply px-6 py-4;
}

/* Animaciones */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

</x-layout.default>