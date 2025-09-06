<x-layout.default>
 <div x-data="devolucionesList" x-init="init()" class="container mx-auto px-4 py-8">
        <!-- Filtros -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
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

            <!-- Buscador -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1 text-gray-700">Buscar</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                            </svg>
                        </span>
                        <input type="text" x-model="filters.q" @keyup.enter="buscar()"
                            placeholder="Producto, Motivo o Usuario..."
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

        <!-- Mensaje de Error -->
        <div x-show="error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700" x-text="error"></p>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="text-center py-12 bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="inline-flex items-center justify-center">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-base font-medium text-gray-700">Cargando devoluciones...</span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Devoluciones de Compra</h1>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-undo mr-2"></i>
                Nueva Devolución
            </a>
        </div>

        <!-- Tabla de Devoluciones Mejorada -->
        <div x-show="!loading && !error && devoluciones.length > 0"
            class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Fecha Devolución
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Cantidad
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Precio Unitario
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total Devolución
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(devolucion, index) in devoluciones" :key="devolucion.idDevolucionCompra">
                            <tr class="transition-all duration-150 hover:bg-gray-50"
                                :class="{ 'bg-gray-50/50': index % 2 === 0 }">
                                <!-- Fecha Devolución -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <span x-text="formatDateTime(devolucion.fecha_devolucion)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"></span>
                                    </div>
                                </td>

                                <!-- Producto -->
                                <td class="px-6 py-4 text-center">
                                    <span x-text="devolucion.producto?.nombre ?? 'N/A'"
                                        class="text-sm text-gray-700 font-medium"></span>
                                </td>

                                <!-- Cantidad -->
                                <td class="px-6 py-4 text-center">
                                    <span x-text="devolucion.cantidad"
                                        class="text-sm font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full"></span>
                                </td>

                                <!-- Precio Unitario -->
                                <td class="px-6 py-4 text-center">
                                    <span x-text="formatCurrency(devolucion.precio_unitario)"
                                        class="text-sm font-semibold text-purple-700 bg-purple-100 px-2.5 py-1 rounded-full"></span>
                                </td>

                                <!-- Total Devolución -->
                                <td class="px-6 py-4 text-center">
                                    <span x-text="formatCurrency(devolucion.total_devolucion)"
                                        class="text-sm font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full"></span>
                                </td>

                                <!-- Usuario -->
                                <td class="px-6 py-4 text-center">
                                    <span x-text="devolucion.usuario?.Nombre ?? 'N/A'"
                                        class="text-sm text-gray-700 font-medium"></span>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex space-x-2 justify-center">
                                        <!-- Botón Ver Detalles -->
                                        <button @click="verDetalles(devolucion.idDevolucionCompra)" class="btn btn-info">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detalles
                                        </button>

                                        <!-- Botón Imprimir Reporte -->
                                        <button @click="imprimirReporte(devolucion.idDevolucionCompra)" class="btn btn-warning">
                                            <i class="fas fa-print mr-1"></i>
                                            Reporte
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
        <div x-show="!loading && !error && devoluciones.length === 0"
            class="text-center py-16 bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                <i class="fas fa-undo text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No se encontraron devoluciones</h3>
            <p class="text-gray-500">Intenta ajustar los filtros para ver más resultados.</p>
        </div>

        <!-- Paginación Mejorada -->
        <div x-show="!loading && !error && devoluciones.length > 0"
            class="bg-white rounded-lg shadow-sm px-6 py-4 flex items-center justify-between border border-gray-100">
            <div class="text-sm text-gray-700">
                Mostrando <span class="font-medium" x-text="pagination.from"></span> a
                <span class="font-medium" x-text="pagination.to"></span> de
                <span class="font-medium" x-text="pagination.total"></span> resultados
            </div>

            <div class="flex items-center space-x-2">
                <button @click="previousPage()" :disabled="pagination.current_page === 1"
                    :class="{
                        'opacity-50 cursor-not-allowed': pagination.current_page === 1,
                        'hover:bg-gray-50': pagination.current_page !== 1
                    }"
                    class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 transition-colors duration-200">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Anterior
                </button>

                <div class="hidden sm:flex sm:space-x-1">
                    <template x-for="page in getPages()" :key="page">
                        <button @click="goToPage(page)"
                            :class="{
                                'bg-blue-600 border-blue-600 text-white': page === pagination.current_page,
                                'border-gray-300 text-gray-700 hover:bg-gray-50': page !== pagination.current_page
                            }"
                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md transition-colors duration-200"
                            x-text="page"></button>
                    </template>
                </div>

                <button @click="nextPage()" :disabled="pagination.current_page === pagination.last_page"
                    :class="{
                        'opacity-50 cursor-not-allowed': pagination.current_page === pagination.last_page,
                        'hover:bg-gray-50': pagination.current_page !== pagination.last_page
                    }"
                    class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 transition-colors duration-200">
                    Siguiente
                    <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</x-layout.default>
