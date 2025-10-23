<x-layout.default>
    <!-- Incluir Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            height: 48px;
            padding: 8px 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px;
            padding-left: 0;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
        }
        .select2-container--default.select2-container--disabled .select2-selection--single {
            background-color: #f9fafb;
            opacity: 0.6;
        }
    </style>
    
    <div x-data="solicitudRepuesto()" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Principal -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-blue-100 transition-all duration-300 hover:shadow-2xl">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <h1 class="text-4xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Crear Nueva Orden de Repuesto
                        </h1>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center bg-blue-50 px-3 py-2 rounded-lg">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-gray-700">Usuario: <strong class="text-gray-900">{{ auth()->user()->name ?? 'Administrador' }}</strong></span>
                            </div>
                            <div class="flex items-center bg-blue-50 px-3 py-2 rounded-lg">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span x-text="currentDate" class="text-gray-900 font-medium"></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl px-6 py-4 shadow-lg transition-all duration-300 hover:scale-105">
                        <div class="text-center text-white">
                            <div class="text-2xl font-bold">#ORD-<span x-text="orderNumber.toString().padStart(3, '0')"></span></div>
                            <div class="text-sm opacity-90">Número de Orden</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Columna Principal -->
                <div class="xl:col-span-3 space-y-8">
                    <!-- Selección de Ticket -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-white text-purple-600 rounded-full font-bold shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Selección de Ticket</h2>
                                    <p class="text-purple-100 text-sm">Busque y seleccione el ticket asociado a esta solicitud</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Ticket de Servicio</label>
                                <select x-ref="ticketSelect" class="w-full">
                                    <option value="">Buscar ticket por número...</option>
                                    <template x-for="ticket in tickets" :key="ticket.idTickets">
                                        <option :value="ticket.idTickets" x-text="ticket.numero_ticket"></option>
                                    </template>
                                </select>
                            </div>
                            
                            <!-- Loading State -->
                            <div x-show="loadingTicket" class="flex justify-center items-center py-8">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                            </div>
                            
                            <!-- Información del Ticket Seleccionado -->
                            <div x-show="selectedTicketInfo && !loadingTicket" x-transition:enter="transition ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 transform -translate-y-2" 
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="bg-blue-50 rounded-xl p-5 border border-blue-200 mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-lg font-bold text-gray-900">Información del Ticket</h4>
                                    <button @click="clearTicketSelection()" class="text-red-600 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm text-gray-600">Ticket:</span>
                                        <p class="font-semibold text-gray-900" x-text="selectedTicketInfo?.numero_ticket"></p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Cliente General:</span>
                                        <p class="font-semibold text-gray-900" x-text="selectedTicketInfo?.cliente_general"></p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Cliente:</span>
                                        <p class="font-semibold text-gray-900" x-text="selectedTicketInfo?.cliente_nombre || 'No especificado'"></p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Documento:</span>
                                        <p class="font-semibold text-gray-900" x-text="selectedTicketInfo?.cliente_documento || 'No especificado'"></p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Tienda:</span>
                                        <p class="font-semibold text-gray-900" x-text="selectedTicketInfo?.tienda_nombre || 'No especificado'"></p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Marca/Modelo:</span>
                                        <p class="font-semibold text-gray-900">
                                            <span x-text="selectedTicketInfo?.marca_nombre || 'No especificado'"></span> / 
                                            <span x-text="selectedTicketInfo?.modelo_nombre || 'No especificado'"></span>
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="text-sm text-gray-600">Serie:</span>
                                        <p class="font-semibold text-gray-900" x-text="selectedTicketInfo?.serie || 'No especificado'"></p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Fecha Compra:</span>
                                        <p class="font-semibold text-gray-900" x-text="formatDate(selectedTicketInfo?.fechaCompra)"></p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="text-sm text-gray-600">Falla Reportada:</span>
                                        <p class="font-semibold text-gray-900" x-text="selectedTicketInfo?.fallaReportada || 'No especificada'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de Productos -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-white text-blue-600 rounded-full font-bold shadow-md">
                                    1
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Selección de Productos</h2>
                                    <p class="text-blue-100 text-sm">Agregue los repuestos necesarios para la orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Tabla de Productos -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-900">Productos Seleccionados</h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-600" x-text="`${totalUniqueProducts} producto${totalUniqueProducts !== 1 ? 's' : ''}`"></span>
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" x-show="products.length > 0"></div>
                                    </div>
                                </div>

                                <div class="overflow-hidden rounded-xl border border-blue-100">
                                    <table class="w-full">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">Ticket</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">Modelo</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">Tipo de Repuesto</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">Código</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">Cantidad</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-blue-100">
                                            <template x-if="products.length === 0">
                                                <tr>
                                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                        </svg>
                                                        <p class="mt-4 text-lg font-medium text-gray-900">No hay productos agregados</p>
                                                        <p class="text-sm mt-2">Agregue productos usando el formulario inferior</p>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="(product, index) in products" :key="product.uniqueId">
                                                <tr class="product-row hover:bg-blue-50 transition-all duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-purple-600" x-text="product.ticket"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900" x-text="product.modelo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-700" x-text="product.tipo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-blue-600 font-mono font-bold" x-text="product.codigo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                                                        <div class="flex items-center space-x-3">
                                                            <span class="font-bold" x-text="product.cantidad"></span>
                                                            <div class="flex space-x-1">
                                                                <button @click="updateQuantity(index, -1)" class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </button>
                                                                <button @click="updateQuantity(index, 1)" class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base">
                                                        <button @click="removeProduct(index)" class="text-red-600 hover:text-red-700 font-semibold flex items-center space-x-2 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            <span>Eliminar</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Formulario para Agregar Producto -->
                            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Agregar Nuevo Producto</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                    <!-- Modelo (se llena automáticamente desde el ticket) -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Modelo</label>
                                        <select x-model="newProduct.modelo" x-ref="modeloSelect" class="w-full" disabled>
                                            <option value="">Seleccione un ticket primero</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1" x-show="!selectedTicket">El modelo se cargará automáticamente al seleccionar un ticket</p>
                                    </div>

                                    <!-- Tipo de Repuesto (idsubcategoria) -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Tipo de Repuesto</label>
                                        <select x-model="newProduct.tipo" x-ref="tipoSelect" class="w-full" :disabled="!newProduct.modelo">
                                            <option value="">Seleccione modelo primero</option>
                                        </select>
                                        <div x-show="loadingTipos" class="text-xs text-blue-500 mt-1">Cargando tipos de repuesto...</div>
                                    </div>

                                    <!-- Código -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Código</label>
                                        <select x-model="newProduct.codigo" x-ref="codigoSelect" class="w-full" :disabled="!newProduct.tipo">
                                            <option value="">Seleccione tipo primero</option>
                                        </select>
                                        <div x-show="loadingCodigos" class="text-xs text-blue-500 mt-1">Cargando códigos...</div>
                                    </div>

                                    <!-- Cantidad -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Cantidad</label>
                                        <div class="flex w-full">
                                            <button @click="decreaseQuantity()" class="bg-blue-600 text-white flex justify-center items-center rounded-l-lg px-4 font-semibold border border-r-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" x-model="newProduct.cantidad" min="1" max="100" 
                                                   class="w-16 text-center border-y border-blue-600 focus:ring-0 bg-white text-gray-900 font-semibold" readonly />
                                            <button @click="increaseQuantity()" class="bg-blue-600 text-white flex justify-center items-center rounded-r-lg px-4 font-semibold border border-l-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2 text-center">Mín: 1 - Máx: 100</div>
                                    </div>
                                </div>

                                <button @click="addProduct()" :disabled="!canAddProduct"
                                        :class="{'opacity-50 cursor-not-allowed': !canAddProduct, 'hover:scale-[1.02]': canAddProduct}"
                                        class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg font-bold transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="text-lg">Agregar Producto a la Orden</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100 transition-all duration-300">
                        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-white text-cyan-600 rounded-full font-bold shadow-md">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Información Adicional</h2>
                                    <p class="text-cyan-100 text-sm">Complete los detalles de la orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Tipo de Servicio -->
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-4">Tipo de Servicio</label>
                                    <select x-model="orderInfo.tipoServicio" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        <option value="">Seleccione un tipo de servicio</option>
                                        <template x-for="servicio in tiposServicio" :key="servicio.value">
                                            <option :value="servicio.value" x-text="servicio.text"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Nivel de Urgencia -->
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-4">Nivel de Urgencia</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <template x-for="(urgencia, index) in nivelesUrgencia" :key="index">
                                            <button type="button" @click="orderInfo.urgencia = urgencia.value"
                                                    :class="{
                                                        'bg-green-500 text-white border-green-500': orderInfo.urgencia === urgencia.value && urgencia.value === 'baja',
                                                        'bg-yellow-500 text-white border-yellow-500': orderInfo.urgencia === urgencia.value && urgencia.value === 'media',
                                                        'bg-red-500 text-white border-red-500': orderInfo.urgencia === urgencia.value && urgencia.value === 'alta',
                                                        'border-gray-300 text-gray-700 hover:bg-gray-50': orderInfo.urgencia !== urgencia.value
                                                    }"
                                                    class="py-4 px-4 border-2 rounded-lg text-sm font-bold transition-all duration-200">
                                                <span x-text="urgencia.emoji"></span> <span x-text="urgencia.text"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-8">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Observaciones y Comentarios</label>
                                <textarea x-model="orderInfo.observaciones" rows="5"
                                          class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 resize-none transition-all duration-200"
                                          placeholder="Describa cualquier observación, comentario adicional o instrucción especial para esta orden..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="xl:col-span-1 space-y-8">
                    <!-- Resumen de la Orden -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Resumen de Orden</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Productos Únicos</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="totalUniqueProducts"></span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Total Cantidad</span>
                                <span class="text-2xl font-bold text-green-600" x-text="totalQuantity"></span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-700 font-medium">Estado</span>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">Pendiente</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Acciones</h3>
                        <div class="space-y-4">
                            <button @click="clearAll()" :disabled="products.length === 0"
                                    :class="{'opacity-50 cursor-not-allowed': products.length === 0}"
                                    class="w-full px-6 py-4 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Limpiar Todo</span>
                            </button>

                            <button @click="saveDraft()"
                                    class="w-full px-6 py-4 border-2 border-blue-500 text-blue-500 rounded-lg font-bold hover:bg-blue-500 hover:text-white transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                <span>Guardar Borrador</span>
                            </button>

                            <button @click="createOrder()" :disabled="!canCreateOrder"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !canCreateOrder,
                                        'hover:scale-[1.02]': canCreateOrder
                                    }"
                                    class="w-full px-6 py-4 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-lg">Crear Orden</span>
                            </button>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                        <h4 class="text-lg font-bold text-blue-600 mb-4 text-center">¿Necesita Ayuda?</h4>
                        <div class="text-center text-gray-700 text-sm space-y-2">
                            <p>📞 <strong>Soporte Técnico:</strong> +1 (555) 123-4567</p>
                            <p>✉️ <strong>Email:</strong> soporte@empresa.com</p>
                            <p>🕒 <strong>Horario:</strong> 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notificación Toast -->
        <div x-show="notification.show" x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             :class="getNotificationClass()"
             class="fixed top-6 right-6 text-white px-6 py-4 rounded-xl shadow-2xl z-50 max-w-sm">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 text-xl" x-html="getNotificationIcon()"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium" x-text="notification.message"></p>
                </div>
                <button @click="notification.show = false" class="flex-shrink-0 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Incluir jQuery y Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/es.min.js"></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuesto', () => ({
                // Estado de la aplicación
                currentDate: '',
                orderNumber: 1,
                selectedTicket: '',
                selectedTicketInfo: null,
                loadingTicket: false,
                loadingTipos: false,
                loadingCodigos: false,
                products: [],
                newProduct: {
                    modelo: '',
                    tipo: '',
                    codigo: '',
                    cantidad: 1
                },
                orderInfo: {
                    tipoServicio: '',
                    urgencia: '',
                    observaciones: ''
                },
                notification: {
                    show: false,
                    message: '',
                    type: 'info'
                },
                notificationTimeout: null,

                // Tickets desde Laravel
                tickets: @json($tickets),

                // Datos para los selects
                tiposServicio: [
                    { value: 'mantenimiento', text: '🛠️ Mantenimiento Preventivo' },
                    { value: 'reparacion', text: '🔧 Reparación Correctiva' },
                    { value: 'instalacion', text: '⚡ Instalación' },
                    { value: 'garantia', text: '📋 Garantía' }
                ],
                nivelesUrgencia: [
                    { value: 'baja', text: 'Baja', emoji: '🟢' },
                    { value: 'media', text: 'Media', emoji: '🟡' },
                    { value: 'alta', text: 'Alta', emoji: '🔴' }
                ],

                // Computed properties
                get totalQuantity() {
                    return this.products.reduce((sum, product) => sum + product.cantidad, 0);
                },
                get totalUniqueProducts() {
                    // Contar productos únicos por combinación de ticket + modelo + tipo + código
                    const uniqueProducts = new Set();
                    this.products.forEach(product => {
                        const key = `${product.ticketId}-${product.modeloId}-${product.tipoId}-${product.codigoId}`;
                        uniqueProducts.add(key);
                    });
                    return uniqueProducts.size;
                },
                get canAddProduct() {
                    return this.newProduct.modelo && this.newProduct.tipo && this.newProduct.codigo && this.newProduct.cantidad > 0 && this.selectedTicket;
                },
                get canCreateOrder() {
                    return this.products.length > 0 && this.selectedTicket && this.orderInfo.tipoServicio && this.orderInfo.urgencia;
                },

                // Métodos
                init() {
                    this.currentDate = new Date().toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    // Inicializar Select2 después de que Alpine haya renderizado
                    this.$nextTick(() => {
                        this.initSelect2();
                    });
                },

                initSelect2() {
                    // Ticket Select
                    if (this.$refs.ticketSelect) {
                        $(this.$refs.ticketSelect).select2({
                            placeholder: 'Buscar ticket por número...',
                            language: 'es',
                            width: '100%'
                        }).on('change', (e) => {
                            this.selectedTicket = e.target.value;
                            if (e.target.value) {
                                this.loadTicketInfo(e.target.value);
                            } else {
                                this.clearTicketSelection();
                            }
                        });
                    }

                    // Modelo Select (solo lectura)
                    if (this.$refs.modeloSelect) {
                        $(this.$refs.modeloSelect).select2({
                            placeholder: 'Seleccione un ticket primero',
                            language: 'es',
                            width: '100%',
                            disabled: true
                        }).on('change', (e) => {
                            this.newProduct.modelo = e.target.value;
                        });
                    }

                    // Tipo Select
                    if (this.$refs.tipoSelect) {
                        $(this.$refs.tipoSelect).select2({
                            placeholder: 'Seleccione modelo primero',
                            language: 'es',
                            width: '100%',
                            disabled: true
                        }).on('change', (e) => {
                            this.newProduct.tipo = e.target.value;
                            if (e.target.value && this.newProduct.modelo) {
                                this.loadCodigosRepuesto(this.newProduct.modelo, e.target.value);
                            } else {
                                this.clearCodigoSelect();
                            }
                        });
                    }

                    // Código Select
                    if (this.$refs.codigoSelect) {
                        $(this.$refs.codigoSelect).select2({
                            placeholder: 'Seleccione tipo primero',
                            language: 'es',
                            width: '100%',
                            disabled: true
                        }).on('change', (e) => {
                            this.newProduct.codigo = e.target.value;
                        });
                    }
                },

                // Métodos para notificación
                getNotificationClass() {
                    switch(this.notification.type) {
                        case 'success': return 'bg-green-500';
                        case 'error': return 'bg-red-500';
                        case 'warning': return 'bg-yellow-500';
                        default: return 'bg-blue-500';
                    }
                },

                getNotificationIcon() {
                    switch(this.notification.type) {
                        case 'success': return '✅';
                        case 'error': return '❌';
                        case 'warning': return '⚠️';
                        default: return 'ℹ️';
                    }
                },

                async loadTicketInfo(ticketId) {
                    this.loadingTicket = true;
                    this.selectedTicketInfo = null;
                    
                    try {
                        const response = await fetch(`/api/ticket-info/${ticketId}`);
                        const ticketData = await response.json();
                        
                        if (ticketData) {
                            this.selectedTicketInfo = ticketData;
                            
                            // Cargar modelo automáticamente desde el ticket
                            if (ticketData.idModelo && ticketData.modelo_nombre) {
                                this.newProduct.modelo = ticketData.idModelo;
                                this.updateModeloSelect(ticketData.idModelo, ticketData.modelo_nombre);
                                this.loadTiposRepuesto(ticketData.idModelo);
                            }
                            
                            this.showNotification('✅ Información del ticket cargada', 'success');
                        } else {
                            this.showNotification('❌ No se encontró información del ticket', 'error');
                        }
                    } catch (error) {
                        console.error('Error loading ticket info:', error);
                        this.showNotification('❌ Error al cargar la información del ticket', 'error');
                    } finally {
                        this.loadingTicket = false;
                    }
                },

                updateModeloSelect(modeloId, modeloNombre) {
                    if (this.$refs.modeloSelect) {
                        // Limpiar y agregar nueva opción
                        $(this.$refs.modeloSelect).empty().append(
                            $('<option>', {
                                value: modeloId,
                                text: modeloNombre
                            })
                        ).val(modeloId).trigger('change');
                        
                        // Habilitar el select (aunque sea solo lectura)
                        $(this.$refs.modeloSelect).prop('disabled', false);
                    }
                },

                async loadTiposRepuesto(modeloId) {
                    this.loadingTipos = true;
                    this.clearTipoSelect();
                    this.clearCodigoSelect();
                    
                    try {
                        const response = await fetch(`/api/tipos-repuesto/${modeloId}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const tipos = await response.json();
                        console.log('Tipos cargados:', tipos);
                        
                        if (this.$refs.tipoSelect && tipos.length > 0) {
                            // Limpiar el select
                            $(this.$refs.tipoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'Seleccione tipo de repuesto'
                                })
                            );
                            
                            // Agregar las opciones de tipos
                            tipos.forEach(tipo => {
                                $(this.$refs.tipoSelect).append(
                                    $('<option>', {
                                        value: tipo.idsubcategoria,
                                        text: tipo.tipo_repuesto
                                    })
                                );
                            });
                            
                            // Habilitar el select
                            $(this.$refs.tipoSelect).prop('disabled', false).trigger('change');
                            
                            this.showNotification(`✅ ${tipos.length} tipos de repuesto cargados`, 'success');
                        } else {
                            $(this.$refs.tipoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'No hay tipos disponibles'
                                })
                            ).prop('disabled', true).trigger('change');
                            this.showNotification('⚠️ No se encontraron tipos de repuesto para este modelo', 'warning');
                        }
                    } catch (error) {
                        console.error('Error loading tipos repuesto:', error);
                        $(this.$refs.tipoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Error al cargar tipos'
                            })
                        ).prop('disabled', true).trigger('change');
                        this.showNotification('❌ Error al cargar los tipos de repuesto', 'error');
                    } finally {
                        this.loadingTipos = false;
                    }
                },

                async loadCodigosRepuesto(modeloId, subcategoriaId) {
                    this.loadingCodigos = true;
                    this.clearCodigoSelect();
                    
                    try {
                        const response = await fetch(`/api/codigos-repuesto/${modeloId}/${subcategoriaId}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const codigos = await response.json();
                        console.log('Códigos cargados:', codigos);
                        
                        if (this.$refs.codigoSelect && codigos.length > 0) {
                            // Limpiar el select
                            $(this.$refs.codigoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'Seleccione código'
                                })
                            );
                            
                            // Agregar las opciones de códigos
                            codigos.forEach(codigo => {
                                $(this.$refs.codigoSelect).append(
                                    $('<option>', {
                                        value: codigo.codigo_repuesto,
                                        text: `${codigo.codigo_repuesto} - ${codigo.nombre}`
                                    })
                                );
                            });
                            
                            // Habilitar el select
                            $(this.$refs.codigoSelect).prop('disabled', false).trigger('change');
                            
                            this.showNotification(`✅ ${codigos.length} códigos cargados`, 'success');
                        } else {
                            $(this.$refs.codigoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'No hay códigos disponibles'
                                })
                            ).prop('disabled', true).trigger('change');
                            this.showNotification('⚠️ No se encontraron códigos para este tipo de repuesto', 'warning');
                        }
                    } catch (error) {
                        console.error('Error loading codigos:', error);
                        $(this.$refs.codigoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Error al cargar códigos'
                            })
                        ).prop('disabled', true).trigger('change');
                        this.showNotification('❌ Error al cargar los códigos', 'error');
                    } finally {
                        this.loadingCodigos = false;
                    }
                },

                clearTicketSelection() {
                    this.selectedTicket = '';
                    this.selectedTicketInfo = null;
                    this.clearModeloSelect();
                    this.clearTipoSelect();
                    this.clearCodigoSelect();
                    
                    if (this.$refs.ticketSelect) {
                        $(this.$refs.ticketSelect).val('').trigger('change');
                    }
                },

                clearModeloSelect() {
                    this.newProduct.modelo = '';
                    if (this.$refs.modeloSelect) {
                        $(this.$refs.modeloSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Seleccione un ticket primero'
                            })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                clearTipoSelect() {
                    this.newProduct.tipo = '';
                    if (this.$refs.tipoSelect) {
                        $(this.$refs.tipoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Seleccione modelo primero'
                            })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                clearCodigoSelect() {
                    this.newProduct.codigo = '';
                    if (this.$refs.codigoSelect) {
                        $(this.$refs.codigoSelect).empty().append(
                            $('<option>', {
                                value: '',
                                text: 'Seleccione tipo primero'
                            })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return 'No especificada';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES');
                },

                increaseQuantity() {
                    if (this.newProduct.cantidad < 100) {
                        this.newProduct.cantidad++;
                    }
                },

                decreaseQuantity() {
                    if (this.newProduct.cantidad > 1) {
                        this.newProduct.cantidad--;
                    }
                },

                addProduct() {
                    if (!this.canAddProduct) {
                        this.showNotification('Por favor complete todos los campos del producto', 'error');
                        return;
                    }

                    // Obtener los textos de los selects
                    const modeloText = $(this.$refs.modeloSelect).find('option:selected').text() || this.newProduct.modelo;
                    const tipoText = $(this.$refs.tipoSelect).find('option:selected').text() || this.newProduct.tipo;
                    const codigoText = $(this.$refs.codigoSelect).find('option:selected').text() || this.newProduct.codigo;
                    const ticketText = this.selectedTicketInfo?.numero_ticket || 'N/A';

                    // Crear una clave única para este producto
                    const productKey = `${this.selectedTicket}-${this.newProduct.modelo}-${this.newProduct.tipo}-${this.newProduct.codigo}`;

                    // Buscar si ya existe un producto igual
                    const existingProductIndex = this.products.findIndex(product => 
                        product.ticketId === this.selectedTicket &&
                        product.modeloId === this.newProduct.modelo &&
                        product.tipoId === this.newProduct.tipo &&
                        product.codigoId === this.newProduct.codigo
                    );

                    if (existingProductIndex !== -1) {
                        // Si existe, sumar la cantidad
                        this.products[existingProductIndex].cantidad += this.newProduct.cantidad;
                        this.showNotification(`✅ Cantidad actualizada: ${this.products[existingProductIndex].cantidad} unidades`, 'success');
                    } else {
                        // Si no existe, agregar nuevo producto
                        const product = {
                            uniqueId: Date.now() + Math.random(),
                            ticket: ticketText,
                            ticketId: this.selectedTicket,
                            modelo: modeloText,
                            modeloId: this.newProduct.modelo,
                            tipo: tipoText,
                            tipoId: this.newProduct.tipo,
                            codigo: codigoText,
                            codigoId: this.newProduct.codigo,
                            cantidad: this.newProduct.cantidad
                        };

                        this.products.push(product);
                        this.showNotification('✅ Producto agregado correctamente', 'success');
                    }

                    // Reset form - mantener el modelo pero limpiar tipo y código
                    this.newProduct.cantidad = 1;
                    this.clearTipoSelect();
                    this.clearCodigoSelect();
                    
                    // Recargar tipos de repuesto para el modelo actual
                    if (this.newProduct.modelo) {
                        this.loadTiposRepuesto(this.newProduct.modelo);
                    }
                },

                removeProduct(index) {
                    this.products.splice(index, 1);
                    this.showNotification('🗑️ Producto eliminado de la orden', 'info');
                },

                updateQuantity(index, change) {
                    const newQuantity = this.products[index].cantidad + change;
                    if (newQuantity >= 1 && newQuantity <= 100) {
                        this.products[index].cantidad = newQuantity;
                    }
                },

                clearAll() {
                    if (this.products.length === 0) {
                        this.showNotification('No hay productos para limpiar', 'info');
                        return;
                    }

                    if (confirm('¿Está seguro de que desea eliminar todos los productos de la orden?')) {
                        this.products = [];
                        this.showNotification('🗑️ Todos los productos han sido eliminados', 'info');
                    }
                },

                saveDraft() {
                    this.showNotification('📝 Borrador guardado correctamente', 'success');
                },

                createOrder() {
                    if (!this.canCreateOrder) {
                        this.showNotification('❌ Complete todos los campos requeridos para crear la orden', 'error');
                        return;
                    }

                    this.showNotification('🎉 ¡Orden creada exitosamente!', 'success');
                    
                    setTimeout(() => {
                        this.products = [];
                        this.clearTicketSelection();
                        this.orderInfo = {
                            tipoServicio: '',
                            urgencia: '',
                            observaciones: ''
                        };
                        this.orderNumber++;
                    }, 2000);
                },

                showNotification(message, type = 'info') {
                    this.notification.message = message;
                    this.notification.type = type;
                    this.notification.show = true;

                    if (this.notificationTimeout) {
                        clearTimeout(this.notificationTimeout);
                    }

                    this.notificationTimeout = setTimeout(() => {
                        this.notification.show = false;
                    }, 4000);
                }
            }))
        });
    </script>
</x-layout.default>