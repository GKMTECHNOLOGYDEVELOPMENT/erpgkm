<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header Mejorado -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión de Repuestos Entregados</h1>
                <div class="flex flex-wrap items-center gap-4">
                    <p class="text-lg text-gray-600 bg-green-50 px-3 py-1 rounded-full">
                        📦 Código: <span class="font-semibold" id="codigoSolicitud">{{ $solicitud->codigo }}</span>
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-sm text-green-600">Repuesto entregado</span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('solicitudrepuesto.index') }}" 
                   class="flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Tarjeta de Información de Solicitud Mejorada -->
        <div class="bg-gradient-to-br from-white to-green-50 rounded-2xl shadow-lg p-6 mb-8 border border-green-100">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="text-center lg:text-left">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto lg:mx-0 mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700 mb-1">Solicitante</h3>
                    <p class="text-gray-900 font-medium" id="nombreSolicitante">{{ $solicitud->nombre_solicitante }}</p>
                </div>
                
                <div class="text-center lg:text-left">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto lg:mx-0 mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        @if($repuestos && $repuestos->count() > 0)
        <!-- Panel de Control de Estados -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Control de Estados del Repuesto
                </h2>
                <p class="text-green-100 text-sm mt-1">Selecciona el estado de uso para cada repuesto entregado</p>
            </div>

            <div class="divide-y divide-gray-100" id="listaRepuestos">
                @foreach($repuestos as $repuesto)
                @php
                    $estadoActual = $estadosRepuestos[$repuesto->idArticulos] ?? 'pendiente';
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
                <div class="p-6 hover:bg-gray-50 transition-all duration-300" data-repuesto-id="{{ $repuesto->idArticulos }}">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4">
                        <!-- Información del Repuesto -->
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $repuesto->nombre }}</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                            <span><strong>Código:</strong> {{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <span><strong>Tipo:</strong> {{ $repuesto->tipo_repuesto }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span><strong>Cantidad:</strong> {{ $repuesto->cantidad_solicitada }} unidad(es)</span>
                                        </div>
                                          <!-- Nueva columna para el ticket -->
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                            </svg>
                                            <span><strong>Ticket:</strong> 
                                                @if($repuesto->numero_ticket_repuesto)
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
                                <span class="px-3 py-1 text-sm font-semibold rounded-full border estado-repuesto {{ $clasesEstado[$estadoActual] }}">
                                    {{ $textoEstado[$estadoActual] }}
                                </span>
                                <span class="text-xs text-gray-500 fecha-actualizacion">
                                    @if($estadoActual === 'usado' && $repuesto->fechaUsado)
                                        {{ \Carbon\Carbon::parse($repuesto->fechaUsado)->format('d/m/Y H:i') }}
                                    @elseif($estadoActual === 'no_usado' && $repuesto->fechaSinUsar)
                                        {{ \Carbon\Carbon::parse($repuesto->fechaSinUsar)->format('d/m/Y H:i') }}
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
                                    data-repuesto-id="{{ $repuesto->idArticulos }}"
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
                                    data-repuesto-id="{{ $repuesto->idArticulos }}"
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

                    @if($repuesto->observacion)
                    <div class="mt-4 p-3 bg-orange-50 rounded-xl border border-orange-200">
                        <h4 class="font-medium text-orange-700 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Observación del Repuesto
                        </h4>
                        <p class="text-orange-600 text-sm mt-1">{{ $repuesto->observacion }}</p>
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
        <!-- Mensaje cuando no hay repuestos -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-8">
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">No hay repuestos procesados</h2>
                        <p class="text-yellow-100 text-sm">No se encontraron repuestos entregados para gestionar</p>
                    </div>
                </div>
            </div>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay repuestos para gestionar</h3>
                <p class="text-gray-600 mb-6">Esta solicitud no contiene repuestos entregados que requieran gestión de estados.</p>
                <a href="{{ route('solicitudrepuesto.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al Listado
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Scripts para la funcionalidad del front -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Función para cambiar estado de repuesto via AJAX
            async function cambiarEstadoRepuesto(repuestoId, nuevoEstado) {
                const url = nuevoEstado === 'usado' 
                    ? `/solicitudrepuesto/{{ $solicitud->idsolicitudesordenes }}/marcar-usado`
                    : `/solicitudrepuesto/{{ $solicitud->idsolicitudesordenes }}/marcar-no-usado`;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            articulo_id: repuestoId,
                            observacion: '' // Puedes agregar un campo para observaciones si lo necesitas
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Actualizar la UI localmente
                        const repuestoElement = document.querySelector(`[data-repuesto-id="${repuestoId}"]`);
                        const estadoElement = repuestoElement.querySelector('.estado-repuesto');
                        const fechaElement = repuestoElement.querySelector('.fecha-actualizacion');
                        const btnUsado = repuestoElement.querySelector('.btn-usado');
                        const btnNoUsado = repuestoElement.querySelector('.btn-no-usado');
                        
                        if (nuevoEstado === 'usado') {
                            contadorUsados++;
                            contadorPendientes--;
                            estadoElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-200 estado-repuesto';
                            estadoElement.textContent = '✅ Usado';
                            
                            // Deshabilitar botones
                            btnUsado.disabled = true;
                            btnUsado.classList.add('opacity-50', 'cursor-not-allowed');
                            btnUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="font-semibold">✅ Ya Marcado como Usado</span>';
                            
                            btnNoUsado.disabled = false;
                            btnNoUsado.classList.remove('opacity-50', 'cursor-not-allowed');
                            btnNoUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="font-semibold">Marcar como No Usado</span>';
                        } else if (nuevoEstado === 'no_usado') {
                            contadorNoUsados++;
                            contadorPendientes--;
                            estadoElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200 estado-repuesto';
                            estadoElement.textContent = '❌ No Usado';
                            
                            // Deshabilitar botones
                            btnNoUsado.disabled = true;
                            btnNoUsado.classList.add('opacity-50', 'cursor-not-allowed');
                            btnNoUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="font-semibold">❌ Ya Marcado como No Usado</span>';
                            
                            btnUsado.disabled = false;
                            btnUsado.classList.remove('opacity-50', 'cursor-not-allowed');
                            btnUsado.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="font-semibold">Marcar como Usado</span>';
                        }
                        
                        // Actualizar fecha
                        const ahora = new Date();
                        fechaElement.textContent = `Actualizado: ${ahora.toLocaleDateString()} ${ahora.toLocaleTimeString()}`;
                        
                        // Actualizar contadores visuales
                        actualizarContadores();
                        
                        // Mostrar notificación
                        mostrarNotificacion(data.message, 'success');
                    } else {
                        mostrarNotificacion('Error: ' + data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    mostrarNotificacion('Error de conexión', 'error');
                }
            }

            // Función para mostrar notificaciones
            function mostrarNotificacion(mensaje, tipo = 'success') {
                // Crear elemento de notificación
                const notificacion = document.createElement('div');
                const bgColor = tipo === 'success' ? 'bg-green-500' : 'bg-red-500';
                notificacion.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50`;
                notificacion.textContent = mensaje;
                
                document.body.appendChild(notificacion);
                
                // Animación de entrada
                setTimeout(() => {
                    notificacion.style.transform = 'translateX(0)';
                }, 100);
                
                // Animación de salida
                setTimeout(() => {
                    notificacion.style.transform = 'translateX-full';
                    setTimeout(() => {
                        document.body.removeChild(notificacion);
                    }, 300);
                }, 3000);
            }

            // Event listeners para botones
            document.querySelectorAll('.btn-usado').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) return;
                    
                    const repuestoId = this.getAttribute('data-repuesto-id');
                    if (confirm('¿Estás seguro de marcar este repuesto como USADO?\n\nEsta acción no se puede deshacer.')) {
                        cambiarEstadoRepuesto(repuestoId, 'usado');
                    }
                });
            });

            document.querySelectorAll('.btn-no-usado').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) return;
                    
                    const repuestoId = this.getAttribute('data-repuesto-id');
                    if (confirm('¿Estás seguro de marcar este repuesto como NO USADO?\n\nEl repuesto estará disponible para devolución.')) {
                        cambiarEstadoRepuesto(repuestoId, 'no_usado');
                    }
                });
            });

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