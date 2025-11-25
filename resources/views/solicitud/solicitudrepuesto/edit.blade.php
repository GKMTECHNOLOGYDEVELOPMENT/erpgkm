<x-layout.default>
    <!-- Incluir Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

    <div x-data="solicitudRepuestoEdit()" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="container mx-auto px-4 w-full">
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="{{ route('solicitudarticulo.index') }}" class="text-primary hover:underline">Solicitudes</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <a href="{{ route('solicitudrepuesto.create') }}" class="text-primary hover:underline">Crear Solicitud</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Editar Solicitud de Repuesto</span>
                    </li>
                </ul>
            </div>

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
                            <p class="text-gray-600 text-lg">Modifique la información de la orden de repuestos existente</p>
                        </div>

                        <!-- Información en Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha de Creación</p>
                                    <p class="font-semibold text-gray-900" x-text="formatDate(solicitud.fechaCreacion)"></p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Estado</p>
                                    <p class="font-semibold text-gray-900 capitalize" x-text="solicitud.estado"></p>
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
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full font-bold shadow-lg border border-white/30">
                                    <i class="fas fa-ticket-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Selección de Ticket</h2>
                                    <p class="text-white/80 text-sm">Busque y seleccione el ticket asociado a esta solicitud</p>
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

                            <!-- Información del Ticket -->
                            <div x-show="selectedTicketInfo && !loadingTicket"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="bg-white rounded-2xl p-6 border border-blue-200 shadow-lg mt-4">
                                <!-- ... (mismo contenido que en create) ... -->
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
                                    <p class="text-blue-100 text-sm">Modifique los repuestos de la orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Tabla de Productos -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-900">Productos Seleccionados</h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-600"
                                            x-text="`${totalUniqueProducts} producto${totalUniqueProducts !== 1 ? 's' : ''}`"></span>
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"
                                            x-show="products.length > 0"></div>
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
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                            </path>
                                                        </svg>
                                                        <p class="mt-4 text-lg font-medium text-gray-900">No hay productos agregados</p>
                                                        <p class="text-sm mt-2">Agregue productos usando el formulario inferior</p>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="(product, index) in products" :key="product.uniqueId">
                                                <tr class="product-row hover:bg-blue-50 transition-all duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-purple-600"
                                                        x-text="product.ticket"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900"
                                                        x-text="product.modelo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-700"
                                                        x-text="product.tipo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-blue-600 font-mono font-bold"
                                                        x-text="product.codigo"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                                                        <div class="flex items-center space-x-3">
                                                            <span class="font-bold" x-text="product.cantidad"></span>
                                                            <div class="flex space-x-1">
                                                                <button @click="updateQuantity(index, -1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <button @click="updateQuantity(index, 1)"
                                                                    class="p-1 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-base">
                                                        <button @click="removeProduct(index)"
                                                            class="text-red-600 hover:text-red-700 font-semibold flex items-center space-x-2 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
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
                                    <!-- Modelo -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Modelo</label>
                                        <select x-model="newProduct.modelo" x-ref="modeloSelect" class="w-full" disabled>
                                            <option value="">Seleccione un ticket primero</option>
                                        </select>
                                    </div>

                                    <!-- Tipo de Repuesto -->
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
                                            <button @click="decreaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center rounded-l-lg px-4 font-semibold border border-r-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" x-model="newProduct.cantidad" min="1" max="100"
                                                class="w-16 text-center border-y border-blue-600 focus:ring-0 bg-white text-gray-900 font-semibold"
                                                readonly />
                                            <button @click="increaseQuantity()"
                                                class="bg-blue-600 text-white flex justify-center items-center rounded-r-lg px-4 font-semibold border border-l-0 border-blue-600 hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2 text-center">Mín: 1 - Máx: 100</div>
                                    </div>
                                </div>

                                <button @click="addProduct()" :disabled="!canAddProduct"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !canAddProduct,
                                        'hover:scale-[1.02]': canAddProduct
                                    }"
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
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-white text-cyan-600 rounded-full font-bold shadow-md">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Información Adicional</h2>
                                    <p class="text-blue-100 text-sm">Modifique los detalles de la orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Tipo de Servicio -->
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-tools text-blue-500 mr-2"></i>
                                        Tipo de Servicio
                                    </label>
                                    <div class="relative">
                                        <select x-model="orderInfo.tipoServicio"
                                            class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white cursor-pointer shadow-sm hover:shadow-md">
                                            <option value="">Seleccione un tipo de servicio</option>
                                            <template x-for="servicio in tiposServicio" :key="servicio.value">
                                                <option :value="servicio.value" x-text="servicio.text"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Fecha Requerida -->
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                                        Fecha Requerida
                                    </label>
                                    <div class="relative">
                                        <input type="text" x-ref="fechaRequeridaInput" x-model="orderInfo.fechaRequerida"
                                            class="w-full pr-11 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 transition-all duration-200 shadow-sm hover:shadow-md"
                                            placeholder="Seleccione una fecha">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nivel de Urgencia -->
                            <div class="mt-8 space-y-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-gauge-high text-blue-500 mr-2"></i>
                                    Nivel de Urgencia
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <template x-for="(urgencia, index) in nivelesUrgencia" :key="index">
                                        <button type="button" @click="orderInfo.urgencia = urgencia.value"
                                            class="group relative text-left transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                                            <div class="p-5 rounded-xl border-2 transition-all duration-300 h-full shadow-sm hover:shadow-md"
                                                :class="{
                                                    [urgencia.borderColor + ' ' + urgencia.bgColor]: orderInfo.urgencia === urgencia.value,
                                                    'border-gray-200 bg-gray-50 hover:border-gray-300': orderInfo.urgencia !== urgencia.value
                                                }">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas text-xl" :class="urgencia.icon + ' ' + urgencia.iconColor"></i>
                                                        <h3 class="font-bold text-gray-900 text-lg" x-text="urgencia.text"></h3>
                                                    </div>
                                                    <div class="w-6 h-6 flex items-center justify-center transition-all duration-300"
                                                        :class="{
                                                            [urgencia.iconColor]: orderInfo.urgencia === urgencia.value,
                                                            'text-gray-400': orderInfo.urgencia !== urgencia.value
                                                        }">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path x-show="orderInfo.urgencia === urgencia.value" fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                            <path x-show="orderInfo.urgencia !== urgencia.value" fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <p class="text-sm text-gray-600 leading-relaxed" x-text="urgencia.description"></p>
                                            </div>
                                        </button>
                                    </template>
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
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="text-gray-700 font-medium">Fecha Requerida</span>
                                <span class="text-lg font-bold text-orange-600"
                                    x-text="orderInfo.fechaRequerida ? formatDateForDisplay(orderInfo.fechaRequerida) : 'No definida'"></span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-700 font-medium">Estado</span>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold capitalize" x-text="solicitud.estado"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Acciones</h3>
                        <div class="space-y-4">
                            <button @click="clearAll()" :disabled="products.length === 0 || isUpdatingOrder"
                                :class="{ 'opacity-50 cursor-not-allowed': products.length === 0 || isUpdatingOrder }"
                                class="w-full px-6 py-4 bg-warning text-white rounded-lg font-bold hover:bg-yellow-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span>Limpiar Todo</span>
                            </button>
                            
                            @if(\App\Helpers\PermisoHelper::tienePermiso('EDITAR SOLICITUD REPUESTO'))
                            <button @click="updateOrder()" :disabled="!canUpdateOrder || isUpdatingOrder"
                                :class="{
                                    'opacity-50 cursor-not-allowed': !canUpdateOrder || isUpdatingOrder,
                                    'hover:scale-[1.02]': canUpdateOrder && !isUpdatingOrder
                                }"
                                class="w-full px-6 py-4 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition-all duration-200 flex items-center justify-center space-x-3 shadow-lg">
                                <template x-if="!isUpdatingOrder">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                                <template x-if="isUpdatingOrder">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                </template>
                                <span class="text-lg" x-text="isUpdatingOrder ? 'Actualizando...' : 'Actualizar Solicitud'"></span>
                            </button>
                            @endif

                            <a href="{{ route('solicitudarticulo.index') }}"
                                class="w-full px-6 py-4 bg-gray-500 text-white rounded-lg font-bold hover:bg-gray-600 transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Cancelar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para confirmar limpieza -->
        <div x-show="showClearModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div x-show="showClearModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 mx-auto">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar todos los artículos?</h3>
                    <p class="text-gray-600">
                        Esta acción eliminará <span class="font-semibold text-red-600" x-text="totalUniqueProducts"></span> artículo(s)
                        con un total de <span class="font-semibold text-red-600" x-text="totalQuantity"></span> unidades.
                    </p>
                    <p class="text-sm text-gray-500 mt-2">Esta acción no se puede deshacer.</p>
                </div>
                <div class="flex space-x-3">
                    <button @click="cancelClearAll()"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button @click="confirmClearAll()"
                        class="flex-1 px-4 py-3 bg-danger text-white rounded-lg font-semibold hover:bg-red-700 transition-colors duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar Todo</span>
                    </button>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('solicitudRepuestoEdit', () => ({
                // Estado de la aplicación
                solicitud: @json($solicitud),
                articulos: @json($articulos),
                tickets: @json($tickets),
                selectedTicket: '',
                selectedTicketInfo: null,
                loadingTicket: false,
                showClearModal: false,
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
                    observaciones: '',
                    fechaRequerida: ''
                },
                notification: {
                    show: false,
                    message: '',
                    type: 'info'
                },
                notificationTimeout: null,
                isUpdatingOrder: false,

                // Datos para los selects
                tiposServicio: [
                    { value: 'mantenimiento', text: 'Mantenimiento Preventivo' },
                    { value: 'reparacion', text: 'Reparación Correctiva' },
                    { value: 'instalacion', text: 'Instalación' },
                    { value: 'garantia', text: 'Garantía' }
                ],
                nivelesUrgencia: [
                    {
                        value: 'baja',
                        text: 'Baja',
                        icon: 'fa-circle-check',
                        iconColor: 'text-green-500',
                        bgColor: 'bg-green-50',
                        borderColor: 'border-green-200',
                        description: 'Sin urgencia específica'
                    },
                    {
                        value: 'media',
                        text: 'Media',
                        icon: 'fa-clock',
                        iconColor: 'text-yellow-500',
                        bgColor: 'bg-yellow-50',
                        borderColor: 'border-yellow-200',
                        description: 'Necesario en los próximos días'
                    },
                    {
                        value: 'alta',
                        text: 'Alta',
                        icon: 'fa-triangle-exclamation',
                        iconColor: 'text-red-500',
                        bgColor: 'bg-red-50',
                        borderColor: 'border-red-200',
                        description: 'Urgente - necesario inmediatamente'
                    }
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
                get canUpdateOrder() {
                    return this.products.length > 0 &&
                        this.selectedTicket &&
                        this.orderInfo.tipoServicio &&
                        this.orderInfo.urgencia &&
                        this.orderInfo.fechaRequerida;
                },

                // Métodos
                init() {
                    this.initializeFormData();
                    this.$nextTick(() => {
                        this.initSelect2();
                        this.initFlatpickr();
                    });
                },

                initializeFormData() {
                    console.log('Solicitud data:', this.solicitud); // DEBUG
    console.log('IDsolicitudesordenes:', this.solicitud.idsolicitudesordenes); // DEBUG
                    // Cargar datos de la solicitud existente
                    this.orderInfo = {
                        tipoServicio: this.solicitud.tiposervicio || '',
                        urgencia: this.solicitud.urgencia || '',
                        observaciones: this.solicitud.observaciones || '',
                        fechaRequerida: this.solicitud.fecharequerida ? 
                            new Date(this.solicitud.fecharequerida).toISOString().split('T')[0] : ''
                    };

                    // Cargar productos existentes
                    if (this.articulos && this.articulos.length > 0) {
                        this.products = this.articulos.map(articulo => ({
                            uniqueId: Date.now() + Math.random(),
                            ticket: articulo.numero_ticket,
                            ticketId: articulo.idticket,
                            modelo: articulo.modelo_nombre,
                            modeloId: articulo.idModelo,
                            tipo: articulo.tipo_repuesto,
                            tipoId: articulo.subcategoria_id,
                            codigo: articulo.codigo_repuesto,
                            codigoId: articulo.codigo_repuesto,
                            cantidad: articulo.cantidad
                        }));

                        // Establecer el ticket seleccionado
                        if (this.articulos[0].idticket) {
                            this.selectedTicket = this.articulos[0].idticket;
                            this.loadTicketInfo(this.articulos[0].idticket);
                        }
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

                        // Establecer el valor inicial si existe
                        if (this.selectedTicket) {
                            $(this.$refs.ticketSelect).val(this.selectedTicket).trigger('change');
                        }
                    }

                    // Modelo Select
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

                initFlatpickr() {
                    this.$nextTick(() => {
                        if (this.$refs.fechaRequeridaInput && typeof flatpickr !== 'undefined') {
                            try {
                                flatpickr(this.$refs.fechaRequeridaInput, {
                                    locale: 'es',
                                    dateFormat: 'Y-m-d',
                                    minDate: 'today',
                                    disableMobile: false,
                                    allowInput: true,
                                    clickOpens: true,
                                    onChange: (selectedDates, dateStr) => {
                                        this.orderInfo.fechaRequerida = dateStr;
                                    },
                                    onReady: (selectedDates, dateStr, instance) => {
                                        if (this.orderInfo.fechaRequerida) {
                                            instance.setDate(this.orderInfo.fechaRequerida);
                                        }
                                    }
                                });
                            } catch (error) {
                                console.error('Error al inicializar Flatpickr:', error);
                            }
                        }
                    });
                },

                // Resto de métodos (loadTicketInfo, updateModeloSelect, loadTiposRepuesto, loadCodigosRepuesto, etc.)
                // ... (son similares a los del create, puedes copiarlos) ...

                async loadTicketInfo(ticketId) {
                    this.loadingTicket = true;
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
                            $('<option>', { value: modeloId, text: modeloNombre })
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
                        if (response.ok) {
                            const tipos = await response.json();
                            if (this.$refs.tipoSelect && tipos.length > 0) {
                                $(this.$refs.tipoSelect).empty().append(
                                    $('<option>', { value: '', text: 'Seleccione tipo de repuesto' })
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
                            }
                        }
                    } catch (error) {
                        console.error('Error loading tipos repuesto:', error);
                    } finally {
                        this.loadingTipos = false;
                    }
                },

                async loadCodigosRepuesto(modeloId, subcategoriaId) {
                    this.loadingCodigos = true;
                    this.clearCodigoSelect();
                    try {
                        const response = await fetch(`/api/codigos-repuesto/${modeloId}/${subcategoriaId}`);
                        if (response.ok) {
                            const codigos = await response.json();
                            if (this.$refs.codigoSelect && codigos.length > 0) {
                                $(this.$refs.codigoSelect).empty().append(
                                    $('<option>', { value: '', text: 'Seleccione código' })
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
                            }
                        }
                    } catch (error) {
                        console.error('Error loading codigos:', error);
                    } finally {
                        this.loadingCodigos = false;
                    }
                },

                clearTicketSelection() {
                    if (this.$refs.ticketSelect) {
                        $(this.$refs.ticketSelect).val('').trigger('change.select2');
                    }
                    this.selectedTicket = '';
                    this.selectedTicketInfo = null;
                    this.clearModeloSelect();
                    this.clearTipoSelect();
                    this.clearCodigoSelect();
                },

                clearModeloSelect() {
                    this.newProduct.modelo = '';
                    if (this.$refs.modeloSelect) {
                        $(this.$refs.modeloSelect).empty().append(
                            $('<option>', { value: '', text: 'Seleccione un ticket primero' })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                clearTipoSelect() {
                    this.newProduct.tipo = '';
                    if (this.$refs.tipoSelect) {
                        $(this.$refs.tipoSelect).empty().append(
                            $('<option>', { value: '', text: 'Seleccione modelo primero' })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                clearCodigoSelect() {
                    this.newProduct.codigo = '';
                    if (this.$refs.codigoSelect) {
                        $(this.$refs.codigoSelect).empty().append(
                            $('<option>', { value: '', text: 'Seleccione tipo primero' })
                        ).prop('disabled', true).trigger('change');
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return 'No especificada';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES');
                },

                formatDateForDisplay(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}/${month}/${day}`;
                },

                increaseQuantity() {
                    if (this.newProduct.cantidad < 100) this.newProduct.cantidad++;
                },

                decreaseQuantity() {
                    if (this.newProduct.cantidad > 1) this.newProduct.cantidad--;
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
                        this.showNotification(`Cantidad actualizada: ${this.products[existingProductIndex].cantidad} unidades`, 'success');
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
                        this.showNotification('No hay artículos para limpiar', 'info');
                        return;
                    }
                    this.showClearModal = true;
                },

                confirmClearAll() {
                    this.products = [];
                    this.showClearModal = false;
                    this.showNotification('Todos los artículos han sido eliminados', 'info');
                },

                cancelClearAll() {
                    this.showClearModal = false;
                },

             async updateOrder() {
    if (!this.canUpdateOrder) {
        this.showNotification('❌ Complete todos los campos requeridos', 'error');
        return;
    }

    // CORREGIR EL NOMBRE DEL CAMPO - usar idSolicitudesOrdenes en lugar de idsolicitudesordenes
    const solicitudId = this.solicitud.idSolicitudesOrdenes;
    console.log('Actualizando solicitud ID:', solicitudId); // DEBUG
    
    if (!solicitudId) {
        this.showNotification('❌ Error: ID de solicitud no encontrado', 'error');
        return;
    }

    this.isUpdatingOrder = true;

    try {
        const orderData = {
            ticketId: this.selectedTicket,
            orderInfo: this.orderInfo,
            products: this.products
        };

        // Usar el ID corregido
        const response = await fetch('/solicitudrepuesto/' + solicitudId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        });

        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            this.showNotification(`✅ ¡Orden ${result.codigo_orden} actualizada exitosamente!`, 'success');
            setTimeout(() => {
                window.location.href = "{{ route('solicitudarticulo.index') }}";
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
                getNotificationClass() {
                    switch (this.notification.type) {
                        case 'success': return 'bg-green-500';
                        case 'error': return 'bg-red-500';
                        case 'warning': return 'bg-yellow-500';
                        default: return 'bg-blue-500';
                    }
                },

                getNotificationIcon() {
                    switch (this.notification.type) {
                        case 'success': return '✅';
                        case 'error': return '❌';
                        case 'warning': return '⚠️';
                        default: return 'ℹ️';
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