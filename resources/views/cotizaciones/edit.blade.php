<x-layout.default title="Editar Cotización - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/cotizaciones.css') }}">

    <div class="fade-in">
        <div class="grid xl:grid-cols-4 gap-8">
            <!-- Panel Principal -->
            <div class="xl:col-span-3">
                <div class="card mb-8 overflow-hidden">
                    <!-- Header de la Card -->
                    <div class="card-header bg-warning">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div>
                                    <h2 class="text-2xl font-bold text-black">Editar Cotización</h2>
                                    <p class="text-yellow-100 mt-1">Modifique los datos de la cotización existente</p>
                                </div>
                            </div>

                            <!-- Información de Estado -->
                            <div class="flex items-center space-x-4">
                                <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i> Modo Edición
                                </span>
                                <span class="text-yellow-100 text-sm font-medium"
                                    id="cotizacionNo">{{ $cotizacion->numero_cotizacion }}</span>
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
                                            value="{{ $cotizacion->numero_cotizacion }}" readonly>
                                        <p class="text-xs text-gray-500 mt-1">Número de cotización no editable</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="form-label">Fecha de Emisión</label>
                                            <div class="date-input-wrapper">
                                                <input type="text" class="form-control flatpickr-input"
                                                    id="fechaEmision" value="{{ $cotizacion->fecha_emision }}"
                                                    data-date-format="d/m/Y">
                                                <i class="fas fa-calendar-alt icon"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Válida hasta</label>
                                            <div class="date-input-wrapper">
                                                <input type="text" class="form-control flatpickr-input"
                                                    id="validaHasta" value="{{ $cotizacion->valida_hasta }}"
                                                    data-date-format="d/m/Y">
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
                                                <div class="font-semibold text-gray-900">
                                                    {{ $cotizacion->cliente->nombre }}</div>
                                                <div class="text-sm text-gray-600 mt-1">
                                                    {{ $cotizacion->cliente->empresa }}</div>
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
                                            value="{{ $cotizacion->cliente->nombre }}" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control bg-gray-50"
                                            value="{{ $cotizacion->cliente->email }}" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            value="{{ $cotizacion->cliente->telefono }}" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Empresa</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            value="{{ $cotizacion->cliente->empresa }}" readonly>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control bg-gray-50"
                                            value="{{ $cotizacion->cliente->direccion }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección NGR -->
                        <div id="seccionNGR" style="{{ $cotizacion->idTickets ? '' : 'display: none;' }}"
                            class="mb-8">
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
                                            {{ $cotizacion->idTickets ? 'checked' : '' }}>
                                        <label for="ngrCheckboxEdit"
                                            class="ml-2 text-sm font-medium text-gray-900 cursor-pointer flex items-center">
                                            NGR
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Ticket -->
                            @if ($cotizacion->idTickets)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6 bg-blue-50 rounded-xl border border-blue-200 mb-4">
                                    <div>
                                        <label class="form-label">OT</label>
                                        <input type="text" class="form-control bg-gray-100"
                                            value="{{ $cotizacion->ot }}" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Técnico</label>
                                        <input type="text" class="form-control bg-gray-100"
                                            value="{{ $cotizacion->ticket->tecnico->Nombre ?? '' }}" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Tienda</label>
                                        <input type="text" class="form-control bg-gray-100"
                                            value="{{ $cotizacion->ticket->tienda->nombre ?? '' }}" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label">Serie</label>
                                        <input type="text" class="form-control" placeholder="Ej: SER-001-2024"
                                            value="{{ $cotizacion->serie }}" id="serieInput">
                                    </div>
                                </div>
                            @endif
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
                                <button type="button" class="btn btn-primary" id="btnAddItem">
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
                                    <tbody id="itemsTableBody">
                                        @if ($cotizacion->productos->count() > 0)
                                            @foreach ($cotizacion->productos as $index => $producto)
                                                <tr class="group hover:bg-gray-50 transition-all duration-200">
                                                    <td class="text-center text-gray-600 font-medium py-4 px-4">
                                                        {{ $index + 1 }}</td>
                                                    <td class="py-4 px-4">
                                                        <input type="text" class="form-control w-full"
                                                            value="{{ $producto->descripcion }}"
                                                            name="items[{{ $index }}][descripcion]">
                                                        <div class="mt-1">
                                                            @if ($producto->codigo_repuesto)
                                                                <small class="text-gray-500">Código:
                                                                    {{ $producto->codigo_repuesto }}</small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-4 px-4">
                                                        <input type="number"
                                                            class="form-control text-center border-gray-200 group-hover:border-gray-300 transition-colors w-full"
                                                            value="{{ $producto->cantidad }}"
                                                            name="items[{{ $index }}][cantidad]"
                                                            min="0" onchange="actualizarTotales()">
                                                    </td>
                                                    <td class="py-4 px-4">
                                                        <div class="relative">
                                                            <span
                                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm"
                                                                id="simboloMoneda">S/</span>
                                                            <input type="number"
                                                                class="form-control text-right border-gray-200 group-hover:border-gray-300 transition-colors pl-8 w-full"
                                                                value="{{ $producto->precio_unitario }}"
                                                                name="items[{{ $index }}][precio_unitario]"
                                                                min="0" step="0.01"
                                                                onchange="actualizarTotales()">
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="text-right font-semibold text-gray-900 text-lg py-4 px-4">
                                                        <span id="simboloMonedaTotal">S/</span>
                                                        <span>{{ number_format($producto->precio_unitario * $producto->cantidad, 2) }}</span>
                                                    </td>
                                                    <td class="text-center py-4 px-4">
                                                        <button type="button" onclick="removeItem(this)"
                                                            class="text-gray-400 hover:text-red-500 transition-all duration-200 transform hover:scale-110 p-2 rounded-lg hover:bg-red-50"
                                                            title="Eliminar item">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
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
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totales -->
                            <div class="total-card mt-8">
                                <div class="flex justify-between items-center mb-4 p-4 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700 font-medium">Configuración de Impuestos:</span>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-600"
                                            id="textoIGV">{{ $cotizacion->incluir_igv ? 'CON IGV' : 'SIN IGV' }}</span>
                                        <button type="button" onclick="toggleIGV()"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $cotizacion->incluir_igv ? 'bg-blue-600' : 'bg-gray-200' }}">
                                            <span class="sr-only">Toggle IGV</span>
                                            <span aria-hidden="true"
                                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $cotizacion->incluir_igv ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <div class="w-80 space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-700 font-medium">Subtotal:</span>
                                            <span class="text-lg font-semibold text-gray-900">
                                                <span id="simboloMonedaSubtotal">S/</span>
                                                <span
                                                    id="subtotal">{{ number_format($cotizacion->subtotal, 2) }}</span>
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center" id="divIGV"
                                            style="{{ $cotizacion->incluir_igv ? '' : 'display: none;' }}">
                                            <span class="text-gray-700 font-medium">IGV (18%):</span>
                                            <span class="text-lg font-semibold text-gray-900">
                                                <span id="simboloMonedaIGV">S/</span>
                                                <span id="igv">{{ number_format($cotizacion->igv, 2) }}</span>
                                            </span>
                                        </div>

                                        <hr class="border-gray-200">
                                        <div class="flex justify-between items-center text-xl pt-2">
                                            <span class="font-bold text-gray-900">TOTAL:</span>
                                            <span class="font-bold text-primary text-2xl">
                                                <span id="simboloMonedaTotalFinal">S/</span>
                                                <span id="total">{{ number_format($cotizacion->total, 2) }}</span>
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
                            <textarea class="form-control h-32" name="terminos_condiciones"
                                placeholder="Incluya aquí los términos de pago, condiciones de entrega, garantías, y cualquier otra información relevante...">{{ $cotizacion->terminos_condiciones }}</textarea>
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
                                    id="textoTipoIGV">{{ $cotizacion->incluir_igv ? 'CON IGV' : 'SIN IGV' }}</span>
                                <button type="button" onclick="toggleIGV()"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $cotizacion->incluir_igv ? 'bg-blue-600' : 'bg-gray-200' }}">
                                    <span class="sr-only">Toggle IGV</span>
                                    <span aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $cotizacion->incluir_igv ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Moneda</label>
                            <select class="form-control" name="idMonedas" id="monedaSelect">
                                @foreach ($monedas as $moneda)
                                    <option value="{{ $moneda->id }}"
                                        {{ $cotizacion->idMonedas == $moneda->id ? 'selected' : '' }}>
                                        {{ $moneda->nombre }} ({{ $moneda->simbolo }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Términos de Pago</label>
                            <select class="form-control" name="terminos_pago" id="terminosPagoSelect">
                                @foreach ($terminosPago as $termino)
                                    <option value="{{ $termino->id }}"
                                        {{ $cotizacion->terminos_pago == $termino->id ? 'selected' : '' }}>
                                        {{ $termino->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Validez (días)</label>
                            <input type="number" class="form-control" name="dias_validez"
                                value="{{ $cotizacion->dias_validez }}" min="1" max="90">
                        </div>

                        <!-- Estado de la Cotización -->
                        <div>
                            <label class="form-label">Estado</label>
                            <select class="form-control" name="estado_cotizacion">
                                <option value="pendiente"
                                    {{ $cotizacion->estado_cotizacion == 'pendiente' ? 'selected' : '' }}>Pendiente
                                </option>
                                <option value="enviada"
                                    {{ $cotizacion->estado_cotizacion == 'enviada' ? 'selected' : '' }}>Enviada
                                </option>
                                <option value="aprobada"
                                    {{ $cotizacion->estado_cotizacion == 'aprobada' ? 'selected' : '' }}>Aprobada
                                </option>
                                <option value="rechazada"
                                    {{ $cotizacion->estado_cotizacion == 'rechazada' ? 'selected' : '' }}>Rechazada
                                </option>
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
                        <form id="formEditarCotizacion" method="POST"
                            action="{{ route('cotizaciones.update', $cotizacion->idCotizaciones) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success w-full justify-center">
                                <i class="fas fa-save mr-3"></i> Actualizar Cotización
                            </button>
                        </form>

                        <button type="button" class="btn btn-primary w-full justify-center" onclick="vistaPrevia()">
                            <i class="fas fa-eye mr-3"></i> Vista Previa
                        </button>

                        <button type="button" class="btn btn-danger w-full justify-center" onclick="generarPDF()">
                            <i class="fas fa-file-pdf mr-3"></i> Generar PDF
                        </button>

                        <button type="button" class="btn btn-warning w-full justify-center" onclick="enviarEmail()">
                            <i class="fas fa-paper-plane mr-3"></i> Enviar por Email
                        </button>

                        <a href="{{ route('cotizaciones.index') }}"
                            class="btn btn-outline-danger w-full justify-center">
                            <i class="fas fa-times mr-3"></i> Cancelar
                        </a>
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
                            <span
                                class="font-medium text-gray-900">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Modificado:</span>
                            <span
                                class="font-medium text-gray-900">{{ $cotizacion->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        let incluirIGV = {{ $cotizacion->incluir_igv ? 'true' : 'false' }};
        let itemCounter = {{ $cotizacion->productos->count() }};

        document.addEventListener('DOMContentLoaded', function() {
            inicializarSelect2();
            inicializarFlatpickr();

            // Toggle NGR
            document.getElementById('ngrCheckboxEdit').addEventListener('change', function() {
                document.getElementById('seccionNGR').style.display = this.checked ? 'block' : 'none';
            });

            // Agregar item
            document.getElementById('btnAddItem').addEventListener('click', addItem);
        });

        function inicializarSelect2() {
            $('#monedaSelect').select2({
                placeholder: 'Seleccionar moneda',
                allowClear: true
            });

            $('#terminosPagoSelect').select2({
                placeholder: 'Seleccionar términos de pago',
                allowClear: true
            });
        }

        function inicializarFlatpickr() {
            flatpickr.localize(flatpickr.l10ns.es);
            flatpickr('#fechaEmision', {
                locale: 'es',
                dateFormat: 'd/m/Y'
            });
            flatpickr('#validaHasta', {
                locale: 'es',
                dateFormat: 'd/m/Y'
            });
        }

        function addItem() {
            const tbody = document.getElementById('itemsTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'group hover:bg-gray-50 transition-all duration-200';
            newRow.innerHTML = `
                <td class="text-center text-gray-600 font-medium py-4 px-4">${itemCounter + 1}</td>
                <td class="py-4 px-4">
                    <input type="text" class="form-control w-full" name="items[${itemCounter}][descripcion]" placeholder="Descripción del producto/servicio">
                </td>
                <td class="py-4 px-4">
                    <input type="number" class="form-control text-center border-gray-200 group-hover:border-gray-300 transition-colors w-full"
                        name="items[${itemCounter}][cantidad]" value="1" min="0" onchange="actualizarTotales()">
                </td>
                <td class="py-4 px-4">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">S/</span>
                        <input type="number" class="form-control text-right border-gray-200 group-hover:border-gray-300 transition-colors pl-8 w-full"
                            name="items[${itemCounter}][precio_unitario]" value="0" min="0" step="0.01" onchange="actualizarTotales()">
                    </div>
                </td>
                <td class="text-right font-semibold text-gray-900 text-lg py-4 px-4">
                    <span>S/</span>
                    <span>0.00</span>
                </td>
                <td class="text-center py-4 px-4">
                    <button type="button" onclick="removeItem(this)" class="text-gray-400 hover:text-red-500 transition-all duration-200 transform hover:scale-110 p-2 rounded-lg hover:bg-red-50" title="Eliminar item">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
            itemCounter++;
        }

        function removeItem(button) {
            const row = button.closest('tr');
            row.remove();
            actualizarTotales();
        }

        function toggleIGV() {
            incluirIGV = !incluirIGV;
            const texto = incluirIGV ? 'CON IGV' : 'SIN IGV';
            const bgColor = incluirIGV ? 'bg-blue-600' : 'bg-gray-200';
            const translate = incluirIGV ? 'translate-x-5' : 'translate-x-0';

            document.getElementById('textoIGV').textContent = texto;
            document.getElementById('textoTipoIGV').textContent = texto;

            const toggle = document.querySelector('button[onclick="toggleIGV()"]');
            toggle.className = toggle.className.replace(/bg-(blue-600|gray-200)/, bgColor);

            const span = toggle.querySelector('span:last-child');
            span.className = span.className.replace(/translate-x-(5|0)/, translate);

            document.getElementById('divIGV').style.display = incluirIGV ? 'flex' : 'none';
            actualizarTotales();
        }

        function actualizarTotales() {
            // Esta función calcularía los totales basándose en los inputs
            // Por simplicidad, aquí solo mostraría un mensaje
            console.log('Actualizando totales...');
        }

        function vistaPrevia() {
            toastr.info('Función de vista previa en desarrollo');
        }

        function generarPDF() {
            window.open('{{ route('cotizaciones.pdf', $cotizacion->idCotizaciones) }}', '_blank');
        }

        function enviarEmail() {
            toastr.info('Función de email en desarrollo');
        }
    </script>
</x-layout.default>
