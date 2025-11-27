<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header Mejorado -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión de Artículos Entregados</h1>
                <div class="flex flex-wrap items-center gap-4">
                    <p class="text-lg text-gray-600 bg-blue-50 px-3 py-1 rounded-full">
                        📦 Código: <span class="font-semibold" id="codigoSolicitud">{{ $solicitud->codigo }}</span>
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                        <span class="text-sm text-blue-600">Artículo entregado</span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('solicitudarticulo.index') }}" 
                   class="flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Tarjeta de Información de Solicitud Mejorada -->
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg p-6 mb-8 border border-blue-100">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="text-center lg:text-left">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto lg:mx-0 mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700 mb-1">Solicitante</h3>
                    <p class="text-gray-900 font-medium" id="nombreSolicitante">{{ $solicitud->nombre_solicitante }}</p>
                </div>
                
                <div class="text-center lg:text-left">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto lg:mx-0 mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700 mb-1">Estado</h3>
                    @php
                        $estadoClases = [
                            'aprobada' => 'bg-green-100 text-green-800 border-green-200',
                            'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'rechazada' => 'bg-red-100 text-red-800 border-red-200'
                        ];
                        $estadoTexto = [
                            'aprobada' => 'Aprobada',
                            'pendiente' => 'Pendiente',
                            'rechazada' => 'Rechazada'
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $estadoClases[$solicitud->estado] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}" id="estadoSolicitud">
                        {{ $estadoTexto[$solicitud->estado] ?? ucfirst($solicitud->estado) }}
                    </span>
                </div>
                
                <div class="text-center lg:text-left">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto lg:mx-0 mb-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700 mb-1">Urgencia</h3>
                    @php
                        $urgenciaClases = [
                            'alta' => 'bg-red-100 text-red-800 border-red-200',
                            'media' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'baja' => 'bg-green-100 text-green-800 border-green-200'
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $urgenciaClases[$solicitud->niveldeurgencia] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}" id="urgenciaSolicitud">
                        {{ ucfirst($solicitud->niveldeurgencia) }}
                    </span>
                </div>

                <div class="text-center lg:text-left">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto lg:mx-0 mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700 mb-1">Fecha Entrega</h3>
                    <p class="text-gray-900 font-medium" id="fechaEntrega">
                        @if($solicitud->fecharequerida)
                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d/m/Y') }}
                        @else
                            {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
            </div>
            
            @if($solicitud->observaciones)
            <div class="mt-6 p-4 bg-white rounded-xl border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Observaciones
                </h3>
                <p class="text-gray-700" id="observacionesSolicitud">{{ $solicitud->observaciones }}</p>
            </div>
            @endif
        </div>

        @if($articulos && $articulos->count() > 0)
        <!-- Panel de Control de Estados -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Control de Estados del Artículo
                </h2>
                <p class="text-blue-100 text-sm mt-1">Selecciona el estado de uso para cada artículo entregado</p>
            </div>

            <div class="divide-y divide-gray-100" id="listaArticulos">
                @foreach($articulos as $articulo)
                @php
                    $estadoActual = $estadosArticulos[$articulo->idArticulos] ?? 'pendiente';
                    $clasesEstado = [
                        'usado' => 'bg-green-100 text-green-800 border-green-200',
                        'no_usado' => 'bg-blue-100 text-blue-800 border-blue-200', 
                        'pendiente' => 'bg-gray-100 text-gray-800 border-gray-200'
                    ];
                    $textoEstado = [
                        'usado' => '✅ Usado',
                        'no_usado' => '❌ No Usado',
                        'pendiente' => '⏳ Pendiente'
                    ];
                @endphp
                <div class="p-6 hover:bg-gray-50 transition-all duration-300" data-articulo-id="{{ $articulo->idArticulos }}">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4">
                        <!-- Información del Artículo -->
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $articulo->nombre }}</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                            <span><strong>Código:</strong> {{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <span><strong>Tipo:</strong> {{ $articulo->tipo_articulo }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span><strong>Cantidad:</strong> {{ $articulo->cantidad_solicitada }} unidad(es)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado Actual -->
                        <div class="lg:text-right">
                            <div class="inline-flex flex-col items-end gap-2">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full border estado-articulo {{ $clasesEstado[$estadoActual] }}">
                                    {{ $textoEstado[$estadoActual] }}
                                </span>
                                <span class="text-xs text-gray-500 fecha-actualizacion">
                                    @if($estadoActual === 'usado' && $articulo->fechaUsado)
                                        {{ \Carbon\Carbon::parse($articulo->fechaUsado)->format('d/m/Y H:i') }}
                                    @elseif($estadoActual === 'no_usado' && $articulo->fechaSinUsar)
                                        {{ \Carbon\Carbon::parse($articulo->fechaSinUsar)->format('d/m/Y H:i') }}
                                    @else
                                        Sin definir
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Selector de Estados -->
                    <div class="bg-gray-50 rounded-xl p-4 mt-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Seleccionar Estado de Uso
                        </h4>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Botón Usado -->
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 btn-usado @if($estadoActual === 'usado') opacity-50 cursor-not-allowed @endif"
                                    data-articulo-id="{{ $articulo->idArticulos }}"
                                    data-articulo-codigo="{{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}"
                                    @if($estadoActual === 'usado') disabled @endif>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="font-semibold">
                                    @if($estadoActual === 'usado')
                                        ✅ Ya Marcado como Usado
                                    @else
                                        Marcar como Usado
                                    @endif
                                </span>
                            </button>

                            <!-- Botón No Usado -->
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 btn-no-usado @if($estadoActual === 'no_usado') opacity-50 cursor-not-allowed @endif"
                                    data-articulo-id="{{ $articulo->idArticulos }}"
                                    data-articulo-codigo="{{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}"
                                    @if($estadoActual === 'no_usado') disabled @endif>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="font-semibold">
                                    @if($estadoActual === 'no_usado')
                                        ❌ Ya Marcado como No Usado
                                    @else
                                        Marcar como No Usado
                                    @endif
                                </span>
                            </button>
                        </div>

                        <!-- Información de permisos -->
                        <div class="mt-3 text-xs text-gray-500 flex items-center justify-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="infoPermisos">
                                Usuario: {{ auth()->user()->name }}
                            </span>
                        </div>
                    </div>

                    @if($articulo->observacion)
                    <div class="mt-4 p-3 bg-orange-50 rounded-xl border border-orange-200">
                        <h4 class="font-medium text-orange-700 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Observación del Artículo
                        </h4>
                        <p class="text-orange-600 text-sm mt-1">{{ $articulo->observacion }}</p>
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
                        <p class="text-green-600 text-sm font-semibold">Usados</p>
                        <p class="text-3xl font-bold text-green-700 mt-2" id="contadorUsados">{{ $contadores['usados'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-semibold">No Usados</p>
                        <p class="text-3xl font-bold text-blue-700 mt-2" id="contadorNoUsados">{{ $contadores['no_usados'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Pendientes</p>
                        <p class="text-3xl font-bold text-gray-700 mt-2" id="contadorPendientes">{{ $contadores['pendientes'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @else
        <!-- Mensaje cuando no hay artículos -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-8">
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">No hay artículos procesados</h2>
                        <p class="text-yellow-100 text-sm">No se encontraron artículos entregados para gestionar</p>
                    </div>
                </div>
            </div>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay artículos para gestionar</h3>
                <p class="text-gray-600 mb-6">Esta solicitud no contiene artículos entregados que requieran gestión de estados.</p>
                <a href="{{ route('solicitudarticulo.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al Listado
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal para Marcar como Usado -->
    <div id="modalUsado" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Marcar Artículo como Usado
                    </h3>
                    <button type="button" id="cerrarModal" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Fecha de Uso *
                    </label>
                    <input type="datetime-local" 
                           id="fecha_uso" 
                           name="fecha_uso" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           value="{{ now()->format('Y-m-d\TH:i') }}"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Selecciona la fecha y hora en que se utilizó el artículo</p>
                </div>

                <!-- Observación -->
                <div class="mb-6">
                    <label for="observacion" class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Observación
                    </label>
                    <textarea 
                        id="observacion" 
                        name="observacion" 
                        rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                        placeholder="Describe dónde y cómo se utilizó el artículo, o cualquier información relevante..."
                        maxlength="500"></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Máximo 500 caracteres</p>
                        <span id="contadorCaracteres" class="text-xs text-gray-500">0/500</span>
                    </div>
                </div>

                <!-- Subida de Fotos -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Fotos del Artículo Usado
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition-colors" id="dropZone">
                        <input type="file" 
                               id="fotos" 
                               name="fotos[]" 
                               multiple 
                               accept="image/*"
                               class="hidden">
                        <div class="space-y-3">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-gray-600 font-medium">Haz clic para subir fotos</p>
                                <p class="text-gray-500 text-sm">o arrastra y suelta las imágenes aquí</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG, JPEG hasta 5MB cada una</p>
                        </div>
                    </div>
                    <div id="previewFotos" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                    <p class="text-xs text-gray-500 mt-2">Máximo 5 fotos. Muestra evidencia de dónde se utilizó el artículo.</p>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelarModal" class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all font-semibold shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirmar como Usado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Marcar como No Usado -->
    <div id="modalNoUsado" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Devolver Artículo al Inventario
                    </h3>
                    <button type="button" id="cerrarModalNoUsado" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Fecha de Devolución *
                    </label>
                    <input type="datetime-local" 
                           id="fecha_devolucion" 
                           name="fecha_devolucion" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           value="{{ now()->format('Y-m-d\TH:i') }}"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Selecciona la fecha y hora de la devolución al inventario</p>
                </div>

                <!-- Observación -->
                <div class="mb-6">
                    <label for="observacion_no_usado" class="block text-gray-700 font-semibold mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Observación de la Devolución
                    </label>
                    <textarea 
                        id="observacion_no_usado" 
                        name="observacion" 
                        rows="4" 
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
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Fotos de la Devolución
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors" id="dropZoneNoUsado">
                        <input type="file" 
                               id="fotos_no_usado" 
                               name="fotos[]" 
                               multiple 
                               accept="image/*"
                               class="hidden">
                        <div class="space-y-3">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <button type="button" id="cancelarModalNoUsado" class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all font-semibold shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Confirmar Devolución
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts para la funcionalidad del front -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables para modal Usado
            const modal = document.getElementById('modalUsado');
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
                
                // Mostrar modal
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Cerrar modal Usado
            document.getElementById('cerrarModal').addEventListener('click', cerrarModal);
            document.getElementById('cancelarModal').addEventListener('click', cerrarModal);

            function cerrarModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
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
                    mostrarNotificacion('Máximo 5 fotos permitidas', 'error');
                    return;
                }
                
                // Validar tipo y tamaño
                for (const archivo of nuevosArchivos) {
                    if (!archivo.type.startsWith('image/')) {
                        mostrarNotificacion('Solo se permiten archivos de imagen', 'error');
                        return;
                    }
                    
                    if (archivo.size > 5 * 1024 * 1024) {
                        mostrarNotificacion('Las imágenes deben ser menores a 5MB', 'error');
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
                            <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors" data-index="${index}">
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
                
                if (!currentArticuloId) return;
                
                const formData = new FormData();
                formData.append('articulo_id', currentArticuloId);
                formData.append('fecha_uso', document.getElementById('fecha_uso').value);
                formData.append('observacion', document.getElementById('observacion').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                // Agregar archivos
                archivosSeleccionados.forEach(archivo => {
                    formData.append('fotos[]', archivo);
                });
                
                try {
                    const response = await fetch(`/solicitudarticulo/{{ $solicitud->idsolicitudesordenes }}/marcar-usado`, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        mostrarNotificacion(data.message, 'success');
                        cerrarModal();
                        // Actualizar la UI
                        actualizarEstadoArticulo(currentArticuloId, 'usado');
                    } else {
                        mostrarNotificacion('Error: ' + data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    mostrarNotificacion('Error de conexión', 'error');
                }
            });

            // ========== MODAL NO USADO ==========
            // Event listeners para botones "Marcar como No Usado"
            document.querySelectorAll('.btn-no-usado').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) return;
                    
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
                
                // Mostrar modal
                modalNoUsado.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Cerrar modal No Usado
            document.getElementById('cerrarModalNoUsado').addEventListener('click', cerrarModalNoUsado);
            document.getElementById('cancelarModalNoUsado').addEventListener('click', cerrarModalNoUsado);

            function cerrarModalNoUsado() {
                modalNoUsado.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Contador de caracteres para observación No Usado
            observacionTextareaNoUsado.addEventListener('input', function() {
                const longitud = this.value.length;
                contadorCaracteresNoUsado.textContent = `${longitud}/500`;
                
                if (longitud > 500) {
                    contadorCaracteresNoUsado.classList.add('text-red-500');
                } else {
                    contadorCaracteresNoUsado.classList.remove('text-red-500');
                }
            });

            // Funcionalidad de subida de archivos para No Usado
            dropZoneNoUsado.addEventListener('click', () => fileInputNoUsado.click());

            dropZoneNoUsado.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZoneNoUsado.classList.add('border-blue-400', 'bg-blue-50');
            });

            dropZoneNoUsado.addEventListener('dragleave', () => {
                dropZoneNoUsado.classList.remove('border-blue-400', 'bg-blue-50');
            });

            dropZoneNoUsado.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZoneNoUsado.classList.remove('border-blue-400', 'bg-blue-50');
                
                if (e.dataTransfer.files.length > 0) {
                    manejarArchivosNoUsado(e.dataTransfer.files);
                }
            });

            fileInputNoUsado.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    manejarArchivosNoUsado(e.target.files);
                }
            });

            function manejarArchivosNoUsado(archivos) {
                const nuevosArchivos = Array.from(archivos);
                
                // Validar cantidad máxima
                if (archivosSeleccionadosNoUsado.length + nuevosArchivos.length > 5) {
                    mostrarNotificacion('Máximo 5 fotos permitidas', 'error');
                    return;
                }
                
                // Validar tipo y tamaño
                for (const archivo of nuevosArchivos) {
                    if (!archivo.type.startsWith('image/')) {
                        mostrarNotificacion('Solo se permiten archivos de imagen', 'error');
                        return;
                    }
                    
                    if (archivo.size > 5 * 1024 * 1024) {
                        mostrarNotificacion('Las imágenes deben ser menores a 5MB', 'error');
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
                            <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors" data-index="${index}">
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
            previewFotosNoUsado.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    const index = parseInt(e.target.getAttribute('data-index'));
                    archivosSeleccionadosNoUsado.splice(index, 1);
                    actualizarVistaPreviaNoUsado();
                }
            });

            // Envío del formulario No Usado
            formNoUsado.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!currentArticuloId) return;
                
                const formData = new FormData();
                formData.append('articulo_id', currentArticuloId);
                formData.append('fecha_devolucion', document.getElementById('fecha_devolucion').value);
                formData.append('observacion', document.getElementById('observacion_no_usado').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                // Agregar archivos
                archivosSeleccionadosNoUsado.forEach(archivo => {
                    formData.append('fotos[]', archivo);
                });
                
                try {
                    const response = await fetch(`/solicitudarticulo/{{ $solicitud->idsolicitudesordenes }}/marcar-no-usado`, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        mostrarNotificacion(data.message, 'success');
                        cerrarModalNoUsado();
                        // Actualizar la UI
                        actualizarEstadoArticuloNoUsado(currentArticuloId, 'no_usado');
                    } else {
                        mostrarNotificacion('Error: ' + data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    mostrarNotificacion('Error de conexión', 'error');
                }
            });

            // ========== FUNCIONES COMUNES ==========
            // Función para actualizar estado del artículo en la UI para Usado
            function actualizarEstadoArticulo(articuloId, nuevoEstado) {
                const articuloElement = document.querySelector(`[data-articulo-id="${articuloId}"]`);
                const estadoElement = articuloElement.querySelector('.estado-articulo');
                const fechaElement = articuloElement.querySelector('.fecha-actualizacion');
                const btnUsado = articuloElement.querySelector('.btn-usado');
                const btnNoUsado = articuloElement.querySelector('.btn-no-usado');
                
                if (nuevoEstado === 'usado') {
                    contadorUsados++;
                    contadorPendientes--;
                    estadoElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-200 estado-articulo';
                    estadoElement.textContent = '✅ Usado';
                    
                    // Deshabilitar botones
                    btnUsado.disabled = true;
                    btnUsado.classList.add('opacity-50', 'cursor-not-allowed');
                    btnUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="font-semibold">✅ Ya Marcado como Usado</span>';
                    
                    btnNoUsado.disabled = false;
                    btnNoUsado.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnNoUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="font-semibold">Marcar como No Usado</span>';
                    
                    // Actualizar fecha
                    const ahora = new Date();
                    fechaElement.textContent = `Actualizado: ${ahora.toLocaleDateString()} ${ahora.toLocaleTimeString()}`;
                    
                    // Actualizar contadores visuales
                    actualizarContadores();
                }
            }

            // Función para actualizar estado del artículo en la UI para No Usado
            function actualizarEstadoArticuloNoUsado(articuloId, nuevoEstado) {
                const articuloElement = document.querySelector(`[data-articulo-id="${articuloId}"]`);
                const estadoElement = articuloElement.querySelector('.estado-articulo');
                const fechaElement = articuloElement.querySelector('.fecha-actualizacion');
                const btnUsado = articuloElement.querySelector('.btn-usado');
                const btnNoUsado = articuloElement.querySelector('.btn-no-usado');
                
                if (nuevoEstado === 'no_usado') {
                    contadorNoUsados++;
                    contadorPendientes--;
                    estadoElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200 estado-articulo';
                    estadoElement.textContent = '❌ No Usado';
                    
                    // Deshabilitar botones
                    btnNoUsado.disabled = true;
                    btnNoUsado.classList.add('opacity-50', 'cursor-not-allowed');
                    btnNoUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="font-semibold">❌ Ya Marcado como No Usado</span>';
                    
                    btnUsado.disabled = false;
                    btnUsado.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="font-semibold">Marcar como Usado</span>';
                    
                    // Actualizar fecha
                    const ahora = new Date();
                    fechaElement.textContent = `Actualizado: ${ahora.toLocaleDateString()} ${ahora.toLocaleTimeString()}`;
                    
                    // Actualizar contadores visuales
                    actualizarContadores();
                }
            }

            // Función para mostrar notificaciones
            function mostrarNotificacion(mensaje, tipo = 'success') {
                const notificacion = document.createElement('div');
                const bgColor = tipo === 'success' ? 'bg-green-500' : 'bg-red-500';
                notificacion.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50`;
                notificacion.textContent = mensaje;
                
                document.body.appendChild(notificacion);
                
                setTimeout(() => {
                    notificacion.style.transform = 'translateX(0)';
                }, 100);
                
                setTimeout(() => {
                    notificacion.style.transform = 'translateX-full';
                    setTimeout(() => {
                        document.body.removeChild(notificacion);
                    }, 300);
                }, 3000);
            }

            // Efectos hover mejorados
            const buttons = document.querySelectorAll('button:not(:disabled)');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    if (!this.disabled) {
                        this.style.transform = 'translateY(-2px)';
                    }
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Efecto de carga suave
            const cards = document.querySelectorAll('.bg-white');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Inicializar contadores
            actualizarContadores();
        });
    </script>

    <style>
        .translate-x-full {
            transform: translateX(100%);
        }
        .transition-transform {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</x-layout.default>