<x-layout.default>

    <!-- Scripts y estilos -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .tachado td {
            text-decoration: line-through !important;
            color: #999 !important;
            position: relative;
        }

        .tachado::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            pointer-events: none;
            z-index: 1;
        }
    </style>

    <style>
        .panel {
            overflow: visible !important;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        #myTable1 thead th {
            pointer-events: none;
            /* Evita la interacción con el encabezado */
        }

        #myTable1 thead .sorting,
        #myTable1 thead .sorting_asc,
        #myTable1 thead .sorting_desc {
            background-image: none !important;
            /* Oculta los íconos de ordenación */
        }

        /* SCROLLBAR MODERNO Y REDONDEADO */
        .custom-scroll {
            overflow-x: auto !important;
            scrollbar-width: thin;
            /* Firefox */
            scrollbar-color: #a8a8a8 #e0e0e0;
            /* Gris más sutil */
        }

        /* WebKit (Chrome, Safari, Edge) */
        .custom-scroll::-webkit-scrollbar {
            height: 10px;
            /* Tamaño del scrollbar */
            background: #e0e0e0;
            /* Fondo gris claro */
            border-radius: 40px;
        }

        #clienteGeneralFilter+.nice-select {
            line-height: normal !important;
        }
    </style>

    <div x-data="multipleTable">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Tickets</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Ordenes de Trabajo</span>
                </li>
            </ul>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap items-end gap-4 mb-6">
            <!-- Fecha de Inicio -->
            <div class="w-[260px]">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="text" id="startDate" x-model="startDate" placeholder="Seleccionar Fecha"
                    class="form-input w-full px-3 py-2 text-sm" x-init="flatpickr($el, {
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            startDate = dateStr;
                            fetchDataAndInitTable();
                        }
                    })" />
            </div>

            <!-- Fecha Fin -->
            <div class="w-[260px]">
                <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="text" id="endDate" x-model="endDate" placeholder="Seleccionar Fecha"
                    class="form-input w-full px-3 py-2 text-sm" x-init="flatpickr($el, {
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            endDate = dateStr;
                            fetchDataAndInitTable();
                        }
                    })" />
            </div>

            <!-- Cliente General -->
            <div class="w-[260px]">
                <label for="clienteGeneralFilter" class="block text-sm font-medium text-gray-700">Filtrar por Cliente General</label>
                <select id="clienteGeneralFilter" x-model="clienteGeneralFilter"
                    @change="onClienteGeneralChange($event.target.value)"
                    class="form-select w-full px-3 py-2 text-sm border-gray-300 rounded">
                    <option value="">Todos los clientes generales</option>
                    <template x-for="cliente in clienteGenerales" :key="cliente.idClienteGeneral">
                        <option :value="cliente.idClienteGeneral" x-text="cliente.descripcion"></option>
                    </template>
                </select>
            </div>
            

            <!-- Marca -->
            <div class="w-[260px]">
                <label for="marcaFilter" class="block text-sm font-medium text-gray-700">Filtrar por Marca</label>
                <select x-model="marcaFilter" @change="fetchDataAndInitTable()" id="marcaFilter"
                    class="form-select w-full px-3 py-2 text-sm border-gray-300 rounded">
                    <option value="">Todas las marcas</option>
                    <template x-for="marca in marcas" :key="marca.idMarca">
                        <option :value="marca.idMarca" x-text="marca.nombre"></option>
                    </template>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex items-end gap-2">
                <!-- Agregar -->
                <a href="{{ route('ordenes.createsmart') }}" class="btn btn-primary btn-sm px-3 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block mx-auto" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <line x1="12" y1="6" x2="12" y2="18" stroke-linecap="round" />
                        <line x1="6" y1="12" x2="18" y2="12" stroke-linecap="round" />
                    </svg>
                </a>

                <!-- Exportar -->
                <a class="btn btn-success btn-sm px-3 py-2"
                    x-bind:href="`{{ route('ordenes.export.excel') }}?clienteGeneral=${clienteGeneralFilter}&startDate=${startDate}&endDate=${endDate}`">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block mx-auto" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path d="M6 2C4.9 2 4 2.9 4 4v16c0 1.1 0.9 2 2 2h12c1.1 0 2-0.9 2-2V8l-6-6H6z" fill="#107C41" />
                        <path d="M14 2v6h6" fill="#0B5E30" />
                        <path d="M9 13l6 6M15 13l-6 6" stroke="#fff" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </a>

                <!-- Refrescar -->
                <button class="btn btn-secondary btn-sm px-3 py-2"
                    @click="
                startDate = '';
                endDate = '';
                marcaFilter = '';
                clienteGeneralFilter = '';
                fetchDataAndInitTable();">
                    <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 block mx-auto' fill='none'
                        viewBox='0 0 24 24' stroke='currentColor'>
                        <polyline points='23 4 23 10 17 10' stroke-linecap='round' />
                        <polyline points='1 20 1 14 7 14' stroke-linecap='round' />
                        <path d='M3.51 9a9 9 0 0114.36-3.36L23 10' stroke-linecap='round' />
                        <path d='M20.49 15a9 9 0 01-14.36 3.36L1 14' stroke-linecap='round' />
                    </svg>
                </button>
            </div>
        </div>




        <!-- Tabla y Paginación -->
        <div class="panel mt-6">
            <!-- Scroll horizontal superior -->
            <div id="scroll-top" class="custom-scroll overflow-x-auto mb-2 hidden" style="height: 12px;">
                <div id="scroll-top-inner" style="height: 1px;"></div>
            </div>
            <div class="relative overflow-x-auto custom-scroll">
                <!-- Preloader -->
                <div x-show="isLoading" x-transition class="absolute inset-0 flex items-center justify-center z-50">
                    <span class="relative flex items-center justify-center w-16 h-16">
                        <!-- Cuadrado blanco de fondo más grande -->
                        <span class="absolute w-14 h-14 bg-white rounded-md"></span>
                        <!-- Círculo animado -->
                        <span class="animate-ping inline-flex h-5 w-5 rounded-full bg-info"></span>
                    </span>
                </div>


                <!-- Tabla con clases Bootstraahi ep/DataTables -->
                <table id="myTable1" class="display table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th class="text-center px-4 py-2">ID</th>
                            <th class="text-center px-4 py-2">ACCIONES</th>
                            <th class="text-center px-4 py-2">N. TICKET</th>
                            <th class="text-center px-4 py-2">F. TICKET</th>
                            <th class="text-center px-4 py-2">F. VISITA</th>
                            <th class="text-center px-4 py-2">CATEGORIA</th>
                            <th class="text-center px-4 py-2">GENERAL</th>
                            <th class="text-center px-4 py-2">MARCA</th>
                            <th class="text-center px-4 py-2">MODELO</th>
                            <th class="text-center px-4 py-2">SERIE</th>
                            <th class="text-center px-4 py-2">CLIENTE</th>
                            <th class="text-center px-4 py-2">DIRECCIÓN</th>
                            <th class="text-center px-4 py-2">MÁS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se llenarán dinámicamente -->
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            <div id="pagination" class="flex flex-wrap justify-center gap-2 mt-4"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const topScroll = document.getElementById('scroll-top');
                const topInner = document.getElementById('scroll-top-inner');
                const scrolls = document.querySelectorAll('.custom-scroll');
                const bottomScroll = scrolls[1]; // ✅ Usar el segundo .custom-scroll (el de la tabla)
                const table = document.querySelector('#myTable1');

                if (!topScroll || !topInner || !bottomScroll || !table) return;

                // Ajustar el ancho del scroll superior
                topInner.style.width = table.scrollWidth + 'px';
                topScroll.classList.remove('hidden');

                // Sincronizar scroll horizontal
                topScroll.onscroll = () => {
                    bottomScroll.scrollLeft = topScroll.scrollLeft;
                };
                bottomScroll.onscroll = () => {
                    topScroll.scrollLeft = bottomScroll.scrollLeft;
                };

            }, 1000);
        });
    </script>



    <!-- Scripts adicionales -->
    <script src="{{ asset('assets/js/tickets/smart/list.js') }}"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
</x-layout.default>
