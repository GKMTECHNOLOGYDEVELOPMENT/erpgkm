<x-layout.default title="Editar Cotización - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/cotizaciones.css') }}">

    <div x-data="cotizacionEdit" x-init="init()" class="fade-in">
        <div class="grid xl:grid-cols-4 gap-8">
            <!-- Panel Principal -->
            <div class="xl:col-span-3">
                <div class="card mb-8 overflow-hidden">
                    <!-- Header de la Card -->
                    <div class="card-header bg-warning">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Editar Cotización</h2>
                                    <p class="text-yellow-100 mt-1">Modifique los datos de la cotización existente</p>
                                </div>
                            </div>

                            <!-- Información de Estado -->
                            <div class="flex items-center space-x-4">
                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i> Modo Edición
                                </span>
                                <span class="text-yellow-100 text-sm font-medium" x-text="params.cotizacionNo"></span>
                            </div>

                            <!-- Logo de la empresa -->
                            <div class="flex items-center">
                                <img src="/assets/images/auth/profile.png" alt="Logo GKM Technology"
                                    class="w-16 h-16 rounded-full border-2 border-yellow-200 shadow-lg">
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <!-- Información de la Empresa -->
                        <div class="flex flex-col lg:flex-row gap-8 mb-8">
                            <!-- Información Corporativa -->
                            <div class="lg:w-1/2">
                                <div class="flex items-center mb-6">
                                    <div class="icon-container icon-container-primary">
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
                                    <div class="icon-container icon-container-success">
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
                                        <label class="form-label">Número de Cotización</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            x-model="params.cotizacionNo" readonly>
                                        <p class="text-xs text-gray-500 mt-1">Número de cotización no editable</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="form-label">Fecha de Emisión</label>
                                            <div class="date-input-wrapper">
                                                <input type="text" class="form-control flatpickr-input"
                                                    id="fechaEmision" placeholder="Seleccionar fecha"
                                                    x-model="params.fechaEmision" data-date-format="d/m/Y">
                                                <i class="fas fa-calendar-alt icon"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Válida hasta</label>
                                            <div class="date-input-wrapper">
                                                <input type="text" class="form-control flatpickr-input"
                                                    id="validaHasta" placeholder="Seleccionar fecha"
                                                    x-model="params.validaHasta" data-date-format="d/m/Y">
                                                <i class="fas fa-calendar-alt icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- Información del Cliente -->
                        <div class="mb-8">
                            <div class="flex items-center mb-8">
                                <div class="icon-container icon-container-warning">
                                    <i class="fas fa-users text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Información del Cliente</h3>
                                    <p class="text-gray-500 text-sm">Datos del cliente destinatario</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="form-label">Cliente Seleccionado</label>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-semibold text-gray-900" x-text="params.cliente.nombre">
                                                </div>
                                                <div class="text-sm text-gray-600 mt-1"
                                                    x-text="params.cliente.empresa"></div>
                                            </div>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                Cliente asignado
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">El cliente no puede ser modificado en una
                                        cotización existente</p>
                                </div>

                                <!-- Campos de información del cliente -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div>
                                        <label class="form-label">Nombre o Razón Social</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            x-model="params.cliente.nombre" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control bg-gray-50"
                                            x-model="params.cliente.email" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            x-model="params.cliente.telefono" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Empresa</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            x-model="params.cliente.empresa" readonly>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            x-model="params.cliente.direccion" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección NGR -->
                        <div x-show="mostrarNGR" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-8">

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="icon-container icon-container-info">
                                        <i class="fas fa-ticket-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Información del Ticket NGR
                                        </h3>
                                        <p class="text-gray-500 text-sm">Datos relacionados con la garantía</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="ngrCheckboxEdit"
                                            class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                            x-model="mostrarNGR">
                                        <label for="ngrCheckboxEdit"
                                            class="ml-2 text-sm font-medium text-gray-900 cursor-pointer flex items-center">
                                            NGR
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Ticket -->
                            <div x-show="params.ticket.id"
                                class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6 bg-blue-50 rounded-xl border border-blue-200 mb-4">
                                <div>
                                    <label class="form-label">OT</label>
                                    <input type="text" class="form-control bg-gray-100" x-model="params.ot"
                                        readonly>
                                </div>
                                <div>
                                    <label class="form-label">Técnico</label>
                                    <input type="text" class="form-control bg-gray-100"
                                        x-model="params.ticket.tecnico_nombre" readonly>
                                </div>
                                <div>
                                    <label class="form-label">Tienda</label>
                                    <input type="text" class="form-control bg-gray-100"
                                        x-model="params.ticket.tienda_nombre" readonly>
                                </div>
                                <div>
                                    <label class="form-label">Serie</label>
                                    <input type="text" class="form-control" placeholder="Ej: SER-001-2024"
                                        x-model="params.serie">
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- Items de la Cotización -->
                        <div>
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center">
                                    <div class="icon-container icon-container-primary">
                                        <i class="fas fa-cube text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Detalle de
                                            Productos/Servicios</h3>
                                        <p class="text-gray-500 text-sm">Lista de items a cotizar</p>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" @click="addItem()">
                                    <i class="fas fa-plus-circle mr-3"></i> Agregar Item
                                </button>
                            </div>

                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="min-w-full">
                                    <thead class="sticky top-0 bg-white z-10">
                                        <tr>
                                            <th
                                                class="w-12 text-center py-4 px-4 bg-white font-semibold text-gray-700">
                                                #</th>
                                            <th class="min-w-[300px] py-4 px-4 bg-white font-semibold text-gray-700">
                                                Descripción</th>
                                            <th
                                                class="w-24 text-center py-4 px-4 bg-white font-semibold text-gray-700">
                                                Cantidad</th>
                                            <th class="w-32 text-right py-4 px-4 bg-white font-semibold text-gray-700">
                                                Precio Unit.</th>
                                            <th class="w-32 text-right py-4 px-4 bg-white font-semibold text-gray-700">
                                                Total</th>
                                            <th
                                                class="w-16 text-center py-4 px-4 bg-white font-semibold text-gray-700">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-if="items.length <= 0">
                                            <tr>
                                                <td colspan="6" class="text-center py-12 text-gray-500">
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
                                            <tr class="group hover:bg-gray-50 transition-all duration-200">
                                                <td class="text-center text-gray-600 font-medium py-4 px-4"
                                                    x-text="index + 1"></td>
                                                <td class="py-4 px-4">
                                                    <select class="form-control w-full articulo-select"
                                                        :id="'articulo-select-' + item.id">
                                                        <option value="">Seleccionar artículo...</option>
                                                    </select>
                                                    <div class="mt-1">
                                                        <small class="text-gray-500"
                                                            x-text="item.codigo_repuesto ? 'Código: ' + item.codigo_repuesto : ''"
                                                            x-show="item.codigo_repuesto"></small>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <input type="number"
                                                        class="form-control text-center border-gray-200 group-hover:border-gray-300 transition-colors w-full"
                                                        placeholder="0" x-model="item.cantidad" min="0"
                                                        @change="actualizarTotales()">
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm"
                                                            x-text="obtenerSimboloMoneda()"></span>
                                                        <input type="number"
                                                            class="form-control text-right border-gray-200 group-hover:border-gray-300 transition-colors pl-8 w-full"
                                                            placeholder="0.00" x-model="item.precio" min="0"
                                                            step="0.01" @change="actualizarTotales()">
                                                    </div>
                                                </td>
                                                <td class="text-right font-semibold text-gray-900 text-lg py-4 px-4">
                                                    <span x-text="obtenerSimboloMoneda()"></span>
                                                    <span x-text="(item.precio * item.cantidad).toFixed(2)"></span>
                                                </td>
                                                <td class="text-center py-4 px-4">
                                                    <button type="button" @click="removeItem(item)"
                                                        class="text-gray-400 hover:text-red-500 transition-all duration-200 transform hover:scale-110 p-2 rounded-lg hover:bg-red-50"
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
                            <div class="total-card mt-8">
                                <div class="flex justify-between items-center mb-4 p-4 bg-gray-50 rounded-lg">
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
                                    <div class="w-80 space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-700 font-medium">Subtotal:</span>
                                            <span class="text-lg font-semibold text-gray-900">
                                                <span x-text="obtenerSimboloMoneda()"></span>
                                                <span x-text="subtotal.toFixed(2)"></span>
                                            </span>
                                        </div>

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
                                            <span class="font-bold text-primary text-2xl">
                                                <span x-text="obtenerSimboloMoneda()"></span>
                                                <span x-text="total.toFixed(2)"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- Notas -->
                        <div class="mt-8">
                            <label class="form-label">Términos y Condiciones</label>
                            <textarea class="form-control h-32"
                                placeholder="Incluya aquí los términos de pago, condiciones de entrega, garantías, y cualquier otra información relevante..."
                                x-model="params.notas"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Configuración -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-cog text-primary mr-3"></i>
                            Configuración
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="form-label">Tipo de Cotización</label>
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
                        </div>

                        <div>
                            <label class="form-label">Moneda</label>
                            <select class="form-control" x-model="params.moneda" id="monedaSelect">
                                <option value="">Cargando monedas...</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Términos de Pago</label>
                            <select class="form-control" x-model="params.terminosPago" id="terminosPagoSelect">
                                <option value="">Cargando términos...</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Validez (días)</label>
                            <input type="number" class="form-control" x-model="params.diasValidez" min="1"
                                max="90">
                        </div>

                        <!-- Estado de la Cotización -->
                        <div>
                            <label class="form-label">Estado</label>
                            <select class="form-control" x-model="params.estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="enviada">Enviada</option>
                                <option value="aprobada">Aprobada</option>
                                <option value="rechazada">Rechazada</option>
                                <option value="vencida">Vencida</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-play-circle text-primary mr-3"></i>
                            Acciones
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <button type="button" class="btn btn-success w-full justify-center"
                            @click="actualizarCotizacion()">
                            <i class="fas fa-save mr-3"></i> Actualizar Cotización
                        </button>

                        <button type="button" class="btn btn-primary w-full justify-center" @click="vistaPrevia()">
                            <i class="fas fa-eye mr-3"></i> Vista Previa
                        </button>

                        <button type="button" class="btn btn-danger w-full justify-center" @click="generarPDF()">
                            <i class="fas fa-file-pdf mr-3"></i> Generar PDF
                        </button>

                        <button type="button" class="btn btn-warning w-full justify-center" @click="enviarEmail()">
                            <i class="fas fa-paper-plane mr-3"></i> Enviar por Email
                        </button>

                        <button type="button" class="btn btn-outline-danger w-full justify-center"
                            @click="cancelarEdicion()">
                            <i class="fas fa-times mr-3"></i> Cancelar
                        </button>
                    </div>
                </div>

                <!-- Información de Auditoría -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-history text-primary mr-3"></i>
                            Información de Auditoría
                        </h3>
                    </div>
                    <div class="p-6 space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Creado:</span>
                            <span class="font-medium text-gray-900"
                                x-text="params.fecha_creacion ? formatearFecha(params.fecha_creacion) : 'N/A'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Modificado:</span>
                            <span class="font-medium text-gray-900"
                                x-text="params.fecha_modificacion ? formatearFecha(params.fecha_modificacion) : 'N/A'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Creado por:</span>
                            <span class="font-medium text-gray-900" x-text="params.usuario_creacion || 'N/A'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function cotizacionEdit() {
            return {
                cotizacionId: {{ $cotizacion->id ?? 'null' }},
                params: {
                    cotizacionNo: '',
                    fechaEmision: '',
                    validaHasta: '',
                    moneda: '',
                    terminosPago: '',
                    diasValidez: 30,
                    estado: 'pendiente',
                    notas: '',
                    incluirIGV: true,
                    cliente: {
                        id: '',
                        nombre: '',
                        email: '',
                        telefono: '',
                        empresa: '',
                        direccion: ''
                    },
                    ticket: {
                        id: null,
                        tecnico_nombre: '',
                        tienda_nombre: '',
                        fecha_llegada: ''
                    },
                    ot: '',
                    serie: '',
                    fecha_creacion: '',
                    fecha_modificacion: '',
                    usuario_creacion: ''
                },
                items: [],
                mostrarNGR: false,
                incluirIGV: true,
                subtotal: 0,
                igv: 0,
                total: 0,

                async init() {
                    await this.cargarCotizacion();
                    this.inicializarSelect2();
                    this.inicializarFlatpickr();
                    this.actualizarTotales();
                },

                async cargarCotizacion() {
                    try {
                        const response = await fetch(`/api/cotizaciones/${this.cotizacionId}`);
                        const data = await response.json();

                        if (data.success) {
                            this.params = {
                                ...this.params,
                                ...data.cotizacion
                            };
                            this.items = data.items || [];
                            this.mostrarNGR = data.cotizacion.ticket_id !== null;
                            this.incluirIGV = data.cotizacion.incluir_igv;

                            // Cargar información del ticket si existe
                            if (data.cotizacion.ticket_id) {
                                await this.cargarInformacionTicket(data.cotizacion.ticket_id);
                            }
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error('Error cargando cotización:', error);
                        toastr.error('Error al cargar la cotización');
                    }
                },

                async cargarInformacionTicket(ticketId) {
                    try {
                        const response = await fetch(`/api/tickets/${ticketId}`);
                        const data = await response.json();

                        if (data.success) {
                            this.params.ticket = {
                                ...this.params.ticket,
                                ...data.ticket
                            };
                        }
                    } catch (error) {
                        console.error('Error cargando información del ticket:', error);
                    }
                },

                inicializarSelect2() {
                    // Inicializar Select2 para moneda
                    $('#monedaSelect').select2({
                        placeholder: 'Seleccionar moneda',
                        allowClear: true
                    }).on('change', (e) => {
                        this.params.moneda = e.target.value;
                    });

                    // Inicializar Select2 para términos de pago
                    $('#terminosPagoSelect').select2({
                        placeholder: 'Seleccionar términos de pago',
                        allowClear: true
                    }).on('change', (e) => {
                        this.params.terminosPago = e.target.value;
                    });

                    // Cargar opciones para los selects
                    this.cargarOpcionesSelect2();
                },

                async cargarOpcionesSelect2() {
                    // Cargar monedas
                    try {
                        const response = await fetch('/api/monedas');
                        const data = await response.json();

                        if (data.success) {
                            $('#monedaSelect').empty().append('<option value="">Seleccionar moneda</option>');
                            data.monedas.forEach(moneda => {
                                $('#monedaSelect').append(
                                    `<option value="${moneda.codigo}">${moneda.nombre} (${moneda.simbolo})</option>`
                                    );
                            });
                            $('#monedaSelect').val(this.params.moneda).trigger('change');
                        }
                    } catch (error) {
                        console.error('Error cargando monedas:', error);
                    }

                    // Cargar términos de pago
                    try {
                        const response = await fetch('/api/terminos-pago');
                        const data = await response.json();

                        if (data.success) {
                            $('#terminosPagoSelect').empty().append('<option value="">Seleccionar términos</option>');
                            data.terminos.forEach(termino => {
                                $('#terminosPagoSelect').append(
                                    `<option value="${termino.id}">${termino.nombre}</option>`);
                            });
                            $('#terminosPagoSelect').val(this.params.terminosPago).trigger('change');
                        }
                    } catch (error) {
                        console.error('Error cargando términos de pago:', error);
                    }
                },

                inicializarFlatpickr() {
                    flatpickr('#fechaEmision', {
                        locale: 'es',
                        dateFormat: 'd/m/Y',
                        defaultDate: this.params.fechaEmision
                    });

                    flatpickr('#validaHasta', {
                        locale: 'es',
                        dateFormat: 'd/m/Y',
                        defaultDate: this.params.validaHasta
                    });
                },

                addItem() {
                    const newItem = {
                        id: Date.now() + Math.random(),
                        articulo_id: '',
                        descripcion: '',
                        cantidad: 1,
                        precio: 0,
                        codigo_repuesto: ''
                    };
                    this.items.push(newItem);

                    this.$nextTick(() => {
                        this.inicializarSelect2Articulo(newItem.id);
                    });
                },

                removeItem(item) {
                    this.items = this.items.filter(i => i.id !== item.id);
                    this.actualizarTotales();
                },

                inicializarSelect2Articulo(itemId) {
                    $(`#articulo-select-${itemId}`).select2({
                        placeholder: 'Buscar artículo...',
                        ajax: {
                            url: '/api/articulos',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(articulo => ({
                                        id: articulo.id,
                                        text: articulo.nombre,
                                        precio: articulo.precio,
                                        codigo_repuesto: articulo.codigo_repuesto
                                    }))
                                };
                            }
                        }
                    }).on('change', (e) => {
                        const selectedOption = $(e.target).find('option:selected');
                        const item = this.items.find(i => i.id === itemId);
                        if (item && selectedOption.length > 0) {
                            item.articulo_id = selectedOption.val();
                            item.descripcion = selectedOption.text();
                            item.precio = parseFloat(selectedOption.data('precio')) || 0;
                            item.codigo_repuesto = selectedOption.data('codigo_repuesto') || '';
                            this.actualizarTotales();
                        }
                    });
                },

                actualizarTotales() {
                    this.subtotal = this.items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
                    this.igv = this.incluirIGV ? this.subtotal * 0.18 : 0;
                    this.total = this.subtotal + this.igv;
                },

                toggleIGV() {
                    this.incluirIGV = !this.incluirIGV;
                    this.actualizarTotales();
                },

                obtenerSimboloMoneda() {
                    const monedas = {
                        'PEN': 'S/',
                        'USD': '$',
                        'EUR': '€'
                    };
                    return monedas[this.params.moneda] || '';
                },

                formatearFecha(fecha) {
                    if (!fecha) return 'N/A';
                    return new Date(fecha).toLocaleDateString('es-ES');
                },

                async actualizarCotizacion() {
                    try {
                        const cotizacionData = {
                            ...this.params,
                            incluir_igv: this.incluirIGV,
                            items: this.items
                        };

                        const response = await fetch(`/api/cotizaciones/${this.cotizacionId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(cotizacionData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            toastr.success('Cotización actualizada correctamente');
                            // Redirigir al index o mostrar mensaje de éxito
                            setTimeout(() => {
                                window.location.href = '/cotizaciones';
                            }, 1500);
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (error) {
                        console.error('Error actualizando cotización:', error);
                        toastr.error('Error al actualizar la cotización');
                    }
                },

                vistaPrevia() {
                    // Lógica para vista previa
                    toastr.info('Función de vista previa en desarrollo');
                },

                generarPDF() {
                    // Lógica para generar PDF
                    toastr.info('Generando PDF...');
                },

                enviarEmail() {
                    // Lógica para enviar email
                    toastr.info('Enviando por email...');
                },

                cancelarEdicion() {
                    if (confirm('¿Está seguro de cancelar la edición? Los cambios no guardados se perderán.')) {
                        window.location.href = '/cotizaciones';
                    }
                }
            }
        }
    </script>
</x-layout.default>
