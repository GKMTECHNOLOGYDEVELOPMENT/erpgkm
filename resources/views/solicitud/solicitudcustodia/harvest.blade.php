<x-layout.default>
    <!-- Agregar CDN de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Estilos personalizados para Select2 */
        .select2-container--default .select2-selection--single {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            height: 52px;
            background: white;
            transition: all 0.3s ease;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #6ee7b7;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 50px;
            padding-left: 16px;
            font-size: 14px;
            color: #374151;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px;
            right: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #9ca3af transparent transparent transparent;
            transition: transform 0.3s ease;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #9ca3af transparent;
            transform: translateY(-50%) rotate(180deg);
        }

        .select2-dropdown {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #f0fdf4;
            color: #065f46;
        }

        .select2-search--dropdown .select2-search__field {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            margin: 8px;
            width: calc(100% - 16px) !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #10b981;
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Notificación AJAX -->
        <div id="ajax-notification"
            class="hidden rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 flex items-center gap-3 shadow-sm mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="ajax-message" class="font-medium"></span>
            <button class="ml-auto text-emerald-600 hover:text-emerald-800" onclick="hideNotification()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Header -->
        <div
            class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-2xl border border-green-200 shadow-lg mb-8 overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 p-6">
                <!-- Left Content -->
                <div class="flex-1">
                    <!-- Main Title -->
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-1">
                                Sistema Harvest
                            </h1>
                            <p class="text-lg text-emerald-700 font-semibold">
                                Retiro de Repuestos
                            </p>
                        </div>
                    </div>

                    <!-- Info Cards -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Código Card -->
                        <div
                            class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-shadow duration-200">
                            <div class="p-1.5 bg-blue-100 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Código</span>
                                <p class="text-sm font-bold text-gray-800">#{{ $custodia->codigocustodias }}</p>
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="w-px h-6 bg-gray-300"></div>

                        <!-- Equipo Card -->
                        <div
                            class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-shadow duration-200">
                            <div class="p-1.5 bg-green-100 rounded-lg">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-green-600 uppercase tracking-wide">Equipo</span>
                                <p class="text-sm font-bold text-gray-800">{{ $custodia->marca->nombre ?? '—' }}
                                    {{ $custodia->modelo->nombre ?? '' }}</p>
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="w-px h-6 bg-gray-300"></div>

                        <!-- Serie Card -->
                        <div
                            class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl shadow-sm border border-purple-100 hover:shadow-md transition-shadow duration-200">
                            <div class="p-1.5 bg-purple-100 rounded-lg">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-purple-600 uppercase tracking-wide">Serie</span>
                                <p class="text-sm font-bold text-gray-800">{{ $custodia->serie ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Button -->
                <div class="flex-shrink-0">
                    <a href="{{ route('solicitudcustodia.opciones', ['id' => $custodia->id]) }}"
                        class="btn bg-warning text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center gap-3 group">
                        <div
                            class="p-1 bg-white/20 rounded-lg group-hover:rotate-180 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </div>
                        <span class="font-medium">Volver a Opciones</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Formulario para retirar repuestos -->
            <div class="panel rounded-2xl shadow-lg overflow-hidden border border-green-100">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-5">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        Retirar Repuesto
                    </h2>
                </div>

                <form id="form-retiro" class="p-6 space-y-6 bg-gradient-to-br from-white to-green-50/30">
                    @csrf

                    <!-- Campo oculto para id_articulo -->
                    <input type="hidden" id="id_articulo" name="id_articulo">

                    <!-- Selección de repuesto por código CON SELECT2 -->
                    <div class="group">
                        <label for="codigo_repuesto"
                            class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Código de Repuesto
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="codigo_repuesto" name="codigo_repuesto" class="w-full">
                            <option value="" class="text-gray-400">Seleccionar código de repuesto</option>
                            @foreach ($repuestos as $repuesto)
                                <option value="{{ $repuesto->codigo_repuesto }}"
                                    data-id-articulo="{{ $repuesto->idArticulos }}"
                                    data-modelos="{{ $repuesto->modelos->pluck('nombre')->implode(', ') }}"
                                    data-subcategoria="{{ $repuesto->subcategoria->nombre ?? 'N/A' }}">
                                    {{ $repuesto->codigo_repuesto }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Información del repuesto seleccionado -->
                    <div id="info-repuesto" class="hidden transform transition-all duration-500 ease-out">
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 p-5 rounded-2xl border-2 border-green-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-gray-800">Información del Repuesto</h4>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start gap-3">
                                    <div class="p-1.5 bg-white rounded-lg shadow-sm mt-0.5">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Modelos compatibles:</span>
                                        <span id="info-modelos" class="ml-2 text-gray-800 font-medium"></span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="p-1.5 bg-white rounded-lg shadow-sm mt-0.5">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Subcategoría:</span>
                                        <span id="info-subcategoria" class="ml-2 text-gray-800 font-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="group">
                        <label for="cantidad"
                            class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Cantidad a retirar
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="cantidad" name="cantidad" min="1" value="1"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-white shadow-sm hover:border-green-300 group-hover:shadow-md [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex flex-col">
                                <button type="button" onclick="document.getElementById('cantidad').stepUp()"
                                    class="text-gray-400 hover:text-green-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                                <button type="button" onclick="document.getElementById('cantidad').stepDown()"
                                    class="text-gray-400 hover:text-green-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="group">
                        <label for="observaciones"
                            class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Observaciones
                        </label>
                        <textarea id="observaciones" name="observaciones" rows="3"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-white shadow-sm hover:border-green-300 group-hover:shadow-md resize-none"
                            placeholder="Motivo del retiro, detalles específicos, destinatario, etc."></textarea>
                    </div>

                    <!-- Botón de retiro -->
                    <div class="pt-2">
                        @if(\App\Helpers\PermisoHelper::tienePermiso('RETIRAR REPUESTO HAVERST CUSTODIA'))
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-3 group">
                            <div
                                class="p-1 bg-white/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-lg">Retirar Repuesto</span>
                        </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Lista de retiros realizados -->
            <div
                class="panel rounded-2xl shadow-xl overflow-hidden border border-blue-100 bg-gradient-to-br from-white to-blue-50/30">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-5">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        Historial de Retiros
                        <span class="bg-white/20 text-white/90 text-sm font-medium px-3 py-1 rounded-full ml-2">
                            {{ $retiros->count() }}
                        </span>
                    </h2>
                </div>

                <div class="p-6">
                    @if ($retiros->count() > 0)
                        <div class="max-h-96 overflow-y-auto pr-3 space-y-4 custom-scrollbar">
                            @foreach ($retiros as $retiro)
                                <div
                                    class="group bg-white border-2 border-blue-100 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 transform hover:scale-[1.02]">
                                    <!-- Header con información principal -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="p-2 bg-blue-50 rounded-lg">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-xs font-semibold text-gray-500 block">Retirado
                                                        por</span>
                                                    <span class="text-sm font-medium text-gray-800 truncate block">
                                                        {{ $retiro->responsable->Nombre ?? 'N/A' }}
                                                        {{ $retiro->responsable->apellidoPaterno ?? '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-green-50 rounded-lg">
                                                    <svg class="w-4 h-4 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-semibold text-gray-500 block">Fecha</span>
                                                    <span class="text-sm font-medium text-gray-800">
                                                        {{ $retiro->created_at->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <span
                                                class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold px-3 py-2 rounded-full shadow-lg whitespace-nowrap">
                                                {{ $retiro->cantidad_retirada }} unidades
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Información del artículo -->
                                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                                        <div class="bg-gray-50 rounded-xl p-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                <span class="text-xs font-semibold text-gray-600">Modelos
                                                    compatibles</span>
                                            </div>
                                            <p class="text-sm text-gray-800 font-medium">
                                                @if ($retiro->articulo->modelos->count() > 0)
                                                    {{ $retiro->articulo->modelos->pluck('nombre')->implode(', ') }}
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="bg-gray-50 rounded-xl p-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-purple-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                <span class="text-xs font-semibold text-gray-600">Subcategoría</span>
                                            </div>
                                            <p class="text-sm text-gray-800 font-medium">
                                                {{ $retiro->articulo->subcategoria->nombre ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Observaciones -->
                                    @if ($retiro->observaciones)
                                        <div class="mb-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-amber-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                </svg>
                                                <span class="text-xs font-semibold text-gray-600">Observaciones</span>
                                            </div>
                                            <p
                                                class="text-sm text-gray-700 bg-amber-50 border border-amber-100 rounded-xl p-3">
                                                {{ $retiro->observaciones }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Botón de anular -->
                                    <div class="flex justify-end pt-4">
                                   @if(\App\Helpers\PermisoHelper::tienePermiso('ANULAR RETIRO HAVERTS CUSTODIA'))
                                    <button type="button" onclick="anularRetiro({{ $retiro->id }})"
                                            class="btn bg-danger text-white font-medium py-2.5 px-5 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2 group">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 group-hover:scale-110 transition-transform"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Anular Retiro
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 mx-auto mb-4 bg-blue-50 rounded-2xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay retiros registrados</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto">
                                Los retiros de repuestos aparecerán aquí cuando sean realizados.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar jQuery y Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Inicializar Select2
        $(document).ready(function() {
            $('#codigo_repuesto').select2({
                placeholder: 'Seleccionar código de repuesto',
                allowClear: true,
                width: '100%',
                theme: 'default',
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });

            // Manejar el evento change de Select2
            $('#codigo_repuesto').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const idArticulo = selectedOption.data('id-articulo');
                const modelos = selectedOption.data('modelos');
                const subcategoria = selectedOption.data('subcategoria');

                if (idArticulo) {
                    $('#id_articulo').val(idArticulo);
                    $('#info-modelos').text(modelos);
                    $('#info-subcategoria').text(subcategoria);
                    $('#info-repuesto').removeClass('hidden');
                } else {
                    $('#id_articulo').val('');
                    $('#info-repuesto').addClass('hidden');
                }
            });
        });

        // Ocultar notificación
        function hideNotification() {
            const notification = document.getElementById('ajax-notification');
            if (notification) {
                notification.classList.add('hidden');
            }
        }

        // Mostrar notificación
        function showNotification(message, isSuccess = true) {
            const notification = document.getElementById('ajax-notification');
            const messageElement = document.getElementById('ajax-message');

            if (!notification || !messageElement) return;

            messageElement.textContent = message;

            if (isSuccess) {
                notification.classList.remove('bg-red-50', 'border-red-200', 'text-red-800');
                notification.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
            } else {
                notification.classList.remove('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
                notification.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
            }

            notification.classList.remove('hidden');

            setTimeout(hideNotification, 5000);
        }

        // Anular retiro
        function anularRetiro(idRetiro) {
            if (!confirm('¿Estás seguro de anular este retiro?')) {
                return;
            }

            fetch("{{ route('solicitudcustodia.anular-retiro', '') }}/" + idRetiro, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, true);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotification(data.message, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error de conexión', false);
                });
        }

        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            const formRetiro = document.getElementById('form-retiro');

            if (formRetiro) {
                formRetiro.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const btnSubmit = this.querySelector('button[type="submit"]');
                    const originalText = btnSubmit.innerHTML;

                    // Validar campos requeridos
                    const codigoRepuesto = document.getElementById('codigo_repuesto').value;
                    const cantidad = document.getElementById('cantidad').value;
                    const idArticulo = document.getElementById('id_articulo').value;

                    if (!codigoRepuesto) {
                        showNotification('Por favor selecciona un código de repuesto', false);
                        return;
                    }

                    if (!idArticulo) {
                        showNotification('Error: No se pudo obtener el ID del artículo', false);
                        return;
                    }

                    if (!cantidad || cantidad < 1) {
                        showNotification('Por favor ingresa una cantidad válida', false);
                        return;
                    }

                    // Mostrar loader
                    btnSubmit.innerHTML = '<span>Procesando...</span>';
                    btnSubmit.disabled = true;

                    fetch("{{ route('solicitudcustodia.retirar-repuesto', $custodia->id) }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showNotification(data.message, true);
                                this.reset();
                                // Limpiar también el campo oculto
                                document.getElementById('id_articulo').value = '';
                                // Resetear Select2
                                $('#codigo_repuesto').val(null).trigger('change');

                                // Recargar la página para actualizar la lista
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showNotification(data.message, false);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Error de conexión: ' + error.message, false);
                        })
                        .finally(() => {
                            btnSubmit.innerHTML = originalText;
                            btnSubmit.disabled = false;
                        });
                });
            }
        });
    </script>
</x-layout.default>
