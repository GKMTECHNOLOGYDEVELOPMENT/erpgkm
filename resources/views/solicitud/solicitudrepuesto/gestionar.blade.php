<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div class="mx-auto w-full px-4 py-8">

        <div class="mb-6">
            <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudarticulo.index') }}" class="text-primary hover:underline">Solicitudes</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Gestión de Repuestos Entregados</span>
                </li>
            </ul>
        </div>
        <!-- Header Principal - Compacto con Usuario y Fecha -->
        <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between lg:gap-6">
                <!-- Contenido Principal -->
                <div class="flex-1">
                    <!-- Título y Descripción -->
                    <div class="mb-3">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Gestión de Repuestos Entregados
                        </h1>
                        <p class="text-gray-600 text-sm sm:text-base">Visualice y gestione los repuestos entregados</p>
                    </div>

                    <!-- Información en Grid - 4 columnas -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <!-- Estado -->
                        <div class="flex items-center p-2 sm:p-3 bg-green-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center mr-2">
                                <i class="fas fa-check-circle text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Estado</p>
                                @php
                                    $estadoClases = [
                                        'aprobada' => 'bg-green-100 text-green-800',
                                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                                        'rechazada' => 'bg-red-100 text-red-800',
                                    ];
                                    $estadoTexto = [
                                        'aprobada' => 'Aprobada',
                                        'pendiente' => 'Pendiente',
                                        'rechazada' => 'Rechazada',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-0.5 text-xs font-semibold rounded {{ $estadoClases[$solicitud->estado] ?? 'bg-gray-200 text-gray-800' }}">
                                    {{ $estadoTexto[$solicitud->estado] ?? ucfirst($solicitud->estado) }}
                                </span>
                            </div>
                        </div>

                        <!-- Urgencia -->
                        <div class="flex items-center p-2 sm:p-3 bg-orange-50 rounded-lg">
                            <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center mr-2">
                                <i class="fas fa-bolt text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Urgencia</p>
                                @php
                                    $urgenciaClases = [
                                        'alta' => 'bg-red-100 text-red-800',
                                        'media' => 'bg-yellow-100 text-yellow-800',
                                        'baja' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-0.5 text-xs font-semibold rounded {{ $urgenciaClases[$solicitud->niveldeurgencia] ?? 'bg-gray-200 text-gray-800' }}">
                                    {{ ucfirst($solicitud->niveldeurgencia) }}
                                </span>
                            </div>
                        </div>

                        <!-- Solicitante -->
                        <div class="flex items-center p-2 sm:p-3 bg-purple-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center mr-2">
                                <i class="fas fa-user text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Solicitante</p>
                                <p class="font-semibold text-gray-900 text-sm truncate">
                                    {{ $solicitud->nombre_solicitante ?? 'No especificado' }}</p>
                            </div>
                        </div>

                        <!-- Fecha Entrega -->
                        <div class="flex items-center p-2 sm:p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-gray-100 rounded-md flex items-center justify-center mr-2">
                                <i class="fas fa-calendar-check text-gray-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Fecha Entrega</p>
                                <p class="font-semibold text-gray-900 text-sm">
                                    @if ($solicitud->fecharequerida)
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d/m/Y') }}
                                    @else
                                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Indicador de estado -->
                    <div class="flex items-center gap-2 mt-3">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-sm text-green-600">Repuesto entregado</span>
                    </div>
                </div>

                <!-- Sección derecha con Código y Botón -->
                <div class="mt-4 lg:mt-0">
                    <div
                        class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg p-3 sm:p-4 text-center shadow">
                        <!-- Código -->
                        <div class="mb-2">
                            <p class="text-white/80 text-xs font-medium">Código</p>
                            <div class="text-lg sm:text-xl font-black text-white tracking-wide">{{ $solicitud->codigo }}
                            </div>
                        </div>

                        <!-- Botón Volver -->
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white/20 hover:bg-white/30 text-white rounded transition-all duration-200 border border-white/30 hover:border-white/50 text-xs sm:text-sm w-full">
                            <i class="fas fa-arrow-left mr-1.5 text-xs"></i>
                            <span class="font-semibold">Volver al listado</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if ($repuestos && $repuestos->count() > 0)
            <!-- Panel de Control de Estados -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Control de Estados del Repuesto
                    </h2>
                    <p class="text-white text-sm mt-1">Selecciona el estado de uso para cada repuesto entregado</p>
                </div>

                <div class="divide-y divide-gray-100" id="listaRepuestos">
                    @foreach ($repuestos as $repuesto)
                        @php
                            $estadoActual = $estadosRepuestos[$repuesto->idArticulos] ?? 'pendiente';
                            $clasesEstado = [
                                'usado' => 'bg-green-100 text-green-800 border-green-200',
                                'no_usado' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'pendiente' => 'bg-gray-100 text-gray-800 border-gray-200',
                            ];
                            $textoEstado = [
                                'usado' => 'Usado',
                                'no_usado' => 'No Usado',
                                'pendiente' => 'Pendiente',
                            ];
                        @endphp
                        <div class="p-6 hover:bg-gray-50" data-repuesto-id="{{ $repuesto->idArticulos }}">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4">
                                <!-- Información del Repuesto -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-cogs text-green-600 text-xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $repuesto->nombre }}
                                            </h3>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-barcode text-gray-400 mr-2"></i>
                                                    <span><strong>Código:</strong>
                                                        {{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-tag text-gray-400 mr-2"></i>
                                                    <span><strong>Tipo:</strong> {{ $repuesto->tipo_repuesto }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-layer-group text-gray-400 mr-2"></i>
                                                    <span><strong>Cantidad:</strong>
                                                        {{ $repuesto->cantidad_solicitada }} unidad(es)</span>
                                                </div>
                                                <!-- Nueva columna para el ticket -->
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-ticket-alt text-gray-400 mr-2"></i>
                                                    <span><strong>Ticket:</strong>
                                                        @if ($repuesto->numero_ticket_repuesto)
                                                            {{ $repuesto->numero_ticket_repuesto }}
                                                        @else
                                                            <span class="text-gray-400">Sin ticket</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado Actual -->
                                <div class="lg:text-right">
                                    <div class="inline-flex flex-col items-end gap-2">
                                        <span
                                            class="px-3 py-1 text-sm font-semibold rounded-full border estado-repuesto {{ $clasesEstado[$estadoActual] }}">
                                            @if ($estadoActual === 'usado')
                                                <i class="fas fa-check-circle mr-1"></i>
                                            @elseif($estadoActual === 'no_usado')
                                                <i class="fas fa-times-circle mr-1"></i>
                                            @else
                                                <i class="fas fa-clock mr-1"></i>
                                            @endif
                                            {{ $textoEstado[$estadoActual] }}
                                        </span>
                                        <span class="text-xs text-gray-500 fecha-actualizacion">
                                            @if ($estadoActual === 'usado' && $repuesto->fechaUsado)
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                {{ \Carbon\Carbon::parse($repuesto->fechaUsado)->format('d/m/Y H:i') }}
                                            @elseif($estadoActual === 'no_usado' && $repuesto->fechaSinUsar)
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                {{ \Carbon\Carbon::parse($repuesto->fechaSinUsar)->format('d/m/Y H:i') }}
                                            @else
                                                <i class="fas fa-clock mr-1"></i> Sin definir
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Selector de Estados -->
                            <div class="bg-gray-50 rounded-xl p-4 mt-4">
                                <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-cog text-gray-500 mr-2"></i>
                                    Seleccionar Estado de Uso
                                </h4>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <!-- Botón Usado -->
                                    <button type="button"
                                        class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-md btn-usado @if ($estadoActual === 'usado') opacity-50 cursor-not-allowed @endif"
                                        data-repuesto-id="{{ $repuesto->idArticulos }}"
                                        data-repuesto-codigo="{{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}"
                                        data-repuesto-ticket="{{ $repuesto->numero_ticket_repuesto }}"
                                        @if ($estadoActual === 'usado') disabled @endif>
                                        <i class="fas fa-check-circle mr-2 text-lg"></i>
                                        <span class="font-semibold">
                                            @if ($estadoActual === 'usado')
                                                <i class="fas fa-check-double mr-1"></i> Ya Marcado como Usado
                                            @else
                                                Marcar como Usado
                                            @endif
                                        </span>
                                    </button>

                                    <!-- Botón No Usado -->
                                    <button type="button"
                                        class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-md btn-no-usado @if ($estadoActual === 'usado' || $estadoActual === 'no_usado') opacity-50 cursor-not-allowed @endif"
                                        data-repuesto-id="{{ $repuesto->idArticulos }}"
                                        data-repuesto-codigo="{{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}"
                                        data-repuesto-ticket="{{ $repuesto->numero_ticket_repuesto }}"
                                        @if ($estadoActual === 'usado' || $estadoActual === 'no_usado') disabled @endif>
                                        <i class="fas fa-times-circle mr-2 text-lg"></i>
                                        <span class="font-semibold">
                                            @if ($estadoActual === 'no_usado')
                                                <i class="fas fa-check-double mr-1"></i> Ya Marcado como No Usado
                                            @elseif ($estadoActual === 'usado')
                                                No disponible
                                            @else
                                                Marcar como No Usado
                                            @endif
                                        </span>
                                    </button>
                                </div>

                                <!-- Información de permisos -->
                                <div class="mt-3 text-xs text-gray-500 flex items-center justify-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span id="infoPermisos">
                                        Usuario: {{ auth()->user()->name }}
                                    </span>
                                </div>
                            </div>

                            @if ($repuesto->observacion)
                                <div class="mt-4 p-3 bg-orange-50 rounded-xl border border-orange-200">
                                    <div class="flex items-start">
                                        <div
                                            class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3 mt-0.5">
                                            <i class="fas fa-comment-alt text-orange-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-orange-700 text-sm">Observación del Repuesto
                                            </h4>
                                            <p class="text-orange-600 text-sm mt-1">{{ $repuesto->observacion }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Resumen de Estados -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-semibold flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> Usados
                            </p>
                            <p class="text-3xl font-bold text-green-700 mt-2" id="contadorUsados">
                                {{ $contadores['usados'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-green-200">
                        <p class="text-xs text-green-600">
                            <i class="fas fa-chart-line mr-1"></i>
                            {{ $repuestos->count() > 0 ? number_format(($contadores['usados'] / $repuestos->count()) * 100, 1) : 0 }}%
                            del total
                        </p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-semibold flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> No Usados
                            </p>
                            <p class="text-3xl font-bold text-blue-700 mt-2" id="contadorNoUsados">
                                {{ $contadores['no_usados'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-times text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <p class="text-xs text-blue-600">
                            <i class="fas fa-chart-line mr-1"></i>
                            {{ $repuestos->count() > 0 ? number_format(($contadores['no_usados'] / $repuestos->count()) * 100, 1) : 0 }}%
                            del total
                        </p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold flex items-center">
                                <i class="fas fa-clock mr-2"></i> Pendientes
                            </p>
                            <p class="text-3xl font-bold text-gray-700 mt-2" id="contadorPendientes">
                                {{ $contadores['pendientes'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-gray-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-600">
                            <i class="fas fa-chart-line mr-1"></i>
                            {{ $repuestos->count() > 0 ? number_format(($contadores['pendientes'] / $repuestos->count()) * 100, 1) : 0 }}%
                            del total
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Mensaje cuando no hay repuestos -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-400 to-green-500 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-white text-green-600 rounded-full flex items-center justify-center font-bold shadow-md">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">No hay repuestos procesados</h2>
                            <p class="text-white text-sm">No se encontraron repuestos entregados para gestionar
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cogs text-yellow-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay repuestos para gestionar</h3>
                    <p class="text-gray-600 mb-6">Esta solicitud no contiene repuestos entregados que requieran gestión
                        de estados.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal para Marcar como Usado -->
    <div id="modalUsado"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999] transition-all duration-300 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Marcar Repuesto como Usado
                    </h3>
                    <button type="button" id="cerrarModal" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="formUsado" class="p-6">
                @csrf
                <input type="hidden" id="articulo_id" name="articulo_id">

                <!-- Información del Repuesto -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                        Información del Repuesto
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Código</label>
                            <p id="modalRepuestoCodigo" class="text-gray-800 font-semibold"></p>
                        </div>
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Número de Ticket</label>
                            <p id="modalRepuestoTicket" class="text-gray-800 font-semibold"></p>
                        </div>
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Solicitud</label>
                            <p class="text-gray-800 font-semibold">{{ $solicitud->codigo }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fecha de Uso -->
                <div class="mb-6">
                    <label for="fecha_uso" class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <i class="fas fa-calendar-alt text-gray-500 mr-2"></i>
                        Fecha de Uso *
                    </label>
                    <input type="datetime-local" id="fecha_uso" name="fecha_uso"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                        value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    <p class="text-xs text-gray-500 mt-1">Selecciona la fecha y hora en que se utilizó el repuesto</p>
                </div>

                <!-- Observación -->
                <div class="mb-6">
                    <label for="observacion" class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <i class="fas fa-comment-alt text-gray-500 mr-2"></i>
                        Observación
                    </label>
                    <textarea id="observacion" name="observacion" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                        placeholder="Describe dónde y cómo se utilizó el repuesto, o cualquier información relevante..." maxlength="500"></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Máximo 500 caracteres</p>
                        <span id="contadorCaracteres" class="text-xs text-gray-500">0/500</span>
                    </div>
                </div>

                <!-- Subida de Fotos -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <i class="fas fa-camera text-gray-500 mr-2"></i>
                        Fotos del Repuesto Usado
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition-colors cursor-pointer"
                        id="dropZone">
                        <input type="file" id="fotos" name="fotos[]" multiple accept="image/*"
                            class="hidden">
                        <div class="space-y-3">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                            <div>
                                <p class="text-gray-600 font-medium">Haz clic para subir fotos</p>
                                <p class="text-gray-500 text-sm">o arrastra y suelta las imágenes aquí</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG, JPEG hasta 5MB cada una</p>
                        </div>
                    </div>
                    <div id="previewFotos" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                    <p class="text-xs text-gray-500 mt-2">Máximo 5 fotos. Muestra evidencia de dónde se utilizó el
                        repuesto.</p>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelarModal"
                        class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="w-full sm:flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all font-semibold shadow-md flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmar como Usado
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal para Marcar como No Usado -->
    <div id="modalNoUsado"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999] transition-all duration-300 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        Devolver Repuesto al Inventario
                    </h3>
                    <button type="button" id="cerrarModalNoUsado"
                        class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="formNoUsado" class="p-6">
                @csrf
                <input type="hidden" id="articulo_id_no_usado" name="articulo_id">

                <!-- Información del Repuesto -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                        Información del Repuesto a Devolver
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Código</label>
                            <p id="modalRepuestoCodigoNoUsado" class="text-gray-800 font-semibold"></p>
                        </div>
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Número de Ticket</label>
                            <p id="modalRepuestoTicketNoUsado" class="text-gray-800 font-semibold"></p>
                        </div>
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Solicitud</label>
                            <p class="text-gray-800 font-semibold">{{ $solicitud->codigo }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fecha de Devolución -->
                <div class="mb-6">
                    <label for="fecha_devolucion" class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <i class="fas fa-calendar-alt text-gray-500 mr-2"></i>
                        Fecha de Devolución *
                    </label>
                    <input type="datetime-local" id="fecha_devolucion" name="fecha_devolucion"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    <p class="text-xs text-gray-500 mt-1">Selecciona la fecha y hora de la devolución al inventario</p>
                </div>

                <!-- Observación -->
                <div class="mb-6">
                    <label for="observacion_no_usado"
                        class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <i class="fas fa-comment-alt text-gray-500 mr-2"></i>
                        Observación de la Devolución
                    </label>
                    <textarea id="observacion_no_usado" name="observacion" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Describe el motivo de la devolución, estado del repuesto, o cualquier información relevante..."
                        maxlength="500"></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Máximo 500 caracteres</p>
                        <span id="contadorCaracteresNoUsado" class="text-xs text-gray-500">0/500</span>
                    </div>
                </div>

                <!-- Subida de Fotos -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <i class="fas fa-camera text-gray-500 mr-2"></i>
                        Fotos de la Devolución
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer"
                        id="dropZoneNoUsado">
                        <input type="file" id="fotos_no_usado" name="fotos[]" multiple accept="image/*"
                            class="hidden">
                        <div class="space-y-3">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                            <div>
                                <p class="text-gray-600 font-medium">Haz clic para subir fotos</p>
                                <p class="text-gray-500 text-sm">o arrastra y suelta las imágenes aquí</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG, JPEG hasta 5MB cada una</p>
                        </div>
                    </div>
                    <div id="previewFotosNoUsado" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                    <p class="text-xs text-gray-500 mt-2">Máximo 5 fotos. Muestra evidencia del repuesto devuelto.</p>
                </div>

                <!-- Información Importante -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mr-2 mt-0.5 text-lg"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800">Información Importante</h4>
                            <ul class="text-blue-700 text-sm mt-1 space-y-1">
                                <li>• El repuesto será devuelto al inventario automáticamente</li>
                                <li>• El stock se incrementará en la ubicación original</li>
                                <li>• Se eliminará el registro de salida del sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelarModalNoUsado"
                        class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="w-full sm:flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all font-semibold shadow-md flex items-center justify-center">
                        <i class="fas fa-undo mr-2"></i>
                        Confirmar Devolución
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables para modal Usado
            const modal = document.getElementById('modalUsado');
            const modalContent = modal.querySelector('div[class*="rounded-2xl"]');
            const form = document.getElementById('formUsado');
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fotos');
            const previewFotos = document.getElementById('previewFotos');
            const contadorCaracteres = document.getElementById('contadorCaracteres');
            const observacionTextarea = document.getElementById('observacion');
            let currentRepuestoId = null;
            let archivosSeleccionados = [];

            // Variables para modal No Usado
            const modalNoUsado = document.getElementById('modalNoUsado');
            const modalContentNoUsado = modalNoUsado?.querySelector('div[class*="rounded-2xl"]');
            const formNoUsado = document.getElementById('formNoUsado');
            const dropZoneNoUsado = document.getElementById('dropZoneNoUsado');
            const fileInputNoUsado = document.getElementById('fotos_no_usado');
            const previewFotosNoUsado = document.getElementById('previewFotosNoUsado');
            const contadorCaracteresNoUsado = document.getElementById('contadorCaracteresNoUsado');
            const observacionTextareaNoUsado = document.getElementById('observacion_no_usado');
            let archivosSeleccionadosNoUsado = [];

            // Contadores iniciales desde PHP
            let contadorUsados = {{ $contadores['usados'] }};
            let contadorNoUsados = {{ $contadores['no_usados'] }};
            let contadorPendientes = {{ $contadores['pendientes'] }};

            // Función para actualizar contadores
            function actualizarContadores() {
                document.getElementById('contadorUsados').textContent = contadorUsados;
                document.getElementById('contadorNoUsados').textContent = contadorNoUsados;
                document.getElementById('contadorPendientes').textContent = contadorPendientes;
            }

            // ========== MODAL USADO ==========
            // Event listeners para botones "Marcar como Usado"
            document.querySelectorAll('.btn-usado').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) return;

                    const repuestoId = this.getAttribute('data-repuesto-id');
                    const repuestoCodigo = this.getAttribute('data-repuesto-codigo');
                    const repuestoTicket = this.getAttribute('data-repuesto-ticket');

                    abrirModalUsado(repuestoId, repuestoCodigo, repuestoTicket);
                });
            });

            // Función para abrir el modal Usado
            function abrirModalUsado(repuestoId, codigo, ticket) {
                currentRepuestoId = repuestoId;

                // Llenar información del repuesto
                document.getElementById('modalRepuestoCodigo').textContent = codigo;
                document.getElementById('modalRepuestoTicket').textContent = ticket || 'Sin ticket';
                document.getElementById('articulo_id').value = repuestoId;

                // Resetear formulario
                form.reset();
                archivosSeleccionados = [];
                previewFotos.innerHTML = '';
                previewFotos.classList.add('hidden');
                contadorCaracteres.textContent = '0/500';

                // Mostrar modal con animación
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.style.opacity = '1';
                    modal.style.backdropFilter = 'blur(4px)';
                }, 10);
                document.body.style.overflow = 'hidden';
            }

            // Cerrar modal Usado
            document.getElementById('cerrarModal').addEventListener('click', cerrarModal);
            document.getElementById('cancelarModal').addEventListener('click', cerrarModal);

            // Cerrar modal al hacer clic fuera
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    cerrarModal();
                }
            });

            // Prevenir cierre al hacer clic dentro del contenido
            if (modalContent) {
                modalContent.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            function cerrarModal() {
                modal.style.opacity = '0';
                modal.style.backdropFilter = 'blur(0px)';
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }

            // Contador de caracteres para observación Usado
            observacionTextarea.addEventListener('input', function() {
                const longitud = this.value.length;
                contadorCaracteres.textContent = `${longitud}/500`;

                if (longitud > 500) {
                    contadorCaracteres.classList.add('text-red-500');
                } else {
                    contadorCaracteres.classList.remove('text-red-500');
                }
            });

            // Funcionalidad de subida de archivos para Usado
            dropZone.addEventListener('click', () => fileInput.click());

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-green-400', 'bg-green-50');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-green-400', 'bg-green-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-green-400', 'bg-green-50');

                if (e.dataTransfer.files.length > 0) {
                    manejarArchivos(e.dataTransfer.files);
                }
            });

            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    manejarArchivos(e.target.files);
                }
            });

            function manejarArchivos(archivos) {
                const nuevosArchivos = Array.from(archivos);

                // Validar cantidad máxima
                if (archivosSeleccionados.length + nuevosArchivos.length > 5) {
                    toastr.error('Máximo 5 fotos permitidas');
                    return;
                }

                // Validar tipo y tamaño
                for (const archivo of nuevosArchivos) {
                    if (!archivo.type.startsWith('image/')) {
                        toastr.error('Solo se permiten archivos de imagen');
                        return;
                    }

                    if (archivo.size > 5 * 1024 * 1024) {
                        toastr.error('Las imágenes deben ser menores a 5MB');
                        return;
                    }

                    archivosSeleccionados.push(archivo);
                }

                actualizarVistaPrevia();
            }

            function actualizarVistaPrevia() {
                previewFotos.innerHTML = '';

                archivosSeleccionados.forEach((archivo, index) => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                    <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600" data-index="${index}">
                        ×
                    </button>
                `;
                        previewFotos.appendChild(div);
                    };

                    reader.readAsDataURL(archivo);
                });

                if (archivosSeleccionados.length > 0) {
                    previewFotos.classList.remove('hidden');
                } else {
                    previewFotos.classList.add('hidden');
                }
            }

            // Eliminar foto de la vista previa Usado
            previewFotos.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    const index = parseInt(e.target.getAttribute('data-index'));
                    archivosSeleccionados.splice(index, 1);
                    actualizarVistaPrevia();
                }
            });

            // Envío del formulario Usado
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!currentRepuestoId) return;

                const formData = new FormData();
                formData.append('articulo_id', currentRepuestoId);
                formData.append('fecha_uso', document.getElementById('fecha_uso').value);
                formData.append('observacion', document.getElementById('observacion').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));

                // Agregar archivos
                archivosSeleccionados.forEach(archivo => {
                    formData.append('fotos[]', archivo);
                });

                try {
                    const response = await fetch(
                        `/solicitudrepuesto/{{ $solicitud->idsolicitudesordenes }}/marcar-usado`, {
                            method: 'POST',
                            body: formData
                        });

                    const data = await response.json();

                    if (data.success) {
                        toastr.success(data.message);
                        cerrarModal();
                        // Actualizar la UI
                        actualizarEstadoRepuesto(currentRepuestoId, 'usado');
                    } else {
                        toastr.error('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    toastr.error('Error de conexión');
                }
            });

            // ========== MODAL NO USADO ==========
            // Event listeners para botones "Marcar como No Usado"
            document.querySelectorAll('.btn-no-usado').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) return;

                    const repuestoId = this.getAttribute('data-repuesto-id');
                    const repuestoCodigo = this.getAttribute('data-repuesto-codigo');
                    const repuestoTicket = this.getAttribute('data-repuesto-ticket');

                    abrirModalNoUsado(repuestoId, repuestoCodigo, repuestoTicket);
                });
            });

            // Función para abrir el modal de No Usado
            function abrirModalNoUsado(repuestoId, codigo, ticket) {
                currentRepuestoId = repuestoId;

                // Llenar información del repuesto
                document.getElementById('modalRepuestoCodigoNoUsado').textContent = codigo;
                document.getElementById('modalRepuestoTicketNoUsado').textContent = ticket || 'Sin ticket';
                document.getElementById('articulo_id_no_usado').value = repuestoId;

                // Resetear formulario
                formNoUsado.reset();
                archivosSeleccionadosNoUsado = [];
                previewFotosNoUsado.innerHTML = '';
                previewFotosNoUsado.classList.add('hidden');
                contadorCaracteresNoUsado.textContent = '0/500';

                // Mostrar modal con animación
                modalNoUsado.classList.remove('hidden');
                setTimeout(() => {
                    modalNoUsado.style.opacity = '1';
                    modalNoUsado.style.backdropFilter = 'blur(4px)';
                }, 10);
                document.body.style.overflow = 'hidden';
            }

            // Cerrar modal No Usado
            document.getElementById('cerrarModalNoUsado')?.addEventListener('click', cerrarModalNoUsado);
            document.getElementById('cancelarModalNoUsado')?.addEventListener('click', cerrarModalNoUsado);

            // Cerrar modal al hacer clic fuera
            if (modalNoUsado) {
                modalNoUsado.addEventListener('click', function(e) {
                    if (e.target === modalNoUsado) {
                        cerrarModalNoUsado();
                    }
                });
            }

            // Prevenir cierre al hacer clic dentro del contenido
            if (modalContentNoUsado) {
                modalContentNoUsado.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            function cerrarModalNoUsado() {
                modalNoUsado.style.opacity = '0';
                modalNoUsado.style.backdropFilter = 'blur(0px)';
                setTimeout(() => {
                    modalNoUsado.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }

            // Contador de caracteres para observación No Usado
            observacionTextareaNoUsado?.addEventListener('input', function() {
                const longitud = this.value.length;
                contadorCaracteresNoUsado.textContent = `${longitud}/500`;

                if (longitud > 500) {
                    contadorCaracteresNoUsado.classList.add('text-red-500');
                } else {
                    contadorCaracteresNoUsado.classList.remove('text-red-500');
                }
            });

            // Funcionalidad de subida de archivos para No Usado
            dropZoneNoUsado?.addEventListener('click', () => fileInputNoUsado.click());

            dropZoneNoUsado?.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZoneNoUsado.classList.add('border-blue-400', 'bg-blue-50');
            });

            dropZoneNoUsado?.addEventListener('dragleave', () => {
                dropZoneNoUsado.classList.remove('border-blue-400', 'bg-blue-50');
            });

            dropZoneNoUsado?.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZoneNoUsado.classList.remove('border-blue-400', 'bg-blue-50');

                if (e.dataTransfer.files.length > 0) {
                    manejarArchivosNoUsado(e.dataTransfer.files);
                }
            });

            fileInputNoUsado?.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    manejarArchivosNoUsado(e.target.files);
                }
            });

            function manejarArchivosNoUsado(archivos) {
                const nuevosArchivos = Array.from(archivos);

                // Validar cantidad máxima
                if (archivosSeleccionadosNoUsado.length + nuevosArchivos.length > 5) {
                    toastr.error('Máximo 5 fotos permitidas');
                    return;
                }

                // Validar tipo y tamaño
                for (const archivo of nuevosArchivos) {
                    if (!archivo.type.startsWith('image/')) {
                        toastr.error('Solo se permiten archivos de imagen');
                        return;
                    }

                    if (archivo.size > 5 * 1024 * 1024) {
                        toastr.error('Las imágenes deben ser menores a 5MB');
                        return;
                    }

                    archivosSeleccionadosNoUsado.push(archivo);
                }

                actualizarVistaPreviaNoUsado();
            }

            function actualizarVistaPreviaNoUsado() {
                previewFotosNoUsado.innerHTML = '';

                archivosSeleccionadosNoUsado.forEach((archivo, index) => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                    <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600" data-index="${index}">
                        ×
                    </button>
                `;
                        previewFotosNoUsado.appendChild(div);
                    };

                    reader.readAsDataURL(archivo);
                });

                if (archivosSeleccionadosNoUsado.length > 0) {
                    previewFotosNoUsado.classList.remove('hidden');
                } else {
                    previewFotosNoUsado.classList.add('hidden');
                }
            }

            // Eliminar foto de la vista previa No Usado
            previewFotosNoUsado?.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    const index = parseInt(e.target.getAttribute('data-index'));
                    archivosSeleccionadosNoUsado.splice(index, 1);
                    actualizarVistaPreviaNoUsado();
                }
            });

            // Envío del formulario No Usado
            formNoUsado?.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!currentRepuestoId) return;

                const formData = new FormData();
                formData.append('articulo_id', currentRepuestoId);
                formData.append('fecha_devolucion', document.getElementById('fecha_devolucion').value);
                formData.append('observacion', document.getElementById('observacion_no_usado').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));

                // Agregar archivos
                archivosSeleccionadosNoUsado.forEach(archivo => {
                    formData.append('fotos[]', archivo);
                });

                try {
                    const response = await fetch(
                        `/solicitudrepuesto/{{ $solicitud->idsolicitudesordenes }}/marcar-no-usado`, {
                            method: 'POST',
                            body: formData
                        });

                    const data = await response.json();

                    if (data.success) {
                        toastr.success(data.message);
                        cerrarModalNoUsado();
                        // Actualizar la UI
                        actualizarEstadoRepuestoNoUsado(currentRepuestoId, 'no_usado');
                    } else {
                        toastr.error('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    toastr.error('Error de conexión');
                }
            });

            // ========== FUNCIONES COMUNES ==========
            // Función para actualizar estado del repuesto en la UI para Usado
            function actualizarEstadoRepuesto(repuestoId, nuevoEstado) {
                const repuestoElement = document.querySelector(`[data-repuesto-id="${repuestoId}"]`);
                if (!repuestoElement) return;

                const estadoElement = repuestoElement.querySelector('.estado-repuesto');
                const fechaElement = repuestoElement.querySelector('.fecha-actualizacion');
                const btnUsado = repuestoElement.querySelector('.btn-usado');
                const btnNoUsado = repuestoElement.querySelector('.btn-no-usado');

                if (nuevoEstado === 'usado') {
                    contadorUsados++;
                    contadorPendientes--;
                    estadoElement.className =
                        'px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-200 estado-repuesto';
                    estadoElement.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Usado';

                    // Deshabilitar botones
                    btnUsado.disabled = true;
                    btnUsado.classList.add('opacity-50', 'cursor-not-allowed');
                    btnUsado.innerHTML =
                        '<i class="fas fa-check-circle mr-2 text-lg"></i><span class="font-semibold"><i class="fas fa-check-double mr-1"></i> Ya Marcado como Usado</span>';

                    btnNoUsado.disabled = false;
                    btnNoUsado.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnNoUsado.innerHTML =
                        '<i class="fas fa-times-circle mr-2 text-lg"></i><span class="font-semibold">Marcar como No Usado</span>';

                    // Actualizar fecha
                    const ahora = new Date();
                    fechaElement.innerHTML = '<i class="far fa-calendar-alt mr-1"></i> Actualizado: ' + ahora
                        .toLocaleDateString() + ' ' + ahora.toLocaleTimeString();

                    // Actualizar contadores visuales
                    actualizarContadores();
                }
            }

            // Función para actualizar estado del repuesto en la UI para No Usado
            function actualizarEstadoRepuestoNoUsado(repuestoId, nuevoEstado) {
                const repuestoElement = document.querySelector(`[data-repuesto-id="${repuestoId}"]`);
                if (!repuestoElement) return;

                const estadoElement = repuestoElement.querySelector('.estado-repuesto');
                const fechaElement = repuestoElement.querySelector('.fecha-actualizacion');
                const btnUsado = repuestoElement.querySelector('.btn-usado');
                const btnNoUsado = repuestoElement.querySelector('.btn-no-usado');

                if (nuevoEstado === 'no_usado') {
                    contadorNoUsados++;
                    contadorPendientes--;
                    estadoElement.className =
                        'px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200 estado-repuesto';
                    estadoElement.innerHTML = '<i class="fas fa-times-circle mr-1"></i> No Usado';

                    // Deshabilitar botones
                    btnNoUsado.disabled = true;
                    btnNoUsado.classList.add('opacity-50', 'cursor-not-allowed');
                    btnNoUsado.innerHTML =
                        '<i class="fas fa-times-circle mr-2 text-lg"></i><span class="font-semibold"><i class="fas fa-check-double mr-1"></i> Ya Marcado como No Usado</span>';

                    btnUsado.disabled = false;
                    btnUsado.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnUsado.innerHTML =
                        '<i class="fas fa-check-circle mr-2 text-lg"></i><span class="font-semibold">Marcar como Usado</span>';

                    // Actualizar fecha
                    const ahora = new Date();
                    fechaElement.innerHTML = '<i class="far fa-calendar-alt mr-1"></i> Actualizado: ' + ahora
                        .toLocaleDateString() + ' ' + ahora.toLocaleTimeString();

                    // Actualizar contadores visuales
                    actualizarContadores();
                }
            }

            // Inicializar contadores
            actualizarContadores();
        });
    </script>
</x-layout.default>
