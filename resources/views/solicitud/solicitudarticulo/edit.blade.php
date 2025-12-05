<x-layout.default>
    <!-- Incluir Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

    <div x-data="solicitudArticuloEdit(
        {{ Js::from($solicitud) }},
        {{ Js::from($productosActuales) }},
        {{ Js::from($articulos) }},
        {{ Js::from($cotizacionesAprobadas) }},
        {{ Js::from($cotizacionActual) }},
        {{ Js::from($productosCotizacion) }},
        {{ Js::from($areas) }},
        {{ Js::from($usuarios) }}
    )" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="mx-auto px-4 w-full">
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline">Solicitudes</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Editar Solicitudes Artículos</span>
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
                                Editar Solicitud de Artículos
                            </h1>
                            <p class="text-gray-600 text-lg">Actualice la información de la solicitud de artículos
                                existente</p>

                            <!-- Mostrar código de cotización si existe -->
                            <div x-show="solicitud.codigo_cotizacion" class="mt-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Cotización: <span x-text="solicitud.codigo_cotizacion"
                                        class="font-bold ml-1"></span>
                                </span>
                            </div>
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
                                    <p class="text-sm text-gray-500">Fecha Actualización</p>
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
                                    <p class="font-semibold text-gray-900" x-text="solicitud.estado"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Número de Solicitud -->
                    <div class="mt-6 lg:mt-0 lg:ml-6">
                        <div
                            class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-center shadow-lg border border-blue-200">
                            <p class="text-white/80 text-sm font-medium mb-1">Solicitud N°</p>
                            <div class="text-2xl lg:text-3xl font-black text-white tracking-wide"
                                x-text="solicitud.codigo"></div>
                            <div class="w-12 h-1 bg-white/30 rounded-full mx-auto mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Columna Principal -->
                <div class="xl:col-span-3 space-y-6 sm:space-y-8">
                    <!-- Selección de Artículos -->
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-4">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div
                                    class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-box text-white text-sm sm:text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-white">Selección de Artículos</h2>
                                    <p class="text-white/80 text-xs sm:text-sm">Actualice los artículos o materiales que
                                        necesita</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <!-- Selección de Cotización Aprobada -->
                            <div class="bg-green-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-green-200 mb-6"
                                x-show="!solicitud.codigo_cotizacion">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Cargar Artículos desde
                                    Cotización Aprobada</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <!-- Selección de Cotización -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3">Seleccionar
                                            Cotización</label>
                                        <select x-model="selectedCotizacion" x-ref="cotizacionSelect"
                                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                                            <option value="">Seleccione una cotización aprobada...</option>
                                            <template x-for="cotizacion in cotizacionesAprobadas"
                                                :key="cotizacion.idCotizaciones">
                                                <option :value="cotizacion.idCotizaciones"
                                                    x-text="`${cotizacion.numero_cotizacion} - ${cotizacion.cliente_nombre} (${formatDate(cotizacion.fecha_emision)})`">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Información de la Cotización Seleccionada -->
                                    <div x-show="selectedCotizacionInfo"
                                        class="bg-white rounded-lg p-3 sm:p-4 border border-green-300">
                                        <h4 class="font-semibold text-green-700 text-sm sm:text-base mb-2">Información
                                            de Cotización</h4>
                                        <div class="space-y-1 text-xs sm:text-sm">
                                            <p><span class="font-medium">Número:</span> <span
                                                    x-text="selectedCotizacionInfo.numero_cotizacion"></span></p>
                                            <p><span class="font-medium">Cliente:</span> <span
                                                    x-text="selectedCotizacionInfo.cliente_nombre"></span></p>
                                            <p><span class="font-medium">Fecha:</span> <span
                                                    x-text="formatDate(selectedCotizacionInfo.fecha_emision)"></span>
                                            </p>
                                            <p><span class="font-medium">Artículos:</span> <span
                                                    x-text="cotizacionProducts.length"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lista de Productos de la Cotización -->
                                <div x-show="cotizacionProducts.length > 0" class="mt-6">
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Artículos en la
                                        Cotización</h4>
                                    <div class="overflow-x-auto rounded-xl border border-green-200">
                                        <table class="w-full min-w-[600px] lg:min-w-full">
                                            <thead class="bg-green-50">
                                                <tr>
                                                    <th
                                                        class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-green-600">
                                                        <span class="hidden sm:inline">Artículo</span>
                                                        <span class="sm:hidden">Art.</span>
                                                    </th>
                                                    <th
                                                        class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-green-600">
                                                        <span class="hidden sm:inline">Código</span>
                                                        <span class="sm:hidden">Cód.</span>
                                                    </th>
                                                    <th
                                                        class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-green-600">
                                                        <span class="hidden sm:inline">Tipo</span>
                                                    </th>
                                                    <th
                                                        class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-green-600">
                                                        <span class="hidden sm:inline">Cantidad</span>
                                                        <span class="sm:hidden">Cant.</span>
                                                    </th>
                                                    <th
                                                        class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-green-600">
                                                        Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-green-100">
                                                <template x-for="(product, index) in cotizacionProducts"
                                                    :key="index">
                                                    <tr class="hover:bg-green-50 transition-colors">
                                                        <td class="px-3 sm:px-4 py-2 sm:py-3">
                                                            <div class="space-y-1">
                                                                <div class="text-xs sm:text-sm font-medium text-gray-900"
                                                                    x-text="getArticuloNombre(product.articulo_id)">
                                                                </div>
                                                                <div class="text-xs text-gray-500 space-y-0.5">
                                                                    <div class="flex items-center space-x-1"
                                                                        x-show="getArticuloTipo(product.articulo_id)">
                                                                        <span
                                                                            class="font-medium hidden sm:inline">Tipo:</span>
                                                                        <span class="sm:hidden">T:</span>
                                                                        <span
                                                                            x-text="getArticuloTipo(product.articulo_id)"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-green-600 font-mono"
                                                            x-text="getArticuloCodigo(product.articulo_id)"></td>
                                                        <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700"
                                                            x-text="getArticuloTipo(product.articulo_id)"></td>
                                                        <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700"
                                                            x-text="product.cantidad"></td>
                                                        <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">
                                                            <button @click="addProductFromCotizacion(product)"
                                                                class="bg-green-500 text-white px-2 sm:px-4 py-1 sm:py-2 rounded-lg hover:bg-green-600 transition-colors font-medium text-xs sm:text-sm flex items-center space-x-1 sm:space-x-2 whitespace-nowrap">
                                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                                <span class="hidden sm:inline">Agregar</span>
                                                                <span class="sm:hidden">+</span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Botón para agregar todos los productos -->
                                    <div class="mt-4 flex justify-end">
                                        <button @click="addAllCotizacionProducts()"
                                            class="bg-blue-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-600 transition-colors font-bold flex items-center space-x-2 text-sm sm:text-base">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                </path>
                                            </svg>
                                            <span class="hidden sm:inline">Agregar Todos los Artículos</span>
                                            <span class="sm:hidden">Agregar Todos</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Cotización Actual (si existe) -->
                            <div x-show="solicitud.codigo_cotizacion && cotizacionActual"
                                class="bg-blue-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-200 mb-6">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Información de
                                    Cotización Actual</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-600">Número de Cotización
                                        </p>
                                        <p class="text-base sm:text-lg font-bold text-blue-700"
                                            x-text="cotizacionActual.numero_cotizacion"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-600">Cliente</p>
                                        <p class="text-base sm:text-lg font-semibold text-gray-900"
                                            x-text="cotizacionActual.cliente_nombre"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-600">Fecha Emisión</p>
                                        <p class="text-base sm:text-lg font-semibold text-gray-900"
                                            x-text="formatDate(cotizacionActual.fecha_emision)"></p>
                                    </div>
                                </div>
                                <div class="mt-3 sm:mt-4">
                                    <p class="text-xs sm:text-sm text-gray-600 italic">
                                        Esta solicitud está vinculada a una cotización. Los artículos no pueden ser
                                        modificados desde otras cotizaciones.
                                    </p>
                                </div>
                            </div>

                            <!-- Tabla de Artículos -->
                            <div class="mb-6 sm:mb-8">
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-4 sm:mb-6">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Artículos Seleccionados
                                    </h3>
                                    <div class="flex items-center space-x-2 sm:space-x-4">
                                        <span class="text-xs sm:text-sm text-gray-600"
                                            x-text="`${totalUniqueProducts} artículo${totalUniqueProducts !== 1 ? 's' : ''}`"></span>
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"
                                            x-show="products.length > 0"></div>
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-blue-100">
                                    <table class="w-full min-w-[600px] lg:min-w-full">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th
                                                    class="px-4 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    <span class="hidden sm:inline">Artículo</span>
                                                    <span class="sm:hidden">Art.</span>
                                                </th>
                                                <th
                                                    class="px-4 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    <span class="hidden sm:inline">Código</span>
                                                    <span class="sm:hidden">Cód.</span>
                                                </th>
                                                <th
                                                    class="px-4 sm:px-6 py-2 sm:py-4 text-center text-xs sm:text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    <span class="hidden sm:inline">Cantidad</span>
                                                    <span class="sm:hidden">Cant.</span>
                                                </th>
                                                <th
                                                    class="px-4 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-blue-100">
                                            <template x-if="products.length === 0">
                                                <tr>
                                                    <td colspan="4"
                                                        class="px-4 sm:px-6 py-8 sm:py-12 text-center text-gray-500">
                                                        <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-300"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                            </path>
                                                        </svg>
                                                        <p
                                                            class="mt-3 sm:mt-4 text-base sm:text-lg font-medium text-gray-900">
                                                            No hay
                                                            artículos agregados</p>
                                                        <p class="text-xs sm:text-sm mt-1 sm:mt-2">Agregue artículos
                                                            usando el formulario
                                                            inferior o desde una cotización aprobada</p>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="(product, index) in products" :key="product.uniqueId">
                                                <tr class="product-row hover:bg-blue-50 transition-all duration-200">
                                                    <!-- Columna Artículo con información completa -->
                                                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                        <div class="space-y-1">
                                                            <!-- Nombre principal -->
                                                            <div class="text-sm sm:text-base font-semibold text-gray-900 truncate max-w-[150px] sm:max-w-none"
                                                                :title="product.nombre" x-text="product.nombre"></div>

                                                            <!-- Información adicional -->
                                                            <div class="text-xs text-gray-500 space-y-0.5">
                                                                <!-- Tipo de artículo -->
                                                                <div class="flex items-center space-x-1"
                                                                    x-show="product.tipo_articulo">
                                                                    <span
                                                                        class="font-medium hidden sm:inline">Tipo:</span>
                                                                    <span class="sm:hidden">T:</span>
                                                                    <span x-text="product.tipo_articulo"></span>
                                                                </div>

                                                                <!-- Modelo -->
                                                                <div class="flex items-center space-x-1"
                                                                    x-show="product.modelo">
                                                                    <span
                                                                        class="font-medium hidden sm:inline">Modelo:</span>
                                                                    <span class="sm:hidden">M:</span>
                                                                    <span x-text="product.modelo"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Columna Código -->
                                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                                        <div class="text-sm sm:text-base text-blue-600 font-mono font-bold"
                                                            x-text="product.codigo"></div>
                                                        <div class="text-xs text-gray-400 mt-1 hidden sm:block"
                                                            x-text="product.codigo_barras ? 'Cód. Barras' : 'Cód. Repuesto'">
                                                        </div>
                                                        <div class="text-xs text-gray-400 mt-1 sm:hidden"
                                                            x-text="product.codigo_barras ? 'Barras' : 'Repuesto'">
                                                        </div>
                                                    </td>

                                                    <!-- Columna Cantidad -->
                                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                                        <div
                                                            class="flex items-center space-x-2 sm:space-x-3 justify-center">
                                                            <span class="font-bold text-base sm:text-lg"
                                                                x-text="product.cantidad"></span>
                                                            <div class="flex space-x-1">
                                                                <button @click="updateQuantity(index, -1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors"
                                                                    :disabled="product.cantidad <= 1"
                                                                    :class="{
                                                                        'opacity-50 cursor-not-allowed': product
                                                                            .cantidad <= 1
                                                                    }">
                                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <button @click="updateQuantity(index, 1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors"
                                                                    :disabled="product.cantidad >= 1000"
                                                                    :class="{
                                                                        'opacity-50 cursor-not-allowed': product
                                                                            .cantidad >= 1000
                                                                    }">
                                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
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

                                                    <!-- Columna Acciones -->
                                                    <td
                                                        class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm sm:text-base">
                                                        <button @click="removeProduct(index)"
                                                            class="text-red-600 hover:text-red-700 font-semibold flex items-center space-x-1 sm:space-x-2 transition-colors p-1 sm:p-2 rounded-lg hover:bg-red-50">
                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Eliminar</span>
                                                            <span class="sm:hidden">Elim.</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Formulario para Agregar Artículo -->
                            <div class="bg-blue-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-200"
                                x-show="!solicitud.codigo_cotizacion">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6">Agregar Nuevo
                                    Artículo Manualmente</h3>

                                <div
                                    class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
                                    <!-- Artículo -->
                                    <div class="lg:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Artículo</label>
                                        <select x-model="newProduct.articuloId" x-ref="articuloSelect"
                                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                                            <option value="">Seleccione un artículo...</option>
                                            <template x-if="articulos && articulos.length > 0">
                                                <template x-for="articulo in articulos" :key="articulo.idArticulos">
                                                    <option :value="articulo.idArticulos"
                                                        :data-codigo="articulo.codigo_barras || articulo.codigo_repuesto"
                                                        :data-tipo="articulo.tipo_articulo"
                                                        x-text="articulo.nombre_completo">
                                                    </option>
                                                </template>
                                            </template>
                                        </select>
                                        <div x-show="!articulos || articulos.length === 0"
                                            class="text-red-500 text-xs sm:text-sm mt-2">
                                            No hay artículos disponibles
                                        </div>
                                    </div>

                                    <!-- Cantidad -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                                        <div class="flex w-full">
                                            <button @click="decreaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center rounded-l-lg px-3 sm:px-4 font-semibold border border-r-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" x-model="newProduct.cantidad" min="1"
                                                max="1000"
                                                class="w-12 sm:w-16 text-center border-y border-blue-600 focus:ring-0 bg-white text-gray-900 font-semibold text-sm sm:text-base"
                                                readonly />
                                            <button @click="increaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center rounded-r-lg px-3 sm:px-4 font-semibold border border-l-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2 text-center">Mín: 1 - Máx: 1000</div>
                                    </div>

                                    <!-- Descripción -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                                        <input type="text" x-model="newProduct.descripcion"
                                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Descripción adicional...">
                                    </div>
                                </div>

                                <button @click="addProduct()" :disabled="!canAddProduct"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !canAddProduct,
                                        'hover:scale-[1.02]': canAddProduct
                                    }"
                                    class="w-full bg-blue-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-lg font-bold transition-all duration-200 flex items-center justify-center space-x-2 sm:space-x-3 shadow-lg text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="text-sm sm:text-lg">Agregar Artículo a la Solicitud</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-4">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div
                                    class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-info-circle text-white text-sm sm:text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-1 sm:gap-2">
                                        <h2 class="text-lg sm:text-xl font-bold text-white drop-shadow-sm">Información
                                            Adicional</h2>
                                        <span
                                            class="px-2 py-1 bg-white/20 rounded-full text-white text-xs font-medium border border-white/30">
                                            <span class="hidden sm:inline">Requerido</span>
                                            <span class="sm:hidden">Req.</span>
                                        </span>
                                    </div>
                                    <p class="text-white/80 text-xs sm:text-sm mt-1">Actualice los detalles de la
                                        solicitud</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <!-- Nuevos campos: Área Destino y Usuario Destino -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 sm:mb-8">
                                <!-- Área Destino -->
                                <div>
                                    <label class="block text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">
                                        <i
                                            class="fas fa-building text-green-500 text-sm sm:text-base mr-1 sm:mr-2"></i>
                                        Área Destino
                                    </label>
                                    <select x-model="orderInfo.areaDestino"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white text-sm sm:text-base">
                                        <option value="">Seleccione un área...</option>
                                        <template x-for="area in areas" :key="area.idTipoArea">
                                            <option :value="area.idTipoArea" x-text="area.nombre"
                                                class="text-sm sm:text-base"></option>
                                        </template>
                                    </select>
                                    <div class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-info-circle text-green-400 mr-1 text-xs"></i>
                                        <span class="hidden sm:inline">Área donde se utilizarán los artículos</span>
                                        <span class="sm:hidden">Área de uso</span>
                                    </div>
                                </div>

                                <!-- Usuario Destino -->
                                <div>
                                    <label class="block text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">
                                        <i class="fas fa-user text-purple-500 text-sm sm:text-base mr-1 sm:mr-2"></i>
                                        Usuario Destino
                                    </label>
                                    <select x-model="orderInfo.usuarioDestino"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white text-sm sm:text-base">
                                        <option value="">Seleccione un usuario...</option>
                                        <template x-for="usuario in usuariosFiltrados" :key="usuario.idUsuario">
                                            <option :value="usuario.idUsuario"
                                                x-text="`${usuario.Nombre} ${usuario.apellidoPaterno}`"
                                                class="text-sm sm:text-base">
                                            </option>
                                        </template>
                                    </select>
                                    <div x-show="orderInfo.areaDestino && usuariosFiltrados.length === 0"
                                        class="text-yellow-600 text-xs sm:text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1 text-xs"></i>
                                        No hay usuarios disponibles en esta área
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-info-circle text-purple-400 mr-1 text-xs"></i>
                                        <span class="hidden sm:inline">Usuario que recibirá los artículos</span>
                                        <span class="sm:hidden">Destinatario</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Tipo de Servicio -->
                                <div>
                                    <label class="block text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">
                                        <i class="fas fa-tools text-blue-500 text-sm sm:text-base mr-1 sm:mr-2"></i>
                                        Tipo de Servicio
                                    </label>
                                    <div class="relative">
                                        <select x-model="orderInfo.tipoServicio"
                                            class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white cursor-pointer shadow-sm hover:shadow-md text-sm sm:text-base">
                                            <option value="solicitud_articulo">Solicitud de Artículo</option>
                                            <option value="mantenimiento">Mantenimiento</option>
                                            <option value="reparacion">Reparación</option>
                                            <option value="instalacion">Instalación</option>
                                            <option value="garantia">Garantía</option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tools text-blue-500 text-sm sm:text-base"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fecha Requerida -->
                                <div>
                                    <label class="block text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">
                                        <i
                                            class="fas fa-calendar-day text-blue-500 text-sm sm:text-base mr-1 sm:mr-2"></i>
                                        Fecha Requerida
                                    </label>
                                    <div class="relative">
                                        <input type="text" x-ref="fechaRequeridaInput"
                                            x-model="orderInfo.fechaRequerida"
                                            class="w-full pr-10 sm:pr-11 pl-3 sm:pl-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 transition-all duration-200 shadow-sm hover:shadow-md text-sm sm:text-base"
                                            placeholder="Seleccione fecha">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400 text-sm sm:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-info-circle text-blue-400 mr-1 text-xs"></i>
                                        <span class="hidden sm:inline">Fecha límite para tener todos los
                                            artículos</span>
                                        <span class="sm:hidden">Fecha límite</span>
                                    </div>
                                    <div class="mt-2 sm:mt-3 bg-blue-50 border border-blue-200 rounded-lg p-2 sm:p-3 transition-all duration-300"
                                        x-show="orderInfo.fechaRequerida"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100">
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check-circle text-blue-600 text-base sm:text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs sm:text-sm font-semibold text-blue-800">Fecha
                                                    establecida</p>
                                                <p class="text-xs sm:text-sm text-blue-700 font-medium">
                                                    <span
                                                        x-text="formatDateForDisplay(orderInfo.fechaRequerida)"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nivel de Urgencia -->
                            <div class="mt-6 sm:mt-8 space-y-3 sm:space-y-4">
                                <label class="block text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">
                                    <i class="fas fa-gauge-high text-blue-500 text-sm sm:text-base mr-1 sm:mr-2"></i>
                                    Nivel de Urgencia
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                                    <template x-for="(urgencia, index) in nivelesUrgencia" :key="index">
                                        <button type="button" @click="orderInfo.urgencia = urgencia.value"
                                            class="group relative text-left transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                                            <div class="p-4 sm:p-5 rounded-xl border-2 transition-all duration-300 h-full shadow-sm hover:shadow-md"
                                                :class="{
                                                    [urgencia.borderColor + ' ' + urgencia.bgColor]: orderInfo
                                                        .urgencia === urgencia.value,
                                                        'border-gray-200 bg-gray-50 hover:border-gray-300': orderInfo
                                                        .urgencia !== urgencia.value
                                                }">

                                                <!-- Header con icono y título -->
                                                <div class="flex items-center justify-between mb-2 sm:mb-3">
                                                    <div class="flex items-center space-x-1 sm:space-x-2">
                                                        <i class="fas"
                                                            :class="urgencia.icon + ' ' + urgencia.iconColor +
                                                                ' text-base sm:text-xl'"></i>
                                                        <h3 class="font-bold text-gray-900 text-sm sm:text-lg"
                                                            x-text="urgencia.text"></h3>
                                                    </div>
                                                    <div class="w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center transition-all duration-300"
                                                        :class="{
                                                            [urgencia.iconColor]: orderInfo.urgencia === urgencia.value,
                                                                'text-gray-400': orderInfo.urgencia !== urgencia.value
                                                        }">
                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor"
                                                            viewBox="0 0 20 20">
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
                                                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed"
                                                    x-text="urgencia.description"></p>

                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-6 sm:mt-8">
                                <label
                                    class="block text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Observaciones
                                    y Comentarios</label>
                                <textarea x-model="orderInfo.observaciones" rows="4"
                                    class="w-full px-3 sm:px-4 py-2 sm:py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 resize-none transition-all duration-200 text-sm sm:text-base"
                                    placeholder="Describa cualquier observación, comentario adicional o instrucción especial para esta solicitud..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="xl:col-span-1 space-y-8">
                    <!-- Resumen de la Solicitud - Versión Responsive Mejorada -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100">
                        <!-- Header con icono -->
                        <div class="flex items-center justify-center mb-4 sm:mb-6">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-clipboard-check text-white text-sm sm:text-base"></i>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Resumen de Solicitud</h3>
                        </div>

                        <!-- Items del resumen -->
                        <div class="space-y-3 sm:space-y-4">
                            <!-- Artículos Únicos -->
                            <div
                                class="flex justify-between items-center p-3 sm:p-4 bg-blue-50 rounded-lg sm:rounded-xl border border-blue-100 hover:bg-blue-100 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <i class="fas fa-cube text-blue-600 text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <span class="text-gray-700 font-medium text-xs sm:text-sm block">Artículos
                                            Únicos</span>
                                        <span class="text-gray-500 text-xs hidden sm:block">Cantidad de artículos
                                            diferentes</span>
                                    </div>
                                </div>
                                <span class="text-xl sm:text-2xl font-bold text-blue-600"
                                    x-text="totalUniqueProducts"></span>
                            </div>

                            <!-- Total Cantidad -->
                            <div
                                class="flex justify-between items-center p-3 sm:p-4 bg-green-50 rounded-lg sm:rounded-xl border border-green-100 hover:bg-green-100 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <i class="fas fa-boxes text-green-600 text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <span class="text-gray-700 font-medium text-xs sm:text-sm block">Total
                                            Cantidad</span>
                                        <span class="text-gray-500 text-xs hidden sm:block">Suma total de
                                            unidades</span>
                                    </div>
                                </div>
                                <span class="text-xl sm:text-2xl font-bold text-green-600"
                                    x-text="totalQuantity"></span>
                            </div>

                            <!-- Fecha Requerida -->
                            <div
                                class="flex justify-between items-center p-3 sm:p-4 bg-orange-50 rounded-lg sm:rounded-xl border border-orange-100 hover:bg-orange-100 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-orange-600 text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <span class="text-gray-700 font-medium text-xs sm:text-sm block">Fecha
                                            Requerida</span>
                                        <span class="text-gray-500 text-xs hidden sm:block">Fecha límite de
                                            entrega</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm sm:text-lg font-bold text-orange-600 block"
                                        x-text="orderInfo.fechaRequerida ? formatDateForDisplay(orderInfo.fechaRequerida) : 'No definida'"></span>
                                    <span class="text-xs text-gray-500 hidden sm:block"
                                        x-show="orderInfo.fechaRequerida">
                                        <span x-text="getDaysFromNow(orderInfo.fechaRequerida)"></span> días
                                    </span>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div
                                class="flex justify-between items-center p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl border border-gray-100 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <i class="fas fa-flag text-gray-600 text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <span class="text-gray-700 font-medium text-xs sm:text-sm block">Estado
                                            Actual</span>
                                        <span class="text-gray-500 text-xs hidden sm:block">Estado de la
                                            solicitud</span>
                                    </div>
                                </div>
                                <span
                                    class="px-2 sm:px-3 py-1 bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 rounded-full text-xs sm:text-sm font-bold border border-yellow-200 shadow-sm"
                                    x-text="solicitud.estado ? solicitud.estado.charAt(0).toUpperCase() + solicitud.estado.slice(1) : 'Pendiente'"></span>
                            </div>

                            <!-- Información adicional (solo visible en desktop) -->
                            <div class="hidden sm:block mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200">
                                <div class="text-center">
                                    <div
                                        class="inline-flex items-center space-x-2 bg-blue-50 px-4 py-2 rounded-xl border border-blue-200">
                                        <i class="fas fa-info-circle text-blue-500"></i>
                                        <span class="text-sm text-gray-600">Actualice el resumen con los cambios
                                            realizados</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Destino -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100"
                        x-show="orderInfo.areaDestino || orderInfo.usuarioDestino">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Información de Destino</h3>
                        <div class="space-y-4">
                            <div x-show="orderInfo.areaDestino"
                                class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Área Destino</span>
                                <span class="text-sm font-semibold text-green-700 text-right"
                                    x-text="getAreaNombre(orderInfo.areaDestino)"></span>
                            </div>
                            <div x-show="orderInfo.usuarioDestino" class="flex justify-between items-center py-3">
                                <span class="text-gray-700 font-medium">Usuario Destino</span>
                                <span class="text-sm font-semibold text-purple-700 text-right"
                                    x-text="getUsuarioNombre(orderInfo.usuarioDestino)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Acciones</h3>
                        <div class="space-y-4">
                            <button @click="clearAll()" :disabled="products.length === 0 || isUpdatingSolicitud"
                                :class="{ 'opacity-50 cursor-not-allowed': products.length === 0 || isUpdatingSolicitud }"
                                class="w-full px-6 py-4 bg-warning text-white rounded-lg font-bold hover:bg-yellow-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span>Limpiar Todo</span>
                            </button>

                            @if (\App\Helpers\PermisoHelper::tienePermiso('ACTUALIZAR SOLICITUD ARTICULO'))
                                <button @click="updateSolicitud()"
                                    :disabled="!canCreateSolicitud || isUpdatingSolicitud"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !canCreateSolicitud || isUpdatingSolicitud,
                                        'hover:scale-[1.02]': canCreateSolicitud && !isUpdatingSolicitud
                                    }"
                                    class="w-full px-6 py-4 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
                                    <template x-if="!isUpdatingSolicitud">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                    <template x-if="isUpdatingSolicitud">
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                    </template>
                                    <span class="text-lg"
                                        x-text="isUpdatingSolicitud ? 'Actualizando Solicitud...' : 'Actualizar Solicitud'"></span>
                                </button>
                            @endif

                            <a href="{{ route('solicitudarticulo.index') }}"
                                class="w-full px-6 py-4 bg-gray-500 text-white rounded-lg font-bold hover:bg-gray-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Volver al Listado</span>
                            </a>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-200">
                        <h4 class="text-lg font-bold text-blue-700 mb-6 text-center">¿Necesita Ayuda?</h4>
                        <div class="space-y-4">
                            <!-- Teléfono -->
                            <div
                                class="flex items-center space-x-4 p-4 bg-white/80 rounded-xl border border-blue-100 backdrop-blur-sm">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600">Soporte Técnico</p>
                                    <p class="text-lg font-bold text-gray-900">+1 (555) 123-4567</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div
                                class="flex items-center space-x-4 p-4 bg-white/80 rounded-xl border border-blue-100 backdrop-blur-sm">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600">Email</p>
                                    <p class="text-lg font-bold text-gray-900">soporte@empresa.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudArticuloEdit', (
                solicitud,
                productosActuales,
                articulos,
                cotizacionesAprobadas,
                cotizacionActual,
                productosCotizacion,
                areas,
                usuarios
            ) => ({
                // Estado de la aplicación
                currentDate: '',
                solicitud: solicitud,
                products: [],
                newProduct: {
                    articuloId: '',
                    cantidad: 1,
                    descripcion: ''
                },
                orderInfo: {
                    tipoServicio: solicitud.tiposervicio || 'solicitud_articulo',
                    urgencia: solicitud.urgencia || '',
                    observaciones: solicitud.observaciones || '',
                    fechaRequerida: solicitud.fecharequerida ? solicitud.fecharequerida.split(' ')[0] :
                        '',
                    areaDestino: solicitud.id_area_destino || '',
                    usuarioDestino: solicitud.id_usuario_destino || ''
                },
                isUpdatingSolicitud: false,
                articulos: articulos,

                // Nuevas variables para áreas y usuarios
                areas: areas,
                usuarios: usuarios,
                usuariosFiltrados: [],

                // Variables para cotizaciones
                cotizacionesAprobadas: cotizacionesAprobadas,
                selectedCotizacion: '',
                selectedCotizacionInfo: null,
                cotizacionProducts: [],
                cotizacionActual: cotizacionActual,
                productosCotizacion: productosCotizacion,

                // Niveles de urgencia
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
                    return this.products.length;
                },
                get canAddProduct() {
                    return this.newProduct.articuloId &&
                        this.newProduct.cantidad > 0;
                },
                get canCreateSolicitud() {
                    return this.products.length > 0 &&
                        this.orderInfo.tipoServicio &&
                        this.orderInfo.urgencia &&
                        this.orderInfo.fechaRequerida &&
                        this.orderInfo.areaDestino &&
                        this.orderInfo.usuarioDestino;
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

                    // Cargar productos actuales
                    this.loadExistingProducts();

                    // Filtrar usuarios basado en el área destino actual
                    this.filtrarUsuariosPorArea(this.orderInfo.areaDestino);

                    this.$nextTick(() => {
                        this.initSelect2();
                        this.initFlatpickr();
                    });

                    // Watch para cambios en área destino
                    this.$watch('orderInfo.areaDestino', (value) => {
                        this.filtrarUsuariosPorArea(value);
                    });
                },

                filtrarUsuariosPorArea(areaId) {
                    if (!areaId) {
                        this.usuariosFiltrados = [];
                        return;
                    }

                    this.usuariosFiltrados = this.usuarios.filter(usuario =>
                        usuario.idTipoArea == areaId
                    );
                },

                getAreaNombre(areaId) {
                    const area = this.areas.find(a => a.idTipoArea == areaId);
                    return area ? area.nombre : 'No especificado';
                },

                getUsuarioNombre(usuarioId) {
                    const usuario = this.usuarios.find(u => u.idUsuario == usuarioId);
                    return usuario ? `${usuario.Nombre} ${usuario.apellidoPaterno}` : 'No especificado';
                },

                loadExistingProducts() {
                    if (productosActuales && productosActuales.length > 0) {
                        this.products = productosActuales.map(product => ({
                            uniqueId: Date.now() + Math.random(),
                            articuloId: product.idarticulos,
                            nombre: product.nombre,
                            codigo: product.codigo_barras || product.codigo_repuesto,
                            codigo_barras: product.codigo_barras,
                            codigo_repuesto: product.codigo_repuesto,
                            tipo_articulo: product.tipo_articulo_nombre,
                            modelo: product.nombre_modelo,
                            marca: product.nombre_marca,
                            subcategoria: product.nombre_subcategoria,
                            cantidad: product.cantidad,
                            descripcion: product.descripcion
                        }));
                    }
                },

                initSelect2() {
                    // Artículo Select
                    if (this.$refs.articuloSelect) {
                        $(this.$refs.articuloSelect).select2({
                            placeholder: 'Buscar artículo...',
                            language: 'es',
                            width: '100%'
                        }).on('change', (e) => {
                            this.newProduct.articuloId = e.target.value;
                        });
                    }

                    // Cotización Select (solo si no tiene cotización actual)
                    if (this.$refs.cotizacionSelect && !this.solicitud.codigo_cotizacion) {
                        $(this.$refs.cotizacionSelect).select2({
                            placeholder: 'Buscar cotización...',
                            language: 'es',
                            width: '100%'
                        }).on('change', (e) => {
                            this.selectedCotizacion = e.target.value;
                            this.loadCotizacionProducts();
                        });
                    }
                },

                initFlatpickr() {
                    // Inicializar Flatpickr para el campo de fecha requerida
                    if (this.$refs.fechaRequeridaInput) {
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
                                    instance.setDate(this.orderInfo.fechaRequerida);
                                }
                            }
                        });
                    }
                },

                async loadCotizacionProducts() {
                    if (!this.selectedCotizacion) {
                        this.cotizacionProducts = [];
                        this.selectedCotizacionInfo = null;
                        return;
                    }

                    try {
                        // Obtener información de la cotización seleccionada
                        this.selectedCotizacionInfo = this.cotizacionesAprobadas.find(
                            c => c.idCotizaciones == this.selectedCotizacion
                        );

                        if (!this.selectedCotizacionInfo) {
                            toastr.error('Cotización no encontrada');
                            return;
                        }

                        // Cargar productos de la cotización
                        const response = await fetch(
                            `/api/cotizacion-productos/${this.selectedCotizacion}`);

                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }

                        const data = await response.json();

                        if (data.success) {
                            this.cotizacionProducts = data.products;
                            toastr.success(
                                `${data.products.length} artículos cargados de la cotización`);
                        } else {
                            throw new Error(data.message || 'Error desconocido');
                        }
                    } catch (error) {
                        console.error('Error cargando productos de cotización:', error);
                        toastr.error('Error al cargar los productos de la cotización');
                        this.cotizacionProducts = [];
                        this.selectedCotizacionInfo = null;
                    }
                },

                addProductFromCotizacion(cotizacionProduct) {
                    // Buscar el artículo completo en la lista de artículos
                    const articuloCompleto = this.articulos.find(
                        a => a.idArticulos == cotizacionProduct.articulo_id
                    );

                    if (!articuloCompleto) {
                        toastr.error('Artículo no encontrado en el catálogo');
                        return;
                    }

                    // Verificar si ya existe el artículo en la solicitud
                    const existingProductIndex = this.products.findIndex(
                        product => product.articuloId == cotizacionProduct.articulo_id
                    );

                    if (existingProductIndex !== -1) {
                        // Si existe, verificar que no exceda la cantidad de la cotización
                        const nuevaCantidad = this.products[existingProductIndex].cantidad + 1;

                        // Obtener la cantidad máxima permitida desde la cotización
                        const cantidadMaxima = cotizacionProduct.cantidad;

                        if (nuevaCantidad > cantidadMaxima) {
                            toastr.error(
                                `No puede exceder la cantidad de la cotización: ${cantidadMaxima} unidades`
                                );
                            return;
                        }

                        this.products[existingProductIndex].cantidad = nuevaCantidad;
                        toastr.success(
                            `Cantidad actualizada: ${this.products[existingProductIndex].cantidad}/${cantidadMaxima} unidades`
                            );
                    } else {
                        // Si no existe, agregar nuevo producto con TODA la información del artículo
                        const product = {
                            uniqueId: Date.now() + Math.random(),
                            articuloId: cotizacionProduct.articulo_id,
                            nombre: articuloCompleto.nombre,
                            codigo: articuloCompleto.codigo_barras || articuloCompleto
                                .codigo_repuesto,
                            codigo_barras: articuloCompleto.codigo_barras,
                            codigo_repuesto: articuloCompleto.codigo_repuesto,
                            tipo_articulo: articuloCompleto.tipo_articulo,
                            modelo: articuloCompleto.modelo,
                            marca: articuloCompleto.marca,
                            subcategoria: articuloCompleto.subcategoria,
                            cantidad: 1, // Siempre empezar con 1
                            descripcion: cotizacionProduct.descripcion ||
                                (this.selectedCotizacionInfo ?
                                    `Desde cotización: ${this.selectedCotizacionInfo.numero_cotizacion}` :
                                    'Desde cotización'),
                            cantidadCotizacion: cotizacionProduct
                                .cantidad, // Guardar la cantidad máxima
                            esDeCotizacion: true // Marcar que viene de cotización
                        };

                        this.products.push(product);
                        toastr.success(
                            `Artículo agregado desde cotización (1/${cotizacionProduct.cantidad} unidades)`
                            );
                    }
                },

                addAllCotizacionProducts() {
                    if (this.cotizacionProducts.length === 0) {
                        toastr.info('No hay artículos para agregar');
                        return;
                    }

                    let addedCount = 0;
                    let skippedCount = 0;

                    this.cotizacionProducts.forEach(cotizacionProduct => {
                        const articuloCompleto = this.articulos.find(
                            a => a.idArticulos == cotizacionProduct.articulo_id
                        );

                        if (articuloCompleto) {
                            const existingProductIndex = this.products.findIndex(
                                product => product.articuloId == cotizacionProduct
                                .articulo_id
                            );

                            if (existingProductIndex !== -1) {
                                // Si ya existe, verificar límite
                                const product = this.products[existingProductIndex];
                                if (product.cantidad < cotizacionProduct.cantidad) {
                                    product.cantidad += 1;
                                    addedCount++;
                                } else {
                                    skippedCount++;
                                }
                            } else {
                                // Agregar nuevo producto con TODA la información
                                const product = {
                                    uniqueId: Date.now() + Math.random(),
                                    articuloId: cotizacionProduct.articulo_id,
                                    nombre: articuloCompleto.nombre,
                                    codigo: articuloCompleto.codigo_barras ||
                                        articuloCompleto.codigo_repuesto,
                                    codigo_barras: articuloCompleto.codigo_barras,
                                    codigo_repuesto: articuloCompleto.codigo_repuesto,
                                    tipo_articulo: articuloCompleto.tipo_articulo,
                                    modelo: articuloCompleto.modelo,
                                    marca: articuloCompleto.marca,
                                    subcategoria: articuloCompleto.subcategoria,
                                    cantidad: 1,
                                    descripcion: cotizacionProduct.descripcion ||
                                        (this.selectedCotizacionInfo ?
                                            `Desde cotización: ${this.selectedCotizacionInfo.numero_cotizacion}` :
                                            'Desde cotización'),
                                    cantidadCotizacion: cotizacionProduct.cantidad,
                                    esDeCotizacion: true
                                };

                                this.products.push(product);
                                addedCount++;
                            }
                        }
                    });

                    let message = `${addedCount} artículos agregados desde la cotización`;
                    if (skippedCount > 0) {
                        message += ` (${skippedCount} ya estaban en cantidad máxima)`;
                    }

                    toastr.success(message);
                },

                // Métodos auxiliares para obtener información de artículos
                getArticuloCompleto(articuloId) {
                    return this.articulos.find(a => a.idArticulos == articuloId);
                },

                getArticuloNombre(articuloId) {
                    const articulo = this.getArticuloCompleto(articuloId);
                    return articulo ? articulo.nombre : 'Artículo no encontrado';
                },

                getArticuloCodigo(articuloId) {
                    const articulo = this.getArticuloCompleto(articuloId);
                    return articulo ? (articulo.codigo_barras || articulo.codigo_repuesto) : 'N/A';
                },

                getArticuloTipo(articuloId) {
                    const articulo = this.getArticuloCompleto(articuloId);
                    return articulo ? articulo.tipo_articulo : '';
                },

                getArticuloModelo(articuloId) {
                    const articulo = this.getArticuloCompleto(articuloId);
                    return articulo ? articulo.modelo : '';
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                formatDateForDisplay(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                increaseQuantity() {
                    if (this.newProduct.cantidad < 1000) {
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
                        toastr.error('Por favor seleccione un artículo y cantidad');
                        return;
                    }

                    // Obtener información del artículo seleccionado
                    const selectedOption = $(this.$refs.articuloSelect).find('option:selected');
                    const articuloData = this.articulos.find(a => a.idArticulos == this.newProduct
                        .articuloId);

                    if (!articuloData) {
                        toastr.error('Error al obtener información del artículo');
                        return;
                    }

                    // Verificar si ya existe el artículo
                    const existingProductIndex = this.products.findIndex(product =>
                        product.articuloId === this.newProduct.articuloId
                    );

                    if (existingProductIndex !== -1) {
                        // Si existe, sumar la cantidad
                        this.products[existingProductIndex].cantidad += this.newProduct.cantidad;
                        toastr.success(
                            `Cantidad actualizada: ${this.products[existingProductIndex].cantidad} unidades`
                            );
                    } else {
                        // Si no existe, agregar nuevo producto con toda la información
                        const product = {
                            uniqueId: Date.now() + Math.random(),
                            articuloId: this.newProduct.articuloId,
                            nombre: articuloData.nombre,
                            codigo: articuloData.codigo_barras || articuloData.codigo_repuesto,
                            codigo_barras: articuloData.codigo_barras,
                            codigo_repuesto: articuloData.codigo_repuesto,
                            tipo_articulo: articuloData.tipo_articulo,
                            modelo: articuloData.modelo,
                            marca: articuloData.marca,
                            subcategoria: articuloData.subcategoria,
                            cantidad: this.newProduct.cantidad,
                            descripcion: this.newProduct.descripcion
                        };

                        this.products.push(product);
                        toastr.success('Artículo agregado correctamente');
                    }

                    // Reset form
                    this.newProduct = {
                        articuloId: '',
                        cantidad: 1,
                        descripcion: ''
                    };

                    // Reset Select2
                    if (this.$refs.articuloSelect) {
                        $(this.$refs.articuloSelect).val('').trigger('change');
                    }
                },

                removeProduct(index) {
                    this.products.splice(index, 1);
                    toastr.info('Artículo eliminado de la solicitud');
                },

                updateQuantity(index, change) {
                    const product = this.products[index];
                    const newQuantity = product.cantidad + change;

                    // Si es de cotización, validar que no exceda la cantidad original
                    if (product.esDeCotizacion && product.cantidadCotizacion) {
                        if (newQuantity > product.cantidadCotizacion) {
                            toastr.error(
                                `No puede exceder la cantidad de la cotización: ${product.cantidadCotizacion} unidades`
                                );
                            return;
                        }

                        if (newQuantity < 1) {
                            toastr.error('La cantidad mínima es 1 unidad');
                            return;
                        }
                    } else {
                        // Para artículos manuales, validaciones normales
                        if (newQuantity < 1 || newQuantity > 1000) {
                            return;
                        }
                    }

                    this.products[index].cantidad = newQuantity;

                    // Mostrar feedback si es de cotización
                    if (product.esDeCotizacion && product.cantidadCotizacion) {
                        toastr.info(`Cantidad: ${newQuantity}/${product.cantidadCotizacion} unidades`);
                    }
                },

                clearAll() {
                    if (this.products.length === 0) {
                        toastr.info('No hay artículos para limpiar');
                        return;
                    }

                    if (confirm(
                            '¿Está seguro de que desea eliminar todos los artículos de la solicitud?'
                        )) {
                        this.products = [];
                        toastr.info('Todos los artículos han sido eliminados');
                    }
                },

                async updateSolicitud() {
                    if (!this.canCreateSolicitud) {
                        toastr.error(
                            'Complete todos los campos requeridos para actualizar la solicitud');
                        return;
                    }

                    this.isUpdatingSolicitud = true;

                    try {
                        const solicitudData = {
                            orderInfo: this.orderInfo,
                            products: this.products,
                            selectedCotizacion: this.selectedCotizacion
                        };

                        console.log('Actualizando solicitud:', solicitudData);

                        const response = await fetch(
                            `/solicitudarticulo/update/${this.solicitud.idsolicitudesordenes}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(solicitudData)
                            });

                        const result = await response.json();

                        if (result.success) {
                            toastr.success(
                                `¡Solicitud ${result.codigo_orden} actualizada exitosamente!`);

                            // Redirigir después de 2 segundos
                            setTimeout(() => {
                                window.location.href = '/solicitudarticulo';
                            }, 2000);
                        } else {
                            throw new Error(result.message);
                        }

                    } catch (error) {
                        console.error('Error al actualizar la solicitud:', error);
                        toastr.error(`Error: ${error.message}`);
                    } finally {
                        this.isUpdatingSolicitud = false;
                    }
                }
            }));
        });
    </script>
</x-layout.default>
