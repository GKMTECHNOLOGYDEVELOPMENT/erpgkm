<x-layout.default>
    <div x-data="comprasList" x-init="init()">
        <!-- Filtros - Agregar filtro por estado -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <!-- Fecha Inicio -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Fecha Inicio</label>
                <input type="date" x-model="filters.fecha_inicio" @change="handleDateChange('fecha_inicio')"
                    class="form-input w-full rounded-lg border-gray-300 focus:border-blue-500 
                      focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200">
            </div>

            <!-- Fecha Fin -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Fecha Fin</label>
                <input type="date" x-model="filters.fecha_fin" @change="handleDateChange('fecha_fin')"
                    class="form-input w-full rounded-lg border-gray-300 focus:border-blue-500 
                      focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200">
            </div>

            <!-- Filtro por Estado -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Estado</label>
                <select x-model="filters.estado" @change="loadCompras()"
                    class="form-select w-full rounded-lg border-gray-300 focus:border-blue-500 
                      focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="recibido">Recibido</option>
                    <option value="enviado_almacen">Enviado a Almacén</option>
                    <option value="anulado">Anulado</option>
                </select>
            </div>

            <!-- Buscador -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1 text-gray-700">Buscar</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 116.15 13.65z" />
                            </svg>
                        </span>
                        <input type="text" x-model="filters.q" @keyup.enter="buscar()"
                            placeholder="Serie, N° o Proveedor..."
                            class="form-input w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200">
                    </div>
                    <button @click="buscar()"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                        Buscar
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Mínimo 3 caracteres.</p>
            </div>
        </div>

        <!-- Tabla de Compras - Agregar columna Estado -->
        <div x-show="!loading && !error && compras.length > 0"
            class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Serie/Nro
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Fecha Emisión
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Proveedor
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(compra, index) in compras" :key="compra.idCompra">
                            <tr class="transition-all duration-150 hover:bg-gray-50"
                                :class="{ 'bg-gray-50/50': index % 2 === 0 }">
                                <!-- Serie/Nro -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <div class="h-9 w-9 flex-shrink-0 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span x-text="compra.serie + '-' + compra.nro"
                                                class="text-sm font-medium text-gray-900"></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Fecha Emisión -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <span x-text="formatDate(compra.fechaEmision)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"></span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span x-text="compra?.proveedor?.nombre ?? 'N/A'"
                                        class="text-sm text-gray-700 font-medium"></span>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <span x-text="getEstadoText(compra.estado)" 
                                              :class="getEstadoBadgeClass(compra.estado)"
                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer"
                                              @click="openEstadoModal(compra)">
                                        </span>
                                    </div>
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <span x-text="formatCurrency(compra.total)"
                                            class="text-sm font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full"></span>
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex space-x-2 justify-center">
                                        <!-- Botón Cambiar Estado -->
                                        <button @click="openEstadoModal(compra)" class="btn btn-secondary">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                </path>
                                            </svg>
                                            Estado
                                        </button>

                                        <!-- Botón Detalles de Compra -->
                                        <button @click="detallesCompra(compra.idCompra)" class="btn btn-info">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Detalles
                                        </button>

                                        <!-- Botón Imprimir Factura -->
                                        <button @click="imprimirFactura(compra.idCompra)" class="btn btn-warning">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Factura
                                        </button>

                                        <!-- Botón Imprimir Ticket -->
                                        <button @click="imprimirTicket(compra.idCompra)" class="btn btn-success">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Ticket
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para cambiar estado -->
        <div x-show="estadoModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Cambiar Estado de Compra
                    </h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Compra: <span x-text="selectedCompra ? selectedCompra.serie + '-' + selectedCompra.nro : ''" class="font-semibold"></span>
                        </p>
                        <p class="text-sm text-gray-500 mb-4">
                            Proveedor: <span x-text="selectedCompra?.proveedor?.nombre ?? 'N/A'" class="font-semibold"></span>
                        </p>
                        
                        <select x-model="nuevoEstado" class="form-select mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="pendiente">Pendiente</option>
                            <option value="recibido">Recibido</option>
                            <option value="enviado_almacen">Enviado a Almacén</option>
                            <option value="anulado">Anulado</option>
                        </select>
                    </div>
                    <div class="flex justify-center space-x-3 mt-4">
                        <button @click="closeEstadoModal" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Cancelar
                        </button>
                        <button @click="updateEstado" 
                                :disabled="updatingEstado"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition">
                            <span x-show="!updatingEstado">Actualizar</span>
                            <span x-show="updatingEstado" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Actualizando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('comprasList', () => ({
                compras: [],
                loading: false,
                error: null,
                estadoModalOpen: false,
                selectedCompra: null,
                nuevoEstado: 'pendiente',
                updatingEstado: false,
                
                filters: {
                    fecha_inicio: '',
                    fecha_fin: '',
                    q: '',
                    estado: '', // Nuevo filtro
                },
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 10,
                    total: 0,
                    from: 0,
                    to: 0,
                },

                init() {
                    this.loadCompras();
                },

                async loadCompras() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const clean = {};
                        if (this.filters.fecha_inicio) clean.fecha_inicio = this.filters.fecha_inicio;
                        if (this.filters.fecha_fin) clean.fecha_fin = this.filters.fecha_fin;
                        if (this.filters.q && this.filters.q.trim().length >= 3) clean.q = this.filters.q.trim();
                        if (this.filters.estado) clean.estado = this.filters.estado; // Nuevo filtro

                        const params = new URLSearchParams({
                            page: this.pagination.current_page,
                            per_page: this.pagination.per_page,
                            ...clean,
                        });

                        const response = await fetch(`/compras/data?${params}`);

                        if (!response.ok) {
                            throw new Error('Error al cargar los datos');
                        }

                        const data = await response.json();

                        if (data.error) {
                            throw new Error(data.error);
                        }

                        this.compras = data.data;
                        this.pagination = data.pagination;
                    } catch (error) {
                        console.error('Error loading compras:', error);
                        this.error = error.message || 'Error al cargar las compras';
                    } finally {
                        this.loading = false;
                    }
                },

                buscar() {
                    this.pagination.current_page = 1;
                    this.loadCompras();
                },

                // Métodos para los estados
                getEstadoText(estado) {
                    const estados = {
                        'pendiente': 'Pendiente',
                        'recibido': 'Recibido',
                        'enviado_almacen': 'Enviado Almacén',
                        'anulado': 'Anulado'
                    };
                    return estados[estado] || estado;
                },

                getEstadoBadgeClass(estado) {
                    const classes = {
                        'pendiente': 'bg-yellow-100 text-yellow-800',
                        'recibido': 'bg-green-100 text-green-800',
                        'enviado_almacen': 'bg-blue-100 text-blue-800',
                        'anulado': 'bg-red-100 text-red-800'
                    };
                    return classes[estado] || 'bg-gray-100 text-gray-800';
                },

                openEstadoModal(compra) {
                    this.selectedCompra = compra;
                    this.nuevoEstado = compra.estado;
                    this.estadoModalOpen = true;
                },

                closeEstadoModal() {
                    this.estadoModalOpen = false;
                    this.selectedCompra = null;
                    this.nuevoEstado = 'pendiente';
                    this.updatingEstado = false;
                },

                async updateEstado() {
                    if (!this.selectedCompra) return;

                    this.updatingEstado = true;

                    try {
                        const response = await fetch(`/compras/${this.selectedCompra.idCompra}/estado`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                estado: this.nuevoEstado
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Actualizar el estado en la lista local
                            const compraIndex = this.compras.findIndex(c => c.idCompra === this.selectedCompra.idCompra);
                            if (compraIndex !== -1) {
                                this.compras[compraIndex].estado = this.nuevoEstado;
                            }
                            
                            this.closeEstadoModal();
                            
                            // Mostrar mensaje de éxito
                            this.showNotification('Estado actualizado correctamente', 'success');
                        } else {
                            throw new Error(data.message || 'Error al actualizar el estado');
                        }
                    } catch (error) {
                        console.error('Error updating estado:', error);
                        this.showNotification(error.message, 'error');
                    } finally {
                        this.updatingEstado = false;
                    }
                },

                showNotification(message, type = 'info') {
                    // Implementar notificación (puedes usar Toast, SweetAlert, etc.)
                    alert(`${type.toUpperCase()}: ${message}`);
                },

                // ... resto de tus métodos existentes
                detallesCompra(idCompra) {
                    window.location.href = `/compras/${idCompra}/detalles`;
                },

                imprimirFactura(idCompra) {
                    window.location.href = `/compras/${idCompra}/factura`;
                },

                imprimirTicket(idCompra) {
                    window.location.href = `/compras/${idCompra}/ticket`;
                },

                handleDateChange(field) {
                    if (this.filters[field]) {
                        const date = new Date(this.filters[field]);
                        if (isNaN(date.getTime())) {
                            this.error = 'Fecha inválida';
                            this.filters[field] = '';
                            return;
                        }
                    }
                    this.error = null;
                    this.loadCompras();
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    return new Date(dateString).toLocaleDateString('es-ES');
                },

                formatCurrency(amount) {
                    if (!amount) return 'S/ 0.00';
                    return new Intl.NumberFormat('es-PE', {
                        style: 'currency',
                        currency: 'PEN',
                    }).format(amount);
                },

                previousPage() {
                    if (this.pagination.current_page > 1) {
                        this.pagination.current_page--;
                        this.loadCompras();
                    }
                },

                nextPage() {
                    if (this.pagination.current_page < this.pagination.last_page) {
                        this.pagination.current_page++;
                        this.loadCompras();
                    }
                },

                goToPage(page) {
                    this.pagination.current_page = page;
                    this.loadCompras();
                },

                getPages() {
                    const pages = [];
                    const maxPages = 5;
                    const startPage = Math.max(1, this.pagination.current_page - Math.floor(maxPages / 2));
                    const endPage = Math.min(this.pagination.last_page, startPage + maxPages - 1);

                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }

                    return pages;
                },
            }));
        });
    </script>
</x-layout.default>