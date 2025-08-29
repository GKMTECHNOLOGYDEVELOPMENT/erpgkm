<x-layout.default>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">DETALLES DE KARDEX</h1>
                    <p class="text-gray-600 max-w-3xl">
                        En el módulo KARDEX puede ver los movimientos y costos de entradas - salidas de productos. 
                        Además, puede ver información detallada de los movimientos específicos de un producto por cada mes.
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-print mr-2"></i> Imprimir Reporte
                    </a>
                </div>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8 card-hover">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">EXTRAVAS</h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-boxes text-blue-500 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Entrada de unidades</p>
                                    <p class="text-2xl font-bold text-gray-800">5</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-green-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-dollar-sign text-green-500 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Costo de unidades</p>
                                    <p class="text-2xl font-bold text-gray-800">$50.00 USD</p>
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
                                    <p class="text-2xl font-bold text-gray-800">0</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-money-bill-wave text-purple-500 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Costo de unidades</p>
                                    <p class="text-2xl font-bold text-gray-800">$0.00 USD</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kardex Details Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 card-hover">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">DETALLES DE KARDEX</h2>
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
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">29-08-2025</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Entrada</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">Compra de producto (Mediante registro)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$10.00 USD</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$50.00 USD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Search and Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Search Kardex -->
                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">BUSCAR KARDEX</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">KARDEX POR PRODUCTO</label>
                        <div class="flex">
                            <input type="text" class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar producto...">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-lg">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <button class="w-full gradient-bg text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filtrar Resultados
                    </button>
                </div>

                <!-- Stats -->
                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">INVENTARIO ACTUAL</h2>
                    <div class="space-y-4">
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
            </div>

            <!-- Back Button -->
            <div class="flex justify-center mt-8">
                <a href="#" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-6 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> REGRESAR ATRÁS
                </a>
            </div>
        </div>


          <script>
        // Simple JavaScript for interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to stats cards on page load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
            
            // Print functionality
            const printButton = document.querySelector('a[href="#"]');
            printButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.print();
            });
        });
    </script>
    </x-layout.default>

  