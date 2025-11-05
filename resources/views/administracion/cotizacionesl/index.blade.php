<x-layout.default>
    <div x-data="ticketCotizaciones" class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    📋 Tickets con Suministros por Cotizar
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Lista de tickets que tienen suministros pendientes de cotización
                </p>
            </div>
            
            <!-- Botón Generar Cotización Masiva -->
            <div x-show="tickets.length > 0" class="flex items-center">
                <button 
                    @click="generarCotizacionMasiva"
                    class="flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generar Cotización
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div x-show="tickets.length > 0" class="flex flex-col sm:flex-row gap-4 mb-6">
            <!-- Buscador -->
            <div class="relative flex-1">
                <input 
                    x-model="searchTerm"
                    type="text" 
                    placeholder="Buscar ticket, cliente o falla..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            
            <!-- Filtro por Estado -->
            <div class="sm:w-64">
                <select 
                    x-model="filtroEstado"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Todos los estados</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado['id'] }}">{{ $estado['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Estado vacío -->
        <div x-show="tickets.length === 0" class="text-center py-12">
            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-xl font-medium mb-2">No hay tickets con suministros</p>
                <p class="text-sm">No se encontraron tickets que tengan suministros pendientes de cotización.</p>
            </div>
        </div>

        <!-- Stats y Tabla (solo se muestran si hay tickets) -->
        <div x-show="tickets.length > 0">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tickets</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="filteredTickets.length"></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1m4 0h-4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Suministros</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="totalSuministros"></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Items</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="totalItems"></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="pendientesCount"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <input 
                                        type="checkbox" 
                                        x-model="selectAll"
                                        @change="toggleSelectAll"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ticket
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Visita
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Suministros
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="ticket in filteredTickets" :key="ticket.idTickets">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input 
                                            type="checkbox" 
                                            x-model="selectedTickets"
                                            :value="ticket.idTickets"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        >
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                <span class="text-blue-600 dark:text-blue-400 font-bold" x-text="ticket.numero_ticket"></span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="ticket.numero_ticket"></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs" x-text="ticket.fallaReportada || 'Sin descripción'"></div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white" x-text="ticket.cliente_nombre || 'N/A'"></div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white" x-text="ticket.visita_nombre"></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400" x-text="formatDate(ticket.fecha_programada)"></div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-center">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="ticket.total_suministros"></span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Suministros</div>
                                            </div>
                                            <div class="text-center">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="ticket.total_items"></span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Items</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            x-bind:class="getEstadoBadgeClass(ticket.idEstadoots)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            x-text="getEstadoText(ticket.idEstadoots)"
                                        ></span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="formatDate(ticket.fecha_creacion)"></td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button 
                                                @click="verDetalles(ticket)"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors"
                                                title="Ver detalles"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            
                                            <button 
                                                @click="generarCotizacionIndividual(ticket)"
                                                class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors"
                                                title="Generar cotización"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </button>
                                            
                                            <a 
                                                :href="`/tickets/${ticket.idTickets}/edit`"
                                                class="text-orange-600 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300 transition-colors"
                                                title="Editar ticket"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            
                            <tr x-show="filteredTickets.length === 0 && tickets.length > 0">
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium mb-2">No se encontraron tickets</p>
                                        <p class="text-sm">No hay tickets que coincidan con tu búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Barra de acciones flotante para selección múltiple -->
        <div 
            x-show="selectedTickets.length > 0"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center space-x-4"
        >
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                <span x-text="selectedTickets.length"></span> tickets seleccionados
            </span>
            <button 
                @click="generarCotizacionMultiple"
                class="flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium text-sm"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generar Cotización Multiple
            </button>
            <button 
                @click="selectedTickets = []"
                class="flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors font-medium text-sm"
            >
                Cancelar
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ticketCotizaciones', () => ({
                tickets: @json($ticketsConSuministros),
                searchTerm: '',
                filtroEstado: '',
                selectedTickets: [],
                selectAll: false,
                
                get filteredTickets() {
                    if (!this.tickets) return [];
                    
                    return this.tickets.filter(ticket => {
                        const matchesSearch = this.searchTerm === '' || 
                            ticket.numero_ticket.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            (ticket.cliente_nombre && ticket.cliente_nombre.toLowerCase().includes(this.searchTerm.toLowerCase())) ||
                            (ticket.fallaReportada && ticket.fallaReportada.toLowerCase().includes(this.searchTerm.toLowerCase()));
                        
                        const matchesEstado = this.filtroEstado === '' || ticket.idEstadoots == this.filtroEstado;
                        
                        return matchesSearch && matchesEstado;
                    });
                },
                
                get totalSuministros() {
                    if (!this.filteredTickets) return 0;
                    return this.filteredTickets.reduce((sum, ticket) => sum + parseInt(ticket.total_suministros || 0), 0);
                },
                
                get totalItems() {
                    if (!this.filteredTickets) return 0;
                    return this.filteredTickets.reduce((sum, ticket) => sum + parseInt(ticket.total_items || 0), 0);
                },
                
                get pendientesCount() {
                    if (!this.filteredTickets) return 0;
                    return this.filteredTickets.filter(ticket => ticket.idEstadoots === 2).length;
                },
                
                toggleSelectAll() {
                    if (this.selectAll) {
                        this.selectedTickets = this.filteredTickets.map(ticket => ticket.idTickets);
                    } else {
                        this.selectedTickets = [];
                    }
                },
                
                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                },
                
                getEstadoBadgeClass(estado) {
                    const classes = {
                        2: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400', // Pendiente
                        4: 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400', // En proceso
                        5: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400', // Completado
                        6: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' // Cancelado
                    };
                    return classes[estado] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400';
                },
                
                getEstadoText(estado) {
                    const estados = {
                        2: 'Pendiente Cotización',
                        4: 'En Proceso', 
                        5: 'Completado',
                        6: 'Cancelado'
                    };
                    return estados[estado] || 'Desconocido';
                },
                
                verDetalles(ticket) {
                    // Aquí puedes implementar un modal o redirección
                    alert(`Ver detalles del ticket: ${ticket.numero_ticket}`);
                },
                
                generarCotizacionIndividual(ticket) {
                    // Generar cotización para un solo ticket
                    if (confirm(`¿Generar cotización para el ticket ${ticket.numero_ticket}?`)) {
                        // Aquí va tu lógica para generar la cotización individual
                        console.log('Generando cotización para:', ticket);
                        // window.location.href = `/cotizaciones/generar/${ticket.idTickets}`;
                    }
                },
                
                generarCotizacionMultiple() {
                    // Generar cotización para múltiples tickets seleccionados
                    if (this.selectedTickets.length === 0) {
                        alert('Selecciona al menos un ticket');
                        return;
                    }
                    
                    if (confirm(`¿Generar cotización para ${this.selectedTickets.length} tickets seleccionados?`)) {
                        // Aquí va tu lógica para generar cotización múltiple
                        console.log('Generando cotización para tickets:', this.selectedTickets);
                        // window.location.href = `/cotizaciones/generar-multiple?tickets=${this.selectedTickets.join(',')}`;
                    }
                },
                
                generarCotizacionMasiva() {
                    // Generar cotización para todos los tickets filtrados
                    if (this.filteredTickets.length === 0) {
                        alert('No hay tickets para generar cotización');
                        return;
                    }
                    
                    if (confirm(`¿Generar cotización para todos los ${this.filteredTickets.length} tickets mostrados?`)) {
                        const ticketIds = this.filteredTickets.map(ticket => ticket.idTickets);
                        console.log('Generando cotización masiva para:', ticketIds);
                        // window.location.href = `/cotizaciones/generar-multiple?tickets=${ticketIds.join(',')}`;
                    }
                }
            }));
        });
    </script>
</x-layout.default>