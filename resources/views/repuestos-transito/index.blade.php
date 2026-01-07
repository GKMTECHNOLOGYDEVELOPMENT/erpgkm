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
                                        Ver Detalles
                                    </button>
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

    <!-- MODAL DE DETALLES SIMPLIFICADO -->
    <div x-data="modalDetails" x-cloak>
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
                    <!-- Header -->
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

                    <!-- Contenido -->
                    <div class="p-5">
                        <!-- Loading -->
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
                                        <div class="space-y-2">
                                            <p class="text-sm">
                                                <span class="font-medium">Principal:</span>
                                                <span class="ml-2" x-text="details.codigo_repuesto || 'N/A'"></span>
                                            </p>
                                            <p class="text-sm" x-show="details.sku">
                                                <span class="font-medium">SKU:</span>
                                                <span class="ml-2" x-text="details.sku"></span>
                                            </p>
                                            <p class="text-sm" x-show="details.codigo_barras">
                                                <span class="font-medium">Código Barras:</span>
                                                <span class="ml-2" x-text="details.codigo_barras"></span>
                                            </p>
                                        </div>
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
                                                <span class="font-medium">Código Solicitud:</span>
                                                <span class="ml-2" x-text="details.codigo_solicitud || 'N/A'"></span>
                                            </p>
                                            <p class="text-sm">
                                                <span class="font-medium">Solicitante:</span>
                                                <span class="ml-2" x-text="details.solicitante || 'N/A'"></span>
                                            </p>
                                            <p class="text-sm">
                                                <span class="font-medium">Fecha Solicitud:</span>
                                                <span class="ml-2" x-text="formatDate(details.fechaCreacion)"></span>
                                            </p>
                                            <p class="text-sm" x-show="details.fecharequerida">
                                                <span class="font-medium">Fecha Requerida:</span>
                                                <span class="ml-2" x-text="formatDate(details.fecharequerida)"></span>
                                            </p>
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
                                </div>
                            </div>

                            <!-- Categorización -->
                            <div x-show="details.subcategoria || details.modelo || details.marca" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2">Categorización</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div x-show="details.subcategoria" class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Subcategoría</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white" x-text="details.subcategoria"></p>
                                    </div>
                                    <div x-show="details.modelo" class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                        <p class="text-xs font-medium text-blue-500 dark:text-blue-400 mb-1">Modelo</p>
                                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300" x-text="details.modelo"></p>
                                    </div>
                                    <div x-show="details.marca" class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                                        <p class="text-xs font-medium text-green-500 dark:text-green-400 mb-1">Marca</p>
                                        <p class="text-sm font-semibold text-green-800 dark:text-green-300" x-text="details.marca"></p>
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
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end items-center px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" class="btn btn-outline-danger" @click="toggle">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Simplificado -->
    <script>
        // Función global para abrir modal
        window.openDetailsModal = function(id) {
            console.log('Abriendo detalles para ID:', id);
            const modal = document.querySelector('[x-data="modalDetails"]');
            if (modal) {
                const instance = Alpine.$data(modal);
                instance.toggle();
                instance.loadDetails(id);
            }
        };

        // Inicializar Alpine cuando esté listo
        document.addEventListener('alpine:init', () => {
            // Modal de detalles
            Alpine.data('modalDetails', () => ({
                open: false,
                loading: false,
                dataLoaded: false,
                error: false,
                modalTitle: 'Detalles del Repuesto',
                modalSubtitle: '',
                details: {},
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
                    this.details = {};
                    this.currentId = null;
                },

                async loadDetails(id) {
                    this.currentId = id;
                    this.loading = true;
                    this.dataLoaded = false;

                    try {
                        const response = await fetch(`/repuestos-transito/${id}/detalles`);
                        const data = await response.json();

                        if (data.success) {
                            this.details = data.data;
                            this.modalTitle = data.data.nombre_repuesto || 'Detalles del Repuesto';
                            this.modalSubtitle = `Código: ${data.data.codigo_repuesto || 'N/A'} | Estado: ${this.getStatusText(data.data.estado)}`;
                            this.dataLoaded = true;
                        } else {
                            this.error = true;
                            alert(data.message || 'Error al cargar detalles');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error = true;
                        alert('Error de conexión');
                    } finally {
                        this.loading = false;
                    }
                },

                getStatusClass(status) {
                    switch (status) {
                        case 'usado': return 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400';
                        case 'no_usado': return 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400';
                        case 'pendiente': return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
                        default: return 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-400';
                    }
                },

                getStatusIcon(status) {
                    switch (status) {
                        case 'usado': return '✅';
                        case 'no_usado': return '❌';
                        case 'pendiente': return '🔄';
                        default: return '❓';
                    }
                },

                getStatusText(status) {
                    switch (status) {
                        case 'usado': return 'Usado';
                        case 'no_usado': return 'No Usado';
                        case 'pendiente': return 'En Tránsito';
                        default: return 'Desconocido';
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
                }
            }));
        });

        // Verificar que AlpineJS esté cargado
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Página de repuestos en tránsito cargada');
            console.log('openDetailsModal disponible:', typeof window.openDetailsModal === 'function');
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
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
    </style>
</x-layout.default>