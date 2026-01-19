<x-layout.default>

    @php
        $estadosSinCeder = ['entregado', 'listo_para_entregar', 'aprobada'];
        $mostrarColumnaCeder = !in_array($solicitud->estado, $estadosSinCeder);

        // Obtener todos los usuarios disponibles para entregar repuestos
        $usuariosDisponibles = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'correo')
            ->where('estado', 1)
            ->orderBy('Nombre')
            ->get();

        Log::debug('COLUMNA CEDER - Decisión:', [
            'solicitud_id' => $solicitud->idsolicitudesordenes,
            'estado_solicitud' => $solicitud->estado,
            'mostrar_columna_ceder' => $mostrarColumnaCeder ? 'SÍ' : 'NO',
            'estados_bloqueados' => $estadosSinCeder,
        ]);
    @endphp

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

                            <div
                                class="flex items-center space-x-3 p-4 rounded-xl
    @if ($solicitud->estado == 'aprobada') bg-dark/10 border border-dark/20
    @elseif($solicitud->estado == 'listo_para_entregar')
        bg-success/10 border border-success/20
    @elseif($solicitud->estado == 'pendiente')
        bg-warning/10 border border-warning/20
    @elseif($solicitud->estado == 'parcial_listo')
        bg-orange-50 border border-orange-200
    @else
        bg-gray-50 border border-gray-200 @endif">

                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center
        @if ($solicitud->estado == 'aprobada') bg-dark/20
        @elseif($solicitud->estado == 'listo_para_entregar')
            bg-success/20
        @elseif($solicitud->estado == 'pendiente')
            bg-warning/20
        @elseif($solicitud->estado == 'parcial_listo')
            bg-orange-100
        @else
            bg-gray-100 @endif">

                                    <i
                                        class="fas fa-info-circle
            @if ($solicitud->estado == 'aprobada') text-dark
            @elseif($solicitud->estado == 'listo_para_entregar')
                text-success
            @elseif($solicitud->estado == 'pendiente')
                text-warning
            @elseif($solicitud->estado == 'parcial_listo')
                text-orange-600
            @else
                text-gray-600 @endif"></i>
                                </div>

                                <div>
                                    <p
                                        class="text-sm
            @if ($solicitud->estado == 'aprobada') text-dark/70
            @elseif($solicitud->estado == 'listo_para_entregar')
                text-success/70
            @elseif($solicitud->estado == 'pendiente')
                text-warning/70
            @elseif($solicitud->estado == 'parcial_listo')
                text-orange-600/70
            @else
                text-gray-500 @endif">
                                        Estado General
                                    </p>

                                    <p
                                        class="font-semibold capitalize
            @if ($solicitud->estado == 'aprobada') text-dark
            @elseif($solicitud->estado == 'listo_para_entregar')
                text-success
            @elseif($solicitud->estado == 'pendiente')
                text-warning
            @elseif($solicitud->estado == 'parcial_listo')
                text-orange-700
            @else
                text-gray-900 @endif">
                                        @if ($solicitud->estado == 'aprobada')
                                            Entregado
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
                <!-- Mensaje cuando no hay repuestos -->
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
                <!-- Panel Principal de Gestión -->
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
                            <!-- Tabla Mejorada -->
                            <div class="overflow-x-auto rounded-2xl border border-slate-200/60 shadow-sm mb-8">
                                <table class="w-full min-w-[900px] lg:min-w-full">
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
                                            <!-- NUEVA COLUMNA: Ceder/Estado -->
                                            @if ($mostrarColumnaCeder)
                                                <th
                                                    class="px-4 sm:px-6 py-4 sm:py-5 text-left text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60">
                                                    <i class="fas fa-exchange-alt text-slate-500 mr-1"></i>
                                                    <span class="hidden sm:inline">Ceder</span>
                                                    <span class="sm:hidden">Ced.</span>
                                                </th>
                                            @endif
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
        @php
            $esCedido = $repuesto->entrega_info && isset($repuesto->entrega_info->entrega_origen_id) && !empty($repuesto->entrega_info->entrega_origen_id);
            $estadoRepuesto = $repuesto->estado_actual ?? 'no_procesado';
            
            // Determinar estados especiales
            $esPendienteRetorno = $repuesto->ya_procesado && $repuesto->estado_actual == 'pendiente_por_retorno';
            $esUsado = $repuesto->ya_procesado && $repuesto->estado_actual == 'usado';
            $esDevuelto = $repuesto->ya_procesado && $repuesto->estado_actual == 'devuelto';
            
            // Si es pendiente por retorno, no debe mostrarse como cedido
            if ($esPendienteRetorno || $esUsado || $esDevuelto) {
                $esCedido = false;
            }
        @endphp
        
        <tr
            class="transition-all duration-200 hover:bg-blue-50/30
                @if ($repuesto->ya_procesado) 
                    @if ($esCedido) bg-purple-50/50
                    @elseif($esPendienteRetorno) bg-amber-50/50
                    @elseif($esUsado) bg-slate-50/50
                    @elseif($esDevuelto) bg-indigo-50/50
                    @else bg-emerald-50/50 
                    @endif
                @endif">

            <!-- Información del Repuesto -->
            <td class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10
                            @if ($esCedido) bg-secondary-light
                            @elseif($esPendienteRetorno) bg-warning-light
                            @elseif($esUsado) bg-success-light
                            @elseif($esDevuelto) bg-danger-light
                            @else bg-danger-light @endif rounded-xl flex items-center justify-center flex-shrink-0">
                        <i
                            class="fas fa-cog
                            @if ($esCedido) text-purple-600
                            @elseif($esPendienteRetorno) text-amber-600
                            @elseif($esUsado) text-success
                            @elseif($esDevuelto) text-danger
                            @else text-blue-600 @endif text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 text-sm sm:text-base truncate"
                            title="{{ $repuesto->nombre }}">
                            {{ $repuesto->nombre }}
                            @if ($esCedido && !$esPendienteRetorno && !$esUsado && !$esDevuelto)
                                <span class="text-xs text-purple-600 font-normal">
                                    (Cedido)</span>
                            @endif
                            @if ($esPendienteRetorno)
                                <span class="text-xs text-amber-600 font-normal">
                                    (Retorno Pendiente)</span>
                            @endif
                            @if ($esUsado)
                                <span class="text-xs text-success font-normal">
                                    (Usado)</span>
                            @endif
                            @if ($esDevuelto)
                                <span class="text-xs text-danger font-normal">
                                    (Devuelto)</span>
                            @endif
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
                    class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold
                        @if ($esCedido && !$esPendienteRetorno && !$esUsado && !$esDevuelto) bg-secondary-light text-secondary
                        @elseif($esPendienteRetorno) bg-amber-100 text-amber-700
                        @elseif($esUsado) bg-success-light text-success
                        @elseif($esDevuelto) bg-danger-light text-danger
                        @else bg-blue-100 text-blue-700 @endif">
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
                        class="text-base sm:text-lg font-bold
                            @if ($repuesto->suficiente_stock) 
                                @if ($esCedido && !$esPendienteRetorno && !$esUsado && !$esDevuelto) text-secondary
                                @elseif($esPendienteRetorno) text-amber-600
                                @elseif($esUsado) text-success
                                @elseif($esDevuelto) text-danger
                                @else text-emerald-600 @endif
                            @else text-rose-600 @endif">
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
                @if ($repuesto->ya_procesado)
                    <div class="text-center">
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold
                                @if ($esCedido && !$esPendienteRetorno && !$esUsado && !$esDevuelto) bg-secondary-light text-secondary
                                @elseif($esPendienteRetorno) bg-amber-100 text-amber-700
                                @elseif($esUsado) bg-success-light text-success
                                @elseif($esDevuelto) bg-danger-light text-danger
                                @else bg-emerald-100 text-emerald-700 @endif">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span class="hidden sm:inline">Procesado</span>
                            <span class="sm:hidden">OK</span>
                        </span>
                    </div>
                @elseif(isset($repuesto->ubicaciones_detalle) && count($repuesto->ubicaciones_detalle) > 0)
                    <div class="space-y-2 max-w-[180px] sm:max-w-xs">
                        <select name="ubicaciones[{{ $repuesto->idArticulos }}]"
                            class="w-full border border-slate-300 rounded-xl px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                            x-model="seleccionesUbicacion[{{ $repuesto->idArticulos }}]"
                            :disabled="seleccionesCeder[{{ $repuesto->idArticulos }}] ||
                                procesandoIndividual[{{ $repuesto->idArticulos }}]"
                            @change="resetearCeder({{ $repuesto->idArticulos }})">
                            <option value="">
                                <i
                                    class="fas fa-map-marker-alt mr-2 hidden sm:inline"></i>
                                <span class="text-xs sm:text-sm">Seleccionar
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

                        <button type="button"
                            @click="abrirModalDestinatario({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }}, '{{ $repuesto->nombre }}')"
                            :disabled="!seleccionesUbicacion[{{ $repuesto->idArticulos }}] ||
                                seleccionesCeder[{{ $repuesto->idArticulos }}] ||
                                procesandoIndividual[{{ $repuesto->idArticulos }}]"
                            :class="{
                                'bg-blue-600 hover:bg-blue-700': seleccionesUbicacion[
                                        {{ $repuesto->idArticulos }}] &&
                                    !seleccionesCeder[
                                        {{ $repuesto->idArticulos }}] &&
                                    !procesandoIndividual[
                                        {{ $repuesto->idArticulos }}],
                                'bg-gray-400 cursor-not-allowed': !
                                    seleccionesUbicacion[
                                        {{ $repuesto->idArticulos }}] ||
                                    seleccionesCeder[
                                        {{ $repuesto->idArticulos }}] ||
                                    procesandoIndividual[
                                        {{ $repuesto->idArticulos }}]
                            }"
                            class="w-full text-white px-3 py-2 rounded-lg font-medium transition-all duration-300 flex items-center justify-center text-xs">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Usar ubicación
                        </button>
                    </div>
                @else
                    <p class="text-xs sm:text-sm text-rose-500 italic font-medium">
                        <i class="fas fa-times-circle mr-1"></i>
                        <span class="hidden sm:inline">Sin ubicaciones</span>
                        <span class="sm:hidden">Sin ubic.</span>
                    </p>
                @endif
            </td>

            <!-- NUEVA COLUMNA: Ceder -->
            @if ($mostrarColumnaCeder)
                <td class="px-4 sm:px-6 py-4 sm:py-6">
                    @php
                        $estadosRepuestoSinSelect = [
                            'entregado',
                            'pendiente_entrega',
                            'listo_para_ceder',
                            'usado',
                            'devuelto',
                            'pendiente_por_retorno'
                        ];
                        $mostrarSelectRepuesto = !(
                            $repuesto->ya_procesado &&
                            in_array(
                                $repuesto->estado_actual,
                                $estadosRepuestoSinSelect,
                            )
                        );

                        $repuestosParaCeder = DB::table('repuestos_entregas as re')
                            ->select(
                                're.id',
                                're.ubicacion_utilizada',
                                're.articulo_id',
                                're.usuario_destino_id',
                                're.tipo_entrega',
                                're.cantidad',
                                're.numero_ticket',
                                're.estado',
                                're.solicitud_id',
                                're.fecha_entrega',
                                're.fecha_preparacion',
                                'so.codigo as codigo_solicitud_origen',
                                'u.Nombre as usuario_nombre',
                                'u.apellidoPaterno as usuario_apellido',
                                'a.nombre as articulo_nombre',
                                'a.codigo_repuesto',
                            )
                            ->leftJoin(
                                'usuarios as u',
                                're.usuario_destino_id',
                                '=',
                                'u.idUsuario',
                            )
                            ->leftJoin(
                                'articulos as a',
                                're.articulo_id',
                                '=',
                                'a.idArticulos',
                            )
                            ->leftJoin(
                                'solicitudesordenes as so',
                                're.solicitud_id',
                                '=',
                                'so.idsolicitudesordenes',
                            )
                            ->where('re.articulo_id', $repuesto->idArticulos)
                            ->where('re.estado', 'pendiente_por_retorno')
                            ->where(
                                're.solicitud_id',
                                '!=',
                                $solicitud->idsolicitudesordenes,
                            )
                            ->orderBy('re.fecha_entrega', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp

                    @if ($mostrarSelectRepuesto)
                        <div class="space-y-2 max-w-[220px]">
                            @if ($repuestosParaCeder->count() > 0)
                                <select
                                    class="w-full border border-slate-300 rounded-xl px-2 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                    x-model="seleccionesCeder[{{ $repuesto->idArticulos }}]"
                                    :disabled="seleccionesUbicacion[
                                            {{ $repuesto->idArticulos }}] ||
                                        procesandoIndividual[
                                            {{ $repuesto->idArticulos }}]"
                                    @change="resetearUbicacion({{ $repuesto->idArticulos }})">
                                    <option value="">-- Ceder repuesto --
                                    </option>

                                    @foreach ($repuestosParaCeder as $entrega)
                                        @php
                                            $usuarioInfo = $entrega->usuario_nombre
                                                ? substr(
                                                        $entrega->usuario_nombre,
                                                        0,
                                                        1,
                                                    ) .
                                                    '. ' .
                                                    $entrega->usuario_apellido
                                                : 'ID:' .
                                                    $entrega->usuario_destino_id;

$textoOpcion = "[{$entrega->codigo_solicitud_origen}] {$entrega->ubicacion_utilizada} - {$entrega->cantidad}uds - {$usuarioInfo}";                                        @endphp

                                        <option value="{{ $entrega->id }}"
                                            data-ubicacion="{{ $entrega->ubicacion_utilizada }}"
                                            data-cantidad="{{ $entrega->cantidad }}"
                                            data-usuario="{{ $entrega->usuario_destino_id }}"
                                            data-tipo="{{ $entrega->tipo_entrega }}"
                                            data-solicitud-origen="{{ $entrega->codigo_solicitud_origen }}"
                                            data-solicitud-id="{{ $entrega->solicitud_id }}"
                                            data-estado="{{ $entrega->estado }}"
                                            data-articulo-id="{{ $entrega->articulo_id }}"
                                            class="text-xs">
                                            {{ $textoOpcion }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="button"
                                    @click="abrirModalCeder({{ $repuesto->idArticulos }})"
                                    :disabled="!seleccionesCeder[
                                            {{ $repuesto->idArticulos }}] ||
                                        seleccionesUbicacion[
                                            {{ $repuesto->idArticulos }}] ||
                                        procesandoIndividual[
                                            {{ $repuesto->idArticulos }}]"
                                    :class="{
                                        'bg-secondary hover:bg-purple-600': seleccionesCeder[
                                                {{ $repuesto->idArticulos }}] &&
                                            !seleccionesUbicacion[
                                                {{ $repuesto->idArticulos }}] &&
                                            !procesandoIndividual[
                                                {{ $repuesto->idArticulos }}],
                                        'bg-gray-400 cursor-not-allowed': !
                                            seleccionesCeder[
                                                {{ $repuesto->idArticulos }}] ||
                                            seleccionesUbicacion[
                                                {{ $repuesto->idArticulos }}] ||
                                            procesandoIndividual[
                                                {{ $repuesto->idArticulos }}]
                                    }"
                                    class="w-full text-white px-3 py-2 rounded-lg font-medium transition-all duration-300 flex items-center justify-center text-xs">
                                    <i class="fas fa-exchange-alt mr-2"></i>
                                    Ceder repuesto
                                </button>
                            @else
                                <div
                                    class="text-center p-2 bg-amber-50 rounded-lg border border-amber-200">
                                    <i
                                        class="fas fa-clock text-amber-500 text-sm mb-1"></i>
                                    <p
                                        class="text-[11px] text-amber-700 font-medium">
                                        Sin retornos pendientes</p>
                                    <p class="text-[10px] text-amber-600 mt-1">
                                        {{ $repuesto->codigo_repuesto ?? $repuesto->nombre }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center">
                            <span class="text-xs text-gray-500 italic">
                                No aplica
                            </span>
                        </div>
                    @endif
                </td>
            @endif

            <!-- Estado -->
            <td class="px-4 sm:px-6 py-4 sm:py-6">
                @if ($repuesto->ya_procesado)
                    @if ($repuesto->estado_actual == 'cedido')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2
                            rounded-xl text-xs sm:text-sm font-semibold
                            bg-secondary-light text-secondary border border-purple-500 shadow-sm">
                            <i class="fas fa-exchange-alt mr-1"></i>
                            <span class="hidden sm:inline">Cedido</span>
                            <span class="sm:hidden">Ced.</span>
                        </span>
                    @elseif ($repuesto->estado_actual == 'entregado')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                            text-xs sm:text-sm font-semibold bg-dark text-white
                            border border-dark shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span class="hidden sm:inline">Entregado</span>
                            <span class="sm:hidden">Entreg.</span>
                        </span>
                    @elseif ($repuesto->estado_actual == 'entregado_cedido')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                            text-xs sm:text-sm font-semibold bg-secondary-light text-secondary
                            border border-purple-500 shadow-sm">
                            <i class="fas fa-exchange-alt mr-1"></i>
                            <span class="hidden sm:inline">Entregado Cedido</span>
                            <span class="sm:hidden">Ent. Cedido</span>
                        </span>
                    @elseif ($repuesto->estado_actual == 'pendiente_entrega')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                            text-xs sm:text-sm font-semibold bg-success text-white
                            border border-success shadow-sm">
                            <i class="fas fa-clock mr-1"></i>
                            <span class="hidden sm:inline">Listo para
                                Entregar</span>
                            <span class="sm:hidden">Listo</span>
                        </span>
                    @elseif ($repuesto->estado_actual == 'listo_para_ceder')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                            text-xs sm:text-sm font-semibold bg-secondary-light text-secondary
                            border border-purple-500 shadow-sm">
                            <i class="fas fa-exchange-alt mr-1"></i>
                            <span class="hidden sm:inline">Listo para Ceder</span>
                            <span class="sm:hidden">Ceder</span>
                        </span>
                    @elseif ($repuesto->estado_actual == 'pendiente_por_retorno')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                            text-xs sm:text-sm font-semibold bg-warning text-white
                            border border-warning shadow-sm">
                            <i class="fas fa-history mr-1"></i>
                            <span class="hidden sm:inline">Pendiente por Retorno</span>
                            <span class="sm:hidden">Pend. Retorno</span>
                        </span>
                    @elseif ($repuesto->estado_actual == 'usado')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                            text-xs sm:text-sm font-semibold bg-success-light text-success
                            border border-success shadow-sm">
                            <i class="fas fa-tools mr-1"></i>
                            <span class="hidden sm:inline">Usado</span>
                            <span class="sm:hidden">Usado</span>
                        </span>
                    @elseif ($repuesto->estado_actual == 'devuelto')
                        <span
                            class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                            text-xs sm:text-sm font-semibold bg-danger-light text-danger
                            border border-danger shadow-sm">
                            <i class="fas fa-undo-alt mr-1"></i>
                            <span class="hidden sm:inline">Devuelto</span>
                            <span class="sm:hidden">Dev.</span>
                        </span>
                    @endif
                @elseif ($repuesto->suficiente_stock)
                    <span
                        class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                        text-xs sm:text-sm font-semibold bg-warning text-white
                        border border-warning shadow-sm">
                        <i class="fas fa-cog mr-1"></i>
                        <span class="hidden sm:inline">Pendiente</span>
                        <span class="sm:hidden">Pend.</span>
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 rounded-xl
                        text-xs sm:text-sm font-semibold bg-danger text-white
                        border border-red-200 shadow-sm">
                        <i class="fas fa-times-circle mr-1"></i>
                        <span class="hidden sm:inline">Insuficiente</span>
                        <span class="sm:hidden">Ins.</span>
                    </span>
                @endif
            </td>

            <!-- Acción -->
            <td class="px-4 sm:px-6 py-4 sm:py-6">
                @if ($repuesto->ya_procesado)
                    @if ($repuesto->estado_actual == 'entregado' || $repuesto->estado_actual == 'entregado_cedido')
                        <button type="button"
                            @click="abrirModalVerConfirmacion({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }})"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-1 sm:py-1.5 rounded-lg font-medium transition-all duration-300 flex items-center justify-center text-xs sm:text-sm w-full">
                            <i class="fas fa-eye mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Ver Confirmación</span>
                            <span class="sm:hidden">Ver</span>
                        </button>
                    @elseif($repuesto->estado_actual == 'pendiente_entrega')
                        <div class="space-y-2">
                            <!-- Nuevo botón para seleccionar quién entrega -->
                            <button type="button"
                                @click="abrirModalSeleccionarEntregador({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }}, '{{ $repuesto->nombre }}')"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-1 sm:py-1.5 rounded-lg font-medium transition-all duration-300 flex items-center justify-center text-xs sm:text-sm w-full">
                                <i class="fas fa-user-check mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Seleccionar
                                    Entregador</span>
                                <span class="sm:hidden">Entregar</span>
                            </button>
                        </div>
                    @elseif($repuesto->estado_actual == 'listo_para_ceder')
                        <div class="space-y-2">
                            <button type="button"
                                @click="confirmarEntregaCedida({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }}, {{ $repuesto->entrega_info->entrega_id ?? 0 }})"
                                class="bg-secondary hover:bg-purple-700 text-white px-3 sm:px-4 py-1 sm:py-1.5 rounded-lg font-medium transition-all duration-300 flex items-center justify-center text-xs sm:text-sm w-full">
                                <i class="fas fa-exchange-alt mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Confirmar Entrega
                                    Cedida</span>
                                <span class="sm:hidden">Entregar Cedido</span>
                            </button>
                        </div>
                    @elseif($repuesto->estado_actual == 'pendiente_por_retorno')
                        <div class="text-center text-xs text-gray-500">
                            Esperando retorno
                        </div>
                    @elseif($repuesto->estado_actual == 'usado' || $repuesto->estado_actual == 'devuelto')
                        <div class="text-center text-xs text-gray-500">
                            Proceso completado
                        </div>
                    @endif
                @elseif($repuesto->suficiente_stock)
                    <div class="text-center text-xs text-gray-500">
                        Seleccione una opción
                    </div>
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

                <!-- Panel de Estrategias de Procesamiento -->
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
                        </div>

                        <!-- Barra de Estado Mejorada -->
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
                <div class="bg-success px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Marcar como Listo para Entrega</h3>
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
                                                    descontará</strong> hasta que el técnico marque como "usado".</p>
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

        <div x-show="mostrarModalSeleccionarEntregador" x-cloak @click.self="cerrarModalSeleccionarEntregador()"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all duration-300 scale-100"
                x-show="mostrarModalSeleccionarEntregador" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <!-- Header del Modal -->
                <div class="bg-primary px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-tag text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Seleccionar Quién Entregará</h3>
                                <p class="text-amber-100 text-sm" x-text="repuestoEntregaNombre"></p>
                            </div>
                        </div>
                        <button @click="cerrarModalSeleccionarEntregador"
                            class="text-white hover:text-amber-200 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-6 flex flex-col h-full">
                    <div class="flex-1 overflow-hidden flex flex-col">
                        <div class="mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                                <h4 class="font-semibold text-blue-700 mb-2">¿Quién entregará este repuesto?</h4>
                                <p class="text-sm text-gray-600">
                                    Seleccione el usuario que realizará la entrega física del repuesto.
                                    Este usuario deberá confirmar la entrega posteriormente.
                                </p>
                            </div>

                            <!-- Selección de usuario entregador -->
                            <div class="mb-6 flex-1 min-h-0">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="fas fa-user-circle mr-2"></i>Usuario que entregará:
                                </label>

                                <!-- Contenedor con scroll - MEJORADO -->
                                <div
                                    class="space-y-3 h-[300px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 hover:scrollbar-thumb-gray-400">
                                    <!-- Estilos personalizados para scroll -->
                                    <style>
                                        .scrollbar-thin::-webkit-scrollbar {
                                            width: 6px;
                                        }

                                        .scrollbar-thin::-webkit-scrollbar-track {
                                            background: #f1f1f1;
                                            border-radius: 3px;
                                        }

                                        .scrollbar-thin::-webkit-scrollbar-thumb {
                                            background: #d1d5db;
                                            border-radius: 3px;
                                        }

                                        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
                                            background: #9ca3af;
                                        }
                                    </style>

                                    <!-- Opción: Yo mismo (usuario actual) -->
                                    <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors"
                                        :class="usuarioEntregadorSeleccionado === 'yo_mismo' ? 'bg-blue-100 border-blue-300' :
                                            ''"
                                        @click="seleccionarUsuarioEntregador('yo_mismo', '{{ Auth::user()->name ?? 'Usuario Actual' }}')">
                                        <div class="flex-shrink-0 mr-3">
                                            <div
                                                class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">
                                                {{ Auth::user()->name ?? 'Usuario Actual' }}</p>
                                            <p class="text-xs text-gray-500">Yo mismo
                                                ({{ Auth::user()->email ?? 'Usuario' }})</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center"
                                                :class="usuarioEntregadorSeleccionado === 'yo_mismo' ?
                                                    'bg-primary border-primary' : ''">
                                                <i x-show="usuarioEntregadorSeleccionado === 'yo_mismo'"
                                                    class="fas fa-check text-white text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Opción: Técnico asignado -->
                                    @if ($tecnico)
                                        <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors"
                                            :class="usuarioEntregadorSeleccionado === '{{ $tecnico->idUsuario }}' ?
                                                'bg-green-100 border-green-300' : ''"
                                            @click="seleccionarUsuarioEntregador('{{ $tecnico->idUsuario }}', '{{ $tecnico->Nombre }} {{ $tecnico->apellidoPaterno }}')">
                                            <div class="flex-shrink-0 mr-3">
                                                <div
                                                    class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user-cog text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">{{ $tecnico->Nombre }}
                                                    {{ $tecnico->apellidoPaterno }}</p>
                                                <p class="text-xs text-gray-500">Técnico asignado a esta solicitud</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center"
                                                    :class="usuarioEntregadorSeleccionado === '{{ $tecnico->idUsuario }}' ?
                                                        'bg-success border-success' : ''">
                                                    <i x-show="usuarioEntregadorSeleccionado === '{{ $tecnico->idUsuario }}'"
                                                        class="fas fa-check text-white text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Separador "Otros usuarios" -->
                                    @if ($usuariosDisponibles->count() > 0)
                                        <div class="flex items-center gap-2 mt-4 mb-2">
                                            <div class="flex-1 border-t border-gray-200"></div>
                                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Otros usuarios
                                            </span>
                                            <div class="flex-1 border-t border-gray-200"></div>
                                        </div>
                                    @endif

                                    <!-- Lista de otros usuarios -->
                                    @forelse ($usuariosDisponibles as $usuario)
                                        @if (!$tecnico || $usuario->idUsuario != $tecnico->idUsuario)
                                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-purple-50 cursor-pointer transition-colors"
                                                :class="usuarioEntregadorSeleccionado === '{{ $usuario->idUsuario }}' ?
                                                    'bg-purple-100 border-purple-300' : ''"
                                                @click="seleccionarUsuarioEntregador('{{ $usuario->idUsuario }}', '{{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }}')">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div
                                                        class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-user-friends text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900">{{ $usuario->Nombre }}
                                                        {{ $usuario->apellidoPaterno }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $usuario->correo ?? 'Sin correo' }}</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center"
                                                        :class="usuarioEntregadorSeleccionado === '{{ $usuario->idUsuario }}'
                                                            ?
                                                            'bg-secondary border-secondary' : ''">
                                                        <i x-show="usuarioEntregadorSeleccionado === '{{ $usuario->idUsuario }}'"
                                                            class="fas fa-check text-white text-xs"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-4 text-gray-500">
                                            <i class="fas fa-users-slash text-2xl mb-2"></i>
                                            <p>No hay otros usuarios disponibles</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-500 mt-1 mr-2"></i>
                            <div>
                                <p class="text-sm font-semibold text-yellow-700 mb-1">Importante:</p>
                                <p class="text-xs text-yellow-600">
                                    El usuario seleccionado deberá confirmar la entrega posteriormente con foto y
                                    firma.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex space-x-3">
                        <button @click="cerrarModalSeleccionarEntregador"
                            class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button @click="confirmarSeleccionEntregador" :disabled="!usuarioEntregadorSeleccionado"
                            :class="{
                                'bg-primary': usuarioEntregadorSeleccionado,
                                'bg-gray-400 cursor-not-allowed': !usuarioEntregadorSeleccionado
                            }"
                            class="flex-1 px-4 py-2.5 text-white rounded-xl font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Confirmar Entregador
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ceder repuesto -->
        <div x-show="mostrarModalCeder" x-cloak @click.self="cerrarModalCeder()"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all duration-300 scale-100"
                x-show="mostrarModalCeder" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <!-- HEADER -->
                <div class="bg-secondary px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Ceder repuesto</h3>
                                <p class="text-white/80 text-sm"
                                    x-text="entregaCederInfo.repuestoNombre || 'Repuesto'">
                                </p>
                            </div>
                        </div>
                        <button @click="cerrarModalCeder" class="text-white hover:text-white/70 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- CONTENIDO -->
                <div class="p-6 space-y-6">

                    <!-- DETALLE -->
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                        <h4 class="font-semibold text-purple-700 mb-3">
                            Detalles del repuesto a ceder
                        </h4>

                        <div class="space-y-2 text-sm">
                            <div class="flex">
                                <span class="font-medium w-32">Repuesto:</span>
                                <span x-text="entregaCederInfo.repuestoNombre" class="text-gray-700"></span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-32">Ubicación:</span>
                                <span x-text="entregaCederInfo.ubicacion" class="text-gray-700"></span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-32">Cantidad:</span>
                                <span class="text-gray-700">
                                    <span x-text="entregaCederInfo.cantidad"></span> unidades
                                </span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-32">Solicitud origen:</span>
                                <span x-text="entregaCederInfo.solicitudOrigen" class="text-gray-700"></span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-32">Estado:</span>
                                <span class="text-orange-600 font-semibold" x-text="entregaCederInfo.estado"></span>
                            </div>
                        </div>
                    </div>

                    <!-- INFO -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-2">
                        <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                        <p class="text-xs text-amber-700">
                            Al ceder este repuesto, estás reutilizando uno que ya fue entregado anteriormente y está
                            marcado como <strong>"pendiente por retorno"</strong>. No se descontará de stock nuevo, sino
                            que se
                            reutilizará el mismo repuesto.
                        </p>
                    </div>

                    <!-- ACCIONES -->
                    <div class="flex gap-3">
                        <button @click="cerrarModalCeder"
                            class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700
                           rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>

                        <button @click="confirmarCeder"
                            class="flex-1 px-4 py-2.5 bg-secondary hover:bg-secondary/90
                           text-white rounded-xl font-medium transition-colors
                           flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Confirmar ceder
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
                        <!-- Información del repuesto y entregador -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Detalles de entrega</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="repuestoEntregaNombre"></p>
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-user-tag text-green-600"></i>
                                    <span class="text-gray-700 dark:text-gray-300 font-medium">Entregador:</span>
                                    <span class="text-gray-700 dark:text-gray-300" x-text="nombreEntregador"></span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-user-check text-blue-600"></i>
                                    <span class="text-gray-700 dark:text-gray-300 font-medium">Para:</span>
                                    <span
                                        class="text-gray-700 dark:text-gray-300">{{ $tecnico ? $tecnico->Nombre . ' ' . $tecnico->apellidoPaterno : 'Técnico no asignado' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sección para foto -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-camera mr-2"></i>Foto del repuesto entregado
                            </label>
                            <div class="mt-1">
                                <!-- Contenedor para subir imagen -->
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
                                        <div class="relative w-full h-64 sm:h-72 md:h-80 overflow-hidden rounded-md">
                                            <img :src="fotoPreviewEntrega" alt="Vista previa"
                                                class="absolute inset-0 w-full h-full object-contain">
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

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-comment-alt mr-2"></i>Observaciones (opcional)
                            </label>
                            <textarea x-model="observacionesEntrega" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                                placeholder="Alguna observación sobre la entrega..."></textarea>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end items-center mt-8 gap-3">
                            <button type="button" @click="cerrarModalEntrega()"
                                class="bg-danger hover:bg-danger/90 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 flex items-center gap-2 border-0">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </button>

                            <button type="button" @click="firmarYConfirmarEntrega()"
                                :disabled="!fotoPreviewEntrega || isLoadingEntrega"
                                :class="{
                                    'opacity-50 cursor-not-allowed': !fotoPreviewEntrega || isLoadingEntrega,
                                    'bg-success hover:bg-success/90': true
                                }"
                                class="text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 flex items-center gap-2 border-0">
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

        <!-- Modal para confirmar entrega cedida -->
        <div x-show="mostrarModalEntregaCedida" x-cloak class="fixed inset-0 bg-[black]/60 z-[9999] overflow-y-auto">

            <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModalEntregaCedida()">

                <div x-show="mostrarModalEntregaCedida" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8
                   w-full max-w-lg bg-white">

                    <!-- HEADER -->
                    <div class="flex bg-secondary text-white items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg flex items-center gap-2">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Confirmar Entrega Cedida</span>
                        </div>
                        <button type="button" class="text-white hover:text-gray-200"
                            @click="cerrarModalEntregaCedida()">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <!-- CONTENIDO -->
                    <div class="p-5">

                        <!-- INFO -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-2">
                                Detalles de entrega cedida
                            </h4>

                            <p class="text-sm text-gray-600" x-text="repuestoEntregaNombre"></p>

                            <div class="mt-3 space-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-exchange-alt text-purple-600"></i>
                                    <span class="font-medium text-gray-700">
                                        Repuesto Cedido
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-orange-500"></i>
                                    <span class="font-semibold text-orange-600">
                                        Pendiente por Retorno
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- FOTO -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-camera mr-2"></i>
                                Foto del repuesto cedido entregado
                            </label>

                            <!-- SUBIR -->
                            <div x-show="!fotoPreviewEntregaCedida" @click="abrirFileInputCedida()"
                                class="flex justify-center px-6 pt-5 pb-6
                               border-2 border-dashed border-gray-300
                               rounded-lg hover:border-purple-400
                               cursor-pointer transition-colors">

                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>

                                    <div class="flex flex-col items-center text-sm text-gray-600">
                                        <span class="font-medium text-purple-600">
                                            Subir una foto
                                        </span>
                                        <p class="mt-1">o arrastra y suelta</p>
                                    </div>

                                    <p class="text-xs text-gray-500 mt-2">
                                        JPG, PNG • Máx 5MB
                                    </p>
                                </div>
                            </div>

                            <!-- PREVIEW (ALTURA LIMITADA, NO CRECE MODAL) -->
                            <div x-show="fotoPreviewEntregaCedida" class="relative">
                                <div
                                    class="border-2 border-purple-500 rounded-lg
                                    overflow-hidden bg-gray-100 p-2">
                                    <div class="relative w-full h-64 overflow-hidden rounded-md">
                                        <img :src="fotoPreviewEntregaCedida"
                                            class="absolute inset-0 w-full h-full object-contain" alt="Vista previa">
                                    </div>
                                </div>

                                <!-- QUITAR -->
                                <button type="button" @click.stop="removeFotoEntregaCedida()"
                                    class="absolute top-3 right-3 bg-danger text-white
                                   rounded-full p-2 hover:bg-danger/90 shadow-lg">
                                    <i class="fas fa-times text-sm"></i>
                                </button>

                                <!-- CAMBIAR -->
                                <button type="button" @click.stop="abrirFileInputCedida()"
                                    class="absolute top-3 left-3 bg-primary text-white
                                   rounded-full p-2 hover:bg-primary/90 shadow-lg">
                                    <i class="fas fa-sync-alt text-sm"></i>
                                </button>
                            </div>

                            <input type="file" x-ref="fileInputEntregaCedida" class="hidden" accept="image/*"
                                @change="handleFotoUploadEntregaCedida">
                        </div>

                        <!-- OBSERVACIONES -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-alt mr-2"></i>
                                Observaciones (opcional)
                            </label>
                            <textarea x-model="observacionesEntregaCedida" rows="3"
                                class="w-full px-3 py-2 border border-gray-300
                               rounded-lg focus:ring-2 focus:ring-purple-500
                               focus:border-transparent"
                                placeholder="Alguna observación sobre la entrega..."></textarea>
                        </div>

                        <!-- ACCIONES -->
                        <div class="flex justify-end items-center mt-8 gap-3">

                            <button type="button" @click="cerrarModalEntregaCedida()"
                                class="bg-danger hover:bg-danger/90 text-white
                               px-4 py-2 rounded-lg font-medium
                               flex items-center gap-2 border-0">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </button>

                            <button type="button" @click="firmarYConfirmarEntregaCedida()"
                                :disabled="!fotoPreviewEntregaCedida || isLoadingEntregaCedida"
                                :class="{
                                    'opacity-50 cursor-not-allowed':
                                        !fotoPreviewEntregaCedida || isLoadingEntregaCedida,
                                    'bg-secondary hover:bg-secondary/90': true
                                }"
                                class="text-white px-4 py-2 rounded-lg font-medium
                               flex items-center gap-2 border-0">

                                <template x-if="isLoadingEntregaCedida">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </template>
                                <template x-if="!isLoadingEntregaCedida">
                                    <i class="fas fa-signature"></i>
                                </template>

                                <span>Firmar y Confirmar Entrega</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal confirmación de entrega -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="mostrarModalVerConfirmacion && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4 py-8"
                @click.self="cerrarModalVerConfirmacion()">

                <div x-show="mostrarModalVerConfirmacion" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-xl overflow-hidden my-8 w-full max-w-4xl shadow-2xl dark:shadow-2xl dark:shadow-gray-900/30">

                    <!-- Header con gradiente -->
                    <div class="relative bg-primary px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white">CONFIRMACIÓN DE ENTREGA</h3>
                                    <p class="text-xs text-blue-100 dark:text-gray-300 mt-0.5">Documentación del
                                        repuesto entregado</p>
                                </div>
                            </div>
                            <button type="button"
                                class="text-white hover:text-blue-200 dark:hover:text-gray-300 transition-colors"
                                @click="cerrarModalVerConfirmacion()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del modal - Diseño de dos columnas -->
                    <div class="flex flex-col lg:flex-row bg-white dark:bg-gray-800">

                        <!-- COLUMNA IZQUIERDA - INFORMACIÓN -->
                        <div class="lg:w-2/3 p-6">

                            <!-- Tarjeta principal del repuesto -->
                            <div
                                class="mb-6 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-lg">
                                <div class="flex items-center mb-4">
                                    <div class="relative mr-4">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                                            <i class="fas fa-box-open text-white text-lg"></i>
                                        </div>
                                        <div
                                            class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                                            <i class="fas fa-check text-white text-[8px]"></i>
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4
                                                class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                REPUESTO ENTREGADO</h4>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i>CONFIRMADO
                                            </span>
                                        </div>

                                        <!-- Código -->
                                        <p class="text-lg font-bold text-gray-900 dark:text-white"
                                            x-text="repuestoVerCodigo" :title="repuestoVerCodigo"></p>

                                        <!-- Subcategoría -->
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mt-1">
                                            <i class="fas fa-layer-group text-gray-400 mr-2 text-xs"></i>
                                            <span x-text="repuestoVerSubcategoria"
                                                :title="repuestoVerSubcategoria"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Grid de información detallada -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                                <!-- Tarjeta Entregado por -->
                                <div
                                    class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-user-check text-blue-600 dark:text-blue-400 text-xs"></i>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-semibold text-gray-500 dark:text-gray-400">ENTREGADO
                                                POR</span>
                                        </div>
                                    </div>
                                    <div class="pl-11">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-1"
                                            x-text="entregaInfo.usuario_entrego || 'Almacén'"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Personal autorizado</p>
                                    </div>
                                </div>

                                <!-- Tarjeta Fecha de entrega -->
                                <div
                                    class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i
                                                class="fas fa-calendar-check text-green-600 dark:text-green-400 text-xs"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">FECHA
                                                Y HORA</span>
                                        </div>
                                    </div>
                                    <div class="pl-11">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-1"
                                            x-text="entregaInfo.fecha_entrega || 'No disponible'"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Fecha de entrega</p>
                                    </div>
                                </div>

                                <!-- Tarjeta Estado de firma -->
                                <div
                                    class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3"
                                            :class="entregaInfo.firma_confirma ?
                                                'bg-green-100 dark:bg-green-900/30' :
                                                'bg-orange-100 dark:bg-orange-900/30'">
                                            <i class="fas fa-signature text-xs"
                                                :class="entregaInfo.firma_confirma ?
                                                    'text-green-600 dark:text-green-400' :
                                                    'text-orange-600 dark:text-orange-400'"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">FIRMA
                                                DIGITAL</span>
                                        </div>
                                    </div>
                                    <div class="pl-11">
                                        <p class="text-sm font-bold mb-1"
                                            :class="entregaInfo.firma_confirma ?
                                                'text-green-700 dark:text-green-300' :
                                                'text-orange-700 dark:text-orange-300'"
                                            x-text="entregaInfo.firma_confirma ? 'CONFIRMADA' : 'PENDIENTE'"></p>
                                    </div>
                                </div>

                                <!-- Tarjeta Método de entrega -->
                                <div
                                    class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-truck text-purple-600 dark:text-purple-400 text-xs"></i>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-semibold text-gray-500 dark:text-gray-400">MÉTODO</span>
                                        </div>
                                    </div>
                                    <div class="pl-11">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">ENTREGA FÍSICA
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Presencial con evidencia
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div x-show="entregaInfo.observaciones_entrega" class="mb-6">
                                <div class="flex items-center mb-3">
                                    <div
                                        class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-sticky-note text-yellow-600 dark:text-yellow-400 text-xs"></i>
                                    </div>
                                    <div>
                                        <span
                                            class="text-sm font-semibold text-gray-500 dark:text-gray-400">OBSERVACIONES</span>
                                    </div>
                                </div>
                                <div
                                    class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 ml-11">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                                        x-text="entregaInfo.observaciones_entrega"></p>
                                </div>
                            </div>

                        </div>

                        <!-- COLUMNA DERECHA - FOTO Y DETALLES VISUALES -->
                        <div
                            class="lg:w-1/3 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-black p-6 border-l border-gray-200 dark:border-gray-700">

                            <!-- Sección de Foto Principal -->
                            <div x-show="entregaInfo.foto_url" class="mb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-camera text-red-600 dark:text-red-400 text-xs"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">EVIDENCIA
                                                VISUAL</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Foto de entrega</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenedor de la foto -->
                                <div
                                    class="relative rounded-xl overflow-hidden border-2 border-white dark:border-gray-800 shadow-xl bg-black">
                                    <div class="relative w-full h-80 bg-gray-900">
                                        <img :src="entregaInfo.foto_url" class="w-full h-full object-contain p-3"
                                            x-on:error="entregaInfo.foto_url = null"
                                            alt="Evidencia fotográfica de entrega">

                                        <!-- Overlay de verificación -->
                                        <div class="absolute top-3 right-3">
                                            <div
                                                class="bg-green-600 text-white text-xs px-3 py-1.5 rounded-full flex items-center shadow-md">
                                                <i class="fas fa-check-circle mr-1 text-xs"></i>
                                                <span class="font-semibold">VERIFICADO</span>
                                            </div>
                                        </div>

                                        <!-- Marca de agua sutil -->
                                        <div
                                            class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-5">
                                            <i class="fas fa-check-circle text-6xl text-white"></i>
                                        </div>
                                    </div>

                                    <!-- Pie de foto -->
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3">
                                        <div class="text-white">
                                            <p class="text-xs font-medium">Foto de entrega confirmada</p>
                                            <p class="text-[10px] text-gray-300">Tomada al momento de la entrega</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detalles de la foto -->
                                <div
                                    class="mt-4 bg-white dark:bg-gray-900 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">TIPO</p>
                                            <p class="text-xs font-semibold text-gray-900 dark:text-white">Entrega
                                                Física</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">ESTADO</p>
                                            <p class="text-xs font-semibold text-green-600 dark:text-green-400">
                                                Completado</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de cierre en versión móvil -->
                            <div class="lg:hidden mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
                                <button type="button"
                                    class="btn w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 border-0 text-white text-sm py-2.5 shadow-lg"
                                    @click="cerrarModalVerConfirmacion()">
                                    <i class="fas fa-times mr-2"></i>
                                    Cerrar confirmación
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de cierre (solo desktop) -->
                    <div
                        class="hidden lg:flex justify-end items-center px-6 py-5 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                            class="btn bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 border-0 text-white text-sm py-2.5 px-6 shadow-lg transition-all duration-300"
                            @click="cerrarModalVerConfirmacion()">
                            <i class="fas fa-times mr-2"></i>
                            Cerrar confirmación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                // Variables para ubicación
                seleccionesUbicacion: {},
                seleccionesCeder: {},
                procesandoIndividual: {},
                isLoadingGrupal: false,

                // Variables para modales
                entregaInfo: {},
                repuestoVerCodigo: null,
                repuestoVerSubcategoria: null,
                mostrarModalVerConfirmacion: false,
                repuestoVerNombre: '',

                // Modal destinatario
                mostrarModalDestinatario: false,
                destinatarioSeleccionado: 'tecnico',
                solicitudIdSeleccionada: null,
                articuloIdSeleccionado: null,
                repuestoSeleccionadoNombre: '',

                // Variables para controlar el flujo de entrega
                usuarioEntregadorSeleccionado: null,
                nombreEntregador: '',
                solicitudIdParaEntrega: null,
                articuloIdParaEntrega: null,
                repuestoNombreParaEntrega: '',

                // Modal seleccionar entregador
                mostrarModalSeleccionarEntregador: false,

                // Modal ceder
                mostrarModalCeder: false,
                entregaCederInfo: {},
                articuloCederId: null,

                // Modal entrega física
                mostrarModalEntregaFisica: false,
                fotoPreviewEntrega: null,
                fotoFileEntrega: null,
                firmaConfirmadaEntrega: false,
                fechaFirmaEntrega: null,
                observacionesEntrega: '',
                isLoadingEntrega: false,

                // Modal entrega cedida
                mostrarModalEntregaCedida: false,
                fotoPreviewEntregaCedida: null,
                fotoFileEntregaCedida: null,
                observacionesEntregaCedida: '',
                isLoadingEntregaCedida: false,
                repuestoEntregaNombre: null,
                entregaCedidaId: null,

                // Getters
                get destinatarioValido() {
                    return @json($tecnico != null);
                },

                get todasUbicacionesSeleccionadas() {
                    const repuestos = @json($repuestos);
                    return repuestos.every(repuesto => {
                        if (repuesto.ya_procesado || !repuesto.suficiente_stock) {
                            return true;
                        }
                        return this.seleccionesUbicacion[repuesto.idArticulos] && this
                            .seleccionesUbicacion[
                                repuesto.idArticulos] !== '';
                    });
                },

                get todosDisponibles() {
                    return @json($puede_aceptar);
                },

                // ============ FUNCIONES PARA BLOQUEO MUTUO ============
                resetearCeder(articuloId) {
                    if (this.seleccionesUbicacion[articuloId]) {
                        this.seleccionesCeder[articuloId] = '';
                    }
                },

                resetearUbicacion(articuloId) {
                    if (this.seleccionesCeder[articuloId]) {
                        this.seleccionesUbicacion[articuloId] = '';
                    }
                },

                // ============ MÉTODOS PARA SELECCIONAR QUIÉN ENTREGARÁ ============
                abrirModalSeleccionarEntregador(solicitudId, articuloId, nombreRepuesto) {
                    console.log('🔍 Abriendo modal para seleccionar entregador');
                    console.log('   - Solicitud ID:', solicitudId);
                    console.log('   - Artículo ID:', articuloId);
                    console.log('   - Repuesto:', nombreRepuesto);

                    // Guardar los datos para la entrega
                    this.solicitudIdParaEntrega = solicitudId;
                    this.articuloIdParaEntrega = articuloId;
                    this.repuestoNombreParaEntrega = nombreRepuesto;

                    // Resetear selección previa
                    this.usuarioEntregadorSeleccionado = null;
                    this.nombreEntregador = '';

                    this.mostrarModalSeleccionarEntregador = true;
                },

                cerrarModalSeleccionarEntregador() {
                    this.mostrarModalSeleccionarEntregador = false;
                },

                confirmarSeleccionEntregador() {
                    console.log('✅ Confirmando selección de entregador');
                    console.log('   - Usuario seleccionado:', this.usuarioEntregadorSeleccionado);
                    console.log('   - Nombre entregador:', this.nombreEntregador);

                    if (!this.usuarioEntregadorSeleccionado) {
                        toastr.error('Seleccione un usuario que entregará el repuesto');
                        return;
                    }

                    this.cerrarModalSeleccionarEntregador();

                    // Ahora abrir el modal de confirmación de entrega con foto
                    console.log('🔄 Abriendo modal de confirmación de entrega');
                    console.log('   - Solicitud ID:', this.solicitudIdParaEntrega);
                    console.log('   - Artículo ID:', this.articuloIdParaEntrega);
                    console.log('   - Repuesto:', this.repuestoNombreParaEntrega);

                    this.abrirModalConfirmarEntrega();
                },

                abrirModalConfirmarEntrega() {
                    const repuestos = @json($repuestos);
                    const repuesto = repuestos.find(r =>
                        parseInt(r.idArticulos) === parseInt(this.articuloIdParaEntrega)
                    );

                    if (!repuesto) {
                        toastr.error('No se encontró información del repuesto');
                        return;
                    }

                    this.articuloEntregaId = repuesto.idArticulos;

                    // ✅ AQUÍ se arma el texto FINAL
                    this.repuestoEntregaNombre = repuesto.codigo_repuesto ?
                        `${repuesto.nombre} (${repuesto.codigo_repuesto})` :
                        repuesto.nombre;

                    // (opcional) si quieres usarlo aparte
                    this.repuestoVerCodigo = repuesto.codigo_repuesto ?? null;

                    this.resetFormEntrega();
                    this.mostrarModalEntregaFisica = true;
                },

                // ============ MÉTODOS PARA MANEJAR SELECCIÓN DE USUARIO ============
                seleccionarUsuarioEntregador(usuarioId, nombreUsuario) {
                    console.log('👤 Seleccionando usuario entregador:', usuarioId, nombreUsuario);
                    this.usuarioEntregadorSeleccionado = usuarioId;
                    this.nombreEntregador = nombreUsuario;
                },

                // Helper para obtener nombre de usuario
                obtenerNombreUsuario(usuarioId) {
                    if (usuarioId === 'yo_mismo') {
                        return '{{ Auth::user()->name ?? 'Usuario Actual' }}';
                    }

                    // Buscar en la lista de usuarios
                    const usuarios = @json($usuariosDisponibles);
                    const tecnico = @json($tecnico);

                    if (usuarioId === '{{ $tecnico->idUsuario ?? '' }}') {
                        return '{{ $tecnico ? $tecnico->Nombre . ' ' . $tecnico->apellidoPaterno : '' }}';
                    }

                    const usuarioEncontrado = usuarios.find(u => u.idUsuario == usuarioId);
                    return usuarioEncontrado ?
                        `${usuarioEncontrado.Nombre} ${usuarioEncontrado.apellidoPaterno}` :
                        'Usuario desconocido';
                },

                // ============ MÉTODOS PARA CEDER REPUESTO ============
                abrirModalCeder(articuloId) {
                    console.log('🔍 =========== DEBUG ABRIR MODAL CEDER ===========');

                    const selectElement = document.querySelector(
                        `select[x-model="seleccionesCeder[${articuloId}]"]`);

                    if (!selectElement) {
                        console.error('❌ Select no encontrado');
                        toastr.error('Error: No se encontró el selector de ceder');
                        return;
                    }

                    const selectedOption = selectElement.options[selectElement.selectedIndex];

                    if (!selectedOption || !selectedOption.value || selectedOption.value === '') {
                        console.error('❌ No hay opción seleccionada');
                        toastr.error('Seleccione un repuesto para ceder');
                        return;
                    }

                    const entregaId = selectedOption.value;

                    const repuestos = @json($repuestos);
                    const repuesto = repuestos.find(r => parseInt(r.idArticulos) === parseInt(
                        articuloId));

                    if (!repuesto) {
                        console.error('❌ Repuesto no encontrado');
                        toastr.error('No se encontró información del repuesto');
                        return;
                    }

                    this.articuloCederId = parseInt(articuloId);
                    this.entregaCederInfo = {
                        id: parseInt(entregaId),
                        ubicacion: selectedOption.dataset.ubicacion || '',
                        cantidad: selectedOption.dataset.cantidad ? parseInt(selectedOption.dataset
                            .cantidad) : 0,
                        usuario: selectedOption.dataset.usuario || '',
                        tipo: selectedOption.dataset.tipo || '',
                        solicitudOrigen: selectedOption.dataset.solicitudOrigen || '',
                        solicitudId: selectedOption.dataset.solicitudId || '',
                        estado: selectedOption.dataset.estado || '',
                        repuestoNombre: repuesto.nombre || repuesto.codigo_repuesto || 'Repuesto'
                    };

                    this.mostrarModalCeder = true;
                },

                cerrarModalCeder() {
                    this.mostrarModalCeder = false;
                    this.entregaCederInfo = {};
                    this.articuloCederId = null;
                },

                async confirmarCeder() {
                    console.log('=========== CONFIRMAR CEDER ===========');

                    const selectElement = document.querySelector(
                        `select[x-model="seleccionesCeder[${this.articuloCederId}]"]`);

                    if (!selectElement) {
                        console.error('❌ Select no encontrado');
                        toastr.error('Error interno: No se encontró el selector');
                        return;
                    }

                    const selectedOption = selectElement.options[selectElement.selectedIndex];

                    if (!selectedOption || !selectedOption.value) {
                        toastr.error('No hay repuesto seleccionado para ceder');
                        return;
                    }

                    const entregaId = selectedOption.value;
                    const articuloId = this.articuloCederId;

                    const payload = {
                        articulo_id: articuloId,
                        entrega_id: entregaId,
                        ubicacion: selectedOption.dataset.ubicacion || '',
                        cantidad: selectedOption.dataset.cantidad || 0,
                        tipo_entrega: selectedOption.dataset.tipo || '',
                        usuario_destino: selectedOption.dataset.usuario || '',
                        solicitud_origen: selectedOption.dataset.solicitudOrigen || '',
                        solicitud_id_origen: selectedOption.dataset.solicitudId || ''
                    };

                    try {
                        this.procesandoIndividual[articuloId] = true;
                        this.cerrarModalCeder();

                        const response = await fetch(
                            `/solicitudrepuesto/{{ $solicitud->idsolicitudesordenes }}/ceder-repuesto`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(payload)
                            });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            toastr.error(data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error de conexión');
                    } finally {
                        this.procesandoIndividual[articuloId] = false;
                    }
                },

                // ============ MÉTODOS PARA ENTREGA CEDIDA ============
                confirmarEntregaCedida(solicitudId, articuloId, entregaId) {
                    console.log('🔍 Confirmar entrega cedida:');
                    console.log('  - Solicitud ID:', solicitudId);
                    console.log('  - Artículo ID:', articuloId);
                    console.log('  - Entrega ID:', entregaId);

                    const repuestos = @json($repuestos);
                    const repuesto = repuestos.find(r => parseInt(r.idArticulos) === parseInt(
                        articuloId));

                    if (!repuesto) {
                        toastr.error('No se encontró información del repuesto');
                        return;
                    }

                    this.articuloEntregaId = articuloId;
                    this.repuestoEntregaNombre = repuesto.nombre || repuesto.codigo_repuesto ||
                        'Repuesto';
                    this.entregaCedidaId = entregaId;

                    this.abrirModalEntregaCedida();
                },

                abrirModalEntregaCedida() {
                    this.mostrarModalEntregaCedida = true;
                    this.resetFormEntregaCedida();
                },

                cerrarModalEntregaCedida() {
                    this.mostrarModalEntregaCedida = false;
                    this.resetFormEntregaCedida();
                },

                resetFormEntregaCedida() {
                    this.fotoPreviewEntregaCedida = null;
                    this.fotoFileEntregaCedida = null;
                    this.observacionesEntregaCedida = '';
                    this.isLoadingEntregaCedida = false;
                },

                abrirFileInputCedida() {
                    this.$refs.fileInputEntregaCedida.click();
                },

                handleFotoUploadEntregaCedida(event) {
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

                        this.fotoFileEntregaCedida = file;

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.fotoPreviewEntregaCedida = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                removeFotoEntregaCedida() {
                    this.fotoPreviewEntregaCedida = null;
                    this.fotoFileEntregaCedida = null;
                    if (this.$refs.fileInputEntregaCedida) {
                        this.$refs.fileInputEntregaCedida.value = '';
                    }
                },

                async firmarYConfirmarEntregaCedida() {
                    if (!this.fotoPreviewEntregaCedida) {
                        toastr.error('Por favor, sube una foto del repuesto cedido entregado');
                        return;
                    }

                    const datosParaEnviar = {
                        articuloEntregaId: this.articuloEntregaId,
                        repuestoEntregaNombre: this.repuestoEntregaNombre,
                        entregaCedidaId: this.entregaCedidaId,
                        observacionesEntregaCedida: this.observacionesEntregaCedida,
                        fotoFileEntregaCedida: this.fotoFileEntregaCedida
                    };

                    this.cerrarModalEntregaCedida();

                    const confirmacionFirma = await Swal.fire({
                        title: '<div class="flex items-center justify-center gap-3 mb-4">' +
                            '<div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">' +
                            '<i class="fas fa-exchange-alt text-purple-600 text-xl"></i>' +
                            '</div>' +
                            '<h3 class="text-xl font-bold text-gray-800">Confirmar Entrega de Repuesto Cedido</h3>' +
                            '</div>',
                        html: `<div class="text-center px-4 py-2">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-5 mb-4 border border-purple-100">
                        <div class="flex flex-col space-y-3">
                            <div class="flex items-center justify-center gap-3">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-500">Firmante</p>
                                    <p class="text-lg font-bold text-gray-800">{{ Auth::user()->name ?? 'Usuario' }}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-center gap-3">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-500">Repuesto Cedido</p>
                                    <p class="text-lg font-bold text-gray-800">${datosParaEnviar.repuestoEntregaNombre}</p>
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
                                <p class="text-sm text-amber-600">Al firmar, confirmas que has recibido el repuesto CEDIDO.</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-500 italic">
                        ¿Confirmas la recepción del repuesto cedido?
                    </div>
                </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#8b5cf6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Confirmar y Firmar Entrega Cedida',
                        cancelButtonText: 'Cancelar'
                    });

                    if (!confirmacionFirma.isConfirmed) {
                        this.abrirModalEntregaCedida();
                        return;
                    }

                    this.isLoadingEntregaCedida = true;

                    try {
                        const formData = new FormData();
                        formData.append('solicitud_id', {{ $solicitud->idsolicitudesordenes }});
                        formData.append('articulo_id', datosParaEnviar.articuloEntregaId);
                        formData.append('entrega_id', datosParaEnviar.entregaCedidaId);
                        formData.append('observaciones', datosParaEnviar
                            .observacionesEntregaCedida);
                        formData.append('firma_confirmada', 1);
                        formData.append('nombre_firmante',
                            '{{ Auth::user()->name ?? 'Usuario' }}');
                        formData.append('fecha_firma', new Date().toLocaleString());

                        if (datosParaEnviar.fotoFileEntregaCedida) {
                            formData.append('foto', datosParaEnviar.fotoFileEntregaCedida);
                        }

                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'));

                        const response = await fetch(
                            `/solicitudrepuesto/{{ $solicitud->idsolicitudesordenes }}/confirmar-entrega-cedida`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });

                        const data = await response.json();

                        if (data.success) {
                            await Swal.fire({
                                title: '¡Éxito!',
                                text: data.message ||
                                    'Entrega de repuesto cedido confirmada exitosamente',
                                icon: 'success',
                                confirmButtonColor: '#8b5cf6',
                                confirmButtonText: 'OK',
                                timer: 2000,
                                timerProgressBar: true
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(data.message || 'Error al confirmar la entrega cedida');
                            this.abrirModalEntregaCedida();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error al procesar la solicitud');
                        this.abrirModalEntregaCedida();
                    } finally {
                        this.isLoadingEntregaCedida = false;
                    }
                },

                // ============ MODAL DESTINATARIO (UBICACIÓN) ============
                abrirModalDestinatario(solicitudId, articuloId, nombreRepuesto) {
                    const ubicacionId = this.seleccionesUbicacion[articuloId];

                    if (!ubicacionId) {
                        toastr.error('Seleccione una ubicación para este repuesto');
                        return;
                    }

                    if (!@json($tecnico != null)) {
                        toastr.error('No hay técnico asignado a esta solicitud');
                        return;
                    }

                    this.solicitudIdSeleccionada = solicitudId;
                    this.articuloIdSeleccionado = articuloId;
                    this.repuestoSeleccionadoNombre = nombreRepuesto;
                    this.destinatarioSeleccionado = 'tecnico';
                    this.mostrarModalDestinatario = true;
                },

                cerrarModalDestinatario() {
                    this.mostrarModalDestinatario = false;
                    this.destinatarioSeleccionado = '';
                    this.usuarioSeleccionado = '';
                },

                async confirmarProcesamientoIndividual() {
                    if (!this.destinatarioValido) {
                        toastr.error('No hay técnico asignado para realizar la entrega');
                        return;
                    }

                    const ubicacionId = this.seleccionesUbicacion[this.articuloIdSeleccionado];
                    const nombreTecnico = @json($tecnico ? $tecnico->Nombre . ' ' . $tecnico->apellidoPaterno : 'Técnico');
                    const nombreRepuesto = this.repuestoSeleccionadoNombre;

                    this.procesandoIndividual[this.articuloIdSeleccionado] = true;
                    this.mostrarModalDestinatario = false;

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

                // ============ FUNCIONES PARA ENTREGA FÍSICA NORMAL ============
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
                    console.log('📝 Iniciando firma y confirmación de entrega');
                    console.log('   - Usuario entregador seleccionado:', this
                        .usuarioEntregadorSeleccionado);
                    console.log('   - Nombre entregador:', this.nombreEntregador);
                    console.log('   - Tiene foto:', !!this.fotoPreviewEntrega);

                    if (!this.fotoPreviewEntrega) {
                        toastr.error('Por favor, sube una foto del repuesto entregado');
                        return;
                    }

                    if (!this.usuarioEntregadorSeleccionado) {
                        toastr.error('No se ha seleccionado quién entregará el repuesto');
                        return;
                    }

                    const datosParaEnviar = {
                        articuloEntregaId: this.articuloEntregaId,
                        repuestoEntregaNombre: this.repuestoEntregaNombre,
                        observacionesEntrega: this.observacionesEntrega,
                        fotoFileEntrega: this.fotoFileEntrega,
                        usuarioEntregadorSeleccionado: this.usuarioEntregadorSeleccionado,
                        nombreEntregador: this.nombreEntregador,
                        solicitudId: this.solicitudIdParaEntrega
                    };

                    this.cerrarModalEntrega();

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
                                    <p class="text-sm font-medium text-gray-500">Entregador</p>
                                    <p class="text-lg font-bold text-gray-800">${datosParaEnviar.nombreEntregador}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-center gap-3">
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-500">Repuesto</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        ${datosParaEnviar.repuestoEntregaNombre}
                                    </p>

                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-center gap-3">
                                <div class="w-10 h-10 bg-warning rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-check text-white"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-500">Para</p>
                                    <p class="text-lg font-bold text-gray-800">{{ $tecnico ? $tecnico->Nombre . ' ' . $tecnico->apellidoPaterno : 'Técnico no asignado' }}</p>
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
                                <p class="text-sm text-amber-600">Al firmar, confirmas que has entregado el repuesto.</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-500 italic">
                        ¿Confirmas la entrega del repuesto?
                    </div>
                </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Confirmar y Firmar Entrega',
                        cancelButtonText: 'Cancelar'
                    });

                    if (!confirmacionFirma.isConfirmed) {
                        console.log('❌ Usuario canceló la confirmación');
                        this.mostrarModalEntregaFisica = true;
                        return;
                    }

                    this.isLoadingEntrega = true;

                    try {
                        const formData = new FormData();
                        formData.append('solicitud_id', datosParaEnviar.solicitudId);
                        formData.append('articulo_id', datosParaEnviar.articuloEntregaId);
                        formData.append('observaciones', datosParaEnviar.observacionesEntrega);
                        formData.append('firma_confirmada', 1);
                        formData.append('nombre_firmante', datosParaEnviar.nombreEntregador);

                        // Manejar el usuario_entrego_id correctamente
                        if (datosParaEnviar.usuarioEntregadorSeleccionado === 'yo_mismo') {
                            formData.append('usuario_entrego_id', '{{ Auth::id() ?? 'null' }}');
                        } else {
                            formData.append('usuario_entrego_id', datosParaEnviar
                                .usuarioEntregadorSeleccionado);
                        }

                        formData.append('fecha_firma', new Date().toLocaleString());

                        if (datosParaEnviar.fotoFileEntrega) {
                            formData.append('foto', datosParaEnviar.fotoFileEntrega);
                        }

                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'));

                        console.log('📤 Enviando datos al servidor...');
                        const response = await fetch(
                            `/solicitudrepuesto/${datosParaEnviar.solicitudId}/confirmar-entrega-fisica-con-foto`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });

                        const data = await response.json();

                        if (data.success) {
                            await Swal.fire({
                                title: '¡Éxito!',
                                text: data.message || 'Entrega confirmada exitosamente',
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                confirmButtonText: 'OK',
                                timer: 2000,
                                timerProgressBar: true
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(data.message || 'Error al confirmar la entrega');
                            this.mostrarModalEntregaFisica = true;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error al procesar la solicitud');
                        this.mostrarModalEntregaFisica = true;
                    } finally {
                        this.isLoadingEntrega = false;
                    }
                },

                // ============ FUNCIONES PARA VER CONFIRMACIÓN ============
                async abrirModalVerConfirmacion(solicitudId, articuloId) {
                    try {
                        this.repuestoVerNombre = 'Cargando...';
                        const response = await fetch(
                            `/api/obtener-info-entrega/${solicitudId}/${articuloId}`);

                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success && data.data) {
                            this.entregaInfo = data.data;

                            this.repuestoVerCodigo = data.data.codigo_repuesto ?? '—';
                            this.repuestoVerSubcategoria = data.data.subcategoria ??
                                '—'; // 👈 NUEVO

                            if (data.data.foto_entrega && data.data.tipo_archivo_foto) {
                                this.entregaInfo.foto_url =
                                    `data:${data.data.tipo_archivo_foto};base64,${data.data.foto_entrega}`;
                            }

                            this.mostrarModalVerConfirmacion = true;
                        } else {
                            alert('Error: ' + (data.message || 'No se pudo cargar la información'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al cargar la información de entrega: ' + error.message);
                    }
                },

                cerrarModalVerConfirmacion() {
                    this.mostrarModalVerConfirmacion = false;
                    this.entregaInfo = {};
                    this.repuestoVerNombre = '';
                },

                // ============ FUNCIÓN PARA PROCESAMIENTO GRUPAL ============
                async validarYProcesarGrupal(id) {
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
                                Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>
                `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Procesar todo',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
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
                                ubicaciones: this.seleccionesUbicacion
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
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
