<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .panel {
            overflow: visible !important;
        }

        #myTableContactoFinal {
            min-width: 1000px;
        }

        /* Estilos para el botón de limpiar búsqueda */
        #clearInputContacto {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        /* Ocultar el buscador de DataTables */
        .dataTables_filter {
            display: none !important;
        }

        /* Estilos para el selector de registros por página */
        .dataTables_length {
            margin-bottom: 15px;
        }

        /* Estilos para Select2 */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            min-height: 42px;
            border: 1px solid #e0e6ed;
            border-radius: 4px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered,
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            line-height: 42px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
            padding: 0 10px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }
    </style>

    <div x-data="contactoFinalTable">
        <!-- Breadcrumb -->
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Asociados</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Contactos Finales</span>
                </li>
            </ul>
        </div>

        <!-- Panel Principal -->
        <div class="panel mt-6">
            <!-- Botón Agregar Contacto -->
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <button type="button" class="btn btn-primary btn-sm flex items-center gap-2"
                        @click="$dispatch('toggle-modal')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5">
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5"
                                d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        <span>Agregar Contacto</span>
                    </button>
                </div>
            </div>

            <!-- Buscador Personalizado (ÚNICO BUSCADOR) -->
            <div class="mb-4 flex justify-end items-center gap-3">
                <div class="relative w-64">
                    <input type="text" id="searchInputContacto" placeholder="Buscar contactos..."
                        class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                    <button type="button" id="clearInputContacto"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <button id="btnSearchContacto"
                    class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm">
                    Buscar
                </button>
            </div>

            <!-- Tabla de Contactos Finales -->
            <table id="myTableContactoFinal" class="w-full min-w-[1000px] table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Tipo Documento</th>
                        <th>Número Documento</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Cliente General</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar contacto final -->
    <div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-2xl my-8 animate__animated animate__zoomInUp">

                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Contacto Final</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <form class="p-5 space-y-4" id="contactoFinalForm" method="post">
                        @csrf

                        <!-- Cliente General (Ocupa todo el ancho) -->
                        <div>
                            <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                            <select id="idClienteGeneral" name="idClienteGeneral[]" class="select2 form-input w-full" multiple>
                                @foreach ($clientesGenerales as $clienteGeneral)
                                    <option value="{{ $clienteGeneral->idClienteGeneral }}">
                                        {{ $clienteGeneral->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tipo Documento -->
                            <div>
                                <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                                <select id="idTipoDocumento" name="idTipoDocumento" class="form-select w-full">
                                    <option value="" disabled selected>Seleccionar Tipo Documento</option>
                                    @foreach ($tiposDocumento as $tipoDocumento)
                                        <option value="{{ $tipoDocumento->idTipoDocumento }}">
                                            {{ $tipoDocumento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Número Documento -->
                            <div>
                                <label for="numero_documento" class="block text-sm font-medium">Número Documento</label>
                                <input id="numero_documento" type="text" name="numero_documento"
                                    class="form-input w-full" placeholder="Ingrese el número de documento">
                            </div>

                            <!-- Nombre Completo -->
                            <div class="md:col-span-2">
                                <label for="nombre_completo" class="block text-sm font-medium">Nombre Completo</label>
                                <input id="nombre_completo" type="text" name="nombre_completo" class="form-input w-full"
                                    placeholder="Ingrese el nombre completo">
                            </div>

                            <!-- Correo -->
                            <div>
                                <label for="correo" class="block text-sm font-medium">Correo</label>
                                <input id="correo" type="email" name="correo" class="form-input w-full"
                                    placeholder="Ingrese el correo">
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                <input id="telefono" type="text" name="telefono" class="form-input w-full"
                                    placeholder="Ingrese el teléfono">
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium">Estado</label>
                                <select id="estado" name="estado" class="form-select w-full">
                                    <option value="Activo" selected>Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger" @click="open = false">Cancelar</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Eliminar -->
    <div x-data="confirmModal" class="mb-5">
        <!-- Modal -->
        <div class="fixed inset-0 bg-[black]/60 z-[9999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-md">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg">Confirmar Eliminación</div>
                        <button type="button" class="text-white-dark hover:text-dark" @click="close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="text-center">
                            <!-- Icono de advertencia -->
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">¿Estás seguro?</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Esta acción eliminará permanentemente el contacto. Esta acción no se puede deshacer.
                            </p>
                        </div>
                        <div class="flex justify-center items-center gap-3 mt-6">
                            <button type="button" class="btn btn-outline-secondary w-full" @click="close">
                                Cancelar
                            </button>
                            <button type="button" class="btn btn-danger w-full" @click="confirmDelete">
                                Sí, Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Configuración global de Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Inicializar Select2
        $(document).ready(function() {
            // Inicializar Select2 para Cliente General
            $('#idClienteGeneral').select2({
                placeholder: "Seleccionar Cliente General",
                allowClear: true,
                width: '100%'
            });
        });

        document.addEventListener("alpine:init", () => {
            // Modal de confirmación
            Alpine.data("confirmModal", () => ({
                open: false,
                contactoId: null,
                contactoNombre: null,

                openModal(id, nombre) {
                    this.contactoId = id;
                    this.contactoNombre = nombre;
                    this.open = true;
                },

                close() {
                    this.open = false;
                    this.contactoId = null;
                    this.contactoNombre = null;
                },

                async confirmDelete() {
                    if (!this.contactoId) {
                        this.close();
                        return;
                    }

                    try {
                        const response = await fetch(`/contactofinal/${this.contactoId}`, {
                            method: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || "Error al eliminar contacto.");
                        }

                        // Toastr success
                        toastr.success(data.message || 'Contacto eliminado correctamente.',
                            '¡Eliminado!');

                        // Recargar tabla
                        if ($.fn.DataTable.isDataTable('#myTableContactoFinal')) {
                            $('#myTableContactoFinal').DataTable().ajax.reload(null, false);
                        }

                    } catch (error) {
                        // Toastr error
                        toastr.error(error.message || 'Ocurrió un error al eliminar el contacto.',
                            'Error');
                    } finally {
                        this.close();
                    }
                }
            }));

            // DataTable principal
            Alpine.data("contactoFinalTable", () => ({
                datatable: null,
                searchInput: null,
                confirmModal: null,

                init() {
                    this.fetchDataAndInitTable();
                    this.initSearchFunctionality();

                    // Obtener referencia al modal de confirmación
                    this.confirmModal = Alpine.$data(document.querySelector('[x-data="confirmModal"]'));
                },

                async fetchDataAndInitTable() {
                    this.datatable = $('#myTableContactoFinal').DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: "{{ route('contactofinal.getAll') }}",
                            type: "GET"
                        },
                        columns: [{
                                data: 'tipo_documento',
                                className: 'text-center'
                            },
                            {
                                data: 'numero_documento',
                                className: 'text-center'
                            },
                            {
                                data: 'nombre_completo',
                                className: 'text-center'
                            },
                            {
                                data: 'correo',
                                className: 'text-center',
                                render: email => email ||
                                    '<span class="text-gray-400">N/A</span>'
                            },
                            {
                                data: 'telefono',
                                className: 'text-center',
                                render: telefono => telefono ||
                                    '<span class="text-gray-400">N/A</span>'
                            },
                            {
                                data: 'clientes_generales',
                                className: 'text-center',
                                render: function(data, type, row) {
                                    if (data && data.length > 0) {
                                        let html = '<div class="flex flex-wrap gap-1 justify-center">';
                                        data.forEach(function(cliente) {
                                            html += `<span class="badge badge-outline-primary">${cliente.descripcion}</span>`;
                                        });
                                        html += '</div>';
                                        return html;
                                    }
                                    return '<span class="text-gray-400">N/A</span>';
                                }
                            },
                            {
                                data: 'estado',
                                className: 'text-center',
                                render: estado => estado === 'Activo' ?
                                    '<span class="badge badge-outline-success">Activo</span>' :
                                    '<span class="badge badge-outline-danger">Inactivo</span>'
                            },
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'text-center',
                                render: (_, __, row) => {
                                    return `
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="/contactofinal/${row.idContactoFinal}/edit" 
                                           class="text-gray-600 hover:text-gray-800"
                                           x-tooltip="Editar">
                                            <svg width="24" height="24" class="w-4.5 h-4.5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M15.29 3.15L14.36 4.08 5.84 12.6c-.58.58-.87.87-1.11 1.2-.29.38-.54.79-.75 1.2-.17.36-.3.73-.56 1.51L2.32 19.8l-.27.8c-.13.38-.03.8.27 1.1.3.3.72.4 1.1.27l.8-.27 3.28-1.1c.78-.26 1.15-.39 1.51-.56.41-.21.82-.46 1.2-.75.33-.24.62-.53 1.2-1.11l8.52-8.52.93-.93c1.54-1.54 1.54-4.04 0-5.58-1.54-1.54-4.04-1.54-5.58 0z" stroke-width="1.5"/>
                                                <path d="M14.36 4.08s.12 1.97 1.85 3.74c1.73 1.77 3.74 1.79 3.74 1.79M4.2 21.68l-1.88-1.88" opacity="0.5" stroke-width="1.5"/>
                                            </svg>
                                        </a>
                                        <button type="button" 
                                                class="text-gray-600 hover:text-gray-800" 
                                                x-tooltip="Eliminar" 
                                                onclick="window.deleteContactoFinal(${row.idContactoFinal}, '${row.nombre_completo.replace(/'/g, "\\'")}')">
                                            <svg width="24" height="24" class="w-5 h-5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M9.17 4c.41-1.17 1.52-2 2.83-2s2.42.83 2.83 2" opacity="0.5" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M20.5 6h-17" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M18.83 8.5l-.46 6.9c-.18 2.65-.27 3.97-1.13 4.78-.86.8-2.19.82-4.85.82h-.77c-2.66 0-4 .02-4.85-.82-.86-.81-.95-2.13-1.13-4.78l-.46-6.9" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M9.5 11l.5 5" opacity="0.5" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M14.5 11l-.5 5" opacity="0.5" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                `;
                                }
                            }
                        ],
                        responsive: true,
                        autoWidth: false,
                        order: [
                            [0, 'desc']
                        ],
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50],
                        // CONFIGURACIÓN MODIFICADA - TODO DEBAJO DE LA TABLA
                        dom: 't<"flex flex-col md:flex-row justify-between items-center mt-4 space-y-4 md:space-y-0"<"flex items-center justify-start w-full md:w-auto order-1"l><"flex items-center justify-center w-full md:w-auto order-2"i><"flex items-center justify-end w-full md:w-auto order-3"p>>',
                        language: {
                            search: '', // Vacío para que no muestre el label de búsqueda
                            searchPlaceholder: '', // Vacío también
                            zeroRecords: 'No se encontraron registros',
                            lengthMenu: 'Mostrar _MENU_ registros por página',
                            loadingRecords: 'Cargando...',
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
                        drawCallback: function() {
                            // Actualizar información de paginación
                            const info = this.api().page.info();
                            $('.dataTables_info').text(
                                `Mostrando ${info.start + 1} a ${info.end} de ${info.recordsTotal} registros`
                            );

                            // Agregar clases adicionales para centrar elementos
                            $('.dataTables_length').addClass('text-center');
                            $('.dataTables_info').addClass('text-center');
                        },
                        initComplete: function() {
                            // Agregar clases después de la inicialización
                            $('.dataTables_length').addClass('text-center');
                            $('.dataTables_info').addClass('text-center');

                            // Centrar el selector de registros
                            const lengthSelect = $('.dataTables_length select');
                            if (lengthSelect.length) {
                                lengthSelect.addClass('mx-auto');
                            }
                        }
                    });
                },

                initSearchFunctionality() {
                    const searchInput = document.getElementById('searchInputContacto');
                    const searchBtn = document.getElementById('btnSearchContacto');
                    const clearBtn = document.getElementById('clearInputContacto');

                    // Esperar a que DataTable se inicialice
                    setTimeout(() => {
                        // Buscar al hacer clic
                        searchBtn.addEventListener('click', () => {
                            if (this.datatable) {
                                this.datatable.search(searchInput.value).draw();
                            }
                        });

                        // Buscar al presionar Enter
                        searchInput.addEventListener('keypress', (e) => {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                if (this.datatable) {
                                    this.datatable.search(searchInput.value).draw();
                                }
                            }
                        });

                        // Mostrar/ocultar botón de limpiar
                        searchInput.addEventListener('input', () => {
                            if (searchInput.value.length > 0) {
                                clearBtn.classList.remove('hidden');
                            } else {
                                clearBtn.classList.add('hidden');
                                if (this.datatable) {
                                    this.datatable.search('').draw();
                                }
                            }
                        });

                        // Limpiar búsqueda
                        clearBtn.addEventListener('click', () => {
                            searchInput.value = '';
                            clearBtn.classList.add('hidden');
                            if (this.datatable) {
                                this.datatable.search('').draw();
                            }
                            searchInput.focus();
                        });
                    }, 1000);
                }
            }));
        });

        // Función global para abrir el modal de confirmación
        window.deleteContactoFinal = function(idContactoFinal, nombreCompleto) {
            const confirmModal = Alpine.$data(document.querySelector('[x-data="confirmModal"]'));
            if (confirmModal) {
                confirmModal.openModal(idContactoFinal, nombreCompleto);
            }
        };

        // Form submission para contacto final
        document.getElementById('contactoFinalForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            // También puedes usar JSON si prefieres
            let data = {
                nombre_completo: document.getElementById('nombre_completo').value,
                idTipoDocumento: document.getElementById('idTipoDocumento').value,
                numero_documento: document.getElementById('numero_documento').value,
                correo: document.getElementById('correo').value,
                telefono: document.getElementById('telefono').value,
                estado: document.getElementById('estado').value,
                idClienteGeneral: Array.from(document.getElementById('idClienteGeneral').selectedOptions).map(option => option.value)
            };

            fetch('/contactofinal/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Toastr success
                        toastr.success(data.message, '¡Éxito!');

                        // Limpiar formulario
                        document.getElementById('contactoFinalForm').reset();
                        
                        // Resetear Select2
                        $('#idClienteGeneral').val(null).trigger('change');

                        // Recargar tabla
                        if ($.fn.DataTable.isDataTable('#myTableContactoFinal')) {
                            $('#myTableContactoFinal').DataTable().ajax.reload(null, false);
                        }

                        // Cerrar modal
                        const modalEvent = new CustomEvent('toggle-modal');
                        window.dispatchEvent(modalEvent);

                    } else {
                        let errorMessage = data.message || 'Error desconocido';
                        if (data.errors) {
                            errorMessage = Object.values(data.errors).flat().join(', ');
                        }

                        // Toastr error
                        toastr.error(errorMessage, 'Error');
                    }
                })
                .catch(error => {
                    console.error('Error en fetch:', error);
                    // Toastr error de conexión
                    toastr.error('No se pudo conectar con el servidor. Verifica tu conexión.',
                        'Error de conexión');
                });
        });

        // Mostrar mensajes de sesión si existen
        @if (session('success'))
            toastr.success('{{ session('success') }}', '¡Éxito!');
        @endif

        @if (session('error'))
            toastr.error('{{ session('error') }}', 'Error');
        @endif

        @if (session('info'))
            toastr.info('{{ session('info') }}', 'Información');
        @endif

        @if (session('warning'))
            toastr.warning('{{ session('warning') }}', 'Advertencia');
        @endif
    </script>
</x-layout.default>