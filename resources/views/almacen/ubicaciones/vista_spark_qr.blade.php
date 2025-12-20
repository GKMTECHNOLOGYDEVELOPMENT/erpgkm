<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ubicación {{ $ubicacion['codigo_unico'] ?? $ubicacion['codigo'] }} - Almacén</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --color-disponible: #10b981;
            --color-parcial: #f59e0b;
            --color-ocupado: #f97316;
            --color-lleno: #ef4444;
            --color-spark: #3b82f6;
        }

        /* Estilos mejorados */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-header {
            position: relative;
            padding-left: 1rem;
        }

        .section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 70%;
            width: 4px;
            border-radius: 2px;
        }

        .inventory-section::before {
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
        }

        .capacity-section::before {
            background: linear-gradient(to bottom, #10b981, #059669);
        }

        .info-section::before {
            background: linear-gradient(to bottom, #f59e0b, #d97706);
        }

        .status-badge {
            position: relative;
            overflow: hidden;
        }

        .status-badge::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.3) 50%, transparent 70%);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .qr-frame {
            background: linear-gradient(45deg, #f8fafc 25%, #f1f5f9 25%, #f1f5f9 50%, #f8fafc 50%, #f8fafc 75%, #f1f5f9 75%, #f1f5f9);
            background-size: 20px 20px;
            animation: movePattern 20s linear infinite;
        }

        @keyframes movePattern {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 40px 40px;
            }
        }

        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .print-optimized {
            break-inside: avoid;
        }

        @media (max-width: 768px) {
            .mobile-stack {
                display: flex;
                flex-direction: column;
            }

            .mobile-full {
                width: 100% !important;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .print-header {
                position: fixed;
                top: 0;
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 font-sans">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        @if ($error)
            <!-- Error State -->
            <div class="min-h-screen flex items-center justify-center">
                <div class="glass-panel rounded-2xl p-8 max-w-md w-full text-center shadow-xl">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ $mensaje }}</h2>
                    <p class="text-gray-600 mb-6">La ubicación solicitada no existe o no está disponible.</p>
                    <div class="bg-gray-100 rounded-lg p-4 mb-6">
                        <code class="text-gray-800 font-mono">{{ $codigo }}</code>
                    </div>
                    <button onclick="window.history.back()"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Volver atrás
                    </button>
                </div>
            </div>
        @else
            <!-- Layout Principal -->
            <div class="space-y-8">
                <!-- Header Principal Rediseñado -->
                <div
                    class="print-header bg-gradient-to-r from-blue-700 via-blue-600 to-blue-800 text-white rounded-3xl p-6 md:p-8 shadow-2xl">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="bg-white/20 rounded-xl p-3 mr-4">
                                    <i class="fas fa-location-dot text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold">
                                        Ubicación: {{ $ubicacion['codigo_unico'] ?? $ubicacion['codigo'] }}
                                    </h1>
                                    <p class="text-blue-100 mt-2">Detalles completos del espacio de almacenamiento</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mt-4">
                                <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl flex items-center">
                                    <i class="fas fa-warehouse mr-2"></i>
                                    <span class="font-medium">{{ $ubicacion['rack_nombre'] }}</span>
                                </div>
                                <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span class="font-medium">{{ $ubicacion['sede'] }}</span>
                                </div>
                                <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl flex items-center">
                                    <i class="fas fa-layer-group mr-2"></i>
                                    <span>Nivel {{ $ubicacion['nivel'] }}</span>
                                </div>
                                <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl flex items-center">
                                    <i class="fas fa-arrows-alt-h mr-2"></i>
                                    <span>Posición {{ $ubicacion['posicion'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch gap-4">
                            <!-- Estado -->
                            @php
                                $estadoConfig = match ($ubicacion['estado_ocupacion']) {
                                    'disponible' => ['bg' => 'bg-emerald-500', 'icon' => 'fa-check-circle'],
                                    'parcial' => ['bg' => 'bg-amber-500', 'icon' => 'fa-hourglass-half'],
                                    'ocupado' => ['bg' => 'bg-orange-500', 'icon' => 'fa-box-open'],
                                    'lleno' => ['bg' => 'bg-rose-500', 'icon' => 'fa-exclamation-triangle'],
                                    default => ['bg' => 'bg-gray-500', 'icon' => 'fa-question-circle'],
                                };
                            @endphp
                            <div class="bg-gray-600 rounded-xl p-4 flex items-center gap-4 border border-blue-500/30">
                                <div class="{{ $estadoConfig['bg'] }} status-badge w-4 h-4 rounded-full"></div>
                                <div>
                                    <p class="text-sm text-white/70">Estado actual</p>
                                    <p class="text-lg font-bold">{{ ucfirst($ubicacion['estado_ocupacion']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Estadísticas Rápidas - Solo 2 items -->
                <div class="grid grid-cols-1 xs:grid-cols-2 gap-3 sm:gap-4 print-optimized">
                    <!-- Items -->
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 border-l-4 border-blue-500 shadow-md sm:shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0 mr-3">
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Items totales</p>
                                <div class="flex items-baseline">
                                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800">
                                        {{ $ubicacion['total_items'] }}
                                    </p>
                                    <span class="ml-2 text-xs sm:text-sm text-gray-400">items</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-xl">
                                    <i class="fas fa-boxes-stacked text-xl sm:text-2xl text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-2 text-blue-400"></i>
                                <span class="truncate">En esta ubicación</span>
                            </div>
                        </div>
                    </div>

                    <!-- Capacidad -->
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 border-l-4 border-emerald-500 shadow-md sm:shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0 mr-3">
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Ocupación</p>
                                <div class="flex items-baseline">
                                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800">
                                        @if ($ubicacion['capacidad_maxima'])
                                            {{ $ubicacion['cantidad_total'] }}
                                            <span class="text-lg sm:text-xl font-normal text-gray-500">
                                                /{{ $ubicacion['capacidad_maxima'] }}
                                            </span>
                                        @else
                                            {{ $ubicacion['cantidad_total'] }}
                                        @endif
                                    </p>
                                </div>
                                @if ($ubicacion['capacidad_maxima'])
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                            <span>Porcentaje</span>
                                            <span class="font-semibold text-emerald-600">
                                                {{ round(($ubicacion['cantidad_total'] / $ubicacion['capacidad_maxima']) * 100, 1) }}%
                                            </span>
                                        </div>
                                        <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500"
                                                style="width: {{ min(($ubicacion['cantidad_total'] / $ubicacion['capacidad_maxima']) * 100, 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-3 rounded-xl">
                                    <i class="fas fa-chart-pie text-xl sm:text-2xl text-emerald-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-chart-bar mr-2 text-emerald-400"></i>
                                @if ($ubicacion['capacidad_maxima'])
                                    <span class="truncate">{{ $ubicacion['estado_ocupacion'] }}</span>
                                @else
                                    <span class="truncate">Sin límite de capacidad</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido Principal en Columnas -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Columna Izquierda - Inventario -->
                    <div class="lg:col-span-2 space-y-6 print-optimized">
                        <!-- Productos -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div
                                class="section-header inventory-section px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-3 rounded-xl mr-4">
                                            <i class="fas fa-boxes text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-800">Inventario Actual</h2>
                                            <p class="text-sm text-gray-600">Productos almacenados en esta ubicación</p>
                                        </div>
                                    </div>
                                    <span
                                        class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-4 py-2 rounded-full font-semibold">
                                        {{ count($ubicacion['productos']) }} items
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                @if (count($ubicacion['productos']) > 0)
                                    <!-- Vista de Tabla para Desktop -->
                                    <div class="hidden md:block">
                                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Producto</th>
                                                        <th scope="col"
                                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Tipo</th>
                                                        <th scope="col"
                                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Cantidad</th>
                                                        <th scope="col"
                                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Cliente</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    @foreach ($ubicacion['productos'] as $producto)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-6 py-4">
                                                                <div class="flex items-center">
                                                                    <div
                                                                        class="flex-shrink-0 h-12 w-12 rounded-xl flex items-center justify-center mr-4
                                                        {{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'bg-purple-100' : ($producto['es_repuesto'] ? 'bg-teal-100' : 'bg-green-100') }}">
                                                                        <i
                                                                            class="{{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'fas fa-shield-alt text-purple-600' : ($producto['es_repuesto'] ? 'fas fa-cogs text-teal-600' : 'fas fa-box text-green-600') }}"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="font-semibold text-gray-900">
                                                                            {{ $producto['nombre'] }}</div>
                                                                        <div class="text-sm text-gray-500">
                                                                            {{ $producto['categoria'] ?? 'Sin categoría' }}
                                                                        </div>
                                                                        @if (isset($producto['codigo_repuesto']) && $producto['codigo_repuesto'])
                                                                            <div class="text-xs text-gray-400">Código:
                                                                                {{ $producto['codigo_repuesto'] }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                @if ($producto['tipo_articulo'] == 'CUSTODIA')
                                                                    <span
                                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                                        Custodia
                                                                    </span>
                                                                @elseif($producto['es_repuesto'])
                                                                    <span
                                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800">
                                                                        Repuesto
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                                        {{ $producto['tipo_articulo'] ?? 'General' }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <div
                                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-gray-900 text-white font-bold">
                                                                    {{ $producto['cantidad'] }}
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <span
                                                                    class="{{ $producto['cliente_general_nombre'] != 'Sin cliente' ? 'text-purple-700 font-semibold' : 'text-gray-500' }}">
                                                                    {{ $producto['cliente_general_nombre'] ?? 'Sin cliente' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Vista de Cards para Mobile -->
                                    <div class="md:hidden space-y-4">
                                        @foreach ($ubicacion['productos'] as $producto)
                                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover-lift">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-start">
                                                        <div
                                                            class="flex-shrink-0 h-12 w-12 rounded-lg flex items-center justify-center mr-4
                                            {{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'bg-purple-100' : ($producto['es_repuesto'] ? 'bg-teal-100' : 'bg-green-100') }}">
                                                            <i
                                                                class="{{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'fas fa-shield-alt text-purple-600' : ($producto['es_repuesto'] ? 'fas fa-cogs text-teal-600' : 'fas fa-box text-green-600') }}"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900">
                                                                {{ $producto['nombre'] }}</h4>
                                                            <div class="text-sm text-gray-600 mt-1">
                                                                <span
                                                                    class="{{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'text-purple-600' : ($producto['es_repuesto'] ? 'text-teal-600' : 'text-gray-600') }}">
                                                                    {{ $producto['tipo_articulo'] ?? 'General' }}
                                                                </span>
                                                                • {{ $producto['categoria'] ?? 'Sin categoría' }}
                                                            </div>
                                                            @if (isset($producto['codigo_repuesto']) && $producto['codigo_repuesto'])
                                                                <div class="text-xs text-gray-400 mt-1">Código:
                                                                    {{ $producto['codigo_repuesto'] }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col items-end">
                                                        <div
                                                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-gray-900 text-white font-bold">
                                                            {{ $producto['cantidad'] }}
                                                        </div>
                                                        <span class="text-xs text-gray-500 mt-2">
                                                            {{ $producto['cliente_general_nombre'] ?? 'Sin cliente' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <div
                                            class="mx-auto h-20 w-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Ubicación vacía</h3>
                                        <p class="text-gray-500">No hay productos almacenados actualmente.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Cajas (si existen) -->
                        @if (count($ubicacion['cajas']) > 0)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden print-optimized">
                                <div
                                    class="section-header capacity-section px-6 py-5 bg-gradient-to-r from-cyan-50 to-teal-50 border-b">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-cyan-500 to-teal-500 p-3 rounded-xl mr-4">
                                            <i class="fas fa-archive text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-800">Cajas Almacenadas</h2>
                                            <p class="text-sm text-gray-600">Contenedores en esta ubicación</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($ubicacion['cajas'] as $caja)
                                            <div
                                                class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-5 hover-lift">
                                                <div class="flex justify-between items-start mb-4">
                                                    <div class="flex items-center">
                                                        <div class="bg-cyan-100 p-3 rounded-lg mr-3">
                                                            <i class="fas fa-archive text-cyan-600"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="font-bold text-gray-900">
                                                                {{ $caja['caja']['nombre'] }}</h3>
                                                            <p class="text-xs text-gray-500">ID:
                                                                {{ $caja['caja']['id'] ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="text-xs font-semibold px-2 py-1 rounded-full 
                                        {{ $caja['caja']['es_custodia'] ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ $caja['caja']['es_custodia'] ? 'Custodia' : 'Caja' }}
                                                    </span>
                                                </div>

                                                @if ($caja['contenido']['nombre'] != 'Vacía')
                                                    <div
                                                        class="mb-4 p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-100">
                                                        <div class="text-xs text-gray-500 mb-1">Contenido</div>
                                                        <div class="font-semibold text-gray-900">
                                                            {{ $caja['contenido']['nombre'] }}</div>
                                                        @if (isset($caja['contenido']['codigo_repuesto']) && $caja['contenido']['codigo_repuesto'])
                                                            <div class="text-xs text-gray-400 mt-1">
                                                                {{ $caja['contenido']['codigo_repuesto'] }}</div>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="mb-4">
                                                    <div class="flex justify-between text-sm text-gray-700 mb-2">
                                                        <span>Capacidad</span>
                                                        <span
                                                            class="font-bold">{{ $caja['caja']['porcentaje_llenado'] }}%</span>
                                                    </div>
                                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                        <div class="h-full bg-gradient-to-r from-cyan-400 to-cyan-500"
                                                            style="width: {{ $caja['caja']['porcentaje_llenado'] }}%">
                                                        </div>
                                                    </div>
                                                    <div class="text-xs text-gray-500 text-center mt-2">
                                                        {{ $caja['caja']['cantidad_actual'] }}/{{ $caja['caja']['capacidad'] }}
                                                        unidades
                                                    </div>
                                                </div>

                                                <div
                                                    class="flex justify-between items-center pt-4 border-t border-gray-200">
                                                    <div class="text-xs text-gray-500">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        {{ date('d/m/Y', strtotime($caja['caja']['fecha_entrada'])) }}
                                                    </div>
                                                    <div class="text-xs text-cyan-600 font-medium">
                                                        <i class="fas fa-box-open mr-1"></i>
                                                        {{ $caja['caja']['cantidad_actual'] }} items
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Sección en Fila: Información del Rack + Acceso Rápido -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print-optimized">
                            <!-- Panel de Información del Rack -->
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                                <div
                                    class="section-header info-section px-6 py-5 bg-gradient-to-r from-amber-50 to-orange-50 border-b">
                                    <h3 class="text-lg font-bold text-gray-800">Información del Rack</h3>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                            <div class="flex items-center">
                                                <i class="fas fa-warehouse text-gray-400 mr-3"></i>
                                                <span class="text-gray-600">Nombre</span>
                                            </div>
                                            <span
                                                class="font-semibold text-gray-900">{{ $ubicacion['rack_nombre'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-gray-400 mr-3"></i>
                                                <span class="text-gray-600">Sede</span>
                                            </div>
                                            <span class="font-semibold text-gray-900">{{ $ubicacion['sede'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                            <div class="flex items-center">
                                                <i class="fas fa-cube text-gray-400 mr-3"></i>
                                                <span class="text-gray-600">Tipo</span>
                                            </div>
                                            <span
                                                class="font-semibold text-gray-900">{{ $ubicacion['tipo_rack'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                            <div class="flex items-center">
                                                <i class="fas fa-expand-alt text-gray-400 mr-3"></i>
                                                <span class="text-gray-600">Dimensiones</span>
                                            </div>
                                            <span
                                                class="font-semibold text-gray-900">{{ $ubicacion['filas'] ?? '?' }}×{{ $ubicacion['columnas'] ?? '?' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-history text-gray-400 mr-3"></i>
                                                <span class="text-gray-600">Actualizado</span>
                                            </div>
                                            <span class="font-semibold text-gray-900">
                                                {{ $ubicacion['fecha_actualizacion'] ? date('d/m/Y', strtotime($ubicacion['fecha_actualizacion'])) : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel de Código QR -->
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                                <div class="px-6 py-5 bg-gradient-to-r from-indigo-50 to-purple-50 border-b">
                                    <h3 class="text-lg font-bold text-gray-800">Acceso Rápido</h3>
                                    <p class="text-sm text-gray-600">Escanea para acceder a esta ubicación</p>
                                </div>
                                <div class="p-6">
                                    <div class="qr-frame rounded-xl p-4 text-center mb-4">
                                        <img src="{{ url('/almacen/ubicaciones/qr/' . ($ubicacion['codigo_unico'] ?? $ubicacion['codigo']) . '?ruta=spark') }}"
                                            alt="QR Code" class="w-48 h-48 mx-auto">
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                            <code
                                                class="text-sm font-mono text-gray-800">{{ $ubicacion['codigo_unico'] ?? $ubicacion['codigo'] }}</code>
                                        </div>
                                        <a href="{{ url('/almacen/ubicaciones/qr/' . ($ubicacion['codigo_unico'] ?? $ubicacion['codigo'])) }}?download=1&ruta=spark"
                                            class="block w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-3 rounded-xl hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 no-print text-center">
                                            <i class="fas fa-download mr-2"></i>Descargar QR
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha - Información y Acciones -->
                    <div class="space-y-6 print-optimized">
                        <!-- Panel de Estado y Capacidad -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div
                                class="section-header capacity-section px-6 py-5 bg-gradient-to-r from-emerald-50 to-green-50 border-b">
                                <h3 class="text-lg font-bold text-gray-800">Estado y Capacidad</h3>
                            </div>
                            <div class="p-6">
                                <!-- Barra de Capacidad -->
                                @if ($ubicacion['capacidad_maxima'])
                                    <div class="mb-6">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-sm font-medium text-gray-700">Ocupación actual</span>
                                            <span class="text-sm font-bold text-gray-800">
                                                {{ round(($ubicacion['cantidad_total'] / $ubicacion['capacidad_maxima']) * 100, 1) }}%
                                            </span>
                                        </div>
                                        <div
                                            class="h-3 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full overflow-hidden">
                                            @php
                                                $porcentaje = min(
                                                    ($ubicacion['cantidad_total'] / $ubicacion['capacidad_maxima']) *
                                                        100,
                                                    100,
                                                );
                                                $gradient = match ($ubicacion['estado_ocupacion']) {
                                                    'disponible' => 'from-emerald-400 to-emerald-500',
                                                    'parcial' => 'from-amber-400 to-amber-500',
                                                    'ocupado' => 'from-orange-400 to-orange-500',
                                                    'lleno' => 'from-rose-400 to-rose-500',
                                                    default => 'from-gray-400 to-gray-500',
                                                };
                                            @endphp
                                            <div class="h-full bg-gradient-to-r {{ $gradient }}"
                                                style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                                            <span>{{ $ubicacion['cantidad_total'] }} unidades</span>
                                            <span>{{ $ubicacion['capacidad_maxima'] }} máximo</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Detalles de Capacidad -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                        <div class="text-2xl font-bold text-blue-600">
                                            {{ $ubicacion['cantidad_total'] }}</div>
                                        <div class="text-xs text-blue-700 font-medium mt-1">Actual</div>
                                    </div>
                                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                        <div class="text-2xl font-bold text-gray-600">
                                            {{ $ubicacion['capacidad_maxima'] ?? '∞' }}</div>
                                        <div class="text-xs text-gray-700 font-medium mt-1">Máximo</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel de Acciones Rápidas -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden no-print">
                            <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                                <h3 class="text-lg font-bold text-gray-800">Acciones</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <a href="{{ route('almacen.ubicaciones.detalle', ['rack' => $ubicacion['rack_nombre'], 'sede' => $ubicacion['sede']]) }}"
                                        class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300 group">
                                        <div class="flex items-center">
                                            <div
                                                class="bg-blue-500 p-3 rounded-lg mr-4 group-hover:bg-blue-600 transition-colors">
                                                <i class="fas fa-warehouse text-white"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800">Ver Rack Completo</div>
                                                <div class="text-sm text-gray-600">Todas las ubicaciones del rack</div>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie de Página -->
                <footer class="mt-12 pt-8 border-t border-gray-200 text-center no-print">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-bolt text-blue-500 mr-2"></i>
                            Sistema de Gestión de Almacén • Vista Spark QR
                        </p>
                        <div class="text-sm text-gray-400">
                            Generado el {{ date('d/m/Y H:i:s') }}
                        </div>
                    </div>
                </footer>
            </div>
        @endif
    </div>

    <script>
        // Funcionalidades interactivas
        document.addEventListener('DOMContentLoaded', function() {
            // Mejorar experiencia de hover en dispositivos táctiles
            const hoverElements = document.querySelectorAll('.hover-lift');
            hoverElements.forEach(el => {
                el.addEventListener('touchstart', function() {
                    this.classList.add('active');
                });
                el.addEventListener('touchend', function() {
                    this.classList.remove('active');
                });
            });

            // Copiar código QR al hacer clic
            const qrCode = document.querySelector('code');
            if (qrCode) {
                qrCode.addEventListener('click', function() {
                    const text = this.textContent;
                    navigator.clipboard.writeText(text).then(() => {
                        const original = this.textContent;
                        this.textContent = '¡Copiado!';
                        this.style.color = '#10b981';
                        setTimeout(() => {
                            this.textContent = original;
                            this.style.color = '';
                        }, 1500);
                    });
                });
                qrCode.style.cursor = 'pointer';
                qrCode.title = 'Click para copiar el código';
            }

            // Optimizar para impresión
            window.addEventListener('beforeprint', () => {
                document.body.classList.add('printing');
            });
            window.addEventListener('afterprint', () => {
                document.body.classList.remove('printing');
            });
        });
    </script>
</body>

</html>
