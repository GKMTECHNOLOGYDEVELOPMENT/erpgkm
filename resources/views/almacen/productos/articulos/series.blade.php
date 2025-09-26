<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .badge-activo {
            background-color: #10b981;
            color: white;
        }

        .badge-inactivo {
            background-color: #ef4444;
            color: white;
        }

        .info-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        /* Ocultar el buscador de DataTables */
        .dataTables_filter {
            display: none !important;
        }

        /* Ocultar el control de longitud de registros */
        .dataTables_length {
            display: none !important;
        }

        .badge-compra {
            background-color: #3b82f6;
            color: white;
        }

        .badge-entrada_proveedor {
            background-color: #8b5cf6;
            color: white;
        }
    </style>

    <div x-data="seriesTable">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('producto.index') }}" class="text-primary hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i> Productos
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Series - {{ $articulo->nombre }}</span>
                </li>
            </ul>
        </div>

        <div class="mb-6">
            <div class="panel rounded-lg shadow-sm p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Información del Producto
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Código de Barras -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-barcode text-blue-600 mr-2"></i>
                            <span class="text-sm font-semibold text-blue-800">Código de Barras</span>
                        </div>
                        <p class="text-gray-800 font-medium">{{ $articulo->codigo_barras }}</p>
                    </div>

                    <!-- SKU -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-tag text-green-600 mr-2"></i>
                            <span class="text-sm font-semibold text-green-800">SKU</span>
                        </div>
                        <p class="text-gray-800 font-medium">{{ $articulo->sku }}</p>
                    </div>

                    <!-- Modelo -->
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-cube text-purple-600 mr-2"></i>
                            <span class="text-sm font-semibold text-purple-800">Modelo</span>
                        </div>
                        <p class="text-gray-800 font-medium">{{ $articulo->modelo->nombre ?? 'N/A' }}</p>
                    </div>

                    <!-- Stock Total -->
                    <div class="bg-orange-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-boxes text-orange-600 mr-2"></i>
                            <span class="text-sm font-semibold text-orange-800">Stock Total</span>
                        </div>
                        <p class="text-gray-800 font-medium">{{ $articulo->stock_total }} unidades</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Series -->
        <div class="panel">
            <div class="flex justify-between items-center mb-5">
                <h5 class="font-semibold text-lg">Gestión de Series</h5>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">
                        Total de series: <strong>{{ $series->count() }}</strong>
                    </span>
                    <a href="{{ route('producto.index') }}" class="btn btn-dark btn-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                </div>
            </div>

            <!-- Filtros -->
            <div class="mb-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Filtrar por estado</label>
                    <select class="form-select" x-model="filtroEstado" @change="filtrarSeries">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Filtrar por origen</label>
                    <select class="form-select" x-model="filtroOrigen" @change="filtrarSeries">
                        <option value="">Todos los orígenes</option>
                        <option value="compra">Compra</option>
                        <option value="entrada_proveedor">Entrada Proveedor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Buscar por serie</label>
                    <input type="text" class="form-input" placeholder="Buscar número de serie..."
                        x-model="busquedaSerie" @input="filtrarSeries">
                </div>
            </div>

            <!-- Tabla de Series -->
            <div class="table-responsive">
                <table id="tablaSeries" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Número de Serie</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Origen</th>
                            <th class="text-center">Fecha de Ingreso</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($series as $index => $serie)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="font-mono font-semibold text-center">{{ $serie->numero_serie }}</td>
                                <td class="text-center">
                                    @php
                                        $badgeClass =
                                            [
                                                'activo' => 'badge-activo',
                                                'inactivo' => 'badge-inactivo',
                                            ][$serie->estado] ?? 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($serie->estado) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $origenBadgeClass =
                                            [
                                                'compra' => 'badge-compra',
                                                'entrada_proveedor' => 'badge-entrada_proveedor',
                                            ][$serie->origen] ?? 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $origenBadgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $serie->origen)) }}
                                    </span>
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($serie->fecha_ingreso)->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button type="button" class="btn btn-info btn-sm"
                                            @click="verDetalleSerie({{ $serie->idArticuloSerie }})"
                                            x-tooltip="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm"
                                            @click="cambiarEstado({{ $serie->idArticuloSerie }}, '{{ $serie->numero_serie }}', '{{ $serie->estado }}')"
                                            x-tooltip="Cambiar estado">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            @click="eliminarSerie({{ $serie->idArticuloSerie }}, '{{ $serie->numero_serie }}')"
                                            x-tooltip="Eliminar serie">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500">No se encontraron series para este artículo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Modal Cambiar Estado -->
        <div x-show="mostrarModalEstado" x-cloak
            class="fixed inset-0 bg-black/60 z-[999] flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-md">
                <div class="px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">Cambiar Estado de Serie</h5>
                </div>
                <div class="p-6">
                    <p class="mb-4">Serie: <span class="font-mono font-semibold" x-text="serieSeleccionada"></span>
                    </p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Nuevo Estado</label>
                        <select class="form-select" x-model="nuevoEstado">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" @click="cerrarModal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" @click="guardarNuevoEstado">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Eliminar Serie -->
        <div x-show="mostrarModalEliminar" x-cloak
            class="fixed inset-0 bg-black/60 z-[999] flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-md">
                <div class="px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold text-red-600">Eliminar Serie</h5>
                </div>
                <div class="p-6">
                    <p class="mb-4">¿Estás seguro de que deseas eliminar la serie?</p>
                    <p class="font-mono font-semibold text-lg text-center" x-text="serieAEliminar"></p>
                    <p class="text-sm text-gray-500 mt-2 text-center">Esta acción no se puede deshacer.</p>
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" @click="cerrarModal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" @click="confirmarEliminar">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('seriesTable', () => ({
                filtroEstado: '',
                filtroOrigen: '',
                busquedaSerie: '',
                mostrarModalEstado: false,
                mostrarModalEliminar: false,
                serieSeleccionada: '',
                serieSeleccionadaId: null,
                serieAEliminar: '',
                serieAEliminarId: null,
                nuevoEstado: 'activo',
                datatable: null,

                init() {
                    this.initDataTable();
                },

                initDataTable() {
                    this.datatable = $('#tablaSeries').DataTable({
                        responsive: true,
                        autoWidth: false,
                        pageLength: 10,
                        dom: 'rtip',
                        language: {
                            zeroRecords: 'No se encontraron registros',
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                            infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                            infoFiltered: '(filtrado de _MAX_ registros totales)',
                            paginate: {
                                first: 'Primero',
                                last: 'Último',
                                next: 'Siguiente',
                                previous: 'Anterior'
                            }
                        },
                        columnDefs: [{
                                orderable: false,
                                targets: [5]
                            } // Deshabilitar ordenamiento en columna de acciones
                        ]
                    });
                },

                filtrarSeries() {
                    // Aplicar filtros simultáneamente
                    this.datatable
                        .column(1) // Columna de número de serie
                        .search(this.busquedaSerie)
                        .column(2) // Columna de estado
                        .search(this.filtroEstado)
                        .column(3) // Columna de origen
                        .search(this.filtroOrigen)
                        .draw();
                },

                verDetalleSerie(idSerie) {
                    // Implementar lógica para ver detalles
                    alert('Detalles de la serie ID: ' + idSerie);
                    // window.location.href = '/series/' + idSerie + '/detalle';
                },

                cambiarEstado(idSerie, numeroSerie, estadoActual) {
                    this.serieSeleccionadaId = idSerie;
                    this.serieSeleccionada = numeroSerie;
                    this.nuevoEstado = estadoActual;
                    this.mostrarModalEstado = true;
                },

                eliminarSerie(idSerie, numeroSerie) {
                    this.serieAEliminarId = idSerie;
                    this.serieAEliminar = numeroSerie;
                    this.mostrarModalEliminar = true;
                },

                cerrarModal() {
                    this.mostrarModalEstado = false;
                    this.mostrarModalEliminar = false;
                    this.serieSeleccionadaId = null;
                    this.serieSeleccionada = '';
                    this.serieAEliminarId = null;
                    this.serieAEliminar = '';
                },

                async guardarNuevoEstado() {
                    if (!this.serieSeleccionadaId) return;

                    try {
                        const response = await fetch(
                            `/articulo-series/${this.serieSeleccionadaId}/cambiar-estado`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    estado: this.nuevoEstado
                                })
                            });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success('Estado actualizado correctamente');
                            this.cerrarModal();
                            // Recargar después de 1 segundo para ver los cambios
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error(data.message || 'Error al actualizar el estado');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error de conexión');
                    }
                },

                async confirmarEliminar() {
                    if (!this.serieAEliminarId) return;

                    try {
                        const response = await fetch(`/articulo-series/${this.serieAEliminarId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success('Serie eliminada correctamente');
                            this.cerrarModal();
                            // Recargar después de 1 segundo para ver los cambios
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error(data.message || 'Error al eliminar la serie');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error de conexión');
                    }
                },

                exportarExcel() {
                    // Implementar exportación a Excel
                    window.location.href =
                        '/producto/{{ $articulo->idArticulos }}/series/exportar-excel';
                }
            }));
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</x-layout.default>
