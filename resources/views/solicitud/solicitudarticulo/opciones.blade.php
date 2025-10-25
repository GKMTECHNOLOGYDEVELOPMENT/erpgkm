<x-layout.default>
    <div x-data="solicitudRepuestoOpciones()" class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">

            <!-- Header Mejorado -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-green-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Gestión de Artículos</h1>
                                <p class="text-gray-600 text-lg">Procese artículos individualmente o en grupo</p>
                            </div>
                        </div>

                        <!-- Resumen de Progreso -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">

                            <!-- Código de Solicitud -->
                            <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Código</p>
                                    <p class="font-semibold text-gray-900">{{ $solicitud->codigo }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-green-600 font-bold text-sm">{{ $articulos_procesados }}/{{ $total_articulos }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Procesados</p>
                                    <p class="font-semibold text-gray-900">
                                        @if($articulos_procesados == $total_articulos)
                                        ✅ Completado
                                        @else
                                        ⏳ En progreso
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-xl">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">{{ $articulos_disponibles }}/{{ $total_articulos }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Disponibles</p>
                                    <p class="font-semibold text-gray-900">
                                        @if($articulos_disponibles == $total_articulos)
                                        ✅ Listos
                                        @else
                                        ⚡ Parcial
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- <div class="flex items-center space-x-3 p-4 bg-purple-50 rounded-xl">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Ticket</p>
                                    <p class="font-semibold text-gray-900">{{ $solicitud->numero_ticket ?? 'N/A' }}</p>
                                </div>
                            </div> -->

                            <div class="flex items-center space-x-3 p-4 bg-orange-50 rounded-xl">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Estado General</p>
                                    <p class="font-semibold text-gray-900 capitalize">
                                        @if($solicitud->estado == 'aprobada')
                                        ✅ Aprobada
                                        @elseif($articulos_procesados > 0)
                                        ⚡ Parcial
                                        @else
                                        ⏳ Pendiente
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verificar si hay artículos -->
            @if(!$articulos || $articulos->count() == 0)
            <!-- Mensaje cuando no hay artículos -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-8">
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md">
                            ⚠️
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">No hay artículos</h2>
                            <p class="text-yellow-100 text-sm">No se encontraron artículos en esta solicitud</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 text-center">
                    <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No se encontraron artículos</h3>
                    <p class="text-gray-600 mb-6">Esta solicitud no contiene artículos para gestionar.</p>
                    <a href="{{ route('solicitudrepuesto.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al Listado
                    </a>
                </div>
            </div>
            @else
            <!-- Panel de Artículos con Acciones Individuales y Grupales -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-green-100 mb-8">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white text-green-600 rounded-full flex items-center justify-center font-bold shadow-md">
                            📦
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Gestión de Artículos</h2>
                            <p class="text-green-100 text-sm">Procese artículos individualmente o en grupo</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Formulario para selección grupal -->
                    <form id="formUbicaciones" @submit.prevent="procesarSeleccion">
                        <!-- Tabla de Artículos con ambas opciones -->
                        <div class="overflow-hidden rounded-xl border border-gray-200 mb-6">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-gray-50 to-green-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Artículo</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Solicitado</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Disponible</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Ubicación</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Acción Individual</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($articulos as $articulo)
                                    <tr class="hover:bg-green-50 transition-colors duration-200 
                                            @if($articulo->ya_procesado) bg-green-50 @endif">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <p class="font-semibold text-gray-900 text-base">{{ $articulo->nombre }}</p>
                                                <p class="text-sm text-gray-500 mt-1">{{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}</p>
                                                <p class="text-xs text-gray-400">{{ $articulo->tipo_articulo }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ $articulo->cantidad_solicitada }} unidades
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-center">
                                                <span class="text-lg font-bold 
                                                        @if($articulo->suficiente_stock) text-green-600
                                                        @else text-red-600 @endif">
                                                    {{ $articulo->stock_disponible }}
                                                </span>
                                                <span class="text-sm text-gray-500 block">unidades</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs">
                                                @if($articulo->ya_procesado)
                                                <div class="text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        ✅ Procesado
                                                    </span>
                                                </div>
                                                @elseif(isset($articulo->ubicaciones_detalle) && count($articulo->ubicaciones_detalle) > 0)
                                                <select
                                                    name="ubicaciones[{{ $articulo->idArticulos }}]"
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                    x-model="selecciones[{{ $articulo->idArticulos }}]"
                                                    :disabled="procesandoIndividual[{{ $articulo->idArticulos }}]">
                                                    <option value="">-- Seleccione ubicación --</option>
                                                    @foreach($articulo->ubicaciones_detalle as $ubicacion)
                                                    <option value="{{ $ubicacion->rack_ubicacion_id }}">
                                                        {{ $ubicacion->ubicacion_codigo }} ({{ $ubicacion->stock_ubicacion }} uds)
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @else
                                                <p class="text-sm text-red-500 italic">Sin ubicaciones</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($articulo->ya_procesado)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                ✅ Procesado
                                            </span>
                                            @elseif($articulo->suficiente_stock)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                ⏳ Pendiente
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                ❌ Insuficiente
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($articulo->ya_procesado)
                                            <span class="text-green-600 font-medium">Completado</span>
                                            @elseif($articulo->suficiente_stock)
                                            <button
                                                type="button"
                                                @click="procesarIndividual({{ $solicitud->idsolicitudesordenes }}, {{ $articulo->idArticulos }})"
                                                :disabled="!selecciones[{{ $articulo->idArticulos }}] || procesandoIndividual[{{ $articulo->idArticulos }}]"
                                                :class="{
                                                            'opacity-50 cursor-not-allowed': !selecciones[{{ $articulo->idArticulos }}] || procesandoIndividual[{{ $articulo->idArticulos }}],
                                                            'bg-green-500 hover:bg-green-600': selecciones[{{ $articulo->idArticulos }}] && !procesandoIndividual[{{ $articulo->idArticulos }}],
                                                            'bg-gray-400': !selecciones[{{ $articulo->idArticulos }}]
                                                        }"
                                                class="px-4 py-2 text-white rounded-lg font-medium transition-colors duration-200">
                                                <span x-show="!procesandoIndividual[{{ $articulo->idArticulos }}]">Procesar</span>
                                                <span x-show="procesandoIndividual[{{ $articulo->idArticulos }}]">Procesando...</span>
                                            </button>
                                            @else
                                            <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-lg font-medium cursor-not-allowed">
                                                Sin Stock
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panel de Acciones - Individual y Grupal -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-green-100">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white text-green-600 rounded-full flex items-center justify-center font-bold shadow-md">
                            ⚡
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Opciones de Procesamiento</h2>
                            <p class="text-green-100 text-sm">Elija procesar individualmente o todo el grupo</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Procesamiento Individual -->
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-blue-900">Procesamiento Individual</h3>
                                    <p class="text-blue-700 text-sm">Procese cada artículo por separado</p>
                                </div>
                            </div>
                            <ul class="space-y-2 mb-4">
                                <li class="flex items-center text-sm text-blue-700">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Seleccione ubicación para cada artículo
                                </li>
                                <li class="flex items-center text-sm text-blue-700">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Use el botón "Procesar" en cada fila
                                </li>
                                <li class="flex items-center text-sm text-blue-700">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Ideal para procesamiento parcial
                                </li>
                            </ul>
                            <div class="text-center text-sm text-blue-600 font-medium">
                                Progreso: {{ $articulos_procesados }} de {{ $total_articulos }}
                            </div>
                        </div>

                        <!-- Procesamiento Grupal -->
                        <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-900">Procesamiento Grupal</h3>
                                    <p class="text-green-700 text-sm">Procese todos los artículos disponibles</p>
                                </div>
                            </div>
                            <ul class="space-y-2 mb-4">
                                <li class="flex items-center text-sm text-green-700">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Seleccione ubicaciones para todos los artículos
                                </li>
                                <li class="flex items-center text-sm text-green-700">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Procese todo en una sola acción
                                </li>
                                <li class="flex items-center text-sm text-green-700">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Requiere que todos tengan stock suficiente
                                </li>
                            </ul>
                            <div class="flex flex-col space-y-3">
                                <button
                                    @click="validarYProcesarGrupal({{ $solicitud->idsolicitudesordenes }})"
                                    :disabled="isLoadingGrupal || !todasUbicacionesSeleccionadas || !todosDisponibles"
                                    :class="{ 
                                            'opacity-50 cursor-not-allowed': isLoadingGrupal || !todasUbicacionesSeleccionadas || !todosDisponibles,
                                            'bg-gradient-to-r from-green-500 to-emerald-600': todasUbicacionesSeleccionadas && todosDisponibles,
                                            'bg-gradient-to-r from-gray-400 to-gray-500': !todasUbicacionesSeleccionadas || !todosDisponibles
                                        }"
                                    class="flex items-center justify-center px-6 py-3 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                    <svg x-show="!isLoadingGrupal" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div x-show="isLoadingGrupal" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                    <span x-text="isLoadingGrupal ? 'Procesando...' : 'Procesar Todo'"></span>
                                </button>

                                <div class="text-center text-sm 
                                        @if($puede_aceptar) text-green-600 @else text-red-600 @endif font-medium">
                                    @if($puede_aceptar)
                                    ✅ Todos los artículos están disponibles
                                    @else
                                    ⚠️ Algunos artículos no tienen stock suficiente
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Estado -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-medium">
                                    @if($articulos_procesados == $total_articulos)
                                    ✅ Todos los artículos han sido procesados
                                    @else
                                    📊 Progreso: {{ $articulos_procesados }} de {{ $total_articulos }} artículos procesados
                                    @endif
                                </p>
                            </div>

                            <a href="{{ route('solicitudrepuesto.index') }}"
                                class="flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg font-medium hover:shadow-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver al Listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuestoOpciones', () => ({
                selecciones: {},
                procesandoIndividual: {},
                isLoadingGrupal: false,

                get todasUbicacionesSeleccionadas() {
                    const articulos = @json($articulos);
                    return articulos.every(articulo => {
                        // Solo verificar artículos no procesados y con stock suficiente
                        if (articulo.ya_procesado || !articulo.suficiente_stock) {
                            return true;
                        }
                        return this.selecciones[articulo.idArticulos] && this.selecciones[articulo.idArticulos] !== '';
                    });
                },

                get todosDisponibles() {
                    return @json($puede_aceptar);
                },

                async procesarIndividual(solicitudId, articuloId) {
                    const ubicacionId = this.selecciones[articuloId];

                    if (!ubicacionId) {
                        this.mostrarNotificacion('error', 'Seleccione una ubicación para este artículo');
                        return;
                    }

                    if (!confirm(`¿Está seguro de que desea procesar este artículo?\n\nEl stock será descontado de la ubicación seleccionada.`)) {
                        return;
                    }

                    this.procesandoIndividual[articuloId] = true;

                    try {
                        const response = await fetch(`/solicitudarticulo/${solicitudId}/aceptar-individual`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                articulo_id: articuloId,
                                ubicacion_id: ubicacionId
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.mostrarNotificacion('success', data.message);
                            if (data.todos_procesados) {
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        } else {
                            this.mostrarNotificacion('error', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.mostrarNotificacion('error', 'Error al procesar el artículo');
                    } finally {
                        this.procesandoIndividual[articuloId] = false;
                    }
                },

                async validarYProcesarGrupal(id) {
                    if (!this.todasUbicacionesSeleccionadas) {
                        this.mostrarNotificacion('error', 'Debe seleccionar una ubicación para todos los artículos disponibles');
                        return;
                    }

                    if (!this.todosDisponibles) {
                        this.mostrarNotificacion('error', 'No todos los artículos tienen stock suficiente para procesamiento grupal');
                        return;
                    }

                    if (!confirm('¿Está seguro de que desea procesar TODOS los artículos?\n\nEl stock será descontado de las ubicaciones seleccionadas para cada artículo.')) {
                        return;
                    }

                    this.isLoadingGrupal = true;

                    try {
                        const response = await fetch(`/solicitudarticulo/${id}/aceptar`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ubicaciones: this.selecciones
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.mostrarNotificacion('success', data.message);
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            this.mostrarNotificacion('error', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.mostrarNotificacion('error', 'Error al procesar la solicitud');
                    } finally {
                        this.isLoadingGrupal = false;
                    }
                },

                mostrarNotificacion(tipo, mensaje) {
                    const iconos = {
                        success: '✅',
                        error: '❌',
                        info: 'ℹ️'
                    };

                    const alerta = document.createElement('div');
                    alerta.className = `fixed top-4 right-4 p-4 rounded-xl shadow-lg z-50 transform transition-all duration-300 ${
                        tipo === 'success' ? 'bg-green-500 text-white' :
                        tipo === 'error' ? 'bg-red-500 text-white' :
                        'bg-blue-500 text-white'
                    }`;
                    alerta.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">${iconos[tipo]}</span>
                            <span class="font-medium">${mensaje}</span>
                        </div>
                    `;

                    document.body.appendChild(alerta);

                    setTimeout(() => {
                        alerta.remove();
                    }, 5000);
                }
            }));
        });
    </script>
</x-layout.default>