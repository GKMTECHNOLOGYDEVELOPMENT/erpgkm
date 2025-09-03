<x-layout.default>
    <!-- Font Awesome (iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

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

            <a href="#" class="btn btn-outline-primary">
                <i class="fa-solid fa-arrow-left"> </i> REGRESAR ATRÁS
            </a>
        </div>

        <!-- Barra de acciones -->
        <div class="rounded-lg shadow-sm p-4 mb-8">
            <div class="flex flex-wrap items-center justify-center gap-4 md:gap-10 text-gray-700">
                <a href="#" class="btn btn-primary">
                    <div class="p-2 bg-sky-100 rounded-full group-hover:bg-sky-200 transition-colors">
                        <i class="fa-solid fa-bag-shopping text-sky-600"></i>
                    </div>
                    <span class="font-medium text-sm">NUEVA COMPRA</span>
                </a>
                <a href="#" class="btn btn-warning">
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
                        <h2 class="text-xl font-bold text-gray-800">Información de la compra</h2>
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
                        <h2 class="text-xl font-bold text-gray-800">Información del proveedor</h2>
                    </div>

                    <div class="space-y-4">
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
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-800">
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100">#
                            </th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100">
                                DESCRIPCIÓN</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-indigo-100 w-40">
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

                    <tbody class="divide-y divide-gray-100">
                        @php
                            $subtotal = 0;
                            $igv = $compra->igv;
                            $total = $compra->total;
                            $monedaSimbolo = $compra->moneda->simbolo ?? 'S/.';
                        @endphp

                        @foreach ($compra->detalles as $index => $detalle)
                            @php
                                $productSubtotal = $detalle->cantidad * $detalle->precio;
                                $subtotal += $productSubtotal;
                            @endphp
                            <tr class="text-gray-700 hover:bg-indigo-50/50 transition-all duration-200">
                                <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
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
                                        {{ $detalle->cantidad }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-green-600">
                                    {{ $monedaSimbolo }}{{ number_format($detalle->precio, 2) }}</td>
                                <td class="px-6 py-4 font-semibold text-blue-600">
                                    {{ $monedaSimbolo }}{{ number_format($productSubtotal, 2) }}</td>
                                <td class="px-6 py-4">
                                    <button class="btn btn-dark items-center gap-2 devolver-btn" 
                                            title="Registrar devolución"
                                            data-id="{{ $detalle->idDetalleCompra }}"
                                            data-producto="{{ $detalle->producto->nombre ?? 'Producto' }}"
                                            data-max="{{ $detalle->cantidad }}"
                                            data-precio="{{ $detalle->precio }}"
                                            data-subtotal="{{ $productSubtotal }}">
                                        <i class="fa-solid fa-arrow-rotate-left text-sm"></i>
                                        <span class="text-xs font-medium">Devolver</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <!-- Totales -->
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
                                {{ $compra->sujetoporcentaje ?? 18 }}%</td>
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
                </table>
            </div>
        </div>


         <!-- Modal para devoluciones -->
        <div id="modalDevolucion" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="bg-blue-100 p-3 rounded-t-lg">
                        <h3 class="text-lg font-medium text-blue-800">Registrar Devolución</h3>
                    </div>
                    
                    <form id="devolucionForm" class="px-4 py-3">
                        @csrf
                        <input type="hidden" name="idDetalleCompra" id="idDetalleCompra">
                        <input type="hidden" name="idCompra" value="{{ $compra->idCompra }}">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="producto">
                                Producto
                            </label>
                            <input type="text" id="producto" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="cantidad">
                                Cantidad a devolver (Máximo: <span id="maxCantidad"></span>)
                            </label>
                            <input type="number" name="cantidad" id="cantidad" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="motivo">
                                Motivo de la devolución
                            </label>
                            <textarea name="motivo" id="motivo" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t">
                            <button type="button" id="cancelBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Registrar Devolución
                            </button>
                        </div>
                    </form>
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
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">FECHA</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">PRODUCTO</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">CANTIDAD</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">PRECIO</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">TOTAL</th>
                            <th class="px-6 py-4 text-left font-bold uppercase text-xs border-b border-rose-100">VENDEDOR</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100" id="devoluciones-body">
    @if($devoluciones && count($devoluciones) > 0)
        @foreach($devoluciones as $index => $devolucion)
            <tr>
                <td class="px-6 py-4">{{ $index + 1 }}</td>
                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">{{ $devolucion->producto_nombre ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $devolucion->cantidad }}</td>
                <td class="px-6 py-4">{{ $monedaSimbolo }}{{ number_format($devolucion->precio_unitario, 2) }}</td>
                <td class="px-6 py-4">{{ $monedaSimbolo }}{{ number_format($devolucion->total_devolucion, 2) }}</td>
                <td class="px-6 py-4">{{ $devolucion->usuario_nombre ?? 'N/A' }} {{ $devolucion->usuario_apellido ?? '' }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                <i class="fa-solid fa-inbox text-4xl mb-3"></i>
                <p class="text-sm font-medium">No hay devoluciones registradas</p>
            </td>
        </tr>
    @endif
</tbody>
                </table>
            </div>
        </section>
    </div>

    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


   <script>
        function abrirTicket(url) {
            const width = 320;
            const height = 600;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            
            window.open(url, 'Ticket', `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`);
        }

        $(document).ready(function() {
            // Variables globales
            let monedaSimbolo = '{{ $compra->moneda->simbolo ?? "S/." }}';
            let porcentajeIGV = {{ $compra->sujetoporcentaje ?? 18 }};
            let currentDetalleId = null;
            
            // Abrir modal de devolución
            $('.devolver-btn').click(function() {
                currentDetalleId = $(this).data('id');
                const producto = $(this).data('producto');
                const maxCantidad = $(this).data('max');
                const precio = $(this).data('precio');
                
                $('#idDetalleCompra').val(currentDetalleId);
                $('#producto').val(producto);
                $('#maxCantidad').text(maxCantidad);
                $('#cantidad').attr('max', maxCantidad);
                $('#cantidad').val(1);
                $('#motivo').val('');
                
                $('#modalDevolucion').removeClass('hidden').addClass('flex');
            });
            
            // Cerrar modal
            $('#cancelBtn').click(function() {
                $('#modalDevolucion').removeClass('flex').addClass('hidden');
            });
            
            // Enviar formulario de devolución
            $('#devolucionForm').submit(function(e) {
                e.preventDefault();
                
                const cantidad = parseInt($('#cantidad').val());
                const maxCantidad = parseInt($('#cantidad').attr('max'));
                
                if (cantidad > maxCantidad) {
                    alert('La cantidad a devolver no puede ser mayor a la cantidad comprada.');
                    return;
                }
                
                if (cantidad <= 0) {
                    alert('La cantidad a devolver debe ser mayor a cero.');
                    return;
                }
                
                // Mostrar loading
                $('button[type="submit"]').html('<i class="fa fa-spinner fa-spin"></i> Procesando...').prop('disabled', true);
                
                // Enviar datos por AJAX
                $.ajax({
                    url: '{{ route("compras.devolucion") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
    if (response.success) {
        // Recargar la página para actualizar todos los datos
        location.reload();
        
        // O si prefieres no recargar, actualizar la interfaz manualmente:
        /*
        // Actualizar la cantidad en la tabla
        const nuevaCantidad = maxCantidad - cantidad;
        $(`button[data-id="${currentDetalleId}"]`)
            .closest('tr')
            .find('td:eq(2) span') // Ajusta este selector según tu estructura
            .text(nuevaCantidad);
        
        // Actualizar los totales
        $('#subtotal-total').text(monedaSimbolo + response.nuevos_totales.subtotal.toFixed(2));
        $('#igv-total').text(monedaSimbolo + response.nuevos_totales.igv.toFixed(2));
        $('#total-total').text(monedaSimbolo + response.nuevos_totales.total.toFixed(2));
        
        // Actualizar tabla de devoluciones
        $('#devoluciones-body').prepend(`
            <tr>
                <td class="px-6 py-4">${$('#devoluciones-body tr').length + 1}</td>
                <td class="px-6 py-4">${response.fecha_devolucion}</td>
                <td class="px-6 py-4">${response.producto_nombre}</td>
                <td class="px-6 py-4">${cantidad}</td>
                <td class="px-6 py-4">${monedaSimbolo}${response.precio_unitario}</td>
                <td class="px-6 py-4">${monedaSimbolo}${response.total_devolucion}</td>
                <td class="px-6 py-4">${response.usuario_nombre}</td>
            </tr>
        `);
        
        // Actualizar el máximo para futuras devoluciones
        $(`button[data-id="${currentDetalleId}"]`).data('max', nuevaCantidad);
        
        if (nuevaCantidad === 0) {
            $(`button[data-id="${currentDetalleId}"]`).prop('disabled', true).addClass('opacity-50');
        }
        */
        
        // Cerrar modal
        $('#modalDevolucion').removeClass('flex').addClass('hidden');
    } else {
        alert('Error: ' + response.message);
    }
},
                    error: function(xhr) {
                        alert('Error al procesar la devolución. Intente nuevamente.');
                    },
                    complete: function() {
                        $('button[type="submit"]').html('Registrar Devolución').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</x-layout.default>
