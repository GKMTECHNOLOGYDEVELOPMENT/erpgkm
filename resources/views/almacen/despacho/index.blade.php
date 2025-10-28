<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 0.3s ease;
        }

        .fade-enter-from,
        .fade-leave-to {
            opacity: 0;
        }
    </style>
    <div x-data="despachoIndex()" class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="p-3 bg-blue-500 rounded-xl shadow-lg">
                            <i class="fas fa-shipping-fast text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                Gestión de Despachos
                            </h1>
                            <p class="text-gray-600 mt-1">Administra y controla todos tus despachos</p>
                        </div>
                    </div>
                    <a href="{{ route('despacho.create') }}"
                        class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md transition">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Nuevo Despacho
                    </a>
                </div>
            </div>

            <!-- Stats Cards con Iconos Mejorados -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Total Despachos -->
                <div
                    class="group bg-white rounded-2xl shadow-lg p-6 border border-blue-200 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Despachos</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1" x-text="totalDespachos"></p>
                        </div>
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-blue-100 rounded-2xl group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-boxes text-2xl text-blue-500 group-hover:text-white transition-all"></i>
                        </div>
                    </div>
                </div>

                <!-- Completados -->
                <div
                    class="group bg-white rounded-2xl shadow-lg p-6 border border-green-200 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Completados</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1" x-text="estados.completado"></p>
                        </div>
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-green-100 rounded-2xl group-hover:bg-green-500 group-hover:text-white transition-all duration-300">
                            <i
                                class="fas fa-check-circle text-2xl text-green-500 group-hover:text-white transition-all"></i>
                        </div>
                    </div>
                </div>

                <!-- En Proceso -->
                <div
                    class="group bg-white rounded-2xl shadow-lg p-6 border border-yellow-200 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">En Proceso</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1" x-text="estados.en_proceso"></p>
                        </div>
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-yellow-100 rounded-2xl group-hover:bg-yellow-500 group-hover:text-white transition-all duration-300">
                            <i
                                class="fas fa-sync-alt text-2xl text-yellow-500 group-hover:text-white transition-all"></i>
                        </div>
                    </div>
                </div>

                <!-- Pendientes -->
                <div
                    class="group bg-white rounded-2xl shadow-lg p-6 border border-red-200 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pendientes</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1" x-text="estados.pendiente"></p>
                        </div>
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-red-100 rounded-2xl group-hover:bg-red-500 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-clock text-2xl text-red-500 group-hover:text-white transition-all"></i>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Panel de Filtros Formal -->
            <section class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-10">
                <!-- Encabezado -->
                <header
                    class="flex flex-col md:flex-row md:items-center md:justify-between bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-filter text-blue-600"></i>
                        Panel de Filtros
                    </h2>
                    <div class="mt-3 md:mt-0 flex items-center gap-3">
                        <button @click="limpiarFiltros"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-all duration-200">
                            <i class="fas fa-undo-alt"></i>
                            Limpiar
                        </button>
                        <button @click="aplicarFiltros"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-sm transition-all duration-200">
                            <i class="fas fa-search"></i>
                            Aplicar
                        </button>
                    </div>
                </header>

                <!-- Contenido de filtros -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Buscar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar despacho</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" x-model="filters.search" placeholder="Ingrese término de búsqueda..."
                                class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 text-gray-800 placeholder-gray-400">
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-traffic-light"></i>
                            </span>
                            <select x-model="filters.estado"
                                class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 text-gray-800">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En proceso</option>
                                <option value="completado">Completado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha desde</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input id="fecha_desde" type="text" x-model="filters.fecha_desde"
                                placeholder="Selecciona una fecha"
                                class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 text-gray-800">
                        </div>
                    </div>

                    <!-- Fecha Hasta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha hasta</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            <input id="fecha_hasta" type="text" x-model="filters.fecha_hasta"
                                placeholder="Selecciona una fecha"
                                class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 text-gray-800">
                        </div>
                    </div>
                </div>

                <!-- Pie -->
                <footer
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 px-6 py-4 border-t border-gray-200 text-sm text-gray-600">
                    <p>
                        Mostrando <span class="font-semibold text-gray-800" x-text="despachosFiltrados.length"></span>
                        de
                        <span class="font-semibold text-gray-800" x-text="totalDespachos"></span> despachos.
                    </p>
                    <p class="mt-2 sm:mt-0 text-gray-500 italic">Actualizado: <span
                            x-text="new Date().toLocaleDateString()"></span></p>
                </footer>
            </section>



            <!-- Tabla de Despachos -->
            <div class="panel rounded-2xl shadow-lg overflow-hidden backdrop-blur-sm">
                <!-- Contenedor Scroll -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-700">
                        <!-- Encabezado -->
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <tr>
                                <template
                                    x-for="col in [
                        {label: '#', field: 'id'},
                        {label: 'Número', field: 'numero'},
                        {label: 'Cliente', field: ''},
                        {label: 'Fecha Entrega', field: 'fecha_entrega'},
                        {label: 'Total', field: 'total'},
                        {label: 'Estado', field: ''},
                        {label: 'Acciones', field: ''}
                    ]">
                                    <th scope="col"
                                        class="px-6 py-4 text-left font-semibold uppercase tracking-wide text-xs text-gray-600 cursor-pointer select-none"
                                        @click="col.field && ordenarPor(col.field)">
                                        <div class="flex items-center space-x-1">
                                            <span x-text="col.label"></span>
                                            <i class="fas text-xs"
                                                :class="col.field && sortField === col.field ?
                                                    (sortDirection === 'asc' ?
                                                        'fa-arrow-up text-blue-600' :
                                                        'fa-arrow-down text-blue-600') :
                                                    (col.field ? 'fa-sort text-gray-400' : '')">
                                            </i>
                                        </div>
                                    </th>
                                </template>
                            </tr>
                        </thead>

                        <!-- Cuerpo -->
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="despacho in despachosFiltrados" :key="despacho.id">
                                <tr class="hover:bg-blue-50/50 transition-all duration-200">
                                    <!-- ID -->
                                    <td class="px-6 py-4 font-medium text-gray-900" x-text="despacho.id"></td>

                                    <!-- Número -->
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="font-semibold text-gray-800" x-text="despacho.numero"></span>
                                            <p class="text-xs text-gray-500" x-text="despacho.tipo_guia"></p>
                                        </div>
                                    </td>

                                    <!-- Cliente -->
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="font-medium text-gray-900"
                                                x-text="despacho.cliente_nombre"></span>
                                            <p class="text-xs text-gray-500" x-text="despacho.documento"></p>
                                        </div>
                                    </td>

                                    <!-- Fecha -->
                                    <td class="px-6 py-4">
                                        <span class="text-gray-800"
                                            x-text="formatFecha(despacho.fecha_entrega)"></span>
                                    </td>

                                    <!-- Total -->
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-emerald-600"
                                            x-text="`S/ ${formatMoneda(despacho.total)}`"></span>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium shadow-sm"
                                            :class="getEstadoClasses(despacho.estado)">
                                            <i class="mr-1 text-xs" :class="getEstadoIcon(despacho.estado)"></i>
                                            <span x-text="getEstadoTexto(despacho.estado)"></span>
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Ver -->
                                            <a :href="`/despacho/${despacho.id}`"
                                                class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all duration-200 shadow-sm"
                                                title="Ver detalles">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <!-- Editar -->
                                            <a :href="`/despacho/${despacho.id}/edit`"
                                                class="p-2 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all duration-200 shadow-sm"
                                                title="Editar despacho">
                                                <i class="fas fa-pen text-sm"></i>
                                            </a>
                                            <!-- Eliminar -->
                                            <button @click="confirmarEliminacion(despacho)"
                                                class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 shadow-sm"
                                                title="Eliminar despacho">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Estado vacío -->
                <div x-show="despachosFiltrados.length === 0"
                    class="text-center py-20 bg-gray-50 border-t border-gray-100">
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center shadow-inner">
                            <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No se encontraron despachos</h3>
                        <p class="text-gray-600 mb-6"
                            x-text="filters.search ? 'No se encontraron resultados para tu búsqueda.' : 'Aún no tienes despachos registrados.'">
                        </p>
                        <a href="{{ route('despacho.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all duration-200">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Crear Nuevo Despacho
                        </a>
                    </div>
                </div>
            </div>


        </div>

        <!-- Modal de Confirmación Eliminar -->
        <div x-show="modalEliminar.open"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6"
                @click.outside="modalEliminar.open = false">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 bg-red-100 rounded-xl">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Confirmar Eliminación</h3>
                        <p class="text-gray-600">¿Está seguro de eliminar este despacho?</p>
                    </div>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-red-800">
                        Despacho: <strong x-text="modalEliminar.despacho?.numero"></strong>
                    </p>
                    <p class="text-xs text-red-600 mt-1">Esta acción no se puede deshacer.</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button @click="modalEliminar.open = false"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button @click="eliminarDespacho"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl transition-colors duration-200 flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        // Definir la función globalmente antes de Alpine
        function despachoIndex() {
            return {
                loading: false,
                despachos: @json($despachos),
                filters: {
                    search: '',
                    estado: '',
                    fecha_desde: '',
                    fecha_hasta: ''
                },
                sortField: 'id',
                sortDirection: 'desc',
                modalEliminar: {
                    open: false,
                    despacho: null
                },

                init() {
                    console.log('Alpine initialized with', this.despachos.length, 'despachos');

                    // Inicializar Flatpickr después de que el DOM esté listo
                    this.$nextTick(() => {
                        // Inicializar Flatpickr con idioma español
                        flatpickr("#fecha_desde", {
                            dateFormat: "Y-m-d",
                            locale: "es",
                            allowInput: true,
                            onChange: (selectedDates, dateStr) => {
                                this.filters.fecha_desde = dateStr;
                            }
                        });

                        flatpickr("#fecha_hasta", {
                            dateFormat: "Y-m-d",
                            locale: "es",
                            allowInput: true,
                            onChange: (selectedDates, dateStr) => {
                                this.filters.fecha_hasta = dateStr;
                            }
                        });
                    });
                },

                get despachosFiltrados() {
                    let filtered = this.despachos;

                    // Filtro de búsqueda
                    if (this.filters.search) {
                        const searchTerm = this.filters.search.toLowerCase();
                        filtered = filtered.filter(despacho =>
                            despacho.numero.toLowerCase().includes(searchTerm) ||
                            (despacho.cliente_nombre && despacho.cliente_nombre.toLowerCase().includes(
                                searchTerm)) ||
                            despacho.tipo_guia.toLowerCase().includes(searchTerm)
                        );
                    }

                    // Filtro por estado
                    if (this.filters.estado) {
                        filtered = filtered.filter(despacho => despacho.estado === this.filters.estado);
                    }

                    // Filtro por fecha
                    if (this.filters.fecha_desde) {
                        filtered = filtered.filter(despacho =>
                            new Date(despacho.fecha_entrega) >= new Date(this.filters.fecha_desde)
                        );
                    }

                    if (this.filters.fecha_hasta) {
                        filtered = filtered.filter(despacho =>
                            new Date(despacho.fecha_entrega) <= new Date(this.filters.fecha_hasta)
                        );
                    }

                    // Ordenamiento
                    filtered.sort((a, b) => {
                        let aValue = a[this.sortField];
                        let bValue = b[this.sortField];

                        if (this.sortField === 'fecha_entrega') {
                            aValue = new Date(aValue);
                            bValue = new Date(bValue);
                        }

                        if (this.sortField === 'total') {
                            aValue = parseFloat(aValue);
                            bValue = parseFloat(bValue);
                        }

                        if (aValue < bValue) return this.sortDirection === 'asc' ? -1 : 1;
                        if (aValue > bValue) return this.sortDirection === 'asc' ? 1 : -1;
                        return 0;
                    });

                    return filtered;
                },

                get totalDespachos() {
                    return this.despachos.length;
                },

                get estados() {
                    return {
                        pendiente: this.despachos.filter(d => d.estado === 'pendiente').length,
                        en_proceso: this.despachos.filter(d => d.estado === 'en_proceso').length,
                        completado: this.despachos.filter(d => d.estado === 'completado').length,
                        cancelado: this.despachos.filter(d => d.estado === 'cancelado').length
                    };
                },

                limpiarFiltros() {
                    this.filters = {
                        search: '',
                        estado: '',
                        fecha_desde: '',
                        fecha_hasta: ''
                    };

                    // También limpiar los campos de Flatpickr
                    const fechaDesde = document.getElementById('fecha_desde');
                    const fechaHasta = document.getElementById('fecha_hasta');
                    if (fechaDesde._flatpickr) fechaDesde._flatpickr.clear();
                    if (fechaHasta._flatpickr) fechaHasta._flatpickr.clear();
                },

                aplicarFiltros() {
                    // Los filtros se aplican automáticamente a través de la propiedad computada
                    console.log('Filtros aplicados:', this.filters);
                },

                ordenarPor(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                },

                getEstadoClasses(estado) {
                    const classes = {
                        pendiente: 'bg-yellow-100 text-yellow-800',
                        en_proceso: 'bg-blue-100 text-blue-800',
                        completado: 'bg-green-100 text-green-800',
                        cancelado: 'bg-red-100 text-red-800'
                    };
                    return classes[estado] || 'bg-gray-100 text-gray-800';
                },

                getEstadoIcon(estado) {
                    const icons = {
                        pendiente: 'fas fa-clock',
                        en_proceso: 'fas fa-sync-alt',
                        completado: 'fas fa-check-circle',
                        cancelado: 'fas fa-times-circle'
                    };
                    return icons[estado] || 'fas fa-question-circle';
                },

                getEstadoTexto(estado) {
                    const textos = {
                        pendiente: 'Pendiente',
                        en_proceso: 'En Proceso',
                        completado: 'Completado',
                        cancelado: 'Cancelado'
                    };
                    return textos[estado] || 'Desconocido';
                },

                formatFecha(fecha) {
                    if (!fecha) return 'N/A';
                    return new Date(fecha).toLocaleDateString('es-ES');
                },

                formatMoneda(monto) {
                    return parseFloat(monto || 0).toFixed(2);
                },

                confirmarEliminacion(despacho) {
                    this.modalEliminar.despacho = despacho;
                    this.modalEliminar.open = true;
                },

                async eliminarDespacho() {
                    if (!this.modalEliminar.despacho) return;

                    try {
                        const response = await fetch(`/despacho/${this.modalEliminar.despacho.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });

                        if (response.ok) {
                            this.despachos = this.despachos.filter(d => d.id !== this.modalEliminar.despacho.id);
                            this.modalEliminar.open = false;
                            this.mostrarMensaje('Despacho eliminado exitosamente', 'success');
                        } else {
                            throw new Error('Error al eliminar');
                        }
                    } catch (error) {
                        this.mostrarMensaje('Error al eliminar el despacho', 'error');
                    }
                },

                mostrarMensaje(mensaje, tipo) {
                    // Notificación simple
                    alert(mensaje);
                }
            };
        }
    </script>
</x-layout.default>
