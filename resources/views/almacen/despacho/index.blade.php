{{-- resources/views/almacen/despacho/index.blade.php --}}
<x-layout.default>
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
                },

                get despachosFiltrados() {
                    let filtered = this.despachos;

                    // Filtro de búsqueda
                    if (this.filters.search) {
                        const searchTerm = this.filters.search.toLowerCase();
                        filtered = filtered.filter(despacho => 
                            despacho.numero.toLowerCase().includes(searchTerm) ||
                            (despacho.cliente_nombre && despacho.cliente_nombre.toLowerCase().includes(searchTerm)) ||
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
            }
        }
    </script>

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
                            <h1 class="text-3xl font-bold text-gray-900">Gestión de Despachos</h1>
                            <p class="text-gray-600 mt-1">Administra y controla todos tus despachos</p>
                        </div>
                    </div>
                    <a href="{{ route('despacho.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Nuevo Despacho
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Despachos</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="totalDespachos"></p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl">
                            <i class="fas fa-boxes text-blue-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Completados</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="estados.completado"></p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-xl">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">En Proceso</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="estados.en_proceso"></p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-xl">
                            <i class="fas fa-sync-alt text-yellow-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pendientes</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="estados.pendiente"></p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-xl">
                            <i class="fas fa-clock text-red-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   x-model="filters.search" 
                                   placeholder="Buscar despacho..." 
                                   class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select x-model="filters.estado" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="completado">Completado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>

                    <!-- Fecha Desde -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                        <input type="date" 
                               x-model="filters.fecha_desde" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>

                    <!-- Fecha Hasta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                        <input type="date" 
                               x-model="filters.fecha_hasta" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                </div>

                <!-- Botones de acción filtros -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-600">
                        Mostrando <span x-text="despachosFiltrados.length"></span> de <span x-text="totalDespachos"></span> despachos
                    </div>
                    <button @click="limpiarFiltros" 
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all duration-200">
                        <i class="fas fa-eraser mr-2"></i>
                        Limpiar Filtros
                    </button>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer" 
                                    @click="ordenarPor('id')">
                                    <div class="flex items-center space-x-1">
                                        <span>#</span>
                                        <i class="fas text-xs" 
                                           :class="sortField === 'id' ? (sortDirection === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down') : 'fa-sort'"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                    @click="ordenarPor('numero')">
                                    <div class="flex items-center space-x-1">
                                        <span>Número</span>
                                        <i class="fas text-xs" 
                                           :class="sortField === 'numero' ? (sortDirection === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down') : 'fa-sort'"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                    @click="ordenarPor('fecha_entrega')">
                                    <div class="flex items-center space-x-1">
                                        <span>Fecha Entrega</span>
                                        <i class="fas text-xs" 
                                           :class="sortField === 'fecha_entrega' ? (sortDirection === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down') : 'fa-sort'"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                    @click="ordenarPor('total')">
                                    <div class="flex items-center space-x-1">
                                        <span>Total</span>
                                        <i class="fas text-xs" 
                                           :class="sortField === 'total' ? (sortDirection === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down') : 'fa-sort'"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="despacho in despachosFiltrados" :key="despacho.id">
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900" x-text="despacho.id"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <span class="text-sm font-semibold text-gray-900" x-text="despacho.numero"></span>
                                            <p class="text-xs text-gray-500" x-text="despacho.tipo_guia"></p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900" x-text="despacho.cliente_nombre"></span>
                                            <p class="text-xs text-gray-500" x-text="despacho.documento"></p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900" x-text="formatFecha(despacho.fecha_entrega)"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-green-600" x-text="`S/ ${formatMoneda(despacho.total)}`"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                              :class="getEstadoClasses(despacho.estado)">
                                            <i class="mr-1 text-xs" :class="getEstadoIcon(despacho.estado)"></i>
                                            <span x-text="getEstadoTexto(despacho.estado)"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <!-- Ver -->
                                            <a :href="`/despacho/${despacho.id}`" 
                                               class="inline-flex items-center p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200"
                                               title="Ver detalles">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>

                                            <!-- Editar -->
                                            <a :href="`/despacho/${despacho.id}/edit`" 
                                               class="inline-flex items-center p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors duration-200"
                                               title="Editar">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>

                                            <!-- Eliminar -->
                                            <button @click="confirmarEliminacion(despacho)" 
                                                    class="inline-flex items-center p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-200"
                                                    title="Eliminar">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div x-show="despachosFiltrados.length === 0" class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No se encontraron despachos</h3>
                        <p class="text-gray-600 mb-6" x-text="filters.search ? 'Intenta con otros términos de búsqueda' : 'Comienza creando tu primer despacho'"></p>
                        <a href="{{ route('despacho.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Crear Primer Despacho
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación Eliminar -->
        <div x-show="modalEliminar.open" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
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

    <style>
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.3s ease;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }
    </style>
</x-layout.default>