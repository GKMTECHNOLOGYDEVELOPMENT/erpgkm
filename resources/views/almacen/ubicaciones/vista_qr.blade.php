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
            .no-print { display: none !important; }
            .print-break { page-break-inside: avoid; }
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
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
            .mobile-stack { flex-direction: column !important; }
            .mobile-full { width: 100% !important; }
            .mobile-text-center { text-align: center !important; }
            .mobile-p-4 { padding: 1rem !important; }
            .mobile-space-y-4 > * + * { margin-top: 1rem !important; }
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
                        <div class="w-24 h-24 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                        </div>
                    </div>
                    <div class="pt-16 text-center">
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">Ubicación no encontrada</h1>
                        <p class="text-gray-600 mb-8 leading-relaxed">{{ $mensaje ?? 'El código QR no corresponde a ninguna ubicación registrada.' }}</p>
                        
                        <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-200">
                            <p class="text-sm text-gray-500 font-medium mb-2">Código escaneado:</p>
                            <div class="bg-white rounded-lg p-3">
                                <p class="font-mono text-lg font-bold text-gray-800 break-all">{{ $codigo ?? 'N/A' }}</p>
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
            <!-- Header Fijo Superior -->
            <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-4 border border-white/20">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-location-dot text-white text-xl"></i>
                            </div>
                            @if(($ubicacion['estado_ocupacion'] ?? '') == 'ocupado')
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full border-2 border-white"></div>
                            @else
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white"></div>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $ubicacion['codigo_unico'] ?? 'N/A' }}</h1>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i class="fas fa-warehouse text-gray-400"></i>
                                <span>{{ $ubicacion['rack_nombre'] ?? 'Sin rack' }} • {{ $ubicacion['sede'] ?? 'Sin sede' }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="hidden md:block text-right">
                            <p class="text-sm text-gray-500">Nivel {{ $ubicacion['nivel'] ?? 'N/A' }} • Pos. {{ $ubicacion['posicion'] ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">
                                @if(isset($ubicacion['fecha_actualizacion']))
                                Actualizado: {{ \Carbon\Carbon::parse($ubicacion['fecha_actualizacion'])->format('d/m/Y H:i') }}
                                @endif
                            </p>
                        </div>
                        <div class="bg-white/50 backdrop-blur-sm rounded-xl p-2 border border-gray-200">
                            <img src="{{ url('/almacen/ubicaciones/qr/' . $ubicacion['codigo_unico']) }}" 
                                 alt="QR" class="w-14 h-14 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de Métricas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg p-6 border border-blue-100 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600 mb-1">Cajas en ubicación</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $ubicacion['total_cajas'] ?? 0 }}</h3>
                        </div>
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-box text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-100">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-cube text-gray-400 mr-2"></i>
                            {{ $ubicacion['total_articulos_en_cajas'] ?? 0 }} artículos dentro
                        </p>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-white to-green-50 rounded-2xl shadow-lg p-6 border border-green-100 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 mb-1">Estado de ubicación</p>
                            <h3 class="text-2xl font-bold text-gray-900 capitalize">
                                {{ ($ubicacion['estado_ocupacion'] ?? 'disponible') == 'ocupado' ? 'Ocupado' : 'Disponible' }}
                            </h3>
                        </div>
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                            @if(($ubicacion['estado_ocupacion'] ?? '') == 'ocupado')
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            @else
                            <i class="fas fa-circle text-green-400 text-2xl"></i>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-green-100">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-pallet text-gray-400 mr-2"></i>
                            Tipo: <span class="font-semibold uppercase">{{ $ubicacion['tipo_rack'] ?? 'N/A' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sección Principal: QR + Detalles -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 print-break">
                <!-- QR Grande - Tarjeta Principal -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-6 card-hover">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- QR con diseño premium -->
                        <div class="flex-shrink-0">
                            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-5 border border-gray-200 qr-shadow">
                                <div class="text-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-800 mb-1">Código QR</h3>
                                    <p class="text-sm text-gray-500">Escanea para ver esta ubicación</p>
                                </div>
                                @if(isset($ubicacion['codigo_unico']))
                                <div class="bg-white p-3 rounded-xl mb-4">
                                    <img src="{{ url('/almacen/ubicaciones/qr/' . $ubicacion['codigo_unico']) }}" 
                                         alt="QR Code" 
                                         class="w-full max-w-[220px] mx-auto">
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-xs text-gray-500 mb-1 text-center">Código único</p>
                                    <p class="font-mono font-bold text-gray-900 text-center text-sm break-all px-2">
                                        {{ $ubicacion['codigo_unico'] }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Detalles de la ubicación -->
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                Información detallada
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Fila de detalles -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-500 mb-1 flex items-center gap-2">
                                            <i class="fas fa-warehouse text-gray-400"></i>
                                            Rack
                                        </p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $ubicacion['rack_nombre'] ?? 'N/A' }}</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-500 mb-1 flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                            Sede
                                        </p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $ubicacion['sede'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Ubicación específica -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4">
                                    <p class="text-sm text-blue-600 font-medium mb-2">Posición exacta</p>
                                    <div class="flex items-center gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-blue-700">N{{ $ubicacion['nivel'] ?? 'N/A' }}</div>
                                            <p class="text-xs text-blue-600">Nivel</p>
                                        </div>
                                        <div class="text-blue-400">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-indigo-700">P{{ $ubicacion['posicion'] ?? 'N/A' }}</div>
                                            <p class="text-xs text-indigo-600">Posición</p>
                                        </div>
                                        <div class="text-blue-400 ml-auto">
                                            <i class="fas fa-pallet text-xl"></i>
                                            <span class="text-sm font-semibold ml-2 uppercase">{{ $ubicacion['tipo_rack'] ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Categorías y tipos -->
                                <div class="space-y-3">
                                    @if(isset($ubicacion['categorias']) && count($ubicacion['categorias']) > 0)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                            <i class="fas fa-tags text-purple-500"></i>
                                            Categorías presentes
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($ubicacion['categorias'] as $categoria)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-purple-100 to-purple-50 text-purple-800 border border-purple-200">
                                                <i class="fas fa-tag mr-1.5 text-xs"></i>
                                                {{ $categoria }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if(isset($ubicacion['tipos_articulos']) && count($ubicacion['tipos_articulos']) > 0)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                            <i class="fas fa-boxes text-orange-500"></i>
                                            Tipos de artículos
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($ubicacion['tipos_articulos'] as $tipo)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-orange-100 to-orange-50 text-orange-800 border border-orange-200">
                                                <i class="fas fa-cube mr-1.5 text-xs"></i>
                                                {{ $tipo }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Panel de Acciones Rápidas -->
                <div class="bg-white rounded-2xl shadow-xl p-6 card-hover">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-bolt text-yellow-500"></i>
                        Acciones rápidas
                    </h3>
                    
                    <div class="space-y-3">
                        @if(isset($ubicacion['codigo_unico']))
                        <a href="{{ url('/almacen/ubicaciones/qr/' . $ubicacion['codigo_unico'] . '?download=true') }}" 
                           class="group flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl border border-blue-200 transition-all duration-200 active:scale-95">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-download text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Descargar QR</p>
                                    <p class="text-xs text-gray-500">Guardar código QR</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-blue-400 group-hover:text-blue-600"></i>
                        </a>
                        @endif
                        
                        <button onclick="window.print()" 
                                class="group flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl border border-gray-200 transition-all duration-200 active:scale-95 w-full text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-print text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Imprimir</p>
                                    <p class="text-xs text-gray-500">Documento para registro</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                        </button>
                        
                        <a href="{{ route('almacen.vista') ?? '#' }}" 
                           class="group flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl border border-green-200 transition-all duration-200 active:scale-95">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:bg-green-600 transition-colors">
                                    <i class="fas fa-arrow-left text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Volver al almacén</p>
                                    <p class="text-xs text-gray-500">Vista general</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-green-400 group-hover:text-green-600"></i>
                        </a>
                    </div>
                
                </div>
            </div>

            <!-- Contenido de la Ubicación - Cajas -->
            @if(($ubicacion['cajas']->count() ?? 0) > 0)
            <div class="bg-white rounded-2xl shadow-xl p-6 card-hover print-break">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-boxes text-green-500"></i>
                            Contenido de la ubicación
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $ubicacion['cajas']->count() }} caja(s) • {{ $ubicacion['total_articulos_en_cajas'] ?? 0 }} artículo(s) total
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
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl border border-gray-200 p-5 card-hover">
                        <!-- Header de la caja -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-box text-white text-sm"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $cajaInfo['caja']['nombre'] }}</h4>
                                </div>
                                
                                <!-- Estado y etiquetas -->
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium 
                                        {{ $cajaInfo['caja']['estado'] == 'completa' 
                                            ? 'bg-green-100 text-green-800 border border-green-200' 
                                            : ($cajaInfo['caja']['estado'] == 'parcial'
                                                ? 'bg-yellow-100 text-yellow-800 border border-yellow-200'
                                                : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                                        {{ ucfirst($cajaInfo['caja']['estado']) }}
                                    </span>
                                    
                                    @if($cajaInfo['caja']['es_custodia'])
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-lg text-xs font-medium border border-red-200">
                                        <i class="fas fa-shield-alt mr-1"></i>Custodia
                                    </span>
                                    @endif
                                
                                </div>
                            </div>
                            
                            <!-- Contador -->
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">{{ $cajaInfo['caja']['cantidad_actual'] }}</div>
                                <div class="text-xs text-gray-500">de {{ $cajaInfo['caja']['capacidad'] }}</div>
                            </div>
                        </div>
                        
                        <!-- Barra de progreso mejorada -->
                        <div class="mb-5">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Capacidad utilizada</span>
                                <span class="font-bold text-gray-900">{{ $cajaInfo['caja']['porcentaje_llenado'] }}%</span>
                            </div>
                            <div class="relative">
                                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full progress-bar bg-gradient-to-r from-green-400 to-emerald-500"
                                         style="width: {{ $cajaInfo['caja']['porcentaje_llenado'] }}%"></div>
                                </div>
                                <div class="absolute top-0 left-0 h-3 w-full flex">
                                    @for($i = 0; $i <= 100; $i += 25)
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
                            
                            @if($cajaInfo['contenido']['nombre'] !== 'Vacía')
                            <div class="bg-gray-50 rounded-xl p-4">
                                <!-- Nombre del artículo -->
                                <div class="mb-3">
                                    <p class="font-bold text-gray-900 text-lg mb-1">{{ $cajaInfo['contenido']['nombre'] }}</p>
                                    @if($cajaInfo['contenido']['tipo_articulo'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                                    @if($cajaInfo['contenido']['categoria'] && $cajaInfo['contenido']['categoria'] !== 'Sin categoría')
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tag text-gray-400"></i>
                                        <span class="text-gray-600">{{ $cajaInfo['contenido']['categoria'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($cajaInfo['contenido']['codigo_repuesto'] ?? false)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-barcode text-gray-400"></i>
                                        <span class="text-gray-600">{{ $cajaInfo['contenido']['codigo_repuesto'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($cajaInfo['contenido']['marca'] ?? false)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-copyright text-gray-400"></i>
                                        <span class="text-gray-600">{{ $cajaInfo['contenido']['marca'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($cajaInfo['contenido']['modelo'] ?? false)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-cube text-gray-400"></i>
                                        <span class="text-gray-600">{{ $cajaInfo['contenido']['modelo'] }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Información adicional -->
                                @if($cajaInfo['contenido']['stock_total'] ?? false)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Stock total:</span>
                                        <span class="font-bold text-gray-900">{{ $cajaInfo['contenido']['stock_total'] }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 text-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-inbox text-gray-400 text-xl"></i>
                                </div>
                                <p class="font-semibold text-gray-700">Caja vacía</p>
                                <p class="text-sm text-gray-500 mt-1">No contiene artículos</p>
                            </div>
                            @endif
                            
                            <!-- Fecha de entrada -->
                            @if($cajaInfo['caja']['fecha_entrada'])
                            <div class="mt-4 pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>Fecha de entrada:</span>
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
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Ubicación vacía</h3>
                    <p class="text-gray-600 mb-6">Esta ubicación no contiene cajas ni artículos en este momento.</p>
                    <div class="bg-gray-50 rounded-xl p-4 inline-block">
                        <p class="text-sm text-gray-500">Estado actual:</p>
                        <p class="text-lg font-bold text-green-600 capitalize">{{ ($ubicacion['estado_ocupacion'] ?? 'disponible') == 'ocupado' ? 'Ocupado' : 'Disponible' }}</p>
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
                            target.scrollIntoView({ behavior: 'smooth' });
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