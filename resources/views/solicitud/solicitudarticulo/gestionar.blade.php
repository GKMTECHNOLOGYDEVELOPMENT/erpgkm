<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <div class="mx-auto px-4 py-8 w-full">
        <div class="mb-4">
            <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudarticulo.index') }}" class="text-primary hover:underline">Solicitudes</a>
                </li>

                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Gestionar de Artículos</span>
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
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Gestión de Artículos Entregados
                        </h1>
                        <p class="text-gray-600 text-sm sm:text-base">Visualice y gestione los artículos entregados</p>
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

                        <!-- Usuario -->
                        <div class="flex items-center p-2 sm:p-3 bg-purple-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center mr-2">
                                <i class="fas fa-user text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Usuario</p>
                                <p class="font-semibold text-gray-900 text-sm truncate">
                                    {{ auth()->user()->name ?? 'Administrador' }}</p>
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
                </div>

                <!-- Sección derecha con Código y Botón -->
                <div class="mt-4 lg:mt-0">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-3 sm:p-4 text-center shadow">
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


        @if ($articulos && $articulos->count() > 0)
            <!-- Panel de Control de Estados -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 border border-gray-200">
                <!-- Header con gradiente mejorado -->
                <div class="px-6 py-5 bg-blue-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Control de Estados</h2>
                                <p class="text-blue-100 text-sm mt-1">Gestiona el estado de uso de cada artículo
                                    entregado</p>
                            </div>
                        </div>
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-boxes mr-1"></i> {{ $articulos->count() }} Artículos
                        </span>
                    </div>
                </div>

                <!-- Lista de Artículos -->
                <div class="divide-y divide-gray-100" id="listaArticulos">
                    @foreach ($articulos as $index => $articulo)
                        @php
                            $estadoActual = $estadosArticulos[$articulo->idArticulos] ?? 'pendiente';
                            $clasesEstado = [
                                'usado' => 'bg-green-100 text-green-800 border-green-300',
                                'no_usado' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'pendiente' => 'bg-gray-100 text-gray-800 border-gray-300',
                            ];
                            $textoEstado = [
                                'usado' => 'Usado',
                                'no_usado' => 'No Usado',
                                'pendiente' => 'Pendiente',
                            ];
                            $iconosEstado = [
                                'usado' => 'fas fa-check-circle',
                                'no_usado' => 'fas fa-times-circle',
                                'pendiente' => 'fas fa-clock',
                            ];
                        @endphp

                        <div class="p-6 hover:bg-gray-50/50 transition-all duration-200"
                            data-articulo-id="{{ $articulo->idArticulos }}">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4">
                                <!-- Información del Artículo -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <!-- Ícono del artículo -->
                                        <div
                                            class="w-14 h-14 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-100">
                                            <i class="fas fa-box-open text-blue-600 text-xl"></i>
                                        </div>

                                        <!-- Detalles del artículo -->
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between">
                                                <h3 class="font-bold text-gray-900 text-lg mb-2">
                                                    {{ $articulo->nombre }}</h3>
                                            </div>

                                            <!-- Grid de información -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                                                <div class="flex items-center text-gray-700">
                                                    <div
                                                        class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-barcode text-gray-500"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500">Código</p>
                                                        <p class="font-medium">
                                                            {{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center text-gray-700">
                                                    <div
                                                        class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-tag text-gray-500"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500">Tipo</p>
                                                        <p class="font-medium">{{ $articulo->tipo_articulo }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center text-gray-700">
                                                    <div
                                                        class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-layer-group text-gray-500"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500">Cantidad</p>
                                                        <p class="font-medium">{{ $articulo->cantidad_solicitada }}
                                                            unidad(es)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado Actual -->
                                <div class="lg:text-right">
                                    <div class="inline-flex flex-col items-start lg:items-end gap-3">
                                        <!-- Badge Principal del Estado -->
                                        <div
                                            class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-200 shadow-sm">
                                            <!-- Texto del Estado -->
                                            <div class="flex flex-col">
                                                <span class="text-xs text-gray-500 font-medium mb-1">Estado
                                                    Actual</span>
                                                <span
                                                    class="text-sm font-semibold estado-articulo {{ $estadoActual === 'usado' ? 'text-green-700' : ($estadoActual === 'no_usado' ? 'text-blue-700' : 'text-gray-700') }}">

                                                    {{ $textoEstado[$estadoActual] }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Información de Fecha -->
                                        <div class="flex flex-col items-start lg:items-end gap-1">
                                            <!-- Badge de Fecha -->
                                            <div class="bg-gray-50 rounded-lg px-3 py-2 border border-gray-200">
                                                <div class="flex items-center gap-2">
                                                    <i class="far fa-calendar text-gray-400 text-xs"></i>
                                                    <span class="text-xs text-gray-600 fecha-actualizacion">
                                                        @if ($estadoActual === 'usado' && $articulo->fechaUsado)
                                                            <span
                                                                class="font-medium">{{ \Carbon\Carbon::parse($articulo->fechaUsado)->format('d/m/Y') }}</span>
                                                            <span
                                                                class="text-gray-500 ml-1">{{ \Carbon\Carbon::parse($articulo->fechaUsado)->format('H:i') }}</span>
                                                        @elseif($estadoActual === 'no_usado' && $articulo->fechaSinUsar)
                                                            <span
                                                                class="font-medium">{{ \Carbon\Carbon::parse($articulo->fechaSinUsar)->format('d/m/Y') }}</span>
                                                            <span
                                                                class="text-gray-500 ml-1">{{ \Carbon\Carbon::parse($articulo->fechaSinUsar)->format('H:i') }}</span>
                                                        @else
                                                            <span class="text-gray-500">Sin actualización</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selector de Estados -->
                            <div
                                class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-5 mt-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-semibold text-gray-800 flex items-center">
                                        <div
                                            class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-cog text-blue-600"></i>
                                        </div>
                                        Seleccionar Estado de Uso
                                    </h4>
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-user-circle mr-1"></i> {{ auth()->user()->name }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <!-- Botón Usado -->
                                    <button type="button"
                                        class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 btn-usado 
        @if ($estadoActual === 'usado') opacity-50 cursor-not-allowed pointer-events-none 
        @elseif ($estadoActual === 'no_usado') opacity-50 cursor-not-allowed pointer-events-none @endif"
                                        data-articulo-id="{{ $articulo->idArticulos }}"
                                        data-articulo-codigo="{{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}"
                                        @if ($estadoActual === 'usado' || $estadoActual === 'no_usado') disabled @endif
                                        @if ($estadoActual === 'usado' || $estadoActual === 'no_usado') onclick="return false;" @endif>
                                        <i class="fas fa-check-circle mr-2 text-lg"></i>
                                        <span class="font-semibold">
                                            @if ($estadoActual === 'usado')
                                                <i class="fas fa-check-double mr-1"></i> Ya Marcado como Usado
                                            @elseif ($estadoActual === 'no_usado')
                                                <i class="fas fa-lock mr-1"></i> Bloqueado (Artículo No Usado)
                                            @else
                                                Marcar como Usado
                                            @endif
                                        </span>
                                    </button>

                                    <!-- Botón No Usado -->
                                    <button type="button"
                                        class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 btn-no-usado 
        @if ($estadoActual === 'no_usado') opacity-50 cursor-not-allowed pointer-events-none 
        @elseif ($estadoActual === 'usado') opacity-50 cursor-not-allowed pointer-events-none @endif"
                                        data-articulo-id="{{ $articulo->idArticulos }}"
                                        data-articulo-codigo="{{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}"
                                        @if ($estadoActual === 'no_usado' || $estadoActual === 'usado') disabled @endif
                                        @if ($estadoActual === 'no_usado' || $estadoActual === 'usado') onclick="return false;" @endif>
                                        <i class="fas fa-times-circle mr-2 text-lg"></i>
                                        <span class="font-semibold">
                                            @if ($estadoActual === 'no_usado')
                                                <i class="fas fa-check-double mr-1"></i> Ya Marcado como No Usado
                                            @elseif ($estadoActual === 'usado')
                                                <i class="fas fa-lock mr-1"></i> Bloqueado (Artículo Usado)
                                            @else
                                                Marcar como No Usado
                                            @endif
                                        </span>
                                    </button>
                                </div>

                                <!-- Información adicional -->
                                <div class="mt-4 text-xs text-gray-500 flex items-center justify-between">
                                    <span>
                                        <i class="fas fa-info-circle mr-1"></i> Cambio registrado en tiempo real
                                    </span>
                                </div>
                            </div>

                            @if ($articulo->observacion)
                                <div class="mt-4 p-4 bg-orange-50 rounded-xl border border-orange-200">
                                    <div class="flex items-start">
                                        <div
                                            class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3 mt-0.5">
                                            <i class="fas fa-comment-alt text-orange-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-orange-700 text-sm flex items-center">
                                                Observación del Artículo
                                            </h4>
                                            <p class="text-orange-600 text-sm mt-1">{{ $articulo->observacion }}</p>
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
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border border-green-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-semibold flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> Usados
                            </p>
                            <p class="text-3xl font-bold text-green-700 mt-2" id="contadorUsados">
                                {{ $contadores['usados'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-green-200">
                        <p class="text-xs text-green-600">
                            <i class="fas fa-chart-line mr-1"></i>
                            {{ number_format(($contadores['usados'] / $articulos->count()) * 100, 1) }}% del total
                        </p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-semibold flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> No Usados
                            </p>
                            <p class="text-3xl font-bold text-blue-700 mt-2" id="contadorNoUsados">
                                {{ $contadores['no_usados'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-times text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <p class="text-xs text-blue-600">
                            <i class="fas fa-chart-line mr-1"></i>
                            {{ number_format(($contadores['no_usados'] / $articulos->count()) * 100, 1) }}% del total
                        </p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold flex items-center">
                                <i class="fas fa-clock mr-2"></i> Pendientes
                            </p>
                            <p class="text-3xl font-bold text-gray-700 mt-2" id="contadorPendientes">
                                {{ $contadores['pendientes'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-gray-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-600">
                            <i class="fas fa-chart-line mr-1"></i>
                            {{ number_format(($contadores['pendientes'] / $articulos->count()) * 100, 1) }}% del total
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Mensaje cuando no hay artículos -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-white text-blue-600 rounded-full flex items-center justify-center font-bold shadow-md">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">No hay artículos procesados</h2>
                            <p class="text-white text-sm">No se encontraron artículos entregados para gestionar
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box-open text-yellow-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay artículos para gestionar</h3>
                    <p class="text-gray-600 mb-6">Esta solicitud no contiene artículos entregados que requieran gestión
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
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Marcar Artículo como Usado
                    </h3>
                    <button type="button" id="cerrarModal" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form id="formUsado" class="p-6">
                @csrf
                <input type="hidden" id="articulo_id" name="articulo_id">

                <!-- Información del Artículo -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Información del Artículo
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Código</label>
                            <p id="modalArticuloCodigo" class="text-gray-800 font-semibold"></p>
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Fecha de Uso *
                    </label>
                    <input type="datetime-local" id="fecha_uso" name="fecha_uso"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                        value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    <p class="text-xs text-gray-500 mt-1">Selecciona la fecha y hora en que se utilizó el artículo</p>
                </div>

                <!-- Observación -->
                <div class="mb-6">
                    <label for="observacion" class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Observación
                    </label>
                    <textarea id="observacion" name="observacion" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                        placeholder="Describe dónde y cómo se utilizó el artículo, o cualquier información relevante..." maxlength="500"></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Máximo 500 caracteres</p>
                        <span id="contadorCaracteres" class="text-xs text-gray-500">0/500</span>
                    </div>
                </div>

                <!-- Subida de Fotos -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Fotos del Artículo Usado
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition-colors cursor-pointer"
                        id="dropZone">
                        <input type="file" id="fotos" name="fotos[]" multiple accept="image/*"
                            class="hidden">
                        <div class="space-y-3">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-gray-600 font-medium">Haz clic para subir fotos</p>
                                <p class="text-gray-500 text-sm">o arrastra y suelta las imágenes aquí</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG, JPEG hasta 5MB cada una</p>
                        </div>
                    </div>
                    <div id="previewFotos" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                    <p class="text-xs text-gray-500 mt-2">Máximo 5 fotos. Muestra evidencia de dónde se utilizó el
                        artículo.</p>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelarModal"
                        class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="w-full sm:flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all font-semibold shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
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
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Devolver Artículo al Inventario
                    </h3>
                    <button type="button" id="cerrarModalNoUsado"
                        class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form id="formNoUsado" class="p-6">
                @csrf
                <input type="hidden" id="articulo_id_no_usado" name="articulo_id">

                <!-- Información del Artículo -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Información del Artículo a Devolver
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Código</label>
                            <p id="modalArticuloCodigoNoUsado" class="text-gray-800 font-semibold"></p>
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Observación de la Devolución
                    </label>
                    <textarea id="observacion_no_usado" name="observacion" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Describe el motivo de la devolución, estado del artículo, o cualquier información relevante..."
                        maxlength="500"></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Máximo 500 caracteres</p>
                        <span id="contadorCaracteresNoUsado" class="text-xs text-gray-500">0/500</span>
                    </div>
                </div>

                <!-- Subida de Fotos -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Fotos de la Devolución
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer"
                        id="dropZoneNoUsado">
                        <input type="file" id="fotos_no_usado" name="fotos[]" multiple accept="image/*"
                            class="hidden">
                        <div class="space-y-3">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-gray-600 font-medium">Haz clic para subir fotos</p>
                                <p class="text-gray-500 text-sm">o arrastra y suelta las imágenes aquí</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG, JPEG hasta 5MB cada una</p>
                        </div>
                    </div>
                    <div id="previewFotosNoUsado" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                    <p class="text-xs text-gray-500 mt-2">Máximo 5 fotos. Muestra evidencia del artículo devuelto.</p>
                </div>

                <!-- Información Importante -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-800">Información Importante</h4>
                            <ul class="text-blue-700 text-sm mt-1 space-y-1">
                                <li>• El artículo será devuelto al inventario automáticamente</li>
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
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
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
            let currentArticuloId = null;
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
                    if (this.disabled) {
                        toastr.info('Este artículo ya ha sido marcado como usado');
                        return;
                    }

                    const articuloId = this.getAttribute('data-articulo-id');
                    const articuloCodigo = this.getAttribute('data-articulo-codigo');

                    abrirModalUsado(articuloId, articuloCodigo);
                });
            });

            // Función para abrir el modal Usado
            function abrirModalUsado(articuloId, codigo) {
                currentArticuloId = articuloId;

                // Llenar información del artículo
                document.getElementById('modalArticuloCodigo').textContent = codigo;
                document.getElementById('articulo_id').value = articuloId;

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

            // Cerrar modal Usado - Botones
            document.getElementById('cerrarModal').addEventListener('click', cerrarModal);
            document.getElementById('cancelarModal').addEventListener('click', cerrarModal);

            // Cerrar modal al hacer clic fuera (en el fondo oscuro)
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    cerrarModal();
                }
            });

            // Prevenir cierre al hacer clic dentro del contenido del modal
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
                    if (longitud === 501) {
                        toastr.warning('Has excedido el límite de 500 caracteres');
                    }
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
                        toastr.error('Solo se permiten archivos de imagen (JPG, PNG, JPEG)');
                        return;
                    }

                    if (archivo.size > 5 * 1024 * 1024) {
                        toastr.error('Las imágenes deben ser menores a 5MB');
                        return;
                    }

                    archivosSeleccionados.push(archivo);
                }

                if (nuevosArchivos.length > 0) {
                    toastr.success(`${nuevosArchivos.length} foto(s) agregada(s) correctamente`);
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
                    const archivoEliminado = archivosSeleccionados[index];
                    archivosSeleccionados.splice(index, 1);
                    actualizarVistaPrevia();
                    toastr.info('Foto eliminada');
                }
            });

            // Envío del formulario Usado
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!currentArticuloId) {
                    toastr.error('Error: ID del artículo no válido');
                    return;
                }

                // Validar fecha
                const fechaUso = document.getElementById('fecha_uso').value;
                if (!fechaUso) {
                    toastr.error('Por favor, selecciona una fecha de uso');
                    return;
                }

                // Mostrar indicador de carga
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
                submitBtn.disabled = true;

                const formData = new FormData();
                formData.append('articulo_id', currentArticuloId);
                formData.append('fecha_uso', fechaUso);
                formData.append('observacion', document.getElementById('observacion').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));

                // Agregar archivos
                archivosSeleccionados.forEach(archivo => {
                    formData.append('fotos[]', archivo);
                });

                try {
                    const response = await fetch(
                        `/solicitudarticulo/{{ $solicitud->idsolicitudesordenes }}/marcar-usado`, {
                            method: 'POST',
                            body: formData
                        });

                    const data = await response.json();

                    // Restaurar botón
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    if (data.success) {
                        toastr.success(data.message);
                        cerrarModal();
                        // Actualizar la UI
                        actualizarEstadoArticulo(currentArticuloId, 'usado');
                    } else {
                        toastr.error('Error: ' + (data.message || 'No se pudo completar la operación'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // Restaurar botón
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    toastr.error(
                        'Error de conexión. Por favor, verifica tu conexión a internet e intenta nuevamente'
                    );
                }
            });

            // ========== MODAL NO USADO ==========
            // Event listeners para botones "Marcar como No Usado"
            document.querySelectorAll('.btn-no-usado').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) {
                        toastr.info('Este artículo ya ha sido marcado como devuelto');
                        return;
                    }

                    const articuloId = this.getAttribute('data-articulo-id');
                    const articuloCodigo = this.getAttribute('data-articulo-codigo');

                    abrirModalNoUsado(articuloId, articuloCodigo);
                });
            });

            // Función para abrir el modal de No Usado
            function abrirModalNoUsado(articuloId, codigo) {
                currentArticuloId = articuloId;

                // Llenar información del artículo
                document.getElementById('modalArticuloCodigoNoUsado').textContent = codigo;
                document.getElementById('articulo_id_no_usado').value = articuloId;

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

            // Cerrar modal No Usado - Botones
            document.getElementById('cerrarModalNoUsado')?.addEventListener('click', cerrarModalNoUsado);
            document.getElementById('cancelarModalNoUsado')?.addEventListener('click', cerrarModalNoUsado);

            // Cerrar modal al hacer clic fuera (en el fondo oscuro)
            if (modalNoUsado) {
                modalNoUsado.addEventListener('click', function(e) {
                    if (e.target === modalNoUsado) {
                        cerrarModalNoUsado();
                    }
                });
            }

            // Prevenir cierre al hacer clic dentro del contenido del modal
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
                    if (longitud === 501) {
                        toastr.warning('Has excedido el límite de 500 caracteres');
                    }
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
                        toastr.error('Solo se permiten archivos de imagen (JPG, PNG, JPEG)');
                        return;
                    }

                    if (archivo.size > 5 * 1024 * 1024) {
                        toastr.error('Las imágenes deben ser menores a 5MB');
                        return;
                    }

                    archivosSeleccionadosNoUsado.push(archivo);
                }

                if (nuevosArchivos.length > 0) {
                    toastr.success(`${nuevosArchivos.length} foto(s) agregada(s) correctamente`);
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
                    toastr.info('Foto eliminada');
                }
            });

            // Envío del formulario No Usado
            formNoUsado?.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!currentArticuloId) {
                    toastr.error('Error: ID del artículo no válido');
                    return;
                }

                // Validar fecha
                const fechaDevolucion = document.getElementById('fecha_devolucion').value;
                if (!fechaDevolucion) {
                    toastr.error('Por favor, selecciona una fecha de devolución');
                    return;
                }

                // Mostrar indicador de carga
                const submitBtn = formNoUsado.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
                submitBtn.disabled = true;

                const formData = new FormData();
                formData.append('articulo_id', currentArticuloId);
                formData.append('fecha_devolucion', fechaDevolucion);
                formData.append('observacion', document.getElementById('observacion_no_usado').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));

                // Agregar archivos
                archivosSeleccionadosNoUsado.forEach(archivo => {
                    formData.append('fotos[]', archivo);
                });

                try {
                    const response = await fetch(
                        `/solicitudarticulo/{{ $solicitud->idsolicitudesordenes }}/marcar-no-usado`, {
                            method: 'POST',
                            body: formData
                        });

                    const data = await response.json();

                    // Restaurar botón
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    if (data.success) {
                        toastr.success(data.message);
                        cerrarModalNoUsado();
                        // Actualizar la UI
                        actualizarEstadoArticuloNoUsado(currentArticuloId, 'no_usado');
                    } else {
                        toastr.error('Error: ' + (data.message || 'No se pudo completar la operación'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // Restaurar botón
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    toastr.error(
                        'Error de conexión. Por favor, verifica tu conexión a internet e intenta nuevamente'
                    );
                }
            });

            // Función para actualizar estado del artículo en la UI para Usado
            function actualizarEstadoArticulo(articuloId, nuevoEstado) {
                const articuloElement = document.querySelector(`[data-articulo-id="${articuloId}"]`);
                if (!articuloElement) {
                    toastr.error('No se pudo encontrar el artículo en la lista');
                    return;
                }

                const estadoElement = articuloElement.querySelector('.estado-articulo');
                const fechaElement = articuloElement.querySelector('.fecha-actualizacion');
                const btnUsado = articuloElement.querySelector('.btn-usado');
                const btnNoUsado = articuloElement.querySelector('.btn-no-usado');

                if (nuevoEstado === 'usado') {
                    contadorUsados++;
                    contadorPendientes--;

                    // Actualizar estado visual
                    estadoElement.className = 'text-sm font-semibold estado-articulo text-green-700';
                    estadoElement.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Usado';

                    // Deshabilitar botón USADO y habilitar botón NO USADO
                    btnUsado.disabled = true;
                    btnUsado.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    btnUsado.innerHTML =
                        '<i class="fas fa-check-circle mr-2 text-lg"></i>' +
                        '<span class="font-semibold">' +
                        '<i class="fas fa-check-double mr-1"></i> Ya Marcado como Usado' +
                        '</span>';

                    // Habilitar botón NO USADO si no está ya en estado no_usado
                    const estadoActual = articuloElement.getAttribute('data-estado-actual') || 'pendiente';
                    if (estadoActual !== 'no_usado') {
                        btnNoUsado.disabled = false;
                        btnNoUsado.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                        btnNoUsado.innerHTML =
                            '<i class="fas fa-times-circle mr-2 text-lg"></i>' +
                            '<span class="font-semibold">Marcar como No Usado</span>';
                    }

                    // Actualizar fecha
                    const ahora = new Date();
                    const fechaFormateada =
                        `${ahora.getDate().toString().padStart(2, '0')}/${(ahora.getMonth() + 1).toString().padStart(2, '0')}/${ahora.getFullYear()}`;
                    const horaFormateada =
                        `${ahora.getHours().toString().padStart(2, '0')}:${ahora.getMinutes().toString().padStart(2, '0')}`;
                    fechaElement.innerHTML =
                        `<span class="font-medium">${fechaFormateada}</span>` +
                        `<span class="text-gray-500 ml-1">${horaFormateada}</span>`;

                    // Actualizar contadores visuales
                    actualizarContadores();

                    // Actualizar atributo de estado en el elemento
                    articuloElement.setAttribute('data-estado-actual', 'usado');

                    // Notificación adicional
                    toastr.success('Estado actualizado correctamente en el sistema');
                }
            }

                      // Función para actualizar estado del artículo en la UI para Usado
            function actualizarEstadoArticulo(articuloId, nuevoEstado) {
                const articuloElement = document.querySelector(`[data-articulo-id="${articuloId}"]`);
                if (!articuloElement) {
                    toastr.error('No se pudo encontrar el artículo en la lista');
                    return;
                }

                const estadoElement = articuloElement.querySelector('.estado-articulo');
                const fechaElement = articuloElement.querySelector('.fecha-actualizacion');
                const btnUsado = articuloElement.querySelector('.btn-usado');
                const btnNoUsado = articuloElement.querySelector('.btn-no-usado');

                if (nuevoEstado === 'usado') {
                    contadorUsados++;
                    contadorPendientes--;

                    // Actualizar estado visual
                    estadoElement.className = 'text-sm font-semibold estado-articulo text-green-700';
                    estadoElement.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Usado';

                    // Deshabilitar botón USADO y habilitar botón NO USADO
                    btnUsado.disabled = true;
                    btnUsado.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    btnUsado.innerHTML =
                        '<i class="fas fa-check-circle mr-2 text-lg"></i>' +
                        '<span class="font-semibold">' +
                        '<i class="fas fa-check-double mr-1"></i> Ya Marcado como Usado' +
                        '</span>';

                    // Habilitar botón NO USADO si no está ya en estado no_usado
                    const estadoActual = articuloElement.getAttribute('data-estado-actual') || 'pendiente';
                    if (estadoActual !== 'no_usado') {
                        btnNoUsado.disabled = false;
                        btnNoUsado.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                        btnNoUsado.innerHTML =
                            '<i class="fas fa-times-circle mr-2 text-lg"></i>' +
                            '<span class="font-semibold">Marcar como No Usado</span>';
                    }

                    // Actualizar fecha
                    const ahora = new Date();
                    const fechaFormateada =
                        `${ahora.getDate().toString().padStart(2, '0')}/${(ahora.getMonth() + 1).toString().padStart(2, '0')}/${ahora.getFullYear()}`;
                    const horaFormateada =
                        `${ahora.getHours().toString().padStart(2, '0')}:${ahora.getMinutes().toString().padStart(2, '0')}`;
                    fechaElement.innerHTML =
                        `<span class="font-medium">${fechaFormateada}</span>` +
                        `<span class="text-gray-500 ml-1">${horaFormateada}</span>`;

                    // Actualizar contadores visuales
                    actualizarContadores();

                    // Actualizar atributo de estado en el elemento
                    articuloElement.setAttribute('data-estado-actual', 'usado');

                    // Notificación adicional
                    toastr.success('Estado actualizado correctamente en el sistema');
                }
            }

            // Función para actualizar estado del artículo en la UI para No Usado
            function actualizarEstadoArticuloNoUsado(articuloId, nuevoEstado) {
                const articuloElement = document.querySelector(`[data-articulo-id="${articuloId}"]`);
                if (!articuloElement) {
                    toastr.error('No se pudo encontrar el artículo en la lista');
                    return;
                }

                const estadoElement = articuloElement.querySelector('.estado-articulo');
                const fechaElement = articuloElement.querySelector('.fecha-actualizacion');
                const btnUsado = articuloElement.querySelector('.btn-usado');
                const btnNoUsado = articuloElement.querySelector('.btn-no-usado');

                if (nuevoEstado === 'no_usado') {
                    contadorNoUsados++;
                    contadorPendientes--;

                    // Actualizar estado visual
                    estadoElement.className = 'text-sm font-semibold estado-articulo text-blue-700';
                    estadoElement.innerHTML = '<i class="fas fa-times-circle mr-2"></i>No Usado';

                    // Deshabilitar botón NO USADO y habilitar botón USADO
                    btnNoUsado.disabled = true;
                    btnNoUsado.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    btnNoUsado.innerHTML =
                        '<i class="fas fa-times-circle mr-2 text-lg"></i>' +
                        '<span class="font-semibold">' +
                        '<i class="fas fa-check-double mr-1"></i> Ya Marcado como No Usado' +
                        '</span>';

                    // Habilitar botón USADO si no está ya en estado usado
                    const estadoActual = articuloElement.getAttribute('data-estado-actual') || 'pendiente';
                    if (estadoActual !== 'usado') {
                        btnUsado.disabled = false;
                        btnUsado.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                        btnUsado.innerHTML =
                            '<i class="fas fa-check-circle mr-2 text-lg"></i>' +
                            '<span class="font-semibold">Marcar como Usado</span>';
                    }

                    // Actualizar fecha
                    const ahora = new Date();
                    const fechaFormateada =
                        `${ahora.getDate().toString().padStart(2, '0')}/${(ahora.getMonth() + 1).toString().padStart(2, '0')}/${ahora.getFullYear()}`;
                    const horaFormateada =
                        `${ahora.getHours().toString().padStart(2, '0')}:${ahora.getMinutes().toString().padStart(2, '0')}`;
                    fechaElement.innerHTML =
                        `<span class="font-medium">${fechaFormateada}</span>` +
                        `<span class="text-gray-500 ml-1">${horaFormateada}</span>`;

                    // Actualizar contadores visuales
                    actualizarContadores();

                    // Actualizar atributo de estado en el elemento
                    articuloElement.setAttribute('data-estado-actual', 'no_usado');

                    // Notificación adicional
                    toastr.success('Artículo marcado como no usado correctamente');
                }
            }

            // Inicializar contadores
            actualizarContadores();

            // Configuración opcional de Toastr (puedes personalizar)
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000"
            };
        });
    </script>
</x-layout.default>
