<x-layout.default>
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Notificación para AJAX -->
        <div id="ajax-notification" class="hidden rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="ajax-message" class="font-medium"></span>
            <button class="ml-auto text-emerald-600 hover:text-emerald-800" onclick="hideNotification()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @if (session('ok'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 flex items-center gap-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-medium">{{ session('ok') }}</span>
                <button class="ml-auto text-emerald-600 hover:text-emerald-800" onclick="this.parentElement.style.display='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3 shadow-sm">
                <div class="font-medium mb-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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
                'Rechazado' => 'bg-danger text-white',
                'Devuelto' => 'bg-info text-white',
                default => 'bg-gray-100 text-white',
            };
        @endphp

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div>
                <!-- Título -->
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Editar Custodia</span>
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
                        <span>{{ $custodia->ticket->cliente->nombre ?? 'Cliente N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span id="estado-badge" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                    {{ $estado }}
                </span>
            </div>
        </div>

        <!-- Summary card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-primary p-5 rounded-xl border shadow-sm flex items-start gap-4">
                <div class="h-12 w-12 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-white">Equipo</div>
                    <div class="font-semibold text-white mt-1">
                        {{ $custodia->ticket->marca->nombre ?? '—' }}
                        {{ $custodia->ticket->modelo->nombre ?? '' }}
                    </div>
                </div>
            </div>

            <div class="bg-info p-5 rounded-xl border border-gray-200 shadow-sm flex items-start gap-4">
                <div class="h-12 w-12 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-white">Serie</div>
                    <div class="font-semibold text-white mt-1">{{ $custodia->ticket->serie ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="bg-dark p-5 rounded-xl border border-gray-200 shadow-sm flex items-start gap-4">
                <div class="h-12 w-12 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-white">Ingreso a custodia</div>
                    <div class="font-semibold text-white mt-1">
                        {{ \Carbon\Carbon::parse($custodia->fecha_ingreso_custodia)->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form card -->
        <div class="panel rounded-xl shadow-sm overflow-hidden">
            <div class="bg-dark-light px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Estado
                        </label>
                        <div class="relative">
                            <select id="estado" name="estado" class="w-full appearance-none border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                @foreach (['Pendiente', 'En revisión', 'Aprobado', 'Rechazado', 'Devuelto'] as $op)
                                    <option value="{{ $op }}" @selected(old('estado', $custodia->estado) === $op)>
                                        {{ $op }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Ubicación actual -->
                    <div>
                        <label for="ubicacion_actual" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Ubicación Recepción
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 极 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <input type="text" id="ubicacion_actual" name="ubicacion_actual" maxlength="100"
                                value="{{ old('ubicacion_actual', $custodia->ubicacion_actual) }}"
                                class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                placeholder="Ej. Laboratorio o Zona TCL"
                                {{ $custodia->estado === 'Aprobado' ? 'readonly' : '' }}>
                        </div>
                        <div id="error-ubicacion" class="mt-1 text-sm text-red-600 hidden flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 极 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span id="error-ubicacion-text"></span>
                        </div>
                    </div>
                </div>

                <!-- Campos adicionales para estado Aprobado (inicialmente ocultos) -->
                <div id="campos-aprobado" class="hidden space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Ubicación de equipo en almacén
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Select de ubicación en almacén -->
                            <div>
                                <label for="ubicacion_almacen" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap极="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ubicación en Almacén
                                </label>
                                <div class="relative">
                                    <select id="ubicacion_almacen" name="ubicacion_almacen" class="w-full appearance-none border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                        <option value="">Seleccionar ubicación</option>
                                        <option value="Almacén Principal - Estante A">Almacén Principal - Estante A</option>
                                        <option value="Almacén Principal - Estante B">Almacén Principal - Estante B</option>
                                        <option value="Almacén Principal - Estante C">Almacén Principal - Estante C</option>
                                        <option value="Almacén Secundario - Zona 1">Almacén Secundario - Zona 1</option>
                                        <option value="Almacén Secundario - Zona 2">Almacén Secundario - Zona 2</option>
                                        <option value="Bodega de Custodia - Área Segura">Bodega de Custodia - Área Segura</option>
                                        <option value="Sector Reparación - Temporal">Sector Reparación - Temporal</option>
                                        <option value="Sector Diagnóstico">Sector Diagnóstico</option>
                                        <option value="Cuarentena Técnica">Cuarentena Técnica</option>
                                        <option value="Otra ubicación">Otra ubicación</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Campo para observaciones específicas de almacén -->
                            <div>
                                <label for="observaciones_almacen" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Observaciones de Almacén
                                </label>
                                <textarea id="observaciones_almacen" name="observaciones_almacen" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="Detalles específicos sobre la ubicación en almacén, condición del equipo, etc."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @php
                        $fechaIngreso = \Carbon\Carbon::parse($custodia->fecha_ingreso_custodia);
                        $dias = $fechaIngreso->diffInDays(\Carbon\Carbon::now()->startOfDay());
                        $horas = $fechaIngreso->diffInHours();

                        $textoTiempo = match (true) {
                            $horas < 24 => 'Lleva menos de un día',
                            $dias === 1 => 'Hace 1 día',
                            default => "Hace {$dias} días",
                        };
                    @endphp

                    <!-- Tiempo en custodia -->
                    <div id="wrap-fecha">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7极V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V极7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                        <label for="responsable_recepcion" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500" viewBox="极 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 极 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Responsable de recepción
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7极z" />
                                </svg>
                            </span>
                            <input type="text" id="id_responsable_recepcion" name="id_responsable_recepcion" maxlength="100"
                                value="{{ $custodia->responsableRecepcion ? $custodia->responsableRecepcion->Nombre . ' ' . $custodia->responsableRecepcion->apellidoPaterno : '' }}"
                                class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                placeholder="Nombre y apellido" disabled>
                        </div>
                    </div>
                </div>

                <!-- Observaciones generales -->
                <div>
                    <label for="observaciones" class="block text-sm font-medium text-gray-极700 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Observaciones Generales
                    </label>
                    <textarea id="observaciones" name="observaciones" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                        placeholder="Notas internas, detalles del estado, accesorios, etc.">{{ old('observaciones', $custodia->observaciones) }}</textarea>
                </div>

                <!-- Sticky actions -->
                <div class="border-t border-gray-200 -mx-6 px-6 py-4 bg-white/80 backdrop-blur supports-[backdrop-filter]:sticky supports-[backdrop-filter]:bottom-0 mt-8">
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                        <a href="{{ route('solicitudcustodia.index') }}" class="btn btn-danger">
                            Atras
                        </a>
                        <button type="button" id="btn-guardar" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Actualizar cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Ocultar notificación
        function hideNotification() {
            document.getElementById('ajax-notification').classList.add('hidden');
        }

        // Mostrar notificación
        function showNotification(message, isSuccess = true) {
            const notification = document.getElementById('ajax-notification');
            const messageElement = document.getElementById('ajax-message');
            
            messageElement.textContent = message;
            
            if (isSuccess) {
                notification.classList.remove('bg-red-50', 'border-red-200', 'text-red-800');
                notification.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
            } else {
                notification.classList.remove('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
                notification.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
            }
            
            notification.classList.remove('hidden');
            
            // Ocultar después de 5 segundos
            setTimeout(hideNotification, 5000);
        }

        // Validar formulario
        function validarFormulario() {
            const estado = document.getElementById('estado').value;
            const ubicacion = document.getElementById('ubicacion_actual').value;
            const ubicacionAlmacen = document.getElementById('ubicacion_almacen');
            const errorUbicacion = document.getElementById('error-ubicacion');
            const errorUbicacionText = document.getElementById('error-ubicacion-text');
            
            // Limpiar errores anteriores
            errorUbicacion.classList.add('hidden');
            errorUbicacionText.textContent = '';
            
            // Validar que si el estado es Aprobado, la ubicación no esté vacía
            if (estado === 'Aprobado' && !ubicacion.trim()) {
                errorUbicacionText.textContent = 'La ubicación es requerida cuando el estado es Aprobado';
                errorUbicacion.classList.remove('hidden');
                return false;
            }
            
            // Validar ubicación de almacén si el estado es Aprobado
            if (estado === 'Aprobado' && ubicacionAlmacen && !ubicacionAlmacen.value) {
                errorUbicacionText.textContent = 'La ubicación en almacén es requerida cuando el estado es Aprobado';
                errorUbicacion.classList.remove('hidden');
                return false;
            }
            
            return true;
        }

        // Actualizar clases del badge según el estado
        function actualizarBadgeEstado(estado) {
            const estadoBadge = document.getElementById('estado-badge');
            let badgeClass = '';
            
            switch(estado) {
                case 'Pendiente':
                    badgeClass = 'bg-warning text-white';
                    break;
                case 'En revisión':
                    badgeClass = 'bg-secondary text-white';
                    break;
                case 'Aprobado':
                    badgeClass = 'bg-success text-white';
                    break;
                case 'Rechazado':
                    badgeClass = 'bg-danger text-white';
                    break;
                case 'Devuelto':
                    badgeClass = 'bg-info text-white';
                    break;
                default:
                    badgeClass = 'bg-gray-100 text-white';
            }
            
            estadoBadge.className = `inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium ring-1 ring-inset ${badgeClass}`;
            estadoBadge.textContent = estado;
        }

        // Mostrar u ocultar campos adicionales para estado Aprobado
        function toggleCamposAprobado() {
            const estado = document.getElementById('estado').value;
            const camposAprobado = document.getElementById('campos-aprobado');
            const ubicacionInput = document.getElementById('ubicacion_actual');
            
            if (estado === 'Aprobado') {
                camposAprobado.classList.remove('hidden');
                ubicacionInput.setAttribute('readonly', true);
            } else {
                camposAprobado.classList.add('hidden');
                ubicacionInput.removeAttribute('readonly');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btnGuardar = document.getElementById('btn-guardar');
            const estadoSelect = document.getElementById('estado');
            const ubicacionInput = document.getElementById('ubicacion_actual');
            
            // Inicializar visibilidad de campos según el estado actual
            toggleCamposAprobado();
            
            // Cambiar cuando se modifique el estado
            estadoSelect.addEventListener('change', function() {
                toggleCamposAprobado();
            });
            
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
                    formData.append('ubicacion_almacen', document.getElementById('ubicacion_almacen').value);
                    formData.append('observaciones_almacen', document.getElementById('observaciones_almacen').value);
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
                        showNotification(data.message || 'Cambios guardados correctamente', true);
                        
                        // Actualizar el badge de estado
                        actualizarBadgeEstado(data.estado_actualizado);
                        
                        // Si el estado cambió a Aprobado, actualizar la interfaz
                        if (data.estado_actualizado === 'Aprobado') {
                            ubicacionInput.setAttribute('readonly', true);
                        }
                    } else {
                        let errorMessage = data.message || 'Error al guardar los cambios';
                        
                        // Mostrar errores de validación si existen
                        if (data.errors) {
                            errorMessage = Object.values(data.errors).join(', ');
                        }
                        
                        showNotification(errorMessage, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error de conexión: ' + error.message, false);
                })
                .finally(() => {
                    // Restaurar botón
                    btnGuardar.innerHTML = originalText;
                    btnGuardar.disabled = false;
                });
            });
        });
    </script>
</x-layout.default>