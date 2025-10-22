<x-layout.default>
    <div x-data="solicitudRepuestos">
        <!-- Header con contador de seleccionados -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-clipboard-list text-blue-500 mr-3"></i>
                        Solicitudes de Repuestos
                    </h1>
                    <p class="text-gray-600 mt-2">Gestiona todas las solicitudes de repuestos del sistema</p>
                </div>
                
                <!-- Contador de seleccionados -->
                <div x-show="selectedItems.length > 0" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="bg-blue-500 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                    <span x-text="selectedItems.length"></span>
                    <span>seleccionados</span>
                    <button @click="clearSelection()" class="ml-2 hover:text-blue-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500 hover:shadow-md transition cursor-pointer"
                 @click="filterByStatus('pendiente')">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-clock text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pendientes</p>
                        <p class="text-2xl font-bold" x-text="stats.pendientes"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500 hover:shadow-md transition cursor-pointer"
                 @click="filterByStatus('aprobado')">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Aprobadas</p>
                        <p class="text-2xl font-bold" x-text="stats.aprobadas"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500 hover:shadow-md transition cursor-pointer"
                 @click="filterByStatus('rechazado')">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-times-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Rechazadas</p>
                        <p class="text-2xl font-bold" x-text="stats.rechazadas"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500 hover:shadow-md transition cursor-pointer"
                 @click="clearFilters()">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-tools text-purple-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold" x-text="stats.total"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Herramientas -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                <div class="flex space-x-2">
                    <button @click="showCreateModal = true" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <i class="fas fa-plus mr-2"></i> Nueva Solicitud
                    </button>
                    
                    <!-- Menu de acciones en lote -->
                    <div x-show="selectedItems.length > 0" 
                         x-transition
                         class="relative">
                        <button @click="bulkActionsOpen = !bulkActionsOpen"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                            <i class="fas fa-cog mr-2"></i> Acciones
                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                        </button>
                        
                        <div x-show="bulkActionsOpen" 
                             @click.away="bulkActionsOpen = false"
                             class="absolute top-12 left-0 bg-white shadow-lg rounded-lg py-2 z-10 min-w-48">
                            <button @click="bulkUpdateStatus('aprobado')" 
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i> Aprobar seleccionados
                            </button>
                            <button @click="bulkUpdateStatus('rechazado')" 
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center">
                                <i class="fas fa-times text-red-500 mr-2"></i> Rechazar seleccionados
                            </button>
                            <button @click="exportSelected()" 
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center">
                                <i class="fas fa-download text-blue-500 mr-2"></i> Exportar seleccionados
                            </button>
                        </div>
                    </div>
                    
                    <button @click="exportAll()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <i class="fas fa-download mr-2"></i> Exportar Todo
                    </button>
                </div>
                
                <div class="flex space-x-2">
                    <div class="relative">
                        <input type="text" 
                               x-model="searchTerm"
                               @input="debouncedSearch()"
                               placeholder="Buscar solicitud..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <button x-show="searchTerm" 
                                @click="searchTerm = ''; filterSolicitudes()"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <select x-model="statusFilter" @change="filterSolicitudes()"
                            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>

        <!-- Tabla de Solicitudes -->
        <div x-show="!loading" class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" 
                                       @change="toggleAll()"
                                       x-bind:checked="selectedItems.length === filteredSolicitudes.length && filteredSolicitudes.length > 0"
                                       class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                @click="sortBy('id')">
                                ID
                                <i class="fas fa-sort ml-1 text-gray-400" 
                                   x-bind:class="{
                                       'fa-sort-up text-blue-500': sortField === 'id' && sortDirection === 'asc',
                                       'fa-sort-down text-blue-500': sortField === 'id' && sortDirection === 'desc'
                                   }"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                @click="sortBy('solicitante')">
                                Solicitante
                                <i class="fas fa-sort ml-1 text-gray-400"
                                   x-bind:class="{
                                       'fa-sort-up text-blue-500': sortField === 'solicitante' && sortDirection === 'asc',
                                       'fa-sort-down text-blue-500': sortField === 'solicitante' && sortDirection === 'desc'
                                   }"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Repuesto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                @click="sortBy('cantidad')">
                                Cantidad
                                <i class="fas fa-sort ml-1 text-gray-400"
                                   x-bind:class="{
                                       'fa-sort-up text-blue-500': sortField === 'cantidad' && sortDirection === 'asc',
                                       'fa-sort-down text-blue-500': sortField === 'cantidad' && sortDirection === 'desc'
                                   }"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                @click="sortBy('fecha')">
                                Fecha
                                <i class="fas fa-sort ml-1 text-gray-400"
                                   x-bind:class="{
                                       'fa-sort-up text-blue-500': sortField === 'fecha' && sortDirection === 'asc',
                                       'fa-sort-down text-blue-500': sortField === 'fecha' && sortDirection === 'desc'
                                   }"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="solicitud in filteredSolicitudes" :key="solicitud.id">
                            <tr class="hover:bg-gray-50 transition"
                                x-bind:class="{
                                    'bg-blue-50': selectedItems.includes(solicitud.id)
                                }">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           x-bind:checked="selectedItems.includes(solicitud.id)"
                                           @change="toggleSelection(solicitud.id)"
                                           class="rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" 
                                    x-text="solicitud.id"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full flex items-center justify-center"
                                             x-bind:class="getUserColor(solicitud.solicitante)">
                                            <span x-text="solicitud.solicitante.charAt(0)" 
                                                  x-bind:class="getUserTextColor(solicitud.solicitante)"></span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900" x-text="solicitud.solicitante"></p>
                                            <p class="text-sm text-gray-500" x-text="solicitud.departamento"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" 
                                    x-text="solicitud.repuesto"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" 
                                    x-text="solicitud.cantidad"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" 
                                    x-text="formatDate(solicitud.fecha)"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer"
                                          x-bind:class="getStatusClasses(solicitud.estado)"
                                          @click="changeStatus(solicitud)">
                                        <span x-text="getStatusText(solicitud.estado)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button @click="viewSolicitud(solicitud)" 
                                            class="text-blue-600 hover:text-blue-900 transition"
                                            title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="editSolicitud(solicitud)" 
                                            class="text-green-600 hover:text-green-900 transition"
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteSolicitud(solicitud)" 
                                            class="text-red-600 hover:text-red-900 transition"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Empty State -->
                        <tr x-show="filteredSolicitudes.length === 0 && !loading">
                            <td colspan="8" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-2"></i>
                                <p class="text-gray-500 text-lg">No se encontraron solicitudes</p>
                                <p class="text-gray-400 text-sm mt-1" x-show="searchTerm || statusFilter">
                                    Intenta ajustar los filtros de búsqueda
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-700">
                            Mostrando
                            <span class="font-medium" x-text="filteredSolicitudes.length"></span>
                            de
                            <span class="font-medium" x-text="solicitudes.length"></span>
                            resultados
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="previousPage()" 
                                x-bind:disabled="currentPage === 1"
                                x-bind:class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                            Anterior
                        </button>
                        <button @click="nextPage()" 
                                x-bind:disabled="currentPage * itemsPerPage >= filteredSolicitudes.length"
                                x-bind:class="currentPage * itemsPerPage >= filteredSolicitudes.length ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear/editar solicitud -->
        <div x-show="showCreateModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-90vh overflow-y-auto">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold" x-text="editingSolicitud ? 'Editar Solicitud' : 'Nueva Solicitud'"></h3>
                </div>
                <div class="p-6">
                    <!-- Formulario aquí -->
                    <p class="text-gray-600">Formulario para crear/editar solicitudes...</p>
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-3">
                    <button @click="showCreateModal = false" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                        Cancelar
                    </button>
                    <button @click="saveSolicitud()" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Definir la función globalmente antes de que Alpine.js la use
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuestos', () => ({
                // Estado de la aplicación
                loading: true,
                solicitudes: [],
                filteredSolicitudes: [],
                selectedItems: [],
                bulkActionsOpen: false,
                showCreateModal: false,
                editingSolicitud: null,
                
                // Filtros y búsqueda
                searchTerm: '',
                statusFilter: '',
                sortField: 'fecha',
                sortDirection: 'desc',
                
                // Paginación
                currentPage: 1,
                itemsPerPage: 10,
                
                // Estadísticas
                stats: {
                    pendientes: 0,
                    aprobadas: 0,
                    rechazadas: 0,
                    total: 0
                },
                
                // Inicialización
                init() {
                    this.loadSampleData();
                    this.calculateStats();
                },
                
                // Cargar datos de ejemplo
                loadSampleData() {
                    // Simular carga de datos
                    setTimeout(() => {
                        this.solicitudes = [
                            {
                                id: '#SOL-001',
                                solicitante: 'Juan Pérez',
                                departamento: 'Taller Mecánico',
                                repuesto: 'Filtro de Aceite',
                                cantidad: 5,
                                fecha: '2024-03-15',
                                estado: 'pendiente'
                            },
                            {
                                id: '#SOL-002',
                                solicitante: 'María García',
                                departamento: 'Electricidad',
                                repuesto: 'Bujías',
                                cantidad: 12,
                                fecha: '2024-03-14',
                                estado: 'aprobado'
                            },
                            {
                                id: '#SOL-003',
                                solicitante: 'Carlos López',
                                departamento: 'Pintura',
                                repuesto: 'Pastillas de Freno',
                                cantidad: 4,
                                fecha: '2024-03-13',
                                estado: 'rechazado'
                            },
                            {
                                id: '#SOL-004',
                                solicitante: 'Ana Rodríguez',
                                departamento: 'Mecánica',
                                repuesto: 'Aceite Motor',
                                cantidad: 8,
                                fecha: '2024-03-12',
                                estado: 'pendiente'
                            },
                            {
                                id: '#SOL-005',
                                solicitante: 'Luis Martínez',
                                departamento: 'Electricidad',
                                repuesto: 'Fusibles',
                                cantidad: 20,
                                fecha: '2024-03-11',
                                estado: 'aprobado'
                            }
                        ];
                        this.filteredSolicitudes = [...this.solicitudes];
                        this.loading = false;
                        this.calculateStats();
                    }, 1000);
                },
                
                // Filtrar solicitudes
                filterSolicitudes() {
                    let filtered = this.solicitudes;
                    
                    // Filtrar por búsqueda
                    if (this.searchTerm) {
                        const term = this.searchTerm.toLowerCase();
                        filtered = filtered.filter(s => 
                            s.solicitante.toLowerCase().includes(term) ||
                            s.repuesto.toLowerCase().includes(term) ||
                            s.departamento.toLowerCase().includes(term) ||
                            s.id.toLowerCase().includes(term)
                        );
                    }
                    
                    // Filtrar por estado
                    if (this.statusFilter) {
                        filtered = filtered.filter(s => s.estado === this.statusFilter);
                    }
                    
                    // Ordenar
                    filtered.sort((a, b) => {
                        let aVal = a[this.sortField];
                        let bVal = b[this.sortField];
                        
                        if (this.sortField === 'fecha') {
                            aVal = new Date(aVal);
                            bVal = new Date(bVal);
                        }
                        
                        if (aVal < bVal) return this.sortDirection === 'asc' ? -1 : 1;
                        if (aVal > bVal) return this.sortDirection === 'asc' ? 1 : -1;
                        return 0;
                    });
                    
                    this.filteredSolicitudes = filtered;
                    this.calculateStats();
                },
                
                // Búsqueda con debounce
                debouncedSearch() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.filterSolicitudes();
                    }, 300);
                },
                
                // Ordenar
                sortBy(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                    this.filterSolicitudes();
                },
                
                // Selección múltiple
                toggleAll() {
                    if (this.selectedItems.length === this.filteredSolicitudes.length) {
                        this.selectedItems = [];
                    } else {
                        this.selectedItems = this.filteredSolicitudes.map(s => s.id);
                    }
                },
                
                toggleSelection(id) {
                    if (this.selectedItems.includes(id)) {
                        this.selectedItems = this.selectedItems.filter(item => item !== id);
                    } else {
                        this.selectedItems.push(id);
                    }
                },
                
                clearSelection() {
                    this.selectedItems = [];
                },
                
                // Filtros rápidos
                filterByStatus(status) {
                    this.statusFilter = status;
                    this.filterSolicitudes();
                },
                
                clearFilters() {
                    this.searchTerm = '';
                    this.statusFilter = '';
                    this.filterSolicitudes();
                },
                
                // Calcular estadísticas
                calculateStats() {
                    this.stats.pendientes = this.solicitudes.filter(s => s.estado === 'pendiente').length;
                    this.stats.aprobadas = this.solicitudes.filter(s => s.estado === 'aprobado').length;
                    this.stats.rechazadas = this.solicitudes.filter(s => s.estado === 'rechazado').length;
                    this.stats.total = this.solicitudes.length;
                },
                
                // Acciones
                viewSolicitud(solicitud) {
                    alert(`Viendo: ${solicitud.id}\nSolicitante: ${solicitud.solicitante}\nRepuesto: ${solicitud.repuesto}`);
                },
                
                editSolicitud(solicitud) {
                    this.editingSolicitud = solicitud;
                    this.showCreateModal = true;
                },
                
                deleteSolicitud(solicitud) {
                    if (confirm(`¿Estás seguro de eliminar la solicitud ${solicitud.id}?`)) {
                        this.solicitudes = this.solicitudes.filter(s => s.id !== solicitud.id);
                        this.filterSolicitudes();
                    }
                },
                
                changeStatus(solicitud) {
                    const statuses = ['pendiente', 'aprobado', 'rechazado'];
                    const currentIndex = statuses.indexOf(solicitud.estado);
                    const nextIndex = (currentIndex + 1) % statuses.length;
                    solicitud.estado = statuses[nextIndex];
                    this.calculateStats();
                },
                
                bulkUpdateStatus(status) {
                    this.solicitudes.forEach(s => {
                        if (this.selectedItems.includes(s.id)) {
                            s.estado = status;
                        }
                    });
                    this.calculateStats();
                    this.clearSelection();
                    this.bulkActionsOpen = false;
                },
                
                exportSelected() {
                    const selected = this.solicitudes.filter(s => this.selectedItems.includes(s.id));
                    alert(`Exportando ${selected.length} solicitudes seleccionadas`);
                },
                
                exportAll() {
                    alert(`Exportando todas las solicitudes (${this.solicitudes.length})`);
                },
                
                // Utilidades
                getStatusClasses(status) {
                    const classes = {
                        pendiente: 'bg-yellow-100 text-yellow-800',
                        aprobado: 'bg-green-100 text-green-800',
                        rechazado: 'bg-red-100 text-red-800'
                    };
                    return classes[status] || 'bg-gray-100 text-gray-800';
                },
                
                getStatusText(status) {
                    const texts = {
                        pendiente: 'Pendiente',
                        aprobado: 'Aprobado',
                        rechazado: 'Rechazado'
                    };
                    return texts[status] || status;
                },
                
                getUserColor(name) {
                    const colors = ['bg-blue-100', 'bg-green-100', 'bg-red-100', 'bg-purple-100', 'bg-orange-100'];
                    const index = name.charCodeAt(0) % colors.length;
                    return colors[index];
                },
                
                getUserTextColor(name) {
                    const colors = ['text-blue-600', 'text-green-600', 'text-red-600', 'text-purple-600', 'text-orange-600'];
                    const index = name.charCodeAt(0) % colors.length;
                    return colors[index];
                },
                
                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                },
                
                // Paginación
                previousPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },
                
                nextPage() {
                    if (this.currentPage * this.itemsPerPage < this.filteredSolicitudes.length) {
                        this.currentPage++;
                    }
                },
                
                saveSolicitud() {
                    // Lógica para guardar
                    this.showCreateModal = false;
                    this.editingSolicitud = null;
                    alert('Solicitud guardada exitosamente');
                }
            }));
        });
    </script>
</x-layout.default>