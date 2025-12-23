<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<x-layout.default>
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Notificación para AJAX -->
        <div id="ajax-notification"
            class="hidden rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="ajax-message" class="font-medium"></span>
            <button class="ml-auto text-emerald-600 hover:text-emerald-800" onclick="hideNotification()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @if (session('ok'))
            <div
                class="rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 flex items-center gap-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-medium">{{ session('ok') }}</span>
                <button class="ml-auto text-emerald-600 hover:text-emerald-800"
                    onclick="this.parentElement.style.display='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3 shadow-sm">
                <div class="font-medium mb-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Corrige los siguientes campos:
                </div>
                <ul class="list-disc list-inside text-sm space-y-0.5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $estado = $custodia->estado;
            $badge = match ($estado) {
                'Pendiente' => 'bg-warning text-white',
                'En revisión' => 'bg-secondary text-white',
                'Aprobado' => 'bg-success text-white',
                default => 'bg-gray-100 text-white',
            };
            // Determinar si el formulario debe estar deshabilitado
            $isDisabled = $custodia->estado === 'Aprobado';
        @endphp

        <!-- Header -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div>
                <!-- Título -->
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Editar Custodia</span>
                    @if ($isDisabled)
                        <span class="text-sm text-gray-500">(Solo lectura - Estado Aprobado)</span>
                    @endif
                </h1>

                <!-- Info custodia -->
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700 mt-1">
                    <div class="flex items-center gap-1 bg-primary-light px-3 py-1.5 rounded-md">
                        <span class="text-xs uppercase tracking-wide">Código:</span>
                        <span>#{{ $custodia->codigocustodias }}</span>
                    </div>

                    <span class="text-gray-400">-</span>

                    <div class="flex items-center gap-1 bg-success-light px-3 py-1.5 rounded-md">
                        <span class="text-xs uppercase tracking-wide">Cliente:</span>
                        <span>{{ $custodia->cliente->nombre ?? 'Cliente N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span id="estado-badge"
                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                    {{ $estado }}
                </span>
            </div>
        </div>

        <!-- Summary card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Equipo -->
            <div
                class="group relative bg-primary p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Efecto de brillo animado -->
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                </div>

                <div class="relative flex items-start gap-4">
                    <div
                        class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-white mb-1">Equipo</div>
                        <div class="text-lg font-bold text-white truncate">
                            {{ $custodia->marca->nombre ?? '—' }}
                            {{ $custodia->modelo->nombre ?? '' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Serie -->
            <div
                class="group relative bg-info p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Efecto de brillo animado -->
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                </div>

                <div class="relative flex items-start gap-4">
                    <div
                        class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-blue-100 mb-1">Serie</div>
                        <div class="text-lg font-bold text-white truncate">{{ $custodia->serie ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Fecha de Ingreso -->
            <div
                class="group relative bg-secondary p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Efecto de brillo animado -->
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                </div>

                <div class="relative flex items-start gap-4">
                    <div
                        class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-white mb-1">Ingreso a custodia</div>
                        <div class="text-lg font-bold text-white">
                            {{ \Carbon\Carbon::parse($custodia->fecha_ingreso_custodia)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form card -->
        <div class="panel rounded-xl shadow-sm overflow-hidden">
            <div class="bg-dark-light px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Información de la custodia
                </h2>
            </div>

            <form id="custodia-form" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Estado -->
                    <div>
                        <label for="estado"
                            class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Estado
                        </label>
                        <div class="relative">
                            <select id="estado" name="estado"
                                class="w-full appearance-none border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $isDisabled ? 'disabled' : '' }}>
                                @foreach (['Pendiente', 'En revisión', 'Aprobado'] as $op)
                                    <option value="{{ $op }}" @selected(old('estado', $custodia->estado) === $op)>
                                        {{ $op }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($isDisabled)
                                <input type="hidden" name="estado" value="Aprobado">
                            @endif
                        </div>
                    </div>

                    <!-- Ubicación actual -->
                    <div>
                        <label for="ubicacion_actual"
                            class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Ubicación Recepción
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <input type="text" id="ubicacion_actual" name="ubicacion_actual" maxlength="100"
                                value="{{ old('ubicacion_actual', $custodia->ubicacion_actual) }}"
                                class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                placeholder="Ej. Laboratorio o Zona TCL" {{ $isDisabled ? 'readonly' : '' }}>
                        </div>
                        <div id="error-ubicacion" class="mt-1 text-sm text-red-600 hidden flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span id="error-ubicacion-text"></span>
                        </div>
                    </div>
                </div>

                <!-- Campos adicionales para estado Aprobado (inicialmente ocultos) -->
                <div id="campos-aprobado" class="{{ $custodia->estado === 'Aprobado' ? '' : 'hidden' }} space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Ubicación de equipo en almacén
                        </h3>

                        <!-- En la sección de ubicación en almacén -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Select de ubicación en rack -->
                            <div>
                                <label for="rack_ubicacion_id"
                                    class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ubicación en Rack
                                </label>
                                <div class="relative">
                                    <select id="rack_ubicacion_id" name="rack_ubicacion_id"
                                        class="w-full appearance-none border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">Seleccionar ubicación en rack</option>
                                        <!-- Las opciones se cargarán via JavaScript -->
                                    </select>
                                    @if ($isDisabled)
                                        <input type="hidden" name="rack_ubicacion_id" id="hidden_rack_ubicacion_id"
                                            value="{{ old('rack_ubicacion_id', $custodia->rack_ubicacion_id ?? '') }}">
                                    @endif
                                </div>
                                @if (\App\Helpers\PermisoHelper::tienePermiso('CARGAR SUGERENCIAS CUSTODIA'))
                                    <!-- Botón con ID específico -->
                                    <button type="button" id="btn-cargar-sugerencias"
                                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Cargar sugerencias
                                    </button>
                                @endif
                            </div>

                            <!-- Campo para observaciones específicas de almacén -->
                            <div>
                                <label for="observacion_almacen"
                                    class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Observaciones de Almacén
                                </label>
                                <textarea id="observacion_almacen" name="observacion_almacen" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    placeholder="Detalles específicos sobre la ubicación en rack, condición del equipo, etc."
                                    {{ $isDisabled ? 'readonly' : '' }}>{{ old('observacion_almacen', $custodia->observacion_almacen ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Campo oculto para la cantidad (siempre será 1) -->
                        <input type="hidden" name="cantidad" value="1">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @php
                        $fechaIngreso = \Carbon\Carbon::parse($custodia->fecha_ingreso_custodia);

                        // Calcular diferencia en días (mínimo 1)
                        $dias = max(1, $fechaIngreso->diffInDays(\Carbon\Carbon::now()->startOfDay()));

                        $textoTiempo = match (true) {
                            $dias === 1 => 'Hace 1 día',
                            default => "Hace {$dias} días",
                        };
                    @endphp

                    <!-- Tiempo en custodia -->
                    <div id="wrap-fecha">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tiempo en custodia
                        </label>
                        <div class="pl-10 py-2 text-gray-800 font-medium bg-gray-50 rounded-lg border border-gray-200">
                            {{ $textoTiempo }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1 pl-10">Ingreso: {{ $fechaIngreso->format('d/m/Y') }}</p>
                    </div>

                    <!-- Responsable recepción -->
                    <div id="wrap-resp" class="{{ $custodia->estado === 'Devuelto' ? '' : 'opacity-70' }}">
                        <label for="responsable_recepcion"
                            class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Responsable de recepción
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" id="id_responsable_recepcion" name="id_responsable_recepcion"
                                maxlength="100"
                                value="{{ $custodia->responsableRecepcion ? $custodia->responsableRecepcion->Nombre . ' ' . $custodia->responsableRecepcion->apellidoPaterno : '' }}"
                                class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                placeholder="Nombre y apellido" disabled>
                        </div>
                    </div>
                </div>

                <!-- Campos adicionales para estado En revisión -->
                <div id="campos-revision"
                    class="{{ $custodia->estado === 'En revisión' ? '' : 'hidden' }} space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293L18.707 8.707A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                            </svg>
                            Observaciones de Revisión
                        </h3>
                        <textarea id="observaciones" name="observaciones" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            placeholder="Detalles de revisión (pendientes, ajustes, comentarios internos)"
                            {{ $isDisabled ? 'readonly' : '' }}>{{ old('observaciones', $custodia->observaciones ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Sección para subir fotos (solo para estados En revisión y Aprobado) -->
                <div id="campos-fotos"
                    class="{{ in_array($custodia->estado, ['Pendiente', 'En revisión', 'Aprobado']) ? '' : 'hidden' }} space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Gestión de Fotos
                        </h3>

                        <!-- Área para subir fotos -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Subir Fotos
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors duration-200">
                                <input type="file" id="fotos" name="fotos[]" multiple accept="image/*"
                                    class="hidden" {{ $custodia->estado === 'Aprobado' ? 'disabled' : '' }}>
                                <label for="fotos"
                                    class="cursor-pointer {{ $custodia->estado === 'Aprobado' ? 'cursor-not-allowed opacity-50' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium text-indigo-600">Haz clic para subir</span> o arrastra
                                        y suelta
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG, WEBP hasta 5MB cada una</p>
                                    @if ($custodia->estado === 'Aprobado')
                                        <p class="text-xs text-red-500 mt-2">Solo lectura - No se pueden subir nuevas
                                            fotos en estado Aprobado</p>
                                    @endif
                                </label>
                            </div>
                            <div id="preview-fotos" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>

                            <!-- Botón para subir fotos -->
                            @if ($custodia->estado !== 'Aprobado')
                                <div class="mt-4 text-right">
                                    @if (\App\Helpers\PermisoHelper::tienePermiso('SUBIR FOTOS CUSTODIA'))
                                        <button type="button" id="btn-subir-fotos" onclick="subirFotos()"
                                            class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            Subir Fotos
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Galería de fotos existentes -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Fotos existentes</h4>
                            <div id="galeria-fotos" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <!-- Las fotos existentes se cargarán aquí via AJAX -->
                                <div class="text-center text-gray-500 py-8 col-span-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm">Cargando fotos...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky actions -->
                <div
                    class="border-t border-gray-200 -mx-6 px-6 py-4 bg-white/80 backdrop-blur supports-[backdrop-filter]:sticky supports-[backdrop-filter]:bottom-0 mt-8">
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                        <a href="{{ route('solicitudcustodia.index') }}" class="btn btn-danger">
                            Atras
                        </a>
                        @if (!$isDisabled)
                            @if (\App\Helpers\PermisoHelper::tienePermiso('ACTUALIZAR CAMBIOS CUSTODIA'))
                                <button type="button" id="btn-guardar" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Actualizar cambios
                                </button>
                            @endif
                        @else
                            <button type="button" class="btn btn-secondary cursor-not-allowed opacity-70" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Solo lectura
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

 <script>
    // ========== FUNCIONES DE UBICACIÓN ==========
    // Función para cargar sugerencias de ubicaciones
    function cargarSugerenciasUbicaciones() {
        const selectUbicacion = document.getElementById('rack_ubicacion_id');

        // Mostrar loading
        selectUbicacion.innerHTML = '<option value="">Cargando sugerencias...</option>';

        fetch(`/custodia/{{ $custodia->id }}/sugerencias-ubicacion`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Limpiar select
                    selectUbicacion.innerHTML = '<option value="">Seleccionar ubicación en rack</option>';

                    // Agregar opciones
                    data.sugerencias.forEach(sugerencia => {
                        const option = document.createElement('option');
                        option.value = sugerencia.id;
                        option.textContent =
                            `${sugerencia.codigo} - ${sugerencia.rack_nombre} (${sugerencia.sede})`;
                        option.setAttribute('data-capacidad', sugerencia.espacio_disponible);
                        selectUbicacion.appendChild(option);
                    });

                    if (data.sugerencias.length === 0) {
                        selectUbicacion.innerHTML = '<option value="">No hay ubicaciones disponibles</option>';
                    }

                    // ✅ Después de cargar sugerencias, seleccionar la ubicación actual si existe
                    cargarUbicacionActual();

                } else {
                    selectUbicacion.innerHTML = '<option value="">Error cargando sugerencias</option>';
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error cargando sugerencias:', error);
                selectUbicacion.innerHTML = '<option value="">Error de conexión</option>';
            });
    }

    // Función para cargar ubicación actual via AJAX
    function cargarUbicacionActual() {
        const custodiaId = '{{ $custodia->id }}';

        fetch(`/custodia/${custodiaId}/ubicacion-actual`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.ubicacion_actual) {
                    mostrarUbicacionActual(data.ubicacion_actual);
                    seleccionarUbicacionActual(data.ubicacion_actual);
                }
            })
            .catch(error => {
                console.error('Error cargando ubicación actual:', error);
            });
    }

    // Función para mostrar la ubicación actual en la interfaz
    function mostrarUbicacionActual(ubicacion) {
        console.log('📍 Ubicación actual:', ubicacion);

        // Crear o actualizar elemento para mostrar ubicación
        let ubicacionElement = document.getElementById('display-ubicacion-actual');

        if (!ubicacionElement) {
            // Crear elemento si no existe
            ubicacionElement = document.createElement('div');
            ubicacionElement.id = 'display-ubicacion-actual';
            ubicacionElement.className = 'mt-4 p-4 bg-green-50 border border-green-200 rounded-lg';

            // Insertar después del select de ubicación
            const ubicacionSelect = document.getElementById('rack_ubicacion_id');
            ubicacionSelect.parentNode.appendChild(ubicacionElement);
        }

        if (ubicacion) {
            ubicacionElement.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-green-800">Ubicación actual en almacén:</h4>
                        <p class="text-sm text-green-700">
                            <strong>Rack:</strong> ${ubicacion.rack_nombre} | 
                            <strong>Código:</strong> ${ubicacion.codigo} | 
                            <strong>Sede:</strong> ${ubicacion.sede} |
                            <strong>Cantidad:</strong> ${ubicacion.cantidad}
                        </p>
                    </div>
                </div>
            `;
            ubicacionElement.classList.remove('hidden');
        } else {
            ubicacionElement.innerHTML = `
                <div class="text-center text-gray-500">
                    <p>No hay ubicación asignada en almacén</p>
                </div>
            `;
        }
    }

    // Función para actualizar el select con la ubicación actual
    function seleccionarUbicacionActual(ubicacionActual) {
        if (ubicacionActual && ubicacionActual.idRackUbicacion) {
            const select = document.getElementById('rack_ubicacion_id');
            const option = Array.from(select.options).find(opt =>
                parseInt(opt.value) === ubicacionActual.idRackUbicacion
            );

            if (option) {
                select.value = ubicacionActual.idRackUbicacion;
            } else {
                // Si la opción no está en el select, agregarla
                const newOption = new Option(
                    `${ubicacionActual.codigo} - ${ubicacionActual.rack_nombre} (ACTUAL)`,
                    ubicacionActual.idRackUbicacion,
                    true,
                    true
                );
                select.add(newOption);
            }
        }
    }

    // ========== FUNCIONES DE VALIDACIÓN Y ESTADO ==========
    function validarFormulario() {
        const estado = document.getElementById('estado').value;
        const ubicacion = document.getElementById('ubicacion_actual').value;
        const errorUbicacion = document.getElementById('error-ubicacion');
        const errorUbicacionText = document.getElementById('error-ubicacion-text');

        // Limpiar errores anteriores
        errorUbicacion.classList.add('hidden');
        errorUbicacionText.textContent = '';

        // Validar que si el estado es Aprobado, la ubicación no esté vacía
        if (estado === 'Aprobado' && !ubicacion.trim()) {
            errorUbicacionText.textContent = 'La ubicación es requerida cuando el estado es Aprobado';
            errorUbicacion.classList.remove('hidden');
            toastr.error('La ubicación es requerida cuando el estado es Aprobado');
            return false;
        }

        return true;
    }

    function actualizarBadgeEstado(estado) {
        const estadoBadge = document.getElementById('estado-badge');
        let badgeClass = '';

        switch (estado) {
            case 'Pendiente':
                badgeClass = 'bg-warning text-white';
                break;
            case 'En revisión':
                badgeClass = 'bg-secondary text-white';
                break;
            case 'Aprobado':
                badgeClass = 'bg-success text-white';
                break;
            default:
                badgeClass = 'bg-gray-100 text-white';
        }

        estadoBadge.className =
            `inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium ring-1 ring-inset ${badgeClass}`;
        estadoBadge.textContent = estado;
    }

    function toggleCamposPorEstado() {
        const estado = document.getElementById('estado').value;
        const camposAprobado = document.getElementById('campos-aprobado');
        const camposRevision = document.getElementById('campos-revision');
        const camposFotos = document.getElementById('campos-fotos');
        const ubicacionInput = document.getElementById('ubicacion_actual');
        const fileInput = document.getElementById('fotos');
        const btnSubirFotos = document.getElementById('btn-subir-fotos');

        console.log('Estado actual:', estado);

        // Mostrar/ocultar campos de ubicación
        if (estado === 'Aprobado') {
            camposAprobado.classList.remove('hidden');
            camposRevision.classList.add('hidden');
            if (ubicacionInput) {
                ubicacionInput.setAttribute('readonly', true);
                ubicacionInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            }

            // Cargar sugerencias automáticamente cuando el estado es Aprobado
            setTimeout(() => {
                cargarSugerenciasUbicaciones();
            }, 300);
        } else if (estado === 'En revisión') {
            camposRevision.classList.remove('hidden');
            camposAprobado.classList.add('hidden');
            if (ubicacionInput) {
                ubicacionInput.removeAttribute('readonly');
                ubicacionInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        } else {
            camposAprobado.classList.add('hidden');
            camposRevision.classList.add('hidden');
            if (ubicacionInput) {
                ubicacionInput.removeAttribute('readonly');
                ubicacionInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        }

        // MOSTRAR CAMPOS DE FOTOS PARA TODOS LOS ESTADOS
        if (estado === 'Aprobado' || estado === 'En revisión' || estado === 'Pendiente') {
            console.log('Mostrando sección de fotos para estado:', estado);
            camposFotos.classList.remove('hidden');

            // Configurar permisos según el estado
            if (estado === 'Aprobado') {
                if (fileInput) {
                    fileInput.setAttribute('disabled', true);
                    fileInput.parentElement.classList.add('cursor-not-allowed', 'opacity-50');
                }
                if (btnSubirFotos) {
                    btnSubirFotos.style.display = 'none';
                }
            } else {
                if (fileInput) {
                    fileInput.removeAttribute('disabled');
                    fileInput.parentElement.classList.remove('cursor-not-allowed', 'opacity-50');
                }
                if (btnSubirFotos) {
                    btnSubirFotos.style.display = 'block';
                }
            }

            // SIEMPRE cargar galería cuando se muestre la sección
            console.log('Cargando galería de fotos para estado:', estado);
            cargarGaleriaFotos();
        } else {
            console.log('Ocultando sección de fotos para estado:', estado);
            camposFotos.classList.add('hidden');
        }
    }

    // ========== FUNCIONES DE FOTOS ==========
    function setupFilePreview() {
        const fileInput = document.getElementById('fotos');
        const previewContainer = document.getElementById('preview-fotos');

        fileInput.addEventListener('change', function(e) {
            renderPreviews();
        });
    }

    function renderPreviews() {
        const fileInput = document.getElementById('fotos');
        const previewContainer = document.getElementById('preview-fotos');
        const files = Array.from(fileInput.files);

        previewContainer.innerHTML = '';

        if (files.length === 0) {
            previewContainer.innerHTML = `
                <div class="col-span-4 text-center text-gray-500 py-4">
                    <p class="text-sm">No hay fotos seleccionadas</p>
                </div>
            `;
            return;
        }

        files.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'relative group bg-white rounded-lg border border-gray-200 p-3';
                preview.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <button type="button" onclick="eliminarPreview(${index})" 
                                    class="text-white bg-danger rounded-full p-2 transform scale-0 group-hover:scale-100 transition-transform" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs text-gray-700 font-medium truncate" title="${file.name}">
                            ${file.name}
                        </p>
                        <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
                    </div>
                `;
                previewContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });
    }

    function eliminarPreview(index) {
        const fileInput = document.getElementById('fotos');
        const files = Array.from(fileInput.files);

        // Crear nueva lista de archivos excluyendo el índice a eliminar
        const newFiles = files.filter((_, i) => i !== index);

        // Crear nuevo DataTransfer y agregar los archivos restantes
        const dt = new DataTransfer();
        newFiles.forEach(file => dt.items.add(file));

        // Actualizar el input de archivos
        fileInput.files = dt.files;

        // Volver a renderizar las previsualizaciones
        renderPreviews();

        // Disparar el evento change para notificar a otros listeners
        fileInput.dispatchEvent(new Event('change'));
    }

    function cargarGaleriaFotos() {
        const custodiaId = '{{ $custodia->id }}';
        const galeria = document.getElementById('galeria-fotos');

        console.log('🔍 Iniciando carga de galería de fotos...');
        console.log('📁 Custodia ID:', custodiaId);
        console.log('🔗 URL:', `/custodia/${custodiaId}/fotos`);

        // Mostrar loading
        galeria.innerHTML = `
            <div class="text-center text-gray-500 py-8 col-span-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-2"></div>
                <p class="text-sm">Cargando fotos...</p>
            </div>
        `;

        fetch(`/custodia/${custodiaId}/fotos`)
            .then(response => {
                console.log('📡 Respuesta del servidor:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('✅ Datos recibidos:', data);

                if (data.fotos && data.fotos.length > 0) {
                    console.log(`🖼️ Se encontraron ${data.fotos.length} fotos`);

                    galeria.innerHTML = data.fotos.map((foto, index) => `
                        <div class="relative group bg-white rounded-lg border border-gray-200 p-3">
                            <div class="relative">
                                <img src="/custodia/fotos/${foto.id}/imagen?t=${new Date().getTime()}" 
                                     class="w-full h-24 object-cover rounded-lg"
                                     alt="${foto.nombre_archivo}"
                                     loading="lazy"
                                     onerror="console.error('❌ Error cargando imagen ${foto.id}'); this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2YzZjRmNiIvPjx0ZXh0IHg9IjEwMCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5YzljOWMiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FcnJvciBjYXJnYW5kbyBpbWFnZW48L3RleHQ+PC9zdmc+'"
                                     onload="console.log('✅ Imagen ${foto.id} cargada correctamente')">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <button type="button" onclick="verFoto(${foto.id})" 
                                            class="text-white bg-blue-600 rounded-full p-2 mx-1 transform scale-0 group-hover:scale-100 transition-transform" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button type="button" onclick="descargarFoto(${foto.id})" 
                                            class="text-white bg-green-600 rounded-full p-2 mx-1 transform scale-0 group-hover:scale-100 transition-transform" title="Descargar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </button>
                                    ${!{{ $isDisabled ? 'true' : 'false' }} ? `
                                            <button type="button" onclick="eliminarFoto(${foto.id})" 
                                                    class="text-white bg-danger rounded-full p-2 mx-1 transform scale-0 group-hover:scale-100 transition-transform" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        ` : ''}
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-700 font-medium truncate" title="${foto.nombre_archivo}">
                                    ${foto.nombre_archivo}
                                </p>
                                <p class="text-xs text-gray-500">${(foto.tamaño_archivo / 1024).toFixed(1)} KB</p>
                            </div>
                        </div>
                    `).join('');
                } else {
                    console.log('📭 No se encontraron fotos para esta custodia');
                    galeria.innerHTML = `
                        <div class="text-center text-gray-500 py-8 col-span-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-600">No hay fotos</p>
                            <p class="text-xs text-gray-500 mt-1">No se han subido fotos para esta custodia</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('❌ Error cargando galería:', error);
                galeria.innerHTML = `
                    <div class="text-center text-red-500 py-8 col-span-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-sm font-medium">Error cargando fotos</p>
                        <p class="text-xs mt-1">${error.message}</p>
                        <button onclick="cargarGaleriaFotos()" class="mt-2 text-xs bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition-colors">
                            Reintentar
                        </button>
                    </div>
                `;
            });
    }

    function subirFotos() {
        const fileInput = document.getElementById('fotos');
        const files = fileInput.files;
        const btnSubir = document.getElementById('btn-subir-fotos');

        if (files.length === 0) {
            toastr.warning('Selecciona al menos una foto');
            return;
        }

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        for (let i = 0; i < files.length; i++) {
            formData.append('fotos[]', files[i]);
        }

        // Mostrar loader
        const originalText = btnSubir.innerHTML;
        btnSubir.innerHTML = '<span>Subiendo...</span>';
        btnSubir.disabled = true;

        fetch(`/custodia/{{ $custodia->id }}/fotos`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Fotos subidas correctamente');
                    fileInput.value = ''; // Limpiar input
                    document.getElementById('preview-fotos').innerHTML = ''; // Limpiar preview
                    cargarGaleriaFotos(); // Recargar galería
                } else {
                    toastr.error(data.message || 'Error al subir fotos');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Error de conexión al subir fotos');
            })
            .finally(() => {
                // Restaurar botón
                btnSubir.innerHTML = originalText;
                btnSubir.disabled = false;
            });
    }

    function verFoto(idFoto) {
        window.open(`/custodia/fotos/${idFoto}/imagen`, '_blank');
    }

    function descargarFoto(idFoto) {
        window.open(`/custodia/fotos/${idFoto}/descargar`, '_blank');
    }

    function verificarIntegridad(idFoto) {
        fetch(`/custodia/fotos/${idFoto}/verificar`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.integro) {
                        toastr.success('✅ La foto está íntegra y no ha sido modificada');
                    } else {
                        toastr.error('❌ La foto ha sido modificada o está corrupta');
                    }
                } else {
                    toastr.error(data.message || 'Error al verificar integridad');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Error de conexión al verificar integridad');
            });
    }

    function eliminarFoto(idFoto) {
        if (!confirm('¿Estás seguro de eliminar esta foto?')) return;

        fetch(`/custodia/fotos/${idFoto}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Foto eliminada correctamente');
                    cargarGaleriaFotos();
                } else {
                    toastr.error(data.message || 'Error al eliminar foto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Error de conexión');
            });
    }

    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Página cargada. Estado inicial:', '{{ $custodia->estado }}');

        // ✅ CARGAR UBICACIÓN ACTUAL AL INICIAR SI EL ESTADO ES APROBADO
        if (document.getElementById('estado').value === 'Aprobado') {
            setTimeout(() => {
                cargarUbicacionActual();
            }, 500);
        }

        @if (!$isDisabled)
            const btnGuardar = document.getElementById('btn-guardar');
            const estadoSelect = document.getElementById('estado');

            // Inicializar visibilidad de campos según el estado actual
            toggleCamposPorEstado();

            // En el evento change del estado
            estadoSelect.addEventListener('change', function() {
                toggleCamposPorEstado();
            });

            // Inicializar preview de fotos
            setupFilePreview();

            // Cargar galería de fotos existentes
            cargarGaleriaFotos();

            // Manejar clic en guardar
            btnGuardar.addEventListener('click', function() {
                if (!validarFormulario()) {
                    return;
                }

                // Mostrar loader en el botón
                const originalText = btnGuardar.innerHTML;
                btnGuardar.innerHTML = '<span>Guardando...</span>';
                btnGuardar.disabled = true;

                // Recoger datos del formulario
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('_method', 'PUT');
                formData.append('estado', document.getElementById('estado').value);
                formData.append('ubicacion_actual', document.getElementById('ubicacion_actual').value);
                formData.append('observaciones', document.getElementById('observaciones').value);

                // Agregar campos adicionales si el estado es Aprobado
                if (document.getElementById('estado').value === 'Aprobado') {
                    let rackUbicacionId;

                    if ({{ $isDisabled ? 'true' : 'false' }}) {
                        // Si está deshabilitado, usar el campo oculto
                        rackUbicacionId = document.getElementById('hidden_rack_ubicacion_id').value;
                    } else {
                        // Si no está deshabilitado, usar el select normal
                        rackUbicacionId = document.getElementById('rack_ubicacion_id').value;
                    }

                    formData.append('rack_ubicacion_id', rackUbicacionId);
                    formData.append('observacion_almacen', document.getElementById(
                        'observacion_almacen').value);
                    formData.append('cantidad', document.querySelector('input[name="cantidad"]').value);
                }

                // Enviar solicitud AJAX
                fetch("{{ route('solicitudcustodia.update', $custodia->id) }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message || 'Cambios guardados correctamente');

                            // Actualizar el badge de estado
                            actualizarBadgeEstado(data.estado_actualizado);

                            // Si el estado cambió a Aprobado, actualizar la interfaz y recargar
                            if (data.estado_actualizado === 'Aprobado') {
                                const ubicacionInput = document.getElementById('ubicacion_actual');
                                ubicacionInput.setAttribute('readonly', true);
                                ubicacionInput.classList.add('bg-gray-100', 'cursor-not-allowed');

                                // Recargar la página después de un breve delay para mostrar la notificación
                                setTimeout(() => {
                                    // ✅ Cargar ubicación actual si no venía en la respuesta
                                    if (!data.ubicacion_actual) {
                                        cargarUbicacionActual();
                                    }
                                    window.location.reload();
                                }, 1500);
                            } else {
                                // Si no es Aprobado, solo mostrar el mensaje de éxito
                                console.log('Cambios guardados pero no es necesario recargar');
                            }
                        } else {
                            let errorMessage = data.message || 'Error al guardar los cambios';

                            // Mostrar errores de validación si existen
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).join(', ');
                            }

                            toastr.error(errorMessage);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Error de conexión: ' + error.message);
                    })
                    .finally(() => {
                        // Restaurar botón
                        btnGuardar.innerHTML = originalText;
                        btnGuardar.disabled = false;
                    });
            });
        @endif
    });

    // Agregar evento al botón de cargar sugerencias
    document.getElementById('btn-cargar-sugerencias').addEventListener('click', function() {
        cargarSugerenciasUbicaciones();
    });

    // Cargar inmediatamente cuando el DOM esté listo
    setTimeout(() => {
        cargarGaleriaFotos();
    }, 100);
</script>
</x-layout.default>
