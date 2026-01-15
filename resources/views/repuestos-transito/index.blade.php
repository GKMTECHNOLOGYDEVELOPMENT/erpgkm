<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/viewerjs/dist/viewer.min.css" />

    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="panel mb-8 p-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                <i class="fas fa-box mr-2"></i>
                Gestión de Repuestos
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Seguimiento de repuestos usados, no usados y en tránsito
            </p>
        </div>

        <!-- Cards de Resumen ACTUALIZADAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="summary-cards">
            <!-- Las tarjetas se cargarán dinámicamente via AJAX -->
            @include('repuestos-transito.partials.summary-cards', compact('contadores'))
        </div>

        <!-- Filtros CON AJAX -->
        <div class="bg-white dark:bg-[#1b2e4b] rounded-xl shadow-sm p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4"><i class="fas fa-search mr-2"></i>Filtros de Búsqueda</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-filter mr-2 text-sm"></i>
                                Estado
                            </span>
                        </label>
                        <select name="estado" id="estado"
                            class="filter-select form-select w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos los estados</option>
                            <option value="en_transito">En Tránsito</option>
                            <option value="cedido">Cedido</option>
                            <option value="usado">Usado</option>
                            <option value="devuelto">Devuelto</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>

                    <!-- Código Repuesto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-barcode mr-2 text-sm"></i>
                                Código Repuesto
                            </span>
                        </label>
                        <input type="text" name="codigo_repuesto" id="codigo_repuesto"
                            class="filter-input form-input w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            placeholder="Ej: REP-001">
                    </div>

                    <!-- Fecha Desde -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-sm"></i>
                                Desde
                            </span>
                        </label>
                        <input type="text" name="fecha_desde" id="fecha_desde"
                            class="filter-date flatpickr-date form-input w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            placeholder="Seleccionar fecha">
                    </div>

                    <!-- Fecha Hasta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-sm"></i>
                                Hasta
                            </span>
                        </label>
                        <input type="text" name="fecha_hasta" id="fecha_hasta"
                            class="filter-date flatpickr-date form-input w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            placeholder="Seleccionar fecha">
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-wrap gap-2 pt-2">
                    <button id="btnLimpiar" class="btn btn-outline-primary">
                        <i class="fas fa-redo mr-2"></i>
                        Limpiar Filtros
                    </button>
                    
                    <div class="ml-auto flex items-center space-x-2">
                        <div id="loading-indicator" class="hidden">
                            <div class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Buscando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Repuestos -->
        <div id="tabla-container"
            class="bg-white dark:bg-[#1b2e4b] rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- El contenido de la tabla se cargará dinámicamente via AJAX -->
            @include('repuestos-transito.partials.tabla', compact('repuestos'))
        </div>
    </div>

    <!-- MODAL DE DETALLES (igual que antes) -->
    <div x-data="modalDetails" x-cloak>
        <!-- Modal Overlay -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] overflow-y-auto" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Modal Container -->
            <div class="flex items-start justify-center min-h-screen py-8 px-4" @click.self="toggle">
                <!-- Modal Content -->
                <div x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white dark:bg-[#1b2e4b] rounded-xl shadow-2xl w-full max-w-5xl max-h-[95vh] overflow-y-auto border border-gray-200 dark:border-gray-800">

                    <!-- Loading Overlay -->
                    <div x-show="loading"
                        class="absolute inset-0 bg-white/80 dark:bg-[#1b2e4b]/80 z-50 flex items-center justify-center rounded-xl">
                        <div class="text-center">
                            <div
                                class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent mx-auto">
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 mt-4 font-medium">Cargando detalles...</p>
                        </div>
                    </div>

                    <!-- Header -->
                    <div
                        class="sticky top-0 z-10 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/40 dark:to-blue-800/40 px-6 py-4 border-b border-blue-200 dark:border-blue-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white dark:bg-blue-900 rounded-lg shadow-sm">
                                    <i class="fas fa-box-open text-blue-600 dark:text-blue-300 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800 dark:text-white" x-text="modalTitle">
                                    </h2>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-300"
                                            x-text="modalSubtitle"></span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="getStatusClass(details.estado)"
                                            x-show="details.estado && !loading">
                                            <i :class="getStatusIcon(details.estado)" class="mr-1"></i>
                                            <span x-text="getStatusText(details.estado)"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button @click="toggle"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i
                                    class="fas fa-times text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- GALERÍA DE FOTOS -->
                        <div x-show="!loading && dataLoaded && tieneFotos"
                            class="mb-8 bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                                    <i class="fas fa-images text-blue-500 mr-2"></i>Galería de Fotos
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <span x-text="totalFotos"></span> fotos disponibles - Haz clic para ampliar
                                </p>
                            </div>

                            <!-- Fotos Usadas -->
                            <div
                                x-show="fotos.foto_articulo_usado && fotos.foto_articulo_usado.tiene && fotos.foto_articulo_usado.fotos.length > 0">
                                <div class="mb-3">
                                    <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Repuesto Usado
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-full"
                                            x-text="fotos.foto_articulo_usado.fotos.length"></span>
                                    </h4>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 viewer-gallery"
                                    id="gallery-usado">
                                    <template x-for="(foto, index) in fotos.foto_articulo_usado.fotos"
                                        :key="index">
                                        <div class="relative group">
                                            <div
                                                class="relative h-48 w-full bg-gray-200 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden">
                                                <img :src="foto.base64" :alt="foto.nombre || 'Foto ' + (index + 1)"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 cursor-pointer"
                                                    @click="viewImage($event.target)">

                                                <div
                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white text-2xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Fotos No Usadas -->
                            <div x-show="fotos.foto_articulo_no_usado && fotos.foto_articulo_no_usado.tiene && fotos.foto_articulo_no_usado.fotos.length > 0"
                                class="mt-6">
                                <div class="mb-3">
                                    <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>Repuesto Devuelto
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs rounded-full"
                                            x-text="fotos.foto_articulo_no_usado.fotos.length"></span>
                                    </h4>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 viewer-gallery"
                                    id="gallery-no-usado">
                                    <template x-for="(foto, index) in fotos.foto_articulo_no_usado.fotos"
                                        :key="index">
                                        <div class="relative group">
                                            <div
                                                class="relative h-48 w-full bg-gray-200 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden">
                                                <img :src="foto.base64" :alt="foto.nombre || 'Foto ' + (index + 1)"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 cursor-pointer"
                                                    @click="viewImage($event.target)">

                                                <div
                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white text-2xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Fotos Originales -->
                            <div x-show="fotos.fotoRepuesto && fotos.fotoRepuesto.tiene && fotos.fotoRepuesto.fotos.length > 0"
                                class="mt-6">
                                <div class="mb-3">
                                    <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>Fotos Originales
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded-full"
                                            x-text="fotos.fotoRepuesto.fotos.length"></span>
                                    </h4>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 viewer-gallery"
                                    id="gallery-original">
                                    <template x-for="(foto, index) in fotos.fotoRepuesto.fotos"
                                        :key="index">
                                        <div class="relative group">
                                            <div
                                                class="relative h-48 w-full bg-gray-200 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden">
                                                <img :src="foto.base64"
                                                    :alt="foto.nombre || 'Foto ' + (index + 1)"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 cursor-pointer"
                                                    @click="viewImage($event.target)">

                                                <div
                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white text-2xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Fotos de Entrega -->
                            <div x-show="fotos.foto_entrega && fotos.foto_entrega.tiene && fotos.foto_entrega.fotos.length > 0"
                                class="mt-6">
                                <div class="mb-3">
                                    <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>Fotos de Entrega
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded-full"
                                            x-text="fotos.foto_entrega.fotos.length"></span>
                                    </h4>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 viewer-gallery"
                                    id="gallery-entrega">
                                    <template x-for="(foto, index) in fotos.foto_entrega.fotos"
                                        :key="index">
                                        <div class="relative group">
                                            <div
                                                class="relative h-48 w-full bg-gray-200 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden">
                                                <img :src="foto.base64"
                                                    :alt="foto.nombre || 'Foto de entrega ' + (index + 1)"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 cursor-pointer"
                                                    @click="viewImage($event.target)">

                                                <div
                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white text-2xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Sin Fotos -->
                        <div x-show="!loading && dataLoaded && !tieneFotos"
                            class="mb-8 text-center py-8 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                            <i class="fas fa-image text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">No hay fotos disponibles</p>
                        </div>

                        <!-- INFORMACIÓN DEL REPUESTO -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Columna Izquierda -->
                            <div class="space-y-6">
                                <!-- Información Principal -->
                                <div
                                    class="bg-white dark:bg-gray-900 rounded-lg p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
                                    <h3
                                        class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                        Información Principal
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nombre
                                                del Repuesto</label>
                                            <p class="text-lg font-semibold text-gray-800 dark:text-white"
                                                x-text="details.nombre_repuesto || 'N/A'"></p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Código</label>
                                                <p class="font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="details.codigo_repuesto || 'N/A'"></p>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cantidad</label>
                                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400"
                                                    x-text="details.cantidad + ' und'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información de Solicitud -->
                                <div
                                    class="bg-white dark:bg-gray-900 rounded-lg p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
                                    <h3
                                        class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                        <i class="fas fa-file-alt text-green-500 mr-2"></i>
                                        Información de Solicitud
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Código
                                                Solicitud:</span>
                                            <span class="font-medium text-gray-800 dark:text-white"
                                                x-text="details.codigo_solicitud || 'N/A'"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Solicitante:</span>
                                            <span class="font-medium text-gray-800 dark:text-white"
                                                x-text="details.solicitante || 'N/A'"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Fecha
                                                Solicitud:</span>
                                            <span class="font-medium text-gray-800 dark:text-white"
                                                x-text="formatDate(details.fechaCreacion)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="space-y-6">
                                <!-- Fechas Importantes -->
                                <div
                                    class="bg-white dark:bg-gray-900 rounded-lg p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
                                    <h3
                                        class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                        <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                                        Fechas Importantes
                                    </h3>
                                    <div class="space-y-4">
                                        <!-- Fecha de Uso -->
                                        <div x-show="details.fechaUsado"
                                            class="flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                            <div class="flex-shrink-0 mr-4">
                                                <div
                                                    class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-green-800 dark:text-green-300">
                                                    Usado</p>
                                                <p class="text-sm text-green-600 dark:text-green-400"
                                                    x-text="formatDateTime(details.fechaUsado)"></p>
                                            </div>
                                        </div>

                                        <!-- Fecha de Devolución -->
                                        <div x-show="details.fechaSinUsar"
                                            class="flex items-center p-3 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                            <div class="flex-shrink-0 mr-4">
                                                <div
                                                    class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-undo text-red-600 dark:text-red-400"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-red-800 dark:text-red-300">
                                                    Devuelto</p>
                                                <p class="text-sm text-red-600 dark:text-red-400"
                                                    x-text="formatDateTime(details.fechaSinUsar)"></p>
                                            </div>
                                        </div>

                                        <!-- Fecha de Solicitud -->
                                        <div x-show="!details.fechaUsado && !details.fechaSinUsar"
                                            class="flex items-center p-3 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                            <div class="flex-shrink-0 mr-4">
                                                <div
                                                    class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">
                                                    Solicitado</p>
                                                <p class="text-sm text-yellow-600 dark:text-yellow-400"
                                                    x-text="formatDateTime(details.fechaCreacion)"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div x-show="details.observacion"
                                    class="bg-white dark:bg-gray-900 rounded-lg p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
                                    <h3
                                        class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                        <i class="fas fa-sticky-note text-indigo-500 mr-2"></i>
                                        Observaciones
                                    </h3>
                                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                                            x-text="details.observacion"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="sticky bottom-0 bg-white dark:bg-[#1b2e4b] border-t border-gray-200 dark:border-gray-800 px-6 py-4 mt-6">
                            <div class="flex justify-end space-x-3">
                                <button @click="toggle"
                                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/viewerjs/dist/viewer.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    
    <script>
        // Configuración de AJAX
        let ajaxTimeout;
        let currentPage = 1;
        let currentFilters = {};

        // Función para aplicar filtros via AJAX
        function aplicarFiltrosAJAX() {
            clearTimeout(ajaxTimeout);
            
            // Obtener valores de filtros
            const filtros = {
                estado: $('#estado').val(),
                codigo_repuesto: $('#codigo_repuesto').val(),
                fecha_desde: $('#fecha_desde').val(),
                fecha_hasta: $('#fecha_hasta').val(),
                page: currentPage
            };

            currentFilters = filtros;

            // Mostrar loading
            $('#loading-indicator').removeClass('hidden');
            
            // Retraso para evitar muchas llamadas mientras se escribe
            ajaxTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route("repuesto-transito.filtrar") }}',
                    method: 'GET',
                    data: filtros,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        // Actualizar tabla
                        $('#tabla-container').html(response.tabla);
                        
                        // Actualizar tarjetas de resumen
                        $('#summary-cards').html(response.summary);
                        
                        // Actualizar parámetros de paginación
                        setupPaginationEvents();
                        
                        // Restaurar valores de filtros en selects
                        $('#estado').val(filtros.estado);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', error);
                        alert('Error al aplicar filtros: ' + error);
                    },
                    complete: function() {
                        $('#loading-indicator').addClass('hidden');
                    }
                });
            }, 500); // Debounce de 500ms
        }

        // Función para limpiar filtros
        function limpiarFiltros() {
            $('#estado').val('');
            $('#codigo_repuesto').val('');
            $('#fecha_desde').val('');
            $('#fecha_hasta').val('');
            
            // Limpiar flatpickr
            if (window.flatpickrInstances) {
                window.flatpickrInstances.forEach(instance => {
                    instance.clear();
                });
            }
            
            currentPage = 1;
            aplicarFiltrosAJAX();
        }

        // Función para configurar eventos de paginación
        function setupPaginationEvents() {
            $('.pagination a').on('click', function(e) {
                e.preventDefault();
                
                const url = $(this).attr('href');
                const pageMatch = url.match(/page=(\d+)/);
                
                if (pageMatch) {
                    currentPage = pageMatch[1];
                    aplicarFiltrosAJAX();
                }
            });
        }

        // Event Listeners para filtros
        $(document).ready(function() {
            // Inicializar flatpickr
            const dateInputs = document.querySelectorAll('.flatpickr-date');
            window.flatpickrInstances = [];
            
            if (dateInputs.length > 0) {
                dateInputs.forEach(input => {
                    const fp = flatpickr(input, {
                        locale: "es",
                        dateFormat: "Y-m-d",
                        altFormat: "d/m/Y",
                        altInput: true,
                        allowInput: true,
                        theme: "airbnb",
                        onChange: function() {
                            aplicarFiltrosAJAX();
                        },
                        onReady: function() {
                            if (document.documentElement.classList.contains('dark')) {
                                this.calendarContainer.classList.add('flatpickr-dark');
                            }
                        }
                    });
                    window.flatpickrInstances.push(fp);
                });
            }

            // Eventos para filtros
            $('.filter-select, .filter-input').on('change keyup', function() {
                aplicarFiltrosAJAX();
            });

            // Evento para botón limpiar
            $('#btnLimpiar').on('click', function() {
                limpiarFiltros();
            });

            // Configurar paginación inicial
            setupPaginationEvents();

            // Preservar filtros al cargar la página
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('estado')) {
                $('#estado').val(urlParams.get('estado'));
            }
            if (urlParams.get('codigo_repuesto')) {
                $('#codigo_repuesto').val(urlParams.get('codigo_repuesto'));
            }
        });

        // Alpine.js para modal (igual que antes)
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalDetails', () => ({
                open: false,
                loading: false,
                dataLoaded: false,
                modalTitle: 'Detalles del Repuesto',
                modalSubtitle: '',
                details: {},
                currentId: null,
                fotos: {
                    cargandoFotos: false,
                    tieneFotos: false,
                    fotoRepuesto: { tiene: false, fotos: [] },
                    foto_articulo_usado: { tiene: false, fotos: [] },
                    foto_articulo_no_usado: { tiene: false, fotos: [] },
                    foto_entrega: { tiene: false, fotos: [] }
                },
                viewer: null,

                get tieneFotos() {
                    return this.fotos.tieneFotos;
                },

                get totalFotos() {
                    let total = 0;
                    total += this.fotos.fotoRepuesto.fotos?.length || 0;
                    total += this.fotos.foto_articulo_usado.fotos?.length || 0;
                    total += this.fotos.foto_articulo_no_usado.fotos?.length || 0;
                    total += this.fotos.foto_entrega.fotos?.length || 0;
                    return total;
                },

                toggle() {
                    this.open = !this.open;
                    if (!this.open) {
                        this.reset();
                        if (this.viewer) {
                            this.viewer.destroy();
                            this.viewer = null;
                        }
                    }
                },

                reset() {
                    this.loading = false;
                    this.dataLoaded = false;
                    this.details = {};
                    this.currentId = null;
                    this.resetFotos();
                },

                resetFotos() {
                    this.fotos = {
                        cargandoFotos: false,
                        tieneFotos: false,
                        fotoRepuesto: { tiene: false, fotos: [] },
                        foto_articulo_usado: { tiene: false, fotos: [] },
                        foto_articulo_no_usado: { tiene: false, fotos: [] },
                        foto_entrega: { tiene: false, fotos: [] }
                    };
                },

                async loadDetails(id) {
                    this.currentId = id;
                    this.loading = true;
                    this.dataLoaded = false;
                    this.resetFotos();

                    try {
                        const response = await fetch(`/repuestos-transito/${id}/detalles`);
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);

                        const data = await response.json();
                        if (data.success && data.data) {
                            this.details = data.data;
                            this.modalTitle = data.data.nombre_repuesto || 'Detalles del Repuesto';
                            this.modalSubtitle = `Código: ${data.data.codigo_repuesto || 'N/A'} | Estado: ${this.getStatusText(data.data.estado)}`;

                            if (data.data.tiene_fotos) {
                                await this.loadFotos(id);
                            }

                            this.dataLoaded = true;
                        } else {
                            alert(data.message || 'Error al cargar detalles');
                        }
                    } catch (error) {
                        console.error('Error al cargar detalles:', error);
                        alert('Error de conexión: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },

                async loadFotos(id) {
                    this.fotos.cargandoFotos = true;

                    try {
                        const response = await fetch(`/repuestos-transito/${id}/fotos`);
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);

                        const data = await response.json();
                        if (data.success && data.fotos) {
                            this.resetFotos();

                            const tiposFotos = [
                                'fotoRepuesto',
                                'foto_articulo_usado',
                                'foto_articulo_no_usado',
                                'foto_entrega'
                            ];

                            tiposFotos.forEach(tipo => {
                                const fotoData = data.fotos[tipo];
                                if (fotoData && fotoData.tiene && fotoData.fotos && fotoData.fotos.length > 0) {
                                    this.fotos[tipo].tiene = true;
                                    this.fotos[tipo].fotos = fotoData.fotos;
                                    this.fotos.tieneFotos = true;
                                }
                            });

                            this.$nextTick(() => {
                                this.initViewer();
                            });
                        }
                    } catch (error) {
                        console.error('Error al cargar fotos:', error);
                    } finally {
                        this.fotos.cargandoFotos = false;
                    }
                },

                initViewer() {
                    this.$nextTick(() => {
                        setTimeout(() => {
                            const images = this.$el.querySelectorAll('img[src*="base64"]');
                            if (images.length > 0) {
                                try {
                                    const imageElements = Array.from(images);
                                    this.viewer = new Viewer(imageElements, {
                                        className: 'viewerjs-modal',
                                        title: (image, imageData) => image.alt || 'Imagen',
                                        toolbar: {
                                            zoomIn: 1,
                                            zoomOut: 1,
                                            oneToOne: 1,
                                            reset: 1,
                                            prev: 1,
                                            next: 1,
                                            rotateLeft: 1,
                                            rotateRight: 1,
                                            flipHorizontal: 1,
                                            flipVertical: 1,
                                        }
                                    });
                                } catch (error) {
                                    console.error('Error al inicializar Viewer.js:', error);
                                }
                            }
                        }, 500);
                    });
                },

                viewImage(imgElement) {
                    if (typeof Viewer === 'undefined') {
                        alert('Viewer.js no está disponible. Recarga la página.');
                        return;
                    }

                    if (this.viewer) {
                        try {
                            const allImages = Array.from(this.$el.querySelectorAll('img[src*="base64"]'));
                            const index = allImages.indexOf(imgElement);
                            if (index !== -1) {
                                this.viewer.view(index);
                            } else {
                                this.viewer.show();
                            }
                        } catch (error) {
                            console.error('Error al abrir Viewer.js:', error);
                            window.open(imgElement.src, '_blank');
                        }
                    } else {
                        window.open(imgElement.src, '_blank');
                    }
                },

             getStatusClass(status) {
    switch (status) {
        case 'en_transito':
            return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
        case 'cedido':
            return 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400';  // NUEVO
        case 'usado':
            return 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400';
        case 'devuelto':
            return 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400';
        case 'pendiente':
            return 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400';
        default:
            return 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-400';
    }
},

getStatusIcon(status) {
    switch (status) {
        case 'en_transito':
            return 'fas fa-shipping-fast';
        case 'cedido':
            return 'fas fa-exchange-alt';  // NUEVO
        case 'usado':
            return 'fas fa-check-circle';
        case 'devuelto':
            return 'fas fa-undo-alt';
        case 'pendiente':
            return 'fas fa-clock';
        default:
            return 'fas fa-question-circle';
    }
},

getStatusText(status) {
    switch (status) {
        case 'en_transito':
            return 'En Tránsito';
        case 'cedido':
            return 'Cedido';  // NUEVO
        case 'usado':
            return 'Usado';
        case 'devuelto':
            return 'Devuelto';
        case 'pendiente':
            return 'Pendiente';
        default:
            return status || 'Sin Estado';
    }
},
                formatDate(dateString) {
                    if (!dateString) return 'No disponible';
                    try {
                        const date = new Date(dateString);
                        return isNaN(date.getTime()) ? 'Fecha inválida' : date.toLocaleDateString('es-PE');
                    } catch {
                        return 'Fecha inválida';
                    }
                },

                formatDateTime(dateString) {
                    if (!dateString) return 'No disponible';
                    try {
                        const date = new Date(dateString);
                        if (isNaN(date.getTime())) return 'Fecha inválida';
                        return date.toLocaleDateString('es-PE') + ' ' + date.toLocaleTimeString('es-PE');
                    } catch {
                        return 'Fecha inválida';
                    }
                }
            }));
        });

        // Función global para abrir modal
        window.openDetailsModal = function(id) {
            const modal = document.querySelector('[x-data="modalDetails"]');
            if (modal) {
                const instance = Alpine.$data(modal);
                instance.toggle();
                instance.loadDetails(id);
            }
        };
    </script>
</x-layout.default>