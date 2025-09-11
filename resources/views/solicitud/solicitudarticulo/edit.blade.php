<x-layout.default>
    <!-- jQuery (requerido por Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 py-8 text-white">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">Editar Solicitud de Artículos</h1>
            <p class="text-blue-100 max-w-2xl mx-auto">Modifique los datos de la solicitud según sea necesario</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-6x2 mx-auto px-4 py-8 -mt-10">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-800">
            <!-- Form Content -->
            <form id="solicitudForm" method="POST" action="{{ route('solicitudarticulo.update', $solicitud->idSolicitud) }}" class="p-6 space-y-8">
                @csrf
                @method('PUT')

                <!-- Sección 1: Información del Solicitante -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 p-2 rounded-lg dark:bg-blue-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Información del Solicitante</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Campo Código de Solicitud (solo lectura) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m9 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Código de Solicitud
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m9 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="text" readonly
                                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    id="codigoSolicitud" name="codigoSolicitud" value="{{ $solicitud->codigoSolicitud }}">
                            </div>
                        </div>

                        <!-- Campo Nombre (solo lectura) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Nombre Completo
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" readonly
                                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    value="{{ auth()->user()->Nombre }} {{ auth()->user()->apellidoPaterno }} {{ auth()->user()->apellidoMaterno }}">
                                <input type="hidden" name="nombre" value="{{ auth()->user()->Nombre }} {{ auth()->user()->apellidoPaterno }} {{ auth()->user()->apellidoMaterno }}">
                            </div>
                        </div>

                        <!-- Campo Departamento (solo lectura) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Departamento/Área
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @if(auth()->user()->tipoArea)
                                <input type="text" readonly
                                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    value="{{ auth()->user()->tipoArea->nombre }}">
                                <input type="hidden" name="departamento" value="{{ auth()->user()->tipoArea->nombre }}">
                                @else
                                <input type="text" readonly
                                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    value="No asignado">
                                <input type="hidden" name="departamento" value="">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Detalles del Artículo -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-100 p-2 rounded-lg dark:bg-purple-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Detalles del Artículo</h2>
                    </div>

                    <!-- Contenedor para artículos -->
                    <div id="articulos-container">
                        @foreach($solicitud->solicitudArticulos as $index => $articuloSolicitud)
                        <div class="articulo-item bg-gray-50 p-6 rounded-xl dark:bg-gray-700/30 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Selección de Artículo con Select2 -->
                                <div class="md:col-span-2">
                                    <label for="articulo_id_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Artículo/Producto
                                        </span>
                                    </label>
                                    <select id="articulo_id_{{ $index }}" name="articulos[{{ $index }}][articulo_id]" required
                                        class="select2-articulo w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Seleccione un artículo...</option>
                                        @foreach($articulos as $articulo)
                                        <option value="{{ $articulo->idArticulos }}"
                                            data-stock="{{ $articulo->stock_total }}"
                                            data-codigo="{{ $articulo->codigo_barras }}"
                                            data-precio="{{ $articulo->precio_compra }}"
                                            data-tipo="{{ $articulo->tipoArticulo->nombre ?? 'N/A' }}"
                                            {{ $articuloSolicitud->idArticulo == $articulo->idArticulos ? 'selected' : '' }}>
                                            {{ $articulo->nombre }} (Código: {{ $articulo->codigo_barras }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Cantidad -->
                                <div>
                                    <label for="cantidad_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            Cantidad
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 000 4zM10 18a2 2 0 110-4 2 2 0 000 4z" />
                                            </svg>
                                        </div>
                                        <input type="number" id="cantidad_{{ $index }}" name="articulos[{{ $index }}][cantidad]" min="1" 
                                            value="{{ $articuloSolicitud->cantidad }}" required
                                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <small class="stock-disponible text-xs text-gray-500 dark:text-gray-400 mt-1 block"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional del artículo seleccionado -->
                            <div class="info-articulo mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Código:</span>
                                        <span class="codigo-articulo block font-medium">{{ $articuloSolicitud->articulo->codigo_barras ?? '' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Precio:</span>
                                        <span class="precio-articulo block font-medium">
                                            @if(isset($articuloSolicitud->articulo->precio_compra))
                                                S/ {{ $articuloSolicitud->articulo->precio_compra }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Tipo de artículo:</span>
                                        <span class="unidad-articulo block font-medium">
                                            {{ $articuloSolicitud->articulo->tipoArticulo->nombre ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mt-6">
                                <label for="descripcion_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        Descripción/Especificaciones
                                    </span>
                                </label>
                                <textarea id="descripcion_{{ $index }}" name="articulos[{{ $index }}][descripcion]" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Proporcione detalles adicionales como modelo, color, características técnicas...">{{ $articuloSolicitud->descripcion }}</textarea>
                            </div>
                            
                            <!-- Botón para eliminar artículo -->
                            @if($index > 0)
                            <div class="mt-4 flex justify-end">
                                <button type="button" class="eliminar-articulo text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar artículo
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Botón para agregar más artículos -->
                    <button type="button" id="agregar-articulo"
                        class="flex items-center justify-center w-full py-2 px-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 transition-colors dark:border-gray-600 dark:text-gray-400 dark:hover:border-blue-500 dark:hover:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Agregar otro artículo
                    </button>
                </div>

                <!-- Sección 3: Información Adicional -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-yellow-100 p-2 rounded-lg dark:bg-yellow-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Información Adicional</h2>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl dark:bg-gray-700/30">
                        <!-- Urgencia -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Nivel de Urgencia
                                </span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Opción Baja -->
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer transition-colors {{ $solicitud->nivelUrgencia == 1 ? 'border-blue-500 bg-blue-50 dark:border-blue-500 dark:bg-blue-900/20' : 'border-gray-300 hover:border-blue-500 dark:border-gray-600 dark:hover:border-blue-500' }}">
                                    <input type="radio" name="urgencia" value="1" class="hidden" {{ $solicitud->nivelUrgencia == 1 ? 'checked' : '' }}>
                                    <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 dark:bg-blue-800 dark:text-blue-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-gray-200">Baja</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Sin prisa, puede esperar</p>
                                    </div>
                                </label>

                                <!-- Opción Media -->
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer transition-colors {{ $solicitud->nivelUrgencia == 2 ? 'border-yellow-500 bg-yellow-50 dark:border-yellow-500 dark:bg-yellow-900/20' : 'border-gray-300 hover:border-yellow-500 dark:border-gray-600 dark:hover:border-yellow-500' }}">
                                    <input type="radio" name="urgencia" value="2" class="hidden" {{ $solicitud->nivelUrgencia == 2 ? 'checked' : '' }}>
                                    <div class="bg-yellow-100 text-yellow-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 dark:bg-yellow-800 dark:text-yellow-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-gray-200">Media</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Necesario pronto</p>
                                    </div>
                                </label>

                                <!-- Opción Alta -->
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer transition-colors {{ $solicitud->nivelUrgencia == 3 ? 'border-red-500 bg-red-50 dark:border-red-500 dark:bg-red-900/20' : 'border-gray-300 hover:border-red-500 dark:border-gray-600 dark:hover:border-red-500' }}">
                                    <input type="radio" name="urgencia" value="3" class="hidden" {{ $solicitud->nivelUrgencia == 3 ? 'checked' : '' }}>
                                    <div class="bg-red-100 text-red-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 dark:bg-red-800 dark:text-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-gray-200">Alta</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Urgente, prioridad máxima</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Fecha Requerida -->
                        <div>
                            <label for="fecha_requerida" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Fecha Requerida
                                </span>
                            </label>
                            <div class="relative max-w-xs">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="date" id="fecha_requerida" name="fecha_requerida"
                                    value="{{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('Y-m-d') }}"
                                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 4: Notas Adicionales -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-100 p-2 rounded-lg dark:bg-green-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Notas Adicionales</h2>
                    </div>

                    <div class="relative">
                        <label for="notas" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Comentarios o Información Adicional
                            </span>
                        </label>
                        <textarea id="notas" name="notas" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="¿Alguna información adicional que debamos considerar?">{{ $solicitud->comentario }}</textarea>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                    <a href="{{ route('solicitudarticulo.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 transition-colors text-center">
                        <span class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </span>
                    </a>
                    <button type="submit" id="submitBtn" class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <span class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Actualizar Solicitud
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Agrega este modal para mostrar mensajes -->
    <div id="responseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full dark:bg-gray-800">
            <div id="modalContent" class="text-center">
                <div id="modalIcon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-white mb-2">Éxito</h3>
                <p id="modalMessage" class="text-sm text-gray-500 dark:text-gray-300">La solicitud se ha actualizado correctamente.</p>
                <div class="mt-4">
                    <button id="modalCloseBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Definir funciones en el ámbito global
        window.formatArticulo = function(articulo) {
            if (!articulo.id) {
                return articulo.text;
            }

            var $container = $(
                '<div class="flex items-center">' +
                '<div class="flex-1">' +
                '<div class="font-medium">' + articulo.text + '</div>' +
                '<div class="text-xs text-gray-500">Código: ' + $(articulo.element).data('codigo') + '</div>' +
                '</div>' +
                '<div class="text-xs text-gray-400 ml-2">Stock: ' + $(articulo.element).data('stock') + '</div>' +
                '</div>'
            );
            return $container;
        };

        window.formatArticuloSelection = function(articulo) {
            if (!articulo.id) {
                return articulo.text;
            }
            return articulo.text.split(' (')[0]; // Mostrar solo el nombre sin el código
        };

        // Función para inicializar Select2 en un elemento
        function initSelect2(element) {
            $(element).select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccione un artículo...',
                allowClear: true,
                width: '100%',
                templateResult: window.formatArticulo,
                templateSelection: window.formatArticuloSelection
            });
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2 para todos los selects de artículos
            document.querySelectorAll('.select2-articulo').forEach(function(select) {
                initSelect2(select);
                
                // Evento change para actualizar información del artículo
                $(select).on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const articuloItem = $(this).closest('.articulo-item');
                    const infoDiv = articuloItem.find('.info-articulo');
                    const stockDisponible = articuloItem.find('.stock-disponible');

                    if (selectedOption.val()) {
                        articuloItem.find('.codigo-articulo').text(selectedOption.data('codigo'));
                        articuloItem.find('.precio-articulo').text('S/ ' + selectedOption.data('precio'));
                        articuloItem.find('.unidad-articulo').text(selectedOption.data('tipo'));
                        stockDisponible.text('Stock disponible: ' + selectedOption.data('stock'));
                        infoDiv.removeClass('hidden');

                        // Establecer cantidad máxima según stock
                        articuloItem.find('input[type="number"]').attr('max', selectedOption.data('stock'));
                    } else {
                        infoDiv.addClass('hidden');
                        stockDisponible.text('');
                    }
                });
            });

            // Función para agregar artículos adicionales
            document.getElementById('agregar-articulo').addEventListener('click', function() {
                const contenedor = document.getElementById('articulos-container');
                const count = contenedor.children.length;
                const nuevoIndex = count;

                const nuevoArticulo = document.createElement('div');
                nuevoArticulo.className = 'articulo-item bg-white p-6 rounded-xl shadow-sm dark:bg-gray-700 mb-4';
                nuevoArticulo.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Selección de Artículo con Select2 -->
                        <div class="md:col-span-2">
                            <label for="articulo_id_${nuevoIndex}" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Artículo/Producto
                                </span>
                            </label>
                            <select id="articulo_id_${nuevoIndex}" name="articulos[${nuevoIndex}][articulo_id]" required
                                class="select2-articulo w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Seleccione un artículo...</option>
                                @foreach($articulos as $articulo)
                                    <option value="{{ $articulo->idArticulos }}"
                                        data-stock="{{ $articulo->stock_total }}"
                                        data-codigo="{{ $articulo->codigo_barras }}"
                                        data-precio="{{ $articulo->precio_compra }}"
                                        data-tipo="{{ $articulo->tipoArticulo->nombre ?? 'N/A' }}">
                                        {{ $articulo->nombre }} (Código: {{ $articulo->codigo_barras }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cantidad -->
                        <div>
                            <label for="cantidad_${nuevoIndex}" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Cantidad
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </div>
                                <input type="number" id="cantidad_${nuevoIndex}" name="articulos[${nuevoIndex}][cantidad]" min="1" value="1" required
                                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <small class="stock-disponible text-xs text-gray-500 dark:text-gray-400 mt-1 block"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional del artículo seleccionado -->
                    <div class="info-articulo mt-4 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Código:</span>
                                <span class="codigo-articulo block font-medium"></span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Precio:</span>
                                <span class="precio-articulo block font-medium"></span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Tipo de artículo:</span>
                                <span class="unidad-articulo block font-medium"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-6">
                        <label for="descripcion_${nuevoIndex}" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                Descripción/Especificaciones
                            </span>
                        </label>
                        <textarea id="descripcion_${nuevoIndex}" name="articulos[${nuevoIndex}][descripcion]" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Proporcione detalles adicionales como modelo, color, características técnicas..."></textarea>
                    </div>
                    
                    <!-- Botón para eliminar artículo -->
                    <div class="mt-4 flex justify-end">
                        <button type="button" class="eliminar-articulo text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar artículo
                        </button>
                    </div>
                `;

                contenedor.appendChild(nuevoArticulo);

                // Inicializar Select2 para el nuevo select
                initSelect2(`#articulo_id_${nuevoIndex}`);

                // Agregar evento al botón de eliminar
                $(nuevoArticulo).find('.eliminar-articulo').on('click', function() {
                    $(nuevoArticulo).remove();
                });
            });

            // Agregar eventos a los botones de eliminar existentes
            document.querySelectorAll('.eliminar-articulo').forEach(function(button) {
                button.addEventListener('click', function() {
                    if (document.querySelectorAll('.articulo-item').length > 1) {
                        this.closest('.articulo-item').remove();
                    } else {
                        alert('Debe haber al menos un artículo en la solicitud.');
                    }
                });
            });
        });

        // Función para mostrar el modal de respuesta
        function showModal(title, message, isSuccess) {
            const modal = document.getElementById('responseModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalIcon = document.getElementById('modalIcon');

            modalTitle.textContent = title;
            modalMessage.textContent = message;

            // Cambiar el icono según si es éxito o error
            modalIcon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4';
            if (isSuccess) {
                modalIcon.classList.add('bg-green-100');
                modalIcon.innerHTML = '<svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
            } else {
                modalIcon.classList.add('bg-red-100');
                modalIcon.innerHTML = '<svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
            }

            modal.classList.remove('hidden');
        }

        // Cerrar modal
        document.getElementById('modalCloseBtn').addEventListener('click', function() {
            document.getElementById('responseModal').classList.add('hidden');
        });
    </script>
</x-layout.default>