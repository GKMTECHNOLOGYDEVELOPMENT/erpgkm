<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <div class="panel mt-6">
        <div class="mb-4 flex justify-end items-center gap-3">
            <!-- Input búsqueda -->
            <div class="relative w-64">
                <input type="text" id="searchInput" placeholder="Buscar cliente..."
                    class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                <button type="button" id="clearInput"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
            <!-- Botón buscar -->
            <button id="btnSearch"
                class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm">
                Buscar
            </button>
        </div>

        <!-- Tabla -->
        <table id="tablaClientes" class="w-full min-w-[700px] table whitespace-nowrap">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#tablaClientes').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('clientes.index.data') }}',
                columns: [
                    { data: 'nombre', name: 'cliente.nombre' },
                    { data: 'telefono', name: 'cliente.telefono' },
                    { data: 'servicio_nombre', name: 'servicios.nombre' },
                    {
                        data: 'estado',
                        render: estado => estado == 1
                            ? '<span class="text-green-600 font-semibold">Activo</span>'
                            : '<span class="text-red-500 font-semibold">Inactivo</span>',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });

            $('#btnSearch').on('click', () => {
                table.search($('#searchInput').val()).draw();
            });

            $('#searchInput').on('keypress', e => {
                if (e.which === 13) $('#btnSearch').click();
            });

            const input = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearInput');

            input.addEventListener('input', () => {
                clearBtn.classList.toggle('hidden', input.value.trim() === '');
            });

            clearBtn.addEventListener('click', () => {
                input.value = '';
                clearBtn.classList.add('hidden');
                table.search('').draw();
            });
        });
    </script>
</x-layout.default>
