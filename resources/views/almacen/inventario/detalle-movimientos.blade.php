<x-layout.default>
    @php
        $fechaFormateada = \Carbon\Carbon::parse($kardex->fecha)->format('d/m/Y');
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
        $nombreMes = $meses[$month] ?? 'Mes';

        // Obtener el mes y año actual para el contador de registros
        $mesActual = now()->month;
        $anoActual = now()->year;

        // Contar registros de entrada (compra o entrada_proveedor) de este mes
        $totalRegistrosEntradaMes = $movimientos
            ->filter(function ($movimiento) use ($mesActual, $anoActual) {
                $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
                return ($movimiento->tipo_ingreso == 'compra' || $movimiento->tipo_ingreso == 'entrada_proveedor') &&
                    $fechaMovimiento->month == $mesActual &&
                    $fechaMovimiento->year == $anoActual;
            })
            ->count();
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
        <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
            <!-- Header Mejorado -->
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                    <div class="p-2 bg-primary rounded-xl shadow-lg">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3a9 9 0 100 18 9 9 0 000-18zM11 3v9h9" />
                        </svg>
                    </div>

                    <div>
                        <h1
                            class="text-xl sm:text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            MOVIMIENTOS DEL MES - {{ $nombreMes }} {{ $year }}
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Detalle completo de movimientos mensuales</p>
                    </div>
                </div>

                <div class="panel backdrop-blur-sm rounded-2xl p-4 sm:p-6 shadow-sm border border-white/50">
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                        Todos los movimientos del mes que generaron el kardex para
                        <span
                            class="font-semibold text-white bg-dark px-2 py-1 rounded-lg">{{ $articulo->nombre }}</span>
                        del cliente <span
                            class="font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-lg">{{ $cliente->descripcion ?? 'Cliente' }}</span>
                    </p>
                    <div class="flex items-center gap-2 mt-3 text-xs sm:text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Registro del kardex: {{ $fechaFormateada }}</span>
                    </div>
                </div>
            </div>
            <!-- Botón de volver Mejorado -->
            <div class="mb-6 sm:mt-8 flex justify-center sm:justify-end">
                <a href="#"
                    class="btn btn-danger inline-flex items-center px-4 sm:px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al Kardex
                </a>
            </div>
            <!-- Resumen del Kardex Mejorado -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Tarjetas principales -->
                <div class="lg:col-span-2 grid grid-cols-2 gap-3 sm:gap-4">
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-4 sm:p-6 border border-primary 
         shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 ease-out">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-primary rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="text-primary font-semibold text-sm">Entradas Totales</div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-primary">{{ $kardex->unidades_entrada }}</div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-4 sm:p-6 border border-danger 
            shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 ease-out">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-danger rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 12H4" />
                                </svg>
                            </div>
                            <div class="text-danger font-semibold text-sm">Salidas Totales</div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-danger">{{ $kardex->unidades_salida }}</div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-4 sm:p-6 border border-success 
            shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 ease-out">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-success rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="text-success font-semibold text-sm">Inventario Inicial</div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-success">{{ $kardex->inventario_inicial }}</div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-4 sm:p-6 border border-secondary 
            shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 ease-out">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-secondary rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-secondary font-semibold text-sm">Inventario Final</div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-secondary">{{ $kardex->inventario_actual }}
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-50 to-teal-100 rounded-2xl p-4 sm:p-6 border border-dark
         shadow-sm hover:shadow-lg hover:brightness-105 hover:-translate-y-1 
         transition-all duration-300 ease-out hover:animate-pulse">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-dark rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-dark font-semibold text-sm">Entradas este Mes</div>
                            <div class="text-xs text-dark">Compras y entradas de proveedor</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-dark mb-2">{{ $totalRegistrosEntradaMes }}</div>
                        <div class="text-xs text-dark bg-emerald-100 px-2 py-1 rounded-full">
                            Registros contabilizados
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tabla de movimientos Mejorada -->
            <div
                class="panel rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden border border-gray-200/50 bg-white/80 backdrop-blur-sm">
                <!-- Header de la tabla - Mejorado -->
                <div
                    class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-900 px-4 sm:px-6 py-4 sm:py-5 relative overflow-hidden">

                    <!-- Efecto glow sutil -->
                    <div class="absolute inset-0">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/30 rounded-full blur-2xl">
                        </div>
                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-indigo-500/30 rounded-full blur-2xl">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative z-10">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm border border-white/10">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-primary drop-shadow-sm">Movimientos del
                                    Mes
                                </h2>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-white/90 text-xs sm:text-sm font-medium bg-white/10 px-2 py-1 rounded-full">
                                        {{ $movimientos->count() }} registros encontrados
                                    </span>
                                    <span class="w-1 h-1 bg-white/40 rounded-full"></span>
                                    <span class="text-white/70 text-xs">Última actualización:
                                        {{ now()->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-white/5 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/10">
                            <div class="text-right">
                                <span class="block text-white/90 text-sm font-semibold">{{ $nombreMes }}
                                    {{ $year }}</span>
                                <span class="text-white/60 text-xs">Período activo</span>
                            </div>
                            <div
                                class="w-8 h-8 bg-primary/20 rounded-lg flex items-center justify-center border border-primary/30">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vista móvil con tarjetas - Mejorada -->
                <div class="block sm:hidden p-4 space-y-3 bg-gradient-to-br from-gray-50/50 to-white/30">
                    @forelse($movimientos as $movimiento)
                        @php
                            $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
                            $esMismoDia =
                                $fechaMovimiento->format('Y-m-d') ===
                                \Carbon\Carbon::parse($kardex->fecha)->format('Y-m-d');
                            $esCompra = $movimiento->tipo_ingreso == 'compra';
                        @endphp

                        <div class="group relative">
                            <!-- Efecto de tarjeta flotante -->
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-primary/5 to-blue-500/5 rounded-xl transform group-hover:scale-105 transition-transform duration-300 opacity-0 group-hover:opacity-100">
                            </div>

                            <div
                                class="relative bg-white rounded-xl p-4 border border-gray-200/60 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 {{ $esMismoDia ? 'border-yellow-300 bg-yellow-50/80 ring-1 ring-yellow-200' : '' }}">
                                <!-- Header de la tarjeta -->
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="p-1.5 rounded-lg {{ $esCompra ? 'bg-green-100' : 'bg-blue-100' }}">
                                            <svg class="w-3 h-3 {{ $esCompra ? 'text-green-600' : 'text-blue-600' }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $esCompra ? 'M12 6v6m0 0v6m0-6h6m-6 0H6' : 'M13 16h-1v-4h-1m1-4h.01' }}" />
                                            </svg>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $esCompra ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                            {{ strtoupper($movimiento->tipo_ingreso) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full font-medium">Día
                                            {{ $fechaMovimiento->format('d') }}</span>
                                        <div
                                            class="w-2 h-2 {{ $esCompra ? 'bg-green-400' : 'bg-blue-400' }} rounded-full">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenido de la tarjeta -->
                                <div class="space-y-2.5">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 font-medium">Fecha/Hora:</span>
                                        <span
                                            class="text-sm font-semibold text-gray-800">{{ $fechaMovimiento->format('d/m/Y H:i') }}</span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 font-medium">Cantidad:</span>
                                        <span
                                            class="text-sm font-bold {{ $esCompra ? 'text-green-700' : 'text-blue-700' }} bg-{{ $esCompra ? 'green' : 'blue' }}-50 px-2 py-1 rounded-lg">
                                            {{ $movimiento->cantidad }} unidades
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 font-medium">Origen:</span>
                                        <span
                                            class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">ID:{{ $movimiento->ingreso_id }}</span>
                                    </div>

                                    @if ($movimiento->compra_id)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 font-medium">Compra ID:</span>
                                            <span
                                                class="text-sm font-mono text-gray-700">{{ $movimiento->compra_id }}</span>
                                        </div>
                                    @endif

                                    @if ($esMismoDia)
                                        <div
                                            class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-lg p-2 mt-2">
                                            <span
                                                class="text-xs text-yellow-800 font-semibold flex items-center gap-2">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                                <span>Día del corte del kardex - Registro principal</span>
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Indicador de estado -->
                                <div class="absolute top-3 right-3">
                                    <div
                                        class="w-2 h-2 {{ $esCompra ? 'bg-green-400' : 'bg-blue-400' }} rounded-full animate-pulse">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 px-4">
                            <div class="max-w-md mx-auto">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay movimientos registrados
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">No se encontraron registros para el período
                                    seleccionado</p>
                                <button
                                    class="text-xs text-primary font-medium bg-primary/10 px-3 py-1.5 rounded-full hover:bg-primary/20 transition-colors">
                                    Actualizar búsqueda
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Vista desktop con tabla - Mejorada -->
                <div class="hidden sm:block overflow-x-auto bg-gradient-to-br from-gray-50/30 to-white/20">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-slate-100/80 border-b border-gray-200/60">
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Tipo Movimiento
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Fecha/Hora
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Cantidad
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Origen
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Día
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/40">
                            @forelse($movimientos as $movimiento)
                                @php
                                    $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
                                    $esMismoDia =
                                        $fechaMovimiento->format('Y-m-d') ===
                                        \Carbon\Carbon::parse($kardex->fecha)->format('Y-m-d');
                                    $esCompra = $movimiento->tipo_ingreso == 'compra';
                                @endphp

                                <tr
                                    class="group hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-300 {{ $esMismoDia ? 'bg-yellow-50/40 border-l-4 border-warning' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="p-2 rounded-lg {{ $esCompra ? 'bg-green-100' : 'bg-blue-100' }}">
                                                <svg class="w-4 h-4 {{ $esCompra ? 'text-green-600' : 'text-blue-600' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="{{ $esCompra ? 'M12 6v6m0 0v6m0-6h6m-6 0H6' : 'M13 16h-1v-4h-1m1-4h.01' }}" />
                                                </svg>
                                            </div>
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $esCompra ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                                {{ strtoupper($movimiento->tipo_ingreso) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $fechaMovimiento->format('d/m/Y H:i:s') }}</span>
                                            @if ($esMismoDia)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 border border-yellow-200 font-medium">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                    Corte
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="text-sm font-bold {{ $esCompra ? 'text-green-700' : 'text-blue-700' }} bg-{{ $esCompra ? 'green' : 'blue' }}-50 px-3 py-1.5 rounded-lg">
                                            {{ $movimiento->cantidad }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">ID:
                                            {{ $movimiento->ingreso_id }}</div>
                                        @if ($movimiento->compra_id)
                                            <div class="text-xs text-gray-500 mt-1 font-mono">Compra:
                                                {{ $movimiento->compra_id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full text-sm font-bold text-gray-700 shadow-sm">
                                            {{ $fechaMovimiento->format('d') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="max-w-md mx-auto">
                                            <div
                                                class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay movimientos
                                                registrados</h3>
                                            <p class="text-sm text-gray-500">No se encontraron registros para el
                                                período seleccionado</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }

        @media (max-width: 640px) {
            .btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.8125rem;
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</x-layout.default>
