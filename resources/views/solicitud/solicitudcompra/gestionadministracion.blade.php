<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Solicitudes de Compra</h1>
            <p class="text-gray-600">Administración - Revisión de solicitudes</p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="rechazada">Rechazada</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas las prioridades</option>
                        <!-- Aquí irían las opciones de prioridad -->
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha desde</label>
                    <input type="date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha hasta</label>
                    <input type="date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="solicitudes-container">
            @if(isset($solicitudes) && count($solicitudes) > 0)
                @foreach($solicitudes as $solicitud)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                        <!-- Header de la Card -->
                        <div class="border-b border-gray-200 px-4 py-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                        @if($solicitud->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                        @elseif($solicitud->estado == 'aprobada') bg-green-100 text-green-800
                                        @elseif($solicitud->estado == 'rechazada') bg-red-100 text-red-800
                                        @elseif($solicitud->estado == 'en_proceso') bg-blue-100 text-blue-800
                                        @elseif($solicitud->estado == 'completada') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">Código</span>
                                    <p class="text-sm font-semibold text-gray-800">{{ $solicitud->codigo_solicitud }}</p>
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-800 truncate">{{ $solicitud->proyecto_asociado ?? 'Sin proyecto' }}</h3>
                        </div>

                        <!-- Contenido de la Card -->
                        <div class="p-4">
                            <!-- Información del Solicitante -->
                            <div class="mb-4">
                                <div class="flex items-center mb-2">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">
                                            {{ substr($solicitud->solicitante_compra ?? $solicitud->solicitante, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $solicitud->solicitante_compra ?? $solicitud->solicitante }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Detalles importantes -->
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Fecha Requerida:</span>
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Total Unidades:</span>
                                    <span class="text-sm font-medium text-gray-800">{{ $solicitud->total_unidades }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Monto Total:</span>
                                    <span class="text-sm font-bold text-green-600">
                                        {{ $solicitud->resumen_moneda }}{{ number_format($solicitud->total, 2) }}
                                    </span>
                                </div>
                                @if($solicitud->multiple_currencies)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Monedas:</span>
                                    <span class="text-sm font-medium text-blue-600">
                                        {{ $solicitud->monedas_utilizadas }}
                                    </span>
                                </div>
                                @endif
                            </div>

                            <!-- Resumen de productos -->
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Productos solicitados:</p>
                                <div class="bg-gray-50 rounded p-3 max-h-20 overflow-y-auto">
                                    @if(isset($solicitud->detalles) && count($solicitud->detalles) > 0)
                                        @foreach($solicitud->detalles->take(3) as $detalle)
                                            <div class="flex justify-between text-xs mb-1 last:mb-0">
                                                <span class="text-gray-700 truncate flex-1 mr-2">
                                                    {{ $detalle->descripcion_producto }}
                                                </span>
                                                <div class="flex items-center space-x-1 whitespace-nowrap">
                                                    <span class="text-gray-900 font-medium">
                                                        {{ $detalle->cantidad }} {{ $detalle->unidad }}
                                                    </span>
                                                  
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(count($solicitud->detalles) > 3)
                                            <p class="text-xs text-gray-500 text-center mt-1">
                                                +{{ count($solicitud->detalles) - 3 }} más...
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-xs text-gray-500 text-center">No hay detalles disponibles</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Justificación -->
                            @if($solicitud->justificacion)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Justificación:</p>
                                    <p class="text-xs text-gray-700 bg-gray-50 rounded p-2 max-h-16 overflow-y-auto">
                                        {{ Str::limit($solicitud->justificacion, 120) }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer de la Card -->
                        <div class="border-t border-gray-200 px-4 py-3 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <!-- Botón Ver Detalles -->
                                    <button onclick="verDetalle({{ $solicitud->idSolicitudCompra }})" 
                                            class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition-colors duration-200">
                                        Ver Detalles
                                    </button>
                                </div>
                                
                                <!-- Indicador de prioridad -->
                                <div class="w-3 h-3 rounded-full 
                                    @if($solicitud->idPrioridad == 1) bg-red-500
                                    @elseif($solicitud->idPrioridad == 2) bg-orange-500
                                    @else bg-green-500 @endif"
                                    title="Prioridad {{ $solicitud->idPrioridad }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Estado vacío -->
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay solicitudes</h3>
                    <p class="text-gray-500">No se encontraron solicitudes de compra para mostrar.</p>
                </div>
            @endif
        </div>

        <!-- Paginación -->
        @if(isset($solicitudes) && $solicitudes->hasPages())
            <div class="mt-6">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </div>

    <script>
        // Función para ver detalles - REDIRIGE A EVALUACIÓN
        function verDetalle(idSolicitud) {
            console.log('Redirigiendo a evaluación de solicitud:', idSolicitud);
            window.location.href = `/solicitudcompra/${idSolicitud}/evaluacion`;
        }

        function aprobarSolicitud(idSolicitud) {
            if(confirm('¿Estás seguro de que deseas aprobar esta solicitud?')) {
                console.log('Aprobar solicitud:', idSolicitud);
                // Aquí puedes agregar lógica AJAX para aprobar
            }
        }

        function rechazarSolicitud(idSolicitud) {
            const motivo = prompt('Ingrese el motivo del rechazo:');
            if(motivo !== null && motivo.trim() !== '') {
                console.log('Rechazar solicitud:', idSolicitud, 'Motivo:', motivo);
                // Aquí puedes agregar lógica AJAX para rechazar
            }
        }

        // Filtros dinámicos
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado - funciones JavaScript disponibles');
            
            const filtros = document.querySelectorAll('select, input[type="date"]');
            filtros.forEach(filtro => {
                filtro.addEventListener('change', function() {
                    aplicarFiltros();
                });
            });
        });

        function aplicarFiltros() {
            console.log('Aplicando filtros...');
            // Aquí puedes agregar la lógica para filtrar las solicitudes
            // Puede ser mediante AJAX o recargando la página con parámetros
        }

        // Verificar que las funciones estén disponibles globalmente
        console.log('verDetalle function is defined:', typeof verDetalle === 'function');
        console.log('aprobarSolicitud function is defined:', typeof aprobarSolicitud === 'function');
        console.log('rechazarSolicitud function is defined:', typeof rechazarSolicitud === 'function');
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Estilos para mejorar la apariencia de los scrollbars en áreas con overflow */
        .bg-gray-50::-webkit-scrollbar {
            width: 4px;
        }
        .bg-gray-50::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .bg-gray-50::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .bg-gray-50::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</x-layout.default>