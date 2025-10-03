<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    </style>
    <div x-data="rackDetalle()" x-init="init()" class="min-h-screen flex flex-col ">
        <!-- Header Mejorado -->
        <header class="relative overflow-hidden bg-black text-white px-6 py-8">
            <!-- Efecto Shine (equivalente al ::before) -->
            <div
                class="absolute inset-0 -translate-x-full animate-[shine_3s_linear_infinite]
                bg-gradient-to-r from-transparent via-white/10 to-transparent">
            </div>

            <!-- Elementos decorativos de fondo -->
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
                            Rack <span x-text="rack.nombre" class="text-yellow-300"></span>
                        </h1>
                        <p class="text-white/80 text-base mt-1">Sistema de Gestión de Almacén</p>
                    </div>
                </div>

                <!-- Stats flotantes -->
                <div
                    class="flex gap-8 px-8 py-5 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 shadow-lg">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-400" x-text="getStats().ocupadas"></div>
                        <div class="text-sm text-white/80 mt-1">Ocupadas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-400" x-text="getStats().vacias"></div>
                        <div class="text-sm text-white/80 mt-1">Vacías</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-400" x-text="getStats().total"></div>
                        <div class="text-sm text-white/80 mt-1">Total</div>
                    </div>
                </div>

                <!-- Navegación mejorada -->
                <!-- En la sección de navegación, modifica los botones: -->
                <div class="flex gap-3">
                    <button @click="cambiarRack(idxRack - 1)"
                        class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300 flex items-center gap-3 group border border-white/20 backdrop-blur-sm hover:shadow-lg">
                        <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i>
                        Anterior
                    </button>
                    <button @click="cambiarRack(idxRack + 1)"
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
                <template x-for="(nivel, idxNivel) in rack.niveles.slice().reverse()" :key="idxNivel">
                    <div
                        class="panel rounded-2xl p-6 shadow-xl backdrop-blur-md border border-white/20
            transition-transform duration-300 ease-in-out hover:translate-z-[10px] hover:scale-[1.02] hover:shadow-2xl">

                        <div class="flex items-center gap-4 mb-6">
                            <div class="px-3 py-6 rounded-lg font-bold text-sm text-white 
                                    [writing-mode:vertical-rl] [text-orientation:mixed] 
                                    [transform:translateZ(5px)]"
                                style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                                NIVEL <span x-text="rack.niveles.length - idxNivel"></span>
                            </div>
                            <div class="flex-1 h-px bg-gradient-to-r from-purple-500 to-transparent"></div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span x-text="nivel.ubicaciones.filter(u => u.producto).length"></span>/<span
                                    x-text="nivel.ubicaciones.length"></span> ocupadas
                                <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></div>
                            </div>
                        </div>
                        <!-- Carrusel de ubicaciones -->
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                <template x-for="(ubi, idx) in nivel.ubicaciones" :key="idx">
                                    <div class="swiper-slide">
                                        <div @click="manejarClickUbicacion(ubi)"
                                            class="relative group h-32 rounded-xl flex flex-col items-center justify-center cursor-pointer
           transition-all duration-300 ease-in-out overflow-hidden
           hover:-translate-y-0.5 hover:scale-105 hover:shadow-xl"
                                            :class="[
                                                getEstadoClass(ubi.estado), // 👈 aquí le faltaba la coma
                                                modoReubicacion.activo ?
                                                (esDestinoValido(ubi) ? 'animate-pulse-valid' :
                                                    'animate-pulse-invalid') :
                                                ''
                                            ]"
                                            :style="{
                                                backgroundColor: ubi.estado === 'bajo' ? '#22c55e' : ubi
                                                    .estado === 'medio' ? '#facc15' : ubi.estado === 'alto' ?
                                                    '#f97316' : ubi.estado === 'muy_alto' ? '#ef4444' : '#888ea8'
                                            }">

                                            <!-- Simulación del ::before con un div -->
                                            <div
                                                class="absolute inset-[-2px] rounded-inherit
                bg-gradient-to-tr from-transparent via-white/20 to-transparent
                opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                                            </div>

                                            <!-- Código -->
                                            <div class="text-xs font-bold mb-2 opacity-90 text-white"
                                                x-text="ubi.codigo"></div>

                                            <!-- Icono -->
                                            <div class="text-2xl mb-2 text-white">
                                                <template x-if="ubi.producto">
                                                    <i class="fas fa-box"></i>
                                                </template>
                                                <template x-if="!ubi.producto">
                                                    <i class="fas fa-cube text-white"></i>
                                                </template>
                                            </div>

                                            <!-- Nombre -->
                                            <template x-if="ubi.producto">
                                                <div class="text-xs font-medium text-center px-2 truncate w-full text-white"
                                                    x-text="ubi.producto"></div>
                                            </template>
                                            <template x-if="!ubi.producto">
                                                <div class="text-xs text-white">Vacío</div>
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


        <!-- Modal -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="modal.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="modal.open = false">
                <div x-show="modal.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg relative">

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
                        <!-- Si tiene producto -->
                        <template x-if="modal.ubi.producto">
                            <div class="space-y-4">
                                <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Producto</label>
                                            <p class="text-lg font-bold text-gray-800" x-text="modal.ubi.producto">
                                            </p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Cantidad</label>
                                            <p class="text-lg font-bold text-gray-800"
                                                x-text="modal.ubi.cantidad + ' unidades'"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-xl">
                                    <label class="text-sm font-medium text-gray-600">Último movimiento</label>
                                    <p class="text-gray-800" x-text="formatFecha(modal.ubi.fecha)"></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3 pt-4">
                                    <button @click="iniciarReubicacion(modal.ubi)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-arrows-alt text-xs"></i>
                                        Reubicar
                                    </button>

                                    <button @click="iniciarReubicacionRack(modal.ubi)"
                                        class="bg-secondary hover:bg-purple-700 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-exchange-alt text-xs"></i>
                                        Otro Rack
                                    </button>

                                    <button
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
                        <template x-if="!modal.ubi.producto">
                            <div class="space-y-4">
                                <div class="text-center py-4">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-cube text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800 mb-2">Ubicación disponible</h3>
                                    <p class="text-gray-600">Esta posición está vacía y lista para almacenar productos
                                    </p>
                                </div>

                                <!-- Botones para ubicación vacía -->
                                <div class="grid grid-cols-2 gap-3 pt-4">
                                    <button @click="abrirHistorial(modal.ubi)"
                                        class="bg-dark hover:bg-gray-700 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-history text-xs"></i>
                                        Historial Completo
                                    </button>

                                    <button
                                        class="bg-success hover:bg-green-700 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
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

    <script src="{{ asset('assets/js/almacen/ubicaciones/detalle-rack.js') }}"></script>
</x-layout.default>
