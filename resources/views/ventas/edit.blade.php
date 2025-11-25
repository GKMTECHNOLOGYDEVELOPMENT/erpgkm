<x-layout.default>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <a href="{{ route('ventas.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver a ventas
                            </a>
                            <span class="text-gray-400">/</span>
                            <span class="text-gray-600">Editando Venta #V-001</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">Editar Venta</h1>
                        <p class="text-gray-600 mt-2">Modifica la información de la venta existente</p>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium border border-yellow-200">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                Estado: Pendiente
                            </div>
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
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Main Form - 3/4 width -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                        <!-- Form Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Editando Venta #V-001</h2>
                            <p class="text-gray-600 mt-1">Modifica los datos básicos del cliente y la transacción</p>
                        </div>

                        <form id="ventaEditForm" class="p-6">
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
                                                <div class="flex items-center justify-center w-full px-4 py-3 border-2 border-blue-500 rounded-lg radio-checked:border-blue-500 radio-checked:bg-blue-50 radio-checked:shadow-sm">
                                                    <span class="text-sm font-medium text-blue-700">Existente</span>
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
                                                <option value="1" selected>Juan Pérez - juan@email.com</option>
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

                                <!-- Client Info Display -->
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">Cliente:</span>
                                            <p class="text-gray-900">Juan Pérez</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Email:</span>
                                            <p class="text-gray-900">juan@email.com</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Teléfono:</span>
                                            <p class="text-gray-900">+51 987 654 321</p>
                                        </div>
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
                                                <input type="text" id="productSearch" placeholder="Buscar productos para agregar..." 
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
                                            <!-- Preloaded Products -->
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-900">Laptop HP Pavilion</div>
                                                    <div class="text-xs text-gray-500">SKU: LP-HP-001</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" step="0.01" value="1200.00" 
                                                           class="w-24 px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 precio" 
                                                           onchange="calcularTotales()">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" value="1" min="1" 
                                                           class="w-20 px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cantidad" 
                                                           onchange="calcularTotales()">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="font-semibold text-gray-900 subtotal">$1,200.00</span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <button onclick="eliminarProducto(this)" class="p-2 text-red-600 hover:bg-red-100 rounded transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-900">Mouse Inalámbrico</div>
                                                    <div class="text-xs text-gray-500">SKU: MS-WL-005</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" step="0.01" value="45.00" 
                                                           class="w-24 px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 precio" 
                                                           onchange="calcularTotales()">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" value="2" min="1" 
                                                           class="w-20 px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cantidad" 
                                                           onchange="calcularTotales()">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="font-semibold text-gray-900 subtotal">$90.00</span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <button onclick="eliminarProducto(this)" class="p-2 text-red-600 hover:bg-red-100 rounded transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
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
                                                <option value="efectivo" selected>Efectivo</option>
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
                                                <option value="pendiente" selected>Pendiente</option>
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

                                <div class="mb-6">
                                    <label for="notas" class="block text-sm font-medium text-gray-700 mb-3">Notas Adicionales</label>
                                    <textarea id="notas" name="notas" rows="3" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" 
                                              placeholder="Observaciones o comentarios sobre la venta...">Cliente solicita factura electrónica</textarea>
                                </div>

                                <!-- Audit Information -->
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Información de Auditoría</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-600">
                                        <div>
                                            <span class="font-medium">Creado:</span>
                                            <p>15 Mar, 2024 - 10:30 AM</p>
                                            <p>Por: Admin User</p>
                                        </div>
                                        <div>
                                            <span class="font-medium">Actualizado:</span>
                                            <p>16 Mar, 2024 - 02:15 PM</p>
                                            <p>Por: Admin User</p>
                                        </div>
                                        <div>
                                            <span class="font-medium">Venta ID:</span>
                                            <p>#V-001</p>
                                            <p>Referencia: VTA-2024-001</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Form Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <button type="button" onclick="confirmarCancelacion()" 
                                        class="w-full sm:w-auto px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancelar
                                </button>
                                <button type="button" onclick="mostrarVistaPrevia()" 
                                        class="w-full sm:w-auto px-8 py-3 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Vista Previa
                                </button>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <button type="button" onclick="guardarCambios()" 
                                        class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center font-medium shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Guardar Cambios
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
                                <span class="font-semibold text-gray-900" id="subtotal">$1,290.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">IGV (18%)</span>
                                <span class="font-semibold text-gray-900" id="igv">$232.20</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Descuento</span>
                                <div class="flex items-center space-x-2">
                                    <span class="font-semibold text-red-600" id="descuento">$0.00</span>
                                    <button class="p-1 text-blue-600 hover:bg-blue-100 rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-blue-600" id="total">$1,522.20</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                        
                        <div class="space-y-3">
                            <button onclick="duplicarVenta()" class="w-full px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg hover:bg-green-100 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Duplicar Venta
                            </button>
                            <button onclick="generarFactura()" class="w-full px-4 py-3 bg-purple-50 border border-purple-200 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generar Factura
                            </button>
                            <button onclick="marcarCompletada()" class="w-full px-4 py-3 bg-orange-50 border border-orange-200 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Marcar Completada
                            </button>
                        </div>
                    </div>

                    <!-- Sale History -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Historial de Cambios</h3>
                        
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Venta creada</p>
                                    <p class="text-xs text-gray-500">15 Mar, 2024 - 10:30 AM</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Producto agregado</p>
                                    <p class="text-xs text-gray-500">Mouse Inalámbrico</p>
                                    <p class="text-xs text-gray-500">15 Mar, 2024 - 10:35 AM</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Estado actualizado</p>
                                    <p class="text-xs text-gray-500">Pendiente → En proceso</p>
                                    <p class="text-xs text-gray-500">16 Mar, 2024 - 02:15 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize with preloaded data
        document.addEventListener('DOMContentLoaded', function() {
            calcularTotales();
        });

        // Product management functions
        function agregarProducto() {
            const searchInput = document.getElementById('productSearch');
            const productName = searchInput.value.trim();
            
            if (productName) {
                const tableBody = document.getElementById('productosTable');
                const newRow = document.createElement('tr');
                newRow.className = 'hover:bg-gray-50 transition-colors';
                newRow.innerHTML = `
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">${productName}</div>
                        <div class="text-xs text-gray-500">SKU: NUEVO</div>
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
            if (confirm('¿Estás seguro de eliminar este producto de la venta?')) {
                row.remove();
                calcularTotales();
            }
        }

        function calcularTotales() {
            let subtotal = 0;
            
            document.querySelectorAll('#productosTable tr').forEach(row => {
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

        // Edit-specific functions
        function guardarCambios() {
            if (confirm('¿Estás seguro de guardar los cambios en esta venta?')) {
                // Add save logic here
                alert('Cambios guardados exitosamente!');
            }
        }

        function confirmarCancelacion() {
            if (confirm('¿Estás seguro de cancelar? Los cambios no guardados se perderán.')) {
                window.location.href = "{{ route('ventas.index') }}";
            }
        }

        function mostrarVistaPrevia() {
            alert('Función de vista previa - Aquí se mostraría el PDF de la venta');
        }

        function duplicarVenta() {
            if (confirm('¿Quieres duplicar esta venta?')) {
                // Add duplicate logic here
                alert('Venta duplicada exitosamente!');
            }
        }

        function generarFactura() {
            alert('Generando factura electrónica...');
        }

        function marcarCompletada() {
            if (confirm('¿Marcar esta venta como completada?')) {
                document.getElementById('estado').value = 'completada';
                alert('Venta marcada como completada!');
            }
        }
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
        
        /* Custom scrollbar for history */
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }
    </style>
</x-layout.default>