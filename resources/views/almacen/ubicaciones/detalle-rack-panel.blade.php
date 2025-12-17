<script>
    // LO MÁS SIMPLE - Sin complicaciones
    window.rackData = <?= json_encode($rack, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    window.todosRacks =
        <?= json_encode($todosRacks ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    window.rackActual =
        <?= json_encode($rackActual ?? '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    window.sedeActual =
        <?= json_encode($sedeActual ?? '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    console.log('rackData:', window.rackData);
</script>
<x-layout.default title="Rack Panel - {{ $rack['nombre'] }} - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/detalle-rack-panel.css') }}">
    <div x-data="rackDetalle" x-init="init()" class="min-h-screen flex flex-col">
        <!-- Header Mejorado - ESTÁTICO -->
        <div class="relative overflow-hidden bg-black text-white px-6 py-8">
            <div
                class="absolute inset-0 -translate-x-full animate-[shine_3s_linear_infinite] bg-gradient-to-r from-transparent via-white/10 to-transparent">
            </div>

            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-white/5 rounded-full"></div>
            </div>

            <div class="flex flex-col lg:flex-row justify-between items-center relative z-10 gap-6">
                <!-- Logo y título -->
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-warehouse text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Rack Panel <span x-text="rack.nombre" class="text-warning">{{ $rack['nombre'] }}</span>
                        </h1>
                        <p class="text-white/80 text-base mt-1">
                            Sede: {{ $rack['sede'] }} - Sistema de Gestión de Almacén
                        </p>
                    </div>
                </div>

                <!-- Stats flotantes -->
                <div
                    class="flex gap-8 px-8 py-5 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 shadow-lg">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-400" x-text="getStats().ocupadas">0</div>
                        <div class="text-sm text-white/80 mt-1">Ocupadas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-400" x-text="getStats().vacias">0</div>
                        <div class="text-sm text-white/80 mt-1">Vacías</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-400" x-text="getStats().total">0</div>
                        <div class="text-sm text-white/80 mt-1">Total</div>
                    </div>
                </div>

                <!-- Navegación invisible (mantiene espacio) -->
                <div class="flex gap-3">
                    <button @click="cambiarRack('prev')"
                        class="px-6 py-3 rounded-xl flex items-center gap-3 border border-transparent opacity-0 pointer-events-none select-none">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </button>
                    <button @click="cambiarRack('next')"
                        class="px-6 py-3 rounded-xl flex items-center gap-3 border border-transparent opacity-0 pointer-events-none select-none">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- Indicador de Reubicación -->
        <div x-show="modoReubicacion.activo"
            class="fixed top-5 right-5 bg-blue-600 text-white px-5 py-3 rounded-lg shadow-xl z-50 flex items-center gap-3 font-semibold animate-pulse">
            <i class="fas fa-arrows-alt"></i>
            <span>Reubicando: <span x-text="modoReubicacion.producto"></span></span>
            <button @click="cancelarReubicacion()"
                class="ml-2 bg-white/20 hover:bg-white/30 text-white rounded px-2 py-1 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- DISEÑO DINÁMICO CON DATOS DEL CONTROLADOR -->
        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Iterar sobre niveles (ordenados: nivel 2 arriba, nivel 1 abajo) -->
                <template x-for="nivel in rack.niveles" :key="nivel.numero">
                    <div
                        class="panel rounded-2xl p-6 shadow-xl backdrop-blur-md border border-white/20 transition-all duration-300 ease-in-out hover:shadow-2xl">

                        <!-- Header del nivel -->
                        <div class="panel-header">
                            <div class="px-3 py-6 rounded-lg font-bold text-sm text-white [writing-mode:vertical-rl] [text-orientation:mixed] [transform:translateZ(5px)]"
                                style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                                NIVEL <span x-text="nivel.numero"></span>
                            </div>
                            <div class="separator"></div>
                            <div class="estado-counter">
                                <span x-text="getUbicacionesOcupadas(nivel.numero)">0</span>/<span
                                    x-text="nivel.ubicaciones.length">0</span> ocupadas
                                <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></div>
                            </div>
                        </div>

                        <!-- CARRUSEL SWIPER DINÁMICO -->
                        <div class="swiper swiper-rack" :id="'swiperRackNivel' + nivel.numero">
                            <div class="swiper-wrapper">
                                <!-- Agrupar ubicaciones en slides de 4 (2 grupos de 2) -->
                                <template
                                    x-for="(slide, slideIndex) in agruparUbicacionesEnSlides(nivel.ubicaciones, 4)"
                                    :key="slideIndex">
                                    <div class="swiper-slide">
                                        <div class="ubicaciones-fila">
                                            <!-- Iterar sobre grupos de 2 ubicaciones -->
                                            <template
                                                x-for="(grupo, grupoIndex) in agruparUbicacionesEnGrupos(slide, 2)"
                                                :key="grupoIndex">
                                                <div class="rack-grupo-container">
                                                    <div class="rack-grupo-ubicaciones">
                                                        <template x-for="ubicacion in grupo" :key="ubicacion.id">
                                                            <div @click="manejarClickUbicacion(ubicacion)"
                                                                class="ubicacion-box"
                                                                :class="ubicacion.estado === 'vacio' ? 'vacia' : 'ocupada'"
                                                                :style="getColorEstado(ubicacion.estado)">

                                                                <div class="ubicacion-codigo"
                                                                    x-text="ubicacion.codigo || 'N/A'">
                                                                </div>
                                                                <div class="ubicacion-icono">
                                                                    <i class="fas fa-tv"></i>
                                                                </div>
                                                                <div class="ubicacion-info">
                                                                    <template x-if="ubicacion.estado !== 'vacio'">
                                                                        <div>
                                                                            <!-- ✅ SOLO mostrar cantidad (sin capacidad) -->
                                                                            <div class="ubicacion-cantidad">
                                                                                <span
                                                                                    x-text="ubicacion.cantidad_total || ubicacion.cantidad || 0"></span>
                                                                                <span
                                                                                    x-show="ubicacion?.capacidad_total_cajas > 0">
                                                                                    /<span
                                                                                        x-text="ubicacion?.capacidad_total_cajas"></span>
                                                                                </span>
                                                                            </div>
                                                                            <div class="ubicacion-categoria"
                                                                                x-text="ubicacion.categoria || 'Sin categoría'">
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="ubicacion.estado === 'vacio'">
                                                                        <div>
                                                                            <div class="ubicacion-cantidad">Vacía</div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- ✅ MANTENER LA BASE DEL RACK CON TABLA DE MADERA (solo estilo visual) -->
                                                    <div class="rack-grupo-base">
                                                        <!-- ❌ QUITAR EL TEXTO DE CANTIDAD TABLA -->
                                                        <!-- <div class="cantidad-tabla-madera-container"
                                    x-show="grupo.some(ubicacion => ubicacion.tiene_ubicacion2 && ubicacion.estado !== 'vacio')">
                                    <div class="cantidad-tabla-madera"
                                        x-text="getCantidadTablaGrupo(grupo)">
                                    </div>
                                </div> -->
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Botones de navegación del Swiper -->
                            <div
                                class="swiper-button-prev-rack swiper-button-prev-ex5 grid place-content-center ltr:left-2 rtl:right-2 p-1 transition text-primary hover:text-white border border-primary hover:border-primary hover:bg-primary rounded-full absolute z-[999] top-[44%] -translate-y-1/2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div
                                class="swiper-button-next-rack swiper-button-next-ex5 grid place-content-center ltr:right-2 rtl:left-2 p-1 transition text-primary hover:text-white border border-primary hover:border-primary hover:bg-primary rounded-full absolute z-[999] top-[44%] -translate-y-1/2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>

                            <!-- Paginación del Swiper -->
                            <div class="swiper-pagination swiper-pagination-rack"></div>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        <!-- Modal para Detalles de Ubicación - SIN CAPACIDAD MÁXIMA -->
        <div x-data="modalDetalleUbicacion()" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="open && '!block'" x-show="open">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="closeModal">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl">

                    <!-- Header del Modal -->
                    <div class="flex bg-primary items-center justify-between px-6 py-4">
                        <div class="font-bold text-lg text-white">
                            Detalles de Ubicación
                        </div>
                        <button type="button" class="text-white hover:text-gray-200 transition-colors"
                            @click="closeModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido del Modal -->
                    <div class="p-6">
                        <!-- Información Principal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Columna Izquierda - Información General y Estadísticas -->
                            <div class="space-y-4">
                                <!-- Ubicación -->
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Ubicación:</span>
                                    <span class="font-bold text-lg text-primary"
                                        x-text="ubicacion?.codigo || 'N/A'"></span>
                                </div>

                                <!-- Estado -->
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Estado:</span>
                                    <span class="font-bold text-lg" :class="getEstadoColor(ubicacion?.estado)"
                                        x-text="getEstadoTexto(ubicacion?.estado)"></span>
                                </div>

                                <!-- Nivel -->
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Nivel:</span>
                                    <span class="font-bold text-lg text-blue-600"
                                        x-text="ubicacion?.nivel || 'N/A'"></span>
                                </div>

                                <!-- Cantidad Total -->
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Cantidad Total:</span>
                                    <div class="text-right">
                                        <span class="font-bold text-lg"
                                            :class="ubicacion?.capacidad_total_cajas > 0 ? 'text-amber-600' : 'text-green-600'">
                                            <span x-text="ubicacion?.cantidad_total || 0"></span>
                                            <span x-show="ubicacion?.capacidad_total_cajas > 0">
                                                /<span x-text="ubicacion?.capacidad_total_cajas"></span>
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Categoría -->
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Categoría:</span>
                                    <span class="font-bold text-lg text-orange-600"
                                        x-text="ubicacion?.categoria || 'Sin categoría'"></span>
                                </div>
                            </div>

                            <!-- Columna Derecha - Solo QR Code -->
                            <div class="space-y-4">
                                <div
                                    class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border h-full flex flex-col">
                                    <div class="text-center mb-3">
                                        <h3 class="font-semibold text-gray-700 mb-2">Código QR de Ubicación</h3>
                                        <p class="text-sm text-gray-500 mb-3">Escanea este código para acceder
                                            rápidamente</p>
                                    </div>

                                    <!-- QR Code Container -->
                                    <div class="flex flex-col items-center justify-center flex-1">
                                        <!-- QR Image -->
                                        <div class="mb-4 p-2 bg-white rounded-lg shadow-sm border">
                                            <img :src="'/almacen/ubicaciones/qr/' + encodeURIComponent(ubicacion?.codigo_unico ||
                                                ubicacion?.codigo)"
                                                :alt="'QR Code - ' + (ubicacion?.codigo || 'N/A')"
                                                class="w-48 h-48 object-contain" id="qr-image" x-ref="qrImage">
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex space-x-3 mt-auto">
                                            <!-- Download Button -->
                                            <button type="button" @click="downloadQR()"
                                                class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                                Descargar QR
                                            </button>

                                            <!-- Copy Link Button -->
                                            <button type="button" @click="copyQRUrl()"
                                                class="flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Copiar URL
                                            </button>
                                        </div>

                                        <!-- Info Text -->
                                        <div class="mt-3 text-center">
                                            <p class="text-xs text-gray-500">
                                                Código: <span x-text="ubicacion?.codigo_unico || ubicacion?.codigo"
                                                    class="font-medium"></span>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Tamaño: 350x350px | Tipo: PNG
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Productos -->
                        <template x-if="ubicacion?.productos?.length > 0">
                            <div class="mb-6">
                                <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">
                                    <i class="fas fa-boxes mr-2"></i>Contenido de la Ubicación
                                </h3>

                                <!-- SECCIÓN DE CAJAS -->
                                <template x-if="ubicacion.tiene_cajas">
                                    <div class="mb-6">
                                        <h4 class="font-semibold text-amber-700 mb-3 flex items-center">
                                            <i class="fas fa-archive mr-2"></i>Cajas en esta ubicación
                                            <span
                                                class="ml-2 text-sm bg-amber-100 text-amber-800 px-2 py-1 rounded-full"
                                                x-text="ubicacion.cantidad_cajas + ' caja' + (ubicacion.cantidad_cajas > 1 ? 's' : '')"></span>
                                        </h4>

                                        <!-- Contenedor con scroll condicional -->
                                        <div
                                            :class="ubicacion.cantidad_cajas > 2 ? 'max-h-80 overflow-y-auto pr-2 space-y-3' :
                                                'space-y-3'">
                                            <template x-for="caja in ubicacion.cajas" :key="caja.idCaja">
                                                <div
                                                    class="p-4 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                                                    <!-- Encabezado de la caja -->
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-archive text-2xl text-amber-500 mr-3"></i>
                                                            <div>
                                                                <div class="font-bold text-gray-800"
                                                                    x-text="caja.nombre_caja || caja.nombre"></div>
                                                                <div class="text-sm text-gray-600">
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mr-2"
                                                                        :class="caja.estado_caja === 'cerrada' ?
                                                                            'bg-green-100 text-green-800' :
                                                                            'bg-yellow-100 text-yellow-800'">
                                                                        <i class="fas fa-lock mr-1"
                                                                            x-show="caja.estado_caja === 'cerrada'"></i>
                                                                        <i class="fas fa-lock-open mr-1"
                                                                            x-show="caja.estado_caja !== 'cerrada'"></i>
                                                                        <span x-text="caja.estado_caja"></span>
                                                                    </span>
                                                                    <template x-if="caja.es_custodia">
                                                                        <span
                                                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                            <i class="fas fa-shield-alt mr-1"></i>
                                                                            <span>Custodia</span>
                                                                        </span>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="font-bold text-lg text-amber-600">
                                                                <!-- Muestra cantidad/capacidad -->
                                                                <span x-text="caja.cantidad || '0'"></span>/<span
                                                                    x-text="caja.capacidad_caja || '0'"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- ARTÍCULO DENTRO DE LA CAJA -->
                                                    <template x-if="caja.articulo_en_caja">
                                                        <div class="mt-3 pt-3 border-t border-amber-200">
                                                            <h6
                                                                class="font-semibold text-green-700 mb-2 flex items-center text-sm">
                                                                <i class="fas fa-box mr-1 text-xs"></i>Artículo en esta
                                                                caja
                                                            </h6>

                                                            <div class="p-2 bg-white rounded border border-gray-200">
                                                                <div class="flex items-center justify-between">
                                                                    <!-- Columna 1: Información del artículo -->
                                                                    <div class="flex-1 pr-3">
                                                                        <!-- Código de repuesto -->
                                                                        <div class="mb-1">
                                                                            <div class="flex items-center">
                                                                                <i
                                                                                    class="fas fa-hashtag text-gray-500 mr-1 text-xs"></i>
                                                                                <span
                                                                                    class="text-base font-semibold text-gray-800"
                                                                                    x-text="caja.articulo_en_caja.codigo_repuesto || 
                                                                   caja.articulo_en_caja.codigo_barras || 
                                                                   caja.articulo_en_caja.nombre || 'Sin código'">
                                                                                </span>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Tipo y Categoría -->
                                                                        <div
                                                                            class="flex items-center space-x-3 text-xs text-gray-600">
                                                                            <div class="flex items-center">
                                                                                <i
                                                                                    class="fas fa-cube text-purple-500 mr-1 text-xs"></i>
                                                                                <span
                                                                                    x-text="caja.articulo_en_caja.tipo_articulo || 'Sin tipo'"></span>
                                                                            </div>
                                                                            <div class="flex items-center">
                                                                                <i
                                                                                    class="fas fa-folder text-blue-500 mr-1 text-xs"></i>
                                                                                <span
                                                                                    x-text="caja.articulo_en_caja.categoria || 'Sin categoría'"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Columna 2: Cantidad + Botón -->
                                                                    <div class="flex items-center space-x-2">
                                                                        <!-- Cantidad -->
                                                                        <div class="text-right min-w-16">
                                                                            <div class="font-bold text-xl text-blue-600"
                                                                                x-text="caja.articulo_en_caja.cantidad || '0'">
                                                                            </div>
                                                                            <div class="text-xs text-gray-500">und.
                                                                            </div>
                                                                            <div class="text-xs text-gray-400 mt-0.5"
                                                                                x-show="caja.articulo_en_caja.stock_total"
                                                                                x-text="'Stock: ' + caja.articulo_en_caja.stock_total">
                                                                            </div>
                                                                        </div>

                                                                        <!-- Botón Mover -->
                                                                        <button
                                                                            @click="iniciarMovimientoDesdeModalConArticulo(caja.articulo_en_caja, ubicacion, caja)"
                                                                            class="px-3 py-1.5 bg-primary hover:bg-primary-dark text-white text-xs rounded flex items-center justify-center transition-colors shadow-sm hover:shadow-md whitespace-nowrap h-fit">
                                                                            <i
                                                                                class="fas fa-exchange-alt mr-1 text-xs"></i>
                                                                            Mover
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <!-- Si no tiene artículo -->
                                                    <template x-if="!caja.articulo_en_caja">
                                                        <div class="mt-4 pt-4 border-t border-amber-200">
                                                            <h5
                                                                class="font-semibold text-gray-500 mb-2 flex items-center">
                                                                <i class="fas fa-box mr-2"></i>Esta caja está vacía
                                                            </h5>
                                                            <p class="text-sm text-gray-500 italic">No contiene ningún
                                                                artículo</p>
                                                        </div>
                                                    </template>

                                                    <!-- Botón para mover la caja
                                                    <button @click="iniciarMovimientoDesdeModalConArticulo(caja)"
                                                        class="mt-3 px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded-lg flex items-center transition-colors">
                                                        <i class="fas fa-exchange-alt mr-2"></i> Mover esta caja
                                                    </button>-->
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="!ubicacion?.productos?.length">
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <i class="fas fa-box-open text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 font-semibold">Ubicación Vacía</p>
                                <p class="text-gray-400 text-sm mt-1">No hay productos en esta ubicación</p>
                            </div>
                        </template>

                        <!-- Resumen de Productos -->
                        <template x-if="ubicacion?.productos?.length > 0">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div
                                    class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-boxes text-blue-500 mr-2"></i>
                                        <span class="font-semibold text-blue-700">Total Unidades</span>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-600"
                                        x-text="ubicacion?.cantidad_total || 0"></div>
                                </div>

                                <div
                                    class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-tags text-green-500 mr-2"></i>
                                        <span class="font-semibold text-green-700">Tipos</span>
                                    </div>
                                    <div class="text-2xl font-bold text-green-600"
                                        x-text="ubicacion?.tipos_acumulados || 'Sin tipo'"></div>
                                </div>

                                <div
                                    class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-layer-group text-purple-500 mr-2"></i>
                                        <span class="font-semibold text-purple-700">Categorías</span>
                                    </div>
                                    <div class="text-2xl font-bold text-purple-600"
                                        x-text="ubicacion?.categorias_acumuladas || 'Sin categoría'"></div>
                                </div>
                            </div>
                        </template>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end items-center space-x-3 pt-4 border-t">
                            <button type="button" class="btn btn-outline-danger px-6 py-2" @click="closeModal">
                                <i class="fas fa-times mr-2"></i>Cerrar
                            </button>
                            <button type="button"
                                class="btn btn-primary px-6 py-2 bg-gradient-to-r from-[#6366f1] to-[#8b5cf6] border-0"
                                @click="abrirHistorial(ubicacion)">
                                <i class="fas fa-history mr-2"></i>Ver Historial
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-data x-show="$store.rackDetalle.modoMovimiento.activo"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-[black]/60 z-[1000] flex items-start justify-center min-h-screen px-4 py-8"
            style="display: none;" @keydown.escape.window="$store.rackDetalle.cancelarMovimiento()"
            @click.self="$store.rackDetalle.cancelarMovimiento()">

            <div x-show="$store.rackDetalle.modoMovimiento.activo" x-transition x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-4xl my-8">
                <!-- Cambiado a max-w-4xl -->

                <!-- Header del Modal -->
                <div class="flex bg-primary items-center justify-between px-6 py-4">
                    <div class="font-bold text-lg text-white flex items-center">
                        <i class="fas fa-exchange-alt mr-2"></i>Mover Artículo
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 transition-colors"
                        @click="$store.rackDetalle.cancelarMovimiento()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    <!-- Información del artículo -->
                    <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                        <h4 class="font-bold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-box mr-2"></i>Artículo a Mover
                        </h4>
                        <!-- Fila 1: Código Repuesto - Ocupa toda la fila -->
                        <div class="mb-4">
                            <div
                                class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-barcode text-2xl text-blue-500 mr-3"></i>
                                        <div>
                                            <div
                                                class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                                Código Repuesto
                                            </div>
                                            <div class="font-mono text-2xl md:text-3xl font-bold text-blue-600 break-all"
                                                x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.codigo_repuesto || 
                               $store.rackDetalle.modoMovimiento.articuloSeleccionado?.codigo_barras || 
                               $store.rackDetalle.modoMovimiento.articuloSeleccionado?.sku || 'N/A'">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <button @click="$store.rackDetalle.copiarCodigo()"
                                            class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg font-medium transition-colors flex items-center"
                                            title="Copiar código al portapapeles">
                                            <i class="fas fa-copy mr-2"></i>
                                            Copiar
                                        </button>

                                        <!-- Mostrar nombre del artículo si existe -->
                                        <div x-show="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.nombre"
                                            class="text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg border">
                                            <span class="font-medium">Nombre:</span>
                                            <span class="ml-2"
                                                x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.nombre"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 2: Disponible, Origen y Categoría en 3 columnas - COMPACTO -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Disponible -->
                            <div class="p-3 bg-white rounded-lg border border-amber-100 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-box-open text-amber-500 text-sm mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Disponible</span>
                                    </div>
                                    <span class="text-xs text-amber-600 font-medium">
                                        <i class="fas fa-mouse-pointer mr-1"></i>Para mover
                                    </span>
                                </div>
                                <div class="flex items-baseline justify-between">
                                    <div class="text-2xl font-bold text-amber-600"
                                        x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.cantidad || 0">
                                    </div>
                                    <div class="text-right">
                                        <div class="text-base font-bold text-gray-800"
                                            x-text="$store.rackDetalle.modoMovimiento.cantidad"></div>
                                        <div class="text-xs text-gray-500">seleccionadas</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Origen -->
                            <div class="p-3 bg-white rounded-lg border border-green-100 shadow-sm">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-green-500 text-sm mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Origen</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-arrow-right text-green-600 mr-2"></i>
                                    <div class="text-lg font-bold text-green-700 truncate"
                                        x-text="$store.rackDetalle.modoMovimiento.ubicacionOrigen?.codigo || 'N/A'">
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 truncate"
                                    x-text="$store.rackDetalle.modoMovimiento.ubicacionOrigen?.rack_nombre || ''">
                                </div>
                            </div>

                            <!-- Categoría -->
                            <div class="p-3 bg-white rounded-lg border border-orange-100 shadow-sm">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-tag text-orange-500 text-sm mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Categoría</span>
                                </div>
                                <div class="text-lg font-bold text-orange-600 mb-1 truncate"
                                    x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.categoria || 'Sin categoría'">
                                </div>
                                <div class="text-xs text-gray-600 flex items-center">
                                    <i class="fas fa-cube mr-1"></i>
                                    <span class="truncate"
                                        x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.tipo_articulo || 'Sin tipo'"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Mostrar nombre del artículo si existe (opcional) -->
                        <template x-if="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.nombre">
                            <div class="mt-3 p-2 bg-gray-50 rounded text-sm">
                                <span class="text-gray-600">Nombre:</span>
                                <span class="font-medium text-gray-800 ml-2"
                                    x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.nombre"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Cantidad y observaciones - COMPACTO Y RESPONSIVE -->
                    <div class="space-y-4 mb-6">
                        <!-- Cantidad a mover - Diseño compacto -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-sort-amount-up text-primary mr-2"></i>
                                        <span>Cantidad a mover</span>
                                    </h4>

                                    <div class="flex items-center">
                                        <button
                                            @click="$store.rackDetalle.modoMovimiento.cantidad > 1 && $store.rackDetalle.modoMovimiento.cantidad--"
                                            :class="$store.rackDetalle.modoMovimiento.cantidad <= 1 ?
                                                'opacity-50 cursor-not-allowed bg-gray-100' :
                                                'hover:bg-gray-100 active:bg-gray-200'"
                                            class="w-10 h-10 flex items-center justify-center rounded-l-lg border border-gray-300 transition-colors"
                                            title="Disminuir">
                                            <i class="fas fa-minus text-gray-600"></i>
                                        </button>

                                        <input type="number" x-model="$store.rackDetalle.modoMovimiento.cantidad"
                                            min="1"
                                            :max="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.cantidad || 1"
                                            class="w-16 h-10 text-center border-y border-gray-300 font-bold text-gray-800 focus:ring-2 focus:ring-primary focus:border-transparent">

                                        <button
                                            @click="$store.rackDetalle.modoMovimiento.cantidad < ($store.rackDetalle.modoMovimiento.articuloSeleccionado?.cantidad || 1) && $store.rackDetalle.modoMovimiento.cantidad++"
                                            :class="$store.rackDetalle.modoMovimiento.cantidad >= ($store.rackDetalle
                                                    .modoMovimiento.articuloSeleccionado?.cantidad || 1) ?
                                                'opacity-50 cursor-not-allowed bg-gray-100' :
                                                'hover:bg-gray-100 active:bg-gray-200'"
                                            class="w-10 h-10 flex items-center justify-center rounded-r-lg border border-gray-300 transition-colors"
                                            title="Aumentar">
                                            <i class="fas fa-plus text-gray-600"></i>
                                        </button>

                                        <div class="ml-3 flex items-center space-x-2">
                                            <div class="px-3 py-1.5 bg-blue-50 rounded-lg border border-blue-100">
                                                <span class="text-sm text-gray-600">
                                                    Máx: <span class="font-bold text-blue-600"
                                                        x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.cantidad || 0"></span>
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 hidden sm:block">
                                                unidades disponibles
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Info en móviles -->
                                    <div class="text-xs text-gray-500 mt-2 sm:hidden">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Máximo: <span class="font-bold text-blue-600"
                                            x-text="$store.rackDetalle.modoMovimiento.articuloSeleccionado?.cantidad || 0"></span>
                                        unidades
                                    </div>
                                </div>

                                <!-- Indicador visual de porcentaje -->
                                <div class="sm:w-32">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-primary"
                                            x-text="Math.round(($store.rackDetalle.modoMovimiento.cantidad / ($store.rackDetalle.modoMovimiento.articuloSeleccionado?.cantidad || 1)) * 100) + '%'">
                                        </div>
                                        <div class="text-xs text-gray-500">del total</div>
                                    </div>
                                    <!-- Barra de progreso -->
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                        <div class="bg-primary h-2 rounded-full transition-all duration-300"
                                            :style="'width: ' + Math.min(($store.rackDetalle.modoMovimiento.cantidad / ($store
                                                .rackDetalle.modoMovimiento.articuloSeleccionado
                                                ?.cantidad || 1)) * 100, 100) + '%'">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicaciones disponibles -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-700 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>Ubicaciones Disponibles
                                <span class="ml-2 text-sm bg-primary text-white px-2 py-0.5 rounded-full"
                                    x-text="($store.rackDetalle.modoMovimiento.ubicacionesFiltradas || $store.rackDetalle.modoMovimiento.ubicacionesDisponibles).length || 0"></span>
                            </h4>
                            <div class="flex items-center space-x-3">
                                <!-- BUSCADOR -->
                                <div class="relative">
                                    <input type="text"
                                        x-model="$store.rackDetalle.modoMovimiento.busquedaUbicacion"
                                        placeholder="Buscar por código..."
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm w-64"
                                        @input="$store.rackDetalle.filtrarUbicaciones()">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <!-- Botón para limpiar búsqueda -->
                                    <button x-show="$store.rackDetalle.modoMovimiento.busquedaUbicacion"
                                        @click="$store.rackDetalle.modoMovimiento.busquedaUbicacion = ''; $store.rackDetalle.filtrarUbicaciones()"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <button @click="cargarUbicacionesDisponibles()"
                                    :disabled="$store.rackDetalle.modoMovimiento.cargandoUbicaciones"
                                    :class="$store.rackDetalle.modoMovimiento.cargandoUbicaciones ?
                                        'opacity-50 cursor-not-allowed' : 'hover:text-primary-dark'"
                                    class="text-primary">
                                    <i class="fas fa-sync-alt"
                                        :class="$store.rackDetalle.modoMovimiento.cargandoUbicaciones && 'animate-spin'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Loading -->
                        <div x-show="$store.rackDetalle.modoMovimiento.cargandoUbicaciones"
                            class="text-center py-8 bg-gray-50 rounded-lg border">
                            <i class="fas fa-spinner fa-spin text-3xl text-primary mb-3"></i>
                            <p class="text-gray-600">Cargando ubicaciones disponibles...</p>
                        </div>

                        <!-- Sin ubicaciones -->
                        <div x-show="!$store.rackDetalle.modoMovimiento.cargandoUbicaciones && 
                    (($store.rackDetalle.modoMovimiento.ubicacionesFiltradas || $store.rackDetalle.modoMovimiento.ubicacionesDisponibles).length === 0)"
                            class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium">No hay ubicaciones disponibles</p>
                            <p class="text-gray-500 text-sm mt-1"
                                x-show="$store.rackDetalle.modoMovimiento.busquedaUbicacion">
                                No se encontraron resultados para "<span
                                    x-text="$store.rackDetalle.modoMovimiento.busquedaUbicacion"></span>"
                            </p>
                            <p class="text-gray-500 text-sm mt-1"
                                x-show="!$store.rackDetalle.modoMovimiento.busquedaUbicacion">
                                Intenta recargar la lista
                            </p>
                        </div>

                        <!-- CONTENEDOR CON SCROLL -->
                        <div x-show="!$store.rackDetalle.modoMovimiento.cargandoUbicaciones 
                    && (($store.rackDetalle.modoMovimiento.ubicacionesFiltradas || $store.rackDetalle.modoMovimiento.ubicacionesDisponibles).length > 0)"
                            class="border rounded-lg bg-gray-50">

                            <!-- CONTENEDOR SCROLLEABLE -->
                            <div class="max-h-80 overflow-y-auto p-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <!-- Cambiado a 3 columnas -->
                                    <template
                                        x-for="ubicacion in ($store.rackDetalle.modoMovimiento.ubicacionesFiltradas || $store.rackDetalle.modoMovimiento.ubicacionesDisponibles)"
                                        :key="ubicacion.idRackUbicacion">

                                        <div @click="seleccionarUbicacionDestino(ubicacion)"
                                            class="p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:shadow-md bg-white"
                                            :class="$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada
                                                ?.idRackUbicacion ===
                                                ubicacion.idRackUbicacion ?
                                                'border-primary border-2 bg-blue-50 ring-2 ring-primary/20' :
                                                'border-gray-200 hover:border-primary'">

                                            <!-- Header -->
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-800 text-lg mb-1">
                                                        <span x-text="ubicacion.codigo_unico"></span>
                                                    </div>
                                                    <div class="text-xs font-semibold px-2 py-1 rounded-full inline-block"
                                                        :class="ubicacion.clase_css ||
                                                            (ubicacion.cantidad_ocupada == 0 ?
                                                                'bg-green-100 text-green-800' :
                                                                ubicacion.tiene_mismo_articulo ?
                                                                'bg-blue-100 text-blue-800' :
                                                                'bg-yellow-100 text-yellow-800')"
                                                        x-text="ubicacion.estado_visual ||
                                                    (ubicacion.cantidad_ocupada == 0
                                                        ? 'Vacía'
                                                        : ubicacion.tiene_mismo_articulo
                                                            ? 'Mismo artículo'
                                                            : 'Ocupada')">
                                                    </div>
                                                </div>

                                                <!-- Cantidades -->
                                                <div class="text-right">
                                                    <div class="text-lg font-bold"
                                                        :class="ubicacion.cantidad_ocupada == 0 ?
                                                            'text-green-600' :
                                                            'text-amber-600'">
                                                        <span x-text="ubicacion.cantidad_ocupada"></span>
                                                        <span class="text-xs">art.</span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-box-open mr-1"></i>
                                                        <span x-text="ubicacion.espacio_disponible"></span> disp.
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detalles -->
                                            <div class="space-y-2 mt-3 pt-3 border-t border-gray-100">
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <i class="fas fa-layer-group text-gray-500 mr-2 text-xs"></i>
                                                        Nivel
                                                    </span>
                                                    <span class="font-medium" x-text="ubicacion.nivel || '0'"></span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <i class="fas fa-warehouse text-purple-500 mr-2 text-xs"></i>
                                                        Capacidad
                                                    </span>
                                                    <span class="font-medium text-purple-600"
                                                        x-text="ubicacion.capacidad_maxima"></span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <i
                                                            class="fas fa-tachometer-alt text-green-500 mr-2 text-xs"></i>
                                                        Ocupación
                                                    </span>
                                                    <span class="font-medium">
                                                        <span x-text="ubicacion.cantidad_ocupada"></span>/
                                                        <span x-text="ubicacion.capacidad_maxima"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación destino seleccionada -->
                    <div x-show="$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada"
                        class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-bold text-green-800 mb-4 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>Ubicación Destino Seleccionada
                        </h4>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4"> <!-- Cambiado a 4 columnas -->
                            <div class="flex items-center justify-between p-3 bg-white rounded border">
                                <span class="font-semibold text-green-700">Código:</span>
                                <span class="font-bold text-lg text-green-600"
                                    x-text="$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada?.codigo_unico || 
                                   $store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada?.codigo"></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded border">
                                <span class="font-semibold text-green-700">Rack:</span>
                                <span class="font-bold text-green-600"
                                    x-text="$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada?.rack_nombre"></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded border">
                                <span class="font-semibold text-green-700">Nivel:</span>
                                <span class="font-bold text-green-600"
                                    x-text="$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada?.nivel"></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded border">
                                <span class="font-semibold text-green-700">Estado:</span>
                                <span class="font-bold text-green-600"
                                    x-text="$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada?.estado_ocupacion === 'vacio' ? 'Vacía' : 'Ocupada'">
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex justify-between items-center space-x-3 pt-4 border-t">
                        <div class="text-sm text-gray-500">
                            <span
                                x-text="($store.rackDetalle.modoMovimiento.ubicacionesFiltradas || $store.rackDetalle.modoMovimiento.ubicacionesDisponibles).length || 0"></span>
                            ubicaciones encontradas
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" class="btn btn-outline-danger px-6 py-2"
                                @click="$store.rackDetalle.cancelarMovimiento()">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </button>
                            <button @click="moverArticulo()"
                                :disabled="!$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada || $store.rackDetalle
                                    .modoMovimiento.moviendoArticulo"
                                :class="$store.rackDetalle.modoMovimiento.ubicacionDestinoSeleccionada && !$store.rackDetalle
                                    .modoMovimiento.moviendoArticulo ?
                                    'btn-primary px-6 py-2 bg-gradient-to-r from-[#6366f1] to-[#8b5cf6] border-0 hover:shadow-lg' :
                                    'btn-outline-secondary px-6 py-2 opacity-50 cursor-not-allowed'"
                                class="flex items-center transition-all duration-200">

                                <template x-if="$store.rackDetalle.modoMovimiento.moviendoArticulo">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                </template>
                                <template x-if="!$store.rackDetalle.modoMovimiento.moviendoArticulo">
                                    <i class="fas fa-exchange-alt mr-2"></i>
                                </template>

                                <span
                                    x-text="$store.rackDetalle.modoMovimiento.moviendoArticulo ? 'Moviendo...' : 'Mover Artículo'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Swiper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('assets/js/almacen/detalle-rack/panel.js') }}"></script>
</x-layout.default>
