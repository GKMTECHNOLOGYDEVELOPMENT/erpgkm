<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <div x-data="editPurchaseRequest()" class="space-y-6">
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudcompra.index') }}" class="text-primary hover:underline">
                        Solicitudes Compra
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Editar Solicitud Compra</span>
                </li>
            </ul>
        </div>
        <!-- Header Mejorado -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Información Principal -->
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-xl font-bold text-gray-900">Editar Solicitud de Compra</h1>
                            <span
                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                Editando
                            </span>
                        </div>
                        <p class="text-base text-gray-600">Modifique los artículos y detalles de la solicitud
                            {{ $solicitud->codigo_solicitud }}</p>
                    </div>
                </div>

                <!-- Botones de Acción - Al Extremo Derecho -->
                <div class="flex justify-center lg:justify-end space-x-3 w-full lg:w-auto">
                    <a href="{{ route('solicitudcompra.index') }}"
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

        <form id="purchaseRequestForm" action="{{ route('solicitudcompra.update', $solicitud->idSolicitudCompra) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Campos hidden importantes -->
            <input type="hidden" name="codigo_solicitud" value="{{ $solicitud->codigo_solicitud }}">
            <input type="hidden" name="idSolicitudAlmacen" value="{{ $solicitud->idSolicitudAlmacen }}">
            <input type="hidden" name="solicitante_compra" value="{{ $solicitud->solicitante_compra }}">
            <input type="hidden" name="solicitante_almacen" value="{{ $solicitud->solicitante_almacen }}">

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Información General -->
                    <div
                        class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-info-circle text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Información General</h2>
                                    <p class="text-sm text-gray-600">Datos básicos de la solicitud</p>
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
                                    <div
                                        class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <span
                                            class="text-lg font-bold text-gray-900">{{ $solicitud->codigo_solicitud }}</span>
                                        <button type="button"
                                            class="text-gray-500 hover:text-blue-600 transition-colors"
                                            @click="copyCode()" title="Copiar código">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Solicitud de Almacén -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-warehouse text-blue-500 text-sm"></i>
                                        Solicitud de Almacén
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                            value="{{ $solicitud->solicitudAlmacen ? $solicitud->solicitudAlmacen->codigo_solicitud . ' - ' . $solicitud->solicitudAlmacen->titulo : 'No seleccionada' }}"
                                            readonly>
                                    </div>
                                    <small class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fas fa-info-circle"></i>
                                        No se puede modificar en edición
                                    </small>
                                </div>

                                <!-- Solicitante Almacén -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-user-tag text-blue-500 text-sm"></i>
                                        Solicitante Almacén
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                            value="{{ $solicitud->solicitante_almacen }}" readonly>
                                    </div>
                                </div>

                                <!-- Solicitante Compra -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-user-check text-blue-500 text-sm"></i>
                                        Solicitante Compra *
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                            value="{{ $solicitud->solicitante_compra }}" readonly>
                                    </div>
                                </div>

                                <!-- Departamento -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-building text-blue-500 text-sm"></i>
                                        Departamento *
                                    </label>
                                    <div class="relative">
                                        <select
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            x-model="form.idTipoArea" name="idTipoArea" required>
                                            <option value="">Seleccione departamento</option>
                                            @foreach ($tipoAreas as $area)
                                                <option value="{{ $area->idTipoArea }}"
                                                    {{ $solicitud->idTipoArea == $area->idTipoArea ? 'selected' : '' }}>
                                                    {{ $area->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            x-model="form.idPrioridad" name="idPrioridad" required>
                                            <option value="">Seleccione prioridad</option>
                                            @foreach ($prioridades as $prioridad)
                                                <option value="{{ $prioridad->idPrioridad }}"
                                                    {{ $solicitud->idPrioridad == $prioridad->idPrioridad ? 'selected' : '' }}>
                                                    {{ $prioridad->nombre }} (Nivel {{ $prioridad->nivel }})
                                                </option>
                                            @endforeach
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
                                        <input type="date"
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            x-model="form.fecha_requerida" name="fecha_requerida"
                                            :min="new Date().toISOString().split('T')[0]" required>
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
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            x-model="form.idCentroCosto" name="idCentroCosto">
                                            <option value="">Seleccione centro de costo</option>
                                            @foreach ($centrosCosto as $centro)
                                                <option value="{{ $centro->idCentroCosto }}"
                                                    {{ $solicitud->idCentroCosto == $centro->idCentroCosto ? 'selected' : '' }}>
                                                    {{ $centro->codigo }} - {{ $centro->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Proyecto Asociado -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-project-diagram text-blue-500 text-sm"></i>
                                        Proyecto Asociado
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="Nombre del proyecto" x-model="form.proyecto_asociado"
                                            name="proyecto_asociado">

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
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            rows="3" placeholder="Explique por qué es necesaria esta compra" x-model="form.justificacion"
                                            name="justificacion" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Artículos de la Solicitud -->
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
                                        <h2 class="text-lg font-bold text-gray-800">Artículos Solicitados</h2>
                                        <p class="text-sm text-gray-600">Productos cargados desde la solicitud de
                                            almacén</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border shadow-sm"
                                    x-show="loadingAlmacen">
                                    <div
                                        class="w-4 h-4 border-2 border-green-500 border-t-transparent rounded-full animate-spin">
                                    </div>
                                    <span class="text-sm text-gray-600">Cargando productos...</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <!-- Controles del slider -->
                            <div class="flex items-center justify-between mb-4" x-show="form.items.length > 1">
                                <div class="flex items-center space-x-2">
                                    <button type="button"
                                        class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        @click="currentItemIndex = (currentItemIndex - 1 + form.items.length) % form.items.length"
                                        :disabled="form.items.length <= 1">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>

                                    <div class="flex items-center space-x-1">
                                        <span class="text-sm font-medium text-gray-700">Artículo</span>
                                        <span class="text-sm font-bold text-green-600"
                                            x-text="currentItemIndex + 1"></span>
                                        <span class="text-sm text-gray-500">de</span>
                                        <span class="text-sm font-bold text-gray-700"
                                            x-text="form.items.length"></span>
                                    </div>

                                    <button type="button"
                                        class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        @click="currentItemIndex = (currentItemIndex + 1) % form.items.length"
                                        :disabled="form.items.length <= 1">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                                <!-- Indicadores de puntos -->
                                <div class="flex space-x-1" x-show="form.items.length > 1">
                                    <template x-for="(item, index) in form.items" :key="index">
                                        <button type="button"
                                            class="w-2 h-2 rounded-full transition-all duration-300"
                                            :class="index === currentItemIndex ? 'bg-green-500' : 'bg-gray-300'"
                                            @click="currentItemIndex = index"></button>
                                    </template>
                                </div>
                            </div>

                            <!-- Contenedor del slider -->
                            <div class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-200">
                                <div class="flex transition-transform duration-500 ease-in-out"
                                    :style="`transform: translateX(-${currentItemIndex * 100}%)`">
                                    <template x-for="(item, index) in form.items" :key="index">
                                        <div class="w-full flex-shrink-0 px-4 py-6">
                                            <div
                                                class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                                                <!-- Header del artículo -->
                                                <div
                                                    class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                                    <div class="flex items-center space-x-4">
                                                        <div
                                                            class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-sm">
                                                            <i class="fas fa-box text-white text-sm"></i>
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <span class="text-base font-semibold text-gray-800"
                                                                x-text="`Artículo ${index + 1}`"></span>
                                                            <div class="flex items-center space-x-2 mt-1">
                                                                <span
                                                                    class="text-xs bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200 text-gray-700 font-medium"
                                                                    x-text="item.codigo_producto || codigoSolicitud + '-' + String(index + 1).padStart(2, '0')"></span>
                                                                <span
                                                                    class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-md border border-green-200 flex items-center gap-1.5"
                                                                    x-show="item.fromAlmacen">
                                                                    <i class="fas fa-check-circle text-xs"></i>
                                                                    Desde Almacén
                                                                </span>
                                                                <span
                                                                    class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md border border-blue-200 flex items-center gap-1.5"
                                                                    x-show="!item.fromAlmacen">
                                                                    <i class="fas fa-edit text-xs"></i>
                                                                    Editado
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500 flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-lg">
                                                        <i class="fas fa-layer-group text-gray-400"></i>
                                                        <span x-text="index + 1" class="font-medium"></span> de <span
                                                            x-text="form.items.length" class="font-medium"></span>
                                                    </div>
                                                </div>

                                                <!-- Grid de información del artículo -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <!-- Descripción -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i
                                                                    class="fas fa-align-left text-green-600 text-xs"></i>
                                                            </div>
                                                            Descripción del Artículo *
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                x-model="item.descripcion_producto"
                                                                :name="`items[${index}][descripcion_producto]`"
                                                                :readonly="item.fromAlmacen" required>
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
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                x-model="item.categoria"
                                                                :name="`items[${index}][categoria]`"
                                                                :readonly="item.fromAlmacen">
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
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                                min="1" x-model="item.cantidad"
                                                                :name="`items[${index}][cantidad]`"
                                                                @change="updateItemTotal(index)" required>
                                                        </div>
                                                        <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-1"
                                                            x-show="item.fromAlmacen && item.cantidad_original">
                                                            <i class="fas fa-info-circle text-gray-400"></i>
                                                            Cantidad original: <span x-text="item.cantidad_original"
                                                                class="font-medium"></span>
                                                        </p>
                                                    </div>

                                                    <!-- Unidad -->
                                                    <div class="space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i
                                                                    class="fas fa-balance-scale text-green-600 text-xs"></i>
                                                            </div>
                                                            Unidad
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                x-model="item.unidad"
                                                                :name="`items[${index}][unidad]`"
                                                                :readonly="item.fromAlmacen">
                                                        </div>
                                                    </div>

                                                    <!-- Precio Unitario -->
                                                    <div class="space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-tag text-green-600 text-xs"></i>
                                                            </div>
                                                            Precio Unitario *
                                                        </label>
                                                        <div class="flex space-x-3">
                                                            <button type="button"
                                                                class="px-4 py-3 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-sm flex items-center gap-2 font-medium"
                                                                @click="cycleCurrency(index)"
                                                                :title="getMonedaNombre(item.idMonedas)">
                                                                <span class="font-semibold"
                                                                    x-text="getMonedaSimbolo(item.idMonedas)"></span>
                                                                <i class="fas fa-chevron-down text-xs"></i>
                                                            </button>
                                                            <div class="relative flex-1">
                                                                <input type="number"
                                                                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                                    min="0" step="0.01"
                                                                    x-model="item.precio_unitario_estimado"
                                                                    :name="`items[${index}][precio_unitario_estimado]`"
                                                                    @change="updateItemTotal(index)" required>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" :name="`items[${index}][idMonedas]`"
                                                            x-model="item.idMonedas">
                                                        <p
                                                            class="text-xs text-gray-500 flex items-center gap-1.5 mt-1">
                                                            <i class="fas fa-info-circle text-gray-400"></i>
                                                            <span x-text="getMonedaNombre(item.idMonedas)"
                                                                class="font-medium"></span> - Precio estimado para
                                                            compra
                                                        </p>
                                                    </div>

                                                    <!-- Total -->
                                                    <div class="space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i
                                                                    class="fas fa-calculator text-green-600 text-xs"></i>
                                                            </div>
                                                            Total
                                                        </label>
                                                        <div
                                                            class="px-4 py-3 bg-white border border-gray-300 rounded-lg flex items-center shadow-sm">
                                                            <span class="text-base font-bold text-emerald-700"
                                                                x-text="getMonedaSimbolo(item.idMonedas) + (item.total_producto || '0.00')"></span>
                                                        </div>
                                                        <input type="hidden"
                                                            :name="`items[${index}][total_producto]`"
                                                            x-model="item.total_producto">
                                                    </div>

                                                    <!-- Código del Producto -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-barcode text-green-600 text-xs"></i>
                                                            </div>
                                                            Código del Producto
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                x-model="item.codigo_producto"
                                                                :name="`items[${index}][codigo_producto]`"
                                                                :readonly="item.fromAlmacen">
                                                        </div>
                                                        <input type="hidden"
                                                            :name="`items[${index}][idSolicitudAlmacenDetalle]`"
                                                            x-model="item.idSolicitudAlmacenDetalle">
                                                        <input type="hidden" :name="`items[${index}][idArticulo]`"
                                                            x-model="item.idArticulo">
                                                        <input type="hidden"
                                                            :name="`items[${index}][idSolicitudCompraDetalle]`"
                                                            x-model="item.idSolicitudCompraDetalle">
                                                    </div>

                                                    <!-- Marca -->
                                                    <div class="md:col-span-2 space-y-2">
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
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                x-model="item.marca" :name="`items[${index}][marca]`"
                                                                :readonly="item.fromAlmacen">
                                                        </div>
                                                    </div>

                                                    <!-- Proveedor Sugerido -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-truck text-green-600 text-xs"></i>
                                                            </div>
                                                            Proveedor Sugerido
                                                        </label>
                                                        <div class="relative">
                                                            <select
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white appearance-none"
                                                                x-model="item.idProveedor"
                                                                :name="`items[${index}][idProveedor]`">
                                                                <option value="">Seleccione un proveedor</option>
                                                                @foreach ($proveedores as $proveedor)
                                                                    <option value="{{ $proveedor->idProveedor }}">
                                                                        {{ $proveedor->nombre }}
                                                                        @if ($proveedor->telefono)
                                                                            - Tel: {{ $proveedor->telefono }}
                                                                        @endif
                                                                        @if ($proveedor->email)
                                                                            - Email: {{ $proveedor->email }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                                <option value="otro">Otro proveedor...</option>
                                                            </select>
                                                        </div>
                                                        <div class="relative" x-show="item.idProveedor === 'otro'">
                                                            <input type="text"
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white mt-2"
                                                                placeholder="Especifique el nombre del proveedor"
                                                                x-model="item.proveedor_otro"
                                                                :name="`items[${index}][proveedor_otro]`">
                                                        </div>
                                                        <input type="hidden"
                                                            :name="`items[${index}][proveedor_sugerido]`"
                                                            x-model="item.idProveedor === 'otro' ? item.proveedor_otro : getProveedorNombre(item.idProveedor)">
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
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                rows="3" x-model="item.especificaciones_tecnicas" :name="`items[${index}][especificaciones_tecnicas]`"
                                                                :readonly="item.fromAlmacen"></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Justificación del Producto -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i
                                                                    class="fas fa-comment-dots text-green-600 text-xs"></i>
                                                            </div>
                                                            Justificación del Producto
                                                        </label>
                                                        <div class="relative">
                                                            <textarea
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                                rows="3" placeholder="Justifique por qué necesita este producto específico..."
                                                                x-model="item.justificacion_producto" :name="`items[${index}][justificacion_producto]`"></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Observaciones del Detalle -->
                                                    <div class="md:col-span-2 space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i
                                                                    class="fas fa-sticky-note text-green-600 text-xs"></i>
                                                            </div>
                                                            Observaciones del Detalle
                                                        </label>
                                                        <div class="relative">
                                                            <textarea
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                                rows="3" placeholder="Observaciones adicionales para este producto..." x-model="item.observaciones_detalle"
                                                                :name="`items[${index}][observaciones_detalle]`"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Estado vacío -->
                            <div x-show="form.items.length === 0 && !loadingAlmacen"
                                class="text-center py-12 bg-gradient-to-br from-gray-50 to-white rounded-xl border-2 border-dashed border-gray-300 transition-all duration-300 hover:border-green-400">
                                <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay artículos cargados</h3>
                                <p class="text-gray-500 max-w-md mx-auto">No se encontraron productos en esta solicitud
                                </p>
                            </div>

                            <!-- Resumen de Artículos - Versión Mejorada -->
                            <div class="mt-8 bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border border-gray-200 shadow-lg"
                                x-show="form.items.length > 0">

                                <!-- Header del Resumen -->
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                                            <i class="fas fa-chart-pie text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Resumen de la Solicitud</h3>
                                            <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                                <i class="fas fa-info-circle text-green-500"></i>
                                                <span x-text="form.items.length + ' artículo(s) en total'"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 bg-green-50 px-3 py-2 rounded-lg border border-green-200">
                                        <i class="fas fa-calendar-alt text-green-600"></i>
                                        <span class="text-sm font-medium text-green-800"
                                            x-text="new Date().toLocaleDateString('es-PE')"></span>
                                    </div>
                                </div>

                                <!-- Tarjetas de Métricas Principales -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                                    <!-- Total Artículos -->
                                    <div
                                        class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 mb-1">Total Artículos</p>
                                                <p class="text-2xl font-bold text-gray-900"
                                                    x-text="form.items.length"></p>
                                            </div>
                                            <div
                                                class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-boxes text-blue-500 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <i class="fas fa-cube"></i>
                                                <span x-text="totalUnidades + ' unidades totales'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div
                                        class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 mb-1">Subtotal</p>
                                                <p class="text-2xl font-bold text-gray-900"
                                                    x-text="getResumenMoneda() + subtotal.toLocaleString('es-PE', {minimumFractionDigits: 2})">
                                                </p>
                                            </div>
                                            <div
                                                class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-receipt text-green-500 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="text-xs text-gray-500">Antes de impuestos</div>
                                        </div>
                                        <input type="hidden" name="subtotal" x-model="subtotal">
                                    </div>

                                    <!-- IGV -->
                                    <div
                                        class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 mb-1">IGV (18%)</p>
                                                <p class="text-2xl font-bold text-gray-900"
                                                    x-text="getResumenMoneda() + igv.toLocaleString('es-PE', {minimumFractionDigits: 2})">
                                                </p>
                                            </div>
                                            <div
                                                class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-percentage text-orange-500 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="text-xs text-gray-500">Impuesto General a las Ventas</div>
                                        </div>
                                        <input type="hidden" name="iva" x-model="igv">
                                    </div>

                                    <!-- Total General -->
                                    <div
                                        class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-5 shadow-lg text-white hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-green-100 mb-1">Total General</p>
                                                <p class="text-2xl font-bold"
                                                    x-text="getResumenMoneda() + total.toLocaleString('es-PE', {minimumFractionDigits: 2})">
                                                </p>
                                            </div>
                                            <div
                                                class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                                <i class="fas fa-file-invoice-dollar text-white text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-green-400/30">
                                            <div class="text-xs text-green-100">Monto total a solicitar</div>
                                        </div>
                                        <input type="hidden" name="total" x-model="total">
                                        <input type="hidden" name="total_unidades" x-model="totalUnidades">
                                    </div>
                                </div>

                                <!-- Información Adicional -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Monedas Utilizadas -->
                                    <div x-show="hasMultipleCurrencies"
                                        class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-globe-americas text-purple-500"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Monedas Utilizadas</p>
                                                <p class="text-sm text-gray-900 font-semibold"
                                                    x-text="getMonedasUtilizadas()"></p>
                                            </div>
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
                                    <p class="text-sm text-gray-600">Detalles complementarios de la solicitud</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="space-y-4">
                                <!-- Observaciones -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-sticky-note text-purple-500 text-sm"></i>
                                        Observaciones
                                    </label>
                                    <div class="relative">
                                        <textarea
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                            rows="3" placeholder="Observaciones adicionales, condiciones especiales, etc." x-model="form.observaciones"
                                            name="observaciones"></textarea>
                                        <i class="fas fa-comment-dots absolute left-3 top-3 text-gray-400"></i>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1"
                                        x-show="form.observaciones_auto">
                                        <i class="fas fa-download"></i>
                                        Observaciones cargadas desde almacén
                                    </p>
                                </div>

                                <!-- Archivos Adjuntos -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-paperclip text-purple-500 text-sm"></i>
                                        Archivos Adjuntos
                                    </label>

                                    <!-- Área de upload mejorada -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-purple-400 transition-all duration-300 bg-gradient-to-br from-gray-50 to-white hover:from-purple-50 hover:to-indigo-50"
                                        @click="$refs.fileInput.click()">
                                        <input type="file" x-ref="fileInput" multiple class="hidden"
                                            name="archivos[]" @change="handleFileSelect">
                                        <i class="fas fa-cloud-upload-alt text-purple-400 text-4xl mb-3"></i>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Arrastre archivos o haga
                                            clic para seleccionar</h4>
                                        <p class="text-xs text-gray-500">Cotizaciones, imágenes, especificaciones -
                                            máximo 10MB por archivo</p>
                                    </div>

                                    <!-- Archivos existentes -->
                                    <div x-show="existingFiles.length > 0" class="space-y-2 mt-4">
                                        <div class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fas fa-folder-open text-purple-500 text-sm"></i>
                                            Archivos existentes
                                        </div>
                                        <template x-for="(file, index) in existingFiles" :key="index">
                                            <div
                                                class="flex items-center justify-between bg-white rounded-lg p-3 border shadow-sm transition-all duration-300 hover:shadow">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <i class="fas fa-file text-purple-500"></i>
                                                    <div class="flex-1 min-w-0">
                                                        <span class="text-sm text-gray-700 truncate block"
                                                            x-text="file.nombre_archivo"></span>
                                                        <span class="text-xs text-gray-500"
                                                            x-text="`${Math.round(file.tamaño / 1024)} KB`"></span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a :href="file.ruta_completa" target="_blank"
                                                        class="text-blue-500 hover:text-blue-700 transition-colors p-2 rounded-full hover:bg-blue-50"
                                                        title="Ver archivo">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Nuevos archivos seleccionados -->
                                    <div x-show="form.files.length > 0" class="space-y-2 mt-4">
                                        <div class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fas fa-plus-circle text-green-500 text-sm"></i>
                                            Nuevos archivos
                                        </div>
                                        <template x-for="(file, index) in form.files" :key="index">
                                            <div
                                                class="flex items-center justify-between bg-white rounded-lg p-3 border shadow-sm transition-all duration-300 hover:shadow">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <i class="fas fa-file text-purple-500"></i>
                                                    <div class="flex-1 min-w-0">
                                                        <span class="text-sm text-gray-700 truncate block"
                                                            x-text="file.name"></span>
                                                        <span class="text-xs text-gray-500"
                                                            x-text="formatFileSize(file.size)"></span>
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
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
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
                            </div>

                            <!-- Información General -->
                            <div class="space-y-3 mb-6">
                                <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Información General</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Código:</span>
                                        <span
                                            class="font-medium text-gray-900">{{ $solicitud->codigo_solicitud }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Solicitante:</span>
                                        <span class="font-medium text-gray-900"
                                            x-text="form.solicitante_compra"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Departamento:</span>
                                        <span class="font-medium text-gray-900"
                                            x-text="getDepartmentText(form.idTipoArea)"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Prioridad:</span>
                                        <span class="font-medium text-gray-900"
                                            x-text="getPriorityText(form.idPrioridad)"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Fecha Requerida:</span>
                                        <span class="font-medium text-gray-900"
                                            x-text="form.fecha_requerida ? formatPreviewDate(form.fecha_requerida) : 'No especificada'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen Financiero -->
                            <div class="space-y-3" x-show="form.items.length > 0">
                                <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Resumen Financiero</h3>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="text-center bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                        <i class="fas fa-boxes text-blue-500 text-lg mb-1"></i>
                                        <div class="text-xs text-gray-600">Total Artículos</div>
                                        <div class="text-lg font-bold text-gray-900" x-text="form.items.length"></div>
                                    </div>

                                    <div class="text-center bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                        <i class="fas fa-cubes text-blue-500 text-lg mb-1"></i>
                                        <div class="text-xs text-gray-600">Total Unidades</div>
                                        <div class="text-lg font-bold text-gray-900" x-text="totalUnidades"></div>
                                    </div>

                                    <div class="text-center bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                        <i class="fas fa-receipt text-green-500 text-lg mb-1"></i>
                                        <div class="text-xs text-gray-600">Subtotal</div>
                                        <div class="text-sm font-bold text-gray-900"
                                            x-text="getResumenMoneda() + subtotal.toLocaleString('es-PE', {minimumFractionDigits: 2})">
                                        </div>
                                    </div>

                                    <div class="text-center bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                        <i class="fas fa-percentage text-orange-500 text-lg mb-1"></i>
                                        <div class="text-xs text-gray-600">IGV (18%)</div>
                                        <div class="text-sm font-bold text-gray-900"
                                            x-text="getResumenMoneda() + igv.toLocaleString('es-PE', {minimumFractionDigits: 2})">
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg p-4 text-white text-center mt-3">
                                    <div class="text-xs text-green-100 mb-1">Total General</div>
                                    <div class="text-xl font-bold"
                                        x-text="getResumenMoneda() + total.toLocaleString('es-PE', {minimumFractionDigits: 2})">
                                    </div>
                                </div>

                                <div class="text-center" x-show="hasMultipleCurrencies">
                                    <span class="text-xs text-gray-600">
                                        Monedas: <span class="font-medium" x-text="getMonedasUtilizadas()"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Sin artículos -->
                            <div x-show="form.items.length === 0" class="text-center py-4">
                                <i class="fas fa-inbox text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">No hay artículos cargados</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información de ayuda - Versión Compacta Mejorada -->
                    <div
                        class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl border border-blue-200 p-6 shadow-md">
                        <!-- Header compacto -->
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-lightbulb text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Guía de Edición</h3>
                                <p class="text-sm text-blue-700">Consejos para modificar la solicitud</p>
                            </div>
                        </div>

                        <!-- Grid de consejos compacto -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <!-- Consejo 1 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-lock text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Campos bloqueados</p>
                                    <p class="text-xs text-gray-500 mt-1">Los campos "Desde Almacén" no se pueden
                                        modificar</p>
                                </div>
                            </div>

                            <!-- Consejo 2 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-edit text-yellow-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Campos editables</p>
                                    <p class="text-xs text-gray-500 mt-1">Puede ajustar cantidades, precios y
                                        proveedores</p>
                                </div>
                            </div>

                            <!-- Consejo 3 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-money-bill-wave text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Tipos de moneda</p>
                                    <p class="text-xs text-gray-500 mt-1">Haga clic en el símbolo para cambiar moneda
                                    </p>
                                </div>
                            </div>

                            <!-- Consejo 4 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-file text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Archivos existentes</p>
                                    <p class="text-xs text-gray-500 mt-1">Los archivos actuales se mantienen
                                        automáticamente</p>
                                </div>
                            </div>

                            <!-- Consejo 5 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-plus-circle text-indigo-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Nuevos archivos</p>
                                    <p class="text-xs text-gray-500 mt-1">Puede agregar archivos adicionales</p>
                                </div>
                            </div>

                            <!-- Consejo 6 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-calculator text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Cálculos automáticos</p>
                                    <p class="text-xs text-gray-500 mt-1">Los totales se actualizan en tiempo real</p>
                                </div>
                            </div>

                            <!-- Consejo 7 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-save text-cyan-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Guardar cambios</p>
                                    <p class="text-xs text-gray-500 mt-1">Verifique todos los datos antes de actualizar
                                    </p>
                                </div>
                            </div>

                            <!-- Consejo 8 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-undo text-pink-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Restablecer cambios</p>
                                    <p class="text-xs text-gray-500 mt-1">Puede volver a los valores originales</p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer compacto -->
                        <div class="mt-4 p-3 bg-blue-100 rounded-lg border border-blue-200">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                                <p class="text-xs text-blue-800">Contacte al administrador para ayuda adicional</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script>
        function editPurchaseRequest() {
            return {
                form: {
                    idSolicitudAlmacen: '{{ $solicitud->idSolicitudAlmacen }}',
                    solicitante_almacen: '{{ $solicitud->solicitante_almacen }}',
                    solicitante_compra: '{{ $solicitud->solicitante_compra }}',
                    idTipoArea: '{{ $solicitud->idTipoArea }}',
                    idPrioridad: '{{ $solicitud->idPrioridad }}',
                    fecha_requerida: '{{ $solicitud->fecha_requerida ? \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('Y-m-d') : '' }}',
                    idCentroCosto: '{{ $solicitud->idCentroCosto }}',
                    proyecto_asociado: '{{ $solicitud->proyecto_asociado }}',
                    justificacion: `{!! addslashes($solicitud->justificacion) !!}`,
                    observaciones: `{!! addslashes($solicitud->observaciones) !!}`,
                    items: [
                        @foreach ($solicitud->detalles as $detalle)
                            {
                                idSolicitudCompraDetalle: {{ $detalle->idSolicitudCompraDetalle }},
                                idSolicitudAlmacenDetalle: {{ $detalle->idSolicitudAlmacenDetalle ?? 'null' }},
                                idArticulo: {{ $detalle->idArticulo ?? 'null' }},
                                descripcion_producto: '{{ addslashes($detalle->descripcion_producto) }}',
                                categoria: '{{ addslashes($detalle->categoria ?? '') }}',
                                cantidad: {{ $detalle->cantidad }},
                                cantidad_original: {{ $detalle->cantidad }},
                                unidad: '{{ addslashes($detalle->unidad ?? 'unidad') }}',
                                precio_unitario_estimado: {{ $detalle->precio_unitario_estimado }},
                                total_producto: {{ $detalle->total_producto }},
                                codigo_producto: '{{ addslashes($detalle->codigo_producto ?? '') }}',
                                marca: '{{ addslashes($detalle->marca ?? '') }}',
                                especificaciones_tecnicas: '{{ addslashes($detalle->especificaciones_tecnicas ?? '') }}',
                                idProveedor: '{{ $detalle->proveedor_sugerido ? 'otro' : '' }}',
                                proveedor_otro: '{{ addslashes($detalle->proveedor_sugerido ?? '') }}',
                                justificacion_producto: '{{ addslashes($detalle->justificacion_producto ?? '') }}',
                                observaciones_detalle: '{{ addslashes($detalle->observaciones_detalle ?? '') }}',
                                idMonedas: {{ $detalle->idMonedas ?? 1 }},
                                fromAlmacen: {{ $detalle->idSolicitudAlmacenDetalle ? 'true' : 'false' }}
                            },
                        @endforeach
                    ],
                    files: []
                },

                existingFiles: [
                    @foreach ($solicitud->archivos as $archivo)
                        {
                            idSolicitudCompraArchivo: {{ $archivo->idSolicitudCompraArchivo }},
                            nombre_archivo: '{{ addslashes($archivo->nombre_archivo) }}',
                            ruta_archivo: '{{ addslashes($archivo->ruta_archivo) }}',
                            tipo_archivo: '{{ addslashes($archivo->tipo_archivo) }}',
                            tamaño: {{ $archivo->tamaño }},
                            ruta_completa: '{{ asset('storage/' . $archivo->ruta_archivo) }}'
                        },
                    @endforeach
                ],

                currentItemIndex: 0,
                proveedoresData: @json($proveedores),
                monedasData: @json($monedas->keyBy('idMonedas')),
                monedasList: @json($monedas),

                init() {
                    console.log('Edit Purchase Request initialized');
                    console.log('Items loaded:', this.form.items.length);
                    console.log('Existing files:', this.existingFiles.length);

                    // Inicializar los totales de los items
                    this.form.items.forEach((item, index) => {
                        this.updateItemTotal(index);
                    });
                },

                get totalUnidades() {
                    return this.form.items.reduce((sum, item) => {
                        return sum + (parseInt(item.cantidad) || 0);
                    }, 0);
                },

                get subtotal() {
                    return this.form.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.total_producto) || 0);
                    }, 0);
                },

                get igv() {
                    return this.subtotal * 0.18;
                },

                get total() {
                    return this.subtotal + this.igv;
                },

                get hasMultipleCurrencies() {
                    const currencies = new Set();
                    this.form.items.forEach(item => {
                        if (item.idMonedas) {
                            currencies.add(item.idMonedas);
                        }
                    });
                    return currencies.size > 1;
                },

                updateItemTotal(index) {
                    const item = this.form.items[index];
                    const quantity = parseFloat(item.cantidad) || 0;
                    const unitPrice = parseFloat(item.precio_unitario_estimado) || 0;
                    item.total_producto = (quantity * unitPrice).toFixed(2);

                    // Forzar actualización de Alpine.js
                    this.form.items = [...this.form.items];
                },

                cycleCurrency(index) {
                    const item = this.form.items[index];
                    const currentCurrencyId = item.idMonedas || 1;

                    const currentIndex = this.monedasList.findIndex(moneda => moneda.idMonedas == currentCurrencyId);
                    const nextIndex = (currentIndex + 1) % this.monedasList.length;
                    const nextCurrency = this.monedasList[nextIndex];

                    item.idMonedas = nextCurrency.idMonedas;
                    this.updateItemTotal(index);
                },

                getProveedorNombre(proveedorId) {
                    if (!proveedorId || proveedorId === 'otro') return '';
                    const proveedor = this.proveedoresData.find(p => p.idProveedor == proveedorId);
                    return proveedor ? proveedor.nombre : '';
                },

                getMonedaSimbolo(idMonedas) {
                    if (!idMonedas) return 'S/';
                    const moneda = this.monedasData[idMonedas];
                    return moneda ? moneda.simbolo : 'S/';
                },

                getMonedaNombre(idMonedas) {
                    if (!idMonedas) return 'Sol Peruano';
                    const moneda = this.monedasData[idMonedas];
                    return moneda ? moneda.nombre : 'Sol Peruano';
                },

                getResumenMoneda() {
                    if (this.form.items.length === 0) return 'S/';

                    const currencyCount = {};
                    this.form.items.forEach(item => {
                        if (item.idMonedas) {
                            currencyCount[item.idMonedas] = (currencyCount[item.idMonedas] || 0) + 1;
                        }
                    });

                    const mostCommonCurrency = Object.keys(currencyCount).reduce((a, b) =>
                        currencyCount[a] > currencyCount[b] ? a : b, 1
                    );

                    return this.getMonedaSimbolo(mostCommonCurrency);
                },

                getMonedasUtilizadas() {
                    const currencies = new Set();
                    this.form.items.forEach(item => {
                        if (item.idMonedas) {
                            currencies.add(this.getMonedaNombre(item.idMonedas));
                        }
                    });
                    return Array.from(currencies).join(', ');
                },

                getDepartmentText(idTipoArea) {
                    const departments = {
                        @foreach ($tipoAreas as $area)
                            '{{ $area->idTipoArea }}': '{{ $area->nombre }}',
                        @endforeach
                    };
                    return departments[idTipoArea] || 'No especificado';
                },

                getCostCenterText(idCentroCosto) {
                    const costCenters = {
                        @foreach ($centrosCosto as $centro)
                            '{{ $centro->idCentroCosto }}': '{{ $centro->codigo }} - {{ $centro->nombre }}',
                        @endforeach
                    };
                    return costCenters[idCentroCosto] || 'No especificado';
                },

                getPriorityText(idPrioridad) {
                    const priorities = {
                        @foreach ($prioridades as $prioridad)
                            '{{ $prioridad->idPrioridad }}': '{{ $prioridad->nombre }}',
                        @endforeach
                    };
                    return priorities[idPrioridad] || 'No especificada';
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
                    navigator.clipboard.writeText('{{ $solicitud->codigo_solicitud }}').then(() => {
                        // Usar Toastr si está disponible, sino alert normal
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Código copiado al portapapeles', '¡Éxito!', {
                                timeOut: 2000,
                                progressBar: true
                            });
                        } else {
                            alert('Código copiado al portapapeles');
                        }
                    });
                },

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    files.forEach(file => {
                        if (file.size > 10 * 1024 * 1024) {
                            if (typeof toastr !== 'undefined') {
                                toastr.error('El archivo ' + file.name + ' excede el tamaño máximo de 10MB',
                                    'Error', {
                                        timeOut: 5000,
                                        progressBar: true
                                    });
                            } else {
                                alert('El archivo ' + file.name + ' excede el tamaño máximo de 10MB');
                            }
                            return;
                        }
                        this.form.files.push(file);
                    });
                    event.target.value = '';
                },

                removeFile(index) {
                    this.form.files.splice(index, 1);
                },

                // Navegación del slider
                nextItem() {
                    if (this.currentItemIndex < this.form.items.length - 1) {
                        this.currentItemIndex++;
                    }
                },

                prevItem() {
                    if (this.currentItemIndex > 0) {
                        this.currentItemIndex--;
                    }
                },

                goToItem(index) {
                    this.currentItemIndex = index;
                },

                resetForm() {
                    // Crear modal personalizado
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
                    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 animate-scaleIn" id="modalContent">
            <!-- Header -->
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

            <!-- Body -->
            <div class="px-6 py-4">
                <div class="flex items-start gap-3 mb-4">
                    <i class="fas fa-eraser text-amber-500 text-xl mt-0.5"></i>
                    <div>
                        <p class="text-gray-700 font-medium mb-1">¿Está seguro de que desea restablecer todos los cambios?</p>
                        <p class="text-sm text-gray-500">Se perderán todas las modificaciones realizadas.</p>
                    </div>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-exclamation text-red-500"></i>
                        <span class="text-sm text-red-700 font-medium">Advertencia: Esta acción no se puede deshacer</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
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

                    // Agregar estilos de animación
                    const style = document.createElement('style');
                    style.textContent = `
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-scaleIn {
            animation: scaleIn 0.3s ease-out forwards;
        }
    `;
                    document.head.appendChild(style);

                    document.body.appendChild(modal);

                    // Event listeners
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

                        // Mostrar Toastr de confirmación antes de recargar
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Formulario restablecido correctamente', '¡Éxito!', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 3000,
                                extendedTimeOut: 1000,
                                showMethod: "fadeIn",
                                hideMethod: "fadeOut"
                            });

                            // Esperar a que se muestre el Toastr antes de recargar
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Fallback sin Toastr
                            setTimeout(() => {
                                window.location.reload();
                            }, 350);
                        }
                    };

                    cancelReset.onclick = () => {
                        // Mostrar Toastr de cancelación
                        if (typeof toastr !== 'undefined') {
                            toastr.info('Operación cancelada', 'Acción cancelada', {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }
                        closeModal();
                    };

                    // Cerrar al hacer clic fuera del modal
                    modal.onclick = (e) => {
                        if (e.target === modal) {
                            // Mostrar Toastr de cancelación
                            if (typeof toastr !== 'undefined') {
                                toastr.info('Operación cancelada', 'Acción cancelada', {
                                    timeOut: 3000,
                                    progressBar: true
                                });
                            }
                            closeModal();
                        }
                    };

                    // Cerrar con tecla Escape
                    const handleEscape = (e) => {
                        if (e.key === 'Escape') {
                            // Mostrar Toastr de cancelación
                            if (typeof toastr !== 'undefined') {
                                toastr.info('Operación cancelada', 'Acción cancelada', {
                                    timeOut: 3000,
                                    progressBar: true
                                });
                            }
                            closeModal();
                            document.removeEventListener('keydown', handleEscape);
                        }
                    };
                    document.addEventListener('keydown', handleEscape);
                },

                submitForm() {
                    // Validar campos obligatorios
                    if (!this.form.idTipoArea || !this.form.idPrioridad || !this.form.fecha_requerida || !this.form
                        .justificacion) {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Por favor complete todos los campos obligatorios (*)', 'Campos incompletos', {
                                closeButton: true,
                                timeOut: 5000,
                                progressBar: true,
                                showMethod: "fadeIn",
                                hideMethod: "fadeOut"
                            });
                        } else {
                            alert('Por favor complete todos los campos obligatorios (*)');
                        }
                        return;
                    }

                    // Validar que haya items
                    if (this.form.items.length === 0) {
                        if (typeof toastr !== 'undefined') {
                            toastr.warning('Debe haber al menos un artículo en la solicitud', 'Sin artículos', {
                                closeButton: true,
                                timeOut: 5000,
                                progressBar: true,
                                showMethod: "fadeIn",
                                hideMethod: "fadeOut"
                            });
                        } else {
                            alert('Debe haber al menos un artículo en la solicitud');
                        }
                        return;
                    }

                    // Validar cada item
                    for (let i = 0; i < this.form.items.length; i++) {
                        const item = this.form.items[i];
                        if (!item.descripcion_producto || !item.cantidad || !item.precio_unitario_estimado || !item
                            .idMonedas) {
                            const errorMessage = `Por favor complete todos los campos obligatorios del artículo ${i + 1}`;

                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage, 'Error en artículo ' + (i + 1), {
                                    closeButton: true,
                                    timeOut: 6000,
                                    progressBar: true,
                                    showMethod: "fadeIn",
                                    hideMethod: "fadeOut"
                                });
                            } else {
                                alert(errorMessage);
                            }
                            return;
                        }

                        if (item.cantidad <= 0) {
                            const errorMessage = `La cantidad del artículo ${i + 1} debe ser mayor a 0`;

                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage, 'Error en artículo ' + (i + 1), {
                                    closeButton: true,
                                    timeOut: 6000,
                                    progressBar: true,
                                    showMethod: "fadeIn",
                                    hideMethod: "fadeOut"
                                });
                            } else {
                                alert(errorMessage);
                            }
                            return;
                        }

                        if (item.precio_unitario_estimado < 0) {
                            const errorMessage = `El precio unitario del artículo ${i + 1} no puede ser negativo`;

                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage, 'Error en artículo ' + (i + 1), {
                                    closeButton: true,
                                    timeOut: 6000,
                                    progressBar: true,
                                    showMethod: "fadeIn",
                                    hideMethod: "fadeOut"
                                });
                            } else {
                                alert(errorMessage);
                            }
                            return;
                        }
                    }

                    // Mostrar confirmación con SweetAlert2 o modal nativo
                    if (typeof Swal !== 'undefined') {
                        // Usar SweetAlert2 si está disponible
                        Swal.fire({
                            title: '¿Actualizar solicitud?',
                            html: `
            <div class="text-center">
                <div class="mb-2">
                    <br>
                    <p class="text-sm text-gray-600">Los cambios serán guardados permanentemente</p>
                </div>
                
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-4">
                    <div class="flex items-center justify-center gap-2 text-sm text-amber-700">
                        <i class="fas fa-exclamation-circle text-amber-500"></i>
                        <span>Esta acción no se puede deshacer</span>
                    </div>
                </div>
            </div>
        `,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3b82f6', // AZUL
                            cancelButtonColor: '#ef4444', // ROJO
                            confirmButtonText: '<i class="fas fa-save mr-2"></i>Sí, actualizar',
                            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                            background: '#ffffff',
                            backdrop: 'rgba(0,0,0,0.4)',
                            customClass: {
                                container: 'text-center',
                                popup: 'rounded-xl shadow-2xl',
                                confirmButton: 'px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white transition-all duration-200',
                                cancelButton: 'px-6 py-2.5 rounded-lg font-medium flex items-center justify-center bg-red-500 hover:bg-red-600 text-white transition-all duration-200',
                                htmlContainer: 'text-center',
                                actions: 'gap-4 !mt-8' // ESPACIO ENTRE BOTONES
                            },
                            buttonsStyling: false,
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown animate__faster'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp animate__faster'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Mostrar notificación de envío
                                if (typeof toastr !== 'undefined') {
                                    toastr.success('Actualizando solicitud de compra...', 'Procesando', {
                                        timeOut: 2000,
                                        progressBar: true,
                                        showMethod: "fadeIn",
                                        hideMethod: "fadeOut"
                                    });
                                }

                                document.getElementById('purchaseRequestForm').submit();
                            }
                        });
                    } else {
                        // Fallback con confirm nativo
                        if (confirm('¿Está seguro de que desea actualizar la solicitud de compra?')) {
                            // Mostrar notificación de envío
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Actualizando solicitud de compra...', 'Procesando', {
                                    timeOut: 2000,
                                    progressBar: true,
                                    showMethod: "fadeIn",
                                    hideMethod: "fadeOut"
                                });
                            }

                            document.getElementById('purchaseRequestForm').submit();
                        }
                    }
                }
            }
        }
    </script>
</x-layout.default>
