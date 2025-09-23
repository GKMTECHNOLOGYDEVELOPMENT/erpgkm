<x-layout.default>
    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Solicitudes de Ingreso por Compra</h1>
                <p class="text-gray-600 mt-1">Gestiona las solicitudes agrupadas por compra</p>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-wrap gap-2">
                    <button class="filtro-btn active px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition" data-estado="">
                        Todas las Compras
                    </button>
                    <button class="filtro-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-600 hover:text-white transition" data-estado="pendiente">
                        Con Pendientes
                    </button>
                    <button class="filtro-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-600 hover:text-white transition" data-estado="recibido">
                        Parcialmente Recibidas
                    </button>
                    <button class="filtro-btn px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-600 hover:text-white transition" data-estado="completado">
                        Completadas
                    </button>
                </div>
                <div class="relative w-full md:w-64">
                    <input type="text" id="busqueda" placeholder="Buscar por código o proveedor..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Grid de Cards de Compras -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6" id="contenedor-compras">
            <!-- Las compras se cargarán aquí -->
        </div>
    </div>

    <!-- Modal para detalle de compra -->
    <div id="modal-detalle-compra" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800" id="modal-titulo">Detalle de Compra</h3>
                <button id="cerrar-modal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-auto max-h-[70vh]">
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código Compra</label>
                            <p class="font-semibold" id="detalle-codigo"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                            <p class="font-semibold" id="detalle-proveedor"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Compra</label>
                            <p class="font-semibold" id="detalle-fecha"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-lg mb-3">Productos de la Compra</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3">Artículo</th>
                                    <th class="px-4 py-3">Cantidad</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3">Ubicación</th>
                                    <th class="px-4 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-productos">
                                <!-- Los productos se cargarán aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar producto individual - SIMPLIFICADO -->
    <div id="modal-editar-producto" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Editar Estado del Producto</h3>
            </div>
            <div class="p-6">
                <form id="form-editar-producto">
                    <input type="hidden" id="solicitud-id">
                    <input type="hidden" id="articulo-id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Artículo</label>
                        <p class="font-semibold" id="producto-nombre"></p>
                        <p class="text-sm text-gray-500" id="producto-id-text"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                        <p class="font-semibold" id="producto-cantidad"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select id="producto-estado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pendiente">Pendiente</option>
                            <option value="recibido">Recibido</option>
                            <option value="ubicado">Ubicado</option>
                        </select>
                    </div>

                    <div class="mb-4" id="ubicacion-container" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                        <select id="producto-ubicacion" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar ubicación</option>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->idUbicacion }}">{{ $ubicacion->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea id="producto-observaciones" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button id="cancelar-editar-btn" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancelar</button>
                <button id="guardar-editar-btn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Guardar</button>
            </div>
        </div>
    </div>

    <!-- Pasar datos de PHP a JavaScript -->
    <script>
        const comprasConSolicitudes = @json($comprasConSolicitudes);
        const ubicaciones = @json($ubicaciones);
    </script>

    <style>
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-pendiente { background-color: #fef3cd; color: #856404; }
        .badge-recibido { background-color: #cce7ff; color: #004085; }
        .badge-ubicado { background-color: #d1f2eb; color: #155724; }
    </style>

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

            // Aplicar filtros
            if (filtroEstado === 'pendiente') {
                comprasFiltradas = comprasFiltradas.filter(c => c.pendientes > 0);
            } else if (filtroEstado === 'recibido') {
                comprasFiltradas = comprasFiltradas.filter(c => c.recibidos > 0 && c.pendientes > 0);
            } else if (filtroEstado === 'completado') {
                comprasFiltradas = comprasFiltradas.filter(c => c.pendientes === 0);
            }

            if (busqueda) {
                const termino = busqueda.toLowerCase();
                comprasFiltradas = comprasFiltradas.filter(c => 
                    c.codigocompra.toLowerCase().includes(termino) || 
                    (c.proveedor && c.proveedor.toLowerCase().includes(termino))
                );
            }

            if (comprasFiltradas.length === 0) {
                contenedor.innerHTML = `
                    <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No hay compras encontradas</h3>
                        <p class="mt-1 text-gray-500">No se encontraron compras con los filtros aplicados.</p>
                    </div>
                `;
                return;
            }

            contenedor.innerHTML = comprasFiltradas.map(compra => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">${compra.codigocompra}</h3>
                                <p class="text-sm text-gray-600 mt-1">Proveedor: ${compra.proveedor || 'N/A'}</p>
                            </div>
                            <span class="badge ${compra.pendientes === 0 ? 'badge-ubicado' : compra.recibidos > 0 ? 'badge-recibido' : 'badge-pendiente'}">
                                ${compra.pendientes === 0 ? 'Completada' : compra.recibidos > 0 ? 'Parcial' : 'Pendiente'}
                            </span>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Fecha Compra</p>
                                <p class="text-sm font-medium text-gray-800">${formatFecha(compra.fecha_compra)}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total</p>
                                <p class="text-sm font-medium text-gray-800">S/ ${compra.total}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Estado de Productos</p>
                            <div class="flex space-x-2 mt-2">
                                <span class="badge badge-pendiente">Pendientes: ${compra.pendientes}</span>
                                <span class="badge badge-recibido">Recibidos: ${compra.recibidos}</span>
                                <span class="badge badge-ubicado">Ubicados: ${compra.ubicados}</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 font-medium">Total Productos</p>
                            <p class="text-lg font-bold text-gray-800">${compra.total_solicitudes} productos</p>
                        </div>
                    </div>
                    
                    <div class="px-6 py-3 bg-gray-50 border-t flex justify-end">
                        <button class="ver-detalle-btn flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition" data-id="${compra.idCompra}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver Detalle
                        </button>
                    </div>
                </div>
            `).join('');

            // Agregar event listeners a los botones
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
                document.getElementById('modal-titulo').textContent = `Detalle de Compra: ${compra.codigocompra}`;
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
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-medium">${solicitud.articulo || 'N/A'}</p>
                            <p class="text-sm text-gray-500">${solicitud.codigo_solicitud}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3">${solicitud.cantidad} unidades</td>
                    <td class="px-4 py-3">
                        <span class="badge ${solicitud.estado === 'pendiente' ? 'badge-pendiente' : solicitud.estado === 'recibido' ? 'badge-recibido' : 'badge-ubicado'}">
                            ${solicitud.estado ? solicitud.estado.charAt(0).toUpperCase() + solicitud.estado.slice(1) : 'N/A'}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        ${solicitud.ubicacion ? solicitud.ubicacion : '<span class="text-red-500">Por asignar</span>'}
                    </td>
                    <td class="px-4 py-3">
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

        // Función para abrir modal de edición - SIMPLIFICADA
        function abrirModalEditarProducto(solicitudId) {
            const solicitud = todasLasSolicitudes.find(s => s.id == solicitudId);
            
            if (solicitud) {
                document.getElementById('solicitud-id').value = solicitudId;
                document.getElementById('articulo-id').value = solicitud.articulo_id || 'N/A';
                document.getElementById('producto-nombre').textContent = solicitud.articulo || 'N/A';
                document.getElementById('producto-id-text').textContent = `ID Artículo: ${solicitud.articulo_id || 'N/A'}`;
                document.getElementById('producto-cantidad').textContent = `${solicitud.cantidad} unidades`;
                document.getElementById('producto-estado').value = solicitud.estado || 'pendiente';
                document.getElementById('producto-observaciones').value = solicitud.observaciones || '';

                // Si ya tiene ubicación, seleccionarla
                if (solicitud.ubicacion) {
                    const ubicacionEncontrada = ubicaciones.find(u => u.nombre === solicitud.ubicacion);
                    if (ubicacionEncontrada) {
                        document.getElementById('producto-ubicacion').value = ubicacionEncontrada.idUbicacion;
                    }
                }

                // Mostrar/ocultar campo de ubicación según estado
                toggleUbicacionField();
                
                document.getElementById('modal-editar-producto').classList.remove('hidden');
                document.getElementById('modal-editar-producto').classList.add('flex');
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

        // Función para guardar cambios - SIMPLIFICADA
        async function guardarEdicion() {
            const solicitudId = document.getElementById('solicitud-id').value;
            const estado = document.getElementById('producto-estado').value;
            const ubicacionId = document.getElementById('producto-ubicacion').value;
            const observaciones = document.getElementById('producto-observaciones').value;

            // Validar ubicación si el estado es "ubicado"
            if (estado === 'ubicado' && !ubicacionId) {
                alert('Por favor selecciona una ubicación para el estado "Ubicado"');
                return;
            }

            // Obtener nombre de la ubicación seleccionada
            const ubicacionSeleccionada = ubicaciones.find(u => u.idUbicacion == ubicacionId);
            const nombreUbicacion = ubicacionSeleccionada ? ubicacionSeleccionada.nombre : null;

            try {
                const response = await fetch('/solicitudes-ingreso/procesar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: solicitudId,
                        estado: estado,
                        ubicacion_id: ubicacionId,
                        ubicacion_nombre: nombreUbicacion,
                        observaciones: observaciones
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Actualizar la solicitud en el array local
                    const index = todasLasSolicitudes.findIndex(s => s.id == solicitudId);
                    if (index !== -1) {
                        todasLasSolicitudes[index].estado = estado;
                        todasLasSolicitudes[index].ubicacion = nombreUbicacion;
                        todasLasSolicitudes[index].observaciones = observaciones;
                    }

                    // Re-renderizar la tabla
                    renderizarTablaProductos(todasLasSolicitudes);
                    
                    document.getElementById('modal-editar-producto').classList.add('hidden');
                    document.getElementById('modal-editar-producto').classList.remove('flex');
                    
                    alert('Solicitud actualizada correctamente');
                } else {
                    alert('Error al actualizar la solicitud');
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
                    
                    renderizarCompras(this.dataset.estado, document.getElementById('busqueda').value);
                });
            });

            // Búsqueda
            const inputBusqueda = document.getElementById('busqueda');
            if (inputBusqueda) {
                inputBusqueda.addEventListener('input', function() {
                    const estadoActivo = document.querySelector('.filtro-btn.bg-blue-500')?.dataset.estado || '';
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
    </script>
</x-layout.default>