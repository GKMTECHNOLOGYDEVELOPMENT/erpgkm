<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
                <div class="flex items-center gap=5">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-warehouse text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Rack <span x-text="rack.nombre" class="text-yellow-300">PANEL-001</span>
                        </h1>
                        <p class="text-white/80 text-base mt-1">Sede: Principal - Sistema de Gestión de Almacén</p>
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

                <!-- Navegación mejorada -->
                <div class="flex gap-3">
                    <button @click="cambiarRack('prev')"
                        class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300 flex items-center gap-3 group border border-white/20 backdrop-blur-sm hover:shadow-lg">
                        <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i>
                        Anterior
                    </button>
                    <button @click="cambiarRack('next')"
                        class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300 flex items-center gap-3 group border border-white/20 backdrop-blur-sm hover:shadow-lg">
                        Siguiente
                        <i class="fas fa-chevron-right group-hover:translate-x-1 transition-transform"></i>
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

        <!-- DISEÑO CON NIVELES INVERTIDOS (2 ARRIBA, 1 ABAJO) -->
        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Nivel 2 - ARRIBA - CON SWIPER PARA MÁS UBICACIONES -->
                <div
                    class="panel rounded-2xl p-6 shadow-xl backdrop-blur-md border border-white/20 transition-all duration-300 ease-in-out hover:shadow-2xl">
                    <!-- Header del nivel -->
                    <div class="panel-header">
                        <div class="nivel-indicator">NIVEL 2</div>
                        <div class="separator"></div>
                        <div class="estado-counter">
                            <span x-text="getUbicacionesOcupadas(2)">3</span>/<span>6</span> ocupadas
                            <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></div>
                        </div>
                    </div>

                    <!-- CARRUSEL SWIPER PARA UBICACIONES -->
                    <div class="swiper swiper-rack" id="swiperRackNivel2">
                        <div class="swiper-wrapper">
                            <!-- Slide 1: U005, U006, U007, U008 -->
                            <div class="swiper-slide">
                                <div class="ubicaciones-fila">
                                    <!-- Grupo 1: U005 y U006 -->
                                    <div class="rack-grupo-container">
                                        <div class="rack-grupo-ubicaciones">
                                            <div @click="manejarClickUbicacion(getUbicacion('U005'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('medio')">
                                                <div class="ubicacion-codigo">U005</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">15/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div @click="manejarClickUbicacion(getUbicacion('U006'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('medio')">
                                                <div class="ubicacion-codigo">U006</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">12/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Base del rack (tabla de madera) debajo del grupo -->
                                        <div class="rack-grupo-base"></div>
                                    </div>

                                    <!-- Grupo 2: U007 y U008 -->
                                    <div class="rack-grupo-container">
                                        <div class="rack-grupo-ubicaciones">
                                            <div @click="manejarClickUbicacion(getUbicacion('U007'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('bajo')">
                                                <div class="ubicacion-codigo">U007</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">11/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div @click="manejarClickUbicacion(getUbicacion('U008'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('bajo')">
                                                <div class="ubicacion-codigo">U008</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">9/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Base del rack (tabla de madera) debajo del grupo -->
                                        <div class="rack-grupo-base"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 2: U009, U010, Vacías -->
                            <div class="swiper-slide">
                                <div class="ubicaciones-fila">
                                    <!-- Grupo 3: U009 y U010 -->
                                    <div class="rack-grupo-container">
                                        <div class="rack-grupo-ubicaciones">
                                            <div @click="manejarClickUbicacion(getUbicacion('U009'))"
                                                class="ubicacion-box vacia">
                                                <div class="ubicacion-codigo">U009</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div class="ubicacion-cantidad">Vacía</div>
                                                    <div class="ubicacion-categoria">Paneles</div>
                                                </div>
                                            </div>
                                            <div @click="manejarClickUbicacion(getUbicacion('U010'))"
                                                class="ubicacion-box vacia">
                                                <div class="ubicacion-codigo">U010</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div class="ubicacion-cantidad">Vacía</div>
                                                    <div class="ubicacion-categoria">Paneles</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Base del rack (tabla de madera) debajo del grupo -->
                                        <div class="rack-grupo-base"></div>
                                    </div>
                                </div>
                            </div>
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

                <!-- Nivel 1 - ABAJO - CON SWIPER PARA 6 UBICACIONES -->
                <div
                    class="panel rounded-2xl p-6 shadow-xl backdrop-blur-md border border-white/20 transition-all duration-300 ease-in-out hover:shadow-2xl">
                    <!-- Header del nivel -->
                    <div class="panel-header">
                        <div class="nivel-indicator">NIVEL 1</div>
                        <div class="separator"></div>
                        <div class="estado-counter">
                            <span x-text="getUbicacionesOcupadas(1)">4</span>/<span>6</span> ocupadas
                            <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></div>
                        </div>
                    </div>

                    <!-- CARRUSEL SWIPER PARA UBICACIONES DEL NIVEL 1 -->
                    <div class="swiper swiper-rack" id="swiperRackNivel1">
                        <div class="swiper-wrapper">
                            <!-- Slide 1: U001, U002, U003, U004 -->
                            <div class="swiper-slide">
                                <div class="ubicaciones-fila">
                                    <!-- Grupo 1: U001 y U002 -->
                                    <div class="rack-grupo-container">
                                        <div class="rack-grupo-ubicaciones">
                                            <div @click="manejarClickUbicacion(getUbicacion('U001'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('alto')">
                                                <div class="ubicacion-codigo">U001</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">18/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div @click="manejarClickUbicacion(getUbicacion('U002'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('medio')">
                                                <div class="ubicacion-codigo">U002</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">14/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Base del rack (tabla de madera) debajo del grupo -->
                                        <div class="rack-grupo-base"></div>
                                    </div>

                                    <!-- Grupo 2: U003 y U004 -->
                                    <div class="rack-grupo-container">
                                        <div class="rack-grupo-ubicaciones">
                                            <div @click="manejarClickUbicacion(getUbicacion('U003'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('bajo')">
                                                <div class="ubicacion-codigo">U003</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">7/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div @click="manejarClickUbicacion(getUbicacion('U004'))"
                                                class="ubicacion-box vacia">
                                                <div class="ubicacion-codigo">U004</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div class="ubicacion-cantidad">Vacía</div>
                                                    <div class="ubicacion-categoria">Paneles</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Base del rack (tabla de madera) debajo del grupo -->
                                        <div class="rack-grupo-base"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 2: U011 y U012 -->
                            <div class="swiper-slide">
                                <div class="ubicaciones-fila">
                                    <!-- Grupo 3: U011 y U012 -->
                                    <div class="rack-grupo-container">
                                        <div class="rack-grupo-ubicaciones">
                                            <div @click="manejarClickUbicacion(getUbicacion('U011'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('bajo')">
                                                <div class="ubicacion-codigo">U011</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">9/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div @click="manejarClickUbicacion(getUbicacion('U012'))"
                                                class="ubicacion-box ocupada" :style="getColorEstado('medio')">
                                                <div class="ubicacion-codigo">U012</div>
                                                <div class="ubicacion-icono">
                                                    <i class="fas fa-tv"></i>
                                                </div>
                                                <div class="ubicacion-info">
                                                    <div>
                                                        <div class="ubicacion-cantidad">11/20</div>
                                                        <div class="ubicacion-categoria">Paneles</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Base del rack (tabla de madera) debajo del grupo -->
                                        <div class="rack-grupo-base"></div>
                                    </div>
                                </div>
                            </div>
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
            </div>
        </main>
        <!-- Modal para Detalles de Ubicación - SOLO PANELES -->
        <div x-data="modalDetalleUbicacion()" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="open && '!block'" x-show="open">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="closeModal">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl">

                    <!-- Header del Modal -->
                    <div class="flex bg-primary items-center justify-between px-6 py-4">
                        <div class="font-bold text-lg text-white">
                            Detalles de Panel
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
                                    <span class="font-semibold text-gray-700">Paneles Actuales:</span>
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

                        <!-- Información de Paneles -->
                        <template x-if="ubicacion?.productos?.length > 0">
                            <div class="mb-6">
                                <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">
                                    <i class="fas fa-tv mr-2"></i>Paneles en la Ubicación
                                </h3>
                                <!-- En la sección de información de paneles -->
                                <div class="space-y-3">
                                    <template x-for="panel in ubicacion.productos" :key="panel.nombre">
                                        <div
                                            class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800" x-text="panel.nombre"></div>
                                                <div class="text-sm text-gray-600 mt-1 flex items-center gap-4">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-ruler-combined text-blue-500 mr-1"></i>
                                                        <span x-text="panel.pulgadas"></span>
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-tv text-green-500 mr-1"></i>
                                                        <span x-text="panel.subcategoria"></span>
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-tag text-purple-500 mr-1"></i>
                                                        <span x-text="panel.categoria"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-lg text-blue-600" x-text="panel.cantidad">
                                                </div>
                                                <div class="text-xs text-gray-500">paneles</div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="!ubicacion?.productos?.length">
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <i class="fas fa-tv text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 font-semibold">Ubicación Vacía</p>
                                <p class="text-gray-400 text-sm mt-1">No hay paneles en esta ubicación</p>
                            </div>
                        </template>

                        <!-- Resumen de Paneles -->
                        <template x-if="ubicacion?.productos?.length > 0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div
                                    class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-tv text-blue-500 mr-2"></i>
                                        <span class="font-semibold text-blue-700">Total Paneles</span>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-600"
                                        x-text="ubicacion?.cantidad_total || 0"></div>
                                </div>

                                <div
                                    class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-ruler-combined text-green-500 mr-2"></i>
                                        <span class="font-semibold text-green-700">Medida del Panel</span>
                                    </div>
                                    <div class="text-2xl font-bold text-green-600"
                                        x-text="getPulgadasUbicacion(ubicacion)"></div>
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
    <script src="{{ asset('assets/js/almacen/detalle-rack/panel.js') }}"></script>
</x-layout.default>
