<x-layout.default>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div x-data="solicitudArticuloOpciones()" class="min-h-screen py-8">
        <div class="mx-auto w-full px-4">
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline">Solicitudes</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Gestión de Artículos</span>
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
                                <i class="fas fa-cogs text-white text-lg"></i>
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
                                        class="text-green-600 font-bold text-sm">{{ $articulos_procesados }}/{{ $total_articulos }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Procesados</p>
                                    <p class="font-semibold text-gray-900">
                                        @if ($articulos_procesados == $total_articulos)
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
                                        class="text-blue-600 font-bold text-sm">{{ $articulos_disponibles }}/{{ $total_articulos }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Disponibles</p>
                                    <p class="font-semibold text-gray-900">
                                        @if ($articulos_disponibles == $total_articulos)
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
                                        @elseif($articulos_procesados > 0)
                                            Parcial
                                        @else
                                            Pendiente
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Solicitante -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl">
                            <!-- Información del Solicitante (existente) -->
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

                            <!-- NUEVO: Información del Usuario Destino -->
                            @if ($solicitud->usuario_destino_nombre)
                                <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-check text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Destinatario Final</p>
                                        <p class="font-semibold text-gray-900">{{ $solicitud->usuario_destino_nombre }}
                                            {{ $solicitud->usuario_destino_apellido }}</p>
                                        @if ($solicitud->nombre_area_destino)
                                            <p class="text-xs text-green-600">{{ $solicitud->nombre_area_destino }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verificar si hay artículos -->
            @if (!$articulos || $articulos->count() == 0)
                <!-- Mensaje cuando no hay artículos - Responsive -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-8">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-4 sm:px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-white">No hay artículos</h2>
                                <p class="text-yellow-100 text-xs sm:text-sm">No se encontraron artículos en esta
                                    solicitud</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8 text-center">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box-open text-yellow-500 text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">No se encontraron artículos
                        </h3>
                        <p class="text-gray-600 text-sm sm:text-base mb-6">Esta solicitud no contiene artículos para
                            gestionar.</p>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="inline-flex items-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105 text-sm sm:text-base">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            @else
                <!-- BOTÓN PARA ENVIAR A ABASTECIMIENTO - RESPONSIVE -->
                <div x-data="enviarAlmacen()" class="mb-8">
                    <!-- Panel de Artículos Sin Stock -->
                    <div x-show="articulosSinStock.length > 0"
                        class="bg-white rounded-2xl shadow-lg border border-orange-200">
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 px-4 sm:px-6 py-4 rounded-t-2xl">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-primary text-sm sm:text-base"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold">Artículos Sin Stock Disponible</h2>
                                    <p class="text-orange-100 text-xs sm:text-sm">Envíe estos artículos a solicitud de
                                        abastecimiento para su reposición</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <!-- Lista de artículos sin stock -->
                            <div class="space-y-3 mb-4">
                                <template x-for="articulo in articulosSinStock" :key="articulo.idArticulos">
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200 gap-2">
                                        <div class="flex items-center space-x-3">
                                            <input type="checkbox" x-model="articulosSeleccionados"
                                                :value="articulo.idArticulos"
                                                class="w-4 h-4 text-orange-600 border-orange-300 rounded focus:ring-orange-500">
                                            <div>
                                                <span class="font-semibold text-gray-900 text-sm sm:text-base"
                                                    x-text="articulo.nombre"></span>
                                                <div
                                                    class="flex flex-wrap items-center gap-1 sm:gap-4 text-xs sm:text-sm text-gray-600 mt-1">
                                                    <span
                                                        class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs sm:text-sm">
                                                        Solicitud: <span class="font-bold"
                                                            x-text="articulo.cantidad_solicitada"></span>
                                                    </span>
                                                    <span
                                                        class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs sm:text-sm">
                                                        Disponible: <span class="font-bold"
                                                            x-text="articulo.stock_disponible"></span>
                                                    </span>
                                                    <span
                                                        class="bg-red-500 text-white px-2 py-1 rounded font-bold text-xs sm:text-sm">
                                                        Faltan: <span
                                                            x-text="articulo.cantidad_solicitada - articulo.stock_disponible"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Botones de acción -->
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-orange-200 gap-4">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" @change="seleccionarTodos()"
                                        :checked="articulosSeleccionados.length === articulosSinStock.length"
                                        class="w-4 h-4 text-orange-600 border-orange-300 rounded focus:ring-orange-500">
                                    <span class="text-xs sm:text-sm text-gray-600">Seleccionar todos</span>
                                    <span class="text-xs sm:text-sm text-orange-600 font-semibold">
                                        (<span x-text="articulosSeleccionados.length"></span>)
                                    </span>
                                </div>

                                <button @click="abrirModal()" :disabled="articulosSeleccionados.length === 0"
                                    :class="{
                                        'bg-success transform hover:scale-105': articulosSeleccionados.length > 0,
                                        'bg-gray-400 cursor-not-allowed': articulosSeleccionados.length === 0
                                    }"
                                    class="px-4 sm:px-6 py-2.5 sm:py-3 text-white rounded-xl font-semibold transition-all duration-300 flex items-center justify-center space-x-2 shadow-lg text-sm sm:text-base">
                                    <i class="fas fa-warehouse"></i>
                                    <span class="hidden sm:inline">Enviar a Abastecimiento</span>
                                    <span class="sm:hidden">Abastecimiento</span>
                                    (<span x-text="articulosSeleccionados.length"></span>)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <h2 class="text-xl sm:text-2xl font-bold text-white">Gestión de Artículos</h2>
                                <p class="text-blue-100 text-sm sm:text-base">Seleccione ubicaciones y procese los
                                    artículos</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-8">
                        <!-- Formulario para selección -->
                        <form id="formUbicaciones" @submit.prevent="procesarSeleccion">
                            <!-- Tabla Mejorada - Responsive -->
                            <div class="overflow-x-auto rounded-2xl border border-slate-200/60 shadow-sm mb-8">
                                <table class="w-full min-w-[900px] lg:min-w-full">
                                    <thead class="bg-gradient-to-r from-slate-50 to-blue-50/30">
                                        <tr>
                                            <th
                                                class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-box text-slate-500"></i>
                                                    <span class="hidden sm:inline">Artículo</span>
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
                                        @foreach ($articulos as $articulo)
                                            <tr
                                                class="transition-all duration-200 hover:bg-blue-50/30 @if ($articulo->ya_procesado) bg-emerald-50/50 @endif">
                                                <!-- Información del Artículo -->
                                                <td class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                                                    <div class="flex items-center space-x-3 sm:space-x-4">
                                                        <div
                                                            class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                            <i
                                                                class="fas fa-box text-blue-600 text-sm sm:text-base"></i>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="font-semibold text-slate-900 text-sm sm:text-base truncate"
                                                                title="{{ $articulo->nombre }}">
                                                                {{ $articulo->nombre }}
                                                            </p>
                                                            <p class="text-xs text-slate-500 mt-1 truncate"
                                                                title="{{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}">
                                                                {{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}
                                                            </p>
                                                            <p class="text-xs text-slate-400 font-medium truncate"
                                                                title="{{ $articulo->tipo_articulo }}">
                                                                {{ $articulo->tipo_articulo }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Cantidad Solicitada -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-blue-100 text-blue-700">
                                                        <i class="fas fa-sort-numeric-up mr-1 hidden sm:inline"></i>
                                                        {{ $articulo->cantidad_solicitada }} <span
                                                            class="hidden sm:inline ml-1">unidades</span>
                                                        <span class="sm:hidden">uds</span>
                                                    </span>
                                                </td>

                                                <!-- Stock Disponible -->
                                                <td class="px-4 sm:px-6 py-4 sm:py-6">
                                                    <div class="text-center">
                                                        <span
                                                            class="text-base sm:text-lg font-bold @if ($articulo->suficiente_stock) text-emerald-600 @else text-rose-600 @endif">
                                                            {{ $articulo->stock_disponible }}
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
                                                        @if ($articulo->ya_procesado)
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-emerald-100 text-emerald-700">
                                                                    <i class="fas fa-check-circle mr-1"></i>
                                                                    <span class="hidden sm:inline">Procesado</span>
                                                                    <span class="sm:hidden">OK</span>
                                                                </span>
                                                            </div>
                                                        @elseif(isset($articulo->ubicaciones_detalle) && count($articulo->ubicaciones_detalle) > 0)
                                                            <select name="ubicaciones[{{ $articulo->idArticulos }}]"
                                                                class="w-full border border-slate-300 rounded-xl px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                                                x-model="selecciones[{{ $articulo->idArticulos }}]"
                                                                :disabled="procesandoIndividual[{{ $articulo->idArticulos }}]">
                                                                <option value="">
                                                                    <i
                                                                        class="fas fa-map-marker-alt mr-2 hidden sm:inline"></i>
                                                                    <span class="text-xs sm:text-sm">Seleccione
                                                                        ubicación</span>
                                                                </option>
                                                                @foreach ($articulo->ubicaciones_detalle as $ubicacion)
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
                                                    @if ($articulo->ya_procesado)
                                                        @php
                                                            // Obtener información de a quién se entregó este artículo
                                                            $entregaInfo = DB::table('articulos_entregas as ae')
                                                                ->select(
                                                                    'ae.tipo_entrega',
                                                                    'ae.usuario_destino_id',
                                                                    'u.Nombre',
                                                                    'u.apellidoPaterno',
                                                                    'u.apellidoMaterno',
                                                                )
                                                                ->leftJoin(
                                                                    'usuarios as u',
                                                                    'ae.usuario_destino_id',
                                                                    '=',
                                                                    'u.idUsuario',
                                                                )
                                                                ->where(
                                                                    'ae.solicitud_id',
                                                                    $solicitud->idsolicitudesordenes,
                                                                )
                                                                ->where('ae.articulo_id', $articulo->idArticulos)
                                                                ->first();
                                                        @endphp

                                                        @if ($entregaInfo && $entregaInfo->usuario_destino_id)
                                                            <div class="text-center min-w-[120px]">
                                                                @switch($entregaInfo->tipo_entrega)
                                                                    @case('destino')
                                                                        <div
                                                                            class="flex items-center justify-center space-x-2">
                                                                            <i
                                                                                class="fas fa-user-check text-green-600 text-sm sm:text-base"></i>
                                                                            <div class="hidden sm:block">
                                                                                <p
                                                                                    class="font-semibold text-slate-900 text-xs sm:text-sm truncate">
                                                                                    {{ $entregaInfo->Nombre }}
                                                                                    {{ $entregaInfo->apellidoPaterno }}
                                                                                </p>
                                                                                <p class="text-xs text-slate-500">Destino
                                                                                    Original</p>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="sm:hidden text-xs font-semibold text-slate-900 truncate">
                                                                            {{ substr($entregaInfo->Nombre, 0, 1) }}.
                                                                            {{ $entregaInfo->apellidoPaterno }}
                                                                        </div>
                                                                    @break

                                                                    @case('solicitante')
                                                                        <div
                                                                            class="flex items-center justify-center space-x-2">
                                                                            <i
                                                                                class="fas fa-user-tie text-blue-600 text-sm sm:text-base"></i>
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

                                                                    @case('otro_usuario')
                                                                        <div
                                                                            class="flex items-center justify-center space-x-2">
                                                                            <i
                                                                                class="fas fa-users text-orange-600 text-sm sm:text-base"></i>
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
                                                    @if ($articulo->ya_procesado)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-success text-white border border-green-200 shadow-sm">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            <span class="hidden sm:inline">Completado</span>
                                                            <span class="sm:hidden">Comp.</span>
                                                        </span>
                                                    @elseif($articulo->suficiente_stock)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-warning text-white border border-amber-200 shadow-sm">
                                                            <i class="fas fa-clock mr-1"></i>
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
                                                    @if ($articulo->ya_procesado)
                                                        <span class="text-green-600 font-semibold text-xs sm:text-sm">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            <span class="hidden sm:inline">Completado</span>
                                                            <span class="sm:hidden">OK</span>
                                                        </span>
                                                    @elseif($articulo->suficiente_stock)
                                                        @if (\App\Helpers\PermisoHelper::tienePermiso('PROCESAR ARTICULO INDIVIDUAL'))
                                                            <button type="button"
                                                                @click="abrirModalDestinatario({{ $solicitud->idsolicitudesordenes }}, {{ $articulo->idArticulos }}, '{{ $articulo->nombre }}')"
                                                                :disabled="!selecciones[{{ $articulo->idArticulos }}] ||
                                                                    procesandoIndividual[{{ $articulo->idArticulos }}]"
                                                                :class="{
                                                                    'opacity-50 cursor-not-allowed': !selecciones[
                                                                            {{ $articulo->idArticulos }}] ||
                                                                        procesandoIndividual[
                                                                            {{ $articulo->idArticulos }}],
                                                                    'bg-blue-600 hover:bg-blue-700': selecciones[
                                                                            {{ $articulo->idArticulos }}] && !
                                                                        procesandoIndividual[
                                                                            {{ $articulo->idArticulos }}]
                                                                }"
                                                                class="bg-blue-600 hover:bg-blue-700 disabled:bg-slate-400 text-white px-3 sm:px-5 py-1.5 sm:py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg text-xs sm:text-sm">
                                                                <span
                                                                    x-show="!procesandoIndividual[{{ $articulo->idArticulos }}]">
                                                                    <i class="fas fa-play-circle mr-1 sm:mr-2"></i>
                                                                    <span class="hidden sm:inline">Procesar</span>
                                                                    <span class="sm:hidden">Proc.</span>
                                                                </span>
                                                                <span
                                                                    x-show="procesandoIndividual[{{ $articulo->idArticulos }}]"
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
                                        <p class="text-xs sm:text-sm text-slate-600">Procese cada artículo de forma
                                            independiente según necesidad.</p>
                                    </div>
                                </div>

                                <ul class="space-y-1.5 sm:space-y-2 mb-4 sm:mb-6">
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Seleccione ubicación específica para cada artículo.</span>
                                    </li>
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Elija a qué usuario entregar cada artículo.</span>
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
                                            <span class="font-bold">{{ $articulos_procesados }}</span> de
                                            <span class="font-bold">{{ $total_articulos }}</span>
                                            <span class="hidden sm:inline"> artículos</span>
                                            <span class="sm:hidden"> arts</span>
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
                                        <p class="text-xs sm:text-sm text-slate-600">Procese todos los artículos en una
                                            sola acción eficiente.</p>
                                    </div>
                                </div>

                                <ul class="space-y-1.5 sm:space-y-2 mb-4 sm:mb-6">
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Seleccione ubicaciones para todos los artículos.</span>
                                    </li>
                                    <li class="flex items-center text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Procese todo el lote con un solo clic.</span>
                                    </li>
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                        <span>Requiere stock completo en todos los artículos.</span>
                                    </li>
                                </ul>

                                <div class="space-y-3 sm:space-y-4">
                                    <!-- Verificar si ya están todos procesados -->
                                    @if ($articulos_procesados == $total_articulos)
                                        <!-- Botón deshabilitado cuando ya está todo procesado -->
                                        <button disabled
                                            class="w-full flex items-center justify-center px-3 sm:px-6 py-2.5 sm:py-3 bg-gray-300 text-gray-600 rounded-lg sm:rounded-xl font-semibold cursor-not-allowed text-xs sm:text-sm">
                                            <i class="fas fa-check-circle mr-1.5 sm:mr-2"></i>
                                            <span>Todos los artículos ya están procesados</span>
                                        </button>
                                    @else
                                        <!-- Botón activo solo si no están todos procesados -->
                                        @if (App\Helpers\PermisoHelper::tienePermiso('PROCESAR ARTICULO GRUPAL'))
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
                                        @if ($articulos_procesados == $total_articulos)
                                            <div class="flex items-center justify-center gap-1">
                                                <i class="fas fa-check-double text-success"></i>
                                                <span class="hidden sm:inline">Todos los artículos ya fueron
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
                                                <span class="hidden sm:inline">Algunos artículos no cumplen las
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
                                        @if ($articulos_procesados == $total_articulos)
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
                                                    <span class="text-slate-700">{{ $articulos_procesados }}</span>
                                                    <span class="text-slate-500">de</span>
                                                    <span class="text-slate-700">{{ $total_articulos }}</span>
                                                    <span class="hidden sm:inline">artículos</span>
                                                    <span class="sm:hidden">arts</span>
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
                                        <a href="{{ route('solicitudarticulo.conformidad-pdf', $solicitud->idsolicitudesordenes) }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-danger text-white rounded-lg font-medium shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs sm:text-sm whitespace-nowrap">
                                            <i class="fas fa-file-pdf mr-1 sm:mr-1.5"></i>
                                            <span class="hidden sm:inline">Descargar Conformidad</span>
                                            <span class="sm:hidden">Conformidad</span>
                                        </a>
                                    @endif

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

                <!-- Los modales (destinatario individual, grupal y enviar a almacén) mantienen su estructura -->
                <!-- Solo se cambian las clases responsivas en los botones dentro de los modales -->
                <!-- Modal para seleccionar destinatario - VERSIÓN MEJORADA Y RESPONSIVE -->
                <div x-show="mostrarModalDestinatario" x-cloak
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all duration-300 scale-100"
                        x-show="mostrarModalDestinatario" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-4 sm:px-6 py-4 rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-user-check text-white text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-bold text-white">Seleccionar Destinatario
                                        </h3>
                                        <p class="text-blue-100 text-xs sm:text-sm"
                                            x-text="articuloSeleccionadoNombre"></p>
                                    </div>
                                </div>
                                <button @click="cerrarModalDestinatario"
                                    class="text-white hover:text-blue-200 transition-colors">
                                    <i class="fas fa-times text-base sm:text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal -->
                        <div class="p-4 sm:p-6">
                            <p class="text-gray-600 text-xs sm:text-sm mb-4">Seleccione a quién se le entregará el
                                artículo:</p>

                            <!-- Opción 1: Usuario Destino (NUEVA OPCIÓN) -->
                            @if ($solicitud->usuario_destino_nombre)
                                <div class="mb-3 sm:mb-4">
                                    <label
                                        class="flex items-start space-x-2 sm:space-x-3 p-3 sm:p-4 border-2 border-green-200 rounded-xl hover:border-green-400 hover:bg-green-50 cursor-pointer transition-all duration-200"
                                        :class="{ 'border-green-500 bg-green-50': destinatarioSeleccionado === 'destino' }">
                                        <input type="radio" x-model="destinatarioSeleccionado" value="destino"
                                            class="mt-0.5 sm:mt-1 text-green-600 focus:ring-green-500">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-1 sm:space-x-2 mb-1">
                                                <i class="fas fa-user-check text-green-600 text-sm sm:text-base"></i>
                                                <span class="font-semibold text-gray-900 text-sm sm:text-base">Usuario
                                                    Destino Original</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-gray-600">
                                                {{ $solicitud->usuario_destino_nombre }}
                                                {{ $solicitud->usuario_destino_apellido }}
                                                @if ($solicitud->nombre_area_destino)
                                                    <br><span
                                                        class="text-green-500 text-xs sm:text-sm">{{ $solicitud->nombre_area_destino }}</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-info-circle"></i> Usuario definido en la solicitud
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            @endif

                            <!-- Opción 2: Solicitante -->
                            <div class="mb-3 sm:mb-4">
                                <label
                                    class="flex items-start space-x-2 sm:space-x-3 p-3 sm:p-4 border-2 border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition-all duration-200"
                                    :class="{ 'border-blue-500 bg-blue-50': destinatarioSeleccionado === 'solicitante' }">
                                    <input type="radio" x-model="destinatarioSeleccionado" value="solicitante"
                                        class="mt-0.5 sm:mt-1 text-blue-600 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-1 sm:space-x-2 mb-1">
                                            <i class="fas fa-user-tie text-blue-600 text-sm sm:text-base"></i>
                                            <span class="font-semibold text-gray-900 text-sm sm:text-base">Entregar al
                                                Solicitante</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-600">
                                            @if ($solicitante)
                                                {{ $solicitante->Nombre }} {{ $solicitante->apellidoPaterno }}
                                            @else
                                                Usuario que realizó la solicitud
                                            @endif
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <!-- Opción 3: Otro Usuario -->
                            <div class="mb-4 sm:mb-6">
                                <label
                                    class="flex items-start space-x-2 sm:space-x-3 p-3 sm:p-4 border-2 border-orange-200 rounded-xl hover:border-orange-400 hover:bg-orange-50 cursor-pointer transition-all duration-200"
                                    :class="{ 'border-orange-500 bg-orange-50': destinatarioSeleccionado === 'otro' }">
                                    <input type="radio" x-model="destinatarioSeleccionado" value="otro"
                                        class="mt-0.5 sm:mt-1 text-orange-600 focus:ring-orange-500">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-1 sm:space-x-2 mb-1">
                                            <i class="fas fa-users text-orange-600 text-sm sm:text-base"></i>
                                            <span class="font-semibold text-gray-900 text-sm sm:text-base">Otro
                                                Usuario</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-600">Seleccionar un usuario diferente
                                        </p>

                                        <!-- Select de usuarios (solo visible cuando se selecciona "otro") -->
                                        <div x-show="destinatarioSeleccionado === 'otro'" class="mt-2 sm:mt-3">
                                            <select x-model="usuarioSeleccionado"
                                                class="w-full border border-gray-300 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-xs sm:text-sm">
                                                <option value="">Seleccione un usuario</option>
                                                @foreach ($usuarios as $usuario)
                                                    <option value="{{ $usuario->idUsuario }}"
                                                        class="text-xs sm:text-sm">
                                                        {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }} -
                                                        {{ $usuario->correo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex space-x-2 sm:space-x-3">
                                <button @click="cerrarModalDestinatario"
                                    class="flex-1 px-3 sm:px-4 py-1.5 sm:py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                                    Cancelar
                                </button>
                                <button @click="confirmarProcesamientoIndividual" :disabled="!destinatarioValido"
                                    :class="{
                                        'bg-blue-600 hover:bg-blue-700': destinatarioValido,
                                        'bg-gray-400 cursor-not-allowed': !destinatarioValido
                                    }"
                                    class="flex-1 px-3 sm:px-4 py-1.5 sm:py-2.5 text-white rounded-xl font-medium transition-colors text-xs sm:text-sm">
                                    <i class="fas fa-check-circle mr-1 sm:mr-2"></i>
                                    Confirmar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para seleccionar destinatario GRUPAL - RESPONSIVE -->
                <div x-show="mostrarModalDestinatarioGrupal" x-cloak
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all duration-300 scale-100"
                        x-show="mostrarModalDestinatarioGrupal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-4 sm:px-6 py-4 rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-users text-white text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-bold text-white">Destinatario -
                                            Procesamiento Grupal</h3>
                                        <p class="text-emerald-100 text-xs sm:text-sm">Seleccione el destinatario para
                                            todos los artículos</p>
                                    </div>
                                </div>
                                <button @click="cerrarModalDestinatarioGrupal"
                                    class="text-white hover:text-emerald-200 transition-colors">
                                    <i class="fas fa-times text-base sm:text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal -->
                        <div class="p-4 sm:p-6">
                            <p class="text-gray-600 text-xs sm:text-sm mb-4">Seleccione a quién se entregarán
                                <strong>todos los artículos</strong>:
                            </p>

                            <!-- Opción 1: Usuario Destino -->
                            @if ($solicitud->usuario_destino_nombre)
                                <div class="mb-3 sm:mb-4">
                                    <label
                                        class="flex items-start space-x-2 sm:space-x-3 p-3 sm:p-4 border-2 border-green-200 rounded-xl hover:border-green-400 hover:bg-green-50 cursor-pointer transition-all duration-200"
                                        :class="{ 'border-green-500 bg-green-50': destinatarioGrupalSeleccionado === 'destino' }">
                                        <input type="radio" x-model="destinatarioGrupalSeleccionado"
                                            value="destino"
                                            class="mt-0.5 sm:mt-1 text-green-600 focus:ring-green-500">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-1 sm:space-x-2 mb-1">
                                                <i class="fas fa-user-check text-green-600 text-sm sm:text-base"></i>
                                                <span class="font-semibold text-gray-900 text-sm sm:text-base">Usuario
                                                    Destino Original</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-gray-600">
                                                {{ $solicitud->usuario_destino_nombre }}
                                                {{ $solicitud->usuario_destino_apellido }}
                                                @if ($solicitud->nombre_area_destino)
                                                    <br><span
                                                        class="text-green-500 text-xs sm:text-sm">{{ $solicitud->nombre_area_destino }}</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-info-circle"></i> Usuario definido en la solicitud
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            @endif

                            <!-- Opción 2: Solicitante -->
                            <div class="mb-3 sm:mb-4">
                                <label
                                    class="flex items-start space-x-2 sm:space-x-3 p-3 sm:p-4 border-2 border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition-all duration-200"
                                    :class="{ 'border-blue-500 bg-blue-50': destinatarioGrupalSeleccionado === 'solicitante' }">
                                    <input type="radio" x-model="destinatarioGrupalSeleccionado"
                                        value="solicitante" class="mt-0.5 sm:mt-1 text-blue-600 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-1 sm:space-x-2 mb-1">
                                            <i class="fas fa-user-tie text-blue-600 text-sm sm:text-base"></i>
                                            <span class="font-semibold text-gray-900 text-sm sm:text-base">Entregar al
                                                Solicitante</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-600">
                                            @if ($solicitante)
                                                {{ $solicitante->Nombre }} {{ $solicitante->apellidoPaterno }}
                                            @else
                                                Usuario que realizó la solicitud
                                            @endif
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <!-- Opción 3: Otro Usuario -->
                            <div class="mb-4 sm:mb-6">
                                <label
                                    class="flex items-start space-x-2 sm:space-x-3 p-3 sm:p-4 border-2 border-orange-200 rounded-xl hover:border-orange-400 hover:bg-orange-50 cursor-pointer transition-all duration-200"
                                    :class="{ 'border-orange-500 bg-orange-50': destinatarioGrupalSeleccionado === 'otro' }">
                                    <input type="radio" x-model="destinatarioGrupalSeleccionado" value="otro"
                                        class="mt-0.5 sm:mt-1 text-orange-600 focus:ring-orange-500">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-1 sm:space-x-2 mb-1">
                                            <i class="fas fa-users text-orange-600 text-sm sm:text-base"></i>
                                            <span class="font-semibold text-gray-900 text-sm sm:text-base">Otro
                                                Usuario</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-600">Seleccionar un usuario diferente
                                        </p>

                                        <!-- Select de usuarios -->
                                        <div x-show="destinatarioGrupalSeleccionado === 'otro'" class="mt-2 sm:mt-3">
                                            <select x-model="usuarioGrupalSeleccionado"
                                                class="w-full border border-gray-300 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-xs sm:text-sm">
                                                <option value="">Seleccione un usuario</option>
                                                @foreach ($usuarios as $usuario)
                                                    <option value="{{ $usuario->idUsuario }}"
                                                        class="text-xs sm:text-sm">
                                                        {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }} -
                                                        {{ $usuario->correo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex space-x-2 sm:space-x-3">
                                <button @click="cerrarModalDestinatarioGrupal"
                                    class="flex-1 px-3 sm:px-4 py-1.5 sm:py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                                    Cancelar
                                </button>
                                <button @click="confirmarProcesamientoGrupal" :disabled="!destinatarioGrupalValido"
                                    :class="{
                                        'bg-emerald-600 hover:bg-emerald-700': destinatarioGrupalValido,
                                        'bg-gray-400 cursor-not-allowed': !destinatarioGrupalValido
                                    }"
                                    class="flex-1 px-3 sm:px-4 py-1.5 sm:py-2.5 text-white rounded-xl font-medium transition-colors text-xs sm:text-sm">
                                    <i class="fas fa-play-circle mr-1 sm:mr-2"></i>
                                    Procesar Todo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Enviar a Abastecimiento - RESPONSIVE -->
                <div x-show="mostrarModalEnviarAlmacen" x-cloak @click.self="cerrarModalEnviarAlmacen"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-100"
                        x-show="mostrarModalEnviarAlmacen" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                        <!-- Header del Modal -->
                        <div class="bg-primary px-4 sm:px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-warehouse text-white text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-bold text-white">Crear Solicitud de
                                            Abastecimiento</h3>
                                        <p class="text-white text-xs sm:text-sm"
                                            x-text="`${articulosSeleccionadosEnviar.length} artículos seleccionados`">
                                        </p>
                                    </div>
                                </div>
                                <button @click="cerrarModalEnviarAlmacen"
                                    class="text-white hover:text-orange-200 transition-colors">
                                    <i class="fas fa-times text-base sm:text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal - Versión Mejorada -->
                        <div class="p-4 sm:p-6 lg:p-8 overflow-y-auto max-h-[70vh] custom-scrollbar">

                            <!-- Información General -->
                            <div class="mb-8">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-1.5 h-5 bg-gradient-to-b from-orange-500 to-orange-600 rounded-full">
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-gray-900">Información de la Solicitud
                                    </h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                                    <!-- Título -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            <span class="text-orange-600">*</span> Título de la Solicitud
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <!-- Icono Font Awesome para título -->
                                                <i class="fas fa-heading text-gray-400 text-sm"></i>
                                            </div>
                                            <input type="text" x-model="formEnviarAlmacen.titulo"
                                                class="pl-10 w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm"
                                                placeholder="Ej: Reabastecimiento de artículos con stock insuficiente"
                                                required>
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500">Describa brevemente el propósito de la
                                            solicitud</p>
                                    </div>

                                    <!-- Tipo de Solicitud -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            <span class="text-orange-600">*</span> Tipo de Solicitud
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <!-- Icono Font Awesome para tipo -->
                                                <i class="fas fa-list-alt text-gray-400 text-sm"></i>
                                            </div>
                                            <select x-model="formEnviarAlmacen.idTipoSolicitud"
                                                class="pl-10 appearance-none w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm bg-white"
                                                required>
                                                <option value="" class="text-sm text-gray-400">Seleccione tipo
                                                    de solicitud</option>
                                                <template x-for="tipo in tiposSolicitud" :key="tipo.idTipoSolicitud">
                                                    <option :value="tipo.idTipoSolicitud" x-text="tipo.nombre"
                                                        class="text-sm"></option>
                                                </template>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <!-- Flecha del select con Font Awesome -->
                                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Prioridad -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            <span class="text-orange-600">*</span> Prioridad
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <!-- Icono Font Awesome para prioridad -->
                                                <i class="fas fa-flag text-gray-400 text-sm"></i>
                                            </div>
                                            <select x-model="formEnviarAlmacen.idPrioridad"
                                                class="pl-10 appearance-none w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm bg-white"
                                                required>
                                                <option value="" class="text-sm text-gray-400">Seleccione
                                                    prioridad</option>
                                                <template x-for="prioridad in prioridades"
                                                    :key="prioridad.idPrioridad">
                                                    <option :value="prioridad.idPrioridad" x-text="prioridad.nombre"
                                                        class="text-sm"></option>
                                                </template>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500" x-text="getPrioridadDescripcion()"></p>
                                    </div>

                                    <!-- Fecha Requerida -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            <span class="text-orange-600">*</span> Fecha Requerida
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <!-- Icono Font Awesome para fecha -->
                                                <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                                            </div>
                                            <input type="date" x-model="formEnviarAlmacen.fecha_requerida"
                                                :min="new Date().toISOString().split('T')[0]"
                                                class="pl-10 w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Centro de Costo -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            Centro de Costo <span class="text-gray-500 text-xs">(Opcional)</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <!-- Icono Font Awesome para costo -->
                                                <i class="fas fa-money-bill-wave text-gray-400 text-sm"></i>
                                            </div>
                                            <select x-model="formEnviarAlmacen.idCentroCosto"
                                                class="pl-10 appearance-none w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm bg-white">
                                                <option value="" class="text-sm text-gray-400">Seleccione centro
                                                    de costo</option>
                                                <template x-for="centro in centrosCosto" :key="centro.idCentroCosto">
                                                    <option :value="centro.idCentroCosto" x-text="centro.nombre"
                                                        class="text-sm"></option>
                                                </template>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Área -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            <span class="text-orange-600">*</span> Área
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <!-- Icono Font Awesome para área -->
                                                <i class="fas fa-building text-gray-400 text-sm"></i>
                                            </div>
                                            <select x-model="formEnviarAlmacen.idTipoArea"
                                                class="pl-10 appearance-none w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm bg-white"
                                                required>
                                                <option value="" class="text-sm text-gray-400">Seleccione área
                                                </option>
                                                <template x-for="area in areas" :key="area.idTipoArea">
                                                    <option :value="area.idTipoArea" x-text="area.nombre"
                                                        class="text-sm"></option>
                                                </template>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción y Justificación -->
                            <div class="mb-8">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-1.5 h-5 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full">
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-gray-900">Detalles Adicionales</h4>
                                </div>

                                <div class="grid grid-cols-1 gap-5 sm:gap-6">
                                    <!-- Descripción -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            <span class="text-orange-600">*</span> Descripción
                                        </label>
                                        <div class="relative">
                                            <div class="absolute top-3 left-3">
                                                <!-- Icono Font Awesome para descripción -->
                                                <i class="fas fa-align-left text-gray-400 text-sm"></i>
                                            </div>
                                            <textarea x-model="formEnviarAlmacen.descripcion" rows="3"
                                                class="pl-10 w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm"
                                                placeholder="Describa el propósito de esta solicitud de abastecimiento..." required></textarea>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <p class="text-xs text-gray-500">Proporcione una descripción clara del
                                                requerimiento</p>
                                            <span class="text-xs text-gray-400"
                                                x-text="formEnviarAlmacen.descripcion.length + '/500'"></span>
                                        </div>
                                    </div>

                                    <!-- Justificación -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            <span class="text-orange-600">*</span> Justificación
                                        </label>
                                        <div class="relative">
                                            <div class="absolute top-3 left-3">
                                                <!-- Icono Font Awesome para justificación -->
                                                <i class="fas fa-check-circle text-gray-400 text-sm"></i>
                                            </div>
                                            <textarea x-model="formEnviarAlmacen.justificacion" rows="3"
                                                class="pl-10 w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm"
                                                placeholder="Explique por qué es necesario este abastecimiento..." required></textarea>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <p class="text-xs text-gray-500">Explique la necesidad y urgencia de esta
                                                solicitud</p>
                                            <span class="text-xs text-gray-400"
                                                x-text="formEnviarAlmacen.justificacion.length + '/500'"></span>
                                        </div>
                                    </div>

                                    <!-- Observaciones -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2.5">
                                            Observaciones <span class="text-gray-500 text-xs">(Opcional)</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute top-3 left-3">
                                                <!-- Icono Font Awesome para observaciones -->
                                                <i class="fas fa-sticky-note text-gray-400 text-sm"></i>
                                            </div>
                                            <textarea x-model="formEnviarAlmacen.observaciones" rows="2"
                                                class="pl-10 w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all duration-200 hover:border-gray-400 shadow-sm"
                                                placeholder="Observaciones adicionales..."></textarea>
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500">Información adicional que considere
                                            relevante</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen de Artículos -->
                            <div class="mb-4">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-1.5 h-5 bg-success rounded-full">
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-gray-900">Artículos a Solicitar</h4>
                                    <span
                                        class="ml-2 px-2.5 py-0.5 bg-orange-100 text-orange-800 text-xs font-medium rounded-full"
                                        x-text="articulosSeleccionadosEnviar.length + ' artículos'"></span>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 p-5 sm:p-6 shadow-sm">
                                    <div class="space-y-4">
                                        <template x-for="articulo in articulosSeleccionadosEnviar"
                                            :key="articulo.idArticulos">
                                            <div
                                                class="group relative panel rounded-xl border border-gray-200 p-4 hover:border-orange-300 hover:shadow-sm transition-all duration-200">
                                                <div
                                                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-3 mb-2">
                                                            <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                                            <h5 class="font-bold text-gray-900 text-sm sm:text-base"
                                                                x-text="articulo.nombre"></h5>
                                                        </div>
                                                        <div
                                                            class="flex flex-wrap items-center gap-3 text-xs sm:text-sm">
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="text-gray-600">Cantidad a
                                                                    solicitar:</span>
                                                                <span class="font-bold text-orange-600 text-sm"
                                                                    x-text="calcularCantidadSolicitar(articulo)"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-wrap gap-2">
                                                        <!-- Stock Solicitado -->
                                                        <div
                                                            class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg">
                                                            <!-- Icono Font Awesome para solicitado -->
                                                            <i class="fas fa-clock text-blue-600 text-xs"></i>
                                                            <span class="font-semibold text-sm">
                                                                Sol: <span
                                                                    x-text="articulo.cantidad_solicitada"></span>
                                                            </span>
                                                        </div>

                                                        <!-- Stock Disponible -->
                                                        <div
                                                            class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-3 py-1.5 rounded-lg">
                                                            <!-- Icono Font Awesome para disponible -->
                                                            <i class="fas fa-boxes text-red-600 text-xs"></i>
                                                            <span class="font-semibold text-sm">
                                                                Disp: <span x-text="articulo.stock_disponible"></span>
                                                            </span>
                                                        </div>

                                                        <!-- Faltante -->
                                                        <div
                                                            class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-700 px-3 py-1.5 rounded-lg font-bold">
                                                            <!-- Icono Font Awesome para faltante -->
                                                            <i
                                                                class="fas fa-exclamation-triangle text-orange-600 text-xs"></i>
                                                            <span class="text-sm">
                                                                Faltan: <span
                                                                    x-text="articulo.cantidad_solicitada - articulo.stock_disponible"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Barra de progreso visual -->
                                                <div class="mt-3">
                                                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                                                        <span>Disponibilidad</span>
                                                        <span
                                                            x-text="Math.round((articulo.stock_disponible / articulo.cantidad_solicitada) * 100) + '%'"></span>
                                                    </div>
                                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                        <div class="h-full bg-gradient-to-r from-red-500 via-orange-500 to-green-500 rounded-full"
                                                            :style="'width: ' + Math.min((articulo.stock_disponible / articulo
                                                                .cantidad_solicitada) * 100, 100) + '%'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Resumen Total Mejorado -->
                                    <div class="mt-8 pt-6 border-t border-gray-200">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                            <!-- Total Artículos -->
                                            <div
                                                class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
                                                <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-white">
                                                </div>

                                                <div class="relative p-5 flex items-center gap-4">
                                                    <div
                                                        class="w-12 h-12 flex items-center justify-center rounded-xl bg-warning text-white">
                                                        <i class="fas fa-cubes text-xl"></i>
                                                    </div>

                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-500">
                                                            Total de artículos
                                                        </p>
                                                        <p class="text-3xl font-bold text-gray-900"
                                                            x-text="articulosSeleccionadosEnviar.length">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Unidades -->
                                            <div
                                                class="relative overflow-hidden rounded-2xl border border-orange-200 bg-white shadow-sm hover:shadow-md transition">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-br from-orange-50 to-orange-100/60">
                                                </div>

                                                <div class="relative p-5 flex items-center gap-4">
                                                    <div
                                                        class="w-12 h-12 flex items-center justify-center rounded-xl bg-warning text-white">
                                                        <i class="fas fa-box-open text-xl"></i>
                                                    </div>

                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-orange-700">
                                                            Total de unidades
                                                        </p>
                                                        <p class="text-3xl font-bold text-orange-900"
                                                            x-text="calcularTotalUnidades()">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Footer del Modal -->
                        <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-0">
                                <div class="text-xs sm:text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1 sm:mr-2"></i>
                                    Complete todos los campos obligatorios (*)
                                </div>
                                <div class="flex space-x-2 sm:space-x-3">
                                    <button @click="cerrarModalEnviarAlmacen"
                                        class="px-3 sm:px-6 py-1.5 sm:py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                                        Cancelar
                                    </button>
                                    <button @click="confirmarEnvioAlmacen"
                                        :disabled="!formEnviarAlmacenValido || enviandoAlmacen"
                                        :class="{
                                            'bg-primary': formEnviarAlmacenValido && !enviandoAlmacen,
                                            'bg-gray-400 cursor-not-allowed': !formEnviarAlmacenValido ||
                                                enviandoAlmacen
                                        }"
                                        class="px-3 sm:px-6 py-1.5 sm:py-2.5 text-white rounded-xl font-medium transition-colors flex items-center justify-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                                        <i class="fas fa-spinner fa-spin" x-show="enviandoAlmacen"></i>
                                        <i class="fas fa-warehouse" x-show="!enviandoAlmacen"></i>
                                        <span x-text="enviandoAlmacen ? 'Creando...' : 'Crear Solicitud'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
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
            Alpine.data('solicitudArticuloOpciones', function() {
                return {
                    // Variables principales
                    selecciones: {},
                    procesandoIndividual: {},
                    isLoadingGrupal: false,

                    // Variables para el modal de destinatario INDIVIDUAL
                    mostrarModalDestinatario: false,
                    destinatarioSeleccionado: '',
                    usuarioSeleccionado: '',
                    solicitudIdSeleccionada: null,
                    articuloIdSeleccionado: null,
                    articuloSeleccionadoNombre: '',

                    // Variables para el modal de destinatario GRUPAL
                    mostrarModalDestinatarioGrupal: false,
                    destinatarioGrupalSeleccionado: '',
                    usuarioGrupalSeleccionado: '',

                    // Variables para el modal de enviar a almacén
                    mostrarModalEnviarAlmacen: false,
                    articulosSeleccionadosEnviar: [],
                    formEnviarAlmacen: {
                        titulo: '',
                        idTipoSolicitud: '',
                        idPrioridad: '',
                        fecha_requerida: '',
                        idCentroCosto: '',
                        idTipoArea: '',
                        descripcion: '',
                        justificacion: '',
                        observaciones: ''
                    },
                    tiposSolicitud: [],
                    prioridades: [],
                    centrosCosto: [],
                    areas: [],
                    enviandoAlmacen: false,

                    // Computed properties
                    get destinatarioValido() {
                        if (this.destinatarioSeleccionado === 'destino' || this
                            .destinatarioSeleccionado === 'solicitante') {
                            return true;
                        }
                        if (this.destinatarioSeleccionado === 'otro') {
                            return this.usuarioSeleccionado !== '';
                        }
                        return false;
                    },

                    get destinatarioGrupalValido() {
                        if (this.destinatarioGrupalSeleccionado === 'destino' || this
                            .destinatarioGrupalSeleccionado === 'solicitante') {
                            return true;
                        }
                        if (this.destinatarioGrupalSeleccionado === 'otro') {
                            return this.usuarioGrupalSeleccionado !== '';
                        }
                        return false;
                    },

                    get formEnviarAlmacenValido() {
                        return this.formEnviarAlmacen.titulo &&
                            this.formEnviarAlmacen.idTipoSolicitud &&
                            this.formEnviarAlmacen.idPrioridad &&
                            this.formEnviarAlmacen.fecha_requerida &&
                            this.formEnviarAlmacen.idTipoArea &&
                            this.formEnviarAlmacen.descripcion &&
                            this.formEnviarAlmacen.justificacion;
                    },

                    get todasUbicacionesSeleccionadas() {
                        const articulos = @json($articulos);
                        return articulos.every(articulo => {
                            if (articulo.ya_procesado || !articulo.suficiente_stock) {
                                return true;
                            }
                            return this.selecciones[articulo.idArticulos] && this.selecciones[
                                articulo.idArticulos] !== '';
                        });
                    },

                    get todosDisponibles() {
                        return @json($puede_aceptar);
                    },

                    // Métodos de inicialización
                    async init() {
                        console.log('Componente Alpine inicializado correctamente');
                        await this.cargarDatosModalEnviarAlmacen();
                    },

                    async cargarDatosModalEnviarAlmacen() {
                        try {
                            const response = await fetch(
                                '/solicitudarticulo/enviar-almacen/modal-data');
                            const data = await response.json();

                            this.tiposSolicitud = data.tiposSolicitud;
                            this.prioridades = data.prioridades;
                            this.centrosCosto = data.centrosCosto;
                            this.areas = data.areas;

                            // Establecer valores por defecto
                            this.formEnviarAlmacen.idPrioridad = this.prioridades.find(p => p.nivel >=
                                3)?.idPrioridad || this.prioridades[0]?.idPrioridad;
                            this.formEnviarAlmacen.fecha_requerida = new Date().toISOString().split(
                                'T')[0];
                            this.formEnviarAlmacen.titulo =
                                'Reabastecimiento - Solicitud {{ $solicitud->codigo }}';
                            this.formEnviarAlmacen.descripcion =
                                'Solicitud automática generada desde el sistema. Artículos con stock insuficiente de la solicitud: {{ $solicitud->codigo }}';
                            this.formEnviarAlmacen.justificacion =
                                'Los artículos incluidos en esta solicitud no cuentan con stock suficiente en almacén para atender la solicitud original.';
                            this.formEnviarAlmacen.observaciones =
                                'Generado automáticamente por el sistema el {{ now()->format('d/m/Y H:i') }}';

                            // Establecer área desde la solicitud original
                            @if ($solicitud->id_area_destino)
                                this.formEnviarAlmacen.idTipoArea = {{ $solicitud->id_area_destino }};
                            @endif
                        } catch (error) {
                            console.error('Error cargando datos del modal:', error);
                        }
                    },

                    // Métodos para modal de enviar a almacén
                    abrirModalEnviarAlmacen(articulos) {
                        this.articulosSeleccionadosEnviar = articulos;
                        this.mostrarModalEnviarAlmacen = true;
                    },

                    cerrarModalEnviarAlmacen() {
                        this.mostrarModalEnviarAlmacen = false;
                    },

                    calcularCantidadSolicitar(articulo) {
                        const cantidadFaltante = articulo.cantidad_solicitada - articulo.stock_disponible;
                        return Math.max(1, cantidadFaltante);
                    },

                    calcularTotalUnidades() {
                        return this.articulosSeleccionadosEnviar.reduce((total, articulo) => total + this
                            .calcularCantidadSolicitar(articulo), 0);
                    },

                    async confirmarEnvioAlmacen() {
                        if (!this.formEnviarAlmacenValido) {
                            toastr.error('Complete todos los campos obligatorios');
                            return;
                        }

                        this.enviandoAlmacen = true;

                        try {
                            const articulosParaEnviar = this.articulosSeleccionadosEnviar.map(
                                articulo => ({
                                    idArticulos: articulo.idArticulos,
                                    nombre: articulo.nombre,
                                    cantidad_solicitada: articulo.cantidad_solicitada,
                                    stock_disponible: articulo.stock_disponible
                                }));

                            const response = await fetch(
                                `/solicitudarticulo/{{ $solicitud->idsolicitudesordenes }}/enviar-almacen`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        articulos: articulosParaEnviar,
                                        datos_solicitud: this.formEnviarAlmacen
                                    })
                                });

                            const data = await response.json();

                            if (data.success) {
                                toastr.success(
                                    `¡Solicitud de abastecimiento ${data.codigo_abastecimiento} creada exitosamente! ` +
                                    `Se enviaron ${data.total_articulos} artículos.`
                                );
                                this.cerrarModalEnviarAlmacen();

                                setTimeout(() => {
                                    location.reload();
                                }, 3000);
                            } else {
                                toastr.error(data.message);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            toastr.error('Error al crear la solicitud');
                        } finally {
                            this.enviandoAlmacen = false;
                        }
                    },

                    abrirModalDestinatario(solicitudId, articuloId, nombreArticulo) {
                        const ubicacionId = this.selecciones[articuloId];

                        if (!ubicacionId) {
                            toastr.error('Seleccione una ubicación para este artículo');
                            return;
                        }

                        this.solicitudIdSeleccionada = solicitudId;
                        this.articuloIdSeleccionado = articuloId;
                        this.articuloSeleccionadoNombre = nombreArticulo;
                        this.destinatarioSeleccionado = '';
                        this.usuarioSeleccionado = '';
                        this.mostrarModalDestinatario = true;
                    },

                    cerrarModalDestinatario() {
                        this.mostrarModalDestinatario = false;
                        this.destinatarioSeleccionado = '';
                        this.usuarioSeleccionado = '';
                    },

                    abrirModalDestinatarioGrupal() {
                        this.destinatarioGrupalSeleccionado = '';
                        this.usuarioGrupalSeleccionado = '';
                        this.mostrarModalDestinatarioGrupal = true;
                    },

                    cerrarModalDestinatarioGrupal() {
                        this.mostrarModalDestinatarioGrupal = false;
                        this.destinatarioGrupalSeleccionado = '';
                        this.usuarioGrupalSeleccionado = '';
                    },

                    async confirmarProcesamientoIndividual() {
                        if (!this.destinatarioValido) {
                            toastr.error('Seleccione un destinatario válido');
                            return;
                        }

                        const ubicacionId = this.selecciones[this.articuloIdSeleccionado];

                        if (!confirm(
                                `¿Está seguro de que desea procesar este artículo?\n\nArtículo: ${this.articuloSeleccionadoNombre}\nDestinatario: ${this.obtenerNombreDestinatario()}\n\nEl stock será descontado de la ubicación seleccionada.`
                            )) {
                            return;
                        }

                        this.procesandoIndividual[this.articuloIdSeleccionado] = true;
                        this.mostrarModalDestinatario = false;

                        try {
                            const response = await fetch(
                                `/solicitudarticulo/${this.solicitudIdSeleccionada}/aceptar-individual`, {
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
                                        usuario_destino_id: this.obtenerUsuarioDestinoId()
                                    })
                                });

                            const data = await response.json();

                            if (data.success) {
                                toastr.success(data.message);
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
                                toastr.error(data.message);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            toastr.error('Error al procesar el artículo');
                        } finally {
                            this.procesandoIndividual[this.articuloIdSeleccionado] = false;
                        }
                    },

                    obtenerUsuarioDestinoId() {
                        switch (this.destinatarioSeleccionado) {
                            case 'destino':
                                return {{ $solicitud->id_usuario_destino ?? 'null' }};
                            case 'solicitante':
                                return {{ $solicitud->idusuario ?? 'null' }};
                            case 'otro':
                                return this.usuarioSeleccionado;
                            default:
                                return null;
                        }
                    },

                    obtenerNombreDestinatario() {
                        switch (this.destinatarioSeleccionado) {
                            case 'destino':
                                return '{{ $solicitud->usuario_destino_nombre ?? 'Usuario Destino' }}';
                            case 'solicitante':
                                return '{{ $solicitante ? $solicitante->Nombre . ' ' . $solicitante->apellidoPaterno : 'Solicitante' }}';
                            case 'otro':
                                const select = document.querySelector('[x-model="usuarioSeleccionado"]');
                                return select ? select.options[select.selectedIndex]?.text : 'Otro usuario';
                            default:
                                return 'No seleccionado';
                        }
                    },

                    async confirmarProcesamientoGrupal() {
                        if (!this.destinatarioGrupalValido) {
                            toastr.error(
                                'Seleccione un destinatario válido para el procesamiento grupal');
                            return;
                        }

                        if (!confirm(
                                `¿Está seguro de que desea procesar TODOS los artículos?\n\nDestinatario: ${this.obtenerNombreDestinatarioGrupal()}\n\nEl stock será descontado de las ubicaciones seleccionadas para cada artículo.`
                            )) {
                            return;
                        }

                        this.isLoadingGrupal = true;
                        this.mostrarModalDestinatarioGrupal = false;

                        try {
                            const response = await fetch(
                                `/solicitudarticulo/${@json($solicitud->idsolicitudesordenes)}/aceptar`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        ubicaciones: this.selecciones,
                                        tipo_destinatario: this
                                            .destinatarioGrupalSeleccionado,
                                        usuario_destino_id: this
                                            .obtenerUsuarioDestinoGrupalId()
                                    })
                                });

                            const data = await response.json();

                            if (data.success) {
                                toastr.success(data.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                toastr.error(data.message);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            toastr.error('Error al procesar la solicitud');
                        } finally {
                            this.isLoadingGrupal = false;
                        }
                    },

                    obtenerUsuarioDestinoGrupalId() {
                        switch (this.destinatarioGrupalSeleccionado) {
                            case 'destino':
                                return {{ $solicitud->id_usuario_destino ?? 'null' }};
                            case 'solicitante':
                                return {{ $solicitud->idusuario ?? 'null' }};
                            case 'otro':
                                return this.usuarioGrupalSeleccionado;
                            default:
                                return null;
                        }
                    },

                    obtenerNombreDestinatarioGrupal() {
                        switch (this.destinatarioGrupalSeleccionado) {
                            case 'destino':
                                return '{{ $solicitud->usuario_destino_nombre ?? 'Usuario Destino' }}';
                            case 'solicitante':
                                return '{{ $solicitante ? $solicitante->Nombre . ' ' . $solicitante->apellidoPaterno : 'Solicitante' }}';
                            case 'otro':
                                const select = document.querySelector(
                                    '[x-model="usuarioGrupalSeleccionado"]');
                                return select ? select.options[select.selectedIndex]?.text : 'Otro usuario';
                            default:
                                return 'No seleccionado';
                        }
                    },

                    async validarYProcesarGrupal(id) {
                        // Verificar si ya están todos procesados
                        if (@json($articulos_procesados) == @json($total_articulos)) {
                            toastr.info('Todos los artículos ya fueron procesados');
                            return;
                        }

                        if (!this.todasUbicacionesSeleccionadas) {
                            toastr.error(
                                'Debe seleccionar una ubicación para todos los artículos disponibles'
                            );
                            return;
                        }

                        if (!this.todosDisponibles) {
                            toastr.error(
                                'No todos los artículos tienen stock suficiente para procesamiento grupal'
                            );
                            return;
                        }

                        // Abrir modal para seleccionar destinatario grupal
                        this.abrirModalDestinatarioGrupal();
                    }
                }
            });

            // Función simplificada para enviar a abastecimiento
            Alpine.data('enviarAlmacen', function() {
                return {
                    articulosSinStock: @json($articulos->where('suficiente_stock', false)->where('ya_procesado', false)->values()),
                    articulosSeleccionados: [],

                    init() {
                        // Inicializar con todos los artículos seleccionados por defecto
                        this.articulosSeleccionados = this.articulosSinStock.map(articulo => articulo
                            .idArticulos);
                    },

                    seleccionarTodos() {
                        if (this.articulosSeleccionados.length === this.articulosSinStock.length) {
                            this.articulosSeleccionados = [];
                        } else {
                            this.articulosSeleccionados = this.articulosSinStock.map(articulo => articulo
                                .idArticulos);
                        }
                    },

                    abrirModal() {
                        if (this.articulosSeleccionados.length === 0) {
                            toastr.error('Seleccione al menos un artículo para enviar a abastecimiento');
                            return;
                        }

                        // Obtener los artículos seleccionados
                        const articulosParaEnviar = this.articulosSinStock.filter(articulo =>
                            this.articulosSeleccionados.includes(articulo.idArticulos)
                        );

                        // Abrir modal usando el componente principal
                        const mainComponent = Alpine.$data(document.querySelector(
                            '[x-data="solicitudArticuloOpciones()"]'));
                        mainComponent.abrirModalEnviarAlmacen(articulosParaEnviar);
                    }
                }
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-layout.default>
