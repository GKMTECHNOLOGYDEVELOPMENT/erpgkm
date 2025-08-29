<x-layout.default>
    <div x-data="comprasList" x-init="init()">
        <!-- Filtros -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Inicio</label>
                <input type="date" x-model="filters.fecha_inicio" 
                       @change="handleDateChange('fecha_inicio')" class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Fin</label>
                <input type="date" x-model="filters.fecha_fin" 
                       @change="handleDateChange('fecha_fin')" class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Items por página</label>
                <select x-model="pagination.per_page" @change="loadCompras()" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Mensaje de Error -->
        <div x-show="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <p x-text="error"></p>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-2 text-gray-600">Cargando compras...</p>
        </div>

        <!-- Tabla de Compras -->
        <div x-show="!loading && !error && compras.length > 0" class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Serie/Nro
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Emisión
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID Proveedor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="compra in compras" :key="compra.idCompra">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-text="compra.serie + '-' + compra.nro" class="text-sm font-medium text-gray-900"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-text="formatDate(compra.fechaEmision)" class="text-sm text-gray-900"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-text="compra.idSujeto || 'N/A'" class="text-sm text-gray-900"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-text="formatCurrency(compra.total)" class="text-sm font-medium text-green-600"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <!-- Botón Detalles de Compra -->
                                        <button @click="detallesCompra(compra.idCompra)" 
                                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Detalles
                                        </button>
                                        
                                        <!-- Botón Imprimir Factura -->
                                        <button @click="imprimirFactura(compra.idCompra)" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Factura
                                        </button>
                                        
                                        <!-- Botón Imprimir Ticket -->
                                        <button @click="imprimirTicket(compra.idCompra)" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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

        <!-- Empty State -->
        <div x-show="!loading && !error && compras.length === 0" class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="text-gray-600">No se encontraron compras</p>
        </div>

        <!-- Paginación -->
        <div x-show="!loading && !error && compras.length > 0" class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Mostrando <span x-text="pagination.from"></span> a 
                <span x-text="pagination.to"></span> de 
                <span x-text="pagination.total"></span> resultados
            </div>
            
            <div class="flex space-x-2">
                <button 
                    @click="previousPage()" 
                    :disabled="pagination.current_page === 1"
                    :class="{'opacity-50 cursor-not-allowed': pagination.current_page === 1}"
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Anterior
                </button>
                
                <div class="flex space-x-1">
                    <template x-for="page in getPages()" :key="page">
                        <button 
                            @click="goToPage(page)"
                            :class="{
                                'bg-blue-600 text-white': page === pagination.current_page,
                                'border-gray-300 text-gray-700 hover:bg-gray-50': page !== pagination.current_page
                            }"
                            class="px-3 py-2 border rounded-md text-sm font-medium"
                            x-text="page"
                        ></button>
                    </template>
                </div>
                
                <button 
                    @click="nextPage()" 
                    :disabled="pagination.current_page === pagination.last_page"
                    :class="{'opacity-50 cursor-not-allowed': pagination.current_page === pagination.last_page}"
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Siguiente
                </button>
            </div>
        </div>
    </div>

  

    <style>
        [x-cloak] { display: none !important; }
        
        /* Estilos para los botones */
        .btn-detalles { background-color: #7c3aed; }
        .btn-detalles:hover { background-color: #6d28d9; }
        
        .btn-factura { background-color: #2563eb; }
        .btn-factura:hover { background-color: #1d4ed8; }
        
        .btn-ticket { background-color: #16a34a; }
        .btn-ticket:hover { background-color: #15803d; }
    </style>

     <script src="{{ asset('assets/js/compras/list.js') }}" defer></script>

</x-layout.default>