<x-layout.default>
    <!-- Scripts y estilos -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Fecha de Inicio -->
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="text" id="startDate" x-model="startDate" placeholder="Seleccionar Fecha"
                    class="form-input w-full" x-init="flatpickr($el, {
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            startDate = dateStr;
                            fetchDataAndInitTable();
                        }
                    })" />
            </div>
            <!-- Fecha de Fin -->
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="text" id="endDate" x-model="endDate" placeholder="Seleccionar Fecha"
                    class="form-input w-full" x-init="flatpickr($el, {
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            endDate = dateStr;
                            fetchDataAndInitTable();
                        }
                    })" />
            </div>
            <!-- Filtrar por Marca
        <div x-data="{ marcas: [], marcaFilter: '' }" x-init="fetch('http://127.0.0.1:8000/api/marcas')
            .then(response => response.json())
            .then(data => {
                marcas = data;
                $nextTick(() => { new NiceSelect(document.getElementById('marcaFilter')); });
            })
            .catch(error => console.error('Error loading marcas:', error))">
          <label for="marcaFilter" class="block text-sm font-medium text-gray-700">Filtrar por Marca</label>
          <select id="marcaFilter" x-model="marcaFilter"
            class="form-select w-full text-white-dark" @change="fetchDataAndInitTable()">
            <option value="">Todas las marcas</option>
            <template x-for="marca in marcas" :key="marca.idMarca">
              <option :value="marca.idMarca" x-text="marca.nombre"></option>
            </template>
          </select>
        </div> -->


            <!-- Filtrar por Cliente General -->
            <div x-data="{ clienteGenerales: [], clienteGeneralFilter: '' }" x-init="fetch('/api/clientegenerales')
                .then(response => response.json())
                .then(data => {
                    console.log('Datos de clientes generales:', data); // Añadir log para inspeccionar los datos
                    clienteGenerales = data;
                    $nextTick(() => { new NiceSelect(document.getElementById('clienteGeneralFilter')); });
                })
                .catch(error => console.error('Error al cargar clientes generales:', error))">
                <label for="clienteGeneralFilter" class="block text-sm font-medium text-gray-700">Filtrar por Cliente
                    General</label>
                <select id="clienteGeneralFilter" x-model="clienteGeneralFilter"
                    class="form-select w-full text-white-dark" @change="fetchDataAndInitTable()">
                    <option value="">Todos los clientes generales</option>
                    <template x-for="cliente in clienteGenerales" :key="cliente.idClienteGeneral">
                        <option :value="cliente.idClienteGeneral" x-text="cliente.descripcion"></option>
                    </template>
                </select>
            </div>




            <!-- Botones de Acción -->
            <div class="flex flex-wrap items-end gap-2">
                <!-- Botón Agregar -->
                <a href="{{ route('ordenes.createsmart') }}" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block mx-auto" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </a>
                <!-- Botón Exportar (Excel) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="btn btn-success btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block mx-auto" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M6 2C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2H6Z"
                                fill="#2E7D32" />
                            <path d="M14 2V8H20" fill="#1B5E20" />
                            <path d="M9 13L15 19" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M15 13L9 19" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <!-- Botón Refrescar -->
                <button @click="startDate = ''; endDate = ''; marcaFilter = ''; fetchDataAndInitTable()"
                    class="btn btn-secondary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block mx-auto" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <polyline points="23 4 23 10 17 10" />
                        <polyline points="1 20 1 14 7 14" />
                        <path d="M3.51 9a9 9 0 0114.36-3.36L23 10" />
                        <path d="M20.49 15a9 9 0 01-14.36 3.36L1 14" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tabla y Paginación -->
        <div class="panel mt-6">
            <div class="relative overflow-x-auto">
                <!-- Tabla con clases Bootstrap/DataTables -->
                <table id="myTable1" class="display table table-striped table-bordered dt-responsive nowrap">
                  <thead>
                      <tr>
                          <th class="text-center px-4 py-2">EDITAR</th>
                          <th class="text-center px-4 py-2">N. TICKET</th>
                          <th class="text-center px-4 py-2">F. TICKET</th>
                          <th class="text-center px-4 py-2">F. VISITA</th>
                          <th class="text-center px-4 py-2">CATEGORIA</th>
                          <th class="text-center px-4 py-2">GENERAL</th>
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
                
                <!-- Preloader -->
                <div x-show="isLoading"
                    class="absolute inset-0 flex items-center justify-center bg-white bg-opaacity-75">
                    <span class="w-10 h-10">
                        <span class="animate-ping inline-flex h-full w-full rounded-full bg-primary"></span>
                    </span>
                </div>
            </div>
            <!-- Paginación -->
            <div id="pagination" class="flex flex-wrap justify-center gap-2 mt-4"></div>
        </div>
    </div>

    <!-- Scripts adicionales -->
    <script src="{{ asset('assets/js/tickets/smart/list.js') }}"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
