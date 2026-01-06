<x-layout.default>
    <div x-data="dashboardTransito()">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    <i class="fas fa-chart-bar mr-2"></i>Dashboard - Repuestos en Tránsito
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Estadísticas y análisis de repuestos en tránsito
                </p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('repuesto-transito.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-list mr-2"></i>Ver Lista
                </a>
                <a href="{{ route('repuesto-transito.reporte') }}" class="btn btn-success">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Reporte
                </a>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total en Tránsito -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total en Tránsito</p>
                        <p class="text-4xl font-bold mt-2">{{ $estadisticas->total_en_transito ?? 0 }}</p>
                        <p class="text-xs opacity-80 mt-1">Artículos activos</p>
                    </div>
                    <div class="text-5xl opacity-80">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>
            
            <!-- Cantidad Total -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Cantidad Total</p>
                        <p class="text-4xl font-bold mt-2">{{ $estadisticas->cantidad_total ?? 0 }}</p>
                        <p class="text-xs opacity-80 mt-1">Unidades totales</p>
                    </div>
                    <div class="text-5xl opacity-80">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
            
            <!-- Promedio Días -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Promedio Días</p>
                        <p class="text-4xl font-bold mt-2">{{ round($estadisticas->promedio_dias ?? 0) }}</p>
                        <p class="text-xs opacity-80 mt-1">Días promedio</p>
                    </div>
                    <div class="text-5xl opacity-80">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
            
            <!-- Máximo Días -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Máximo Días</p>
                        <p class="text-4xl font-bold mt-2">{{ $estadisticas->maximo_dias ?? 0 }}</p>
                        <p class="text-xs opacity-80 mt-1">Días máximo</p>
                    </div>
                    <div class="text-5xl opacity-80">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y Tablas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Repuestos por Área -->
            <div class="panel">
                <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                    <i class="fas fa-chart-pie mr-2"></i>Repuestos por Área
                </h5>
                
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th class="!text-gray-700 dark:!text-gray-300">Área</th>
                                <th class="!text-gray-700 dark:!text-gray-300">Cantidad Repuestos</th>
                                <th class="!text-gray-700 dark:!text-gray-300">Total Artículos</th>
                                <th class="!text-gray-700 dark:!text-gray-300">Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalRepuestos = $por_area->sum('cantidad');
                            @endphp
                            @foreach($por_area as $area)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" 
                                             style="background-color: {{ $loop->index % 2 == 0 ? '#3B82F6' : '#10B981' }}"></div>
                                        {{ $area->area }}
                                    </div>
                                </td>
                                <td>{{ $area->cantidad }}</td>
                                <td>{{ $area->total_articulos }}</td>
                                <td>
                                    @php
                                        $porcentaje = $totalRepuestos > 0 ? ($area->cantidad / $totalRepuestos * 100) : 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                            <div class="bg-primary h-2 rounded-full" 
                                                 style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                        <span class="text-sm">{{ round($porcentaje, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Repuestos Recientes -->
            <div class="panel">
                <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                    <i class="fas fa-clock mr-2"></i>Repuestos Recientes
                </h5>
                
                <div class="space-y-4">
                    @foreach($recientes as $reciente)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center mr-3">
                                <i class="fas fa-box text-primary"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $reciente->articulo }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $reciente->solicitud }} • {{ $reciente->tecnico }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($reciente->created_at)->diffForHumans() }}
                            </p>
                            <a href="{{ route('repuesto-transito.show', $reciente->idOrdenesArticulos) }}" 
                               class="text-primary text-sm hover:underline">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Alertas de Tiempo Excedido -->
        <div class="panel mb-6">
            <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                <i class="fas fa-exclamation-circle mr-2"></i>Alertas de Tiempo Excedido
            </h5>
            
            @php
                $alertas = DB::table('ordenesarticulos as oa')
                    ->select([
                        'oa.idOrdenesArticulos',
                        'a.nombre as articulo',
                        'so.codigo as solicitud',
                        'oa.created_at',
                        DB::raw('DATEDIFF(NOW(), oa.created_at) as dias')
                    ])
                    ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
                    ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
                    ->whereNull('oa.fechaUsado')
                    ->whereNull('oa.fechaSinUsar')
                    ->where('oa.estado', 1)
                    ->having('dias', '>', 7)
                    ->orderBy('dias', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            @if($alertas->count() > 0)
            <div class="space-y-3">
                @foreach($alertas as $alerta)
                <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $alerta->articulo }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $alerta->solicitud }} • 
                                {{ \Carbon\Carbon::parse($alerta->created_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="badge bg-red-600 text-white">{{ $alerta->dias }} días</span>
                        <div class="mt-2">
                            <a href="{{ route('repuesto-transito.show', $alerta->idOrdenesArticulos) }}" 
                               class="text-primary text-sm hover:underline">
                                <i class="fas fa-eye mr-1"></i>Ver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                <p class="text-gray-600 dark:text-gray-400">No hay alertas críticas en este momento</p>
            </div>
            @endif
        </div>

        <!-- Filtros por Tiempo -->
        <div class="panel">
            <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                <i class="fas fa-filter mr-2"></i>Filtros por Tiempo en Tránsito
            </h5>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- 0-3 días -->
                <div class="text-center p-6 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2">
                        {{ $repuestos_0_3 ?? 0 }}
                    </div>
                    <p class="text-gray-800 dark:text-white font-medium">0-3 días</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tiempo normal</p>
                </div>
                
                <!-- 4-7 días -->
                <div class="text-center p-6 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="text-4xl font-bold text-yellow-600 dark:text-yellow-400 mb-2">
                        {{ $repuestos_4_7 ?? 0 }}
                    </div>
                    <p class="text-gray-800 dark:text-white font-medium">4-7 días</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Atención requerida</p>
                </div>
                
                <!-- Más de 7 días -->
                <div class="text-center p-6 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">
                        {{ $repuestos_mas_7 ?? 0 }}
                    </div>
                    <p class="text-gray-800 dark:text-white font-medium">Más de 7 días</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Acción inmediata</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function dashboardTransito() {
        return {
            // Puedes agregar funciones específicas del dashboard aquí
        };
    }
    </script>
    @endpush
</x-layout.default>