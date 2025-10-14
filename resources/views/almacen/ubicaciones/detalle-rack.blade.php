<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        @keyframes pulse-valid {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
        }

        @keyframes pulse-invalid {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
        }

        .animate-pulse-valid {
            animation: pulse-valid 2s infinite;
        }

        .animate-pulse-invalid {
            animation: pulse-invalid 2s infinite;
        }



        .floating-stats {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }


        @keyframes shine {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Flechas */
        .swiper-button-next,
        .swiper-button-prev {
            color: #000 !important;
            /* Negro */
        }

        /* Bullets */
        .swiper-pagination-bullet {
            background: #000 !important;
            /* Negro */
        }

        /* Bullet activo */
        .swiper-pagination-bullet-active {
            background: #000 !important;
        }

        @keyframes pulse-valid {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
        }

        @keyframes pulse-invalid {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
        }

        .animate-pulse-valid {
            animation: pulse-valid 2s infinite;
        }

        .animate-pulse-invalid {
            animation: pulse-invalid 2s infinite;
        }

        .floating-stats {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes shine {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #000 !important;
        }

        .swiper-pagination-bullet {
            background: #000 !important;
        }

        .swiper-pagination-bullet-active {
            background: #000 !important;
        }
    </style>

    <div x-data="rackDetalle()" x-init="init()" class="min-h-screen flex flex-col">
        <!-- Header Mejorado -->
        <header class="relative overflow-hidden bg-black text-white px-6 py-8">
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
                            Rack <span x-text="rack.nombre" class="text-yellow-300">{{ $rack['nombre'] }}</span>
                        </h1>
                        <p class="text-white/80 text-base mt-1">Sede: {{ $rack['sede'] }} - Sistema de Gestión de
                            Almacén</p>
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
        </header>

        <!-- Indicador de Reubicación -->
        <div x-show="modoReubicacion.activo"
            class="fixed top-5 right-5 bg-blue-600 text-white px-5 py-3 rounded-lg shadow-xl z-50
            flex items-center gap-3 font-semibold animate-pulse">
            <i class="fas fa-arrows-alt"></i>
            <span>Reubicando: <span x-text="modoReubicacion.producto"></span></span>
            <button @click="cancelarReubicacion()"
                class="ml-2 bg-white/20 hover:bg-white/30 text-white rounded px-2 py-1 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Rack Grid Mejorado -->
        <main class="flex-1 p-8 perspective-[1000px] [transform-style:preserve-3d]">
            <div class="max-w-7xl mx-auto space-y-8">
                <template x-for="(nivel, nivelIndex) in rack.niveles" :key="nivelIndex">
                    <div
                        class="panel rounded-2xl p-6 shadow-xl backdrop-blur-md border border-white/20 transition-transform duration-300 ease-in-out hover:translate-z-[10px] hover:scale-[1.02] hover:shadow-2xl">

                        <div class="flex items-center gap-4 mb-6">
                            <div class="px-3 py-6 rounded-lg font-bold text-sm text-white [writing-mode:vertical-rl] [text-orientation:mixed] [transform:translateZ(5px)]"
                                style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                                NIVEL <span x-text="nivel.numero"></span>
                            </div>
                            <div class="flex-1 h-px bg-gradient-to-r from-purple-500 to-transparent"></div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span x-text="nivel.ubicaciones.filter(u => u.producto).length"></span>/<span
                                    x-text="nivel.ubicaciones.length"></span> ocupadas
                                <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></div>
                            </div>
                        </div>

                        <!-- Carrusel de ubicaciones -->
                        <div class="swiper mySwiper" :data-swiper-index="nivelIndex">
                            <div class="swiper-wrapper">
                                <!-- Dentro del carrusel de ubicaciones -->
                                <template x-for="(ubi, ubiIndex) in nivel.ubicaciones" :key="ubiIndex">
                                    <div class="swiper-slide">
                                        <div @click="manejarClickUbicacion(ubi)"
                                            class="relative group h-32 rounded-xl flex flex-col items-center justify-center cursor-pointer transition-all duration-300 ease-in-out overflow-hidden hover:-translate-y-0.5 hover:scale-105 hover:shadow-xl"
                                            :class="[
                                                getEstadoClass(ubi.estado),
                                                modoReubicacion.activo ? (esDestinoValido(ubi) ? 'animate-pulse-valid' :
                                                    'animate-pulse-invalid') : ''
                                            ]"
                                            :style="{
                                                backgroundColor: ubi.estado === 'bajo' ? '#22c55e' : ubi
                                                    .estado === 'medio' ? '#facc15' : ubi.estado === 'alto' ?
                                                    '#f97316' : ubi.estado === 'muy_alto' ? '#ef4444' : '#888ea8'
                                            }">

                                            <div
                                                class="absolute inset-[-2px] rounded-inherit bg-gradient-to-tr from-transparent via-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                                            </div>

                                            <!-- Código -->
                                            <div class="text-xs font-bold mb-1 opacity-90 text-white"
                                                x-text="ubi.codigo"></div>

                                            <!-- Icono -->
                                            <div class="text-xl mb-2 text-white">
                                                <template x-if="ubi.productos && ubi.productos.length > 0">
                                                    <i class="fas fa-boxes"></i>
                                                </template>
                                                <template x-if="!ubi.productos || ubi.productos.length === 0">
                                                    <i class="fas fa-cube text-white"></i>
                                                </template>
                                            </div>

                                            <!-- Información simplificada -->
                                            <template x-if="ubi.productos && ubi.productos.length > 0">
                                                <div class="text-center space-y-1 w-full px-2">
                                                    <!-- Categorías acumuladas -->
                                                    <div class="text-xs font-semibold text-white bg-black/40 px-2 py-1 rounded truncate"
                                                        x-text="ubi.categorias_acumuladas || 'Sin categoría'">
                                                    </div>

                                                    <!-- Cantidad total -->
                                                    <div class="text-xs font-bold text-white bg-white/30 px-2 py-1 rounded"
                                                        x-text="ubi.cantidad_total + '/' + ubi.capacidad">
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="!ubi.productos || ubi.productos.length === 0">
                                                <div class="text-xs text-white bg-black/30 px-2 py-1 rounded">Vacío
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Controles -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </template>
            </div>
        </main>


        <!-- Modal Principal -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="modal.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="modal.open = false">
                <div x-show="modal.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl relative">

                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg text-gray-800">
                            Ubicación <span x-text="modal.ubi.codigo" class="text-purple-600"></span>
                        </div>
                        <button type="button" class="text-gray-500 hover:text-gray-700" @click="modal.open = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        <!-- Si tiene productos -->
                        <template x-if="modal.ubi.productos && modal.ubi.productos.length > 0">
                            <div class="space-y-4">
                                <!-- Resumen de la ubicación -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-green-50 p-4 rounded-xl border border-blue-200">
                                    <div class="grid grid-cols-3 gap-4 mb-3">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-blue-600"
                                                x-text="modal.ubi.productos.length"></div>
                                            <div class="text-xs text-gray-600">Productos</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600"
                                                x-text="modal.ubi.cantidad_total"></div>
                                            <div class="text-xs text-gray-600">Unidades</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-purple-600"
                                                x-text="modal.ubi.capacidad"></div>
                                            <div class="text-xs text-gray-600">Capacidad</div>
                                        </div>
                                    </div>

                                    <!-- Categorías y tipos -->
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div class="bg-white p-2 rounded border">
                                            <div class="font-medium text-gray-600">Categorías:</div>
                                            <div class="font-bold text-gray-800 truncate"
                                                x-text="modal.ubi.categorias_acumuladas || 'Sin categoría'"></div>
                                        </div>
                                        <div class="bg-white p-2 rounded border">
                                            <div class="font-medium text-gray-600">Tipos:</div>
                                            <div class="font-bold text-gray-800 truncate"
                                                x-text="modal.ubi.tipos_acumulados || 'Sin tipo'"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lista de productos -->
                                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-3 border-b">
                                        <h3 class="font-semibold text-gray-800">Productos en esta ubicación</h3>
                                    </div>

                                    <div class="max-h-64 overflow-y-auto">
                                        <template x-for="(producto, idx) in modal.ubi.productos"
                                            :key="idx">
                                            <div class="border-b border-gray-100 last:border-b-0">
                                                <div
                                                    class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-3 mb-2">
                                                            <div
                                                                class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-box text-blue-600 text-sm"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="font-semibold text-gray-800 truncate"
                                                                    x-text="producto.nombre"></p>
                                                                <div class="flex gap-2 text-xs text-gray-500 mt-1">
                                                                    <span
                                                                        class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded"
                                                                        x-text="producto.categoria"></span>
                                                                    <span
                                                                        class="bg-green-100 text-green-700 px-2 py-0.5 rounded"
                                                                        x-text="producto.tipo_articulo"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="text-right">
                                                        <span class="block font-bold text-gray-800 text-lg"
                                                            x-text="producto.cantidad + ' und.'"></span>
                                                        <button
                                                            @click="iniciarReubicacionProducto(modal.ubi, producto)"
                                                            class="mt-1 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition-all duration-200 hover:scale-105 flex items-center gap-1">
                                                            <i class="fas fa-arrows-alt text-xs"></i>
                                                            Mover
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Información adicional -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-3 rounded-xl">
                                        <label class="text-sm font-medium text-gray-600">Último movimiento</label>
                                        <p class="text-gray-800 font-medium" x-text="formatFecha(modal.ubi.fecha)">
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-xl">
                                        <label class="text-sm font-medium text-gray-600">Estado</label>
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 rounded-full"
                                                :class="{
                                                    'bg-green-500': modal.ubi.estado === 'bajo',
                                                    'bg-yellow-500': modal.ubi.estado === 'medio',
                                                    'bg-orange-500': modal.ubi.estado === 'alto',
                                                    'bg-red-500': modal.ubi.estado === 'muy_alto',
                                                    'bg-gray-500': !modal.ubi.estado
                                                }">
                                            </div>
                                            <span class="text-gray-800 font-medium capitalize"
                                                x-text="modal.ubi.estado || 'vacío'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="grid grid-cols-2 gap-3 pt-4">
                                    <button @click="iniciarReubicacionMultiple(modal.ubi)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-arrows-alt text-xs"></i>
                                        Reubicar Todo
                                    </button>

                                    <button @click="iniciarReubicacionRack(modal.ubi)"
                                        class="bg-secondary text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-exchange-alt text-xs"></i>
                                        Otro Rack
                                    </button>

                                    <button @click="vaciarUbicacion(modal.ubi)"
                                        class="bg-red-500 hover:bg-red-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-trash text-xs"></i>
                                        Vaciar
                                    </button>

                                    <button @click="abrirHistorial(modal.ubi)"
                                        class="bg-gray-600 hover:bg-gray-700 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-history text-xs"></i>
                                        Historial
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Si está vacío -->
                        <template x-if="!modal.ubi.productos || modal.ubi.productos.length === 0">
                            <div class="space-y-4">
                                <div class="text-center py-8">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-cube text-3xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-medium text-gray-800 mb-2">Ubicación disponible</h3>
                                    <p class="text-gray-600 max-w-md mx-auto">Esta posición está vacía y lista para
                                        almacenar productos. Puedes agregar nuevos productos o consultar el historial de
                                        movimientos.</p>
                                </div>

                                <!-- Botones para ubicación vacía -->
                                <div class="grid grid-cols-2 gap-3 pt-4">
                                    <button @click="abrirHistorial(modal.ubi)"
                                        class="bg-gray-600 hover:bg-gray-700 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-history text-xs"></i>
                                        Historial Completo
                                    </button>

                                    <button @click="abrirModalAgregarProducto(modal.ubi)"
                                        class="bg-green-500 hover:bg-green-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-plus text-xs"></i>
                                        Agregar Producto
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para Reubicación entre Racks -->
        <div x-show="modalReubicacionRack.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalReubicacionRack.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModalReubicacionRack()">
                <div x-show="modalReubicacionRack.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg relative">

                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg text-gray-800">
                            Reubicar a Otro Rack
                        </div>
                        <button type="button" class="text-gray-500 hover:text-gray-700"
                            @click="cerrarModalReubicacionRack()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        <!-- Información del producto origen -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 mb-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <label class="font-medium text-gray-600">Producto</label>
                                    <p class="font-bold text-gray-800"
                                        x-text="modalReubicacionRack.ubicacionOrigen.producto"></p>
                                </div>
                                <div>
                                    <label class="font-medium text-gray-600">Cantidad</label>
                                    <p class="font-bold text-gray-800"
                                        x-text="modalReubicacionRack.ubicacionOrigen.cantidad + ' unidades'"></p>
                                </div>
                                <div class="col-span-2">
                                    <label class="font-medium text-gray-600">Ubicación origen</label>
                                    <p class="font-bold text-gray-800"
                                        x-text="modalReubicacionRack.ubicacionOrigen.codigo"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Selección de rack destino -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Rack
                                Destino:</label>
                            <select x-model="modalReubicacionRack.rackDestinoSeleccionado"
                                @change="cargarUbicacionesDestino()"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione un rack</option>
                                <template x-for="rack in modalReubicacionRack.racksDisponibles" :key="rack.id">
                                    <option :value="rack.id" x-text="'Rack ' + rack.nombre + ' - ' + rack.sede">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <!-- Selección de ubicación destino -->
                        <div class="mb-4" x-show="modalReubicacionRack.rackDestinoSeleccionado">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Ubicación
                                Destino:</label>
                            <select x-model="modalReubicacionRack.ubicacionDestinoSeleccionada"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione una ubicación</option>
                                <template x-for="ubicacion in modalReubicacionRack.ubicacionesDestino"
                                    :key="ubicacion.id">
                                    <option :value="ubicacion.id"
                                        x-text="ubicacion.codigo + ' (Capacidad: ' + ubicacion.capacidad_maxima + ')'">
                                    </option>
                                </template>
                            </select>
                            <p class="text-xs text-gray-500 mt-1"
                                x-text="modalReubicacionRack.ubicacionesDestino.length + ' ubicaciones vacías disponibles'"
                                x-show="modalReubicacionRack.rackDestinoSeleccionado"></p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button @click="cerrarModalReubicacionRack()"
                                class="flex-1 bg-gray-500 text-white py-3 px-4 rounded-xl font-medium hover:bg-gray-600 transition">
                                Cancelar
                            </button>
                            <button @click="confirmarReubicacionRack()"
                                :disabled="!modalReubicacionRack.ubicacionDestinoSeleccionada"
                                :class="!modalReubicacionRack.ubicacionDestinoSeleccionada ? 'bg-gray-400 cursor-not-allowed' :
                                    'bg-primary hover:bg-purple-700'"
                                class="flex-1 text-white py-3 px-4 rounded-xl font-medium transition flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i>
                                Confirmar Reubicación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Agregar Múltiples Productos -->
        <div x-show="modalAgregarProducto.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalAgregarProducto.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModalAgregarProducto()">
                <div x-show="modalAgregarProducto.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-4xl relative">

                    <!-- Header Mejorado -->
                    <div
                        class="flex bg-gradient-to-r from-blue-600 to-purple-600 items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-boxes text-white text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold text-lg text-white">Agregar Múltiples Productos</div>
                                <div class="text-blue-100 text-sm">Ubicación: <span
                                        x-text="modalAgregarProducto.ubicacion.codigo" class="font-semibold"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="text-white hover:text-blue-200 transition-colors"
                            @click="cerrarModalAgregarProducto()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 bg-gray-50">
                        <!-- Información de la ubicación -->
                        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
                            <div class="grid grid-cols-4 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-blue-600"
                                        x-text="modalAgregarProducto.capacidadMaxima"></div>
                                    <div class="text-xs text-gray-600 mt-1">Capacidad Total</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-600" x-text="getTotalCantidades()">
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">Unidades a Agregar</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold"
                                        :class="getCapacidadDisponible() >= 0 ? 'text-green-600' : 'text-red-600'"
                                        x-text="getCapacidadDisponible()"></div>
                                    <div class="text-xs text-gray-600 mt-1">Disponible</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-purple-600"
                                        x-text="modalAgregarProducto.productosSeleccionados.length"></div>
                                    <div class="text-xs text-gray-600 mt-1">Productos</div>
                                </div>
                            </div>

                            <!-- Barra de progreso -->
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span>Ocupación</span>
                                    <span x-text="getPorcentajeOcupacion() + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300"
                                        :style="'width: ' + Math.min(getPorcentajeOcupacion(), 100) + '%;'"
                                        :class="getPorcentajeOcupacion() > 80 ? 'from-yellow-500 to-red-500' :
                                            'from-green-500 to-blue-500'">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Columna izquierda - Búsqueda y selección -->
                            <div class="space-y-4">
                                <!-- Buscador de productos -->
                                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">
                                        <i class="fas fa-search mr-2 text-blue-500"></i>
                                        Buscar y Seleccionar Artículos
                                    </label>

                                    <div class="space-y-3">

                                        <!-- Búsqueda rápida -->
                                        <div class="relative">
                                            <input type="text" x-model="modalAgregarProducto.busqueda"
                                                @input="filtrarProductos()"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10"
                                                placeholder="Buscar artículos...">
                                            <i
                                                class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Artículos filtrados -->
                                <!-- Artículos filtrados -->
                                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm"
                                    x-show="modalAgregarProducto.productosFiltrados.length > 0">
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">
                                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                                        Artículos Disponibles
                                        <span class="text-gray-500 font-normal"
                                            x-text="'(' + modalAgregarProducto.productosFiltrados.length + ' encontrados)'"></span>
                                    </label>

                                    <!-- Virtual Scroll Container SIMPLIFICADO -->
                                    <div class="relative border border-gray-200 rounded-lg bg-gray-50">
                                        <!-- Scroll container -->
                                        <div class="h-64 overflow-y-auto custom-scrollbar" x-ref="scrollContainer"
                                            @scroll.debounce="handleScroll($event)">

                                            <!-- Contenido principal SIN virtual scroll complejo -->
                                            <div class="space-y-2 p-2">
                                                <template x-for="producto in modalAgregarProducto.productosFiltrados"
                                                    :key="producto.id">
                                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md"
                                                        @click="agregarProductoDesdeLista(producto)">
                                                        <div class="flex items-center gap-3 flex-1">
                                                            <div
                                                                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                                <i class="fas fa-box text-blue-600"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="font-semibold text-gray-800 text-sm truncate mb-1"
                                                                    x-text="producto.nombre"></p>
                                                                <div class="flex gap-2">
                                                                    <span
                                                                        class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium"
                                                                        x-text="producto.categoria || 'General'"></span>
                                                                    <span class="px-2 py-1 rounded text-xs font-medium"
                                                                        :class="{
                                                                            'bg-green-100 text-green-700': producto
                                                                                .tipo_articulo === 'PRODUCTOS',
                                                                            'bg-yellow-100 text-warning': producto
                                                                                .tipo_articulo === 'REPUESTOS',
                                                                            'bg-purple-100 text-secondary': producto
                                                                                .tipo_articulo === 'SUMINISTROS',
                                                                            'bg-orange-100 text-orange-700': producto
                                                                                .tipo_articulo === 'HERAMIENTAS',
                                                                            'bg-gray-100 text-gray-700': !['PRODUCTOS',
                                                                                'REPUESTOS', 'SUMINISTROS',
                                                                                'HERAMIENTAS'
                                                                            ].includes(producto.tipo_articulo)
                                                                        }"
                                                                        x-text="producto.tipo_articulo || 'Standard'">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded"
                                                                x-text="'Stock: ' + (producto.stock || 'N/A')"></span>
                                                            <i
                                                                class="fas fa-plus text-green-500 text-lg hover:text-green-600 transition-colors"></i>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Loading indicator -->
                                        <div x-show="modalAgregarProducto.loading"
                                            class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center rounded-lg">
                                            <div class="flex flex-col items-center gap-3 text-gray-600">
                                                <div
                                                    class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                                </div>
                                                <span class="text-sm font-medium">Cargando productos...</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información de resultados -->
                                    <div class="mt-2 text-xs text-gray-500 flex justify-between items-center">
                                        <span>💡 Usa la rueda del mouse para navegar rápidamente</span>
                                        <span
                                            x-text="modalAgregarProducto.productosFiltrados.length + ' productos'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha - Productos seleccionados -->
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-sm font-semibold text-gray-800">
                                            <i class="fas fa-clipboard-list mr-2 text-green-500"></i>
                                            Artículos Seleccionados
                                            <span class="text-gray-500 font-normal"
                                                x-text="'(' + modalAgregarProducto.productosSeleccionados.length + ')'"></span>
                                        </label>

                                        <button @click="limpiarSeleccion()"
                                            class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors flex items-center gap-1">
                                            <i class="fas fa-trash text-xs"></i>
                                            Limpiar Todo
                                        </button>
                                    </div>

                                    <!-- Lista de productos seleccionados (MEJORADO) -->
                                    <div class="space-y-3 max-h-80 overflow-y-auto">
                                        <template x-if="modalAgregarProducto.productosSeleccionados.length > 0">
                                            <template
                                                x-for="(producto, index) in modalAgregarProducto.productosSeleccionados"
                                                :key="producto.id">
                                                <div
                                                    class="border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition-all duration-200">
                                                    <!-- Encabezado del producto -->
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div class="flex items-start gap-3 flex-1">
                                                            <!-- Ícono del producto -->
                                                            <div
                                                                class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                                                <i class="fas fa-box text-sm"></i>
                                                            </div>

                                                            <!-- Información del producto -->
                                                            <div class="flex-1 min-w-0">
                                                                <h4 class="font-semibold text-gray-800 text-sm truncate"
                                                                    x-text="producto.nombre || 'Sin nombre'"></h4>
                                                                <div class="flex gap-2 mt-2">
                                                                    <span
                                                                        class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs"
                                                                        x-text="producto.categoria || 'Sin categoría'"></span>
                                                                    <span
                                                                        class="px-2 py-0.5 rounded text-xs font-medium"
                                                                        :class="{
                                                                            'bg-green-100 text-green-700': producto
                                                                                .tipo_articulo === 'PRODUCTOS',
                                                                            'bg-yellow-100 text-warning': producto
                                                                                .tipo_articulo === 'REPUESTOS',
                                                                            'bg-purple-100 text-secondary': producto
                                                                                .tipo_articulo === 'SUMINISTROS',
                                                                            'bg-orange-100 text-danger': producto
                                                                                .tipo_articulo === 'HERAMIENTAS',
                                                                            'bg-gray-100 text-gray-700': !['PRODUCTOS',
                                                                                'REPUESTOS', 'SUMINISTROS',
                                                                                'HERAMIENTAS'
                                                                            ].includes(producto.tipo_articulo)
                                                                        }"
                                                                        x-text="producto.tipo_articulo || 'Sin tipo'">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Controles y stock -->
                                                    <div
                                                        class="flex items-center justify-between pt-3 border-t border-gray-100">
                                                        <!-- Controles de cantidad -->
                                                        <div class="flex items-center gap-2">
                                                            <button @click="decrementarCantidad(index)"
                                                                :disabled="producto.cantidad <= 1"
                                                                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                                <i class="fas fa-minus text-gray-600 text-xs"></i>
                                                            </button>

                                                            <input type="number" x-model="producto.cantidad"
                                                                :max="modalAgregarProducto.capacidadMaxima"
                                                                min="1" @change="validarCantidad(index)"
                                                                class="w-16 text-center p-1 border border-gray-300 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                                                            <button @click="incrementarCantidad(index)"
                                                                :disabled="getTotalCantidades() >= modalAgregarProducto
                                                                    .capacidadMaxima"
                                                                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                                <i class="fas fa-plus text-gray-600 text-xs"></i>
                                                            </button>

                                                            <!-- Remover -->
                                                            <button @click="removerProductoSeleccionado(index)"
                                                                class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-colors ml-2">
                                                                <i class="fas fa-times text-xs"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Stock disponible -->
                                                        <div class="flex flex-col items-end">
                                                            <span class="text-xs text-gray-500">Stock disponible:
                                                                <span class="font-semibold"
                                                                    x-text="producto.stock || 'N/A'"></span>
                                                            </span>
                                                            <span class="text-xs font-semibold mt-1"
                                                                :class="producto.cantidad > (producto.stock || 999) ?
                                                                    'text-red-600' : 'text-green-600'">
                                                                <span x-text="producto.cantidad"></span> unidades
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </template>

                                        <!-- Estado vacío -->
                                        <template x-if="modalAgregarProducto.productosSeleccionados.length === 0">
                                            <div
                                                class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                                <i class="fas fa-box-open text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-gray-600 font-medium">No hay productos seleccionados</p>
                                                <p class="text-sm text-gray-500 mt-1">Usa el buscador para agregar
                                                    productos</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">
                                        <i class="fas fa-edit mr-2 text-purple-500"></i>
                                        Observaciones (Opcional)
                                    </label>
                                    <textarea x-model="modalAgregarProducto.observaciones"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                        rows="3" placeholder="Agregue notas sobre este movimiento de inventario..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex gap-3 pt-6 border-t border-gray-200 mt-6">
                            <button @click="cerrarModalAgregarProducto()"
                                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 hover:scale-105 flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </button>
                            <button @click="confirmarAgregarProducto()"
                                :disabled="modalAgregarProducto.productosSeleccionados.length === 0 || getTotalCantidades() >
                                    modalAgregarProducto.capacidadMaxima"
                                :class="modalAgregarProducto.productosSeleccionados.length === 0 || getTotalCantidades() >
                                    modalAgregarProducto.capacidadMaxima ?
                                    'bg-gray-400 cursor-not-allowed' :
                                    'bg-green-500 hover:from-green-600 hover:to-blue-600'"
                                class="flex-1 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 hover:scale-105 flex items-center justify-center gap-2 shadow-lg">
                                <i class="fas fa-check"></i>
                                Agregar Productos
                                <template x-if="modalAgregarProducto.productosSeleccionados.length > 0">
                                    <span class="bg-white/20 px-2 py-1 rounded text-xs ml-2"
                                        x-text="modalAgregarProducto.productosSeleccionados.length"></span>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para selección de rack destino -->
        <div x-show="modalSeleccionRack.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalSeleccionRack.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4"
                @click.self="modalSeleccionRack.open = false">
                <div x-show="modalSeleccionRack.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg relative">

                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg text-gray-800">
                            Mover a Otro Rack
                        </div>
                        <button type="button" class="text-gray-500 hover:text-gray-700"
                            @click="modalSeleccionRack.open = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 mb-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <label class="font-medium text-gray-600">Producto</label>
                                    <p class="font-bold text-gray-800" x-text="modalSeleccionRack.producto"></p>
                                </div>
                                <div>
                                    <label class="font-medium text-gray-600">Cantidad</label>
                                    <p class="font-bold text-gray-800"
                                        x-text="modalSeleccionRack.cantidad + ' unidades'"></p>
                                </div>
                                <div class="col-span-2">
                                    <label class="font-medium text-gray-600">Ubicación origen</label>
                                    <p class="font-bold text-gray-800" x-text="modalSeleccionRack.origen"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Rack
                                Destino:</label>
                            <select x-model="modalSeleccionRack.rackDestino"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <template x-for="(rack, index) in racks" :key="index">
                                    <option :value="index" :disabled="index === modoReubicacion.rackOrigen"
                                        x-text="'Rack ' + rack.nombre + (index === modoReubicacion.rackOrigen ? ' (Actual)' : '')">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button @click="modalSeleccionRack.open = false"
                                class="flex-1 bg-gray-500 text-white py-3 px-4 rounded-xl font-medium hover:bg-gray-600 transition">
                                Cancelar
                            </button>
                            <button @click="confirmarReubicacionRack()"
                                class="flex-1 bg-primary text-white py-3 px-4 rounded-xl font-medium hover:bg-purple-700 transition">
                                Continuar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Historial -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalHistorial.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="modalHistorial.open = false">
                <div x-show="modalHistorial.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg relative">

                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg text-gray-800">
                            Historial <span x-text="modalHistorial.ubi.codigo" class="text-purple-600"></span>
                        </div>
                        <button type="button" class="text-gray-500 hover:text-gray-700"
                            @click="modalHistorial.open = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5 max-h-[70vh] overflow-y-auto">
                        <!-- En el modal de historial, actualiza el template: -->
                        <template x-if="modalHistorial.ubi.historial && modalHistorial.ubi.historial.length > 0">
                            <ul class="space-y-3">
                                <template x-for="(mov, idx) in modalHistorial.ubi.historial" :key="idx">
                                    <li class="p-3 border rounded-lg bg-white shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-medium text-gray-800" x-text="mov.producto || 'Vacío'">
                                                </p>
                                                <p class="text-xs text-gray-500 capitalize" x-text="mov.tipo"></p>
                                            </div>
                                            <span
                                                class="font-semibold text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded"
                                                x-text="mov.cantidad + ' und.'"></span>
                                        </div>
                                        <div class="text-xs text-gray-500" x-text="formatFecha(mov.fecha)"></div>
                                        <!-- Información adicional para reubicaciones -->
                                        <template x-if="mov.desde">
                                            <div class="text-xs text-purple-600 mt-1">
                                                <i class="fas fa-arrow-right"></i> Desde: <span
                                                    x-text="mov.desde"></span>
                                                <template x-if="mov.rack_origen"> (Rack <span
                                                        x-text="mov.rack_origen"></span>)</template>
                                            </div>
                                        </template>
                                        <template x-if="mov.hacia">
                                            <div class="text-xs text-green-600 mt-1">
                                                <i class="fas fa-arrow-right"></i> Hacia: <span
                                                    x-text="mov.hacia"></span>
                                                <template x-if="mov.rack_destino"> (Rack <span
                                                        x-text="mov.rack_destino"></span>)</template>
                                            </div>
                                        </template>
                                    </li>
                                </template>
                            </ul>
                        </template>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalReubicacion.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="cancelarReubicacion()">
                <div x-show="modalReubicacion.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-md relative">
                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg text-gray-800">Confirmar Reubicación</div>
                        <button type="button" class="text-gray-500 hover:text-gray-700"
                            @click="modalReubicacion.open = false; cancelarReubicacion()">
                            <i class="fas fa-times"></i>
                        </button>

                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                                <i class="fas fa-arrows-alt text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Mover producto</h2>
                                <p class="text-gray-600">Entre ubicaciones</p>
                            </div>
                        </div>

                        <!-- Info producto -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 mb-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Producto</label>
                                    <p class="text-lg font-bold text-gray-800" x-text="modalReubicacion.producto">
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Cantidad</label>
                                    <p class="text-lg font-bold text-gray-800"
                                        x-text="modalReubicacion.cantidad + ' unidades'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Desde / Hacia -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl mb-4">
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-600">Desde</div>
                                <div class="text-lg font-bold text-gray-800" x-text="modalReubicacion.origen">
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-600">Hacia</div>
                                <div class="text-lg font-bold text-gray-800" x-text="modalReubicacion.destino">
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-3 pt-2">
                            <button @click="cancelarReubicacion()"
                                class="flex-1 btn btn-outline-danger">Cancelar</button>
                            <button @click="confirmarReubicacion()"
                                class="flex-1 btn btn-primary flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i>
                                Confirmar
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function rackDetalle() {
            return {
                // Datos del rack desde PHP
                rack: @json($rack),
                todosRacks: @json($todosRacks),
                rackActual: '{{ $rackActual }}',

                modalAgregarProducto: {
                    open: false,
                    ubicacion: {},
                    productos: [],
                    productosSeleccionados: [],
                    productosFiltrados: [],
                    productoSeleccionado: '',
                    busqueda: '',
                    cantidad: 1,
                    capacidadMaxima: 0,
                    observaciones: '',

                    // CONFIGURACIÓN MEJORADA para virtual scrolling
                    virtualScroll: {
                        visibleItems: [],
                        startOffset: 0,
                        endOffset: 0,
                        itemHeight: 80, // Aumentado para mejor visualización
                        visibleCount: 8, // Número óptimo para la altura
                        buffer: 3, // Items extra para scroll suave
                        loading: false,
                        searchTimeout: null
                    }
                },
                modalReubicacionRack: {
                    open: false,
                    ubicacionOrigen: {},
                    racksDisponibles: [],
                    rackDestinoSeleccionado: '',
                    ubicacionDestinoSeleccionada: '',
                    ubicacionesDestino: []
                },

                // Estado de la aplicación
                idxRackActual: {{ array_search($rackActual, $todosRacks) }},
                swipers: [],
                modal: {
                    open: false,
                    ubi: {}
                },
                modalReubicacion: {
                    open: false,
                    origen: '',
                    destino: '',
                    producto: '',
                    cantidad: 0
                },
                modoReubicacion: {
                    activo: false,
                    origen: '',
                    producto: '',
                    rackOrigen: null
                },
                modalSeleccionRack: {
                    open: false,
                    origen: '',
                    producto: '',
                    cantidad: 0,
                    rackDestino: null
                },
                modalHistorial: {
                    open: false,
                    ubi: {}
                },

                init() {
                    // Configuración global de toastr
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: '3000',
                    };

                    // ✅ NUEVO: Procesar los datos del rack para acumular categorías y tipos
                    this.procesarDatosRack();
                    this.initSwipers();
                },

                // ✅ NUEVO MÉTODO: Procesar datos para acumular categorías y tipos
                procesarDatosRack() {
                    this.rack.niveles.forEach(nivel => {
                        nivel.ubicaciones.forEach(ubicacion => {
                            if (ubicacion.productos && ubicacion.productos.length > 0) {
                                // ✅ Acumular categorías únicas
                                const categoriasUnicas = [...new Set(ubicacion.productos
                                    .map(p => p.categoria)
                                    .filter(c => c && c !== 'Sin categoría')
                                )];

                                // ✅ Acumular tipos de artículo únicos
                                const tiposUnicos = [...new Set(ubicacion.productos
                                    .map(p => p.tipo_articulo)
                                    .filter(t => t && t !== 'Sin tipo')
                                )];

                                // ✅ Agregar propiedades acumuladas a la ubicación
                                ubicacion.categorias_acumuladas = categoriasUnicas.length > 0 ?
                                    categoriasUnicas.join(', ') : 'Sin categoría';

                                ubicacion.tipos_acumulados = tiposUnicos.length > 0 ?
                                    tiposUnicos.join(', ') : 'Sin tipo';

                                // ✅ Calcular cantidad total
                                ubicacion.cantidad_total = ubicacion.productos.reduce((sum, p) => sum + (p
                                    .cantidad || 0), 0);

                            } else {
                                // Si no hay productos, establecer valores por defecto
                                ubicacion.categorias_acumuladas = 'Sin categoría';
                                ubicacion.tipos_acumulados = 'Sin tipo';
                                ubicacion.cantidad_total = 0;
                            }
                        });
                    });
                },

                initSwipers() {
                    this.$nextTick(() => {
                        // Destruir swipers anteriores
                        this.swipers.forEach((s) => s.destroy(true, true));
                        this.swipers = [];

                        document.querySelectorAll('.mySwiper').forEach((el) => {
                            const swiper = new Swiper(el, {
                                slidesPerView: 4,
                                spaceBetween: 20,
                                navigation: {
                                    nextEl: el.querySelector('.swiper-button-next'),
                                    prevEl: el.querySelector('.swiper-button-prev'),
                                },
                                pagination: {
                                    el: el.querySelector('.swiper-pagination'),
                                    clickable: true,
                                },
                                breakpoints: {
                                    320: {
                                        slidesPerView: 1
                                    },
                                    640: {
                                        slidesPerView: 2
                                    },
                                    1024: {
                                        slidesPerView: 4
                                    },
                                },
                                observer: true,
                                observeParents: true,
                            });
                            this.swipers.push(swiper);
                        });
                    });
                },

                // En tu Alpine.js, agrega estos métodos:

                // Método para reubicar un producto específico
                iniciarReubicacionProducto(ubi, producto) {
                    this.modoReubicacion.activo = true;
                    this.modoReubicacion.origen = ubi.codigo;
                    this.modoReubicacion.producto = producto.nombre;
                    this.modoReubicacion.ubicacionOrigenId = ubi.id;
                    this.modoReubicacion.productoId = producto.id;
                    this.modoReubicacion.cantidad = producto.cantidad;
                    this.modoReubicacion.tipo = 'producto_especifico';
                    this.modal.open = false;
                    this.success('Modo reubicación activado para: ' + producto.nombre);
                },

                // Método para reubicar todos los productos
                iniciarReubicacionMultiple(ubi) {
                    this.modoReubicacion.activo = true;
                    this.modoReubicacion.origen = ubi.codigo;
                    this.modoReubicacion.producto = 'Todos los productos';
                    this.modoReubicacion.ubicacionOrigenId = ubi.id;
                    this.modoReubicacion.cantidad = ubi.cantidad;
                    this.modoReubicacion.tipo = 'todos_los_productos';
                    this.modal.open = false;
                    this.success('Modo reubicación activado para todos los productos');
                },

                // Actualizar el método esDestinoValido
                esDestinoValido(ubi) {
                    if (this.modoReubicacion.tipo === 'producto_especifico') {
                        // Para producto específico, verificar que no exista el mismo producto en el destino
                        const productoExistente = ubi.productos?.find(p => p.id === this.modoReubicacion.productoId);
                        return !productoExistente && ubi.codigo !== this.modoReubicacion.origen;
                    }
                    // Para reubicación completa, el destino debe estar vacío
                    return (!ubi.productos || ubi.productos.length === 0) && ubi.codigo !== this.modoReubicacion.origen;
                },

                // Navegación entre racks
                cambiarRack(direccion) {
                    let nuevoIdx = this.idxRackActual;

                    if (direccion === 'next') {
                        nuevoIdx = (nuevoIdx + 1) % this.todosRacks.length;
                    } else if (direccion === 'prev') {
                        nuevoIdx = (nuevoIdx - 1 + this.todosRacks.length) % this.todosRacks.length;
                    }

                    const siguienteRack = this.todosRacks[nuevoIdx];
                    window.location.href = `/almacen/ubicaciones/detalle/${siguienteRack}`;
                },

                // Métodos de utilidad
                getStats() {
                    const todasUbicaciones = this.rack.niveles.flatMap((n) => n.ubicaciones);

                    // ✅ Usar cantidad_total en lugar de cantidad
                    const ocupadas = todasUbicaciones.filter((u) =>
                        u.productos && u.productos.length > 0 && u.cantidad_total > 0
                    ).length;

                    return {
                        total: todasUbicaciones.length,
                        ocupadas: ocupadas,
                        vacias: todasUbicaciones.length - ocupadas,
                    };
                },

                getEstadoClass(estado) {
                    switch (estado) {
                        case 'muy_alto':
                            return 'text-white shadow-lg shadow-red-500/30';
                        case 'alto':
                            return 'text-white shadow-lg shadow-orange-500/30';
                        case 'medio':
                            return 'text-black shadow-lg shadow-yellow-400/30';
                        case 'bajo':
                            return 'text-white shadow-lg shadow-green-500/30';
                        default:
                            return 'bg-slate-200 text-slate-500 border-2 border-dashed border-slate-400';
                    }
                },

                formatFecha(fecha) {
                    if (!fecha) return 'Sin registros';
                    return new Date(fecha).toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                    });
                },

                // Métodos de interacción (mantén los mismos que tenías)
                manejarClickUbicacion(ubi) {
                    if (this.modoReubicacion.activo) {
                        // Lógica de reubicación...
                        if (ubi.codigo === this.modoReubicacion.origen) {
                            this.cancelarReubicacion();
                        } else if (this.esDestinoValido(ubi)) {
                            // CORRECIÓN: Usar la cantidad del modoReubicacion, no de la ubicación destino
                            this.modalReubicacion.origen = this.modoReubicacion.origen;
                            this.modalReubicacion.destino = ubi.codigo;
                            this.modalReubicacion.producto = this.modoReubicacion.producto;
                            this.modalReubicacion.cantidad = this.modoReubicacion.cantidad; // ← ESTA ES LA CORRECCIÓN
                            this.modalReubicacion.open = true;
                        }
                    } else {
                        this.verDetalle(ubi);
                    }
                },

                verDetalle(ubi) {
                    this.modal.ubi = ubi;
                    this.modal.open = true;
                },

                abrirHistorial(ubi) {
                    this.modalHistorial.ubi = ubi;
                    this.modalHistorial.open = true;
                },

                // MÉTODOS PARA REUBICACIÓN ENTRE RACKS
                async iniciarReubicacionRack(ubi) {
                    try {
                        if (!ubi.producto || ubi.cantidad <= 0) {
                            this.error('No se puede reubicar: la ubicación está vacía');
                            return;
                        }

                        // Cargar lista de racks disponibles (excluyendo el actual)
                        const response = await fetch('/almacen/racks/disponibles', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.modalReubicacionRack.racksDisponibles = result.data;
                            this.modalReubicacionRack.ubicacionOrigen = ubi;
                            this.modalReubicacionRack.rackDestinoSeleccionado = '';
                            this.modalReubicacionRack.ubicacionDestinoSeleccionada = '';
                            this.modalReubicacionRack.ubicacionesDestino = [];
                            this.modalReubicacionRack.open = true;
                            this.modal.open = false;
                        } else {
                            this.error(result.message || 'Error al cargar racks disponibles');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error al iniciar reubicación entre racks');
                    }
                },

                async cargarUbicacionesDestino() {
                    if (!this.modalReubicacionRack.rackDestinoSeleccionado) {
                        this.modalReubicacionRack.ubicacionesDestino = [];
                        return;
                    }

                    try {
                        const response = await fetch(
                            `/almacen/racks/${this.modalReubicacionRack.rackDestinoSeleccionado}/ubicaciones-vacias`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                }
                            });

                        const result = await response.json();

                        if (result.success) {
                            this.modalReubicacionRack.ubicacionesDestino = result.data;
                        } else {
                            this.error(result.message || 'Error al cargar ubicaciones destino');
                            this.modalReubicacionRack.ubicacionesDestino = [];
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error al cargar ubicaciones destino');
                        this.modalReubicacionRack.ubicacionesDestino = [];
                    }
                },

                async confirmarReubicacionRack() {
                    try {
                        // Validaciones
                        if (!this.modalReubicacionRack.rackDestinoSeleccionado) {
                            this.error('Por favor seleccione un rack destino');
                            return;
                        }

                        if (!this.modalReubicacionRack.ubicacionDestinoSeleccionada) {
                            this.error('Por favor seleccione una ubicación destino');
                            return;
                        }

                        const payload = {
                            ubicacion_origen_id: this.modalReubicacionRack.ubicacionOrigen.id,
                            ubicacion_destino_id: this.modalReubicacionRack.ubicacionDestinoSeleccionada,
                            producto: this.modalReubicacionRack.ubicacionOrigen.producto,
                            cantidad: parseInt(this.modalReubicacionRack.ubicacionOrigen.cantidad),
                            tipo_reubicacion: 'otro_rack'
                        };

                        console.log('Confirmando reubicación entre racks:', payload);

                        const response = await fetch('/almacen/reubicacion/confirmar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();
                        console.log('Respuesta reubicación entre racks:', result);

                        if (result.success) {
                            this.success('Producto reubicado entre racks exitosamente');

                            // Actualizar la interfaz
                            this.actualizarInterfazDespuesReubicacionRack(
                                this.modalReubicacionRack.ubicacionOrigen.id,
                                this.modalReubicacionRack.ubicacionDestinoSeleccionada,
                                parseInt(this.modalReubicacionRack.ubicacionOrigen.cantidad)
                            );

                            this.cerrarModalReubicacionRack();
                        } else {
                            this.error(result.message || 'Error al reubicar entre racks');
                            if (result.errors) {
                                Object.values(result.errors).forEach(errorArray => {
                                    errorArray.forEach(error => {
                                        this.error(error);
                                    });
                                });
                            }
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },
                // Método para agregar producto desde el select
                agregarProductoSeleccionado() {
                    if (!this.modalAgregarProducto.productoSeleccionado) return;

                    const producto = this.modalAgregarProducto.productos.find(
                        p => p.id == this.modalAgregarProducto.productoSeleccionado
                    );

                    if (producto && !this.modalAgregarProducto.productosSeleccionados.some(p => p.id === producto.id)) {
                        this.modalAgregarProducto.productosSeleccionados.push({
                            ...producto,
                            cantidad: 1
                        });
                        this.modalAgregarProducto.productoSeleccionado = '';
                        this.filtrarProductos();
                    }
                },

                // Método para agregar desde la lista filtrada
                agregarProductoDesdeLista(producto) {
                    if (!this.modalAgregarProducto.productosSeleccionados.some(p => p.id === producto.id)) {
                        this.modalAgregarProducto.productosSeleccionados.push({
                            ...producto,
                            cantidad: 1
                        });
                        this.filtrarProductos();
                    }
                },

                // Filtrar productos por búsqueda
                filtrarProductos() {
                    // Limpiar timeout anterior
                    if (this.modalAgregarProducto.virtualScroll.searchTimeout) {
                        clearTimeout(this.modalAgregarProducto.virtualScroll.searchTimeout);
                    }

                    // Mostrar loading si hay muchos productos
                    if (this.modalAgregarProducto.productos.length > 1000) {
                        this.modalAgregarProducto.virtualScroll.loading = true;
                    }

                    // Usar debouncing para evitar múltiples búsquedas rápidas
                    this.modalAgregarProducto.virtualScroll.searchTimeout = setTimeout(() => {
                        const busqueda = this.modalAgregarProducto.busqueda.toLowerCase().trim();

                        if (!busqueda) {
                            this.modalAgregarProducto.productosFiltrados = this.modalAgregarProducto.productos;
                        } else {
                            // Búsqueda optimizada
                            this.modalAgregarProducto.productosFiltrados = this.modalAgregarProducto.productos
                                .filter(producto => {
                                    return (producto.nombre && producto.nombre.toLowerCase().includes(
                                            busqueda)) ||
                                        (producto.categoria && producto.categoria.toLowerCase().includes(
                                            busqueda)) ||
                                        (producto.tipo_articulo && producto.tipo_articulo.toLowerCase()
                                            .includes(busqueda)) ||
                                        (producto.codigo && producto.codigo.toLowerCase().includes(busqueda));
                                });
                        }

                        // Inicializar virtual scroll después de filtrar
                        this.initVirtualScroll();
                        this.modalAgregarProducto.virtualScroll.loading = false;

                    }, 300); // 300ms de debounce
                },

                // NUEVO MÉTODO: Inicializar virtual scroll
                initVirtualScroll() {
                    this.$nextTick(() => {
                        const virtualScroll = this.modalAgregarProducto.virtualScroll;
                        const allItems = this.modalAgregarProducto.productosFiltrados;

                        // Calcular cuántos items mostrar (con buffer)
                        const visibleWithBuffer = virtualScroll.visibleCount + (virtualScroll.buffer * 2);

                        // Calcular offsets
                        virtualScroll.startOffset = 0;
                        virtualScroll.endOffset = Math.max(0, (allItems.length - virtualScroll.visibleCount) *
                            virtualScroll.itemHeight);

                        // Mostrar primeros items (con buffer)
                        virtualScroll.visibleItems = allItems.slice(0, Math.min(visibleWithBuffer, allItems
                            .length));

                        // Resetear scroll position
                        if (this.$refs.scrollContainer) {
                            this.$refs.scrollContainer.scrollTop = 0;
                        }
                    });
                },

                // NUEVO MÉTODO: Manejar scroll para virtual scrolling
                handleScroll(event) {
                    const scrollContainer = event.target;
                    const scrollTop = scrollContainer.scrollTop;
                    const virtualScroll = this.modalAgregarProducto.virtualScroll;
                    const allItems = this.modalAgregarProducto.productosFiltrados;

                    if (allItems.length === 0) return;

                    // Calcular índice de inicio con buffer
                    const startIndex = Math.max(0, Math.floor(scrollTop / virtualScroll.itemHeight) - virtualScroll.buffer);
                    const visibleWithBuffer = virtualScroll.visibleCount + (virtualScroll.buffer * 2);
                    const endIndex = Math.min(startIndex + visibleWithBuffer, allItems.length);

                    // Actualizar items visibles
                    virtualScroll.visibleItems = allItems.slice(startIndex, endIndex);

                    // Calcular offsets
                    virtualScroll.startOffset = startIndex * virtualScroll.itemHeight;
                    virtualScroll.endOffset = Math.max(0, (allItems.length - endIndex) * virtualScroll.itemHeight);
                },

                // Incrementar cantidad
                incrementarCantidad(index) {
                    const producto = this.modalAgregarProducto.productosSeleccionados[index];
                    if (this.getTotalCantidades() < this.modalAgregarProducto.capacidadMaxima) {
                        producto.cantidad++;
                    }
                },

                // Decrementar cantidad
                decrementarCantidad(index) {
                    const producto = this.modalAgregarProducto.productosSeleccionados[index];
                    if (producto.cantidad > 1) {
                        producto.cantidad--;
                    }
                },

                // Validar cantidad
                validarCantidad(index) {
                    const producto = this.modalAgregarProducto.productosSeleccionados[index];
                    if (producto.cantidad < 1) {
                        producto.cantidad = 1;
                    }

                    const total = this.getTotalCantidades();
                    if (total > this.modalAgregarProducto.capacidadMaxima) {
                        const exceso = total - this.modalAgregarProducto.capacidadMaxima;
                        producto.cantidad = Math.max(1, producto.cantidad - exceso);
                        this.warning('Se ajustó la cantidad para no exceder la capacidad máxima');
                    }
                },

                // Remover producto seleccionado
                removerProductoSeleccionado(index) {
                    this.modalAgregarProducto.productosSeleccionados.splice(index, 1);
                },

                // Limpiar toda la selección
                limpiarSeleccion() {
                    if (this.modalAgregarProducto.productosSeleccionados.length > 0) {
                        if (confirm('¿Está seguro de que desea limpiar todos los productos seleccionados?')) {
                            this.modalAgregarProducto.productosSeleccionados = [];
                        }
                    }
                },

                // Calcular total de cantidades
                getTotalCantidades() {
                    return this.modalAgregarProducto.productosSeleccionados.reduce((sum, p) => {
                        return sum + (parseInt(p.cantidad) || 0);
                    }, 0);
                },

                // Obtener capacidad disponible
                getCapacidadDisponible() {
                    return this.modalAgregarProducto.capacidadMaxima - this.getTotalCantidades();
                },

                // Calcular porcentaje de ocupación
                getPorcentajeOcupacion() {
                    if (this.modalAgregarProducto.capacidadMaxima === 0) return 0;
                    return Math.round((this.getTotalCantidades() / this.modalAgregarProducto.capacidadMaxima) * 100);
                },

                // 3. También necesitas actualizar el método abrirModalAgregarProducto:
                async abrirModalAgregarProducto(ubi) {
                    try {
                        this.modalAgregarProducto.ubicacion = ubi;
                        this.modalAgregarProducto.capacidadMaxima = ubi.capacidad;
                        this.modalAgregarProducto.productosSeleccionados = [];
                        this.modalAgregarProducto.productosFiltrados = [];
                        this.modalAgregarProducto.busqueda = '';
                        this.modalAgregarProducto.productoSeleccionado = '';
                        this.modalAgregarProducto.observaciones = '';

                        // Inicializar virtual scroll
                        this.modalAgregarProducto.virtualScroll = {
                            visibleItems: [],
                            startOffset: 0,
                            endOffset: 0,
                            itemHeight: 72,
                            visibleCount: 10,
                            loading: true,
                            searchTimeout: null
                        };

                        console.log('Cargando productos...');

                        const response = await fetch('/almacen/productos/listar', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            this.modalAgregarProducto.productos = result.data;
                            this.modalAgregarProducto.productosFiltrados = result.data;

                            // Inicializar virtual scroll después de cargar datos
                            this.initVirtualScroll();
                            console.log('Productos cargados:', result.data.length);
                        } else {
                            this.error(result.message || 'Error al cargar productos');
                            return;
                        }

                        this.modalAgregarProducto.open = true;
                        this.modal.open = false;
                        this.modalAgregarProducto.virtualScroll.loading = false;

                    } catch (error) {
                        console.error('Error al cargar productos:', error);
                        this.error('Error al cargar la lista de productos: ' + error.message);
                        this.modalAgregarProducto.virtualScroll.loading = false;
                    }
                },

                // 4. Y actualizar el método cerrarModalAgregarProducto:
                cerrarModalAgregarProducto() {
                    this.modalAgregarProducto.open = false;
                    this.modalAgregarProducto.ubicacion = {};
                    this.modalAgregarProducto.productos = [];
                    this.modalAgregarProducto.productosSeleccionados = []; // ← Limpiar
                    this.modalAgregarProducto.productosFiltrados = []; // ← Limpiar
                    this.modalAgregarProducto.busqueda = ''; // ← Limpiar
                    this.modalAgregarProducto.productoSeleccionado = '';
                    this.modalAgregarProducto.cantidad = 1;
                    this.modalAgregarProducto.observaciones = '';
                },

                actualizarInterfazDespuesReubicacionRack(origenId, destinoId, cantidad) {
                    // Vaciar la ubicación origen en la interfaz actual
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === origenId) {
                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubi,
                                    producto: null,
                                    cantidad: 0,
                                    estado: 'vacio',
                                    fecha: null
                                };
                            }
                        });
                    });

                    // Forzar actualización
                    this.rack = {
                        ...this.rack
                    };
                    this.$nextTick(() => {
                        this.initSwipers();
                    });
                },

                cerrarModalReubicacionRack() {
                    this.modalReubicacionRack.open = false;
                    this.modalReubicacionRack.ubicacionOrigen = {};
                    this.modalReubicacionRack.racksDisponibles = [];
                    this.modalReubicacionRack.rackDestinoSeleccionado = '';
                    this.modalReubicacionRack.ubicacionDestinoSeleccionada = '';
                    this.modalReubicacionRack.ubicacionesDestino = [];
                },
                async iniciarReubicacion(ubi) {
                    // Convertir cantidad a número
                    const cantidad = parseInt(ubi.cantidad);

                    if (!ubi.producto || cantidad <= 0 || isNaN(cantidad)) {
                        this.error('No se puede reubicar: cantidad inválida');
                        return;
                    }

                    console.log('Datos de reubicación (JS):', {
                        id: ubi.id,
                        producto: ubi.producto,
                        cantidad: cantidad,
                        tipo_cantidad: typeof cantidad
                    });

                    try {
                        const payload = {
                            ubicacion_origen_id: Number(ubi.id),
                            producto: ubi.producto,
                            cantidad: cantidad // Usar la cantidad convertida
                        };

                        console.log('Payload enviado:', payload);

                        const response = await fetch('/almacen/reubicacion/iniciar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();
                        console.log('Respuesta del servidor:', result);

                        if (result.success) {
                            this.modoReubicacion.activo = true;
                            this.modoReubicacion.origen = ubi.codigo;
                            this.modoReubicacion.producto = ubi.producto;
                            this.modoReubicacion.ubicacionOrigenId = ubi.id;
                            this.modoReubicacion.cantidad = cantidad; // Guardar la cantidad convertida
                            this.modal.open = false;
                            this.success('Modo reubicación activado. Selecciona la ubicación destino.');
                        } else {
                            this.error(result.message || 'Error al iniciar reubicación');
                            if (result.errors) {
                                console.error('Errores de validación:', result.errors);
                            }
                            if (result.debug_types) {
                                console.error('Tipos de datos recibidos:', result.debug_types);
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

                async confirmarAgregarProducto() {
                    try {
                        // Validaciones
                        if (!this.modalAgregarProducto.productoSeleccionado) {
                            this.error('Por favor seleccione un producto');
                            return;
                        }

                        const cantidad = parseInt(this.modalAgregarProducto.cantidad);
                        if (isNaN(cantidad) || cantidad <= 0) {
                            this.error('La cantidad debe ser mayor a 0');
                            return;
                        }

                        if (cantidad > this.modalAgregarProducto.capacidadMaxima) {
                            this.error(
                                `La cantidad no puede superar la capacidad máxima de ${this.modalAgregarProducto.capacidadMaxima} unidades`
                            );
                            return;
                        }

                        const payload = {
                            ubicacion_id: this.modalAgregarProducto.ubicacion.id,
                            articulo_id: this.modalAgregarProducto.productoSeleccionado,
                            cantidad: cantidad,
                            observaciones: this.modalAgregarProducto.observaciones
                        };

                        console.log('Agregando producto:', payload);

                        const response = await fetch('/almacen/ubicaciones/agregar-producto', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();
                        console.log('Respuesta agregar producto:', result);

                        if (result.success) {
                            this.success('Producto agregado exitosamente');

                            // Actualizar la interfaz
                            this.actualizarInterfazDespuesAgregarProducto(
                                this.modalAgregarProducto.ubicacion.id,
                                result.data.producto,
                                cantidad
                            );

                            this.cerrarModalAgregarProducto();
                        } else {
                            this.error(result.message || 'Error al agregar producto');
                            if (result.errors) {
                                Object.values(result.errors).forEach(errorArray => {
                                    errorArray.forEach(error => {
                                        this.error(error);
                                    });
                                });
                            }
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

                actualizarInterfazDespuesAgregarProducto(ubicacionId, producto, cantidad) {
                    // Buscar la ubicación en la estructura de datos
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionId) {
                                // ✅ CORRECCIÓN: Manejar múltiples productos
                                const productosActuales = ubi.productos || [];

                                // Crear el nuevo producto con la estructura correcta
                                const nuevoProducto = {
                                    id: producto.id,
                                    nombre: producto.nombre,
                                    categoria: producto.categoria || 'Sin categoría',
                                    tipo_articulo: producto.tipo_articulo || 'Sin tipo',
                                    cantidad: cantidad
                                };

                                // Verificar si el producto ya existe en la ubicación
                                const productoExistenteIndex = productosActuales.findIndex(p => p.id ===
                                    producto.id);

                                if (productoExistenteIndex >= 0) {
                                    // Si existe, actualizar la cantidad
                                    productosActuales[productoExistenteIndex].cantidad += cantidad;
                                } else {
                                    // Si no existe, agregar el nuevo producto
                                    productosActuales.push(nuevoProducto);
                                }

                                // ✅ ACTUALIZACIÓN COMPLETA de la ubicación
                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubi,
                                    productos: productosActuales,
                                    // Mantener compatibilidad con propiedades antiguas
                                    producto: productosActuales.length > 0 ? productosActuales[0]
                                        .nombre : null,
                                    cantidad: productosActuales.reduce((sum, p) => sum + p.cantidad, 0),
                                    // Actualizar propiedades calculadas
                                    cantidad_total: productosActuales.reduce((sum, p) => sum + p
                                        .cantidad, 0),
                                    estado: this.calcularEstado(
                                        productosActuales.reduce((sum, p) => sum + p.cantidad, 0),
                                        ubi.capacidad
                                    ),
                                    fecha: new Date().toISOString()
                                };
                            }
                        });
                    });

                    // ✅ Reprocesar datos para recalcular categorías y tipos acumulados
                    this.procesarDatosRack();

                    // ✅ Forzar actualización de Alpine.js
                    this.rack = {
                        ...this.rack
                    };

                    // ✅ Reinicializar swipers
                    this.$nextTick(() => {
                        this.initSwipers();
                    });
                },

                // Método para vaciar ubicación (también lo agregué)
                async vaciarUbicacion(ubi) {
                    if (!confirm('¿Está seguro de que desea vaciar esta ubicación?')) {
                        return;
                    }

                    try {
                        const response = await fetch('/almacen/ubicaciones/vaciar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                ubicacion_id: ubi.id
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.success('Ubicación vaciada exitosamente');

                            // ✅ ACTUALIZACIÓN CORREGIDA: Vaciar todos los productos y propiedades
                            this.actualizarInterfazDespuesVaciar(ubi.id);
                            this.modal.open = false;
                        } else {
                            this.error(result.message || 'Error al vaciar ubicación');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

                actualizarInterfazDespuesVaciar(ubicacionId) {
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionId) {
                                // ✅ VACIADO COMPLETO - Restablecer todas las propiedades
                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubi,
                                    productos: [], // Vaciar array de productos
                                    producto: null, // Mantener compatibilidad
                                    cantidad: 0,
                                    cantidad_total: 0, // ✅ Importante: resetear cantidad total
                                    categorias_acumuladas: 'Sin categoría', // ✅ Resetear categorías
                                    tipos_acumulados: 'Sin tipo', // ✅ Resetear tipos
                                    estado: 'vacio',
                                    fecha: null
                                };
                            }
                        });
                    });

                    // ✅ Forzar actualización de Alpine.js
                    this.rack = {
                        ...this.rack
                    };

                    // ✅ Reprocesar datos para asegurar consistencia
                    this.procesarDatosRack();

                    // ✅ Reinicializar swipers
                    this.$nextTick(() => {
                        this.initSwipers();
                    });
                },


                async confirmarReubicacion() {
                    try {
                        // Buscar la ubicación destino por código
                        const ubicacionDestino = this.buscarUbicacionPorCodigo(this.modalReubicacion.destino);

                        if (!ubicacionDestino) {
                            this.error('Ubicación destino no encontrada');
                            return;
                        }

                        // Asegurar que la cantidad sea un número válido
                        const cantidad = parseInt(this.modalReubicacion.cantidad);

                        console.log('Confirmando reubicación:', {
                            origen_id: this.modoReubicacion.ubicacionOrigenId,
                            destino_id: ubicacionDestino.id,
                            producto: this.modalReubicacion.producto,
                            cantidad: cantidad,
                            tipo_cantidad: typeof cantidad
                        });

                        if (cantidad <= 0 || isNaN(cantidad)) {
                            this.error('Cantidad inválida para reubicación');
                            return;
                        }

                        const response = await fetch('/almacen/reubicacion/confirmar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                ubicacion_origen_id: this.modoReubicacion.ubicacionOrigenId,
                                ubicacion_destino_id: ubicacionDestino.id,
                                producto: this.modalReubicacion.producto,
                                cantidad: cantidad,
                                tipo_reubicacion: 'mismo_rack'
                            })
                        });

                        const result = await response.json();
                        console.log('Respuesta confirmación:', result);

                        if (result.success) {
                            this.success(result.message);

                            // Actualizar la interfaz
                            this.actualizarInterfazDespuesReubicacion(
                                this.modoReubicacion.ubicacionOrigenId,
                                ubicacionDestino.id,
                                cantidad
                            );

                            this.cancelarReubicacion();
                            this.modalReubicacion.open = false;
                        } else {
                            this.error(result.message || 'Error al confirmar reubicación');
                            if (result.errors) {
                                console.error('Errores de validación:', result.errors);
                                Object.values(result.errors).forEach(errorArray => {
                                    errorArray.forEach(error => {
                                        this.error(error);
                                    });
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },


                async cancelarReubicacion() {
                    try {
                        await fetch('/almacen/reubicacion/cancelar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        this.modoReubicacion.activo = false;
                        this.modoReubicacion.origen = '';
                        this.modoReubicacion.producto = '';
                        this.modoReubicacion.ubicacionOrigenId = null;
                        this.modoReubicacion.cantidad = 0;
                        this.modalReubicacion.open = false;

                        this.info('Reubicación cancelada');
                    } catch (error) {
                        console.error('Error:', error);
                        this.modoReubicacion.activo = false;
                        this.modalReubicacion.open = false;
                    }
                },

                // Método auxiliar para buscar ubicación por código
                buscarUbicacionPorCodigo(codigo) {
                    for (const nivel of this.rack.niveles) {
                        for (const ubi of nivel.ubicaciones) {
                            if (ubi.codigo === codigo) {
                                return ubi;
                            }
                        }
                    }
                    return null;
                },

                // Método para actualizar la interfaz después de reubicación
                actualizarInterfazDespuesReubicacion(origenId, destinoId, cantidad) {
                    // Buscar las ubicaciones en la estructura de datos
                    let ubicacionOrigen = null;
                    let ubicacionDestino = null;
                    let nivelOrigenIndex = -1;
                    let ubiOrigenIndex = -1;
                    let nivelDestinoIndex = -1;
                    let ubiDestinoIndex = -1;

                    // Encontrar las ubicaciones
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === origenId) {
                                ubicacionOrigen = ubi;
                                nivelOrigenIndex = nivelIndex;
                                ubiOrigenIndex = ubiIndex;
                            }
                            if (ubi.id === destinoId) {
                                ubicacionDestino = ubi;
                                nivelDestinoIndex = nivelIndex;
                                ubiDestinoIndex = ubiIndex;
                            }
                        });
                    });

                    if (ubicacionOrigen && ubicacionDestino) {
                        // Mover datos de origen a destino
                        ubicacionDestino.producto = ubicacionOrigen.producto;
                        ubicacionDestino.cantidad = cantidad;
                        ubicacionDestino.estado = this.calcularEstado(cantidad, ubicacionDestino.capacidad);
                        ubicacionDestino.fecha = new Date().toISOString().split('T')[0];

                        // Actualizar origen
                        const nuevaCantidadOrigen = ubicacionOrigen.cantidad - cantidad;
                        if (nuevaCantidadOrigen > 0) {
                            ubicacionOrigen.cantidad = nuevaCantidadOrigen;
                            ubicacionOrigen.estado = this.calcularEstado(nuevaCantidadOrigen, ubicacionOrigen.capacidad);
                        } else {
                            ubicacionOrigen.producto = null;
                            ubicacionOrigen.cantidad = 0;
                            ubicacionOrigen.estado = 'vacio';
                            ubicacionOrigen.fecha = null;
                        }

                        // Forzar actualización de Alpine.js
                        this.rack.niveles[nivelOrigenIndex].ubicaciones[ubiOrigenIndex] = {
                            ...ubicacionOrigen
                        };
                        this.rack.niveles[nivelDestinoIndex].ubicaciones[ubiDestinoIndex] = {
                            ...ubicacionDestino
                        };

                        // Reinicializar swipers
                        this.$nextTick(() => {
                            this.initSwipers();
                        });
                    }
                },

                // Método para calcular estado basado en cantidad y capacidad
                calcularEstado(cantidad, capacidad) {
                    if (capacidad <= 0) return 'vacio';

                    const porcentaje = (cantidad / capacidad) * 100;

                    if (porcentaje == 0) return 'vacio';
                    if (porcentaje <= 24) return 'bajo';
                    if (porcentaje <= 49) return 'medio';
                    if (porcentaje <= 74) return 'alto';
                    return 'muy_alto';
                },


                esDestinoValido(ubi) {
                    return !ubi.producto || ubi.codigo === this.modoReubicacion.origen;
                },

                // Métodos toastr
                success(msg) {
                    toastr.success(msg);
                },
                error(msg) {
                    toastr.error(msg);
                },
                warning(msg) {
                    toastr.warning(msg);
                },
                info(msg) {
                    toastr.info(msg);
                },

                // Los demás métodos de reubicación los mantienes igual...
                // confirmarReubicacion(), iniciarReubicacionRack(), etc.
            };
        }
    </script>
</x-layout.default>
