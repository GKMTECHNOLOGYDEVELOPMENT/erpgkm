<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <div x-data="createPurchaseRequest()" class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudcompra.index') }}" class="text-primary hover:underline">
                        Solicitudes Compra
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Crear Solicitud Compra</span>
                </li>
            </ul>
        </div>
        <!-- Header -->
        <div class="panel rounded-xl shadow-md border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Información Principal -->
                <div class="flex items-center space-x-4 flex-1">
                    <!-- Icono -->
                    <div
                        class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md">
                        <i class="fas fa-file-invoice-dollar text-white text-lg"></i>
                    </div>

                    <!-- Texto -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-xl font-bold text-gray-900">Nueva Solicitud de Compra</h1>
                            <span
                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                Nuevo
                            </span>
                        </div>
                        <p class="text-base text-gray-600">Complete los artículos y detalles de la solicitud</p>
                    </div>
                </div>

                <!-- Botones de Acción - Al Extremo Derecho -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('solicitudcompra.index') }}"
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

                    <button type="button"
                        class="inline-flex items-center px-4 py-3 bg-primary text-white rounded-lg text-base font-semibold hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg min-w-[140px] justify-center"
                        @click="submitForm()">
                        <i class="fas fa-paper-plane mr-2 text-sm"></i>
                        Crear Solicitud
                    </button>
                </div>
            </div>
        </div>

        <form id="purchaseRequestForm" action="{{ route('solicitudcompra.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <!-- Campos hidden importantes -->
            <input type="hidden" name="codigo_solicitud" x-model="requestCode">
            <input type="hidden" name="idSolicitudAlmacen" x-model="form.idSolicitudAlmacen">
            <input type="hidden" name="solicitante_compra" x-model="form.solicitante_compra">
            <input type="hidden" name="solicitante_almacen" x-model="form.solicitante_almacen">

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Form Section -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Información General -->
                    <div
                        class="panel rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-clipboard-list text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-800">Información General</h2>
                                        <p class="text-sm text-gray-600">Datos básicos de la solicitud</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border shadow-sm">
                                    <i class="fas fa-hashtag text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">Código:</span>
                                    <span class="text-sm font-bold text-blue-600" x-text="requestCode"></span>
                                    <button type="button" class="text-gray-400 hover:text-blue-500 transition-colors"
                                        @click="copyCode()" title="Copiar código">
                                        <i class="fas fa-copy text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Solicitud de Almacén -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-warehouse text-blue-500 text-sm"></i>
                                        Solicitud de Almacén *
                                    </label>
                                    <div class="relative">
                                        <select
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            x-model="form.idSolicitudAlmacen" @change="loadAlmacenItems()"
                                            name="idSolicitudAlmacen" required>
                                            <option value="">Seleccione una solicitud de almacén</option>
                                            @if ($solicitudesAlmacen->count() > 0)
                                                @foreach ($solicitudesAlmacen as $solicitud)
                                                    <option value="{{ $solicitud->idSolicitudAlmacen }}">
                                                        {{ $solicitud->codigo_solicitud }} - {{ $solicitud->titulo }}
                                                        ({{ $solicitud->detalles->count() }} productos)
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No hay solicitudes de almacén
                                                    disponibles
                                                </option>
                                            @endif
                                        </select>

                                    </div>
                                    @if ($solicitudesAlmacen->count() === 0)
                                        <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            No hay solicitudes de almacén aprobadas disponibles para crear compras.
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                            <i class="fas fa-info-circle"></i>
                                            Se muestran solo solicitudes de almacén que no tienen compra asociada.
                                        </p>
                                    @endif
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
                                            x-model="form.solicitante_almacen" readonly>

                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fas fa-sync-alt"></i>
                                        Cargado automáticamente desde la solicitud de almacén
                                    </p>
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
                                            x-model="form.solicitante_compra" readonly>

                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fas fa-user-circle"></i>
                                        Usuario autenticado del sistema
                                    </p>
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
                                                <option value="{{ $area->idTipoArea }}">{{ $area->nombre }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1"
                                        x-show="form.departamento_auto">
                                        <i class="fas fa-download"></i>
                                        <span x-text="form.departamento_auto"></span> (desde almacén)
                                    </p>
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
                                                <option value="{{ $prioridad->idPrioridad }}">
                                                    {{ $prioridad->nombre }} (Nivel {{ $prioridad->nivel }})
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1"
                                        x-show="form.prioridad_auto">
                                        <i class="fas fa-download"></i>
                                        <span x-text="form.prioridad_auto"></span> (desde almacén)
                                    </p>
                                </div>
                                <!-- Fecha Requerida -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-blue-500 text-sm"></i>
                                        Fecha Requerida *
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                            class="w-full pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all flatpickr-date bg-white cursor-pointer"
                                            x-model="form.fecha_requerida" x-ref="fechaRequeridaInput"
                                            name="fecha_requerida" placeholder="Seleccione una fecha" readonly required>

                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1"
                                        x-show="form.fecha_requerida_auto">
                                        <i class="fas fa-download"></i>
                                        <span x-text="form.fecha_requerida_auto"></span> (desde almacén)
                                    </p>
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
                                                <option value="{{ $centro->idCentroCosto }}">
                                                    {{ $centro->codigo }} - {{ $centro->nombre }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1"
                                        x-show="form.centro_costo_auto">
                                        <i class="fas fa-download"></i>
                                        <span x-text="form.centro_costo_auto"></span> (desde almacén)
                                    </p>
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
                                            rows="5" placeholder="Explique por qué es necesaria esta compra"
                                            x-model="form.justificacion" name="justificacion" required></textarea>

                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1"
                                        x-show="form.justificacion_auto">
                                        <i class="fas fa-download"></i>
                                        Justificación cargada desde almacén
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Artículos de la Solicitud -->
                    <div
                        class="panel rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="px-5 py-4 border-b border-gray-100 bg-green-50">
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

                        <!-- Loading overlay -->
                        <div x-show="loadingAlmacen"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
                            <div
                                class="panel rounded-2xl shadow-2xl p-8 mx-4 max-w-md w-full transform transition-all duration-300 scale-95 hover:scale-100">
                                <!-- Icono de carga animado -->
                                <div class="flex justify-center mb-6">
                                    <div class="relative">
                                        <!-- Círculo exterior giratorio -->
                                        <div class="w-16 h-16 border-4 border-blue-200 rounded-full"></div>
                                        <!-- Círculo interior giratorio -->
                                        <div
                                            class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full absolute top-0 left-0 animate-spin">
                                        </div>
                                        <!-- Icono de caja en el centro -->
                                        <div
                                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <i class="fas fa-boxes text-blue-500 text-xl"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Texto de carga -->
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Cargando productos</h3>
                                    <p class="text-gray-600">Estamos obteniendo los artículos de la solicitud de
                                        almacén</p>
                                </div>

                                <!-- Barra de progreso -->
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                    <div
                                        class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full animate-pulse">
                                    </div>
                                </div>

                                <!-- Indicadores de estado -->
                                <div class="flex justify-between text-xs text-gray-500 mb-2">
                                    <span>Conectando...</span>
                                    <span>Procesando...</span>
                                    <span>Finalizando...</span>
                                </div>

                                <!-- Contador de productos -->
                                <div class="text-center">
                                    <div
                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-spinner animate-spin mr-2"></i>
                                        <span x-text="`Cargando Productos...`"></span>
                                    </div>
                                </div>

                                <!-- Mensaje de paciencia -->
                                <div class="text-center mt-4">
                                    <p class="text-xs text-gray-500">Esto puede tomar unos segundos...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Artículos con Slider -->
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
                                        <span class="text-sm font-bold text-gray-700" x-text="form.items.length"></span>
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
                                        <button type="button" class="w-2 h-2 rounded-full transition-all duration-300"
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
                                                                    x-text="item.codigo_producto || `${requestCode}-${String(index + 1).padStart(2, '0')}`"></span>
                                                                <span
                                                                    class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-md border border-green-200 flex items-center gap-1.5"
                                                                    x-show="item.fromAlmacen">
                                                                    <i class="fas fa-check-circle text-xs"></i>
                                                                    Desde Almacén
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
                                                                <i class="fas fa-align-left text-green-600 text-xs"></i>
                                                            </div>
                                                            Descripción del Artículo *
                                                        </label>
                                                        <div class="relative">
                                                            <input type="text"
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:bg-white transition-colors"
                                                                x-model="item.descripcion_producto"
                                                                :name="`items[${index}][descripcion_producto]`" required
                                                                readonly>
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
                                                                :name="`items[${index}][categoria]`" readonly>
                                                        </div>
                                                    </div>

                                                    <!-- Cantidad Aprobada -->
                                                    <div class="space-y-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-800 flex items-center gap-2 mb-2">
                                                            <div
                                                                class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                                                                <i class="fas fa-cubes text-green-600 text-xs"></i>
                                                            </div>
                                                            Cantidad Aprobada *
                                                        </label>
                                                        <div class="relative">
                                                            <input type="number"
                                                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white"
                                                                min="1" x-model="item.cantidad_aprobada"
                                                                :name="`items[${index}][cantidad]`"
                                                                @change="updateItemTotal(index)" required>
                                                        </div>
                                                        <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-1">
                                                            <i class="fas fa-info-circle text-gray-400"></i>
                                                            Cantidad original: <span x-text="item.cantidad"
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
                                                                x-model="item.unidad" :name="`items[${index}][unidad]`"
                                                                readonly>
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
                                                        <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-1">
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
                                                                <i class="fas fa-calculator text-green-600 text-xs"></i>
                                                            </div>
                                                            Total
                                                        </label>
                                                        <div
                                                            class="px-4 py-3 bg-white border border-gray-300 rounded-lg flex items-center shadow-sm">
                                                            <span class="text-base font-bold text-emerald-700"
                                                                x-text="getMonedaSimbolo(item.idMonedas) + (item.total_producto || '0.00')"></span>
                                                        </div>
                                                        <input type="hidden" :name="`items[${index}][total_producto]`"
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
                                                                :name="`items[${index}][codigo_producto]`" readonly>
                                                        </div>
                                                        <input type="hidden"
                                                            :name="`items[${index}][idSolicitudAlmacenDetalle]`"
                                                            x-model="item.idSolicitudAlmacenDetalle">
                                                        <input type="hidden" :name="`items[${index}][idArticulo]`"
                                                            x-model="item.idArticulo">
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
                                                                readonly>
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
                                                                rows="3" x-model="item.especificaciones_tecnicas"
                                                                :name="`items[${index}][especificaciones_tecnicas]`"
                                                                readonly></textarea>
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
                                                                rows="3"
                                                                placeholder="Justifique por qué necesita este producto específico..."
                                                                x-model="item.justificacion_producto"
                                                                :name="`items[${index}][justificacion_producto]`"></textarea>
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
                                                                rows="3"
                                                                placeholder="Observaciones adicionales para este producto..."
                                                                x-model="item.observaciones_detalle"
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
                                class="text-center py-12 rounded-xl border-2 border-dashed border-gray-300 transition-all duration-300 hover:border-green-400">
                                <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay artículos cargados</h3>
                                <p class="text-gray-500 max-w-md mx-auto">Seleccione una solicitud de almacén aprobada
                                    para cargar los productos</p>
                            </div>

                            <!-- Loading state -->
                            <div x-show="loadingAlmacen"
                                class="text-center py-12 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200">
                                <div
                                    class="w-10 h-10 border-2 border-green-500 border-t-transparent rounded-full animate-spin mx-auto mb-4">
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Cargando productos...</h3>
                                <p class="text-gray-500">Obteniendo los artículos de la solicitud de almacén</p>
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
                                                <p class="text-2xl font-bold text-gray-900" x-text="form.items.length">
                                                </p>
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
                                        class="panel rounded-xl p-4 border border-gray-200 shadow-sm">
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
                        class="panel rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
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
                                            rows="3"
                                            placeholder="Observaciones adicionales, condiciones especiales, etc."
                                            x-model="form.observaciones" name="observaciones"></textarea>
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
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-purple-400 transition-all duration-300"
                                        @click="$refs.fileInput.click()">
                                        <input type="file" x-ref="fileInput" multiple class="hidden" name="archivos[]"
                                            @change="handleFileSelect">
                                        <i class="fas fa-cloud-upload-alt text-purple-400 text-4xl mb-3"></i>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Arrastre archivos o haga
                                            clic para seleccionar</h4>
                                        <p class="text-xs text-gray-500">Cotizaciones, imágenes, especificaciones -
                                            máximo 10MB por archivo</p>
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
                    <div class="panel rounded-xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-primary-light">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
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
                                        class="inline-flex items-center px-3 py-1 bg-primary text-white rounded-full text-sm font-bold">
                                        Nueva Solicitud
                                    </span>
                                </div>

                                <!-- Información General -->
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-sm font-bold text-gray-800">Información General</h3>
                                        <!-- Indicador de carga minimalista -->
                                        <div x-show="updatingPreview" class="flex items-center space-x-1">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-ping"></div>
                                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-ping"
                                                style="animation-delay: 0.1s"></div>
                                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-ping"
                                                style="animation-delay: 0.2s"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 text-sm relative">
                                        <!-- Efecto de pulso sutil en el fondo -->
                                        <div x-show="updatingPreview"
                                            class="absolute inset-0 bg-blue-50 rounded-lg opacity-50 animate-pulse">
                                        </div>

                                        <span class="text-gray-600">Código:</span>
                                        <span class="font-medium text-primary" x-text="requestCode"></span>

                                        <span class="text-gray-600">Solicitud Almacén:</span>
                                        <span
                                            x-text="getSolicitudAlmacenText(form.idSolicitudAlmacen) || 'No seleccionada'"
                                            :class="{ 'text-blue-600 font-medium': updatingPreview }"></span>

                                        <span class="text-gray-600">Solicitante Almacén:</span>
                                        <span x-text="form.solicitante_almacen || 'No especificado'"
                                            :class="{ 'text-blue-600 font-medium': updatingPreview }"></span>

                                        <span class="text-gray-600">Solicitante Compra:</span>
                                        <span x-text="form.solicitante_compra || 'No especificado'"></span>

                                        <span class="text-gray-600">Departamento:</span>
                                        <span x-text="getDepartmentText(form.idTipoArea) || 'No especificado'"
                                            :class="{ 'text-blue-600 font-medium': updatingPreview }"></span>

                                        <span class="text-gray-600">Prioridad:</span>
                                        <span class="font-medium" :class="{
                                                'text-red-500': form.idPrioridad == 1,
                                                'text-orange-500': form.idPrioridad == 2,
                                                'text-yellow-500': form.idPrioridad == 3,
                                                'text-green-500': form.idPrioridad == 4,
                                                'text-gray-500': !form.idPrioridad,
                                                'text-blue-600': updatingPreview
                                            }" x-text="getPriorityText(form.idPrioridad) || 'No especificada'"></span>

                                        <span class="text-gray-600">Fecha Requerida:</span>
                                        <span
                                            x-text="form.fecha_requerida ? formatPreviewDate(form.fecha_requerida) : 'No especificada'"
                                            :class="{ 'text-blue-600 font-medium': updatingPreview }"></span>

                                        <span class="text-gray-600" x-show="form.idCentroCosto">Centro de
                                            Costo:</span>
                                        <span x-show="form.idCentroCosto" x-text="getCostCenterText(form.idCentroCosto)"
                                            :class="{ 'text-blue-600 font-medium': updatingPreview }"></span>

                                        <span class="text-gray-600" x-show="form.proyecto_asociado">Proyecto:</span>
                                        <span x-show="form.proyecto_asociado" x-text="form.proyecto_asociado"
                                            :class="{ 'text-blue-600 font-medium': updatingPreview }"></span>
                                    </div>
                                </div>

                                <!-- Artículos Solicitados -->
                                <div class="space-y-4" x-show="form.items.length > 0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-bold text-gray-800 flex items-center space-x-2">
                                            <span>Artículos Solicitados</span>
                                        </h3>
                                        <span
                                            class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium"
                                            x-text="form.items.length + ' artículo(s)'"></span>
                                    </div>

                                    <div class="space-y-3 max-h-96 overflow-y-auto">
                                        <template x-for="(item, index) in form.items" :key="index">
                                            <div
                                                class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:border-blue-200">
                                                <!-- Header con número y total -->
                                                <div class="flex justify-between items-start mb-3">
                                                    <div class="flex items-start space-x-3 flex-1">
                                                        <!-- Número del artículo -->
                                                        <div
                                                            class="flex-shrink-0 w-6 h-6 bg-success rounded-full flex items-center justify-center">
                                                            <span class="text-xs font-bold text-white"
                                                                x-text="index + 1"></span>
                                                        </div>

                                                        <!-- Información principal -->
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="text-sm font-semibold text-gray-800 leading-tight mb-1"
                                                                x-text="item.descripcion_producto || 'Sin descripción'">
                                                            </h4>
                                                            <div
                                                                class="flex items-center space-x-2 text-xs text-gray-500">
                                                                <span
                                                                    class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-200"
                                                                    x-text="item.codigo_producto || `${requestCode}-${String(index + 1).padStart(2, '0')}`"></span>
                                                                <span x-show="item.fromAlmacen"
                                                                    class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full border border-green-200 flex items-center space-x-1">
                                                                    <i class="fas fa-check text-xs"></i>
                                                                    <span>Desde Almacén</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Total del artículo -->
                                                    <div class="text-right ml-3">
                                                        <span class="text-sm font-bold text-blue-600 whitespace-nowrap"
                                                            x-text="getMonedaSimbolo(item.idMonedas) + (parseFloat(item.total_producto) || 0).toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                                    </div>
                                                </div>

                                                <!-- Detalles del artículo -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                                    <!-- Columna izquierda -->
                                                    <div class="space-y-2">
                                                        <!-- Cantidad y unidad -->
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-cube text-primary text-xs w-4"></i>
                                                            <span class="text-gray-700 font-medium"
                                                                x-text="item.cantidad_aprobada + ' ' + (item.unidad || 'unidad')"></span>
                                                        </div>

                                                        <!-- Precio unitario -->
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-tag text-primary text-xs w-4"></i>
                                                            <span class="text-gray-700"
                                                                x-text="getMonedaSimbolo(item.idMonedas) + (parseFloat(item.precio_unitario_estimado) || 0).toLocaleString('es-PE', {minimumFractionDigits: 2}) + ' c/u'"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Columna derecha -->
                                                    <div class="space-y-2">
                                                        <!-- Categoría -->
                                                        <div x-show="item.categoria"
                                                            class="flex items-center space-x-2">
                                                            <i class="fas fa-tags text-primary text-xs w-4"></i>
                                                            <span
                                                                class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs border border-blue-200"
                                                                x-text="item.categoria"></span>
                                                        </div>

                                                        <!-- Moneda -->
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-globe text-primary text-xs w-4"></i>
                                                            <span
                                                                class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs border border-blue-200"
                                                                x-text="getMonedaNombre(item.idMonedas)"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Información adicional -->
                                                <div class="mt-3 space-y-2">
                                                    <!-- Especificaciones técnicas -->
                                                    <div x-show="item.especificaciones_tecnicas"
                                                        class="text-xs text-gray-600 bg-blue-50 rounded p-2 border border-blue-200">
                                                        <div class="flex items-start space-x-2">
                                                            <i
                                                                class="fas fa-toolbox text-primary mt-0.5 flex-shrink-0"></i>
                                                            <span class="leading-relaxed"
                                                                x-text="item.especificaciones_tecnicas"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Proveedor -->
                                                    <div x-show="item.idProveedor || item.proveedor_otro"
                                                        class="flex items-center space-x-2 text-xs">
                                                        <i class="fas fa-truck text-primary text-xs w-4"></i>
                                                        <span class="text-gray-600 font-medium">Proveedor:</span>
                                                        <span class="text-gray-700"
                                                            x-text="item.idProveedor === 'otro' ? item.proveedor_otro : getProveedorNombre(item.idProveedor)"></span>
                                                    </div>

                                                    <!-- Marca -->
                                                    <div x-show="item.marca"
                                                        class="flex items-center space-x-2 text-xs">
                                                        <i class="fas fa-copyright text-primary text-xs w-4"></i>
                                                        <span class="text-gray-600 font-medium">Marca:</span>
                                                        <span class="text-gray-700" x-text="item.marca"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Resumen Financiero -->
                                <div class="space-y-3" x-show="form.items.length > 0">
                                    <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Resumen Financiero</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span
                                                x-text="getResumenMoneda() + subtotal.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>IGV (18%):</span>
                                            <span
                                                x-text="getResumenMoneda() + igv.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                        </div>
                                        <div class="flex justify-between border-t pt-2 font-bold">
                                            <span>Total:</span>
                                            <span class="text-success"
                                                x-text="getResumenMoneda() + total.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                        </div>
                                        <div class="flex justify-between text-xs" x-show="hasMultipleCurrencies">
                                            <span>Monedas Utilizadas:</span>
                                            <span x-text="getMonedasUtilizadas()"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Justificación -->
                                <div class="space-y-3">
                                    <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Justificación</h3>
                                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3"
                                        x-text="form.justificacion || 'Sin justificación'"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de ayuda - Versión Compacta Mejorada -->
                    <div class="panel rounded-xl border border-blue-200 p-6 shadow-md">
                        <!-- Header compacto -->
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-lightbulb text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Consejos para una Solicitud Exitosa</h3>
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
                                    <i class="fas fa-sync-alt text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Carga automática desde almacén</p>
                                </div>
                            </div>

                            <!-- Consejo 2 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-tag text-yellow-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Verifique precios unitarios</p>
                                </div>
                            </div>

                            <!-- Consejo 3 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check-circle text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Revise cantidades aprobadas</p>
                                </div>
                            </div>

                            <!-- Consejo 4 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-truck text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Agregue proveedores sugeridos</p>
                                </div>
                            </div>

                            <!-- Consejo 5 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-calculator text-indigo-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Confirme totales correctos</p>
                                </div>
                            </div>

                            <!-- Consejo 6 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-percentage text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">IGV aplicado del 18%</p>
                                </div>
                            </div>

                            <!-- Consejo 7 -->
                            <div
                                class="flex items-start gap-3 p-3 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-exchange-alt text-cyan-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Cambie tipos de moneda</p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer compacto -->
                        <div class="mt-4 p-3 bg-blue-100 rounded-lg border border-blue-200">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                                <p class="text-xs text-blue-800">Contacte a compras para ayuda adicional</p>
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
        function initializeFlatpickr() {
            const fechaInput = document.querySelector('.flatpickr-date');

            if (fechaInput && typeof flatpickr !== 'undefined') {
                flatpickr('.flatpickr-date', {
                    locale: 'es',
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    defaultDate: 'today',
                    position: 'auto',
                    static: true,
                    monthSelectorType: 'static',
                    prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
                    nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
                    onReady: function (selectedDates, dateStr, instance) {
                        instance.element.value = dateStr;
                    },
                    onChange: function (selectedDates, dateStr, instance) {
                        // SOLUCIÓN: Usar Alpine directamente en lugar de acceder a __x
                        const alpineElement = document.querySelector('[x-data]');
                        if (alpineElement && alpineElement.__x) {
                            const alpine = alpineElement.__x;
                            alpine.$data.form.fecha_requerida = dateStr;
                        }
                    }
                });
            }
        }

        // Inicializar cuando Alpine.js esté listo
        document.addEventListener('alpine:init', function () {
            initializeFlatpickr();
        });


        function createPurchaseRequest() {
            return {

                showTableView: true, // true para vista tabla, false para vista detallada
                expandedItem: null, // índice del item expandido
                allExpanded: false,
                loadingAlmacen: false,
                updatingPreview: false, // <-- AGREGAR ESTA LÍNEA
                previewTimeout: null, // <-- AGREGAR ESTA LÍNEA
                currentItemIndex: 0,
                form: {
                    idSolicitudAlmacen: '',
                    solicitante_almacen: '',
                    solicitante_compra: '{{ $solicitanteCompra }}',
                    idTipoArea: '',
                    idPrioridad: '',
                    fecha_requerida: '',
                    idCentroCosto: '',
                    proyecto_asociado: '',
                    justificacion: '',
                    observaciones: '',
                    items: [],
                    files: [],
                    // Campos para mostrar datos automáticos
                    departamento_auto: '',
                    prioridad_auto: '',
                    fecha_requerida_auto: '',
                    centro_costo_auto: '',
                    justificacion_auto: '',
                    observaciones_auto: ''
                },

                loadingAlmacen: false,
                solicitudesAlmacenData: @json($solicitudesAlmacen->keyBy('idSolicitudAlmacen')),
                proveedoresData: @json($proveedores),
                monedasData: @json($monedas->keyBy('idMonedas')),
                monedasList: @json($monedas),

                get requestCode() {
                    const now = new Date();
                    const year = now.getFullYear().toString().slice(-2);
                    const month = (now.getMonth() + 1).toString().padStart(2, '0');
                    const day = now.getDate().toString().padStart(2, '0');
                    const random = Math.floor(Math.random() * 999).toString().padStart(3, '0');
                    return `SC-${year}${month}${day}-${random}`;
                },

                init() {
                    const today = new Date().toISOString().split('T')[0];
                    this.form.fecha_requerida = today;

                    // Inicializar Flatpickr después de que Alpine esté listo
                    this.$nextTick(() => {
                        if (typeof flatpickr !== 'undefined') {
                            initializeFlatpickr();
                        }
                    });

                    // Agregar navegación con teclado
                    document.addEventListener('keydown', (e) => {
                        if (this.form.items.length <= 1) return;

                        if (e.key === 'ArrowLeft') {
                            e.preventDefault();
                            this.currentItemIndex = (this.currentItemIndex - 1 + this.form.items.length) % this.form
                                .items.length;
                        } else if (e.key === 'ArrowRight') {
                            e.preventDefault();
                            this.currentItemIndex = (this.currentItemIndex + 1) % this.form.items.length;
                        }
                    });
                },

                get totalUnidades() {
                    return this.form.items.reduce((sum, item) => {
                        return sum + (parseInt(item.cantidad_aprobada) || 0);
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

                async loadAlmacenItems() {
                    console.log('Cargando items para solicitud almacén:', this.form.idSolicitudAlmacen);

                    if (!this.form.idSolicitudAlmacen) {
                        this.resetAlmacenData();
                        return;
                    }

                    this.loadingAlmacen = true;
                    this.resetAlmacenData();

                    try {
                        const response = await fetch(
                            `/solicitudcompra/solicitud-almacen/${this.form.idSolicitudAlmacen}/detalles`);
                        const result = await response.json();

                        console.log('Respuesta del servidor:', result);

                        if (result.success && result.detalles && result.detalles.length > 0) {
                            if (result.solicitud) {
                                this.autocompleteFormData(result.solicitud);
                            }

                            this.form.items = result.detalles.map(detalle => ({
                                idSolicitudAlmacenDetalle: detalle.idSolicitudAlmacenDetalle,
                                idArticulo: detalle.idArticulo,
                                descripcion_producto: detalle.descripcion_producto || '',
                                categoria: detalle.categoria || '',
                                cantidad: detalle.cantidad || 0,
                                cantidad_aprobada: detalle.cantidad_aprobada || detalle.cantidad || 1,
                                unidad: detalle.unidad || 'unidad',
                                precio_unitario_estimado: detalle.precio_unitario_estimado || 0,
                                total_producto: detalle.total_producto || 0,
                                codigo_producto: detalle.codigo_producto || '',
                                marca: detalle.marca || '',
                                especificaciones_tecnicas: detalle.especificaciones_tecnicas || '',
                                idProveedor: detalle.proveedor_sugerido || '',
                                proveedor_otro: '',
                                justificacion_producto: detalle.justificacion_producto || '',
                                observaciones_detalle: detalle.observaciones_detalle || '',
                                idMonedas: detalle.idMonedas || 1, // Moneda por defecto: Sol Peruano
                                fromAlmacen: true
                            }));

                            this.form.items.forEach((item, index) => {
                                this.updateItemTotal(index);
                            });

                            console.log('Items cargados exitosamente:', this.form.items.length);

                        } else {
                            alert('No se encontraron productos aprobados en esta solicitud de almacén');
                            this.form.items = [];
                        }
                    } catch (error) {
                        console.error('Error loading almacen items:', error);
                        alert('Error al cargar los productos de la solicitud de almacén');
                        this.form.items = [];
                    } finally {
                        this.loadingAlmacen = false;
                    }
                },
                toggleAllItems() {
                    if (this.allExpanded) {
                        this.expandedItem = null;
                    } else {
                        this.expandedItem = 0; // Expande el primero
                    }
                    this.allExpanded = !this.allExpanded;
                },
                autocompleteFormData(solicitudData) {
                    console.log('Autocompletando formulario con datos:', solicitudData);

                    this.form.departamento_auto = '';
                    this.form.prioridad_auto = '';
                    this.form.centro_costo_auto = '';
                    this.form.justificacion_auto = '';
                    this.form.observaciones_auto = '';

                    this.form.solicitante_almacen = solicitudData.solicitante_almacen || '';

                    if (solicitudData.idTipoArea) {
                        this.form.idTipoArea = solicitudData.idTipoArea;
                        this.form.departamento_auto = solicitudData.tipo_area_nombre;
                    }

                    if (solicitudData.idPrioridad) {
                        this.form.idPrioridad = solicitudData.idPrioridad;
                        this.form.prioridad_auto = solicitudData.prioridad_nombre;
                    }

                    if (solicitudData.fecha_requerida) {
                        this.form.fecha_requerida = solicitudData.fecha_requerida;
                        this.form.fecha_requerida_auto = solicitudData.fecha_requerida;
                    }

                    if (solicitudData.idCentroCosto && !this.form.idCentroCosto) {
                        this.form.idCentroCosto = solicitudData.idCentroCosto;
                        this.form.centro_costo_auto = solicitudData.centro_costo_nombre;
                    }

                    if (solicitudData.justificacion && !this.form.justificacion) {
                        this.form.justificacion = solicitudData.justificacion;
                        this.form.justificacion_auto = solicitudData.justificacion;
                    }

                    if (solicitudData.observaciones && !this.form.observaciones) {
                        this.form.observaciones = solicitudData.observaciones;
                        this.form.observaciones_auto = solicitudData.observaciones;
                    }
                },

                resetAlmacenData() {
                    console.log('Reseteando datos de almacén...');

                    this.form.items = [];
                    this.form.solicitante_almacen = '';
                    this.form.departamento_auto = '';
                    this.form.prioridad_auto = '';
                    this.form.fecha_requerida_auto = '';
                    this.form.centro_costo_auto = '';
                    this.form.justificacion_auto = '';
                    this.form.observaciones_auto = '';
                },


                // Método para navegar a un artículo específico
                goToItem(index) {
                    if (index >= 0 && index < this.form.items.length) {
                        this.currentItemIndex = index;
                    }
                },

                // Método para actualizar el total cuando cambiamos de artículo
                updateItemTotal(index) {
                    const item = this.form.items[index];
                    const quantity = parseFloat(item.cantidad_aprobada) || 0;
                    const unitPrice = parseFloat(item.precio_unitario_estimado) || 0;
                    item.total_producto = (quantity * unitPrice).toFixed(2);
                },
                // NUEVO MÉTODO: Cambiar moneda al hacer clic
                cycleCurrency(index) {
                    const item = this.form.items[index];
                    const currentCurrencyId = item.idMonedas || 1;

                    // Encontrar el índice actual de la moneda
                    const currentIndex = this.monedasList.findIndex(moneda => moneda.idMonedas == currentCurrencyId);

                    // Obtener la siguiente moneda (cíclico)
                    const nextIndex = (currentIndex + 1) % this.monedasList.length;
                    const nextCurrency = this.monedasList[nextIndex];

                    // Actualizar la moneda del item
                    item.idMonedas = nextCurrency.idMonedas;

                    // Forzar actualización del total
                    this.updateItemTotal(index);

                    console.log(`Moneda cambiada a: ${nextCurrency.nombre} (${nextCurrency.simbolo})`);

                    // Debug: mostrar información actual
                    this.showDebugInfo();
                },
                showDebugInfo() {
                    const debugDiv = document.getElementById('debug-info');
                    if (debugDiv) {
                        debugDiv.innerHTML = `
            <strong>Debug Info:</strong><br>
            Items: ${this.form.items.length}<br>
            Monedas: ${this.form.items.map(item => `Item ${item.idMonedas}`).join(', ')}
        `;
                    }
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
                    // Para el resumen general, usar la moneda más común o Sol Peruano por defecto
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

                getSolicitudAlmacenText(idSolicitudAlmacen) {
                    const solicitud = this.solicitudesAlmacenData[idSolicitudAlmacen];
                    return solicitud ? `${solicitud.codigo_solicitud} - ${solicitud.titulo}` : '';
                },

                getDepartmentText(idTipoArea) {
                    const departments = {
                        @foreach ($tipoAreas as $area)
                            '{{ $area->idTipoArea }}': '{{ $area->nombre }}',
                        @endforeach
                    };
                return departments[idTipoArea] || idTipoArea;
            },

                getCostCenterText(idCentroCosto) {
                const costCenters = {
                    @foreach ($centrosCosto as $centro)
                        '{{ $centro->idCentroCosto }}': '{{ $centro->codigo }} - {{ $centro->nombre }}',
                    @endforeach
                    };
            return costCenters[idCentroCosto] || idCentroCosto;
        },

        getPriorityText(idPrioridad) {
            const priorities = {
                @foreach ($prioridades as $prioridad)
                    '{{ $prioridad->idPrioridad }}': '{{ $prioridad->nombre }}',
                @endforeach
                    };
        return priorities[idPrioridad] || idPrioridad;
                },

        formatPreviewDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        },

        copyCode() {
            navigator.clipboard.writeText(this.requestCode).then(() => {
                const btn = event.target.closest('.btn-copy');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    `;
                btn.style.color = '#10b981';

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.color = '';
                }, 2000);
            });
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
                        <h3 class="text-lg font-bold text-gray-900">Confirmar Limpieza</h3>
                        <p class="text-sm text-gray-600">Esta acción no se puede deshacer</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-4">
                <div class="flex items-start gap-3 mb-4">
                    <i class="fas fa-eraser text-amber-500 text-xl mt-0.5"></i>
                    <div>
                        <p class="text-gray-700 font-medium mb-1">¿Está seguro de que desea limpiar todos los campos?</p>
                        <p class="text-sm text-gray-500">Se perderán todos los datos ingresados en el formulario.</p>
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
                        Sí, Limpiar Todo
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
                // Cerrar el modal inmediatamente
                closeModal();

                // Ejecutar la limpieza del formulario después de cerrar el modal
                setTimeout(() => {
                    this.form = {
                        idSolicitudAlmacen: '',
                        solicitante_almacen: '',
                        solicitante_compra: '{{ $solicitanteCompra }}',
                        idTipoArea: '',
                        idPrioridad: '',
                        fecha_requerida: new Date().toISOString().split('T')[0],
                        idCentroCosto: '',
                        proyecto_asociado: '',
                        justificacion: '',
                        observaciones: '',
                        items: [],
                        files: [],
                        departamento_auto: '',
                        prioridad_auto: '',
                        fecha_requerida_auto: '',
                        centro_costo_auto: '',
                        justificacion_auto: '',
                        observaciones_auto: ''
                    };

                    // Resetear Flatpickr
                    const flatpickrInstance = document.querySelector('.flatpickr-date')._flatpickr;
                    if (flatpickrInstance) {
                        flatpickrInstance.setDate(this.form.fecha_requerida);
                    }

                    // Mostrar notificación de éxito con Toastr
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Formulario limpiado correctamente', '¡Éxito!', {
                            closeButton: true,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            timeOut: 4000,
                            extendedTimeOut: 2000,
                            showMethod: "fadeIn",
                            hideMethod: "fadeOut"
                        });
                    } else {
                        // Fallback si Toastr no está disponible
                        console.log('Toastr no disponible, usando fallback');
                        this.showNotification('Formulario limpiado correctamente', 'success');
                    }
                }, 350); // Un poco después de que el modal se cierre
            };

            cancelReset.onclick = () => {
                // Mostrar notificación de cancelación con Toastr
                if (typeof toastr !== 'undefined') {
                    toastr.info('La operación fue cancelada', 'Acción cancelada', {
                        timeOut: 3000,
                        progressBar: true
                    });
                }
                closeModal();
            };

            // Cerrar al hacer clic fuera del modal
            modal.onclick = (e) => {
                if (e.target === modal) {
                    // Mostrar notificación de cancelación con Toastr
                    if (typeof toastr !== 'undefined') {
                        toastr.info('La operación fue cancelada', 'Acción cancelada', {
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
                    // Mostrar notificación de cancelación con Toastr
                    if (typeof toastr !== 'undefined') {
                        toastr.info('La operación fue cancelada', 'Acción cancelada', {
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

        // Función auxiliar para notificaciones (fallback si Toastr no está disponible)
        showNotification(message, type = 'info') {
            // Crear notificación toast
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-blue-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-info-circle';

            toast.className =
                `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full z-50`;
            toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${icon} text-lg"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;

            document.body.appendChild(toast);

            // Animación de entrada
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 100);

            // Auto-remover después de 3 segundos
            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        },

        // Modifica el método submitForm para usar Toastr en las validaciones
        submitForm() {
            // Debug antes de enviar
            console.log('Datos a enviar:', JSON.stringify(this.form.items, null, 2));

            if (!this.form.idSolicitudAlmacen || !this.form.solicitante_compra || !this.form.idTipoArea ||
                !this.form.idPrioridad || !this.form.fecha_requerida || !this.form.justificacion) {

                if (typeof toastr !== 'undefined') {
                    toastr.error('Por favor complete todos los campos obligatorios (*)', 'Campos incompletos', {
                        closeButton: true,
                        timeOut: 5000,
                        progressBar: true
                    });
                } else {
                    alert('Por favor complete todos los campos obligatorios (*)');
                }
                return;
            }

            if (this.form.items.length === 0) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning('Debe seleccionar una solicitud de almacén con productos aprobados',
                        'Sin productos', {
                        closeButton: true,
                        timeOut: 5000,
                        progressBar: true
                    });
                } else {
                    alert('Debe seleccionar una solicitud de almacén con productos aprobados');
                }
                return;
            }

            // Validar que todos los items tengan moneda
            for (let i = 0; i < this.form.items.length; i++) {
                const item = this.form.items[i];
                if (!item.descripcion_producto || !item.cantidad_aprobada || !item.precio_unitario_estimado || !item
                    .idMonedas) {
                    const errorMessage =
                        `Por favor complete todos los campos obligatorios del artículo ${i + 1}\n\nFaltante: ${!item.descripcion_producto ? 'Descripción' : !item.cantidad_aprobada ? 'Cantidad' : !item.precio_unitario_estimado ? 'Precio' : 'Moneda'}`;

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage, 'Error en artículo ' + (i + 1), {
                            closeButton: true,
                            timeOut: 6000,
                            progressBar: true
                        });
                    } else {
                        alert(errorMessage);
                    }
                    return;
                }
            }

            // Mostrar datos finales en consola
            console.log('Enviando formulario con datos:', {
                items: this.form.items,
                formData: this.form
            });

            // Mostrar notificación de envío exitoso
            if (typeof toastr !== 'undefined') {
                toastr.success('Enviando solicitud de compra...', 'Procesando', {
                    timeOut: 2000,
                    progressBar: true
                });
            }

            document.getElementById('purchaseRequestForm').submit();
        }
            }
        }
    </script>
</x-layout.default>