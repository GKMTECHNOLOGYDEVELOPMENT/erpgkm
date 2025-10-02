<x-layout.default>
    <!-- Incluir Axios CDN -->
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.7);
            /* gris */
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

    <div x-data="solicitudApp()" class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Solicitudes de Ingreso</h1>
            <p class="text-gray-600">Gestiona las solicitudes de ingreso agrupadas por origen</p>
        </div>

        <!-- Filtros -->
        <div class="panel rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por estado:</label>
                    <select x-model="filtroEstado"
                            class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="todos">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="recibido">Recibido</option>
                        <option value="ubicado">Ubicado</option>
                        <option value="actualizar">Actualizar Solicitud</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por origen:</label>
                    <select x-model="filtroOrigen"
                        class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="todos">Todos</option>
                        <option value="compra">Compra</option>
                        <option value="entrada_proveedor">Entrada Proveedor</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[300px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar:</label>
                    <input type="text" x-model="searchTerm" placeholder="Buscar por código, proveedor, artículo..."
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600">Pendientes</p>
                        <p class="text-2xl font-bold text-blue-800" x-text="contadores.pendiente"></p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600">Recibidos</p>
                        <p class="text-2xl font-bold text-green-800" x-text="contadores.recibido"></p>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-map-marker-alt text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600">Ubicados</p>
                        <p class="text-2xl font-bold text-purple-800" x-text="contadores.ubicado"></p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <i class="fas fa-boxes text-gray-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Grupos</p>
                        <p class="text-2xl font-bold text-gray-800" x-text="contadores.total"></p>
                    </div>
                </div>
            </div>

            <!-- En las estadísticas -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i class="fas fa-sync-alt text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-orange-600">Por Actualizar</p>
                        <p class="text-2xl font-bold text-orange-800" x-text="contadores.actualizar"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Cards Agrupadas -->
        <template x-if="solicitudesAgrupadasFiltradas.length === 0">
            <div class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron solicitudes agrupadas</p>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6">
            <template x-for="grupo in solicitudesAgrupadasFiltradas" :key="grupo.origen + '_' + grupo.origen_id">
                <div
                    class="panel rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <!-- Header de la Card Grupal -->
                    <div class="border-b border-gray-200 p-4 bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    :class="getEstadoClasses(grupo.estado_general)">
                                    <i :class="getEstadoIcon(grupo.estado_general)" class="mr-1"></i>
                                    <span
                                        x-text="grupo.estado_general.charAt(0).toUpperCase() + grupo.estado_general.slice(1)"></span>
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                    :class="grupo.origen === 'compra' ? 'bg-blue-100 text-blue-800' :
                                        'bg-green-100 text-green-800'">
                                    <i :class="grupo.origen === 'compra' ? 'fas fa-shopping-cart' : 'fas fa-truck'"
                                        class="mr-1"></i>
                                    <span x-text="grupo.origen === 'compra' ? 'Compra' : 'Entrada Proveedor'"></span>
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-gray-800"
                                    x-text="grupo.origen === 'compra' ? grupo.origen_especifico.codigocompra : grupo.origen_especifico.codigo_entrada">
                                </h3>
                                <p class="text-sm text-gray-600"
                                    x-text="grupo.mostrar_cliente ? 'Cliente: ' + grupo.cliente_general.descripcion : 'Proveedor: ' + grupo.proveedor.nombre">
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800"
                                    x-text="grupo.total_articulos + ' artículos'"></p>
                                <p class="text-sm text-gray-600"
                                    x-text="'Total: ' + grupo.total_cantidad + ' unidades'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Body de la Card - Lista de Artículos -->
                    <div class="p-4">
                        <div class="mb-3">
                            <p class="text-sm font-medium text-gray-700">Artículos en esta solicitud:</p>
                        </div>

                        <!-- Contenedor fijo para artículos con scroll -->
                        <div class="border border-gray-200 rounded-md bg-gray-50">
                            <div class="relative h-64 overflow-y-auto pr-2 custom-scrollbar p-2">
                                <div class="space-y-3">
                                    <template x-for="solicitud in grupo.solicitudes"
                                        :key="solicitud.idSolicitudIngreso">
                                        <div class="border border-gray-200 rounded-lg p-3 bg-white shadow-sm">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-medium text-gray-800"
                                                            x-text="getNombreArticulo(solicitud.articulo)"></p>
                                                        <!-- Indicador de serie -->
                                                        <template
                                                            x-if="solicitud.articulo && solicitud.articulo.maneja_serie === 1">
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                <i class="fas fa-barcode mr-1"></i>
                                                                Serie
                                                            </span>
                                                        </template>
                                                    </div>
                                                    <p class="text-xs text-gray-500"
                                                        x-text="'Tipo: ' + getTipoArticulo(solicitud.articulo?.idTipoArticulo)">
                                                    </p>
                                                </div>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium ml-2"
                                                    :class="getEstadoClasses(solicitud.estado)">
                                                    <span x-text="solicitud.estado"></span>
                                                </span>
                                            </div>

                                            <!-- Info cantidad y ubicación -->
                                            <div class="grid grid-cols-1 gap-2 text-xs">
                                                <div class="flex justify-between">
                                                    <div>
                                                        <span class="text-gray-500">Cantidad:</span>
                                                        <span class="font-medium ml-1"
                                                            x-text="solicitud.cantidad"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Ubicación:</span>
                                                        <span class="font-medium ml-1"
                                                            x-text="getUbicacionesTexto(solicitud)"></span>
                                                    </div>
                                                </div>
                                                <template x-if="solicitud.series && solicitud.series.length > 0">
                                                    <div>
                                                        <span class="text-gray-500">Series:</span>
                                                        <button
                                                            @click="seriesModal = true; seriesSeleccionadas = solicitud.series"
                                                            class="ml-2 text-blue-600 text-xs underline hover:text-blue-800">
                                                            Ver series (<span x-text="solicitud.series.length"></span>)
                                                        </button>
                                                    </div>
                                                </template>

                                            </div>

                                            <!-- Botones -->
                                            <div class="flex space-x-1 mt-2">
                                                <button
                                                    @click="cambiarEstado(solicitud.idSolicitudIngreso, 'pendiente')"
                                                    class="flex-1 px-2 py-1 text-xs rounded transition-colors"
                                                    :class="solicitud.estado === 'pendiente' ?
                                                        'bg-yellow-100 text-yellow-800' :
                                                        'bg-gray-100 text-gray-600 hover:bg-yellow-50'">
                                                    Pendiente
                                                </button>
                                                <button
                                                    @click="cambiarEstado(solicitud.idSolicitudIngreso, 'recibido')"
                                                    class="flex-1 px-2 py-1 text-xs rounded transition-colors"
                                                    :class="solicitud.estado === 'recibido' ? 'bg-green-100 text-green-800' :
                                                        'bg-gray-100 text-gray-600 hover:bg-green-50'">
                                                    Recibido
                                                </button>
                                                <button @click="abrirModalUbicacion(solicitud)"
                                                    class="flex-1 px-2 py-1 text-xs rounded transition-colors"
                                                    :class="solicitud.estado === 'ubicado' ? 'bg-purple-100 text-purple-800' :
                                                        'bg-gray-100 text-gray-600 hover:bg-purple-50'">
                                                    Ubicar
                                                </button>
                                                <!-- Nuevo botón para Actualizar Solicitud - DESHABILITADO cuando esté ubicado -->
                                                <button @click="abrirModalActualizar(solicitud)"
                                                        :disabled="solicitud.estado === 'ubicado'"
                                                        class="flex-1 px-2 py-1 text-xs rounded transition-colors"
                                                        :class="solicitud.estado === 'ubicado' ? 
                                                                'bg-gray-100 text-gray-400 cursor-not-allowed' :
                                                                solicitud.estado === 'actualizar' ? 
                                                                'bg-orange-100 text-orange-800' : 
                                                                'bg-gray-100 text-gray-600 hover:bg-orange-50'">
                                                    <template x-if="solicitud.estado === 'ubicado'">
                                                        <span title="No se puede actualizar una solicitud ya ubicada">Actualizar</span>
                                                    </template>
                                                    <template x-if="solicitud.estado !== 'ubicado'">
                                                        <span>Actualizar</span>
                                                    </template>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Footer de la Card Grupal -->
                            <div class="border-t border-gray-200 p-4 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500"
                                        x-text="'Fecha origen: ' + formatFecha(grupo.fecha_origen)"></span>
                                    <div class="flex space-x-2">
                                        <button @click="cambiarEstadoGrupo(grupo, 'pendiente')"
                                            class="px-3 py-1 text-xs rounded-lg transition-colors"
                                            :class="grupo.estado_general === 'pendiente' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-gray-100 text-gray-600 hover:bg-yellow-50'">
                                            Todos Pendiente
                                        </button>
                                        <button @click="cambiarEstadoGrupo(grupo, 'recibido')"
                                            class="px-3 py-1 text-xs rounded-lg transition-colors"
                                            :class="grupo.estado_general === 'recibido' ? 'bg-green-100 text-green-800' :
                                                'bg-gray-100 text-gray-600 hover:bg-green-50'">
                                            Todos Recibido
                                        </button>
                                        <button @click="cambiarEstadoGrupo(grupo, 'ubicado')"
                                            class="px-3 py-1 text-xs rounded-lg transition-colors"
                                            :class="grupo.estado_general === 'ubicado' ? 'bg-purple-100 text-purple-800' :
                                                'bg-gray-100 text-gray-600 hover:bg-purple-50'">
                                            Todos Ubicado
                                        </button>
                                        <!-- Nuevo botón grupal -->
                                        <button @click="cambiarEstadoGrupo(grupo, 'actualizar')"
                                                :disabled="grupo.estado_general === 'ubicado' || grupo.solicitudes.some(s => s.estado === 'ubicado')"
                                                class="px-3 py-1 text-xs rounded-lg transition-colors"
                                                :class="(grupo.estado_general === 'ubicado' || grupo.solicitudes.some(s => s.estado === 'ubicado')) ? 
                                                        'bg-gray-100 text-gray-400 cursor-not-allowed' :
                                                        grupo.estado_general === 'actualizar' ? 
                                                        'bg-orange-100 text-orange-800' : 
                                                        'bg-gray-100 text-gray-600 hover:bg-orange-50'">
                                            <template x-if="grupo.estado_general === 'ubicado' || grupo.solicitudes.some(s => s.estado === 'ubicado')">
                                                <span title="No se puede actualizar solicitudes ya ubicadas">Todos Actualizar</span>
                                            </template>
                                            <template x-if="!(grupo.estado_general === 'ubicado' || grupo.solicitudes.some(s => s.estado === 'ubicado'))">
                                                <span>Todos Actualizar</span>
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </template>
        </div>
        <!-- Modal de series -->
        <!-- Modal Extra Grande con Grid -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden" :class="seriesModal && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="seriesModal = false">
                <div x-show="seriesModal" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-5xl my-8">

                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">
                            <i class="fas fa-barcode text-blue-600 mr-2"></i> Series del Artículo
                        </h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="seriesModal = false">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido con scroll -->
                    <div class="p-5 max-h-[70vh] overflow-y-auto">
                        <template x-if="seriesSeleccionadas.length > 0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                <template x-for="(serie, index) in seriesSeleccionadas" :key="index">
                                    <div class="border rounded-lg p-3 bg-white shadow-sm flex flex-col">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs text-gray-400"># <span
                                                    x-text="index + 1"></span></span>
                                        </div>
                                        <p class="font-mono font-semibold text-gray-800 text-sm truncate"
                                            x-text="serie.numero_serie"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="seriesSeleccionadas.length === 0">
                            <p class="text-gray-500 text-sm">No hay series registradas.</p>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end items-center px-5 py-3 bg-gray-50">
                        <button type="button" class="btn btn-outline-danger" @click="seriesModal = false">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Ubicación con Series -->
        <div x-data="{ open: false }">
            <!-- Modal -->
            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
                :class="modalUbicacionAbierto && '!block'">
                <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModalUbicacion()">
                    <div x-show="modalUbicacionAbierto" x-transition x-transition.duration.300
                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-4xl max-h-[95vh]">

                        <!-- Header -->
                        <div
                            class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3 border-b">
                            <div class="font-bold text-lg text-gray-800 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                Ubicar Artículo
                                <template x-if="solicitudSeleccionada?.articulo?.maneja_serie === 1">
                                    <span class="text-sm font-normal text-blue-600 ml-2 flex items-center">
                                        <i class="fas fa-barcode mr-1"></i> (Requiere Series)
                                    </span>
                                </template>
                            </div>
                            <button type="button" class="text-gray-400 hover:text-gray-600" @click="open = false">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>

                        <!-- Contenido -->
                        <div class="p-5 overflow-y-auto max-h-[75vh]">
                            <!-- Información del artículo -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-4" x-show="solicitudSeleccionada">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium"
                                            x-text="'Artículo: ' + getNombreArticulo(solicitudSeleccionada?.articulo)">
                                        </p>
                                        <p class="text-sm text-gray-600"
                                            x-text="'Cantidad total: ' + (solicitudSeleccionada?.cantidad || 0)">
                                        </p>
                                    </div>
                                    <template x-if="solicitudSeleccionada?.articulo?.maneja_serie === 1">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-barcode mr-1"></i> Maneja Series
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <!-- Formulario de ubicaciones -->
                            <div class="space-y-4 mb-6">
                                <h4 class="text-md font-medium text-gray-800">
                                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                    Ubicaciones
                                </h4>
                                <template x-for="(ubicacion, index) in ubicacionesForm" :key="index">
                                    <div class="flex gap-3 items-start border border-gray-200 rounded-lg p-3">
                                        <div class="flex-1">
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                                            <select x-model="ubicacion.ubicacion_id"
                                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Seleccionar ubicación</option>
                                                <template x-for="ubic in ubicaciones" :key="ubic.idUbicacion">
                                                    <option :value="ubic.idUbicacion" x-text="ubic.nombre"
                                                        :selected="ubicacion.ubicacion_id == ubic.idUbicacion || ubic.nombre ===
                                                            ubicacion
                                                            .nombre_ubicacion">
                                                    </option>
                                                </template>
                                            </select>
                                            <template x-if="ubicacion.nombre_ubicacion && !ubicacion.ubicacion_id">
                                                <p class="text-xs text-orange-600 mt-1">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Ubicación temporal: <span
                                                        x-text="ubicacion.nombre_ubicacion"></span>
                                                </p>
                                            </template>
                                        </div>
                                        <div class="w-32">
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                                            <input type="number" x-model="ubicacion.cantidad"
                                                :max="cantidadDisponible + (parseInt(ubicacion.cantidad) || 0)"
                                                min="1"
                                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div class="pt-6">
                                            <button type="button" @click="eliminarUbicacion(index)"
                                                class="btn btn-danger" :disabled="ubicacionesForm.length === 1">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <!-- Cantidad disponible -->
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <span x-text="'Cantidad disponible: ' + cantidadDisponible"></span>
                                        <span x-show="cantidadDisponible === 0" class="text-red-600 font-medium"> -
                                            ¡Toda la
                                            cantidad ha sido distribuida!</span>
                                    </p>
                                </div>

                                <!-- Botón para agregar más ubicaciones -->
                                <button type="button" @click="agregarUbicacion" :disabled="cantidadDisponible === 0"
                                    class="flex items-center gap-2 text-blue-600 hover:text-blue-800 disabled:text-gray-400 disabled:cursor-not-allowed">
                                    <i class="fas fa-plus"></i>
                                    Agregar otra ubicación
                                </button>
                            </div>

                            <!-- Sección de Series (solo si maneja_serie = 1) -->
                            <div x-show="articuloRequiereSeries" class="border-t border-gray-200 pt-6">
                                <h4 class="text-md font-medium text-gray-800 mb-3">
                                    <i class="fas fa-barcode mr-2 text-blue-500"></i>
                                    Números de Serie Requeridos
                                </h4>

                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-amber-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Este artículo requiere números de serie únicos. Debe ingresar
                                        <span class="font-bold" x-text="solicitudSeleccionada?.cantidad || 0"></span>
                                        número(s) de serie.
                                    </p>
                                </div>

                                <!-- Contador de progreso -->
                                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-blue-800">
                                            Series completadas:
                                            <span class="font-bold" x-text="seriesCompletadas"></span> /
                                            <span x-text="solicitudSeleccionada?.cantidad || 0"></span>
                                        </span>
                                        <span x-show="seriesCompletadas === (solicitudSeleccionada?.cantidad || 0)"
                                            class="text-success font-medium text-sm">
                                            <i class="fas fa-check-circle"></i> Completo
                                        </span>
                                    </div>

                                    <!-- Barra de progreso -->
                                    <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                            :style="`width: ${((seriesCompletadas / (solicitudSeleccionada?.cantidad || 1)) * 100)}%`">
                                        </div>
                                    </div>
                                </div>

                                <!-- Lista de campos de series -->
                                <div
                                    class="space-y-3 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                                    <template x-for="(serie, index) in seriesForm" :key="index">
                                        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
                                            <div class="flex gap-3 items-start">
                                                <!-- Número de serie -->
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        <i class="fas fa-hashtag mr-1"></i>
                                                        <span x-text="'Serie #' + (index + 1)"></span>
                                                    </label>
                                                    <div class="relative">
                                                        <input type="text" x-model="serie.numero_serie"
                                                            :placeholder="'Ej: SN' + String(index + 1).padStart(3, '0') + '12345'"
                                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-10 text-sm"
                                                            maxlength="50"
                                                            @input="$nextTick(() => console.log('Serie actualizada:', serie.numero_serie))">
                                                    </div>

                                                    <!-- Validación de series duplicadas -->
                                                    <template x-if="validarSerieDuplicada(serie.numero_serie, index)">
                                                        <p class="text-xs text-red-600 mt-1 flex items-center">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            Este número de serie ya está siendo usado
                                                        </p>
                                                    </template>

                                                    <!-- Indicador de campo completo -->
                                                    <template
                                                        x-if="serie.numero_serie && serie.numero_serie.trim() !== ''">
                                                        <p class="text-xs text-green-600 mt-1 flex items-center">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Serie válida
                                                        </p>
                                                    </template>

                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Mensaje si no hay series para mostrar -->
                                <template x-if="seriesForm.length === 0">
                                    <div class="text-center py-4 text-gray-500 text-sm">
                                        <i class="fas fa-barcode text-2xl mb-2"></i>
                                        <p>No se han inicializado campos de series</p>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer -->
                            <div
                                class="flex justify-end items-center gap-3 px-5 py-3 border-t bg-[#fbfbfb] dark:bg-[#121c2c]">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="cerrarModalUbicacion()">
                                    Cancelar
                                </button>

                                <button type="button" class="btn btn-primary" :disabled="!puedeGuardar"
                                    @click="guardarUbicaciones">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar&nbsp;
                                    <span x-show="articuloRequiereSeries">Ubicaciones y Series</span>
                                    <span x-show="!articuloRequiereSeries">Ubicaciones</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






<!-- Modal para Actualizar Solicitud -->
<div class="fixed inset-0 bg-[black]/60 z-[999] hidden" :class="modalActualizarAbierto && '!block'">
    <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModalActualizar()">
        <div x-show="modalActualizarAbierto" x-transition x-transition.duration.300
             class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl max-h-[95vh]">
            
            <!-- Header -->
            <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3 border-b">
                <div class="font-bold text-lg text-gray-800 flex items-center">
                    <i class="fas fa-sync-alt mr-2 text-orange-500"></i>
                    Actualizar Solicitud de Ingreso
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600" @click="cerrarModalActualizar()">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Contenido -->
            <div class="p-5 overflow-y-auto max-h-[75vh]">
                <!-- Información de la solicitud (SOLO LECTURA) -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6" x-show="solicitudActualizar">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-medium text-gray-700">Artículo:</p>
                            <p class="text-lg font-semibold" x-text="getNombreArticulo(solicitudActualizar?.articulo)"></p>
                            <p class="text-sm text-gray-600" 
                               x-text="'Tipo: ' + getTipoArticulo(solicitudActualizar?.articulo?.idTipoArticulo)"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Cantidad Actual:</p>
                            <p class="text-lg font-semibold" x-text="solicitudActualizar?.cantidad || 0"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Origen:</p>
                            <p class="text-sm" x-text="solicitudActualizar?.origen === 'compra' ? 'Compra' : 'Entrada Proveedor'"></p>
                            <p class="text-sm text-gray-600" 
                               x-text="solicitudActualizar?.origen === 'compra' ? 
                                       solicitudActualizar?.origen_especifico?.codigocompra : 
                                       solicitudActualizar?.origen_especifico?.codigo_entrada"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Estado Actual:</p>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                  :class="getEstadoClasses(solicitudActualizar?.estado)">
                                <i :class="getEstadoIcon(solicitudActualizar?.estado)" class="mr-1"></i>
                                <span x-text="solicitudActualizar?.estado"></span>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Información adicional (solo lectura) -->
                    <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200">
                        <div>
                            <p class="font-medium text-gray-700 text-sm">Fecha Origen:</p>
                            <p class="text-sm" x-text="formatFecha(solicitudActualizar?.fecha_origen)"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700 text-sm">Lote:</p>
                            <p class="text-sm" x-text="solicitudActualizar?.lote || 'N/A'"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700 text-sm">Fecha Vencimiento:</p>
                            <p class="text-sm" x-text="solicitudActualizar?.fecha_vencimiento ? formatFecha(solicitudActualizar.fecha_vencimiento) : 'N/A'"></p>
                        </div>
                    </div> -->
                </div>

                <!-- Formulario de actualización SIMPLIFICADO -->
                <div class="space-y-6">
                    <h4 class="text-md font-medium text-gray-800 border-b pb-2">
                        <i class="fas fa-edit mr-2 text-blue-500"></i>
                        Modificar Información
                    </h4>

                    <!-- Solo Cantidad y Observaciones -->
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Cantidad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-boxes mr-1"></i>
                                Cantidad *
                            </label>
                            <input type="number" x-model="formActualizar.cantidad" min="1"
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">
                                Cantidad actual: <span class="font-semibold" x-text="solicitudActualizar?.cantidad || 0"></span>
                            </p>
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-sticky-note mr-1"></i>
                                Observaciones
                            </label>
                            <textarea x-model="formActualizar.observaciones" rows="3"
                                      placeholder="Ingrese observaciones adicionales..."
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <!-- Mensaje informativo -->
                    <!-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Información importante</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    Solo puedes modificar la cantidad y observaciones. Los demás campos son informativos y no se pueden modificar.
                                </p>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end items-center gap-3 px-5 py-3 border-t bg-[#fbfbfb] dark:bg-[#121c2c]">
                <button type="button" class="btn btn-outline-danger" @click="cerrarModalActualizar()">
                    Cancelar
                </button>
                <button type="button" class="btn btn-warning" :disabled="!puedeActualizar" @click="guardarActualizacion">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Actualizar Solicitud
                </button>
            </div>
        </div>
    </div>
</div>



    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function solicitudApp() {
            return {
                solicitudesAgrupadas: @json($solicitudesAgrupadas),
                ubicaciones: @json($ubicaciones),
                modalUbicacionAbierto: false,
                modalActualizarAbierto: false,
                solicitudActualizar: null,
                seriesModal: false,
                seriesSeleccionadas: [],
                solicitudSeleccionada: null,
                ubicacionesForm: [],
                seriesForm: [], // Nueva propiedad para series
                formActualizar: {
                    cantidad: 0,
                    observaciones: ''
                },

                filtroEstado: 'todos',
                filtroOrigen: 'todos',
                searchTerm: '',

                get contadores() {
                    const grupos = this.solicitudesAgrupadasFiltradas;
                    return {
                        pendiente: grupos.filter(g => g.estado_general === 'pendiente').length,
                        recibido: grupos.filter(g => g.estado_general === 'recibido').length,
                        ubicado: grupos.filter(g => g.estado_general === 'ubicado').length,
                        total: grupos.length
                    };
                },


                // Computed property para validar
get puedeActualizar() {
    return this.formActualizar.cantidad > 0;
},

// Método para abrir el modal de actualización con validación
abrirModalActualizar(solicitud) {
    console.log('=== DEBUG abrirModalActualizar ===');
    console.log('Solicitud a actualizar:', solicitud);
    
    // Validar que no esté ubicado
    if (solicitud.estado === 'ubicado') {
        this.mostrarNotificacion('No se puede actualizar una solicitud ya ubicada', 'warning');
        return;
    }
    
    this.solicitudActualizar = solicitud;
    
    // Llenar el formulario solo con campos editables
    this.formActualizar = {
        cantidad: solicitud.cantidad || 0,
        observaciones: solicitud.observaciones || ''
    };
    
    this.modalActualizarAbierto = true;
},

// Método para cerrar el modal
cerrarModalActualizar() {
    this.modalActualizarAbierto = false;
    this.solicitudActualizar = null;
    this.formActualizar = {
        cantidad: 0,
        observaciones: ''
    };
},

// Método para formatear fecha para input type="date"
formatFechaParaInput(fecha) {
    if (!fecha) return '';
    return new Date(fecha).toISOString().split('T')[0];
},

// Método para guardar la actualización SIMPLIFICADO
async guardarActualizacion() {
    console.log('=== DEBUG guardarActualizacion ===');
    console.log('Datos a guardar:', this.formActualizar);
    
    if (!this.puedeActualizar) {
        this.mostrarNotificacion('La cantidad debe ser mayor a 0', 'error');
        return;
    }

    try {
        const response = await axios.post(`/solicitud-ingreso/${this.solicitudActualizar.idSolicitudIngreso}/actualizar`, this.formActualizar);

        if (response.data.success) {
            this.mostrarNotificacion(response.data.message, 'success');

            // Actualizar la solicitud en la vista
            this.solicitudesAgrupadas.forEach(grupo => {
                grupo.solicitudes.forEach(solicitud => {
                    if (solicitud.idSolicitudIngreso === this.solicitudActualizar.idSolicitudIngreso) {
                        // Actualizar solo los datos editables
                        solicitud.cantidad = this.formActualizar.cantidad;
                        solicitud.observaciones = this.formActualizar.observaciones;
                        solicitud.estado = 'actualizar'; // Cambiar estado a actualizar
                        
                        console.log('✅ Solicitud actualizada en vista:', solicitud);
                    }
                });
                
                // Recalcular estado general del grupo
                grupo.estado_general = this.calcularEstadoGeneral(grupo.solicitudes);
            });

            this.cerrarModalActualizar();
        }
    } catch (error) {
        console.error('❌ Error al actualizar solicitud:', error);
        const message = error.response?.data?.message || 'Error al actualizar la solicitud';
        this.mostrarNotificacion(message, 'error');
    }
},




                get solicitudesAgrupadasFiltradas() {
                    return this.solicitudesAgrupadas.filter(grupo => {
                        const coincideEstado = this.filtroEstado === 'todos' || grupo.estado_general === this
                            .filtroEstado;
                        const coincideOrigen = this.filtroOrigen === 'todos' || grupo.origen === this
                            .filtroOrigen;

                        const codigoOrigen = grupo.origen === 'compra' ?
                            grupo.origen_especifico.codigocompra : grupo.origen_especifico.codigo_entrada;

                        const textoBusqueda = this.searchTerm.toLowerCase();
                        const coincideBusqueda = !this.searchTerm ||
                            codigoOrigen.toLowerCase().includes(textoBusqueda) ||
                            (grupo.proveedor?.nombre?.toLowerCase().includes(textoBusqueda)) ||
                            (grupo.cliente_general?.descripcion?.toLowerCase().includes(textoBusqueda)) ||
                            grupo.solicitudes.some(s =>
                                this.getNombreArticulo(s.articulo).toLowerCase().includes(textoBusqueda)
                            );

                        return coincideEstado && coincideOrigen && coincideBusqueda;
                    });
                },

                get cantidadDisponible() {
                    const totalAsignado = this.ubicacionesForm.reduce((sum, ubic) => sum + (parseInt(ubic.cantidad) ||
                        0), 0);
                    return (this.solicitudSeleccionada?.cantidad || 0) - totalAsignado;
                },

                // Nuevas propiedades computed para series
                get articuloRequiereSeries() {
                    return this.solicitudSeleccionada?.articulo?.maneja_serie === 1;
                },

                get seriesCompletadas() {
                    if (!this.articuloRequiereSeries) return 0;

                    return this.seriesForm.filter(serie =>
                        serie.numero_serie && serie.numero_serie.trim() !== ''
                    ).length;
                },


                get puedeGuardar() {
                    if (this.ubicacionesForm.length === 0) return false;

                    const totalAsignado = this.ubicacionesForm.reduce((sum, ubic) => sum + (parseInt(ubic.cantidad) ||
                        0), 0);
                    const cantidadTotal = this.solicitudSeleccionada?.cantidad || 0;

                    const ubicacionesValidas = this.ubicacionesForm.every(ubic =>
                        ubic.ubicacion_id && ubic.cantidad > 0
                    );

                    // Si requiere series, validar que estén completas
                    if (this.articuloRequiereSeries) {
                        const seriesValidas = this.seriesForm.length === cantidadTotal &&
                            this.seriesCompletadas === cantidadTotal &&
                            !this.tieneSerieDuplicada();

                        return ubicacionesValidas && totalAsignado === cantidadTotal && seriesValidas;
                    }

                    return ubicacionesValidas && totalAsignado === cantidadTotal;
                },

                // Función para obtener el nombre correcto del artículo
                getNombreArticulo(articulo) {
                    if (!articulo) return 'N/A';

                    if (articulo.idTipoArticulo === 2) {
                        return articulo.codigo_repuesto || articulo.nombre || 'N/A';
                    } else {
                        return articulo.nombre || 'N/A';
                    }
                },

                // Función para mostrar las ubicaciones del artículo
                getUbicacionesTexto(solicitud) {
                    if (solicitud.ubicacion && solicitud.ubicacion !== 'null' && solicitud.ubicacion !== '') {
                        return solicitud.ubicacion;
                    }

                    if (!solicitud.ubicaciones || solicitud.ubicaciones.length === 0) {
                        return 'Sin ubicar';
                    }

                    return solicitud.ubicaciones.map(ubic => {
                        if (ubic.nombre_ubicacion) {
                            return `${ubic.nombre_ubicacion} (${ubic.cantidad})`;
                        } else {
                            const nombre = this.getNombreUbicacion(ubic.ubicacion_id);
                            return `${nombre} (${ubic.cantidad})`;
                        }
                    }).join(', ');
                },

                // Nueva función para mostrar las series
                getSeriesTexto(series) {
                    if (!series || series.length === 0) {
                        return 'Sin series';
                    }

                    return series.map(serie => serie.numero_serie).join(', ');
                },

                // Función para mostrar el nombre correcto del tipo de artículo
                getTipoArticulo(idTipoArticulo) {
                    const tipos = {
                        1: 'Producto',
                        2: 'Repuesto',
                        3: 'Herramienta',
                        4: 'Suministro'
                    };
                    return tipos[idTipoArticulo] || 'Desconocido';
                },

                getEstadoClasses(estado) {
                    const classes = {
                        pendiente: 'bg-yellow-100 text-yellow-800',
                        recibido: 'bg-green-100 text-green-800',
                        ubicado: 'bg-purple-100 text-purple-800',
                        actualizar: 'bg-orange-100 text-orange-800'
                    };
                    return classes[estado] || 'bg-gray-100 text-gray-800';
                },

                getEstadoIcon(estado) {
                    const icons = {
                        pendiente: 'fas fa-clock',
                        recibido: 'fas fa-check',
                        ubicado: 'fas fa-map-marker-alt',
                        actualizar: 'fas fa-sync-alt'
                    };
                    return icons[estado] || 'fas fa-question';
                },

                formatFecha(fecha) {
                    if (!fecha) return 'N/A';
                    return new Date(fecha).toLocaleDateString('es-ES');
                },

                // Nuevos métodos para series
                validarSerieDuplicada(numeroSerie, indiceActual) {
                    if (!numeroSerie || numeroSerie.trim() === '') return false;

                    return this.seriesForm.some((serie, index) =>
                        index !== indiceActual &&
                        serie.numero_serie === numeroSerie
                    );
                },

                tieneSerieDuplicada() {
                    const series = this.seriesForm.map(s => s.numero_serie).filter(s => s && s.trim() !== '');
                    return series.length !== [...new Set(series)].length;
                },

                inicializarSeries() {
                    console.log('🔍 Verificando si requiere series:', this.articuloRequiereSeries);
                    console.log('📋 Artículo maneja_serie:', this.solicitudSeleccionada?.articulo?.maneja_serie);

                    if (!this.articuloRequiereSeries) {
                        this.seriesForm = [];
                        return;
                    }

                    const cantidad = this.solicitudSeleccionada?.cantidad || 0;

                    // Si ya hay series existentes, cargarlas
                    if (this.solicitudSeleccionada?.series && this.solicitudSeleccionada.series.length > 0) {
                        console.log('✅ Cargando series existentes:', this.solicitudSeleccionada.series);
                        this.seriesForm = this.solicitudSeleccionada.series.map(serie => ({
                            numero_serie: serie.numero_serie,
                            ubicacion_id: serie.ubicacion_id
                        }));

                        // Si faltan series, completar con campos vacíos
                        while (this.seriesForm.length < cantidad) {
                            this.seriesForm.push({
                                numero_serie: '',
                                ubicacion_id: ''
                            });
                        }
                    } else {
                        // Crear campos vacíos para todas las series necesarias
                        console.log('🆕 Inicializando series vacías para cantidad:', cantidad);
                        this.seriesForm = Array.from({
                            length: cantidad
                        }, () => ({
                            numero_serie: '',
                            ubicacion_id: ''
                        }));
                    }

                    console.log('📝 Series form inicializado:', this.seriesForm);
                },

                abrirModalUbicacion(solicitud) {
                    console.log('=== DEBUG abrirModalUbicacion ===');
                    console.log('Solicitud seleccionada:', solicitud);
                    console.log('Ubicaciones existentes:', solicitud.ubicaciones);
                    console.log('Series existentes:', solicitud.series);
                    console.log('Campo ubicacion directo:', solicitud.ubicacion);

                    this.solicitudSeleccionada = solicitud;

                    // Cargar ubicaciones existentes
                    if (solicitud.ubicacion && solicitud.ubicacion !== 'null' && solicitud.ubicacion !== '' && solicitud
                        .ubicacion !== 'undefined') {
                        console.log('✅ Intentando parsear ubicacion directo:', solicitud.ubicacion);

                        try {
                            const ubicacionesParseadas = this.parsearUbicacionDesdeTexto(solicitud.ubicacion);
                            console.log('Ubicaciones parseadas:', ubicacionesParseadas);

                            if (ubicacionesParseadas.length > 0) {
                                this.ubicacionesForm = ubicacionesParseadas;
                                console.log('✅ Cargado desde campo directo');
                            } else {
                                throw new Error('No se pudieron parsear las ubicaciones');
                            }
                        } catch (error) {
                            console.log('❌ Error parseando, usando ubicaciones relacionadas o formulario vacío');
                            this.cargarUbicacionesPorRelacion(solicitud);
                        }
                    } else {
                        this.cargarUbicacionesPorRelacion(solicitud);
                    }

                    // Inicializar series después de cargar ubicaciones
                    this.inicializarSeries();

                    console.log('Cantidad disponible inicial:', this.cantidadDisponible);
                    console.log('Ubicaciones form final:', this.ubicacionesForm);
                    console.log('Series form inicial:', this.seriesForm);
                    console.log('Requiere series:', this.articuloRequiereSeries);

                    this.modalUbicacionAbierto = true;
                },

                parsearUbicacionDesdeTexto(textoUbicacion) {
                    console.log('Parseando texto:', textoUbicacion);

                    const ubicaciones = [];
                    const partes = textoUbicacion.split(',');

                    partes.forEach(parte => {
                        parte = parte.trim();
                        const match = parte.match(/(.+)\s+\((\d+)\)/);

                        if (match && match.length === 3) {
                            const nombreUbicacion = match[1].trim();
                            const cantidad = parseInt(match[2]);

                            const ubicacionEncontrada = this.ubicaciones.find(ubic =>
                                ubic.nombre === nombreUbicacion
                            );

                            if (ubicacionEncontrada) {
                                ubicaciones.push({
                                    ubicacion_id: ubicacionEncontrada.idUbicacion,
                                    cantidad: cantidad,
                                    nombre_ubicacion: nombreUbicacion
                                });
                            } else {
                                ubicaciones.push({
                                    ubicacion_id: '',
                                    cantidad: cantidad,
                                    nombre_ubicacion: nombreUbicacion
                                });
                            }
                        }
                    });

                    return ubicaciones;
                },

                cargarUbicacionesPorRelacion(solicitud) {
                    if (solicitud.ubicaciones && solicitud.ubicaciones.length > 0) {
                        console.log('✅ Cargando ubicaciones existentes desde relaciones:', solicitud.ubicaciones);

                        this.ubicacionesForm = solicitud.ubicaciones.map(ubic => ({
                            ubicacion_id: ubic.ubicacion_id,
                            cantidad: parseInt(ubic.cantidad),
                            nombre_ubicacion: ubic.nombre_ubicacion
                        }));
                    } else {
                        console.log('🆕 No hay ubicaciones existentes, iniciando con formulario vacío');

                        this.ubicacionesForm = [{
                            ubicacion_id: '',
                            cantidad: solicitud.cantidad
                        }];

                        if (solicitud.cantidad > 1) {
                            this.ubicacionesForm[0].cantidad = 1;
                        }
                    }
                },

                cerrarModalUbicacion() {
                    this.modalUbicacionAbierto = false;
                    this.solicitudSeleccionada = null;
                    this.ubicacionesForm = [];
                    this.seriesForm = []; // Limpiar series también
                },

                agregarUbicacion() {
                    if (this.cantidadDisponible > 0) {
                        const nuevaUbicacion = {
                            ubicacion_id: '',
                            cantidad: Math.min(this.cantidadDisponible, 1)
                        };

                        this.ubicacionesForm.push(nuevaUbicacion);
                        console.log('➕ Nueva ubicación agregada. Disponible:', this.cantidadDisponible);
                    }
                },

                eliminarUbicacion(index) {
                    if (this.ubicacionesForm.length > 1) {
                        this.ubicacionesForm.splice(index, 1);
                        console.log('✅ Ubicación eliminada. Nueva cantidad disponible:', this.cantidadDisponible);
                    }
                },

                async guardarUbicaciones() {
                    console.log('=== DEBUG guardarUbicaciones ===');
                    console.log('Solicitud seleccionada:', this.solicitudSeleccionada);
                    console.log('Ubicaciones a guardar:', this.ubicacionesForm);
                    console.log('Series a guardar:', this.seriesForm);
                    console.log('Requiere series:', this.articuloRequiereSeries);
                    console.log('Puede guardar?:', this.puedeGuardar);

                    if (!this.puedeGuardar) {
                        let mensaje = 'Por favor completa todas las ubicaciones correctamente';

                        if (this.articuloRequiereSeries && this.seriesCompletadas < (this.solicitudSeleccionada
                                ?.cantidad || 0)) {
                            mensaje =
                                `Debe completar todos los números de serie. Faltan ${(this.solicitudSeleccionada?.cantidad || 0) - this.seriesCompletadas} serie(s)`;
                        }

                        if (this.articuloRequiereSeries && this.tieneSerieDuplicada()) {
                            mensaje = 'No puede haber números de serie duplicados';
                        }

                        console.log('❌ Validaciones no pasadas:', mensaje);
                        this.mostrarNotificacion(mensaje, 'error');
                        return;
                    }

                    try {
                        const requestData = {
                            solicitud_id: this.solicitudSeleccionada.idSolicitudIngreso,
                            ubicaciones: this.ubicacionesForm
                        };

                        // Solo agregar series si el artículo las requiere
                        if (this.articuloRequiereSeries) {
                            requestData.series = this.seriesForm.filter(serie =>
                                serie.numero_serie && serie.numero_serie.trim() !== ''
                            );
                        }

                        console.log('📤 Datos a enviar:', requestData);

                        const response = await axios.post('/solicitud-ingreso/guardar-ubicacion', requestData);

                        console.log('Respuesta del servidor:', response.data);

                        if (response.data.success) {
                            this.mostrarNotificacion(response.data.message, 'success');

                            // Actualizar la solicitud en la vista
                            this.solicitudesAgrupadas.forEach(grupo => {
                                grupo.solicitudes.forEach(solicitud => {
                                    if (solicitud.idSolicitudIngreso === this.solicitudSeleccionada
                                        .idSolicitudIngreso) {
                                        // Actualizar estado
                                        solicitud.estado = 'ubicado';

                                        // Actualizar ubicaciones
                                        solicitud.ubicacion = response.data.ubicacion_texto || this
                                            .generarTextoUbicaciones(this.ubicacionesForm);
                                        solicitud.ubicaciones = this.ubicacionesForm.map(ubic => ({
                                            ubicacion_id: ubic.ubicacion_id,
                                            cantidad: parseInt(ubic.cantidad),
                                            nombre_ubicacion: this.getNombreUbicacion(ubic
                                                .ubicacion_id)
                                        }));

                                        // Actualizar series si las hay
                                        if (response.data.series) {
                                            solicitud.series = response.data.series;
                                        }

                                        console.log('✅ Solicitud actualizada:', solicitud);

                                        // Recalcular estado general del grupo
                                        grupo.estado_general = this.calcularEstadoGeneral(grupo
                                            .solicitudes);
                                    }
                                });
                            });

                            this.cerrarModalUbicacion();
                        }
                    } catch (error) {
                        console.error('❌ Error al guardar ubicaciones:', error);
                        const message = error.response?.data?.message || 'Error al guardar las ubicaciones';
                        this.mostrarNotificacion(message, 'error');
                    }
                },

                generarTextoUbicaciones(ubicacionesForm) {
                    return ubicacionesForm.map(ubic => {
                        const nombre = this.getNombreUbicacion(ubic.ubicacion_id);
                        return `${nombre} (${ubic.cantidad})`;
                    }).join(', ');
                },

                getNombreUbicacion(ubicacionId) {
                    console.log('🔍 Buscando nombre para ubicacion_id:', ubicacionId);

                    const ubicacion = this.ubicaciones.find(u => u.idUbicacion == ubicacionId);

                    if (ubicacion) {
                        console.log('✅ Ubicación encontrada por ID:', ubicacion.nombre);
                        return ubicacion.nombre;
                    }

                    console.log('❌ Ubicación no encontrada por ID:', ubicacionId);
                    return 'Ubicación desconocida';
                },

              async cambiarEstado(id, nuevoEstado) {
    // Validar que no se pueda cambiar a "actualizar" si está ubicado
    if (nuevoEstado === 'actualizar') {
        let solicitudEncontrada = null;
        this.solicitudesAgrupadas.forEach(grupo => {
            const solicitud = grupo.solicitudes.find(s => s.idSolicitudIngreso === id);
            if (solicitud) solicitudEncontrada = solicitud;
        });

        if (solicitudEncontrada && solicitudEncontrada.estado === 'ubicado') {
            this.mostrarNotificacion('No se puede actualizar una solicitud ya ubicada', 'warning');
            return;
        }
    }

    if (nuevoEstado === 'ubicado') {
        // Buscar la solicitud y abrir modal de ubicación
        let solicitudEncontrada = null;
        this.solicitudesAgrupadas.forEach(grupo => {
            const solicitud = grupo.solicitudes.find(s => s.idSolicitudIngreso === id);
            if (solicitud) solicitudEncontrada = solicitud;
        });

        if (solicitudEncontrada) {
            this.abrirModalUbicacion(solicitudEncontrada);
        }
        return;
    }

    // Resto del código existente...
    try {
        if (confirm('¿Estás seguro de cambiar el estado de este artículo?')) {
            const response = await axios.post(`/solicitud-ingreso/${id}/cambiar-estado`, {
                estado: nuevoEstado
            });

            if (response.data.success) {
                this.solicitudesAgrupadas.forEach(grupo => {
                    const solicitud = grupo.solicitudes.find(s => s.idSolicitudIngreso === id);
                    if (solicitud) {
                        solicitud.estado = nuevoEstado;
                        grupo.estado_general = this.calcularEstadoGeneral(grupo.solicitudes);
                    }
                });

                this.mostrarNotificacion('Estado actualizado correctamente', 'success');
            }
        }
    } catch (error) {
        console.error('Error al cambiar estado:', error);
        this.mostrarNotificacion('Error al cambiar el estado', 'error');
    }
},

                async cambiarEstadoGrupo(grupo, nuevoEstado) {
                    try {
                        if (confirm(
                                `¿Estás seguro de cambiar el estado de todos los artículos (${grupo.solicitudes.length}) a ${nuevoEstado}?`
                            )) {
                            // Cambiar estado de todas las solicitudes del grupo
                            const promises = grupo.solicitudes.map(solicitud =>
                                axios.post(`/solicitud-ingreso/${solicitud.idSolicitudIngreso}/cambiar-estado`, {
                                    estado: nuevoEstado
                                })
                            );

                            const results = await Promise.all(promises);

                            if (results.every(result => result.data.success)) {
                                // Actualizar estados localmente
                                grupo.solicitudes.forEach(solicitud => {
                                    solicitud.estado = nuevoEstado;
                                });
                                grupo.estado_general = nuevoEstado;

                                this.mostrarNotificacion(
                                    `Estado de ${grupo.solicitudes.length} artículos actualizado a ${nuevoEstado}`,
                                    'success');
                            }
                        }
                    } catch (error) {
                        console.error('Error al cambiar estado del grupo:', error);
                        this.mostrarNotificacion('Error al cambiar el estado del grupo', 'error');
                    }
                },

                calcularEstadoGeneral(solicitudes) {
                    const estados = [...new Set(solicitudes.map(s => s.estado))];

                    if (estados.length === 1) {
                        return estados[0];
                    }

                    if (estados.includes('pendiente')) {
                        return 'pendiente';
                    }

                    if (estados.includes('recibido')) {
                        return 'recibido';
                    }

                    return 'ubicado';
                },

                mostrarNotificacion(mensaje, tipo = 'info') {
                    switch (tipo) {
                        case 'success':
                            toastr.success(mensaje, 'Éxito');
                            break;
                        case 'error':
                            toastr.error(mensaje, 'Error');
                            break;
                        case 'warning':
                            toastr.warning(mensaje, 'Advertencia');
                            break;
                        default:
                            toastr.info(mensaje, 'Información');
                            break;
                    }
                }

            }
        }
    </script>
</x-layout.default>
