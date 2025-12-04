<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div x-data="warehouseRequestDetail()" x-init="init()">
        <div class="min-h-screen bg-gray-50 p-4 md:p-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-5">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div
                            class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white"
                                viewBox="0 0 16 16">
                                <path
                                    d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Detalle de Solicitud de Abastecimiento</h1>
                            <p class="text-gray-600">Información completa de la solicitud</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('solicitudalmacen.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                            </svg>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información Principal -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <!-- Header de la solicitud -->
                <div class="p-6 bg-gradient-to-r from-blue-50/50 to-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <!-- Icono y código -->
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-blue-200 rounded-xl blur-sm opacity-50"></div>

                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                    <h2 class="text-2xl font-bold text-gray-900" x-text="solicitud.codigo_solicitud">
                                    </h2>
                                    <!-- Estado (ahora al lado del título) -->
                                    <div class="px-4 py-1.5 rounded-full text-sm font-semibold shadow-sm transform hover:scale-105 transition-transform duration-200 cursor-pointer self-start sm:self-center"
                                        :class="{
                                            'bg-info text-white shadow-blue-200': solicitud
                                                .estado === 'Solicitud Enviada administración',
                                            'bg-warning text-white shadow-yellow-200': solicitud.estado === 'pendiente',
                                            'bg-success text-white shadow-green-200': solicitud.estado === 'aprobada',
                                            'bg-danger text-white shadow-red-200': solicitud.estado === 'rechazada',
                                            'bg-primary text-white shadow-blue-200': solicitud.estado === 'en_proceso',
                                            'bg-secondary text-white shadow-purple-200': solicitud
                                                .estado === 'completada',
                                            'bg-gray-400 text-white shadow-gray-200': solicitud.estado === 'finalizado'
                                        }"
                                        x-text="getStatusText(solicitud.estado)"></div>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                        <i class="fas fa-calendar text-xs"></i>
                                        <span x-text="formatDate(solicitud.created_at)"></span>
                                    </div>
                                    <span class="text-gray-300">•</span>
                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                        <i class="fas fa-user text-xs"></i>
                                        <span x-text="solicitud.solicitante"></span>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="px-3 py-1 rounded-full font-medium"
                            :class="{
                                'bg-green-100 text-green-800': getPriorityLevel(solicitud.prioridad
                                    ?.nivel) === 'low',
                                'bg-yellow-100 text-yellow-800': getPriorityLevel(solicitud.prioridad
                                    ?.nivel) === 'medium',
                                'bg-orange-100 text-orange-800': getPriorityLevel(solicitud.prioridad
                                    ?.nivel) === 'high',
                                'bg-red-100 text-red-800': getPriorityLevel(solicitud.prioridad?.nivel) === 'urgent',
                                'bg-gray-100 text-gray-800': !solicitud.prioridad
                            }">
                            <span x-text="solicitud.prioridad?.nombre || 'Sin prioridad'"></span>
                        </div>
                    </div>
                </div>

                <!-- Alertas de estado -->
                <div class="px-6 pt-6" x-show="solicitud.estado">
                    <div class="mb-4 p-4 rounded-lg"
                        :class="{
                            'bg-blue-50 text-blue-800 border border-blue-200': solicitud.estado === 'pendiente',
                            'bg-yellow-50 text-yellow-800 border border-yellow-200': solicitud.estado === 'en_proceso',
                            'bg-green-50 text-green-800 border border-green-200': solicitud.estado === 'completada' ||
                                solicitud.estado === 'aprobada',
                            'bg-red-50 text-red-800 border border-red-200': solicitud.estado === 'rechazada'
                        }"
                        x-show="solicitud.estado">
                        <strong x-show="solicitud.estado === 'pendiente'">📝 Solicitud Pendiente:</strong>
                        <strong x-show="solicitud.estado === 'en_proceso'">⚡ Solicitud en Proceso:</strong>
                        <strong x-show="solicitud.estado === 'completada'">✅ Evaluación Completada:</strong>
                        <strong x-show="solicitud.estado === 'aprobada'">🎉 Solicitud Aprobada:</strong>
                        <strong x-show="solicitud.estado === 'rechazada'">❌ Solicitud Rechazada:</strong>
                        <span x-show="solicitud.estado === 'pendiente'"> Puede comenzar a aprobar o rechazar productos
                            individualmente.</span>
                        <span x-show="solicitud.estado === 'en_proceso'"> Algunos productos han sido evaluados. Continúe
                            con los productos pendientes.</span>
                        <span x-show="solicitud.estado === 'completada'"> Todos los productos tienen estado. Defina el
                            estado final de la solicitud.</span>
                        <span x-show="solicitud.estado === 'aprobada'"> La solicitud ha sido aprobada
                            completamente.</span>
                        <span x-show="solicitud.estado === 'rechazada'"> La solicitud ha sido rechazada
                            completamente.</span>
                    </div>
                </div>

                <!-- Grid de información -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Información General -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
                            <!-- Encabezado -->
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Información General</h3>
                                    <p class="text-sm text-gray-500">Detalles de la solicitud</p>
                                </div>
                            </div>

                            <!-- Cards en una columna -->
                            <div class="space-y-4">
                                <!-- Card 1: Título -->
                                <div
                                    class="bg-blue-50 rounded-lg p-4 border border-blue-100 hover:bg-blue-100 transition-colors duration-200">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-heading text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Título</span>
                                    </div>
                                    <div class="font-medium text-gray-800 ml-11 truncate" x-text="solicitud.titulo">
                                    </div>
                                </div>

                                <!-- Card 2: Tipo de Solicitud -->
                                <div
                                    class="bg-blue-50 rounded-lg p-4 border border-blue-100 hover:bg-blue-100 transition-colors duration-200">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-tag text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Tipo de Solicitud</span>
                                    </div>
                                    <div class="font-medium text-gray-800 ml-11 truncate"
                                        x-text="solicitud.tipoSolicitud?.nombre || 'No especificado'"></div>
                                </div>

                                <!-- Card 3: Solicitante -->
                                <div
                                    class="bg-blue-50 rounded-lg p-4 border border-blue-100 hover:bg-blue-100 transition-colors duration-200">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Solicitante</span>
                                    </div>
                                    <div class="font-medium text-gray-800 ml-11 truncate"
                                        x-text="solicitud.solicitante"></div>
                                </div>

                                <!-- Card 4: Fechas -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Fecha Requerida -->
                                    <div
                                        class="bg-blue-50 rounded-lg p-4 border border-blue-100 hover:bg-blue-100 transition-colors duration-200">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-calendar-day text-blue-600 text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">Fecha Requerida</span>
                                        </div>
                                        <div class="font-medium text-gray-800 ml-11 truncate"
                                            x-text="formatDate(solicitud.fecha_requerida)"></div>
                                    </div>

                                    <!-- Fecha de Creación -->
                                    <div
                                        class="bg-blue-50 rounded-lg p-4 border border-blue-100 hover:bg-blue-100 transition-colors duration-200">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">Fecha de Creación</span>
                                        </div>
                                        <div class="font-medium text-gray-800 ml-11 truncate"
                                            x-text="formatDateTime(solicitud.created_at)"></div>
                                    </div>
                                </div>

                                <!-- Card 5: Centro de Costo -->
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 hover:bg-blue-100 transition-colors duration-200"
                                    x-show="solicitud.centroCosto">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-building text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Centro de Costo</span>
                                    </div>
                                    <div class="font-medium text-gray-800 ml-11 truncate"
                                        x-text="solicitud.centroCosto?.nombre"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen de Estados -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-200">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chart-pie text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Resumen de Estados</h3>
                                    <p class="text-sm text-gray-500">Estadísticas de productos</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Total Productos -->
                                <div
                                    class="bg-blue-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-blue-100">
                                    <div class="flex items-center justify-between mb-3">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-box text-blue-600"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-blue-600"
                                                x-text="solicitud.detalles?.length || 0"></div>
                                            <div class="text-xs text-blue-500">Total</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-blue-800">Productos</div>
                                    <div class="text-xs text-blue-600 mt-1">Cantidad total de productos</div>
                                </div>

                                <!-- Total Unidades -->
                                <div
                                    class="bg-purple-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-purple-100">
                                    <div class="flex items-center justify-between mb-3">
                                        <div
                                            class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-cubes text-purple-600"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-purple-600"
                                                x-text="solicitud.total_unidades || 0"></div>
                                            <div class="text-xs text-purple-500">Total</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-purple-800">Unidades</div>
                                    <div class="text-xs text-purple-600 mt-1">Cantidad total de unidades</div>
                                </div>

                                <!-- Aprobados -->
                                <div class="bg-green-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-green-100"
                                    x-show="solicitud.detalles">
                                    <div class="flex items-center justify-between mb-3">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-green-600"
                                                x-text="getAprobadosCount()"></div>
                                            <div class="text-xs text-green-500">Productos</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-green-800">Aprobados</div>
                                    <div class="text-xs text-green-600 mt-1">Productos aprobados</div>
                                </div>

                                <!-- Rechazados -->
                                <div class="bg-red-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-red-100"
                                    x-show="solicitud.detalles">
                                    <div class="flex items-center justify-between mb-3">
                                        <div
                                            class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-times-circle text-red-600"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-red-600"
                                                x-text="getRechazadosCount()"></div>
                                            <div class="text-xs text-red-500">Productos</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-red-800">Rechazados</div>
                                    <div class="text-xs text-red-600 mt-1">Productos rechazados</div>
                                </div>

                                <!-- Pendientes -->
                                <div class="bg-yellow-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-yellow-100"
                                    x-show="solicitud.detalles">
                                    <div class="flex items-center justify-between mb-3">
                                        <div
                                            class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clock text-yellow-600"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-yellow-600"
                                                x-text="getPendientesCount()"></div>
                                            <div class="text-xs text-yellow-500">Productos</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-yellow-800">Pendientes</div>
                                    <div class="text-xs text-yellow-600 mt-1">Productos pendientes</div>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción y Justificación -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-gray-200">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-indigo-200 rounded-lg blur-sm opacity-50"></div>
                                    <div
                                        class="relative w-10 h-10 bg-secondary rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-clipboard-check text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Descripción y Justificación</h3>
                                    <p class="text-sm text-gray-500">Detalles adicionales</p>
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-align-left text-blue-500"></i>
                                        <h4 class="font-medium text-gray-700">Descripción</h4>
                                    </div>
                                    <div class="text-gray-600 bg-blue-50 p-4 rounded-lg text-sm leading-relaxed min-h-[120px]"
                                        x-text="solicitud.descripcion || 'Sin descripción'"></div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-bullhorn text-green-500"></i>
                                        <h4 class="font-medium text-gray-700">Justificación</h4>
                                    </div>
                                    <div class="text-gray-600 bg-green-50 p-4 rounded-lg text-sm leading-relaxed min-h-[120px]"
                                        x-text="solicitud.justificacion || 'Sin justificación'"></div>
                                </div>
                                <div x-show="solicitud.observaciones">
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-sticky-note text-gray-500"></i>
                                        <h4 class="font-medium text-gray-700">Observaciones</h4>
                                    </div>
                                    <div class="text-gray-600 bg-gray-50 p-4 rounded-lg text-sm leading-relaxed min-h-[120px]"
                                        x-text="solicitud.observaciones"></div>
                                </div>
                                <div x-show="!solicitud.observaciones" class="text-center p-6 text-gray-400">
                                    <i class="fas fa-sticky-note text-2xl mb-2"></i>
                                    <p class="text-sm">Sin observaciones</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Productos Solicitados -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8 hover:shadow-md transition-shadow duration-300">
                        <!-- Encabezado mejorado -->
                        <div
                            class="px-6 py-4 bg-gradient-to-r from-blue-50 to-white border-b border-gray-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-blue-200 rounded-lg blur-sm opacity-50"></div>
                                    <div
                                        class="relative w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-boxes text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Productos Solicitados</h3>
                                    <p class="text-sm text-gray-500">Gestión de Estados</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full"
                                x-text="(solicitud.detalles?.length || 0) + ' productos'"></span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Producto
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cantidad
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Unidad
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado Actual
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(producto, index) in solicitud.detalles"
                                        :key="producto.idSolicitudAlmacenDetalle">
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <!-- Información básica del producto -->
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <strong class="text-gray-900 block"
                                                                x-text="producto.descripcion_producto"></strong>
                                                            <div class="mt-1 space-x-2">
                                                                <span x-show="producto.categoria"
                                                                    class="inline-block text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded"
                                                                    x-text="producto.categoria"></span>
                                                                <span x-show="producto.codigo_producto"
                                                                    class="inline-block text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded"
                                                                    x-text="producto.codigo_producto"></span>
                                                                <span x-show="producto.marca"
                                                                    class="inline-block text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded"
                                                                    x-text="producto.marca"></span>
                                                            </div>
                                                        </div>

                                                        <!-- Botón para expandir detalles -->
                                                        <button @click="toggleProductDetails(index)"
                                                            class="ml-2 p-1 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                                            :class="{
                                                                'text-blue-500 bg-blue-50': expandedProductIndex ===
                                                                    index
                                                            }">
                                                            <i class="fas"
                                                                :class="expandedProductIndex === index ? 'fa-chevron-up' :
                                                                    'fa-chevron-down'"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Detalles expandibles -->
                                                    <div x-show="expandedProductIndex === index"
                                                        class="mt-3 space-y-3 animate-fadeIn">
                                                        <!-- Especificaciones Técnicas -->
                                                        <div x-show="producto.especificaciones_tecnicas"
                                                            class="bg-blue-50 rounded-lg p-3">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <i class="fas fa-tools text-blue-500 text-sm"></i>
                                                                <h4 class="text-sm font-medium text-gray-700">
                                                                    Especificaciones Técnicas</h4>
                                                            </div>
                                                            <p class="text-gray-600 text-sm leading-relaxed"
                                                                x-text="producto.especificaciones_tecnicas"></p>
                                                        </div>

                                                        <!-- Justificación del Producto -->
                                                        <div x-show="producto.justificacion_producto"
                                                            class="bg-green-50 rounded-lg p-3">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <i class="fas fa-bullhorn text-green-500 text-sm"></i>
                                                                <h4 class="text-sm font-medium text-gray-700">
                                                                    Justificación del Producto</h4>
                                                            </div>
                                                            <p class="text-gray-600 text-sm leading-relaxed"
                                                                x-text="producto.justificacion_producto"></p>
                                                        </div>

                                                        <!-- Observaciones -->
                                                        <div x-show="producto.observaciones_detalle"
                                                            class="bg-gray-50 rounded-lg p-3">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <i
                                                                    class="fas fa-sticky-note text-gray-500 text-sm"></i>
                                                                <h4 class="text-sm font-medium text-gray-700">
                                                                    Observaciones</h4>
                                                            </div>
                                                            <p class="text-gray-600 text-sm leading-relaxed italic"
                                                                x-text="producto.observaciones_detalle"></p>
                                                        </div>

                                                        <!-- Mensaje si no hay detalles -->
                                                        <div x-show="!producto.especificaciones_tecnicas && !producto.justificacion_producto && !producto.observaciones_detalle"
                                                            class="text-center py-4 text-gray-400">
                                                            <i class="fas fa-info-circle text-lg mb-2"></i>
                                                            <p class="text-sm">Sin detalles adicionales</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-900 font-medium"
                                                        x-text="producto.cantidad"></span>
                                                    <i class="fas fa-hashtag text-gray-400 text-xs"></i>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-gray-900 font-medium"
                                                    x-text="producto.unidad"></span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-3 py-1.5 text-xs rounded-full font-semibold inline-flex items-center gap-1"
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-800': producto
                                                            .estado === 'pendiente',
                                                        'bg-green-100 text-green-800': producto.estado === 'aprobado',
                                                        'bg-red-100 text-red-800': producto.estado === 'rechazado'
                                                    }"
                                                    x-text="getProductStatusText(producto.estado)">
                                                    <i class="fas"
                                                        :class="{
                                                            'fa-clock': producto.estado === 'pendiente',
                                                            'fa-check-circle': producto.estado === 'aprobado',
                                                            'fa-times-circle': producto.estado === 'rechazado'
                                                        }"></i>
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div x-show="canChangeProductStatus(solicitud.estado)"
                                                    class="flex flex-col sm:flex-row gap-2">
                                                    <!-- Botón Aprobar -->
                                                    <template
                                                        x-if="producto.estado === 'pendiente' || producto.estado === 'rechazado'">
                                                        <button
                                                            @click="changeProductStatus(producto.idSolicitudAlmacenDetalle, 'aprobado', producto.descripcion_producto)"
                                                            class="inline-flex items-center justify-center gap-2 px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200">
                                                            <i class="fas fa-check text-xs"></i>
                                                            Aprobar
                                                        </button>
                                                    </template>

                                                    <!-- Botón Rechazar -->
                                                    <template
                                                        x-if="producto.estado === 'pendiente' || producto.estado === 'aprobado'">
                                                        <button
                                                            @click="changeProductStatus(producto.idSolicitudAlmacenDetalle, 'rechazado', producto.descripcion_producto)"
                                                            class="inline-flex items-center justify-center gap-2 px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200">
                                                            <i class="fas fa-times text-xs"></i>
                                                            Rechazar
                                                        </button>
                                                    </template>
                                                </div>
                                                <span x-show="!canChangeProductStatus(solicitud.estado)"
                                                    class="text-gray-500 text-sm italic">
                                                    <i class="fas fa-lock mr-1"></i>
                                                    No editable
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Controles para estado final -->
                        <div x-show="solicitud.estado === 'completada'"
                            class="p-6 bg-gradient-to-r from-green-50 to-white border-t border-gray-200">
                            <div class="text-center">
                                <div class="flex items-center justify-center gap-2 mb-4">
                                    <i class="fas fa-clipboard-check text-green-600 text-xl"></i>
                                    <h4 class="text-lg font-semibold text-gray-800">Definir Estado Final de la
                                        Solicitud</h4>
                                </div>

                                <div class="flex flex-col md:flex-row justify-center gap-4 mb-4">
                                    <!-- Botón Aprobar Solicitud Completa -->
                                    <button @click="changeFinalStatus('aprobada')"
                                        class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                        <i class="fas fa-check-circle text-lg"></i>
                                        <span class="text-sm md:text-base">✅ Aprobar Solicitud Completa</span>
                                    </button>

                                    <!-- Botón Rechazar Solicitud Completa -->
                                    <button @click="changeFinalStatus('rechazada')"
                                        x-show="canRejectCompleteRequest(solicitud.detalles)"
                                        class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                        <i class="fas fa-times-circle text-lg"></i>
                                        <span class="text-sm md:text-base">❌ Rechazar Solicitud Completa</span>
                                    </button>
                                </div>

                                <p class="text-xs text-gray-500 max-w-2xl mx-auto">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Según los estados de los productos, el sistema determinará qué acción está permitida
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de Estados -->
                    {{-- <div x-show="solicitud.historial && solicitud.historial.length > 0"
                        class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-8">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Historial de Estados</h3>
                        </div>
                        <div class="p-6">
                            <div class="relative">
                                <div class="absolute left-0 top-0 h-full w-0.5 bg-gray-200"></div>
                                <template x-for="(evento, index) in solicitud.historial" :key="evento.idHistorial">
                                    <div class="relative mb-6 ml-6">
                                        <div class="absolute -left-3 top-1 w-2 h-2 rounded-full border-2 border-white shadow"
                                            :class="{
                                                'bg-green-500': evento.estado_nuevo === 'aprobado' || evento
                                                    .estado_nuevo === 'aprobada',
                                                'bg-red-500': evento.estado_nuevo === 'rechazado' || evento
                                                    .estado_nuevo === 'rechazada',
                                                'bg-yellow-500': evento.estado_nuevo === 'pendiente',
                                                'bg-blue-500': evento.estado_nuevo === 'en_proceso',
                                                'bg-gray-500': evento.estado_nuevo === 'completada'
                                            }">
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
                                                <span class="font-medium text-gray-800"
                                                    x-text="getStatusText(evento.estado_nuevo)"></span>
                                                <span class="text-sm text-gray-500"
                                                    x-text="formatDateTime(evento.created_at)"></span>
                                            </div>
                                            <p class="text-gray-600 mb-2"
                                                x-text="evento.observaciones || 'Sin observaciones'"></p>
                                            <div class="text-sm text-gray-500">
                                                <span x-show="evento.usuario"
                                                    x-text="'Por: ' + evento.usuario?.name"></span>
                                                <span x-show="evento.tipo_cambio" class="ml-3"
                                                    x-text="'Tipo: ' + getTipoCambioText(evento.tipo_cambio)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Archivos Adjuntos -->
                    <div x-show="solicitud.archivos && solicitud.archivos.length > 0"
                        class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Archivos Adjuntos</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="archivo in solicitud.archivos" :key="archivo.idArchivo">
                                    <div
                                        class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate"
                                                    x-text="archivo.nombre_archivo"></p>
                                                <p class="text-xs text-gray-500 mt-1"
                                                    x-text="formatFileSize(archivo.tamaño) + ' • ' + archivo.tipo_archivo">
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a :href="archivo.ruta_archivo" target="_blank"
                                                    class="inline-flex items-center gap-1 px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm rounded transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                                        <path
                                                            d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                                    </svg>
                                                    Descargar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function warehouseRequestDetail() {
            return {
                solicitud: {},
                expandedProductIndex: null, // Nueva variable para controlar qué producto está expandido

                async init() {
                    await this.loadRequestDetail();
                },

                async loadRequestDetail() {
                    try {
                        const response = await fetch(`/solicitudalmacen/${@json($id)}/detalles-data`);
                        const data = await response.json();

                        if (data.success) {
                            this.solicitud = data.solicitud;
                            this.expandedProductIndex = null; // Resetear índice al cargar nuevos datos
                        } else {
                            alert('Error al cargar los detalles de la solicitud');
                            window.location.href = '/solicitudalmacen';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al cargar los detalles');
                    }
                },

                // Nueva función para alternar la expansión de detalles del producto
                toggleProductDetails(index) {
                    this.expandedProductIndex = this.expandedProductIndex === index ? null : index;
                },

                getStatusText(status) {
                    const statusMap = {
                        'pendiente': 'Pendiente',
                        'aprobada': 'Aprobada',
                        'rechazada': 'Rechazada',
                        'en_proceso': 'En Proceso',
                        'completada': 'Completada',
                        'Solicitud Enviada administración': 'Enviada a Administración'
                    };
                    return statusMap[status] || status;
                },

                getProductStatusText(status) {
                    const statusMap = {
                        'pendiente': 'Pendiente',
                        'aprobado': 'Aprobado',
                        'rechazado': 'Rechazado'
                    };
                    return statusMap[status] || status;
                },

                getTipoCambioText(tipo) {
                    const tipoMap = {
                        'detalle': 'Cambio de Producto',
                        'solicitud': 'Cambio de Solicitud',
                        'final': 'Estado Final'
                    };
                    return tipoMap[tipo] || tipo;
                },

                getPriorityLevel(nivel) {
                    const map = {
                        1: 'low',
                        2: 'medium',
                        3: 'high',
                        4: 'urgent'
                    };
                    return map[nivel] || 'medium';
                },

                formatDate(dateString) {
                    if (!dateString) return 'No especificada';
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('es-ES', options);
                },

                formatDateTime(dateString) {
                    if (!dateString) return 'No especificada';
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    return new Date(dateString).toLocaleDateString('es-ES', options);
                },

                formatFileSize(bytes) {
                    if (!bytes) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                getAprobadosCount() {
                    if (!this.solicitud.detalles) return 0;
                    return this.solicitud.detalles.filter(d => d.estado === 'aprobado').length;
                },

                getRechazadosCount() {
                    if (!this.solicitud.detalles) return 0;
                    return this.solicitud.detalles.filter(d => d.estado === 'rechazado').length;
                },

                getPendientesCount() {
                    if (!this.solicitud.detalles) return 0;
                    return this.solicitud.detalles.filter(d => d.estado === 'pendiente').length;
                },

                canChangeProductStatus(solicitudEstado) {
                    return ['pendiente', 'en_proceso', 'completada'].includes(solicitudEstado);
                },

                canRejectCompleteRequest(detalles) {
                    if (!detalles) return false;
                    const aprobados = detalles.filter(d => d.estado === 'aprobado').length;
                    const rechazados = detalles.filter(d => d.estado === 'rechazado').length;

                    // Solo se puede rechazar completamente si TODOS los productos están rechazados
                    return rechazados === detalles.length && aprobados === 0;
                },

                async changeProductStatus(productoId, nuevoEstado, productoNombre) {
                    console.log('Cambiando estado del producto:', {
                        productoId: productoId,
                        nuevoEstado: nuevoEstado,
                        productoNombre: productoNombre
                    });

                    const accion = nuevoEstado === 'aprobado' ? 'APROBAR' : 'RECHAZAR';
                    if (!confirm(`¿Está seguro de ${accion} el producto:\n"${productoNombre}"?`)) {
                        return;
                    }

                    try {
                        const observaciones = prompt('Ingrese observaciones (opcional):') || '';

                        const response = await fetch(`/solicitudalmacen/detalle/${productoId}/cambiar-estado`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                estado: nuevoEstado,
                                observaciones_detalle: observaciones
                            })
                        });

                        const data = await response.json();
                        console.log('Respuesta completa del servidor:', data);

                        if (data.success) {
                            let mensaje = '✅ Estado del producto actualizado exitosamente';
                            if (data.solicitud_estado) {
                                mensaje += `\n📋 Estado de la solicitud: ${this.getStatusText(data.solicitud_estado)}`;
                            }
                            if (data.detalles_estado) {
                                mensaje +=
                                    `\n📊 Resumen: ${data.detalles_estado.aprobados} aprobados, ${data.detalles_estado.rechazados} rechazados, ${data.detalles_estado.pendientes} pendientes`;
                            }

                            alert(mensaje);
                            await this.loadRequestDetail();
                        } else {
                            alert('❌ Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error completo:', error);
                        alert('❌ Error al cambiar el estado del producto: ' + error.message);
                    }
                },

                async changeFinalStatus(estadoFinal) {
                    const accion = estadoFinal === 'aprobada' ? 'APROBAR' : 'RECHAZAR';
                    if (!confirm(
                            `¿Está seguro de ${accion} la solicitud completa?\n\nEsta acción definirá el estado final de toda la solicitud.`
                        )) {
                        return;
                    }

                    try {
                        let motivo_rechazo = null;
                        let observaciones = prompt('Ingrese observaciones (opcional):') || '';

                        if (estadoFinal === 'rechazada') {
                            motivo_rechazo = prompt('Ingrese el motivo del rechazo de la solicitud completa:');
                            if (!motivo_rechazo) {
                                alert('Debe ingresar un motivo para rechazar la solicitud completa.');
                                return;
                            }
                        }

                        const response = await fetch(
                            `/solicitudalmacen/${@json($id)}/cambiar-estado-final`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    estado: estadoFinal,
                                    motivo_rechazo: motivo_rechazo,
                                    observaciones: observaciones
                                })
                            });

                        const data = await response.json();
                        console.log('Respuesta del servidor (estado final):', data);

                        if (data.success) {
                            alert('✅ Estado final actualizado exitosamente');
                            await this.loadRequestDetail();
                        } else {
                            alert('❌ Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error completo:', error);
                        alert('❌ Error al cambiar el estado final');
                    }
                },

                printDetail() {
                    window.print();
                }
            }
        }
    </script>
</x-layout.default>
