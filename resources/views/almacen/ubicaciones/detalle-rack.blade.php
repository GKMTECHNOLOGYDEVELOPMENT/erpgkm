<x-layout.default title="Rack Spark - {{ $rack['nombre'] }} - ERP Solutions Force">
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

        /* Estilos para Select2 en modales */
        .select2-container--bootstrap4 .select2-selection--single {
            height: 48px !important;
            padding: 10px 12px;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            background-color: white !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
            color: #374151 !important;
            font-size: 0.875rem !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }

        .select2-container--bootstrap4 .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        /* Para modo oscuro */
        .dark .select2-container--bootstrap4 .select2-selection--single {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
            color: #f9fafb !important;
        }

        .dark .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #f9fafb !important;
        }

        .dark .select2-container--bootstrap4 .select2-dropdown {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
        }

        .dark .select2-container--bootstrap4 .select2-results__option {
            color: #f9fafb !important;
        }

        .dark .select2-container--bootstrap4 .select2-results__option:hover {
            background-color: #374151 !important;
        }
    </style>

    <div x-data="rackDetalle()" x-init="init()" class="min-h-screen flex flex-col">
        <!-- Header Mejorado -->
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
                            Rack Spark <span x-text="rack.nombre" class="text-success">{{ $rack['nombre'] }}</span>
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
                                                
                                                    'bg-success': modal.ubi.estado === 'bajo',
                                                    'bg-warning': modal.ubi.estado === 'medio',
                                                    'bg-secondary': modal.ubi.estado === 'alto',
                                                    'bg-danger': modal.ubi.estado === 'muy_alto',
                                                
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
                                        <i class="fas fa-boxes text-xs"></i>
                                        Reubicar Todo
                                    </button>

                                    <button @click="abrirModalReubicacionRack(modal.ubi)"
                                        class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white py-2.5 px-4 rounded-lg font-semibold transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                        <i class="fas fa-exchange-alt"></i>
                                        Mover a Otro Rack
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


        <!-- Modal para Reubicación entre Racks - CON OPCIÓN SELECCIONAR TODOS -->
        <div x-show="modalReubicacionRack.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalReubicacionRack.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModalReubicacionRack()">
                <div x-show="modalReubicacionRack.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-4xl my-8 bg-white dark:bg-[#1b2e4b]">

                    <!-- Header -->
                    <div
                        class="flex bg-gradient-to-r from-blue-500 to-purple-600 items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-lg text-white"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-lg text-white">Reubicar a Otro Rack</h5>
                                <p class="text-blue-100 text-xs">Seleccionar artículos para mover</p>
                            </div>
                        </div>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="cerrarModalReubicacionRack()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Body - 2 Columnas -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            <!-- COLUMNA IZQUIERDA - INFORMACIÓN Y ARTÍCULOS -->
                            <div class="space-y-6">

                                <!-- Información de la Ubicación Origen -->
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg border border-blue-200 dark:border-blue-700 p-4">
                                    <h3
                                        class="font-semibold text-gray-800 dark:text-white text-sm mb-3 flex items-center gap-2">
                                        <i class="fas fa-info-circle text-blue-500"></i>
                                        Ubicación Origen
                                    </h3>

                                    <div class="space-y-3">
                                        <!-- Ubicación -->
                                        <div
                                            class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded flex items-center justify-center">
                                                    <i
                                                        class="fas fa-map-marker-alt text-blue-600 dark:text-blue-400 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                                        Ubicación Actual</p>
                                                    <p class="font-bold text-gray-800 dark:text-white text-sm"
                                                        x-text="modalReubicacionRack.ubicacionOrigen.codigo"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Estadísticas -->
                                        <div class="grid grid-cols-2 gap-2">
                                            <div
                                                class="bg-white dark:bg-gray-800 p-3 rounded border border-gray-200 dark:border-gray-700 text-center">
                                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total
                                                    Artículos</p>
                                                <p class="font-bold text-blue-600 dark:text-blue-400"
                                                    x-text="modalReubicacionRack.articulos.length + ' tipos'"></p>
                                            </div>
                                            <div
                                                class="bg-white dark:bg-gray-800 p-3 rounded border border-gray-200 dark:border-gray-700 text-center">
                                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                                    Cantidad Total</p>
                                                <p class="font-bold text-purple-600 dark:text-purple-400"
                                                    x-text="modalReubicacionRack.ubicacionOrigen.cantidad + ' und'">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selección de Artículos -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-white flex items-center gap-2">
                                            <i class="fas fa-boxes text-orange-500"></i>
                                            Seleccionar Artículos a Mover
                                        </label>

                                        <!-- Botón Seleccionar Todos -->
                                        <div class="flex items-center gap-2"
                                            x-show="modalReubicacionRack.articulos.length > 0">
                                            <button type="button" @click="seleccionarTodosArticulos()"
                                                class="btn btn-outline-primary btn-sm py-1 px-3 text-xs flex items-center gap-1"
                                                :class="todosSeleccionados ? 'bg-blue-500 text-white' : ''">
                                                <i class="fas"
                                                    :class="todosSeleccionados ? 'fa-check-square' : 'fa-square'"></i>
                                                <span
                                                    x-text="todosSeleccionados ? 'Todos seleccionados' : 'Seleccionar todos'"></span>
                                            </button>

                                            <!-- Contador rápido -->
                                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium"
                                                x-text="articulosSeleccionados.length + '/' + modalReubicacionRack.articulos.length">
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Lista de Artículos en 2 columnas -->
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800">
                                        <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                            <template x-if="modalReubicacionRack.articulos.length === 0">
                                                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-inbox text-3xl mb-3"></i>
                                                    <p class="text-sm font-medium">No hay artículos en esta ubicación
                                                    </p>
                                                    <p class="text-xs mt-1">La ubicación seleccionada está vacía</p>
                                                </div>
                                            </template>

                                            <!-- Grid de artículos en 2 columnas -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-3">
                                                <template x-for="(articulo, index) in modalReubicacionRack.articulos"
                                                    :key="articulo.id">
                                                    <div class="bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-3 hover:shadow-md transition-all duration-200"
                                                        :class="articulo.seleccionado ?
                                                            'ring-2 ring-blue-500 dark:ring-blue-400' : ''">
                                                        <!-- Header del artículo -->
                                                        <div class="flex items-center justify-between mb-2">
                                                            <div class="flex items-center gap-2 flex-1">
                                                                <input type="checkbox" x-model="articulo.seleccionado"
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                                                                <div class="flex-1 min-w-0">
                                                                    <p class="font-medium text-gray-800 dark:text-white text-sm truncate"
                                                                        x-text="articulo.nombre_mostrar || articulo.nombre || articulo.codigo_repuesto || 'Sin nombre'">
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <!-- Cantidad badge -->
                                                            <div
                                                                class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs font-bold">
                                                                <span x-text="articulo.cantidad"></span> und
                                                            </div>
                                                        </div>

                                                        <!-- Información adicional -->
                                                        <div
                                                            class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                                            <div class="flex items-center gap-1">
                                                                <i class="fas fa-user text-gray-400"></i>
                                                                <span x-text="articulo.cliente_nombre || 'Sin cliente'"
                                                                    class="truncate"></span>
                                                            </div>
                                                            <div class="flex items-center gap-1">
                                                                <i class="fas fa-tag text-gray-400"></i>
                                                                <span
                                                                    x-text="articulo.tipo_articulo || 'Sin tipo'"></span>
                                                            </div>
                                                        </div>

                                                        <!-- Selector de cantidad si está seleccionado -->
                                                        <div x-show="articulo.seleccionado"
                                                            class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                                            <div class="space-y-2">
                                                                <label
                                                                    class="text-xs text-gray-600 dark:text-gray-400 font-medium block">
                                                                    Cantidad a mover:
                                                                </label>
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center gap-2">
                                                                        <button type="button"
                                                                            @click="decrementarCantidadArticulo(index)"
                                                                            class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                                                            :disabled="articulo.cantidad_a_mover <= 1">
                                                                            <i class="fas fa-minus text-xs"></i>
                                                                        </button>
                                                                        <input type="number"
                                                                            x-model="articulo.cantidad_a_mover"
                                                                            min="1" :max="articulo.cantidad"
                                                                            class="w-16 text-center border border-gray-300 dark:border-gray-600 rounded py-1 px-2 text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                                                                        <button type="button"
                                                                            @click="incrementarCantidadArticulo(index)"
                                                                            class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                                                            :disabled="articulo.cantidad_a_mover >= articulo
                                                                                .cantidad">
                                                                            <i class="fas fa-plus text-xs"></i>
                                                                        </button>
                                                                    </div>
                                                                    <span
                                                                        class="text-xs text-gray-500 dark:text-gray-400">
                                                                        de <span x-text="articulo.cantidad"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Indicador de scroll -->
                                        <div x-show="modalReubicacionRack.articulos.length > 4"
                                            class="border-t border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-800">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                                <i class="fas fa-arrows-up-down mr-1"></i>
                                                Desplázate para ver más artículos
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Resumen de selección -->
                                    <div x-show="articulosSeleccionados.length > 0"
                                        class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-orange-700 dark:text-orange-400 font-medium flex items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                Resumen de selección
                                            </span>
                                            <span
                                                class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold"
                                                x-text="articulosSeleccionados.length + ' / ' + modalReubicacionRack.articulos.length">
                                            </span>
                                        </div>
                                        <div class="mt-2 text-sm text-orange-600 dark:text-orange-300">
                                            <div class="flex justify-between items-center">
                                                <span>Total a mover:</span>
                                                <span class="font-bold text-lg"
                                                    x-text="cantidadTotalSeleccionada + ' unidades'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- COLUMNA DERECHA - DESTINO Y CONFIRMACIÓN -->
                            <div class="space-y-6">
                                <!-- Selección de Rack Destino -->
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-white mb-3 flex items-center gap-2">
                                            <i class="fas fa-warehouse text-blue-500"></i>
                                            Seleccionar Rack Destino
                                        </label>
                                        <select x-model="modalReubicacionRack.rackDestinoSeleccionado"
                                            x-ref="rackDestinoSelect"
                                            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all duration-200 bg-white dark:bg-gray-800 text-gray-700 dark:text-white text-sm select2-rack-destino">
                                            <option value="" class="text-gray-400 text-sm">Seleccione un rack
                                                destino</option>
                                            <template x-for="rack in modalReubicacionRack.racksDisponibles"
                                                :key="rack.id">
                                                <option :value="rack.id"
                                                    class="text-gray-700 dark:text-gray-300 text-sm"
                                                    x-text="'Rack ' + rack.nombre + ' - ' + rack.sede">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Selección de Ubicación Destino -->
                                    <div x-show="modalReubicacionRack.rackDestinoSeleccionado" class="space-y-3">
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-white mb-3 flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-green-500"></i>
                                            Seleccionar Ubicación Destino
                                        </label>

                                        <select x-model="modalReubicacionRack.ubicacionDestinoSeleccionada"
                                            x-ref="ubicacionDestinoSelect"
                                            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition-all duration-200 bg-white dark:bg-gray-800 text-gray-700 dark:text-white text-sm select2-ubicacion-destino">
                                            <option value="" class="text-gray-400 text-sm">Seleccione una
                                                ubicación destino</option>
                                            <template x-for="ubicacion in modalReubicacionRack.ubicacionesDestino"
                                                :key="ubicacion.id">
                                                <option :value="ubicacion.id"
                                                    class="text-gray-700 dark:text-gray-300 text-sm"
                                                    x-text="ubicacion.codigo + ' • Ocupado: ' + ubicacion.cantidad_total_articulos + '/' + ubicacion.capacidad_maxima + ' und • Disponible: ' + ubicacion.espacio_disponible + ' und'">
                                                </option>
                                            </template>
                                        </select>

                                        <!-- Contador de ubicaciones disponibles -->
                                        <div
                                            class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-3">
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-green-700 dark:text-green-400 font-medium flex items-center gap-2">
                                                    <i class="fas fa-check-circle"></i>
                                                    Ubicaciones disponibles
                                                </span>
                                                <span
                                                    class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold"
                                                    x-text="modalReubicacionRack.ubicacionesDestino.length">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estado cuando no hay rack seleccionado -->
                                    <div x-show="!modalReubicacionRack.rackDestinoSeleccionado"
                                        class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5 text-center">
                                        <i class="fas fa-warehouse text-3xl text-gray-400 dark:text-gray-500 mb-3"></i>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Selecciona un
                                            rack destino</p>
                                        <p class="text-gray-500 dark:text-gray-500 text-xs mt-1">Para ver las
                                            ubicaciones disponibles</p>
                                    </div>
                                </div>

                                <!-- Información Adicional -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-lightbulb text-blue-500 dark:text-blue-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-blue-800 dark:text-blue-300 text-sm mb-2">
                                                Información importante</h4>
                                            <ul class="text-blue-700 dark:text-blue-400 text-xs space-y-2">
                                                <li class="flex items-start gap-2">
                                                    <i
                                                        class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                                    <span>Selecciona los artículos específicos que deseas mover</span>
                                                </li>
                                                <li class="flex items-start gap-2">
                                                    <i
                                                        class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                                    <span>Puedes mover cantidades parciales de cada artículo</span>
                                                </li>
                                                <li class="flex items-start gap-2">
                                                    <i
                                                        class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                                    <span>La ubicación destino debe tener capacidad suficiente</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado del Formulario -->
                                <div
                                    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center">
                                    <p class="text-sm font-medium mb-2"
                                        :class="modalReubicacionRack.ubicacionDestinoSeleccionada && articulosSeleccionados
                                            .length > 0 ?
                                            'text-green-600 dark:text-green-400' :
                                            'text-amber-600 dark:text-amber-400'"
                                        x-text="modalReubicacionRack.ubicacionDestinoSeleccionada && articulosSeleccionados.length > 0 ? 
                                    '✅ Todo listo para reubicar' : 
                                    '⏳ Completa la selección'">
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"
                                        x-text="modalReubicacionRack.ubicacionDestinoSeleccionada && articulosSeleccionados.length > 0 ? 
                                    articulosSeleccionados.length + ' artículo(s) seleccionados • ' + cantidadTotalSeleccionada + ' unidades' :
                                    'Selecciona artículos y ubicación destino'">
                                    </p>
                                </div>

                                <!-- Botones de Acción -->
                                <div class="flex gap-3">
                                    <button type="button" class="btn btn-outline-danger flex-1 py-3 text-sm"
                                        @click="cerrarModalReubicacionRack()">
                                        <i class="fas fa-times ltr:mr-2 rtl:ml-2"></i>
                                        Cancelar
                                    </button>
                                    <button type="button" @click="confirmarReubicacionRack()"
                                        :disabled="!modalReubicacionRack.ubicacionDestinoSeleccionada || articulosSeleccionados
                                            .length === 0"
                                        class="btn btn-primary flex-1 py-3 text-sm"
                                        :class="(!modalReubicacionRack.ubicacionDestinoSeleccionada || articulosSeleccionados
                                            .length === 0) ? 'opacity-50 cursor-not-allowed' : ''">
                                        <i class="fas fa-check ltr:mr-2 rtl:ml-2"></i>
                                        Confirmar Reubicación
                                    </button>
                                </div>
                            </div>
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

        <!-- Modal Historial Mejorado -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalHistorial.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="modalHistorial.open = false">
                <div x-show="modalHistorial.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-4xl relative">

                    <!-- Header Mejorado -->
                    <div class="flex bg-primary items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-history text-white text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold text-lg text-white">Historial Completo de Ubicación</div>
                                <div class="text-blue-100 text-sm">Código: <span x-text="modalHistorial.ubi.codigo"
                                        class="font-semibold text-white"></span></div>
                            </div>
                        </div>
                        <button type="button" class="text-white hover:text-blue-200 transition-colors"
                            @click="modalHistorial.open = false">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 bg-gray-50">
                        <!-- Resumen Estadístico -->
                        <div class="grid grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-xl p-4 text-center border border-gray-200 shadow-sm">
                                <div class="text-2xl font-bold text-blue-600"
                                    x-text="modalHistorial.ubi.historial ? modalHistorial.ubi.historial.length : 0">
                                </div>
                                <div class="text-xs text-gray-600 mt-1">Total Movimientos</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 text-center border border-gray-200 shadow-sm">
                                <div class="text-2xl font-bold text-green-600"
                                    x-text="getMovimientosPorTipo('ingreso')"></div>
                                <div class="text-xs text-gray-600 mt-1">Ingresos</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 text-center border border-gray-200 shadow-sm">
                                <div class="text-2xl font-bold text-orange-600"
                                    x-text="getMovimientosPorTipo('reubicacion')"></div>
                                <div class="text-xs text-gray-600 mt-1">Reubicaciones</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 text-center border border-gray-200 shadow-sm">
                                <div class="text-2xl font-bold text-red-600" x-text="getMovimientosPorTipo('salida')">
                                </div>
                                <div class="text-xs text-gray-600 mt-1">Salidas</div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm mb-6">
                            <div class="flex flex-wrap gap-4 items-center">
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por
                                        tipo:</label>
                                    <select x-model="modalHistorial.filtroTipo" @change="filtrarHistorial()"
                                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="todos">Todos los movimientos</option>
                                        <option value="ingreso">Ingresos</option>
                                        <option value="salida">Salidas</option>
                                        <option value="reubicacion">Reubicaciones Normales</option>
                                        <option value="reubicacion_custodia">Reubicaciones Custodia</option>
                                        <option value="ajuste">Ajustes</option>
                                        <option value="custodia">Todas las Custodias</option>
                                    </select>
                                </div>
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar
                                        producto:</label>
                                    <div class="relative">
                                        <input type="text" x-model="modalHistorial.busqueda"
                                            @input="filtrarHistorial()"
                                            placeholder="Buscar por producto, serie o código..."
                                            class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <i
                                            class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Movimientos -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b">
                                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-list-ul text-blue-500"></i>
                                    Registro de Movimientos
                                    <span class="text-sm text-gray-500 font-normal ml-2"
                                        x-text="'(' + (modalHistorial.historialFiltrado ? modalHistorial.historialFiltrado.length : 0) + ' resultados)'"></span>
                                </h3>
                            </div>

                            <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                                <template
                                    x-if="modalHistorial.historialFiltrado && modalHistorial.historialFiltrado.length > 0">
                                    <div class="divide-y divide-gray-100">
                                        <template x-for="(mov, idx) in modalHistorial.historialFiltrado"
                                            :key="idx">
                                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                                <!-- Header del Movimiento -->
                                                <div class="flex justify-between items-start mb-3">
                                                    <div class="flex items-center gap-3">
                                                        <!-- Icono según tipo - AHORA DIFERENCIADO -->
                                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm"
                                                            :class="{
                                                                'bg-success': mov.tipo === 'ingreso',
                                                                'bg-danger': mov.tipo === 'salida',
                                                                'bg-info': mov.tipo === 'reubicacion',
                                                                'bg-secondary': mov.tipo === 'reubicacion_custodia',
                                                                'bg-warning': mov.tipo === 'ajuste',
                                                                'bg-gray-500': !mov.tipo
                                                            }">
                                                            <i
                                                                :class="{
                                                                    'fas fa-sign-in-alt': mov.tipo === 'ingreso',
                                                                    'fas fa-sign-out-alt': mov.tipo === 'salida',
                                                                    'fas fa-arrows-alt': mov.tipo === 'reubicacion',
                                                                    'fas fa-shield-alt': mov
                                                                        .tipo === 'reubicacion_custodia',
                                                                    'fas fa-cog': mov.tipo === 'ajuste',
                                                                    'fas fa-exchange-alt': !mov.tipo
                                                                }"></i>
                                                        </div>
                                                        <div>
                                                            <p class="font-semibold text-gray-800 text-sm"
                                                                x-text="mov.producto || 'Movimiento de inventario'">
                                                            </p>
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <span
                                                                    class="text-xs font-medium px-2 py-1 rounded capitalize"
                                                                    :class="{
                                                                        'bg-green-100 text-success': mov
                                                                            .tipo === 'ingreso',
                                                                        'bg-red-100 text-danger': mov
                                                                            .tipo === 'salida',
                                                                        'bg-blue-100 text-info': mov
                                                                            .tipo === 'reubicacion',
                                                                        'bg-secondary text-white': mov
                                                                            .tipo === 'reubicacion_custodia',
                                                                        'bg-orange-100 text-warning': mov
                                                                            .tipo === 'ajuste',
                                                                        'bg-gray-100 text-gray-800': !mov.tipo
                                                                    }"
                                                                    x-text="mov.tipo === 'reubicacion_custodia' ? 'reubicación custodia' : (mov.tipo || 'movimiento')"></span>

                                                                <!-- Badge de Custodia - SOLO PARA REUBICACION_CUSTODIA -->
                                                                <span x-show="mov.tipo === 'reubicacion_custodia'"
                                                                    class="bg-secondary text-white text-xs font-medium px-2 py-1 rounded flex items-center gap-1">
                                                                    <i class="fas fa-shield-alt text-xs"></i>
                                                                    Custodia
                                                                </span>

                                                                <!-- Badge de Cliente -->
                                                                <span
                                                                    x-show="mov.cliente_general_nombre && mov.cliente_general_nombre !== 'Sin cliente'"
                                                                    class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded flex items-center gap-1">
                                                                    <i class="fas fa-user-tie text-xs"></i>
                                                                    <span x-text="mov.cliente_general_nombre"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="font-bold text-lg"
                                                            :class="{
                                                                'text-green-600': mov.tipo === 'ingreso',
                                                                'text-red-600': mov.tipo === 'salida',
                                                                'text-blue-600': mov.tipo === 'reubicacion',
                                                                'text-secondary': mov.tipo === 'reubicacion_custodia',
                                                                'text-warning': mov.tipo === 'ajuste',
                                                                'text-gray-600': !mov.tipo
                                                            }"
                                                            x-text="mov.cantidad + ' und.'"></span>
                                                        <div class="text-xs text-gray-500 mt-1"
                                                            x-text="formatFechaHora(mov.fecha)"></div>
                                                    </div>
                                                </div>

                                                <!-- Detalles del Movimiento -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                    <!-- Información de Origen/Destino -->
                                                    <template x-if="mov.desde || mov.hacia">
                                                        <div class="space-y-2">
                                                            <template x-if="mov.desde">
                                                                <div class="flex items-center gap-2">
                                                                    <i
                                                                        class="fas fa-arrow-right text-red-500 text-xs"></i>
                                                                    <span
                                                                        class="text-gray-600 font-medium">Desde:</span>
                                                                    <span class="font-semibold text-gray-800"
                                                                        x-text="mov.desde"></span>
                                                                    <template x-if="mov.rack_origen">
                                                                        <span class="text-xs text-gray-500">(Rack <span
                                                                                x-text="mov.rack_origen"></span>)</span>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                            <template x-if="mov.hacia">
                                                                <div class="flex items-center gap-2">
                                                                    <i
                                                                        class="fas fa-arrow-right text-green-500 text-xs"></i>
                                                                    <span
                                                                        class="text-gray-600 font-medium">Hacia:</span>
                                                                    <span class="font-semibold text-gray-800"
                                                                        x-text="mov.hacia"></span>
                                                                    <template x-if="mov.rack_destino">
                                                                        <span class="text-xs text-gray-500">(Rack <span
                                                                                x-text="mov.rack_destino"></span>)</span>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>

                                                    <!-- Información Adicional -->
                                                    <div class="space-y-2">
                                                        <template x-if="mov.usuario">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fas fa-user text-gray-400 text-xs"></i>
                                                                <span class="text-gray-600 font-medium">Usuario:</span>
                                                                <span class="font-semibold text-gray-800"
                                                                    x-text="mov.usuario"></span>
                                                            </div>
                                                        </template>
                                                        <template x-if="mov.observaciones">
                                                            <div class="flex items-start gap-2">
                                                                <i
                                                                    class="fas fa-sticky-note text-gray-400 text-xs mt-1"></i>
                                                                <div>
                                                                    <span
                                                                        class="text-gray-600 font-medium">Observaciones:</span>
                                                                    <p class="text-gray-800"
                                                                        x-text="mov.observaciones"></p>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <template x-if="mov.numero_ticket">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fas fa-ticket-alt text-blue-400 text-xs"></i>
                                                                <span class="text-gray-600 font-medium">Ticket:</span>
                                                                <span class="font-semibold text-blue-800"
                                                                    x-text="mov.numero_ticket"></span>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Estado vacío -->
                                <template
                                    x-if="!modalHistorial.historialFiltrado || modalHistorial.historialFiltrado.length === 0">
                                    <div class="text-center py-12">
                                        <div
                                            class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-history text-3xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-800 mb-2">No hay movimientos
                                            registrados</h3>
                                        <p class="text-gray-600 max-w-md mx-auto">Esta ubicación no tiene historial de
                                            movimientos aún.</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Leyenda ACTUALIZADA -->
                        <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-800 text-sm mb-2 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                Leyenda de Movimientos
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-success rounded-full"></div>
                                    <span class="text-gray-700">Ingreso</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-danger rounded-full"></div>
                                    <span class="text-gray-700">Salida</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-info rounded-full"></div>
                                    <span class="text-gray-700">Reubicación</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-secondary rounded-full"></div>
                                    <span class="text-gray-700">Reubicación Custodia</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-warning rounded-full"></div>
                                    <span class="text-gray-700">Ajuste</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Confirmar Reubicación - DISEÑO MEJORADO -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[999] hidden overflow-y-auto"
            :class="modalReubicacion.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4 py-8" @click.self="cancelarReubicacion()">
                <div x-show="modalReubicacion.open" x-transition x-transition.duration.300
                    class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md transform transition-all">

                    <!-- Header Mejorado -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-5 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas text-xl text-white"
                                        :class="modalReubicacion.tipo === 'multiple' ? 'fa-boxes' : 'fa-arrows-alt'"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white"
                                        x-text="modalReubicacion.tipo === 'multiple' ? 'Reubicación Múltiple' : 'Reubicación'">
                                    </h2>
                                    <p class="text-blue-100 text-sm"
                                        x-text="modalReubicacion.tipo === 'multiple' ? 'Mover todos los productos' : 'Mover producto específico'">
                                    </p>
                                </div>
                            </div>
                            <button type="button"
                                class="text-white/80 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/10"
                                @click="modalReubicacion.open = false; cancelarReubicacion()">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body Mejorado -->
                    <div class="p-6 space-y-6">

                        <!-- Resumen de la Operación -->
                        <div class="text-center">
                            <div
                                class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-50 to-purple-50 px-4 py-3 rounded-full border border-blue-200">
                                <div class="text-center">
                                    <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Desde
                                    </div>
                                    <div class="text-lg font-bold text-gray-800" x-text="modalReubicacion.origen">
                                    </div>
                                </div>
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-right text-white text-sm"></i>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs font-semibold text-purple-600 uppercase tracking-wide">Hacia
                                    </div>
                                    <div class="text-lg font-bold text-gray-800" x-text="modalReubicacion.destino">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Producto/Productos -->
                        <div
                            class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border border-gray-200 p-5 shadow-sm">
                            <template x-if="modalReubicacion.tipo === 'multiple'">
                                <div class="space-y-4">
                                    <!-- Estadísticas Rápidas -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="text-center bg-white rounded-lg p-3 shadow-sm border">
                                            <div class="text-2xl font-bold text-purple-600"
                                                x-text="modalReubicacion.productos.length"></div>
                                            <div class="text-xs font-medium text-gray-600 mt-1">Total Artículos</div>
                                        </div>
                                        <div class="text-center bg-white rounded-lg p-3 shadow-sm border">
                                            <div class="text-2xl font-bold text-blue-600"
                                                x-text="modalReubicacion.cantidad"></div>
                                            <div class="text-xs font-medium text-gray-600 mt-1">Unidades Totales</div>
                                        </div>
                                    </div>

                                    <!-- Lista de Productos con Scroll Mejorado -->
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                            <i class="fas fa-list-ul text-blue-500"></i>
                                            Productos a mover:
                                        </h3>
                                        <div
                                            class="max-h-48 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                            <!-- Scroll Container Mejorado -->
                                            <div class="max-h-48 overflow-y-auto custom-scrollbar">
                                                <div class="divide-y divide-gray-100">
                                                    <template x-for="(producto, index) in modalReubicacion.productos"
                                                        :key="index">
                                                        <div class="p-3 hover:bg-blue-50 transition-colors group">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                                                    <!-- Icono según tipo -->
                                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                                                        :class="producto.es_custodia ?
                                                                            'bg-purple-100 text-purple-600' :
                                                                            'bg-blue-100 text-blue-600'">
                                                                        <i class="fas text-xs"
                                                                            :class="producto.es_custodia ? 'fa-shield-alt' :
                                                                                'fa-box'"></i>
                                                                    </div>

                                                                    <!-- Información del producto -->
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="font-medium text-gray-800 text-sm truncate"
                                                                            x-text="producto.nombre"></p>
                                                                        <div class="flex items-center gap-2 mt-1">
                                                                            <span
                                                                                class="text-xs px-2 py-1 rounded-full font-medium"
                                                                                :class="producto.es_custodia ?
                                                                                    'bg-purple-100 text-purple-700' :
                                                                                    'bg-blue-100 text-blue-700'"
                                                                                x-text="producto.es_custodia ? 'Custodia' : producto.tipo_articulo">
                                                                            </span>
                                                                            <span class="text-xs text-gray-500"
                                                                                x-text="'Cliente: ' + producto.cliente_nombre"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Cantidad -->
                                                                <div class="flex items-center gap-2 ml-3">
                                                                    <span
                                                                        class="bg-primary text-white text-sm font-bold px-3 py-1 rounded-full min-w-[60px] text-center">
                                                                        <span x-text="producto.cantidad"></span> und
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Contador de productos -->
                                            <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                                                <div class="flex justify-between items-center text-xs text-gray-600">
                                                    <span>Total de articulos listados</span>
                                                    <span class="font-semibold"
                                                        x-text="modalReubicacion.productos.length"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="modalReubicacion.tipo === 'simple'">
                                <div class="space-y-4">
                                    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-box text-blue-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800"
                                                    x-text="modalReubicacion.producto"></h3>
                                                <div class="flex items-center gap-3 mt-2">
                                                    <span
                                                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                                        <i class="fas fa-cube mr-1"></i>
                                                        <span x-text="modalReubicacion.cantidad + ' unidades'"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Advertencia para Múltiples Productos -->
                        <div x-show="modalReubicacion.tipo === 'multiple'"
                            class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-yellow-800 text-sm">Reubicación completa</h4>
                                    <p class="text-yellow-700 text-xs mt-1 leading-relaxed">
                                        Todos los productos serán movidos a la ubicación destino seleccionada.
                                        La ubicación origen quedará completamente vacía después de esta operación.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción Mejorados -->
                        <div class="flex gap-3 pt-2">
                            <button @click="cancelarReubicacion()"
                                class="flex-1 bg-danger text-white py-3.5 px-6 rounded-xl font-semibold transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </button>
                            <button @click="confirmarReubicacion()"
                                class="flex-1 text-white py-3.5 px-6 rounded-xl font-semibold transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                                :class="modalReubicacion.tipo === 'multiple' ?
                                    'bg-primary' : 'bg-success'">
                                <i class="fas fa-check"></i>
                                <span
                                    x-text="modalReubicacion.tipo === 'multiple' ? 'Reubicar Todo' : 'Confirmar'"></span>
                            </button>
                        </div>

                        <!-- Información Adicional -->
                        <div class="text-center">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Esta acción no se puede deshacer automáticamente
                            </p>
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
                    ubi: {},
                    historialFiltrado: [],
                    filtroTipo: 'todos',
                    busqueda: ''
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
                    articulos: [], // ✅ AGREGAR ESTA LÍNEA
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

                    // ✅ DEBUG: Verificar datos iniciales
                    console.log('📥 Datos iniciales del rack:', this.rack);
                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubicacion, ubiIndex) => {
                            if (ubicacion.codigo === 'A-A2-01') {
                                console.log(`📍 Ubicación A-A2-01 INICIAL:`, {
                                    productosCount: ubicacion.productos?.length || 0,
                                    productos: ubicacion.productos
                                });
                            }
                        });
                    });

                    this.procesarDatosRack();
                    this.initSwipers();
                },

                // ========== MÉTODOS PARA SELECT2 ==========
                initSelect2() {
                    // Inicializar Select2 para rack destino
                    $(this.$refs.rackDestinoSelect).select2({
                        placeholder: "Seleccione un rack destino",
                        allowClear: true,
                        width: '100%',
                        theme: 'bootstrap4',
                        dropdownParent: this.$refs.rackDestinoSelect.closest('.modal-content') || document.body
                    }).on('change', (e) => {
                        this.modalReubicacionRack.rackDestinoSeleccionado = e.target.value;
                        this.cargarUbicacionesDestino();
                    });

                    // Inicializar Select2 para ubicación destino
                    $(this.$refs.ubicacionDestinoSelect).select2({
                        placeholder: "Seleccione una ubicación destino",
                        allowClear: true,
                        width: '100%',
                        theme: 'bootstrap4',
                        dropdownParent: this.$refs.ubicacionDestinoSelect.closest('.modal-content') || document.body
                    }).on('change', (e) => {
                        this.modalReubicacionRack.ubicacionDestinoSeleccionada = e.target.value;
                    });
                },

                destroySelect2() {
                    // Destruir Select2 al cerrar el modal
                    if (this.$refs.rackDestinoSelect) {
                        $(this.$refs.rackDestinoSelect).select2('destroy');
                    }
                    if (this.$refs.ubicacionDestinoSelect) {
                        $(this.$refs.ubicacionDestinoSelect).select2('destroy');
                    }
                },

                // Actualizar Select2 cuando cambian los datos
                actualizarSelectUbicacionesDestino() {
                    this.$nextTick(() => {
                        if (this.$refs.ubicacionDestinoSelect && $(this.$refs.ubicacionDestinoSelect).hasClass(
                                'select2-hidden-accessible')) {
                            $(this.$refs.ubicacionDestinoSelect).trigger('change.select2');
                        }
                    });
                },

                // ========== MÉTODOS DE INICIALIZACIÓN ==========
                procesarDatosRack() {
                    console.log('🔄 Reprocesando datos del rack...');

                    this.rack.niveles.forEach(nivel => {
                        nivel.ubicaciones.forEach(ubicacion => {
                            // ✅ LIMPIAR Y NORMALIZAR DATOS
                            if (!ubicacion.productos) ubicacion.productos = [];

                            // ❌ ELIMINAR ESTA SECCIÓN QUE ESTÁ CAUSANDO EL PROBLEMA
                            // NO eliminar duplicados - cada registro de la BD es único

                            if (ubicacion.productos.length > 0) {
                                ubicacion.cantidad_total = ubicacion.productos.reduce((sum, p) => sum + (p
                                    .cantidad || 0), 0);
                                ubicacion.estado = this.calcularEstado(ubicacion.cantidad_total, ubicacion
                                    .capacidad);

                                // ✅ ACUMULAR CATEGORÍAS Y TIPOS
                                const categoriasUnicas = [...new Set(ubicacion.productos
                                    .map(p => p.categoria)
                                    .filter(c => c && c !== 'Sin categoría'))];

                                const tiposUnicos = [...new Set(ubicacion.productos
                                    .map(p => p.tipo_articulo)
                                    .filter(t => t && t !== 'Sin tipo'))];

                                const clientesUnicos = [...new Set(ubicacion.productos
                                    .map(p => p.cliente_general_nombre)
                                    .filter(c => c && c !== 'Sin cliente'))];

                                ubicacion.categorias_acumuladas = categoriasUnicas.length > 0 ?
                                    categoriasUnicas.join(', ') : 'Sin categoría';
                                ubicacion.tipos_acumulados = tiposUnicos.length > 0 ?
                                    tiposUnicos.join(', ') : 'Sin tipo';
                                ubicacion.clientes_acumulados = clientesUnicos.length > 0 ?
                                    clientesUnicos.join(', ') : 'Sin cliente';

                            } else {
                                ubicacion.categorias_acumuladas = 'Sin categoría';
                                ubicacion.tipos_acumulados = 'Sin tipo';
                                ubicacion.clientes_acumulados = 'Sin cliente';
                                ubicacion.cantidad_total = 0;
                                ubicacion.estado = 'vacio';
                            }

                            // ✅ DEBUG: Verificar productos individuales
                            if (ubicacion.codigo === 'A-A2-01') { // Tu ubicación específica
                                console.log(`📍 Ubicación ${ubicacion.codigo} después de procesar:`, {
                                    productosCount: ubicacion.productos.length,
                                    productos: ubicacion.productos.map(p => ({
                                        id: p.id,
                                        nombre: p.nombre,
                                        cantidad: p.cantidad,
                                        tipo: p.tipo_articulo
                                    }))
                                });
                            }
                        });
                    });

                    console.log('✅ Reprocesamiento de datos completado');
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
                            // ✅ VERIFICAR QUE TENEMOS LOS DATOS NECESARIOS
                            if (this.modoReubicacion.tipo === 'multiple' && !this.modoReubicacion.productos) {
                                this.error('No hay productos para reubicar');
                                this.cancelarReubicacion();
                                return;
                            }

                            if (this.modoReubicacion.tipo === 'simple' && !this.modoReubicacion.producto) {
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

                            // ✅ PREPARAR DATOS PARA EL MODAL DE CONFIRMACIÓN
                            this.modalReubicacion.origen = this.modoReubicacion.origen;
                            this.modalReubicacion.destino = ubi.codigo;
                            this.modalReubicacion.tipo = this.modoReubicacion.tipo;

                            if (this.modoReubicacion.tipo === 'multiple') {
                                this.modalReubicacion.producto = 'Todos los productos';
                                this.modalReubicacion.cantidad = this.modoReubicacion.cantidad;
                                this.modalReubicacion.productos = this.modoReubicacion.productos;
                            } else {
                                this.modalReubicacion.producto = this.modoReubicacion.producto;
                                this.modalReubicacion.cantidad = this.modoReubicacion.cantidad;
                            }

                            // ✅ SOLO AHORA ABRIR EL MODAL DE CONFIRMACIÓN
                            console.log('📋 Abriendo modal de confirmación con datos:', {
                                tipo: this.modalReubicacion.tipo,
                                origen: this.modalReubicacion.origen,
                                destino: this.modalReubicacion.destino,
                                productos: this.modalReubicacion.productos?.length
                            });

                            this.modalReubicacion.open = true;
                        } else {
                            this.error('La ubicación destino no es válida para esta reubicación');
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
                    console.log('🔍 DEBUG - Datos de ubicación al abrir historial:', ubi);
                    console.log('📊 DEBUG - Historial disponible:', ubi.historial);

                    this.modalHistorial.ubi = ubi;
                    this.modalHistorial.filtroTipo = 'todos';
                    this.modalHistorial.busqueda = '';
                    this.modalHistorial.open = true;

                    // Inicializar historial filtrado
                    this.$nextTick(() => {
                        this.filtrarHistorial();
                    });
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

                    const cantidadProducto = producto.cantidad || 1;

                    // ✅ CORREGIDO: Payload diferente para custodias vs productos normales
                    const payload = {
                        ubicacion_origen_id: ubi.id,
                        producto: nombreProducto.toString().trim(),
                        cantidad: cantidadProducto,
                    };

                    // ✅ PARA CUSTODIAS
                    if (producto.custodia_id) {
                        payload.custodia_id = producto.custodia_id;
                        // Para custodias, NO enviar articulo_id ni cliente_general_id
                        payload.articulo_id = null;
                        payload.cliente_general_id = null;
                    }
                    // ✅ PARA PRODUCTOS NORMALES
                    else {
                        payload.articulo_id = producto.id;
                        payload.cliente_general_id = producto.cliente_general_id;
                        payload.custodia_id = null;
                    }

                    console.log('📤 Enviando datos de reubicación:', payload);

                    // ✅ NUEVO: Llamar al backend para iniciar reubicación
                    fetch('/almacen/reubicacion/iniciar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(response => response.json())
                        .then(result => {
                            console.log('📥 Respuesta del backend:', result);

                            if (result.success) {
                                // ✅ Configurar modo reubicación con datos del backend
                                this.modoReubicacion.activo = true;
                                this.modoReubicacion.origen = ubi.codigo;
                                this.modoReubicacion.producto = result.data.ubicacion_origen.producto;
                                this.modoReubicacion.ubicacionOrigenId = ubi.id;

                                // ✅ DIFERENCIAR ENTRE CUSTODIA Y PRODUCTO NORMAL
                                if (producto.custodia_id) {
                                    this.modoReubicacion.productoId = producto.custodia_id;
                                    this.modoReubicacion.esCustodia = true;
                                    this.modoReubicacion.articuloId = null;
                                    this.modoReubicacion.clienteGeneralId = null;
                                } else {
                                    this.modoReubicacion.productoId = producto.id;
                                    this.modoReubicacion.esCustodia = false;
                                    this.modoReubicacion.articuloId = producto.id;
                                    this.modoReubicacion.clienteGeneralId = producto.cliente_general_id;
                                }

                                this.modoReubicacion.cantidad = cantidadProducto;
                                this.modoReubicacion.tipo = 'producto_especifico';

                                this.modal.open = false;

                                console.log('✅ Datos configurados para reubicación:', {
                                    producto: this.modoReubicacion.producto,
                                    productoId: this.modoReubicacion.productoId,
                                    articuloId: this.modoReubicacion.articuloId,
                                    clienteGeneralId: this.modoReubicacion.clienteGeneralId,
                                    esCustodia: this.modoReubicacion.esCustodia,
                                    cantidad: this.modoReubicacion.cantidad
                                });

                                const mensaje = producto.custodia_id ?
                                    'Modo reubicación activado para custodia: ' + nombreProducto :
                                    'Modo reubicación activado para: ' + nombreProducto + ' - Cliente: ' + (producto
                                        .cliente_general_nombre || 'Sin cliente');

                                this.success(mensaje);
                            } else {
                                this.error(result.message || 'Error al iniciar reubicación');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.error('Error de conexión al servidor');
                        });
                },

                async iniciarReubicacionMultiple(ubi) {
                    if (!ubi.productos || ubi.productos.length === 0) {
                        this.error('No hay productos para reubicar');
                        return;
                    }

                    this.loading = true;

                    try {
                        const response = await fetch('/almacen/reubicacion/iniciar-multiple', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                ubicacion_origen_id: ubi.id
                            })
                        });

                        const result = await response.json();
                        console.log('📥 Respuesta reubicación múltiple:', result);

                        if (result.success) {
                            const data = result.data.ubicacion_origen;

                            // ✅ CORREGIDO: Solo configurar el modo reubicación, NO abrir el modal todavía
                            this.modoReubicacion.activo = true;
                            this.modoReubicacion.tipo = 'multiple';
                            this.modoReubicacion.origen = data.codigo;
                            this.modoReubicacion.ubicacionOrigenId = data.id;
                            this.modoReubicacion.productos = data.productos;
                            this.modoReubicacion.cantidad = data.cantidad_total;
                            this.modoReubicacion.totalProductos = data.total_productos;

                            // ✅ NO abrir modalReubicacion todavía - solo cerrar modal principal
                            this.modal.open = false;

                            this.success(
                                `Modo reubicación activado para ${data.total_productos} productos. Selecciona una ubicación destino.`
                            );

                            console.log('✅ Modo reubicación múltiple activado. Esperando selección de destino...');
                        } else {
                            this.error(result.message || 'Error al iniciar reubicación múltiple');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    } finally {
                        this.loading = false;
                    }
                },


                esDestinoValido(ubi) {
                    if (ubi.codigo === this.modoReubicacion.origen) {
                        return false;
                    }

                    // Para reubicación múltiple, la ubicación destino debe estar vacía
                    if (this.modoReubicacion.tipo === 'multiple') {
                        return (!ubi.productos || ubi.productos.length === 0);
                    }

                    // Para custodias individuales, la ubicación destino debe estar vacía
                    if (this.modoReubicacion.esCustodia) {
                        return (!ubi.productos || ubi.productos.length === 0);
                    }

                    // Para productos normales individuales, verificar que no exista el mismo producto
                    if (this.modoReubicacion.tipo === 'producto_especifico') {
                        const productoExistente = ubi.productos?.find(p => p.id === this.modoReubicacion.productoId);
                        return !productoExistente;
                    }

                    return (!ubi.productos || ubi.productos.length === 0);
                },

                async confirmarReubicacion() {
                    try {
                        if (!this.modalReubicacion.destino) {
                            this.error('Por favor selecciona una ubicación destino');
                            return;
                        }

                        const ubicacionDestino = this.buscarUbicacionPorCodigo(this.modalReubicacion.destino);
                        if (!ubicacionDestino) {
                            this.error('Ubicación destino no encontrada');
                            return;
                        }

                        // Determinar qué endpoint usar según el tipo
                        const url = this.modoReubicacion.tipo === 'multiple' ?
                            '/almacen/reubicacion/confirmar-multiple' :
                            '/almacen/reubicacion/confirmar';

                        // Preparar payload según el tipo
                        let payload = {
                            ubicacion_origen_id: Number(this.modoReubicacion.ubicacionOrigenId),
                            ubicacion_destino_id: ubicacionDestino.id,
                            tipo_reubicacion: 'mismo_rack'
                        };

                        if (this.modoReubicacion.tipo === 'multiple') {
                            // Payload para reubicación múltiple
                            payload.producto = 'Todos los productos';
                            payload.cantidad = this.modoReubicacion.cantidad;
                        } else {
                            // Payload para reubicación simple
                            payload.producto = this.modalReubicacion.producto.toString().trim();
                            payload.cantidad = parseInt(this.modalReubicacion.cantidad);
                            payload.es_custodia = this.modoReubicacion.esCustodia || false;

                            // Agregar campos según el tipo
                            if (this.modoReubicacion.esCustodia) {
                                payload.custodia_id = this.modoReubicacion.productoId;
                                payload.articulo_id = null;
                                payload.cliente_general_id = null;
                            } else {
                                payload.custodia_id = null;
                                payload.articulo_id = this.modoReubicacion.articuloId;
                                payload.cliente_general_id = this.modoReubicacion.clienteGeneralId;
                            }
                        }

                        console.log('📤 PAYLOAD enviado al backend:', payload);

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();
                        console.log('📥 Respuesta confirmación:', result);

                        if (result.success) {
                            const mensaje = this.modoReubicacion.tipo === 'multiple' ?
                                `✅ ${result.data.total_productos} productos reubicados exitosamente` :
                                result.message;

                            this.success(mensaje);

                            // Recargar datos completos
                            const recargaExitosa = await this.recargarDatosRackCompletos();

                            // ✅ ACTUALIZAR HISTORIAL EN TIEMPO REAL
                            if (recargaExitosa) {
                                await this.actualizarHistorialEnTiempoReal();
                            }

                            if (recargaExitosa) {
                                this.cancelarReubicacion();
                                this.modalReubicacion.open = false;

                                // Cerrar modal de detalle si está abierto
                                if (this.modal.open) {
                                    this.modal.open = false;
                                }
                            }
                        } else {
                            let errorMessage = result.message || 'Error al confirmar reubicación';
                            if (result.errors) {
                                console.error('Errores de validación:', result.errors);
                                errorMessage = Object.values(result.errors).flat().join(', ');
                            }
                            this.error(errorMessage);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },
                // En tu Alpine.js - MÉTODO MEJORADO
                async recargarDatosRackCompletos() {
                    try {
                        console.log('🔄 Recargando datos del rack desde servidor...');

                        const rackNombre = this.rack.nombre;
                        const sede = this.rack.sede;

                        const timestamp = new Date().getTime();
                        const response = await fetch(
                            `/almacen/racks/${rackNombre}/datos-actualizados?sede=${encodeURIComponent(sede)}&_t=${timestamp}`
                        );

                        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

                        const result = await response.json();

                        if (result.success && result.data) {
                            console.log('📥 Datos COMPLETOS recibidos del servidor:', result.data);

                            // ✅ DEBUG: Verificar si viene el historial
                            const ubicacionesConHistorial = result.data.niveles?.flatMap(n => n.ubicaciones)
                                .filter(u => u.historial && u.historial.length > 0);

                            console.log('📊 Ubicaciones con historial:', ubicacionesConHistorial?.length || 0);
                            if (ubicacionesConHistorial && ubicacionesConHistorial.length > 0) {
                                console.log('🔍 Ejemplo de historial:', ubicacionesConHistorial[0].historial);
                            }

                            // ✅ REEMPLAZAR COMPLETAMENTE LOS DATOS
                            this.rack = JSON.parse(JSON.stringify(result.data));

                            // ✅ REPROCESAR DATOS
                            this.procesarDatosRack();

                            await this.$nextTick();
                            await this.$nextTick();

                            setTimeout(() => {
                                this.initSwipers();
                            }, 150);

                            console.log('✅ Datos recargados exitosamente');
                            return true;
                        } else {
                            throw new Error(result.message || 'Error en la respuesta');
                        }
                    } catch (error) {
                        console.error('❌ Error recargando datos:', error);
                        this.error('Error al actualizar datos: ' + error.message);
                        return false;
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

                        // Limpiar estado existente
                        this.modoReubicacion.activo = false;
                        this.modoReubicacion.origen = '';
                        this.modoReubicacion.producto = '';
                        this.modoReubicacion.ubicacionOrigenId = null;
                        this.modoReubicacion.cantidad = 0;
                        this.modoReubicacion.tipo = '';
                        this.modoReubicacion.esCustodia = false;
                        this.modoReubicacion.productoId = null;
                        this.modoReubicacion.articuloId = null;
                        this.modoReubicacion.clienteGeneralId = null;
                        this.modoReubicacion.productos = [];
                        this.modoReubicacion.totalProductos = 0;

                        this.modalReubicacion.open = false;
                        this.modalReubicacion.origen = '';
                        this.modalReubicacion.destino = '';
                        this.modalReubicacion.producto = '';
                        this.modalReubicacion.cantidad = 0;
                        this.modalReubicacion.tipo = 'simple';
                        this.modalReubicacion.productos = [];

                        // Recargar datos para asegurar consistencia
                        await this.recargarDatosRackCompletos();
                    } catch (error) {
                        console.error('Error:', error);
                        // Aún así limpiar el estado local
                        this.modoReubicacion.activo = false;
                        this.modalReubicacion.open = false;

                        // Intentar recargar a pesar del error
                        try {
                            await this.recargarDatosRackCompletos();
                        } catch (e) {
                            console.error('Error secundario al recargar:', e);
                        }
                    }
                },
                // Computed property para verificar si todos están seleccionados
                get todosSeleccionados() {
                    if (!this.modalReubicacionRack.articulos || this.modalReubicacionRack.articulos.length === 0) {
                        return false;
                    }
                    return this.modalReubicacionRack.articulos.every(articulo => articulo.seleccionado);
                },

                // Método para seleccionar/deseleccionar todos
                seleccionarTodosArticulos() {
                    const todosSeleccionados = this.todosSeleccionados;

                    this.modalReubicacionRack.articulos.forEach(articulo => {
                        articulo.seleccionado = !todosSeleccionados;
                        // Si se están seleccionando, establecer la cantidad a mover como la cantidad total
                        if (!todosSeleccionados) {
                            articulo.cantidad_a_mover = articulo.cantidad;
                        }
                    });

                    console.log(`✅ ${!todosSeleccionados ? 'Todos los artículos seleccionados' : 'Selección limpiada'}`);
                },

                // Método para seleccionar todos (alternativa)
                seleccionarTodos() {
                    this.modalReubicacionRack.articulos.forEach(articulo => {
                        articulo.seleccionado = true;
                        articulo.cantidad_a_mover = articulo.cantidad;
                    });
                    console.log('✅ Todos los artículos seleccionados');
                },

                // Método para deseleccionar todos
                deseleccionarTodos() {
                    this.modalReubicacionRack.articulos.forEach(articulo => {
                        articulo.seleccionado = false;
                    });
                    console.log('✅ Selección limpiada');
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

                    const total = this.modal.ubi.productos.reduce((sum, p) => {
                        return sum + (parseInt(p.cantidad) || 0);
                    }, 0);

                    console.log('📊 Total modal calculado:', total);
                    return total;
                },
                incrementarCantidadExistente(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];
                    const totalActual = this.getCantidadTotalModal();

                    if (totalActual < this.modal.ubi.capacidad) {
                        producto.cantidad = parseInt(producto.cantidad) + 1;

                        // ✅ ACTUALIZAR CANTIDAD TOTAL EN TIEMPO REAL
                        this.modal.ubi.cantidad_total = this.getCantidadTotalModal();
                        this.modal.ubi.estado = this.calcularEstado(this.modal.ubi.cantidad_total, this.modal.ubi
                            .capacidad);

                        console.log('➕ Cantidad incrementada:', {
                            producto: producto.nombre,
                            cantidad: producto.cantidad,
                            total_ubicacion: this.modal.ubi.cantidad_total
                        });
                    } else {
                        this.warning('No se puede exceder la capacidad máxima de la ubicación');
                    }
                },

                decrementarCantidadExistente(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];
                    if (producto.cantidad > 1) {
                        producto.cantidad = parseInt(producto.cantidad) - 1;

                        // ✅ ACTUALIZAR CANTIDAD TOTAL EN TIEMPO REAL
                        this.modal.ubi.cantidad_total = this.getCantidadTotalModal();
                        this.modal.ubi.estado = this.calcularEstado(this.modal.ubi.cantidad_total, this.modal.ubi
                            .capacidad);

                        console.log('➖ Cantidad decrementada:', {
                            producto: producto.nombre,
                            cantidad: producto.cantidad,
                            total_ubicacion: this.modal.ubi.cantidad_total
                        });
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

                    // ✅ ACTUALIZAR CANTIDAD TOTAL EN TIEMPO REAL
                    this.modal.ubi.cantidad_total = this.getCantidadTotalModal();
                    this.modal.ubi.estado = this.calcularEstado(this.modal.ubi.cantidad_total, this.modal.ubi.capacidad);
                },

                async guardarCambiosProducto(index) {
                    if (!this.modal.ubi || !this.modal.ubi.productos) return;

                    const producto = this.modal.ubi.productos[index];
                    const ubicacionId = this.modal.ubi.id;

                    try {
                        const payload = {
                            ubicacion_id: ubicacionId,
                            articulo_id: producto.id,
                            cantidad: parseInt(producto.cantidad),
                            accion: 'actualizar'
                        };

                        console.log('📤 Actualizando producto:', payload);

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

                            // ✅ RECARGAR DATOS COMPLETOS
                            await this.recargarDatosRackCompletos();

                            // ✅ ACTUALIZAR HISTORIAL EN TIEMPO REAL
                            await this.actualizarHistorialEnTiempoReal();

                            // ✅ CERRAR MODAL Y REABRIR CON DATOS ACTUALIZADOS
                            if (this.modal.open) {
                                const ubiActualizada = this.buscarUbicacionPorId(ubicacionId);
                                if (ubiActualizada) {
                                    this.modal.ubi = ubiActualizada;
                                    this.prepararEdicionProductos();
                                }
                            }
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
                // Métodos adicionales para el historial
                getMovimientosPorTipo(tipo) {
                    if (!this.modalHistorial.ubi.historial) return 0;
                    return this.modalHistorial.ubi.historial.filter(mov => mov.tipo === tipo).length;
                },

                filtrarHistorial() {
                    console.log('🔄 Filtrando historial...');

                    if (!this.modalHistorial.ubi || !this.modalHistorial.ubi.historial) {
                        console.warn('❌ No hay historial disponible');
                        this.modalHistorial.historialFiltrado = [];
                        return;
                    }

                    console.log('📋 Historial completo:', this.modalHistorial.ubi.historial);

                    let filtered = [...this.modalHistorial.ubi.historial];

                    // Filtrar por tipo
                    if (this.modalHistorial.filtroTipo !== 'todos') {
                        filtered = filtered.filter(mov => {
                            if (!mov.tipo) return false;

                            switch (this.modalHistorial.filtroTipo) {
                                case 'ingreso':
                                case 'salida':
                                case 'ajuste':
                                    return mov.tipo === this.modalHistorial.filtroTipo;
                                case 'reubicacion':
                                    return mov.tipo === 'reubicacion';
                                case 'reubicacion_custodia':
                                    return mov.tipo === 'reubicacion_custodia';
                                case 'custodia':
                                    return mov.tipo === 'reubicacion_custodia';
                                default:
                                    return true;
                            }
                        });
                    }

                    // Filtrar por búsqueda
                    if (this.modalHistorial.busqueda) {
                        const busqueda = this.modalHistorial.busqueda.toLowerCase();
                        filtered = filtered.filter(mov =>
                            (mov.producto && mov.producto.toLowerCase().includes(busqueda)) ||
                            (mov.observaciones && mov.observaciones.toLowerCase().includes(busqueda)) ||
                            (mov.serie && mov.serie.toLowerCase().includes(busqueda)) ||
                            (mov.codigocustodias && mov.codigocustodias.toLowerCase().includes(busqueda)) ||
                            (mov.usuario && mov.usuario.toLowerCase().includes(busqueda)) ||
                            (mov.numero_ticket && mov.numero_ticket.toLowerCase().includes(busqueda))
                        );
                    }

                    // Ordenar por fecha (más reciente primero)
                    filtered.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

                    console.log('✅ Historial filtrado:', filtered);
                    this.modalHistorial.historialFiltrado = filtered;
                },

                formatFechaHora(fecha) {
                    if (!fecha) return 'Sin fecha';
                    return new Date(fecha).toLocaleString('es-ES', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },


                // En abrirHistorial, inicializar los filtros
                abrirHistorial(ubi) {
                    this.modalHistorial.ubi = ubi;
                    this.modalHistorial.filtroTipo = 'todos';
                    this.modalHistorial.busqueda = '';
                    this.modalHistorial.open = true;

                    // Inicializar historial filtrado
                    this.$nextTick(() => {
                        this.filtrarHistorial();
                    });
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

                            // ✅ CORREGIDO: En lugar de actualizar manualmente, recargar datos completos
                            await this.recargarDatosRackCompletos();

                            this.cerrarModalAgregarProducto();

                            // ✅ CERRAR MODAL PRINCIPAL SI ESTÁ ABIERTO
                            if (this.modal.open) {
                                this.modal.open = false;
                            }

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
                    console.log('🔄 Actualizando interfaz para ubicación:', ubicacionId);

                    this.rack.niveles.forEach((nivel, nivelIndex) => {
                        nivel.ubicaciones.forEach((ubi, ubiIndex) => {
                            if (ubi.id === ubicacionId) {
                                // ✅ CALCULAR CANTIDAD TOTAL DESDE CERO
                                const cantidadTotal = ubi.productos ?
                                    ubi.productos.reduce((sum, p) => sum + (parseInt(p.cantidad) || 0), 0) :
                                    0;

                                // ✅ ACTUALIZAR PROPIEDADES
                                this.rack.niveles[nivelIndex].ubicaciones[ubiIndex] = {
                                    ...ubi,
                                    cantidad_total: cantidadTotal,
                                    estado: this.calcularEstado(cantidadTotal, ubi.capacidad),
                                    fecha: new Date().toISOString()
                                };

                                console.log(`✅ Ubicación ${ubicacionId} actualizada:`, {
                                    cantidad_total: cantidadTotal,
                                    productos: ubi.productos?.length || 0
                                });
                            }
                        });
                    });

                    // ✅ FORZAR ACTUALIZACIÓN REACTIVA
                    this.procesarDatosRack();
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

                // ========== MÉTODOS PARA REUBICACIÓN ENTRE RACKS ==========
                abrirModalReubicacionRack(ubicacion) {
                    console.log('📍 Abriendo modal reubicación entre racks:', ubicacion);

                    this.modalReubicacionRack.open = true;
                    this.modalReubicacionRack.ubicacionOrigen = {
                        id: ubicacion.id,
                        codigo: ubicacion.codigo,
                        cantidad: ubicacion.cantidad_total || 0,
                        producto: ubicacion.producto || 'Artículos varios' // ✅ AGREGAR ESTA LÍNEA
                    };

                    // Cargar artículos de la ubicación
                    this.cargarArticulosUbicacion(ubicacion.id);

                    // Cargar racks disponibles
                    this.cargarRacksDisponibles();
                    this.modalReubicacionRack.rackDestinoSeleccionado = '';
                    this.modalReubicacionRack.ubicacionDestinoSeleccionada = '';
                    this.modalReubicacionRack.ubicacionesDestino = [];

                    // Inicializar Select2 después de que el modal esté visible
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.initSelect2();
                        }, 100);
                    });
                },
                async cargarRacksDisponibles() {
                    try {
                        console.log('🔄 Cargando racks disponibles...');

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
                            // Filtrar para excluir el rack actual
                            this.modalReubicacionRack.racksDisponibles = result.data.filter(rack =>
                                rack.id !== this.rack.idRack
                            );
                            console.log('✅ Racks disponibles cargados:', this.modalReubicacionRack.racksDisponibles);
                        } else {
                            this.error('Error al cargar racks disponibles');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

                async cargarUbicacionesDestino() {
                    if (!this.modalReubicacionRack.rackDestinoSeleccionado) {
                        this.modalReubicacionRack.ubicacionesDestino = [];
                        this.actualizarSelectUbicacionesDestino();
                        return;
                    }

                    try {
                        console.log('🔄 Cargando ubicaciones para rack:', this.modalReubicacionRack
                            .rackDestinoSeleccionado);

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
                            console.log('✅ Ubicaciones destino cargadas:', this.modalReubicacionRack
                                .ubicacionesDestino);

                            // Actualizar Select2 después de cargar las ubicaciones
                            this.actualizarSelectUbicacionesDestino();
                        } else {
                            this.error('Error al cargar ubicaciones destino');
                            this.modalReubicacionRack.ubicacionesDestino = [];
                            this.actualizarSelectUbicacionesDestino();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                        this.modalReubicacionRack.ubicacionesDestino = [];
                        this.actualizarSelectUbicacionesDestino();
                    }
                },

                async confirmarReubicacionRack() {
                    try {
                        if (!this.modalReubicacionRack.ubicacionDestinoSeleccionada) {
                            this.error('Por favor selecciona una ubicación destino');
                            return;
                        }

                        console.log('📤 Confirmando reubicación entre racks:', {
                            origen: this.modalReubicacionRack.ubicacionOrigen.id,
                            destino: this.modalReubicacionRack.ubicacionDestinoSeleccionada,
                            producto: this.modalReubicacionRack.ubicacionOrigen.producto
                        });

                        const productoValue = this.modalReubicacionRack.ubicacionOrigen.producto || 'Artículos varios';

                        const payload = {
                            ubicacion_origen_id: this.modalReubicacionRack.ubicacionOrigen.id,
                            ubicacion_destino_id: this.modalReubicacionRack.ubicacionDestinoSeleccionada,
                            producto: productoValue,
                            cantidad: this.modalReubicacionRack.ubicacionOrigen.cantidad,
                            tipo_reubicacion: 'otro_rack'
                        };

                        console.log('📤 PAYLOAD enviado:', payload);

                        const response = await fetch('/almacen/reubicacion/confirmar-entre-racks', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();
                        console.log('📥 Respuesta reubicación entre racks:', result);

                        if (result.success) {
                            this.success('✅ Reubicación entre racks completada exitosamente');

                            // ✅ RECARGAR DATOS COMPLETOS
                            await this.recargarDatosRackCompletos();

                            // ✅ ACTUALIZAR HISTORIAL EN TIEMPO REAL
                            await this.actualizarHistorialEnTiempoReal();

                            this.cerrarModalReubicacionRack();

                            if (this.modal.open) {
                                this.modal.open = false;
                            }
                        } else {
                            this.error(result.message || 'Error al confirmar reubicación entre racks');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },


                // ✅ MÉTODO MEJORADO: Actualizar historial en tiempo real con más debug
                async actualizarHistorialEnTiempoReal() {
                    try {
                        console.log('🔄 Actualizando historial en tiempo real...');
                        console.log('🔍 Estado del modal historial:', {
                            abierto: this.modalHistorial.open,
                            tiene_ubi: !!this.modalHistorial.ubi,
                            ubi_id: this.modalHistorial.ubi?.id,
                            ubi_codigo: this.modalHistorial.ubi?.codigo
                        });

                        // Si el modal de historial está abierto, actualizar sus datos
                        if (this.modalHistorial.open && this.modalHistorial.ubi) {
                            const ubicacionId = this.modalHistorial.ubi.id;
                            console.log('📍 Buscando ubicación actualizada para ID:', ubicacionId);

                            // Buscar la ubicación actualizada en los datos recargados
                            const ubicacionActualizada = this.buscarUbicacionPorId(ubicacionId);

                            if (ubicacionActualizada) {
                                console.log('📍 Ubicación actualizada encontrada:', {
                                    codigo: ubicacionActualizada.codigo,
                                    historial_count: ubicacionActualizada.historial?.length || 0,
                                    historial: ubicacionActualizada.historial
                                });

                                // ✅ ACTUALIZAR COMPLETAMENTE EL OBJETO UBI
                                this.modalHistorial.ubi = JSON.parse(JSON.stringify(ubicacionActualizada));

                                // Re-filtrar el historial
                                this.filtrarHistorial();

                                console.log('✅ Historial actualizado en tiempo real:', {
                                    nuevo_count: this.modalHistorial.ubi.historial?.length || 0,
                                    historial_filtrado: this.modalHistorial.historialFiltrado?.length || 0
                                });
                            } else {
                                console.warn('❌ No se encontró la ubicación actualizada para el historial con ID:',
                                    ubicacionId);
                                console.log('📋 Ubicaciones disponibles:', this.rack.niveles.flatMap(n => n.ubicaciones)
                                    .map(u => ({
                                        id: u.id,
                                        codigo: u.codigo
                                    })));
                            }
                        } else {
                            console.log('ℹ️ Modal de historial no está abierto o no tiene ubicación');
                        }

                        // Si el modal principal está abierto, también actualizar su historial
                        if (this.modal.open && this.modal.ubi) {
                            const ubicacionId = this.modal.ubi.id;
                            const ubicacionActualizada = this.buscarUbicacionPorId(ubicacionId);

                            if (ubicacionActualizada) {
                                this.modal.ubi = {
                                    ...this.modal.ubi,
                                    historial: ubicacionActualizada.historial || []
                                };
                                console.log('✅ Historial del modal principal actualizado');
                            }
                        }
                    } catch (error) {
                        console.error('❌ Error actualizando historial en tiempo real:', error);
                    }
                },

                // ========== MÉTODOS PARA REUBICACIÓN ENTRE RACKS ==========
                iniciarReubicacionRack(ubicacion) {
                    console.log('📍 Abriendo modal reubicación entre racks:', ubicacion);

                    this.modalReubicacionRack.open = true;
                    this.modalReubicacionRack.ubicacionOrigen = {
                        id: ubicacion.id,
                        codigo: ubicacion.codigo,
                        cantidad: ubicacion.cantidad_total || 0
                    };

                    // Cargar artículos de la ubicación
                    this.cargarArticulosUbicacion(ubicacion.id);

                    // Cargar racks disponibles
                    this.cargarRacksDisponibles();
                    this.modalReubicacionRack.rackDestinoSeleccionado = '';
                    this.modalReubicacionRack.ubicacionDestinoSeleccionada = '';
                    this.modalReubicacionRack.ubicacionesDestino = [];
                },

                async cargarArticulosUbicacion(ubicacionId) {
                    try {
                        console.log('🔄 Cargando artículos de la ubicación:', ubicacionId);

                        const response = await fetch(`/almacen/ubicaciones/${ubicacionId}/articulos`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Preparar artículos con propiedades de selección
                            this.modalReubicacionRack.articulos = result.data.map(articulo => ({
                                ...articulo,
                                seleccionado: false,
                                cantidad_a_mover: articulo.cantidad // Por defecto mover toda la cantidad
                            }));
                            console.log('✅ Artículos cargados:', this.modalReubicacionRack.articulos);
                        } else {
                            this.error('Error al cargar artículos de la ubicación');
                            this.modalReubicacionRack.articulos = [];
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                        this.modalReubicacionRack.articulos = [];
                    }
                },

                // Computed properties para artículos seleccionados
                get articulosSeleccionados() {
                    return this.modalReubicacionRack.articulos.filter(articulo => articulo.seleccionado);
                },

                get cantidadTotalSeleccionada() {
                    return this.articulosSeleccionados.reduce((total, articulo) => total + articulo.cantidad_a_mover,
                        0);
                },

                // Métodos para manejar cantidades
                incrementarCantidadArticulo(index) {
                    const articulo = this.modalReubicacionRack.articulos[index];
                    if (articulo.cantidad_a_mover < articulo.cantidad) {
                        articulo.cantidad_a_mover++;
                    }
                },

                decrementarCantidadArticulo(index) {
                    const articulo = this.modalReubicacionRack.articulos[index];
                    if (articulo.cantidad_a_mover > 1) {
                        articulo.cantidad_a_mover--;
                    }
                },

                cerrarModalReubicacionRack() {
                    this.destroySelect2();

                    this.modalReubicacionRack.open = false;
                    this.modalReubicacionRack.ubicacionOrigen = {};
                    this.modalReubicacionRack.articulos = [];
                    this.modalReubicacionRack.racksDisponibles = [];
                    this.modalReubicacionRack.rackDestinoSeleccionado = '';
                    this.modalReubicacionRack.ubicacionDestinoSeleccionada = '';
                    this.modalReubicacionRack.ubicacionesDestino = [];
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

                buscarUbicacionPorId(ubicacionId) {
                    console.log('🔍 Buscando ubicación por ID:', ubicacionId);

                    for (const nivel of this.rack.niveles) {
                        for (const ubi of nivel.ubicaciones) {
                            if (ubi.id === ubicacionId) {
                                console.log('✅ Ubicación encontrada:', {
                                    id: ubi.id,
                                    codigo: ubi.codigo,
                                    historial_count: ubi.historial?.length || 0
                                });
                                return ubi;
                            }
                        }
                    }

                    console.warn('❌ Ubicación no encontrada con ID:', ubicacionId);
                    return null;
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
