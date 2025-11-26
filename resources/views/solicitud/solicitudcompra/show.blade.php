<x-layout.default>

<link rel="stylesheet" href="{{ asset('assets/css/solicitudcompra.css') }}">

@php
    // Funciones auxiliares para manejar monedas
    function getResumenMoneda($solicitud) {
        if ($solicitud->detalles->isEmpty()) return 'S/';
        
        $currencyCount = [];
        foreach ($solicitud->detalles as $detalle) {
            if ($detalle->moneda) {
                $currencyId = $detalle->moneda->idMonedas;
                $currencyCount[$currencyId] = ($currencyCount[$currencyId] ?? 0) + 1;
            }
        }
        
        if (empty($currencyCount)) return 'S/';
        
        $mostCommonCurrency = array_keys($currencyCount)[0];
        foreach ($solicitud->detalles as $detalle) {
            if ($detalle->moneda && $detalle->moneda->idMonedas == $mostCommonCurrency) {
                return $detalle->moneda->simbolo ?? 'S/';
            }
        }
        
        return 'S/';
    }

    function hasMultipleCurrencies($solicitud) {
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

    function getMonedasUtilizadas($solicitud) {
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
                    <h1>Detalles de Solicitud de Compra</h1>
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

    <!-- Información Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Información Básica -->
        <div class="col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información de la Solicitud</h3>
                    <div class="card-status status-{{ $solicitud->estado }}">
                        {{ $solicitud->estado_texto }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="detail-group">
                            <label class="detail-label">Código de Solicitud</label>
                            <p class="detail-value">{{ $solicitud->codigo_solicitud }}</p>
                        </div>
                        <div class="detail-group">
                            <label class="detail-label">Solicitante Compra</label>
                            <p class="detail-value">{{ $solicitud->solicitante_compra }}</p>
                        </div>
                        <div class="detail-group">
                            <label class="detail-label">Solicitante Almacén</label>
                            <p class="detail-value">{{ $solicitud->solicitante_almacen }}</p>
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
                        @if($solicitud->solicitudAlmacen)
                        <div class="detail-group col-span-2">
                            <label class="detail-label">Solicitud de Almacén Relacionada</label>
                            <p class="detail-value">
                                {{ $solicitud->solicitudAlmacen->codigo_solicitud }} - {{ $solicitud->solicitudAlmacen->titulo }}
                            </p>
                        </div>
                        @endif
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
                            <span class="font-semibold">{{ $monedaResumen }}{{ number_format($solicitud->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">IGV (18%):</span>
                            <span class="font-semibold">{{ $monedaResumen }}{{ number_format($solicitud->iva, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t pt-2">
                            <span class="text-gray-800 font-bold">Total:</span>
                            <span class="text-lg font-bold text-primary">{{ $monedaResumen }}{{ number_format($solicitud->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Unidades:</span>
                            <span class="font-semibold">{{ $solicitud->total_unidades }}</span>
                        </div>
                        @if($multipleMonedas)
                        <div class="flex justify-between items-center border-t pt-2">
                            <span class="text-gray-600 text-sm">Monedas Utilizadas:</span>
                            <span class="text-sm font-semibold">{{ $monedasUtilizadas }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información de Aprobación -->
            @if($solicitud->estado == 'aprobada' || $solicitud->estado == 'rechazada')
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Información de Aprobación</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-2">
                        <div class="detail-group">
                            <label class="detail-label">Estado</label>
                            <p class="detail-value">
                                <span class="status-{{ $solicitud->estado }}">
                                    {{ $solicitud->estado_texto }}
                                </span>
                            </p>
                        </div>
                        @if($solicitud->fecha_aprobacion)
                        <div class="detail-group">
                            <label class="detail-label">Fecha de Aprobación</label>
                            <p class="detail-value">{{ \Carbon\Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        @if($solicitud->motivo_rechazo)
                        <div class="detail-group">
                            <label class="detail-label">Motivo de Rechazo</label>
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
            <h3 class="card-title">Productos Solicitados</h3>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moneda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $detalle->cantidad }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $detalle->unidad ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->precio_unitario_estimado, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $detalle->moneda->nombre ?? 'PEN' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $detalle->moneda->simbolo ?? 'S/' }}{{ number_format($detalle->total_producto, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($detalle->estado == 'aprobado') bg-green-100 text-green-800
                                    @elseif($detalle->estado == 'rechazado') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $detalle->estado }}
                                </span>
                            </td>
                        </tr>
                        @if($detalle->justificacion_producto || $detalle->especificaciones_tecnicas || $detalle->proveedor_sugerido)
                        <tr>
                            <td colspan="8" class="px-6 py-3 bg-gray-50 text-sm text-gray-600">
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
                            <td class="px-6 py-3 text-sm text-gray-500">
                                <!-- Monedas utilizadas -->
                            </td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                {{ $monedaResumen }}{{ number_format($solicitud->detalles->sum('total_producto'), 2) }}
                            </td>
                            <td></td>
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
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
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
                       class="text-primary hover:text-primary-dark">
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

<style>
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
.status-completada { @apply bg-gray-100 text-gray-800; }
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
    @apply flex space-x-3;
}

.currency-badge {
    @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800;
}
</style>

</x-layout.default>