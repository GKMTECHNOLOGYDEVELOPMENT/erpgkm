<x-layout.default title="Crear Solicitud Articulos - ERP Solutions">
    <!-- Incluir Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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

    <div x-data="solicitudArticulo()" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="container mx-auto px-4 w-full">
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline">Solicitudes</a>
                    </li>

                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Solicitud de Artículos</span>
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
                                Solicitud de Artículos
                            </h1>
                            <p class="text-gray-600 text-lg">Complete la información requerida para solicitar artículos
                                o materiales</p>
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
                            <p class="text-white/80 text-sm font-medium mb-1">Solicitud N°</p>
                            <div class="text-2xl lg:text-3xl font-black text-white tracking-wide">
                                SOL-<span x-text="orderNumber.toString().padStart(3, '0')"></span>
                            </div>
                            <div class="w-12 h-1 bg-white/30 rounded-full mx-auto mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Columna Principal -->
                <div class="xl:col-span-3 space-y-8">
                    <!-- Selección de Artículos -->
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-box text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Selección de Artículos</h2>
                                    <p class="text-white/80 text-sm">Agregue los artículos o materiales que necesita</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Selección de Cotización Aprobada -->
                            <div class="bg-green-50 rounded-2xl p-6 border border-green-200 mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Cargar Artículos desde Cotización
                                    Aprobada</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Selección de Cotización -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Seleccionar
                                            Cotización</label>
                                        <select x-model="selectedCotizacion" x-ref="cotizacionSelect" class="w-full">
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
                                        class="bg-white rounded-lg p-4 border border-green-300">
                                        <h4 class="font-semibold text-green-700 mb-2">Información de Cotización</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="font-medium">Número:</span>
                                                <span
                                                    x-text="selectedCotizacionInfo ? selectedCotizacionInfo.numero_cotizacion : ''"></span>
                                            </p>
                                            <p><span class="font-medium">Cliente:</span>
                                                <span
                                                    x-text="selectedCotizacionInfo ? selectedCotizacionInfo.cliente_nombre : ''"></span>
                                            </p>
                                            <p><span class="font-medium">Fecha:</span>
                                                <span
                                                    x-text="selectedCotizacionInfo ? formatDate(selectedCotizacionInfo.fecha_emision) : ''"></span>
                                            </p>
                                            <p><span class="font-medium">Artículos:</span> <span
                                                    x-text="cotizacionProducts.length"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lista de Productos de la Cotización -->
                                <div x-show="cotizacionProducts.length > 0" class="mt-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Artículos en la Cotización
                                    </h4>
                                    <div class="overflow-hidden rounded-xl border border-green-200">
                                        <table class="w-full">
                                            <thead class="bg-green-50">
                                                <tr>
                                                    <th
                                                        class="px-4 py-3 text-left text-sm font-semibold text-green-600">
                                                        Artículo</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-sm font-semibold text-green-600">
                                                        Código</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-sm font-semibold text-green-600">
                                                        Cantidad</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-sm font-semibold text-green-600">
                                                        Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-green-100">
                                                <template x-for="(product, index) in cotizacionProducts"
                                                    :key="index">
                                                    <tr class="hover:bg-green-50 transition-colors">
                                                        <td class="px-4 py-3 text-sm font-medium text-gray-900"
                                                            x-text="product.nombre_articulo || product.descripcion">
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-green-600 font-mono"
                                                            x-text="product.codigo_repuesto"></td>
                                                        <td class="px-4 py-3 text-sm text-gray-700"
                                                            x-text="product.cantidad"></td>
                                                        <td class="px-4 py-3 text-sm">
                                                            <button @click="addProductFromCotizacion(product)"
                                                                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors font-medium text-sm flex items-center space-x-2">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                                <span>Agregar</span>
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
                                            class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors font-bold flex items-center space-x-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                </path>
                                            </svg>
                                            <span>Agregar Todos los Artículos</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de Artículos -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-900">Artículos Seleccionados</h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-600"
                                            x-text="`${totalUniqueProducts} artículo${totalUniqueProducts !== 1 ? 's' : ''}`"></span>
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"
                                            x-show="products.length > 0"></div>
                                    </div>
                                </div>

                                <div class="overflow-hidden rounded-xl border border-blue-100">
                                    <table class="w-full">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    Artículo</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    Código</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    Tipo</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    Cantidad</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                                    Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-blue-100">
                                            <template x-if="products.length === 0">
                                                <tr>
                                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                            </path>
                                                        </svg>
                                                        <p class="mt-4 text-lg font-medium text-gray-900">No hay
                                                            artículos agregados</p>
                                                        <p class="text-sm mt-2">Agregue artículos usando el formulario
                                                            inferior o desde una cotización aprobada</p>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="(product, index) in products" :key="product.uniqueId">
                                                <tr class="product-row hover:bg-blue-50 transition-all duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900"
                                                        x-text="product.nombre"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-blue-600 font-mono font-bold"
                                                        x-text="product.codigo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-700"
                                                        x-text="product.tipo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                                                        <div class="flex items-center space-x-3">
                                                            <span class="font-bold" x-text="product.cantidad"></span>
                                                            <div class="flex space-x-1">
                                                                <button @click="updateQuantity(index, -1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <button @click="updateQuantity(index, 1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-5 h-5" fill="none"
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
                                                    <td class="px-6 py-4 whitespace-nowrap text-base">
                                                        <button @click="removeProduct(index)"
                                                            class="text-red-600 hover:text-red-700 font-semibold flex items-center space-x-2 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            <span>Eliminar</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Formulario para Agregar Artículo -->
                            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Agregar Nuevo Artículo Manualmente
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                    <!-- Artículo -->
                                    <div class="lg:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Artículo</label>
                                        <select x-model="newProduct.articuloId" x-ref="articuloSelect"
                                            class="w-full">
                                            <option value="">Seleccione un artículo...</option>
                                            <template x-for="articulo in articulos" :key="articulo.idArticulos">
                                                <option :value="articulo.idArticulos"
                                                    :data-codigo="articulo.codigo_barras || articulo.codigo_repuesto"
                                                    :data-tipo="articulo.tipo_articulo"
                                                    :data-stock="articulo.stock_total"
                                                    x-text="`${articulo.nombre} (${articulo.codigo_barras || articulo.codigo_repuesto})`">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Cantidad -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Cantidad</label>
                                        <div class="flex w-full">
                                            <button @click="decreaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center rounded-l-lg px-4 font-semibold border border-r-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" x-model="newProduct.cantidad" min="1"
                                                max="1000"
                                                class="w-16 text-center border-y border-blue-600 focus:ring-0 bg-white text-gray-900 font-semibold"
                                                readonly />
                                            <button @click="increaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center rounded-r-lg px-4 font-semibold border border-l-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
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
                                            class="block text-sm font-semibold text-gray-700 mb-3">Descripción</label>
                                        <input type="text" x-model="newProduct.descripcion"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Descripción adicional...">
                                    </div>
                                </div>

                                <button @click="addProduct()" :disabled="!canAddProduct"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !canAddProduct,
                                        'hover:scale-[1.02]': canAddProduct
                                    }"
                                    class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg font-bold transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="text-lg">Agregar Artículo a la Solicitud</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 rounded-lg shadow-xl border border-white/10 relative overflow-hidden">
                            <!-- Efecto sutil de brillo -->
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-white/5 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <div class="flex items-center space-x-3 relative z-10">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-info-circle text-white text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-xl font-bold text-white drop-shadow-sm">Información Adicional
                                        </h2>
                                        <span
                                            class="px-2 py-1 bg-white/20 rounded-full text-white text-xs font-medium border border-white/30">
                                            Requerido
                                        </span>
                                    </div>
                                    <p class="text-white/80 text-sm mt-1">Complete los detalles de la solicitud</p>
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
                                            <option value="solicitud_articulo">Solicitud de Artículo</option>
                                            <option value="mantenimiento">Mantenimiento</option>
                                            <option value="reparacion">Reparación</option>
                                            <option value="instalacion">Instalación</option>
                                            <option value="garantia">Garantía</option>
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
                                        Fecha límite para tener todos los artículos
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

                            <!-- Nivel de Urgencia -->
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
                                                        <i class="fas"
                                                            :class="urgencia.icon + ' ' + urgencia.iconColor + ' text-xl'"></i>
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
                                    placeholder="Describa cualquier observación, comentario adicional o instrucción especial para esta solicitud..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="xl:col-span-1 space-y-8">
                    <!-- Resumen de la Solicitud -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Resumen de Solicitud</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Artículos Únicos</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="totalUniqueProducts"></span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Total Cantidad</span>
                                <span class="text-2xl font-bold text-green-600" x-text="totalQuantity"></span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Fecha Requerida</span>
                                <span class="text-lg font-bold text-orange-600"
                                    x-text="orderInfo.fechaRequerida ? formatDateForDisplay(orderInfo.fechaRequerida) : 'No definida'"></span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-700 font-medium">Estado</span>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">En
                                    creación</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Acciones</h3>
                        <div class="space-y-4">
                            <button @click="clearAll()" :disabled="products.length === 0 || isCreatingOrder"
                                :class="{ 'opacity-50 cursor-not-allowed': products.length === 0 || isCreatingOrder }"
                                class="w-full px-6 py-4 bg-warning text-white rounded-lg font-bold hover:bg-yellow-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span>Limpiar Todo</span>
                            </button>

                            <button @click="createSolicitud()" :disabled="!canCreateSolicitud || isCreatingOrder"
                                :class="{
                                    'opacity-50 cursor-not-allowed': !canCreateSolicitud || isCreatingOrder,
                                    'hover:scale-[1.02]': canCreateSolicitud && !isCreatingOrder
                                }"
                                class="w-full px-6 py-4 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
                                <template x-if="!isCreatingOrder">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                                <template x-if="isCreatingOrder">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                </template>
                                <span class="text-lg"
                                    x-text="isCreatingOrder ? 'Creando Solicitud...' : 'Crear Solicitud'"></span>
                            </button>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudArticulo', () => ({
                // Estado de la aplicación
                currentDate: '',
                orderNumber: {{ $nextOrderNumber ?? 1 }},
                products: [],
                // En tu estado de Alpine, agrega:
                showClearModal: false,
                newProduct: {
                    articuloId: '',
                    cantidad: 1,
                    descripcion: ''
                },
                orderInfo: {
                    tipoServicio: 'solicitud_articulo',
                    urgencia: '',
                    observaciones: '',
                    fechaRequerida: ''
                },
                notification: {
                    show: false,
                    message: '',
                    type: 'info'
                },
                notificationTimeout: null,
                minDate: '',
                isCreatingOrder: false,

                // Nuevas variables para cotizaciones
                cotizacionesAprobadas: @json($cotizacionesAprobadas ?? []),
                selectedCotizacion: '',
                selectedCotizacionInfo: null,
                cotizacionProducts: [],

                // Artículos desde Laravel
                articulos: @json($articulos),

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

                    this.$nextTick(() => {
                        this.initSelect2();
                        this.initFlatpickr(); // Inicializar Flatpickr
                    });
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

                    // Cotización Select
                    if (this.$refs.cotizacionSelect) {
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
                            disableMobile: false, // Cambiar a false para mejor compatibilidad móvil
                            allowInput: true,
                            clickOpens: true,
                            onChange: (selectedDates, dateStr) => {
                                this.orderInfo.fechaRequerida = dateStr;
                            },
                            onReady: (selectedDates, dateStr, instance) => {
                                // Asegurarse de que el valor del modelo se sincronice
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
                            this.showNotification('❌ Cotización no encontrada', 'error');
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
                            this.showNotification(
                                `✅ ${data.products.length} artículos cargados de la cotización`,
                                'success');
                        } else {
                            throw new Error(data.message || 'Error desconocido');
                        }
                    } catch (error) {
                        console.error('Error cargando productos de cotización:', error);
                        this.showNotification('❌ Error al cargar los productos de la cotización',
                            'error');
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
                        this.showNotification('❌ Artículo no encontrado en el catálogo', 'error');
                        return;
                    }

                    // Verificar si ya existe el artículo en la solicitud
                    const existingProductIndex = this.products.findIndex(
                        product => product.articuloId == cotizacionProduct.articulo_id
                    );

                    if (existingProductIndex !== -1) {
                        // Si existe, verificar que no exceda la cantidad de la cotización
                        const nuevaCantidad = this.products[existingProductIndex].cantidad +
                            cotizacionProduct.cantidad;
                        if (nuevaCantidad > cotizacionProduct.cantidad) {
                            this.showNotification(
                                `❌ No puede exceder la cantidad de la cotización: ${cotizacionProduct.cantidad} unidades`,
                                'error'
                            );
                            return;
                        }

                        this.products[existingProductIndex].cantidad = nuevaCantidad;
                        this.showNotification(
                            `✅ Cantidad actualizada: ${this.products[existingProductIndex].cantidad} unidades`,
                            'success'
                        );
                    } else {
                        // Si no existe, agregar nuevo producto con la cantidad exacta de la cotización
                        const product = {
                            uniqueId: Date.now() + Math.random(),
                            articuloId: cotizacionProduct.articulo_id,
                            nombre: articuloCompleto.nombre,
                            codigo: articuloCompleto.codigo_barras || articuloCompleto
                                .codigo_repuesto,
                            tipo: articuloCompleto.tipo_articulo,
                            cantidad: cotizacionProduct
                                .cantidad, // Usar la cantidad exacta de la cotización
                            descripcion: cotizacionProduct.descripcion ||
                                (this.selectedCotizacionInfo ?
                                    `Desde cotización: ${this.selectedCotizacionInfo.numero_cotizacion}` :
                                    'Desde cotización'),
                            cantidadCotizacion: cotizacionProduct
                                .cantidad // Guardar la cantidad original
                        };

                        this.products.push(product);
                        this.showNotification('✅ Artículo agregado desde cotización', 'success');
                    }
                },

                addAllCotizacionProducts() {
                    if (this.cotizacionProducts.length === 0) {
                        this.showNotification('No hay artículos para agregar', 'info');
                        return;
                    }

                    let addedCount = 0;

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
                                this.products[existingProductIndex].cantidad +=
                                    cotizacionProduct.cantidad;
                            } else {
                                const product = {
                                    uniqueId: Date.now() + Math.random(),
                                    articuloId: cotizacionProduct.articulo_id,
                                    nombre: articuloCompleto.nombre,
                                    codigo: articuloCompleto.codigo_barras ||
                                        articuloCompleto.codigo_repuesto,
                                    tipo: articuloCompleto.tipo_articulo,
                                    cantidad: cotizacionProduct.cantidad,
                                    descripcion: cotizacionProduct.descripcion ||
                                        (this.selectedCotizacionInfo ?
                                            `Desde cotización: ${this.selectedCotizacionInfo.numero_cotizacion}` :
                                            'Desde cotización')
                                };

                                this.products.push(product);
                            }
                            addedCount++;
                        }
                    });

                    this.showNotification(`✅ ${addedCount} artículos agregados desde la cotización`,
                        'success');
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

                // Métodos para notificación
                getNotificationClass() {
                    switch (this.notification.type) {
                        case 'success':
                            return 'bg-green-500';
                        case 'error':
                            return 'bg-red-500';
                        case 'warning':
                            return 'bg-yellow-500';
                        default:
                            return 'bg-blue-500';
                    }
                },

                getNotificationIcon() {
                    switch (this.notification.type) {
                        case 'success':
                            return '✅';
                        case 'error':
                            return '❌';
                        case 'warning':
                            return '⚠️';
                        default:
                            return 'ℹ️';
                    }
                },

                formatDateForDisplay(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}/${month}/${day}`;
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
                        this.showNotification('Por favor seleccione un artículo y cantidad', 'error');
                        return;
                    }

                    // Obtener información del artículo seleccionado
                    const selectedOption = $(this.$refs.articuloSelect).find('option:selected');
                    const articuloData = this.articulos.find(a => a.idArticulos == this.newProduct
                        .articuloId);

                    if (!articuloData) {
                        this.showNotification('Error al obtener información del artículo', 'error');
                        return;
                    }

                    // Verificar si ya existe el artículo
                    const existingProductIndex = this.products.findIndex(product =>
                        product.articuloId === this.newProduct.articuloId
                    );

                    if (existingProductIndex !== -1) {
                        // Si existe, sumar la cantidad
                        this.products[existingProductIndex].cantidad += this.newProduct.cantidad;
                        this.showNotification(
                            `✅ Cantidad actualizada: ${this.products[existingProductIndex].cantidad} unidades`,
                            'success');
                    } else {
                        // Si no existe, agregar nuevo producto
                        const product = {
                            uniqueId: Date.now() + Math.random(),
                            articuloId: this.newProduct.articuloId,
                            nombre: articuloData.nombre,
                            codigo: articuloData.codigo_barras || articuloData.codigo_repuesto,
                            tipo: articuloData.tipo_articulo,
                            cantidad: this.newProduct.cantidad,
                            descripcion: this.newProduct.descripcion
                        };

                        this.products.push(product);
                        this.showNotification('Artículo agregado correctamente', 'success');
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
                    this.showNotification('Artículo eliminado de la solicitud', 'info');
                },

                // Modificar el método updateQuantity para limitar por cotización
                updateQuantity(index, change) {
                    const product = this.products[index];
                    const newQuantity = product.cantidad + change;

                    // Si es de cotización, validar que no exceda la cantidad original
                    if (product.cantidadCotizacion && newQuantity > product.cantidadCotizacion) {
                        this.showNotification(
                            `❌ No puede exceder la cantidad de la cotización: ${product.cantidadCotizacion} unidades`,
                            'error'
                        );
                        return;
                    }

                    if (newQuantity >= 1 && newQuantity <= 1000) {
                        this.products[index].cantidad = newQuantity;
                    }
                },

                clearAll() {
                    if (this.products.length === 0) {
                        this.showNotification('No hay artículos para limpiar', 'info');
                        return;
                    }
                    this.showClearModal = true;
                },

                confirmClearAll() {
                    this.products = [];
                    this.showClearModal = false;
                    this.showNotification('Todos los artículos han sido eliminados', 'info');
                },

                cancelClearAll() {
                    this.showClearModal = false;
                },

                async createSolicitud() {
                    if (!this.canCreateSolicitud) {
                        this.showNotification(
                            '❌ Complete todos los campos requeridos para crear la solicitud',
                            'error');
                        return;
                    }

                    // Mostrar preloader
                    this.isCreatingOrder = true;

                    try {
                        // Preparar datos para enviar
                        const solicitudData = {
                            orderInfo: this.orderInfo,
                            products: this.products,
                            orderNumber: this.orderNumber,
                            selectedCotizacion: this.selectedCotizacion // Agregar este campo
                        };

                        console.log('Enviando datos de la solicitud:', solicitudData);

                        // Enviar datos al servidor
                        const response = await fetch('/solicitudarticulo/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(solicitudData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            let mensaje =
                                `🎉 ¡Solicitud ${result.codigo_orden} creada exitosamente!`;

                            // Si tiene código de cotización, agregar al mensaje
                            if (result.codigo_cotizacion) {
                                mensaje += ` (Cotización: ${result.codigo_cotizacion})`;
                            }

                            this.showNotification(mensaje, 'success');

                            console.log('Solicitud guardada:', {
                                id: result.solicitud_id,
                                codigo: result.codigo_orden,
                                codigo_cotizacion: result.codigo_cotizacion,
                                productos_unicos: result.estadisticas.productos_unicos,
                                total_cantidad: result.estadisticas.total_cantidad
                            });

                            // Obtener el próximo número de orden
                            await this.getNextOrderNumber();

                            // Limpiar formulario después de éxito
                            setTimeout(() => {
                                this.products = [];
                                this.orderInfo = {
                                    tipoServicio: 'solicitud_articulo',
                                    urgencia: '',
                                    observaciones: '',
                                    fechaRequerida: ''
                                };
                                this.selectedCotizacion = '';
                                this.cotizacionProducts = [];
                                this.selectedCotizacionInfo = null;
                            }, 3000);
                        } else {
                            throw new Error(result.message);
                        }

                    } catch (error) {
                        console.error('Error al crear la solicitud:', error);
                        this.showNotification(`❌ Error: ${error.message}`, 'error');
                    } finally {
                        this.isCreatingOrder = false;
                    }
                },

                async getNextOrderNumber() {
                    try {
                        const response = await fetch('/api/next-order-number-ar');
                        const data = await response.json();
                        if (data.success) {
                            this.orderNumber = data.nextOrderNumber;
                        }
                    } catch (error) {
                        console.error('Error obteniendo número de orden:', error);
                    }
                },

                showNotification(message, type = 'info') {
                    this.notification.message = message;
                    this.notification.type = type;
                    this.notification.show = true;

                    if (this.notificationTimeout) {
                        clearTimeout(this.notificationTimeout);
                    }

                    this.notificationTimeout = setTimeout(() => {
                        this.notification.show = false;
                    }, 4000);
                }
            }))
        });
    </script>
</x-layout.default>
