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

    <div x-data="solicitudRepuestoEdit({{ Js::from($solicitud) }}, {{ Js::from($productosActuales) }}, {{ Js::from($tickets) }})" 
         class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="container mx-auto px-4 w-full">
            <!-- Header Principal -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-blue-100 transition-all duration-300 hover:shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between lg:gap-8">
                    <!-- Contenido Principal -->
                    <div class="flex-1">
                        <!-- Título y Descripción -->
                        <div class="mb-4">
                            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                                Editar Orden de Repuesto
                            </h1>
                            <p class="text-gray-600 text-lg">Actualice la información de la orden de repuestos existente</p>
                        </div>

                        <!-- Información en Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Usuario</p>
                                    <p class="font-semibold text-gray-900">{{ auth()->user()->name ?? 'Administrador' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha Actualización</p>
                                    <p x-text="currentDate" class="font-semibold text-gray-900"></p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Estado</p>
                                    <p class="font-semibold text-gray-900">En edición</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Número de Orden -->
                    <div class="mt-6 lg:mt-0 lg:ml-6">
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-center shadow-lg border border-blue-200">
                            <p class="text-white/80 text-sm font-medium mb-1">Orden N°</p>
                            <div class="text-2xl lg:text-3xl font-black text-white tracking-wide" x-text="solicitud.codigo"></div>
                            <div class="w-12 h-1 bg-white/30 rounded-full mx-auto mt-2"></div>
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
                                    <h2 class="text-xl font-bold text-black">Selección de Ticket</h2>
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
                                        <option :value="ticket.idTickets" x-text="ticket.numero_ticket" :selected="ticket.idTickets == solicitud.idticket"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Loading State -->
                            <div x-show="loadingTicket" class="flex justify-center items-center py-8">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                            </div>

                            <!-- Información del Ticket - Versión Compacta Completa -->
                            <div x-show="selectedTicketInfo && !loadingTicket"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="bg-white rounded-2xl p-6 border border-blue-200 shadow-lg mt-4">

                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-md">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900">Ticket Seleccionado</h4>
                                            <p class="text-blue-600 font-semibold" x-text="selectedTicketInfo?.numero_ticket"></p>
                                        </div>
                                    </div>
                                    <button @click="clearTicketSelection()"
                                        class="flex items-center space-x-2 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg border border-red-200 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="text-sm font-medium">Quitar Ticket</span>
                                    </button>
                                </div>

                                <!-- Información en 3 columnas -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Columna 1: Información General -->
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="selectedTicketInfo?.numero_ticket"></p>
                                                <p class="text-xs text-gray-600">Número de Ticket</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="selectedTicketInfo?.cliente_general"></p>
                                                <p class="text-xs text-gray-600">Cliente General</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="selectedTicketInfo?.cliente_nombre || 'No especificado'"></p>
                                                <p class="text-xs text-gray-600">Cliente</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna 2: Información Adicional -->
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="selectedTicketInfo?.cliente_documento || 'No especificado'"></p>
                                                <p class="text-xs text-gray-600">Documento</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="selectedTicketInfo?.tienda_nombre || 'No especificado'"></p>
                                                <p class="text-xs text-gray-600">Tienda</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="formatDate(selectedTicketInfo?.fechaCompra)"></p>
                                                <p class="text-xs text-gray-600">Fecha de Compra</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna 3: Información del Equipo -->
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    <span x-text="selectedTicketInfo?.marca_nombre || 'N/A'"></span> /
                                                    <span x-text="selectedTicketInfo?.modelo_nombre || 'N/A'"></span>
                                                </p>
                                                <p class="text-xs text-gray-600">Marca / Modelo</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="selectedTicketInfo?.serie || 'No especificado'"></p>
                                                <p class="text-xs text-gray-600">Número de Serie</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900 leading-relaxed" x-text="selectedTicketInfo?.fallaReportada || 'No especificada'"></p>
                                                <p class="text-xs text-gray-600">Falla Reportada</p>
                                            </div>
                                        </div>
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
                                    <p class="text-blue-100 text-sm">Actualice los repuestos necesarios para la orden</p>
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
                                            <input type="number" x-model="newProduct.cantidad" min="1" max="100" class="w-16 text-center border-y border-blue-600 focus:ring-0 bg-white text-gray-900 font-semibold" readonly />
                                            <button @click="increaseQuantity()" class="bg-blue-600 text-white flex justify-center items-center rounded-r-lg px-4 font-semibold border border-l-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2 text-center">Mín: 1 - Máx: 100</div>
                                    </div>
                                </div>

                                <button @click="addProduct()" :disabled="!canAddProduct" :class="{'opacity-50 cursor-not-allowed': !canAddProduct, 'hover:scale-[1.02]': canAddProduct}" class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg font-bold transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
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
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-white text-cyan-600 rounded-full font-bold shadow-md">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Información Adicional</h2>
                                    <p class="text-blue-100 text-sm">Actualice los detalles de la orden</p>
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
                                            <option :value="servicio.value" x-text="servicio.text" :selected="servicio.value == orderInfo.tipoServicio"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Fecha Requerida -->
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-4">Fecha Requerida</label>
                                    <input type="date" x-model="orderInfo.fechaRequerida" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 transition-all duration-200" :min="minDate">
                                    <div class="text-xs text-gray-500 mt-2">Fecha límite para tener todos los repuestos</div>
                                    <div class="text-sm text-blue-600 font-medium mt-2" x-show="orderInfo.fechaRequerida">
                                        📅 Fecha establecida: <span x-text="formatDateForDisplay(orderInfo.fechaRequerida)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Nivel de Urgencia - Versión Cards -->
                            <div class="mt-8 space-y-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Nivel de Urgencia</label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <template x-for="(urgencia, index) in nivelesUrgencia" :key="index">
                                        <button type="button" @click="orderInfo.urgencia = urgencia.value" class="group relative text-left transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                                            <div class="p-5 rounded-xl border-2 transition-all duration-300 h-full bg-white shadow-sm hover:shadow-md" :class="{
                                                    'border-green-500 bg-gradient-to-r from-green-50 to-emerald-50 shadow-green-100': orderInfo.urgencia === urgencia.value && urgencia.value === 'baja',
                                                    'border-yellow-500 bg-gradient-to-r from-yellow-50 to-amber-50 shadow-yellow-100': orderInfo.urgencia === urgencia.value && urgencia.value === 'media',
                                                    'border-red-500 bg-gradient-to-r from-red-50 to-rose-50 shadow-red-100': orderInfo.urgencia === urgencia.value && urgencia.value === 'alta',
                                                    'border-gray-200 bg-gray-50 hover:border-gray-300': orderInfo.urgencia !== urgencia.value
                                                }">

                                                <!-- Header con emoji y título -->
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-2xl" x-text="urgencia.emoji"></span>
                                                    <div class="w-6 h-6 flex items-center justify-center transition-all duration-300" :class="{
                                                            'text-green-600': orderInfo.urgencia === urgencia.value && urgencia.value === 'baja',
                                                            'text-yellow-600': orderInfo.urgencia === urgencia.value && urgencia.value === 'media',
                                                            'text-red-600': orderInfo.urgencia === urgencia.value && urgencia.value === 'alta',
                                                            'text-gray-400': orderInfo.urgencia !== urgencia.value
                                                        }">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path x-show="orderInfo.urgencia === urgencia.value" fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            <path x-show="orderInfo.urgencia !== urgencia.value" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Contenido -->
                                                <div class="space-y-2">
                                                    <h3 class="font-bold text-gray-900 text-lg" x-text="urgencia.text"></h3>
                                                    <p class="text-sm text-gray-600 leading-relaxed" x-text="urgencia.description"></p>
                                                </div>

                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-8">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Observaciones y Comentarios</label>
                                <textarea x-model="orderInfo.observaciones" rows="5" class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 resize-none transition-all duration-200" placeholder="Describa cualquier observación, comentario adicional o instrucción especial para esta orden..."></textarea>
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
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Fecha Requerida</span>
                                <span class="text-lg font-bold text-orange-600" x-text="orderInfo.fechaRequerida ? formatDateForDisplay(orderInfo.fechaRequerida) : 'No definida'"></span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-700 font-medium">Estado</span>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">Editando</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Acciones</h3>
                        <div class="space-y-4">
                            <button @click="clearAll()" :disabled="products.length === 0 || isUpdatingOrder" :class="{ 'opacity-50 cursor-not-allowed': products.length === 0 || isUpdatingOrder }" class="w-full px-6 py-4 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Limpiar Todo</span>
                            </button>

                            <button @click="updateOrder()" :disabled="!canCreateOrder || isUpdatingOrder" :class="{
                                'opacity-50 cursor-not-allowed': !canCreateOrder || isUpdatingOrder,
                                'hover:scale-[1.02]': canCreateOrder && !isUpdatingOrder
                            }" class="w-full px-6 py-4 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
                                <template x-if="!isUpdatingOrder">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                                <template x-if="isUpdatingOrder">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                </template>
                                <span class="text-lg" x-text="isUpdatingOrder ? 'Actualizando Orden...' : 'Actualizar Orden'"></span>
                            </button>

                            <a href="{{ route('solicitudrepuesto.index') }}" class="w-full px-6 py-4 bg-gray-500 text-white rounded-lg font-bold hover:bg-gray-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Volver al Listado</span>
                            </a>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-200">
                        <h4 class="text-lg font-bold text-blue-700 mb-6 text-center">¿Necesita Ayuda?</h4>
                        <div class="space-y-4">
                            <!-- Teléfono -->
                            <div class="flex items-center space-x-4 p-4 bg-white/80 rounded-xl border border-blue-100 backdrop-blur-sm">
                                <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600">Soporte Técnico</p>
                                    <p class="text-lg font-bold text-gray-900">+1 (555) 123-4567</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-center space-x-4 p-4 bg-white/80 rounded-xl border border-blue-100 backdrop-blur-sm">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600">Email</p>
                                    <p class="text-lg font-bold text-gray-900">soporte@empresa.com</p>
                                </div>
                            </div>

                            <!-- Horario -->
                            <div class="flex items-center space-x-4 p-4 bg-white/80 rounded-xl border border-blue-100 backdrop-blur-sm">
                                <div class="flex-shrink-0 w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600">Horario de Atención</p>
                                    <p class="text-lg font-bold text-gray-900">24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notificación Toast -->
        <div x-show="notification.show" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
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
            Alpine.data('solicitudRepuestoEdit', (solicitud, productosActuales, tickets) => ({
                // Estado de la aplicación
                currentDate: '',
                solicitud: solicitud,
                selectedTicket: solicitud.idticket || '',
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
                    tipoServicio: solicitud.tiposervicio || '',
                    urgencia: solicitud.urgencia || '',
                    observaciones: solicitud.observaciones || '',
                    fechaRequerida: solicitud.fecharequerida ? solicitud.fecharequerida.split(' ')[0] : ''
                },
                notification: {
                    show: false,
                    message: '',
                    type: 'info'
                },
                notificationTimeout: null,
                minDate: '',
                isUpdatingOrder: false,
                tickets: tickets,

                // Datos para los selects
                tiposServicio: [
                    { value: 'mantenimiento', text: '🛠️ Mantenimiento Preventivo' },
                    { value: 'reparacion', text: '🔧 Reparación Correctiva' },
                    { value: 'instalacion', text: '⚡ Instalación' },
                    { value: 'garantia', text: '📋 Garantía' }
                ],
                nivelesUrgencia: [
                    { value: 'baja', text: 'Baja', emoji: '🟢', description: 'Sin urgencia específica' },
                    { value: 'media', text: 'Media', emoji: '🟡', description: 'Necesario en los próximos días' },
                    { value: 'alta', text: 'Alta', emoji: '🔴', description: 'Urgente - necesario inmediatamente' }
                ],

                // Computed properties
                get totalQuantity() {
                    return this.products.reduce((sum, product) => sum + product.cantidad, 0);
                },
                get totalUniqueProducts() {
                    const uniqueProducts = new Set();
                    this.products.forEach(product => {
                        const key = `${product.ticketId}-${product.modeloId}-${product.tipoId}-${product.codigoId}`;
                        uniqueProducts.add(key);
                    });
                    return uniqueProducts.size;
                },
                get canAddProduct() {
                    return this.newProduct.modelo &&
                        this.newProduct.tipo &&
                        this.newProduct.codigo &&
                        this.newProduct.cantidad > 0 &&
                        this.selectedTicket;
                },
                get canCreateOrder() {
                    return this.products.length > 0 &&
                        this.selectedTicket &&
                        this.orderInfo.tipoServicio &&
                        this.orderInfo.urgencia &&
                        this.orderInfo.fechaRequerida;
                },

                // Métodos
                init() {
                    this.currentDate = new Date().toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    this.minDate = new Date().toISOString().split('T')[0];

                    // Cargar productos actuales
                    this.loadExistingProducts();

                    this.$nextTick(() => {
                        this.initSelect2();
                        // Cargar información del ticket si ya está seleccionado
                        if (this.selectedTicket) {
                            this.loadTicketInfo(this.selectedTicket);
                        }
                    });
                },

                loadExistingProducts() {
                    if (productosActuales && productosActuales.length > 0) {
                        this.products = productosActuales.map(product => ({
                            uniqueId: Date.now() + Math.random(),
                            ticket: product.numero_ticket,
                            ticketId: product.idticket,
                            modelo: product.modelo_nombre,
                            modeloId: product.idModelo,
                            tipo: product.tipo_repuesto,
                            tipoId: product.idsubcategoria,
                            codigo: product.codigo_repuesto,
                            codigoId: product.codigo_repuesto,
                            cantidad: product.cantidad
                        }));
                    }
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

                        // Establecer el valor inicial
                        if (this.selectedTicket) {
                            $(this.$refs.ticketSelect).val(this.selectedTicket).trigger('change');
                        }
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
                    switch (this.notification.type) {
                        case 'success':
                            return 'bg-green-500';
                        case 'error':
                            return 'bg-red-500';
                        case 'warning':
                            return 'bg-yellow-500';
                        default:
                            return 'bg-blue-500';
                    }
                },

                getNotificationIcon() {
                    switch (this.notification.type) {
                        case 'success':
                            return '✅';
                        case 'error':
                            return '❌';
                        case 'warning':
                            return '⚠️';
                        default:
                            return 'ℹ️';
                    }
                },

                formatDateForDisplay(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                async loadTicketInfo(ticketId) {
                    this.loadingTicket = true;
                    this.selectedTicketInfo = null;

                    try {
                        const response = await fetch(`/api/ticket-info/${ticketId}`);
                        const ticketData = await response.json();

                        if (ticketData) {
                            this.selectedTicketInfo = ticketData;

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
                        $(this.$refs.modeloSelect).empty().append(
                            $('<option>', {
                                value: modeloId,
                                text: modeloNombre
                            })
                        ).val(modeloId).trigger('change');
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

                        if (this.$refs.tipoSelect && tipos.length > 0) {
                            $(this.$refs.tipoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'Seleccione tipo de repuesto'
                                })
                            );

                            tipos.forEach(tipo => {
                                $(this.$refs.tipoSelect).append(
                                    $('<option>', {
                                        value: tipo.idsubcategoria,
                                        text: tipo.tipo_repuesto
                                    })
                                );
                            });

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

                        if (this.$refs.codigoSelect && codigos.length > 0) {
                            $(this.$refs.codigoSelect).empty().append(
                                $('<option>', {
                                    value: '',
                                    text: 'Seleccione código'
                                })
                            );

                            codigos.forEach(codigo => {
                                $(this.$refs.codigoSelect).append(
                                    $('<option>', {
                                        value: codigo.codigo_repuesto,
                                        text: `${codigo.codigo_repuesto} - ${codigo.nombre}`
                                    })
                                );
                            });

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

                    const modeloText = $(this.$refs.modeloSelect).find('option:selected').text() || this.newProduct.modelo;
                    const tipoText = $(this.$refs.tipoSelect).find('option:selected').text() || this.newProduct.tipo;
                    const codigoText = $(this.$refs.codigoSelect).find('option:selected').text() || this.newProduct.codigo;
                    const ticketText = this.selectedTicketInfo?.numero_ticket || 'N/A';

                    const existingProductIndex = this.products.findIndex(product =>
                        product.ticketId === this.selectedTicket &&
                        product.modeloId === this.newProduct.modelo &&
                        product.tipoId === this.newProduct.tipo &&
                        product.codigoId === this.newProduct.codigo
                    );

                    if (existingProductIndex !== -1) {
                        this.products[existingProductIndex].cantidad += this.newProduct.cantidad;
                        this.showNotification(`✅ Cantidad actualizada: ${this.products[existingProductIndex].cantidad} unidades`, 'success');
                    } else {
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

                    this.newProduct.cantidad = 1;
                    this.clearTipoSelect();
                    this.clearCodigoSelect();

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

                async updateOrder() {
                    if (!this.canCreateOrder) {
                        this.showNotification('❌ Complete todos los campos requeridos para actualizar la orden', 'error');
                        return;
                    }

                    this.isUpdatingOrder = true;

                    try {
                        const orderData = {
                            ticketId: this.selectedTicket,
                            orderInfo: this.orderInfo,
                            products: this.products
                        };

                        console.log('Actualizando orden:', orderData);

                        const response = await fetch(`/solicitudrepuesto/${this.solicitud.idsolicitudesordenes}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(orderData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showNotification(`🎉 ¡Orden ${result.codigo_orden} actualizada exitosamente!`, 'success');
                            
                            // Redirigir después de 2 segundos
                            setTimeout(() => {
                                window.location.href = '/solicitudrepuesto';
                            }, 2000);
                        } else {
                            throw new Error(result.message);
                        }

                    } catch (error) {
                        console.error('Error al actualizar la orden:', error);
                        this.showNotification(`❌ Error: ${error.message}`, 'error');
                    } finally {
                        this.isUpdatingOrder = false;
                    }
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
            }));
        });
    </script>
</x-layout.default>