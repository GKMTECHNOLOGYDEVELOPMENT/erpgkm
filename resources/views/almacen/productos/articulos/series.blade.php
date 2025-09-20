<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .badge-disponible { background-color: #10b981; color: white; }
        .badge-vendido { background-color: #ef4444; color: white; }
        .badge-defectuoso { background-color: #f59e0b; color: white; }
        .badge-garantia { background-color: #3b82f6; color: white; }
        .info-card { background: #f8fafc; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
    </style>

    <div x-data="seriesTable">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li><a href="#" class="text-primary hover:underline">Dashboard</a></li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <a href="#" class="text-primary hover:underline">Artículos</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Series - {{ $articulo->nombre }}</span>
                </li>
            </ul>
        </div>

        <!-- Información del Producto -->
        <div class="info-card grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500">Código de Barras</p>
                <p class="font-semibold">{{ $articulo->codigo_barras }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">SKU</p>
                <p class="font-semibold">{{ $articulo->sku }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Modelo</p>
                <p class="font-semibold">{{ $articulo->modelo->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Stock Total</p>
                <p class="font-semibold">{{ $articulo->stock_total }} unidades</p>
            </div>
        </div>

        <!-- Panel de Series -->
        <div class="panel">
            <div class="flex justify-between items-center mb-5">
                <h5 class="font-semibold text-lg">Gestión de Series</h5>
                <div class="flex items-center gap-2">
                    <a href="{{ route('articulos.index') }}" class="btn btn-secondary btn-sm">
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
                        <option value="disponible">Disponible</option>
                        <option value="vendido">Vendido</option>
                        <option value="defectuoso">Defectuoso</option>
                        <option value="garantia">En garantía</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Buscar por serie</label>
                    <input type="text" class="form-input" placeholder="Buscar serie..." x-model="busquedaSerie" @input="filtrarSeries">
                </div>
                <div class="flex items-end">
                    <button class="btn btn-primary" @click="exportarExcel">
                        <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                    </button>
                </div>
            </div>

            <!-- Tabla de Series -->
            <div class="table-responsive">
                <table id="tablaSeries" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Número de Serie</th>
                            <th>Estado</th>
                            <th>Fecha de Ingreso</th>
                            <th>Código de Compra</th>
                            <th>Proveedor</th>
                            <th>Fecha Compra</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($series as $index => $serie)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-mono">{{ $serie->serie }}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'disponible' => 'badge-disponible',
                                        'vendido' => 'badge-vendido', 
                                        'defectuoso' => 'badge-defectuoso',
                                        'garantia' => 'badge-garantia'
                                    ][$serie->estado] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($serie->estado) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($serie->fecha_ingreso)->format('d/m/Y') }}</td>
                            <td>{{ $serie->codigocompra }}</td>
                            <td>{{ $serie->proveedor ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($serie->fechaEmision)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-info btn-sm" 
                                            @click="verDetalleSerie('{{ $serie->serie }}')"
                                            x-tooltip="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" 
                                            @click="cambiarEstado('{{ $serie->serie }}')"
                                            x-tooltip="Cambiar estado">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">
                                No se encontraron series para este producto.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Cambiar Estado -->
        <div x-show="mostrarModalEstado" x-cloak class="fixed inset-0 bg-black/60 z-[999] flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-md">
                <div class="px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">Cambiar Estado de Serie</h5>
                </div>
                <div class="p-6">
                    <p class="mb-4">Serie: <span class="font-mono font-semibold" x-text="serieSeleccionada"></span></p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Nuevo Estado</label>
                        <select class="form-select" x-model="nuevoEstado">
                            <option value="disponible">Disponible</option>
                            <option value="vendido">Vendido</option>
                            <option value="defectuoso">Defectuoso</option>
                            <option value="garantia">En garantía</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" @click="mostrarModalEstado = false">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" @click="guardarNuevoEstado">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('seriesTable', () => ({
                filtroEstado: '',
                busquedaSerie: '',
                mostrarModalEstado: false,
                serieSeleccionada: '',
                nuevoEstado: 'disponible',
                datatable: null,

                init() {
                    this.initDataTable();
                },

                initDataTable() {
                    this.datatable = $('#tablaSeries').DataTable({
                        responsive: true,
                        autoWidth: false,
                        pageLength: 10,
                        language: {
                            search: 'Buscar:',
                            zeroRecords: 'No se encontraron registros',
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                            infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                            lengthMenu: 'Mostrar _MENU_ registros',
                            paginate: {
                                first: 'Primero',
                                last: 'Último',
                                next: 'Siguiente',
                                previous: 'Anterior'
                            }
                        }
                    });
                },

                filtrarSeries() {
                    this.datatable.column(1).search(this.busquedaSerie).draw();
                    this.datatable.column(2).search(this.filtroEstado).draw();
                },

                verDetalleSerie(serie) {
                    // Aquí puedes implementar la lógica para ver detalles de la serie
                    alert('Detalles de la serie: ' + serie);
                },

                cambiarEstado(serie) {
                    this.serieSeleccionada = serie;
                    this.mostrarModalEstado = true;
                },

                async guardarNuevoEstado() {
                    try {
                        const response = await fetch('/api/series/cambiar-estado', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                serie: this.serieSeleccionada,
                                estado: this.nuevoEstado
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success('Estado actualizado correctamente');
                            location.reload();
                        } else {
                            toastr.error('Error al actualizar el estado');
                        }
                    } catch (error) {
                        toastr.error('Error de conexión');
                    }
                },

                exportarExcel() {
                    // Implementar exportación a Excel
                    window.location.href = '/producto/{{ $articulo->idArticulos }}/series/exportar-excel';
                }
            }));
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</x-layout.default>