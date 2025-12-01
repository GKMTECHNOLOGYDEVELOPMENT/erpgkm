<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <div x-data="purchaseRequests()" x-init="init()">
        <!-- Container principal -->

        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="mb-4">
                <ul class="flex space-x-2 rtl:space-x-reverse">
                    <li><a href="" class="text-primary hover:underline">Solicitudes</a></li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Solicitud Compra</span>
                    </li>
                </ul>
            </div>
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-500 rounded-lg shadow-sm">
                            <i class="fas fa-file-invoice-dollar text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Solicitudes de Compra</h1>
                            <p class="text-gray-600 text-sm">Gestiona y revisa todas las solicitudes</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('solicitudcompra.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-wide hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-150 shadow-sm hover:shadow">
                    <i class="fas fa-plus text-sm mr-2"></i>
                    Nueva Solicitud
                </a>
            </div>

            <!-- Stats - Design Moderno sin Gradients -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Solicitudes -->
                <div class="relative group cursor-pointer transform transition-all duration-300 hover:scale-105">
                    <div class="bg-blue-500 rounded-2xl p-5 text-white shadow-lg">
                        <div
                            class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-300">
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Solicitudes</p>
                                    <p class="text-2xl font-bold mt-1" x-text="requests.length">0</p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-layer-group text-white text-lg"></i>
                                </div>
                            </div>
                            <!-- Indicador de estado -->
                            <div class="mt-3 flex items-center space-x-1">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                <span class="text-blue-100 text-xs">Todas las solicitudes</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pendientes -->
                <div class="relative group cursor-pointer transform transition-all duration-300 hover:scale-105">
                    <div class="bg-amber-500 rounded-2xl p-5 text-white shadow-lg">
                        <div
                            class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-300">
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-amber-100 text-sm font-medium">Pendientes</p>
                                    <p class="text-2xl font-bold mt-1" x-text="getRequestsByStatus('pendiente').length">
                                        0</p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-clock text-white text-lg"></i>
                                </div>
                            </div>
                            <!-- Indicador de estado -->
                            <div class="mt-3 flex items-center space-x-1">
                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                <span class="text-amber-100 text-xs">En espera</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aprobadas -->
                <div class="relative group cursor-pointer transform transition-all duration-300 hover:scale-105">
                    <div class="bg-emerald-500 rounded-2xl p-5 text-white shadow-lg">
                        <div
                            class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-300">
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-100 text-sm font-medium">Aprobadas</p>
                                    <p class="text-2xl font-bold mt-1" x-text="getRequestsByStatus('aprobada').length">0
                                    </p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-check-circle text-white text-lg"></i>
                                </div>
                            </div>
                            <!-- Indicador de éxito -->
                            <div class="mt-3 flex items-center space-x-1">
                                <i class="fas fa-star text-white text-xs"></i>
                                <span class="text-emerald-100 text-xs">Aprobado</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rechazadas -->
                <div class="relative group cursor-pointer transform transition-all duration-300 hover:scale-105">
                    <div class="bg-red-500 rounded-2xl p-5 text-white shadow-lg">
                        <div
                            class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-300">
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-rose-100 text-sm font-medium">Rechazadas</p>
                                    <p class="text-2xl font-bold mt-1" x-text="getRequestsByStatus('rechazada').length">
                                        0</p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-times-circle text-white text-lg"></i>
                                </div>
                            </div>
                            <!-- Indicador de alerta -->
                            <div class="mt-3 flex items-center space-x-1">
                                <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                                <span class="text-rose-100 text-xs">Requiere atención</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Design Mejorado -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-filter text-blue-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Filtros y Búsqueda</h3>
                    </div>
                    <button
                        class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                        @click="clearFilters()">
                        <i class="fas fa-eraser text-gray-500 mr-2"></i>
                        Limpiar Filtros
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Estado -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-tag text-blue-500 text-xs mr-2"></i>
                            Estado
                        </label>
                        <select
                            class="block w-full pl-4 pr-10 py-2.5 text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl bg-white shadow-sm transition-all duration-200"
                            x-model="filters.status">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="rechazada">Rechazada</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="completada">Completada</option>
                            <option value="cancelada">Cancelada</option>
                            <option value="presupuesto_aprobado">Presupuesto Aprobado</option>
                            <option value="pagado">Pagado</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                    </div>

                    <!-- Prioridad -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-flag text-warning text-xs mr-2"></i>
                            Prioridad
                        </label>
                        <select
                            class="block w-full pl-4 pr-10 py-2.5 text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl bg-white shadow-sm transition-all duration-200"
                            x-model="filters.priority">
                            <option value="">Todas las prioridades</option>
                            @foreach ($prioridades as $prioridad)
                                <option value="{{ $prioridad->idPrioridad }}">{{ $prioridad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Área -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-building text-green-500 text-xs mr-2"></i>
                            Área
                        </label>
                        <select
                            class="block w-full pl-4 pr-10 py-2.5 text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl bg-white shadow-sm transition-all duration-200"
                            x-model="filters.area">
                            <option value="">Todas las áreas</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->idTipoArea }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Búsqueda -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-search text-purple-500 text-xs mr-2"></i>
                            Buscar
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-sm"></i>
                            </div>
                            <input type="text"
                                class="block w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl transition-all duration-200"
                                placeholder="Buscar solicitudes..." x-model="filters.search">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Container - Design Moderno con Colores Planos -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="request in paginatedRequests" :key="request.idSolicitudCompra">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-105 group relative"
                        :class="{
                            'border-l-4 border-l-warning': request.estado === 'pendiente',
                            'border-l-4 border-l-success': request.estado === 'aprobada',
                            'border-l-4 border-l-danger': request.estado === 'rechazada',
                            'border-l-4 border-l-primary': request.estado === 'en_proceso',
                            'border-l-4 border-l-secondary': request.estado === 'completada',
                            'border-l-4 border-l-dark': request.estado === 'cancelada',
                            'border-l-4 border-l-warning-light': request.estado === 'presupuesto_aprobado',
                            'border-l-4 border-l-info': request.estado === 'pagado',
                            'border-l-4 border-l-[#888ea8]': request.estado === 'finalizado'
                        
                        }">

                        <!-- Header con color según ESTADO -->
                        <div class="px-6 py-4 text-white relative overflow-hidden"
                            :class="{
                                'bg-warning': request.estado === 'pendiente',
                                'bg-success': request.estado === 'aprobada',
                                'bg-danger': request.estado === 'rechazada',
                                'bg-primary': request.estado === 'en_proceso',
                                'bg-secondary': request.estado === 'completada',
                                'bg-dark': request.estado === 'cancelada',
                                'bg-warning-light': request.estado === 'presupuesto_aprobado',
                                'bg-info': request.estado === 'pagado',
                                'bg-[#888ea8]': request.estado === 'finalizado'
                            }">
                            <div
                                class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <div class="relative z-10 flex justify-between items-start">
                                <div>
                                    <div class="text-lg font-bold" x-text="request.codigo_solicitud"></div>
                                    <div class="text-white/80 text-sm mt-1"
                                        x-show="request.solicitud_almacen?.codigo_solicitud"
                                        x-text="'Almacén: ' + request.solicitud_almacen?.codigo_solicitud"></div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <!-- Badge de estado más prominente -->
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-white/30 text-white shadow-md backdrop-blur-sm border border-white/20"
                                        x-text="getStatusText(request.estado)"></span>
                                    <div class="text-xs text-white/70" x-text="formatDate(request.created_at)"></div>
                                </div>
                            </div>

                            <!-- Indicador de prioridad en esquina superior derecha -->
                            <div class="absolute top-3 right-3">
                                <div class="w-3 h-3 rounded-full border-2 border-white shadow-sm"
                                    :class="{
                                        'bg-danger': request.prioridad?.nivel === 'high',
                                        'bg-warning': request.prioridad?.nivel === 'medium',
                                        'bg-success': request.prioridad?.nivel === 'low',
                                        'bg-dark': !request.prioridad?.nivel
                                    }"
                                    :title="'Prioridad: ' + (request.prioridad?.nombre || 'No definida')">
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <!-- Título del proyecto -->
                            <h3 class="text-xl font-bold text-gray-900 mb-4 line-clamp-2"
                                x-text="request.proyecto_asociado || 'No especificado'"></h3>

                            <!-- Información principal en grid -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <!-- Solicitante Compra -->
                                <div class="space-y-1">
                                    <div
                                        class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        <i class="fas fa-user-tie text-primary mr-2 text-xs"></i>
                                        Solicitante
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 truncate"
                                        x-text="request.solicitante_compra || 'N/A'"></div>
                                </div>

                                <!-- Área -->
                                <div class="space-y-1">
                                    <div
                                        class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        <i class="fas fa-building text-secondary mr-2 text-xs"></i>
                                        Área
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 truncate"
                                        x-text="request.tipo_area?.nombre || 'N/A'"></div>
                                </div>

                                <!-- Prioridad -->
                                <div class="space-y-1">
                                    <div
                                        class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        <i class="fas fa-flag text-warning mr-2 text-xs"></i>
                                        Prioridad
                                    </div>
                                    <div class="text-sm font-medium text-gray-900"
                                        x-text="request.prioridad?.nombre || 'N/A'"></div>
                                </div>

                                <!-- Total -->
                                <div class="space-y-1">
                                    <div
                                        class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        <i class="fas fa-money-bill-wave text-success mr-2 text-xs"></i>
                                        Total
                                    </div>
                                    <div class="text-sm font-bold text-gray-900"
                                        x-text="getCurrencySymbol(request) + (request.total ? Number(request.total).toLocaleString('es-PE', {minimumFractionDigits: 2}) : '0.00')">
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="space-y-3">
                                <!-- Solicitante Almacén -->
                                <div class="flex items-center justify-between py-2 border-b border-gray-100"
                                    x-show="request.solicitante_almacen">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-warehouse text-dark mr-2 text-sm"></i>
                                        Sol. Almacén:
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 truncate max-w-[120px]"
                                        x-text="request.solicitante_almacen"></div>
                                </div>

                                <!-- Moneda -->
                                <div class="flex items-center justify-between py-2 border-b border-gray-100"
                                    x-show="getMainCurrency(request)">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-coins text-warning-light mr-2 text-sm"></i>
                                        Moneda:
                                    </div>
                                    <div class="text-sm font-medium text-gray-900" x-text="getMainCurrency(request)">
                                    </div>
                                </div>
                            </div>

                            <!-- Justificación -->
                            <div class="mt-4 p-4 bg-primary-light rounded-xl border border-primary/20"
                                x-show="request.justificacion">
                                <div class="flex items-start">
                                    <i class="fas fa-comment-dots text-primary mr-3 text-sm"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-primary-dark mb-1">Justificación</p>
                                        <p class="text-sm text-primary-dark/80 leading-relaxed"
                                            x-text="request.justificacion?.substring(0, 120) + (request.justificacion?.length > 120 ? '...' : '')">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-2 text-sm"></i>
                                    <span x-text="'Creado: ' + formatDate(request.created_at)"></span>
                                </div>
                                <div class="flex space-x-2">
                                    <!-- Botón Ver Detalles -->
                                    <button
                                        class="inline-flex items-center px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 transition-all duration-200 transform hover:scale-105"
                                        title="Ver detalles" @click="viewRequest(request.idSolicitudCompra)">
                                        <i class="fas fa-eye mr-1.5 text-xs"></i>
                                        Ver
                                    </button>

                                    <!-- Botón Editar (solo para pendientes) -->
                                    <button
                                        class="inline-flex items-center px-3 py-2 bg-warning text-white rounded-lg text-sm font-medium hover:bg-warning-dark focus:outline-none focus:ring-2 focus:ring-warning focus:ring-offset-1 transition-all duration-200 transform hover:scale-105"
                                        title="Editar" @click="editRequest(request.idSolicitudCompra)"
                                        x-show="request.estado === 'pendiente'">
                                        <i class="fas fa-edit mr-1.5 text-xs"></i>
                                        Editar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <!-- Después del grid de cards -->
            <div class="mt-8 flex items-center justify-between" x-show="filteredRequests.length > 0">
                <!-- Información de paginación -->
                <div class="text-sm text-gray-700">
                    Mostrando
                    <span x-text="(currentPage - 1) * itemsPerPage + 1"></span>
                    a
                    <span x-text="Math.min(currentPage * itemsPerPage, filteredRequests.length)"></span>
                    de
                    <span x-text="filteredRequests.length"></span> resultados
                </div>

                <!-- Controles de paginación -->
                <div class="flex space-x-2">
                    <!-- Botón Anterior -->
                    <button
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="currentPage === 1" @click="currentPage--">
                        <i class="fas fa-chevron-left mr-1 text-xs"></i>
                        Anterior
                    </button>

                    <!-- Números de página -->
                    <div class="flex space-x-1">
                        <template x-for="page in totalPages" :key="page">
                            <button
                                class="w-8 h-8 flex items-center justify-center border text-sm font-medium rounded-md"
                                :class="page === currentPage ?
                                    'border-blue-500 bg-blue-500 text-white' :
                                    'border-gray-300 text-gray-700 bg-white hover:bg-gray-50'"
                                @click="currentPage = page" x-text="page">
                            </button>
                        </template>
                    </div>

                    <!-- Botón Siguiente -->
                    <button
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="currentPage === totalPages" @click="currentPage++">
                        Siguiente
                        <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </button>
                </div>
            </div>
            <!-- Empty State -->
            <div x-show="filteredRequests.length === 0" class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path
                            d="M7 11.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron solicitudes</h3>
                <p class="text-gray-500 mb-4">No hay solicitudes que coincidan con los filtros aplicados</p>
                <button
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    @click="clearFilters()">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Pasar datos de PHP a JavaScript -->
    <script>
        const solicitudesData = @json($solicitudes);
    </script>

    <script>
        function purchaseRequests() {
            return {
                requests: [],
                filters: {
                    status: '',
                    priority: '',
                    area: '',
                    search: ''
                },
                showNewRequestModal: false,
                hoverStat: '',
                // Variables de paginación AGREGADAS
                currentPage: 1,
                itemsPerPage: 6,

                init() {
                    // Usar los datos reales de Laravel
                    this.requests = solicitudesData.map(solicitud => ({
                        ...solicitud,
                        // Asegurar que las relaciones estén disponibles
                        tipo_area: solicitud.tipo_area || null,
                        prioridad: solicitud.prioridad || null,
                        solicitud_almacen: solicitud.solicitud_almacen || null,
                        detalles: solicitud.detalles || []
                    }));
                },

                get filteredRequests() {
                    return this.requests.filter(request => {
                        // Filtrar por estado
                        if (this.filters.status && request.estado !== this.filters.status) {
                            return false;
                        }

                        // Filtrar por prioridad
                        if (this.filters.priority && request.idPrioridad != this.filters.priority) {
                            return false;
                        }

                        // Filtrar por área
                        if (this.filters.area && request.idTipoArea != this.filters.area) {
                            return false;
                        }

                        // Filtrar por búsqueda
                        if (this.filters.search) {
                            const searchTerm = this.filters.search.toLowerCase();
                            return (
                                (request.proyecto_asociado || '').toLowerCase().includes(searchTerm) ||
                                (request.solicitante_compra || '').toLowerCase().includes(searchTerm) ||
                                (request.solicitante_almacen || '').toLowerCase().includes(searchTerm) ||
                                (request.codigo_solicitud || '').toLowerCase().includes(searchTerm) ||
                                (request.solicitud_almacen?.codigo_solicitud || '').toLowerCase().includes(
                                    searchTerm) ||
                                (request.justificacion || '').toLowerCase().includes(searchTerm)
                            );
                        }

                        return true;
                    });
                },

                // Computed property para solicitudes paginadas AGREGADA
                get paginatedRequests() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredRequests.slice(start, end);
                },

                // Computed property para total de páginas AGREGADA
                get totalPages() {
                    return Math.ceil(this.filteredRequests.length / this.itemsPerPage);
                },

                // Método para páginas visibles AGREGADO
                getVisiblePages() {
                    const pages = [];
                    const total = this.totalPages;
                    const current = this.currentPage;

                    if (total <= 5) {
                        for (let i = 1; i <= total; i++) pages.push(i);
                    } else {
                        if (current <= 3) {
                            pages.push(1, 2, 3, 4, '...', total);
                        } else if (current >= total - 2) {
                            pages.push(1, '...', total - 3, total - 2, total - 1, total);
                        } else {
                            pages.push(1, '...', current - 1, current, current + 1, '...', total);
                        }
                    }
                    return pages;
                },

                getRequestsByStatus(status) {
                    return this.requests.filter(request => request.estado === status);
                },

                getStatusText(status) {
                    const statusMap = {
                        'pendiente': 'Pendiente',
                        'aprobada': 'Aprobada',
                        'rechazada': 'Rechazada',
                        'en_proceso': 'En Proceso',
                        'completada': 'Completada',
                        'cancelada': 'Cancelada',
                        'presupuesto_aprobado': 'Presupuesto Aprobado',
                        'pagado': 'Pagado',
                        'finalizado': 'Finalizado'
                    };
                    return statusMap[status] || status;
                },

                getCurrencySymbol(request) {
                    // Obtener el símbolo de moneda más común de los detalles
                    if (!request.detalles || request.detalles.length === 0) {
                        return 'S/';
                    }

                    // Contar monedas por símbolo
                    const currencyCount = {};
                    request.detalles.forEach(detalle => {
                        if (detalle.moneda && detalle.moneda.simbolo) {
                            currencyCount[detalle.moneda.simbolo] = (currencyCount[detalle.moneda.simbolo] || 0) +
                                1;
                        }
                    });

                    // Encontrar la moneda más común
                    const mostCommonCurrency = Object.keys(currencyCount).reduce((a, b) =>
                        currencyCount[a] > currencyCount[b] ? a : b, 'S/'
                    );

                    return mostCommonCurrency;
                },

                getMainCurrency(request) {
                    // Obtener el nombre de la moneda principal
                    if (!request.detalles || request.detalles.length === 0) {
                        return '';
                    }

                    const currencyCount = {};
                    request.detalles.forEach(detalle => {
                        if (detalle.moneda && detalle.moneda.nombre) {
                            currencyCount[detalle.moneda.nombre] = (currencyCount[detalle.moneda.nombre] || 0) + 1;
                        }
                    });

                    const mostCommonCurrency = Object.keys(currencyCount).reduce((a, b) =>
                        currencyCount[a] > currencyCount[b] ? a : b, ''
                    );

                    return mostCommonCurrency;
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    return date.toLocaleDateString('es-ES', options);
                },

                clearFilters() {
                    this.filters = {
                        status: '',
                        priority: '',
                        area: '',
                        search: ''
                    };
                    // Resetear paginación al limpiar filtros AGREGADO
                    this.currentPage = 1;
                },

                viewRequest(id) {
                    // Redirigir a la vista de detalles usando la ruta correcta
                    window.location.href = `/solicitudcompra/${id}`;
                },

                editRequest(id) {
                    // Redirigir a la vista de edición usando la ruta correcta
                    window.location.href = `/solicitudcompra/${id}/edit`;
                }
            }
        }
    </script>
</x-layout.default>
