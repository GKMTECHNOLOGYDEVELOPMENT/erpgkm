<x-layout.default>

    <!-- Scripts y estilos -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
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
            /* Evita la interacción con el encabezado */
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
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Help Desk</span>
                </li>
            </ul>
        </div>

        <!-- Filtros -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Fecha de Inicio -->
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="text" id="startDate" x-model="startDate" placeholder="Seleccionar Fecha"
                    class="form-input w-full" x-init="flatpickr($el, {
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            startDate = dateStr;
                            debouncedFetch();
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
                            debouncedFetch();
                        }
                    })" />
            </div>

            <!-- Filtrar por Cliente General -->
            <div x-data="{
                clienteGenerales: [],
                isLoading: true,
                init() {
                    fetch('/api/clientegeneralfiltros/2')
                        .then(response => response.json())
                        .then(data => {
                            this.clienteGenerales = data;
                            this.isLoading = false;
            
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    const selectEl = document.getElementById('clienteGeneralFilter');
                                    if (selectEl) {
                                        if (typeof NiceSelect !== 'undefined') {
                                            NiceSelect.destroy(selectEl);
                                            NiceSelect.bind(selectEl);
                                        }
                                    }
                                }, 50);
                            });
                        })
                        .catch(error => {
                            console.error('Error cargando clientes:', error);
                            this.isLoading = false;
                        });
                }
            }">
                <label for="clienteGeneralFilter" class="block text-sm font-medium text-gray-700">
                    Filtrar por Cliente General
                </label>
                <select id="clienteGeneralFilter" x-model="$root.clienteGeneralFilter"
                    class="form-select w-full text-white-dark"
                    @change="
                        $root.isLoading = true;
                        $root.contactoFinalFilter = ''; // Limpiar contacto cuando cambia cliente
                        $root.debouncedFetch();
                    ">
                    <option value="">Todos los clientes generales</option>
                    <template x-if="isLoading">
                        <option disabled>Cargando clientes...</option>
                    </template>
                    <template x-if="!isLoading && clienteGenerales.length === 0">
                        <option disabled>No hay clientes disponibles</option>
                    </template>
                    <template x-for="cliente in clienteGenerales" :key="cliente.idClienteGeneral">
                        <option :value="cliente.idClienteGeneral" x-text="cliente.descripcion"></option>
                    </template>
                </select>
            </div>

            <!-- Filtrar por Contacto Final -->
            <div x-data="{
                contactosFinales: [],
                isLoading: false,
                init() {
                    // Cargar contactos cuando cambie el cliente general
                    this.$watch('$root.clienteGeneralFilter', (newValue) => {
                        this.cargarContactos(newValue);
                    });
                },
                cargarContactos(idClienteGeneral) {
                    if (!idClienteGeneral) {
                        this.contactosFinales = [];
                        return;
                    }
                    
                    this.isLoading = true;
                    fetch(`/api/contactos-finales/${idClienteGeneral}`)
                        .then(response => response.json())
                        .then(data => {
                            this.contactosFinales = data;
                            this.isLoading = false;
                            
                            // Recrear NiceSelect
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    const selectEl = document.getElementById('contactoFinalFilter');
                                    if (selectEl) {
                                        if (typeof NiceSelect !== 'undefined') {
                                            NiceSelect.destroy(selectEl);
                                            NiceSelect.bind(selectEl);
                                        }
                                    }
                                }, 50);
                            });
                        })
                        .catch(error => {
                            console.error('Error cargando contactos:', error);
                            this.isLoading = false;
                        });
                }
            }">
                <label for="contactoFinalFilter" class="block text-sm font-medium text-gray-700">
                    Filtrar por Contacto Final
                </label>
                <select id="contactoFinalFilter" x-model="$root.contactoFinalFilter" 
                        class="form-select w-full text-white-dark"
                        @change="$root.isLoading = true; $root.debouncedFetch();"
                        :disabled="!$root.clienteGeneralFilter">
                    <option value="">Todos los contactos</option>
                    <template x-if="isLoading">
                        <option disabled>Cargando contactos...</option>
                    </template>
                    <template x-if="!isLoading && contactosFinales.length === 0 && $root.clienteGeneralFilter">
                        <option disabled>No hay contactos para este cliente</option>
                    </template>
                    <template x-if="!$root.clienteGeneralFilter">
                        <option disabled>Seleccione un cliente general primero</option>
                    </template>
                    <template x-for="contacto in contactosFinales" :key="contacto.id">
                        <option :value="contacto.id" x-text="contacto.nombre_completo"></option>
                    </template>
                </select>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-wrap items-end gap-2">
                @if(\App\Helpers\PermisoHelper::tienePermiso('CREAR NUEVA ORDEN DE TRABAJO HELPDESK'))
                <!-- Botón Agregar -->
                <a href="{{ route('ordenes.createhelpdesk') }}" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block mx-auto" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="6" x2="12" y2="18" stroke-linecap="round" />
                        <line x1="6" y1="12" x2="18" y2="12" stroke-linecap="round" />
                    </svg>
                </a>
                @endif
                @if(\App\Helpers\PermisoHelper::tienePermiso('EXPORTAR ORDENES DE TRABAJO HELPDESK EXCEL'))
                <!-- Botón Exportar (Excel) -->
                <div x-data="{ open: false }" class="relative">
                    <a class="btn btn-success btn-sm"
                        x-bind:href="`{{ route('ordenes.export.helpdesk.excel') }}?clienteGeneral=${clienteGeneralFilter}&contactoFinal=${contactoFinalFilter}&startDate=${startDate}&endDate=${endDate}`">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block mx-auto" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M6 2C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2H6Z"
                                fill="#107C41" />
                            <path d="M14 2V8H20" fill="#0B5E30" />
                            <path d="M9 13L15 19" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M15 13L9 19" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
                @endif
                
                @if(\App\Helpers\PermisoHelper::tienePermiso('ACTUALIZAR TABLA ORDEN DE TRABAJO HELDESK'))
                <!-- Botón Refrescar -->
                <button class="btn btn-secondary btn-sm"
                    @click="
                        startDate = '';
                        endDate = '';
                        marcaFilter = '';
                        clienteGeneralFilter = '';
                        contactoFinalFilter = '';
                        document.getElementById('clienteGeneralFilter').value = '';
                        document.getElementById('contactoFinalFilter').value = '';
                        NiceSelect.destroy(document.getElementById('clienteGeneralFilter'));
                        NiceSelect.bind(document.getElementById('clienteGeneralFilter'));
                        NiceSelect.destroy(document.getElementById('contactoFinalFilter'));
                        NiceSelect.bind(document.getElementById('contactoFinalFilter'));
                        debouncedFetch();
                    ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block mx-auto" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="23 4 23 10 17 10" stroke-linecap="round" stroke-linejoin="round" />
                        <polyline points="1 20 1 14 7 14" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3.51 9a9 9 0 0114.36-3.36L23 10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.49 15a9 9 0 01-14.36 3.36L1 14" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                @endif
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
                        <!-- Cuadrado blanco de fondo más grande -->
                        <span class="absolute w-14 h-14 bg-white rounded-md"></span>
                        <!-- Círculo animado -->
                        <span class="animate-ping inline-flex h-5 w-5 rounded-full bg-info"></span>
                    </span>
                </div>

                <div class="mb-4 flex justify-end items-center gap-3">
                    <!-- Input con ícono para limpiar -->
                    <div class="relative w-64">
                        <input type="text" id="searchInput" placeholder="Buscar..."
                            class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                        <!-- Botón de limpiar -->
                        <button type="button" id="clearInput"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>

                    <!-- Botón Buscar -->
                    <button id="btnSearch"
                        class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm flex items-center justify-center">
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

                <!-- Tabla con clases Bootstraahi ep/DataTables -->
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
                            <th style="display: none;">TIPO TEXTO</th>
                            <th class="text-center px-4 py-2">TIPO SERVICIO</th>
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
            // Botón buscar
            $('#btnSearch').off('click').on('click', function() {
                const btn = $(this);
                const searchText = $('#searchText');
                const spinner = $('#searchSpinner');

                // Mostrar spinner y cambiar texto
                btn.prop('disabled', true);
                searchText.text('Buscando...');
                spinner.removeClass('hidden');

                const value = $('#searchInput').val();

                // Usar setTimeout para permitir que la UI se actualice antes de la búsqueda
                setTimeout(() => {
                    $('#myTable1').DataTable().search(value).draw();

                    // Restaurar botón después de la búsqueda
                    btn.prop('disabled', false);
                    searchText.text('Buscar');
                    spinner.addClass('hidden');
                }, 50);
            });

            // Enter para buscar
            $(document).on('keypress', '#searchInput', function(e) {
                if (e.which === 13) {
                    $('#btnSearch').click();
                }
            });

            // Mostrar botón limpiar si hay texto
            const input = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearInput');

            input.addEventListener('input', () => {
                clearBtn.classList.toggle('hidden', input.value.trim() === '');
            });

            // Botón limpiar
            clearBtn.addEventListener('click', () => {
                input.value = '';
                clearBtn.classList.add('hidden');
                $('#myTable1').DataTable().search('').draw();
            });
        });
    </script>

    <!-- Agrega esto antes de cargar tu script list.js -->
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
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>