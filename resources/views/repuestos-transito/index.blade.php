<x-layout.default>
    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📦 Gestión de Repuestos</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Seguimiento de repuestos usados, no usados y en tránsito</p>
        </div>

        <!-- Cards de Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Repuestos</p>
                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-2">{{ $contadores['total'] }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Todos los estados</p>
                    </div>
                    <div class="bg-blue-200 dark:bg-blue-800 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-700 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- En Tránsito -->
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700 dark:text-yellow-300">En Tránsito</p>
                        <p class="text-3xl font-bold text-yellow-900 dark:text-yellow-100 mt-2">{{ $contadores['pendientes'] }}</p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Pendientes de uso</p>
                    </div>
                    <div class="bg-yellow-200 dark:bg-yellow-800 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-700 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Usados -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">Usados</p>
                        <p class="text-3xl font-bold text-green-900 dark:text-green-100 mt-2">{{ $contadores['usados'] }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">Ya utilizados</p>
                    </div>
                    <div class="bg-green-200 dark:bg-green-800 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- No Usados -->
            <div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700 dark:text-red-300">No Usados</p>
                        <p class="text-3xl font-bold text-red-900 dark:text-red-100 mt-2">{{ $contadores['no_usados'] }}</p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">Devueltos al stock</p>
                    </div>
                    <div class="bg-red-200 dark:bg-red-800 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-[#1b2e4b] rounded-xl shadow-sm p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">🔍 Filtros de Búsqueda</h2>
            <form method="GET" action="" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Estado
                            </span>
                        </label>
                        <select name="estado" class="form-select w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>🔄 En Tránsito</option>
                            <option value="usado" {{ request('estado') == 'usado' ? 'selected' : '' }}>✅ Usado</option>
                            <option value="no_usado" {{ request('estado') == 'no_usado' ? 'selected' : '' }}>❌ No Usado</option>
                        </select>
                    </div>

                    <!-- Código Repuesto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                </svg>
                                Código Repuesto
                            </span>
                        </label>
                        <input type="text" name="codigo_repuesto" value="{{ request('codigo_repuesto') }}"
                            class="form-input w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            placeholder="Ej: REP-001">
                    </div>

                    <!-- Fecha Desde -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Desde
                            </span>
                        </label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                            class="form-input w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <!-- Fecha Hasta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Hasta
                            </span>
                        </label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            class="form-input w-full border-gray-300 dark:border-gray-600 dark:bg-[#121c2c] dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-wrap gap-2 pt-2">
                    <button type="submit"
                        class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Aplicar Filtros
                    </button>

                    <a href=""
                        class="btn btn-outline-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de Repuestos -->
        <div class="bg-white dark:bg-[#1b2e4b] rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Header de la tabla -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#121c2c]">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">📋 Lista de Repuestos</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Mostrando {{ $repuestos->count() }} de {{ $repuestos->total() }} repuestos</p>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Página {{ $repuestos->currentPage() }} de {{ $repuestos->lastPage() }}
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-[#121c2c]">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Repuesto
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Solicitud
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Estado
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Fechas
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Cantidad
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Acciones
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-[#1b2e4b] divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($repuestos as $repuesto)
                        <tr class="hover:bg-gray-50 dark:hover:bg-[#121c2c] transition-colors">
                            <!-- Columna Repuesto -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg flex items-center justify-center border border-blue-200 dark:border-blue-800">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $repuesto->nombre_repuesto }}
                                        </div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 font-medium mt-0.5">
                                            Código: {{ $repuesto->codigo_repuesto ?: 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-500 mt-1 flex flex-wrap gap-1">
                                            @if($repuesto->subcategoria)
                                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded-full">{{ $repuesto->subcategoria }}</span>
                                            @endif
                                            @if($repuesto->modelo)
                                            <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full">{{ $repuesto->modelo }}</span>
                                            @endif
                                            @if($repuesto->marca)
                                            <span class="px-2 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">{{ $repuesto->marca }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Columna Solicitud -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $repuesto->codigo_solicitud }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $repuesto->solicitante ?: 'N/A' }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($repuesto->fecha_solicitud)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Columna Estado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $estadoClase = '';
                                    $estadoIcono = '';
                                    $estadoTexto = '';
                                    
                                    if ($repuesto->fechaUsado) {
                                        $estadoClase = 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400';
                                        $estadoIcono = '✅';
                                        $estadoTexto = 'Usado';
                                    } elseif ($repuesto->fechaSinUsar) {
                                        $estadoClase = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400';
                                        $estadoIcono = '❌';
                                        $estadoTexto = 'No Usado';
                                    } else {
                                        $estadoClase = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
                                        $estadoIcono = '🔄';
                                        $estadoTexto = 'En Tránsito';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $estadoClase }}">
                                    {{ $estadoIcono }} {{ $estadoTexto }}
                                </span>
                            </td>

                            <!-- Columna Fechas -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="space-y-1">
                                    @if($repuesto->fechaUsado)
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Usado:</span>
                                        <span class="ml-1">{{ \Carbon\Carbon::parse($repuesto->fechaUsado)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @endif
                                    @if($repuesto->fechaSinUsar)
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Devuelto:</span>
                                        <span class="ml-1">{{ \Carbon\Carbon::parse($repuesto->fechaSinUsar)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @endif
                                    @if(!$repuesto->fechaUsado && !$repuesto->fechaSinUsar)
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Solicitado:</span>
                                        <span class="ml-1">{{ \Carbon\Carbon::parse($repuesto->fecha_solicitud)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Columna Cantidad -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-sm font-semibold">
                                        {{ $repuesto->cantidad }} unidades
                                    </span>
                                </div>
                                @if($repuesto->observacion)
                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-2 truncate max-w-xs" title="{{ $repuesto->observacion }}">
                                    📝 {{ Str::limit($repuesto->observacion, 50) }}
                                </div>
                                @endif
                            </td>

                            <!-- Columna Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Botón Ver Detalles -->
                                    <button type="button"
                                        onclick="openDetailsModal({{ $repuesto->idOrdenesArticulos }})"
                                        class="btn btn-outline-primary btn-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </button>

                                    <!-- Botón para fotos - VERIFICACIÓN MEJORADA -->
                                    @php
                                        $tieneFotos = false;
                                        
                                       
                                        
                                        // Verificar foto_articulo_usado
                                        if (!$tieneFotos && !empty($repuesto->foto_articulo_usado)) {
                                            try {
                                                $fotos = json_decode($repuesto->foto_articulo_usado, true);
                                                if (is_array($fotos) && count($fotos) > 0) {
                                                    $tieneFotos = true;
                                                }
                                            } catch (\Exception $e) {
                                                $tieneFotos = true;
                                            }
                                        }
                                        
                                        // Verificar foto_articulo_no_usado
                                        if (!$tieneFotos && !empty($repuesto->foto_articulo_no_usado)) {
                                            try {
                                                $fotos = json_decode($repuesto->foto_articulo_no_usado, true);
                                                if (is_array($fotos) && count($fotos) > 0) {
                                                    $tieneFotos = true;
                                                }
                                            } catch (\Exception $e) {
                                                $tieneFotos = true;
                                            }
                                        }
                                    @endphp
                                    
                                    @if($tieneFotos)
                                    <button type="button"
                                        onclick="openPhotosModal({{ $repuesto->idOrdenesArticulos }})"
                                        class="btn btn-outline-info btn-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Fotos
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay repuestos</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No se encontraron repuestos con los filtros aplicados.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($repuestos->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#121c2c]">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-400">
                        Mostrando {{ $repuestos->firstItem() }} a {{ $repuestos->lastItem() }} de {{ $repuestos->total() }} resultados
                    </div>
                    <div class="flex space-x-2">
                        {{ $repuestos->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>


    <!-- MODALES (Fuera del layout) -->
    <div x-data="modalDetails" x-cloak>
        <!-- Modal de detalles -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto" x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="flex items-start justify-center min-h-screen px-4 py-8" @click.self="open = false">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl bg-white dark:bg-[#1b2e4b]">
                    <!-- Header del Modal -->
                    <div class="flex items-center justify-between px-5 py-3 bg-[#fbfbfb] dark:bg-[#121c2c]">
                        <div>
                            <h5 class="font-bold text-lg text-gray-800 dark:text-white" x-text="modalTitle"></h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="modalSubtitle"></p>
                        </div>
                        <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" @click="toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido del Modal -->
                    <div class="p-5">
                        <!-- Indicador de carga -->
                        <div x-show="loading" class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="text-gray-600 dark:text-gray-400 mt-4">Cargando detalles...</p>
                        </div>

                        <!-- Contenido principal -->
                        <div x-show="!loading && dataLoaded" class="space-y-6">
                            <!-- Información básica -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Columna izquierda -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Nombre del Repuesto</label>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-white" x-text="details.nombre_repuesto || 'N/A'"></p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Códigos</label>
                                        <div class="space-y-1">
                                            <p class="text-sm" x-text="'Principal: ' + (details.codigo_repuesto || 'N/A')"></p>
                                            <p class="text-sm" x-show="details.sku" x-text="'SKU: ' + details.sku"></p>
                                            <p class="text-sm" x-show="details.codigo_barras" x-text="'Código Barras: ' + details.codigo_barras"></p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Estado</label>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                            :class="getStatusClass(details.estado)">
                                            <span x-text="getStatusIcon(details.estado)"></span>
                                            <span class="ml-1" x-text="getStatusText(details.estado)"></span>
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Cantidad</label>
                                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" x-text="details.cantidad + ' unidades'"></p>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Información de Solicitud</label>
                                        <div class="space-y-2">
                                            <p class="text-sm">
                                                <span class="font-medium">Código:</span>
                                                <span class="ml-2" x-text="details.codigo_solicitud || 'N/A'"></span>
                                            </p>
                                            <p class="text-sm">
                                                <span class="font-medium">Solicitante:</span>
                                                <span class="ml-2" x-text="details.solicitante || 'N/A'"></span>
                                            </p>
                                            <p class="text-sm">
                                                <span class="font-medium">Fecha Solicitud:</span>
                                                <span class="ml-2" x-text="formatDate(details.fecha_solicitud)"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Categorización</label>
                                        <div class="space-y-1">
                                            <p class="text-sm" x-show="details.subcategoria" x-text="'Subcategoría: ' + details.subcategoria"></p>
                                            <p class="text-sm" x-show="details.modelo" x-text="'Modelo: ' + details.modelo"></p>
                                            <p class="text-sm" x-show="details.marca" x-text="'Marca: ' + details.marca"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fechas importantes -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Fechas Importantes</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div x-show="details.fechaUsado" class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <div class="flex-shrink-0 mr-3">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-green-800 dark:text-green-300">Fecha de Uso</p>
                                            <p class="text-xs text-green-600 dark:text-green-400" x-text="formatDateTime(details.fechaUsado)"></p>
                                        </div>
                                    </div>

                                    <div x-show="details.fechaSinUsar" class="flex items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <div class="flex-shrink-0 mr-3">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-red-800 dark:text-red-300">Fecha de Devolución</p>
                                            <p class="text-xs text-red-600 dark:text-red-400" x-text="formatDateTime(details.fechaSinUsar)"></p>
                                        </div>
                                    </div>

                                    <div x-show="!details.fechaUsado && !details.fechaSinUsar" class="flex items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <div class="flex-shrink-0 mr-3">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">En Tránsito</p>
                                            <p class="text-xs text-yellow-600 dark:text-yellow-400">Pendiente de uso o devolución</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div x-show="details.observacion" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Observaciones</label>
                                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300" x-text="details.observacion"></p>
                                </div>
                            </div>

                            <!-- Evidencias disponibles -->
                            <div x-show="evidencias && evidencias.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Evidencias Fotográficas</label>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <span x-text="evidencias.length"></span> foto(s) disponible(s)
                                </p>
                                <button type="button" @click="viewPhotos" class="btn btn-info btn-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Ver Fotos
                                </button>
                            </div>
                        </div>

                        <!-- Mensaje de error -->
                        <div x-show="!loading && error" class="text-center py-8">
                            <div class="text-red-500 mb-4">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2" x-text="errorTitle"></h4>
                            <p class="text-gray-600 dark:text-gray-400" x-text="errorMessage"></p>
                        </div>
                    </div>

                    <!-- Footer del Modal -->
                    <div class="flex justify-end items-center px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" class="btn btn-outline-danger" @click="toggle">Cerrar</button>
                        <button x-show="evidencias && evidencias.length > 0" type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4" @click="viewPhotos">
                            Ver Fotos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div x-data="modalPhotos" x-cloak>
    <!-- Modal de fotos -->
    <div class="fixed inset-0 bg-[black]/60 z-[1000] overflow-y-auto" x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="flex items-start justify-center min-h-screen px-4 py-8" @@click.self="open = false">
            <div x-show="open" x-transition x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-4xl bg-white dark:bg-[#1b2e4b]">
                <!-- Header del Modal -->
                <div class="flex items-center justify-between px-5 py-3 bg-[#fbfbfb] dark:bg-[#121c2c]">
                    <div>
                        <h5 class="font-bold text-lg text-gray-800 dark:text-white">Evidencias Fotográficas</h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="'Total: ' + (evidencias ? evidencias.length : 0) + ' foto(s)'"></p>
                    </div>
                    <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" @@click="toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-5">
                    <!-- Indicador de carga -->
                    <div x-show="loading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="text-gray-600 dark:text-gray-400 mt-4">Cargando fotos...</p>
                    </div>

                    <!-- Galería de fotos -->
                    <div x-show="!loading && evidencias && evidencias.length > 0" class="space-y-4">
                        <!-- Navegación de fotos -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Foto <span x-text="currentIndex + 1"></span> de <span x-text="evidencias.length"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" @@click="prevPhoto" :disabled="currentIndex === 0"
                                    class="btn btn-outline-primary btn-sm" :class="{ 'opacity-50 cursor-not-allowed': currentIndex === 0 }">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Anterior
                                </button>
                                <button type="button" @@click="nextPhoto" :disabled="currentIndex === evidencias.length - 1"
                                    class="btn btn-outline-primary btn-sm" :class="{ 'opacity-50 cursor-not-allowed': currentIndex === evidencias.length - 1 }">
                                    Siguiente
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Foto actual -->
                        <div class="flex justify-center mb-4">
                            <div class="relative max-w-full">
                                <img :src="getImageUrl(evidencias[currentIndex])"
                                    :alt="'Evidencia ' + (currentIndex + 1)"
                                    class="max-w-full h-auto rounded-lg shadow-lg max-h-[60vh] object-contain"
                                    @@error="handleImageError"
                                    x-ref="currentImage">
                                <div x-show="imageLoading" class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Miniaturas -->
                        <div class="flex space-x-2 overflow-x-auto pb-2" x-data="{ fotos: [] }">
                            <template x-for="(img, index) in evidencias" :key="index">
                                <button type="button"
                                    @@click="goToPhoto(index)"
                                    class="flex-shrink-0 w-16 h-16 rounded border-2"
                                    :class="currentIndex === index ? 'border-blue-500' : 'border-gray-300 dark:border-gray-600'">
                                    <img :src="getImageUrl(img)"
                                        :alt="'Miniatura ' + (index + 1)"
                                        class="w-full h-full object-cover rounded"
                                        @@error="handleThumbnailError">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Sin fotos -->
                    <div x-show="!loading && (!evidencias || evidencias.length === 0)" class="text-center py-8">
                        <div class="text-gray-400 dark:text-gray-500 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">No hay evidencias fotográficas</h4>
                        <p class="text-gray-600 dark:text-gray-400">Este repuesto no tiene fotos de evidencia registradas.</p>
                    </div>
                </div>

                <!-- Footer del Modal -->
                <div class="flex justify-between items-center px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span x-text="evidencias ? evidencias[currentIndex] : ''" class="truncate max-w-xs block"></span>
                    </div>
                    <button type="button" class="btn btn-outline-danger" @@click="toggle">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- JavaScript -->
    <script>
        document.addEventListener("alpine:init", () => {
            // Modal para detalles
            Alpine.data("modalDetails", () => ({
                open: false,
                loading: false,
                dataLoaded: false,
                error: false,
                errorTitle: '',
                errorMessage: '',
                modalTitle: 'Detalles del Repuesto',
                modalSubtitle: '',
                details: {},
                evidencias: [],
                currentId: null,

                toggle() {
                    this.open = !this.open;
                    if (!this.open) {
                        this.reset();
                    }
                },

                reset() {
                    this.loading = false;
                    this.dataLoaded = false;
                    this.error = false;
                    this.errorTitle = '';
                    this.errorMessage = '';
                    this.details = {};
                    this.evidencias = [];
                    this.currentId = null;
                },

                async loadDetails(id) {
                    this.currentId = id;
                    this.loading = true;
                    this.dataLoaded = false;
                    this.error = false;

                    try {
                        const response = await fetch(`/repuestos-transito/${id}/detalles`);

                        if (!response.ok) {
                            throw new Error(`Error ${response.status}: ${response.statusText}`);
                        }

                        const data = await response.json();

                        if (data.success) {
                            this.details = data.data;
                            this.evidencias = data.evidencias || [];
                            this.modalTitle = data.data.nombre_repuesto || 'Detalles del Repuesto';
                            this.modalSubtitle = `Código: ${data.data.codigo_repuesto || 'N/A'}`;
                            this.dataLoaded = true;
                        } else {
                            this.error = true;
                            this.errorTitle = 'Error';
                            this.errorMessage = data.message || 'No se pudieron cargar los detalles';
                        }
                    } catch (error) {
                        console.error('Error al cargar detalles:', error);
                        this.error = true;
                        this.errorTitle = 'Error de conexión';
                        this.errorMessage = 'No se pudo cargar la información. Verifica tu conexión o intenta nuevamente.';
                    } finally {
                        this.loading = false;
                    }
                },

                getStatusClass(status) {
                    switch (status) {
                        case 'usado':
                            return 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400';
                        case 'no_usado':
                            return 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400';
                        case 'pendiente':
                            return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
                        default:
                            return 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-400';
                    }
                },

                getStatusIcon(status) {
                    switch (status) {
                        case 'usado':
                            return '✅';
                        case 'no_usado':
                            return '❌';
                        case 'pendiente':
                            return '🔄';
                        default:
                            return '❓';
                    }
                },

                getStatusText(status) {
                    switch (status) {
                        case 'usado':
                            return 'Usado';
                        case 'no_usado':
                            return 'No Usado';
                        case 'pendiente':
                            return 'En Tránsito';
                        default:
                            return 'Desconocido';
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return 'No disponible';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-PE');
                },

                formatDateTime(dateString) {
                    if (!dateString) return 'No disponible';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-PE') + ' ' + date.toLocaleTimeString('es-PE');
                },

                viewPhotos() {
                    if (this.evidencias && this.evidencias.length > 0) {
                        this.toggle();
                        setTimeout(() => {
                            window.openPhotosModal(this.currentId, this.evidencias);
                        }, 300);
                    }
                }
            }));

           // Modal para fotos - VERSIÓN CORREGIDA
Alpine.data("modalPhotos", () => ({
    open: false,
    loading: false,
    evidencias: [],
    currentIndex: 0,
    imageLoading: false,

    toggle() {
        this.open = !this.open;
        if (!this.open) {
            this.reset();
        }
    },

    reset() {
        this.loading = false;
        this.evidencias = [];
        this.currentIndex = 0;
        this.imageLoading = false;
    },

    async loadPhotos(id, preloadedEvidencias = null) {
        this.loading = true;
        this.currentIndex = 0;

        if (preloadedEvidencias && preloadedEvidencias.length > 0) {
            this.evidencias = preloadedEvidencias;
            this.loading = false;
            return;
        }

        try {
            const response = await fetch(`/repuestos-transito/${id}/detalles`);
            if (!response.ok) throw new Error('Error al cargar fotos');

            const data = await response.json();
            if (data.success && data.evidencias) {
                this.evidencias = data.evidencias;
            } else {
                this.evidencias = [];
            }
        } catch (error) {
            console.error('Error al cargar fotos:', error);
            this.evidencias = [];
        } finally {
            this.loading = false;
        }
    },

    getImageUrl(imgPath) {
        if (!imgPath) return this.getFallbackImage();
        
        // Si ya es una URL completa o relativa
        if (imgPath.startsWith('http') || imgPath.startsWith('/') || imgPath.startsWith('data:')) {
            return imgPath;
        }
        
        return imgPath;
    },

    getFallbackImage() {
        // SVG para imagen no disponible - 600x400
        return 'data:image/svg+xml;base64,' + btoa(`
            <svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 600 400">
                <rect width="600" height="400" fill="#f8f9fa"/>
                <rect x="225" y="150" width="150" height="100" fill="#e9ecef" rx="6"/>
                <circle cx="300" cy="100" r="50" fill="#e9ecef"/>
                <text x="300" y="250" text-anchor="middle" fill="#6c757d" font-family="Arial, sans-serif" font-size="16" font-weight="500">
                    Imagen no disponible
                </text>
                <text x="300" y="280" text-anchor="middle" fill="#adb5bd" font-family="Arial, sans-serif" font-size="12">
                    No se pudo cargar la imagen
                </text>
            </svg>
        `);
    },

    getFallbackThumbnail() {
        // SVG para miniatura - 64x64
        return 'data:image/svg+xml;base64,' + btoa(`
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                <rect width="64" height="64" fill="#f8f9fa"/>
                <rect x="16" y="24" width="32" height="20" fill="#e9ecef" rx="3"/>
                <circle cx="32" cy="14" r="10" fill="#e9ecef"/>
                <text x="32" y="44" text-anchor="middle" fill="#6c757d" font-family="Arial, sans-serif" font-size="10" font-weight="bold">
                    X
                </text>
                <text x="32" y="56" text-anchor="middle" fill="#adb5bd" font-family="Arial, sans-serif" font-size="6">
                    N/A
                </text>
            </svg>
        `);
    },

    handleImageError(event) {
        console.log('Error cargando imagen principal, usando SVG de respaldo');
        event.target.src = this.getFallbackImage();
        event.target.onerror = null; // Prevenir bucles infinitos
    },

    handleThumbnailError(event) {
        console.log('Error cargando miniatura, usando SVG de respaldo');
        event.target.src = this.getFallbackThumbnail();
        event.target.onerror = null; // Prevenir bucles infinitos
    },

    prevPhoto() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.imageLoading = true;
            // Preload la imagen
            this.preloadImage(this.currentIndex);
        }
    },

    nextPhoto() {
        if (this.currentIndex < this.evidencias.length - 1) {
            this.currentIndex++;
            this.imageLoading = true;
            // Preload la imagen
            this.preloadImage(this.currentIndex);
        }
    },

    goToPhoto(index) {
        if (index >= 0 && index < this.evidencias.length) {
            this.currentIndex = index;
            this.imageLoading = true;
            // Preload la imagen
            this.preloadImage(this.currentIndex);
        }
    },

    preloadImage(index) {
        if (!this.evidencias[index]) {
            this.imageLoading = false;
            return;
        }

        const img = new Image();
        img.src = this.getImageUrl(this.evidencias[index]);
        
        img.onload = () => {
            this.imageLoading = false;
        };
        
        img.onerror = () => {
            this.imageLoading = false;
            console.warn('Error al pre-cargar imagen en índice:', index);
        };
    }
}));
        });

        // Funciones globales para abrir modales
        window.openDetailsModal = function(id) {
            const modal = document.querySelector('[x-data="modalDetails"]');
            if (modal) {
                const instance = Alpine.$data(modal);
                instance.toggle();
                instance.loadDetails(id);
            }
        };

        window.openPhotosModal = function(id, preloadedEvidencias = null) {
            const modal = document.querySelector('[x-data="modalPhotos"]');
            if (modal) {
                const instance = Alpine.$data(modal);
                instance.toggle();
                instance.loadPhotos(id, preloadedEvidencias);
            }
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Repuestos en tránsito cargado correctamente');
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Estilos para scroll personalizado */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Estilos para imágenes en galería */
        .gallery-image {
            transition: transform 0.3s ease;
        }

        .gallery-image:hover {
            transform: scale(1.02);
        }
    </style>
</x-layout.default>