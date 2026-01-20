<x-layout.default>
    <!-- Incluir Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            height: 48px;
            padding: 8px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
        }

        .select2-container--default.select2-container--disabled .select2-selection--single {
            background-color: #f9fafb;
            opacity: 0.6;
        }
    </style>

    <div x-data="solicitudRepuesto()" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="container mx-auto px-4 w-full">
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline">Solicitudes</a>
                    </li>

                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Solicitud de Repuesto Provincia</span>
                    </li>
                </ul>
            </div>
            <!-- Header Principal -->
            <div
                class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-blue-100 transition-all duration-300 hover:shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between lg:gap-8">
                    <!-- Contenido Principal -->
                    <div class="flex-1">
                        <!-- Título y Descripción -->
                        <div class="mb-4">
                            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                                Crear Orden de Repuesto Provincia
                            </h1>
                            <p class="text-gray-600 text-lg">Complete la información requerida para generar una nueva
                                orden de repuestos</p>
                        </div>

                        <!-- Información en Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Usuario</p>
                                    <p class="font-semibold text-gray-900">{{ auth()->user()->name ?? 'Administrador' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha</p>
                                    <p x-text="currentDate" class="font-semibold text-gray-900"></p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Estado</p>
                                    <p class="font-semibold text-gray-900">En creación</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Número de Orden -->
                    <div class="mt-6 lg:mt-0 lg:ml-6">
                        <div
                            class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-center shadow-lg border border-blue-200">
                            <p class="text-white/80 text-sm font-medium mb-1">Orden N°</p>
                            <div class="text-2xl lg:text-3xl font-black text-white tracking-wide">
                                ORD-<span x-text="orderNumber.toString().padStart(3, '0')"></span>
                            </div>
                            <div class="w-12 h-1 bg-white/30 rounded-full mx-auto mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Columna Principal -->
                <div class="xl:col-span-3 space-y-8">
                    <!-- Campo para escribir número de ticket -->
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-ticket-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Información del Ticket</h2>
                                    <p class="text-white/80 text-sm">Ingrese el número del ticket</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Número de Ticket</label>
                                <input type="text" x-model="ticketNumber" placeholder="Escriba el número de ticket"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Selección de CAST -->
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-green-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-building text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Selección de CAST</h2>
                                    <p class="text-white/80 text-sm">Seleccione el CAST para esta orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">CAST</label>
                                <select x-model="selectedCast" x-ref="castSelect" class="w-full">
                                    <option value="">Seleccione un CAST...</option>
                                    <template x-for="cast in castList" :key="cast.idCast">
                                        <option :value="cast.idCast" x-text="cast.nombre"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Mostrar información del CAST seleccionado -->
                            <div x-show="selectedCastInfo" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="bg-green-50 rounded-xl p-4 border border-green-200 mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Contacto:</p>
                                        <p class="font-semibold text-gray-900"
                                            x-text="selectedCastInfo?.telefono || 'No especificado'"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Email:</p>
                                        <p class="font-semibold text-gray-900"
                                            x-text="selectedCastInfo?.email || 'No especificado'"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">RUC:</p>
                                        <p class="font-semibold text-gray-900"
                                            x-text="selectedCastInfo?.ruc || 'No especificado'"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Ubicación:</p>
                                        <p class="font-semibold text-gray-900">
                                            <span x-text="selectedCastInfo?.departamento || 'No especificado'"></span>
                                            /
                                            <span x-text="selectedCastInfo?.provincia || 'No especificado'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de Productos -->
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <!-- Header con gradiente -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 md:px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex-shrink-0 flex items-center justify-center w-8 h-8 md:w-10 md:h-10 
                        bg-white text-blue-600 rounded-full font-bold shadow-md">
                                    1
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg md:text-xl font-bold text-white truncate">
                                        Selección de Productos
                                    </h2>
                                    <p class="text-blue-100 text-xs md:text-sm truncate">
                                        Agregue los repuestos necesarios para la orden
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 md:p-6">
                            <!-- Tabla de Productos -->
                            <div class="mb-6 md:mb-8">
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 md:mb-6">
                                    <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                                        Productos Seleccionados
                                    </h3>
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm text-gray-600"
                                            x-text="`${totalUniqueProducts} producto${totalUniqueProducts !== 1 ? 's' : ''}`">
                                        </span>
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"
                                            x-show="products.length > 0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla responsive - contenedor scroll horizontal -->
                                <div class="overflow-x-auto rounded-xl border border-blue-100">
                                    <table class="w-full min-w-[600px] md:min-w-full">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th
                                                    class="px-4 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold 
                                       text-blue-600 uppercase tracking-wider whitespace-nowrap">
                                                    Ticket
                                                </th>
                                                <th
                                                    class="px-4 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold 
                                       text-blue-600 uppercase tracking-wider whitespace-nowrap">
                                                    Modelo
                                                </th>
                                                <th
                                                    class="px-4 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold 
                                       text-blue-600 uppercase tracking-wider whitespace-nowrap">
                                                    Tipo
                                                </th>
                                                <th
                                                    class="px-4 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold 
                                       text-blue-600 uppercase tracking-wider whitespace-nowrap">
                                                    Código
                                                </th>
                                                <th
                                                    class="px-4 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold 
                                       text-blue-600 uppercase tracking-wider whitespace-nowrap">
                                                    Cantidad
                                                </th>
                                                <th
                                                    class="px-4 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold 
                                       text-blue-600 uppercase tracking-wider whitespace-nowrap">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-blue-100">
                                            <!-- Estado vacío -->
                                            <template x-if="products.length === 0">
                                                <tr>
                                                    <td colspan="6"
                                                        class="px-4 md:px-6 py-8 md:py-12 text-center text-gray-500">
                                                        <svg class="mx-auto h-12 w-12 md:h-16 md:w-16 text-gray-300"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                            </path>
                                                        </svg>
                                                        <p
                                                            class="mt-3 md:mt-4 text-base md:text-lg font-medium text-gray-900">
                                                            No hay productos agregados
                                                        </p>
                                                        <p class="text-xs md:text-sm mt-1 md:mt-2">
                                                            Agregue productos usando el formulario inferior
                                                        </p>
                                                    </td>
                                                </tr>
                                            </template>

                                            <!-- Productos -->
                                            <template x-for="(product, index) in products" :key="product.uniqueId">
                                                <tr class="product-row hover:bg-blue-50 transition-all duration-200">
                                                    <!-- Ticket -->
                                                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap 
                                           text-sm md:text-base font-semibold text-purple-600 truncate max-w-[100px]"
                                                        :title="product.ticket" x-text="product.ticket">
                                                    </td>

                                                    <!-- Modelo -->
                                                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap 
                                           text-sm md:text-base font-semibold text-gray-900 truncate max-w-[120px]"
                                                        :title="product.modelo" x-text="product.modelo">
                                                    </td>

                                                    <!-- Tipo -->
                                                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap 
                                           text-sm md:text-base text-gray-700 truncate max-w-[120px]"
                                                        :title="product.tipo" x-text="product.tipo">
                                                    </td>

                                                    <!-- Código -->
                                                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap 
                                           text-sm md:text-base text-blue-600 font-mono font-bold truncate max-w-[100px]"
                                                        :title="product.codigo" x-text="product.codigo">
                                                    </td>

                                                    <!-- Cantidad -->
                                                    <td
                                                        class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap 
                                           text-sm md:text-base text-gray-900">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold" x-text="product.cantidad"></span>
                                                            <div class="flex gap-1">
                                                                <button @click="updateQuantity(index, -1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 
                                                           hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <button @click="updateQuantity(index, 1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 
                                                           hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Acciones -->
                                                    <td
                                                        class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm md:text-base">
                                                        <button @click="removeProduct(index)"
                                                            class="text-red-600 hover:text-red-700 font-semibold 
                                                   flex items-center gap-1 md:gap-2 transition-colors">
                                                            <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Eliminar</span>
                                                            <span class="inline sm:hidden">Elim.</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Formulario para Agregar Producto -->
                            <div class="bg-blue-50 rounded-2xl p-4 md:p-6 border border-blue-200">
                                <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 md:mb-6">
                                    Agregar Nuevo Producto
                                </h3>

                                <!-- Grid de formulario responsive -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
                                    <!-- Modelo -->
                                    <div class="sm:col-span-2 lg:col-span-1">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Modelo
                                        </label>
                                        <select x-model="newProduct.modelo" x-ref="modeloSelect"
                                            class="w-full text-sm md:text-base px-3 py-2 border rounded-lg">
                                            <option value="">Seleccione un modelo</option>
                                            <template x-for="modelo in modelos" :key="modelo.idModelo">
                                                <option :value="modelo.idModelo" x-text="modelo.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Tipo de Repuesto -->
                                    <div class="sm:col-span-2 lg:col-span-1">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tipo de Repuesto
                                        </label>
                                        <select x-model="newProduct.tipo" x-ref="tipoSelect"
                                            class="w-full text-sm md:text-base px-3 py-2 border rounded-lg"
                                            :disabled="!newProduct.modelo">
                                            <option value="">Seleccione modelo primero</option>
                                        </select>
                                        <div x-show="loadingTipos" class="text-xs text-blue-500 mt-1">
                                            Cargando tipos de repuesto...
                                        </div>
                                    </div>

                                    <!-- Código -->
                                    <div class="sm:col-span-2 lg:col-span-1">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Código
                                        </label>
                                        <select x-model="newProduct.codigo" x-ref="codigoSelect"
                                            class="w-full text-sm md:text-base px-3 py-2 border rounded-lg"
                                            :disabled="!newProduct.tipo">
                                            <option value="">Seleccione tipo primero</option>
                                        </select>
                                        <div x-show="loadingCodigos" class="text-xs text-blue-500 mt-1">
                                            Cargando códigos...
                                        </div>
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="sm:col-span-2 lg:col-span-1">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Cantidad
                                        </label>
                                        <div class="flex w-full max-w-[200px] mx-auto lg:mx-0">
                                            <button @click="decreaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center 
                                       rounded-l-lg px-3 md:px-4 font-semibold border border-r-0 
                                       border-blue-600 hover:bg-blue-700 transition-colors
                                       active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" x-model="newProduct.cantidad" min="1"
                                                max="100"
                                                class="w-16 text-center border-y border-blue-600 focus:ring-0 
                                      bg-white text-gray-900 font-semibold text-sm md:text-base"
                                                readonly />
                                            <button @click="increaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center 
                                       rounded-r-lg px-3 md:px-4 font-semibold border border-l-0 
                                       border-blue-600 hover:bg-blue-700 transition-colors
                                       active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2 text-center lg:text-left">
                                            Mín: 1 - Máx: 100
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón Agregar Producto -->
                                <button @click="addProduct()" :disabled="!canAddProduct"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !canAddProduct,
                                        'hover:scale-[1.02] active:scale-95': canAddProduct
                                    }"
                                    class="w-full bg-blue-600 text-white py-3 md:py-4 px-4 md:px-6 rounded-lg 
                           font-bold transition-all duration-200 flex items-center justify-center 
                           gap-2 md:gap-3 shadow-lg text-sm md:text-base">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="truncate">Agregar Producto a la Orden</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white text-cyan-600 rounded-full font-bold shadow-md">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Información Adicional</h2>
                                    <p class="text-blue-100 text-sm">Complete los detalles de la orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Tipo de Servicio -->
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-tools text-blue-500 mr-2"></i>
                                        Tipo de Servicio
                                    </label>
                                    <div class="relative">
                                        <select x-model="orderInfo.tipoServicio"
                                            class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white cursor-pointer shadow-sm hover:shadow-md">
                                            <option value="">Seleccione un tipo de servicio</option>
                                            <template x-for="servicio in tiposServicio" :key="servicio.value">
                                                <option :value="servicio.value" x-text="servicio.text"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Fecha Requerida -->
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                                        Fecha Requerida
                                    </label>
                                    <div class="relative">
                                        <input type="text" x-ref="fechaRequeridaInput"
                                            x-model="orderInfo.fechaRequerida"
                                            class="w-full pr-11 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 transition-all duration-200 shadow-sm hover:shadow-md"
                                            placeholder="Seleccione una fecha">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                                        Fecha límite para tener todos los repuestos
                                    </div>
                                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3 transition-all duration-300"
                                        x-show="orderInfo.fechaRequerida"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check-circle text-blue-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-blue-800">Fecha establecida</p>
                                                <p class="text-sm text-blue-700 font-medium">
                                                    <span
                                                        x-text="formatDateForDisplay(orderInfo.fechaRequerida)"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nivel de Urgencia - Versión Cards -->
                            <div class="mt-8 space-y-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-gauge-high text-blue-500 mr-2"></i>
                                    Nivel de Urgencia
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <template x-for="(urgencia, index) in nivelesUrgencia" :key="index">
                                        <button type="button" @click="orderInfo.urgencia = urgencia.value"
                                            class="group relative text-left transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                                            <div class="p-5 rounded-xl border-2 transition-all duration-300 h-full shadow-sm hover:shadow-md"
                                                :class="{
                                                    [urgencia.borderColor + ' ' + urgencia.bgColor]: orderInfo
                                                        .urgencia === urgencia.value,
                                                        'border-gray-200 bg-gray-50 hover:border-gray-300': orderInfo
                                                        .urgencia !== urgencia.value
                                                }">

                                                <!-- Header con icono y título -->
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas text-xl"
                                                            :class="urgencia.icon + ' ' + urgencia.iconColor"></i>
                                                        <h3 class="font-bold text-gray-900 text-lg"
                                                            x-text="urgencia.text"></h3>
                                                    </div>
                                                    <div class="w-6 h-6 flex items-center justify-center transition-all duration-300"
                                                        :class="{
                                                            [urgencia.iconColor]: orderInfo.urgencia === urgencia.value,
                                                                'text-gray-400': orderInfo.urgencia !== urgencia.value
                                                        }">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path x-show="orderInfo.urgencia === urgencia.value"
                                                                fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                            <path x-show="orderInfo.urgencia !== urgencia.value"
                                                                fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Descripción -->
                                                <p class="text-sm text-gray-600 leading-relaxed"
                                                    x-text="urgencia.description"></p>

                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-8">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Observaciones y
                                    Comentarios</label>
                                <textarea x-model="orderInfo.observaciones" rows="5"
                                    class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 resize-none transition-all duration-200"
                                    placeholder="Describa cualquier observación, comentario adicional o instrucción especial para esta orden..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="xl:col-span-1 space-y-8">
                    <!-- Resumen de la Orden - Versión Grid para móvil -->
                    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border border-blue-100">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6 text-center md:text-left">
                            Resumen de Orden
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3 md:gap-4">
                            <!-- Productos Únicos -->
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium text-sm">
                                        Productos Únicos
                                    </span>
                                    <span class="text-xl font-bold text-blue-600" x-text="totalUniqueProducts">
                                    </span>
                                </div>
                            </div>

                            <!-- Total Cantidad -->
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium text-sm">
                                        Total Cantidad
                                    </span>
                                    <span class="text-xl font-bold text-green-600" x-text="totalQuantity">
                                    </span>
                                </div>
                            </div>

                            <!-- Fecha Requerida -->
                            <div class="bg-orange-50 p-3 rounded-lg sm:col-span-2 lg:col-span-1">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1">
                                    <span class="text-gray-700 font-medium text-sm">
                                        Fecha Requerida
                                    </span>
                                    <span class="text-base font-bold text-orange-600 truncate"
                                        x-text="orderInfo.fechaRequerida ? formatDateForDisplay(orderInfo.fechaRequerida) : 'No definida'"
                                        :title="orderInfo.fechaRequerida ? formatDateForDisplay(orderInfo.fechaRequerida) : ''">
                                    </span>
                                </div>
                            </div>

                            <!-- CAST -->
                            <div class="bg-purple-50 p-3 rounded-lg sm:col-span-2 lg:col-span-1">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1">
                                    <span class="text-gray-700 font-medium text-sm">
                                        CAST
                                    </span>
                                    <span class="text-xs font-bold text-purple-600 truncate max-w-[200px]"
                                        x-text="selectedCastInfo ? selectedCastInfo.nombre : 'No seleccionado'"
                                        :title="selectedCastInfo ? selectedCastInfo.nombre : ''">
                                    </span>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="bg-yellow-50 p-3 rounded-lg sm:col-span-2 lg:col-span-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium text-sm">
                                        Estado
                                    </span>
                                    <span
                                        class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full 
                            text-xs font-bold">
                                        En creación
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border border-blue-100">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6 text-center">Acciones</h3>

                        <div class="space-y-3 md:space-y-4">
                            <!-- Botón Limpiar Todo -->
                            <button @click="clearAll()" :disabled="products.length === 0 || isCreatingOrder"
                                :class="{
                                    'opacity-50 cursor-not-allowed': products.length === 0 || isCreatingOrder,
                                    'active:scale-95': products.length > 0 && !isCreatingOrder
                                }"
                                class="w-full px-4 md:px-6 py-3 md:py-4 bg-warning text-white rounded-lg font-bold 
                       hover:bg-yellow-600 transition-all duration-200 
                       flex items-center justify-center gap-2 md:gap-3 
                       text-sm md:text-base">
                                <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span class="truncate">Limpiar Todo</span>
                            </button>

                            <!-- Botón Guardar Solicitud -->
                            @if (\App\Helpers\PermisoHelper::tienePermiso('GUARDAR SOLICITUD REPUESTO'))
                                <button @click="createOrder()" :disabled="!canCreateOrder || isCreatingOrder"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !canCreateOrder || isCreatingOrder,
                                        'hover:scale-[1.02] active:scale-95': canCreateOrder && !isCreatingOrder
                                    }"
                                    class="w-full px-4 md:px-6 py-3 md:py-4 bg-green-500 text-white rounded-lg font-bold 
                           hover:bg-green-600 transition-all duration-200 
                           flex items-center justify-center gap-2 md:gap-3 
                           text-sm md:text-base shadow-lg">
                                    <!-- Spinner cuando está guardando -->
                                    <template x-if="isCreatingOrder">
                                        <div
                                            class="animate-spin rounded-full h-4 w-4 md:h-5 md:w-5 border-b-2 border-white flex-shrink-0">
                                        </div>
                                    </template>

                                    <!-- Icono de check cuando no está guardando -->
                                    <template x-if="!isCreatingOrder">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>

                                    <!-- Texto del botón -->
                                    <span class="truncate"
                                        x-text="isCreatingOrder ? 'Guardando...' : 'Guardar Solicitud'"></span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Información de Contacto - Versión minimalista -->
                    <div
                        class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-4 md:p-6 border border-blue-200">
                        <h4 class="text-base md:text-lg font-bold text-blue-700 mb-4 md:mb-6 text-center">
                            ¿Necesita Ayuda?
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3 md:gap-4">
                            <!-- Teléfono -->
                            <div class="bg-white/80 p-3 md:p-4 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-success rounded-lg 
                            flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-gray-600">
                                            Soporte Técnico
                                        </p>
                                        <p class="text-sm font-bold text-gray-900 truncate">
                                            +1 (555) 123-4567
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="bg-white/80 p-3 md:p-4 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-primary rounded-lg 
                            flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-gray-600">
                                            Email
                                        </p>
                                        <p class="text-sm font-bold text-gray-900 truncate">
                                            soporte@empresa.com
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Horario -->
                            <div
                                class="bg-white/80 p-3 md:p-4 rounded-xl border border-blue-100 sm:col-span-2 lg:col-span-1">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-secondary rounded-lg 
                            flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-gray-600">
                                            Horario de Atención
                                        </p>
                                        <p class="text-sm font-bold text-gray-900">
                                            24/7
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal para confirmar limpieza -->
        <div x-show="showClearModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div x-show="showClearModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 mx-auto">

                <!-- Icono de advertencia -->
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>

                <!-- Contenido -->
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar todos los artículos?</h3>
                    <p class="text-gray-600">
                        Esta acción eliminará <span class="font-semibold text-red-600"
                            x-text="totalUniqueProducts"></span> artículo(s)
                        con un total de <span class="font-semibold text-red-600" x-text="totalQuantity"></span>
                        unidades.
                    </p>
                    <p class="text-sm text-gray-500 mt-2">Esta acción no se puede deshacer.</p>
                </div>

                <!-- Botones -->
                <div class="flex space-x-3">
                    <button @click="cancelClearAll()"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button @click="confirmClearAll()"
                        class="flex-1 px-4 py-3 bg-danger text-white rounded-lg font-semibold hover:bg-red-700 transition-colors duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar Todo</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Notificación Toast -->
        <div x-show="notification.show" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
            :class="getNotificationClass()"
            class="fixed top-6 right-6 text-white px-6 py-4 rounded-xl shadow-2xl z-50 max-w-sm">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 text-xl" x-html="getNotificationIcon()"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium" x-text="notification.message"></p>
                </div>
                <button @click="notification.show = false" class="flex-shrink-0 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Incluir jQuery y Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/es.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuesto', () => ({
                // Estado de la aplicación
                currentDate: '',
                orderNumber: {{ $nextOrderNumber ?? 1 }},
                ticketNumber: '', // Solo es un campo de texto
                selectedCast: '',
                selectedCastInfo: null,
                castList: @json($castList ?? []),
                modelos: @json($modelos ?? []),
                loadingTipos: false,
                loadingCodigos: false,
                products: [],
                newProduct: {
                    modelo: '',
                    tipo: '',
                    codigo: '',
                    cantidad: 1
                },
                orderInfo: {
                    tipoServicio: '',
                    urgencia: '',
                    observaciones: '',
                    fechaRequerida: ''
                },
                minDate: '',
                isCreatingOrder: false,
                showClearModal: false,

                // Datos para los selects
                tiposServicio: [{
                        value: 'mantenimiento',
                        text: 'Mantenimiento Preventivo'
                    },
                    {
                        value: 'reparacion',
                        text: 'Reparación Correctiva'
                    },
                    {
                        value: 'instalacion',
                        text: 'Instalación'
                    },
                    {
                        value: 'garantia',
                        text: 'Garantía'
                    }
                ],
                nivelesUrgencia: [{
                        value: 'baja',
                        text: 'Baja',
                        icon: 'fa-circle-check',
                        iconColor: 'text-green-500',
                        bgColor: 'bg-green-50',
                        borderColor: 'border-green-200',
                        description: 'Sin urgencia específica'
                    },
                    {
                        value: 'media',
                        text: 'Media',
                        icon: 'fa-clock',
                        iconColor: 'text-yellow-500',
                        bgColor: 'bg-yellow-50',
                        borderColor: 'border-yellow-200',
                        description: 'Necesario en los próximos días'
                    },
                    {
                        value: 'alta',
                        text: 'Alta',
                        icon: 'fa-triangle-exclamation',
                        iconColor: 'text-red-500',
                        bgColor: 'bg-red-50',
                        borderColor: 'border-red-200',
                        description: 'Urgente - necesario inmediatamente'
                    }
                ],

                // Computed properties
                get totalQuantity() {
                    return this.products.reduce((sum, product) => sum + product.cantidad, 0);
                },
                get totalUniqueProducts() {
                    const uniqueProducts = new Set();
                    this.products.forEach(product => {
                        const key =
                            `${product.ticketNumber}-${product.modeloId}-${product.tipoId}-${product.codigoId}`;
                        uniqueProducts.add(key);
                    });
                    return uniqueProducts.size;
                },
                get canAddProduct() {
                    return this.newProduct.modelo &&
                        this.newProduct.tipo &&
                        this.newProduct.codigo &&
                        this.newProduct.cantidad > 0 &&
                        this.ticketNumber;
                },
                get canCreateOrder() {
                    return this.products.length > 0 &&
                        this.ticketNumber &&
                        this.selectedCast &&
                        this.orderInfo.tipoServicio &&
                        this.orderInfo.urgencia &&
                        this.orderInfo.fechaRequerida;
                },

                // Métodos
                init() {
                    this.currentDate = new Date().toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    this.minDate = new Date().toISOString().split('T')[0];

                    // Obtener el próximo número de orden
                    this.getNextOrderNumber();

                    this.$nextTick(() => {
                        this.initSelect2();
                        this.initFlatpickr();
                    });
                },

                async getNextOrderNumber() {
                    try {
                        const response = await fetch('/api/next-order-number');
                        const data = await response.json();
                        if (data.success) {
                            this.orderNumber = data.nextOrderNumber;
                        }
                    } catch (error) {
                        console.error('Error obteniendo número de orden:', error);
                        this.orderNumber = {{ $nextOrderNumber ?? 1 }};
                    }
                },

                initSelect2() {
                    // CAST Select
                    if (this.$refs.castSelect) {
                        $(this.$refs.castSelect).select2({
                            placeholder: 'Seleccione un CAST...',
                            language: 'es',
                            width: '100%'
                        }).on('change', (e) => {
                            this.selectedCast = e.target.value;
                            if (e.target.value) {
                                this.loadCastInfo(e.target.value);
                            } else {
                                this.selectedCastInfo = null;
                            }
                        });
                    }

                    // Modelo Select
                    if (this.$refs.modeloSelect) {
                        $(this.$refs.modeloSelect).select2({
                            placeholder: 'Seleccione un modelo',
                            language: 'es',
                            width: '100%'
                        }).on('change', (e) => {
                            this.newProduct.modelo = e.target.value;
                            if (e.target.value) {
                                this.loadTiposRepuesto(e.target.value);
                            } else {
                                this.clearTipoSelect();
                                this.clearCodigoSelect();
                            }
                        });
                    }

                    // Tipo Select
                    if (this.$refs.tipoSelect) {
                        $(this.$refs.tipoSelect).select2({
                            placeholder: 'Seleccione tipo de repuesto',
                            language: 'es',
                            width: '100%',
                            disabled: true
                        }).on('change', (e) => {
                            this.newProduct.tipo = e.target.value;
                            if (e.target.value && this.newProduct.modelo) {
                                this.loadCodigosRepuesto(this.newProduct.modelo, e.target
                                    .value);
                            } else {
                                this.clearCodigoSelect();
                            }
                        });
                    }

                    // Código Select
                    if (this.$refs.codigoSelect) {
                        $(this.$refs.codigoSelect).select2({
                            placeholder: 'Seleccione código',
                            language: 'es',
                            width: '100%',
                            disabled: true
                        }).on('change', (e) => {
                            this.newProduct.codigo = e.target.value;
                        });
                    }
                },

                initFlatpickr() {
                    this.$nextTick(() => {
                        if (this.$refs.fechaRequeridaInput && typeof flatpickr !==
                            'undefined') {
                            try {
                                flatpickr(this.$refs.fechaRequeridaInput, {
                                    locale: 'es',
                                    dateFormat: 'Y-m-d',
                                    minDate: 'today',
                                    disableMobile: false,
                                    allowInput: true,
                                    clickOpens: true,
                                    onChange: (selectedDates, dateStr) => {
                                        this.orderInfo.fechaRequerida = dateStr;
                                    },
                                    onReady: (selectedDates, dateStr, instance) => {
                                        if (this.orderInfo.fechaRequerida) {
                                            instance.setDate(this.orderInfo
                                                .fechaRequerida);
                                        }
                                    }
                                });
                            } catch (error) {
                                console.error('Error al inicializar Flatpickr:', error);
                            }
                        }
                    });
                },

                // Cargar información del CAST
                async loadCastInfo(castId) {
                    try {
                        const response = await fetch(`/api/cast-info/${castId}`);
                        const castData = await response.json();

                        if (castData) {
                            this.selectedCastInfo = castData;
                        }
                    } catch (error) {
                        console.error('Error loading CAST info:', error);
                        toastr.error('Error al cargar información del CAST');
                    }
                },

                async loadTiposRepuesto(modeloId) {
                    this.loadingTipos = true;
                    this.clearTipoSelect();
                    this.clearCodigoSelect();

                    try {
                        const response = await fetch(`/api/tipos-repuesto/${modeloId}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const tipos = await response.json();

                        if (this.$refs.tipoSelect && tipos.length > 0) {
                            $(this.$refs.tipoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'Seleccione tipo de repuesto'
                                })
                            );

                            tipos.forEach(tipo => {
                                $(this.$refs.tipoSelect).append(
                                    $('<option>', {
                                        value: tipo.idsubcategoria,
                                        text: tipo.tipo_repuesto
                                    })
                                );
                            });

                            $(this.$refs.tipoSelect).prop('disabled', false).trigger('change');
                            toastr.success(`${tipos.length} tipos de repuesto cargados`);
                        } else {
                            $(this.$refs.tipoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'No hay tipos disponibles'
                                })
                            ).prop('disabled', true).trigger('change');
                            toastr.warning('No se encontraron tipos de repuesto para este modelo');
                        }
                    } catch (error) {
                        console.error('Error loading tipos repuesto:', error);
                        $(this.$refs.tipoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Error al cargar tipos'
                            })
                        ).prop('disabled', true).trigger('change');
                        toastr.error('Error al cargar los tipos de repuesto');
                    } finally {
                        this.loadingTipos = false;
                    }
                },

                async loadCodigosRepuesto(modeloId, subcategoriaId) {
                    this.loadingCodigos = true;
                    this.clearCodigoSelect();

                    try {
                        const response = await fetch(
                            `/api/codigos-repuesto/${modeloId}/${subcategoriaId}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const codigos = await response.json();

                        if (this.$refs.codigoSelect && codigos.length > 0) {
                            $(this.$refs.codigoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'Seleccione código'
                                })
                            );

                            codigos.forEach(codigo => {
                                $(this.$refs.codigoSelect).append(
                                    $('<option>', {
                                        value: codigo.codigo_repuesto,
                                        text: `${codigo.codigo_repuesto} - ${codigo.nombre}`
                                    })
                                );
                            });

                            $(this.$refs.codigoSelect).prop('disabled', false).trigger('change');
                            toastr.success(`${codigos.length} códigos cargados`);
                        } else {
                            $(this.$refs.codigoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'No hay códigos disponibles'
                                })
                            ).prop('disabled', true).trigger('change');
                            toastr.warning('No se encontraron códigos para este tipo de repuesto');
                        }
                    } catch (error) {
                        console.error('Error loading codigos:', error);
                        $(this.$refs.codigoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Error al cargar códigos'
                            })
                        ).prop('disabled', true).trigger('change');
                        toastr.error('Error al cargar los códigos');
                    } finally {
                        this.loadingCodigos = false;
                    }
                },

                clearTipoSelect() {
                    this.newProduct.tipo = '';
                    if (this.$refs.tipoSelect) {
                        $(this.$refs.tipoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Seleccione tipo primero'
                            })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                clearCodigoSelect() {
                    this.newProduct.codigo = '';
                    if (this.$refs.codigoSelect) {
                        $(this.$refs.codigoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Seleccione tipo primero'
                            })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                formatDateForDisplay(dateString) {
                    if (!dateString) return '';

                    const date = new Date(dateString + 'T00:00:00'); // evita desfase
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');

                    return `${year}/${month}/${day}`;
                },


                increaseQuantity() {
                    if (this.newProduct.cantidad < 100) {
                        this.newProduct.cantidad++;
                    }
                },

                decreaseQuantity() {
                    if (this.newProduct.cantidad > 1) {
                        this.newProduct.cantidad--;
                    }
                },

                addProduct() {
                    if (!this.canAddProduct) {
                        toastr.error('Por favor complete todos los campos del producto');
                        return;
                    }

                    const modeloText = $(this.$refs.modeloSelect).find('option:selected').text() || this
                        .newProduct.modelo;
                    const tipoText = $(this.$refs.tipoSelect).find('option:selected').text() || this
                        .newProduct.tipo;
                    const codigoText = $(this.$refs.codigoSelect).find('option:selected').text() || this
                        .newProduct.codigo;

                    const existingProductIndex = this.products.findIndex(product =>
                        product.ticketNumber === this.ticketNumber &&
                        product.modeloId === this.newProduct.modelo &&
                        product.tipoId === this.newProduct.tipo &&
                        product.codigoId === this.newProduct.codigo
                    );

                    if (existingProductIndex !== -1) {
                        this.products[existingProductIndex].cantidad += this.newProduct.cantidad;
                        toastr.success(
                            `Cantidad actualizada: ${this.products[existingProductIndex].cantidad} unidades`
                        );
                    } else {
                        const product = {
                            uniqueId: Date.now() + Math.random(),
                            ticket: this.ticketNumber,
                            ticketNumber: this.ticketNumber,
                            modelo: modeloText,
                            modeloId: this.newProduct.modelo,
                            tipo: tipoText,
                            tipoId: this.newProduct.tipo,
                            codigo: codigoText,
                            codigoId: this.newProduct.codigo,
                            cantidad: this.newProduct.cantidad
                        };

                        this.products.push(product);
                        toastr.success('Producto agregado correctamente');
                    }

                    this.newProduct.cantidad = 1;
                    this.clearTipoSelect();
                    this.clearCodigoSelect();

                    if (this.newProduct.modelo) {
                        this.loadTiposRepuesto(this.newProduct.modelo);
                    }
                },

                removeProduct(index) {
                    this.products.splice(index, 1);
                    toastr.info('Producto eliminado de la orden');
                },

                updateQuantity(index, change) {
                    const newQuantity = this.products[index].cantidad + change;
                    if (newQuantity >= 1 && newQuantity <= 100) {
                        this.products[index].cantidad = newQuantity;
                    }
                },

                clearAll() {
                    if (this.products.length === 0) {
                        toastr.info('No hay artículos para limpiar');
                        return;
                    }
                    this.showClearModal = true;
                },

                confirmClearAll() {
                    this.products = [];
                    this.showClearModal = false;
                    toastr.info('Todos los artículos han sido eliminados');
                },

                cancelClearAll() {
                    this.showClearModal = false;
                },

                async createOrder() {
                    if (!this.canCreateOrder) {
                        toastr.error('Complete todos los campos requeridos para crear la orden');
                        return;
                    }

                    this.isCreatingOrder = true;

                    try {
                        const orderData = {
                            ticketNumber: this.ticketNumber,
                            castId: this.selectedCast,
                            orderInfo: this.orderInfo,
                            products: this.products.map(product => ({
                                modeloId: product.modeloId,
                                tipoId: product.tipoId,
                                codigoId: product.codigoId,
                                cantidad: product.cantidad
                            })),
                            orderNumber: this.orderNumber,
                            esProvincia: true
                        };

                        console.log('Enviando datos de la orden provincia:', orderData);

                        const response = await fetch('/solicitudrepuesto/store-provincia', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(orderData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            toastr.success(`¡Orden ${result.codigo_orden} creada exitosamente!`);

                            // Mostrar mensaje de éxito
                            toastr.success(`Redirigiendo al listado de solicitudes...`, '', {
                                timeOut: 2000,
                                onHidden: () => {
                                    // Redirigir después de que el toast se oculte
                                    if (result.redirect_url) {
                                        // Si el backend devuelve una URL específica
                                        window.location.href = result.redirect_url;
                                    } else {
                                        // Redirigir al index de solicitudes
                                        window.location.href =
                                            '{{ route('solicitudarticulo.index') }}';
                                    }
                                }
                            });

                        } else {
                            throw new Error(result.message);
                        }

                    } catch (error) {
                        console.error('Error al crear la orden:', error);
                        toastr.error(`Error: ${error.message}`);
                    } finally {
                        this.isCreatingOrder = false;
                    }
                },

                // Métodos de notificación removidos ya que usamos toastr
            }));
        });
    </script>
</x-layout.default>
