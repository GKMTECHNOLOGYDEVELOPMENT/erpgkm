<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.8.2/slimselect.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    <!-- Breadcrumb -->
    <div class="mx-auto w-full px-4 py-6">
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('asignar-articulos.index') }}" class="text-primary hover:underline">
                        Asignación de Artículos
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span class="font-medium text-gray-700">Editar Asignación #{{ $asignacion->id }}</span>
                </li>
            </ul>
        </div>

        <!-- Header -->
        <div class="panel mb-8 p-6 rounded-2xl shadow-lg border-0 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-primary to-primary/80 rounded-xl shadow-lg flex items-center justify-center">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Editar Asignación</h1>
                        <p class="text-gray-600 mt-1">Modificar asignación de artículos a usuario</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span
                        class="px-3 py-1.5 bg-{{ $asignacion->estado == 'activo' ? 'success' : ($asignacion->estado == 'devuelto' ? 'secondary' : 'danger') }}/10 text-{{ $asignacion->estado == 'activo' ? 'success' : ($asignacion->estado == 'devuelto' ? 'secondary' : 'danger') }} text-sm font-semibold rounded-full">
                        {{ ucfirst($asignacion->estado) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sección principal del formulario -->
            <div class="lg:col-span-2">
                <div class="panel rounded-2xl shadow-lg border-0 bg-white p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">
                        <i class="fas fa-edit text-primary mr-2"></i>
                        Editar Información de la Asignación
                    </h3>

                    <div class="space-y-6">
                        <!-- Usuario -->
                        <div class="space-y-3 group">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-primary text-sm"></i>
                                </div>
                                Usuario
                            </label>
                            <div class="relative">
                                <!-- Select de Usuario -->
                                <select id="usuarioSelect"
                                    class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none shadow-sm focus:shadow-md cursor-pointer text-sm h-12">
                                    <option value="" class="text-gray-400 text-sm">Seleccionar usuario...</option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->idUsuario }}"
                                            {{ $asignacion->idUsuario == $usuario->idUsuario ? 'selected' : '' }}
                                            class="text-gray-700 py-2 text-sm">
                                            {{ $usuario->nombre_completo }} - {{ $usuario->correo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Lista de Artículos -->
                        <div class="space-y-3 group">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                    <div class="w-8 h-8 bg-success/10 rounded-lg flex items-center justify-center mr-2">
                                        <i class="fas fa-boxes text-success text-sm"></i>
                                    </div>
                                    Artículos asignados
                                </label>
                                <button type="button" id="agregarArticuloBtn"
                                    class="px-3 py-1.5 bg-success text-white text-sm font-medium rounded-lg hover:bg-success-dark transition-all duration-200 flex items-center">
                                    <i class="fas fa-plus mr-1"></i> Agregar artículo
                                </button>
                            </div>

                            <div id="articulosContainer" class="articulos-scroll-container">
                                @foreach ($asignacion->detalles as $detalle)
                                    <div class="articulo-item bg-gray-50 rounded-xl p-4 border border-gray-200 mb-3"
                                        data-id="{{ $detalle->id }}">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                            <!-- Artículo (más ancho) -->
                                            <div class="md:col-span-5">
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Artículo
                                                </label>
                                                <!-- Select de Artículo -->
                                                <select
                                                    class="articulo-select w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-lg hover:border-success/40 focus:border-success focus:ring-2 focus:ring-success/20 transition-all duration-200 appearance-none shadow-sm focus:shadow-md cursor-pointer text-sm h-12">
                                                    <option value="" class="text-gray-400 text-sm">Buscar
                                                        artículo...</option>
                                                    @foreach ($articulos as $articulo)
                                                        <option value="{{ $articulo->idArticulos }}"
                                                            data-stock="{{ $articulo->stock_disponible }}"
                                                            data-maneja-serie="{{ $articulo->maneja_serie }}"
                                                            data-precio="{{ $articulo->precio_venta ?? 0 }}"
                                                            data-nombre="{{ $articulo->nombre }}"
                                                            data-codigo="{{ $articulo->codigo_repuesto }}"
                                                            data-search="{{ strtolower($articulo->nombre ?: $articulo->codigo_repuesto) . ' ' . strtolower($articulo->codigo_repuesto ?: '') }}"
                                                            {{ $detalle->articulo_id == $articulo->idArticulos ? 'selected' : '' }}
                                                            class="py-2 text-sm">
                                                            @if ($articulo->nombre)
                                                                {{ $articulo->nombre }}
                                                                @if ($articulo->codigo_repuesto && $articulo->codigo_repuesto != $articulo->nombre)
                                                                    [{{ $articulo->codigo_repuesto }}]
                                                                @endif
                                                            @else
                                                                {{ $articulo->codigo_repuesto }}
                                                            @endif
                                                            (Stock: {{ $articulo->stock_disponible }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Cantidad (al lado del select) -->
                                            <div class="md:col-span-2">
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Cantidad
                                                </label>
                                                <div class="flex items-center">
                                                    <input type="number" min="1"
                                                        value="{{ $detalle->cantidad }}"
                                                        class="articulo-cantidad w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-warning focus:ring-2 focus:ring-warning/20 transition-all duration-200">
                                                    <div
                                                        class="ml-2 text-xs text-gray-500 stock-info whitespace-nowrap">
                                                        Stock: <span class="font-medium">0</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Estado -->
                                            <div class="md:col-span-3">
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Estado
                                                </label>
                                                <select
                                                    class="articulo-estado w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200">
                                                    <option value="activo"
                                                        {{ $detalle->estado_articulo == 'activo' ? 'selected' : '' }}>
                                                        Activo</option>
                                                    <option value="dañado"
                                                        {{ $detalle->estado_articulo == 'dañado' ? 'selected' : '' }}>
                                                        Dañado</option>
                                                    <option value="perdido"
                                                        {{ $detalle->estado_articulo == 'perdido' ? 'selected' : '' }}>
                                                        Perdido</option>
                                                    <option value="devuelto"
                                                        {{ $detalle->estado_articulo == 'devuelto' ? 'selected' : '' }}>
                                                        Devuelto</option>
                                                </select>
                                            </div>

                                            <!-- Botón eliminar -->
                                            <div class="md:col-span-2">
                                                <button type="button"
                                                    class="eliminar-articulo-btn w-full px-3 py-2.5 bg-danger text-white rounded-lg hover:bg-red-700 transition-all duration-200 flex items-center justify-center">
                                                    <i class="fas fa-trash mr-2"></i> Quitar
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Número de serie -->
                                        <div
                                            class="mt-3 articulo-serie {{ $detalle->articulo->maneja_serie ? '' : 'hidden' }}">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Número de serie
                                            </label>
                                            <input type="text"
                                                class="articulo-serie-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200"
                                                placeholder="Número de serie del artículo..."
                                                value="{{ $detalle->numero_serie ?? '' }}">
                                            @if ($detalle->articulo->maneja_serie)
                                                <p class="text-xs text-gray-500 mt-1">Este artículo requiere número de
                                                    serie</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total de artículos -->
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Total de artículos:</span>
                                <span id="totalArticulos"
                                    class="text-lg font-bold text-primary">{{ $asignacion->detalles->sum('cantidad') }}</span>
                            </div>
                        </div>

                        <!-- Estado, Cantidad y Fecha -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-3 group">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-tag text-primary mr-2"></i>
                                    Estado de Asignación
                                </label>
                                <div class="relative">
                                    <select id="estadoAsignacion"
                                        class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 shadow-sm focus:shadow-md">
                                        <option value="activo" {{ $asignacion->estado == 'activo' ? 'selected' : '' }}
                                            class="text-success">Activo</option>
                                        <option value="devuelto"
                                            {{ $asignacion->estado == 'devuelto' ? 'selected' : '' }}
                                            class="text-secondary">Devuelto</option>
                                        <option value="vencido"
                                            {{ $asignacion->estado == 'vencido' ? 'selected' : '' }}
                                            class="text-danger">Vencido</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-3 group">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar-alt text-info mr-2"></i>
                                    Fecha de Asignación
                                </label>
                                <div class="relative">
                                    <input type="text" id="fechaAsignacion"
                                        value="{{ $asignacion->fecha_asignacion->format('Y-m-d') }}"
                                        class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-info/40 focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200 shadow-sm focus:shadow-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 group">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar-check text-warning mr-2"></i>
                                    Fecha de Devolución
                                </label>
                                <div class="relative">
                                    <input type="text" id="fechaDevolucion"
                                        value="{{ $asignacion->fecha_devolucion ? $asignacion->fecha_devolucion->format('Y-m-d') : '' }}"
                                        class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-warning/40 focus:border-warning focus:ring-2 focus:ring-warning/20 transition-all duration-200 shadow-sm focus:shadow-md"
                                        placeholder="Seleccionar fecha...">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="space-y-3 group">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-sticky-note text-secondary mr-2"></i>
                                Observaciones
                            </label>
                            <textarea id="observaciones" rows="4"
                                class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-secondary/40 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200 shadow-sm focus:shadow-md resize-none"
                                placeholder="Notas adicionales sobre esta asignación...">{{ $asignacion->observaciones }}</textarea>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                            <div class="flex space-x-4">
                                <a href="{{ route('asignar-articulos.index') }}"
                                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow flex items-center">
                                    <i class="fas fa-times mr-2"></i> Cancelar
                                </a>
                                @if ($asignacion->estado == 'activo')
                                    <form action="{{ route('asignar-articulos.devolver', $asignacion->id) }}"
                                        method="POST" class="inline" id="devolverTodoForm">
                                        @csrf
                                        <button type="button" id="devolverTodoBtn"
                                            class="px-5 py-2.5 bg-success text-white font-semibold rounded-xl hover:bg-success-dark transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                                            <i class="fas fa-undo mr-2"></i> Devolver Todo
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="flex space-x-4">
                                <button id="actualizarAsignacionBtn"
                                    class="px-5 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200 shadow-md hover:shadow-lg flex items-center group">
                                    <i class="fas fa-save mr-2 group-hover:animate-pulse"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel lateral informativo -->
            <div class="space-y-6">
                <!-- Resumen rápido -->
                <div class="panel rounded-2xl shadow-lg border-0 bg-white p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">
                        <i class="fas fa-clipboard-check text-primary mr-2"></i>
                        Resumen de Asignación
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Usuario:</span>
                            <span id="resumenUsuario" class="font-semibold text-gray-900">
                                {{ $asignacion->usuario->Nombre }} {{ $asignacion->usuario->apellidoPaterno }}
                            </span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <div class="mb-2">
                                <span class="text-gray-600">Artículos:</span>
                            </div>
                            <ul id="resumenArticulos" class="space-y-3">
                                @foreach ($asignacion->detalles as $detalle)
                                    <li class="space-y-1">
                                        <div class="flex items-start justify-between">
                                            <span
                                                class="text-sm text-gray-700 truncate">{{ $detalle->articulo->nombre }}</span>
                                            <span
                                                class="font-medium text-success ml-2 whitespace-nowrap">x{{ $detalle->cantidad }}</span>
                                        </div>
                                        @if ($detalle->numero_serie)
                                            <div class="text-xs text-info bg-info/10 rounded-lg px-2 py-1 mt-1">
                                                <span class="font-medium mr-1">Serie:</span>
                                                <span class="font-mono">{{ $detalle->numero_serie }}</span>
                                            </div>
                                        @endif
                                        <div
                                            class="text-xs text-{{ $detalle->estado_articulo == 'activo' ? 'success' : ($detalle->estado_articulo == 'dañado' ? 'danger' : 'warning') }} bg-{{ $detalle->estado_articulo == 'activo' ? 'success' : ($detalle->estado_articulo == 'dañado' ? 'danger' : 'warning') }}/10 rounded-lg px-2 py-1 mt-1 inline-block">
                                            {{ ucfirst($detalle->estado_articulo) }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Total artículos:</span>
                            <span id="resumenTotal"
                                class="font-bold text-primary">{{ $asignacion->detalles->sum('cantidad') }}</span>
                        </div>
                        @php
                            $valorTotal = $asignacion->detalles->sum(function ($detalle) {
                                return $detalle->cantidad * ($detalle->articulo->precio_venta ?? 0);
                            });
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Valor total:</span>
                            <span id="resumenValor"
                                class="font-bold text-success">S/{{ number_format($valorTotal, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Fecha asignación:</span>
                            <span id="resumenFecha"
                                class="font-semibold text-gray-900">{{ $asignacion->fecha_asignacion->format('d/m/Y') }}</span>
                        </div>
                        @if ($asignacion->fecha_devolucion)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-600">Fecha devolución:</span>
                                <span
                                    class="font-semibold text-gray-900">{{ $asignacion->fecha_devolucion->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información de la asignación -->
                <div class="panel rounded-2xl shadow-lg border-0 bg-gradient-to-br from-blue-50 to-white p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-info mr-2"></i>
                        Información del Sistema
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-calendar-plus text-primary mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Creada el</p>
                                <p class="text-sm text-gray-600">{{ $asignacion->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-2">
                            <i class="fas fa-calendar-edit text-warning mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Última modificación</p>
                                <p class="text-sm text-gray-600">{{ $asignacion->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-2">
                            <i class="fas fa-boxes text-success mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Total de artículos</p>
                                <p class="text-sm text-gray-600">{{ $asignacion->detalles->count() }} tipos diferentes
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consejos para edición -->
                <div class="panel rounded-2xl shadow-lg border-0 bg-gradient-to-br from-green-50 to-white p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-lightbulb text-warning mr-2"></i>
                        Recomendaciones para edición
                    </h3>

                    <div class="space-y-3">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <p class="text-sm text-gray-600">Verifica el estado actual de cada artículo</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt=1"></i>
                            <p class="text-sm text-gray-600">Actualiza números de serie si es necesario</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <p class="text-sm text-gray-600">Documenta cambios importantes en observaciones</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <p class="text-sm text-gray-600">Marca como devuelto si el usuario ya retornó todo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .articulos-scroll-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 500px;
            overflow-y: auto;
            padding-right: 8px;
            transition: max-height 0.3s ease;
        }

        .articulos-scroll-container::-webkit-scrollbar {
            width: 6px;
        }

        .articulos-scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .articulos-scroll-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .articulos-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        .articulos-scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f1f1f1;
        }

        .articulos-scroll-container.scroll-activo {
            max-height: 500px;
            overflow-y: auto;
        }

        .articulos-scroll-container.scroll-inactivo {
            max-height: none;
            overflow-y: visible;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.8.2/slimselect.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar Toastr globalmente
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Configuración de Flatpickr
            const flatpickrConfig = {
                locale: "es",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                allowInput: true,
                disableMobile: true,
                theme: "material_blue",
                minDate: "today"
            };

            // Inicializar Flatpickr para fecha de asignación
            const fechaAsignacion = flatpickr("#fechaAsignacion", {
                ...flatpickrConfig,
                defaultDate: "{{ $asignacion->fecha_asignacion->format('Y-m-d') }}",
                onChange: function(selectedDates, dateStr, instance) {
                    actualizarResumen();

                    // Si hay fecha de devolución y es anterior a la nueva fecha de asignación, limpiarla
                    if (fechaDevolucion.selectedDates.length > 0) {
                        const fechaDev = fechaDevolucion.selectedDates[0];
                        if (selectedDates[0] > fechaDev) {
                            fechaDevolucion.clear();
                            toastr.info(
                                'La fecha de devolución se ha limpiado porque era anterior a la nueva fecha de asignación'
                            );
                        }
                    }

                    // Actualizar minDate de fechaDevolucion
                    if (selectedDates[0]) {
                        fechaDevolucion.set('minDate', selectedDates[0]);
                    }
                }
            });

            // Inicializar Flatpickr para fecha de devolución
            const fechaDevolucion = flatpickr("#fechaDevolucion", {
                ...flatpickrConfig,
                @if ($asignacion->fecha_devolucion)
                    defaultDate: "{{ $asignacion->fecha_devolucion->format('Y-m-d') }}",
                @endif
                minDate: "{{ $asignacion->fecha_asignacion->format('Y-m-d') }}",
                onChange: function(selectedDates, dateStr, instance) {
                    actualizarResumen();
                }
            });

            // Inicializar Slim Select para el usuario
            const usuarioSlimSelect = new SlimSelect({
                select: '#usuarioSelect',
                settings: {
                    placeholderText: 'Seleccionar usuario...',
                    searchText: 'Buscar usuario...',
                    searchPlaceholder: 'Escribe para buscar...',
                    searchHighlight: true,
                    hideSelected: true,
                    closeOnSelect: true,
                    allowDeselect: true,
                    showSearch: true,
                    searchFocus: true,
                }
            });

            // Elementos principales
            const usuarioSelect = document.getElementById('usuarioSelect');
            const estadoAsignacion = document.getElementById('estadoAsignacion');
            const articulosContainer = document.getElementById('articulosContainer');
            const agregarArticuloBtn = document.getElementById('agregarArticuloBtn');
            const totalArticulosSpan = document.getElementById('totalArticulos');

            // Elementos del resumen
            const resumenUsuario = document.getElementById('resumenUsuario');
            const resumenArticulos = document.getElementById('resumenArticulos');
            const resumenTotal = document.getElementById('resumenTotal');
            const resumenValor = document.getElementById('resumenValor');
            const resumenFecha = document.getElementById('resumenFecha');

            // Contador de artículos
            let contadorArticulos = {{ $asignacion->detalles->count() }};

            // Función para actualizar el scroll
            function actualizarScroll() {
                const articulosItems = document.querySelectorAll('.articulo-item');
                const container = document.getElementById('articulosContainer');

                let alturaTotal = 0;
                articulosItems.forEach(item => {
                    alturaTotal += item.offsetHeight + 16;
                });

                if (alturaTotal > 500) {
                    container.classList.remove('scroll-inactivo');
                    container.classList.add('scroll-activo');
                } else {
                    container.classList.remove('scroll-activo');
                    container.classList.add('scroll-inactivo');
                }
            }

            // Función para inicializar Slim Select en un select específico
            function inicializarSlimSelectArticulo(selectElement) {
                if (selectElement && !selectElement.hasAttribute('data-slimselect-initialized')) {
                    new SlimSelect({
                        select: selectElement,
                        settings: {
                            placeholderText: 'Buscar artículo...',
                            searchText: 'Buscar artículo...',
                            searchPlaceholder: 'Escribe para buscar...',
                            searchHighlight: true,
                            hideSelected: true,
                            closeOnSelect: true,
                            showSearch: true,
                            searchFocus: true,
                            allowDeselect: true,
                        },
                        events: {
                            afterChange: () => {
                                actualizarArticulo(selectElement);
                                actualizarResumen();
                            }
                        }
                    });
                    selectElement.setAttribute('data-slimselect-initialized', 'true');
                }
            }

            // Función para actualizar el stock y número de serie
            function actualizarArticulo(selectElement) {
                const cantidadInput = selectElement.closest('.articulo-item').querySelector('.articulo-cantidad');
                const stockSpan = selectElement.closest('.articulo-item').querySelector('.stock-info span');
                const serieContainer = selectElement.closest('.articulo-item').querySelector('.articulo-serie');

                if (selectElement.value && selectElement.value !== '') {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                    const manejaSerie = selectedOption.getAttribute('data-maneja-serie') == 1;

                    stockSpan.textContent = stock;
                    cantidadInput.max = stock;

                    const currentValue = parseInt(cantidadInput.value) || 1;
                    cantidadInput.value = Math.min(currentValue, stock);

                    if (manejaSerie) {
                        serieContainer.classList.remove('hidden');
                    } else {
                        serieContainer.classList.add('hidden');
                        // Limpiar serie si no aplica
                        const serieInput = serieContainer.querySelector('.articulo-serie-input');
                        if (serieInput) serieInput.value = '';
                    }
                } else {
                    stockSpan.textContent = '0';
                    cantidadInput.max = 1;
                    cantidadInput.value = 1;
                    serieContainer.classList.add('hidden');
                }
            }

            // Función para actualizar el resumen
            function actualizarResumen() {
                // Actualizar usuario
                if (usuarioSelect.value) {
                    const usuarioNombre = usuarioSelect.options[usuarioSelect.selectedIndex].text.split(' - ')[0];
                    resumenUsuario.textContent = usuarioNombre;
                    resumenUsuario.classList.remove('text-gray-500');
                    resumenUsuario.classList.add('text-primary', 'font-semibold');
                } else {
                    resumenUsuario.textContent = '-';
                    resumenUsuario.classList.remove('text-primary', 'font-semibold');
                    resumenUsuario.classList.add('text-gray-500');
                }

                // Actualizar artículos en el resumen
                const articulosItems = document.querySelectorAll('.articulo-item');
                let totalItems = 0;
                let totalValor = 0;

                if (articulosItems.length > 0) {
                    resumenArticulos.innerHTML = '';

                    articulosItems.forEach((item, index) => {
                        const select = item.querySelector('.articulo-select');
                        const cantidadInput = item.querySelector('.articulo-cantidad');
                        const serieInput = item.querySelector('.articulo-serie-input');
                        const estadoSelect = item.querySelector('.articulo-estado');

                        if (select.value && cantidadInput.value) {
                            let articuloNombre = '';
                            if (select.options[select.selectedIndex]) {
                                articuloNombre = select.options[select.selectedIndex].text;
                                articuloNombre = articuloNombre.replace(/\s*\(Stock: \d+\)/g, '');
                            }

                            const cantidad = parseInt(cantidadInput.value) || 0;
                            const serie = serieInput ? serieInput.value : '';
                            const precio = parseFloat(select.options[select.selectedIndex].getAttribute(
                                'data-precio')) || 0;
                            const estado = estadoSelect ? estadoSelect.value : 'activo';

                            totalItems += cantidad;
                            totalValor += cantidad * precio;

                            const li = document.createElement('li');
                            li.className = 'space-y-1';

                            const articuloInfo = document.createElement('div');
                            articuloInfo.className = 'flex items-start justify-between';

                            const nombreSpan = document.createElement('span');
                            nombreSpan.className = 'text-sm text-gray-700 truncate';
                            nombreSpan.textContent = articuloNombre;

                            const cantidadSpan = document.createElement('span');
                            cantidadSpan.className = 'font-medium text-success ml-2 whitespace-nowrap';
                            cantidadSpan.textContent = `x${cantidad}`;

                            articuloInfo.appendChild(nombreSpan);
                            articuloInfo.appendChild(cantidadSpan);

                            li.appendChild(articuloInfo);

                            // Número de serie (si existe)
                            if (serie) {
                                const serieDiv = document.createElement('div');
                                serieDiv.className =
                                    'text-xs text-info bg-info/10 rounded-lg px-2 py-1 mt-1';

                                const serieLabel = document.createElement('span');
                                serieLabel.className = 'font-medium mr-1';
                                serieLabel.textContent = 'Serie:';

                                const serieValue = document.createElement('span');
                                serieValue.className = 'font-mono';
                                serieValue.textContent = serie;

                                serieDiv.appendChild(serieLabel);
                                serieDiv.appendChild(serieValue);

                                li.appendChild(serieDiv);
                            }

                            // Estado del artículo
                            const estadoDiv = document.createElement('div');
                            const estadoColor = estado == 'activo' ? 'success' :
                                estado == 'dañado' ? 'danger' :
                                estado == 'perdido' ? 'warning' : 'secondary';
                            estadoDiv.className =
                                `text-xs text-${estadoColor} bg-${estadoColor}/10 rounded-lg px-2 py-1 mt-1 inline-block`;
                            estadoDiv.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);

                            li.appendChild(estadoDiv);
                            resumenArticulos.appendChild(li);
                        }
                    });

                    if (totalItems === 0) {
                        const li = document.createElement('li');
                        li.className = 'text-sm text-gray-500 italic';
                        li.textContent = 'Ningún artículo seleccionado';
                        resumenArticulos.appendChild(li);
                    }
                } else {
                    resumenArticulos.innerHTML =
                        '<li class="text-sm text-gray-500 italic">Ningún artículo seleccionado</li>';
                }

                // Actualizar totales
                resumenTotal.textContent = totalItems;
                resumenValor.textContent = 'S/' + totalValor.toFixed(2);
                totalArticulosSpan.textContent = totalItems;

                // Actualizar fecha
                if (fechaAsignacion.selectedDates.length > 0) {
                    const fecha = fechaAsignacion.selectedDates[0];
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);

                    if (fecha.getTime() === hoy.getTime()) {
                        resumenFecha.textContent = 'Hoy';
                    } else {
                        const dia = fecha.getDate().toString().padStart(2, '0');
                        const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
                        const año = fecha.getFullYear();
                        resumenFecha.textContent = `${dia}/${mes}/${año}`;
                    }
                    resumenFecha.classList.remove('text-gray-500');
                    resumenFecha.classList.add('text-info', 'font-semibold');
                } else {
                    resumenFecha.textContent = 'Hoy';
                    resumenFecha.classList.remove('text-info', 'font-semibold');
                    resumenFecha.classList.add('text-gray-500');
                }
            }

            // Función para agregar nuevo artículo
            function agregarArticulo() {
                contadorArticulos++;

                const nuevoArticulo = document.createElement('div');
                nuevoArticulo.className = 'articulo-item bg-gray-50 rounded-xl p-4 border border-gray-200';
                nuevoArticulo.innerHTML = `
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <!-- Artículo -->
        <div class="md:col-span-5">
            <label class="block text-xs font-medium text-gray-600 mb-1">Artículo</label>
<select class="articulo-select w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-lg hover:border-success/40 focus:border-success focus:ring-2 focus:ring-success/20 transition-all duration-200 appearance-none shadow-sm focus:shadow-md cursor-pointer text-sm h-12">
                <option value="" class="text-gray-400">Buscar artículo...</option>
                @foreach ($articulos as $articulo)
                <option value="{{ $articulo->idArticulos }}" 
                    data-stock="{{ $articulo->stock_disponible }}"
                    data-maneja-serie="{{ $articulo->maneja_serie }}"
                    data-precio="{{ $articulo->precio_venta ?? 0 }}"
                    data-nombre="{{ $articulo->nombre }}"
                    data-codigo="{{ $articulo->codigo_repuesto }}"
                    data-search="{{ strtolower($articulo->nombre ?: $articulo->codigo_repuesto) . ' ' . strtolower($articulo->codigo_repuesto ?: '') }}">
                    @if ($articulo->nombre)
                        {{ $articulo->nombre }}
                        @if ($articulo->codigo_repuesto && $articulo->codigo_repuesto != $articulo->nombre)
                            [{{ $articulo->codigo_repuesto }}]
                        @endif
                    @else
                        {{ $articulo->codigo_repuesto }}
                    @endif
                    (Stock: {{ $articulo->stock_disponible }})
                </option>
                @endforeach
            </select>
        </div>

        <!-- Cantidad (al lado del artículo) -->
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Cantidad</label>
            <div class="flex items-center">
                <input type="number" min="1" value="1" 
                    class="articulo-cantidad w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-warning focus:ring-2 focus:ring-warning/20 transition-all duration-200">
                <div class="ml-2 text-xs text-gray-500 stock-info whitespace-nowrap">
                    Stock: <span class="font-medium stock-value">0</span>
                </div>
            </div>
        </div>

        <!-- Estado del artículo -->
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Estado</label>
            <select class="articulo-estado w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200">
                <option value="activo" class="text-success">Activo</option>
                <option value="dañado" class="text-danger">Dañado</option>
                <option value="perdido" class="text-warning">Perdido</option>
                <option value="devuelto" class="text-secondary">Devuelto</option>
            </select>
        </div>

        <!-- Botón eliminar -->
        <div class="md:col-span-2">
            <button type="button" class="eliminar-articulo-btn w-full px-3 py-2.5 bg-danger text-white rounded-lg hover:bg-danger-dark transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-trash mr-1"></i> Quitar
            </button>
        </div>
    </div>

    <!-- Número de serie -->
    <div class="mt-3 articulo-serie hidden">
        <label class="block text-xs font-medium text-gray-600 mb-1">Número de serie</label>
        <input type="text" class="articulo-serie-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200" placeholder="Número de serie del artículo...">
        <p class="text-xs text-gray-500 mt-1">Este artículo requiere número de serie</p>
    </div>
`;

                articulosContainer.appendChild(nuevoArticulo);

                // Inicializar Slim Select para el nuevo select
                const nuevoSelect = nuevoArticulo.querySelector('.articulo-select');
                inicializarSlimSelectArticulo(nuevoSelect);

                actualizarBotonesEliminar();
                actualizarResumen();
                actualizarScroll();

                toastr.success('Nuevo artículo agregado');
            }

            // Función para actualizar botones eliminar
            function actualizarBotonesEliminar() {
                const botonesEliminar = document.querySelectorAll('.eliminar-articulo-btn');
                botonesEliminar.forEach((btn, index) => {
                    if (botonesEliminar.length === 1) {
                        btn.disabled = true;
                        btn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
                    } else {
                        btn.disabled = false;
                        btn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
                    }

                    btn.onclick = null;
                    btn.onclick = function() {
                        if (botonesEliminar.length > 1) {
                            const articuloItem = this.closest('.articulo-item');
                            const selectElement = articuloItem.querySelector('select');
                            if (selectElement) {
                                selectElement.removeAttribute('data-slimselect-initialized');
                            }
                            articuloItem.remove();
                            actualizarBotonesEliminar();
                            actualizarResumen();
                            actualizarScroll();
                            toastr.info('Artículo eliminado del formulario');
                        }
                    };
                });
            }

            // Event Listeners
            agregarArticuloBtn.addEventListener('click', agregarArticulo);
            usuarioSelect.addEventListener('change', actualizarResumen);
            estadoAsignacion.addEventListener('change', actualizarResumen);

            // Event delegation para artículos dinámicos
            articulosContainer.addEventListener('change', function(e) {
                if (e.target.classList.contains('articulo-select')) {
                    actualizarArticulo(e.target);
                    actualizarResumen();
                }
                if (e.target.classList.contains('articulo-estado')) {
                    actualizarResumen();
                }
            });

            articulosContainer.addEventListener('input', function(e) {
                if (e.target.classList.contains('articulo-cantidad') ||
                    e.target.classList.contains('articulo-serie-input')) {
                    actualizarResumen();
                }
            });

            // Inicializar Slim Select para artículos existentes
            document.querySelectorAll('.articulo-select').forEach(select => {
                inicializarSlimSelectArticulo(select);
                actualizarArticulo(select);
            });

            // Inicializar
            actualizarBotonesEliminar();
            actualizarResumen();
            actualizarScroll();

            // Botón Devolver Todo
            // Botón Devolver Todo
            document.getElementById('devolverTodoBtn')?.addEventListener('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    html: '<div class="text-gray-600">¿Estás seguro de devolver todos los artículos de esta asignación?</div>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, devolver todo',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545',
                    reverseButtons: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeIn animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut animate__faster'
                    },
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'btn btn-success btn-lg px-4 py-2',
                        cancelButton: 'btn btn-danger btn-lg px-4 py-2',
                        actions: 'gap-2'
                    },
                    buttonsStyling: false,
                    backdrop: 'rgba(0,0,0,0.4)',
                    allowOutsideClick: false,
                    allowEscapeKey: true,
                    allowEnterKey: false,
                    focusConfirm: false,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('devolverTodoForm').submit();
                    }
                });
            });

            // Validar y actualizar la asignación
            document.getElementById('actualizarAsignacionBtn').addEventListener('click', function() {
                if (!usuarioSelect.value) {
                    toastr.error('Por favor, selecciona un usuario.');
                    usuarioSelect.focus();
                    return;
                }

                const articulosData = [];
                let error = false;
                let mensajeError = '';

                document.querySelectorAll('.articulo-item').forEach(item => {
                    const select = item.querySelector('.articulo-select');
                    const cantidadInput = item.querySelector('.articulo-cantidad');
                    const serieInput = item.querySelector('.articulo-serie-input');
                    const estadoSelect = item.querySelector('.articulo-estado');
                    const detalleId = item.getAttribute('data-id');

                    if (select.value) {
                        const selectedOption = select.options[select.selectedIndex];
                        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                        const cantidad = parseInt(cantidadInput.value) || 1;
                        const manejaSerie = selectedOption.getAttribute('data-maneja-serie') == 1;
                        const nombreArticulo = selectedOption.text.replace(/\s*\(Stock: \d+\)/g,
                            '');

                        // Validar stock solo si el artículo no está devuelto
                        const estadoArticulo = estadoSelect ? estadoSelect.value : 'activo';
                        if (estadoArticulo !== 'devuelto' && estadoAsignacion.value !==
                            'devuelto' && cantidad > stock) {
                            error = true;
                            cantidadInput.classList.add('border-danger');
                            mensajeError =
                                `La cantidad para "${nombreArticulo}" excede el stock disponible (${stock})`;
                            toastr.error(mensajeError);
                        } else {
                            cantidadInput.classList.remove('border-danger');
                        }

                        if (manejaSerie && (!serieInput || !serieInput.value.trim())) {
                            error = true;
                            mensajeError =
                                `El artículo "${nombreArticulo}" requiere número de serie`;
                            toastr.error(mensajeError);
                        }

                        articulosData.push({
                            id: detalleId || null,
                            articulo_id: select.value,
                            cantidad: cantidad,
                            numero_serie: serieInput ? serieInput.value.trim() : null,
                            estado_articulo: estadoSelect ? estadoSelect.value : 'activo'
                        });
                    }
                });

                if (error) return;

                if (articulosData.length === 0) {
                    toastr.error('Por favor, agrega al menos un artículo.');
                    return;
                }

                // Obtener los valores de las fechas de Flatpickr
                const fechaAsignacionValue = fechaAsignacion.input.value;
                const fechaDevolucionValue = fechaDevolucion.input.value;

                // Validar fecha de devolución
                if (fechaAsignacionValue && fechaDevolucionValue) {
                    const fechaAsig = new Date(fechaAsignacionValue);
                    const fechaDev = new Date(fechaDevolucionValue);

                    if (fechaDev < fechaAsig) {
                        toastr.error(
                            'La fecha de devolución no puede ser anterior a la fecha de asignación');
                        fechaDevolucion.open();
                        return;
                    }
                }

                // Reemplaza la sección de confirmación (líneas alrededor de 490-520) con:

                // Confirmación antes de actualizar
                Swal.fire({
                    title: '¿Estás seguro?',
                    html: '<div class="text-gray-600">¿Estás seguro de guardar los cambios en la asignación?</div>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar cambios',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#198754', // Color verde
                    cancelButtonColor: '#dc3545', // Color rojo
                    reverseButtons: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeIn animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut animate__faster'
                    },
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'btn btn-success btn-lg px-4 py-2',
                        cancelButton: 'btn btn-danger btn-lg px-4 py-2',
                        actions: 'gap-2'
                    },
                    buttonsStyling: false,
                    backdrop: 'rgba(0,0,0,0.4)',
                    allowOutsideClick: false,
                    allowEscapeKey: true,
                    allowEnterKey: false,
                    focusConfirm: false,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        actualizarAsignacion(articulosData, fechaAsignacionValue,
                            fechaDevolucionValue);
                    }
                });
            });

            // Función para actualizar la asignación
            function actualizarAsignacion(articulosData, fechaAsignacionValue, fechaDevolucionValue) {
                const btn = document.getElementById('actualizarAsignacionBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';

                fetch('{{ route('asignar-articulos.update', $asignacion->id) }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            idUsuario: usuarioSelect.value,
                            fecha_asignacion: fechaAsignacionValue,
                            fecha_devolucion: fechaDevolucionValue || null,
                            observaciones: document.getElementById('observaciones').value,
                            estado: estadoAsignacion.value,
                            articulos: articulosData
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('asignar-articulos.index') }}';
                            }, 1500);
                        } else {
                            if (data.errors) {
                                // Mostrar errores de validación
                                Object.values(data.errors).forEach(errorMessages => {
                                    errorMessages.forEach(message => {
                                        toastr.error(message);
                                    });
                                });
                            } else {
                                toastr.error(data.message || 'Error al actualizar la asignación');
                            }
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-save mr-2"></i> Guardar Cambios';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Error de conexión al actualizar la asignación');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save mr-2"></i> Guardar Cambios';
                    });
            }
        });
    </script>
</x-layout.default>
