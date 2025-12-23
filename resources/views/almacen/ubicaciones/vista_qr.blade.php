<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ubicacion['codigo_unico'] ?? 'Ubicación' }} - Sistema de Almacén</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .print-break {
                page-break-inside: avoid;
            }
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .qr-shadow {
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
        }

        .progress-bar {
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

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

        /* Scroll personalizado */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Mejoras para móviles */
        @media (max-width: 640px) {
            .mobile-stack {
                flex-direction: column !important;
            }

            .mobile-full {
                width: 100% !important;
            }

            .mobile-text-center {
                text-align: center !important;
            }

            .mobile-p-4 {
                padding: 1rem !important;
            }

            .mobile-space-y-4>*+* {
                margin-top: 1rem !important;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        @if (isset($error) && $error)
            <!-- Vista de Error - Mejorada -->
            <div class="fade-in min-h-screen flex items-center justify-center px-4">
                <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full">
                    <div class="relative">
                        <div class="absolute -top-16 left-1/2 transform -translate-x-1/2">
                            <div
                                class="w-24 h-24 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                            </div>
                        </div>
                        <div class="pt-16 text-center">
                            <h1 class="text-2xl font-bold text-gray-900 mb-3">Ubicación no encontrada</h1>
                            <p class="text-gray-600 mb-8 leading-relaxed">
                                {{ $mensaje ?? 'El código QR no corresponde a ninguna ubicación registrada.' }}</p>

                            <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-200">
                                <p class="text-sm text-gray-500 font-medium mb-2">Código escaneado:</p>
                                <div class="bg-white rounded-lg p-3">
                                    <p class="font-mono text-lg font-bold text-gray-800 break-all">
                                        {{ $codigo ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('almacen.vista') ?? '#' }}"
                                    class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-200 active:scale-95 shadow-lg shadow-blue-500/25">
                                    <i class="fas fa-arrow-left text-sm"></i>
                                    <span>Volver al almacén</span>
                                </a>
                                <button onclick="window.history.back()"
                                    class="flex-1 inline-flex items-center justify-center gap-2 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-3.5 px-6 rounded-xl transition-all duration-200 active:scale-95">
                                    <i class="fas fa-redo"></i>
                                    <span>Intentar de nuevo</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Vista Principal - Completamente Rediseñada -->
            <div class="fade-in space-y-6">
                <!-- Header Fijo Superior - ACTUALIZADO PARA MOBILES -->
                <div
                    class="sticky top-0 z-10 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-4 border border-white/20">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <!-- Columna izquierda: Logo y datos principales -->
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-location-dot text-white text-xl"></i>
                                </div>
                                @if (($ubicacion['estado_ocupacion'] ?? '') == 'ocupado')
                                    <div
                                        class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full border-2 border-white">
                                    </div>
                                @else
                                    <div
                                        class="absolute -top-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white">
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">
                                        {{ $ubicacion['codigo_unico'] ?? 'N/A' }}
                                    </h1>
                                    <!-- ESTADO compacto al lado del código -->
                                    <span
                                        class="px-2 py-0.5 rounded-lg text-xs font-semibold 
                        {{ ($ubicacion['estado_ocupacion'] ?? 'disponible') == 'ocupado'
                            ? 'bg-yellow-100 text-yellow-800'
                            : 'bg-green-100 text-green-800' }}">
                                        {{ ($ubicacion['estado_ocupacion'] ?? 'disponible') == 'ocupado' ? 'OCUPADO' : 'DISPONIBLE' }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <p class="text-sm text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-warehouse text-gray-400"></i>
                                        <span>{{ $ubicacion['rack_nombre'] ?? 'Sin rack' }} •
                                            {{ $ubicacion['sede'] ?? 'Sin sede' }}</span>
                                    </p>

                                    <!-- POSICIÓN EXACTA EN HEADER -->
                                    <div class="flex items-center gap-2 ml-2">
                                        <div class="flex items-center gap-1">
                                            <div class="text-center">
                                                <div class="flex items-center gap-1 bg-blue-50 px-2 py-1 rounded-lg">
                                                    <span class="text-xs text-blue-600 font-medium">Nivel</span>
                                                    <span
                                                        class="text-sm font-bold text-blue-700">N{{ $ubicacion['nivel'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="text-blue-400">
                                                <i class="fas fa-arrow-right text-xs"></i>
                                            </div>
                                            <div class="text-center">
                                                <div class="flex items-center gap-1 bg-indigo-50 px-2 py-1 rounded-lg">
                                                    <span class="text-xs text-indigo-600 font-medium">Posición</span>
                                                    <span
                                                        class="text-sm font-bold text-indigo-700">P{{ $ubicacion['posicion'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha: Información adicional -->
                        <div class="flex items-center gap-3">
                            <!-- Información que SI se muestra en móviles - CENTRADA -->
                            <div class="md:hidden w-full text-center mt-2">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <span
                                            class="px-2 py-1 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 rounded-lg text-xs font-medium border border-blue-200">
                                            <i class="fas fa-pallet text-xs mr-1"></i>
                                            {{ strtoupper($ubicacion['tipo_rack'] ?? 'N/A') }}
                                        </span>
                                    </div>
                                    @if (isset($ubicacion['fecha_actualizacion']))
                                        <p class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($ubicacion['fecha_actualizacion'])->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Información que SOLO se muestra en desktop -->
                            <div class="hidden md:block text-right">
                                <div class="flex items-center gap-2 justify-end mb-1">
                                    <span
                                        class="px-2 py-1 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 rounded-lg text-xs font-medium border border-blue-200">
                                        <i class="fas fa-pallet text-xs mr-1"></i>
                                        {{ strtoupper($ubicacion['tipo_rack'] ?? 'N/A') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400">
                                    @if (isset($ubicacion['fecha_actualizacion']))
                                        Actualizado:
                                        {{ \Carbon\Carbon::parse($ubicacion['fecha_actualizacion'])->format('d/m/Y H:i') }}
                                    @endif
                                </p>
                            </div>

                            <!-- QR que SOLO se muestra en desktop -->
                            <div
                                class="hidden md:block bg-white/50 backdrop-blur-sm rounded-xl p-2 border border-gray-200">
                                <img src="{{ url('/almacen/ubicaciones/qr/' . $ubicacion['codigo_unico']) }}"
                                    alt="QR" class="w-14 h-14 object-contain">
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Contenido de la Ubicación - Cajas -->
                @if (($ubicacion['cajas']->count() ?? 0) > 0)
                    <div class="bg-white rounded-2xl shadow-xl p-6 card-hover print-break">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-boxes text-green-500"></i>
                                    Contenido de la ubicación
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $ubicacion['cajas']->count() }} caja(s) •
                                    {{ $ubicacion['total_articulos_en_cajas'] ?? 0 }} artículo(s) total
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    {{ $ubicacion['cajas']->count() }} caja(s)
                                </span>
                                <span class="px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $ubicacion['total_articulos_en_cajas'] ?? 0 }} artículos
                                </span>
                            </div>
                        </div>

                        <!-- Grid de cajas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach ($ubicacion['cajas'] as $cajaInfo)
                                <div
                                    class="bg-gradient-to-br from-white to-gray-50 rounded-2xl border border-gray-200 p-5 card-hover">
                                    <!-- Header de la caja -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <div
                                                    class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-box text-white text-sm"></i>
                                                </div>
                                                <h4 class="font-bold text-gray-900 text-lg">
                                                    {{ $cajaInfo['caja']['nombre'] }}</h4>
                                            </div>

                                            <!-- Estado y etiquetas -->
                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    class="px-2 py-1 rounded-lg text-xs font-medium 
                                        {{ $cajaInfo['caja']['estado'] == 'completa'
                                            ? 'bg-green-100 text-green-800 border border-green-200'
                                            : ($cajaInfo['caja']['estado'] == 'parcial'
                                                ? 'bg-yellow-100 text-yellow-800 border border-yellow-200'
                                                : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                                                    {{ ucfirst($cajaInfo['caja']['estado']) }}
                                                </span>

                                                @if ($cajaInfo['caja']['es_custodia'])
                                                    <span
                                                        class="px-2 py-1 bg-red-100 text-red-800 rounded-lg text-xs font-medium border border-red-200">
                                                        <i class="fas fa-shield-alt mr-1"></i>Custodia
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Contador -->
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-gray-900">
                                                {{ $cajaInfo['caja']['cantidad_actual'] }}</div>
                                            <div class="text-xs text-gray-500">de {{ $cajaInfo['caja']['capacidad'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Barra de progreso mejorada -->
                                    <div class="mb-5">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Capacidad utilizada</span>
                                            <span
                                                class="font-bold text-gray-900">{{ $cajaInfo['caja']['porcentaje_llenado'] }}%</span>
                                        </div>
                                        <div class="relative">
                                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full progress-bar bg-gradient-to-r from-green-400 to-emerald-500"
                                                    style="width: {{ $cajaInfo['caja']['porcentaje_llenado'] }}%">
                                                </div>
                                            </div>
                                            <div class="absolute top-0 left-0 h-3 w-full flex">
                                                @for ($i = 0; $i <= 100; $i += 25)
                                                    <div class="flex-1 border-r border-white/50"></div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contenido de la caja -->
                                    <div class="border-t border-gray-200 pt-4">
                                        <h5 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                            <i class="fas fa-box-open text-orange-500"></i>
                                            Contenido
                                        </h5>

                                        @if ($cajaInfo['contenido']['nombre'] !== 'Vacía')
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <!-- Nombre del artículo -->
                                                <div class="mb-3">
                                                    <p class="font-bold text-gray-900 text-lg mb-1">
                                                        {{ $cajaInfo['contenido']['nombre'] }}</p>
                                                    @if ($cajaInfo['contenido']['tipo_articulo'])
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $cajaInfo['contenido']['tipo_articulo'] == 'CUSTODIA'
                                            ? 'bg-red-100 text-red-800'
                                            : ($cajaInfo['contenido']['es_repuesto'] ?? false
                                                ? 'bg-orange-100 text-orange-800'
                                                : 'bg-blue-100 text-blue-800') }}">
                                                            <i class="fas fa-tag mr-1 text-xs"></i>
                                                            {{ $cajaInfo['contenido']['tipo_articulo'] }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Detalles en grid -->
                                                <div class="grid grid-cols-2 gap-3 text-sm">
                                                    @if ($cajaInfo['contenido']['categoria'] && $cajaInfo['contenido']['categoria'] !== 'Sin categoría')
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-tag text-gray-400"></i>
                                                            <span
                                                                class="text-gray-600">{{ $cajaInfo['contenido']['categoria'] }}</span>
                                                        </div>
                                                    @endif

                                                    @if ($cajaInfo['contenido']['codigo_repuesto'] ?? false)
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-barcode text-gray-400"></i>
                                                            <span
                                                                class="text-gray-600">{{ $cajaInfo['contenido']['codigo_repuesto'] }}</span>
                                                        </div>
                                                    @endif

                                                    @if ($cajaInfo['contenido']['marca'] ?? false)
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-copyright text-gray-400"></i>
                                                            <span
                                                                class="text-gray-600">{{ $cajaInfo['contenido']['marca'] }}</span>
                                                        </div>
                                                    @endif

                                                    <!-- Modelo -->
                                                    @if (!empty($cajaInfo['contenido']['modelos']) && count($cajaInfo['contenido']['modelos']) > 0)
                                                        <div class="flex items-start gap-2 col-span-2">
                                                            <i class="fas fa-cubes text-gray-400 mt-1"></i>
                                                            <div>
                                                                <span
                                                                    class="text-gray-600 text-sm block mb-1">Modelo(s):</span>
                                                                @if ($cajaInfo['contenido']['tiene_multiple_modelos'] ?? false)
                                                                    <div class="flex flex-wrap gap-1">
                                                                        @foreach ($cajaInfo['contenido']['modelos'] as $modelo)
                                                                            <span
                                                                                class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs">
                                                                                {{ $modelo['nombre'] }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <span
                                                                        class="font-medium">{{ $cajaInfo['contenido']['modelo_nombre'] }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @elseif($cajaInfo['contenido']['modelo_nombre'] ?? false)
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-cube text-gray-400"></i>
                                                            <span
                                                                class="text-gray-600">{{ $cajaInfo['contenido']['modelo_nombre'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Información adicional -->
                                                @if ($cajaInfo['contenido']['stock_total'] ?? false)
                                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-sm text-gray-600">Stock total:</span>
                                                            <span
                                                                class="font-bold text-gray-900">{{ $cajaInfo['contenido']['stock_total'] }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div
                                                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 text-center">
                                                <div
                                                    class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <i class="fas fa-inbox text-gray-400 text-xl"></i>
                                                </div>
                                                <p class="font-semibold text-gray-700">Caja vacía</p>
                                                <p class="text-sm text-gray-500 mt-1">No contiene artículos</p>
                                            </div>
                                        @endif

                                        <!-- Fecha de entrada -->
                                        @if ($cajaInfo['caja']['fecha_entrada'])
                                            <div class="mt-4 pt-3 border-t border-gray-200">
                                                <div class="flex items-center justify-between text-sm">
                                                    <div class="flex items-center gap-2 text-gray-600">
                                                        <i class="far fa-calendar-alt"></i>
                                                        <span>Fecha de entrada (caja):</span>
                                                    </div>
                                                    <span class="font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($cajaInfo['caja']['fecha_entrada'])->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Mensaje de ubicación vacía -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 text-center card-hover">
                        <div class="max-w-md mx-auto">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Ubicación vacía</h3>
                            <p class="text-gray-600 mb-6">Esta ubicación no contiene cajas ni artículos en este
                                momento.</p>
                            <div class="bg-gray-50 rounded-xl p-4 inline-block">
                                <p class="text-sm text-gray-500">Estado actual:</p>
                                <p class="text-lg font-bold text-green-600 capitalize">
                                    {{ ($ubicacion['estado_ocupacion'] ?? 'disponible') == 'ocupado' ? 'Ocupado' : 'Disponible' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Vista QR cargada - Optimizada para móviles');

            // Mejora de experiencia en móviles
            if (window.innerWidth < 768) {
                // Suavizar scroll en móviles
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            }

            // Efecto de carga progresiva para elementos
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px 50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, observerOptions);

            // Observar todas las tarjetas
            document.querySelectorAll('.card-hover').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>

</html>
