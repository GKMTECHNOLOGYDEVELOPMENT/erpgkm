<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <div x-data="warehouseRequests()" x-init="init()">
        <div class="mx-auto w-full px-4 py-6">
            <div class="mb-4">
                <ul class="flex space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="" class="text-primary hover:underline">
                            Solicitudes
                        </a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Solicitud de Abastecimiento</span>
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
                                    d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Solicitudes de Abastecimiento</h1>
                            <p class="text-gray-600">Gestiona las necesidades de inventario del almacén</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            @click="modal.openModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16" class="mr-2">
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                            </svg>
                            Nueva Solicitud
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Solicitudes -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl shadow-sm">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-800" x-text="requests.length"></div>
                            <div class="text-gray-600 text-sm font-medium">Total Solicitudes</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-blue-100">
                        <div class="text-xs text-blue-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-chart-line text-xs"></i>
                            <span>Todas las solicitudes</span>
                        </div>
                    </div>
                </div>

                <!-- Pendientes -->
                <div
                    class="bg-gradient-to-br from-yellow-50 to-white rounded-xl border border-yellow-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="bg-warning p-4 rounded-xl shadow-sm">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-800"
                                x-text="getRequestsByStatus('pendiente').length"></div>
                            <div class="text-gray-600 text-sm font-medium">Pendientes</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-yellow-100">
                        <div class="text-xs text-yellow-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle text-xs"></i>
                            <span>Requieren atención</span>
                        </div>
                    </div>
                </div>

                <!-- Aprobadas -->
                <div
                    class="bg-gradient-to-br from-green-50 to-white rounded-xl border border-green-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl shadow-sm">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-800"
                                x-text="getRequestsByStatus('aprobada').length"></div>
                            <div class="text-gray-600 text-sm font-medium">Aprobadas</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-green-100">
                        <div class="text-xs text-green-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-play-circle text-xs"></i>
                            <span>Listas para procesar</span>
                        </div>
                    </div>
                </div>

                <!-- Completadas -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-white rounded-xl border border-purple-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl shadow-sm">
                            <i class="fas fa-flag-checkered text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-800"
                                x-text="getRequestsByStatus('completada').length"></div>
                            <div class="text-gray-600 text-sm font-medium">Completadas</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-purple-100">
                        <div class="text-xs text-purple-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-trophy text-xs"></i>
                            <span>Finalizadas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2 rounded-lg">
                            <i class="fas fa-filter text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Filtros</h3>
                            <p class="text-xs text-gray-500 mt-1">Filtra las solicitudes según tus necesidades</p>
                        </div>
                    </div>
                    <button
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 flex items-center gap-2"
                        @click="clearFilters()">
                        <i class="fas fa-times text-gray-500"></i>
                        Limpiar Filtros
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-tag text-blue-500 text-xs"></i>
                            Estado
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-circle-notch text-gray-400"></i>
                            </div>
                            <select
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white appearance-none cursor-pointer hover:border-gray-300"
                                x-model="filters.status">
                                <option value="" class="text-gray-400">Todos los estados</option>
                                <option value="Solicitud Enviada administración">
                                    Solicitud Enviada administración
                                </option>
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobada">Aprobada</option>
                                <option value="rechazada">Rechazada</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="completada">Completada</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">

                            </div>
                        </div>
                    </div>

                    <!-- Prioridad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">

                            Prioridad
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-signal text-gray-400"></i>
                            </div>
                            <select
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white appearance-none cursor-pointer"
                                x-model="filters.priority">
                                <option value="" class="text-gray-400">Todas las prioridades</option>
                                <option value="low" class="text-green-600">🟢 Baja</option>
                                <option value="medium" class="text-yellow-600">🟡 Media</option>
                                <option value="high" class="text-orange-600">🟠 Alta</option>
                                <option value="urgent" class="text-red-600">🔴 Urgente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">

                            Tipo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-cube text-gray-400"></i>
                            </div>
                            <select
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white appearance-none cursor-pointer"
                                x-model="filters.type">
                                <option value="" class="text-gray-400">Todos los tipos</option>
                                <option value="Reabastecimiento" class="flex items-center gap-2">
                                    <i class="fas fa-truck-loading text-blue-500 text-xs"></i>
                                    Reabastecimiento
                                </option>
                                <option value="Producto Nuevo">
                                    <i class="fas fa-star text-yellow-500 text-xs"></i>
                                    Producto Nuevo
                                </option>
                                <option value="Reposición">
                                    <i class="fas fa-sync-alt text-green-500 text-xs"></i>
                                    Reposición
                                </option>
                                <option value="Estacional">
                                    <i class="fas fa-calendar-alt text-purple-500 text-xs"></i>
                                    Estacional
                                </option>
                                <option value="Emergencia">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-xs"></i>
                                    Emergencia
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Buscar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">

                            Buscar
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                                placeholder="Código, título, solicitante..." x-model="filters.search">
                            <button x-show="filters.search" @click="filters.search = ''"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center" type="button">
                                <i class="fas fa-times text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Cards Container -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="request in filteredRequests" :key="request.id">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group"
                        :class="{
                            'border-l-4 border-info': request.status === 'Solicitud Enviada administración',
                            'border-l-4 border-warning': request.status === 'pendiente',
                            'border-l-4 border-success': request.status === 'aprobada',
                            'border-l-4 border-red-600': request.status === 'rechazada',
                            'border-l-4 border-primary': request.status === 'en_proceso',
                            'border-l-4 border-secondary': request.status === 'completada',
                            'border-l-4 border-gray-400': request.status === 'finalizado'
                        }">
                        <!-- Header de la card -->
                        <div class="p-5 border-b border-gray-100">
                            <!-- Código y Tipo -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center gap-1 bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1.5 rounded-full">
                                        <i class="fas fa-hashtag text-gray-500 text-xs"></i>
                                        <span x-text="request.code"></span>
                                    </span>
                                </div>
                                <!-- Badge de Estado -->
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm"
                                    :class="{
                                        'bg-info text-white': request.status === 'Solicitud Enviada administración',
                                        'bg-warning text-white': request.status === 'pendiente',
                                        'bg-success text-white': request.status === 'aprobada',
                                        'bg-danger text-white': request.status === 'rechazada',
                                        'bg-primary text-white': request.status === 'en_proceso',
                                        'bg-secondary text-white': request.status === 'completada',
                                        'bg-gray-400 text-white': request.status === 'finalizado'
                                    }">
                                    <span x-text="getStatusText(request.status)"></span>
                                </span>
                            </div>

                            <!-- Título y Descripción -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-200"
                                x-text="request.title"></h3>
                            <p class="text-gray-600 text-sm mb-5 line-clamp-2" x-text="request.description"></p>

                            <!-- Información clave -->
                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div class="space-y-2">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-user text-gray-400 text-sm mt-1"></i>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs text-gray-500 mb-0.5">Solicitante</div>
                                            <div class="text-xs sm:text-sm font-medium text-gray-800 break-words line-clamp-2 leading-tight"
                                                x-text="request.requested_by"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-flag text-gray-400 text-sm"></i>
                                        <div>
                                            <div class="text-xs text-gray-500">Prioridad</div>
                                            <div class="text-sm font-medium"
                                                :class="{
                                                    'text-success': request.priority === 'low',
                                                    'text-warning': request.priority === 'medium',
                                                    'text-orange-600': request.priority === 'high',
                                                    'text-danger': request.priority === 'urgent'
                                                }">
                                                <span x-text="getPriorityText(request.priority)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                                        <div>
                                            <div class="text-xs text-gray-500">Fecha Requerida</div>
                                            <div class="text-sm font-medium text-gray-800"
                                                x-text="formatDate(request.required_date)"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-cubes text-gray-400 text-sm"></i>
                                        <div>
                                            <div class="text-xs text-gray-500">Productos</div>
                                            <div class="text-sm font-bold text-blue-600"
                                                x-text="(request.products ? request.products.length : 0) + ' items'">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Productos con Scroll -->
                            <div x-show="request.products && request.products.length > 0" class="mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-boxes text-blue-500"></i>
                                        Productos solicitados
                                    </h4>
                                    <span class="text-xs font-medium px-2 py-1 rounded-full"
                                        :class="{
                                            'bg-blue-100 text-blue-700': request.products.length <= 3,
                                            'bg-orange-100 text-orange-700': request.products.length > 3
                                        }">
                                        <span x-text="request.products.length"></span> productos
                                    </span>
                                </div>

                                <div class="relative">
                                    <div
                                        class="max-h-40 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                        <div class="space-y-2">
                                            <template x-for="(product, index) in request.products.slice(0, 6)"
                                                :key="product.id">
                                                <div class="flex justify-between items-center text-sm px-3 py-2.5 rounded-lg border transition-all duration-150"
                                                    :class="{
                                                        'bg-blue-50 border-blue-100 hover:bg-blue-100': index % 4 === 0,
                                                        'bg-green-50 border-green-100 hover:bg-green-100': index % 4 ===
                                                            1,
                                                        'bg-yellow-50 border-yellow-100 hover:bg-yellow-100': index %
                                                            4 === 2,
                                                        'bg-purple-50 border-purple-100 hover:bg-purple-100': index %
                                                            4 === 3
                                                    }">
                                                    <div class="flex-1 min-w-0">
                                                        <div
                                                            class="font-medium text-gray-800 truncate flex items-center gap-2">
                                                            <i class="fas fa-box text-xs text-gray-500"></i>
                                                            <span x-text="product.name || 'Producto'"></span>
                                                        </div>
                                                        <div
                                                            class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                                            <i class="fas fa-barcode text-xs"></i>
                                                            <span x-text="product.code || 'Sin código'"></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3 ml-3">
                                                        <div class="text-right whitespace-nowrap">
                                                            <div class="font-bold text-gray-900 text-sm"
                                                                x-text="product.quantity + ' ' + (product.unit || 'ud')">
                                                            </div>
                                                            <div class="text-xs text-gray-500"
                                                                x-text="product.price ? '$' + product.price : ''">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Mostrar más productos si hay más de 6 -->
                                    <div x-show="request.products.length > 6"
                                        class="text-center text-sm text-gray-500 py-2 mt-2 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                        + <span x-text="request.products.length - 6"></span> productos más...
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje si no hay productos -->
                            <div x-show="!request.products || request.products.length === 0"
                                class="mt-4 text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <i class="fas fa-box text-gray-300 text-lg mb-2"></i>
                                <p class="text-sm text-gray-500">Sin productos agregados</p>
                            </div>
                        </div>

                        <!-- Footer de la card -->
                        <div
                            class="bg-gradient-to-r from-gray-50 to-white px-5 py-4 flex justify-between items-center border-t border-gray-100">
                            <div class="flex items-center gap-3">
                                <!-- Fecha de creación -->
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-calendar-plus text-gray-400"></i>
                                    <span x-text="formatDate(request.created_at)"></span>
                                </div>

                                <!-- Tipo de solicitud -->
                                <span
                                    class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1.5 rounded-full">
                                    <i class="fas fa-tag text-xs"></i>
                                    <span x-text="request.type || 'Sin tipo'"></span>
                                </span>

                                <!-- Total (si existe) -->
                                <div class="flex items-center gap-2 text-sm text-gray-600" x-show="request.total">
                                    <i class="fas fa-dollar-sign text-gray-400"></i>
                                    <span class="font-semibold" x-text="'$' + (request.total || 0)"></span>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex gap-1.5">
                                <!-- Botón Ver Detalles -->
                                <button
                                    class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 group/btn"
                                    title="Ver detalles" @click="viewRequest(request)">
                                    <div class="relative">
                                        <i class="fas fa-eye"></i>
                                        <span
                                            class="absolute -top-8 -left-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            Ver detalles
                                        </span>
                                    </div>
                                </button>

                                <!-- Botón Editar -->
                                <button
                                    class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                    title="Editar" x-show="request.status === 'pendiente'"
                                    @click="editRequest(request)">
                                    <div class="relative">
                                        <i class="fas fa-edit"></i>
                                        <span
                                            class="absolute -top-8 -left-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            Editar
                                        </span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredRequests.length === 0"
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4"
                        fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path
                            d="M7 11.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No se encontraron solicitudes</h3>
                    <p class="text-gray-600 mb-6" x-show="hasActiveFilters()">No hay solicitudes que coincidan con los
                        filtros aplicados</p>
                    <p class="text-gray-600 mb-6" x-show="!hasActiveFilters() && requests.length === 0">No hay
                        solicitudes de abastecimiento registradas</p>
                    <button x-show="requests.length === 0"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                        @click="showCreateModal = true">
                        Crear Primera Solicitud
                    </button>
                </div>
            </div>
        </div>

        <div x-show="modal.open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50" style="display: none;"
            @click.self="modal.closeModal()">

            <!-- Contenido del modal con animación -->
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300"
                @click.stop>

                <!-- Encabezado con gradiente sutil -->
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Nueva Solicitud de Abastecimiento</h2>
                                <p class="text-sm text-gray-500 mt-1">Crear una nueva solicitud</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200 active:scale-95"
                            @click="modal.closeModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700"
                                fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del modal -->
                <div class="p-6">
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        ¿Desea crear una nueva solicitud de abastecimiento para el almacén?
                    </p>

                    <!-- Información con iconos -->
                    <div class="space-y-4 mb-6">
                        <!-- Item 1 -->
                        <div
                            class="flex items-start gap-3 p-4 bg-blue-50/50 rounded-lg border border-blue-100 hover:bg-blue-50 transition-colors duration-200">
                            <div class="p-2 bg-white rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800 mb-1">Productos necesarios</h4>
                                <p class="text-sm text-gray-600">Complete los productos necesarios para el almacén</p>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div
                            class="flex items-start gap-3 p-4 bg-blue-50/50 rounded-lg border border-blue-100 hover:bg-blue-50 transition-colors duration-200">
                            <div class="p-2 bg-white rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800 mb-1">Detalles específicos</h4>
                                <p class="text-sm text-gray-600">Especifique cantidades y justificaciones</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nota adicional -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                            </svg>
                            El proceso toma aproximadamente 5-10 minutos
                        </p>
                    </div>
                </div>

                <!-- Pie del modal con botones -->
                <div class="p-6 border-t border-gray-200 bg-gray-50/50 rounded-b-xl">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <button
                            class="px-5 py-2.5 text-gray-700 hover:text-gray-900 font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 active:scale-95 order-2 sm:order-1"
                            @click="modal.closeModal()">
                            Cancelar
                        </button>
                        <button
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2.5 px-5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg active:scale-95 order-1 sm:order-2"
                            @click="redirectToCreate()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                            </svg>
                            Crear Solicitud
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    </div>

    <script>
        function warehouseRequests() {
            return {
                requests: @json($requests),
                filters: {
                    status: '',
                    priority: '',
                    type: '',
                    search: ''
                },
                // Ya no necesitamos showCreateModal aquí, se maneja con el componente modal

                // Componente modal integrado
                modal: {
                    open: false,
                    toggle() {
                        this.open = !this.open;
                    },
                    // Función para abrir el modal desde fuera
                    openModal() {
                        this.open = true;
                    },
                    // Función para cerrar el modal desde fuera
                    closeModal() {
                        this.open = false;
                    }
                },

                init() {
                    console.log('Solicitudes cargadas:', this.requests);
                },

                get filteredRequests() {
                    return this.requests.filter(request => {
                        // Filtrar por estado
                        if (this.filters.status && request.status !== this.filters.status) {
                            return false;
                        }

                        // Filtrar por prioridad
                        if (this.filters.priority && request.priority !== this.filters.priority) {
                            return false;
                        }

                        // Filtrar por tipo
                        if (this.filters.type && request.type !== this.filters.type) {
                            return false;
                        }

                        // Filtrar por búsqueda
                        if (this.filters.search) {
                            const searchTerm = this.filters.search.toLowerCase();
                            return (
                                (request.title && request.title.toLowerCase().includes(searchTerm)) ||
                                (request.description && request.description.toLowerCase().includes(
                                    searchTerm)) ||
                                (request.code && request.code.toLowerCase().includes(searchTerm)) ||
                                (request.requested_by && request.requested_by.toLowerCase().includes(
                                    searchTerm))
                            );
                        }

                        return true;
                    });
                },

                getRequestsByStatus(status) {
                    return this.requests.filter(request => request.status === status);
                },

                getStatusText(status) {
                    const statusMap = {
                        'Solicitud Enviada administración': 'Enviada a Administración',
                        'pendiente': 'Pendiente',
                        'aprobada': 'Aprobada',
                        'rechazada': 'Rechazada',
                        'en_proceso': 'En Proceso',
                        'completada': 'Completada',
                        'finalizado': 'Finalizado'
                    };
                    return statusMap[status] || status;
                },

                getPriorityText(priority) {
                    const priorityMap = {
                        'low': 'Baja',
                        'medium': 'Media',
                        'high': 'Alta',
                        'urgent': 'Urgente'
                    };
                    return priorityMap[priority] || priority;
                },

                formatDate(dateString) {
                    if (!dateString) return 'Sin fecha';
                    try {
                        const date = new Date(dateString);
                        if (isNaN(date.getTime())) return 'Fecha inválida';

                        const options = {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        };
                        return date.toLocaleDateString('es-ES', options);
                    } catch (error) {
                        return 'Fecha inválida';
                    }
                },

                hasActiveFilters() {
                    return this.filters.status || this.filters.priority || this.filters.type || this.filters.search;
                },

                clearFilters() {
                    this.filters = {
                        status: '',
                        priority: '',
                        type: '',
                        search: ''
                    };
                },

                viewRequest(request) {
                    // Usa el campo correcto según tu estructura de datos
                    window.location.href = `/solicitudalmacen/${request.id}/detalles`;
                },

                editRequest(request) {
                    if (request.status === 'pendiente') {
                        window.location.href = `/solicitudalmacen/${request.id}/edit`;
                    }
                },

                // Función modificada para usar el nuevo modal
                redirectToCreate() {
                    this.modal.closeModal();
                    window.location.href = '/solicitudalmacen/create';
                }
            }
        }

        // También puedes mantener el componente modal separado si lo prefieres
        document.addEventListener("alpine:init", () => {
            Alpine.data("modal", (initialOpenState = false) => ({
                open: initialOpenState,

                toggle() {
                    this.open = !this.open;
                }
            }));
        });
    </script>
</x-layout.default>
