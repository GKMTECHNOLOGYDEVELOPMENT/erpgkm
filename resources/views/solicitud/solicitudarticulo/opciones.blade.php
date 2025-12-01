<x-layout.default>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <div x-data="solicitudArticuloOpciones()" class="min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-7xl">

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

                            <!-- NUEVO: Información del Usuario Destino -->
                            @if($solicitud->usuario_destino_nombre)
                            <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-check text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Destinatario Final</p>
                                    <p class="font-semibold text-gray-900">{{ $solicitud->usuario_destino_nombre }} {{ $solicitud->usuario_destino_apellido }}</p>
                                    @if($solicitud->nombre_area_destino)
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
                <!-- Mensaje cuando no hay artículos -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-8">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">No hay artículos</h2>
                                <p class="text-yellow-100 text-sm">No se encontraron artículos en esta solicitud</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box-open text-yellow-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No se encontraron artículos</h3>
                        <p class="text-gray-600 mb-6">Esta solicitud no contiene artículos para gestionar.</p>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            @else
                <!-- BOTÓN PARA ENVIAR A ABASTECIMIENTO - COLOCADO AL INICIO PARA MEJOR VISIBILIDAD -->
                <div x-data="enviarAlmacen()" class="mb-8">
                    <!-- Panel de Artículos Sin Stock -->
                    <div x-show="articulosSinStock.length > 0" class="bg-white rounded-2xl shadow-lg border border-orange-200">
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4 rounded-t-2xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Artículos Sin Stock Disponible</h2>
                                    <p class="text-orange-100 text-sm">Envíe estos artículos a solicitud de abastecimiento para su reposición</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Lista de artículos sin stock -->
                            <div class="space-y-3 mb-4">
                                <template x-for="articulo in articulosSinStock" :key="articulo.idArticulos">
                                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                                        <div class="flex items-center space-x-3">
                                            <input type="checkbox" 
                                                   x-model="articulosSeleccionados" 
                                                   :value="articulo.idArticulos" 
                                                   class="w-4 h-4 text-orange-600 border-orange-300 rounded focus:ring-orange-500">
                                            <div>
                                                <span class="font-semibold text-gray-900" x-text="articulo.nombre"></span>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">Solicitado: <span class="font-bold" x-text="articulo.cantidad_solicitada"></span></span>
                                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded">Disponible: <span class="font-bold" x-text="articulo.stock_disponible"></span></span>
                                                    <span class="bg-red-500 text-white px-2 py-1 rounded font-bold">
                                                        Faltan: <span x-text="articulo.cantidad_solicitada - articulo.stock_disponible"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex items-center justify-between pt-4 border-t border-orange-200">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           @change="seleccionarTodos()" 
                                           :checked="articulosSeleccionados.length === articulosSinStock.length"
                                           class="w-4 h-4 text-orange-600 border-orange-300 rounded focus:ring-orange-500">
                                    <span class="text-sm text-gray-600">Seleccionar todos</span>
                                    <span class="text-sm text-orange-600 font-semibold">
                                        (<span x-text="articulosSeleccionados.length"></span> seleccionados)
                                    </span>
                                </div>

                                <button @click="abrirModal()"
                                        :disabled="articulosSeleccionados.length === 0"
                                        :class="{
                                            'bg-orange-600 hover:bg-orange-700 transform hover:scale-105': articulosSeleccionados.length > 0,
                                            'bg-gray-400 cursor-not-allowed': articulosSeleccionados.length === 0
                                        }"
                                        class="px-6 py-3 text-white rounded-xl font-semibold transition-all duration-300 flex items-center space-x-2 shadow-lg">
                                    <i class="fas fa-warehouse"></i>
                                    <span>Enviar a Abastecimiento (<span x-text="articulosSeleccionados.length"></span>)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <h2 class="text-2xl font-bold text-white">Gestión de Artículos</h2>
                                <p class="text-blue-100 text-base">Seleccione ubicaciones y procese los artículos</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <!-- Formulario para selección -->
                        <form id="formUbicaciones" @submit.prevent="procesarSeleccion">
                            <!-- Tabla Mejorada -->
                            <div class="overflow-hidden rounded-2xl border border-slate-200/60 shadow-sm mb-8">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-slate-50 to-blue-50/30">
                                        <tr>
                                            <th
                                                class="px-8 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-box text-slate-500"></i>
                                                    <span>Artículo</span>
                                                </div>
                                            </th>
                                            <th
                                                class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                Solicitado
                                            </th>
                                            <th
                                                class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                Disponible
                                            </th>
                                            <th
                                                class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-map-marker-alt mr-1 text-slate-500"></i>
                                                Ubicación
                                            </th>
                                            <th
                                                class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-user-check mr-1 text-slate-500"></i>
                                                Entregado A
                                            </th>
                                            <th
                                                class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-tasks mr-1 text-slate-500"></i>
                                                Estado
                                            </th>
                                            <th
                                                class="px-6 py-5 text-left text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                <i class="fas fa-play-circle mr-1 text-slate-500"></i>
                                                Acción
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200/40">
                                        @foreach ($articulos as $articulo)
                                            <tr
                                                class="transition-all duration-200 hover:bg-blue-50/30 @if ($articulo->ya_procesado) bg-emerald-50/50 @endif">
                                                <!-- Información del Artículo -->
                                                <td class="px-8 py-6">
                                                    <div class="flex items-center space-x-4">
                                                        <div
                                                            class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                                                            <i class="fas fa-box text-blue-600"></i>
                                                        </div>
                                                        <div>
                                                            <p class="font-semibold text-slate-900 text-base">
                                                                {{ $articulo->nombre }}</p>
                                                            <p class="text-sm text-slate-500 mt-1">
                                                                {{ $articulo->codigo_repuesto ?: $articulo->codigo_barras }}
                                                            </p>
                                                            <p class="text-xs text-slate-400 font-medium">
                                                                {{ $articulo->tipo_articulo }}</p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Cantidad Solicitada -->
                                                <td class="px-6 py-6">
                                                    <span
                                                        class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-blue-100 text-blue-700">
                                                        <i class="fas fa-sort-numeric-up mr-1"></i>
                                                        {{ $articulo->cantidad_solicitada }} unidades
                                                    </span>
                                                </td>

                                                <!-- Stock Disponible -->
                                                <td class="px-6 py-6">
                                                    <div class="text-center">
                                                        <span
                                                            class="text-lg font-bold @if ($articulo->suficiente_stock) text-emerald-600 @else text-rose-600 @endif">
                                                            {{ $articulo->stock_disponible }}
                                                        </span>
                                                        <span class="text-sm text-slate-500 block">disponibles</span>
                                                    </div>
                                                </td>

                                                <!-- Selección de Ubicación -->
                                                <td class="px-6 py-6">
                                                    <div class="max-w-xs">
                                                        @if ($articulo->ya_procesado)
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-emerald-100 text-emerald-700">
                                                                    <i class="fas fa-check-circle mr-1"></i>
                                                                    Procesado
                                                                </span>
                                                            </div>
                                                        @elseif(isset($articulo->ubicaciones_detalle) && count($articulo->ubicaciones_detalle) > 0)
                                                            <select name="ubicaciones[{{ $articulo->idArticulos }}]"
                                                                class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                                                x-model="selecciones[{{ $articulo->idArticulos }}]"
                                                                :disabled="procesandoIndividual[{{ $articulo->idArticulos }}]">
                                                                <option value=""><i
                                                                        class="fas fa-map-marker-alt mr-2"></i>Seleccione
                                                                    ubicación</option>
                                                                @foreach ($articulo->ubicaciones_detalle as $ubicacion)
                                                                    <option
                                                                        value="{{ $ubicacion->rack_ubicacion_id }}">
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
                                                    @if ($articulo->ya_procesado)
                                                        @php
                                                            // Obtener información de a quién se entregó este artículo
                                                            $entregaInfo = DB::table('articulos_entregas as ae')
                                                                ->select(
                                                                    'ae.tipo_entrega',
                                                                    'ae.usuario_destino_id',
                                                                    'u.Nombre',
                                                                    'u.apellidoPaterno',
                                                                    'u.apellidoMaterno'
                                                                )
                                                                ->leftJoin('usuarios as u', 'ae.usuario_destino_id', '=', 'u.idUsuario')
                                                                ->where('ae.solicitud_id', $solicitud->idsolicitudesordenes)
                                                                ->where('ae.articulo_id', $articulo->idArticulos)
                                                                ->first();
                                                        @endphp

                                                        @if($entregaInfo && $entregaInfo->usuario_destino_id)
                                                            <div class="text-center">
                                                                @switch($entregaInfo->tipo_entrega)
                                                                    @case('destino')
                                                                        <div class="flex items-center justify-center space-x-2">
                                                                            <i class="fas fa-user-check text-green-600"></i>
                                                                            <div>
                                                                                <p class="font-semibold text-slate-900 text-sm">
                                                                                    {{ $entregaInfo->Nombre }} {{ $entregaInfo->apellidoPaterno }}
                                                                                </p>
                                                                                <p class="text-xs text-slate-500">Destino Original</p>
                                                                            </div>
                                                                        </div>
                                                                        @break
                                                                    
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
                                                    @if ($articulo->ya_procesado)
                                                        <span
                                                            class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Completado
                                                        </span>
                                                    @elseif($articulo->suficiente_stock)
                                                        <span
                                                            class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-amber-100 text-amber-700 border border-amber-200 shadow-sm">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Pendiente
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold bg-red-100 text-red-700 border border-red-200 shadow-sm">
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                            Insuficiente
                                                        </span>
                                                    @endif
                                                </td>

                                                <!-- Acción -->
                                                <td class="px-6 py-6">
                                                    @if ($articulo->ya_procesado)
                                                        <span class="text-green-600 font-semibold">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Completado
                                                        </span>
                                                    @elseif($articulo->suficiente_stock)
                                                        @if(\App\Helpers\PermisoHelper::tienePermiso('PROCESAR ARTICULO INDIVIDUAL'))
                                                        <button type="button"
                                                                @click="abrirModalDestinatario({{ $solicitud->idsolicitudesordenes }}, {{ $articulo->idArticulos }}, '{{ $articulo->nombre }}')"
                                                                :disabled="!selecciones[{{ $articulo->idArticulos }}] || procesandoIndividual[{{ $articulo->idArticulos }}]"
                                                                :class="{
                                                                    'opacity-50 cursor-not-allowed': !selecciones[{{ $articulo->idArticulos }}] || procesandoIndividual[{{ $articulo->idArticulos }}],
                                                                    'bg-blue-600 hover:bg-blue-700': selecciones[{{ $articulo->idArticulos }}] && !procesandoIndividual[{{ $articulo->idArticulos }}]
                                                                }"
                                                                class="bg-blue-600 hover:bg-blue-700 disabled:bg-slate-400 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                                                                <span x-show="!procesandoIndividual[{{ $articulo->idArticulos }}]">
                                                                    <i class="fas fa-play-circle mr-2"></i>
                                                                    Procesar
                                                                </span>
                                                                <span x-show="procesandoIndividual[{{ $articulo->idArticulos }}]"
                                                                    class="flex items-center space-x-2">
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
                                        <p class="text-slate-600 text-sm mt-1">Procese cada artículo de forma
                                            independiente según necesidad.</p>
                                    </div>
                                </div>

                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mr-3 flex-shrink-0"></i>
                                        Seleccione ubicación específica para cada artículo.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mr-3 flex-shrink-0"></i>
                                        Elija a qué usuario entregar cada artículo.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-blue-500 mr-3 flex-shrink-0"></i>
                                        Ideal para procesamiento parcial o selectivo.
                                    </li>
                                </ul>

                                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-center">
                                    <p class="text-sm font-semibold text-blue-700">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        Progreso: <span class="font-bold">{{ $articulos_procesados }}</span> de
                                        <span class="font-bold">{{ $total_articulos }}</span> artículos
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
                                        <p class="text-slate-600 text-sm mt-1">Procese todos los artículos en una sola
                                            acción eficiente.</p>
                                    </div>
                                </div>

                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                        Seleccione ubicaciones para todos los artículos.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                        Procese todo el lote con un solo clic.
                                    </li>
                                    <li class="flex items-center text-sm text-slate-700">
                                        <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                        Requiere stock completo en todos los artículos.
                                    </li>
                                </ul>

                                <div class="space-y-4">
                                   @if(App\Helpers\PermisoHelper::tienePermiso('PROCESAR ARTICULO GRUPAL'))                                    
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
                                            Algunos artículos no cumplen las condiciones
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                       <!-- Barra de Estado Mejorada -->
                        <div class="mt-10 p-6 bg-gradient-to-r from-blue-50 to-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                <!-- Estado -->
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-11 h-11 bg-blue-100 rounded-2xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>

                                    <div>
                                        @if ($articulos_procesados == $total_articulos)
                                            <p class="text-sm font-semibold text-green-600 flex items-center gap-1">
                                                <i class="fas fa-check-circle"></i>
                                                Procesamiento completado exitosamente
                                            </p>
                                        @else
                                            <p class="text-sm font-semibold text-blue-600 flex items-center gap-1">
                                                <i class="fas fa-chart-bar"></i>
                                                Progreso general:
                                                <span class="text-slate-700">{{ $articulos_procesados }}</span>
                                                <span class="text-slate-500">de</span>
                                                <span class="text-slate-700">{{ $total_articulos }}</span> artículos
                                            </p>
                                        @endif

                                        <p class="text-xs text-slate-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Última actualización:
                                            <span class="font-medium text-slate-600">{{ now()->format('d/m/Y H:i') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    @if($puede_generar_pdf)
                                    <a href="{{ route('solicitudarticulo.conformidad-pdf', $solicitud->idsolicitudesordenes) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        Descargar Conformidad
                                    </a>
                                    @endif
                                    
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

                <!-- Modal para seleccionar destinatario - VERSIÓN MEJORADA -->
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
                                        <p class="text-blue-100 text-sm" x-text="articuloSeleccionadoNombre"></p>
                                    </div>
                                </div>
                                <button @click="cerrarModalDestinatario" class="text-white hover:text-blue-200 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal -->
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Seleccione a quién se le entregará el artículo:</p>
                            
                            <!-- Opción 1: Usuario Destino (NUEVA OPCIÓN) -->
                            @if($solicitud->usuario_destino_nombre)
                            <div class="mb-4">
                                <label class="flex items-start space-x-3 p-4 border-2 border-green-200 rounded-xl hover:border-green-400 hover:bg-green-50 cursor-pointer transition-all duration-200"
                                       :class="{ 'border-green-500 bg-green-50': destinatarioSeleccionado === 'destino' }">
                                    <input type="radio" 
                                           x-model="destinatarioSeleccionado" 
                                           value="destino" 
                                           class="mt-1 text-green-600 focus:ring-green-500">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <i class="fas fa-user-check text-green-600"></i>
                                            <span class="font-semibold text-gray-900">Usuario Destino Original</span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            {{ $solicitud->usuario_destino_nombre }} {{ $solicitud->usuario_destino_apellido }}
                                            @if($solicitud->nombre_area_destino)
                                            <br><span class="text-green-500">{{ $solicitud->nombre_area_destino }}</span>
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

                <!-- Modal para seleccionar destinatario GRUPAL -->
                <div x-show="mostrarModalDestinatarioGrupal" 
                     x-cloak
                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all duration-300 scale-100"
                         x-show="mostrarModalDestinatarioGrupal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        
                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-6 py-4 rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Destinatario - Procesamiento Grupal</h3>
                                        <p class="text-emerald-100 text-sm">Seleccione el destinatario para todos los artículos</p>
                                    </div>
                                </div>
                                <button @click="cerrarModalDestinatarioGrupal" class="text-white hover:text-emerald-200 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal -->
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Seleccione a quién se entregarán <strong>todos los artículos</strong>:</p>
                            
                            <!-- Opción 1: Usuario Destino -->
                            @if($solicitud->usuario_destino_nombre)
                            <div class="mb-4">
                                <label class="flex items-start space-x-3 p-4 border-2 border-green-200 rounded-xl hover:border-green-400 hover:bg-green-50 cursor-pointer transition-all duration-200"
                                       :class="{ 'border-green-500 bg-green-50': destinatarioGrupalSeleccionado === 'destino' }">
                                    <input type="radio" 
                                           x-model="destinatarioGrupalSeleccionado" 
                                           value="destino" 
                                           class="mt-1 text-green-600 focus:ring-green-500">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <i class="fas fa-user-check text-green-600"></i>
                                            <span class="font-semibold text-gray-900">Usuario Destino Original</span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            {{ $solicitud->usuario_destino_nombre }} {{ $solicitud->usuario_destino_apellido }}
                                            @if($solicitud->nombre_area_destino)
                                            <br><span class="text-green-500">{{ $solicitud->nombre_area_destino }}</span>
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
                            <div class="mb-4">
                                <label class="flex items-start space-x-3 p-4 border-2 border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition-all duration-200"
                                       :class="{ 'border-blue-500 bg-blue-50': destinatarioGrupalSeleccionado === 'solicitante' }">
                                    <input type="radio" 
                                           x-model="destinatarioGrupalSeleccionado" 
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

                            <!-- Opción 3: Otro Usuario -->
                            <div class="mb-6">
                                <label class="flex items-start space-x-3 p-4 border-2 border-orange-200 rounded-xl hover:border-orange-400 hover:bg-orange-50 cursor-pointer transition-all duration-200"
                                       :class="{ 'border-orange-500 bg-orange-50': destinatarioGrupalSeleccionado === 'otro' }">
                                    <input type="radio" 
                                           x-model="destinatarioGrupalSeleccionado" 
                                           value="otro" 
                                           class="mt-1 text-orange-600 focus:ring-orange-500">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <i class="fas fa-users text-orange-600"></i>
                                            <span class="font-semibold text-gray-900">Otro Usuario</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Seleccionar un usuario diferente</p>
                                        
                                        <!-- Select de usuarios -->
                                        <div x-show="destinatarioGrupalSeleccionado === 'otro'" class="mt-3">
                                            <select x-model="usuarioGrupalSeleccionado"
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
                                <button @click="cerrarModalDestinatarioGrupal"
                                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                                    Cancelar
                                </button>
                                <button @click="confirmarProcesamientoGrupal"
                                        :disabled="!destinatarioGrupalValido"
                                        :class="{
                                            'bg-emerald-600 hover:bg-emerald-700': destinatarioGrupalValido,
                                            'bg-gray-400 cursor-not-allowed': !destinatarioGrupalValido
                                        }"
                                        class="flex-1 px-4 py-2.5 text-white rounded-xl font-medium transition-colors">
                                    <i class="fas fa-play-circle mr-2"></i>
                                    Procesar Todo el Lote
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Enviar a Abastecimiento -->
                <div x-show="mostrarModalEnviarAlmacen" 
                     x-cloak
                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-100"
                         x-show="mostrarModalEnviarAlmacen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        
                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-orange-600 to-red-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-warehouse text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Crear Solicitud de Abastecimiento</h3>
                                        <p class="text-orange-100 text-sm" x-text="`${articulosSeleccionadosEnviar.length} artículos seleccionados`"></p>
                                    </div>
                                </div>
                                <button @click="cerrarModalEnviarAlmacen" class="text-white hover:text-orange-200 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal -->
                        <div class="p-6 overflow-y-auto max-h-[70vh]">
                            <!-- Información General -->
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Información de la Solicitud</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Título -->
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Título de la Solicitud *
                                        </label>
                                        <input type="text" 
                                               x-model="formEnviarAlmacen.titulo"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                               placeholder="Ej: Reabastecimiento de artículos con stock insuficiente"
                                               required>
                                    </div>

                                    <!-- Tipo de Solicitud -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Tipo de Solicitud *
                                        </label>
                                        <select x-model="formEnviarAlmacen.idTipoSolicitud"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                required>
                                            <option value="">Seleccione tipo</option>
                                            <template x-for="tipo in tiposSolicitud" :key="tipo.idTipoSolicitud">
                                                <option :value="tipo.idTipoSolicitud" x-text="tipo.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Prioridad -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Prioridad *
                                        </label>
                                        <select x-model="formEnviarAlmacen.idPrioridad"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                required>
                                            <option value="">Seleccione prioridad</option>
                                            <template x-for="prioridad in prioridades" :key="prioridad.idPrioridad">
                                                <option :value="prioridad.idPrioridad" x-text="prioridad.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Fecha Requerida -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Fecha Requerida *
                                        </label>
                                        <input type="date" 
                                               x-model="formEnviarAlmacen.fecha_requerida"
                                               :min="new Date().toISOString().split('T')[0]"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                               required>
                                    </div>

                                    <!-- Centro de Costo -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Centro de Costo
                                        </label>
                                        <select x-model="formEnviarAlmacen.idCentroCosto"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            <option value="">Seleccione centro de costo</option>
                                            <template x-for="centro in centrosCosto" :key="centro.idCentroCosto">
                                                <option :value="centro.idCentroCosto" x-text="centro.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Área -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Área *
                                        </label>
                                        <select x-model="formEnviarAlmacen.idTipoArea"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                required>
                                            <option value="">Seleccione área</option>
                                            <template x-for="area in areas" :key="area.idTipoArea">
                                                <option :value="area.idTipoArea" x-text="area.nombre"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción y Justificación -->
                            <div class="mb-6">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Descripción -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Descripción *
                                        </label>
                                        <textarea x-model="formEnviarAlmacen.descripcion"
                                                  rows="3"
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                  placeholder="Describa el propósito de esta solicitud de abastecimiento..."
                                                  required></textarea>
                                    </div>

                                    <!-- Justificación -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Justificación *
                                        </label>
                                        <textarea x-model="formEnviarAlmacen.justificacion"
                                                  rows="3"
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                  placeholder="Explique por qué es necesario este abastecimiento..."
                                                  required></textarea>
                                    </div>

                                    <!-- Observaciones -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Observaciones
                                        </label>
                                        <textarea x-model="formEnviarAlmacen.observaciones"
                                                  rows="2"
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                  placeholder="Observaciones adicionales..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen de Artículos -->
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Artículos a Solicitar</h4>
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <div class="space-y-3">
                                        <template x-for="articulo in articulosSeleccionadosEnviar" :key="articulo.idArticulos">
                                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-semibold text-gray-900" x-text="articulo.nombre"></span>
                                                        <span class="text-sm text-gray-600">
                                                            Cantidad a solicitar: 
                                                            <span class="font-bold text-orange-600" 
                                                                  x-text="calcularCantidadSolicitar(articulo)"></span>
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mt-2">
                                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                                            Solicitado: <span class="font-bold" x-text="articulo.cantidad_solicitada"></span>
                                                        </span>
                                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded">
                                                            Disponible: <span class="font-bold" x-text="articulo.stock_disponible"></span>
                                                        </span>
                                                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded font-bold">
                                                            Faltan: <span x-text="articulo.cantidad_solicitada - articulo.stock_disponible"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Total -->
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-gray-900">Total de artículos:</span>
                                            <span class="font-bold text-orange-600" x-text="articulosSeleccionadosEnviar.length"></span>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="font-semibold text-gray-900">Total de unidades a solicitar:</span>
                                            <span class="font-bold text-orange-600" x-text="calcularTotalUnidades()"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer del Modal -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Complete todos los campos obligatorios (*)
                                </div>
                                <div class="flex space-x-3">
                                    <button @click="cerrarModalEnviarAlmacen"
                                            class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                                        Cancelar
                                    </button>
                                    <button @click="confirmarEnvioAlmacen"
                                            :disabled="!formEnviarAlmacenValido || enviandoAlmacen"
                                            :class="{
                                                'bg-orange-600 hover:bg-orange-700': formEnviarAlmacenValido && !enviandoAlmacen,
                                                'bg-gray-400 cursor-not-allowed': !formEnviarAlmacenValido || enviandoAlmacen
                                            }"
                                            class="px-6 py-2.5 text-white rounded-xl font-medium transition-colors flex items-center space-x-2">
                                        <i class="fas fa-spinner fa-spin" x-show="enviandoAlmacen"></i>
                                        <i class="fas fa-warehouse" x-show="!enviandoAlmacen"></i>
                                        <span x-text="enviandoAlmacen ? 'Creando Solicitud...' : 'Crear Solicitud de Abastecimiento'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>

    <script>
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
                    if (this.destinatarioSeleccionado === 'destino' || this.destinatarioSeleccionado === 'solicitante') {
                        return true;
                    }
                    if (this.destinatarioSeleccionado === 'otro') {
                        return this.usuarioSeleccionado !== '';
                    }
                    return false;
                },

                get destinatarioGrupalValido() {
                    if (this.destinatarioGrupalSeleccionado === 'destino' || this.destinatarioGrupalSeleccionado === 'solicitante') {
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
                        return this.selecciones[articulo.idArticulos] && this.selecciones[articulo.idArticulos] !== '';
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
                        const response = await fetch('/solicitudarticulo/enviar-almacen/modal-data');
                        const data = await response.json();
                        
                        this.tiposSolicitud = data.tiposSolicitud;
                        this.prioridades = data.prioridades;
                        this.centrosCosto = data.centrosCosto;
                        this.areas = data.areas;

                        // Establecer valores por defecto
                        this.formEnviarAlmacen.idPrioridad = this.prioridades.find(p => p.nivel >= 3)?.idPrioridad || this.prioridades[0]?.idPrioridad;
                        this.formEnviarAlmacen.fecha_requerida = new Date().toISOString().split('T')[0];
                        this.formEnviarAlmacen.titulo = 'Reabastecimiento - Solicitud {{ $solicitud->codigo }}';
                        this.formEnviarAlmacen.descripcion = 'Solicitud automática generada desde el sistema. Artículos con stock insuficiente de la solicitud: {{ $solicitud->codigo }}';
                        this.formEnviarAlmacen.justificacion = 'Los artículos incluidos en esta solicitud no cuentan con stock suficiente en almacén para atender la solicitud original.';
                        this.formEnviarAlmacen.observaciones = 'Generado automáticamente por el sistema el {{ now()->format("d/m/Y H:i") }}';
                        
                        // Establecer área desde la solicitud original
                        @if($solicitud->id_area_destino)
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
                    return this.articulosSeleccionadosEnviar.reduce((total, articulo) => total + this.calcularCantidadSolicitar(articulo), 0);
                },

                async confirmarEnvioAlmacen() {
                    if (!this.formEnviarAlmacenValido) {
                        this.mostrarNotificacion('error', 'Complete todos los campos obligatorios');
                        return;
                    }

                    this.enviandoAlmacen = true;

                    try {
                        const articulosParaEnviar = this.articulosSeleccionadosEnviar.map(articulo => ({
                            idArticulos: articulo.idArticulos,
                            nombre: articulo.nombre,
                            cantidad_solicitada: articulo.cantidad_solicitada,
                            stock_disponible: articulo.stock_disponible
                        }));

                        const response = await fetch(`/solicitudarticulo/{{ $solicitud->idsolicitudesordenes }}/enviar-almacen`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                articulos: articulosParaEnviar,
                                datos_solicitud: this.formEnviarAlmacen
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.mostrarNotificacion('success', 
                                `¡Solicitud de abastecimiento ${data.codigo_abastecimiento} creada exitosamente! ` +
                                `Se enviaron ${data.total_articulos} artículos.`
                            );
                            this.cerrarModalEnviarAlmacen();
                            
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            this.mostrarNotificacion('error', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.mostrarNotificacion('error', 'Error al crear la solicitud');
                    } finally {
                        this.enviandoAlmacen = false;
                    }
                },

                // ... (el resto de tus métodos existentes se mantienen igual)
                abrirModalDestinatario(solicitudId, articuloId, nombreArticulo) {
                    const ubicacionId = this.selecciones[articuloId];
                    
                    if (!ubicacionId) {
                        this.mostrarNotificacion('error', 'Seleccione una ubicación para este artículo');
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
                        this.mostrarNotificacion('error', 'Seleccione un destinatario válido');
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
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                            return '{{ $solicitud->usuario_destino_nombre ?? "Usuario Destino" }}';
                        case 'solicitante':
                            return '{{ $solicitante ? $solicitante->Nombre . " " . $solicitante->apellidoPaterno : "Solicitante" }}';
                        case 'otro':
                            const select = document.querySelector('[x-model="usuarioSeleccionado"]');
                            return select ? select.options[select.selectedIndex]?.text : 'Otro usuario';
                        default:
                            return 'No seleccionado';
                    }
                },

                async confirmarProcesamientoGrupal() {
                    if (!this.destinatarioGrupalValido) {
                        this.mostrarNotificacion('error', 'Seleccione un destinatario válido para el procesamiento grupal');
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
                        const response = await fetch(`/solicitudarticulo/${@json($solicitud->idsolicitudesordenes)}/aceptar`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ubicaciones: this.selecciones,
                                tipo_destinatario: this.destinatarioGrupalSeleccionado,
                                usuario_destino_id: this.obtenerUsuarioDestinoGrupalId()
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
                            return '{{ $solicitud->usuario_destino_nombre ?? "Usuario Destino" }}';
                        case 'solicitante':
                            return '{{ $solicitante ? $solicitante->Nombre . " " . $solicitante->apellidoPaterno : "Solicitante" }}';
                        case 'otro':
                            const select = document.querySelector('[x-model="usuarioGrupalSeleccionado"]');
                            return select ? select.options[select.selectedIndex]?.text : 'Otro usuario';
                        default:
                            return 'No seleccionado';
                    }
                },

                async validarYProcesarGrupal(id) {
                    if (!this.todasUbicacionesSeleccionadas) {
                        this.mostrarNotificacion('error',
                            'Debe seleccionar una ubicación para todos los artículos disponibles'
                        );
                        return;
                    }

                    if (!this.todosDisponibles) {
                        this.mostrarNotificacion('error',
                            'No todos los artículos tienen stock suficiente para procesamiento grupal'
                        );
                        return;
                    }

                    // Abrir modal para seleccionar destinatario grupal
                    this.abrirModalDestinatarioGrupal();
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
            }
        });

        // Función simplificada para enviar a abastecimiento
        Alpine.data('enviarAlmacen', function() {
            return {
                articulosSinStock: @json($articulos->where('suficiente_stock', false)->where('ya_procesado', false)->values()),
                articulosSeleccionados: [],

                init() {
                    // Inicializar con todos los artículos seleccionados por defecto
                    this.articulosSeleccionados = this.articulosSinStock.map(articulo => articulo.idArticulos);
                },

                seleccionarTodos() {
                    if (this.articulosSeleccionados.length === this.articulosSinStock.length) {
                        this.articulosSeleccionados = [];
                    } else {
                        this.articulosSeleccionados = this.articulosSinStock.map(articulo => articulo.idArticulos);
                    }
                },

                abrirModal() {
                    if (this.articulosSeleccionados.length === 0) {
                        this.mostrarNotificacion('error', 'Seleccione al menos un artículo para enviar a abastecimiento');
                        return;
                    }

                    // Obtener los artículos seleccionados
                    const articulosParaEnviar = this.articulosSinStock.filter(articulo => 
                        this.articulosSeleccionados.includes(articulo.idArticulos)
                    );

                    // Abrir modal usando el componente principal
                    const mainComponent = Alpine.$data(document.querySelector('[x-data="solicitudArticuloOpciones()"]'));
                    mainComponent.abrirModalEnviarAlmacen(articulosParaEnviar);
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