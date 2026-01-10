<x-layout.default>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div x-data="solicitudRepuestoOpciones()" class="min-h-screen py-8">
        <div class="mx-auto w-full px-4">
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline">Solicitudes</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Gestión de Repuestos</span>
                    </li>
                </ul>
            </div>
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
                                        @elseif($solicitud->estado == 'listo_para_entregar')
                                            Listo para Entregar
                                        @elseif($solicitud->estado == 'pendiente')
                                            Pendiente
                                        @elseif($solicitud->estado == 'parcial_listo')
                                            Parcialmente Listo
                                        @else
                                            {{ $solicitud->estado }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Solicitante y Técnico -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if ($solicitante)
                                <div class="flex items-center space-x-3 p-4 bg-purple-50 rounded-xl">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-tie text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Solicitante</p>
                                        <p class="font-semibold text-gray-900">{{ $solicitante->Nombre }}
                                            {{ $solicitante->apellidoPaterno }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($tecnico)
                                <div class="flex items-center space-x-3 p-4 bg-indigo-50 rounded-xl">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-cog text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Técnico Asignado</p>
                                        <p class="font-semibold text-gray-900">{{ $tecnico->Nombre }}
                                            {{ $tecnico->apellidoPaterno }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verificar si hay repuestos -->
            @if (!$repuestos || $repuestos->count() == 0)
                <!-- Mensaje cuando no hay repuestos - Ya es responsive -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-8">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-4 sm:px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-white">No hay repuestos</h2>
                                <p class="text-yellow-100 text-xs sm:text-sm">No se encontraron repuestos en esta
                                    solicitud</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8 text-center">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-cogs text-yellow-500 text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">No se encontraron repuestos
                        </h3>
                        <p class="text-gray-600 text-sm sm:text-base mb-6">Esta solicitud no contiene repuestos para
                            gestionar.</p>
                        <a href="{{ route('solicitudrepuesto.index') }}"
                            class="inline-flex items-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105 text-sm sm:text-base">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            @else
                <!-- Panel Principal de Gestión - Responsive -->
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-3xl sm:rounded-3xl shadow-xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-blue-600 px-4 sm:px-8 py-4 sm:py-6">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-clipboard-list text-white text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold text-white">Gestión de Repuestos</h2>
                                <p class="text-blue-100 text-sm sm:text-base">Seleccione ubicaciones y procese los
                                    repuestos</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-8">
                        <!-- Formulario para selección -->
                        <form id="formUbicaciones" @submit.prevent="procesarSeleccion">
                            <!-- Tabla Mejorada - Responsive -->
                            <div class="overflow-x-auto rounded-2xl border border-slate-200/60 shadow-sm mb-8">
                                <table class="w-full min-w-[900px] lg:min-w-full">
                                    <!-- En el thead de la tabla, agregar nueva columna -->
                                    <thead class="bg-gradient-to-r from-slate-50 to-blue-50/30">
                                        <tr>
                                            <th
                                                class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-cog text-slate-500"></i>
                                                    <span class="hidden sm:inline">Repuesto</span>
                                                </div>
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <span class="hidden sm:inline">Solicitado</span>
                                                <span class="sm:hidden">Sol.</span>
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <span class="hidden sm:inline">Disponible</span>
                                                <span class="sm:hidden">Disp.</span>
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-map-marker-alt text-slate-500 mr-1"></i>
                                                <span class="hidden sm:inline">Ubicación</span>
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-user-check text-slate-500 mr-1"></i>
                                                <span class="hidden sm:inline">Entregado A</span>
                                                <span class="sm:hidden">Destino</span>
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-tasks text-slate-500 mr-1"></i>
                                                <span class="hidden sm:inline">Estado</span>
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-play-circle text-slate-500 mr-1"></i>
                                                <span class="hidden sm:inline">Acción</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200/40">
                                        @foreach ($repuestos as $repuesto)
                                            <tr
                                                class="transition-all duration-200 hover:bg-blue-50/30 @if ($repuesto->ya_procesado) bg-emerald-50/50 @endif">
                                                <!-- Información del Repuesto -->
                                                <td class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                                                    <div class="flex items-center space-x-3 sm:space-x-4">
                                                        <div
                                                            class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                            <i
                                                                class="fas fa-cog text-blue-600 text-sm sm:text-base"></i>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="font-semibold text-slate-900 text-sm sm:text-base truncate"
                                                                title="{{ $repuesto->nombre }}">
                                                                {{ $repuesto->nombre }}
                                                            </p>
                                                            <p class="text-xs text-slate-500 mt-1 truncate"
                                                                title="{{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}">
                                                                {{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}
                                                            </p>
                                                            <p class="text-xs text-slate-400 font-medium truncate"
                                                                title="{{ $repuesto->tipo_repuesto }}">
                                                                {{ $repuesto->tipo_repuesto }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Cantidad Solicitada -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-blue-100 text-blue-700">
                                                        <i class="fas fa-sort-numeric-up mr-1 hidden sm:inline"></i>
                                                        {{ $repuesto->cantidad_solicitada }} <span
                                                            class="hidden sm:inline ml-1">unidades</span>
                                                        <span class="sm:hidden">uds</span>
                                                    </span>
                                                </td>

                                                <!-- Stock Disponible -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    <div class="text-center">
                                                        <span
                                                            class="text-base sm:text-lg font-bold @if ($repuesto->suficiente_stock) text-emerald-600 @else text-rose-600 @endif">
                                                            {{ $repuesto->stock_disponible }}
                                                        </span>
                                                        <span class="text-xs sm:text-sm text-slate-500 block">
                                                            <span class="hidden sm:inline">disponibles</span>
                                                            <span class="sm:hidden">disp</span>
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- Selección de Ubicación -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    <div class="max-w-[180px] sm:max-w-xs">
                                                        @if ($repuesto->ya_procesado)
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-emerald-100 text-emerald-700">
                                                                    <i class="fas fa-check-circle mr-1"></i>
                                                                    <span class="hidden sm:inline">Procesado</span>
                                                                    <span class="sm:hidden">OK</span>
                                                                </span>
                                                            </div>
                                                        @elseif(isset($repuesto->ubicaciones_detalle) && count($repuesto->ubicaciones_detalle) > 0)
                                                            <select name="ubicaciones[{{ $repuesto->idArticulos }}]"
                                                                class="w-full border border-slate-300 rounded-xl px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                                                x-model="selecciones[{{ $repuesto->idArticulos }}]"
                                                                :disabled="procesandoIndividual[{{ $repuesto->idArticulos }}]">
                                                                <option value="">
                                                                    <i
                                                                        class="fas fa-map-marker-alt mr-2 hidden sm:inline"></i>
                                                                    <span class="text-xs sm:text-sm">Seleccione
                                                                        ubicación</span>
                                                                </option>
                                                                @foreach ($repuesto->ubicaciones_detalle as $ubicacion)
                                                                    <option
                                                                        value="{{ $ubicacion->rack_ubicacion_id }}"
                                                                        class="text-xs sm:text-sm">
                                                                        {{ $ubicacion->ubicacion_codigo }}
                                                                        <span
                                                                            class="hidden sm:inline">({{ $ubicacion->stock_ubicacion }}
                                                                            uds)</span>
                                                                        <span
                                                                            class="sm:hidden">({{ $ubicacion->stock_ubicacion }})</span>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <p
                                                                class="text-xs sm:text-sm text-rose-500 italic font-medium">
                                                                <i class="fas fa-times-circle mr-1"></i>
                                                                <span class="hidden sm:inline">Sin ubicaciones</span>
                                                                <span class="sm:hidden">Sin ubic.</span>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- NUEVA COLUMNA: Entregado A -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    @if ($repuesto->ya_procesado)
                                                        @php
                                                            // Obtener información de a quién se entregó este repuesto
                                                            $entregaInfo = DB::table('repuestos_entregas as re')
                                                                ->select(
                                                                    're.tipo_entrega',
                                                                    're.usuario_destino_id',
                                                                    'u.Nombre',
                                                                    'u.apellidoPaterno',
                                                                    'u.apellidoMaterno',
                                                                )
                                                                ->leftJoin(
                                                                    'usuarios as u',
                                                                    're.usuario_destino_id',
                                                                    '=',
                                                                    'u.idUsuario',
                                                                )
                                                                ->where(
                                                                    're.solicitud_id',
                                                                    $solicitud->idsolicitudesordenes,
                                                                )
                                                                ->where('re.articulo_id', $repuesto->idArticulos)
                                                                ->first();
                                                        @endphp

                                                        @if ($entregaInfo && $entregaInfo->usuario_destino_id)
                                                            <div class="text-center min-w-[120px]">
                                                                @switch($entregaInfo->tipo_entrega)
                                                                    @case('solicitante')
                                                                        <div
                                                                            class="flex items-center justify-center space-x-2">
                                                                            <i class="fas fa-user-tie text-blue-600"></i>
                                                                            <div class="hidden sm:block">
                                                                                <p
                                                                                    class="font-semibold text-slate-900 text-xs sm:text-sm truncate">
                                                                                    {{ $entregaInfo->Nombre }}
                                                                                    {{ $entregaInfo->apellidoPaterno }}
                                                                                </p>
                                                                                <p class="text-xs text-slate-500">Solicitante
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="sm:hidden text-xs font-semibold text-slate-900 truncate">
                                                                            {{ substr($entregaInfo->Nombre, 0, 1) }}.
                                                                            {{ $entregaInfo->apellidoPaterno }}
                                                                        </div>
                                                                    @break

                                                                    @case('tecnico')
                                                                        <div
                                                                            class="flex items-center justify-center space-x-2">
                                                                            <i class="fas fa-user-cog text-green-600"></i>
                                                                            <div class="hidden sm:block">
                                                                                <p
                                                                                    class="font-semibold text-slate-900 text-xs sm:text-sm truncate">
                                                                                    {{ $entregaInfo->Nombre }}
                                                                                    {{ $entregaInfo->apellidoPaterno }}
                                                                                </p>
                                                                                <p class="text-xs text-slate-500">Técnico</p>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="sm:hidden text-xs font-semibold text-slate-900 truncate">
                                                                            {{ substr($entregaInfo->Nombre, 0, 1) }}.
                                                                            {{ $entregaInfo->apellidoPaterno }}
                                                                        </div>
                                                                    @break

                                                                    @case('otro_usuario')
                                                                        <div
                                                                            class="flex items-center justify-center space-x-2">
                                                                            <i class="fas fa-users text-orange-600"></i>
                                                                            <div class="hidden sm:block">
                                                                                <p
                                                                                    class="font-semibold text-slate-900 text-xs sm:text-sm truncate">
                                                                                    {{ $entregaInfo->Nombre }}
                                                                                    {{ $entregaInfo->apellidoPaterno }}
                                                                                </p>
                                                                                <p class="text-xs text-slate-500">Otro usuario
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="sm:hidden text-xs font-semibold text-slate-900 truncate">
                                                                            {{ substr($entregaInfo->Nombre, 0, 1) }}.
                                                                            {{ $entregaInfo->apellidoPaterno }}
                                                                        </div>
                                                                    @break

                                                                    @default
                                                                        <span class="text-slate-500 text-xs sm:text-sm">
                                                                            <span class="hidden sm:inline">No
                                                                                especificado</span>
                                                                            <span class="sm:hidden">N/E</span>
                                                                        </span>
                                                                @endswitch
                                                            </div>
                                                        @else
                                                            <!-- MOSTRAR "Pendiente por asignar" cuando está procesado pero no tiene info de entrega -->
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-purple-100 text-purple-700">
                                                                    <i class="fas fa-user-clock mr-1"></i>
                                                                    <span class="hidden sm:inline">Pendiente</span>
                                                                    <span class="sm:hidden">Pend.</span>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <!-- Cuando NO está procesado -->
                                                        <div class="text-center">
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-amber-100 text-amber-700">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                <span class="hidden sm:inline">Pendiente</span>
                                                                <span class="sm:hidden">Pend.</span>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>

                                                <!-- Estado -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    @if ($repuesto->ya_procesado)
                                                        @if ($repuesto->estado_actual == 'entregado')
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-success text-white border border-green-200 shadow-sm">
                                                                <i class="fas fa-check-circle mr-1"></i>
                                                                <span class="hidden sm:inline">Entregado</span>
                                                                <span class="sm:hidden">Entreg.</span>
                                                            </span>
                                                        @elseif($repuesto->estado_actual == 'pendiente_entrega')
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-warning text-white border border-yellow-200 shadow-sm">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                <span class="hidden sm:inline">Listo para
                                                                    Entregar</span>
                                                                <span class="sm:hidden">Listo</span>
                                                            </span>
                                                        @endif
                                                    @elseif($repuesto->suficiente_stock)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-info text-white border border-blue-200 shadow-sm">
                                                            <i class="fas fa-cog mr-1"></i>
                                                            <span class="hidden sm:inline">Pendiente</span>
                                                            <span class="sm:hidden">Pend.</span>
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-danger text-white border border-red-200 shadow-sm">
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                            <span class="hidden sm:inline">Insuficiente</span>
                                                            <span class="sm:hidden">Ins.</span>
                                                        </span>
                                                    @endif
                                                </td>

                                                <!-- Acción -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    @if ($repuesto->ya_procesado)
                                                        @if ($repuesto->estado_actual == 'entregado')
                                                            <span
                                                                class="text-green-600 font-semibold text-xs sm:text-sm">
                                                                <i class="fas fa-check-circle mr-1"></i>
                                                                <span class="hidden sm:inline">Entregado</span>
                                                                <span class="sm:hidden">Entreg.</span>
                                                            </span>
                                                        @elseif($repuesto->estado_actual == 'pendiente_entrega')
                                                            <div class="space-y-2">

                                                                <!-- Botón para confirmar entrega física -->
                                                                <button type="button"
                                                                    @click="confirmarEntregaFisica({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }})"
                                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-1 sm:py-1.5 rounded-lg font-medium transition-all duration-300 flex items-center justify-center text-xs sm:text-sm w-full">
                                                                    <i class="fas fa-truck mr-1 sm:mr-2"></i>
                                                                    <span class="hidden sm:inline">Confirmar
                                                                        Entrega</span>
                                                                    <span class="sm:hidden">Entregar</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @elseif($repuesto->suficiente_stock)
                                                        @if (\App\Helpers\PermisoHelper::tienePermiso('PROCESAR REPUESTO INDIVIDUAL'))
                                                            <button type="button"
                                                                @click="abrirModalDestinatario({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }}, '{{ $repuesto->nombre }}')"
                                                                :disabled="!selecciones[{{ $repuesto->idArticulos }}] ||
                                                                    procesandoIndividual[{{ $repuesto->idArticulos }}]"
                                                                :class="{
                                                                    'btn btn-primary': !selecciones[
                                                                            {{ $repuesto->idArticulos }}] ||
                                                                        procesandoIndividual[
                                                                            {{ $repuesto->idArticulos }}],
                                                                    'bg-blue-600 hover:bg-blue-700': selecciones[
                                                                            {{ $repuesto->idArticulos }}] && !
                                                                        procesandoIndividual[
                                                                            {{ $repuesto->idArticulos }}]
                                                                }"
                                                                class="bg-blue-600 hover:bg-blue-700 disabled:bg-slate-400 text-white px-3 sm:px-5 py-1.5 sm:py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg text-xs sm:text-sm">
                                                                <span
                                                                    x-show="!procesandoIndividual[{{ $repuesto->idArticulos }}]">
                                                                    <i class="fas fa-play-circle mr-1 sm:mr-2"></i>
                                                                    <span class="hidden sm:inline">Marcar como
                                                                        Listo</span>
                                                                    <span class="sm:hidden">Listo</span>
                                                                </span>
                                                                <span
                                                                    x-show="procesandoIndividual[{{ $repuesto->idArticulos }}]"
                                                                    class="flex items-center space-x-1 sm:space-x-2">
                                                                    <i class="fas fa-spinner fa-spin mr-1 sm:mr-2"></i>
                                                                    <span class="hidden sm:inline">Procesando...</span>
                                                                    <span class="sm:hidden">...</span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button disabled
                                                            class="px-3 sm:px-6 py-1.5 sm:py-3 bg-gray-300 text-gray-600 rounded-xl font-semibold cursor-not-allowed border border-gray-300 text-xs sm:text-sm">
                                                            <i class="fas fa-ban mr-1 sm:mr-2"></i>
                                                            <span class="hidden sm:inline">Sin Stock</span>
                                                            <span class="sm:hidden">Sin</span>
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

                <!-- Panel de Estrategias de Procesamiento - Responsive -->
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-3xl sm:rounded-3xl shadow-xl overflow-hidden border border-white/20">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-4 sm:px-8 py-4 sm:py-6">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-rocket text-white text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold text-white">Estrategias de Procesamiento</h2>
                                <p class="text-slate-300 text-xs sm:text-base">Elija el método que mejor se adapte a
                                    sus necesidades</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-10">
                            <!-- Procesamiento Individual -->
                            <div
                                class="group bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-blue-200 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start gap-2 sm:gap-3 mb-4 sm:mb-5">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                                        <i class="fas fa-user-cog text-blue-600 text-sm sm:text-base lg:text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3
                                            class="text-base sm:text-lg lg:text-xl font-bold text-slate-800 tracking-tight mb-1">
                                            Procesamiento Individual
                                        </h3>
                                        <p class="text-xs sm:text-sm text-slate-600">Procese cada repuesto de forma
                                            independiente según necesidad.</p>
                                    </div>
                                </div>

                                <ul class="space-y-1.5 sm:space-y-2 mb-4 sm:mb-6">
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Seleccione ubicación específica para cada repuesto.</span>
                                    </li>
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Elija a qué usuario entregar cada repuesto.</span>
                                    </li>
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Ideal para procesamiento parcial o selectivo.</span>
                                    </li>
                                </ul>

                                <div class="bg-blue-50 rounded-lg sm:rounded-xl p-3 border border-blue-100">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                                        <p class="text-xs sm:text-sm font-semibold text-blue-700">
                                            Progreso:
                                            <span class="font-bold">{{ $repuestos_procesados }}</span> de
                                            <span class="font-bold">{{ $total_repuestos }}</span>
                                            <span class="hidden sm:inline"> repuestos</span>
                                            <span class="sm:hidden"> reps</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Procesamiento Grupal -->
                            <div
                                class="group bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-emerald-200 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start gap-2 sm:gap-3 mb-4 sm:mb-5">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                                        <i
                                            class="fas fa-users-cog text-emerald-600 text-sm sm:text-base lg:text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3
                                            class="text-base sm:text-lg lg:text-xl font-bold text-slate-800 tracking-tight mb-1">
                                            Procesamiento Grupal
                                        </h3>
                                        <p class="text-xs sm:text-sm text-slate-600">Procese todos los repuestos en una
                                            sola acción eficiente.</p>
                                    </div>
                                </div>

                                <ul class="space-y-1.5 sm:space-y-2 mb-4 sm:mb-6">
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Seleccione ubicaciones para todos los repuestos.</span>
                                    </li>
                                    <li class="flex items-center text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Procese todo el lote con un solo clic.</span>
                                    </li>
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Requiere stock completo en todos los repuestos.</span>
                                    </li>
                                </ul>

                                <div class="space-y-3 sm:space-y-4">
                                    @if (App\Helpers\PermisoHelper::tienePermiso('PROCESAR REPUESTO GRUPAL'))
                                        <!-- Verificar si ya están todos procesados -->
                                        @if ($repuestos_procesados == $total_repuestos)
                                            <!-- Botón deshabilitado cuando ya está todo procesado -->
                                            <button disabled
                                                class="w-full flex items-center justify-center px-3 sm:px-6 py-2.5 sm:py-3 bg-gray-300 text-gray-600 rounded-lg sm:rounded-xl font-semibold cursor-not-allowed text-xs sm:text-sm">
                                                <i class="fas fa-check-circle mr-1.5 sm:mr-2"></i>
                                                <span>Todos los repuestos ya están procesados</span>
                                            </button>
                                        @else
                                            <!-- Botón activo solo si no están todos procesados -->
                                            <button
                                                @click="validarYProcesarGrupal({{ $solicitud->idsolicitudesordenes }})"
                                                :disabled="isLoadingGrupal || !todasUbicacionesSeleccionadas || !todosDisponibles"
                                                :class="{
                                                    'opacity-50 cursor-not-allowed': isLoadingGrupal || !
                                                        todasUbicacionesSeleccionadas || !todosDisponibles,
                                                    'bg-success hover:bg-green-600 shadow-md hover:shadow-lg': todasUbicacionesSeleccionadas &&
                                                        todosDisponibles,
                                                    'bg-primary': !todasUbicacionesSeleccionadas || !todosDisponibles
                                                }"
                                                class="w-full flex items-center justify-center px-3 sm:px-6 py-2.5 sm:py-3 text-white rounded-lg sm:rounded-xl font-semibold transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs sm:text-sm">
                                                <i x-show="!isLoadingGrupal"
                                                    class="fas fa-play-circle mr-1.5 sm:mr-2"></i>
                                                <i x-show="isLoadingGrupal"
                                                    class="fas fa-spinner fa-spin mr-1.5 sm:mr-2"></i>
                                                <span
                                                    x-text="isLoadingGrupal ? 'Procesando...' : 'Procesar Todo el Lote'"></span>
                                            </button>
                                        @endif
                                    @endif

                                    <div
                                        class="text-center text-xs sm:text-sm font-semibold @if ($puede_aceptar) text-success @else text-danger @endif">
                                        @if ($repuestos_procesados == $total_repuestos)
                                            <div class="flex items-center justify-center gap-1">
                                                <i class="fas fa-check-double text-success"></i>
                                                <span class="hidden sm:inline">Todos los repuestos ya fueron
                                                    procesados</span>
                                                <span class="sm:hidden">Completamente procesado</span>
                                            </div>
                                        @elseif($puede_aceptar)
                                            <div class="flex items-center justify-center gap-1">
                                                <i class="fas fa-check-circle"></i>
                                                <span class="hidden sm:inline">Condiciones óptimas para procesamiento
                                                    grupal</span>
                                                <span class="sm:hidden">Óptimo para grupal</span>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center gap-1">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="hidden sm:inline">Algunos repuestos no cumplen las
                                                    condiciones</span>
                                                <span class="sm:hidden">No óptimo para grupal</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de Estado Mejorada - Responsive -->
                        <div
                            class="mt-4 sm:mt-6 lg:mt-8 p-4 sm:p-5 bg-gradient-to-r from-blue-50 to-slate-50 rounded-xl sm:rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
                                <!-- Estado -->
                                <div class="flex items-start gap-2 sm:gap-3">
                                    <div
                                        class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-info-circle text-blue-600 text-xs sm:text-sm"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        @if ($repuestos_procesados == $total_repuestos)
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-check-circle text-green-600 text-xs sm:text-sm"></i>
                                                <p class="text-xs sm:text-sm font-semibold text-green-600">
                                                    <span class="hidden sm:inline">Procesamiento completado
                                                        exitosamente</span>
                                                    <span class="sm:hidden">Completado</span>
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex items-center flex-wrap gap-1">
                                                <i class="fas fa-chart-bar text-blue-600 text-xs sm:text-sm"></i>
                                                <p class="text-xs sm:text-sm font-semibold text-blue-600">
                                                    <span class="hidden sm:inline">Progreso general:</span>
                                                    <span class="text-slate-700">{{ $repuestos_procesados }}</span>
                                                    <span class="text-slate-500">de</span>
                                                    <span class="text-slate-700">{{ $total_repuestos }}</span>
                                                    <span class="hidden sm:inline">repuestos</span>
                                                    <span class="sm:hidden">reps</span>
                                                </p>
                                            </div>
                                        @endif

                                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                            <i class="fas fa-clock"></i>
                                            <span class="hidden sm:inline">Última actualización:</span>
                                            <span
                                                class="font-medium text-slate-600 text-xs">{{ now()->format('d/m/Y H:i') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Botones en fila horizontal -->
                                <div class="flex flex-row items-center gap-2 sm:gap-3 mt-3 lg:mt-0">
                                    @if ($puede_generar_pdf)
                                        <a href="{{ route('solicitudrepuesto.conformidad-pdf', $solicitud->idsolicitudesordenes) }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-danger text-white rounded-lg font-medium shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs sm:text-sm whitespace-nowrap">
                                            <i class="fas fa-file-pdf mr-1 sm:mr-1.5"></i>
                                            <span class="hidden sm:inline">Descargar Conformidad</span>
                                            <span class="sm:hidden">Conformidad</span>
                                        </a>
                                    @endif

                                    <!-- Botón Volver -->
                                    <a href="{{ route('solicitudarticulo.index') }}"
                                        class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-dark text-white rounded-lg font-medium shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs sm:text-sm whitespace-nowrap">
                                        <i class="fas fa-arrow-left mr-1 sm:mr-1.5"></i>
                                        <span class="hidden sm:inline">Volver al Listado</span>
                                        <span class="sm:hidden">Volver</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal para seleccionar destinatario - SOLO TÉCNICO -->
        <div x-show="mostrarModalDestinatario" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all duration-300 scale-100"
                x-show="mostrarModalDestinatario" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <!-- Header del Modal -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-black">Marcar como Listo para Entrega</h3>
                                <p class="text-green-100 text-sm" x-text="repuestoSeleccionadoNombre"></p>
                            </div>
                        </div>
                        <button @click="cerrarModalDestinatario"
                            class="text-white hover:text-green-200 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal - SOLO TÉCNICO -->
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Este repuesto será marcado como <strong>LISTO PARA ENTREGA</strong>
                        para el técnico:</p>

                    <!-- Opción única: Técnico -->
                    <div class="mb-6">
                        <div class="flex items-start space-x-3 p-4 border-2 border-green-300 rounded-xl bg-green-50">
                            <div
                                class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-user-cog text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="font-semibold text-gray-900">Entrega al Técnico</span>
                                </div>
                                <p class="text-sm text-gray-700 font-medium">
                                    @if ($tecnico)
                                        {{ $tecnico->Nombre }} {{ $tecnico->apellidoPaterno }}
                                        @if ($tecnico->correo)
                                            <br><span class="text-gray-500">{{ $tecnico->correo }}</span>
                                        @endif
                                    @else
                                        <span class="text-red-500">No hay técnico asignado</span>
                                    @endif
                                </p>

                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-yellow-500 mt-1 mr-2"></i>
                                        <div>
                                            <p class="text-sm text-yellow-700 font-semibold">Importante:</p>
                                            <p class="text-xs text-yellow-600">El stock <strong>NO se
                                                    descontará</strong> hasta que se confirme la entrega física.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex space-x-3">
                        <button @click="cerrarModalDestinatario"
                            class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button @click="confirmarProcesamientoIndividual" :disabled="!destinatarioValido"
                            :class="{
                                'bg-green-600 hover:bg-green-700': destinatarioValido,
                                'bg-gray-400 cursor-not-allowed': !destinatarioValido
                            }"
                            class="flex-1 px-4 py-2.5 text-white rounded-xl font-medium transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>
                            Listo para Entrega al Técnico
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para confirmar entrega física -->
        <div x-show="mostrarModalEntregaFisica" x-cloak class="fixed inset-0 bg-[black]/60 z-[9999] overflow-y-auto">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModalEntrega()">
                <div x-show="mostrarModalEntregaFisica" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg bg-white dark:bg-gray-800">
                    <!-- Header del modal -->
                    <div class="flex bg-green-600 text-white items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg flex items-center gap-2">
                            <i class="fas fa-truck"></i>
                            <span>Confirmar Entrega Física</span>
                        </div>
                        <button type="button" class="text-white hover:text-gray-200" @click="cerrarModalEntrega()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido del modal -->
                    <div class="p-5">
                        <!-- Información del repuesto -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Detalles de entrega</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="repuestoEntregaNombre"></p>
                            <div class="mt-2 flex items-center gap-2 text-sm">
                                <i class="fas fa-user-cog text-green-600"></i>
                                <span
                                    class="text-gray-700 dark:text-gray-300">{{ $tecnico ? $tecnico->Nombre . ' ' . $tecnico->apellidoPaterno : 'Técnico no asignado' }}</span>
                            </div>
                        </div>

                        <!-- Sección para foto -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-camera mr-2"></i>Foto del repuesto entregado
                            </label>
                            <div class="mt-1">
                                <!-- Contenedor para subir imagen (sin preview) -->
                                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-green-400 cursor-pointer transition-colors duration-200"
                                    @click="abrirFileInput()" x-show="!fotoPreviewEntrega">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-16 w-16 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div
                                            class="flex flex-col items-center text-sm text-gray-600 dark:text-gray-400">
                                            <label
                                                class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500">
                                                <span>Subir una foto</span>
                                                <input type="file" class="sr-only" accept="image/*"
                                                    @change="handleFotoUploadEntrega">
                                            </label>
                                            <p class="mt-1">o arrastra y suelta</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                            PNG, JPG, GIF hasta 5MB • Recomendado: 800x600px
                                        </p>
                                    </div>
                                </div>

                                <!-- Contenedor para preview de imagen -->
                                <div x-show="fotoPreviewEntrega" class="relative">
                                    <div
                                        class="border-2 border-green-500 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900 p-2">
                                        <!-- Contenedor con relación de aspecto 4:3 (puedes cambiar a 16:9, 1:1, etc.) -->
                                        <div class="relative w-full h-64 sm:h-72 md:h-80 overflow-hidden rounded-md">
                                            <img :src="fotoPreviewEntrega" alt="Vista previa"
                                                class="absolute inset-0 w-full h-full object-contain">
                                        </div>

                                        <!-- Indicador de dimensiones -->
                                        <div class="mt-2 text-center">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                La imagen se ajustará automáticamente al contenedor
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Botón para eliminar foto -->
                                    <button type="button" @click.stop="removeFotoEntrega()"
                                        class="absolute top-3 right-3 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors duration-200 shadow-lg">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>

                                    <!-- Botón para cambiar foto -->
                                    <button type="button" @click.stop="abrirFileInput()"
                                        class="absolute top-3 left-3 bg-blue-500 text-white rounded-full p-2 hover:bg-blue-600 transition-colors duration-200 shadow-lg">
                                        <i class="fas fa-sync-alt text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <input type="file" x-ref="fileInputEntrega" class="hidden" accept="image/*"
                                @change="handleFotoUploadEntrega">
                        </div>

                        <!-- Observaciones (opcional) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-comment-alt mr-2"></i>Observaciones (opcional)
                            </label>
                            <textarea x-model="observacionesEntrega" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                                placeholder="Alguna observación sobre la entrega..."></textarea>
                        </div>

                        <!-- Botones de acción - SOLO UN BOTÓN QUE HACE TODO -->
                        <div class="flex justify-end items-center mt-8 gap-3">
                            <button type="button" @click="cerrarModalEntrega()"
                                class="btn btn-outline-secondary px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancelar
                            </button>

                            <!-- ESTE ES EL BOTÓN QUE HACE TODO: Sube foto + Pide firmar + Confirma entrega -->
                            <button type="button" @click="firmarYConfirmarEntrega()"
                                :disabled="!fotoPreviewEntrega || isLoadingEntrega"
                                :class="{
                                    'opacity-50 cursor-not-allowed': !fotoPreviewEntrega || isLoadingEntrega,
                                    'bg-green-600 hover:bg-green-700': true
                                }"
                                class="btn btn-primary text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 flex items-center gap-2">
                                <template x-if="isLoadingEntrega">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </template>
                                <template x-if="!isLoadingEntrega">
                                    <i class="fas fa-signature"></i>
                                </template>
                                <span>Firmar y Confirmar Entrega</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Configurar Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
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

        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuestoOpciones', () => ({
                selecciones: {},
                procesandoIndividual: {},
                isLoadingGrupal: false,

                // Variables del modal de destinatario
                mostrarModalDestinatario: false,
                destinatarioSeleccionado: '',
                usuarioSeleccionado: '',
                solicitudIdSeleccionada: null,
                articuloIdSeleccionado: null,
                repuestoSeleccionadoNombre: '',

                // ============ VARIABLES PARA EL MODAL DE ENTREGA FÍSICA ============
                mostrarModalEntregaFisica: false,
                fotoPreviewEntrega: null,
                fotoFileEntrega: null,
                firmaConfirmadaEntrega: false,
                fechaFirmaEntrega: null,
                observacionesEntrega: '',
                isLoadingEntrega: false,
                repuestoEntregaNombre: '',
                articuloEntregaId: null,
                // ============ FIN VARIABLES ============

                destinatarioSeleccionado: 'tecnico',

                get destinatarioValido() {
                    // Solo válido si hay técnico asignado
                    return @json($tecnico != null);
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

                // ============ FUNCIÓN PARA ABRIR MODAL ENTREGA ============
                confirmarEntregaFisica(solicitudId, articuloId) {
                    const repuestos = @json($repuestos);
                    const repuesto = repuestos.find(r => r.idArticulos == articuloId);

                    this.articuloEntregaId = articuloId;
                    this.repuestoEntregaNombre = repuesto ? repuesto.nombre : 'Repuesto';
                    this.mostrarModalEntregaFisica = true;
                    this.resetFormEntrega();
                },

                // ============ FUNCIONES DEL MODAL DE ENTREGA ============
                abrirFileInput() {
                    this.$refs.fileInputEntrega.click();
                },

                handleFotoUploadEntrega(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            toastr.error('La foto debe ser menor a 5MB');
                            return;
                        }

                        if (!file.type.match('image.*')) {
                            toastr.error('Por favor, sube una imagen válida');
                            return;
                        }

                        this.fotoFileEntrega = file;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.fotoPreviewEntrega = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                removeFotoEntrega() {
                    this.fotoPreviewEntrega = null;
                    this.fotoFileEntrega = null;
                    if (this.$refs.fileInputEntrega) {
                        this.$refs.fileInputEntrega.value = '';
                    }
                },

                resetFormEntrega() {
                    this.fotoPreviewEntrega = null;
                    this.fotoFileEntrega = null;
                    this.firmaConfirmadaEntrega = false;
                    this.fechaFirmaEntrega = null;
                    this.observacionesEntrega = '';
                    this.isLoadingEntrega = false;
                },

                cerrarModalEntrega() {
                    this.mostrarModalEntregaFisica = false;
                    this.resetFormEntrega();
                },

                async firmarYConfirmarEntrega() {
                    // 1. Validar que hay foto
                    if (!this.fotoPreviewEntrega) {
                        toastr.error('Por favor, sube una foto del repuesto entregado');
                        return;
                    }

                    // 2. Cerrar el modal ANTES de mostrar el SweetAlert
                    this.cerrarModalEntrega();

                    // 3. Mostrar confirmación de firma (ÚNICA CONFIRMACIÓN)
                    const confirmacionFirma = await Swal.fire({
                        title: '<div class="flex items-center justify-center gap-3 mb-4">' +
                            '<div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">' +
                            '<i class="fas fa-signature text-green-600 text-xl"></i>' +
                            '</div>' +
                            '<h3 class="text-xl font-bold text-gray-800">Confirmar Entrega Física</h3>' +
                            '</div>',
                        html: `<div class="text-center px-4 py-2">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 mb-4 border border-green-100">
                <div class="flex flex-col space-y-3">
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-500">Firmante</p>
                            <p class="text-lg font-bold text-gray-800">{{ Auth::user()->name ?? 'Usuario' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-500">Repuesto</p>
                            <p class="text-lg font-bold text-gray-800">${this.repuestoEntregaNombre}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-4 mb-6 border border-amber-200">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-amber-700 mb-1">Confirmación Legal</p>
                        <p class="text-sm text-amber-600">Al firmar, confirmas que:</p>
                        <ul class="text-sm text-amber-600 mt-1 ml-4 list-disc">
                            <li>Has recibido el repuesto <strong>"${this.repuestoEntregaNombre}"</strong></li>
                            <li>El repuesto está en buen estado y condiciones</li>
                            <li>Esta acción registrará la entrega definitivamente</li>
                            <li>Se descontará del stock y no se puede revertir</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="text-sm text-gray-500 italic">
                ¿Confirmas la recepción del repuesto?
            </div>
        </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<div class="flex items-center justify-center gap-2 px-4">' +
                            '<i class="fas fa-signature"></i>' +
                            '<span>Confirmar y Firmar Entrega</span>' +
                            '</div>',
                        cancelButtonText: '<div class="flex items-center justify-center gap-2 px-4">' +
                            '<i class="fas fa-times-circle"></i>' +
                            '<span>Cancelar</span>' +
                            '</div>',
                        reverseButtons: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-gray-200',
                            title: '!mb-0 !pb-0',
                            htmlContainer: '!mb-0',
                            actions: 'flex gap-4',
                            confirmButton: 'btn btn-success !rounded-xl !py-3 !font-semibold !text-base !shadow-lg hover:shadow-xl',
                            cancelButton: 'btn btn-outline-secondary !rounded-xl !py-3 !font-semibold !text-base'
                        },
                        buttonsStyling: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)',
                        width: '500px',
                        padding: '1.5rem'
                    });

                    if (!confirmacionFirma.isConfirmed) {
                        // Si el usuario cancela, volver a abrir el modal
                        this.mostrarModalEntregaFisica = true;
                        return;
                    }

                    // 4. Registrar fecha de firma
                    const now = new Date();
                    this.fechaFirmaEntrega = now.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    this.firmaConfirmadaEntrega = true;

                    // 5. Enviar datos al servidor
                    this.isLoadingEntrega = true;

                    try {
                        const formData = new FormData();
                        formData.append('solicitud_id', {{ $solicitud->idsolicitudesordenes }});
                        formData.append('articulo_id', this.articuloEntregaId);
                        formData.append('observaciones', this.observacionesEntrega);
                        formData.append('firma_confirmada', true);
                        formData.append('nombre_firmante',
                            '{{ Auth::user()->name ?? 'Usuario' }}');
                        formData.append('fecha_firma', this.fechaFirmaEntrega);
                        formData.append('foto', this.fotoFileEntrega);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'));

                        const response = await fetch('/confirmar-entrega-fisica', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Mostrar éxito
                            await Swal.fire({
                                title: '<div class="flex items-center justify-center gap-3 mb-4">' +
                                    '<div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">' +
                                    '<i class="fas fa-check-circle text-green-600 text-xl"></i>' +
                                    '</div>' +
                                    '<h3 class="text-xl font-bold text-gray-800">¡Entrega Confirmada!</h3>' +
                                    '</div>',
                                html: `<div class="text-center">
                    <p class="text-gray-700 mb-4">El repuesto ha sido entregado exitosamente.</p>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-green-700 font-medium">${data.message || 'Entrega registrada correctamente'}</p>
                    </div>
                </div>`,
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                confirmButtonText: 'OK',
                                timer: 2000,
                                timerProgressBar: true
                            });

                            // Recargar después de 2 segundos
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(data.message || 'Error al confirmar la entrega');
                            // Si hay error, volver a abrir el modal
                            this.mostrarModalEntregaFisica = true;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error al procesar la solicitud');
                        // Si hay error, volver a abrir el modal
                        this.mostrarModalEntregaFisica = true;
                    } finally {
                        this.isLoadingEntrega = false;
                    }
                },
                // Modificar la función abrirModalDestinatario para seleccionar automáticamente técnico
                abrirModalDestinatario(solicitudId, articuloId, nombreRepuesto) {
                    const ubicacionId = this.selecciones[articuloId];

                    if (!ubicacionId) {
                        toastr.error('Seleccione una ubicación para este repuesto');
                        return;
                    }

                    // Verificar si hay técnico asignado
                    if (!@json($tecnico != null)) {
                        toastr.error('No hay técnico asignado a esta solicitud');
                        return;
                    }

                    this.solicitudIdSeleccionada = solicitudId;
                    this.articuloIdSeleccionado = articuloId;
                    this.repuestoSeleccionadoNombre = nombreRepuesto;
                    this.destinatarioSeleccionado = 'tecnico'; // Siempre técnico
                    this.mostrarModalDestinatario = true;
                },

                cerrarModalDestinatario() {
                    this.mostrarModalDestinatario = false;
                    this.destinatarioSeleccionado = '';
                    this.usuarioSeleccionado = '';
                },

                // Reemplazar la función confirmarProcesamientoIndividual
                async confirmarProcesamientoIndividual() {
                    if (!this.destinatarioValido) {
                        toastr.error('No hay técnico asignado para realizar la entrega');
                        return;
                    }

                    const ubicacionId = this.selecciones[this.articuloIdSeleccionado];
                    const nombreTecnico = @json($tecnico ? $tecnico->Nombre . ' ' . $tecnico->apellidoPaterno : 'Técnico');
                    const nombreRepuesto = this.repuestoSeleccionadoNombre;

                    // Mostrar loading inmediatamente
                    this.procesandoIndividual[this.articuloIdSeleccionado] = true;
                    this.mostrarModalDestinatario = false;

                    // Mostrar toast informativo
                    toastr.info(`Procesando: ${nombreRepuesto} para ${nombreTecnico}...`);

                    try {
                        const response = await fetch(
                            `/solicitudrepuesto/${this.solicitudIdSeleccionada}/marcar-listo-individual`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    articulo_id: this.articuloIdSeleccionado,
                                    ubicacion_id: ubicacionId,
                                    tipo_destinatario: this.destinatarioSeleccionado,
                                    usuario_destino_id: this
                                        .destinatarioSeleccionado === 'otro' ? this
                                        .usuarioSeleccionado : null
                                })
                            });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success(`✅ ${data.message || 'Repuesto listo para entrega!'}`);

                            // Recargar después de mostrar el mensaje
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(`❌ ${data.message || 'Error al procesar'}`);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('❌ Error de conexión al servidor');
                    } finally {
                        this.procesandoIndividual[this.articuloIdSeleccionado] = false;
                    }
                },
                obtenerNombreDestinatario() {
                    switch (this.destinatarioSeleccionado) {
                        case 'solicitante':
                            return '{{ $solicitante ? $solicitante->Nombre . ' ' . $solicitante->apellidoPaterno : 'Solicitante' }}';
                        case 'tecnico':
                            return '{{ $tecnico ? $tecnico->Nombre . ' ' . $tecnico->apellidoPaterno : 'Técnico' }}';
                        case 'otro':
                            const select = document.querySelector('[x-model="usuarioSeleccionado"]');
                            return select ? select.options[select.selectedIndex]?.text : 'Otro usuario';
                        default:
                            return 'No seleccionado';
                    }
                },

                async validarYProcesarGrupal(id) {
                    // Verificar si ya están todos procesados
                    if (@json($repuestos_procesados) == @json($total_repuestos)) {
                        toastr.info('Todos los repuestos ya fueron procesados');
                        return;
                    }

                    if (!this.todasUbicacionesSeleccionadas) {
                        toastr.error(
                            'Debe seleccionar una ubicación para todos los repuestos disponibles'
                        );
                        return;
                    }

                    if (!this.todosDisponibles) {
                        toastr.error(
                            'No todos los repuestos tienen stock suficiente para procesamiento grupal'
                        );
                        return;
                    }

                    // Reemplazar confirm nativo por SweetAlert2
                    const result = await Swal.fire({
                        title: '<h3 class="text-xl font-bold text-gray-800">¿Procesar TODOS los repuestos?</h3>',
                        html: `
            <div class="mt-3 space-y-4 text-center">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-gray-700 font-medium">
                        Se procesarán <span class="font-bold">todos los repuestos</span> de la solicitud.
                    </p>
                </div>

                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-4 text-left">
                    <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <p class="text-sm text-amber-700">
                        El stock será descontado de las ubicaciones seleccionadas para cada repuesto.
                        <span class="font-semibold">Esta acción no se puede deshacer.</span>
                    </p>
                </div>
            </div>
            `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: `
                <div class="flex items-center gap-2 px-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Procesar todo</span>
                </div>
            `,
                        cancelButtonText: `
                <div class="flex items-center gap-2 px-2">
                    <i class="fas fa-times-circle"></i>
                    <span>Cancelar</span>
                </div>
            `,
                        reverseButtons: true,
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                            actions: 'flex gap-4 mt-6',
                            confirmButton: 'btn btn-primary !rounded-xl !px-6 !py-3 font-semibold',
                            cancelButton: 'btn btn-outline-secondary !rounded-xl !px-6 !py-3 font-semibold'
                        },
                        backdrop: 'rgba(0,0,0,0.5)',
                        width: 480
                    });


                    if (!result.isConfirmed) {
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
                            // Mostrar SweetAlert2 de éxito
                            await Swal.fire({
                                title: '¡Éxito!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK',
                                timer: 2000,
                                timerProgressBar: true,
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            // Mostrar SweetAlert2 de error
                            await Swal.fire({
                                title: 'Error',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        await Swal.fire({
                            title: 'Error',
                            text: 'Error al procesar la solicitud',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    } finally {
                        this.isLoadingGrupal = false;
                    }
                },

                // Método para mostrar notificaciones (mantenido por compatibilidad)
                mostrarNotificacion(tipo, mensaje) {
                    switch (tipo) {
                        case 'success':
                            toastr.success(mensaje);
                            break;
                        case 'error':
                            toastr.error(mensaje);
                            break;
                        case 'info':
                            toastr.info(mensaje);
                            break;
                        default:
                            toastr.info(mensaje);
                    }
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
