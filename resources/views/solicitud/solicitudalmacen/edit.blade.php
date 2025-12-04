<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    
    <div x-data="warehouseEdit()" class="space-y-6">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudalmacen.index') }}" class="text-primary hover:underline">
                        Solicitudes Almacén
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Editar Solicitud Almacén</span>
                </li>
            </ul>
        </div>

        <!-- Header Mejorado -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Información Principal -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-warehouse text-white text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-xl font-bold text-gray-900">Editar Solicitud de Almacén</h1>
                        </div>
                        <p class="text-base text-gray-600">Modifique los productos y detalles de la solicitud 
                            <span x-text="solicitud.codigo_solicitud" class="font-semibold"></span>
                        </p>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-center lg:justify-end space-x-3 w-full lg:w-auto">
                    <a href="{{ route('solicitudalmacen.index') }}"
                        class="inline-flex items-center px-4 py-3 bg-gray-500 text-white rounded-lg text-base font-semibold hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        Volver
                    </a>

                    <button type="button"
                        class="inline-flex items-center px-4 py-3 bg-amber-500 text-white rounded-lg text-base font-semibold hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                        @click="resetForm()">
                        <i class="fas fa-redo mr-2 text-sm"></i>
                        Restablecer
                    </button>

                    <button type="button"
                        class="inline-flex items-center px-4 py-3 bg-primary text-white rounded-lg text-base font-semibold hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg"
                        @click="submitForm()">
                        <i class="fas fa-save mr-2 text-sm"></i>
                        Actualizar
                    </button>
                </div>
            </div>
        </div>

        <form id="warehouseRequestForm" action="{{ route('solicitudalmacen.update', $id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Información General -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-info-circle text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Información General</h2>
                                    <p class="text-sm text-gray-600">Datos básicos de la solicitud de abastecimiento</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Código de Solicitud -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-barcode text-blue-500 text-sm"></i>
                                        Código de Solicitud
                                    </label>
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <span class="text-lg font-bold text-gray-900" x-text="solicitud.codigo_solicitud"></span>
                                        <button type="button" class="text-gray-500 hover:text-blue-600 transition-colors" @click="copyCode()" title="Copiar código">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Título -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-heading text-blue-500 text-sm"></i>
                                        Título de la Solicitud *
                                    </label>
                                    <input type="text" 
                                        class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Ej: Reabastecimiento Material de Oficina"
                                        x-model="form.titulo"
                                        name="titulo"
                                        required>
                                    <i class="fas fa-heading absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Tipo de Solicitud -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-tag text-blue-500 text-sm"></i>
                                        Tipo de Solicitud *
                                    </label>
                                    <select class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none"
                                        x-model="form.idTipoSolicitud"
                                        name="idTipoSolicitud"
                                        required>
                                        <option value="">Seleccione tipo</option>
                                        <template x-for="tipo in tiposSolicitud" :key="tipo.idTipoSolicitud">
                                            <option :value="tipo.idTipoSolicitud" x-text="tipo.nombre"></option>
                                        </template>
                                    </select>
                                    <i class="fas fa-tag absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Área/Departamento -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-building text-blue-500 text-sm"></i>
                                        Área / Departamento *
                                    </label>
                                    <select class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none"
                                        x-model="form.idTipoArea"
                                        name="idTipoArea"
                                        required>
                                        <option value="">Seleccione área</option>
                                        <template x-for="area in areas" :key="area.idTipoArea">
                                            <option :value="area.idTipoArea" x-text="area.nombre"></option>
                                        </template>
                                    </select>
                                    <i class="fas fa-building absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Solicitante -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-user-tag text-blue-500 text-sm"></i>
                                        Solicitante *
                                    </label>
                                    <input type="text" 
                                        class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                        x-model="form.solicitante"
                                        name="solicitante"
                                        readonly
                                        required>
                                    <i class="fas fa-user-tag absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Prioridad -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-flag text-blue-500 text-sm"></i>
                                        Prioridad *
                                    </label>
                                    <select class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none"
                                        x-model="form.idPrioridad"
                                        name="idPrioridad"
                                        required>
                                        <option value="">Seleccione prioridad</option>
                                        <template x-for="prioridad in prioridades" :key="prioridad.idPrioridad">
                                            <option :value="prioridad.idPrioridad" x-text="prioridad.nombre"></option>
                                        </template>
                                    </select>
                                    <i class="fas fa-flag absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Fecha Requerida -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-blue-500 text-sm"></i>
                                        Fecha Requerida *
                                    </label>
                                    <input type="date" 
                                        class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        x-model="form.fecha_requerida"
                                        :min="new Date().toISOString().split('T')[0]"
                                        name="fecha_requerida"
                                        required>
                                    <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Centro de Costo -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-money-bill-wave text-blue-500 text-sm"></i>
                                        Centro de Costo
                                    </label>
                                    <select class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none"
                                        x-model="form.idCentroCosto"
                                        name="idCentroCosto">
                                        <option value="">Seleccione centro de costo</option>
                                        <template x-for="centro in centrosCosto" :key="centro.idCentroCosto">
                                            <option :value="centro.idCentroCosto" x-text="centro.nombre"></option>
                                        </template>
                                    </select>
                                    <i class="fas fa-money-bill-wave absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Descripción -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-align-left text-blue-500 text-sm"></i>
                                        Descripción *
                                    </label>
                                    <textarea class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        rows="3"
                                        placeholder="Describa el propósito de esta solicitud de abastecimiento..."
                                        x-model="form.descripcion"
                                        name="descripcion"
                                        required></textarea>
                                    <i class="fas fa-align-left absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Justificación -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-comment-alt text-blue-500 text-sm"></i>
                                        Justificación *
                                    </label>
                                    <textarea class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        rows="3"
                                        placeholder="Explique por qué es necesario este abastecimiento..."
                                        x-model="form.justificacion"
                                        name="justificacion"
                                        required></textarea>
                                    <i class="fas fa-comment-alt absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Observaciones -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-sticky-note text-blue-500 text-sm"></i>
                                        Observaciones
                                    </label>
                                    <textarea class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        rows="2"
                                        placeholder="Observaciones adicionales..."
                                        x-model="form.observaciones"
                                        name="observaciones"></textarea>
                                    <i class="fas fa-sticky-note absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Productos Solicitados -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-boxes text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-800">Productos Solicitados</h2>
                                        <p class="text-sm text-gray-600">Modifique los productos que necesita el almacén</p>
                                    </div>
                                </div>
                                <button type="button" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md"
                                    @click="addProduct()">
                                    <i class="fas fa-plus mr-2 text-sm"></i>
                                    Agregar Producto
                                </button>
                            </div>
                        </div>

                        <div class="p-6">
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
                                        <span class="text-sm font-bold text-green-600" x-text="currentProductIndex + 1"></span>
                                        <span class="text-sm text-gray-500">de</span>
                                        <span class="text-sm font-bold text-gray-700" x-text="form.productos.length"></span>
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
                                        <button type="button"
                                            class="w-2 h-2 rounded-full transition-all duration-300"
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
                                            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                                                <!-- Header del producto -->
                                                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-sm">
                                                            <i class="fas fa-box text-white text-sm"></i>
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <span class="text-base font-semibold text-gray-800" x-text="`Producto ${index + 1}`"></span>
                                                            <div class="flex items-center space-x-2 mt-1">
                                                                <span class="text-xs bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200 text-gray-700 font-medium"
                                                                    x-text="product.codigo_barras || 'Sin código'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-full hover:bg-red-50"
                                                        @click="removeProduct(index)" title="Eliminar producto">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>

                                                <!-- Grid de información del producto -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <!-- Búsqueda por código -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-search text-green-600 text-xs"></i>
                                                            </div>
                                                            Buscar Artículo por Código
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                                placeholder="Ingrese código de barras, SKU o código repuesto..."
                                                                x-model="product.searchCode"
                                                                @input.debounce.500="searchArticle(index)">
                                                            <i class="fas fa-barcode absolute left-3 top-3 text-gray-400"></i>
                                                            <button class="absolute right-3 top-3 text-green-500 hover:text-green-700"
                                                                @click="searchArticle(index)">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Descripción -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-align-left text-green-600 text-xs"></i>
                                                            </div>
                                                            Descripción del Producto *
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                                placeholder="Nombre del producto"
                                                                x-model="product.descripcion"
                                                                :name="`productos[${index}][descripcion]`"
                                                                required>
                                                            <i class="fas fa-align-left absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Categoría -->
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-tags text-green-600 text-xs"></i>
                                                            </div>
                                                            Categoría
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                placeholder="Categoría"
                                                                x-model="product.categoria_nombre"
                                                                :name="`productos[${index}][categoria_nombre]`">
                                                            <i class="fas fa-tags absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Cantidad -->
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-cubes text-green-600 text-xs"></i>
                                                            </div>
                                                            Cantidad *
                                                        </label>
                                                        <div class="relative">
                                                            <input type="number"
                                                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                                min="1"
                                                                placeholder="1"
                                                                x-model="product.cantidad"
                                                                :name="`productos[${index}][cantidad]`"
                                                                required>
                                                            <i class="fas fa-cubes absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Unidad -->
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-balance-scale text-green-600 text-xs"></i>
                                                            </div>
                                                            Unidad *
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                                placeholder="Unidad de medida"
                                                                x-model="product.unidad_nombre"
                                                                :name="`productos[${index}][unidad_nombre]`">
                                                            <i class="fas fa-balance-scale absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Código Barras -->
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-barcode text-green-600 text-xs"></i>
                                                            </div>
                                                            Código Barras
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                placeholder="Código de barras"
                                                                x-model="product.codigo_barras"
                                                                :name="`productos[${index}][codigo_barras]`">
                                                            <i class="fas fa-barcode absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Marca -->
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-copyright text-green-600 text-xs"></i>
                                                            </div>
                                                            Marca
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                placeholder="Marca del producto"
                                                                x-model="product.marca_nombre"
                                                                :name="`productos[${index}][marca_nombre]`">
                                                            <i class="fas fa-copyright absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Modelo -->
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-cube text-green-600 text-xs"></i>
                                                            </div>
                                                            Modelo
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                placeholder="Modelo del producto"
                                                                x-model="product.modelo_nombre"
                                                                :name="`productos[${index}][modelo_nombre]`">
                                                            <i class="fas fa-cube absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Especificaciones Técnicas -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-cogs text-green-600 text-xs"></i>
                                                            </div>
                                                            Especificaciones Técnicas
                                                        </label>
                                                        <div class="relative">
                                                            <textarea class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                                rows="3"
                                                                placeholder="Especificaciones, características..."
                                                                x-model="product.especificaciones"
                                                                :name="`productos[${index}][especificaciones]`"></textarea>
                                                            <i class="fas fa-cogs absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Justificación del Producto -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-comment-dots text-green-600 text-xs"></i>
                                                            </div>
                                                            Justificación del Producto
                                                        </label>
                                                        <div class="relative">
                                                            <textarea class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                                rows="3"
                                                                placeholder="¿Por qué necesita este producto específico?"
                                                                x-model="product.justificacion_producto"
                                                                :name="`productos[${index}][justificacion_producto]`"></textarea>
                                                            <i class="fas fa-comment-dots absolute left-3 top-3 text-gray-400"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Campos ocultos -->
                                                    <input type="hidden" :name="`productos[${index}][idArticulo]`" x-model="product.idArticulo">
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
                                <p class="text-gray-500 max-w-md mx-auto">Comience agregando el primer producto a su solicitud</p>
                                <button class="inline-flex items-center px-4 py-2.5 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md mt-4"
                                    @click="addProduct()">
                                    <i class="fas fa-plus mr-2 text-sm"></i>
                                    Agregar Primer Producto
                                </button>
                            </div>

                            <!-- Resumen de Productos -->
                            <div class="mt-8 bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border border-gray-200 shadow-lg"
                                x-show="form.productos.length > 0">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                                            <i class="fas fa-chart-pie text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Resumen de Productos</h3>
                                            <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                                <i class="fas fa-info-circle text-green-500"></i>
                                                <span x-text="form.productos.length + ' producto(s) en total'"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 mb-1">Total de Productos</p>
                                                <p class="text-2xl font-bold text-gray-900" x-text="form.productos.length"></p>
                                            </div>
                                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-boxes text-blue-500 text-lg"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 mb-1">Total de Unidades</p>
                                                <p class="text-2xl font-bold text-gray-900" x-text="totalUnits"></p>
                                            </div>
                                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-cubes text-green-500 text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="total_unidades" x-model="totalUnits">
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-plus-circle text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Información Adicional</h2>
                                    <p class="text-sm text-gray-600">Detalles complementarios para el proceso</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Archivos Adjuntos -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-paperclip text-purple-500 text-sm"></i>
                                        Archivos Adjuntos
                                    </label>

                                    <!-- Área de upload -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-purple-400 transition-all duration-300 bg-gradient-to-br from-gray-50 to-white hover:from-purple-50 hover:to-indigo-50"
                                        @click="$refs.fileInput.click()">
                                        <input type="file" x-ref="fileInput" multiple class="hidden"
                                            name="archivos[]" @change="handleFileSelect">
                                        <i class="fas fa-cloud-upload-alt text-purple-400 text-4xl mb-3"></i>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Arrastre archivos o haga clic para seleccionar</h4>
                                        <p class="text-xs text-gray-500">Cotizaciones, imágenes, especificaciones - máximo 10MB por archivo</p>
                                    </div>

                                    <!-- Archivos existentes -->
                                    <div x-show="existingFiles.length > 0" class="space-y-2 mt-4">
                                        <div class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fas fa-folder-open text-purple-500 text-sm"></i>
                                            Archivos existentes
                                        </div>
                                        <template x-for="(file, index) in existingFiles" :key="index">
                                            <div class="flex items-center justify-between bg-white rounded-lg p-3 border shadow-sm transition-all duration-300 hover:shadow">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <i class="fas fa-file text-purple-500"></i>
                                                    <div class="flex-1 min-w-0">
                                                        <span class="text-sm text-gray-700 truncate block" x-text="file.nombre_archivo"></span>
                                                        <span class="text-xs text-gray-500" x-text="`${Math.round(file.tamaño / 1024)} KB`"></span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a :href="file.ruta_completa" target="_blank"
                                                        class="text-blue-500 hover:text-blue-700 transition-colors p-2 rounded-full hover:bg-blue-50"
                                                        title="Ver archivo">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                    <button type="button" @click="removeExistingFile(index)"
                                                        class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-full hover:bg-red-50"
                                                        title="Eliminar archivo">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                        <input type="hidden" name="archivos_eliminados" x-model="deletedFiles">
                                    </div>

                                    <!-- Nuevos archivos seleccionados -->
                                    <div x-show="form.files.length > 0" class="space-y-2 mt-4">
                                        <div class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fas fa-plus-circle text-green-500 text-sm"></i>
                                            Nuevos archivos
                                        </div>
                                        <template x-for="(file, index) in form.files" :key="index">
                                            <div class="flex items-center justify-between bg-white rounded-lg p-3 border shadow-sm transition-all duration-300 hover:shadow">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <i class="fas fa-file text-purple-500"></i>
                                                    <div class="flex-1 min-w-0">
                                                        <span class="text-sm text-gray-700 truncate block" x-text="file.name"></span>
                                                        <span class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></span>
                                                    </div>
                                                </div>
                                                <button type="button"
                                                    class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-full hover:bg-red-50"
                                                    @click="removeFile(index)" title="Eliminar archivo">
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

                <!-- Columna Lateral -->
                <div class="space-y-6">
                    <!-- Resumen de la Solicitud -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-chart-bar text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Resumen</h2>
                                    <p class="text-sm text-gray-600">Vista previa de la solicitud</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Estado -->
                            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-edit text-blue-500"></i>
                                    <span class="text-sm font-medium text-blue-800">Editando Solicitud</span>
                                </div>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-xs text-gray-600">Estado actual:</span>
                                    <span :class="getStatusBadgeClass(solicitud.estado)" class="text-xs font-medium px-2 py-1 rounded-full" x-text="getStatusText(solicitud.estado)"></span>
                                </div>
                            </div>

                            <!-- Información General -->
                            <div class="space-y-3 mb-6">
                                <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Información General</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Código:</span>
                                        <span class="font-medium text-gray-900" x-text="solicitud.codigo_solicitud"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Título:</span>
                                        <span class="font-medium text-gray-900" x-text="form.titulo || 'Sin título'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Solicitante:</span>
                                        <span class="font-medium text-gray-900" x-text="form.solicitante || 'No especificado'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Área:</span>
                                        <span class="font-medium text-gray-900" x-text="getAreaText(form.idTipoArea) || 'No especificada'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tipo:</span>
                                        <span class="font-medium text-gray-900" x-text="getTipoSolicitudText(form.idTipoSolicitud) || 'No especificado'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Prioridad:</span>
                                        <span class="font-medium text-gray-900" :class="getPriorityColorClass(form.idPrioridad)" 
                                              x-text="getPrioridadText(form.idPrioridad) || 'No especificada'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Fecha Requerida:</span>
                                        <span class="font-medium text-gray-900" x-text="form.fecha_requerida ? formatPreviewDate(form.fecha_requerida) : 'No especificada'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm" x-show="form.idCentroCosto">
                                        <span class="text-gray-600">Centro de Costo:</span>
                                        <span class="font-medium text-gray-900" x-text="getCentroCostoText(form.idCentroCosto)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen de Productos -->
                            <div class="space-y-3" x-show="form.productos.length > 0">
                                <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Resumen de Productos</h3>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="text-center bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                        <i class="fas fa-boxes text-blue-500 text-lg mb-1"></i>
                                        <div class="text-xs text-gray-600">Total Productos</div>
                                        <div class="text-lg font-bold text-gray-900" x-text="form.productos.length"></div>
                                    </div>

                                    <div class="text-center bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                        <i class="fas fa-cubes text-blue-500 text-lg mb-1"></i>
                                        <div class="text-xs text-gray-600">Total Unidades</div>
                                        <div class="text-lg font-bold text-gray-900" x-text="totalUnits"></div>
                                    </div>
                                </div>

                                <div class="space-y-2 mt-3 max-h-60 overflow-y-auto">
                                    <template x-for="(product, index) in form.productos" :key="index">
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-sm font-medium text-gray-800 truncate" x-text="product.descripcion || 'Sin descripción'"></span>
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2" x-text="product.cantidad + ' ' + (product.unidad_nombre || 'unidad')"></span>
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center gap-2">
                                                <span x-show="product.codigo_barras" x-text="product.codigo_barras"></span>
                                                <span x-show="product.marca_nombre" class="flex items-center gap-1">
                                                    <i class="fas fa-copyright text-gray-400 text-xs"></i>
                                                    <span x-text="product.marca_nombre"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Sin productos -->
                            <div x-show="form.productos.length === 0" class="text-center py-4">
                                <i class="fas fa-inbox text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">No hay productos cargados</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información de ayuda -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl border border-blue-200 p-6 shadow-md">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-lightbulb text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Guía de Edición</h3>
                                <p class="text-sm text-blue-700">Consejos para modificar la solicitud</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-search text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Búsqueda rápida</p>
                                    <p class="text-xs text-gray-500 mt-1">Use códigos para cargar automáticamente</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-sliders-h text-yellow-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Slider de productos</p>
                                    <p class="text-xs text-gray-500 mt-1">Navegue entre productos con flechas</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-trash-alt text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Eliminar productos</p>
                                    <p class="text-xs text-gray-500 mt-1">Puede remover productos no necesarios</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-file text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Archivos adjuntos</p>
                                    <p class="text-xs text-gray-500 mt-1">Agregue o elimine archivos existentes</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-save text-indigo-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Guardar cambios</p>
                                    <p class="text-xs text-gray-500 mt-1">Verifique todos los datos antes de actualizar</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-undo text-pink-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Restablecer cambios</p>
                                    <p class="text-xs text-gray-500 mt-1">Puede volver a los valores originales</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-100 rounded-lg border border-blue-200">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                                <p class="text-xs text-blue-800">La solicitud mantendrá su estado actual durante la edición</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script>
        function warehouseEdit() {
            return {
                tiposSolicitud: @json($tiposSolicitud),
                prioridades: @json($prioridades),
                centrosCosto: @json($centrosCosto),
                areas: @json($areas),
                solicitud: {},
                originalForm: {},
                existingFiles: [],
                deletedFiles: [],
                currentProductIndex: 0,

                form: {
                    titulo: '',
                    idTipoSolicitud: '',
                    idTipoArea: '',
                    solicitante: '',
                    idPrioridad: '',
                    fecha_requerida: '',
                    idCentroCosto: '',
                    descripcion: '',
                    justificacion: '',
                    observaciones: '',
                    productos: [],
                    files: []
                },

                async init() {
                    await this.loadSolicitudData();
                },

                async loadSolicitudData() {
                    try {
                        const response = await fetch(`/solicitudalmacen/{{ $id }}/edit-data`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.solicitud = data.solicitud;
                            this.existingFiles = data.archivos || [];
                            this.populateForm();
                            this.originalForm = JSON.parse(JSON.stringify(this.form));
                        } else {
                            this.showError('Error al cargar los datos de la solicitud');
                            setTimeout(() => window.location.href = '/solicitudalmacen', 2000);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showError('Error al cargar los datos de la solicitud');
                    }
                },

                populateForm() {
                    let fechaRequerida = '';
                    if (this.solicitud.fecha_requerida) {
                        if (typeof this.solicitud.fecha_requerida === 'string') {
                            fechaRequerida = this.solicitud.fecha_requerida;
                        } else {
                            const date = new Date(this.solicitud.fecha_requerida);
                            fechaRequerida = date.toISOString().split('T')[0];
                        }
                    } else {
                        fechaRequerida = new Date().toISOString().split('T')[0];
                    }

                    this.form = {
                        titulo: this.solicitud.titulo || '',
                        idTipoSolicitud: this.solicitud.idTipoSolicitud || '',
                        idTipoArea: this.solicitud.idTipoArea || '',
                        solicitante: this.solicitud.solicitante || '',
                        idPrioridad: this.solicitud.idPrioridad || '',
                        fecha_requerida: fechaRequerida,
                        idCentroCosto: this.solicitud.idCentroCosto || '',
                        descripcion: this.solicitud.descripcion || '',
                        justificacion: this.solicitud.justificacion || '',
                        observaciones: this.solicitud.observaciones || '',
                        productos: this.solicitud.detalles ? this.solicitud.detalles.map(detalle => ({
                            idArticulo: detalle.idArticulo,
                            searchCode: detalle.codigo_producto || '',
                            descripcion: detalle.descripcion_producto || '',
                            cantidad: detalle.cantidad || 1,
                            codigo_barras: detalle.codigo_producto || '',
                            categoria_nombre: detalle.categoria || '',
                            unidad_nombre: detalle.unidad || '',
                            marca_nombre: detalle.marca || '',
                            modelo_nombre: detalle.modelo_nombre || '',
                            especificaciones: detalle.especificaciones_tecnicas || '',
                            justificacion_producto: detalle.justificacion_producto || ''
                        })) : [],
                        files: []
                    };
                },

                async searchArticle(index) {
                    const product = this.form.productos[index];
                    if (!product.searchCode) return;

                    try {
                        const response = await fetch(`/solicitudalmacen/buscar-articulo/${product.searchCode}`);
                        const data = await response.json();

                        if (data.success && data.articulo) {
                            const articulo = data.articulo;
                            
                            product.idArticulo = articulo.idArticulos;
                            product.descripcion = articulo.nombre;
                            product.codigo_barras = articulo.codigo_barras;
                            product.categoria_nombre = articulo.categoria_nombre || '';
                            product.unidad_nombre = articulo.unidad_nombre || '';
                            product.marca_nombre = articulo.marca_nombre || '';
                            product.modelo_nombre = articulo.modelo_nombre || '';
                            
                            this.showSuccess('Artículo encontrado y cargado');
                        } else {
                            this.showWarning('Artículo no encontrado');
                        }
                    } catch (error) {
                        console.error('Error buscando artículo:', error);
                        this.showError('Error al buscar el artículo');
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

                getAreaText(idTipoArea) {
                    const area = this.areas.find(a => a.idTipoArea == idTipoArea);
                    return area ? area.nombre : '';
                },

                getCentroCostoText(idCentroCosto) {
                    const centro = this.centrosCosto.find(c => c.idCentroCosto == idCentroCosto);
                    return centro ? centro.nombre : '';
                },

                getStatusText(status) {
                    const statusMap = {
                        'pendiente': 'Pendiente',
                        'aprobada': 'Aprobada',
                        'rechazada': 'Rechazada',
                        'en_proceso': 'En Proceso',
                        'completada': 'Completada'
                    };
                    return statusMap[status] || status;
                },

                getStatusBadgeClass(status) {
                    const classMap = {
                        'pendiente': 'bg-yellow-100 text-yellow-800',
                        'aprobada': 'bg-green-100 text-green-800',
                        'rechazada': 'bg-red-100 text-red-800',
                        'en_proceso': 'bg-blue-100 text-blue-800',
                        'completada': 'bg-gray-100 text-gray-800'
                    };
                    return classMap[status] || 'bg-gray-100 text-gray-800';
                },

                getPriorityColorClass(idPrioridad) {
                    const prioridad = this.prioridades.find(p => p.idPrioridad == idPrioridad);
                    if (!prioridad) return 'text-gray-700';
                    
                    const nivel = prioridad.nivel || 2;
                    if (nivel >= 4) return 'text-red-600';
                    if (nivel === 3) return 'text-orange-600';
                    if (nivel === 2) return 'text-yellow-600';
                    return 'text-green-600';
                },

                formatPreviewDate(dateString) {
                    if (!dateString) return 'No especificada';
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('es-ES', options);
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                copyCode() {
                    navigator.clipboard.writeText(this.solicitud.codigo_solicitud).then(() => {
                        this.showSuccess('Código copiado al portapapeles');
                    });
                },

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    files.forEach(file => {
                        if (file.size > 10 * 1024 * 1024) {
                            this.showError('El archivo ' + file.name + ' excede el tamaño máximo de 10MB');
                            return;
                        }
                        this.form.files.push(file);
                    });
                    event.target.value = '';
                },

                removeFile(index) {
                    this.form.files.splice(index, 1);
                },

                removeExistingFile(index) {
                    this.deletedFiles.push(this.existingFiles[index].idSolicitudAlmacenArchivo);
                    this.existingFiles.splice(index, 1);
                    this.showInfo('Archivo marcado para eliminación');
                },

                resetForm() {
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
                    modal.innerHTML = `
                        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 animate-scaleIn" id="modalContent">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50 rounded-t-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Confirmar Restablecimiento</h3>
                                        <p class="text-sm text-gray-600">Se perderán todos los cambios</p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4">
                                <div class="flex items-start gap-3 mb-4">
                                    <i class="fas fa-eraser text-amber-500 text-xl mt-0.5"></i>
                                    <div>
                                        <p class="text-gray-700 font-medium mb-1">¿Restablecer formulario?</p>
                                        <p class="text-sm text-gray-500">Se perderán todas las modificaciones realizadas.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                                <div class="flex gap-3 justify-end">
                                    <button type="button" id="cancelReset" 
                                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 font-medium">
                                        <i class="fas fa-times mr-2"></i>
                                        Cancelar
                                    </button>
                                    <button type="button" id="confirmReset" 
                                        class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all duration-200 font-medium shadow-md hover:shadow-lg">
                                        <i class="fas fa-eraser mr-2"></i>
                                        Sí, Restablecer
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes scaleIn {
                            from { opacity: 0; transform: scale(0.9); }
                            to { opacity: 1; transform: scale(1); }
                        }
                        .animate-scaleIn { animation: scaleIn 0.3s ease-out forwards; }
                    `;
                    document.head.appendChild(style);
                    document.body.appendChild(modal);

                    const confirmReset = document.getElementById('confirmReset');
                    const cancelReset = document.getElementById('cancelReset');
                    const modalContent = document.getElementById('modalContent');

                    const cleanup = () => {
                        modal.remove();
                        style.remove();
                    };

                    const closeModal = () => {
                        if (modalContent) {
                            modalContent.style.transform = 'scale(0.95)';
                            modalContent.style.opacity = '0';
                        }
                        setTimeout(cleanup, 300);
                    };

                    confirmReset.onclick = () => {
                        closeModal();
                        this.form = JSON.parse(JSON.stringify(this.originalForm));
                        this.deletedFiles = [];
                        this.form.files = [];
                        this.currentProductIndex = 0;
                        this.showSuccess('Formulario restablecido correctamente');
                    };

                    cancelReset.onclick = () => {
                        this.showInfo('Operación cancelada');
                        closeModal();
                    };

                    modal.onclick = (e) => {
                        if (e.target === modal) {
                            this.showInfo('Operación cancelada');
                            closeModal();
                        }
                    };

                    const handleEscape = (e) => {
                        if (e.key === 'Escape') {
                            this.showInfo('Operación cancelada');
                            closeModal();
                            document.removeEventListener('keydown', handleEscape);
                        }
                    };
                    document.addEventListener('keydown', handleEscape);
                },

                submitForm() {
                    // Validación básica
                    if (!this.form.titulo || !this.form.idTipoSolicitud || !this.form.solicitante || 
                        !this.form.idPrioridad || !this.form.fecha_requerida || !this.form.descripcion || 
                        !this.form.justificacion || !this.form.idTipoArea) {
                        this.showError('Por favor complete todos los campos obligatorios (*)');
                        return;
                    }

                    if (this.form.productos.length === 0) {
                        this.showError('Debe agregar al menos un producto a la solicitud');
                        return;
                    }

                    // Validar productos
                    for (let i = 0; i < this.form.productos.length; i++) {
                        const product = this.form.productos[i];
                        if (!product.descripcion || !product.cantidad || !product.unidad_nombre) {
                            this.showError(`Por favor complete todos los campos obligatorios del producto ${i + 1}`);
                            return;
                        }
                        if (product.cantidad <= 0) {
                            this.showError(`La cantidad del producto ${i + 1} debe ser mayor a 0`);
                            return;
                        }
                    }

                    // Mostrar confirmación
                    if (confirm('¿Está seguro de que desea actualizar la solicitud de almacén?')) {
                        this.showSuccess('Actualizando solicitud...');
                        document.getElementById('warehouseRequestForm').submit();
                    }
                },

                showSuccess(message) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(message, '¡Éxito!', {
                            timeOut: 3000,
                            progressBar: true
                        });
                    } else {
                        alert(message);
                    }
                },

                showError(message) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(message, 'Error', {
                            timeOut: 5000,
                            progressBar: true
                        });
                    } else {
                        alert(message);
                    }
                },

                showWarning(message) {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning(message, 'Advertencia', {
                            timeOut: 4000,
                            progressBar: true
                        });
                    } else {
                        alert(message);
                    }
                },

                showInfo(message) {
                    if (typeof toastr !== 'undefined') {
                        toastr.info(message, 'Información', {
                            timeOut: 3000,
                            progressBar: true
                        });
                    } else {
                        alert(message);
                    }
                }
            }
        }
    </script>
</x-layout.default>