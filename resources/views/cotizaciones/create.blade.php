<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/cotizaciones.css') }}">


    <div x-data="cotizacionAdd" class="fade-in">
        <div class="grid xl:grid-cols-4 gap-8">
            <!-- Panel Principal -->
            <div class="xl:col-span-3">
                <div class="card mb-8 overflow-hidden">
                    <!-- Header de la Card -->
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div>
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
                                        <input type="text" class="form-control" placeholder="COT-2024-001"
                                            x-model="params.cotizacionNo">
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
                                    <label class="form-label">Seleccionar Cliente</label>
                                    <select id="clienteSelect" class="form-control w-full">
                                        <option value="">Buscar o seleccionar cliente...</option>
                                    </select>
                                </div>

                                <!-- Campos que se llenarán automáticamente -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div>
                                        <label class="form-label">Nombre o Razón Social</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.nombre" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control bg-gray-50"
                                            placeholder="Seleccione un cliente primero" x-model="params.cliente.email"
                                            readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.telefono" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Empresa</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.empresa" readonly>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            placeholder="Seleccione un cliente primero"
                                            x-model="params.cliente.direccion" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección NGR (se muestra cuando el checkbox está activado) -->
                        <div x-show="mostrarNGR" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-8">

                            <!-- Campos NGR: Técnico, Tienda y Serie -->
                            <div
                                class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-blue-50 rounded-xl border border-blue-200">
                                <!-- Técnico - Select2 -->
                                <div>
                                    <label class="form-label">Técnico <span class="text-red-500">*</span></label>
                                    <select id="tecnicoSelect" class="form-control w-full">
                                        <option value="">Seleccionar técnico...</option>
                                        <option value="1">Juan Pérez</option>
                                        <option value="2">María García</option>
                                        <option value="3">Carlos López</option>
                                        <option value="4">Ana Rodríguez</option>
                                        <option value="5">Luis Martínez</option>
                                        <option value="6">Elena Castro</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Seleccione el técnico asignado</p>
                                </div>

                                <!-- Tienda - Select2 -->
                                <div>
                                    <label class="form-label">Tienda <span class="text-red-500">*</span></label>
                                    <select id="tiendaSelect" class="form-control w-full">
                                        <option value="">Seleccionar tienda...</option>
                                        <option value="1">Tienda Principal - Centro</option>
                                        <option value="2">Tienda Norte - Miraflores</option>
                                        <option value="3">Tienda Sur - San Borja</option>
                                        <option value="4">Tienda Este - La Molina</option>
                                        <option value="5">Tienda Oeste - Callao</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Seleccione la tienda de origen</p>
                                </div>

                                <!-- Serie - Input normal -->
                                <div>
                                    <label class="form-label">Serie</label>
                                    <input type="text" class="form-control" placeholder="Ej: SER-001-2024"
                                        x-model="params.serie">
                                    <p class="text-xs text-gray-500 mt-1">Ingrese el número de serie si aplica</p>
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
                                                    <input type="text"
                                                        class="form-control border-gray-200 group-hover:border-gray-300 transition-colors w-full"
                                                        placeholder="Descripción detallada del producto o servicio"
                                                        x-model="item.descripcion">
                                                </td>
                                                <td class="py-4 px-4">
                                                    <input type="number"
                                                        class="form-control text-center border-gray-200 group-hover:border-gray-300 transition-colors w-full"
                                                        placeholder="0" x-model="item.cantidad" min="0">
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">$</span>
                                                        <input type="number"
                                                            class="form-control text-right border-gray-200 group-hover:border-gray-300 transition-colors pl-8 w-full"
                                                            placeholder="0.00" x-model="item.precio" min="0"
                                                            step="0.01">
                                                    </div>
                                                </td>
                                                <td class="text-right font-semibold text-gray-900 text-lg py-4 px-4">
                                                    $ <span x-text="(item.precio * item.cantidad).toFixed(2)"></span>
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
                                <div class="flex justify-end">
                                    <div class="w-80 space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-700 font-medium">Subtotal:</span>
                                            <span class="text-lg font-semibold text-gray-900">$ <span
                                                    x-text="subtotal.toFixed(2)"></span></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-700 font-medium">IGV (18%):</span>
                                            <span class="text-lg font-semibold text-gray-900">$ <span
                                                    x-text="igv.toFixed(2)"></span></span>
                                        </div>
                                        <hr class="border-gray-200">
                                        <div class="flex justify-between items-center text-xl pt-2">
                                            <span class="font-bold text-gray-900">TOTAL:</span>
                                            <span class="font-bold text-primary text-2xl">$ <span
                                                    x-text="total.toFixed(2)"></span></span>
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
                            <label class="form-label">Moneda</label>
                            <select class="form-control" x-model="params.moneda">
                                <option value="USD">Dólares Americanos (USD)</option>
                                <option value="PEN">Soles Peruanos (PEN)</option>
                                <option value="EUR">Euros (EUR)</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Términos de Pago</label>
                            <select class="form-control" x-model="params.terminosPago">
                                <option value="contado">Al contado</option>
                                <option value="30dias">30 días neto</option>
                                <option value="60dias">60 días neto</option>
                                <option value="90dias">90 días neto</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Validez (días)</label>
                            <input type="number" class="form-control" x-model="params.diasValidez" min="1"
                                max="90">
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
                            @click="guardarCotizacion()">
                            <i class="fas fa-save mr-3"></i> Guardar Cotización
                        </button>

                        <button type="button" class="btn btn-primary w-full justify-center" @click="vistaPrevia()">
                            <i class="fas fa-eye mr-3"></i> Vista Previa
                        </button>

                        <button type="button"
                            class="btn btn-danger w-full justify-center hover:bg-gray-800 transition-colors"
                            @click="generarPDF()">
                            <i class="fas fa-file-pdf mr-3"></i> Generar PDF
                        </button>

                        <button type="button"
                            class="btn btn-warning w-full justify-center hover:from-orange-600 hover:to-red-600 transition-all"
                            @click="enviarEmail()">
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
</x-layout.default>
