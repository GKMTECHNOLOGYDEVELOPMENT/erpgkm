<x-layout.default>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-6">
        <div class="max-w-7x2 mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Nueva Venta</h1>
                        <p class="text-gray-600 mt-2">Completa la información para registrar una nueva venta</p>
                    </div>
                    
                    <!-- Progress Steps -->
                    <div class="hidden lg:flex items-center space-x-4 bg-white rounded-xl p-2 shadow-sm border">
                        <div class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg">
                            <div class="w-6 h-6 bg-white text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-2">1</div>
                            <span class="text-sm font-medium">Información</span>
                        </div>
                        <div class="flex items-center px-4 py-2 text-gray-500">
                            <div class="w-6 h-6 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold mr-2">2</div>
                            <span class="text-sm font-medium">Productos</span>
                        </div>
                        <div class="flex items-center px-4 py-2 text-gray-500">
                            <div class="w-6 h-6 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold mr-2">3</div>
                            <span class="text-sm font-medium">Pago</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Main Form - 3/4 width -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                        <!-- Form Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Información de la Venta</h2>
                            <p class="text-gray-600 mt-1">Datos básicos del cliente y la transacción</p>
                        </div>

                        <form id="ventaForm" class="p-6">
                            <!-- Client Information Section -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                    Información del Cliente
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Client Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de Cliente</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="relative flex cursor-pointer">
                                                <input type="radio" name="tipo_cliente" value="existente" class="sr-only" checked>
                                                <div class="flex items-center justify-center w-full px-4 py-3 border-2 border-gray-300 rounded-lg hover:border-blue-500 transition-all radio-checked:border-blue-500 radio-checked:bg-blue-50 radio-checked:shadow-sm">
                                                    <span class="text-sm font-medium text-gray-700 radio-checked:text-blue-700">Existente</span>
                                                </div>
                                            </label>
                                            <label class="relative flex cursor-pointer">
                                                <input type="radio" name="tipo_cliente" value="nuevo" class="sr-only">
                                                <div class="flex items-center justify-center w-full px-4 py-3 border-2 border-gray-300 rounded-lg hover:border-blue-500 transition-all radio-checked:border-blue-500 radio-checked:bg-blue-50 radio-checked:shadow-sm">
                                                    <span class="text-sm font-medium text-gray-700 radio-checked:text-blue-700">Nuevo</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Client Select -->
                                    <div>
                                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-3">Seleccionar Cliente</label>
                                        <div class="relative">
                                            <select id="cliente_id" name="cliente_id" class="w-full pl-4 pr-10 py-3.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white hover:border-gray-400 transition-colors">
                                                <option value="">Selecciona un cliente</option>
                                                <option value="1">Juan Pérez - juan@email.com</option>
                                                <option value="2">María García - maria@email.com</option>
                                                <option value="3">Carlos López - carlos@email.com</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- New Client Fields -->
                                <div id="nuevoClienteFields" class="hidden mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border">
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                                        <input type="text" id="nombre" name="nombre" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    </div>
                                    <div>
                                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                        <input type="tel" id="telefono" name="telefono" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    </div>
                                    <div>
                                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                        <input type="text" id="direccion" name="direccion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    </div>
                                </div>
                            </div>

                            <!-- Products Section -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                                    Productos de la Venta
                                </h3>

                                <!-- Product Search -->
                                <div class="bg-blue-50 rounded-lg p-4 mb-4 border border-blue-100">
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <div class="flex-1">
                                            <div class="relative">
                                                <input type="text" id="productSearch" placeholder="Buscar productos..." 
                                                       class="w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <button type="button" onclick="agregarProducto()" 
                                                class="px-6 py-3.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center font-medium shadow-sm">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Agregar
                                        </button>
                                    </div>
                                </div>

                                <!-- Products Table -->
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 border-b border-gray-200">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">PRODUCTO</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">PRECIO</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CANTIDAD</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SUBTOTAL</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="productosTable" class="bg-white divide-y divide-gray-200">
                                            <tr id="noProducts" class="text-center">
                                                <td colspan="5" class="px-4 py-12 text-gray-500">
                                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-400">No hay productos agregados</p>
                                                    <p class="text-sm text-gray-400 mt-1">Busca y agrega productos a la venta</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</div>
                                    Información de Pago
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="metodo_pago" class="block text-sm font-medium text-gray-700 mb-3">Método de Pago</label>
                                        <div class="relative">
                                            <select id="metodo_pago" name="metodo_pago" class="w-full pl-4 pr-10 py-3.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white hover:border-gray-400 transition-colors">
                                                <option value="efectivo">Efectivo</option>
                                                <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                                <option value="transferencia">Transferencia</option>
                                                <option value="yape">Yape / Plin</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-3">Estado de la Venta</label>
                                        <div class="relative">
                                            <select id="estado" name="estado" class="w-full pl-4 pr-10 py-3.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white hover:border-gray-400 transition-colors">
                                                <option value="pendiente">Pendiente</option>
                                                <option value="completada">Completada</option>
                                                <option value="cancelada">Cancelada</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="notas" class="block text-sm font-medium text-gray-700 mb-3">Notas Adicionales</label>
                                    <textarea id="notas" name="notas" rows="3" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" 
                                              placeholder="Observaciones o comentarios sobre la venta..."></textarea>
                                </div>
                            </div>
                        </form>

                        <!-- Form Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <button type="button" class="w-full sm:w-auto px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>
                            
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <button type="button" class="w-full sm:w-auto px-8 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                                    Guardar Borrador
                                </button>
                                <button type="submit" form="ventaForm" 
                                        class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center font-medium shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Crear Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - 1/4 width -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de Venta</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900" id="subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">IGV (18%)</span>
                                <span class="font-semibold text-gray-900" id="igv">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Descuento</span>
                                <span class="font-semibold text-red-600" id="descuento">$0.00</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-blue-600" id="total">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                        
                        <div class="space-y-3">
                            <button class="w-full px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg hover:bg-green-100 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Venta Rápida
                            </button>
                            <button class="w-full px-4 py-3 bg-purple-50 border border-purple-200 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Agregar Cliente
                            </button>
                            <button class="w-full px-4 py-3 bg-orange-50 border border-orange-200 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Nuevo Producto
                            </button>
                        </div>
                    </div>

                    <!-- Recent Products -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Productos Recientes</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Laptop HP</p>
                                        <p class="text-xs text-gray-500">$1,200.00</p>
                                    </div>
                                </div>
                                <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle between existing and new client
        document.querySelectorAll('input[name="tipo_cliente"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const nuevoClienteFields = document.getElementById('nuevoClienteFields');
                const clienteSelect = document.getElementById('cliente_id');
                
                if (this.value === 'nuevo') {
                    nuevoClienteFields.classList.remove('hidden');
                    clienteSelect.disabled = true;
                    clienteSelect.classList.add('bg-gray-100', 'text-gray-500');
                } else {
                    nuevoClienteFields.classList.add('hidden');
                    clienteSelect.disabled = false;
                    clienteSelect.classList.remove('bg-gray-100', 'text-gray-500');
                }
            });
        });

        // Product management functions
        function agregarProducto() {
            const searchInput = document.getElementById('productSearch');
            const productName = searchInput.value.trim();
            
            if (productName) {
                const noProducts = document.getElementById('noProducts');
                if (noProducts) noProducts.remove();
                
                const tableBody = document.getElementById('productosTable');
                const newRow = document.createElement('tr');
                newRow.className = 'hover:bg-gray-50 transition-colors';
                newRow.innerHTML = `
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">${productName}</div>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" step="0.01" class="w-24 px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 precio" value="0.00" onchange="calcularTotales()">
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" class="w-20 px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cantidad" value="1" min="1" onchange="calcularTotales()">
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-semibold text-gray-900 subtotal">$0.00</span>
                    </td>
                    <td class="px-4 py-3">
                        <button onclick="eliminarProducto(this)" class="p-2 text-red-600 hover:bg-red-100 rounded transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </td>
                `;
                tableBody.appendChild(newRow);
                searchInput.value = '';
                calcularTotales();
            }
        }

        function eliminarProducto(button) {
            const row = button.closest('tr');
            row.remove();
            
            const tableBody = document.getElementById('productosTable');
            if (tableBody.children.length === 0) {
                tableBody.innerHTML = `
                    <tr id="noProducts" class="text-center">
                        <td colspan="5" class="px-4 py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-lg font-medium text-gray-400">No hay productos agregados</p>
                            <p class="text-sm text-gray-400 mt-1">Busca y agrega productos a la venta</p>
                        </td>
                    </tr>
                `;
            }
            calcularTotales();
        }

        function calcularTotales() {
            let subtotal = 0;
            
            document.querySelectorAll('#productosTable tr:not(#noProducts)').forEach(row => {
                const precio = parseFloat(row.querySelector('.precio').value) || 0;
                const cantidad = parseInt(row.querySelector('.cantidad').value) || 0;
                const productSubtotal = precio * cantidad;
                
                row.querySelector('.subtotal').textContent = `$${productSubtotal.toFixed(2)}`;
                subtotal += productSubtotal;
            });
            
            const igv = subtotal * 0.18;
            const total = subtotal + igv;
            
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('igv').textContent = `$${igv.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        // Form submission
        document.getElementById('ventaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add form submission logic here
            alert('Venta creada exitosamente!');
        });
    </script>

    <style>
        /* Custom radio button styles */
        input[type="radio"]:checked + div {
            border-color: #2563eb;
            background-color: #dbeafe;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        input[type="radio"]:checked + div span {
            color: #1e40af;
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.2s ease-in-out;
        }
        
        /* Custom select styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</x-layout.default>