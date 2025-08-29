<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="kardexApp()" x-cloak class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">DETALLES DE KARDEX</h1>
                <p class="text-gray-600 max-w-3xl">
                    En el módulo KARDEX puede ver los movimientos y costos de entradas - salidas de productos. 
                    Además, puede ver información detallada de los movimientos específicos de un producto por cada mes.
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <button @click="printReport()" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-print mr-2"></i> Imprimir Reporte
                </button>
                <button @click="exportToExcel()" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Exportar
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 card-hover" x-show="showFilters">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">FILTRAR KARDEX</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date" x-model="filters.startDate" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date" x-model="filters.endDate" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Movimiento</label>
                    <select x-model="filters.type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        <option value="entrada">Entradas</option>
                        <option value="salida">Salidas</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button @click="applyFilters()" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-filter mr-2"></i> Aplicar Filtros
                </button>
                <button @click="clearFilters()" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg">
                    Limpiar
                </button>
            </div>
        </div>

             <!-- Stats Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 card-hover">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">INVENTARIO ACTUAL</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="stat-card bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Inventario inicial</p>
                    <p class="text-2xl font-bold text-gray-800">5</p>
                </div>
                <div class="stat-card bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Inventario actual</p>
                    <p class="text-2xl font-bold text-gray-800">5</p>
                </div>
                <div class="stat-card bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Costo inventario actual</p>
                    <p class="text-2xl font-bold text-gray-800">$50.00 USD</p>
                </div>
            </div>
        </div>


        <!-- Product Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 card-hover">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">ENTRADAS</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-boxes text-blue-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Entrada de unidades</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="totalEntradas.unidades"></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <i class="fas fa-dollar-sign text-green-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Costo de unidades</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="`$${totalEntradas.costo} USD`"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-0">
                    <h2 class="text-xl font-semibold text-gray-800">SALIDAS</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <div class="bg-red-100 p-3 rounded-full mr-4">
                                <i class="fas fa-external-link-alt text-red-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Salida de unidades</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="totalSalidas.unidades"></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full mr-4">
                                <i class="fas fa-money-bill-wave text-purple-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Costo de unidades</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="`$${totalSalidas.costo} USD`"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     
        <!-- Kardex Details Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 card-hover">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">DETALLES DE KARDEX</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500" x-text="`Mostrando ${filteredMovimientos.length} registros`"></span>
                    <button @click="showFilters = !showFilters" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FECHA</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TIPO</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DESCRIPCIÓN</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UNIDADES</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PRECIO</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TOTAL</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(movimiento, index) in paginatedMovimientos" :key="movimiento.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="index + 1 + ((currentPage - 1) * pageSize)"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(movimiento.fecha)"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-green-100 text-green-800': movimiento.tipo === 'entrada',
                                        'bg-red-100 text-red-800': movimiento.tipo === 'salida'
                                    }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" x-text="movimiento.tipo.toUpperCase()"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500" x-text="movimiento.descripcion"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="movimiento.unidades"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="`$${movimiento.precio} USD`"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="`$${movimiento.total} USD`"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="viewDetails(movimiento)" class="text-blue-500 hover:text-blue-700 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="editMovimiento(movimiento)" class="text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredMovimientos.length === 0">
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron movimientos con los filtros aplicados
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between" x-show="filteredMovimientos.length > 0">
                <div class="text-sm text-gray-700">
                    Mostrando <span x-text="Math.min(((currentPage - 1) * pageSize) + 1, filteredMovimientos.length)"></span> a 
                    <span x-text="Math.min(currentPage * pageSize, filteredMovimientos.length)"></span> de 
                    <span x-text="filteredMovimientos.length"></span> resultados
                </div>
                <div class="flex space-x-2">
                    <button @click="currentPage--" :disabled="currentPage === 1" 
                        :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-gray-200': currentPage > 1}"
                        class="px-3 py-1 rounded border border-gray-300">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <template x-for="page in totalPages" :key="page">
                        <button @click="currentPage = page" 
                            :class="{'bg-blue-500 text-white': currentPage === page, 'text-gray-700 hover:bg-gray-200': currentPage !== page}"
                            class="px-3 py-1 rounded border border-gray-300" x-text="page">
                        </button>
                    </template>
                    <button @click="currentPage++" :disabled="currentPage === totalPages" 
                        :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages, 'hover:bg-gray-200': currentPage < totalPages}"
                        class="px-3 py-1 rounded border border-gray-300">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

      

        <!-- Back Button -->
        <div class="flex justify-center mt-8">
            <a href="#" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-6 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> REGRESAR ATRÁS
            </a>
        </div>

        <!-- Modal Detalles -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">DETALLES DEL MOVIMIENTO</h3>
                    <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="px-6 py-4" x-show="selectedMovimiento">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Fecha</p>
                            <p class="font-medium" x-text="formatDate(selectedMovimiento.fecha)"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tipo</p>
                            <p class="font-medium" x-text="selectedMovimiento.tipo.toUpperCase()"></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Descripción</p>
                            <p class="font-medium" x-text="selectedMovimiento.descripcion"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Unidades</p>
                            <p class="font-medium" x-text="selectedMovimiento.unidades"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Precio Unitario</p>
                            <p class="font-medium" x-text="`$${selectedMovimiento.precio} USD`"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="font-medium" x-text="`$${selectedMovimiento.total} USD`"></p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button @click="showModal = false" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function kardexApp() {
            return {
                showFilters: false,
                showModal: false,
                searchTerm: '',
                sortBy: 'fecha',
                currentPage: 1,
                pageSize: 5,
                selectedMovimiento: null,
                filters: {
                    startDate: '',
                    endDate: '',
                    type: ''
                },
                movimientos: [
                    {
                        id: 1,
                        fecha: '2025-08-29',
                        tipo: 'entrada',
                        descripcion: 'Compra de producto (Mediante registro)',
                        unidades: 5,
                        precio: 10.00,
                        total: 50.00
                    },
                    {
                        id: 2,
                        fecha: '2025-08-15',
                        tipo: 'salida',
                        descripcion: 'Venta a cliente (Factura #001-123)',
                        unidades: 2,
                        precio: 15.00,
                        total: 30.00
                    },
                    {
                        id: 3,
                        fecha: '2025-08-10',
                        tipo: 'entrada',
                        descripcion: 'Ajuste de inventario positivo',
                        unidades: 3,
                        precio: 12.00,
                        total: 36.00
                    },
                    {
                        id: 4,
                        fecha: '2025-08-05',
                        tipo: 'salida',
                        descripcion: 'Traslado a otra sucursal',
                        unidades: 1,
                        precio: 10.00,
                        total: 10.00
                    },
                    {
                        id: 5,
                        fecha: '2025-07-28',
                        tipo: 'entrada',
                        descripcion: 'Compra de producto (Proveedor ABC)',
                        unidades: 10,
                        precio: 9.50,
                        total: 95.00
                    }
                ],
                get filteredMovimientos() {
                    let result = [...this.movimientos];
                    
                    // Aplicar filtro de búsqueda
                    if (this.searchTerm) {
                        const term = this.searchTerm.toLowerCase();
                        result = result.filter(m => 
                            m.descripcion.toLowerCase().includes(term) || 
                            m.tipo.toLowerCase().includes(term)
                        );
                    }
                    
                    // Aplicar filtro por tipo
                    if (this.filters.type) {
                        result = result.filter(m => m.tipo === this.filters.type);
                    }
                    
                    // Aplicar filtro por fecha
                    if (this.filters.startDate) {
                        result = result.filter(m => m.fecha >= this.filters.startDate);
                    }
                    
                    if (this.filters.endDate) {
                        result = result.filter(m => m.fecha <= this.filters.endDate);
                    }
                    
                    // Aplicar ordenamiento
                    switch(this.sortBy) {
                        case 'fecha':
                            result.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
                            break;
                        case 'fecha_asc':
                            result.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
                            break;
                        case 'unidades':
                            result.sort((a, b) => b.unidades - a.unidades);
                            break;
                        case 'unidades_asc':
                            result.sort((a, b) => a.unidades - b.unidades);
                            break;
                    }
                    
                    return result;
                },
                get paginatedMovimientos() {
                    const start = (this.currentPage - 1) * this.pageSize;
                    return this.filteredMovimientos.slice(start, start + this.pageSize);
                },
                get totalPages() {
                    return Math.ceil(this.filteredMovimientos.length / this.pageSize);
                },
                get totalEntradas() {
                    const entradas = this.movimientos.filter(m => m.tipo === 'entrada');
                    const unidades = entradas.reduce((sum, m) => sum + m.unidades, 0);
                    const costo = entradas.reduce((sum, m) => sum + m.total, 0);
                    return { unidades, costo: costo.toFixed(2) };
                },
                get totalSalidas() {
                    const salidas = this.movimientos.filter(m => m.tipo === 'salida');
                    const unidades = salidas.reduce((sum, m) => sum + m.unidades, 0);
                    const costo = salidas.reduce((sum, m) => sum + m.total, 0);
                    return { unidades, costo: costo.toFixed(2) };
                },
                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES');
                },
                applyFilters() {
                    this.currentPage = 1;
                    this.showFilters = false;
                },
                clearFilters() {
                    this.filters = {
                        startDate: '',
                        endDate: '',
                        type: ''
                    };
                    this.searchTerm = '';
                    this.sortBy = 'fecha';
                    this.currentPage = 1;
                },
                applySearch() {
                    this.currentPage = 1;
                },
                applySort() {
                    this.currentPage = 1;
                },
                viewDetails(movimiento) {
                    this.selectedMovimiento = movimiento;
                    this.showModal = true;
                },
                editMovimiento(movimiento) {
                    alert(`Editar movimiento: ${movimiento.descripcion}`);
                    // Aquí iría la lógica para editar el movimiento
                },
                printReport() {
                    window.print();
                },
                exportToExcel() {
                    alert('Funcionalidad de exportación a Excel');
                    // Aquí iría la lógica para exportar a Excel
                }
            }
        }
    </script>
</x-layout.default>