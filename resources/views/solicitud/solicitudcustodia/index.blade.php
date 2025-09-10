<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-700">Sistema de Custodia de Equipos</h1>
                <p class="text-gray-600 mt-2">Gestión y seguimiento de equipos en custodia</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nueva Custodia
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="panel rounded-xl shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('solicitudcustodia.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Buscar por cliente, modelo o serie..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                                value="{{ request('search') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <select name="estado"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                            <option value="Todos los estados" {{ request('estado') == 'Todos los estados' ? 'selected' : '' }}>Todos los estados</option>
                            <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="En revisión" {{ request('estado') == 'En revisión' ? 'selected' : '' }}>En revisión</option>
                            <option value="Aprobado" {{ request('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="Rechazado" {{ request('estado') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                            <option value="Devuelto" {{ request('estado') == 'Devuelto' ? 'selected' : '' }}>Devuelto</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg transition-colors duration-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                    </div>
                    <div>
                        @if(request('search') || request('estado'))
                            <a href="{{ route('solicitudcustodia.index') }}"
                                class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-300 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-primary from-blue-500 to-indigo-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">Total de Solicitudes</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                    </svg>
                </div>
                <p class="text-2xl font-bold mt-2">{{ $totalSolicitudes }}</p>
                <p class="text-xs opacity-75 mt-1">Solicitudes</p>
            </div>

            <div class="bg-warning from-amber-500 to-orange-500 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">Pendientes</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    </svg>

                </div>
                <p class="text-2xl font-bold mt-2">{{ $pendientes }}</p>
                <p class="text-xs opacity-75 mt-1">Por revisar</p>
            </div>

            <div class="bg-success from-green-500 to-emerald-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">En Custodia</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9.75V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v3.75M3 9.75L12 15l9-5.25M3 9.75v8.25A2.25 2.25 0 005.25 20.25h13.5A2.25 2.25 0 0021 18V9.75" />
                    </svg>


                </div>
                <p class="text-2xl font-bold mt-2">{{ $enCustodia }}</p>
                <p class="text-xs opacity-75 mt-1">Equipos activos</p>
            </div>

            <div class="bg-danger from-red-500 to-pink-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">Devueltos</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l-4-4 4-4m11 4H5" />
                    </svg>

                </div>
                <p class="text-2xl font-bold mt-2">{{ $devueltos }}</p>
                <p class="text-xs opacity-75 mt-1">Equipos devueltos</p>
            </div>
        </div>

        <!-- Lista de custodias -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Solicitudes de Custodia</h2>

            @if ($custodias->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($custodias as $custodia)
                        <div
                            class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Header con estado y código -->
                            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                <div class="flex justify-between items-center mb-3">
                                    <span
                                        class="px-3 py-1 bg-info-light text-blue-800 text-xs font-semibold rounded-full flex items-center w-fit shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                        #{{ $custodia->codigocustodias }}
                                    </span>

                                    <span
                                        class="px-3 py-1 
                        @if ($custodia->estado == 'Pendiente') bg-warning text-white
                        @elseif($custodia->estado == 'En revisión') bg-secondary text-white
                        @elseif($custodia->estado == 'Aprobado') bg-success text-white
                        @elseif($custodia->estado == 'Rechazado') bg-danger text-white
                        @elseif($custodia->estado == 'Devuelto') bg-info text-white
                        @else bg-gray-100 text-gray-800 @endif
                        text-xs font-medium rounded-full">
                                        {{ $custodia->estado }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    @if (isset($custodia->ticket->marca->nombre) && isset($custodia->ticket->modelo->nombre))
                                        {{ $custodia->ticket->marca->nombre }} {{ $custodia->ticket->modelo->nombre }}
                                    @else
                                        Equipo sin especificar
                                    @endif
                                </h3>

                                <div
                                    class="flex items-center mt-2 text-sm text-gray-600 bg-white px-2 py-1 rounded-md w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                    Serie: {{ $custodia->ticket->serie ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Información principal -->
                            <div class="p-5">
                                <!-- Información del cliente -->
                                <div class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div
                                        class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center border border-indigo-200">
                                        @php
                                            $cliente = $custodia->ticket->cliente ?? null;
                                            $inicial = 'C';
                                            if ($cliente) {
                                                $nombres = explode(' ', $cliente->nombre ?? '');
                                                $inicial = substr($nombres[0] ?? 'C', 0, 1);
                                                if (count($nombres) > 1) {
                                                    $inicial .= substr($nombres[1] ?? '', 0, 1);
                                                }
                                            }
                                        @endphp
                                        <span class="text-indigo-800 font-medium">{{ $inicial }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ $custodia->ticket->cliente->nombre ?? 'Cliente no especificado' }}
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            @if (isset($custodia->ticket->cliente->documento) && isset($custodia->ticket->cliente->tipoDocumento))
                                                {{ $custodia->ticket->cliente->tipoDocumento->nombre ?? 'Doc' }}:
                                                {{ $custodia->ticket->cliente->documento }}
                                            @else
                                                Sin documento
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Detalles de la custodia -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-2 bg-blue-50 rounded-md">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12h13m0 0l-4-4m4 4l-4 4m7-9v10a2 2 0 01-2 2H7" />
                                            </svg>

                                            <span class="text-sm font-bold block mb-1">Ingreso:</span>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ date('d/m/Y', strtotime($custodia->fecha_ingreso_custodia)) }}</span>
                                    </div>

                                    <div class="flex items-center justify-between p-2 bg-purple-50 rounded-md">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-2 text-purple-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                            </svg>
                                            <span class="text-sm font-bold block mb-1">Ticket:</span>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-purple-600">#{{ $custodia->ticket->numero_ticket ?? 'N/A' }}</span>
                                    </div>

                                    @if ($custodia->fecha_devolucion)
                                        <div class="flex items-center justify-between p-2 bg-dark-light rounded-md">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-2 text-dark-light" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                <span class="text-sm font-bold block mb-1">Devolución:</span>
                                            </div>
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ date('d/m/Y', strtotime($custodia->fecha_devolucion)) }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between p-2 bg-warning-light rounded-md">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-2 text-amber-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm font-bold block mb-1">Tiempo:</span>
                                        </div>
                                        <span class="text-sm font-medium text-amber-600">
                                            @php
                                                $fechaInicio = new DateTime($custodia->fecha_ingreso_custodia);
                                                $fechaFin = $custodia->fecha_devolucion
                                                    ? new DateTime($custodia->fecha_devolucion)
                                                    : new DateTime();
                                                $diferencia = $fechaInicio->diff($fechaFin);

                                                if ($diferencia->y > 0) {
                                                    echo $diferencia->y . ' año' . ($diferencia->y > 1 ? 's ' : ' ');
                                                }
                                                if ($diferencia->m > 0) {
                                                    echo $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es ' : ' ');
                                                }
                                                if ($diferencia->d > 0) {
                                                    echo $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
                                                }
                                                if ($diferencia->y == 0 && $diferencia->m == 0 && $diferencia->d == 0) {
                                                    echo 'Menos de 1 día';
                                                }
                                            @endphp
                                        </span>
                                    </div>

                                    <div class="p-2 bg-success-light rounded-md">
                                        <div class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-2 text-success mt-0.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <div class="w-full">
                                                <span class="text-sm font-bold block mb-1">Ubicación de
                                                    Recepción:</span>
                                                <p class="text-sm text-gray-900 break-words">
                                                    {{ $custodia->ubicacion_actual ?? 'Sin ubicación especificada' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($custodia->estado === 'Aprobado')
                                        <div class="p-2 bg-danger-light rounded-md">
                                            <div class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-2 text-danger mt-0.5" fill="none"
                                                    viewBox="0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h1l1 9a1 1 0 001 1h12a1 1 0 001-1l1-9h1M5 10h14M9 21v-5h6v5" />
                                                </svg>
                                                <div class="w-full">
                                                    <span class="text-sm font-bold block mb-1">Ubicación de Almacén:</span>
                                                    
                                                    @if(isset($custodia->custodiaUbicacion) && isset($custodia->custodiaUbicacion->ubicacion))
                                                        <p class="text-sm text-gray-900 break-words">
                                                            {{ $custodia->custodiaUbicacion->ubicacion->nombre }}
                                                        </p>
                                                        @if($custodia->custodiaUbicacion->observacion)
                                                            <p class="text-xs text-gray-600 mt-1">
                                                                Obs: {{ $custodia->custodiaUbicacion->observacion }}
                                                            </p>
                                                        @endif
                                                    @else
                                                        <p class="text-sm text-gray-500 italic">Sin ubicación asignada</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <!-- Footer con botón de opciones -->
                            <div class="px-5 py-3  border-t border-gray-200 flex justify-end">
                                <a href="{{ route('solicitudcustodia.opciones', ['id' => $custodia->id]) }}"
                                    class="btn btn-warning flex items-center px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                    OPCIONES
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-700 mt-4">No hay custodias registradas</h3>
                    <p class="text-gray-500 mt-2">Comienza agregando tu primera custodia</p>
                </div>
            @endif
        </div>

        <!-- Paginación -->
        @if ($custodias->count() > 0)
            <div class="mt-6">
                {{ $custodias->links() }}
            </div>
        @endif
    </div>
</x-layout.default>
