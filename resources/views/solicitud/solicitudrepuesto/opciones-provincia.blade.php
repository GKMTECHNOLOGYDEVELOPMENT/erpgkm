<x-layout.default>
    <!-- CSS en el HEAD -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 - Reemplaza Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div x-data="solicitudRepuestoProvinciaOpciones()" class="min-h-screen py-8">
        <div class="mx-auto w-full px-4">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="text-primary hover:underline">Solicitudes</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Gestión de Repuestos - Provincia</span>
                    </li>
                </ul>
            </div>

            <!-- Header Mejorado - Responsive -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 border border-blue-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 sm:space-x-4 mb-4">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                                <i class="fas fa-truck text-white text-sm sm:text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 truncate">
                                    Gestión de Repuestos - Provincia
                                </h1>
                                <p class="text-gray-600 text-sm sm:text-base lg:text-lg">
                                    Procese repuestos para envío a provincia
                                </p>
                            </div>
                        </div>

                        <!-- Resumen de Progreso - Responsive -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-4 sm:mt-6">
                            <!-- Código de Solicitud -->
                            <div class="flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 bg-blue-50 rounded-xl">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-hashtag text-blue-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 truncate">Código</p>
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                        {{ $solicitud->codigo }}
                                    </p>
                                </div>
                            </div>

                            <!-- Procesados -->
                            <div class="flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 bg-blue-50 rounded-xl">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-blue-600 font-bold text-xs sm:text-sm">
                                        {{ $repuestos_procesados }}/{{ $total_repuestos }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 truncate">Procesados</p>
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                        @if ($repuestos_procesados == $total_repuestos)
                                            Completado
                                        @else
                                            En progreso
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Disponibles -->
                            <div class="flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 bg-green-50 rounded-xl">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-green-600 font-bold text-xs sm:text-sm">
                                        {{ $repuestos_disponibles }}/{{ $total_repuestos }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 truncate">Disponibles</p>
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                        @if ($repuestos_disponibles == $total_repuestos)
                                            Listos
                                        @else
                                            Parcial
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Tipo -->
                            <div class="flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 bg-orange-50 rounded-xl">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-truck-loading text-orange-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 truncate">Tipo</p>
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                        Envío a Provincia
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Solicitante - Responsive -->
                        <div class="mt-4 sm:mt-6">
                            @if ($solicitante)
                                <div
                                    class="flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 bg-purple-50 rounded-xl">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-tie text-purple-600 text-xs sm:text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500 truncate">Solicitante (Provincia)</p>
                                        <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                            {{ $solicitante->Nombre }} {{ $solicitante->apellidoPaterno }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verificar si hay repuestos -->
            @if (!$repuestos || $repuestos->count() == 0)
                <!-- Mensaje cuando no hay repuestos - Responsive -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200 mb-6 sm:mb-8">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-4 sm:px-6 py-4">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-white text-yellow-600 rounded-full flex items-center justify-center font-bold shadow-md flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-sm sm:text-base"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-base sm:text-lg lg:text-xl font-bold text-white truncate">
                                    No hay repuestos
                                </h2>
                                <p class="text-yellow-100 text-xs sm:text-sm truncate">
                                    No se encontraron repuestos en esta solicitud
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 lg:p-8 text-center">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <i class="fas fa-cogs text-yellow-500 text-base sm:text-lg lg:text-2xl"></i>
                        </div>
                        <h3 class="text-base sm:text-lg lg:text-lg font-semibold text-gray-900 mb-2">
                            No se encontraron repuestos
                        </h3>
                        <p class="text-gray-600 text-sm sm:text-base mb-4 sm:mb-6">
                            Esta solicitud no contiene repuestos para gestionar.
                        </p>
                        <a href="{{ route('solicitudrepuesto.index') }}"
                            class="inline-flex items-center px-3 sm:px-4 lg:px-6 py-2 sm:py-2.5 lg:py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-105 text-xs sm:text-sm lg:text-base">
                            <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Volver al Listado</span>
                            <span class="sm:hidden">Volver</span>
                        </a>
                    </div>
                </div>
            @else
                <!-- Panel Principal de Gestión - Responsive -->
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20 mb-6 sm:mb-8">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                                <i class="fas fa-truck-loading text-white text-sm sm:text-base lg:text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">
                                    Gestión de Repuestos - Provincia
                                </h2>
                                <p class="text-white text-xs sm:text-sm lg:text-base truncate">
                                    Prepare repuestos para envío a provincia
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 lg:p-8">
                        <!-- Formulario para selección -->
                        <form id="formUbicaciones" @submit.prevent="procesarSeleccion">
                            <!-- Tabla Mejorada - Responsive con textos completos -->
                            <div class="overflow-x-auto rounded-2xl border border-slate-200/60 shadow-sm mb-6 sm:mb-8">
                                <table class="w-full min-w-[1024px] xl:min-w-full">
                                    <thead class="bg-gradient-to-r from-slate-50 to-indigo-50/30">
                                        <tr>
                                            <th
                                                class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60 whitespace-nowrap">
                                                <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                                                    <i class="fas fa-cog text-slate-500"></i>
                                                    <span>Repuesto</span>
                                                </div>
                                            </th>
                                            <th
                                                class="px-3 sm:px-4 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60 whitespace-nowrap">
                                                <span>Solicitado</span>

                                            </th>
                                            <th
                                                class="px-3 sm:px-4 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60 whitespace-nowrap">
                                                <span>Disponible</span>

                                            </th>
                                            <th
                                                class="px-3 sm:px-4 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60 whitespace-nowrap">
                                                <i class="fas fa-map-marker-alt text-slate-500 mr-1"></i>
                                                <span>Ubicación</span>

                                            </th>
                                            <th
                                                class="px-3 sm:px-4 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60 whitespace-nowrap">
                                                <i class="fas fa-truck text-slate-500 mr-1"></i>
                                                <span>Destino</span>

                                            </th>
                                            <th
                                                class="px-3 sm:px-4 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60 whitespace-nowrap">
                                                <i class="fas fa-tasks text-slate-500 mr-1"></i>
                                                <span>Estado</span>
                                            </th>
                                            <th
                                                class="px-3 sm:px-4 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200/60 whitespace-nowrap">
                                                <i class="fas fa-play-circle text-slate-500 mr-1"></i>
                                                <span class="hidden sm:inline">Acción</span>
                                                <span class="sm:hidden">Acc.</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200/40">
                                        @foreach ($repuestos as $repuesto)
                                            <tr
                                                class="transition-all duration-200 hover:bg-indigo-50/30 @if ($repuesto->ya_procesado) bg-green-50/50 @endif">
                                                <!-- Información del Repuesto -->
                                                <td class="px-4 sm:px-5 lg:px-6 py-4 sm:py-5 lg:py-6 align-middle">
                                                    <div class="flex items-start space-x-2 sm:space-x-3">
                                                        <div
                                                            class="w-8 h-8 sm:w-9 sm:h-9 lg:w-10 lg:h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-cog text-indigo-600 text-sm"></i>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p
                                                                class="font-semibold text-slate-900 text-sm sm:text-base leading-tight">
                                                                {{ $repuesto->nombre }}
                                                            </p>
                                                            <div class="mt-1 space-y-0.5">
                                                                @if ($repuesto->codigo_repuesto || $repuesto->codigo_barras)
                                                                    <p class="text-xs sm:text-sm text-slate-500">
                                                                        {{ $repuesto->codigo_repuesto ?: $repuesto->codigo_barras }}
                                                                    </p>
                                                                @endif
                                                                @if ($repuesto->tipo_repuesto)
                                                                    <p class="text-xs text-slate-400 font-medium">
                                                                        {{ $repuesto->tipo_repuesto }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Cantidad Solicitada -->
                                                <td class="px-3 sm:px-4 py-4 sm:py-5 lg:py-6 align-middle">
                                                    <div class="flex flex-col items-center justify-center h-full">
                                                        <span
                                                            class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-indigo-100 text-indigo-700">
                                                            {{ $repuesto->cantidad_solicitada }}
                                                        </span>
                                                        <span class="text-xs text-slate-500 mt-1 hidden sm:block">
                                                            unidades
                                                        </span>
                                                        <span class="text-xs text-slate-500 mt-0.5 sm:hidden">
                                                            uds
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- Stock Disponible -->
                                                <td class="px-3 sm:px-4 py-4 sm:py-5 lg:py-6 align-middle">
                                                    <div class="flex flex-col items-center justify-center h-full">
                                                        <span
                                                            class="text-base sm:text-lg font-bold @if ($repuesto->suficiente_stock) text-green-600 @else text-rose-600 @endif">
                                                            {{ $repuesto->stock_disponible }}
                                                        </span>
                                                        <span class="text-xs text-slate-500 mt-0.5">
                                                            disponibles
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- Selección de Ubicación -->
                                                <td class="px-3 sm:px-4 py-4 sm:py-5 lg:py-6 align-middle">
                                                    <div
                                                        class="flex items-center justify-center min-w-[140px] sm:min-w-[160px] h-full">
                                                        @if ($repuesto->ya_procesado)
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-green-100 text-green-700">
                                                                    <i class="fas fa-check-circle mr-1 sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Procesado</span>
                                                                    <span class="sm:hidden">OK</span>
                                                                </span>
                                                            </div>
                                                        @elseif(isset($repuesto->ubicaciones_detalle) && count($repuesto->ubicaciones_detalle) > 0)
                                                            <select name="ubicaciones[{{ $repuesto->idArticulos }}]"
                                                                class="w-full border border-slate-300 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm focus:ring-1 sm:focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/50 backdrop-blur-sm text-center"
                                                                x-model="selecciones[{{ $repuesto->idArticulos }}]"
                                                                :disabled="procesandoIndividual[{{ $repuesto->idArticulos }}]">
                                                                <option value="" class="text-xs sm:text-sm">
                                                                    Seleccione ubicación
                                                                </option>
                                                                @foreach ($repuesto->ubicaciones_detalle as $ubicacion)
                                                                    <option
                                                                        value="{{ $ubicacion->rack_ubicacion_id }}"
                                                                        class="text-xs sm:text-sm">
                                                                        {{ $ubicacion->ubicacion_codigo }}
                                                                        ({{ $ubicacion->stock_ubicacion }} uds)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-rose-100 text-rose-700">
                                                                    <i class="fas fa-times-circle mr-1 sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Sin
                                                                        ubicaciones</span>
                                                                    <span class="sm:hidden">Sin</span>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Destino (Provincia) -->
                                                <td class="px-3 sm:px-4 py-4 sm:py-5 lg:py-6 align-middle">
                                                    <div
                                                        class="flex items-center justify-center min-w-[120px] sm:min-w-[140px] h-full">
                                                        @if ($repuesto->ya_procesado)
                                                            @php
                                                                $envioInfo = DB::table(
                                                                    'repuestos_envios_provincia as re',
                                                                )
                                                                    ->select(
                                                                        're.fecha_entrega_transporte',
                                                                        're.foto_comprobante',
                                                                        're.observaciones',
                                                                        're.transportista',
                                                                        're.placa_vehiculo',
                                                                    )
                                                                    ->where(
                                                                        're.solicitud_id',
                                                                        $solicitud->idsolicitudesordenes,
                                                                    )
                                                                    ->where('re.articulo_id', $repuesto->idArticulos)
                                                                    ->first();
                                                            @endphp

                                                            @if ($envioInfo)
                                                                <div
                                                                    class="flex flex-col items-center justify-center text-center">
                                                                    <i
                                                                        class="fas fa-truck text-indigo-600 text-base sm:text-lg mb-1 sm:mb-2"></i>
                                                                    <div>
                                                                        <p
                                                                            class="font-semibold text-slate-900 text-xs sm:text-sm">
                                                                            Enviado
                                                                        </p>
                                                                        @if ($envioInfo->transportista)
                                                                            <p
                                                                                class="text-xs text-slate-500 mt-0.5 truncate max-w-[110px] sm:max-w-none">
                                                                                {{ $envioInfo->transportista }}
                                                                            </p>
                                                                        @endif
                                                                        @if ($envioInfo->fecha_entrega_transporte)
                                                                            <p class="text-xs text-slate-400 mt-0.5">
                                                                                {{ date('d/m/Y', strtotime($envioInfo->fecha_entrega_transporte)) }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="text-center">
                                                                    <span
                                                                        class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-indigo-100 text-indigo-700">
                                                                        <i class="fas fa-truck mr-1 sm:mr-1.5"></i>
                                                                        <span class="hidden sm:inline">Para
                                                                            Envío</span>
                                                                        <span class="sm:hidden">Envío</span>
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-amber-100 text-amber-700">
                                                                    <i class="fas fa-clock mr-1 sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Pendiente</span>
                                                                    <span class="sm:hidden">Pend.</span>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Estado -->
                                                <td class="px-3 sm:px-4 py-4 sm:py-5 lg:py-6 align-middle">
                                                    <div class="flex items-center justify-center h-full">
                                                        @if ($repuesto->ya_procesado)
                                                            <span
                                                                class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-success text-white border border-green-200 shadow-sm">
                                                                <i class="fas fa-check-circle mr-1 sm:mr-1.5"></i>
                                                                <span class="hidden sm:inline">Listo</span>
                                                                <span class="sm:hidden">OK</span>
                                                            </span>
                                                        @elseif($repuesto->suficiente_stock)
                                                            <span
                                                                class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-warning text-white border border-amber-200 shadow-sm">
                                                                <i class="fas fa-clock mr-1 sm:mr-1.5"></i>
                                                                <span class="hidden sm:inline">Pendiente</span>
                                                                <span class="sm:hidden">Pend.</span>
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-danger text-white border border-red-200 shadow-sm">
                                                                <i class="fas fa-times-circle mr-1 sm:mr-1.5"></i>
                                                                <span class="hidden sm:inline">Insuficiente</span>
                                                                <span class="sm:hidden">Ins.</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Acción -->
                                                <td class="px-3 sm:px-4 py-4 sm:py-5 lg:py-6 align-middle">
                                                    <div
                                                        class="flex items-center justify-center min-w-[100px] sm:min-w-[120px] h-full">
                                                        @if ($repuesto->ya_procesado)
                                                            <div class="text-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold bg-green-100 text-green-700">
                                                                    <i class="fas fa-check-circle mr-1 sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Listo</span>
                                                                    <span class="sm:hidden">✓</span>
                                                                </span>
                                                            </div>
                                                        @elseif($repuesto->suficiente_stock)
                                                            @if (\App\Helpers\PermisoHelper::tienePermiso('PROCESAR REPUESTO INDIVIDUAL'))
                                                                <button type="button"
                                                                    @click="abrirModalEnvioProvincia({{ $solicitud->idsolicitudesordenes }}, {{ $repuesto->idArticulos }}, '{{ $repuesto->nombre }}')"
                                                                    :disabled="!selecciones[{{ $repuesto->idArticulos }}] ||
                                                                        procesandoIndividual[
                                                                            {{ $repuesto->idArticulos }}]"
                                                                    :class="{
                                                                        'opacity-50 cursor-not-allowed': !selecciones[
                                                                                {{ $repuesto->idArticulos }}] ||
                                                                            procesandoIndividual[
                                                                                {{ $repuesto->idArticulos }}],
                                                                        'bg-primary': selecciones[
                                                                                {{ $repuesto->idArticulos }}] && !
                                                                            procesandoIndividual[
                                                                                {{ $repuesto->idArticulos }}]
                                                                    }"
                                                                    class="bg-info disabled:bg-slate-400 text-white px-3 py-1.5 sm:px-4 sm:py-2 lg:px-5 lg:py-2.5 rounded-lg font-medium transition-all duration-300 flex items-center justify-center shadow-sm hover:shadow-md text-xs sm:text-sm">
                                                                    <span
                                                                        x-show="!procesandoIndividual[{{ $repuesto->idArticulos }}]"
                                                                        class="flex items-center">
                                                                        <i
                                                                            class="fas fa-truck mr-1 sm:mr-1.5 lg:mr-2 text-xs sm:text-sm"></i>
                                                                        <span class="hidden sm:inline">Preparar</span>
                                                                        <span class="sm:hidden">Prep.</span>
                                                                    </span>
                                                                    <span
                                                                        x-show="procesandoIndividual[{{ $repuesto->idArticulos }}]"
                                                                        class="flex items-center">
                                                                        <i
                                                                            class="fas fa-spinner fa-spin mr-1 sm:mr-1.5 text-xs sm:text-sm"></i>
                                                                        <span
                                                                            class="hidden sm:inline">Procesando</span>
                                                                        <span class="sm:hidden">Proc.</span>
                                                                    </span>
                                                                </button>
                                                            @endif
                                                        @else
                                                            <button disabled
                                                                class="px-3 py-1.5 sm:px-4 sm:py-2 lg:px-6 lg:py-3 bg-gray-300 text-gray-600 rounded-lg font-semibold cursor-not-allowed border border-gray-300 text-xs sm:text-sm">
                                                                <i class="fas fa-ban mr-1 sm:mr-1.5 lg:mr-2"></i>
                                                                <span class="hidden sm:inline">Sin Stock</span>
                                                                <span class="sm:hidden">Sin</span>
                                                            </button>
                                                        @endif
                                                    </div>
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
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                                <i class="fas fa-rocket text-white text-sm sm:text-base lg:text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">
                                    Estrategias de Procesamiento - Provincia
                                </h2>
                                <p class="text-slate-300 text-xs sm:text-sm lg:text-base truncate">
                                    Prepare repuestos para envío a provincia
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-10">
                            <!-- Procesamiento Individual para Provincia - Responsive -->
                            <div
                                class="group bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-indigo-200 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start gap-2 sm:gap-3 mb-3 sm:mb-4 lg:mb-6">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                                        <i class="fas fa-truck text-indigo-600 text-sm sm:text-base lg:text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3
                                            class="text-base sm:text-lg lg:text-xl font-bold text-slate-800 tracking-tight mb-1">
                                            Procesamiento Individual
                                        </h3>
                                        <p class="text-slate-600 text-xs sm:text-sm mt-1 truncate">
                                            Prepare cada repuesto individualmente para envío a provincia.
                                        </p>
                                    </div>
                                </div>

                                <ul class="space-y-1.5 sm:space-y-2 mb-3 sm:mb-4 lg:mb-6">
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i
                                            class="fas fa-check-circle text-indigo-500 mt-0.5 mr-1.5 sm:mr-2 flex-shrink-0"></i>
                                        <span>Seleccione ubicación específica para cada repuesto.</span>
                                    </li>
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i
                                            class="fas fa-check-circle text-indigo-500 mt-0.5 mr-1.5 sm:mr-2 flex-shrink-0"></i>
                                        <span>Registre foto del comprobante de entrega al transporte.</span>
                                    </li>
                                    <li class="flex items-start text-xs sm:text-sm text-slate-700">
                                        <i
                                            class="fas fa-check-circle text-indigo-500 mt-0.5 mr-1.5 sm:mr-2 flex-shrink-0"></i>
                                        <span>Ideal para envíos parciales o selectivos.</span>
                                    </li>
                                </ul>

                                <div
                                    class="bg-indigo-50 rounded-lg sm:rounded-xl p-2 sm:p-3 lg:p-4 border border-indigo-100 text-center">
                                    <p class="text-xs sm:text-sm font-semibold text-indigo-700">
                                        <i class="fas fa-chart-line mr-1 sm:mr-2"></i>
                                        Progreso: <span class="font-bold">{{ $repuestos_procesados }}</span> de
                                        <span class="font-bold">{{ $total_repuestos }}</span>
                                        <span class="hidden sm:inline"> repuestos</span>
                                        <span class="sm:hidden"> reps</span>
                                    </p>
                                </div>
                            </div>

                            
                        </div>

                        <!-- Barra de Estado Mejorada - Responsive -->
                        <div
                            class="mt-4 sm:mt-6 lg:mt-8 p-3 sm:p-4 lg:p-6 bg-gradient-to-r from-indigo-50 to-slate-50 rounded-xl sm:rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
                                <!-- Estado -->
                                <div class="flex items-start gap-2 sm:gap-3">
                                    <div
                                        class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 lg:w-11 lg:h-11 bg-indigo-100 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-info-circle text-indigo-600 text-xs sm:text-sm"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        @if ($repuestos_procesados == $total_repuestos)
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-check-circle text-green-600 text-xs sm:text-sm"></i>
                                                <p class="text-xs sm:text-sm font-semibold text-green-600 truncate">
                                                    <span class="hidden sm:inline">Todo listo para envío a
                                                        provincia</span>
                                                    <span class="sm:hidden">Listo para envío</span>
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex items-center flex-wrap gap-1">
                                                <i class="fas fa-chart-bar text-indigo-600 text-xs sm:text-sm"></i>
                                                <p class="text-xs sm:text-sm font-semibold text-indigo-600">
                                                    <span class="hidden sm:inline">Progreso:</span>
                                                    <span class="text-slate-700">{{ $repuestos_procesados }}</span>
                                                    <span class="text-slate-500">de</span>
                                                    <span class="text-slate-700">{{ $total_repuestos }}</span>
                                                    <span class="hidden sm:inline">reps</span>
                                                    <span class="sm:hidden">r</span>
                                                </p>
                                            </div>
                                        @endif

                                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1 truncate">
                                            <i class="fas fa-clock text-xs"></i>
                                            <span class="hidden sm:inline">Última actualización:</span>
                                            <span class="font-medium text-slate-600 text-xs">
                                                {{ now()->format('d/m/Y H:i') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Botones en fila horizontal -->
                                <div class="flex flex-row items-center gap-2 sm:gap-3 mt-3 lg:mt-0">
                                 

                                    <!-- Botón Volver -->
                                    <a href="{{ route('solicitudarticulo.index') }}"
                                        class="inline-flex items-center justify-center px-2 sm:px-3 lg:px-5 py-1.5 sm:py-2 lg:py-2.5 bg-dark text-white rounded-lg font-medium shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs sm:text-sm whitespace-nowrap">
                                        <i class="fas fa-arrow-left mr-1 sm:mr-1.5"></i>
                                        <span class="hidden sm:inline">Volver</span>
                                        <span class="sm:hidden">←</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal Envío a Provincia -->
        <div x-show="mostrarModalEnvio" x-cloak x-transition
            class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto">
            <!-- Backdrop -->
            <div class="flex items-start justify-center min-h-screen px-4 py-6 sm:p-6" @click.self="cerrarModalEnvio">
                <!-- Modal -->
                <div x-transition x-transition.duration.300
                    class="bg-white rounded-xl shadow-2xl w-full max-w-md sm:max-w-lg my-auto overflow-hidden transform transition-all">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 sm:px-6 py-4 sm:py-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-truck text-white text-lg"></i>
                                </div>
                                <div class="max-w-[calc(100%-60px)]">
                                    <h3 class="text-base sm:text-lg font-bold text-white">
                                        Registrar Envío a Provincia
                                    </h3>
                                    <p class="text-indigo-100 text-xs sm:text-sm truncate mt-0.5"
                                        x-text="repuestoSeleccionadoNombre"></p>
                                </div>
                            </div>

                            <button type="button" @click="cerrarModalEnvio"
                                class="text-white/80 hover:text-white hover:bg-white/10 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <form @submit.prevent="confirmarEnvioIndividual">
                        <div class="p-5 sm:p-6 space-y-5 sm:space-y-6">
                            <!-- Transportista -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-user text-indigo-500 text-xs"></i>
                                        Transportista
                                    </span>
                                    <span class="text-xs font-normal text-gray-500 block mt-0.5">Nombre del
                                        transportista responsable</span>
                                </label>
                                <input type="text" x-model="datosEnvio.transportista" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all placeholder-gray-400"
                                    placeholder="Ej: Transportes Lima SAC">
                            </div>

                            <!-- Placa -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-car text-indigo-500 text-xs"></i>
                                        Placa del Vehículo
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" x-model="datosEnvio.placa_vehiculo" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm uppercase focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all placeholder-gray-400"
                                        placeholder="Ej: ABC-123">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-calendar-alt text-indigo-500 text-xs"></i>
                                        Fecha de Entrega
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="datetime-local" x-model="datosEnvio.fecha_entrega_transporte"
                                        required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                                </div>
                            </div>

                            <!-- Foto -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-camera text-indigo-500 text-xs"></i>
                                        Comprobante (opcional)
                                    </span>
                                    <span class="text-xs font-normal text-gray-500 block mt-0.5">Suba una foto del
                                        comprobante</span>
                                </label>

                                <!-- Input de archivo con mejor diseño -->
                                <div class="relative">
                                    <input type="file" id="fotoComprobante" accept="image/*"
                                        @change="previsualizarFoto" class="hidden">
                                    <label for="fotoComprobante"
                                        class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-400 hover:bg-indigo-50/50 transition-all cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-xl mb-2"></i>
                                        <span class="text-sm font-medium text-gray-600">Subir imagen</span>
                                        <span class="text-xs text-gray-500 mt-1">PNG, JPG o JPEG (Max. 5MB)</span>
                                    </label>
                                </div>

                                <!-- Previsualización -->
                                <div x-show="previsualizacionFoto" class="space-y-2 animate-fade-in">
                                    <div class="relative inline-block">
                                        <img :src="previsualizacionFoto"
                                            class="max-w-full h-40 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                        <button type="button" @click="eliminarPrevisualizacion"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-sm">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-edit text-indigo-500 text-xs"></i>
                                        Observaciones
                                    </span>
                                    <span class="text-xs font-normal text-gray-500 block mt-0.5">Información adicional
                                        del envío</span>
                                </label>
                                <textarea rows="3" x-model="datosEnvio.observaciones"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all placeholder-gray-400 resize-none"
                                    placeholder="Ingrese observaciones importantes..."></textarea>
                            </div>

                            <!-- Botones -->
                            <div class="flex gap-3 pt-4 border-t border-gray-100">
                                <button type="button" @click="cerrarModalEnvio"
                                    class="flex-1 border border-gray-300 rounded-lg py-3 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all">
                                    Cancelar
                                </button>

                                <button type="submit" :disabled="!datosEnvioValidos"
                                    :class="datosEnvioValidos
                                        ?
                                        'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-sm hover:shadow' :
                                        'bg-gray-300 cursor-not-allowed'"
                                    class="flex-1 text-white rounded-lg py-3 px-4 text-sm font-medium transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i>
                                    Confirmar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- MODAL ENVÍO GRUPAL MEJORADO -->
        <div x-show="mostrarModalEnvioGrupal" x-cloak class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4" @click.self="cerrarModalEnvioGrupal">
                <div x-show="mostrarModalEnvioGrupal" x-transition x-transition.duration.300
                    class="relative w-full max-w-lg rounded-2xl overflow-hidden bg-white shadow-2xl transform transition-all">

                    <!-- HEADER MEJORADO -->
                    <div class="bg-primary px-6 py-5 relative">
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-truck-loading text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-xl">
                                        Envío Grupal a Provincia
                                    </h3>
                                </div>
                            </div>

                            <button type="button"
                                class="text-white/80 hover:text-white hover:bg-white/10 w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                                @click="cerrarModalEnvioGrupal">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- CONTENIDO PRINCIPAL -->
                    <form id="formEnvioProvinciaGrupal" @submit.prevent="confirmarEnvioGrupal">
                        <div class="p-6 space-y-5">

                            <!-- Encabezado de formulario -->
                            <div class="mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-6 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full">
                                    </div>
                                    <h4 class="font-semibold text-gray-800">Detalles del Envío</h4>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 ml-4">Complete los datos de transporte y logística
                                </p>
                            </div>

                            <!-- Transportista -->
                            <div class="space-y-1.5">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <div
                                        class="w-5 h-5 bg-green-100 text-green-600 rounded-md flex items-center justify-center">
                                        <i class="fas fa-user-tie text-xs"></i>
                                    </div>
                                    Transportista
                                </label>
                                <input type="text" x-model="datosEnvioGrupal.transportista"
                                    placeholder="Nombre del transportista"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all hover:border-gray-400">
                                <p class="text-xs text-gray-500 mt-1 ml-7">Persona o empresa responsable del transporte
                                </p>
                            </div>

                            <!-- Placa -->
                            <div class="space-y-1.5">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <div
                                        class="w-5 h-5 bg-green-100 text-green-600 rounded-md flex items-center justify-center">
                                        <i class="fas fa-car text-xs"></i>
                                    </div>
                                    Placa del vehículo
                                </label>
                                <input type="text" x-model="datosEnvioGrupal.placa_vehiculo"
                                    placeholder="Ej: ABC-123"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all hover:border-gray-400 uppercase">
                            </div>

                            <!-- Fecha -->
                            <div class="space-y-1.5">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <div
                                        class="w-5 h-5 bg-green-100 text-green-600 rounded-md flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-xs"></i>
                                    </div>
                                    Fecha de entrega estimada
                                </label>
                                <input type="datetime-local" x-model="datosEnvioGrupal.fecha_entrega_transporte"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all hover:border-gray-400">
                            </div>

                            <!-- Galería de Fotos - RESPONSIVA -->
                            <div class="space-y-2 sm:space-y-2.5">
                                <label
                                    class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-medium text-gray-700">
                                    <div
                                        class="w-4 h-4 sm:w-5 sm:h-5 bg-green-100 text-green-600 rounded flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-images text-[10px] sm:text-xs"></i>
                                    </div>
                                    Fotos de comprobante
                                    <span class="text-xs font-normal text-gray-500">(opcional)</span>
                                </label>

                                <!-- Contador de fotos -->
                                <div x-show="fotosGrupal.length > 0"
                                    class="text-xs sm:text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                                    <i class="fas fa-camera mr-1.5"></i>
                                    <span x-text="fotosGrupal.length + ' foto(s) seleccionada(s)'"></span>
                                </div>

                                <!-- Área de subida -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg sm:rounded-xl p-3 sm:p-4 text-center hover:border-green-400 hover:bg-green-50 transition-colors"
                                    @dragover.prevent="handleDragOverGrupal"
                                    @dragleave.prevent="handleDragLeaveGrupal" @drop.prevent="handleDropGrupal">
                                    <input type="file" id="fotoComprobanteGrupal" accept="image/*" multiple
                                        @change="previsualizarFotoGrupal" class="hidden">
                                    <template x-if="fotosGrupal.length === 0">
                                        <div>
                                            <i
                                                class="fas fa-cloud-upload-alt text-2xl sm:text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Arrastra fotos o haz clic
                                                para subir</p>
                                            <p class="text-xs text-gray-500">Puedes seleccionar múltiples imágenes</p>
                                            <button type="button"
                                                class="mt-3 px-3 sm:px-4 py-1.5 sm:py-2 bg-green-50 text-green-600 rounded-lg text-xs sm:text-sm font-medium hover:bg-green-100 transition-colors"
                                                onclick="document.getElementById('fotoComprobanteGrupal').click()">
                                                <i class="fas fa-upload mr-1.5"></i> Seleccionar fotos
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Grid de previsualización RESPONSIVA -->
                                    <div x-show="fotosGrupal.length > 0" class="space-y-3 sm:space-y-4">
                                        <!-- Controles para múltiples imágenes -->
                                        <div class="flex flex-wrap gap-2 sm:gap-3">
                                            <button type="button"
                                                class="px-2.5 sm:px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors"
                                                onclick="document.getElementById('fotoComprobanteGrupal').click()">
                                                <i class="fas fa-plus mr-1"></i> Agregar más
                                            </button>
                                            <button type="button"
                                                class="px-2.5 sm:px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition-colors"
                                                @click="eliminarTodasLasFotosGrupal">
                                                <i class="fas fa-trash-alt mr-1"></i> Eliminar todas
                                            </button>
                                        </div>

                                        <!-- Grid de imágenes -->
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                                            <template x-for="(foto, index) in fotosGrupal" :key="index">
                                                <div class="relative group">
                                                    <img :src="foto.preview"
                                                        class="w-full h-20 sm:h-24 object-cover rounded-lg border hover:border-green-400 transition-colors cursor-pointer"
                                                        @click="ampliarFotoGrupal(index)">
                                                    <button type="button"
                                                        class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                                                        @click="eliminarFotoGrupal(index)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <div class="text-[10px] sm:text-xs text-gray-500 truncate mt-1 px-1"
                                                        x-text="foto.name || `Foto ${index + 1}`"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="space-y-1.5">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <div
                                        class="w-5 h-5 bg-green-100 text-green-600 rounded-md flex items-center justify-center">
                                        <i class="fas fa-comment-alt text-xs"></i>
                                    </div>
                                    Observaciones adicionales
                                </label>
                                <textarea rows="3" x-model="datosEnvioGrupal.observaciones"
                                    placeholder="Notas, instrucciones especiales, o detalles relevantes para el envío..."
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all hover:border-gray-400 resize-none"></textarea>
                            </div>

                        </div>

                        <!-- FOOTER MEJORADO -->
                        <div class="px-6 py-4 border-t bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1 text-green-600"></i>
                                    Todos los repuestos seleccionados serán procesados juntos
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="button"
                                        class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm min-w-[100px]"
                                        @click="cerrarModalEnvioGrupal">
                                        Cancelar
                                    </button>

                                    <button type="submit" :disabled="!datosEnvioGrupalValidos || isLoadingGrupal"
                                        class="px-5 py-2.5 rounded-xl text-white font-medium transition-all text-sm min-w-[120px] relative overflow-hidden group"
                                        :class="datosEnvioGrupalValidos && !isLoadingGrupal ?
                                            'bg-success ' :
                                            'bg-gray-400'">

                                        <!-- Efecto de brillo al pasar el cursor -->
                                        <div class="absolute inset-0 bg-gray-700 transition-transform duration-700">
                                        </div>

                                        <!-- Contenido del botón -->
                                        <div class="relative flex items-center justify-center gap-2">
                                            <i class="fas"
                                                :class="isLoadingGrupal ? 'fa-spinner fa-spin' : 'fa-truck-loading'"></i>
                                            <span
                                                x-text="isLoadingGrupal ? 'Procesando...' : 'Confirmar Envío'"></span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <!-- Script principal con Alpine.js -->
    <script>
        // Función para mostrar mensajes (reemplaza toastr)
        function showAlert(type, message) {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                switch(type) {
                    case 'success':
                        Toast.fire({
                            icon: 'success',
                            title: message
                        });
                        break;
                    case 'error':
                        Toast.fire({
                            icon: 'error',
                            title: message
                        });
                        break;
                    case 'info':
                        Toast.fire({
                            icon: 'info',
                            title: message
                        });
                        break;
                    case 'warning':
                        Toast.fire({
                            icon: 'warning',
                            title: message
                        });
                        break;
                }
            } else {
                // Fallback si SweetAlert2 no está cargado
                alert(message);
            }
        }

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
                fotosGrupal: [],
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
                        showAlert('error', 'Seleccione una ubicación para este repuesto');
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
                    // Verificar si ya están todos procesados
                    if (@json($repuestos_procesados) == @json($total_repuestos)) {
                        showAlert('info', 'Todos los repuestos ya fueron procesados');
                        return;
                    }

                    if (!this.todasUbicacionesSeleccionadas) {
                        showAlert('error', 'Debe seleccionar una ubicación para todos los repuestos disponibles');
                        return;
                    }

                    if (!this.todosDisponibles) {
                        showAlert('error', 'No todos los repuestos tienen stock suficiente para procesamiento grupal');
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
                    this.fotosGrupal = [];
                    this.previsualizacionFotoGrupal = null;

                    this.mostrarModalEnvioGrupal = true;
                },

                cerrarModalEnvioGrupal() {
                    this.mostrarModalEnvioGrupal = false;
                    this.fotosGrupal = [];
                    this.previsualizacionFotoGrupal = null;
                },

                // MÉTODO NUEVO: Manejar subida múltiple de fotos
                previsualizarFotoGrupal(event) {
                    const files = Array.from(event.target.files);

                    // Verificar límite de archivos
                    if (this.fotosGrupal.length + files.length > 20) {
                        showAlert('warning', 'Máximo 20 fotos permitidas');
                        return;
                    }

                    // Procesar cada archivo
                    files.forEach(file => {
                        // Validar tipo de archivo
                        if (!file.type.startsWith('image/')) {
                            showAlert('error', `El archivo "${file.name}" no es una imagen válida`);
                            return;
                        }

                        // Validar tamaño (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            showAlert('error', `La imagen "${file.name}" excede los 5MB permitidos`);
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.fotosGrupal.push({
                                file: file,
                                preview: e.target.result,
                                name: file.name,
                                size: file.size,
                                type: file.type
                            });
                        };
                        reader.readAsDataURL(file);
                    });

                    // Resetear input para permitir subir las mismas imágenes
                    event.target.value = '';
                },

                // MÉTODO NUEVO: Eliminar foto específica
                eliminarFotoGrupal(index) {
                    this.fotosGrupal.splice(index, 1);
                    showAlert('info', 'Foto eliminada');
                },

                // MÉTODO NUEVO: Eliminar todas las fotos
                eliminarTodasLasFotosGrupal() {
                    if (this.fotosGrupal.length > 0) {
                        if (confirm(`¿Eliminar todas las ${this.fotosGrupal.length} fotos?`)) {
                            this.fotosGrupal = [];
                            document.getElementById('fotoComprobanteGrupal').value = '';
                            showAlert('info', 'Todas las fotos han sido eliminadas');
                        }
                    }
                },

                // Procesamiento individual
                async confirmarEnvioIndividual() {
                    if (!this.datosEnvioValidos) {
                        showAlert('error', 'Complete todos los campos requeridos');
                        return;
                    }

                    const ubicacionId = this.selecciones[this.articuloIdSeleccionado];
                    const fotoComprobante = document.getElementById('fotoComprobante').files[0];

                    try {
                        const result = await Swal.fire({
                            title: '¿Confirmar envío a provincia?',
                            html: `
                                <div class="text-left">
                                    <p><strong>Repuesto:</strong> ${this.repuestoSeleccionadoNombre}</p>
                                    <p><strong>Transportista:</strong> ${this.datosEnvio.transportista}</p>
                                    <p><strong>Vehículo:</strong> ${this.datosEnvio.placa_vehiculo}</p>
                                </div>
                            `,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, confirmar',
                            cancelButtonText: 'Cancelar'
                        });

                        if (!result.isConfirmed) {
                            return;
                        }

                        this.procesandoIndividual[this.articuloIdSeleccionado] = true;

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
                            showAlert('success', data.message);
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
                            showAlert('error', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert('error', 'Error al procesar el envío');
                    } finally {
                        this.procesandoIndividual[this.articuloIdSeleccionado] = false;
                    }
                },

                // Procesamiento grupal
                async confirmarEnvioGrupal() {
                    if (!this.datosEnvioGrupalValidos) {
                        showAlert('error', 'Complete todos los campos requeridos');
                        return;
                    }

                    if (this.fotosGrupal.length > 20) {
                        showAlert('error', 'Máximo 20 fotos permitidas');
                        return;
                    }

                    try {
                        const result = await Swal.fire({
                            title: '¿Confirmar envío grupal a provincia?',
                            html: `
                                <div class="text-left">
                                    <p><strong>Transportista:</strong> ${this.datosEnvioGrupal.transportista}</p>
                                    <p><strong>Vehículo:</strong> ${this.datosEnvioGrupal.placa_vehiculo}</p>
                                    <p><strong>Fotos adjuntas:</strong> ${this.fotosGrupal.length}</p>
                                    <p class="text-sm text-gray-600 mt-2">Todos los repuestos serán procesados juntos.</p>
                                </div>
                            `,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, confirmar',
                            cancelButtonText: 'Cancelar'
                        });

                        if (!result.isConfirmed) {
                            return;
                        }

                        this.isLoadingGrupal = true;

                        const formData = new FormData();
                        formData.append('ubicaciones', JSON.stringify(this.selecciones));
                        formData.append('transportista', this.datosEnvioGrupal.transportista);
                        formData.append('placa_vehiculo', this.datosEnvioGrupal.placa_vehiculo);
                        formData.append('fecha_entrega_transporte', this.datosEnvioGrupal.fecha_entrega_transporte);
                        formData.append('observaciones', this.datosEnvioGrupal.observaciones);

                        // Agregar múltiples fotos
                        this.fotosGrupal.forEach((foto, index) => {
                            formData.append(`fotos_comprobante[]`, foto.file);
                        });

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
                            showAlert('success', data.message);
                            this.cerrarModalEnvioGrupal();

                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showAlert('error', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert('error', 'Error al procesar el envío grupal');
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