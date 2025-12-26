<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Asegurar que la altura coincida con otros inputs */
        .select2-selection {
            min-height: 48px !important;
            display: flex !important;
            align-items: center !important;
        }
    </style>
    <div x-data="warehouseCreate()" x-init="init()" class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Breadcrumb -->
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudalmacen.index') }}" class="text-primary hover:underline">
                        Solicitudes de Abastecimiento
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Crear Solicitud</span>
                </li>
            </ul>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Información Principal -->
                <div class="flex items-center space-x-4 flex-1">
                    <!-- Icono -->
                    <div
                        class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md">
                        <i class="fas fa-boxes text-white text-lg"></i>
                    </div>

                    <!-- Texto -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-xl font-bold text-gray-900">Nueva Solicitud de Abastecimiento</h1>
                            <span
                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                Nuevo
                            </span>
                        </div>
                        <p class="text-base text-gray-600">Complete los productos necesarios para el almacén</p>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('solicitudalmacen.index') }}"
                        class="inline-flex items-center px-4 py-3 bg-gray-500 text-white rounded-lg text-base font-semibold hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 shadow-sm hover:shadow-md min-w-[120px] justify-center">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        Volver
                    </a>

                    <button type="button"
                        class="inline-flex items-center px-4 py-3 bg-amber-500 text-white rounded-lg text-base font-semibold hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all duration-200 shadow-sm hover:shadow-md min-w-[120px] justify-center"
                        @click="resetForm()">
                        <i class="fas fa-redo mr-2 text-sm"></i>
                        Limpiar
                    </button>

                    <!-- Reemplaza tu botón actual por este: -->
                    <button type="button"
                        class="inline-flex items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-base font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg min-w-[140px] justify-center"
                        @click="validateAndShowModal()">
                        <i class="fas fa-paper-plane mr-2 text-sm"></i>
                        Crear Solicitud
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Información General -->
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-clipboard-list text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Información General</h2>
                                    <p class="text-sm text-gray-600">Datos básicos de la solicitud de abastecimiento</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Título de la Solicitud -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-heading text-blue-500 text-sm"></i>
                                    Título de la Solicitud *
                                </label>
                                <div class="relative">
                                    <input type="text"
                                        class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Ej: Reabastecimiento Material de Oficina" x-model="form.titulo"
                                        required>
                                    <i class="fas fa-edit absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Tipo de Solicitud -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-tag text-blue-500 text-sm"></i>
                                    Tipo de Solicitud *
                                </label>
                                <div class="relative">
                                    <select
                                        class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer"
                                        x-model="form.idTipoSolicitud" required>
                                        <option value="">Seleccione tipo</option>
                                        <template x-for="tipo in tiposSolicitud" :key="tipo.idTipoSolicitud">
                                            <option :value="tipo.idTipoSolicitud" x-text="tipo.nombre"></option>
                                        </template>
                                    </select>

                                </div>
                            </div>

                            <!-- Área -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-building text-blue-500 text-sm"></i>
                                    Área *
                                </label>
                                <div class="relative">
                                    <select
                                        class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer"
                                        x-model="form.idTipoArea" required>
                                        <option value="">Seleccione área</option>
                                        <template x-for="area in areas" :key="area.idTipoArea">
                                            <option :value="area.idTipoArea" x-text="area.nombre"></option>
                                        </template>
                                    </select>

                                </div>
                            </div>

                            <!-- Solicitante -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-500 text-sm"></i>
                                    Solicitante *
                                </label>
                                <div class="relative">
                                    <input type="text"
                                        class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                        placeholder="Nombre del responsable" x-model="form.solicitante" required
                                        readonly>

                                </div>
                            </div>

                            <!-- Prioridad -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-flag text-blue-500 text-sm"></i>
                                    Prioridad *
                                </label>
                                <div class="relative">
                                    <select
                                        class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer"
                                        x-model="form.idPrioridad" required>
                                        <option value="">Seleccione prioridad</option>
                                        <template x-for="prioridad in prioridades" :key="prioridad.idPrioridad">
                                            <option :value="prioridad.idPrioridad" x-text="prioridad.nombre"></option>
                                        </template>
                                    </select>

                                </div>
                            </div>

                            <!-- Fecha Requerida -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-blue-500 text-sm"></i>
                                    Fecha Requerida *
                                </label>
                                <div class="relative">
                                    <input type="text" id="fechaRequerida"
                                        class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white cursor-pointer flatpickr-date"
                                        x-model="form.fecha_requerida" placeholder="Seleccione una fecha" readonly
                                        required>
                                    <i class="fas fa-calendar absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Centro de Costo -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave text-blue-500 text-sm"></i>
                                    Centro de Costo
                                </label>
                                <div class="relative">
                                    <select
                                        class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer"
                                        x-model="form.idCentroCosto">
                                        <option value="">Seleccione centro de costo</option>
                                        <template x-for="centro in centrosCosto" :key="centro.idCentroCosto">
                                            <option :value="centro.idCentroCosto" x-text="centro.nombre"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-align-left text-blue-500 text-sm"></i>
                                    Descripción *
                                </label>
                                <div class="relative">
                                    <textarea
                                        class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        rows="3" placeholder="Describa el propósito de esta solicitud de abastecimiento..."
                                        x-model="form.descripcion" required></textarea>
                                    <i class="fas fa-file-alt absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Justificación -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-comment-alt text-blue-500 text-sm"></i>
                                    Justificación *
                                </label>
                                <div class="relative">
                                    <textarea
                                        class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        rows="3" placeholder="Explique por qué es necesario este abastecimiento..." x-model="form.justificacion"
                                        required></textarea>
                                    <i class="fas fa-comments absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-sticky-note text-blue-500 text-sm"></i>
                                    Observaciones
                                </label>
                                <div class="relative">
                                    <textarea
                                        class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        rows="2" placeholder="Observaciones adicionales..." x-model="form.observaciones"></textarea>
                                    <i class="fas fa-notes-medical absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productos Solicitados -->
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-box-open text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Productos Solicitados</h2>
                                    <p class="text-sm text-gray-600">Agregue los productos que necesita el almacén</p>
                                </div>
                            </div>
                            <button type="button"
                                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md font-medium"
                                @click="addProduct()">
                                <i class="fas fa-plus mr-2 text-sm"></i>
                                Agregar Producto
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Productos -->
                    <div class="p-5">
                        <!-- Controles del slider -->
                        <div class="flex items-center justify-between mb-4" x-show="form.productos.length > 1">
                            <div class="flex items-center space-x-2">
                                <button type="button"
                                    class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                    @click="currentProductIndex = (currentProductIndex - 1 + form.productos.length) % form.productos.length"
                                    :disabled="form.productos.length <= 1">
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <div class="flex items-center space-x-1">
                                    <span class="text-sm font-medium text-gray-700">Producto</span>
                                    <span class="text-sm font-bold text-green-600"
                                        x-text="currentProductIndex + 1"></span>
                                    <span class="text-sm text-gray-500">de</span>
                                    <span class="text-sm font-bold text-gray-700"
                                        x-text="form.productos.length"></span>
                                </div>

                                <button type="button"
                                    class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                    @click="currentProductIndex = (currentProductIndex + 1) % form.productos.length"
                                    :disabled="form.productos.length <= 1">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <!-- Indicadores de puntos -->
                            <div class="flex space-x-1" x-show="form.productos.length > 1">
                                <template x-for="(product, index) in form.productos" :key="index">
                                    <button type="button" class="w-2 h-2 rounded-full transition-all duration-300"
                                        :class="index === currentProductIndex ? 'bg-green-500' : 'bg-gray-300'"
                                        @click="currentProductIndex = index"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Contenedor del slider -->
                        <div class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="flex transition-transform duration-500 ease-in-out"
                                :style="`transform: translateX(-${currentProductIndex * 100}%)`">
                                <template x-for="(product, index) in form.productos" :key="index">
                                    <div class="w-full flex-shrink-0 px-4 py-6">
                                        <div
                                            class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                                            <!-- Header del producto -->
                                            <div
                                                class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                                <div class="flex items-center space-x-4">
                                                    <div
                                                        class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-sm">
                                                        <i class="fas fa-box text-white text-sm"></i>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-base font-semibold text-gray-800"
                                                            x-text="`Producto ${index + 1}`"></span>
                                                        <div class="flex items-center space-x-2 mt-1">
                                                            <span
                                                                class="text-xs bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200 text-gray-700 font-medium"
                                                                x-text="product.codigo_barras || 'Sin código'"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="text-red-500 hover:text-red-700"
                                                    @click="removeProduct(index)">
                                                    <i class="fas fa-times text-lg"></i>
                                                </button>
                                            </div>

                                            <!-- Grid de información del producto -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <!-- Buscar Artículo con Select2 -->
                                                <div class="md:col-span-2 space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-search text-green-600 text-xs"></i>
                                                        </div>
                                                        Buscar Artículo
                                                    </label>
                                                    <div class="relative">
                                                        <select
                                                            class="w-full select2-article-search py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                            x-model="product.searchCode"
                                                            data-placeholder="Busque por código, nombre o descripción..."
                                                            style="width: 100%;">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Descripción del Producto -->
                                                <div class="md:col-span-2 space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-align-left text-green-600 text-xs"></i>
                                                        </div>
                                                        Descripción del Producto *
                                                    </label>
                                                    <div class="relative">
                                                        <input type="text"
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                            placeholder="Nombre del producto"
                                                            x-model="product.descripcion" required readonly>
                                                        <i class="fas fa-box absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>

                                                <!-- Categoría -->
                                                <div class="space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-tags text-green-600 text-xs"></i>
                                                        </div>
                                                        Categoría
                                                    </label>
                                                    <div class="relative">
                                                        <input type="text"
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                            placeholder="Categoría" x-model="product.categoria_nombre"
                                                            readonly>
                                                        <i
                                                            class="fas fa-folder absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>

                                                <!-- Cantidad -->
                                                <div class="space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-cubes text-green-600 text-xs"></i>
                                                        </div>
                                                        Cantidad *
                                                    </label>
                                                    <div class="relative">
                                                        <input type="number"
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                            min="1" placeholder="1" x-model="product.cantidad"
                                                            required>
                                                        <i
                                                            class="fas fa-hashtag absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>

                                                <!-- Unidad -->
                                                <div class="space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-balance-scale text-green-600 text-xs"></i>
                                                        </div>
                                                        Unidad *
                                                    </label>
                                                    <div class="relative">
                                                        <input type="text"
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                            placeholder="Unidad de medida"
                                                            x-model="product.unidad_nombre" readonly>
                                                        <i
                                                            class="fas fa-weight absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>

                                                <!-- Código Barras -->
                                                <div class="space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-barcode text-green-600 text-xs"></i>
                                                        </div>
                                                        Código Barras
                                                    </label>
                                                    <div class="relative">
                                                        <input type="text"
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                            placeholder="Código de barras"
                                                            x-model="product.codigo_barras" readonly>
                                                        <i
                                                            class="fas fa-barcode absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>

                                                <!-- Marca -->
                                                <div class="space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-copyright text-green-600 text-xs"></i>
                                                        </div>
                                                        Marca
                                                    </label>
                                                    <div class="relative">
                                                        <input type="text"
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                            placeholder="Marca del producto"
                                                            x-model="product.marca_nombre" readonly>
                                                        <i class="fas fa-tag absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>

                                                <!-- Especificaciones Técnicas -->
                                                <div class="md:col-span-2 space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-cogs text-green-600 text-xs"></i>
                                                        </div>
                                                        Especificaciones Técnicas
                                                    </label>
                                                    <div class="relative">
                                                        <textarea
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                            rows="2" placeholder="Especificaciones, características..." x-model="product.especificaciones"></textarea>
                                                        <i
                                                            class="fas fa-tools absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>

                                                <!-- Justificación del Producto -->
                                                <div class="md:col-span-2 space-y-2">
                                                    <label
                                                        class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                        <div
                                                            class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                            <i class="fas fa-comment-dots text-green-600 text-xs"></i>
                                                        </div>
                                                        Justificación del Producto
                                                    </label>
                                                    <div class="relative">
                                                        <textarea
                                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                            rows="2" placeholder="¿Por qué necesita este producto específico?"
                                                            x-model="product.justificacion_producto"></textarea>
                                                        <i
                                                            class="fas fa-question-circle absolute left-3 top-3 text-gray-400"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Estado vacío -->
                        <div x-show="form.productos.length === 0"
                            class="text-center py-12 bg-gradient-to-br from-gray-50 to-white rounded-xl border-2 border-dashed border-gray-300 transition-all duration-300 hover:border-green-400">
                            <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay productos agregados</h3>
                            <p class="text-gray-500 max-w-md mx-auto">Comience agregando el primer producto a su
                                solicitud</p>
                            <button
                                class="inline-flex items-center px-4 py-2.5 mt-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md font-medium"
                                @click="addProduct()">
                                <i class="fas fa-plus mr-2 text-sm"></i>
                                Agregar Primer Producto
                            </button>
                        </div>
                    </div>

                    <!-- Resumen de Productos -->
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50" x-show="form.productos.length > 0">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">Total de Productos</p>
                                        <p class="text-2xl font-bold text-gray-900" x-text="form.productos.length">
                                        </p>
                                    </div>
                                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-boxes text-blue-500"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">Total de Unidades</p>
                                        <p class="text-2xl font-bold text-gray-900" x-text="totalUnits"></p>
                                    </div>
                                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-cubes text-green-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-plus-circle text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Información Adicional</h2>
                                <p class="text-sm text-gray-600">Detalles complementarios para el proceso</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="space-y-4">
                            <!-- Archivos Adjuntos -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-paperclip text-purple-500 text-sm"></i>
                                    Archivos Adjuntos
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-purple-400 transition-all duration-300 bg-gradient-to-br from-gray-50 to-white hover:from-purple-50 hover:to-indigo-50"
                                    @click="$refs.fileInput.click()">
                                    <input type="file" x-ref="fileInput" multiple class="hidden"
                                        @change="handleFileSelect">
                                    <i class="fas fa-cloud-upload-alt text-purple-400 text-4xl mb-3"></i>
                                    <h4 class="text-sm font-medium text-gray-900 mb-1">Arrastre archivos o haga clic
                                        para seleccionar</h4>
                                    <p class="text-xs text-gray-500">Cotizaciones, imágenes, especificaciones - máximo
                                        10MB por archivo</p>
                                </div>

                                <!-- Lista de archivos -->
                                <div x-show="form.files.length > 0" class="space-y-2">
                                    <template x-for="(file, index) in form.files" :key="index">
                                        <div
                                            class="flex items-center justify-between bg-white rounded-lg p-3 border shadow-sm transition-all duration-300 hover:shadow">
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-file text-purple-500"></i>
                                                <span class="text-sm text-gray-700" x-text="file.name"></span>
                                                <span class="text-xs text-gray-500"
                                                    x-text="formatFileSize(file.size)"></span>
                                            </div>
                                            <button type="button"
                                                class="text-red-500 hover:text-red-700 transition-colors p-1 rounded-full hover:bg-red-50"
                                                @click="removeFile(index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="space-y-6">
                <!-- Resumen de la Solicitud -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-eye text-white text-sm"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Resumen de la Solicitud</h2>
                                    <p class="text-sm text-gray-600">Vista previa antes de enviar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="space-y-6">
                            <!-- Estado -->
                            <div class="text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded-full text-sm font-bold">
                                    Nueva Solicitud
                                </span>
                            </div>

                            <!-- Información General -->
                            <div class="space-y-3">
                                <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Información General</h3>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <span class="text-gray-600">Código:</span>
                                    <span class="font-medium text-blue-600" x-text="requestCode"></span>

                                    <span class="text-gray-600">Título:</span>
                                    <span x-text="form.titulo || 'Sin título'"></span>

                                    <span class="text-gray-600">Tipo:</span>
                                    <span
                                        x-text="getTipoSolicitudText(form.idTipoSolicitud) || 'No especificado'"></span>

                                    <span class="text-gray-600" x-show="form.idTipoArea">Área:</span>
                                    <span x-show="form.idTipoArea" x-text="getAreaText(form.idTipoArea)"></span>

                                    <span class="text-gray-600">Solicitante:</span>
                                    <span x-text="form.solicitante || 'No especificado'"></span>

                                    <span class="text-gray-600">Prioridad:</span>
                                    <span class="font-medium"
                                        :class="{
                                            'text-green-500': getPrioridadNivel(form.idPrioridad) === 'low',
                                            'text-yellow-500': getPrioridadNivel(form.idPrioridad) === 'medium',
                                            'text-red-500': getPrioridadNivel(form.idPrioridad) === 'high',
                                            'text-orange-600': getPrioridadNivel(form.idPrioridad) === 'urgent',
                                            'text-gray-500': !form.idPrioridad
                                        }"
                                        x-text="getPrioridadText(form.idPrioridad) || 'No especificada'"></span>

                                    <span class="text-gray-600">Fecha Requerida:</span>
                                    <span
                                        x-text="form.fecha_requerida ? formatPreviewDate(form.fecha_requerida) : 'No especificada'"></span>

                                    <span class="text-gray-600" x-show="form.idCentroCosto">Centro de Costo:</span>
                                    <span x-show="form.idCentroCosto"
                                        x-text="getCentroCostoText(form.idCentroCosto)"></span>
                                </div>
                            </div>

                            <!-- Productos Solicitados -->
                            <div class="space-y-4" x-show="form.productos.length > 0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-bold text-gray-800 flex items-center space-x-2">
                                        <span>Productos Solicitados</span>
                                    </h3>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium"
                                        x-text="form.productos.length + ' producto(s)'"></span>
                                </div>

                                <div class="space-y-3 max-h-96 overflow-y-auto">
                                    <template x-for="(product, index) in form.productos" :key="index">
                                        <div
                                            class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:border-blue-200">
                                            <!-- Header con número -->
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-start space-x-3 flex-1">
                                                    <!-- Número del producto -->
                                                    <div
                                                        class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-bold text-white"
                                                            x-text="index + 1"></span>
                                                    </div>

                                                    <!-- Información principal -->
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-sm font-semibold text-gray-800 leading-tight mb-1"
                                                            x-text="product.descripcion || 'Sin descripción'"></h4>
                                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                            <span
                                                                class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-200"
                                                                x-text="product.codigo_barras || 'Sin código'"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detalles del producto -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                                <!-- Columna izquierda -->
                                                <div class="space-y-2">
                                                    <!-- Cantidad y unidad -->
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas fa-cube text-green-500 text-xs w-4"></i>
                                                        <span class="text-gray-700 font-medium"
                                                            x-text="product.cantidad + ' ' + (product.unidad_nombre || 'unidad')"></span>
                                                    </div>

                                                    <!-- Categoría -->
                                                    <div x-show="product.categoria_nombre"
                                                        class="flex items-center space-x-2">
                                                        <i class="fas fa-tags text-green-500 text-xs w-4"></i>
                                                        <span
                                                            class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs border border-blue-200"
                                                            x-text="product.categoria_nombre"></span>
                                                    </div>
                                                </div>

                                                <!-- Columna derecha -->
                                                <div class="space-y-2">
                                                    <!-- Marca -->
                                                    <div x-show="product.marca_nombre"
                                                        class="flex items-center space-x-2">
                                                        <i class="fas fa-copyright text-green-500 text-xs w-4"></i>
                                                        <span class="text-gray-700"
                                                            x-text="product.marca_nombre"></span>
                                                    </div>

                                                    <!-- Modelo -->
                                                    <div x-show="product.modelo_nombre"
                                                        class="flex items-center space-x-2">
                                                        <i class="fas fa-cube text-green-500 text-xs w-4"></i>
                                                        <span class="text-gray-700"
                                                            x-text="product.modelo_nombre"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información adicional -->
                                            <div class="mt-3 space-y-2">
                                                <!-- Especificaciones técnicas -->
                                                <div x-show="product.especificaciones"
                                                    class="flex items-center space-x-2 text-xs">
                                                    <div class="flex items-start space-x-2">
                                                        <i
                                                            class="fas fa-toolbox text-green-500 mt-0.5 flex-shrink-0"></i>
                                                        <span class="text-gray-600 font-medium">Especificaciones
                                                            técnicas:</span>
                                                        <span class="text-gray-700"
                                                            x-text="product.especificaciones"></span>
                                                    </div>
                                                </div>

                                                <!-- Justificación -->
                                                <div x-show="product.justificacion_producto"
                                                    class="flex items-center space-x-2 text-xs">
                                                    <i class="fas fa-comment text-green-500 text-xs w-4"></i>
                                                    <span class="text-gray-600 font-medium">Justificación:</span>
                                                    <span class="text-gray-700"
                                                        x-text="product.justificacion_producto"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Justificación -->
                            <div class="space-y-3">
                                <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Justificación</h3>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3"
                                    x-text="form.justificacion || 'Sin justificación'"></p>
                            </div>

                            <!-- Observaciones -->
                            <div class="space-y-3" x-show="form.observaciones">
                                <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Observaciones</h3>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3"
                                    x-text="form.observaciones"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de ayuda -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl border border-blue-200 p-6 shadow-md">
                    <!-- Header compacto -->
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-lightbulb text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Consejos para una buena solicitud</h3>
                            <p class="text-sm text-blue-700">Siga estas recomendaciones clave</p>
                        </div>
                    </div>

                    <!-- Grid de consejos compacto -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Consejo 1 -->
                        <div
                            class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check-circle text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Describa claramente cada producto</p>
                            </div>
                        </div>

                        <!-- Consejo 2 -->
                        <div
                            class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                            <div
                                class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-calculator text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Incluya cantidades realistas</p>
                            </div>
                        </div>

                        <!-- Consejo 3 -->
                        <div
                            class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                            <div
                                class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-file-alt text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Justifique la necesidad</p>
                            </div>
                        </div>

                        <!-- Consejo 4 -->
                        <div
                            class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                            <div
                                class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-barcode text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Verifique códigos correctos</p>
                            </div>
                        </div>

                        <!-- Consejo 5 -->
                        <div
                            class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                            <div
                                class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-cogs text-indigo-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Incluya especificaciones técnicas</p>
                            </div>
                        </div>

                        <!-- Consejo 6 -->
                        <div
                            class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                            <div
                                class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-search text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Busque artículos por código</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer compacto -->
                    <div class="mt-4 p-3 bg-blue-100 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                            <p class="text-xs text-blue-800">Contacte al almacén para ayuda adicional</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de Confirmación -->
        <div x-show="showConfirmationModal" x-cloak class="fixed inset-0 bg-[black]/60 z-[9999] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Overlay negro semi-transparente -->
            <div class="fixed inset-0 bg-[black]/60 transition-opacity" @click="showConfirmationModal = false"></div>

            <div class="flex items-start justify-center min-h-screen px-4 py-8"
                @click.self="showConfirmationModal = false">
                <!-- Modal Content -->
                <div class="relative transform rounded-lg bg-white shadow-xl transition-all w-full max-w-4xl my-8"
                    @click.stop x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-check text-primary text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white" id="modal-title">Confirmar Solicitud</h3>
                                    <p class="text-blue-100 text-sm mt-1">Revise todos los datos antes de crear la
                                        solicitud</p>
                                </div>
                            </div>
                            <button type="button" class="text-white hover:text-blue-200 transition-colors"
                                @click="showConfirmationModal = false">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="bg-gray-50 px-6 py-5 max-h-[70vh] overflow-y-auto">
                        <!-- Resumen de la Solicitud -->
                        <div class="space-y-6">
                            <!-- Información General - EN 3 COLUMNAS -->
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div
                                    class="px-5 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                                    <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                        <i class="fas fa-info-circle text-blue-500"></i>
                                        Información General
                                    </h4>
                                </div>
                                <div class="p-5">
                                    <!-- GRID DE 3 COLUMNAS -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Columna 1 -->
                                        <div class="space-y-4">
                                            <div class="space-y-1">
                                                <span class="text-sm font-medium text-gray-600">Título:</span>
                                                <p class="text-sm text-gray-900 font-semibold truncate"
                                                    x-text="form.titulo || 'Sin título'"
                                                    :title="form.titulo || 'Sin título'"></p>
                                            </div>

                                            <div class="space-y-1">
                                                <span class="text-sm font-medium text-gray-600">Área:</span>
                                                <p class="text-sm text-gray-900"
                                                    x-text="getAreaText(form.idTipoArea) || 'No especificada'"></p>
                                            </div>

                                            <div class="space-y-1">
                                                <span class="text-sm font-medium text-gray-600">Fecha Requerida:</span>
                                                <p class="text-sm text-gray-900"
                                                    x-text="form.fecha_requerida ? formatPreviewDate(form.fecha_requerida) : 'No especificada'">
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Columna 2 -->
                                        <div class="space-y-4">
                                            <div class="space-y-1">
                                                <span class="text-sm font-medium text-gray-600">Tipo de
                                                    Solicitud:</span>
                                                <p class="text-sm text-gray-900"
                                                    x-text="getTipoSolicitudText(form.idTipoSolicitud) || 'No especificado'">
                                                </p>
                                            </div>

                                            <div class="space-y-1">
                                                <span class="text-sm font-medium text-gray-600">Solicitante:</span>
                                                <p class="text-sm text-gray-900 font-semibold truncate"
                                                    x-text="form.solicitante || 'No especificado'"
                                                    :title="form.solicitante || 'No especificado'"></p>
                                            </div>

                                            <div class="space-y-1" x-show="form.idCentroCosto">
                                                <span class="text-sm font-medium text-gray-600">Centro de Costo:</span>
                                                <p class="text-sm text-gray-900 truncate"
                                                    x-text="getCentroCostoText(form.idCentroCosto)"
                                                    :title="getCentroCostoText(form.idCentroCosto)"></p>
                                            </div>
                                        </div>

                                        <!-- Columna 3 -->
                                        <div class="space-y-4">
                                            <div class="space-y-1">
                                                <span class="text-sm font-medium text-gray-600">Prioridad:</span>
                                                <p class="text-sm font-medium"
                                                    :class="{
                                                        'text-green-500': getPrioridadNivel(form
                                                            .idPrioridad) === 'low',
                                                        'text-yellow-500': getPrioridadNivel(form
                                                            .idPrioridad) === 'medium',
                                                        'text-red-500': getPrioridadNivel(form
                                                            .idPrioridad) === 'high',
                                                        'text-orange-600': getPrioridadNivel(form
                                                            .idPrioridad) === 'urgent',
                                                        'text-gray-500': !form.idPrioridad
                                                    }"
                                                    x-text="getPrioridadText(form.idPrioridad) || 'No especificada'">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen de Productos -->
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden"
                                x-show="form.productos.length > 0">
                                <div
                                    class="px-5 py-3 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                            <i class="fas fa-boxes text-green-500"></i>
                                            Productos Solicitados
                                        </h4>
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                            <span x-text="form.productos.length"></span> producto(s)
                                            <span class="ml-2" x-text="totalUnits"></span> unidades
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="space-y-4 max-h-80 overflow-y-auto">
                                        <template x-for="(product, index) in form.productos" :key="index">
                                            <div
                                                class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition-colors">
                                                <div class="flex items-start justify-between mb-3">
                                                    <div class="flex items-start space-x-3">
                                                        <div
                                                            class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <span class="text-sm font-bold text-green-700"
                                                                x-text="index + 1"></span>
                                                        </div>
                                                        <div>
                                                            <h5 class="text-sm font-bold text-gray-800 mb-1 truncate"
                                                                x-text="product.descripcion || 'Sin descripción'"
                                                                :title="product.descripcion || 'Sin descripción'"></h5>
                                                            <div class="flex items-center space-x-2">
                                                                <span
                                                                    class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200"
                                                                    x-text="product.codigo_barras || 'Sin código'"></span>
                                                                <span
                                                                    class="text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200"
                                                                    x-show="product.categoria_nombre"
                                                                    x-text="product.categoria_nombre"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-sm font-bold text-gray-900">
                                                            <span x-text="product.cantidad"></span>
                                                            <span x-text="product.unidad_nombre || 'unidad'"></span>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                                    <div class="space-y-1">
                                                        <template x-if="product.marca_nombre">
                                                            <div class="flex items-center space-x-2">
                                                                <i class="fas fa-tag text-gray-400 text-xs"></i>
                                                                <span class="text-gray-600">Marca:</span>
                                                                <span class="font-medium text-gray-800"
                                                                    x-text="product.marca_nombre"></span>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <template x-if="product.justificacion_producto">
                                                            <div class="flex items-start space-x-2">
                                                                <i
                                                                    class="fas fa-comment text-gray-400 text-xs mt-0.5"></i>
                                                                <div>
                                                                    <span
                                                                        class="text-gray-600 block">Justificación:</span>
                                                                    <span class="text-gray-700"
                                                                        x-text="product.justificacion_producto"></span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <template x-if="product.especificaciones">
                                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                                        <div class="flex items-start space-x-2">
                                                            <i class="fas fa-tools text-gray-400 text-xs mt-0.5"></i>
                                                            <div class="flex-1">
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 block mb-1">Especificaciones
                                                                    Técnicas:</span>
                                                                <p class="text-xs text-gray-700"
                                                                    x-text="product.especificaciones"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Justificación y Observaciones -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Justificación -->
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                    <div
                                        class="px-5 py-3 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
                                        <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                            <i class="fas fa-comment-alt text-amber-500"></i>
                                            Justificación
                                        </h4>
                                    </div>
                                    <div class="p-5">
                                        <p class="text-sm text-gray-700"
                                            x-text="form.justificacion || 'Sin justificación proporcionada'"></p>
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden"
                                    x-show="form.observaciones">
                                    <div
                                        class="px-5 py-3 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-200">
                                        <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                            <i class="fas fa-sticky-note text-gray-500"></i>
                                            Observaciones
                                        </h4>
                                    </div>
                                    <div class="p-5">
                                        <p class="text-sm text-gray-700" x-text="form.observaciones"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Archivos Adjuntos -->
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden"
                                x-show="form.files.length > 0">
                                <div
                                    class="px-5 py-3 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                                    <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                        <i class="fas fa-paperclip text-purple-500"></i>
                                        Archivos Adjuntos
                                        <span class="ml-2 text-sm font-normal text-purple-600"
                                            x-text="form.files.length + ' archivo(s)'"></span>
                                    </h4>
                                </div>
                                <div class="p-5">
                                    <div class="space-y-2">
                                        <template x-for="(file, index) in form.files" :key="index">
                                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fas fa-file text-purple-500"></i>
                                                    <div>
                                                        <span class="text-sm text-gray-700 block"
                                                            x-text="file.name"></span>
                                                        <span class="text-xs text-gray-500"
                                                            x-text="formatFileSize(file.size)"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-gray-500">
                                <p>Revise cuidadosamente todos los datos antes de confirmar.</p>
                                <p class="mt-1">La solicitud será creada con estado: <span
                                        class="font-bold text-blue-600">Pendiente</span></p>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2.5 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-200 font-medium"
                                    @click="showConfirmationModal = false">
                                    <i class="fas fa-edit mr-2"></i>
                                    Volver a editar
                                </button>
                                <button type="button"
                                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md font-bold"
                                    @click="submitFormFromModal()">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Confirmar y Crear Solicitud
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function warehouseCreate() {
            return {
                // Datos cargados desde la base de datos
                showConfirmationModal: false,
                tiposSolicitud: @json($tiposSolicitud),
                prioridades: @json($prioridades),
                centrosCosto: @json($centrosCosto),
                areas: @json($areas),

                form: {
                    titulo: '',
                    idTipoSolicitud: '',
                    solicitante: '{{ $nombreSolicitante }}',
                    idPrioridad: '',
                    fecha_requerida: '',
                    idCentroCosto: '',
                    idTipoArea: '',
                    descripcion: '',
                    justificacion: '',
                    observaciones: '',
                    productos: [],
                    files: []
                },

                currentProductIndex: 0,
                flatpickrInstance: null,

                get requestCode() {
                    const now = new Date();
                    const year = now.getFullYear().toString().slice(-2);
                    const month = (now.getMonth() + 1).toString().padStart(2, '0');
                    const day = now.getDate().toString().padStart(2, '0');
                    const random = Math.floor(Math.random() * 999).toString().padStart(3, '0');
                    return `SA-${year}${month}${day}-${random}`;
                },

                init() {
                    const today = new Date().toISOString().split('T')[0];
                    this.form.fecha_requerida = today;

                    // Configurar toastr
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "5000"
                    };

                    // Inicializar Flatpickr
                    this.$nextTick(() => {
                        this.initializeFlatpickr();
                    });

                    // Agregar navegación con teclado
                    document.addEventListener('keydown', (e) => {
                        if (this.form.productos.length <= 1) return;

                        if (e.key === 'ArrowLeft') {
                            e.preventDefault();
                            this.currentProductIndex = (this.currentProductIndex - 1 + this.form.productos.length) %
                                this.form.productos.length;
                        } else if (e.key === 'ArrowRight') {
                            e.preventDefault();
                            this.currentProductIndex = (this.currentProductIndex + 1) % this.form.productos.length;
                        }
                    });
                },

                initializeFlatpickr() {
                    const fechaInput = document.getElementById('fechaRequerida');

                    if (fechaInput && typeof flatpickr !== 'undefined') {
                        this.flatpickrInstance = flatpickr('#fechaRequerida', {
                            locale: 'es',
                            dateFormat: 'Y-m-d',
                            minDate: 'today',
                            defaultDate: this.form.fecha_requerida || 'today',
                            position: 'auto right',
                            static: true,
                            monthSelectorType: 'static',
                            prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
                            nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
                            onReady: (selectedDates, dateStr, instance) => {
                                instance.element.value = dateStr;
                            },
                            onChange: (selectedDates, dateStr, instance) => {
                                this.form.fecha_requerida = dateStr;
                            }
                        });
                    }
                },

                getAreaText(idTipoArea) {
                    const area = this.areas.find(a => a.idTipoArea == idTipoArea);
                    return area ? area.nombre : '';
                },

                async searchArticle(index, selectedData = null) {
                    const product = this.form.productos[index];

                    if (!selectedData) return;

                    try {
                        // Llenar automáticamente los campos del producto
                        product.idArticulo = selectedData.idArticulos;
                        product.descripcion = selectedData.nombre;
                        product.codigo_barras = selectedData.codigo_barras || '';
                        product.codigo_repuesto = selectedData.codigo_repuesto || '';
                        product.categoria_nombre = selectedData.categoria || '';
                        product.unidad_nombre = selectedData.unidad || '';
                        product.marca_nombre = selectedData.marca || '';
                        product.modelo_nombre = ''; // Si no lo tienes en los datos
                        product.precio_compra = selectedData.precio_compra || '';

                        // Limpiar el campo de búsqueda
                        setTimeout(() => {
                            const selectElement = $(`[data-product-index="${index}"] .select2-article-search`);
                            if (selectElement.length) {
                                selectElement.val(null).trigger('change');
                            }
                            product.searchCode = '';
                        }, 100);

                    } catch (error) {
                        console.error('Error procesando artículo:', error);
                        toastr.error('Error al procesar el artículo');
                    }
                },

                get totalUnits() {
                    return this.form.productos.reduce((sum, product) => {
                        return sum + (parseInt(product.cantidad) || 0);
                    }, 0);
                },

                addProduct() {
                    this.form.productos.push({
                        idArticulo: null,
                        searchCode: '',
                        descripcion: '',
                        cantidad: 1,
                        codigo_barras: '',
                        categoria_nombre: '',
                        unidad_nombre: '',
                        marca_nombre: '',
                        modelo_nombre: '',
                        especificaciones: '',
                        justificacion_producto: ''
                    });

                    this.currentProductIndex = this.form.productos.length - 1;

                    // Inicializar Select2 en el nuevo producto
                    this.$nextTick(() => {
                        setTimeout(() => {
                            const lastSelect = $('.select2-article-search').last();
                            if (lastSelect.length && !lastSelect.hasClass('select2-hidden-accessible')) {
                                const self = this;
                                const currentIndex = this.currentProductIndex;

                                lastSelect.select2({
                                    placeholder: "Busque por código, nombre o descripción...",
                                    allowClear: true,
                                    width: '100%',
                                    language: {
                                        noResults: function() {
                                            return "No se encontraron resultados";
                                        },
                                        searching: function() {
                                            return "Buscando...";
                                        }
                                    },
                                    ajax: {
                                        url: '/solicitudalmacen/buscar-articulos',
                                        type: 'GET',
                                        dataType: 'json',
                                        delay: 250,
                                        data: function(params) {
                                            console.log('Buscando:', params.term);
                                            return {
                                                search: params.term,
                                                page: params.page || 1
                                            };
                                        },
                                        processResults: function(data) {
                                            console.log('Resultados:', data);
                                            return {
                                                results: data.data || [],
                                                pagination: {
                                                    more: data.next_page_url ? true : false
                                                }
                                            };
                                        },
                                        cache: true
                                    },
                                    minimumInputLength: 2,
                                    templateResult: formatArticle,
                                    templateSelection: formatArticleSelection
                                });

                                // Agregar evento change
                                lastSelect.on('select2:select', function(event) {
                                    const selectedData = event.params.data;
                                    console.log('Seleccionado:', selectedData);

                                    // Llenar los campos directamente con los datos
                                    if (selectedData) {
                                        self.searchArticle(currentIndex, selectedData);
                                    }
                                });
                            }
                        }, 100);
                    });
                },

                removeProduct(index) {
                    this.form.productos.splice(index, 1);
                    if (this.currentProductIndex >= this.form.productos.length) {
                        this.currentProductIndex = Math.max(0, this.form.productos.length - 1);
                    }
                },

                getTipoSolicitudText(idTipoSolicitud) {
                    const tipo = this.tiposSolicitud.find(t => t.idTipoSolicitud == idTipoSolicitud);
                    return tipo ? tipo.nombre : '';
                },

                getPrioridadText(idPrioridad) {
                    const prioridad = this.prioridades.find(p => p.idPrioridad == idPrioridad);
                    return prioridad ? prioridad.nombre : '';
                },

                getPrioridadNivel(idPrioridad) {
                    const prioridad = this.prioridades.find(p => p.idPrioridad == idPrioridad);
                    if (!prioridad) return 'medium';

                    const nivelMap = {
                        1: 'low',
                        2: 'medium',
                        3: 'high',
                        4: 'urgent'
                    };
                    return nivelMap[prioridad.nivel] || 'medium';
                },

                getCentroCostoText(idCentroCosto) {
                    const centro = this.centrosCosto.find(c => c.idCentroCosto == idCentroCosto);
                    return centro ? centro.nombre : '';
                },

                formatPreviewDate(dateString) {
                    if (!dateString) return 'No especificada';

                    // Añadir la hora a medianoche para evitar problemas de zona horaria
                    const date = new Date(dateString + 'T00:00:00');

                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        timeZone: 'America/Lima' // Cambia esto por tu zona horaria
                    };

                    return date.toLocaleDateString('es-ES', options);
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    files.forEach(file => {
                        this.form.files.push(file);
                    });
                },

                removeFile(index) {
                    this.form.files.splice(index, 1);
                },

                resetForm() {
                    if (confirm('¿Está seguro de que desea limpiar todos los campos?')) {
                        this.form = {
                            titulo: '',
                            idTipoSolicitud: '',
                            solicitante: '{{ $nombreSolicitante }}',
                            idPrioridad: '',
                            fecha_requerida: new Date().toISOString().split('T')[0],
                            idCentroCosto: '',
                            idTipoArea: '',
                            descripcion: '',
                            justificacion: '',
                            observaciones: '',
                            productos: [],
                            files: []
                        };
                        this.currentProductIndex = 0;

                        // Resetear Flatpickr
                        if (this.flatpickrInstance) {
                            this.flatpickrInstance.setDate(this.form.fecha_requerida);
                        }
                    }
                },

                validateAndShowModal() {
                    // Validación básica antes de mostrar el modal
                    if (!this.form.titulo || !this.form.idTipoSolicitud || !this.form.solicitante ||
                        !this.form.idPrioridad || !this.form.fecha_requerida || !this.form.descripcion ||
                        !this.form.justificacion || !this.form.idTipoArea) {
                        toastr.error('Por favor complete todos los campos obligatorios (*)');
                        return;
                    }

                    if (this.form.productos.length === 0) {
                        toastr.error('Debe agregar al menos un producto a la solicitud');
                        return;
                    }

                    // Validar productos
                    for (let i = 0; i < this.form.productos.length; i++) {
                        const product = this.form.productos[i];
                        if (!product.descripcion || !product.cantidad) {
                            toastr.error(`Por favor complete todos los campos obligatorios del producto ${i + 1}`);
                            return;
                        }
                    }

                    // Si pasa todas las validaciones, mostrar el modal
                    this.showConfirmationModal = true;
                },

                async submitFormFromModal() {
                    // Cerrar el modal primero
                    this.showConfirmationModal = false;

                    try {
                        const formData = {
                            ...this.form,
                            total_unidades: this.totalUnits,
                            codigo_solicitud: this.requestCode
                        };

                        const response = await fetch('/solicitudalmacen', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(formData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            toastr.success(`¡Solicitud ${result.codigo} creada exitosamente!`);
                            window.location.href = '/solicitudalmacen';
                        } else {
                            toastr.error('Error al crear la solicitud: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error al enviar la solicitud');
                    }
                },

                // Método original para mantener compatibilidad
                async submitForm() {
                    this.validateAndShowModal();
                }
            }
        }

        function formatArticle(article) {
            if (article.loading) return article.text;

            // Si no tiene nombre, mostrar el código
            const displayName = article.nombre && article.nombre.trim() !== '' ?
                article.nombre :
                (article.codigo_repuesto || article.codigo_barras || article.sku || 'Sin nombre');

            var $container = $(
                '<div class="flex flex-col p-2">' +
                '<div class="flex justify-between items-start mb-1">' +
                '<span class="font-semibold text-gray-800 text-sm">' +
                displayName +
                '</span>' +
                '</div>' +
                '<div class="flex flex-wrap gap-1 text-xs">' +
                (article.codigo_repuesto ?
                    '<span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-medium">' +
                    'Rep: ' + article.codigo_repuesto +
                    '</span>' : '') +
                (article.codigo_barras ?
                    '<span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">' +
                    'Barras: ' + article.codigo_barras +
                    '</span>' : '') +
                (article.marca ?
                    '<span class="bg-green-100 text-green-800 px-2 py-0.5 rounded">' +
                    article.marca +
                    '</span>' : '') +
                '</div>' +
                '</div>'
            );
            return $container;
        }

        function formatArticleSelection(article) {
            if (!article.id) return article.text;

            // Mostrar el código repuesto si no hay nombre
            if (!article.nombre || article.nombre.trim() === '') {
                return article.codigo_repuesto || article.codigo_barras || article.sku || 'Sin nombre';
            }

            // Si tiene nombre y código, mostrar ambos
            const codigo = article.codigo_repuesto || article.codigo_barras || article.sku;
            if (codigo) {
                return article.nombre + ' [' + codigo + ']';
            }

            return article.nombre;
        }
    </script>
</x-layout.default>
