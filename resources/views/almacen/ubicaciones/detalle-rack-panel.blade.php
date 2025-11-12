<x-layout.default title="Rack Panel - {{ $rack['nombre'] }} - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/detalle-rack-panel.css') }}">
    <div x-data="rackDetalle()" x-init="init()" class="min-h-screen flex flex-col">
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
                                                        <!-- Iterar sobre ubicaciones en el grupo -->
                                                        <template x-for="ubicacion in grupo" :key="ubicacion.id">
                                                            <div @click="manejarClickUbicacion(ubicacion)"
                                                                class="ubicacion-box"
                                                                :class="ubicacion.estado === 'vacio' ? 'vacia' : 'ocupada'"
                                                                :style="getColorEstado(ubicacion.estado)">

                                                                <div class="ubicacion-codigo" x-text="ubicacion.codigo">
                                                                </div>
                                                                <div class="ubicacion-icono">
                                                                    <i class="fas fa-tv"></i>
                                                                </div>
                                                                <div class="ubicacion-info">
                                                                    <template x-if="ubicacion.estado !== 'vacio'">
                                                                        <div>
                                                                            <div class="ubicacion-cantidad"
                                                                                x-text="`${ubicacion.cantidad_total}/${ubicacion.capacidad}`">
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
                                                    <!-- Base del rack (tabla de madera) debajo del grupo -->
                                                    <div class="rack-grupo-base"></div>
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

        <!-- Modal para Detalles de Ubicación - ACTUALIZADO PARA DATOS DINÁMICOS -->
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
                            <!-- Columna Izquierda - Información General -->
                            <div class="space-y-4">
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Ubicación:</span>
                                    <span class="font-bold text-lg text-primary"
                                        x-text="ubicacion?.codigo || 'N/A'"></span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Estado:</span>
                                    <span class="font-bold text-lg" :class="getEstadoColor(ubicacion?.estado)"
                                        x-text="getEstadoTexto(ubicacion?.estado)"></span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Capacidad:</span>
                                    <span class="font-bold text-lg text-blue-600"
                                        x-text="ubicacion?.capacidad || 'N/A'"></span>
                                </div>
                            </div>

                            <!-- Columna Derecha - Estadísticas -->
                            <div class="space-y-4">
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Productos Actuales:</span>
                                    <span class="font-bold text-lg text-green-600"
                                        x-text="`${ubicacion?.cantidad_total || 0}/${ubicacion?.capacidad || 0}`"></span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Espacio Disponible:</span>
                                    <span class="font-bold text-lg text-purple-600"
                                        x-text="(ubicacion?.capacidad - ubicacion?.cantidad_total) || 0"></span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border">
                                    <span class="font-semibold text-gray-700">Ocupación:</span>
                                    <span class="font-bold text-lg" :class="getPorcentajeColor(ubicacion)"
                                        x-text="calcularPorcentaje(ubicacion) + '%'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de Progreso -->
                        <div class="mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-gray-700">Nivel de Ocupación</span>
                                <span class="font-bold" :class="getPorcentajeColor(ubicacion)"
                                    x-text="calcularPorcentaje(ubicacion) + '%'"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full transition-all duration-500"
                                    :class="getProgressBarColor(ubicacion)"
                                    :style="`width: ${calcularPorcentaje(ubicacion)}%`"></div>
                            </div>
                        </div>

                        <!-- Información de Productos -->
                        <template x-if="ubicacion?.productos?.length > 0">
                            <div class="mb-6">
                                <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">
                                    <i class="fas fa-boxes mr-2"></i>Productos en la Ubicación
                                </h3>
                                <div class="space-y-3">
                                    <template x-for="producto in ubicacion.productos"
                                        :key="producto.idRackUbicacionArticulo">
                                        <div
                                            class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800" x-text="producto.nombre">
                                                </div>
                                                <div class="text-sm text-gray-600 mt-1 flex items-center gap-4">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-tag text-blue-500 mr-1"></i>
                                                        <span x-text="producto.categoria"></span>
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-box text-green-500 mr-1"></i>
                                                        <span x-text="producto.tipo_articulo"></span>
                                                    </span>
                                                    <template
                                                        x-if="producto.cliente_general_nombre && producto.cliente_general_nombre !== 'Sin cliente'">
                                                        <span class="flex items-center">
                                                            <i class="fas fa-user text-purple-500 mr-1"></i>
                                                            <span x-text="producto.cliente_general_nombre"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                                <template x-if="producto.custodia_id">
                                                    <div class="text-xs text-gray-500 mt-2">
                                                        <i class="fas fa-shield-alt mr-1"></i>
                                                        Custodia: <span
                                                            x-text="producto.codigocustodias || producto.serie"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-lg text-blue-600"
                                                    x-text="producto.cantidad"></div>
                                                <div class="text-xs text-gray-500">unidades</div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div
                                    class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-boxes text-blue-500 mr-2"></i>
                                        <span class="font-semibold text-blue-700">Total Productos</span>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-600"
                                        x-text="ubicacion?.cantidad_total || 0"></div>
                                </div>

                                <div
                                    class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-tags text-green-500 mr-2"></i>
                                        <span class="font-semibold text-green-700">Tipos de Productos</span>
                                    </div>
                                    <div class="text-2xl font-bold text-green-600"
                                        x-text="ubicacion?.tipos_acumulados || 'Sin tipo'"></div>
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
    </div>

    <!-- Script de Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Pasar datos del PHP a JavaScript
        const rackData = @json($rack);
        const todosRacks = @json($todosRacks);
        const rackActual = @json($rackActual);
        const sedeActual = @json($sedeActual);
    </script>
    <script src="{{ asset('assets/js/almacen/detalle-rack/panel.js') }}"></script>
</x-layout.default>
