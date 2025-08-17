<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <div class="panel mt-6">
        <div class="mb-4 flex flex-wrap justify-between items-center gap-3">
            <!-- Botón agregar -->
            <a href="{{ route('Seguimiento.create') }}"
                class="btn btn-sm bg-success text-white hover:bg-green-600 px-4 py-2 rounded shadow-sm flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Nuevo Seguimiento</span>
            </a>

            <!-- Grupo búsqueda -->
            <div class="flex items-center gap-3">
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
        </div>


        <!-- Tabla -->
        <table id="tablaClientes" class="w-full min-w-[700px] table whitespace-nowrap">
            <thead>
                <tr>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Teléfono</th>
                    <th class="text-center">Servicio</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">Juan Pérez</td>
                    <td class="text-center">987654321</td>
                    <td class="text-center">Instalación</td>
                    <td class="text-center"><span class="badge badge-outline-success">Activo</span></td>
                </tr>
                <tr>
                    <td class="text-center">María López</td>
                    <td class="text-center">912345678</td>
                    <td class="text-center">Soporte Técnico</td>
                    <td class="text-center"><span class="badge badge-outline-danger">Inactivo</span></td>
                </tr>
                <tr>
                    <td class="text-center">Carlos Ramírez</td>
                    <td class="text-center">999888777</td>
                    <td class="text-center">Mantenimiento</td>
                    <td class="text-center"><span class="badge badge-outline-success">Activo</span></td>
                </tr>
            </tbody>
        </table>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#tablaClientes').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                language: {
                    search: 'Buscar...',
                    zeroRecords: 'No se encontraron registros',
                    lengthMenu: 'Mostrar _MENU_ registros por página',
                    loadingRecords: 'Cargando...',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                },
                dom: 'rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function() {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const tableEl = wrapper.querySelector('#tablaClientes');

                    const scrollContainer = document.createElement('div');
                    scrollContainer.className =
                        'dataTables_scrollable overflow-x-auto border border-gray-200 rounded-md mb-3';
                    tableEl.parentNode.insertBefore(scrollContainer, tableEl);
                    scrollContainer.appendChild(tableEl);

                    const scrollTop = document.createElement('div');
                    scrollTop.className = 'dataTables_scrollTop overflow-x-auto mb-2';
                    scrollTop.style.height = '14px';

                    const topInner = document.createElement('div');
                    topInner.style.width = scrollContainer.scrollWidth + 'px';
                    topInner.style.height = '1px';
                    scrollTop.appendChild(topInner);

                    scrollTop.addEventListener('scroll', () => {
                        scrollContainer.scrollLeft = scrollTop.scrollLeft;
                    });
                    scrollContainer.addEventListener('scroll', () => {
                        scrollTop.scrollLeft = scrollContainer.scrollLeft;
                    });

                    wrapper.insertBefore(scrollTop, scrollContainer);

                    const floatingControls = document.createElement('div');
                    floatingControls.className =
                        'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                    Object.assign(floatingControls.style, {
                        position: 'sticky',
                        bottom: '0',
                        left: '0',
                        width: '100%',
                        zIndex: '10'
                    });

                    const info = wrapper.querySelector('.dataTables_info');
                    const length = wrapper.querySelector('.dataTables_length');
                    const paginate = wrapper.querySelector('.dataTables_paginate');

                    if (info && length && paginate) {
                        floatingControls.appendChild(info);
                        floatingControls.appendChild(length);
                        floatingControls.appendChild(paginate);
                        wrapper.appendChild(floatingControls);
                    }
                }
            });

            const $input = $('#searchInput');
            const $clearBtn = $('#clearInput');

            $('#btnSearch').on('click', () => {
                const value = $input.val();
                table.search(value).draw();
            });

            $input.on('keypress', function(e) {
                if (e.which === 13) $('#btnSearch').click();
            });

            $input.on('input', function() {
                $clearBtn.toggleClass('hidden', $input.val().trim() === '');
            });

            $clearBtn.on('click', function() {
                $input.val('');
                $clearBtn.addClass('hidden');
                table.search('').draw();
            });
        });
    </script>
</x-layout.default>
