<!-- Header de la tabla -->
<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#121c2c]">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white"><i class="fas fa-list mr-2"></i>Lista de Repuestos</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Mostrando {{ $repuestos->count() }} de {{ $repuestos->total() }} repuestos</p>
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Página {{ $repuestos->currentPage() }} de {{ $repuestos->lastPage() }}
        </div>
    </div>
</div>

<!-- Tabla -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-[#121c2c]">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                    <div class="flex items-center justify-center"><i class="fas fa-box mr-2"></i>Repuesto</div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                    <div class="flex items-center justify-center"><i class="fas fa-file-alt mr-2"></i>Solicitud</div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                    <div class="flex items-center justify-center"><i class="fas fa-info-circle mr-2"></i>Estado</div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                    <div class="flex items-center justify-center"><i class="fas fa-calendar mr-2"></i>Fechas</div>
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                    <div class="flex items-center justify-center"><i class="fas fa-hourglass-half mr-2"></i>Días</div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                    <div class="flex items-center justify-center"><i class="fas fa-hashtag mr-2"></i>Cantidad</div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                    <div class="flex items-center justify-center"><i class="fas fa-cogs mr-2"></i>Acciones</div>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-[#1b2e4b] divide-y divide-gray-200 dark:divide-gray-700">
            @if($repuestos->count() > 0)
                @foreach($repuestos as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-[#121c2c] transition-colors">
                        <!-- Columna Repuesto -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg flex items-center justify-center border border-blue-200 dark:border-blue-800">
                                    <i class="fas fa-box-open text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->nombre_repuesto }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500 mt-1 flex flex-wrap gap-1">
                                        @if ($item->subcategoria)
                                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded-full">{{ $item->subcategoria }}</span>
                                        @endif
                                        @if ($item->modelo)
                                            <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full">{{ $item->modelo }}</span>
                                        @endif
                                        @if ($item->marca)
                                            <span class="px-2 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">{{ $item->marca }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Solicitud -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white text-center">{{ $item->codigo_solicitud }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1 text-center">
                                <span class="flex items-center justify-center"><i class="fas fa-user mr-2 text-xs"></i>{{ $item->solicitante ?: 'N/A' }}</span>
                            </div>
                        </td>

                        <!-- Columna Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                switch ($item->estado_entrega) {
                                    case 'entregado':
                                        $estadoClase = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
                                        $estadoIcono = '<i class="fas fa-shipping-fast mr-1"></i>';
                                        $estadoTexto = 'En Tránsito';
                                        break;
                                    case 'cedido':
                                        $estadoClase = 'bg-secondary-light text-secondary dark:bg-secondary/30 dark:text-secondary';
                                        $estadoIcono = '<i class="fas fa-exchange-alt mr-1"></i>';
                                        $estadoTexto = 'Cedido';
                                        break;
                                    case 'usado':
                                        $estadoClase = 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400';
                                        $estadoIcono = '<i class="fas fa-check-circle mr-1"></i>';
                                        $estadoTexto = 'Usado';
                                        break;
                                    case 'cedido':
                                        $estadoClase = 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400';  // Cambiado a purple
                                        $estadoIcono = '<i class="fas fa-exchange-alt mr-1"></i>';
                                        $estadoTexto = 'Cedido';
                                        break;
                                    case 'devuelto':
                                        $estadoClase = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400';
                                        $estadoIcono = '<i class="fas fa-undo-alt mr-1"></i>';
                                        $estadoTexto = 'Devuelto';
                                        break;
                                    case 'pendiente_entrega':
                                        $estadoClase = 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400';
                                        $estadoIcono = '<i class="fas fa-clock mr-1"></i>';
                                        $estadoTexto = 'Pendiente';
                                        break;
                                    default:
                                        $estadoClase = 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400';
                                        $estadoIcono = '<i class="fas fa-question-circle mr-1"></i>';
                                        $estadoTexto = $item->estado_entrega ?? 'Sin Entrega';
                                }
                            @endphp
                            <div class="flex justify-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {!! $estadoClase !!}">{!! $estadoIcono !!} {{ $estadoTexto }}</span>
                            </div>
                            @if ($item->numero_ticket)
                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1 text-center"><i class="fas fa-ticket-alt mr-1"></i>{{ $item->numero_ticket }}</div>
                            @endif
                        </td>

                        <!-- Columna Fechas -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="space-y-1">
                                @if ($item->fechaUsado)
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 dark:text-green-400 mr-2 text-xs"></i>
                                        <span class="font-medium">Usado:</span>
                                        <span class="ml-1">{{ \Carbon\Carbon::parse($item->fechaUsado)->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if ($item->fechaSinUsar)
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-undo text-red-600 dark:text-red-400 mr-2 text-xs"></i>
                                        <span class="font-medium">Devuelto:</span>
                                        <span class="ml-1">{{ \Carbon\Carbon::parse($item->fechaSinUsar)->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if (!$item->fechaUsado && !$item->fechaSinUsar)
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 mr-2 text-xs"></i>
                                        <span class="font-medium">Solicitado:</span>
                                        <span class="ml-1">{{ \Carbon\Carbon::parse($item->fecha_solicitud)->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Días -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $inicio = $item->fecha_solicitud ? \Carbon\Carbon::parse($item->fecha_solicitud) : null;
                                if ($item->fechaUsado) {
                                    $fin = \Carbon\Carbon::parse($item->fechaUsado);
                                } elseif ($item->fechaSinUsar) {
                                    $fin = \Carbon\Carbon::parse($item->fechaSinUsar);
                                } else {
                                    $fin = now();
                                }
                                $dias = $inicio && $fin ? (int) $inicio->startOfDay()->diffInDays($fin->startOfDay()) : 0;
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $dias >= 10 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : ($dias >= 5 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400') }}">
                                {{ $dias }} días
                            </span>
                        </td>

                        <!-- Cantidad -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-sm font-semibold">{{ $item->cantidad }} unidades</span>
                            </div>
                            @if ($item->observacion)
                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-2 truncate max-w-xs text-center" title="{{ $item->observacion }}"><i class="fas fa-sticky-note mr-1"></i>{{ Str::limit($item->observacion, 50) }}</div>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" onclick="openDetailsModal({{ $item->idOrdenesArticulos }})" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye mr-2"></i>Ver Detalles
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="text-gray-400 dark:text-gray-500">
                            <i class="fas fa-inbox text-4xl mx-auto mb-4"></i>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay repuestos</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No se encontraron repuestos con los filtros aplicados.</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Paginación -->
@if ($repuestos->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#121c2c]">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-400">
                Mostrando {{ $repuestos->firstItem() }} a {{ $repuestos->lastItem() }} de {{ $repuestos->total() }} resultados
            </div>
            <div class="flex space-x-2">
                {{ $repuestos->links() }}
            </div>
        </div>
    </div>
@endif