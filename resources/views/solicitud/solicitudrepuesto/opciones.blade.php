<x-layout.default>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div x-data="solicitudRepuestoOpciones()" class="min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-7xl">

            <!-- Header Mejorado -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-green-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-tools text-white text-lg"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Gestión de Repuestos</h1>
                                <p class="text-gray-600 text-lg">Procese repuestos individualmente o en grupo</p>
                            </div>
                        </div>

                        <!-- Resumen de Progreso -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">

                            <!-- Código de Solicitud -->
                            <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-hashtag text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Código</p>
                                    <p class="font-semibold text-gray-900">{{ $solicitud->codigo }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-green-600 font-bold text-sm">{{ $repuestos_procesados }}/{{ $total_repuestos }}</span>
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

                            <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-xl">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-blue-600 font-bold text-sm">{{ $repuestos_disponibles }}/{{ $total_repuestos }}</span>
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
                                    <i class="fas fa-info-circle text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Estado General</p>
                                    <p class="font-semibold text-gray-900 capitalize">
                                        @if ($solicitud->estado == 'aprobada')
                                            Aprobada
                                        @elseif($repuestos_procesados > 0)
                                            Parcial
                                        @else
                                            Pendiente
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Solicitante y Técnico -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($solicitante)
                            <div class="flex items-center space-x-3 p-4 bg-purple-50 rounded-xl">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-tie text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Solicitante</p>
                                    <p class="font-semibold text-gray-900">{{ $solicitante->Nombre }} {{ $solicitante->apellidoPaterno }}</p>
                                </div>
                            </div>
                            @endif

                            @if($tecnico)
                            <div class="flex items-center space-x-3 p-4 bg-indigo-50 rounded-xl">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-cog text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Técnico Asignado</p>
                                    <p class="font-semibold text-gray-900">{{ $tecnico->Nombre }} {{ $tecnico->apellidoPaterno }}</p>
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
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            @else
                <!-- Panel Principal de Gestión -->
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-blue-600 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-clipboard-list text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Repuestos</h2>
                                <p class="text-blue-100 text-base">Seleccione ubicaciones y procese los repuestos</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <!-- Formulario para selección -->
                        <form id="formUbicaciones" @submit.prevent="procesarSeleccion">
                            <!-- Tabla Mejorada -->
                            <div class="overflow-hidden rounded-2xl border border-slate-200/60 shadow-sm mb-8">
                                <table class="w-full">
                                  <!-- En el thead de la tabla, agregar nueva columna -->
<thead class="bg-gradient-to-r from-slate-50 to-blue-50/30">
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
            <i class="fas fa-user-check mr-1 text-slate-500"></i>
            Entregado A
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
        <tr class="transition-all duration-200 hover:bg-blue-50/30 @if ($repuesto->ya_procesado) bg-emerald-50/50 @endif">
            <!-- Información del Repuesto -->
            <td class="px-8 py-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cog text-blue-600"></i>
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
                <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-blue-100 text-blue-700">
                    <i class="fas fa-sort-numeric-up mr-1"></i>
                    {{ $repuesto->cantidad_solicitada }} unidades
                </span>
            </td>

            <!-- Stock Disponible -->
            <td class="px-6 py-6">
                <div class="text-center">
                    <span class="text-lg font-bold @if ($repuesto->suficiente_stock) text-emerald-600 @else text-rose-600 @endif">
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
                            <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-emerald-100 text-emerald-700">
                                <i class="fas fa-check-circle mr-1"></i>
                                Procesado
                            </span>
                        </div>
                    @elseif(isset($repuesto->ubicaciones_detalle) && count($repuesto->ubicaciones_detalle) > 0)
                        <select name="ubicaciones[{{ $repuesto->idArticulos }}]"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white/50 backdrop-blur-sm"
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

          <!-- NUEVA COLUMNA: Entregado A -->
<td class="px-6 py-6">
    @if ($repuesto->ya_procesado)
        @php
            // Obtener información de a quién se entregó este repuesto
            $entregaInfo = DB::table('repuestos_entregas as re')
                ->select(
                    're.tipo_entrega',
                    're.usuario_destino_id',
                    'u.Nombre',
                    'u.apellidoPaterno',
                    'u.apellidoMaterno'
                )
                ->leftJoin('usuarios as u', 're.usuario_destino_id', '=', 'u.idUsuario')
                ->where('re.solicitud_id', $solicitud->idsolicitudesordenes)
                ->where('re.articulo_id', $repuesto->idArticulos)
                ->first();
        @endphp

        @if($entregaInfo && $entregaInfo->usuario_destino_id)
            <div class="text-center">
                @switch($entregaInfo->tipo_entrega)
                    @case('solicitante')
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-user-tie text-blue-600"></i>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">
                                    {{ $entregaInfo->Nombre }} {{ $entregaInfo->apellidoPaterno }}
                                </p>
                                <p class="text-xs text-slate-500">Solicitante</p>
                            </div>
                        </div>
                        @break
                    
                    @case('tecnico')
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-user-cog text-green-600"></i>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">
                                    {{ $entregaInfo->Nombre }} {{ $entregaInfo->apellidoPaterno }}
                                </p>
                                <p class="text-xs text-slate-500">Técnico</p>
                            </div>
                        </div>
                        @break
                    
                    @case('otro_usuario')
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-users text-orange-600"></i>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">
                                    {{ $entregaInfo->Nombre }} {{ $entregaInfo->apellidoPaterno }}
                                </p>
                                <p class="text-xs text-slate-500">Otro usuario</p>
                            </div>
                        </div>
                        @break
                    
                    @default
                        <span class="text-slate-500 text-sm">No especificado</span>
                @endswitch
            </div>
        @else
            <!-- MOSTRAR "Pendiente por asignar" cuando está procesado pero no tiene info de entrega -->
            <div class="text-center">
                <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-purple-100 text-purple-700">
                    <i class="fas fa-user-clock mr-1"></i>
                    Pendiente por asignar
                </span>
            </div>
        @endif
    @else
        <!-- Cuando NO está procesado -->
        <div class="text-center">
            <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-amber-100 text-amber-700">
                <i class="fas fa-clock mr-1"></i>
                Pendiente Por Asignar
            </span>
        </div>
    @endif
</td>

            <!-- Estado -->
            <td class="px-6 py-6">
                @if ($repuesto->ya_procesado)
                    <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-success text-white border border-green-200 shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i>
                        Completado
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
                        Completado
                    </span>
                @elseif($repuesto->suficiente_stock)
                    @if(\App\Helpers\PermisoHelper::tienePermiso('PROCESAR REPUESTO INDIVIDUAL'))
                    <button type="button"
                        @click="abrirModalDestinatario({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }}, '{{ $repuesto->nombre }}')"
                        :disabled="!selecciones[{{ $repuesto->idArticulos }}] || procesandoIndividual[{{ $repuesto->idArticulos }}]"
                        :class="{
                            'opacity-50 cursor-not-allowed': !selecciones[{{ $repuesto->idArticulos }}] || procesandoIndividual[{{ $repuesto->idArticulos }}],
                            'bg-blue-600 hover:bg-blue-700': selecciones[{{ $repuesto->idArticulos }}] && !procesandoIndividual[{{ $repuesto->idArticulos }}]
                        }"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-slate-400 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                        <span x-show="!procesandoIndividual[{{ $repuesto->idArticulos }}]">
                            <i class="fas fa-play-circle mr-2"></i>
                            Procesar
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
                                <h2 class="text-2xl font-bold text-white">Estrategias de Procesamiento</h2>
                                <p class="text-slate-300 text-base">Elija el método que mejor se adapte a sus
                                    necesidades</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <!-- Procesamiento Individual -->
                            <div
                                class="group bg-white rounded-2xl p-8 border border-blue-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start gap-4 mb-6">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 rounded-2xl flex items-center justify-center shadow-inner">
                                        <i class="fas fa-user-cog text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">Procesamiento
                                            Individual</h3>
                                        <p class="text-slate-600 text-sm mt-1">Procese cada repuesto de forma
                                            independiente según necesidad.</p>
                                    </div>
                                </div>

                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mr-3 flex-shrink-0"></i>
                                        Seleccione ubicación específica para cada repuesto.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mr-3 flex-shrink-0"></i>
                                        Elija a qué usuario entregar cada repuesto.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mr-3 flex-shrink-0"></i>
                                        Ideal para procesamiento parcial o selectivo.
                                    </li>
                                </ul>

                                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-center">
                                    <p class="text-sm font-semibold text-blue-700">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        Progreso: <span class="font-bold">{{ $repuestos_procesados }}</span> de
                                        <span class="font-bold">{{ $total_repuestos }}</span> repuestos
                                    </p>
                                </div>
                            </div>

                            <!-- Procesamiento Grupal -->
                            <div
                                class="group bg-white rounded-2xl p-8 border border-emerald-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start gap-4 mb-6">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center shadow-inner">
                                        <i class="fas fa-users-cog text-emerald-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">Procesamiento
                                            Grupal</h3>
                                        <p class="text-slate-600 text-sm mt-1">Procese todos los repuestos en una sola
                                            acción eficiente.</p>
                                    </div>
                                </div>

                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                        Seleccione ubicaciones para todos los repuestos.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                        Procese todo el lote con un solo clic.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                        Requiere stock completo en todos los repuestos.
                                    </li>
                                </ul>

                                <div class="space-y-4">
                                    @if(App\Helpers\PermisoHelper::tienePermiso('PROCESAR REPUESTO GRUPAL'))
                                    <button @click="validarYProcesarGrupal({{ $solicitud->idsolicitudesordenes }})"
                                        :disabled="isLoadingGrupal || !todasUbicacionesSeleccionadas || !todosDisponibles"
                                        :class="{
                                            'opacity-50 cursor-not-allowed': isLoadingGrupal || !
                                                todasUbicacionesSeleccionadas || !todosDisponibles,
                                            'bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 shadow-lg hover:shadow-xl': todasUbicacionesSeleccionadas &&
                                                todosDisponibles,
                                            'bg-gradient-to-r from-slate-400 to-slate-500': !
                                                todasUbicacionesSeleccionadas || !todosDisponibles
                                        }"
                                        class="w-full flex items-center justify-center px-8 py-4 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                        <i x-show="!isLoadingGrupal" class="fas fa-play-circle mr-3"></i>
                                        <i x-show="isLoadingGrupal" class="fas fa-spinner fa-spin mr-3"></i>
                                        <span
                                            x-text="isLoadingGrupal ? 'Procesando...' : 'Procesar Todo el Lote'"></span>
                                    </button>
                                    @endif

                                    <div
                                        class="text-center text-sm font-semibold @if ($puede_aceptar) text-success @else text-danger @endif">
                                        @if ($puede_aceptar)
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Condiciones óptimas para procesamiento grupal
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
                            class="mt-10 p-6 bg-gradient-to-r from-blue-50 to-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                <!-- Estado -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex-shrink-0 w-11 h-11 bg-blue-100 rounded-2xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>

                                    <div>
                                        @if ($repuestos_procesados == $total_repuestos)
                                            <p class="text-sm font-semibold text-green-600 flex items-center gap-1">
                                                <i class="fas fa-check-circle"></i>
                                                Procesamiento completado exitosamente
                                            </p>
                                        @else
                                            <p class="text-sm font-semibold text-blue-600 flex items-center gap-1">
                                                <i class="fas fa-chart-bar"></i>
                                                Progreso general:
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
                            <a href="{{ route('solicitudrepuesto.conformidad-pdf', $solicitud->idsolicitudesordenes) }}"
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
                                    Volver al Listado Principal
                                </a>


        </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal para seleccionar destinatario -->
        <div x-show="mostrarModalDestinatario" 
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all duration-300 scale-100"
                 x-show="mostrarModalDestinatario"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <!-- Header del Modal -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Seleccionar Destinatario</h3>
                                <p class="text-blue-100 text-sm" x-text="repuestoSeleccionadoNombre"></p>
                            </div>
                        </div>
                        <button @click="cerrarModalDestinatario" class="text-white hover:text-blue-200 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Seleccione a quién se le entregará el repuesto:</p>
                    
                    <!-- Opción 1: Solicitante -->
                    <div class="mb-4">
                        <label class="flex items-start space-x-3 p-4 border-2 border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition-all duration-200"
                               :class="{ 'border-blue-500 bg-blue-50': destinatarioSeleccionado === 'solicitante' }">
                            <input type="radio" 
                                   x-model="destinatarioSeleccionado" 
                                   value="solicitante" 
                                   class="mt-1 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <i class="fas fa-user-tie text-blue-600"></i>
                                    <span class="font-semibold text-gray-900">Entregar al Solicitante</span>
                                </div>
                                <p class="text-sm text-gray-600">
                                    @if($solicitante)
                                        {{ $solicitante->Nombre }} {{ $solicitante->apellidoPaterno }}
                                    @else
                                        Usuario que realizó la solicitud
                                    @endif
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Opción 2: Técnico -->
                    @if($tecnico)
                    <div class="mb-4">
                        <label class="flex items-start space-x-3 p-4 border-2 border-green-200 rounded-xl hover:border-green-400 hover:bg-green-50 cursor-pointer transition-all duration-200"
                               :class="{ 'border-green-500 bg-green-50': destinatarioSeleccionado === 'tecnico' }">
                            <input type="radio" 
                                   x-model="destinatarioSeleccionado" 
                                   value="tecnico" 
                                   class="mt-1 text-green-600 focus:ring-green-500">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <i class="fas fa-user-cog text-green-600"></i>
                                    <span class="font-semibold text-gray-900">Entregar al Técnico</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $tecnico->Nombre }} {{ $tecnico->apellidoPaterno }}</p>
                            </div>
                        </label>
                    </div>
                    @endif

                    <!-- Opción 3: Otro Usuario -->
                    <div class="mb-6">
                        <label class="flex items-start space-x-3 p-4 border-2 border-orange-200 rounded-xl hover:border-orange-400 hover:bg-orange-50 cursor-pointer transition-all duration-200"
                               :class="{ 'border-orange-500 bg-orange-50': destinatarioSeleccionado === 'otro' }">
                            <input type="radio" 
                                   x-model="destinatarioSeleccionado" 
                                   value="otro" 
                                   class="mt-1 text-orange-600 focus:ring-orange-500">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <i class="fas fa-users text-orange-600"></i>
                                    <span class="font-semibold text-gray-900">Otro Usuario</span>
                                </div>
                                <p class="text-sm text-gray-600">Seleccionar un usuario diferente</p>
                                
                                <!-- Select de usuarios (solo visible cuando se selecciona "otro") -->
                                <div x-show="destinatarioSeleccionado === 'otro'" class="mt-3">
                                    <select x-model="usuarioSeleccionado"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        <option value="">Seleccione un usuario</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->idUsuario }}">
                                                {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }} - {{ $usuario->correo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex space-x-3">
                        <button @click="cerrarModalDestinatario"
                                class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button @click="confirmarProcesamientoIndividual"
                                :disabled="!destinatarioValido"
                                :class="{
                                    'bg-blue-600 hover:bg-blue-700': destinatarioValido,
                                    'bg-gray-400 cursor-not-allowed': !destinatarioValido
                                }"
                                class="flex-1 px-4 py-2.5 text-white rounded-xl font-medium transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>
                            Confirmar Entrega
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuestoOpciones', () => ({
                selecciones: {},
                procesandoIndividual: {},
                isLoadingGrupal: false,
                
                // Nuevas variables para el modal de destinatario
                mostrarModalDestinatario: false,
                destinatarioSeleccionado: '',
                usuarioSeleccionado: '',
                solicitudIdSeleccionada: null,
                articuloIdSeleccionado: null,
                repuestoSeleccionadoNombre: '',

                get destinatarioValido() {
                    if (this.destinatarioSeleccionado === 'solicitante' || this.destinatarioSeleccionado === 'tecnico') {
                        return true;
                    }
                    if (this.destinatarioSeleccionado === 'otro') {
                        return this.usuarioSeleccionado !== '';
                    }
                    return false;
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

                abrirModalDestinatario(solicitudId, articuloId, nombreRepuesto) {
                    const ubicacionId = this.selecciones[articuloId];
                    
                    if (!ubicacionId) {
                        this.mostrarNotificacion('error', 'Seleccione una ubicación para este repuesto');
                        return;
                    }

                    this.solicitudIdSeleccionada = solicitudId;
                    this.articuloIdSeleccionado = articuloId;
                    this.repuestoSeleccionadoNombre = nombreRepuesto;
                    this.destinatarioSeleccionado = '';
                    this.usuarioSeleccionado = '';
                    this.mostrarModalDestinatario = true;
                },

                cerrarModalDestinatario() {
                    this.mostrarModalDestinatario = false;
                    this.destinatarioSeleccionado = '';
                    this.usuarioSeleccionado = '';
                },

                async confirmarProcesamientoIndividual() {
                    if (!this.destinatarioValido) {
                        this.mostrarNotificacion('error', 'Seleccione un destinatario válido');
                        return;
                    }

                    const ubicacionId = this.selecciones[this.articuloIdSeleccionado];

                    if (!confirm(
                        `¿Está seguro de que desea procesar este repuesto?\n\nRepuesto: ${this.repuestoSeleccionadoNombre}\nDestinatario: ${this.obtenerNombreDestinatario()}\n\nEl stock será descontado de la ubicación seleccionada.`
                    )) {
                        return;
                    }

                    this.procesandoIndividual[this.articuloIdSeleccionado] = true;
                    this.mostrarModalDestinatario = false;

                    try {
                        const response = await fetch(
                            `/solicitudrepuesto/${this.solicitudIdSeleccionada}/aceptar-individual`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    articulo_id: this.articuloIdSeleccionado,
                                    ubicacion_id: ubicacionId,
                                    tipo_destinatario: this.destinatarioSeleccionado,
                                    usuario_destino_id: this.destinatarioSeleccionado === 'otro' ? this.usuarioSeleccionado : null
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
                        this.mostrarNotificacion('error', 'Error al procesar el repuesto');
                    } finally {
                        this.procesandoIndividual[this.articuloIdSeleccionado] = false;
                    }
                },

                obtenerNombreDestinatario() {
                    switch (this.destinatarioSeleccionado) {
                        case 'solicitante':
                            return '{{ $solicitante ? $solicitante->Nombre . " " . $solicitante->apellidoPaterno : "Solicitante" }}';
                        case 'tecnico':
                            return '{{ $tecnico ? $tecnico->Nombre . " " . $tecnico->apellidoPaterno : "Técnico" }}';
                        case 'otro':
                            const select = document.querySelector('[x-model="usuarioSeleccionado"]');
                            return select ? select.options[select.selectedIndex]?.text : 'Otro usuario';
                        default:
                            return 'No seleccionado';
                    }
                },

                async validarYProcesarGrupal(id) {
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

                    if (!confirm(
                            '¿Está seguro de que desea procesar TODOS los repuestos?\n\nEl stock será descontado de las ubicaciones seleccionadas para cada repuesto.'
                        )) {
                        return;
                    }

                    this.isLoadingGrupal = true;

                    try {
                        const response = await fetch(`/solicitudrepuesto/${id}/aceptar`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
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