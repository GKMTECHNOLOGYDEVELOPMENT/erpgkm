<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ubicación {{ $ubicacion['codigo_unico'] ?? $ubicacion['codigo'] }} - Almacén</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --color-disponible: #10b981;
            --color-parcial: #f59e0b;
            --color-ocupado: #f97316;
            --color-lleno: #ef4444;
            --color-spark: #3b82f6;
        }

        .status-indicator {
            position: relative;
            overflow: hidden;
        }

        .status-indicator::after {
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

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .section-divider {
            position: relative;
            padding-left: 1.5rem;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 2px;
        }

        .inventory-section::before {
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
        }

        .rack-section::before {
            background: linear-gradient(to bottom, #10b981, #059669);
        }

        .info-section::before {
            background: linear-gradient(to bottom, #f59e0b, #d97706);
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .print-full {
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        @if ($error)
            <!-- Error State -->
            <div class="min-h-screen flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-xl text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ $mensaje }}</h2>
                    <p class="text-gray-600 mb-6">La ubicación solicitada no existe o no está disponible.</p>
                    <div class="bg-gray-100 rounded-lg p-4 mb-6">
                        <code class="text-gray-800 font-mono">{{ $codigo }}</code>
                    </div>
                    <button onclick="window.history.back()"
                        class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition-colors duration-300 w-full">
                        <i class="fas fa-arrow-left mr-2"></i>Volver atrás
                    </button>
                </div>
            </div>
        @else
            <!-- Header Principal -->
            <div class="bg-gradient-to-r from-blue-700 to-blue-800 text-white">
                <div class="container mx-auto px-4 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="bg-white/20 rounded-xl p-3 mr-4">
                                    <i class="fas fa-location-dot text-xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold">
                                        Ubicación: {{ $ubicacion['codigo_unico'] ?? $ubicacion['codigo'] }}
                                    </h1>
                                    <p class="text-blue-100 text-sm">Detalles del espacio de almacenamiento</p>
                                </div>
                            </div>

                            <!-- Badges de información -->
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="bg-white/20 px-3 py-1 rounded-lg text-sm flex items-center">
                                    <i class="fas fa-warehouse mr-2"></i>{{ $ubicacion['rack_nombre'] }}
                                </span>
                                <span class="bg-white/20 px-3 py-1 rounded-lg text-sm flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $ubicacion['sede'] }}
                                </span>
                                <span class="bg-white/20 px-3 py-1 rounded-lg text-sm flex items-center">
                                    <i class="fas fa-layer-group mr-2"></i>Nivel {{ $ubicacion['nivel'] }}
                                </span>
                                <span class="bg-white/20 px-3 py-1 rounded-lg text-sm flex items-center">
                                    <i class="fas fa-arrows-alt-h mr-2"></i>Posición {{ $ubicacion['posicion'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Estado principal -->
                        @php
                            $estadoConfig = match ($ubicacion['estado_ocupacion']) {
                                'disponible' => [
                                    'bg' => 'bg-emerald-500',
                                    'icon' => 'fa-check-circle',
                                    'text' => 'Disponible',
                                ],
                                'parcial' => [
                                    'bg' => 'bg-amber-500',
                                    'icon' => 'fa-hourglass-half',
                                    'text' => 'Parcial',
                                ],
                                'ocupado' => ['bg' => 'bg-orange-500', 'icon' => 'fa-box-open', 'text' => 'Ocupado'],
                                'lleno' => [
                                    'bg' => 'bg-rose-500',
                                    'icon' => 'fa-exclamation-triangle',
                                    'text' => 'Lleno',
                                ],
                                default => [
                                    'bg' => 'bg-gray-500',
                                    'icon' => 'fa-question-circle',
                                    'text' => 'Desconocido',
                                ],
                            };
                        @endphp
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="container mx-auto px-4 py-6">
                <!-- KPI Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Total Items -->
                    <div class="bg-white rounded-xl p-6 shadow-md card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Items totales</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $ubicacion['total_items'] }}</p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <i class="fas fa-boxes-stacked text-blue-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-400"></i>
                                <span class="truncate">En esta ubicación</span>
                            </p>
                        </div>
                    </div>

                    <!-- Tipo de Rack -->
                    <div class="bg-white rounded-xl p-6 shadow-md card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tipo de Rack</p>
                                <p class="text-xl font-bold text-gray-800 uppercase">
                                    {{ $ubicacion['tipo_rack'] ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <i class="fas fa-cube text-green-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="fas fa-expand-alt mr-2 text-green-400"></i>
                                <span>{{ $ubicacion['filas'] ?? '?' }}×{{ $ubicacion['columnas'] ?? '?' }}
                                    dimensiones</span>
                            </p>
                        </div>
                    </div>

                    <!-- Última actualización -->
                    <div class="bg-white rounded-xl p-6 shadow-md card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Última actualización</p>
                                <p class="text-lg font-bold text-gray-800">
                                    @if ($ubicacion['fecha_actualizacion'])
                                        {{ \Carbon\Carbon::parse($ubicacion['fecha_actualizacion'])->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <i class="fas fa-history text-purple-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                @if ($ubicacion['fecha_actualizacion'])
                                    {{ \Carbon\Carbon::parse($ubicacion['fecha_actualizacion'])->format('H:i') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="bg-white rounded-xl p-6 shadow-md card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Productos diferentes</p>
                                <p class="text-3xl font-bold text-gray-800">{{ count($ubicacion['productos']) }}</p>
                            </div>
                            <div class="bg-amber-50 p-3 rounded-lg">
                                <i class="fas fa-box-open text-amber-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500">Tipos almacenados</p>
                        </div>
                    </div>
                </div>

                <!-- Layout de ancho completo -->
                <div class="space-y-6">
                    <!-- Inventario -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                                <div class="flex items-center">
                                    <div class="bg-blue-500 p-2 sm:p-2.5 rounded-lg mr-2 sm:mr-3">
                                        <i class="fas fa-boxes text-white text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-lg sm:text-xl font-bold text-gray-800">Inventario Actual</h2>
                                        <p class="text-xs sm:text-sm text-gray-600">Productos almacenados en esta
                                            ubicación</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-4">
                                    <span
                                        class="bg-blue-500 text-white px-3 sm:px-4 py-1 sm:py-1.5 rounded-full font-semibold whitespace-nowrap text-sm">
                                        {{ count($ubicacion['productos']) }} items
                                    </span>
                                    <div class="hidden sm:flex items-center bg-white px-3 py-1.5 rounded-lg border">
                                        <span class="text-sm text-gray-600 mr-2">Total:</span>
                                        <span class="font-bold text-gray-800">{{ $ubicacion['total_items'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            @if (count($ubicacion['productos']) > 0)
                                <!-- Vista desktop -->
                                <div class="hidden md:block">
                                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                                <tr>
                                                    <th
                                                        class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Producto
                                                    </th>
                                                    <th
                                                        class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Categoría
                                                    </th>
                                                    <th
                                                        class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Tipo
                                                    </th>
                                                    <th
                                                        class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Cantidad
                                                    </th>
                                                    <th
                                                        class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Cliente
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-100">
                                                @foreach ($ubicacion['productos'] as $producto)
                                                    <tr
                                                        class="hover:bg-blue-50/30 transition-colors duration-150 group">
                                                        <td class="px-4 sm:px-6 py-4">
                                                            <div class="flex items-center">
                                                                <div
                                                                    class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4 
                                                    {{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'bg-purple-100' : ($producto['es_repuesto'] ? 'bg-teal-100' : 'bg-green-100') }}">
                                                                    <i
                                                                        class="{{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'fas fa-shield-alt text-purple-600' : ($producto['es_repuesto'] ? 'fas fa-cogs text-teal-600' : 'fas fa-box text-green-600') }} text-sm sm:text-lg"></i>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <div
                                                                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-0">
                                                                        <h4
                                                                            class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                                                            {{ $producto['nombre'] }}
                                                                        </h4>
                                                                    </div>

                                                                    @if (!empty($producto['modelos']) && count($producto['modelos']) > 0)
                                                                        <div class="mt-1 sm:mt-2">
                                                                            @if ($producto['tiene_multiple_modelos'] ?? false)
                                                                                <div class="flex flex-wrap gap-1">
                                                                                    @foreach ($producto['modelos'] as $modelo)
                                                                                        <span
                                                                                            class="px-1.5 py-0.5 sm:px-2 sm:py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-100">
                                                                                            <i
                                                                                                class="fas fa-cube mr-1 text-xs"></i>{{ $modelo['nombre'] }}
                                                                                        </span>
                                                                                    @endforeach
                                                                                </div>
                                                                            @else
                                                                                <span
                                                                                    class="text-xs sm:text-sm text-gray-600">
                                                                                    <i
                                                                                        class="fas fa-cube text-gray-400 mr-1"></i>
                                                                                    {{ $producto['modelo_nombre'] ?? ($producto['modelos'][0]['nombre'] ?? '') }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    @elseif($producto['modelo_nombre'] ?? false)
                                                                        <div
                                                                            class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600">
                                                                            <i
                                                                                class="fas fa-cube text-gray-400 mr-1"></i>
                                                                            {{ $producto['modelo_nombre'] }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-4 text-center">
                                                            @if ($producto['categoria'])
                                                                <span
                                                                    class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    {{ $producto['categoria'] }}
                                                                </span>
                                                            @else
                                                                <span class="text-xs text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-4 text-center">
                                                            @if ($producto['tipo_articulo'] == 'CUSTODIA')
                                                                <span
                                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                                                    <i class="fas fa-shield-alt mr-2"></i>Custodia
                                                                </span>
                                                            @elseif($producto['es_repuesto'])
                                                                <span
                                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-semibold bg-teal-100 text-teal-800 border border-teal-200">
                                                                    <i class="fas fa-cogs mr-2"></i>Repuesto
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                                    <i
                                                                        class="fas fa-box mr-2"></i>{{ $producto['tipo_articulo'] ?? 'General' }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-4 text-center">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 text-white font-bold text-lg shadow">
                                                                    {{ $producto['cantidad'] }}
                                                                </span>
                                                                <span
                                                                    class="mt-2 text-xs text-gray-500">unidades</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-4 text-center">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <span
                                                                    class="{{ $producto['cliente_general_nombre'] != 'Sin cliente' ? 'text-purple-700 font-semibold' : 'text-gray-500' }}">
                                                                    {{ $producto['cliente_general_nombre'] ?? 'Sin cliente' }}
                                                                </span>
                                                                @if ($producto['cliente_general_nombre'] != 'Sin cliente')
                                                                    <span class="text-xs text-purple-500 mt-1">
                                                                        <i class="fas fa-user-tag mr-1"></i>Asignado
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Vista mobile -->
                                <div class="md:hidden space-y-4">
                                    @foreach ($ubicacion['productos'] as $producto)
                                        <div
                                            class="bg-gradient-to-br from-white to-gray-50 rounded-lg p-4 border border-gray-200 shadow-sm">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <div
                                                            class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-2 
                                            {{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'bg-purple-100' : ($producto['es_repuesto'] ? 'bg-teal-100' : 'bg-green-100') }}">
                                                            <i
                                                                class="{{ $producto['tipo_articulo'] == 'CUSTODIA' ? 'fas fa-shield-alt text-purple-600' : ($producto['es_repuesto'] ? 'fas fa-cogs text-teal-600' : 'fas fa-box text-green-600') }} text-xs"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="font-semibold text-gray-900 text-sm truncate">
                                                                {{ $producto['nombre'] }}</h4>
                                                        </div>
                                                        <div class="flex flex-col items-center ml-2">
                                                            <span
                                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-800 text-white font-bold text-sm">
                                                                {{ $producto['cantidad'] }}
                                                            </span>
                                                            <span class="text-xs text-gray-500 mt-1">unid</span>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                                        <div class="bg-gray-50 rounded p-2 text-center">
                                                            <p class="text-xs text-gray-500 mb-1">Categoría</p>
                                                            @if ($producto['categoria'])
                                                                <span
                                                                    class="text-xs font-medium text-gray-700">{{ $producto['categoria'] }}</span>
                                                            @else
                                                                <span class="text-xs text-gray-400">-</span>
                                                            @endif
                                                        </div>

                                                        <div class="bg-gray-50 rounded p-2 text-center">
                                                            <p class="text-xs text-gray-500 mb-1">Tipo</p>
                                                            @if ($producto['tipo_articulo'] == 'CUSTODIA')
                                                                <span
                                                                    class="text-xs font-medium text-purple-700">Custodia</span>
                                                            @elseif($producto['es_repuesto'])
                                                                <span
                                                                    class="text-xs font-medium text-teal-700">Repuesto</span>
                                                            @else
                                                                <span
                                                                    class="text-xs font-medium text-gray-700">{{ $producto['tipo_articulo'] ?? 'General' }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="bg-gray-50 rounded p-2 text-center mb-3">
                                                        <p class="text-xs text-gray-500 mb-1">Cliente</p>
                                                        <span
                                                            class="{{ $producto['cliente_general_nombre'] != 'Sin cliente' ? 'text-purple-700 font-medium' : 'text-gray-600' }} text-xs">
                                                            {{ $producto['cliente_general_nombre'] ?? 'Sin cliente' }}
                                                        </span>
                                                        @if ($producto['cliente_general_nombre'] != 'Sin cliente')
                                                            <div class="mt-1">
                                                                <span class="text-xs text-purple-500">
                                                                    <i class="fas fa-user-tag mr-1"></i>Asignado
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Modelos -->
                                                    @if (!empty($producto['modelos']) && count($producto['modelos']) > 0)
                                                        <div class="mb-3 pt-2 border-t border-gray-200">
                                                            <p class="text-xs text-gray-500 mb-1 text-center">Modelos:
                                                            </p>
                                                            <div class="flex flex-wrap gap-1 justify-center">
                                                                @if ($producto['tiene_multiple_modelos'] ?? false)
                                                                    @foreach ($producto['modelos'] as $modelo)
                                                                        <span
                                                                            class="px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded text-xs border border-blue-100">
                                                                            {{ $modelo['nombre'] }}
                                                                        </span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-xs text-gray-600 text-center">
                                                                        <i class="fas fa-cube text-gray-400 mr-1"></i>
                                                                        {{ $producto['modelo_nombre'] ?? ($producto['modelos'][0]['nombre'] ?? '') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @elseif($producto['modelo_nombre'] ?? false)
                                                        <div class="mb-3 pt-2 border-t border-gray-200 text-center">
                                                            <p class="text-xs text-gray-500 mb-1">Modelo:</p>
                                                            <span
                                                                class="text-xs text-gray-600">{{ $producto['modelo_nombre'] }}</span>
                                                        </div>
                                                    @endif

                                                    @if (isset($producto['codigo_repuesto']) && $producto['codigo_repuesto'])
                                                        <div class="pt-2 border-t border-gray-200 text-center">
                                                            <p class="text-xs text-gray-500 mb-1">Código repuesto:</p>
                                                            <code
                                                                class="text-xs font-mono text-gray-800">{{ $producto['codigo_repuesto'] }}</code>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 sm:py-16">
                                    <div
                                        class="mx-auto h-16 sm:h-20 w-16 sm:w-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                                        <i class="fas fa-inbox text-gray-400 text-2xl sm:text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">Ubicación
                                        vacía</h3>
                                    <p class="text-gray-600 max-w-md mx-auto text-sm sm:text-base">
                                        No hay productos almacenados en esta ubicación actualmente.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t border-gray-200 text-center no-print">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-bolt text-blue-500 mr-2"></i>
                        Sistema de Gestión de Almacén • Vista Spark QR
                    </p>
                </footer>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Interactividad para códigos
            const codes = document.querySelectorAll('code');
            codes.forEach(code => {
                code.addEventListener('click', function() {
                    const text = this.textContent;
                    navigator.clipboard.writeText(text).then(() => {
                        const original = this.textContent;
                        this.textContent = '¡Copiado!';
                        setTimeout(() => {
                            this.textContent = original;
                        }, 1500);
                    });
                });
                code.style.cursor = 'pointer';
                code.title = 'Click para copiar';
            });

            // Optimizar para impresión
            window.addEventListener('beforeprint', () => {
                document.body.classList.add('printing');
                document.querySelectorAll('.card-hover').forEach(el => {
                    el.classList.remove('card-hover');
                });
            });
        });
    </script>
</body>

</html>
