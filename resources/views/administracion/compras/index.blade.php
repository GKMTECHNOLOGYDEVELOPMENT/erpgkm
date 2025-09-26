<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <div x-data="comprasList" x-init="init()">
        <!-- Filtros - Agregar filtro por estado -->
        <div
            class="bg-gradient-to-br from-white via-blue-50/40 to-indigo-50/30 p-6 rounded-2xl shadow-lg border border-white/60 backdrop-blur-sm space-y-6">

            <!-- Título de Filtros -->
            <div class="flex items-center gap-2 border-b pb-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                </svg>
                <h3 class="text-lg font-bold text-gray-800">Filtros de Búsqueda</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Fecha Inicio -->
                <div class="group">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Fecha Inicio
                    </label>
                    <input type="date" x-model="filters.fecha_inicio" @change="handleDateChange('fecha_inicio')"
                        class="form-input w-full rounded-xl border border-gray-200 focus:border-blue-500 
               focus:ring-2 focus:ring-blue-100 transition-all duration-300 hover:border-blue-400
               bg-white/70 backdrop-blur-sm shadow-sm text-gray-700 font-medium h-11">
                </div>

                <!-- Fecha Fin -->
                <div class="group">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Fecha Fin
                    </label>
                    <input type="date" x-model="filters.fecha_fin" @change="handleDateChange('fecha_fin')"
                        class="form-input w-full rounded-xl border border-gray-200 focus:border-blue-500 
               focus:ring-2 focus:ring-blue-100 transition-all duration-300 hover:border-blue-400
               bg-white/70 backdrop-blur-sm shadow-sm text-gray-700 font-medium h-11">
                </div>

                <!-- Estado -->
                <div class="group">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Estado
                    </label>
                    <select x-model="filters.estado" @change="loadCompras()"
                        class="form-select w-full rounded-xl border border-gray-200 focus:border-purple-500 
               focus:ring-2 focus:ring-purple-100 transition-all duration-300 hover:border-purple-400
               bg-white/70 backdrop-blur-sm shadow-sm text-gray-700 font-medium h-11 cursor-pointer">
                        <option value="">✨ Todos los estados</option>
                        <option value="pendiente">🟡 Pendiente</option>
                        <option value="recibido">🟢 Recibido</option>
                        <option value="enviado_almacen">🔵 Enviado a Almacén</option>
                        <option value="anulado">🔴 Anulado</option>
                    </select>
                </div>

                <!-- Botón Buscar -->
                <div class="flex items-end">
                    <button @click="buscar()" class="btn btn-info gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 116.15 13.65z" />
                        </svg>
                        Buscar
                    </button>
                </div>
            </div>

            <!-- Buscador Avanzado -->
            <div class="group">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 116.15 13.65z" />
                    </svg>
                    Buscar por texto
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-hover:text-green-500 transition-colors animate-pulse"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 116.15 13.65z" />
                        </svg>
                    </span>
                    <input type="text" x-model="filters.q" @keyup.enter="buscar()"
                        placeholder="Serie, N° o Proveedor... ✨"
                        class="form-input w-full pl-12 pr-4 rounded-xl border border-gray-200 focus:border-green-500 
               focus:ring-2 focus:ring-green-100 transition-all duration-300 hover:border-green-400
               bg-white/70 backdrop-blur-sm shadow-sm text-gray-700 font-medium h-11 placeholder-gray-400">
                </div>
                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ingresa al menos 3 caracteres
                </p>
            </div>
        </div>

        <!-- Botón Nueva Compra - CON ESPACIOS -->
        <div class="flex justify-end mt-6">
            <a href="{{ route('compras.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 
               btn btn-primary space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nueva Compra</span>
            </a>
        </div>

        <!-- Tabla de Compras - Agregar columna Estado -->
        <div x-show="!loading && !error && compras.length > 0"
            class="panel rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-100 mt-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-bold text-black uppercase tracking-wider">
                                Serie/Nro
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-black uppercase tracking-wider">
                                Fecha Emisión
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-black uppercase tracking-wider">
                                Proveedor
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-black uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-black uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-black uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(compra, index) in compras" :key="compra.idCompra">
                            <tr class="transition-all duration-150 hover:bg-gray-50"
                                :class="{ 'bg-gray-50/50': index % 2 === 0 }">
                                <!-- Serie/Nro -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <!-- Icono con ancho fijo -->
                                        <div
                                            class="h-9 w-9 flex-shrink-0 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>

                                        <!-- Texto alineado -->
                                        <div class="text-left">
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
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
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
                                        <button @click="detallesCompra(compra.idCompra)" class="btn btn-warning">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Detalles
                                        </button>

                                        <!-- Botón Imprimir Factura -->
                                        {{-- <button @click="imprimirFactura(compra.idCompra)" class="btn btn-warning">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Factura
                                        </button> --}}

                                        <!-- Botón Imprimir Ticket -->
                                        {{-- <button @click="imprimirTicket(compra.idCompra)" class="btn btn-success">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Ticket
                                        </button> --}}
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para cambiar estado -->
        <!-- Modal Cambiar Estado -->
        <div x-show="estadoModalOpen" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="estadoModalOpen && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="closeEstadoModal">
                <div
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__fadeIn">

                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] items-center justify-between px-5 py-3 border-b">
                        <h5 class="font-bold text-lg text-gray-800">Cambiar Estado de Compra</h5>
                        <button type="button" class="text-gray-500 hover:text-gray-700" @click="closeEstadoModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5 space-y-4">
                        <!-- Compra -->
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                            Compra:
                            <span x-text="selectedCompra ? selectedCompra.serie + '-' + selectedCompra.nro : ''"
                                class="font-semibold text-gray-800"></span>
                        </p>

                        <!-- Proveedor -->
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-success" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 10-8 0v4m-4 4h16v6H4v-6z" />
                            </svg>
                            Proveedor:
                            <span x-text="selectedCompra?.proveedor?.nombre ?? 'N/A'"
                                class="font-semibold text-gray-800"></span>
                        </p>

                        <!-- Estado -->
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-secondary" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m2 0a2 2 0 100-4 2 2 0 000 4zm-8 0a2 2 0 100-4 2 2 0 000 4zM4 6h16M4 6v12m16-12v12" />
                                </svg>
                                Estado de la Compra
                            </label>
                            <select x-model="nuevoEstado"
                                class="form-select mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="pendiente">Pendiente</option>
                                <option value="recibido">Recibido</option>
                                <option value="enviado_almacen">Enviado a Almacén</option>
                                <option value="anulado">Anulado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end items-center px-5 py-3 border-t space-x-3">
                        <button type="button" class="btn btn-outline-danger" @click="closeEstadoModal">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" :disabled="updatingEstado"
                            @click="updateEstado">
                            <span x-show="!updatingEstado">Actualizar</span>
                            <span x-show="updatingEstado" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Actualizando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
                        if (this.filters.fecha_inicio) clean.fecha_inicio = this.filters
                            .fecha_inicio;
                        if (this.filters.fecha_fin) clean.fecha_fin = this.filters.fecha_fin;
                        if (this.filters.q && this.filters.q.trim().length >= 3) clean.q = this
                            .filters.q.trim();
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
                        const response = await fetch(
                            `/compras/${this.selectedCompra.idCompra}/estado`, {
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
                            const compraIndex = this.compras.findIndex(c => c.idCompra === this
                                .selectedCompra.idCompra);
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
                    switch (type) {
                        case 'success':
                            toastr.success(message, 'Éxito');
                            break;
                        case 'error':
                            toastr.error(message, 'Error');
                            break;
                        case 'warning':
                            toastr.warning(message, 'Advertencia');
                            break;
                        default:
                            toastr.info(message, 'Información');
                            break;
                    }
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
                    const startPage = Math.max(1, this.pagination.current_page - Math.floor(maxPages /
                        2));
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
