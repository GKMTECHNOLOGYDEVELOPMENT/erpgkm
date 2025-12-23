<x-layout.default title="Crear Cotizacion - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div x-data="cotizacionAdd" class="opacity-0 animate-fade-in">
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <!-- Panel Principal -->
            <div class="xl:col-span-3">
                <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden border border-gray-200">
                    <!-- Header de la Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <h2 class="text-2xl font-bold text-gray-900">Nueva Cotización</h2>
                                    <p class="text-gray-600 mt-1">Complete todos los campos requeridos</p>
                                </div>
                            </div>

                            <!-- Checkbox NGR en el centro -->
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="ngrCheckbox"
                                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                        x-model="mostrarNGR">
                                    <label for="ngrCheckbox"
                                        class="ml-2 text-sm font-medium text-gray-900 cursor-pointer flex items-center">
                                        NGR
                                    </label>
                                </div>
                            </div>

                            <!-- Logo de la empresa -->
                            <div class="flex items-center">
                                <img src="/assets/images/auth/profile.png" alt="Logo GKM Technology"
                                    class="w-16 h-16 rounded-full border-2 border-white shadow-lg">
                            </div>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <!-- Información de la Empresa -->
                        <div class="flex flex-col lg:flex-row gap-8 mb-8">
                            <!-- Información Corporativa -->
                            <div class="lg:w-1/2">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center mr-4">
                                        <i class="fas fa-building text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Información de la Empresa
                                        </h3>
                                        <p class="text-gray-500 text-sm">Datos oficiales de su organización</p>
                                    </div>
                                </div>
                                <div class="space-y-4 text-gray-700 bg-gray-50 rounded-xl p-6 border border-gray-200">
                                    <div class="flex items-start">
                                        <i class="fas fa-map-marker-alt text-black mt-1 mr-4 w-4"></i>
                                        <span class="leading-relaxed font-medium">Av. Santa Elvira Mza. B Lote.
                                            8.</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-black mr-4 w-4"></i>
                                        <span class="font-medium">atencionalcliente@gkmtechnology.com.pe</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-black mr-4 w-4"></i>
                                        <span class="font-medium">0800-80142</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-globe text-black mr-4 w-4"></i>
                                        <span class="font-medium">www.gkmtechnology.com.pe</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de la Cotización -->
                            <div class="lg:w-1/2">
                                <div class="flex items-center mb-6">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-green-600 flex items-center justify-center mr-4">
                                        <i class="fas fa-file-invoice-dollar text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Detalles de la Cotización
                                        </h3>
                                        <p class="text-gray-500 text-sm">Información general del documento</p>
                                    </div>
                                </div>
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de
                                            Cotización</label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="COT-2024-001" x-model="params.cotizacionNo">
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de
                                                Emisión</label>
                                            <div class="relative">
                                                <input type="text"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-10"
                                                    id="fechaEmision" placeholder="Seleccionar fecha"
                                                    x-model="params.fechaEmision" data-date-format="d/m/Y">
                                                <i
                                                    class="fas fa-calendar-alt absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Válida
                                                hasta</label>
                                            <div class="relative">
                                                <input type="text"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-10"
                                                    id="validaHasta" placeholder="Seleccionar fecha"
                                                    x-model="params.validaHasta" data-date-format="d/m/Y">
                                                <i
                                                    class="fas fa-calendar-alt absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-8"></div>

                        <!-- Información del Cliente -->
                        <div class="mb-8">
                            <div class="flex items-center mb-8">
                                <!-- Cambiar bg-yellow-600 por bg-yellow-100 y el icono a amarillo -->
                                <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-users text-yellow-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Información del Cliente</h3>
                                    <p class="text-gray-500 text-sm">Datos del cliente destinatario</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar
                                        Cliente</label>
                                    <select id="clienteSelect"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-700 bg-white select2-custom"
                                        style="height: 48px;">
                                        <option value="">Buscar o seleccionar cliente...</option>
                                    </select>
                                </div>

                                <!-- Campos que se llenarán automáticamente -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre o Razón
                                            Social</label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.nombre" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo
                                            Electrónico</label>
                                        <input type="email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Seleccione un cliente primero" x-model="params.cliente.email"
                                            readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.telefono" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Empresa</label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.empresa" readonly>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.direccion" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección NGR -->
                        <div x-show="mostrarNGR" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-8">

                            <!-- Campos NGR: Ticket, Visita, Técnico, Tienda y Serie -->
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-blue-50 rounded-xl border border-blue-200 mb-4">
                                <!-- Ticket - Select2 -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Ticket
                                        <span class="text-red-500">*</span></label>
                                    <select id="ticketSelect"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-700 bg-white select2-custom"
                                        style="height: 48px;">
                                        <option value="">Seleccionar ticket...</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Seleccione un ticket para cargar
                                        automáticamente técnico y tienda</p>
                                </div>

                                <!-- Visita - Select2 -->
                                <div class="md:col-span-2"
                                    x-show="params.ticket.id && params.ticket.visitas && params.ticket.visitas.length > 1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Visita
                                        <span class="text-red-500">*</span></label>
                                    <select id="visitaSelect"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        x-model="params.visita_seleccionada">
                                        <option value="">Seleccionar visita...</option>
                                        <template x-for="visita in params.ticket.visitas" :key="visita.idVisitas">
                                            <option :value="visita.idVisitas"
                                                x-text="'Visita: ' + visita.Nombre + ' - ' + (visita.fecha_llegada ? new Date(visita.fecha_llegada).toLocaleDateString() : 'Sin fecha')">
                                            </option>
                                        </template>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1"
                                        x-text="params.ticket.visitas.length + ' visitas encontradas'"></p>
                                </div>
                            </div>

                            <!-- Información del Ticket -->
                            <div x-show="params.ticket.id" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6 bg-green-50 rounded-xl border border-green-200">

                                <!-- OT - Solo lectura -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">OT</label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        x-model="params.ot" readonly>
                                    <p class="text-xs text-gray-500 mt-1">Número de orden de trabajo</p>
                                </div>

                                <!-- Técnico - Solo lectura -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Técnico
                                        Asignado</label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        x-model="params.ticket.tecnico_nombre" readonly>
                                    <p class="text-xs text-gray-500 mt-1">Técnico de la visita</p>
                                </div>

                                <!-- Tienda - Solo lectura -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tienda</label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        x-model="params.ticket.tienda_nombre" readonly>
                                    <p class="text-xs text-gray-500 mt-1">Tienda del ticket</p>
                                </div>

                                <!-- Fecha de Llegada - Solo lectura -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de
                                        Llegada</label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        x-model="params.ticket.fecha_llegada" readonly>
                                    <p class="text-xs text-gray-500 mt-1">Fecha de llegada de la visita</p>
                                </div>

                                <!-- Serie - Input normal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Serie</label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Ej: SER-001-2024" x-model="params.serie">
                                    <p class="text-xs text-gray-500 mt-1"
                                        x-text="params.ticket.serie_equipo ? 'Serie del equipo: ' + params.ticket.serie_equipo : 'Ingrese número de serie'">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-8"></div>

                        <!-- Botón cargar suministros -->
                        <div x-show="params.ticket.id" class="flex justify-end mt-4">
                            <button type="button" @click="cargarSuministrosManual()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center">
                                <i class="fas fa-refresh mr-2"></i> Cargar Suministros del Ticket
                            </button>
                        </div>

                        <!-- Items de la Cotización -->
                        <div>
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center mr-4">
                                        <i class="fas fa-cube text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Detalle de
                                            Productos/Servicios</h3>
                                        <p class="text-gray-500 text-sm">Lista de items a cotizar</p>
                                    </div>
                                </div>
                                <button type="button" @click="addItem()"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center">
                                    <i class="fas fa-plus-circle mr-3"></i> Agregar Item
                                </button>
                            </div>

                            <div class="overflow-x-auto rounded-lg border border-gray-200" style="max-height: 500px;">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0 z-10">
                                        <tr>
                                            <th
                                                class="w-12 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                #</th>
                                            <th
                                                class="min-w-[300px] px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Descripción</th>
                                            <th
                                                class="w-24 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Cantidad</th>
                                            <th
                                                class="w-32 px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Precio Unit.</th>
                                            <th
                                                class="w-32 px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Total</th>
                                            <th
                                                class="w-16 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-if="items.length <= 0">
                                            <tr>
                                                <td colspan="6" class="px-4 py-12 text-center">
                                                    <i
                                                        class="fas fa-clipboard-list text-5xl mb-4 block text-gray-300"></i>
                                                    <p class="font-medium text-gray-600 text-lg">No hay items agregados
                                                    </p>
                                                    <p class="text-gray-500 mt-2">Comience agregando productos o
                                                        servicios</p>
                                                </td>
                                            </tr>
                                        </template>
                                        <template x-for="(item, index) in items" :key="item.id">
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-4 py-4 text-center text-gray-600 font-medium border-b border-gray-200"
                                                    x-text="index + 1"></td>
                                                <td class="px-4 py-4 border-b border-gray-200">
                                                    <select
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        :id="'articulo-select-' + item.id">
                                                        <option value="">Seleccionar artículo...</option>
                                                    </select>
                                                    <div class="mt-1">
                                                        <small class="text-gray-500"
                                                            x-text="item.codigo_repuesto ? 'Código: ' + item.codigo_repuesto : ''"
                                                            x-show="item.codigo_repuesto"></small>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 border-b border-gray-200">
                                                    <input type="number"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-center"
                                                        placeholder="0" x-model="item.cantidad" min="0"
                                                        @change="actualizarTotales()">
                                                </td>
                                                <td class="px-4 py-4 border-b border-gray-200">
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm"
                                                            x-text="obtenerSimboloMoneda()"></span>
                                                        <input type="number"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pl-8 text-right"
                                                            placeholder="0.00" x-model="item.precio" min="0"
                                                            step="0.01" @change="actualizarTotales()">
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-4 py-4 text-right font-semibold text-gray-900 text-lg border-b border-gray-200">
                                                    <span x-text="obtenerSimboloMoneda()"></span>
                                                    <span x-text="(item.precio * item.cantidad).toFixed(2)"></span>
                                                </td>
                                                <td class="px-4 py-4 text-center border-b border-gray-200">
                                                    <button type="button" @click="removeItem(item)"
                                                        class="text-gray-400 hover:text-red-500 transition-colors duration-200 transform hover:scale-110 p-2 rounded-lg hover:bg-red-50"
                                                        x-show="items.length > 1" title="Eliminar item">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totales -->
                            <div class="bg-white rounded-xl border border-gray-200 p-6 mt-8 shadow-sm">
                                <!-- Badge indicador de IGV con toggle -->
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700 font-medium">Configuración de Impuestos:</span>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-600"
                                            x-text="incluirIGV ? 'CON IGV' : 'SIN IGV'"></span>
                                        <button type="button" @click="toggleIGV()"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            :class="incluirIGV ? 'bg-blue-600' : 'bg-gray-200'">
                                            <span class="sr-only">Toggle IGV</span>
                                            <span aria-hidden="true"
                                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                :class="incluirIGV ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <div class="w-full sm:w-80 space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-700 font-medium">Subtotal:</span>
                                            <span class="text-lg font-semibold text-gray-900">
                                                <span x-text="obtenerSimboloMoneda()"></span>
                                                <span x-text="subtotal.toFixed(2)"></span>
                                            </span>
                                        </div>

                                        <!-- IGV dinámico -->
                                        <div class="flex justify-between items-center" x-show="incluirIGV">
                                            <span class="text-gray-700 font-medium">IGV (18%):</span>
                                            <span class="text-lg font-semibold text-gray-900">
                                                <span x-text="obtenerSimboloMoneda()"></span>
                                                <span x-text="igv.toFixed(2)"></span>
                                            </span>
                                        </div>

                                        <hr class="border-gray-200">
                                        <div class="flex justify-between items-center text-xl pt-2">
                                            <span class="font-bold text-gray-900">TOTAL:</span>
                                            <span class="font-bold text-blue-600 text-2xl">
                                                <span x-text="obtenerSimboloMoneda()"></span>
                                                <span x-text="total.toFixed(2)"></span>
                                            </span>
                                        </div>

                                        <!-- Nota sobre el IGV -->
                                        <div class="text-xs text-gray-500 mt-2 text-center border-t pt-2"
                                            x-text="incluirIGV ?
                    '✅ Precios incluyen IGV 18%' :
                    'ℹ️ Precios no incluyen IGV. Se agregará 18% al momento del pago'">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-8"></div>

                        <!-- Notas -->
                        <div class="mt-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Términos y Condiciones</label>
                            <textarea
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors h-32"
                                placeholder="Incluya aquí los términos de pago, condiciones de entrega, garantías, y cualquier otra información relevante..."
                                x-model="params.notas"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Configuración -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-cog text-blue-600 mr-3"></i>
                            Configuración
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">

                        <!-- Campo CON IGV / SIN IGV -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cotización</label>
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-sm font-medium text-gray-700"
                                    x-text="incluirIGV ? 'CON IGV' : 'SIN IGV'"></span>
                                <button type="button" @click="toggleIGV()"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    :class="incluirIGV ? 'bg-blue-600' : 'bg-gray-200'">
                                    <span class="sr-only">Toggle IGV</span>
                                    <span aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="incluirIGV ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2"
                                x-text="incluirIGV ?
        'Los precios incluyen IGV 18%' :
        'Los precios NO incluyen IGV 18%'">
                            </p>
                        </div>

                        <!-- Moneda -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Moneda</label>
                            <select
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                x-model="params.moneda" id="monedaSelect">
                                <option value="">Cargando monedas...</option>
                            </select>
                        </div>

                        <!-- Términos de Pago -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Términos de Pago</label>
                            <select
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                x-model="params.terminosPago" id="terminosPagoSelect">
                                <option value="">Cargando términos...</option>
                            </select>
                        </div>

                        <!-- Validez -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Validez (días)</label>
                            <input type="number"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                x-model="params.diasValidez" min="1" max="90">
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-play-circle text-blue-600 mr-3"></i>
                            Acciones
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <button type="button" @click="guardarCotizacion()"
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center justify-center">
                            <i class="fas fa-save mr-3"></i> Guardar Cotización
                        </button>

                        <button type="button" @click="vistaPrevia()"
                            class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center justify-center">
                            <i class="fas fa-eye mr-3"></i> Vista Previa
                        </button>

                        <button type="button" @click="generarPDF()"
                            class="w-full px-6 py-3 bg-danger text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center justify-center">
                            <i class="fas fa-file-pdf mr-3"></i> Generar PDF
                        </button>

                        <button type="button" @click="enviarEmail()"
                            class="w-full px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-3"></i> Enviar por Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar jQuery, Select2 y Flatpickr JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('assets/js/cotizaciones/cotizaciones.js') }}"></script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Versión mínima para ajustar altura */
        .select2-container .select2-selection--single {
            height: 48px !important;
            min-height: 48px !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 46px !important;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
    </style>
</x-layout.default>
