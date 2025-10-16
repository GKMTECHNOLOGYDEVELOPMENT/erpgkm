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
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="grid grid-cols-3 gap-4 flex-1">
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

                                        <!-- ✅ NUEVO: Botón para agregar más productos -->
                                        <button @click="abrirModalAgregarProducto(modal.ubi)"
                                            class="ml-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-plus text-xs"></i>
                                            Agregar Más
                                        </button>
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

                                <!-- Lista de productos MEJORADA CON CLIENTE GENERAL PARA CUSTODIAS Y PRODUCTOS -->
                                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                                        <h3 class="font-semibold text-gray-800">Productos en esta ubicación</h3>
                                        <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">
                                            Capacidad: <span x-text="modal.ubi.cantidad_total"></span>/<span
                                                x-text="modal.ubi.capacidad"></span>
                                        </span>
                                    </div>

                                    <div class="max-h-80 overflow-y-auto custom-scrollbar p-2">
                                        <template x-for="(producto, idx) in modal.ubi.productos"
                                            :key="idx">
                                            <div
                                                class="border border-gray-200 rounded-lg mb-2 last:mb-0 bg-white hover:shadow-md transition-all duration-200">
                                                <div class="flex items-center justify-between p-4">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                                                :class="producto.custodia_id ? 'bg-red-100' : 'bg-blue-100'">
                                                                <i class="fas fa-shield"
                                                                    x-show="producto.custodia_id"></i>
                                                                <i class="fas fa-box"
                                                                    x-show="!producto.custodia_id"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <template x-if="producto.custodia_id">
                                                                    <div>
                                                                        <p class="font-semibold text-gray-800 text-sm truncate mb-2"
                                                                            x-text="producto.serie || producto.codigocustodias || 'Custodia'">
                                                                        </p>

                                                                        <!-- ✅ NUEVO: Mostrar número de ticket para custodias -->
                                                                        <div x-show="producto.numero_ticket"
                                                                            class="mb-2">
                                                                            <div class="flex items-center gap-1">
                                                                                <i
                                                                                    class="fas fa-ticket-alt text-xs text-blue-500"></i>
                                                                                <span
                                                                                    class="text-xs text-gray-600 font-medium">Ticket:</span>
                                                                                <span
                                                                                    class="text-xs text-blue-700 font-semibold"
                                                                                    x-text="producto.numero_ticket"></span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="flex gap-2 mb-2">
                                                                            <span
                                                                                class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium"
                                                                                x-text="producto.categoria || 'Custodia'"></span>
                                                                            <template x-if="producto.marca_nombre">
                                                                                <span
                                                                                    class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium"
                                                                                    x-text="producto.marca_nombre"></span>
                                                                            </template>
                                                                            <template x-if="producto.modelo_nombre">
                                                                                <span
                                                                                    class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium"
                                                                                    x-text="producto.modelo_nombre"></span>
                                                                            </template>
                                                                        </div>

                                                                        <!-- ✅ NUEVO: Mostrar Cliente General para custodias -->
                                                                        <div x-show="producto.cliente_general_nombre && producto.cliente_general_nombre !== 'Sin cliente'"
                                                                            class="mt-2">
                                                                            <div class="flex items-center gap-1">
                                                                                <i
                                                                                    class="fas fa-user-tie text-xs text-purple-500"></i>
                                                                                <span
                                                                                    class="text-xs text-gray-600 font-medium">Cliente:</span>
                                                                                <span
                                                                                    class="text-xs text-purple-700 font-semibold"
                                                                                    x-text="producto.cliente_general_nombre"></span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="mt-1 flex flex-wrap gap-2">
                                                                            <template
                                                                                x-if="producto.serie && producto.codigocustodias">
                                                                                <span class="text-xs text-gray-600">
                                                                                    <i class="fas fa-hashtag mr-1"></i>
                                                                                    Código: <span
                                                                                        x-text="producto.codigocustodias"></span>
                                                                                </span>
                                                                            </template>
                                                                            <template
                                                                                x-if="!producto.marca_nombre && producto.idMarca">
                                                                                <span class="text-xs text-gray-600">
                                                                                    <i class="fas fa-tag mr-1"></i>
                                                                                    Marca ID: <span
                                                                                        x-text="producto.idMarca"></span>
                                                                                </span>
                                                                            </template>
                                                                            <template
                                                                                x-if="!producto.modelo_nombre && producto.idModelo">
                                                                                <span class="text-xs text-gray-600">
                                                                                    <i class="fas fa-cube mr-1"></i>
                                                                                    Modelo ID: <span
                                                                                        x-text="producto.idModelo"></span>
                                                                                </span>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </template>

                                                                <template x-if="!producto.custodia_id">
                                                                    <div>
                                                                        <!-- ✅ CORREGIDO: Mostrar nombre o código según el tipo -->
                                                                        <p class="font-semibold text-gray-800 text-sm truncate mb-2"
                                                                            x-text="producto.nombre"></p>

                                                                        <!-- ✅ NUEVO: Mostrar información adicional para repuestos -->
                                                                        <template
                                                                            x-if="producto.mostrando_codigo_repuesto && producto.nombre_original">
                                                                            <p class="text-xs text-gray-500 truncate mb-1"
                                                                                x-text="'Nombre: ' + producto.nombre_original">
                                                                            </p>
                                                                        </template>

                                                                        <div class="flex gap-2 mb-2">
                                                                            <span
                                                                                class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium"
                                                                                x-text="producto.categoria || 'General'"></span>
                                                                            <span
                                                                                class="px-2 py-1 rounded text-xs font-medium"
                                                                                :class="{
                                                                                    'bg-green-100 text-green-700': producto
                                                                                        .tipo_articulo === 'PRODUCTOS',
                                                                                    'bg-yellow-100 text-warning': producto
                                                                                        .tipo_articulo === 'REPUESTOS',
                                                                                    'bg-purple-100 text-secondary': producto
                                                                                        .tipo_articulo === 'SUMINISTROS',
                                                                                    'bg-orange-100 text-danger': producto
                                                                                        .tipo_articulo === 'HERAMIENTAS',
                                                                                    'bg-gray-100 text-gray-700': ![
                                                                                        'PRODUCTOS', 'REPUESTOS',
                                                                                        'SUMINISTROS', 'HERAMIENTAS'
                                                                                    ].includes(producto
                                                                                        .tipo_articulo)
                                                                                }"
                                                                                x-text="producto.tipo_articulo || 'Sin tipo'">
                                                                            </span>
                                                                        </div>

                                                                        <!-- ✅ NUEVO: Mostrar Cliente General solo para productos normales -->
                                                                        <div x-show="producto.cliente_general_nombre && producto.cliente_general_nombre !== 'Sin cliente'"
                                                                            class="mt-2">
                                                                            <div class="flex items-center gap-1">
                                                                                <i
                                                                                    class="fas fa-user-tie text-xs text-purple-500"></i>
                                                                                <span
                                                                                    class="text-xs text-gray-600 font-medium">Cliente:</span>
                                                                                <span
                                                                                    class="text-xs text-purple-700 font-semibold"
                                                                                    x-text="producto.cliente_general_nombre"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="text-right flex items-center gap-4 ml-4">
                                                        <!-- ✅ SOLO MOSTRAR CONTROLES DE CANTIDAD PARA PRODUCTOS NORMALES -->
                                                        <template x-if="!producto.custodia_id">
                                                            <div
                                                                class="flex items-center gap-2 rounded-lg p-2 bg-gray-100">
                                                                <button @click="decrementarCantidadExistente(idx)"
                                                                    :disabled="producto.cantidad <= 1"
                                                                    class="w-7 h-7 bg-white hover:bg-gray-200 rounded flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                                    title="Decrementar">
                                                                    <i class="fas fa-minus text-gray-600 text-xs"></i>
                                                                </button>

                                                                <input type="number" x-model="producto.cantidad"
                                                                    @change="actualizarCantidadProducto(idx)"
                                                                    min="1" :max="modal.ubi.capacidad"
                                                                    class="w-14 text-center p-1 border border-gray-300 rounded text-sm font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                                    title="Cantidad">

                                                                <button @click="incrementarCantidadExistente(idx)"
                                                                    :disabled="getCantidadTotalModal() >= modal.ubi.capacidad"
                                                                    class="w-7 h-7 bg-white hover:bg-gray-200 rounded flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                                    title="Incrementar">
                                                                    <i class="fas fa-plus text-gray-600 text-xs"></i>
                                                                </button>
                                                            </div>
                                                        </template>

                                                        <!-- ✅ PARA CUSTODIAS - NO MOSTRAR NADA EN EL ESPACIO DE CONTROLES -->
                                                        <template x-if="producto.custodia_id">
                                                            <!-- No mostrar controles, espacio vacío -->
                                                        </template>

                                                        <div class="flex flex-col items-end gap-2">
                                                            <span class="block font-bold text-lg"
                                                                :class="producto.custodia_id ? 'text-red-600' : 'text-gray-800'"
                                                                x-text="producto.cantidad + ' und.'"></span>

                                                            <div class="flex gap-2">
                                                                <!-- BOTÓN MOVER - AHORA DISPONIBLE PARA CUSTODIAS -->
                                                                <button
                                                                    @click="iniciarReubicacionProducto(modal.ubi, producto)"
                                                                    :class="producto.custodia_id ?
                                                                        'bg-secondary hover:bg-purple-600' :
                                                                        'bg-primary hover:bg-blue-600'"
                                                                    class="text-xs text-white px-3 py-1.5 rounded transition-all duration-200 hover:scale-105 flex items-center gap-1"
                                                                    :title="producto.custodia_id ?
                                                                        'Mover' :
                                                                        'Mover'">
                                                                    <i class="fas fa-arrows-alt text-xs"></i>
                                                                    <span
                                                                        x-text="producto.custodia_id ? 'Mover' : 'Mover'"></span>
                                                                </button>

                                                                <button @click="eliminarProductoIndividual(idx)"
                                                                    :disabled="producto.custodia_id"
                                                                    :class="producto.custodia_id ?
                                                                        'bg-gray-400 cursor-not-allowed' :
                                                                        'bg-red-500 hover:bg-red-600'"
                                                                    class="text-xs text-white px-3 py-1.5 rounded transition-all duration-200 hover:scale-105 flex items-center gap-1"
                                                                    :title="producto.custodia_id ?
                                                                        'No se puede eliminar - En custodia' :
                                                                        'Eliminar producto'">
                                                                    <i class="fas fa-trash text-xs"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div x-show="!producto.custodia_id && producto.cantidadOriginal !== undefined && producto.cantidad !== producto.cantidadOriginal"
                                                    class="bg-yellow-50 border-t border-yellow-200 px-4 py-3 text-xs text-yellow-700 flex justify-between items-center">
                                                    <span class="font-medium">Cambios pendientes: <span
                                                            x-text="producto.cantidadOriginal"></span> → <span
                                                            x-text="producto.cantidad"></span> unidades</span>
                                                    <div class="flex gap-2">
                                                        <button @click="guardarCambiosProducto(idx)"
                                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium">
                                                            Guardar
                                                        </button>
                                                        <button @click="cancelarCambiosProducto(idx)"
                                                            class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs font-medium">
                                                            Cancelar
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- INFORMACIÓN MODIFICADA PARA CUSTODIAS -->
                                                <div x-show="producto.custodia_id"
                                                    class="bg-blue-50 border-t border-blue-200 px-4 py-3 text-xs text-blue-700 flex items-center gap-2">
                                                    <i class="fas fa-shield-alt"></i>
                                                    <span class="font-medium">Artículo en custodia - Puede ser movido
                                                        entre ubicaciones pero no modificado ni eliminado</span>
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

                                <!-- Botones de acción MEJORADOS -->
                                <div class="grid grid-cols-2 gap-3 pt-4">
                                    <button @click="iniciarReubicacionMultiple(modal.ubi)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-arrows-alt text-xs"></i>
                                        Reubicar Todo
                                    </button>

                                    <button @click="iniciarReubicacionRack(modal.ubi)"
                                        class="bg-secondary hover:bg-purple-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-exchange-alt text-xs"></i>
                                        Otro Rack
                                    </button>

                                    <button @click="abrirModalAgregarProducto(modal.ubi)"
                                        class="bg-green-500 hover:bg-green-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-plus text-xs"></i>
                                        Agregar Más
                                    </button>

                                    <button @click="vaciarUbicacion(modal.ubi)"
                                        class="bg-red-500 hover:bg-red-600 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-trash text-xs"></i>
                                        Vaciar Todo
                                    </button>

                                    <button @click="abrirHistorial(modal.ubi)"
                                        class="bg-gray-600 hover:bg-gray-700 text-white py-2.5 px-3 rounded-lg font-medium flex items-center justify-center gap-2 text-sm transition-all duration-200 hover:scale-105 col-span-2">
                                        <i class="fas fa-history text-xs"></i>
                                        Historial Completo
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
                                <template x-for="rack in modalReubicacionRack.racksDisponibles"
                                    :key="rack.id">
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

        <!-- Modal para Agregar Múltiples Productos - ACTUALIZADO CON CLIENTE GENERAL -->
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
                                        x-text="modalAgregarProducto.ubicacion.capacidad"></div>
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

                            <!-- ✅ NUEVO: Mostrar productos existentes -->
                            <div x-show="modalAgregarProducto.ubicacion.productos && modalAgregarProducto.ubicacion.productos.length > 0"
                                class="mt-3 p-2 bg-blue-50 rounded border border-blue-200">
                                <div class="text-xs text-blue-700 flex justify-between">
                                    <span>Productos existentes:</span>
                                    <span class="font-semibold"
                                        x-text="(modalAgregarProducto.ubicacion.capacidad - modalAgregarProducto.capacidadMaxima) + ' unidades'"></span>
                                </div>
                            </div>

                            <!-- Barra de progreso -->
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span>Ocupación total</span>
                                    <span x-text="getPorcentajeOcupacionTotal() + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300"
                                        :style="'width: ' + Math.min(getPorcentajeOcupacionTotal(), 100) + '%;'"
                                        :class="getPorcentajeOcupacionTotal() > 80 ? 'from-yellow-500 to-red-500' :
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
                                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm"
                                    x-show="modalAgregarProducto.productosFiltrados.length > 0">
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">
                                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                                        Artículos Disponibles
                                        <span class="text-gray-500 font-normal"
                                            x-text="'(' + modalAgregarProducto.productosFiltrados.length + ' encontrados)'"></span>
                                    </label>

                                    <!-- Virtual Scroll Container -->
                                    <div class="relative border border-gray-200 rounded-lg bg-gray-50">
                                        <div class="h-64 overflow-y-auto custom-scrollbar" x-ref="scrollContainer"
                                            @scroll.debounce="handleScroll($event)">

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

                                                                <template
                                                                    x-if="producto.mostrando_codigo_repuesto && producto.nombre_original">
                                                                    <p class="text-xs text-gray-500 truncate mb-1"
                                                                        x-text="'Nombre: ' + producto.nombre_original">
                                                                    </p>
                                                                </template>

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
                                                                            'bg-orange-100 text-danger': producto
                                                                                .tipo_articulo === 'HERAMIENTAS',
                                                                            'bg-gray-100 text-gray-700': !['PRODUCTOS',
                                                                                'REPUESTOS', 'SUMINISTROS',
                                                                                'HERAMIENTAS'
                                                                            ].includes(producto.tipo_articulo)
                                                                        }"
                                                                        x-text="producto.tipo_articulo || 'Standard'">
                                                                    </span>
                                                                </div>

                                                                <div class="mt-1">
                                                                    <span class="text-xs text-gray-500 font-medium"
                                                                        x-text="'Stock: ' + (producto.stock || 'N/A')"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <i
                                                                class="fas fa-plus text-green-500 text-lg hover:text-green-600 transition-colors"></i>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

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

                                    <!-- Lista de productos seleccionados ACTUALIZADA CON CLIENTE GENERAL -->
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
                                                            <div
                                                                class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                                                <i class="fas fa-box text-sm"></i>
                                                            </div>

                                                            <div class="flex-1 min-w-0">
                                                                <h4 class="font-semibold text-gray-800 text-sm truncate mb-1"
                                                                    x-text="producto.nombre || 'Sin nombre'"></h4>

                                                                <template x-if="producto.mostrando_codigo_repuesto">
                                                                    <p class="text-xs text-gray-500 truncate mb-2"
                                                                        x-text="'Nombre: ' + producto.nombre_original">
                                                                    </p>
                                                                </template>

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

                                                    <!-- ✅ NUEVO: Selección de Cliente General -->
                                                    <div class="mb-3">
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                                            <i class="fas fa-user-tie mr-1 text-purple-500"></i>
                                                            Cliente General *
                                                        </label>
                                                        <select x-model="producto.cliente_general_id" required
                                                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Seleccione un cliente</option>
                                                            <template
                                                                x-for="cliente in modalAgregarProducto.clientesGenerales"
                                                                :key="cliente.id">
                                                                <option :value="cliente.id"
                                                                    x-text="cliente.descripcion">
                                                                </option>
                                                            </template>
                                                        </select>
                                                        <p x-show="!producto.cliente_general_id"
                                                            class="text-xs text-red-500 mt-1">
                                                            Este campo es obligatorio
                                                        </p>
                                                    </div>

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
                                    modalAgregarProducto.capacidadMaxima || !todosClientesSeleccionados()"
                                :class="modalAgregarProducto.productosSeleccionados.length === 0 || getTotalCantidades() >
                                    modalAgregarProducto.capacidadMaxima || !todosClientesSeleccionados() ?
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
                // ========== ESTADO DE LA APLICACIÓN ==========
                rack: @json($rack),
                todosRacks: @json($todosRacks),
                rackActual: '{{ $rackActual }}',
                idxRackActual: {{ array_search($rackActual, $todosRacks) }},
                swipers: [],

                // ========== MODALES ==========
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
                modalHistorial: {
                    open: false,
                    ubi: {}
                },
                modalSeleccionRack: {
                    open: false,
                    origen: '',
                    producto: '',
                    cantidad: 0,
                    rackDestino: null
                },
                modalReubicacionRack: {
                    open: false,
                    ubicacionOrigen: {},
                    racksDisponibles: [],
                    rackDestinoSeleccionado: '',
                    ubicacionDestinoSeleccionada: '',
                    ubicacionesDestino: []
                },
                modalAgregarProducto: {
                    open: false,
                    ubicacion: {},
                    productos: [],
                    productosSeleccionados: [],
                    productosFiltrados: [],
                    clientesGenerales: [],
                    productoSeleccionado: '',
                    busqueda: '',
                    cantidad: 1,
                    capacidadMaxima: 0,
                    observaciones: '',
                    virtualScroll: {
                        visibleItems: [],
                        startOffset: 0,
                        endOffset: 0,
                        itemHeight: 80,
                        visibleCount: 8,
                        buffer: 3,
                        loading: false,
                        searchTimeout: null
                    }
                },

                // ========== MODO REUBICACIÓN ==========
                modoReubicacion: {
                    activo: false,
                    origen: '',
                    producto: '',
                    rackOrigen: null,
                    ubicacionOrigenId: null,
                    productoId: null,
                    cantidad: 0,
                    tipo: '',
                    esCustodia: false
                },

                // ========== INICIALIZACIÓN ==========
                init() {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: '3000',
                    };

                    this.procesarDatosRack();
                    this.initSwipers();
                },

                // ========== MÉTODOS DE INICIALIZACIÓN ==========
                procesarDatosRack() {
                    console.log('Reprocesando datos del rack...');

                    this.rack.niveles.forEach(nivel => {
                        nivel.ubicaciones.forEach(ubicacion => {
                            if (!ubicacion.productos) ubicacion.productos = [];
                            if (!ubicacion.cantidad_total) ubicacion.cantidad_total = 0;

                            if (ubicacion.productos && ubicacion.productos.length > 0) {
                                // Acumular categorías únicas
                                const categoriasUnicas = [...new Set(ubicacion.productos
                                    .map(p => {
                                        if (p.custodia_id) {
                                            return p.categoria_custodia || 'Custodia';
                                        }
                                        return p.categoria || 'Sin categoría';
                                    })
                                    .filter(c => c && c !== 'Sin categoría')
                                )];

                                // Acumular tipos únicos
                                const tiposUnicos = [...new Set(ubicacion.productos
                                    .map(p => p.custodia_id ? 'CUSTODIA' : (p.tipo_articulo ||
                                        'Sin tipo'))
                                    .filter(t => t && t !== 'Sin tipo')
                                )];

                                // Acumular clientes únicos
                                const clientesUnicos = [...new Set(ubicacion.productos
                                    .filter(p => p.cliente_general_nombre && p
                                        .cliente_general_nombre !== 'Sin cliente')
                                    .map(p => p.cliente_general_nombre)
                                )];

                                // Asignar propiedades acumuladas
                                ubicacion.categorias_acumuladas = categoriasUnicas.length > 0 ?
                                    categoriasUnicas.join(', ') : 'Sin categoría';
                                ubicacion.tipos_acumulados = tiposUnicos.length > 0 ?
                                    tiposUnicos.join(', ') : 'Sin tipo';
                                ubicacion.clientes_acumulados = clientesUnicos.length > 0 ?
                                    clientesUnicos.join(', ') : 'Sin cliente';
                                ubicacion.cantidad_total = ubicacion.productos.reduce((sum, p) => sum + (p
                                    .cantidad || 0), 0);
                                ubicacion.estado = this.calcularEstado(ubicacion.cantidad_total, ubicacion
                                    .capacidad);

                            } else {
                                // Ubicación vacía
                                ubicacion.categorias_acumuladas = 'Sin categoría';
                                ubicacion.tipos_acumulados = 'Sin tipo';
                                ubicacion.clientes_acumulados = 'Sin cliente';
                                ubicacion.cantidad_total = 0;
                                ubicacion.estado = 'vacio';
                            }
                        });
                    });

                    console.log('Reprocesamiento de datos completado');
                },

                initSwipers() {
                    this.$nextTick(() => {
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

                // ========== MÉTODOS DE INTERACCIÓN CON UBICACIONES ==========
                manejarClickUbicacion(ubi) {
                    if (this.modoReubicacion.activo) {
                        if (ubi.codigo === this.modoReubicacion.origen) {
                            this.cancelarReubicacion();
                        } else if (this.esDestinoValido(ubi)) {
                            if (!this.modoReubicacion.producto) {
                                this.error('No se ha seleccionado un producto para reubicar');
                                this.cancelarReubicacion();
                                return;
                            }

                            const ubicacionOrigenActual = this.buscarUbicacionPorCodigo(this.modoReubicacion.origen);
                            if (!ubicacionOrigenActual) {
                                this.error('No se pudo encontrar la ubicación origen');
                                this.cancelarReubicacion();
                                return;
                            }

                            this.modalReubicacion.origen = this.modoReubicacion.origen;
                            this.modalReubicacion.destino = ubi.codigo;
                            this.modalReubicacion.producto = this.modoReubicacion.producto;
                            this.modalReubicacion.cantidad = ubicacionOrigenActual.cantidad_total || this.modoReubicacion
                                .cantidad;
                            this.modalReubicacion.open = true;
                        }
                    } else {
                        this.verDetalle(ubi);
                    }
                },

                verDetalle(ubi) {
                    this.modal.ubi = ubi;
                    this.modal.open = true;
                    this.prepararEdicionProductos();
                },

                abrirHistorial(ubi) {
                    this.modalHistorial.ubi = ubi;
                    this.modalHistorial.open = true;
                },

                // ========== MÉTODOS DE REUBICACIÓN ==========
                iniciarReubicacionProducto(ubi, producto) {
                    if (!producto) {
                        this.error('No se puede reubicar: producto no válido');
                        return;
                    }

                    console.log('🔍 DEBUG - Producto completo:', producto);

                    let nombreProducto = producto.nombre;
                    if (producto.custodia_id) {
                        nombreProducto = producto.serie || producto.codigocustodias || 'Custodia ' + producto.custodia_id;
                    }

                    if (!nombreProducto) {
                        this.error('No se puede reubicar: nombre de producto no válido');
                        return;
                    }

                    this.modoReubicacion.activo = true;
                    this.modoReubicacion.origen = ubi.codigo;
                    this.modoReubicacion.producto = nombreProducto.toString().trim();
                    this.modoReubicacion.ubicacionOrigenId = ubi.id;
                    this.modoReubicacion.productoId = producto.custodia_id ? producto.custodia_id : producto.id;
                    this.modoReubicacion.cantidad = producto.custodia_id ? 1 : producto.cantidad;
                    this.modoReubicacion.esCustodia = !!producto.custodia_id;
                    this.modoReubicacion.tipo = 'producto_especifico';
                    this.modal.open = false;

                    console.log('✅ Datos configurados para reubicación:', {
                        productoId: this.modoReubicacion.productoId,
                        esCustodia: this.modoReubicacion.esCustodia,
                        custodiaId: producto.custodia_id,
                        cantidad: this.modoReubicacion.cantidad
                    });

                    const mensaje = producto.custodia_id ?
                        'Modo reubicación activado para custodia: ' + nombreProducto :
                        'Modo reubicación activado para: ' + nombreProducto;

                    this.success(mensaje);
                },

                iniciarReubicacionMultiple(ubi) {
                    if (!ubi.productos || ubi.productos.length === 0) {
                        this.error('No hay productos para reubicar');
                        return;
                    }

                    this.modoReubicacion.activo = true;
                    this.modoReubicacion.origen = ubi.codigo;
                    this.modoReubicacion.producto = 'Todos los productos';
                    this.modoReubicacion.ubicacionOrigenId = ubi.id;
                    this.modoReubicacion.cantidad = ubi.cantidad_total;
                    this.modoReubicacion.tipo = 'todos_los_productos';
                    this.modal.open = false;
                    this.success('Modo reubicación activado para todos los productos');
                },

                esDestinoValido(ubi) {
                    if (ubi.codigo === this.modoReubicacion.origen) {
                        return false;
                    }

                    if (this.modoReubicacion.esCustodia) {
                        return (!ubi.productos || ubi.productos.length === 0);
                    }

                    if (this.modoReubicacion.tipo === 'producto_especifico') {
                        const productoExistente = ubi.productos?.find(p => p.id === this.modoReubicacion.productoId);
                        return !productoExistente;
                    }

                    return (!ubi.productos || ubi.productos.length === 0);
                },

                async confirmarReubicacion() {
                    try {
                        if (!this.modalReubicacion.producto || this.modalReubicacion.producto.trim() === '') {
                            this.error('El producto no está definido. Por favor, cancela y reinicia la reubicación.');
                            return;
                        }

                        const ubicacionDestino = this.buscarUbicacionPorCodigo(this.modalReubicacion.destino);
                        if (!ubicacionDestino) {
                            this.error('Ubicación destino no encontrada');
                            return;
                        }

                        const cantidad = parseInt(this.modalReubicacion.cantidad);
                        if (cantidad <= 0 || isNaN(cantidad)) {
                            this.error('Cantidad inválida para reubicación');
                            return;
                        }

                        const payload = {
                            ubicacion_origen_id: Number(this.modoReubicacion.ubicacionOrigenId),
                            ubicacion_destino_id: ubicacionDestino.id,
                            producto: this.modalReubicacion.producto.toString().trim(),
                            cantidad: cantidad,
                            tipo_reubicacion: 'mismo_rack',
                            es_custodia: this.modoReubicacion.esCustodia || false,
                            custodia_id: this.modoReubicacion.esCustodia ? Number(this.modoReubicacion.productoId) :
                                null
                        };

                        console.log('📤 PAYLOAD enviado al backend:', payload);

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
                        console.log('Respuesta confirmación:', result);

                        if (result.success) {
                            this.success(result.message);

                            // ✅ NUEVO: Recargar datos completos del rack
                            await this.recargarDatosRackCompletos();

                            this.cancelarReubicacion();
                            this.modalReubicacion.open = false;

                        } else {
                            let errorMessage = result.message || 'Error al confirmar reubicación';
                            if (result.errors) {
                                console.error('Errores de validación:', result.errors);
                                if (result.errors.producto) {
                                    errorMessage = result.errors.producto[0];
                                } else if (result.errors.cantidad) {
                                    errorMessage = result.errors.cantidad[0];
                                } else {
                                    Object.values(result.errors).forEach(errorArray => {
                                        errorArray.forEach(error => {
                                            this.error(error);
                                        });
                                    });
                                    return;
                                }
                            }
                            this.error(errorMessage);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },
                // ✅ NUEVO MÉTODO: Recargar datos completos del rack
                // En tu Alpine.js - MÉTODO MEJORADO
                async recargarDatosRackCompletos() {
                    try {
                        console.log('🔄 Recargando datos del rack...');

                        const rackNombre = this.rack.nombre;
                        const sede = this.rack.sede;

                        const response = await fetch(
                            `/almacen/racks/${rackNombre}/datos-actualizados?sede=${encodeURIComponent(sede)}`);

                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success && result.data) {
                            // Actualizar el rack completo con los nuevos datos
                            this.rack = result.data;

                            // Reprocesar datos locales
                            this.procesarDatosRack();

                            // Re-inicializar swipers
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    this.initSwipers();
                                }, 300);
                            });

                            console.log('✅ Datos del rack recargados exitosamente', {
                                niveles: this.rack.niveles.length,
                                ubicaciones: this.rack.niveles.reduce((total, nivel) => total + nivel
                                    .ubicaciones.length, 0)
                            });

                            this.success('Datos actualizados correctamente');

                        } else {
                            throw new Error(result.message || 'Error en la respuesta del servidor');
                        }

                    } catch (error) {
                        console.error('❌ Error recargando datos:', error);
                        this.error('Error al actualizar datos. Recargando página...');

                        // Fallback: recargar página después de 2 segundos
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
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
                        this.modoReubicacion.tipo = '';
                        this.modoReubicacion.esCustodia = false;
                        this.modoReubicacion.productoId = null;

                        this.modalReubicacion.open = false;
                        this.modalReubicacion.origen = '';
                        this.modalReubicacion.destino = '';
                        this.modalReubicacion.producto = '';
                        this.modalReubicacion.cantidad = 0;

                        this.info('Reubicación cancelada');
                    } catch (error) {
                        console.error('Error:', error);
                        this.modoReubicacion.activo = false;
                        this.modalReubicacion.open = false;
                    }
                },

                // ========== MÉTODOS DE GESTIÓN DE PRODUCTOS ==========
                prepararEdicionProductos() {
                    if (this.modal.ubi && this.modal.ubi.productos) {
                        this.modal.ubi.productos.forEach(producto => {
                            producto.cantidadOriginal = producto.cantidad;
                        });
                    }
                },

                getCantidadTotalModal() {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return 0;
                    return this.modal.ubi.productos.reduce((sum, p) => sum + (parseInt(p.cantidad) || 0), 0);
                },

                incrementarCantidadExistente(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];
                    const totalActual = this.getCantidadTotalModal();

                    if (totalActual < this.modal.ubi.capacidad) {
                        producto.cantidad = parseInt(producto.cantidad) + 1;
                    } else {
                        this.warning('No se puede exceder la capacidad máxima de la ubicación');
                    }
                },

                decrementarCantidadExistente(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];
                    if (producto.cantidad > 1) {
                        producto.cantidad = parseInt(producto.cantidad) - 1;
                    }
                },

                actualizarCantidadProducto(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];
                    const cantidad = parseInt(producto.cantidad);

                    if (isNaN(cantidad) || cantidad < 1) {
                        producto.cantidad = 1;
                        return;
                    }

                    const total = this.getCantidadTotalModal();
                    if (total > this.modal.ubi.capacidad) {
                        const exceso = total - this.modal.ubi.capacidad;
                        producto.cantidad = Math.max(1, cantidad - exceso);
                        this.warning('Se ajustó la cantidad para no exceder la capacidad máxima');
                    }
                },

                async guardarCambiosProducto(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];

                    try {
                        const payload = {
                            ubicacion_id: this.modal.ubi.id,
                            articulo_id: producto.id,
                            cantidad: parseInt(producto.cantidad),
                            accion: 'actualizar'
                        };

                        console.log('Actualizando producto:', payload);

                        const response = await fetch('/almacen/ubicaciones/actualizar-producto', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.success(`Cantidad de ${producto.nombre} actualizada exitosamente`);
                            producto.cantidadOriginal = producto.cantidad;
                            this.actualizarInterfazDespuesCambio(this.modal.ubi.id);
                        } else {
                            this.error(`Error al actualizar ${producto.nombre}: ${result.message}`);
                            producto.cantidad = producto.cantidadOriginal;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                        producto.cantidad = producto.cantidadOriginal;
                    }
                },

                cancelarCambiosProducto(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];
                    producto.cantidad = producto.cantidadOriginal;
                    delete producto.cantidadOriginal;
                },

                async eliminarProductoIndividual(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];

                    if (!confirm(`¿Está seguro de que desea eliminar ${producto.nombre} de esta ubicación?`)) {
                        return;
                    }

                    try {
                        const payload = {
                            ubicacion_id: this.modal.ubi.id,
                            articulo_id: producto.id,
                            accion: 'eliminar'
                        };

                        console.log('Eliminando producto:', payload);

                        const response = await fetch('/almacen/ubicaciones/eliminar-producto', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.success(`${producto.nombre} eliminado exitosamente`);
                            this.modal.ubi.productos.splice(index, 1);
                            this.actualizarInterfazDespuesCambio(this.modal.ubi.id);

                            if (this.modal.ubi.productos.length === 0) {
                                this.modal.open = false;
                            }
                        } else {
                            this.error(`Error al eliminar ${producto.nombre}: ${result.message}`);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

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

                // ========== MÉTODOS DE AGREGAR PRODUCTOS ==========
                async abrirModalAgregarProducto(ubi) {
                    try {
                        this.modalAgregarProducto.ubicacion = ubi;

                        const productosExistentes = ubi.productos ?
                            ubi.productos.reduce((total, prod) => total + (prod.cantidad || 0), 0) : 0;

                        this.modalAgregarProducto.capacidadMaxima = ubi.capacidad - productosExistentes;

                        this.modalAgregarProducto.productosSeleccionados = [];
                        this.modalAgregarProducto.productosFiltrados = [];
                        this.modalAgregarProducto.busqueda = '';
                        this.modalAgregarProducto.productoSeleccionado = '';
                        this.modalAgregarProducto.observaciones = '';

                        this.modalAgregarProducto.virtualScroll = {
                            visibleItems: [],
                            startOffset: 0,
                            endOffset: 0,
                            itemHeight: 72,
                            visibleCount: 10,
                            loading: true,
                            searchTimeout: null
                        };

                        console.log('Cargando productos y clientes...');

                        const [productosResponse, clientesResponse] = await Promise.all([
                            fetch('/almacen/productos/listar', {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            }),
                            fetch('/almacen/clientes-generales/listar', {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            })
                        ]);

                        if (!productosResponse.ok || !clientesResponse.ok) {
                            throw new Error('Error al cargar datos');
                        }

                        const productosResult = await productosResponse.json();
                        const clientesResult = await clientesResponse.json();

                        if (productosResult.success) {
                            this.modalAgregarProducto.productos = productosResult.data;
                            this.modalAgregarProducto.productosFiltrados = productosResult.data;
                        } else {
                            this.error(productosResult.message || 'Error al cargar productos');
                            return;
                        }

                        if (clientesResult.success) {
                            this.modalAgregarProducto.clientesGenerales = clientesResult.data;
                        } else {
                            this.error(clientesResult.message || 'Error al cargar clientes generales');
                            return;
                        }

                        this.initVirtualScroll();
                        console.log('Productos cargados:', productosResult.data.length);
                        console.log('Clientes cargados:', clientesResult.data.length);

                        this.modalAgregarProducto.open = true;
                        this.modal.open = false;
                        this.modalAgregarProducto.virtualScroll.loading = false;

                    } catch (error) {
                        console.error('Error al cargar datos:', error);
                        this.error('Error al cargar los datos: ' + error.message);
                        this.modalAgregarProducto.virtualScroll.loading = false;
                    }
                },

                cerrarModalAgregarProducto() {
                    this.modalAgregarProducto.open = false;
                    this.modalAgregarProducto.ubicacion = {};
                    this.modalAgregarProducto.productos = [];
                    this.modalAgregarProducto.productosSeleccionados = [];
                    this.modalAgregarProducto.productosFiltrados = [];
                    this.modalAgregarProducto.busqueda = '';
                    this.modalAgregarProducto.productoSeleccionado = '';
                    this.modalAgregarProducto.cantidad = 1;
                    this.modalAgregarProducto.observaciones = '';
                },

                agregarProductoDesdeLista(producto) {
                    if (!this.modalAgregarProducto.productosSeleccionados.some(p => p.id === producto.id)) {
                        this.modalAgregarProducto.productosSeleccionados.push({
                            ...producto,
                            cantidad: 1,
                            cliente_general_id: ''
                        });
                        this.filtrarProductos();
                    }
                },

                filtrarProductos() {
                    if (this.modalAgregarProducto.virtualScroll.searchTimeout) {
                        clearTimeout(this.modalAgregarProducto.virtualScroll.searchTimeout);
                    }

                    if (this.modalAgregarProducto.productos.length > 1000) {
                        this.modalAgregarProducto.virtualScroll.loading = true;
                    }

                    this.modalAgregarProducto.virtualScroll.searchTimeout = setTimeout(() => {
                        const busqueda = this.modalAgregarProducto.busqueda.toLowerCase().trim();

                        if (!busqueda) {
                            this.modalAgregarProducto.productosFiltrados = this.modalAgregarProducto.productos;
                        } else {
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

                        this.initVirtualScroll();
                        this.modalAgregarProducto.virtualScroll.loading = false;

                    }, 300);
                },

                initVirtualScroll() {
                    this.$nextTick(() => {
                        const virtualScroll = this.modalAgregarProducto.virtualScroll;
                        const allItems = this.modalAgregarProducto.productosFiltrados;

                        const visibleWithBuffer = virtualScroll.visibleCount + (virtualScroll.buffer * 2);

                        virtualScroll.startOffset = 0;
                        virtualScroll.endOffset = Math.max(0, (allItems.length - virtualScroll.visibleCount) *
                            virtualScroll.itemHeight);

                        virtualScroll.visibleItems = allItems.slice(0, Math.min(visibleWithBuffer, allItems
                        .length));

                        if (this.$refs.scrollContainer) {
                            this.$refs.scrollContainer.scrollTop = 0;
                        }
                    });
                },

                handleScroll(event) {
                    const scrollContainer = event.target;
                    const scrollTop = scrollContainer.scrollTop;
                    const virtualScroll = this.modalAgregarProducto.virtualScroll;
                    const allItems = this.modalAgregarProducto.productosFiltrados;

                    if (allItems.length === 0) return;

                    const startIndex = Math.max(0, Math.floor(scrollTop / virtualScroll.itemHeight) - virtualScroll.buffer);
                    const visibleWithBuffer = virtualScroll.visibleCount + (virtualScroll.buffer * 2);
                    const endIndex = Math.min(startIndex + visibleWithBuffer, allItems.length);

                    virtualScroll.visibleItems = allItems.slice(startIndex, endIndex);
                    virtualScroll.startOffset = startIndex * virtualScroll.itemHeight;
                    virtualScroll.endOffset = Math.max(0, (allItems.length - endIndex) * virtualScroll.itemHeight);
                },

                incrementarCantidad(index) {
                    const producto = this.modalAgregarProducto.productosSeleccionados[index];
                    if (this.getTotalCantidades() < this.modalAgregarProducto.capacidadMaxima) {
                        producto.cantidad++;
                    }
                },

                decrementarCantidad(index) {
                    const producto = this.modalAgregarProducto.productosSeleccionados[index];
                    if (producto.cantidad > 1) {
                        producto.cantidad--;
                    }
                },

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

                removerProductoSeleccionado(index) {
                    this.modalAgregarProducto.productosSeleccionados.splice(index, 1);
                },

                limpiarSeleccion() {
                    if (this.modalAgregarProducto.productosSeleccionados.length > 0) {
                        if (confirm('¿Está seguro de que desea limpiar todos los productos seleccionados?')) {
                            this.modalAgregarProducto.productosSeleccionados = [];
                        }
                    }
                },

                getTotalCantidades() {
                    return this.modalAgregarProducto.productosSeleccionados.reduce((sum, p) => {
                        return sum + (parseInt(p.cantidad) || 0);
                    }, 0);
                },

                getCapacidadDisponible() {
                    const totalSeleccionado = this.getTotalCantidades();
                    return this.modalAgregarProducto.capacidadMaxima - totalSeleccionado;
                },

                getPorcentajeOcupacionTotal() {
                    const capacidadTotal = this.modalAgregarProducto.ubicacion.capacidad;
                    const productosExistentes = capacidadTotal - this.modalAgregarProducto.capacidadMaxima;
                    const productosNuevos = this.getTotalCantidades();
                    const ocupacionTotal = productosExistentes + productosNuevos;

                    return Math.round((ocupacionTotal / capacidadTotal) * 100);
                },

                todosClientesSeleccionados() {
                    if (this.modalAgregarProducto.productosSeleccionados.length === 0) return false;

                    return this.modalAgregarProducto.productosSeleccionados.every(
                        producto => producto.cliente_general_id && producto.cliente_general_id !== ''
                    );
                },

                async confirmarAgregarProducto() {
                    try {
                        if (this.modalAgregarProducto.productosSeleccionados.length === 0) {
                            this.error('Por favor seleccione al menos un producto');
                            return;
                        }

                        const productosSinCliente = this.modalAgregarProducto.productosSeleccionados.filter(
                            p => !p.cliente_general_id
                        );

                        if (productosSinCliente.length > 0) {
                            this.error('Todos los productos deben tener un cliente general asignado');
                            return;
                        }

                        const totalCantidades = this.getTotalCantidades();
                        if (totalCantidades > this.modalAgregarProducto.capacidadMaxima) {
                            this.error(
                                `La cantidad total (${totalCantidades}) no puede superar la capacidad máxima de ${this.modalAgregarProducto.capacidadMaxima} unidades`
                            );
                            return;
                        }

                        for (let producto of this.modalAgregarProducto.productosSeleccionados) {
                            const cantidad = parseInt(producto.cantidad);
                            if (isNaN(cantidad) || cantidad <= 0) {
                                this.error(`La cantidad para ${producto.nombre} debe ser mayor a 0`);
                                return;
                            }

                            if (producto.stock && cantidad > producto.stock) {
                                this.error(
                                    `La cantidad para ${producto.nombre} (${cantidad}) excede el stock disponible (${producto.stock})`
                                );
                                return;
                            }
                        }

                        console.log('Preparando para agregar productos con clientes:', {
                            ubicacion_id: this.modalAgregarProducto.ubicacion.id,
                            productos_count: this.modalAgregarProducto.productosSeleccionados.length,
                            total_cantidad: totalCantidades
                        });

                        const promises = this.modalAgregarProducto.productosSeleccionados.map(async (producto) => {
                            const payload = {
                                ubicacion_id: this.modalAgregarProducto.ubicacion.id,
                                articulo_id: producto.id,
                                cantidad: parseInt(producto.cantidad),
                                cliente_general_id: producto.cliente_general_id,
                                observaciones: this.modalAgregarProducto.observaciones,
                                tipo_ingreso: 'ajuste'
                            };

                            console.log('Enviando producto con cliente:', payload);

                            const response = await fetch('/almacen/ubicaciones/agregar-producto', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(payload)
                            });

                            return await response.json();
                        });

                        const results = await Promise.all(promises);
                        const allSuccess = results.every(result => result.success);

                        if (allSuccess) {
                            this.success(
                                `✅ ${this.modalAgregarProducto.productosSeleccionados.length} producto(s) agregado(s) exitosamente`
                            );

                            this.modalAgregarProducto.productosSeleccionados.forEach(producto => {
                                this.actualizarInterfazDespuesAgregarProducto(
                                    this.modalAgregarProducto.ubicacion.id,
                                    producto,
                                    parseInt(producto.cantidad)
                                );
                            });

                            this.cerrarModalAgregarProducto();
                        } else {
                            const errorMessages = results
                                .filter(result => !result.success)
                                .map(result => result.message)
                                .join(', ');

                            this.error(`Error al agregar algunos productos: ${errorMessages}`);
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

                // ========== MÉTODOS DE ACTUALIZACIÓN DE INTERFAZ ==========
                actualizarInterfazDespuesAgregarProducto(ubicacionId, producto, cantidad) {
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionId) {
                                const productosActuales = ubi.productos || [];

                                const clienteGeneral = this.modalAgregarProducto.clientesGenerales.find(
                                    cliente => cliente.id == producto.cliente_general_id
                                );

                                const nuevoProducto = {
                                    id: producto.id,
                                    nombre: producto.nombre,
                                    categoria: producto.categoria || 'Sin categoría',
                                    tipo_articulo: producto.tipo_articulo || 'Sin tipo',
                                    cantidad: cantidad,
                                    cliente_general_id: producto.cliente_general_id,
                                    cliente_general_nombre: clienteGeneral ? clienteGeneral
                                        .descripcion : 'Cliente no encontrado',
                                    nombre_original: producto.nombre_original,
                                    codigo_repuesto: producto.codigo_repuesto,
                                    es_repuesto: producto.es_repuesto,
                                    mostrando_codigo_repuesto: producto.mostrando_codigo_repuesto
                                };

                                const productoExistenteIndex = productosActuales.findIndex(p => p.id ===
                                    producto.id);

                                if (productoExistenteIndex >= 0) {
                                    productosActuales[productoExistenteIndex].cantidad += cantidad;
                                    if (producto.cliente_general_id) {
                                        productosActuales[productoExistenteIndex].cliente_general_id =
                                            producto.cliente_general_id;
                                        productosActuales[productoExistenteIndex].cliente_general_nombre =
                                            clienteGeneral ? clienteGeneral.descripcion :
                                            'Cliente no encontrado';
                                    }
                                } else {
                                    productosActuales.push(nuevoProducto);
                                }

                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubi,
                                    productos: productosActuales,
                                    producto: productosActuales.length > 0 ? productosActuales[0]
                                        .nombre : null,
                                    cantidad: productosActuales.reduce((sum, p) => sum + p.cantidad, 0),
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

                    this.procesarDatosRack();
                    this.rack = {
                        ...this.rack
                    };
                    this.$nextTick(() => {
                        this.initSwipers();
                    });
                },

                actualizarInterfazDespuesCambio(ubicacionId) {
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionId) {
                                ubi.cantidad_total = ubi.productos.reduce((sum, p) => sum + p.cantidad, 0);
                                ubi.estado = this.calcularEstado(ubi.cantidad_total, ubi.capacidad);
                                ubi.fecha = new Date().toISOString();
                                this.procesarDatosRack();
                            }
                        });
                    });

                    this.rack = {
                        ...this.rack
                    };
                    this.$nextTick(() => {
                        this.initSwipers();
                    });
                },

                actualizarInterfazDespuesVaciar(ubicacionId) {
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionId) {
                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubi,
                                    productos: [],
                                    producto: null,
                                    cantidad: 0,
                                    cantidad_total: 0,
                                    categorias_acumuladas: 'Sin categoría',
                                    tipos_acumulados: 'Sin tipo',
                                    estado: 'vacio',
                                    fecha: null
                                };
                            }
                        });
                    });

                    this.rack = {
                        ...this.rack
                    };
                    this.procesarDatosRack();
                    this.$nextTick(() => {
                        this.initSwipers();
                    });
                },

                actualizarUbicacionesEnFrontend(ubicacionesActualizadas) {
                    console.log('🔄 Actualizando frontend con:', ubicacionesActualizadas);

                    const {
                        origen,
                        destino
                    } = ubicacionesActualizadas;

                    if (origen) {
                        console.log('Actualizando origen:', origen);
                        this.actualizarUbicacionIndividual(origen.id, origen);
                    }

                    if (destino) {
                        console.log('Actualizando destino:', destino);
                        this.actualizarUbicacionIndividual(destino.id, destino);
                    }

                    // Reprocesar datos para estadísticas
                    this.procesarDatosRack();

                    // Forzar actualización de Alpine
                    this.rack = {
                        ...this.rack
                    };

                    // Re-inicializar swipers
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.initSwipers();
                        }, 200);
                    });

                    console.log('✅ Frontend actualizado');
                },
                actualizarUbicacionIndividual(ubicacionId, nuevosDatos) {
                    let ubicacionEncontrada = false;

                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubicacion, ubiIndex) => {
                            if (ubicacion.id === ubicacionId) {
                                ubicacionEncontrada = true;

                                // Actualizar todos los datos de la ubicación
                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubicacion,
                                    productos: nuevosDatos.productos || [],
                                    cantidad_total: nuevosDatos.cantidad_total || 0,
                                    categorias_acumuladas: nuevosDatos.categorias_acumuladas ||
                                        'Sin categoría',
                                    tipos_acumulados: nuevosDatos.tipos_acumulados || 'Sin tipo',
                                    clientes_acumulados: nuevosDatos.clientes_acumulados ||
                                        'Sin cliente',
                                    estado: nuevosDatos.estado || 'vacio',
                                    fecha: nuevosDatos.fecha || null
                                };

                                console.log(`✅ Ubicación ${ubicacionId} actualizada en frontend`);
                            }
                        });
                    });

                    if (!ubicacionEncontrada) {
                        console.warn(`❌ Ubicación ${ubicacionId} no encontrada en el rack`);
                    }
                },

                actualizarUbicacionEnRack(ubicacionActualizada) {
                    let encontrada = false;

                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionActualizada.id) {
                                encontrada = true;
                                console.log(
                                    `Encontrada ubicación ${ubicacionActualizada.id} en nivel ${nivelIndex}, ubicación ${ubiIndex}`
                                    );

                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubicacionActualizada,
                                    productos: ubicacionActualizada.productos || [],
                                    cantidad_total: ubicacionActualizada.cantidad_total || 0,
                                    categorias_acumuladas: ubicacionActualizada.categorias_acumuladas ||
                                        'Sin categoría',
                                    tipos_acumulados: ubicacionActualizada.tipos_acumulados ||
                                        'Sin tipo',
                                    clientes_acumulados: ubicacionActualizada.clientes_acumulados ||
                                        'Sin cliente',
                                    estado: ubicacionActualizada.estado || 'vacio',
                                    fecha: ubicacionActualizada.fecha || null
                                };

                                console.log('Ubicación actualizada:', this.rack.niveles[nivelIndex]
                                    .ubicaciones[ubiIndex]);
                            }
                        });
                    });

                    if (!encontrada) {
                        console.warn(`No se encontró la ubicación ${ubicacionActualizada.id} en el rack`);
                    }
                },

                marcarUbicacionComoVacia(ubicacionId) {
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionId) {
                                console.log(`Marcando ubicación ${ubicacionId} como vacía`);

                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubi,
                                    productos: [],
                                    producto: null,
                                    cantidad: 0,
                                    cantidad_total: 0,
                                    categorias_acumuladas: 'Sin categoría',
                                    tipos_acumulados: 'Sin tipo',
                                    clientes_acumulados: 'Sin cliente',
                                    estado: 'vacio',
                                    fecha: null
                                };
                            }
                        });
                    });
                },

                // ========== MÉTODOS DE NAVEGACIÓN Y UTILIDAD ==========
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

                getStats() {
                    const todasUbicaciones = this.rack.niveles.flatMap((n) => n.ubicaciones);
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

                calcularEstado(cantidad, capacidad) {
                    if (capacidad <= 0) return 'vacio';

                    const porcentaje = (cantidad / capacidad) * 100;

                    if (porcentaje == 0) return 'vacio';
                    if (porcentaje <= 24) return 'bajo';
                    if (porcentaje <= 49) return 'medio';
                    if (porcentaje <= 74) return 'alto';
                    return 'muy_alto';
                },

                formatFecha(fecha) {
                    if (!fecha) return 'Sin registros';
                    return new Date(fecha).toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                    });
                },

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

                // ========== MÉTODOS TOASTR ==========
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
            };
        }
    </script>
</x-layout.default>
