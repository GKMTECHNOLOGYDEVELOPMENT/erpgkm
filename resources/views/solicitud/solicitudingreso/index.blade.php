<x-layout.default title="Solicitud Ingreso - ERP Solutions Force">
    <!-- Incluir Axios CDN -->
    <!-- Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        /* ESTILOS PARA SELECT2 CON COLORES */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            height: 42px !important;
            background: white !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px !important;
            padding-left: 12px !important;
            padding-right: 30px !important;
            color: #374151 !important;
            font-size: 14px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 8px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e5e7eb !important;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        /* NUEVO: Estilos para opciones verdes (sugerencias) en el dropdown */
        .select2-results__option .text-green-600 {
            color: #059669 !important;
            font-weight: 600 !important;
        }

        /* NUEVO: Estilo para la opción seleccionada cuando es sugerencia */
        .select2-selection__rendered .text-green-600 {
            color: #059669 !important;
            font-weight: 600 !important;
        }

        /* Asegurar que Select2 se vea bien en el modal */
        .select2-container {
            z-index: 9999 !important;
        }
    </style>
    <div class="mb-6">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="" class="text-primary hover:underline">Almacen</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Lista de Solicitudes de Ingreso</span>
            </li>
        </ul>
    </div>
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

        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
            <!-- Pendientes -->
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-white rounded-xl shadow-sm border border-blue-100">
                        <i class="fas fa-clock text-blue-500 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Pendientes</p>
                        <p class="text-xl font-bold text-blue-800 mt-1" x-text="contadores.pendiente"></p>
                    </div>
                </div>
            </div>

            <!-- Recibidos -->
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-white rounded-xl shadow-sm border border-green-100">
                        <i class="fas fa-check text-green-500 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Recibidos</p>
                        <p class="text-xl font-bold text-green-800 mt-1" x-text="contadores.recibido"></p>
                    </div>
                </div>
            </div>

            <!-- Ubicados -->
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-white rounded-xl shadow-sm border border-purple-100">
                        <i class="fas fa-map-marker-alt text-purple-500 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-purple-600 uppercase tracking-wide">Ubicados</p>
                        <p class="text-xl font-bold text-purple-800 mt-1" x-text="contadores.ubicado"></p>
                    </div>
                </div>
            </div>

            <!-- Total Grupos -->
            <div
                class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                        <i class="fas fa-boxes text-gray-500 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Total Grupos</p>
                        <p class="text-xl font-bold text-gray-800 mt-1" x-text="contadores.total"></p>
                    </div>
                </div>
            </div>

            <!-- Por Actualizar -->
            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-white rounded-xl shadow-sm border border-orange-100">
                        <i class="fas fa-sync-alt text-orange-500 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-orange-600 uppercase tracking-wide">Por Actualizar</p>
                        <p class="text-xl font-bold text-orange-800 mt-1" x-text="contadores.actualizar"></p>
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
                                                    :disabled="botonIndividualDeshabilitado(solicitud, 'pendiente')"
                                                    :class="getBotonIndividualClasses(solicitud, 'pendiente')">
                                                    Pendiente
                                                </button>
                                                <button
                                                    @click="cambiarEstado(solicitud.idSolicitudIngreso, 'recibido')"
                                                    :disabled="botonIndividualDeshabilitado(solicitud, 'recibido')"
                                                    :class="getBotonIndividualClasses(solicitud, 'recibido')">
                                                    Recibido
                                                </button>
                                                <button @click="cambiarEstado(solicitud.idSolicitudIngreso, 'ubicado')"
                                                    :disabled="botonIndividualDeshabilitado(solicitud, 'ubicado')"
                                                    :class="getBotonIndividualClasses(solicitud, 'ubicado')">
                                                    Ubicar
                                                </button>
                                                <button @click="abrirModalActualizar(solicitud)"
                                                    :disabled="botonIndividualDeshabilitado(solicitud, 'actualizar')"
                                                    :class="getBotonIndividualClasses(solicitud, 'actualizar')">
                                                    Actualizar
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
                                            :disabled="botonGrupalDeshabilitado(grupo, 'pendiente')"
                                            :class="getBotonGrupalClasses(grupo, 'pendiente')">
                                            Todos Pendiente
                                        </button>
                                        <button @click="cambiarEstadoGrupo(grupo, 'recibido')"
                                            :disabled="botonGrupalDeshabilitado(grupo, 'recibido')"
                                            :class="getBotonGrupalClasses(grupo, 'recibido')">
                                            Todos Recibido
                                        </button>
                                        <button @click="cambiarEstadoGrupo(grupo, 'ubicado')"
                                            :disabled="botonGrupalDeshabilitado(grupo, 'ubicado')"
                                            :class="getBotonGrupalClasses(grupo, 'ubicado')">
                                            Todos Ubicado
                                        </button>
                                        <button @click="cambiarEstadoGrupo(grupo, 'actualizar')"
                                            :disabled="botonGrupalDeshabilitado(grupo, 'actualizar')"
                                            :class="getBotonGrupalClasses(grupo, 'actualizar')">
                                            Todos Actualizar
                                        </button>
                                        <!-- 🔴 NUEVO: Botón Unity a nivel grupo -->
                                        <button
                                            @click="ubicarPrimeraSolicitudEnUnity(grupo)"
                                            :disabled="esBotonUnityGrupoDeshabilitado(grupo)"
                                            class="px-3 py-1 text-xs rounded-lg transition-colors
                       bg-purple-100 text-purple-800 hover:bg-purple-200
                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                                            <i class="fas fa-tv mr-1"></i>
                                            Unity Grupo
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
                            <button type="button" class="text-gray-400 hover:text-gray-600"
                                @click="cerrarModalUbicacion()">
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
                                <div class="flex justify-between items-center">
                                    <h4 class="text-md font-medium text-gray-800">
                                        <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                        Ubicaciones
                                    </h4>

                                    <!-- Botón para buscar sugerencias -->
                                    <button type="button" @click="buscarSugerencias()"
                                        class="flex items-center gap-2 text-green-600 hover:text-green-800 text-sm bg-green-50 px-3 py-1 rounded-lg border border-green-200">
                                        <i class="fas fa-magic"></i>
                                        Buscar Ubicaciones Sugeridas
                                    </button>
                                </div>

                                <template x-for="(ubicacion, index) in ubicacionesForm" :key="index">
                                    <div class="flex gap-3 items-start border border-gray-200 rounded-lg p-3">
                                        <!-- Select de ubicaciones con Select2 -->
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación
                                                *</label>
                                            <select x-model="ubicacion.ubicacion_id" :id="'ubicacion-select-' + index"
                                                class="ubicacion-select w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Seleccionar ubicación</option>
                                                <template x-for="ubic in ubicaciones" :key="ubic.idUbicacion">
                                                    <option :value="ubic.idUbicacion" x-text="ubic.nombre"
                                                        :class="ubic.nombre.includes('Espacio:') ?
                                                            'text-green-600 font-medium' : ''">
                                                    </option>
                                                </template>
                                            </select>

                                            <!-- Mostrar sugerencias si no hay ubicación seleccionada -->
                                            <template x-if="!ubicacion.ubicacion_id && ubicaciones.length > 0">
                                                <div class="mt-2 p-2 bg-blue-50 rounded-lg">
                                                    <p class="text-xs text-blue-700">
                                                        <i class="fas fa-lightbulb mr-1"></i>
                                                        <strong>Sugerencia:</strong> Hay <span
                                                            x-text="ubicaciones.length"></span> ubicaciones disponibles
                                                        para este artículo.
                                                        Las opciones en <span
                                                            class="text-green-600 font-medium">verde</span> son
                                                        sugerencias automáticas.
                                                    </p>
                                                </div>
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
                                            ¡Toda la cantidad ha sido distribuida!</span>
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
                        <button type="button" class="text-gray-400 hover:text-gray-600"
                            @click="cerrarModalActualizar()">
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
                                    <p class="text-lg font-semibold"
                                        x-text="getNombreArticulo(solicitudActualizar?.articulo)"></p>
                                    <p class="text-sm text-gray-600"
                                        x-text="'Tipo: ' + getTipoArticulo(solicitudActualizar?.articulo?.idTipoArticulo)">
                                    </p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Cantidad Actual:</p>
                                    <p class="text-lg font-semibold" x-text="solicitudActualizar?.cantidad || 0"></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Origen:</p>
                                    <p class="text-sm"
                                        x-text="solicitudActualizar?.origen === 'compra' ? 'Compra' : 'Entrada Proveedor'">
                                    </p>
                                    <p class="text-sm text-gray-600"
                                        x-text="solicitudActualizar?.origen === 'compra' ?
                                       solicitudActualizar?.origen_especifico?.codigocompra :
                                       solicitudActualizar?.origen_especifico?.codigo_entrada">
                                    </p>
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
                                        Cantidad actual: <span class="font-semibold"
                                            x-text="solicitudActualizar?.cantidad || 0"></span>
                                    </p>
                                </div>

                                <!-- Observaciones -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        Observaciones
                                    </label>
                                    <textarea x-model="formActualizar.observaciones" rows="3" placeholder="Ingrese observaciones adicionales..."
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end items-center gap-3 px-5 py-3 border-t bg-[#fbfbfb] dark:bg-[#121c2c]">
                        <button type="button" class="btn btn-outline-danger" @click="cerrarModalActualizar()">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-warning" :disabled="!puedeActualizar"
                            @click="guardarActualizacion">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

                // Construye la URL hacia Unity: /unity/{idSolicitudIngreso}/solicitud
                buildUnityUrlForSolicitud(idSolicitudIngreso) {
                    const base = (typeof window !== 'undefined' && window.UNITY_BASE_URL) ?
                        window.UNITY_BASE_URL :
                        '/unity';
                    const baseClean = (base || '/unity').replace(/\/$/, ''); // sin barra final
                    return `${baseClean}/${encodeURIComponent(idSolicitudIngreso)}/solicitud`;
                },

                // 🔹 Unity por solicitud (botón dentro de cada card)
                ubicarSolicitudEnUnity(solicitud) {
                    if (!solicitud) return;

                    const estadoNormalizado = String(solicitud.estado || '').trim().toLowerCase();

                    console.log(
                        '▶️ [Unity] Click solicitud | id:',
                        solicitud.idSolicitudIngreso,
                        '| estado raw:', solicitud.estado,
                        '| normalizado:', estadoNormalizado
                    );

                    // Solo permitir cuando está en estado "recibido"
                    if (estadoNormalizado !== 'recibido') {
                        this.mostrarNotificacion('Solo puedes ubicar artículos en estado "recibido"', 'warning');
                        return;
                    }

                    const url = this.buildUnityUrlForSolicitud(solicitud.idSolicitudIngreso);
                    const w = window.open(url, '_blank', 'noopener');

                    if (!w) {
                        this.mostrarNotificacion('Permite ventanas emergentes para abrir Unity.', 'warning');
                    }
                },

                // 🔹 Deshabilita botón Unity por solicitud
                esBotonUnityDeshabilitado(solicitud) {
                    if (!solicitud) return true;

                    const estadoNormalizado = String(solicitud.estado || '').trim().toLowerCase();

                    // ❗ Reglas:
                    // - deshabilitado si YA está ubicado
                    // - deshabilitado si NO está en 'recibido'
                    const disabled =
                        (estadoNormalizado === 'ubicado') || // ya ubicado
                        (estadoNormalizado !== 'recibido'); // cualquier otro distinto a 'recibido'

                    console.log(
                        '🔎 [Unity btn solicitud] id:',
                        solicitud.idSolicitudIngreso,
                        '| estado raw:', solicitud.estado,
                        '| normalizado:', estadoNormalizado,
                        '| disabled:', disabled
                    );

                    return disabled;
                },

                // 🔹 Unity por grupo: abre la primera solicitud "recibido" que encuentre
                ubicarPrimeraSolicitudEnUnity(grupo) {
                    if (!grupo || !Array.isArray(grupo.solicitudes)) return;

                    // Buscar la primera solicitud en estado 'recibido'
                    const primeraRecibida = grupo.solicitudes.find(s => {
                        const estado = String(s.estado || '').trim().toLowerCase();
                        return estado === 'recibido';
                    });

                    console.log(
                        '▶️ [Unity Grupo] origen_id:', grupo.origen_id,
                        '| estado_general:', grupo.estado_general,
                        '| primeraRecibida:', primeraRecibida ? primeraRecibida.idSolicitudIngreso : null
                    );

                    if (!primeraRecibida) {
                        this.mostrarNotificacion(
                            'No hay artículos en estado "recibido" para abrir en Unity en este grupo.',
                            'info'
                        );
                        return;
                    }

                    const url = this.buildUnityUrlForSolicitud(primeraRecibida.idSolicitudIngreso);
                    const w = window.open(url, '_blank', 'noopener');

                    if (!w) {
                        this.mostrarNotificacion('Permite ventanas emergentes para abrir Unity.', 'warning');
                    }
                },

                // 🔹 Deshabilita el botón Unity Grupo cuando TODO está ubicado o no hay recibidos
                esBotonUnityGrupoDeshabilitado(grupo) {
                    if (!grupo || !Array.isArray(grupo.solicitudes)) return true;

                    // 1) Si el estado_general es 'ubicado' => todo ubicado según tu calcularEstadoGeneral
                    if (String(grupo.estado_general || '').trim().toLowerCase() === 'ubicado') {
                        console.log('🔒 [Unity Grupo] deshabilitado: estado_general = ubicado');
                        return true;
                    }

                    // 2) Si no hay NINGUNA solicitud en 'recibido', también deshabilitar
                    const tieneRecibido = grupo.solicitudes.some(s => {
                        const estado = String(s.estado || '').trim().toLowerCase();
                        return estado === 'recibido';
                    });

                    const disabled = !tieneRecibido;

                    console.log(
                        '🔎 [Unity Grupo btn] origen_id:', grupo.origen_id,
                        '| estado_general:', grupo.estado_general,
                        '| tieneRecibido:', tieneRecibido,
                        '| disabled:', disabled
                    );

                    return disabled;
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
                        const response = await axios.post(
                            `/solicitud-ingreso/${this.solicitudActualizar.idSolicitudIngreso}/actualizar`, this
                            .formActualizar);

                        if (response.data.success) {
                            this.mostrarNotificacion(response.data.message, 'success');

                            // Actualizar la solicitud en la vista
                            this.solicitudesAgrupadas.forEach(grupo => {
                                grupo.solicitudes.forEach(solicitud => {
                                    if (solicitud.idSolicitudIngreso === this.solicitudActualizar
                                        .idSolicitudIngreso) {
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

                // NUEVO: Método para inicializar Select2
                // NUEVO: Método para inicializar Select2
                inicializarSelect2(index) {
                    this.$nextTick(() => {
                        setTimeout(() => {
                            const selectId = 'ubicacion-select-' + index;
                            const selectElement = document.getElementById(selectId);

                            if (selectElement && !$(selectElement).hasClass('select2-hidden-accessible')) {
                                console.log('🔄 Inicializando Select2 para:', selectId);

                                // Usar el modal como parent para el dropdown
                                const modalElement = document.querySelector(
                                    '[x-show="modalUbicacionAbierto"]');

                                $(selectElement).select2({
                                    placeholder: "Seleccionar ubicación",
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: modalElement ? $(modalElement) : $(document
                                        .body),
                                    // NUEVO: Template personalizado para opciones
                                    templateResult: (state) => {
                                        if (!state.id) {
                                            return state.text;
                                        }

                                        // Verificar si es una sugerencia (contiene "Espacio:")
                                        const isSugerencia = state.text.includes('Espacio:');

                                        if (isSugerencia) {
                                            const $state = $(
                                                '<span class="text-green-600 font-medium">' +
                                                state.text + '</span>'
                                            );
                                            return $state;
                                        }

                                        return state.text;
                                    },
                                    // NUEVO: Template para opción seleccionada
                                    templateSelection: (state) => {
                                        if (!state.id) {
                                            return state.text;
                                        }

                                        // Verificar si es una sugerencia
                                        const isSugerencia = state.text.includes('Espacio:');

                                        if (isSugerencia) {
                                            const $state = $(
                                                '<span class="text-green-600 font-medium">' +
                                                state.text + '</span>'
                                            );
                                            return $state;
                                        }

                                        return state.text;
                                    }
                                });

                                // Sincronizar con Alpine.js cuando cambie la selección
                                $(selectElement).on('change', (e) => {
                                    this.ubicacionesForm[index].ubicacion_id = e.target.value;
                                    console.log('📍 Ubicación seleccionada:', e.target.value);
                                });

                                console.log('✅ Select2 inicializado correctamente');
                            }
                        }, 300);
                    });
                },

                // En tu JavaScript, modifica el método para procesar las sugerencias
                // MODIFICADO: Método para abrir modal de ubicación
                async abrirModalUbicacion(solicitud) {
                    console.log('=== DEBUG abrirModalUbicacion ===');
                    console.log('Solicitud seleccionada:', solicitud);

                    this.solicitudSeleccionada = solicitud;

                    // Obtener sugerencias de ubicaciones
                    try {
                        const response = await axios.get(
                            `/solicitud-ingreso/sugerir-ubicaciones/${solicitud.articulo_id}/${solicitud.cantidad}`);

                        if (response.data.success) {
                            console.log('Sugerencias obtenidas:', response.data.sugerencias);

                            if (response.data.sugerencias.length > 0) {
                                this.ubicaciones = response.data.sugerencias.map(sugerencia => ({
                                    idUbicacion: sugerencia.id,
                                    nombre: `${sugerencia.codigo} - ${sugerencia.rack_nombre} (${sugerencia.sede})`
                                }));
                            }
                        }
                    } catch (error) {
                        console.error('Error al obtener sugerencias:', error);
                    }

                    // Cargar ubicaciones existentes y series
                    this.cargarUbicacionesPorRelacion(solicitud);
                    this.inicializarSeries();

                    console.log('Cantidad disponible inicial:', this.cantidadDisponible);
                    console.log('Ubicaciones form final:', this.ubicacionesForm);
                    console.log('Series form inicial:', this.seriesForm);
                    console.log('Requiere series:', this.articuloRequiereSeries);

                    this.modalUbicacionAbierto = true;

                    // Inicializar Select2 después de que el modal esté abierto
                    this.$nextTick(() => {
                        console.log('🔄 Inicializando Select2 para', this.ubicacionesForm.length,
                            'ubicaciones');
                        this.ubicacionesForm.forEach((_, index) => {
                            this.inicializarSelect2(index);
                        });
                    });
                },

                // MODIFICADO: Método para agregar ubicación
                agregarUbicacion() {
                    if (this.cantidadDisponible > 0) {
                        const nuevaUbicacion = {
                            ubicacion_id: '',
                            cantidad: Math.min(this.cantidadDisponible, 1)
                        };

                        this.ubicacionesForm.push(nuevaUbicacion);
                        console.log('➕ Nueva ubicación agregada. Disponible:', this.cantidadDisponible);

                        // Inicializar Select2 para la nueva ubicación
                        this.$nextTick(() => {
                            const newIndex = this.ubicacionesForm.length - 1;
                            this.inicializarSelect2(newIndex);
                        });
                    }
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

                // MODIFICADO: Método para cerrar modal
                cerrarModalUbicacion() {
                    // Destruir todas las instancias de Select2
                    $('.ubicacion-select').each(function() {
                        if ($(this).hasClass('select2-hidden-accessible')) {
                            $(this).select2('destroy');
                        }
                    });

                    this.modalUbicacionAbierto = false;
                    this.solicitudSeleccionada = null;
                    this.ubicacionesForm = [];
                    this.seriesForm = [];
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

                // NUEVO: Método para verificar si un botón individual debe estar deshabilitado
                botonIndividualDeshabilitado(solicitud, estado) {
                    if (solicitud.estado === 'ubicado') {
                        return true; // Si ya está ubicado, deshabilitar todos los botones
                    }
                    return false;
                },

                // NUEVO: Método para verificar si un botón grupal debe estar deshabilitado
                botonGrupalDeshabilitado(grupo, estado) {
                    // Si TODOS los artículos están ubicados, deshabilitar todos los botones grupales
                    if (grupo.estado_general === 'ubicado') {
                        return true;
                    }

                    // Para el botón "Todos Actualizar", verificar si ALGÚN artículo está ubicado
                    if (estado === 'actualizar') {
                        return grupo.solicitudes.some(s => s.estado === 'ubicado');
                    }

                    return false;
                },

                // NUEVO: Método para obtener las clases de los botones individuales
                getBotonIndividualClasses(solicitud, estado) {
                    const baseClasses = 'flex-1 px-2 py-1 text-xs rounded transition-colors';

                    if (this.botonIndividualDeshabilitado(solicitud, estado)) {
                        return `${baseClasses} bg-gray-100 text-gray-400 cursor-not-allowed`;
                    }

                    if (solicitud.estado === estado) {
                        const estadoClasses = {
                            pendiente: 'bg-yellow-100 text-yellow-800',
                            recibido: 'bg-green-100 text-green-800',
                            ubicado: 'bg-purple-100 text-purple-800',
                            actualizar: 'bg-orange-100 text-orange-800'
                        };
                        return `${baseClasses} ${estadoClasses[estado] || 'bg-gray-100 text-gray-800'}`;
                    }

                    const hoverClasses = {
                        pendiente: 'bg-gray-100 text-gray-600 hover:bg-yellow-50',
                        recibido: 'bg-gray-100 text-gray-600 hover:bg-green-50',
                        ubicado: 'bg-gray-100 text-gray-600 hover:bg-purple-50',
                        actualizar: 'bg-gray-100 text-gray-600 hover:bg-orange-50'
                    };

                    return `${baseClasses} ${hoverClasses[estado] || 'bg-gray-100 text-gray-600'}`;
                },

                // NUEVO: Método para obtener las clases de los botones grupales
                getBotonGrupalClasses(grupo, estado) {
                    const baseClasses = 'px-3 py-1 text-xs rounded-lg transition-colors';

                    if (this.botonGrupalDeshabilitado(grupo, estado)) {
                        return `${baseClasses} bg-gray-100 text-gray-400 cursor-not-allowed`;
                    }

                    if (grupo.estado_general === estado) {
                        const estadoClasses = {
                            pendiente: 'bg-yellow-100 text-yellow-800',
                            recibido: 'bg-green-100 text-green-800',
                            ubicado: 'bg-purple-100 text-purple-800',
                            actualizar: 'bg-orange-100 text-orange-800'
                        };
                        return `${baseClasses} ${estadoClasses[estado] || 'bg-gray-100 text-gray-800'}`;
                    }

                    const hoverClasses = {
                        pendiente: 'bg-gray-100 text-gray-600 hover:bg-yellow-50',
                        recibido: 'bg-gray-100 text-gray-600 hover:bg-green-50',
                        ubicado: 'bg-gray-100 text-gray-600 hover:bg-purple-50',
                        actualizar: 'bg-gray-100 text-gray-600 hover:bg-orange-50'
                    };

                    return `${baseClasses} ${hoverClasses[estado] || 'bg-gray-100 text-gray-600'}`;
                },

                // MODIFICAR: El método cambiarEstado para incluir validación
                async cambiarEstado(id, nuevoEstado) {
                    // Buscar la solicitud
                    let solicitudEncontrada = null;
                    this.solicitudesAgrupadas.forEach(grupo => {
                        const solicitud = grupo.solicitudes.find(s => s.idSolicitudIngreso === id);
                        if (solicitud) solicitudEncontrada = solicitud;
                    });

                    if (!solicitudEncontrada) return;

                    // Validar que no se pueda cambiar el estado si ya está ubicado
                    if (solicitudEncontrada.estado === 'ubicado') {
                        this.mostrarNotificacion('No se puede cambiar el estado de un artículo ya ubicado', 'warning');
                        return;
                    }

                    // Para el estado "ubicado", abrir el modal
                    if (nuevoEstado === 'ubicado') {
                        this.abrirModalUbicacion(solicitudEncontrada);
                        return;
                    }

                    // Resto del código existente para cambiar estado...
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

                // MODIFICAR: El método cambiarEstadoGrupo para incluir validación
                async cambiarEstadoGrupo(grupo, nuevoEstado) {
                    // Validar que no se pueda cambiar el estado si TODOS están ubicados
                    if (grupo.estado_general === 'ubicado') {
                        this.mostrarNotificacion('No se puede cambiar el estado de artículos ya ubicados', 'warning');
                        return;
                    }

                    // Para "Todos Actualizar", verificar que NINGUNO esté ubicado
                    if (nuevoEstado === 'actualizar') {
                        const hayUbicados = grupo.solicitudes.some(s => s.estado === 'ubicado');
                        if (hayUbicados) {
                            this.mostrarNotificacion(
                                'No se puede actualizar un grupo que contiene artículos ya ubicados', 'warning');
                            return;
                        }
                    }

                    try {
                        if (confirm(
                                `¿Estás seguro de cambiar el estado de todos los artículos (${grupo.solicitudes.length}) a ${nuevoEstado}?`
                            )) {
                            // Filtrar solo las solicitudes que no están ubicadas
                            const solicitudesParaActualizar = grupo.solicitudes.filter(s => s.estado !== 'ubicado');

                            const promises = solicitudesParaActualizar.map(solicitud =>
                                axios.post(`/solicitud-ingreso/${solicitud.idSolicitudIngreso}/cambiar-estado`, {
                                    estado: nuevoEstado
                                })
                            );

                            const results = await Promise.all(promises);

                            if (results.every(result => result.data.success)) {
                                // Actualizar estados localmente solo de las no ubicadas
                                grupo.solicitudes.forEach(solicitud => {
                                    if (solicitud.estado !== 'ubicado') {
                                        solicitud.estado = nuevoEstado;
                                    }
                                });
                                grupo.estado_general = this.calcularEstadoGeneral(grupo.solicitudes);

                                this.mostrarNotificacion(
                                    `Estado de ${solicitudesParaActualizar.length} artículos actualizado a ${nuevoEstado}`,
                                    'success');
                            }
                        }
                    } catch (error) {
                        console.error('Error al cambiar estado del grupo:', error);
                        this.mostrarNotificacion('Error al cambiar el estado del grupo', 'error');
                    }
                },
                // Método para buscar sugerencias manualmente
                async buscarSugerencias() {
                    if (!this.solicitudSeleccionada) return;

                    try {
                        this.mostrarNotificacion('Buscando ubicaciones sugeridas...', 'info');

                        const response = await axios.get(
                            `/solicitud-ingreso/sugerir-ubicaciones/${this.solicitudSeleccionada.articulo_id}/${this.solicitudSeleccionada.cantidad}`
                        );

                        if (response.data.success && response.data.sugerencias.length > 0) {
                            this.ubicaciones = response.data.sugerencias.map(sugerencia => ({
                                idUbicacion: sugerencia.id,
                                nombre: `${sugerencia.codigo} - ${sugerencia.rack_nombre} (${sugerencia.sede})`
                            }));

                            this.mostrarNotificacion(
                                `Se encontraron ${response.data.sugerencias.length} ubicaciones sugeridas`,
                                'success');
                        } else {
                            this.mostrarNotificacion('No se encontraron ubicaciones sugeridas', 'warning');
                        }
                    } catch (error) {
                        console.error('Error al buscar sugerencias:', error);
                        this.mostrarNotificacion('Error al buscar sugerencias', 'error');
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
