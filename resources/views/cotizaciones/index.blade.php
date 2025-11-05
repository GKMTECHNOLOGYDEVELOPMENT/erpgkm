<x-layout.default title="Cotizaciones - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/index-cotizaciones.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div x-data="cotizacionesIndex" x-init="init()" class="fade-in">
        <!-- Header con Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Cotizaciones</p>
                        <h3 class="text-3xl font-bold mt-2" x-text="stats.total"></h3>
                    </div>
                    <i class="fas fa-file-invoice-dollar text-3xl text-blue-200"></i>
                </div>
            </div>

            <div class="stats-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Aprobadas</p>
                        <h3 class="text-3xl font-bold mt-2" x-text="stats.aprobadas"></h3>
                    </div>
                    <i class="fas fa-check-circle text-3xl text-green-200"></i>
                </div>
            </div>

            <div class="stats-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Pendientes</p>
                        <h3 class="text-3xl font-bold mt-2" x-text="stats.pendientes"></h3>
                    </div>
                    <i class="fas fa-clock text-3xl text-yellow-200"></i>
                </div>
            </div>

            <div class="stats-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Vencidas</p>
                        <h3 class="text-3xl font-bold mt-2" x-text="stats.vencidas"></h3>
                    </div>
                    <i class="fas fa-exclamation-triangle text-3xl text-red-200"></i>
                </div>
            </div>
        </div>

        <!-- Barra de Herramientas Mejorada -->
        <div class="card mb-6 border-0 shadow-xl bg-gradient-to-r from-white to-gray-50/50">
            <div class="p-8">
                <!-- Header Principal -->
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6 mb-8">
                    <!-- Título y Descripción -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-2xl shadow-lg">
                                <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1
                                    class="text-xl font-bold text-gray-900 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Gestión de Cotizaciones
                                </h1>
                                <p class="text-gray-600 mt-1 flex items-center text-sm">
                                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                                    Administre y realice seguimiento a todas sus cotizaciones
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- Botón Nueva Cotización -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('cotizaciones.create') }}"
                            class="btn btn-primary transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl group relative overflow-hidden">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 group-hover:from-blue-700 group-hover:to-purple-700 transition-all duration-300">
                            </div>
                            <div class="relative flex items-center space-x-3">
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span class="font-semibold">Nueva Cotización</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Barra de Búsqueda y Filtros -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-end">
                    <!-- Buscador Principal -->
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-search text-blue-500 mr-2"></i>
                            Buscar Cotizaciones
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" x-model="searchTerm" @input="filtrarCotizaciones()"
    placeholder="Buscar por número, cliente, documento..."
    class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white shadow-sm hover:border-gray-300 placeholder-gray-400">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full"
                                    x-text="`${cotizacionesFiltradas.length} resultados`"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros Avanzados -->
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Filtro por Estado -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-filter text-green-500 mr-2"></i>
                                    Estado de Cotización
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <select x-model="filtroEstado" @change="filtrarCotizaciones()"
                                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 bg-white shadow-sm hover:border-gray-300 appearance-none cursor-pointer">
                                        <option value="">Todos los estados</option>
                                        <option value="pendiente" class="flex items-center">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                            Pendiente
                                        </option>
                                        <option value="aprobada" class="flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Aprobada
                                        </option>
                                        <option value="rechazada" class="flex items-center">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                            Rechazada
                                        </option>
                                        <option value="enviada" class="flex items-center">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                            Enviada
                                        </option>
                                        <option value="vencida" class="flex items-center">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                            Vencida
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filtro por Mes -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                                    Filtro por Mes
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <select x-model="filtroMes" @change="filtrarCotizaciones()"
                                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white shadow-sm hover:border-gray-300 appearance-none cursor-pointer">
                                        <option value="">Todos los meses</option>
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros Activos -->
                <div x-show="searchTerm || filtroEstado || filtroMes"
                    class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200 flex items-center justify-between animate-fade-in">
                    <div class="flex items-center space-x-4">
                        <span class="text-blue-700 font-semibold flex items-center">
                            <i class="fas fa-filter mr-2"></i>
                            Filtros activos:
                        </span>
                        <div class="flex flex-wrap gap-2">
                            <span x-show="searchTerm"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 border border-blue-200">
                                <i class="fas fa-search mr-1"></i>
                                "<span x-text="searchTerm"></span>"
                                <button @click="searchTerm = ''; filtrarCotizaciones()"
                                    class="ml-2 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <span x-show="filtroEstado"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800 border border-green-200">
                                <i class="fas fa-tag mr-1"></i>
                                <span x-text="filtroEstado"></span>
                                <button @click="filtroEstado = ''; filtrarCotizaciones()"
                                    class="ml-2 text-green-600 hover:text-green-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <span x-show="filtroMes"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800 border border-purple-200">
                                <i class="fas fa-calendar mr-1"></i>
                                <span x-text="obtenerNombreMes(filtroMes)"></span>
                                <button @click="filtroMes = ''; filtrarCotizaciones()"
                                    class="ml-2 text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <button @click="limpiarFiltros()"
                        class="text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-2 transition-colors">
                        <i class="fas fa-times-circle"></i>
                        <span>Limpiar todos</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Cotizaciones - Diseño Elegante y Formal -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Header de la Tabla -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Registro de Cotizaciones</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <span x-text="cotizacionesFiltradas.length"></span> documentos encontrados
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-500">Ordenar por:</span>
                        <select
                            class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option>Fecha de emisión</option>
                            <option>Número de cotización</option>
                            <option>Valor total</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Cotización</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Cliente</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Fecha Emisión</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Válida Hasta</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Valor Total</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Estado</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Estado Vacío -->
                        <template x-if="cotizacionesFiltradas.length === 0">
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-file-invoice text-gray-400 text-2xl"></i>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">No se encontraron
                                            cotizaciones</h4>
                                        <p class="text-gray-600 max-w-md text-center"
                                            x-text="searchTerm || filtroEstado ?
                                          'No hay resultados que coincidan con los criterios de búsqueda' :
                                          'El registro de cotizaciones se encuentra vacío'">
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Items de Cotizaciones -->
                        <template x-for="(cotizacion, index) in cotizacionesFiltradas" :key="cotizacion.id">
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Número -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600 font-medium" x-text="index + 1"></span>
                                </td>

                                <!-- Información de Cotización -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-file-invoice text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900"
                                                x-text="cotizacion.cotizacionNo"></div>
                                            <div class="text-xs text-gray-500 flex items-center space-x-1 mt-1">
                                                <span x-text="cotizacion.moneda"></span>
                                                <span>•</span>
                                                <span
                                                    x-text="cotizacion.incluirIGV ? 'Incluye IGV' : 'Sin IGV'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Información del Cliente -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"
                                            x-text="cotizacion.cliente.nombre"></div>
                                        <div class="text-xs text-gray-500 mt-1" x-text="cotizacion.cliente.documento">

                                        </div>
                                    </div>
                                </td>

                                <!-- Fechas -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900"
                                        x-text="formatearFecha(cotizacion.fechaEmision)"></div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium"
                                            :class="esVencida(cotizacion.validaHasta) ? 'text-red-600' : 'text-gray-900'"
                                            x-text="formatearFecha(cotizacion.validaHasta)">
                                        </span>
                                        <span x-show="esVencida(cotizacion.validaHasta)"
                                            class="text-xs text-red-500 mt-1">Vencida</span>
                                    </div>
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-gray-900"
                                        x-text="formatearMoneda(cotizacion.total)"></div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-800': cotizacion.estado === 'aprobada',
                                            'bg-yellow-100 text-yellow-800': cotizacion.estado === 'pendiente',
                                            'bg-red-100 text-red-800': cotizacion.estado === 'rechazada',
                                            'bg-blue-100 text-blue-800': cotizacion.estado === 'enviada',
                                            'bg-gray-100 text-gray-800': cotizacion.estado === 'vencida'
                                        }"
                                        x-text="cotizacion.estado.charAt(0).toUpperCase() + cotizacion.estado.slice(1)">
                                    </span>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center space-x-1">
                                        <!-- Vista -->
                                        <button @click="verCotizacion(cotizacion.id)" class="btn btn-primary"
                                            title="Ver detalles">
                                            <i class="fas fa-eye w-4 h-4"></i>
                                        </button>

                                        <!-- Editar -->
                                        <button @click="editarCotizacion(cotizacion.id)" class="btn btn-warning"
                                            title="Editar">
                                            <i class="fas fa-edit w-4 h-4"></i>
                                        </button>

                                        <!-- PDF -->
                                        <button @click="generarPDF(cotizacion.id)" class="btn btn-danger"
                                            title="Descargar PDF">
                                            <i class="fas fa-file-pdf w-4 h-4"></i>
                                        </button>

                                        <!-- Email -->
                                        <button @click="enviarEmail(cotizacion.id)" class="btn btn-success"
                                            title="Enviar por email">
                                            <i class="fas fa-paper-plane w-4 h-4"></i>
                                        </button>

                                        <!-- Eliminar -->
                                        <button @click="eliminarCotizacion(cotizacion.id)" class="btn btn-danger"
                                            title="Eliminar">
                                            <i class="fas fa-trash w-4 h-4"></i>
                                        </button>


                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Mostrando <span class="font-medium" x-text="cotizacionesFiltradas.length"></span> de
                        <span class="font-medium" x-text="cotizaciones.length"></span> registros
                    </div>

                    <div class="flex items-center space-x-2">
                        <button @click="paginaActual--" :disabled="paginaActual === 1"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                            :class="{ 'cursor-not-allowed opacity-50': paginaActual === 1 }">
                            <i class="fas fa-chevron-left mr-1 w-3 h-3"></i>
                            Anterior
                        </button>

                        <div class="flex items-center space-x-1">
                            <template x-for="pagina in totalPaginas" :key="pagina">
                                <button @click="paginaActual = pagina"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200"
                                    :class="pagina === paginaActual ?
                                        'bg-blue-600 text-white' :
                                        'text-gray-700 hover:bg-gray-100 border border-gray-300'"
                                    x-text="pagina">
                                </button>
                            </template>
                        </div>

                        <button @click="paginaActual++" :disabled="paginaActual === totalPaginas"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                            :class="{ 'cursor-not-allowed opacity-50': paginaActual === totalPaginas }">
                            Siguiente
                            <i class="fas fa-chevron-right ml-1 w-3 h-3"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/cotizaciones/index-cotizaciones.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
    // Configuración de toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
</script>
</x-layout.default>
