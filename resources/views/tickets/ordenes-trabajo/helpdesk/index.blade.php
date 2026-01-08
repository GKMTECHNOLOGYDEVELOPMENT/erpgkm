    <x-layout.default>

        <!-- Scripts y estilos -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
            integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

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

            #myTable1 thead th {
                pointer-events: none;
            }

            /* SCROLLBAR MODERNO Y REDONDEADO */
            .custom-scroll {
                overflow-x: auto !important;
                scrollbar-width: thin;
                scrollbar-color: #a8a8a8 #e0e0e0;
            }

            .custom-scroll::-webkit-scrollbar {
                height: 10px;
                background: #e0e0e0;
                border-radius: 40px;
            }
        </style>

        <div x-data="multipleTable">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <ul class="flex flex-wrap space-x-2 rtl:space-x-reverse">
                    <li><a href="javascript:;" class="text-primary hover:underline">Tickets</a></li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Ordenes de Trabajo</span></li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Help Desk</span></li>
                </ul>
            </div>

            <!-- Filtros - Todos en una fila -->
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 mb-6 flex-wrap">
                <!-- Fecha de Inicio -->
                <div class="flex-1 min-w-[200px] sm:min-w-[180px] lg:min-w-[200px]">
                    <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                    <input type="text" id="startDate" x-model="startDate" placeholder="Seleccionar Fecha"
                        class="form-input w-full text-sm px-3 py-2" x-init="flatpickr($el, {
                            dateFormat: 'Y-m-d',
                            onChange: function(selectedDates, dateStr) {
                                startDate = dateStr;
                                debouncedFetch();
                            }
                        })" />
                </div>

                <!-- Fecha de Fin -->
                <div class="flex-1 min-w-[200px] sm:min-w-[180px] lg:min-w-[200px]">
                    <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                    <input type="text" id="endDate" x-model="endDate" placeholder="Seleccionar Fecha"
                        class="form-input w-full text-sm px-3 py-2" x-init="flatpickr($el, {
                            dateFormat: 'Y-m-d',
                            onChange: function(selectedDates, dateStr) {
                                endDate = dateStr;
                                debouncedFetch();
                            }
                        })" />
                </div>

                <!-- Filtrar por Cliente General -->
                <div class="flex-1 min-w-[200px] sm:min-w-[220px] lg:min-w-[250px]">
                    <label for="clienteGeneralFilter" class="block text-sm font-medium text-gray-700 mb-1">
                        Cliente General
                    </label>
                    <select id="clienteGeneralFilter" x-model="clienteGeneralFilter"
                        class="form-select w-full text-sm px-3 py-2 text-white-dark"
                        @change="
                    console.log('🔄 [SELECT CLIENTE] Cambio detectado:', $event.target.value);
                    // Limpiar filtro de contacto cuando cambia el cliente
                    contactoFinalFilter = '';
                    contactoFinalLoading = true;
                    
                    // Cargar contactos para este cliente
                    if ($event.target.value) {
                        fetchContactosPorCliente($event.target.value);
                    } else {
                        // Si no hay cliente seleccionado, limpiar contactos
                        contactosPorCliente = [];
                        contactoFinalLoading = false;
                    }
                    
                    isLoading = true;
                    if (debouncedFetch) debouncedFetch();
                ">
                        <option value="">Todos los clientes</option>
                        <template x-if="clienteGeneralesLoading">
                            <option disabled>Cargando clientes...</option>
                        </template>
                        <template x-if="!clienteGeneralesLoading && clienteGenerales.length === 0">
                            <option disabled>No hay clientes disponibles</option>
                        </template>
                        <template x-for="cliente in clienteGenerales" :key="cliente.idClienteGeneral">
                            <option :value="cliente.idClienteGeneral" x-text="cliente.descripcion"></option>
                        </template>
                    </select>
                </div>

                <!-- Filtrar por Contacto Final -->
                <div class="flex-1 min-w-[200px] sm:min-w-[220px] lg:min-w-[250px]">
                    <label for="contactoFinalFilter" class="block text-sm font-medium text-gray-700 mb-1">
                        Contacto Final
                    </label>
                    <select id="contactoFinalFilter" x-model="contactoFinalFilter"
                        class="form-select w-full text-sm px-3 py-2 text-white-dark"
                        @change="
                    console.log('🔄 [SELECT CONTACTO] Cambio detectado:', $event.target.value);
                    isLoading = true;
                    if (debouncedFetch) debouncedFetch();
                "
                        :disabled="!clienteGeneralFilter || contactoFinalLoading">
                        <option value="">Todos los contactos</option>
                        <template x-if="contactoFinalLoading">
                            <option disabled>Cargando contactos...</option>
                        </template>
                        <template x-if="!contactoFinalLoading && contactosPorCliente.length === 0 && clienteGeneralFilter">
                            <option disabled>No hay contactos para este cliente</option>
                        </template>
                        <template x-if="!contactoFinalLoading && !clienteGeneralFilter">
                            <option disabled>Seleccione un cliente primero</option>
                        </template>
                        <template x-for="contacto in contactosPorCliente" :key="contacto.idContactoFinal">
                            <option :value="contacto.idContactoFinal" x-text="contacto.nombre_completo"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Tabla y Paginación -->
            <div class="panel mt-6">
                <div id="scroll-top" class="custom-scroll overflow-x-auto mb-2 hidden" style="height: 12px;">
                    <div id="scroll-top-inner" style="height: 1px;"></div>
                </div>
                <div class="relative overflow-x-auto custom-scroll">
                    <!-- Preloader -->
                    <div x-show="isLoading" x-transition class="absolute inset-0 flex items-center justify-center z-50">
                        <span class="relative flex items-center justify-center w-16 h-16">
                            <span class="absolute w-14 h-14 bg-white rounded-md"></span>
                            <span class="animate-ping inline-flex h-5 w-5 rounded-full bg-info"></span>
                        </span>
                    </div>

                    <div class="mb-4 flex justify-between items-center gap-3">
                        <!-- Botones de Acción al lado izquierdo -->
                        <div class="flex gap-2">
                            @if (\App\Helpers\PermisoHelper::tienePermiso('CREAR NUEVA ORDEN DE TRABAJO HELPDESK'))
                                <a href="{{ route('ordenes.createhelpdesk') }}"
                                    class="btn btn-primary btn-sm flex items-center justify-center px-3 py-2 h-[38px] min-w-[40px]"
                                    title="Nueva Orden"> Agregar nueva Orden </a>
                            @endif

                            <a class="btn btn-success btn-sm flex items-center justify-center px-3 py-2 h-[38px] min-w-[40px]"
                                x-bind:href="`{{ route('ordenes.export.helpdesk.excel') }}?clienteGeneral=${clienteGeneralFilter}&contactoFinal=${contactoFinalFilter}&startDate=${startDate}&endDate=${endDate}`"
                                title="Exportar a Excel"> Exportar Excel

                            </a>


                            <button
                                class="btn btn-secondary btn-sm flex items-center justify-center px-3 py-2 h-[38px] min-w-[40px]"
                                @click="resetFilters" title="Resetear Filtros"> Resetear Filtros

                            </button>

                        </div>

                        <!-- Buscador al lado derecho (posición original) -->
                        <div class="flex gap-3">
                            <div class="relative w-64">
                                <input type="text" id="searchInput" placeholder="Buscar..."
                                    class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary h-[38px]">
                                <button type="button" id="clearInput"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>

                            <button id="btnSearch"
                                class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm flex items-center justify-center h-[38px] min-w-[80px]">
                                <span id="searchText">Buscar</span>
                                <span id="searchSpinner" class="hidden ml-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Tabla -->
                    <table id="myTable1" class="display table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th class="text-center px-4 py-2">ACCIONES</th>
                                <th class="text-center px-4 py-2">ID</th>
                                <th class="text-center px-4 py-2">N. TICKET</th>
                                <th class="text-center px-4 py-2">F. TICKET</th>
                                <th class="text-center px-4 py-2">F. VISITA</th>
                                <th class="text-center px-4 py-2">CLIENTE</th>
                                <th class="text-center px-4 py-2">TIENDA</th>
                                <th class="text-center px-4 py-2" style="display: none;">CLIENTE GENERAL</th>
                                <th class="text-center px-4 py-2" style="display: none;">CONTACTO FINAL</th>
                                <th style="display: none;">TIPO TEXTO</th>
                                <th class="text-center px-4 py-2">TIPO SERVICIO</th>
                                <th class="text-center px-4 py-2">MÁS</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="pagination" class="flex flex-wrap justify-center gap-2 mt-4"></div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#btnSearch').off('click').on('click', function() {
                    const btn = $(this);
                    const searchText = $('#searchText');
                    const spinner = $('#searchSpinner');

                    btn.prop('disabled', true);
                    searchText.text('Buscando...');
                    spinner.removeClass('hidden');

                    const value = $('#searchInput').val();

                    setTimeout(() => {
                        $('#myTable1').DataTable().search(value).draw();
                        btn.prop('disabled', false);
                        searchText.text('Buscar');
                        spinner.addClass('hidden');
                    }, 50);
                });

                $(document).on('keypress', '#searchInput', function(e) {
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
                    $('#myTable1').DataTable().search('').draw();
                });
            });
        </script>

        <script>
            window.permisosHelpdesk = {
                puedeEditar: {{ \App\Helpers\PermisoHelper::tienePermiso('EDITAR ORDEN DE TRABAJO HELPDESK') ? 'true' : 'false' }},
                puedeVerPDF: {{ \App\Helpers\PermisoHelper::tienePermiso('VER PDF ORDEN DE TRABAJO HELPDESK') ? 'true' : 'false' }},
                puedeVerEnvio: {{ \App\Helpers\PermisoHelper::tienePermiso('VER ENVIO ORDEN DE TRABAJO HELPDESK') ? 'true' : 'false' }}
            };
        </script>

        <!-- Scripts adicionales -->
        <script src="{{ asset('assets/js/tickets/helpdesk/list.js') }}"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    </x-layout.default>
