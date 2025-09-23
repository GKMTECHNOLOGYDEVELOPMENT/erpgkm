<x-layout.default>
    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Solicitudes de Ingreso por Compra</h1>
                <p class="text-gray-600 mt-1">Gestiona las solicitudes agrupadas por compra</p>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="panel p-4 rounded-lg shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-wrap gap-2">
                    <button
                        class="filtro-btn active px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                        data-estado="">
                        Todas las Compras
                    </button>
                    <button
                        class="filtro-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-600 hover:text-white transition"
                        data-estado="pendiente">
                        Con Pendientes
                    </button>
                    <button
                        class="filtro-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-600 hover:text-white transition"
                        data-estado="recibido">
                        Parcialmente Recibidas
                    </button>
                    <button
                        class="filtro-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-600 hover:text-white transition"
                        data-estado="completado">
                        Completadas
                    </button>
                </div>
                <div class="relative w-full md:w-64">
                    <input type="text" id="busqueda" placeholder="Buscar por código o proveedor..."
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Grid de Cards de Compras -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="contenedor-compras">
            <!-- Las compras se cargarán aquí -->
        </div>
    </div>

    <!-- Modal para detalle de compra -->
    <div id="modal-detalle-compra"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="panel rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center bg-gradient-to-r from-gray-50 to-gray-100">
                <!-- Título -->
                <h3 id="modal-titulo" class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-primary"></i>
                    Detalle de Compra
                </h3>

                <!-- Botón cerrar -->
                <button id="cerrar-modal"
                    class="text-danger hover:text-danger transition-colors duration-200 rounded-full p-1 hover:bg-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-auto max-h-[70vh] space-y-6">
                <!-- Datos generales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Código Compra -->
                    <div
                        class="p-4 rounded-lg border border-gray-200 bg-primary-light shadow-sm flex items-start gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-white">
                            <i class="fa-solid fa-barcode"></i>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">
                                Código Compra
                            </label>
                            <p class="font-semibold text-gray-800" id="detalle-codigo"></p>
                        </div>
                    </div>

                    <!-- Proveedor -->
                    <div
                        class="p-4 rounded-lg border border-gray-200 bg-secondary-light shadow-sm flex items-start gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-secondary text-white">
                            <i class="fa-solid fa-truck-field"></i>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">
                                Proveedor
                            </label>
                            <p class="font-semibold text-gray-800" id="detalle-proveedor"></p>
                        </div>
                    </div>

                    <!-- Fecha Compra -->
                    <div class="p-4 rounded-lg border border-gray-200 bg-info-light shadow-sm flex items-start gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-info text-white">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">
                                Fecha Compra
                            </label>
                            <p class="font-semibold text-gray-800" id="detalle-fecha"></p>
                        </div>
                    </div>
                </div>


                <!-- Tabla de productos -->
                <div class="panel border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50 rounded-t-xl">
                        <h4 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                            <i class="fa-solid fa-box-open text-blue-600"></i>
                            Productos de la Compra
                        </h4>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 border-collapse">
                            <thead class="bg-gray-100 text-black text-xs uppercase tracking-wide">
                                <tr>
                                    <th class="px-4 py-3 text-center">Artículo</th>
                                    <th class="px-4 py-3 text-center">Cantidad</th>
                                    <th class="px-4 py-3 text-center">Estado</th>
                                    <th class="px-4 py-3 text-center">Ubicación</th>
                                    <th class="px-4 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-productos" class="divide-y divide-gray-200">
                                <!-- Los productos se cargarán aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal para editar producto individual - CORREGIDO -->
    <div id="modal-editar-producto"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="panel rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Editar Estado y Ubicaciones</h3>
            </div>
            <!-- Contenido -->
            <div class="p-6 max-h-[70vh] overflow-y-auto space-y-5">
                <form id="form-editar-producto" class="space-y-5">
                    <input type="hidden" id="solicitud-id">
                    <input type="hidden" id="articulo-id">

                    <!-- Artículo -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                            <i class="fa-solid fa-box text-blue-500"></i> Artículo
                        </label>
                        <p class="font-semibold text-gray-800" id="producto-nombre"></p>
                    </div>

                    <!-- Cantidad total -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                            <i class="fa-solid fa-sort-numeric-up text-purple-500"></i> Cantidad Total
                        </label>
                        <p class="font-semibold text-gray-800" id="producto-cantidad"></p>
                    </div>

                    <!-- Estado -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-flag text-amber-500"></i>
                            Estado
                        </label>
                        <select id="producto-estado"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pendiente">Pendiente</option>
                            <option value="recibido">Recibido</option>
                            <option value="ubicado">Ubicado</option>
                        </select>
                    </div>

                    <!-- Ubicaciones -->
                    <div id="ubicacion-section" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-green-500"></i>
                                Ubicaciones del Artículo
                            </label>
                            <button type="button" id="agregar-ubicacion-btn"
                                class="px-3 py-1.5 btn btn-success text-white rounded-md text-sm flex items-center gap-2 transition-colors">
                                <i class="fa-solid fa-plus"></i>
                                Agregar
                            </button>
                        </div>

                        <!-- Contenedor dinámico -->
                        <div id="contenedor-ubicaciones" class="space-y-3">
                            <!-- ubicaciones dinámicas -->
                        </div>

                        <!-- Total distribuido -->
                        <div id="total-distribuido"
                            class="mt-4 p-3 panel rounded-lg text-sm border border-gray-200 shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-blue-500"></i>
                            <span class="font-medium">Total distribuido:</span>
                            <span id="cantidad-distribuida" class="font-semibold text-gray-800">0</span> /
                            <span id="cantidad-total" class="font-semibold text-gray-800">0</span> unidades
                        </div>
                    </div>


                    <!-- Observaciones -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-note-sticky text-indigo-500"></i>
                            Observaciones
                        </label>
                        <textarea id="producto-observaciones"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm
               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
               placeholder-gray-400 resize-y transition"
                            rows="4" placeholder="Escribe aquí tus observaciones..."></textarea>
                    </div>

                </form>
            </div>
            <!-- Footer -->
            <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                <button id="cancelar-editar-btn" class="btn btn-danger flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Cancelar</span>
                </button>

                <button id="guardar-editar-btn" class="btn btn-primary flex items-center gap-2">
                    <i class="fa-solid fa-save"></i>
                    <span>Guardar</span>
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Pasar datos de PHP a JavaScript -->
    <script>
        const comprasConSolicitudes = @json($comprasConSolicitudes);
        const ubicaciones = @json($ubicaciones);
    </script>

    <script>
        let todasLasSolicitudes = [];

        // Función para cargar las solicitudes de una compra específica
        async function cargarSolicitudesCompra(compraId) {
            try {
                const response = await fetch(`/solicitudes-ingreso/por-compra/${compraId}`);
                const data = await response.json();
                return data.solicitudes;
            } catch (error) {
                console.error('Error cargando solicitudes:', error);
                return [];
            }
        }

        // Función para renderizar las compras
        function renderizarCompras(filtroEstado = '', busqueda = '') {
            const contenedor = document.getElementById('contenedor-compras');
            let comprasFiltradas = comprasConSolicitudes;

            // Filtros por estado
            if (filtroEstado === 'pendiente') {
                comprasFiltradas = comprasFiltradas.filter(c => c.pendientes > 0);
            } else if (filtroEstado === 'recibido') {
                comprasFiltradas = comprasFiltradas.filter(c => c.recibidos > 0 && c.pendientes > 0);
            } else if (filtroEstado === 'completado') {
                comprasFiltradas = comprasFiltradas.filter(c => c.pendientes === 0);
            }

            // Búsqueda por código o proveedor
            if (busqueda) {
                const termino = busqueda.toLowerCase();
                comprasFiltradas = comprasFiltradas.filter(c =>
                    c.codigocompra.toLowerCase().includes(termino) ||
                    (c.proveedor && c.proveedor.toLowerCase().includes(termino))
                );
            }

            // Si no hay resultados
            if (comprasFiltradas.length === 0) {
                contenedor.innerHTML = `
            <div class="col-span-full py-12 text-center panel rounded-xl shadow-md border border-gray-200">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-800">No hay compras encontradas</h3>
                <p class="mt-1 text-gray-500">Prueba con otros filtros o términos de búsqueda.</p>
            </div>
        `;
                return;
            }

            // Renderizar compras
            contenedor.innerHTML = comprasFiltradas.map(compra => `
        <div class="panel max-w-lg w-full mx-auto rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
            
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-blue-600"></i>
                        Código: ${compra.codigocompra}
                    </h3>

                    <p class="text-sm text-gray-600 flex items-center gap-2 mt-1">
                        <i class="fa-solid fa-truck text-gray-400"></i>
                        Proveedor: ${compra.proveedor || 'N/A'}
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium 
                    ${compra.pendientes === 0 ? 'bg-success text-white' : 
                      compra.recibidos > 0 ? 'bg-warning text-white' : 
                      'bg-danger text-white'}">
                    ${compra.pendientes === 0 ? 'Completada' : compra.recibidos > 0 ? 'Parcial' : 'Pendiente'}
                </span>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 space-y-5">

                <!-- Fecha y Total - Mejorado -->
                <div class="grid grid-cols-2 gap-5">
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100">
                        <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                            <i class="fa-regular fa-calendar text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Fecha de Compra</p>
                            <p class="text-sm font-bold text-gray-800">${formatFecha(compra.fecha_compra)}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-100">
                        <div class="p-2 rounded-lg bg-warning text-white">
                            <i class="fa-solid fa-coins text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-warning uppercase tracking-wide mb-1">Total</p>
                            <p class="text-sm font-bold text-gray-800">S/ ${compra.total}</p>
                        </div>
                    </div>
                </div>

                <!-- Estado de Productos - Mejorado -->
               <div class="p-4 rounded-xl bg-gray border border-gray-200">
                    <p class="text-xs font-semibold text-dark uppercase tracking-wide mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-chart-column text-dark"></i>
                        Estado de Productos
                    </p>

                    <div class="flex justify-between items-center gap-4">
                        <span class="flex-1 px-3 py-2 text-xs rounded-lg bg-danger-light text-red-700 flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-clock text-red-500"></i>
                            <span class="font-semibold">Pendientes: ${compra.pendientes}</span>
                        </span>

                        <span class="flex-1 px-3 py-2 text-xs rounded-lg bg-warning-light text-warning flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-box-open text-amber-500"></i>
                            <span class="font-semibold">Recibidos: ${compra.recibidos}</span>
                        </span>

                        <span class="flex-1 px-3 py-2 text-xs rounded-lg bg-success-light text-success flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-warehouse text-success"></i>
                            <span class="font-semibold">Ubicados: ${compra.ubicados}</span>
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-purple-500/5 to-indigo-500/10 border-l-4 border-primary">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-lg panel shadow-sm border border-primary">
                            <i class="fa-solid fa-cubes text-primary text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Productos</p>
                            <p class="text-lg font-bold text-gray-900">${compra.total_solicitudes} <span class="text-sm font-medium text-gray-600">productos</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="w-8 h-8 rounded-full bg-primary-light flex items-center justify-center">
                            <span class="text-xs font-bold text-primary">${compra.total_solicitudes}</span>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Footer Compacto -->
            <div class="px-6 py-3 bg-gray-50/50 border-t border-gray-200/40">
                <button class="ver-detalle-btn w-full flex items-center justify-center gap-2 px-4 py-2.5 btn btn-outline-primary text-sm font-medium rounded-lg group" data-id="${compra.idCompra}">
                    
                    <!-- Icono inicial -->
                    <i class="fa-solid fa-eye text-primary text-sm"></i>
                    
                    <span>Ver detalles de la compra</span>
                    
                    <!-- Icono animado al hover -->
                    <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </button>
            </div>
        </div>
    `).join('');

            agregarEventListenersCompras();
        }


        // Función para formatear fechas
        function formatFecha(fecha) {
            if (!fecha) return 'N/A';
            return new Date(fecha).toLocaleDateString('es-ES');
        }

        // Función para agregar event listeners a las compras
        function agregarEventListenersCompras() {
            document.querySelectorAll('.ver-detalle-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const compraId = this.dataset.id;
                    await abrirModalDetalleCompra(compraId);
                });
            });
        }

        // Función para abrir modal de detalle de compra
        async function abrirModalDetalleCompra(compraId) {
            const compra = comprasConSolicitudes.find(c => c.idCompra == compraId);
            if (compra) {
                // Cargar las solicitudes específicas de esta compra
                const solicitudes = await cargarSolicitudesCompra(compraId);
                todasLasSolicitudes = solicitudes;

                // Actualizar el modal
                document.getElementById('modal-titulo').textContent = `DETALLES DE LA COMPRA:`;
                document.getElementById('detalle-codigo').textContent = compra.codigocompra;
                document.getElementById('detalle-proveedor').textContent = compra.proveedor || 'N/A';
                document.getElementById('detalle-fecha').textContent = formatFecha(compra.fecha_compra);

                // Renderizar la tabla de productos
                renderizarTablaProductos(solicitudes);

                document.getElementById('modal-detalle-compra').classList.remove('hidden');
                document.getElementById('modal-detalle-compra').classList.add('flex');
            }
        }

        // Función para renderizar la tabla de productos - SIMPLIFICADA
        function renderizarTablaProductos(solicitudes) {
            const tbody = document.getElementById('tabla-productos');

            if (solicitudes.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No hay productos para esta compra
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = solicitudes.map(solicitud => `
            <tr class="border-b hover:bg-gray-50">
                <!-- Artículo -->
                <td class="px-4 py-3 text-center">
                    <div>
                        <p class="font-medium">${solicitud.articulo || 'N/A'}</p>
                        <p class="text-sm text-gray-500">${solicitud.codigo_solicitud}</p>
                    </div>
                </td>

                <!-- Cantidad -->
                <td class="px-4 py-3 text-center">
                    ${solicitud.cantidad} unidades
                </td>

                <!-- Estado -->
                <td class="px-4 py-3 text-center">
                    <span class="badge ${solicitud.estado === 'pendiente' 
                        ? 'badge bg-warning' 
                        : solicitud.estado === 'recibido' 
                        ? 'badge bg-secondary' 
                        : 'badge bg-success'}">
                        ${solicitud.estado 
                            ? solicitud.estado.charAt(0).toUpperCase() + solicitud.estado.slice(1) 
                            : 'N/A'}
                    </span>
                </td>

                <!-- Ubicaciones -->
                <td class="px-4 py-3 text-center">
                    ${solicitud.ubicaciones && solicitud.ubicaciones.length > 0 
                        ? solicitud.ubicaciones.map(u => 
                            `<div class="text-xs">${u.cantidad} en ${u.ubicacion_nombre}</div>`
                        ).join('') 
                        : '<span class="text-red-500">Sin ubicación</span>'
                    }
                </td>

                <!-- Acciones -->
                <td class="px-4 py-3 text-center">
                    <button class="editar-producto-btn px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm" 
                            data-id="${solicitud.id}">
                        Editar
                    </button>
                </td>
            </tr>
        `).join('');


            // Agregar event listeners a los botones de editar
            agregarEventListenersTablaProductos();
        }

        // Función para abrir modal de edición - CORREGIDA
        function abrirModalEditarProducto(solicitudId) {
            console.log('Abriendo modal para solicitud ID:', solicitudId);

            const solicitud = todasLasSolicitudes.find(s => s.id == solicitudId);
            console.log('Solicitud encontrada:', solicitud);

            if (!solicitud) {
                console.error('No se encontró la solicitud con ID:', solicitudId);
                return;
            }

            // Obtener referencias a los elementos del modal
            const solicitudIdInput = document.getElementById('solicitud-id');
            const articuloIdInput = document.getElementById('articulo-id');
            const productoNombre = document.getElementById('producto-nombre');
            const productoIdText = document.getElementById('producto-id-text');
            const productoCantidad = document.getElementById('producto-cantidad');
            const productoEstado = document.getElementById('producto-estado');
            const productoObservaciones = document.getElementById('producto-observaciones');
            const contenedorUbicaciones = document.getElementById('contenedor-ubicaciones');
            const totalElem = document.getElementById('cantidad-total');
            const distribuidaElem = document.getElementById('cantidad-distribuida');

            // Validación básica
            if (!solicitudIdInput || !articuloIdInput || !productoNombre || !productoCantidad || !productoEstado) {
                console.error('Faltan elementos del modal');
                return;
            }

            // Asignar valores básicos
            solicitudIdInput.value = solicitudId;
            articuloIdInput.value = solicitud.articulo_id || '';
            productoNombre.textContent = solicitud.articulo || 'N/A';

            // Si existe el texto de ID del producto
            if (productoIdText) {
                productoIdText.textContent = `ID: ${solicitud.articulo_id || 'N/A'}`;
            }

            productoCantidad.textContent = `${solicitud.cantidad} unidades`;
            productoEstado.value = solicitud.estado || 'pendiente';
            productoObservaciones.value = solicitud.observaciones || '';

            // Configurar contadores de distribución
            if (totalElem && distribuidaElem) {
                totalElem.textContent = solicitud.cantidad || 0;
                distribuidaElem.textContent = 0;
            } else {
                console.warn("No se encontraron los elementos cantidad-total o cantidad-distribuida");
            }

            // Limpiar y cargar ubicaciones
            if (contenedorUbicaciones) {
                contenedorUbicaciones.innerHTML = '';

                if (solicitud.ubicaciones && solicitud.ubicaciones.length > 0) {
                    solicitud.ubicaciones.forEach(ubicacion => {
                        agregarFilaUbicacion(ubicacion.idUbicacion, ubicacion.cantidad);
                    });
                } else {
                    agregarFilaUbicacion();
                }
            }

            // Actualizar total y mostrar/ocultar sección de ubicaciones
            actualizarTotalDistribuido();
            toggleUbicacionField();

            // Mostrar modal
            const modal = document.getElementById('modal-editar-producto');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }



        // Función para agregar fila de ubicación - CORREGIDA
        function agregarFilaUbicacion(ubicacionId = '', cantidad = '') {
            const contenedor = document.getElementById('contenedor-ubicaciones');
            if (!contenedor) {
                console.error('No se encontró el contenedor de ubicaciones');
                return;
            }

            const index = contenedor.children.length;
            const cantidadValor = cantidad || '';

            const filaHTML = `
        <div class="fila-ubicacion grid grid-cols-12 gap-2 items-end border border-gray-200 rounded p-3 bg-white">
            <div class="col-span-7">
                <label class="block text-xs font-medium text-gray-700 mb-1">Ubicación</label>
                <select class="select-ubicacion w-full px-2 py-1 border border-gray-300 rounded text-sm">
                    <option value="">Seleccionar ubicación</option>
                    ${ubicaciones.map(u => 
                        `<option value="${u.idUbicacion}" ${u.idUbicacion == ubicacionId ? 'selected' : ''}>${u.nombre}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="col-span-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Cantidad</label>
                <input type="number" class="input-cantidad w-full px-2 py-1 border border-gray-300 rounded text-sm" 
                       min="1" value="${cantidadValor}" placeholder="Cantidad">
            </div>
            <div class="col-span-1">
                <button type="button" class="quitar-ubicacion px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                    ×
                </button>
            </div>
        </div>
    `;

            contenedor.insertAdjacentHTML('beforeend', filaHTML);

            // Agregar event listeners a la nueva fila
            const nuevaFila = contenedor.lastElementChild;
            const inputCantidad = nuevaFila.querySelector('.input-cantidad');
            const btnQuitar = nuevaFila.querySelector('.quitar-ubicacion');

            if (inputCantidad) {
                inputCantidad.addEventListener('input', actualizarTotalDistribuido);
            }

            if (btnQuitar) {
                btnQuitar.addEventListener('click', function() {
                    if (contenedor.children.length > 1) {
                        this.closest('.fila-ubicacion').remove();
                        actualizarTotalDistribuido();
                    } else {
                        alert('Debe haber al menos una ubicación');
                    }
                });
            }
        }

        // Función para mostrar/ocultar campo de ubicación
        function toggleUbicacionField() {
            const estado = document.getElementById('producto-estado').value;
            const ubicacionContainer = document.getElementById('ubicacion-container');

            if (estado === 'ubicado') {
                ubicacionContainer.style.display = 'block';
            } else {
                ubicacionContainer.style.display = 'none';
            }
        }

        // Función para actualizar el total distribuido - CORREGIDA
        function actualizarTotalDistribuido() {
            const inputsCantidad = document.querySelectorAll('.input-cantidad');
            let totalDistribuido = 0;

            inputsCantidad.forEach(input => {
                totalDistribuido += parseInt(input.value) || 0;
            });

            const cantidadDistribuidaElem = document.getElementById('cantidad-distribuida');
            if (cantidadDistribuidaElem) {
                cantidadDistribuidaElem.textContent = totalDistribuido;
            }
        }

        // Función para mostrar/ocultar sección de ubicaciones - CORREGIDA
        function toggleUbicacionField() {
            const estado = document.getElementById('producto-estado').value;
            const ubicacionSection = document.getElementById('ubicacion-section');

            if (ubicacionSection) {
                if (estado === 'ubicado') {
                    ubicacionSection.style.display = 'block';
                } else {
                    ubicacionSection.style.display = 'none';
                }
            }
        }

        // Función para inicializar event listeners - CORREGIDA
        function inicializarEventListenersModal() {
            // Botón agregar ubicación
            const btnAgregarUbicacion = document.getElementById('agregar-ubicacion-btn');
            if (btnAgregarUbicacion) {
                btnAgregarUbicacion.addEventListener('click', function() {
                    agregarFilaUbicacion();
                });
            } else {
                console.error('No se encontró el botón agregar-ubicacion-btn');
            }

            // Botón guardar
            const btnGuardar = document.getElementById('guardar-editar-btn');
            if (btnGuardar) {
                btnGuardar.addEventListener('click', guardarEdicion);
            } else {
                console.error('No se encontró el botón guardar-editar-btn');
            }

            // Botón cancelar
            const btnCancelar = document.getElementById('cancelar-editar-btn');
            if (btnCancelar) {
                btnCancelar.addEventListener('click', function() {
                    document.getElementById('modal-editar-producto').classList.add('hidden');
                });
            } else {
                console.error('No se encontró el botón cancelar-editar-btn');
            }

            // Cambio de estado
            const selectEstado = document.getElementById('producto-estado');
            if (selectEstado) {
                selectEstado.addEventListener('change', toggleUbicacionField);
            }
        }
        // Función para guardar cambios
        async function guardarEdicion() {
            const solicitudId = document.getElementById('solicitud-id').value;
            const articuloId = document.getElementById('articulo-id').value;
            const estado = document.getElementById('producto-estado').value;
            const observaciones = document.getElementById('producto-observaciones').value;

            // Recolectar datos de ubicaciones
            const filasUbicacion = document.querySelectorAll('.fila-ubicacion');
            const ubicacionesData = [];
            let totalDistribuido = 0;

            for (const fila of filasUbicacion) {
                const selectUbicacion = fila.querySelector('.select-ubicacion');
                const inputCantidad = fila.querySelector('.input-cantidad');

                if (!selectUbicacion || !inputCantidad) continue;

                const ubicacionId = selectUbicacion.value;
                const cantidad = parseInt(inputCantidad.value) || 0;

                if (!ubicacionId || cantidad <= 0) {
                    showToast("Por favor completa todas las ubicaciones y cantidades", "warning");
                    return;
                }


                ubicacionesData.push({
                    idUbicacion: parseInt(ubicacionId),
                    cantidad: cantidad
                });

                totalDistribuido += cantidad;
            }

            // Validar cantidad total
            const cantidadTotal = parseInt(document.getElementById('producto-cantidad').textContent);
            if (totalDistribuido !== cantidadTotal) {
                showToast(`La distribución suma ${totalDistribuido} unidades, pero el total es ${cantidadTotal}.`,
                    "warning");
                return;
            }


            try {
                const response = await fetch('/solicitudes-ingreso/procesar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        id: solicitudId,
                        articulo_id: articuloId,
                        estado: estado,
                        ubicaciones: ubicacionesData,
                        observaciones: observaciones
                    })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('modal-editar-producto').classList.add('hidden');
                    showToast("Solicitud actualizada correctamente", "success");

                    const compraId = todasLasSolicitudes[0]?.compra_id;
                    if (compraId) {
                        const solicitudesActualizadas = await cargarSolicitudesCompra(compraId);
                        todasLasSolicitudes = solicitudesActualizadas;
                        renderizarTablaProductos(todasLasSolicitudes);
                    }
                } else {
                    showToast("Error: " + data.message, "error");
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            }
        }
        // Función para inicializar event listeners
        function inicializarEventListeners() {
            // Botón guardar
            const btnGuardar = document.getElementById('guardar-editar-btn');
            if (btnGuardar) {
                btnGuardar.addEventListener('click', guardarEdicion);
            }

            // Botón cancelar
            const btnCancelar = document.getElementById('cancelar-editar-btn');
            if (btnCancelar) {
                btnCancelar.addEventListener('click', function() {
                    document.getElementById('modal-editar-producto').classList.add('hidden');
                    document.getElementById('modal-editar-producto').classList.remove('flex');
                });
            }

            // Cambio de estado
            const selectEstado = document.getElementById('producto-estado');
            if (selectEstado) {
                selectEstado.addEventListener('change', toggleUbicacionField);
            }
        }

        // Función para agregar event listeners a los botones de editar en la tabla
        function agregarEventListenersTablaProductos() {
            document.querySelectorAll('.editar-producto-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const solicitudId = this.dataset.id;
                    abrirModalEditarProducto(solicitudId);
                });
            });
        }

        // Event Listeners globales
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar event listeners
            inicializarEventListeners();

            inicializarEventListenersModal();


            // Renderizar compras iniciales
            renderizarCompras();

            // Filtros
            document.querySelectorAll('.filtro-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filtro-btn').forEach(b => {
                        b.classList.remove('bg-blue-500', 'text-white');
                        b.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    this.classList.add('bg-blue-500', 'text-white');

                    renderizarCompras(this.dataset.estado, document.getElementById('busqueda')
                        .value);
                });
            });

            // Búsqueda
            const inputBusqueda = document.getElementById('busqueda');
            if (inputBusqueda) {
                inputBusqueda.addEventListener('input', function() {
                    const estadoActivo = document.querySelector('.filtro-btn.bg-blue-500')?.dataset
                        .estado || '';
                    renderizarCompras(estadoActivo, this.value);
                });
            }

            // Cerrar modal detalle compra
            const btnCerrarModal = document.getElementById('cerrar-modal');
            if (btnCerrarModal) {
                btnCerrarModal.addEventListener('click', function() {
                    document.getElementById('modal-detalle-compra').classList.add('hidden');
                    document.getElementById('modal-detalle-compra').classList.remove('flex');
                });
            }
        });

        function showToast(message, type = "info") {
            switch (type) {
                case "success":
                    toastr.success(message, "Éxito");
                    break;
                case "error":
                    toastr.error(message, "Error");
                    break;
                case "warning":
                    toastr.warning(message, "Atención");
                    break;
                default:
                    toastr.info(message, "Información");
            }
        }
    </script>
</x-layout.default>
