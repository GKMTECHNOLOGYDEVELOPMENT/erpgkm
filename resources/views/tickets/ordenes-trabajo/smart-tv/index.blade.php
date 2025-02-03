<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>

    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Tickets</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Ordenes de Trabajo</span>
                </li>
            </ul>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 mb-4">
            <!-- Fecha de Inicio -->
            <div>
                <label for="startDate" class="font-semibold block mb-1">Fecha Inicio</label>
                <input type="date"
                    id="startDate"
                    x-model="startDate"
                    class="p-2 border rounded-md w-full"
                    @input="fetchDataAndInitTable()" />
            </div>

            <!-- Fecha de Fin -->
            <div>
                <label for="endDate" class="font-semibold block mb-1">Fecha Fin</label>
                <input type="date"
                    id="endDate"
                    x-model="endDate"
                    class="p-2 border rounded-md w-full"
                    @input="fetchDataAndInitTable()" />
            </div>

            <!-- Filtrar por Marca -->
            <div x-data="{ marcas: [], marcaFilter: '' }"
                x-init="
                    fetch('http://127.0.0.1:8000/api/marcas')
                        .then(response => response.json())
                        .then(data => { marcas = data; })
                        .catch(error => console.error('Error loading marcas:', error))
                "
                class="mb-4">
                <label for="marcaFilter" class="font-semibold block mb-1">Filtrar por Marca</label>
                <select id="marcaFilter"
                    x-model="marcaFilter"
                    class="nice-select2 w-full p-2 border rounded-md"
                    @change="fetchDataAndInitTable()">
                    <option value="">Todas las marcas</option>
                    <template x-if="marcas.length > 0">
                        <template x-for="marca in marcas" :key="marca.idMarca">
                            <option :value="marca.idMarca" x-text="marca.nombre"></option>
                        </template>
                    </template>
                </select>
            </div>

            <!-- Enlace Agregar -->
            <a href="{{ route('ordenes.createsmart') }}" class="btn btn-primary btn-xs flex items-center gap-1 p-1 max-w-[50px]">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16M4 3h16c1.104 0 1.99.886 1.99 1.99L22 18H2V4.99C2 3.886 2.886 3 4 3z" />
                </svg>
                <span class="text-xs">Agregar</span>
            </a>

            <!-- Dropdown para Exportar a PDF y Excel -->
            <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                <!-- Botón de Dropdown -->
                <button class="btn btn-success dropdown-toggle" @click="toggle">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16M4 3h16c1.104 0 1.99.886 1.99 1.99L22 18H2V4.99C2 3.886 2.886 3 4 3z" />
                    </svg>
                    <span class="text-xs"> Exportar</span>
                </button>

                <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0 whitespace-nowrap">
                    <!-- Opción Exportar a PDF -->
                    <li><a href="javascript:;" class="dropdown-item" @click="window.location.href = '{{ route('reporte.clientes') }}'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3">
                                <path d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            PDF</a>
                    </li>

                    <!-- Opción Exportar a Excel -->
                    <li><a href="javascript:;" class="dropdown-item" onclick="window.location.href='{{ route('clientes.exportExcel') }}'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3">
                                <path d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2 3 4 3Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Excel</a>
                    </li>
                </ul>
            </div>


            <!-- Botón Refrescar -->
            <button type="button" class="btn btn-secondary btn-xs flex items-center gap-1 p-1 max-w-[50px]"
                @click="startDate = ''; endDate = ''; marcaFilter = ''; fetchDataAndInitTable()">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4V1L1 4l3 3V6a9 9 0 1 1 9 9h-3a6 6 0 1 0-6-6h2l-3 3 3 3 3-3h-2a9 9 0 0 1-9-9h3a6 6 0 1 0 6 6h-2l3-3-3-3z" />
                </svg>
                <span class="text-xs">Refrescar</span>
            </button>
        </div>

        <!-- Tabla y Paginación -->
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Aquí van otros elementos si es necesario -->
                </div>
            </div>

            <div class="relative">
                <table id="myTable1" class="whitespace-nowrap w-full"></table>
                <div id="pagination" class="flex justify-center mt-4"></div>

                <!-- Preloader dentro del contenedor de la tabla -->
                <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
                    <span class="w-10 h-10">
                        <span class="animate-ping inline-flex h-full w-full rounded-full bg-primary"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/tickets/smart/list.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>