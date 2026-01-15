<x-layout.default title="Solicitud Articulos - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('solicitudarticulo.index') }}" class="text-primary hover:underline">Solicitudes</a>
                </li>
            </ul>
        </div>
        <!-- Encabezado principal -->
        <div class=" panel w-full flex flex-col lg:flex-row lg:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Solicitudes</h1>
                <p class="text-gray-600 mt-2">Gestione las solicitudes según su tipo y ubicación</p>
            </div>

            @if (\App\Helpers\PermisoHelper::tienePermiso('NUEVA SOLICITUD ARTICULO'))
                <div class="flex space-x-3">
                    <!-- Botón principal para nueva solicitud -->
                    <button id="openModalBtn"
                        class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg">
                        <!-- Reemplaza el SVG con este icono de Font Awesome -->
                        <i class="fas fa-plus-circle mr-2 text-lg"></i>
                        Nueva Solicitud
                    </button>
                </div>
            @endif
        </div>

        <!-- Resumen rápido con estadísticas reales -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    Resumen de Solicitudes Pendientes
                </h3>
                <span class="text-sm text-gray-500 flex items-center">
                    <i class="fas fa-sync-alt text-gray-400 mr-1"></i>
                    Actualizado hoy
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Repuesto Lima -->
                <div
                    class="text-center p-4 rounded-xl bg-green-50 border border-green-100 hover:shadow-md transition-shadow hover:-translate-y-1 duration-200">
                    <div class="flex justify-center mb-2">
                        <div class="p-2 bg-green-100 rounded-full">
                            <i class="fas fa-wrench text-green-600 text-xl"></i>
                        </div>
                    </div>
                 <div class="text-2xl font-bold text-green-700 mb-1" id="contadorRepuestoLima">
                    {{ $contadoresArray['repuesto_lima'] }}
                </div>
                    <div class="text-sm font-medium text-green-600">Repuesto (Lima)</div>
                    <div class="text-xs text-green-500 mt-1 flex items-center justify-center">
                        <i class="fas fa-clock mr-1"></i>Pendientes
                    </div>
                </div>

                <!-- Repuesto Provincia -->
                <div
                    class="text-center p-4 rounded-xl bg-purple-50 border border-purple-100 hover:shadow-md transition-shadow hover:-translate-y-1 duration-200">
                    <div class="flex justify-center mb-2">
                        <div class="p-2 bg-purple-100 rounded-full">
                            <i class="fas fa-truck text-purple-600 text-xl"></i>
                        </div>
                    </div>
                   <div class="text-2xl font-bold text-purple-700 mb-1" id="contadorRepuestoProvincia">
                        {{ $contadoresArray['repuesto_provincia'] }}
                    </div>
                    <div class="text-sm font-medium text-purple-600">Repuesto (Provincia)</div>
                    <div class="text-xs text-purple-500 mt-1 flex items-center justify-center">
                        <i class="fas fa-clock mr-1"></i>Pendientes
                    </div>
                </div>

                <!-- Solicitud Artículo -->
                <div
                    class="text-center p-4 rounded-xl bg-blue-50 border border-blue-100 hover:shadow-md transition-shadow hover:-translate-y-1 duration-200">
                    <div class="flex justify-center mb-2">
                        <div class="p-2 bg-blue-100 rounded-full">
                            <i class="fas fa-box-open text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-blue-700 mb-1" id="contadorSolicitudArticulo">
                        {{ $contadoresArray['solicitud_articulo'] }}
                    </div>
                    <div class="text-sm font-medium text-blue-600">Solicitud Artículo</div>
                    <div class="text-xs text-blue-500 mt-1 flex items-center justify-center">
                        <i class="fas fa-clock mr-1"></i>Pendientes
                    </div>
                </div>

                <!-- Total General -->
                <div
                    class="text-center p-4 rounded-xl bg-gray-50 border border-gray-100 hover:shadow-md transition-shadow hover:-translate-y-1 duration-200">
                    <div class="flex justify-center mb-2">
                        <div class="p-2 bg-gray-100 rounded-full">
                            <i class="fas fa-clipboard-list text-gray-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-700 mb-1" id="contadorTotal">
                        {{ $contadoresArray['total'] }}
                    </div>
                    <div class="text-sm font-medium text-gray-600">Total Solicitudes</div>
                    <div class="text-xs text-gray-500 mt-1 flex items-center justify-center">
                        <i class="fas fa-list-check mr-1"></i>Todas pendientes
                    </div>
                </div>
            </div>

            <!-- Nota informativa -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-600 bg-blue-50 px-3 py-2 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    </div>
                    <span class="text-blue-700 font-medium">
                        Estadísticas actualizadas basadas en solicitudes pendientes de atención
                    </span>
                </div>
            </div>
        </div>

         <!-- Primer Modal (tu código original) -->
        <div id="solicitudModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <!-- Header del Modal -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800">Crear Nueva Solicitud</h3>
                    <p class="text-sm text-gray-600 mt-1">Selecciona el tipo de solicitud que deseas crear</p>
                </div>

                <!-- Opciones del Modal -->
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">

                        @if (\App\Helpers\PermisoHelper::tienePermiso('CREAR SOLICITUD ARTICULO'))
                            <!-- Opción para solicitud de artículo -->
                            <a href="{{ route('solicitudarticulo.create') }}"
                                class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Solicitud de Artículo</h4>
                                    <p class="text-sm text-gray-600 mt-1">Crear una solicitud para un artículo general
                                    </p>
                                </div>
                            </a>
                        @endif

                        @if (\App\Helpers\PermisoHelper::tienePermiso('CREAR SOLICITUD REPUESTO'))
                            <!-- Opción para solicitud de repuesto - CAMBIADO A BOTÓN -->
                            <button type="button" id="btnRepuesto"
                                class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-all duration-200 text-left w-full">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">Solicitud de Repuesto</h4>
                                    <p class="text-sm text-gray-600 mt-1">Crear una solicitud para un repuesto
                                        específico</p>
                                </div>
                                <div class="ml-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Footer del Modal -->
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end">
                    <button id="closeModalBtn"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <!-- Segundo Modal - CON LA MISMA ESTRUCTURA DEL PRIMER MODAL -->
        <div id="provinciaModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div
                class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0">
                <!-- Header del Modal -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <button id="backToFirstModal" class="mr-3 p-1 rounded-full hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Tipo de Repuesto</h3>
                            <p class="text-sm text-gray-600 mt-1">Selecciona una opción</p>
                        </div>
                    </div>
                </div>

                <!-- Opciones del Modal -->
                <div class="p-6">
                    <div class="text-center mb-6">
                        <p class="text-lg font-medium text-gray-800">¿El repuesto es para provincia?</p>
                        <p class="text-sm text-gray-600 mt-2">Esta elección determinará el formulario a mostrar</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Opción SI (Para provincia) -->
                        <button type="button" id="btnSiProvincia"
                            class="flex flex-col items-center p-6 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-all duration-200 transform hover:scale-105">
                            <div
                                class="flex-shrink-0 w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-800 text-lg">SÍ</h4>
                            <p class="text-sm text-gray-600 mt-1">Para provincia</p>
                        </button>

                        <!-- Opción NO (No para provincia) -->
                        <button type="button" id="btnNoProvincia"
                            class="flex flex-col items-center p-6 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105">
                            <div
                                class="flex-shrink-0 w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-800 text-lg">NO</h4>
                            <p class="text-sm text-gray-600 mt-1">No es para provincia</p>
                        </button>
                    </div>

                    <!-- Nota informativa -->
                    <div
                        class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg transition-all duration-300 hover:bg-yellow-100">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">Información importante</p>
                                <p class="text-xs text-yellow-700 mt-1">
                                    Selecciona "SÍ" si el repuesto será enviado a una ubicación provincial,
                                    esto habilitará campos adicionales para dirección y logística.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer del Modal -->
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end">
                    <button id="closeProvinciaModal"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 transition-colors duration-200">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda - VERSIÓN CON AUTO-APLICACIÓN -->
        <form method="GET" action="{{ route('solicitudarticulo.index') }}" id="filtrosForm"
            class="bg-white p-4 rounded-lg shadow-sm mb-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <!-- Select para Tipo de Solicitud -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Solicitud</label>
                    <select name="tipo"
                        class="filtro-select w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white"
                        data-autosubmit="true">
                        <option value="">Todos los tipos</option>
                        <option value="solicitud_articulo"
                            {{ request('tipo') == 'solicitud_articulo' ? 'selected' : '' }}>
                            Artículos
                        </option>
                        <option value="solicitud_repuesto"
                            {{ request('tipo') == 'solicitud_repuesto' ? 'selected' : '' }}>
                            Repuestos (Lima)
                        </option>
                        <option value="solicitud_repuesto_provincia"
                            {{ request('tipo') == 'solicitud_repuesto_provincia' ? 'selected' : '' }}>
                            Repuestos (Provincia)
                        </option>
                    </select>
                </div>

                <!-- Select para Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="estado"
                        class="filtro-select w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white"
                        data-autosubmit="true">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                            Pendientes
                        </option>
                        <option value="listo_para_entregar"
                            {{ request('estado') == 'listo_para_entregar' ? 'selected' : '' }}>
                            Listo para entregar
                        </option>
                        <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>
                            Entregado
                        </option>
                    </select>
                </div>

                <!-- Select para Urgencia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urgencia</label>
                    <select name="urgencia"
                        class="filtro-select w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white"
                        data-autosubmit="true">
                        <option value="">Todas las urgencias</option>
                        <option value="baja" {{ request('urgencia') == 'baja' ? 'selected' : '' }}>
                            Baja
                        </option>
                        <option value="media" {{ request('urgencia') == 'media' ? 'selected' : '' }}>
                            Media
                        </option>
                        <option value="alta" {{ request('urgencia') == 'alta' ? 'selected' : '' }}>
                            Alta
                        </option>
                    </select>
                </div>

                <!-- Búsqueda con debounce -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Búsqueda</label>
                    <div class="relative">
                        <input type="text" name="search" id="searchInput" placeholder="Código, ticket..."
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            data-autosubmit="true">
                        <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Solo botón Limpiar (cuando hay filtros activos) -->
            @if (request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                <div class="flex justify-end">
                    <a href="{{ route('solicitudarticulo.index') }}"
                        class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Limpiar Filtros
                    </a>
                </div>
            @endif

            <!-- Mostrar filtros activos -->
            @if (request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="font-medium">Filtros activos:</span>
                        <div class="flex flex-wrap gap-2">
                            @if (request('tipo'))
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                    Tipo:
                                    @if (request('tipo') == 'solicitud_articulo')
                                        Artículos
                                    @elseif(request('tipo') == 'solicitud_repuesto')
                                        Repuestos (Lima)
                                    @elseif(request('tipo') == 'solicitud_repuesto_provincia')
                                        Repuestos (Provincia)
                                    @else
                                        {{ request('tipo') }}
                                    @endif
                                    <a href="{{ request()->fullUrlWithoutQuery('tipo') }}"
                                        class="ml-1 text-gray-600 hover:text-gray-800">×</a>
                                </span>
                            @endif
                            @if (request('estado'))
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                    Estado:
                                    @if (request('estado') == 'listo_para_entregar')
                                        Listo para entregar
                                    @else
                                        {{ ucfirst(request('estado')) }}
                                    @endif
                                    <a href="{{ request()->fullUrlWithoutQuery('estado') }}"
                                        class="ml-1 text-gray-600 hover:text-gray-800">×</a>
                                </span>
                            @endif
                            @if (request('urgencia'))
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                    Urgencia: {{ ucfirst(request('urgencia')) }}
                                    <a href="{{ request()->fullUrlWithoutQuery('urgencia') }}"
                                        class="ml-1 text-gray-600 hover:text-gray-800">×</a>
                                </span>
                            @endif
                            @if (request('search'))
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                    Búsqueda: "{{ request('search') }}"
                                    <a href="{{ request()->fullUrlWithoutQuery('search') }}"
                                        class="ml-1 text-gray-600 hover:text-gray-800">×</a>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </form>

        <!-- Resto del código permanece igual -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @forelse($solicitudes as $solicitud)
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 
                    @if ($solicitud->tipoorden == 'solicitud_articulo') border-blue-500
                    @elseif($solicitud->tipoorden == 'solicitud_repuesto') border-green-500
                    @else border-purple-500 @endif
                    transition transform hover:scale-[1.02] hover:shadow-lg">

                    <!-- Header de la Card -->
                    <div class="px-4 md:px-6 py-4 bg-gray-50 border-b">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <!-- Badges en línea -->
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <!-- Badge de tipo de solicitud -->
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if ($solicitud->tipoorden == 'solicitud_articulo') bg-blue-100 text-blue-800
                                    @elseif($solicitud->tipoorden == 'solicitud_repuesto') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                        @if ($solicitud->tipoorden == 'solicitud_articulo')
                                            📦 Artículo
                                        @elseif($solicitud->tipoorden == 'solicitud_repuesto')
                                            🔧 Repuesto (Lima)
                                        @else
                                            🌍 Repuesto (Provincia)
                                        @endif
                                    </span>

                                    <!-- Badge de urgencia -->
                                    @if ($solicitud->niveldeurgencia == 'alta')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            🔴 Alta
                                        </span>
                                    @elseif($solicitud->niveldeurgencia == 'media')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            🟡 Media
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-bold text-lg text-gray-800 truncate">
                                    {{ $solicitud->codigo ?? 'Sin código' }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1 truncate">
                                    <span class="font-medium">Solicitante:</span>
                                    {{ $solicitud->nombre_solicitante ?? 'No especificado' }}
                                </p>
                            </div>

                            <!-- Badge de estado - alineado a la derecha -->
                            <div class="flex-shrink-0">
                                @php
                                    $estados = [
                                        'entregado' => ['class' => 'bg-dark', 'text' => 'Entregado'],
                                        'listo_para_entregar' => [
                                            'class' => 'bg-success',
                                            'text' => 'Listo para Entregar',
                                        ],
                                        'pendiente' => ['class' => 'bg-warning', 'text' => 'Pendiente'],
                                    ];

                                    $estado = $solicitud->estado ?? 'pendiente';
                                    $badgeInfo = $estados[$estado] ?? [
                                        'class' => 'bg-secondary',
                                        'text' => ucfirst(str_replace('_', ' ', $estado)),
                                    ];
                                @endphp

                                <span class="badge {{ $badgeInfo['class'] }} inline-block">
                                    {{ $badgeInfo['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido de la Card -->
                    <div class="px-4 md:px-6 py-4">
                        <!-- Información de Productos -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">
                                @if ($solicitud->tipoorden == 'solicitud_articulo')
                                    Artículos
                                @elseif($solicitud->tipoorden == 'solicitud_repuesto')
                                    Repuestos (Lima)
                                @else
                                    Repuestos (Provincia)
                                @endif
                            </p>
                            <p class="font-medium text-gray-800">
                                {{ $solicitud->total_productos ?? 0 }} productos
                                ({{ $solicitud->totalcantidadproductos ?? 0 }} unidades)
                            </p>
                        </div>

                        <!-- Mostrar número de ticket para provincia -->
                        @if ($solicitud->tipoorden == 'solicitud_repuesto_provincia' && $solicitud->numeroticket)
                            <div class="mb-3">
                                <p class="text-sm text-gray-500 font-medium">Número de Ticket</p>
                                <p class="font-medium text-gray-800 truncate">
                                    🎫 {{ $solicitud->numeroticket }}
                                </p>
                            </div>
                        @endif

                        <!-- Código de Cotización -->
                        @if ($solicitud->codigo_cotizacion ?? false)
                            <div class="mb-3">
                                <p class="text-sm text-gray-500 font-medium">Cotización</p>
                                <p class="font-medium text-purple-600 truncate">
                                    📋 {{ $solicitud->codigo_cotizacion }}
                                </p>
                            </div>
                        @endif

                        <!-- Tipo de Servicio -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Tipo de Servicio</p>
                            <p class="font-medium text-gray-800 truncate">
                                @switch($solicitud->tiposervicio)
                                    @case('solicitud_articulo')
                                        📦 Solicitud de Artículo
                                    @break

                                    @case('solicitud_repuesto')
                                        🔧 Solicitud de Repuesto
                                    @break

                                    @case('mantenimiento')
                                        🛠️ Mantenimiento
                                    @break

                                    @case('reparacion')
                                        🔧 Reparación
                                    @break

                                    @case('instalacion')
                                        ⚡ Instalación
                                    @break

                                    @case('garantia')
                                        📋 Garantía
                                    @break

                                    @default
                                        {{ $solicitud->tiposervicio ?? 'No especificado' }}
                                @endswitch
                            </p>
                        </div>

                        <!-- Fechas (en grid para móvil) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <!-- Fecha de Creación -->
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Fecha Creación</p>
                                <p class="font-medium text-gray-800 text-sm">
                                    @if ($solicitud->fechacreacion)
                                        {{ \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d M Y') }}
                                    @else
                                        No especificada
                                    @endif
                                </p>
                            </div>

                            <!-- Fecha Requerida -->
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Fecha Requerida</p>
                                <p class="font-medium text-gray-800 text-sm">
                                    @if ($solicitud->fecharequerida)
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y') }}
                                    @else
                                        No especificada
                                    @endif
                                </p>
                            </div>
                        </div>

                        @php
                            $fechaRequerida = $solicitud->fecharequerida
                                ? \Carbon\Carbon::parse($solicitud->fecharequerida)->startOfDay()
                                : now()->addDays(7)->startOfDay();

                            $diasRestantes = now()->startOfDay()->diffInDays($fechaRequerida, false);
                        @endphp


                        <p
                            class="text-sm font-semibold
                                @if ($diasRestantes <= 0) text-red-600
                                @elseif ($diasRestantes <= 2) text-red-500
                                @elseif ($diasRestantes <= 5) text-yellow-500
                                @else text-green-600 @endif
                            ">
                            @if ($diasRestantes > 0)
                                {{ $diasRestantes }} día{{ $diasRestantes != 1 ? 's' : '' }} restantes
                            @else
                                ⚠️ Vencida
                            @endif
                        </p>


                        @if (!empty($solicitud->observaciones))
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 font-medium">Observaciones</p>
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $solicitud->observaciones }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer de la Card - BOTONES RESPONSIVE -->
                    <div class="px-4 md:px-6 py-3 bg-gray-50 border-t">
                        <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2">
                            <!-- Botones para Artículos (AZUL) -->
                            @if ($solicitud->tipoorden == 'solicitud_articulo')
                                @if (\App\Helpers\PermisoHelper::tienePermiso('VER SOLICITUD ARTICULO DETALLE'))
                                    <a href="{{ route('solicitudarticulo.show', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span class="truncate">Ver</span>
                                    </a>
                                @endif

                                @if (\App\Helpers\PermisoHelper::tienePermiso('OPCIONES SOLICITUD ARTICULO'))
                                    <a href="{{ route('solicitudarticulo.opciones', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                                            </path>
                                        </svg>
                                        <span class="truncate">Opciones</span>
                                    </a>
                                @endif

                                @if (\App\Helpers\PermisoHelper::tienePermiso('GESTIONAR SOLICITUD ARTICULO'))
                                    <a href="{{ route('solicitudarticulo.gestionar', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">Gestionar</span>
                                    </a>
                                @endif

                                @if (\App\Helpers\PermisoHelper::tienePermiso('EDITAR SOLICITUD ARTICULO'))
                                    @if ($solicitud->estado == 'pendiente')
                                        <a href="{{ route('solicitudarticulo.edit', $solicitud->idsolicitudesordenes) }}"
                                            class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition min-w-[80px]">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            <span class="truncate">Editar</span>
                                        </a>
                                    @endif
                                @endif

                                <!-- Botones para Repuestos Lima (VERDE) -->
                            @elseif($solicitud->tipoorden == 'solicitud_repuesto')
                                @if (\App\Helpers\PermisoHelper::tienePermiso('VER SOLICITUD REPUESTO'))
                                    <a href="{{ route('solicitudrepuesto.show', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span class="truncate">Ver</span>
                                    </a>
                                @endif

                                @if (\App\Helpers\PermisoHelper::tienePermiso('VER OPCIONES REPUESTO'))
                                    <a href="{{ route('solicitudrepuesto.opciones', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                                            </path>
                                        </svg>
                                        <span class="truncate">Opciones</span>
                                    </a>
                                @endif

                                @if (\App\Helpers\PermisoHelper::tienePermiso('GESTIONAR OPCIONES REPUESTO'))
                                    <a href="{{ route('solicitudrepuesto.gestionar', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">Gestionar</span>
                                    </a>
                                @endif

                                @if ($solicitud->estado == 'pendiente')
                                    @if (\App\Helpers\PermisoHelper::tienePermiso('EDITAR SOLICITUD REPUESTO'))
                                        <a href="{{ route('solicitudrepuesto.edit', $solicitud->idsolicitudesordenes) }}"
                                            class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition min-w-[80px]">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            <span class="truncate">Editar</span>
                                        </a>
                                    @endif
                                @endif

                                <!-- Botones para Repuestos Provincia (PURPURA) -->
                            @else
                                @if (\App\Helpers\PermisoHelper::tienePermiso('VER SOLICITUD REPUESTO'))
                                    <a href="{{ route('solicitudrepuestoprovincia.show', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-secondary text-white rounded hover:bg-gray-600 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span class="truncate">Ver</span>
                                    </a>
                                @endif

                                @if (\App\Helpers\PermisoHelper::tienePermiso('VER OPCIONES REPUESTO'))
                                    <a href="{{ route('solicitudrepuestoprovincia.opciones', $solicitud->idsolicitudesordenes) }}"
                                        class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-secondary text-white rounded hover:bg-gray-600 transition min-w-[80px]">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                                            </path>
                                        </svg>
                                        <span class="truncate">Opciones</span>
                                    </a>
                                @endif

                                <!-- BOTÓN GESTIONAR REMOVIDO -->

                                @if ($solicitud->estado == 'pendiente')
                                    @if (\App\Helpers\PermisoHelper::tienePermiso('EDITAR SOLICITUD REPUESTO'))
                                        <a href="{{ route('solicitudrepuestoprovincia.edit', $solicitud->idsolicitudesordenes) }}"
                                            class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 text-sm bg-secondary text-white rounded hover:bg-gray-600 transition min-w-[80px]">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            <span class="truncate">Editar</span>
                                        </a>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">
                            @if (request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                                No se encontraron solicitudes con los filtros aplicados
                            @else
                                No hay solicitudes registradas
                            @endif
                        </h3>
                        <p class="mt-1 text-gray-500 mb-4">
                            @if (request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                                Intenta ajustar los filtros de búsqueda
                            @else
                                Parece que no hay ninguna solicitud en el sistema.
                            @endif
                        </p>
                        <button id="openModalBtnEmpty"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear primera solicitud
                        </button>
                        @if (request()->anyFilled(['tipo', 'estado', 'urgencia', 'search']))
                            <a href="{{ route('solicitudarticulo.index') }}"
                                class="ml-2 inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                                Limpiar filtros
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Paginación con parámetros de filtro -->
            @if ($solicitudes->hasPages())
                <div class="mt-8">
                    {{ $solicitudes->appends(request()->query())->links() }}
                </div>
            @endif

    <script src="{{ asset('assets/js/solicitud/solicitudarticulo.js') }}"></script>
    </x-layout.default>
