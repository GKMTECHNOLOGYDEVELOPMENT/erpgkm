<x-layout.default>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div x-data="solicitudRepuestoProvinciaOpciones()" class="min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-7xl">

            <!-- Header Mejorado -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-blue-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-truck text-white text-lg"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Gestión de Repuestos - Provincia</h1>
                                <p class="text-gray-600 text-lg">Procese repuestos para envío a provincia</p>
                            </div>
                        </div>

                        <!-- Resumen de Progreso -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">

                            <!-- Código de Solicitud -->
                            <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-xl">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-hashtag text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Código</p>
                                    <p class="font-semibold text-gray-900">{{ $solicitud->codigo }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-xl">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-blue-600 font-bold text-sm">{{ $repuestos_procesados }}/{{ $total_repuestos }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Procesados</p>
                                    <p class="font-semibold text-gray-900">
                                        @if ($repuestos_procesados == $total_repuestos)
                                            Completado
                                        @else
                                            En progreso
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-green-600 font-bold text-sm">{{ $repuestos_disponibles }}/{{ $total_repuestos }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Disponibles</p>
                                    <p class="font-semibold text-gray-900">
                                        @if ($repuestos_disponibles == $total_repuestos)
                                            Listos
                                        @else
                                            Parcial
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-orange-50 rounded-xl">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-truck-loading text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tipo</p>
                                    <p class="font-semibold text-gray-900">
                                        Envío a Provincia
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Solicitante -->
                        <div class="mt-6">
                            @if($solicitante)
                            <div class="flex items-center space-x-3 p-4 bg-purple-50 rounded-xl">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-tie text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Solicitante (Provincia)</p>
                                    <p class="font-semibold text-gray-900">{{ $solicitante->Nombre }} {{ $solicitante->apellidoPaterno }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verificar si hay repuestos -->
            @if (!$repuestos || $repuestos->count() == 0)
                <!-- Mensaje cuando no hay repuestos -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-8">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">No hay repuestos</h2>
                                <p class="text-yellow-100 text-sm">No se encontraron repuestos en esta solicitud</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-cogs text-yellow-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No se encontraron repuestos</h3>
                        <p class="text-gray-600 mb-6">Esta solicitud no contiene repuestos para gestionar.</p>
                        <a href="{{ route('solicitudrepuesto.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            @else
                <!-- Panel Principal de Gestión -->
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-truck-loading text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Repuestos - Provincia</h2>
                                <p class="text-indigo-100 text-base">Preparar repuestos para envío a provincia</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <!-- Formulario para selección -->
                        <form id="formUbicaciones" @submit.prevent="procesarSeleccion">
                            <!-- Tabla Mejorada -->
                            <div class="overflow-hidden rounded-2xl border border-slate-200/60 shadow-sm mb-8">
                                <table class="w-full">
<thead class="bg-gradient-to-r from-slate-50 to-indigo-50/30">
    <tr>
        <th class="px-8 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
            <div class="flex items-center space-x-2">
                <i class="fas fa-cog text-slate-500"></i>
                <span>Repuesto</span>
            </div>
        </th>
        <th class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
            Solicitado
        </th>
        <th class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
            Disponible
        </th>
        <th class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
            <i class="fas fa-map-marker-alt mr-1 text-slate-500"></i>
            Ubicación
        </th>
        <th class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
            <i class="fas fa-truck mr-1 text-slate-500"></i>
            Destino
        </th>
        <th class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
            <i class="fas fa-tasks mr-1 text-slate-500"></i>
            Estado
        </th>
        <th class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
            <i class="fas fa-play-circle mr-1 text-slate-500"></i>
            Acción
        </th>
    </tr>
</thead>
                                    <tbody class="divide-y divide-slate-200/40">
    @foreach ($repuestos as $repuesto)
        <tr class="transition-all duration-200 hover:bg-indigo-50/30 @if ($repuesto->ya_procesado) bg-green-50/50 @endif">
            <!-- Información del Repuesto -->
            <td class="px-8 py-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cog text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-base">{{ $repuesto->nombre }}</p>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}
                        </p>
                        <p class="text-xs text-slate-400 font-medium">{{ $repuesto->tipo_repuesto }}</p>
                    </div>
                </div>
            </td>

            <!-- Cantidad Solicitada -->
            <td class="px-6 py-6">
                <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-indigo-100 text-indigo-700">
                    <i class="fas fa-sort-numeric-up mr-1"></i>
                    {{ $repuesto->cantidad_solicitada }} unidades
                </span>
            </td>

            <!-- Stock Disponible -->
            <td class="px-6 py-6">
                <div class="text-center">
                    <span class="text-lg font-bold @if ($repuesto->suficiente_stock) text-green-600 @else text-rose-600 @endif">
                        {{ $repuesto->stock_disponible }}
                    </span>
                    <span class="text-sm text-slate-500 block">disponibles</span>
                </div>
            </td>

            <!-- Selección de Ubicación -->
            <td class="px-6 py-6">
                <div class="max-w-xs">
                    @if ($repuesto->ya_procesado)
                        <div class="text-center">
                            <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-green-100 text-green-700">
                                <i class="fas fa-check-circle mr-1"></i>
                                Procesado
                            </span>
                        </div>
                    @elseif(isset($repuesto->ubicaciones_detalle) && count($repuesto->ubicaciones_detalle) > 0)
                        <select name="ubicaciones[{{ $repuesto->idArticulos }}]"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                            x-model="selecciones[{{ $repuesto->idArticulos }}]"
                            :disabled="procesandoIndividual[{{ $repuesto->idArticulos }}]">
                            <option value=""><i class="fas fa-map-marker-alt mr-2"></i>Seleccione ubicación</option>
                            @foreach ($repuesto->ubicaciones_detalle as $ubicacion)
                                <option value="{{ $ubicacion->rack_ubicacion_id }}">
                                    <i class="fas fa-location-arrow mr-2"></i>
                                    {{ $ubicacion->ubicacion_codigo }}
                                    ({{ $ubicacion->stock_ubicacion }} uds)
                                </option>
                            @endforeach
                        </select>
                    @else
                        <p class="text-sm text-rose-500 italic font-medium">
                            <i class="fas fa-times-circle mr-1"></i>
                            Sin ubicaciones disponibles
                        </p>
                    @endif
                </div>
            </td>

            <!-- COLUMNA MODIFICADA: Destino (Provincia) -->
            <td class="px-6 py-6">
                @if ($repuesto->ya_procesado)
                    @php
                        // Obtener información del envío a provincia
                        $envioInfo = DB::table('repuestos_envios_provincia as re')
                            ->select(
                                're.fecha_entrega_transporte',
                                're.foto_comprobante',
                                're.observaciones',
                                're.transportista',
                                're.placa_vehiculo'
                            )
                            ->where('re.solicitud_id', $solicitud->idsolicitudesordenes)
                            ->where('re.articulo_id', $repuesto->idArticulos)
                            ->first();
                    @endphp

                    @if($envioInfo)
                        <div class="text-center">
                            <div class="flex flex-col items-center space-y-2">
                                <i class="fas fa-truck text-indigo-600 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">
                                        Entregado al Transporte
                                    </p>
                                    @if($envioInfo->transportista)
                                        <p class="text-xs text-slate-500">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            {{ $envioInfo->transportista }}
                                        </p>
                                    @endif
                                    @if($envioInfo->placa_vehiculo)
                                        <p class="text-xs text-slate-500">
                                            <i class="fas fa-car mr-1"></i>
                                            {{ $envioInfo->placa_vehiculo }}
                                        </p>
                                    @endif
                                    @if($envioInfo->fecha_entrega_transporte)
                                        <p class="text-xs text-slate-500">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ date('d/m/Y H:i', strtotime($envioInfo->fecha_entrega_transporte)) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Si está procesado pero no tiene info de envío (debería tener) -->
                        <div class="text-center">
                            <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-indigo-100 text-indigo-700">
                                <i class="fas fa-truck mr-1"></i>
                                Para Envío a Provincia
                            </span>
                        </div>
                    @endif
                @else
                    <!-- Cuando NO está procesado -->
                    <div class="text-center">
                        <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-amber-100 text-amber-700">
                            <i class="fas fa-clock mr-1"></i>
                            Pendiente para Envío
                        </span>
                    </div>
                @endif
            </td>

            <!-- Estado -->
            <td class="px-6 py-6">
                @if ($repuesto->ya_procesado)
                    <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-success text-white border border-green-200 shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i>
                        Listo para Envío
                    </span>
                @elseif($repuesto->suficiente_stock)
                    <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-warning text-white border border-amber-200 shadow-sm">
                        <i class="fas fa-clock mr-1"></i>
                        Pendiente
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-danger text-white border border-red-200 shadow-sm">
                        <i class="fas fa-times-circle mr-1"></i>
                        Insuficiente
                    </span>
                @endif
            </td>

            <!-- Acción -->
            <td class="px-6 py-6">
                @if ($repuesto->ya_procesado)
                    <span class="text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-1"></i>
                        Listo para Envío
                    </span>
                @elseif($repuesto->suficiente_stock)
                    @if(\App\Helpers\PermisoHelper::tienePermiso('PROCESAR REPUESTO INDIVIDUAL'))
                    <button type="button"
                        @click="abrirModalEnvioProvincia({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }}, '{{ $repuesto->nombre }}')"
                        :disabled="!selecciones[{{ $repuesto->idArticulos }}] || procesandoIndividual[{{ $repuesto->idArticulos }}]"
                        :class="{
                            'opacity-50 cursor-not-allowed': !selecciones[{{ $repuesto->idArticulos }}] || procesandoIndividual[{{ $repuesto->idArticulos }}],
                            'bg-indigo-600 hover:bg-indigo-700': selecciones[{{ $repuesto->idArticulos }}] && !procesandoIndividual[{{ $repuesto->idArticulos }}]
                        }"
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-400 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                        <span x-show="!procesandoIndividual[{{ $repuesto->idArticulos }}]">
                            <i class="fas fa-truck mr-2"></i>
                            Preparar Envío
                        </span>
                        <span x-show="procesandoIndividual[{{ $repuesto->idArticulos }}]" class="flex items-center space-x-2">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            <span>Procesando...</span>
                        </span>
                    </button>
                    @endif

                @else
                    <button disabled
                        class="px-6 py-3 bg-gray-300 text-gray-600 rounded-xl font-semibold cursor-not-allowed border border-gray-300">
                        <i class="fas fa-ban mr-2"></i>
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

                <!-- Panel de Estrategias de Procesamiento -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-rocket text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Estrategias de Procesamiento - Provincia</h2>
                                <p class="text-slate-300 text-base">Prepare repuestos para envío a provincia</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <!-- Procesamiento Individual para Provincia -->
                            <div
                                class="group bg-white rounded-2xl p-8 border border-indigo-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start gap-4 mb-6">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-2xl flex items-center justify-center shadow-inner">
                                        <i class="fas fa-truck text-indigo-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">Procesamiento Individual</h3>
                                        <p class="text-slate-600 text-sm mt-1">Prepare cada repuesto individualmente para envío a provincia.</p>
                                    </div>
                                </div>

                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-indigo-500 mr-3 flex-shrink-0"></i>
                                        Seleccione ubicación específica para cada repuesto.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-indigo-500 mr-3 flex-shrink-0"></i>
                                        Registre foto del comprobante de entrega al transporte.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-indigo-500 mr-3 flex-shrink-0"></i>
                                        Ideal para envíos parciales o selectivos.
                                    </li>
                                </ul>

                                <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100 text-center">
                                    <p class="text-sm font-semibold text-indigo-700">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        Progreso: <span class="font-bold">{{ $repuestos_procesados }}</span> de
                                        <span class="font-bold">{{ $total_repuestos }}</span> repuestos preparados
                                    </p>
                                </div>
                            </div>

                            <!-- Procesamiento Grupal para Provincia -->
                            <div
                                class="group bg-white rounded-2xl p-8 border border-green-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start gap-4 mb-6">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-50 rounded-2xl flex items-center justify-center shadow-inner">
                                        <i class="fas fa-truck-loading text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">Procesamiento Grupal</h3>
                                        <p class="text-slate-600 text-sm mt-1">Prepare todos los repuestos para envío en una sola acción.</p>
                                    </div>
                                </div>

                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                        Seleccione ubicaciones para todos los repuestos.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                        Procese todo el lote con un solo comprobante.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                        Requiere stock completo en todos los repuestos.
                                    </li>
                                </ul>

                                <div class="space-y-4">
                                    @if(App\Helpers\PermisoHelper::tienePermiso('PROCESAR REPUESTO GRUPAL'))
                                    <button @click="abrirModalEnvioProvinciaGrupal({{ $solicitud->idsolicitudesordenes }})"
                                        :disabled="isLoadingGrupal || !todasUbicacionesSeleccionadas || !todosDisponibles"
                                        :class="{
                                            'opacity-50 cursor-not-allowed': isLoadingGrupal || !
                                                todasUbicacionesSeleccionadas || !todosDisponibles,
                                            'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 shadow-lg hover:shadow-xl': todasUbicacionesSeleccionadas &&
                                                todosDisponibles,
                                            'bg-gradient-to-r from-slate-400 to-slate-500': !
                                                todasUbicacionesSeleccionadas || !todosDisponibles
                                        }"
                                        class="w-full flex items-center justify-center px-8 py-4 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                        <i x-show="!isLoadingGrupal" class="fas fa-truck-loading mr-3"></i>
                                        <i x-show="isLoadingGrupal" class="fas fa-spinner fa-spin mr-3"></i>
                                        <span
                                            x-text="isLoadingGrupal ? 'Procesando...' : 'Preparar Todo para Envío'"></span>
                                    </button>
                                    @endif

                                    <div
                                        class="text-center text-sm font-semibold @if ($puede_aceptar) text-success @else text-danger @endif">
                                        @if ($puede_aceptar)
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Condiciones óptimas para envío grupal
                                        @else
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Algunos repuestos no cumplen las condiciones
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de Estado Mejorada -->
                        <div
                            class="mt-10 p-6 bg-gradient-to-r from-indigo-50 to-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                <!-- Estado -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex-shrink-0 w-11 h-11 bg-indigo-100 rounded-2xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-info-circle text-indigo-600"></i>
                                    </div>

                                    <div>
                                        @if ($repuestos_procesados == $total_repuestos)
                                            <p class="text-sm font-semibold text-green-600 flex items-center gap-1">
                                                <i class="fas fa-check-circle"></i>
                                                Todo listo para envío a provincia
                                            </p>
                                        @else
                                            <p class="text-sm font-semibold text-indigo-600 flex items-center gap-1">
                                                <i class="fas fa-chart-bar"></i>
                                                Progreso de preparación:
                                                <span class="text-slate-700">{{ $repuestos_procesados }}</span>
                                                <span class="text-slate-500">de</span>
                                                <span class="text-slate-700">{{ $total_repuestos }}</span> repuestos
                                            </p>
                                        @endif

                                        <p class="text-xs text-slate-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Última actualización:
                                            <span
                                                class="font-medium text-slate-600">{{ now()->format('d/m/Y H:i') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    @if($puede_generar_pdf)
                                    <a href="{{ route('solicitudrepuestoprovincia.conformidad-pdf', $solicitud->idsolicitudesordenes) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        Descargar Conformidad
                                    </a>
                                    @endif
                                    
                                    <!-- Botón -->
                                    <a href="{{ route('solicitudarticulo.index') }}"
                                        class="inline-flex items-center px-5 py-2.5 bg-dark text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Volver al Listado
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal para registro de envío a provincia (individual) -->
        <div x-show="mostrarModalEnvio" 
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all duration-300 scale-100"
                 x-show="mostrarModalEnvio"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <!-- Header del Modal -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Registrar Envío a Provincia</h3>
                                <p class="text-indigo-100 text-sm" x-text="repuestoSeleccionadoNombre"></p>
                            </div>
                        </div>
                        <button @click="cerrarModalEnvio" class="text-white hover:text-indigo-200 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <form id="formEnvioProvincia" @submit.prevent="confirmarEnvioIndividual">
                    <div class="p-6 space-y-4">
                        <p class="text-gray-600 mb-4">Complete los datos del envío a provincia:</p>
                        
                        <!-- Transportista -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user-tie mr-1"></i>
                                Transportista
                            </label>
                            <input type="text"
                                   x-model="datosEnvio.transportista"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Nombre del transportista">
                        </div>

                        <!-- Placa del Vehículo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-car mr-1"></i>
                                Placa del Vehículo
                            </label>
                            <input type="text"
                                   x-model="datosEnvio.placa_vehiculo"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Placa del vehículo">
                        </div>

                        <!-- Fecha de Entrega al Transporte -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Fecha de Entrega al Transporte
                            </label>
                            <input type="datetime-local"
                                   x-model="datosEnvio.fecha_entrega_transporte"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Subir Foto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-camera mr-1"></i>
                                Foto del Comprobante (opcional)
                            </label>
                            <input type="file"
                                   id="fotoComprobante"
                                   accept="image/*"
                                   @change="previsualizarFoto"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            
                            <!-- Previsualización de la foto -->
                            <div x-show="previsualizacionFoto" class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                                <img :src="previsualizacionFoto" 
                                     alt="Previsualización" 
                                     class="max-w-xs rounded-lg border border-gray-300">
                                <button type="button"
                                        @click="eliminarPrevisualizacion"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash mr-1"></i> Eliminar foto
                                </button>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-comment-alt mr-1"></i>
                                Observaciones
                            </label>
                            <textarea x-model="datosEnvio.observaciones"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Observaciones adicionales sobre el envío..."></textarea>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex space-x-3 pt-4">
                            <button type="button"
                                    @click="cerrarModalEnvio"
                                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    :disabled="!datosEnvioValidos"
                                    :class="{
                                        'bg-indigo-600 hover:bg-indigo-700': datosEnvioValidos,
                                        'bg-gray-400 cursor-not-allowed': !datosEnvioValidos
                                    }"
                                    class="flex-1 px-4 py-2.5 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-truck mr-2"></i>
                                Confirmar Envío
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para registro de envío grupal a provincia -->
        <div x-show="mostrarModalEnvioGrupal" 
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all duration-300 scale-100"
                 x-show="mostrarModalEnvioGrupal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <!-- Header del Modal -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-truck-loading text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Registrar Envío Grupal a Provincia</h3>
                                <p class="text-green-100 text-sm">Todos los repuestos serán preparados para envío</p>
                            </div>
                        </div>
                        <button @click="cerrarModalEnvioGrupal" class="text-white hover:text-green-200 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <form id="formEnvioProvinciaGrupal" @submit.prevent="confirmarEnvioGrupal">
                    <div class="p-6 space-y-4">
                        <p class="text-gray-600 mb-4">Complete los datos del envío grupal a provincia:</p>
                        
                        <!-- Transportista -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user-tie mr-1"></i>
                                Transportista
                            </label>
                            <input type="text"
                                   x-model="datosEnvioGrupal.transportista"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Nombre del transportista">
                        </div>

                        <!-- Placa del Vehículo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-car mr-1"></i>
                                Placa del Vehículo
                            </label>
                            <input type="text"
                                   x-model="datosEnvioGrupal.placa_vehiculo"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Placa del vehículo">
                        </div>

                        <!-- Fecha de Entrega al Transporte -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Fecha de Entrega al Transporte
                            </label>
                            <input type="datetime-local"
                                   x-model="datosEnvioGrupal.fecha_entrega_transporte"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Subir Foto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-camera mr-1"></i>
                                Foto del Comprobante (opcional)
                            </label>
                            <input type="file"
                                   id="fotoComprobanteGrupal"
                                   accept="image/*"
                                   @change="previsualizarFotoGrupal"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            
                            <!-- Previsualización de la foto -->
                            <div x-show="previsualizacionFotoGrupal" class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                                <img :src="previsualizacionFotoGrupal" 
                                     alt="Previsualización" 
                                     class="max-w-xs rounded-lg border border-gray-300">
                                <button type="button"
                                        @click="eliminarPrevisualizacionGrupal"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash mr-1"></i> Eliminar foto
                                </button>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-comment-alt mr-1"></i>
                                Observaciones
                            </label>
                            <textarea x-model="datosEnvioGrupal.observaciones"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      placeholder="Observaciones adicionales sobre el envío grupal..."></textarea>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex space-x-3 pt-4">
                            <button type="button"
                                    @click="cerrarModalEnvioGrupal"
                                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    :disabled="!datosEnvioGrupalValidos"
                                    :class="{
                                        'bg-green-600 hover:bg-green-700': datosEnvioGrupalValidos,
                                        'bg-gray-400 cursor-not-allowed': !datosEnvioGrupalValidos
                                    }"
                                    class="flex-1 px-4 py-2.5 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-truck-loading mr-2"></i>
                                Confirmar Envío Grupal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuestoProvinciaOpciones', () => ({
                selecciones: {},
                procesandoIndividual: {},
                isLoadingGrupal: false,
                
                // Variables para el modal individual
                mostrarModalEnvio: false,
                datosEnvio: {
                    transportista: '',
                    placa_vehiculo: '',
                    fecha_entrega_transporte: new Date().toISOString().slice(0, 16),
                    observaciones: ''
                },
                previsualizacionFoto: null,
                solicitudIdSeleccionada: null,
                articuloIdSeleccionado: null,
                repuestoSeleccionadoNombre: '',

                // Variables para el modal grupal
                mostrarModalEnvioGrupal: false,
                datosEnvioGrupal: {
                    transportista: '',
                    placa_vehiculo: '',
                    fecha_entrega_transporte: new Date().toISOString().slice(0, 16),
                    observaciones: ''
                },
                previsualizacionFotoGrupal: null,

                // Computadas
                get datosEnvioValidos() {
                    return this.datosEnvio.transportista.trim() !== '' &&
                           this.datosEnvio.placa_vehiculo.trim() !== '' &&
                           this.datosEnvio.fecha_entrega_transporte.trim() !== '';
                },

                get datosEnvioGrupalValidos() {
                    return this.datosEnvioGrupal.transportista.trim() !== '' &&
                           this.datosEnvioGrupal.placa_vehiculo.trim() !== '' &&
                           this.datosEnvioGrupal.fecha_entrega_transporte.trim() !== '';
                },

                get todasUbicacionesSeleccionadas() {
                    const repuestos = @json($repuestos);
                    return repuestos.every(repuesto => {
                        if (repuesto.ya_procesado || !repuesto.suficiente_stock) {
                            return true;
                        }
                        return this.selecciones[repuesto.idArticulos] && this.selecciones[
                            repuesto.idArticulos] !== '';
                    });
                },

                get todosDisponibles() {
                    return @json($puede_aceptar);
                },

                // Métodos para modal individual
                abrirModalEnvioProvincia(solicitudId, articuloId, nombreRepuesto) {
                    const ubicacionId = this.selecciones[articuloId];
                    
                    if (!ubicacionId) {
                        this.mostrarNotificacion('error', 'Seleccione una ubicación para este repuesto');
                        return;
                    }

                    this.solicitudIdSeleccionada = solicitudId;
                    this.articuloIdSeleccionado = articuloId;
                    this.repuestoSeleccionadoNombre = nombreRepuesto;
                    
                    // Resetear datos del formulario
                    this.datosEnvio = {
                        transportista: '',
                        placa_vehiculo: '',
                        fecha_entrega_transporte: new Date().toISOString().slice(0, 16),
                        observaciones: ''
                    };
                    this.previsualizacionFoto = null;
                    
                    this.mostrarModalEnvio = true;
                },

                cerrarModalEnvio() {
                    this.mostrarModalEnvio = false;
                    this.previsualizacionFoto = null;
                },

                previsualizarFoto(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previsualizacionFoto = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                eliminarPrevisualizacion() {
                    this.previsualizacionFoto = null;
                    document.getElementById('fotoComprobante').value = '';
                },

                // Métodos para modal grupal
                abrirModalEnvioProvinciaGrupal(solicitudId) {
                    if (!this.todasUbicacionesSeleccionadas) {
                        this.mostrarNotificacion('error',
                            'Debe seleccionar una ubicación para todos los repuestos disponibles'
                        );
                        return;
                    }

                    if (!this.todosDisponibles) {
                        this.mostrarNotificacion('error',
                            'No todos los repuestos tienen stock suficiente para procesamiento grupal'
                        );
                        return;
                    }

                    this.solicitudIdSeleccionada = solicitudId;
                    
                    // Resetear datos del formulario grupal
                    this.datosEnvioGrupal = {
                        transportista: '',
                        placa_vehiculo: '',
                        fecha_entrega_transporte: new Date().toISOString().slice(0, 16),
                        observaciones: ''
                    };
                    this.previsualizacionFotoGrupal = null;
                    
                    this.mostrarModalEnvioGrupal = true;
                },

                cerrarModalEnvioGrupal() {
                    this.mostrarModalEnvioGrupal = false;
                    this.previsualizacionFotoGrupal = null;
                },

                previsualizarFotoGrupal(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previsualizacionFotoGrupal = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                eliminarPrevisualizacionGrupal() {
                    this.previsualizacionFotoGrupal = null;
                    document.getElementById('fotoComprobanteGrupal').value = '';
                },

                // Procesamiento individual
                async confirmarEnvioIndividual() {
                    if (!this.datosEnvioValidos) {
                        this.mostrarNotificacion('error', 'Complete todos los campos requeridos');
                        return;
                    }

                    const ubicacionId = this.selecciones[this.articuloIdSeleccionado];
                    const fotoComprobante = document.getElementById('fotoComprobante').files[0];

                    if (!confirm(
                        `¿Confirmar envío a provincia?\n\nRepuesto: ${this.repuestoSeleccionadoNombre}\nTransportista: ${this.datosEnvio.transportista}\nVehículo: ${this.datosEnvio.placa_vehiculo}`
                    )) {
                        return;
                    }

                    this.procesandoIndividual[this.articuloIdSeleccionado] = true;

                    try {
                        const formData = new FormData();
                        formData.append('articulo_id', this.articuloIdSeleccionado);
                        formData.append('ubicacion_id', ubicacionId);
                        formData.append('transportista', this.datosEnvio.transportista);
                        formData.append('placa_vehiculo', this.datosEnvio.placa_vehiculo);
                        formData.append('fecha_entrega_transporte', this.datosEnvio.fecha_entrega_transporte);
                        formData.append('observaciones', this.datosEnvio.observaciones);
                        
                        if (fotoComprobante) {
                            formData.append('foto_comprobante', fotoComprobante);
                        }

                        const response = await fetch(
                            `/solicitudrepuestoprovincia/${this.solicitudIdSeleccionada}/aceptar-provincia-individual`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: formData
                            });

                        const data = await response.json();

                        if (data.success) {
                            this.mostrarNotificacion('success', data.message);
                            this.cerrarModalEnvio();
                            
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
                        this.mostrarNotificacion('error', 'Error al procesar el envío');
                    } finally {
                        this.procesandoIndividual[this.articuloIdSeleccionado] = false;
                    }
                },

                // Procesamiento grupal
                async confirmarEnvioGrupal() {
                    if (!this.datosEnvioGrupalValidos) {
                        this.mostrarNotificacion('error', 'Complete todos los campos requeridos');
                        return;
                    }

                    const fotoComprobante = document.getElementById('fotoComprobanteGrupal').files[0];

                    if (!confirm(
                        `¿Confirmar envío grupal a provincia?\n\nTransportista: ${this.datosEnvioGrupal.transportista}\nVehículo: ${this.datosEnvioGrupal.placa_vehiculo}\n\nTodos los repuestos serán procesados.`
                    )) {
                        return;
                    }

                    this.isLoadingGrupal = true;

                    try {
                        const formData = new FormData();
                        formData.append('ubicaciones', JSON.stringify(this.selecciones));
                        formData.append('transportista', this.datosEnvioGrupal.transportista);
                        formData.append('placa_vehiculo', this.datosEnvioGrupal.placa_vehiculo);
                        formData.append('fecha_entrega_transporte', this.datosEnvioGrupal.fecha_entrega_transporte);
                        formData.append('observaciones', this.datosEnvioGrupal.observaciones);
                        
                        if (fotoComprobante) {
                            formData.append('foto_comprobante', fotoComprobante);
                        }

                        const response = await fetch(
                            `/solicitudrepuestoprovincia/${this.solicitudIdSeleccionada}/aceptar-provincia`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: formData
                            });

                        const data = await response.json();

                        if (data.success) {
                            this.mostrarNotificacion('success', data.message);
                            this.cerrarModalEnvioGrupal();
                            
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            this.mostrarNotificacion('error', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.mostrarNotificacion('error', 'Error al procesar el envío grupal');
                    } finally {
                        this.isLoadingGrupal = false;
                    }
                },

                mostrarNotificacion(tipo, mensaje) {
                    const iconos = {
                        success: '<i class="fas fa-check-circle mr-2"></i>',
                        error: '<i class="fas fa-exclamation-triangle mr-2"></i>',
                        info: '<i class="fas fa-info-circle mr-2"></i>'
                    };

                    const alerta = document.createElement('div');
                    alerta.className = `fixed top-4 right-4 p-4 rounded-xl shadow-lg z-50 transform transition-all duration-300 ${
                        tipo === 'success' ? 'bg-green-500 text-white' :
                        tipo === 'error' ? 'bg-red-500 text-white' :
                        'bg-blue-500 text-white'
                    }`;
                    alerta.innerHTML = `
                        <div class="flex items-center space-x-2">
                            ${iconos[tipo]}
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

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-layout.default>