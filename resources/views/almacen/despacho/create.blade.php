<x-layout.default>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/despacho.css') }}">

    <div x-data="despachoForm" class="min-h-screen py-8 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <!-- Header Mejorado -->
            <div class="text-center mb-12 fade-in">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-xl mb-6 border border-blue-200 transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-file-export text-4xl text-blue-600"></i>
                </div>
                <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">Documento de Salida</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">Complete toda la información requerida para generar su documento de salida</p>
            </div>

            <form method="POST" action="{{ route('despacho.store') }}" id="despachoForm" @submit.prevent="submitForm">
                @csrf

                <div class="mb-8 fade-in">
                    <div class="card hover:shadow-2xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center mb-8">
                                <div class="icon-container icon-primary transform hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-file-alt text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="section-title text-2xl">Información del Documento</h3>
                                    <p class="section-subtitle text-lg">Datos principales del documento de salida</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Código de Solicitud -->
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-search mr-2 text-blue-500"></i>
                                        Código de Solicitud (Opcional)
                                    </label>
                                    <div class="relative">
                                        <input type="text" x-model="codigoSolicitud"
                                            @blur="cargarSolicitud()"
                                            placeholder="Ingrese código de solicitud para cargar automáticamente"
                                            class="form-control hover:border-blue-400 transition-colors duration-300 pr-12">
                                        <button type="button" @click="cargarSolicitud()"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition-colors duration-300"
                                            :disabled="!codigoSolicitud">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2" x-show="codigoSolicitud">
                                        <span x-text="solicitudCargada ? '✅ Solicitud cargada' : '⏳ Buscando solicitud...'"></span>
                                    </p>
                                </div>

                                <!-- Tipo Guía -->
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-tag mr-2 text-blue-500"></i>
                                            Tipo Guía
                                        </div>
                                        <button type="button" @click="generarTipoGuia()"
                                            class="text-xs bg-green-500 text-white px-2 py-1 rounded-lg hover:bg-green-600 transition-colors duration-300 flex items-center">
                                            <i class="fas fa-sync-alt mr-1"></i> Generar
                                        </button>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="tipo_guia" x-model="tipoGuiaActual"
                                            class="form-control hover:border-blue-400 transition-colors duration-300 pr-12 font-mono font-bold text-lg" 
                                            required readonly>
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex items-center space-x-1">
                                            <span class="text-green-500 font-semibold" x-text="tipoGuiaActual.replace('GR_Electronica_TI', '')"></span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Formato: GR_Electronica_TI + 4 dígitos</p>
                                </div>

                                <!-- Número -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-hashtag mr-2 text-blue-500"></i>
                                            Número
                                        </div>
                                        <button type="button" @click="generarNumero()"
                                            class="text-xs bg-green-500 text-white px-2 py-1 rounded-lg hover:bg-green-600 transition-colors duration-300 flex items-center">
                                            <i class="fas fa-sync-alt mr-1"></i> Generar
                                        </button>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="numero" x-model="numeroActual"
                                            class="form-control hover:border-blue-400 transition-colors duration-300 pr-12 font-mono font-bold text-lg"
                                            required readonly>
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex items-center space-x-1">
                                            <span class="text-green-500 font-semibold" x-text="numeroActual"></span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Número automático de 4 dígitos</p>
                                </div>

                                <!-- Documento -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-file-invoice mr-2 text-blue-500"></i>
                                        Documento
                                    </label>
                                    <select name="documento" class="form-control hover:border-blue-400 transition-colors duration-300" required>
                                        <option value="guia">Guía</option>
                                        <option value="factura">Factura</option>
                                    </select>
                                </div>

                                <!-- Fecha Entrega -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-calendar-day mr-2 text-blue-500"></i>
                                        Fecha Entrega
                                    </label>
                                    <input type="date" name="fecha_entrega" value="{{ date('Y-m-d') }}"
                                        class="form-control hover:border-blue-400 transition-colors duration-300" required>
                                </div>

                                <!-- Fecha Traslado -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-truck-loading mr-2 text-blue-500"></i>
                                        Fecha Traslado
                                    </label>
                                    <input type="date" name="fecha_traslado" value="{{ date('Y-m-d') }}"
                                        class="form-control hover:border-blue-400 transition-colors duration-300" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Direcciones -->
                <div class="mb-8 fade-in">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                        <!-- Partida -->
                        <div class="card hover:shadow-2xl transition-all duration-300">
                            <div class="p-8">
                                <div class="flex items-center mb-8">
                                    <div class="icon-container icon-success transform hover:rotate-12 transition-transform duration-300">
                                        <i class="fas fa-map-marker-alt text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title text-2xl">Dirección de Partida</h3>
                                        <p class="section-subtitle text-lg">Origen del despacho</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-road mr-2 text-green-500"></i>
                                            Dirección
                                        </label>
                                        <input type="text" name="direccion_partida"
                                            value="AV SANTA ELVIRA E MZ B LOTE 8 URBA SAN ELÍAS"
                                            class="form-control hover:border-green-400 transition-colors duration-300" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-building mr-2 text-green-500"></i>
                                                Departamento
                                            </label>
                                            <select name="dpto_partida" class="form-control hover:border-green-400 transition-colors duration-300" required>
                                                <option value="">Seleccionar</option>
                                                <template x-for="departamento in departamentos" :key="departamento">
                                                    <option :value="departamento" x-text="departamento"
                                                        :selected="departamento === 'Lima'"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-map mr-2 text-green-500"></i>
                                                Provincia
                                            </label>
                                            <input type="text" name="provincia_partida" value="Lima"
                                                class="form-control hover:border-green-400 transition-colors duration-300" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-location-dot mr-2 text-green-500"></i>
                                                Distrito
                                            </label>
                                            <input type="text" name="distrito_partida" value="Los Olivos"
                                                class="form-control hover:border-green-400 transition-colors duration-300" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Llegada -->
                        <div class="card hover:shadow-2xl transition-all duration-300">
                            <div class="p-8">
                                <div class="flex items-center mb-8">
                                    <div class="icon-container icon-primary transform hover:rotate-12 transition-transform duration-300">
                                        <i class="fas fa-flag-checkered text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title text-2xl">Dirección de Llegada</h3>
                                        <p class="section-subtitle text-lg">Destino del despacho</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-road mr-2 text-blue-500"></i>
                                            Dirección
                                        </label>
                                        <input type="text" name="direccion_llegada"
                                            placeholder="Ingrese dirección de llegada"
                                            class="form-control hover:border-blue-400 transition-colors duration-300" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-building mr-2 text-blue-500"></i>
                                                Departamento
                                            </label>
                                            <select name="dpto_llegada" class="form-control hover:border-blue-400 transition-colors duration-300" required>
                                                <option value="">Seleccionar</option>
                                                <template x-for="departamento in departamentos" :key="departamento">
                                                    <option :value="departamento" x-text="departamento"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-map mr-2 text-blue-500"></i>
                                                Provincia
                                            </label>
                                            <input type="text" name="provincia_llegada"
                                                placeholder="Ingrese provincia"
                                                class="form-control hover:border-blue-400 transition-colors duration-300" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-location-dot mr-2 text-blue-500"></i>
                                                Distrito
                                            </label>
                                            <input type="text" name="distrito_llegada"
                                                placeholder="Ingrese distrito"
                                                class="form-control hover:border-blue-400 transition-colors duration-300" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Cliente y Transporte -->
                <div class="mb-8 fade-in">
                    <div class="card hover:shadow-2xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center mb-8">
                                <div class="icon-container icon-warning transform hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-users text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="section-title text-2xl">Cliente y Transporte</h3>
                                    <p class="section-subtitle text-lg">Información del cliente y datos de envío</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-user-tie mr-2 text-amber-500"></i>
                                            Cliente
                                        </label>
                                        <select name="cliente_id" id="cliente_select"
                                            class="form-control hover:border-amber-400 transition-colors duration-300" required>
                                            <option value="">Seleccionar cliente</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-user-check mr-2 text-amber-500"></i>
                                            Vendedor
                                        </label>
                                        <select name="vendedor_id" id="vendedor_select"
                                            class="form-control hover:border-amber-400 transition-colors duration-300" required>
                                            <option value="">Seleccionar vendedor</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-exchange-alt mr-2 text-amber-500"></i>
                                            Trasbordo
                                        </label>
                                        <select name="trasbordo" class="form-control hover:border-amber-400 transition-colors duration-300" required>
                                            <option value="si">Sí</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-shipping-fast mr-2 text-amber-500"></i>
                                            Modo Traslado
                                        </label>
                                        <select name="modo_traslado" class="form-control hover:border-amber-400 transition-colors duration-300" required>
                                            <option value="publico">Público</option>
                                            <option value="privado">Privado</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-id-card-alt mr-2 text-amber-500"></i>
                                            Conductor
                                        </label>
                                        <select name="conductor_id" id="conductor_select"
                                            class="form-control hover:border-amber-400 transition-colors duration-300" required>
                                            <option value="">Seleccionar conductor</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-file-contract mr-2 text-amber-500"></i>
                                                Condiciones
                                            </label>
                                            <select name="condiciones" class="form-control hover:border-amber-400 transition-colors duration-300" required>
                                                <option value="contado">Contado</option>
                                                <option value="contrato">Contrato</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-truck-moving mr-2 text-amber-500"></i>
                                                Tipo Traslado
                                            </label>
                                            <select name="tipo_traslado" class="form-control hover:border-amber-400 transition-colors duration-300" required>
                                                <option value="venta_sujeta_confirmacion">Venta sujeta a confirmación</option>
                                                <option value="traslado_interno">Traslado interno</option>
                                                <option value="importacion">Importación</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              <!-- Sección de Artículos -->
                <div class="mb-8 fade-in">
                    <div class="card hover:shadow-2xl transition-all duration-300">
                        <div class="card-header bg-gradient-to-r from-blue-600 to-purple-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-white bg-opacity-20 mr-4 backdrop-blur-sm transform hover:rotate-6 transition-transform duration-300">
                                        <i class="fas fa-boxes text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white">Gestión de Artículos</h3>
                                        <p class="text-white text-opacity-90 text-base mt-1">
                                            <span x-show="codigoSolicitud && solicitudCargada">Artículos cargados desde solicitud</span>
                                            <span x-show="!codigoSolicitud || !solicitudCargada">Agrega y gestiona los productos del despacho</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" x-show="!codigoSolicitud || !solicitudCargada"
                                        @click="agregarArticulo()"
                                        class="btn bg-white text-blue-600 font-bold rounded-xl px-6 py-3 hover:scale-105 hover:shadow-xl transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                                        <i class="fas fa-plus-circle mr-3"></i> Agregar Artículo
                                    </button>
                                    <button type="button" x-show="codigoSolicitud && solicitudCargada"
                                        @click="limpiarSolicitud()"
                                        class="btn bg-red-500 text-white font-bold rounded-xl px-6 py-3 hover:scale-105 hover:shadow-xl transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                                        <i class="fas fa-times mr-3"></i> Limpiar Solicitud
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <!-- Información de la solicitud -->
                            <div x-show="codigoSolicitud && solicitudCargada" class="mb-6 bg-green-50 border border-green-200 rounded-xl p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                                        <div>
                                            <h4 class="font-bold text-green-800 text-lg">Solicitud Cargada: <span x-text="codigoSolicitud"></span></h4>
                                            <p class="text-green-600" x-text="infoSolicitud"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-green-700 font-semibold" x-text="`${articulos.length} artículo(s) cargado(s)`"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Barra de búsqueda -->
                            <div class="mb-8 relative" x-show="!codigoSolicitud || !solicitudCargada">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                    <input type="text" x-model="searchTerm"
                                        placeholder="Buscar por código, descripción..."
                                        class="w-full pl-12 pr-6 py-4 border-2 border-gray-300 rounded-2xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-300 text-lg shadow-sm">
                                </div>
                            </div>

                            <!-- Lista de artículos -->
                            <div class="space-y-6 max-h-[600px] overflow-y-auto pr-4 custom-scrollbar">
                                <!-- Estado vacío -->
                                <div x-show="articulos.length === 0" class="text-center py-16">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-box-open text-3xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-600 mb-4">No hay artículos agregados</h3>
                                        <p class="text-gray-500 mb-8 text-lg" x-text="codigoSolicitud ? 'No se encontraron artículos para esta solicitud' : 'Comienza agregando el primer artículo a tu despacho'"></p>
                                        <button type="button" x-show="!codigoSolicitud" @click="agregarArticulo()"
                                            class="btn btn-primary rounded-xl px-8 py-4 text-lg font-semibold transform hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-plus-circle mr-3"></i>
                                            Agregar Primer Artículo
                                        </button>
                                    </div>
                                </div>

                                <!-- Artículos existentes -->
                                <template x-for="(articulo, index) in articulos" :key="articulo.id">
                                    <div class="article-item bg-white border-2 border-gray-200 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300 group hover:border-blue-200">
                                        <!-- Header del artículo -->
                                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                                                    <span x-text="index + 1"></span>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-800 text-lg" x-text="articulo.codigo || 'Sin código'"></h4>
                                                    <p class="text-sm text-gray-500" x-text="articulo.descripcion || 'Sin descripción'"></p>
                                                    <div class="flex items-center mt-1 space-x-2">
                                                        <span x-show="articulo.maneja_serie == 1" class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">
                                                            <i class="fas fa-barcode mr-1"></i> Maneja Series
                                                        </span>
                                                        <span x-show="articulo.series" class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                            <i class="fas fa-check mr-1"></i> Series Asignadas
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <!-- Botón para gestionar series -->
                                                <button type="button" @click="gestionarSeries(index)"
                                                    class="w-9 h-9 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm hover:bg-blue-600 transition-all duration-300 opacity-0 group-hover:opacity-100 transform hover:scale-110 shadow-lg"
                                                    :class="{ '!opacity-100': articulo.maneja_serie == 1, '!bg-orange-500': articulo.maneja_serie == 1 }"
                                                    :title="articulo.maneja_serie == 1 ? 'Gestionar series' : 'No maneja series'">
                                                    <i class="fas fa-barcode"></i>
                                                </button>
                                                <!-- Botón eliminar -->
                                                <button type="button" @click="eliminarArticulo(index)"
                                                    class="w-9 h-9 bg-red-500 text-white rounded-full flex items-center justify-center text-sm hover:bg-red-600 transition-all duration-300 opacity-0 group-hover:opacity-100 transform hover:scale-110 shadow-lg"
                                                    :disabled="codigoSolicitud && solicitudCargada">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Campos del artículo -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
                                            <!-- Código y Descripción -->
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                        <i class="fas fa-barcode mr-3 text-blue-500"></i>
                                                        Código
                                                    </label>
                                                    <input type="text" x-model="articulo.codigo"
                                                        @change="cargarArticuloPorCodigo(index)"
                                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-base shadow-sm"
                                                        :readonly="codigoSolicitud && solicitudCargada">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                        <i class="fas fa-align-left mr-3 text-blue-500"></i>
                                                        Descripción
                                                    </label>
                                                    <input type="text" x-model="articulo.descripcion"
                                                        placeholder="Ingresa descripción"
                                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-base shadow-sm"
                                                        :readonly="codigoSolicitud && solicitudCargada">
                                                </div>
                                            </div>

                                            <!-- Stock y Unidad -->
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                        <i class="fas fa-boxes mr-3 text-blue-500"></i>
                                                        Stock
                                                    </label>
                                                    <div class="relative">
                                                        <input type="number" x-model="articulo.stock"
                                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-base shadow-sm pr-12"
                                                            :readonly="codigoSolicitud && solicitudCargada">
                                                        <i class="fas fa-warehouse absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-base"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                        <i class="fas fa-balance-scale mr-3 text-blue-500"></i>
                                                        Unidad
                                                    </label>
                                                    <select x-model="articulo.unidad"
                                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-base shadow-sm"
                                                        :disabled="codigoSolicitud && solicitudCargada">
                                                        <option>Unidad</option>
                                                        <option>Kg</option>
                                                        <option>Litro</option>
                                                        <option>Caja</option>
                                                        <option>Paquete</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Precio y Cantidad -->
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                        <i class="fas fa-tag mr-3 text-blue-500"></i>
                                                        Precio Venta
                                                    </label>
                                                    <div class="relative">
                                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-base font-semibold">S/</span>
                                                        <input type="number" step="0.01"
                                                            x-model="articulo.precio" @input="calcularTotales()"
                                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-base shadow-sm pl-12"
                                                            :readonly="codigoSolicitud && solicitudCargada">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                        <i class="fas fa-calculator mr-3 text-blue-500"></i>
                                                        Cantidad
                                                    </label>
                                                    <div class="relative">
                                                        <input type="number" x-model="articulo.cantidad"
                                                            @input="calcularTotales()"
                                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-base shadow-sm"
                                                            :readonly="codigoSolicitud && solicitudCargada">
                                                        <i class="fas fa-sort-numeric-up absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-base"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total del artículo -->
                                            <div class="flex flex-col justify-center space-y-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-100">
                                                <div class="text-center">
                                                    <p class="text-sm font-semibold text-gray-600 mb-2">Total del Artículo</p>
                                                    <p class="text-3xl font-bold text-blue-600">
                                                        S/ <span x-text="(articulo.precio * articulo.cantidad).toFixed(2)"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Totales -->
                            <div class="mt-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl p-8 text-white shadow-2xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <i class="fas fa-receipt text-3xl text-white opacity-90"></i>
                                        <div>
                                            <p class="text-lg opacity-90">Total del despacho</p>
                                            <p class="text-2xl font-bold" x-text="`${articulos.length} artículo(s)`"></p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-4xl font-bold">S/ <span x-text="total.toFixed(2)"></span></p>
                                        <p class="text-lg opacity-90 mt-2">
                                            <span>S/ <span x-text="subtotal.toFixed(2)"></span></span>
                                            +
                                            <span>S/ <span x-text="igv.toFixed(2)"></span> IGV</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos hidden para enviar datos -->
                            <input type="hidden" name="codigo_solicitud" :value="codigoSolicitud">
                            <input type="hidden" name="subtotal_hidden" :value="subtotal.toFixed(2)">
                            <input type="hidden" name="igv_hidden" :value="igv.toFixed(2)">
                            <input type="hidden" name="total_hidden" :value="total.toFixed(2)">
                            <input type="hidden" name="articulos" :value="JSON.stringify(articulos)">
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-between pt-8 fade-in mt-8">
                    <button type="button" class="btn btn-outline transform hover:-translate-x-1 transition-all duration-300 rounded-xl px-8 py-4 text-lg font-semibold border-2" @click="cancelar()">
                        <i class="fas fa-times mr-3"></i> Cancelar
                    </button>

                    <div class="flex gap-6">
                        <button type="button" class="btn bg-blue-500 text-white font-semibold rounded-xl px-8 py-4 text-lg transform hover:scale-105 hover:shadow-xl transition-all duration-300" @click="validarTodo()">
                            <i class="fas fa-check-circle mr-3"></i> Validar Formulario
                        </button>

                        <button type="submit" class="btn btn-success rounded-xl px-12 py-4 text-lg font-bold transform hover:scale-105 hover:shadow-xl transition-all duration-300" :disabled="articulos.length === 0 || submitting">
                            <i class="fas fa-paper-plane mr-3"></i>
                            <span x-text="submitting ? 'Guardando...' : 'Guardar Documento'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Gestión de Series -->
    <template x-if="seriesModal">
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
                <!-- Header del Modal -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-barcode text-3xl"></i>
                            <div>
                                <h3 class="text-2xl font-bold">Gestión de Series</h3>
                                <p class="text-blue-100" x-text="articuloConSeries !== null ? articulos[articuloConSeries].descripcion : ''"></p>
                            </div>
                        </div>
                        <button @click="seriesModal = false" class="text-white hover:text-blue-200 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <template x-if="articuloConSeries !== null">
                        <div>
                            <!-- Información del artículo -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="font-semibold text-blue-700">Código:</span>
                                        <p x-text="articulos[articuloConSeries].codigo"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-blue-700">Descripción:</span>
                                        <p x-text="articulos[articuloConSeries].descripcion"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-blue-700">Cantidad:</span>
                                        <p x-text="articulos[articuloConSeries].cantidad"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-blue-700">Series Requeridas:</span>
                                        <p x-text="articulos[articuloConSeries].cantidad"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Series Disponibles -->
                            <div class="mb-8" x-show="seriesDisponibles.length > 0">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-list-check mr-2 text-green-500"></i>
                                    Series Disponibles en Stock
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <template x-for="serie in seriesDisponibles" :key="serie.idArticuloSerie">
                                        <div class="border border-gray-200 rounded-lg p-3 hover:border-green-400 transition-colors duration-200">
                                            <div class="flex items-center justify-between">
                                                <span class="font-mono text-sm" x-text="serie.numero_serie"></span>
                                                <button @click="seleccionarSerie(serie)"
                                                    class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-600 transition-colors duration-200"
                                                    :disabled="seriesSeleccionadas.length >= articulos[articuloConSeries].cantidad || seriesSeleccionadas.includes(serie.numero_serie)">
                                                    <span x-text="seriesSeleccionadas.includes(serie.numero_serie) ? '✓ Seleccionada' : 'Seleccionar'"></span>
                                                </button>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1" x-text="serie.estado"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Series Seleccionadas -->
                            <div class="mb-8" x-show="seriesSeleccionadas.length > 0">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                    Series Seleccionadas
                                    <span class="ml-2 bg-blue-500 text-white px-2 py-1 rounded-full text-sm" x-text="seriesSeleccionadas.length"></span>
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <template x-for="(serie, index) in seriesSeleccionadas" :key="index">
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <div class="flex items-center justify-between">
                                                <span class="font-mono text-sm font-semibold" x-text="serie"></span>
                                                <button @click="removerSerieSeleccionada(serie)"
                                                    class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Series Manuales -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-keyboard mr-2 text-orange-500"></i>
                                        Ingreso Manual de Series
                                    </h4>
                                    <button @click="agregarSerieManual()"
                                        class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors duration-200 flex items-center text-sm"
                                        :disabled="(seriesSeleccionadas.length + seriesManuales.length) >= articulos[articuloConSeries].cantidad">
                                        <i class="fas fa-plus mr-2"></i> Agregar Serie
                                    </button>
                                </div>
                                
                                <div class="space-y-3">
                                    <template x-for="(serieManual, index) in seriesManuales" :key="index">
                                        <div class="flex items-center space-x-3">
                                            <input type="text" x-model="seriesManuales[index]"
                                                placeholder="Ingrese número de serie"
                                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 transition text-base">
                                            <button @click="removerSerieManual(index)"
                                                class="text-red-500 hover:text-red-700 transition-colors duration-200 p-2">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Resumen -->
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">Total series asignadas:</p>
                                        <p class="text-xl font-bold text-gray-800" x-text="seriesSeleccionadas.length + seriesManuales.filter(s => s.trim() !== '').length"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Series requeridas:</p>
                                        <p class="text-xl font-bold text-gray-800" x-text="articulos[articuloConSeries].cantidad"></p>
                                    </div>
                                    <div x-show="(seriesSeleccionadas.length + seriesManuales.filter(s => s.trim() !== '').length) !== articulos[articuloConSeries].cantidad"
                                        class="text-red-500 text-sm font-semibold">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Faltan series por asignar
                                    </div>
                                    <div x-show="(seriesSeleccionadas.length + seriesManuales.filter(s => s.trim() !== '').length) === articulos[articuloConSeries].cantidad"
                                        class="text-green-500 text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Todas las series asignadas
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer del Modal -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button @click="seriesModal = false" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button @click="guardarSeries()" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-semibold"
                        :disabled="(seriesSeleccionadas.length + seriesManuales.filter(s => s.trim() !== '').length) !== articulos[articuloConSeries].cantidad">
                        <i class="fas fa-save mr-2"></i> Guardar Series
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        // Parche de seguridad
        (function() {
            'use strict';
            const originalRemove = DOMTokenList.prototype.remove;
            DOMTokenList.prototype.remove = function(...tokens) {
                const validTokens = tokens.filter(token =>
                    token !== null && token !== undefined && token !== '' && String(token).trim() !== ''
                );
                if (validTokens.length > 0) {
                    return originalRemove.apply(this, validTokens);
                }
                return this;
            };
        })();

        document.addEventListener('alpine:init', () => {
            if (window.despachoFormInitialized) return;
            window.despachoFormInitialized = true;

            // Cambiar a función que retorna el objeto
            Alpine.data('despachoForm', () => {
                return {
                    // Variables principales
                    codigoSolicitud: '',
                    solicitudCargada: false,
                    infoSolicitud: '',
                    articulos: [],
                    searchTerm: '',
                    subtotal: 0,
                    igv: 0,
                    total: 0,
                    departamentos: [],
                    clientes: [],
                    usuarios: [],
                    articulosDisponibles: [],
                    submitting: false,
                    errors: {},
                    
                    // Variables para códigos
                    numeroActual: '',
                    tipoGuiaActual: '',

                    // Variables para series - INICIALIZAR CON VALORES POR DEFECTO
                    seriesModal: false,
                    articuloConSeries: null,
                    seriesDisponibles: [],
                    seriesSeleccionadas: [],
                    seriesManuales: [],

                    async init() {
                        await this.cargarDatosIniciales();
                        this.inicializarSelect2();
                        this.calcularTotales();
                        
                        // GENERAR CÓDIGOS AUTOMÁTICAMENTE AL INICIAR
                        this.generarNumero();
                        this.generarTipoGuia();
                    },

                    // FUNCIÓN PARA GENERAR NÚMERO ALEATORIO DE 4 DÍGITOS
                    generarNumero() {
                        const nuevoNumero = Math.floor(1000 + Math.random() * 9000).toString();
                        this.numeroActual = nuevoNumero;
                        
                        const inputNumero = document.querySelector('input[name="numero"]');
                        if (inputNumero) {
                            inputNumero.classList.add('bg-green-50', 'border-green-300', 'transform', 'scale-105');
                            setTimeout(() => {
                                inputNumero.classList.remove('bg-green-50', 'border-green-300', 'transform', 'scale-105');
                            }, 600);
                        }
                        
                        toastr.success(`Número generado: ${nuevoNumero}`);
                    },

                    // FUNCIÓN PARA GENERAR TIPO GUÍA CON 4 DÍGITOS ALEATORIOS
                    generarTipoGuia() {
                        const cuatroDigitos = Math.floor(1000 + Math.random() * 9000).toString();
                        this.tipoGuiaActual = `GR_Electronica_TI${cuatroDigitos}`;
                        
                        const inputTipoGuia = document.querySelector('input[name="tipo_guia"]');
                        if (inputTipoGuia) {
                            inputTipoGuia.classList.add('bg-green-50', 'border-green-300', 'transform', 'scale-105');
                            setTimeout(() => {
                                inputTipoGuia.classList.remove('bg-green-50', 'border-green-300', 'transform', 'scale-105');
                            }, 600);
                        }
                        
                        toastr.success(`Tipo Guía generado: ${this.tipoGuiaActual}`);
                    },

                    async cargarDatosIniciales() {
                        try {
                            // Cargar departamentos
                            const deptResponse = await fetch('/api/departamentosdespacho');
                            if (deptResponse.ok) {
                                this.departamentos = await deptResponse.json();
                            }

                            // Cargar clientes
                            const clientesResponse = await fetch('/api/clientesdespacho');
                            if (clientesResponse.ok) {
                                const clientesData = await clientesResponse.json();
                                this.clientes = clientesData.map(cliente => ({
                                    id: cliente.id,
                                    text: cliente.text || `${cliente.nombre} - ${cliente.documento}`
                                }));
                            }

                            // Cargar usuarios
                            const usuariosResponse = await fetch('/api/usuariosdespacho');
                            if (usuariosResponse.ok) {
                                this.usuarios = await usuariosResponse.json();
                            }

                            // Cargar artículos
                            const articulosResponse = await fetch('/api/articulosdespacho');
                            if (articulosResponse.ok) {
                                this.articulosDisponibles = await articulosResponse.json();
                            }

                            toastr.success('Datos cargados correctamente');
                        } catch (error) {
                            console.error('Error cargando datos:', error);
                            toastr.error('Error al cargar los datos iniciales');
                        }
                    },

                    async cargarSolicitud() {
                        if (!this.codigoSolicitud.trim()) {
                            toastr.warning('Por favor ingrese un código de solicitud');
                            return;
                        }

                        try {
                            this.solicitudCargada = false;
                            toastr.info('Buscando solicitud...');

                            const response = await fetch(`/api/solicitud/${encodeURIComponent(this.codigoSolicitud)}`);

                            if (!response.ok) {
                                const errorData = await response.json().catch(() => ({
                                    error: 'Error desconocido'
                                }));
                                throw new Error(errorData.error || 'Solicitud no encontrada');
                            }

                            const data = await response.json();

                            if (!data.articulos || data.articulos.length === 0) {
                                throw new Error('La solicitud no tiene artículos asociados');
                            }

                            // Cargar artículos de la solicitud
                            this.articulos = data.articulos.map(articulo => ({
                                id: articulo.idArticulos,
                                codigo: articulo.codigo_repuesto || articulo.codigo_barras || 'SIN-CODIGO',
                                descripcion: articulo.nombre || 'Sin descripción',
                                stock: articulo.stock_total || 0,
                                unidad: 'Unidad',
                                precio: parseFloat(articulo.precio_venta) || parseFloat(articulo.precio_compra) || 0,
                                cantidad: parseInt(articulo.cantidad_solicitada) || 1,
                                maneja_serie: articulo.maneja_serie || 0,
                                idArticulos: articulo.idArticulos,
                                readonly: true
                            }));

                            this.infoSolicitud = `Solicitud: ${data.solicitud.tipoorden || 'N/A'} - ${data.solicitud.estado || 'N/A'}`;
                            this.solicitudCargada = true;
                            this.calcularTotales();

                            toastr.success(`Solicitud cargada correctamente (${this.articulos.length} artículos)`);

                        } catch (error) {
                            console.error('Error cargando solicitud:', error);
                            toastr.error('No se pudo cargar la solicitud: ' + error.message);
                            this.solicitudCargada = false;
                            this.articulos = [];
                            this.infoSolicitud = '';
                        }
                    },

                    limpiarSolicitud() {
                        this.codigoSolicitud = '';
                        this.solicitudCargada = false;
                        this.infoSolicitud = '';
                        this.articulos = [];
                        this.calcularTotales();
                        toastr.info('Solicitud limpiada, modo manual activado');
                    },

                    inicializarSelect2() {
                        setTimeout(() => {
                            $('#cliente_select').select2({
                                data: this.clientes,
                                placeholder: 'Seleccionar cliente',
                                width: '100%'
                            });

                            $('#vendedor_select').select2({
                                data: this.usuarios,
                                placeholder: 'Seleccionar vendedor',
                                width: '100%'
                            });

                            $('#conductor_select').select2({
                                data: this.usuarios,
                                placeholder: 'Seleccionar conductor',
                                width: '100%'
                            });
                        }, 500);
                    },

                    validarTodo() {
                        this.errors = {};
                        let isValid = true;

                        // Validar campos básicos
                        const camposRequeridos = [
                            'tipo_guia', 'numero', 'documento', 'fecha_entrega', 'fecha_traslado',
                            'direccion_partida', 'dpto_partida', 'provincia_partida', 'distrito_partida',
                            'direccion_llegada', 'dpto_llegada', 'provincia_llegada', 'distrito_llegada',
                            'cliente_id', 'vendedor_id', 'conductor_id'
                        ];

                        camposRequeridos.forEach(campo => {
                            const element = document.querySelector(`[name="${campo}"]`);
                            if (!element || !element.value) {
                                this.errors[campo] = 'Este campo es requerido';
                                isValid = false;
                            }
                        });

                        // Validar artículos
                        if (this.articulos.length === 0) {
                            this.errors.articulos = 'Debe agregar al menos un artículo';
                            isValid = false;
                        }

                        // Validar series para artículos que las manejan
                        for (let i = 0; i < this.articulos.length; i++) {
                            const articulo = this.articulos[i];
                            if (articulo.maneja_serie == 1) {
                                if (!articulo.series || 
                                    (articulo.series.seleccionadas.length + articulo.series.manuales.length) !== articulo.cantidad) {
                                    this.errors[`articulo_${i}_series`] = `El artículo "${articulo.descripcion}" requiere ${articulo.cantidad} series`;
                                    isValid = false;
                                }
                            }
                        }

                        if (isValid) {
                            toastr.success('¡Todo correcto! Puede guardar el documento.');
                        } else {
                            this.mostrarErrores();
                            toastr.warning('Por favor complete todos los campos requeridos');
                        }

                        return isValid;
                    },

                    mostrarErrores() {
                        document.querySelectorAll('.error-message').forEach(el => el.remove());
                        document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                        Object.keys(this.errors).forEach(field => {
                            const element = document.querySelector(`[name="${field}"]`);
                            if (element) {
                                element.classList.add('border-red-500');
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'error-message text-red-500 text-sm mt-2 flex items-center';
                                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> ${this.errors[field]}`;
                                element.parentNode.appendChild(errorDiv);
                            }
                        });
                    },

                    agregarArticulo() {
                        if (this.solicitudCargada) {
                            toastr.warning('No puede agregar artículos manualmente cuando hay una solicitud cargada');
                            return;
                        }

                        const nuevoId = this.articulos.length > 0 ? Math.max(...this.articulos.map(a => a.id)) + 1 : 1;
                        this.articulos.push({
                            id: nuevoId,
                            codigo: '',
                            descripcion: '',
                            stock: 0,
                            unidad: 'Unidad',
                            precio: 0,
                            cantidad: 1,
                            maneja_serie: 0
                        });
                        this.calcularTotales();
                        toastr.info('Artículo agregado');
                    },

                    eliminarArticulo(index) {
                        if (this.solicitudCargada) {
                            toastr.warning('No puede eliminar artículos cuando hay una solicitud cargada');
                            return;
                        }

                        if (confirm('¿Está seguro de eliminar este artículo?')) {
                            // Limpiar series si existen
                            if (this.articulos[index].series) {
                                delete this.articulos[index].series;
                            }
                            
                            this.articulos.splice(index, 1);
                            this.calcularTotales();
                            toastr.info('Artículo eliminado');
                        }
                    },

                    async cargarArticuloPorCodigo(index) {
                        if (this.solicitudCargada) return;

                        const articulo = this.articulos[index];
                        if (!articulo.codigo.trim()) return;

                        let articuloEncontrado = this.articulosDisponibles.find(a =>
                            a.codigo && a.codigo.toString().trim() === articulo.codigo.toString().trim()
                        );

                        if (articuloEncontrado) {
                            this.articulos[index] = {
                                ...this.articulos[index],
                                descripcion: articuloEncontrado.text || articuloEncontrado.descripcion || 'Sin descripción',
                                precio: articuloEncontrado.precio || 0,
                                stock: articuloEncontrado.stock || 0,
                                maneja_serie: articuloEncontrado.maneja_serie || 0,
                                idArticulos: articuloEncontrado.idArticulos
                            };
                            
                            // Si maneja series, cargar las disponibles
                            if (this.articulos[index].maneja_serie == 1) {
                                await this.cargarSeriesDisponibles(index);
                            }
                            
                            this.calcularTotales();
                            toastr.success('Artículo cargado automáticamente');
                        }
                    },

                    // NUEVO: Cargar series disponibles para un artículo
                    async cargarSeriesDisponibles(index) {
                        const articulo = this.articulos[index];
                        if (!articulo.idArticulos) return;

                        try {
                            const response = await fetch(`/api/articulo-series/${articulo.idArticulos}`);
                            if (response.ok) {
                                this.seriesDisponibles = await response.json();
                            } else {
                                this.seriesDisponibles = [];
                            }
                        } catch (error) {
                            console.error('Error cargando series:', error);
                            this.seriesDisponibles = [];
                        }
                    },

                    // NUEVO: Abrir modal para gestionar series
                    async gestionarSeries(index) {
                        this.articuloConSeries = index;
                        const articulo = this.articulos[index];
                        
                        // Reiniciar selecciones
                        this.seriesSeleccionadas = [];
                        this.seriesManuales = [];
                        
                        // Si maneja series, cargar disponibles
                        if (articulo.maneja_serie == 1) {
                            await this.cargarSeriesDisponibles(index);
                            
                            // Inicializar array para series manuales según la cantidad
                            for (let i = 0; i < articulo.cantidad; i++) {
                                this.seriesManuales.push('');
                            }
                            
                            // Cargar series previamente guardadas si existen
                            if (articulo.series) {
                                this.seriesSeleccionadas = [...articulo.series.seleccionadas];
                                this.seriesManuales = [...articulo.series.manuales];
                            }
                        }
                        
                        this.seriesModal = true;
                    },

                    // NUEVO: Seleccionar serie disponible
                    seleccionarSerie(serie) {
                        if (this.seriesSeleccionadas.length >= this.articulos[this.articuloConSeries].cantidad) {
                            toastr.warning('Ya seleccionó la cantidad máxima de series');
                            return;
                        }

                        if (!this.seriesSeleccionadas.includes(serie.numero_serie)) {
                            this.seriesSeleccionadas.push(serie.numero_serie);
                            toastr.success(`Serie ${serie.numero_serie} seleccionada`);
                        }
                    },

                    // NUEVO: Remover serie seleccionada
                    removerSerieSeleccionada(serie) {
                        this.seriesSeleccionadas = this.seriesSeleccionadas.filter(s => s !== serie);
                    },

                    // NUEVO: Agregar serie manual
                    agregarSerieManual() {
                        if (this.seriesManuales.length >= this.articulos[this.articuloConSeries].cantidad) {
                            toastr.warning('Ya agregó la cantidad máxima de series');
                            return;
                        }
                        this.seriesManuales.push('');
                    },

                    // NUEVO: Remover serie manual
                    removerSerieManual(index) {
                        this.seriesManuales.splice(index, 1);
                    },

                    // NUEVO: Guardar series
                    guardarSeries() {
                        const articuloIndex = this.articuloConSeries;
                        const articulo = this.articulos[articuloIndex];
                        const totalSeries = this.seriesSeleccionadas.length + this.seriesManuales.filter(s => s.trim() !== '').length;
                        
                        // Validar que la cantidad de series coincida con la cantidad del artículo
                        if (totalSeries !== articulo.cantidad) {
                            toastr.error(`Debe ingresar exactamente ${articulo.cantidad} series`);
                            return;
                        }
                        
                        // Validar que no haya series duplicadas
                        const todasLasSeries = [...this.seriesSeleccionadas, ...this.seriesManuales.filter(s => s.trim() !== '')];
                        const seriesUnicas = [...new Set(todasLasSeries)];
                        
                        if (seriesUnicas.length !== todasLasSeries.length) {
                            toastr.error('No puede haber series duplicadas');
                            return;
                        }
                        
                        // Guardar series en el artículo
                        this.articulos[articuloIndex].series = {
                            seleccionadas: this.seriesSeleccionadas,
                            manuales: this.seriesManuales.filter(s => s.trim() !== '')
                        };
                        
                        this.seriesModal = false;
                        toastr.success('Series guardadas correctamente');
                    },

                    calcularTotales() {
                        this.subtotal = this.articulos.reduce((sum, articulo) => {
                            return sum + (articulo.precio * articulo.cantidad);
                        }, 0);
                        this.igv = this.subtotal * 0.18;
                        this.total = this.subtotal + this.igv;
                    },

                    async submitForm() {
                        if (this.submitting) return;

                        if (!this.validarTodo()) {
                            return;
                        }

                        this.submitting = true;
                        const form = document.getElementById('despachoForm');
                        const formData = new FormData(form);

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: formData
                            });

                            const result = await response.json();

                            if (result.success) {
                                toastr.success('Despacho creado exitosamente');
                                setTimeout(() => window.location.href = '/despacho', 1500);
                            } else {
                                let errorMessage = result.message || 'Error desconocido';
                                if (result.errors) errorMessage = Object.values(result.errors).flat().join(', ');
                                toastr.error(errorMessage);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            toastr.error('Error al crear el despacho');
                        } finally {
                            this.submitting = false;
                        }
                    },

                    cancelar() {
                        if (confirm('¿Está seguro de cancelar? Se perderán todos los datos.')) {
                            window.location.href = '/';
                        }
                    }
                }
            });
        });
    </script>
</x-layout.default>