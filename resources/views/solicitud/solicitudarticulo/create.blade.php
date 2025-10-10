<x-layout.default>
    <!-- jQuery (requerido por Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 py-8 text-white">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">Solicitud de Artículos</h1>
            <p class="text-blue-100 max-w-2xl mx-auto">Complete este formulario para solicitar los productos o
                materiales que necesita</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-6x2 mx-auto px-4 py-8 -mt-10">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-800">
            <!-- Form Content -->
            <form id="solicitudForm" method="POST" class="p-6 space-y-8">
                @csrf

                <!-- Sección 1: Información del Solicitante - Diseño Moderno -->
                <div class="relative group">
                    <div
                        class="absolute -left-6 top-0 w-2 h-full bg-gradient-to-b from-blue-500 to-purple-600 rounded-full transform group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <div
                        class="bg-gradient-to-br from-white to-blue-50 rounded-2xl p-8 shadow-lg border border-blue-100 dark:from-gray-800 dark:to-blue-900/20 dark:border-blue-800/30">
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="bg-primary p-3 rounded-2xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-blue-600 bg-clip-text text-transparent dark:from-white dark:to-cyan-300">
                                    👤 Información del Solicitante
                                </h2>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Datos personales y del departamento</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Campo Código de Solicitud -->
                            <div class="space-y-3">
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                    🏷️ Código de Solicitud
                                </label>
                                <div class="flex space-x-3">
                                    <div class="relative flex-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-mono">#</span>
                                        </div>
                                        <input type="text" readonly
                                            class="pl-10 w-full px-4 py-4 border-2 border-gray-200 rounded-xl bg-gray-50 font-mono text-lg font-bold text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all hover:border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                            id="codigoSolicitud" name="codigoSolicitud" value="">
                                    </div>
                                    <button type="button" id="regenerarCodigo"
                                        class="px-5 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95"
                                        title="Generar nuevo código">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Campo Nombre -->
                            <div class="space-y-3">
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                    👤 Nombre Completo
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" readonly
                                        class="pl-12 w-full px-4 py-4 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all hover:border-blue-300"
                                        value="{{ auth()->user()->Nombre }} {{ auth()->user()->apellidoPaterno }} {{ auth()->user()->apellidoMaterno }}">
                                </div>
                            </div>

                            <!-- Campo Departamento -->
                            <div class="space-y-3">
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                    🏢 Departamento/Área
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @if (auth()->user()->tipoArea)
                                        <input type="text" readonly
                                            class="pl-12 w-full px-4 py-4 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all hover:border-blue-300"
                                            value="{{ auth()->user()->tipoArea->nombre }}">
                                    @else
                                        <input type="text" readonly
                                            class="pl-12 w-full px-4 py-4 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            value="No asignado">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Detalles del Artículo - Diseño Mejorado -->
                <div class="relative group">
                    <div
                        class="absolute -left-6 top-0 w-2 h-full bg-gradient-to-b from-purple-500 to-pink-600 rounded-full transform group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <div
                        class="bg-gradient-to-br from-white to-purple-50 rounded-2xl p-8 shadow-lg border border-purple-100 dark:from-gray-800 dark:to-purple-900/20 dark:border-purple-800/30">
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="bg-secondary p-3 rounded-2xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h2
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-purple-600 bg-clip-text text-transparent dark:from-white dark:to-purple-300">
                                    📦 Detalles del Artículo
                                </h2>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Seleccione los productos requeridos
                                </p>
                            </div>
                        </div>

                        <!-- Artículo Principal -->
                        <div
                            class="bg-white/80 rounded-2xl p-6 border-2 border-dashed border-purple-200 dark:bg-gray-700/50 dark:border-purple-800 transition-all hover:border-purple-300">
                            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                                <!-- Selección de Artículo -->
                                <div class="xl:col-span-2 space-y-3">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                        🎯 Artículo/Producto
                                    </label>
                                    <select id="articulo_id" name="articulo_id" required
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all hover:border-purple-300">
                                        <option value="">Seleccione un artículo...</option>
                                        @foreach ($articulos as $articulo)
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
                                <div class="space-y-3">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                        🔢 Cantidad
                                    </label>
                                    <div class="relative">
                                        <input type="number" id="cantidad" name="cantidad" min="1"
                                            value="1" required
                                            class="pl-12 w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all hover:border-purple-300">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 000 4zM10 18a2 2 0 110-4 2 2 0 000 4z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <small id="stock-disponible"
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"></small>
                                </div>
                            </div>

                            <!-- Información del artículo -->
                            <div id="info-articulo" class="mt-6 hidden animate-fade-in">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 dark:from-purple-900/20 dark:to-pink-900/20">
                                    <div class="text-center p-3 bg-white rounded-xl shadow-sm dark:bg-gray-600">
                                        <span
                                            class="text-sm font-semibold text-purple-600 dark:text-purple-300">Código:</span>
                                        <span id="codigo-articulo"
                                            class="block font-bold text-gray-800 dark:text-white text-lg mt-1"></span>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-xl shadow-sm dark:bg-gray-600">
                                        <span
                                            class="text-sm font-semibold text-purple-600 dark:text-purple-300">Precio:</span>
                                        <span id="precio-articulo"
                                            class="block font-bold text-gray-800 dark:text-white text-lg mt-1"></span>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-xl shadow-sm dark:bg-gray-600">
                                        <span
                                            class="text-sm font-semibold text-purple-600 dark:text-purple-300">Tipo:</span>
                                        <span id="unidad-articulo"
                                            class="block font-bold text-gray-800 dark:text-white text-lg mt-1"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mt-6 space-y-3">
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                    📝 Descripción/Especificaciones
                                </label>
                                <textarea id="descripcion" name="descripcion" rows="3"
                                    class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all resize-none hover:border-purple-300"
                                    placeholder="💡 Proporcione detalles adicionales como modelo, color, características técnicas..."></textarea>
                            </div>
                        </div>

                        <!-- Botón para agregar más artículos -->
                        <button type="button" id="agregar-articulo"
                            class="w-full mt-6 py-4 px-6 border-2 border-dashed border-purple-300 rounded-2xl text-purple-600 hover:border-purple-500 hover:bg-purple-50 transition-all duration-300 dark:border-purple-600 dark:text-purple-400 dark:hover:bg-purple-900/20 group transform hover:scale-105">
                            <div class="flex items-center justify-center space-x-3">
                                <div
                                    class="bg-purple-100 p-3 rounded-xl group-hover:bg-purple-200 transition-colors dark:bg-purple-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="font-bold text-lg">➕ Agregar otro artículo</span>
                            </div>
                        </button>

                        <!-- Contenedor para artículos adicionales -->
                        <div id="articulos-adicionales" class="space-y-6 mt-6"></div>
                    </div>
                </div>

                <!-- Sección 3: Información Adicional - Diseño Renovado -->
                <div class="relative group">
                    <div
                        class="absolute -left-6 top-0 w-2 h-full bg-gradient-to-b from-amber-500 to-orange-600 rounded-full transform group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <div
                        class="bg-gradient-to-br from-white to-amber-50 rounded-2xl p-8 shadow-lg border border-amber-100 dark:from-gray-800 dark:to-amber-900/20 dark:border-amber-800/30">
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="bg-gradient-to-r from-amber-500 to-orange-500 p-3 rounded-2xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h2
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-amber-600 bg-clip-text text-transparent dark:from-white dark:to-amber-300">
                                    ⚡ Información Adicional
                                </h2>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Prioridad y fechas importantes</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Urgencia -->
                            <div class="space-y-4">
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                    🚨 Nivel de Urgencia
                                </label>
                                <div class="space-y-4" x-data="{ urgencia: 'baja' }">
                                    <!-- Opción Baja -->
                                    <label
                                        class="flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                                        :class="{
                                            'border-blue-500 bg-blue-50 shadow-lg -translate-y-1': urgencia === 'baja',
                                            'border-gray-200 hover:border-blue-300 dark:border-gray-600': urgencia !== 'baja'
                                        }"
                                        @click="urgencia = 'baja'">
                                        <input type="radio" name="urgencia" value="baja" class="hidden"
                                            x-model="urgencia">
                                        <div
                                            class="bg-blue-100 text-blue-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 dark:bg-blue-800 dark:text-blue-200">
                                            <span class="text-xl">😊</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg">Baja</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Sin prisa, puede
                                                esperar</p>
                                        </div>
                                    </label>

                                    <!-- Opción Media -->
                                    <label
                                        class="flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                                        :class="{
                                            'border-yellow-500 bg-yellow-50 shadow-lg -translate-y-1': urgencia === 'media',
                                            'border-gray-200 hover:border-yellow-300 dark:border-gray-600': urgencia !== 'media'
                                        }"
                                        @click="urgencia = 'media'">
                                        <input type="radio" name="urgencia" value="media" class="hidden"
                                            x-model="urgencia">
                                        <div
                                            class="bg-yellow-100 text-yellow-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 dark:bg-yellow-800 dark:text-yellow-200">
                                            <span class="text-xl">😐</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg">Media</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Necesario pronto</p>
                                        </div>
                                    </label>

                                    <!-- Opción Alta -->
                                    <label
                                        class="flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                                        :class="{
                                            'border-red-500 bg-red-50 shadow-lg -translate-y-1': urgencia === 'alta',
                                            'border-gray-200 hover:border-red-300 dark:border-gray-600': urgencia !== 'alta'
                                        }"
                                        @click="urgencia = 'alta'">
                                        <input type="radio" name="urgencia" value="alta" class="hidden"
                                            x-model="urgencia">
                                        <div
                                            class="bg-red-100 text-red-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 dark:bg-red-800 dark:text-red-200">
                                            <span class="text-xl">😰</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg">Alta</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Urgente, prioridad
                                                máxima</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Fecha Requerida -->
                            <div class="space-y-4">
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                    📅 Fecha Requerida
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="date" id="fecha_requerida" name="fecha_requerida"
                                        class="pl-12 w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-lg font-semibold transition-all hover:border-amber-300">
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">📌 Seleccione la fecha en la que
                                    necesita los artículos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 4: Notas Adicionales -->
                <div class="relative group">
                    <div
                        class="absolute -left-6 top-0 w-2 h-full bg-gradient-to-b from-green-500 to-emerald-600 rounded-full transform group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <div
                        class="bg-gradient-to-br from-white to-emerald-50 rounded-2xl p-8 shadow-lg border border-emerald-100 dark:from-gray-800 dark:to-emerald-900/20 dark:border-emerald-800/30">
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="bg-success p-3 rounded-2xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h2
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-emerald-600 bg-clip-text text-transparent dark:from-white dark:to-emerald-300">
                                    💬 Notas Adicionales
                                </h2>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Información complementaria importante
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label
                                class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                📝 Comentarios o Información Adicional
                            </label>
                            <textarea id="notas" name="notas" rows="4"
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-lg transition-all resize-none hover:border-emerald-300"
                                placeholder="💡 ¿Alguna información adicional que debamos considerar? (ubicación específica, restricciones de horario, etc.)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción - Actualiza el botón de enviar -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                    <button type="button"
                        class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                        <span class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </span>
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <span class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Enviar Solicitud
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
                <div id="modalIcon"
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-white mb-2">Éxito</h3>
                <p id="modalMessage" class="text-sm text-gray-500 dark:text-gray-300">La solicitud se ha enviado
                    correctamente.</p>
                <div class="mt-4">
                    <button id="modalCloseBtn" type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
            '<div class="flex items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg transition-colors">' +
            '<div class="flex-1 min-w-0">' +
            '<div class="font-semibold text-gray-800 dark:text-white truncate">' + articulo.text.split(' (')[0] + '</div>' +
            '<div class="text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-2 mt-1">' +
            '<span>🔖 ' + $(articulo.element).data('codigo') + '</span>' +
            '<span>📦 Stock: ' + $(articulo.element).data('stock') + '</span>' +
            '<span>💰 S/ ' + $(articulo.element).data('precio') + '</span>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
        return $container;
    };

    window.formatArticuloSelection = function(articulo) {
        if (!articulo.id) {
            return articulo.text;
        }
        return articulo.text.split(' (')[0];
    };

    // Función para inicializar Select2 en un elemento
    function initSelect2(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            placeholder: '🔍 Buscar artículo...',
            allowClear: true,
            width: '100%',
            templateResult: window.formatArticulo,
            templateSelection: window.formatArticuloSelection,
            language: {
                noResults: function() {
                    return "❌ No se encontraron artículos";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
    }

    // Función para generar código único
    function generarCodigoSolicitud() {
        const timestamp = Date.now().toString(36);
        const random = Math.random().toString(36).substr(2, 5);
        return `SOL-${timestamp}-${random}`.toUpperCase();
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Generar código inicial
        $('#codigoSolicitud').val(generarCodigoSolicitud());

        // Evento para regenerar código
        $('#regenerarCodigo').on('click', function() {
            $('#codigoSolicitud').val(generarCodigoSolicitud());

            // Efecto visual
            $(this).addClass('animate-spin');
            setTimeout(() => {
                $(this).removeClass('animate-spin');
            }, 500);
        });

        // Inicializar Select2 para el artículo principal
        initSelect2('#articulo_id');

        // Evento change para Select2 principal
        $('#articulo_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const infoDiv = $('#info-articulo');
            const stockDisponible = $('#stock-disponible');

            if (selectedOption.val()) {
                $('#codigo-articulo').text(selectedOption.data('codigo'));
                $('#precio-articulo').text('S/ ' + parseFloat(selectedOption.data('precio')).toFixed(2));
                $('#unidad-articulo').text(selectedOption.data('tipo'));

                const stock = selectedOption.data('stock');
                stockDisponible.text('📦 Stock disponible: ' + stock);

                // Color según stock
                if (stock < 10) {
                    stockDisponible.removeClass('text-green-600 text-gray-600').addClass('text-red-600 font-semibold');
                } else if (stock < 50) {
                    stockDisponible.removeClass('text-green-600 text-red-600').addClass('text-yellow-600');
                } else {
                    stockDisponible.removeClass('text-red-600 text-yellow-600').addClass('text-green-600');
                }

                infoDiv.removeClass('hidden').addClass('animate-fade-in');
                $('#cantidad').attr('max', stock);

                // Efecto visual
                infoDiv.addClass('transform transition-all duration-500 scale-105');
                setTimeout(() => {
                    infoDiv.removeClass('scale-105');
                }, 300);
            } else {
                infoDiv.addClass('hidden');
                stockDisponible.text('').removeClass('text-red-600 text-yellow-600 text-green-600');
            }
        });

        // Validación de cantidad en tiempo real
        $('#cantidad').on('input', function() {
            const max = parseInt($(this).attr('max'));
            const current = parseInt($(this).val());
            const stockDisponible = $('#stock-disponible');

            if (current > max) {
                $(this).addClass('border-red-500 bg-red-50');
                stockDisponible.addClass('text-red-600 font-bold');
            } else {
                $(this).removeClass('border-red-500 bg-red-50');
            }
        });

        // Función para agregar artículos adicionales
        document.getElementById('agregar-articulo').addEventListener('click', function() {
            const contenedor = document.getElementById('articulos-adicionales');
            const count = contenedor.children.length + 1;

            const nuevoArticulo = document.createElement('div');
            nuevoArticulo.className = 'articulo-adicional bg-gradient-to-br from-white to-purple-50 p-6 rounded-2xl shadow-lg border-2 border-dashed border-purple-200 dark:from-gray-700 dark:to-purple-900/20 dark:border-purple-600 transform transition-all duration-500 hover:scale-105 hover:shadow-xl';
            nuevoArticulo.innerHTML = `
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-gray-700 dark:text-gray-300 flex items-center text-lg">
                        <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-2 rounded-xl mr-3">
                            📦
                        </span>
                        Artículo Adicional #${count}
                    </h4>
                    <button type="button" class="eliminar-articulo p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition-all duration-300 transform hover:scale-110 hover:rotate-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="xl:col-span-2 space-y-3">
                        <label for="articulo_id_${count}" class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                            🎯 Artículo/Producto
                        </label>
                        <select id="articulo_id_${count}" name="articulos_adicionales[${count}][articulo_id]" required
                            class="select2-articulo w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-all hover:border-purple-300">
                            <option value="">🔍 Buscar artículo...</option>
                            @foreach ($articulos as $articulo)
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
                    <div class="space-y-3">
                        <label for="cantidad_${count}" class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                            🔢 Cantidad
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 000 4zM10 18a2 2 0 110-4 2 2 0 000 4z" />
                                </svg>
                            </div>
                            <input type="number" id="cantidad_${count}" name="articulos_adicionales[${count}][cantidad]" min="1" value="1" required
                                class="pl-12 w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-all hover:border-purple-300 cantidad-input">
                            <small class="stock-disponible stock-disponible-${count} text-xs font-medium mt-2 block"></small>
                        </div>
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    <label for="descripcion_${count}" class="block text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                        📝 Descripción/Especificaciones
                    </label>
                    <textarea id="descripcion_${count}" name="articulos_adicionales[${count}][descripcion]" rows="2"
                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-all resize-none hover:border-purple-300"
                        placeholder="💡 Detalles adicionales como modelo, color, características técnicas..."></textarea>
                </div>
                <div class="info-articulo-${count} mt-4 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 dark:from-purple-900/20 dark:to-pink-900/20">
                        <div class="text-center p-3 bg-white rounded-xl shadow-sm dark:bg-gray-600">
                            <span class="text-sm font-semibold text-purple-600 dark:text-purple-300">Código:</span>
                            <span class="codigo-articulo block font-bold text-gray-800 dark:text-white text-lg mt-1"></span>
                        </div>
                        <div class="text-center p-3 bg-white rounded-xl shadow-sm dark:bg-gray-600">
                            <span class="text-sm font-semibold text-purple-600 dark:text-purple-300">Precio:</span>
                            <span class="precio-articulo block font-bold text-gray-800 dark:text-white text-lg mt-1"></span>
                        </div>
                        <div class="text-center p-3 bg-white rounded-xl shadow-sm dark:bg-gray-600">
                            <span class="text-sm font-semibold text-purple-600 dark:text-purple-300">Tipo:</span>
                            <span class="tipo-articulo block font-bold text-gray-800 dark:text-white text-lg mt-1"></span>
                        </div>
                    </div>
                </div>
            `;

            contenedor.appendChild(nuevoArticulo);

            // Efecto de aparición
            $(nuevoArticulo).hide().slideDown(400);

            // Inicializar Select2 para el nuevo select
            initSelect2(`#articulo_id_${count}`);

            // Evento change para el nuevo Select2
            $(`#articulo_id_${count}`).on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const stockSpan = $(this).closest('.grid').find(`.stock-disponible-${count}`);
                const infoDiv = $(this).closest('.articulo-adicional').find(`.info-articulo-${count}`);

                if (selectedOption.val()) {
                    $(this).closest('.articulo-adicional').find('.codigo-articulo').text(selectedOption.data('codigo'));
                    $(this).closest('.articulo-adicional').find('.precio-articulo').text('S/ ' + parseFloat(selectedOption.data('precio')).toFixed(2));
                    $(this).closest('.articulo-adicional').find('.tipo-articulo').text(selectedOption.data('tipo'));

                    const stock = selectedOption.data('stock');
                    stockSpan.text('📦 Stock disponible: ' + stock);

                    // Color según stock
                    if (stock < 10) {
                        stockSpan.removeClass('text-green-600 text-gray-600').addClass('text-red-600 font-semibold');
                    } else if (stock < 50) {
                        stockSpan.removeClass('text-green-600 text-red-600').addClass('text-yellow-600');
                    } else {
                        stockSpan.removeClass('text-red-600 text-yellow-600').addClass('text-green-600');
                    }

                    infoDiv.removeClass('hidden').addClass('animate-fade-in');
                    $(this).closest('.grid').find(`input[id^="cantidad_"]`).attr('max', stock);

                    // Efecto visual
                    infoDiv.addClass('transform transition-all duration-500 scale-105');
                    setTimeout(() => {
                        infoDiv.removeClass('scale-105');
                    }, 300);
                } else {
                    infoDiv.addClass('hidden');
                    stockSpan.text('').removeClass('text-red-600 text-yellow-600 text-green-600');
                }
            });

            // Validación de cantidad para artículos adicionales
            $(nuevoArticulo).find('.cantidad-input').on('input', function() {
                const max = parseInt($(this).attr('max'));
                const current = parseInt($(this).val());
                const stockSpan = $(this).closest('.grid').find(`.stock-disponible-${count}`);

                if (current > max) {
                    $(this).addClass('border-red-500 bg-red-50 dark:bg-red-900/20');
                    stockSpan.addClass('text-red-600 font-bold');
                } else {
                    $(this).removeClass('border-red-500 bg-red-50 dark:bg-red-900/20');
                }
            });

            // Agregar evento al botón de eliminar
            $(nuevoArticulo).find('.eliminar-articulo').on('click', function() {
                const $articulo = $(this).closest('.articulo-adicional');

                // Efecto de desaparición
                $articulo.addClass('transform transition-all duration-300 scale-95 opacity-0');
                setTimeout(() => {
                    $articulo.remove();
                    // Renumerar los artículos restantes
                    $('#articulos-adicionales').children().each(function(index) {
                        $(this).find('h4').html(`
                            <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-2 rounded-xl mr-3">
                                📦
                            </span>
                            Artículo Adicional #${index + 1}
                        `);
                    });
                }, 300);
            });
        });

        // Validación del formulario antes de enviar
        $('#solicitudForm').on('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            // Validar que al menos un artículo esté seleccionado
            const articuloPrincipal = $('#articulo_id').val();
            const articulosAdicionales = $('.articulo-adicional select').filter(function() {
                return $(this).val();
            }).length;

            if (!articuloPrincipal && articulosAdicionales === 0) {
                isValid = false;
                errorMessage = '❌ Debe seleccionar al menos un artículo';
            }

            // Validar cantidades
            $('input[type="number"]').each(function() {
                const max = parseInt($(this).attr('max'));
                const current = parseInt($(this).val());
                if (current > max) {
                    isValid = false;
                    errorMessage = `❌ La cantidad no puede ser mayor al stock disponible (${max})`;
                    return false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                // Mostrar mensaje de error con estilo
                if (!$('.error-message').length) {
                    $('body').prepend(`
                        <div class="error-message fixed top-4 right-4 bg-red-500 text-white p-4 rounded-xl shadow-lg transform transition-all duration-300 z-50">
                            <div class="flex items-center space-x-2">
                                <span>⚠️</span>
                                <span>${errorMessage}</span>
                            </div>
                        </div>
                    `);
                    setTimeout(() => {
                        $('.error-message').addClass('opacity-0 transform scale-95');
                        setTimeout(() => $('.error-message').remove(), 300);
                    }, 5000);
                }
            } else {
                // Efecto de loading en el botón de enviar
                $('#submitBtn').html(`
                    <span class="flex items-center justify-center space-x-3">
                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                        <span class="text-lg">Procesando...</span>
                    </span>
                `).prop('disabled', true);
            }
        });
    });
</script>
    <script>
        // 1. Definimos las funciones en el ámbito global (fuera de DOMContentLoaded)
        function generarCodigoSolicitud() {
            const nombre =
                "{{ auth()->user()->Nombre }}{{ auth()->user()->apellidoPaterno }}{{ auth()->user()->apellidoMaterno }}"
                .replace(/\s+/g, '').toUpperCase();
            const fecha = new Date().toISOString().slice(0, 10).replace(/-/g, '');
            const randomStr = Math.random().toString(36).substring(2, 8).toUpperCase();
            return `${nombre.slice(0,3)}-${fecha}-${randomStr}`;
        }

        function actualizarCodigo() {
            document.getElementById("codigoSolicitud").value = generarCodigoSolicitud();
        }

        // 2. Evento principal
        document.addEventListener('DOMContentLoaded', function() {
            const codigoInput = document.getElementById("codigoSolicitud");

            // Solo genera un nuevo código si el campo está vacío
            if (!codigoInput.value.trim()) {
                actualizarCodigo();
            }

            // Botón para regenerar código manualmente
            document.getElementById("regenerarCodigo").addEventListener("click", actualizarCodigo);

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
                    modalIcon.innerHTML =
                        '<svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                } else {
                    modalIcon.classList.add('bg-red-100');
                    modalIcon.innerHTML =
                        '<svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
                }

                modal.classList.remove('hidden');
            }

            // Cerrar modal
            document.getElementById('modalCloseBtn').addEventListener('click', function() {
                document.getElementById('responseModal').classList.add('hidden');
            });

            // Manejar el envío del formulario
            document.getElementById('solicitudForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
            <span class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Procesando...
            </span>
        `;

                // Recolectar datos del formulario
                const formData = new FormData(this);

                // Recolectar artículos adicionales
                const articulosAdicionales = [];
                document.querySelectorAll('#articulos-adicionales .articulo-adicional').forEach((articulo,
                    index) => {
                    const articuloId = articulo.querySelector(
                        `select[name^="articulos_adicionales"]`).value;
                    const cantidad = articulo.querySelector(
                        `input[name^="articulos_adicionales"][name$="[cantidad]"]`).value;
                    const descripcion = articulo.querySelector(
                        `textarea[name^="articulos_adicionales"][name$="[descripcion]"]`).value;

                    if (articuloId) {
                        articulosAdicionales.push({
                            articulo_id: articuloId,
                            cantidad: cantidad,
                            descripcion: descripcion
                        });
                    }
                });

                // Convertir a JSON y agregar al formData
                formData.append('articulos_adicionales', JSON.stringify(articulosAdicionales));

                // Enviar la solicitud AJAX
                fetch("{{ route('solicitudarticulo.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showModal('Éxito', data.message, true);
                            // Limpiar formulario si es necesario
                            document.getElementById('solicitudForm').reset();
                            document.getElementById('articulos-adicionales').innerHTML = '';
                            // No regeneramos el código automáticamente
                            // actualizarCodigo(); ❌ eliminado
                        } else {
                            showModal('Error', data.message ||
                                'Ocurrió un error al procesar la solicitud', false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showModal('Error', 'Ocurrió un error al enviar la solicitud', false);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Enviar Solicitud
                </span>
            `;
                    });
            });
        });
    </script>

</x-layout.default>
