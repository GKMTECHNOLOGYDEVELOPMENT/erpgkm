<x-layout.default>
    <!-- Font Awesome (iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


    <div class="container mx-auto px-4 py-8">

        <!-- Encabezado + botón volver -->
        <div class="flex flex-col md:flex-row items-start justify-between gap-4 mb-8">
            <div class="panel flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-sky-100 rounded-lg">
                        <i class="fa-solid fa-bag-shopping text-xl text-sky-600"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">DETALLES DE COMPRA</h1>
                </div>
                <p class="text-gray-600 text-sm md:text-base max-w-3xl">
                    En el módulo COMPRAS usted podrá registrar compras de productos ya sea nuevos o ya registrados en
                    sistema. También puede ver la lista de todas las compras realizadas, buscar compras y ver
                    información
                    más detallada de cada compra.
                </p>
            </div>
        </div>

        <!-- Barra de acciones -->
        <div class="rounded-lg shadow-sm p-4 mb-8">
            <div class="flex flex-wrap items-center justify-center gap-4 md:gap-10 text-gray-700">
                <a href="{{ route('compras.create') }}" class="btn btn-primary">
                    <div class="p-2 bg-sky-100 rounded-full group-hover:bg-sky-200 transition-colors">
                        <i class="fa-solid fa-bag-shopping text-sky-600"></i>
                    </div>
                    <span class="font-medium text-sm">NUEVA COMPRA</span>
                </a>

                <a href="{{ route('compras.index') }}" class="btn btn-warning">
                    <div class="p-2 bg-sky-100 rounded-full group-hover:bg-sky-200 transition-colors">
                        <i class="fa-solid fa-clipboard-list text-sky-600"></i>
                    </div>
                    <span class="font-medium text-sm">COMPRAS REALIZADAS</span>
                </a>
                <a href="#" class="btn btn-secondary">
                    <div class="p-2 bg-sky-100 rounded-full group-hover:bg-sky-200 transition-colors">
                        <i class="fa-solid fa-magnifying-glass text-sky-600"></i>
                    </div>
                    <span class="font-medium text-sm">BUSCAR COMPRA</span>
                </a>
            </div>
        </div>

        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Tarjeta 1: Información de la compra -->
            <div class="badge badge-outline-primary rounded-2xl shadow-lg p-6 relative overflow-hidden">
                <!-- Elemento decorativo -->
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-blue-200 rounded-full opacity-20"></div>
                <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-indigo-300 rounded-full opacity-20"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-3 bg bg-primary rounded-xl shadow-md">
                            <i class="fa-solid fa-receipt text-white text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">INFORMACIÓN DE LA COMPRA</h2>
                    </div>

                    <div class="space-y-4">
                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-blue-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar text-blue-500"></i>
                                <span class="text-gray-600">Fecha:</span>
                            </div>
                            <span class="font-semibold bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                {{ \Carbon\Carbon::parse($compra->fechaEmision)->format('d-m-Y') }}
                            </span>
                        </div>

                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-blue-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-user text-blue-500"></i>
                                <span class="text-gray-600">Registrada por:</span>
                            </div>
                            <span class="font-semibold bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->usuario->Nombre }} {{ $compra->usuario->apellidoPaterno }}
                            </span>
                        </div>

                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-blue-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-hashtag text-blue-500"></i>
                                <span class="text-gray-600">Serie/Número:</span>
                            </div>
                            <span class="font-semibold bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->serie }}-{{ $compra->nro }}
                            </span>
                        </div>

                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-blue-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-money-bill-wave text-blue-500"></i>
                                <span class="text-gray-600">Total:</span>
                            </div>
                            <span class="font-semibold bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->moneda->simbolo ?? 'S/.' }}{{ number_format($compra->total, 2) }}
                            </span>
                        </div>

                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-blue-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-coins text-blue-500"></i>
                                <span class="text-gray-600">Moneda:</span>
                            </div>
                            <span class="font-semibold bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->moneda->nombre ?? 'SOLES' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Información del proveedor -->
            <div class="badge badge-outline-secondary rounded-2xl shadow-lg p-6 relative overflow-hidden">
                <!-- Elemento decorativo -->
                <div class="absolute -top-4 -left-4 w-20 h-20 bg-purple-200 rounded-full opacity-20"></div>
                <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-pink-300 rounded-full opacity-20"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-3 bg bg-secondary rounded-xl shadow-md">
                            <i class="fa-solid fa-truck text-white text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">INFORMACIÓN DEL PROVEEDOR</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Proveedor -->
                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-purple-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-building text-purple-500"></i>
                                <span class="text-gray-600">Proveedor:</span>
                            </div>
                            <span class="font-semibold bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->proveedor->nombre ?? 'N/A' }}
                            </span>
                        </div>

                        <!-- Ubicación -->
                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-purple-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-purple-500"></i>
                                <span class="text-gray-600">Ubicación:</span>
                            </div>
                            <span class="font-semibold bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->proveedor->direccion ?? 'N/A' }}
                            </span>
                        </div>

                        <!-- Teléfono -->
                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-purple-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-phone text-purple-500"></i>
                                <span class="text-gray-600">Teléfono:</span>
                            </div>
                            <span class="font-semibold bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->proveedor->telefono ?? 'N/A' }}
                            </span>
                        </div>

                        <!-- Tipo de Documento -->
                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-purple-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-id-card text-purple-500"></i>
                                <span class="text-gray-600">Tipo Documento:</span>
                            </div>
                            <span class="font-semibold bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->proveedor->tipodocumento->nombre ?? '---' }}
                            </span>
                        </div>

                        <!-- Número de Documento -->
                        <div
                            class="flex justify-between items-center py-2.5 px-4 bg-white rounded-xl shadow-sm border border-purple-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-hashtag text-purple-500"></i>
                                <span class="text-gray-600">N° Documento:</span>
                            </div>
                            <span class="font-semibold bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                {{ $compra->proveedor->numeroDocumento ?? '---' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="rounded-2xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            <!-- Encabezado de la tabla -->
            <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-700">
                <h2 class="text-xl font-bol flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    Productos comprados
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table id="tabla-productos" class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-800">
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100">#
                            </th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100">
                                DESCRIPCIÓN</th>
                            <th
                                class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100 w-40">
                                CANTIDAD</th>
                            <th
                                class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100 w-48">
                                PRECIO</th>
                            <th
                                class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100 w-48">
                                SUBTOTAL</th>
                            <th
                                class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100 w-40">
                                ACCIÓN</th>
                        </tr>
                    </thead>

                    @php
                        $subtotal = 0;
                        $monedaSimbolo = $compra->moneda->simbolo ?? 'S/.';
                        $productosValidos = collect($compra->detalles)->filter(function ($detalle) use ($compra) {
                            $devueltas = \App\Models\DevolucionCompra::where('idCompra', $compra->idCompra)
                                ->where('idProducto', $detalle->idProducto)
                                ->sum('cantidad');

                            $cantNeta = max(0, (int) $detalle->cantidad - (int) $devueltas);
                            return $cantNeta > 0;
                        });
                    @endphp

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($productosValidos as $index => $detalle)
                            @php
                                $devueltas = \App\Models\DevolucionCompra::where('idCompra', $compra->idCompra)
                                    ->where('idProducto', $detalle->idProducto)
                                    ->sum('cantidad');

                                $cantNeta = max(0, (int) $detalle->cantidad - (int) $devueltas);
                                $productSubtotal = $cantNeta * (float) $detalle->precio;
                                $subtotal += $productSubtotal;
                            @endphp

                            <tr class="text-gray-700 hover:bg-indigo-50/50 transition-all duration-200">
                                <td class="px-6 py-4 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-box text-indigo-600"></i>
                                        </div>
                                        <span
                                            class="font-medium">{{ $detalle->producto->nombre ?? 'Producto no disponible' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center justify-center bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-medium">
                                        {{ $cantNeta }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-green-600">
                                    {{ $monedaSimbolo }}{{ number_format($detalle->precio, 2) }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-blue-600">
                                    {{ $monedaSimbolo }}{{ number_format($productSubtotal, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <button
                                        class="devolver-btn text-emerald-600 hover:text-emerald-700 p-1 rounded-md hover:bg-emerald-50"
                                        title="Registrar devolución"
                                        data-id-detalle="{{ $detalle->idDetalleCompra }}"
                                        data-id-compra="{{ $compra->idCompra }}"
                                        data-producto="{{ $detalle->producto->nombre ?? $detalle->articulo->nombre }}"
                                        data-max="{{ $cantNeta }}"
                                        data-precio="{{ number_format($detalle->precio, 2, '.', '') }}">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-4xl mb-3"></i>
                                    <p class="text-sm font-medium">No hay productos registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($productosValidos->count() > 0)
                        @php
                            $igv = ((float) ($compra->sujetoporcentaje ?? 0) / 100) * $subtotal;
                            $total = $subtotal + $igv;
                        @endphp

                        <tfoot class="bg-gradient-to-r from-gray-50 to-indigo-50">
                            <tr>
                                <td class="px-6 py-4" colspan="3"></td>
                                <td class="px-6 py-4 font-bold text-indigo-700 text-right">SUBTOTAL</td>
                                <td class="px-6 py-4 font-bold text-blue-600">
                                    {{ $monedaSimbolo }}{{ number_format($subtotal, 2) }}</td>
                                <td class="px-6 py-4"></td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4" colspan="3"></td>
                                <td class="px-6 py-4 font-bold text-indigo-700 text-right">IGV
                                    {{ $compra->sujetoporcentaje ?? 0 }}%</td>
                                <td class="px-6 py-4 font-bold text-blue-600">
                                    {{ $monedaSimbolo }}{{ number_format($igv, 2) }}</td>
                                <td class="px-6 py-4"></td>
                            </tr>
                            <tr class="bg-gradient-to-r from-indigo-100 to-purple-100 border-t-2 border-indigo-200">
                                <td class="px-6 py-4" colspan="3"></td>
                                <td class="px-6 py-4 font-bold text-indigo-900 text-lg text-right">TOTAL</td>
                                <td class="px-6 py-4 font-bold text-indigo-900 text-lg">
                                    {{ $monedaSimbolo }}{{ number_format($total, 2) }}</td>
                                <td class="px-6 py-4"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>



        <!-- Modal para devoluciones -->
        <div x-data="devolucionModal(false)" x-on:open-devolucion.window="openWith($event.detail)"
            x-on:close-devolucion.window="open=false" class="mb-5">

            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                <div class="flex items-start justify-center min-h-screen px-4" @click.self="open=false">
                    <div x-show="open" x-transition x-transition.duration.300
                        class="panel border-0 p-0 rounded-lg overflow-visible my-8 w-full max-w-lg bg-white">
                        <div class="flex bg-[#fbfbfb] items-center justify-between px-5 py-3">
                            <div class="font-bold text-lg">Registrar Devolución</div>
                            <button type="button" class="text-gray-500 hover:text-gray-700" @click="open=false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="p-5">
                            <form id="devolucionForm" method="POST" action="{{ route('compras.devolucion') }}">
                                @csrf
                                <input id="idDetalleCompra" type="hidden" name="idDetalleCompra"
                                    :value="form.idDetalleCompra">
                                <input id="idCompra" type="hidden" name="idCompra" :value="form.idCompra">

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                                    <input id="producto" type="text"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-50 text-gray-700 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent shadow-sm"
                                        x-model="form.producto" readonly>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Cantidad a devolver
                                        <span class="text-gray-500">(Máximo: <span
                                                x-text="form.maxCantidad"></span>)</span>
                                    </label>
                                    <input id="cantidad" name="cantidad" type="number"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-50 text-gray-700 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent shadow-sm"
                                        min="1" :max="form.maxCantidad" x-model.number="form.cantidad"
                                        required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la
                                        devolución</label>
                                    <textarea id="motivo" name="motivo" rows="3"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-50 text-gray-700 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent shadow-sm"
                                        x-model="form.motivo" required></textarea>
                                </div>

                                <div class="flex justify-end gap-3 mt-6">
                                    <button id="cancelBtn" type="button" class="btn btn-outline-danger"
                                        @click="open=false">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">
                                        Registrar Devolución
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Botones imprimir -->
        <div class="flex flex-wrap items-center justify-center gap-4 mb-12">
            <button class="btn btn-info inline-flex items-center gap-2">
                <i class="fa-solid fa-file-invoice"></i>
                <span>IMPRIMIR FACTURA</span>
            </button>
            <!-- Con esto: -->
            <button type="button" class="btn btn-success items-center gap-2"
                onclick="abrirTicket('{{ route('compras.ticket', $compra->idCompra) }}')">
                <i class="fa-solid fa-receipt"></i> IMPRIMIR TICKET
            </button>
        </div>


        <!-- DEVOLUCIONES REALIZADAS (actualizado para mostrar devoluciones) -->
        <section class="rounded-2xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            <!-- Encabezado -->
            <div class="px-6 py-5 bg-gradient-to-r from-rose-500 to-pink-600">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </div>
                    Devoluciones realizadas
                </h2>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-pink-50 to-rose-50 text-rose-800">
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">#</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">FECHA
                            </th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">
                                PRODUCTO</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">
                                CANTIDAD</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">PRECIO
                            </th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">TOTAL
                            </th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">
                                VENDEDOR</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100" id="devoluciones-body">
                        @forelse ($devoluciones as $index => $devolucion)
                            <tr>
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">{{ $devolucion->producto_nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $devolucion->cantidad }}</td>
                                <td class="px-6 py-4">
                                    {{ $monedaSimbolo }}{{ number_format($devolucion->precio_unitario, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $monedaSimbolo }}{{ number_format($devolucion->total_devolucion, 2) }}
                                </td>
                                <td class="px-6 py-4">{{ $devolucion->usuario_nombre ?? 'N/A' }}
                                    {{ $devolucion->usuario_apellido ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-4xl mb-3"></i>
                                    <p class="text-sm font-medium">No hay devoluciones registradas</p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </section>

        <!-- Botones imprimir -->
        <div class="flex flex-wrap items-center justify-center gap-4 mb-12">
            <button class="btn btn-info inline-flex items-center gap-2">
                <i class="fa-solid fa-file-invoice"></i>
                <span>IMPRIMIR FACTURA</span>
            </button>
            <!-- Con esto: -->
            <button type="button" class="btn btn-warning items-center gap-2"
                onclick="abrirTicket('{{ route('compras.ticket.devolucion', $compra->idCompra) }}')">
                <i class="fa-solid fa-rotate-left"></i> TICKET DEVOLUCIÓN
            </button>

        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        toastr.options = {
            positionClass: "toast-top-right",
            closeButton: true,
            progressBar: true,
            timeOut: 3000,
            preventDuplicates: true
        };
        const toast = {
            ok: (m) => toastr.success(m),
            err: (m) => toastr.error(m),
            warn: (m) => toastr.warning(m),
            info: (m) => toastr.info(m),
        };

        // Helper de borde rojo en inputs inválidos
        function markInvalid($el, msg) {
            $el.addClass('ring-2 ring-red-400 focus:ring-red-400').one('input', function() {
                $(this).removeClass('ring-2 ring-red-400 focus:ring-red-400');
            });
            if (msg) toast.warn(msg);
        }

        function abrirTicket(url) {
            const width = 320,
                height = 600;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            window.open(url, 'Ticket',
                `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`);
        }

        document.addEventListener("alpine:init", () => {
            Alpine.data("devolucionModal", (initialOpen = false) => ({
                open: initialOpen,
                form: {
                    idDetalleCompra: null,
                    idCompra: null,
                    producto: '',
                    maxCantidad: 1,
                    cantidad: 1,
                    motivo: ''
                },
                openWith({
                    idDetalleCompra,
                    idCompra,
                    producto,
                    maxCantidad
                }) {
                    this.form.idDetalleCompra = idDetalleCompra ?? null;
                    this.form.idCompra = idCompra ?? null;
                    this.form.producto = producto ?? '';
                    this.form.maxCantidad = Number(maxCantidad ?? 1);
                    this.form.cantidad = 1;
                    this.form.motivo = '';
                    this.open = true;
                },
            }));
        });

        $(document).ready(function() {
            // Vars del backend
            let monedaSimbolo = '{{ $compra->moneda->simbolo ?? 'S/.' }}';
            let porcentajeIGV = {{ $compra->sujetoporcentaje ?? 18 }};
            let currentDetalleId = null;

            // Disparar modal (manteniendo tus botones .devolver-btn)
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.devolver-btn');
                if (!btn) return;
                currentDetalleId = btn.dataset.idDetalle;

                window.dispatchEvent(new CustomEvent('open-devolucion', {
                    detail: {
                        idDetalleCompra: btn.dataset.idDetalle,
                        idCompra: btn.dataset.idCompra,
                        producto: btn.dataset.producto,
                        maxCantidad: btn.dataset.max
                    }
                }));
            });

            // Cerrar modal (botón cancelar ya cierra por Alpine). Aquí nada extra.

            // Enviar formulario de devolución por AJAX
            $('#devolucionForm').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const $submit = $form.find('button[type="submit"]');
                const $cantidad = $('#cantidad');
                const $motivo = $('#motivo');

                const cantidad = parseInt($cantidad.val(), 10);
                const maxCantidad = parseInt($cantidad.attr('max'), 10);
                const motivo = ($motivo.val() || '').trim();

                // Validaciones front
                if (isNaN(cantidad) || cantidad <= 0) {
                    return markInvalid($cantidad, 'La cantidad debe ser mayor a cero.');
                }
                if (cantidad > maxCantidad) {
                    return markInvalid($cantidad, 'La cantidad no puede superar la comprada.');
                }
                if (motivo.length < 3) {
                    return markInvalid($motivo, 'Indica un motivo (mín. 3 caracteres).');
                }

                $submit.html('<i class="fa fa-spinner fa-spin"></i> Procesando...').prop('disabled', true);

                $.ajax({
                    url: '{{ route('compras.devolucion') }}',
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        if (!response || !response.success) {
                            toast.err(response?.message ??
                                'No se pudo registrar la devolución.');
                            return;
                        }

                        // === Actualizar UI ===
                        const btnSel = $(`button[data-id-detalle="${currentDetalleId}"]`);
                        const $row = btnSel.closest('tr');
                        const maxOld = parseInt(btnSel.data('max'), 10) || 0;
                        const nuevaCantidad = Math.max(0, maxOld - cantidad);
                        const precioUnit = parseFloat(btnSel.data('precio')) || 0;

                        if (nuevaCantidad === 0) {
                            // quitar fila y renumerar
                            $row.fadeOut(150, function() {
                                $(this).remove();

                                // Renumerar dentro del mismo tbody (usa id si existe)
                                const $tbody = $('#tabla-productos tbody').length ?
                                    $('#tabla-productos tbody') :
                                    btnSel.closest('table').find('tbody').first();

                                $tbody.children('tr').each(function(i) {
                                    $(this).find('td').first().text(i +
                                        1); // primera col = #
                                });
                            });

                            // opcional: deshabilitar botón (ya no existe la fila, pero por seguridad)
                            btnSel.prop('disabled', true).addClass(
                                'opacity-50 cursor-not-allowed');

                        } else {
                            // actualizar cantidad y subtotal
                            $row.find('[data-col="cantidad"]').text(nuevaCantidad);

                            const nuevoSubtotalTxt = (window.monedaSimbolo ||
                                    '{{ $compra->moneda->simbolo ?? 'S/.' }}') +
                                (nuevaCantidad * precioUnit).toFixed(2);

                            const $subCell = $row.find('[data-col="subtotal"]');
                            if ($subCell.length) {
                                $subCell.text(nuevoSubtotalTxt);
                            } else {
                                // fallback: 5ta columna (índice 4) es SUBTOTAL
                                $row.find('td').eq(4).text(nuevoSubtotalTxt);
                            }

                            btnSel.data('max', nuevaCantidad);
                        }

                        // Actualizar totales del tfoot (desde backend)
                        if (response.nuevos_totales) {
                            const m = (window.monedaSimbolo ||
                                '{{ $compra->moneda->simbolo ?? 'S/.' }}');
                            $('#subtotal-total').text(m + Number(response.nuevos_totales
                                .subtotal).toFixed(2));
                            $('#igv-total').text(m + Number(response.nuevos_totales.igv)
                                .toFixed(2));
                            $('#total-total').text(m + Number(response.nuevos_totales.total)
                                .toFixed(2));
                        }

                        // Agregar fila en "Devoluciones realizadas"
                        if (response.fecha_devolucion) {
                            const filas = $('#devoluciones-body tr').length + 1;
                            const m = (window.monedaSimbolo ||
                                '{{ $compra->moneda->simbolo ?? 'S/.' }}');
                            $('#devoluciones-body').prepend(`
                    <tr>
                        <td class="px-6 py-4">${filas}</td>
                        <td class="px-6 py-4">${response.fecha_devolucion}</td>
                        <td class="px-6 py-4">${response.producto_nombre ?? $('#producto').val()}</td>
                        <td class="px-6 py-4">${cantidad}</td>
                        <td class="px-6 py-4">${m}${Number(response.precio_unitario ?? 0).toFixed(2)}</td>
                        <td class="px-6 py-4">${m}${Number(response.total_devolucion ?? 0).toFixed(2)}</td>
                        <td class="px-6 py-4">${response.usuario_nombre ?? ''} ${response.usuario_apellido ?? ''}</td>
                    </tr>
                `);
                        }

                        // Cerrar modal y notificar
                        window.dispatchEvent(new CustomEvent('close-devolucion'));
                        toast.ok('Devolución registrada correctamente.');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            Object.keys(errs).forEach(k => toast.err(errs[k][0]));
                        } else {
                            toast.err('Error al procesar la devolución. Intente nuevamente.');
                        }
                    },
                    complete: function() {
                        $submit.html('Registrar Devolución').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</x-layout.default>
