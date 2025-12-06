<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- SlimSelect CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slim-select@1.27.1/dist/slimselect.css" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

    <style>
        #areasTable {
            min-width: 1000px;
        }

        .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            padding-right: 1.5rem;
            background-image: none;
        }

        .cliente-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 8px;
            background: white;
        }

        .cliente-item:last-child {
            margin-bottom: 0;
        }

        /* Estilos para SlimSelect */
        .ss-main {
            border: 1px solid #e0e6ed;
            border-radius: 4px;
            padding: 6px 12px;
            min-height: 42px;
            background: white;
        }

        .ss-main:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .ss-main .ss-values .ss-value {
            background-color: #3b82f6;
            color: white;
            border-radius: 4px;
        }

        .ss-content {
            z-index: 9999 !important;
        }

        /* Asegurar que SlimSelect se muestre sobre el modal */
        .fixed.inset-0 .ss-content {
            z-index: 10000 !important;
        }
    </style>

    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Administración</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Áreas</span>
                </li>
            </ul>
        </div>

        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex items-center flex-wrap mb-5">
                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm m-1" @click="$dispatch('toggle-modal-areas')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5"
                                d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        Agregar Área
                    </button>
                </div>
            </div>

            <div class="mb-4 flex justify-end items-center gap-3">
                <!-- Input de búsqueda -->
                <div class="relative w-64">
                    <input type="text" id="searchInput" placeholder="Buscar área..."
                        class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                    <button type="button" id="clearInput"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                <!-- Botón Buscar -->
                <button id="btnSearch"
                    class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm">
                    Buscar
                </button>
            </div>

            <table id="areasTable" class="w-full min-w-[1000px] table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Total Clientes</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Crear Área CON SLIMSELECT -->
    <div x-data="areasModal" class="mb-5">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Nueva Área</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="closeModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="submitForm" class="p-5 space-y-4">
                        @csrf

                        <!-- Nombre del Área -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium">Nombre del Área</label>
                            <input type="text" id="nombre" x-model="formData.nombre" class="form-input w-full"
                                placeholder="Ingrese el nombre del área" required>
                            <template x-if="errors.nombre">
                                <span class="text-red-500 text-sm" x-text="errors.nombre[0]"></span>
                            </template>
                        </div>

                        <!-- Clientes Generales CON SLIMSELECT -->
                        <div>
                            <label for="clientes_generales" class="block text-sm font-medium mb-2">Clientes Generales
                                Asociados</label>
                            <select id="clientes_generales" x-ref="clientesSelect" class="form-input w-full" multiple>
                                <!-- Las opciones se cargarán dinámicamente -->
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Seleccione los clientes generales que pertenecerán a
                                esta área</p>
                            <template x-if="errors.clientes_generales">
                                <span class="text-red-500 text-sm" x-text="errors.clientes_generales[0]"></span>
                            </template>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center mb-4">
                            <button type="button" class="btn btn-outline-danger" @click="closeModal()"
                                :disabled="loading">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4" :disabled="loading">
                                <template x-if="loading">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                </template>
                                <template x-if="!loading">
                                    <i class="fas fa-save mr-2"></i>
                                </template>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Ver Clientes del Área -->
    <div x-data="{ 
        open: false, 
        areaNombre: '', 
        clientes: [], 
        loading: false,
        searchTerm: '',
        filteredClientes: []
    }" class="mb-5" @toggle-modal-clientes.window="
        open = true;
        areaNombre = $event.detail.nombre;
        clientes = $event.detail.clientes || [];
        loading = false;
        searchTerm = '';
        filteredClientes = $event.detail.clientes || [];
    ">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">

                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg" x-text="'Clientes del Área: ' + areaNombre"></h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido del Modal -->
                    <div class="p-5 space-y-4">
                        <!-- Barra de búsqueda -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Buscar Cliente</label>
                            <div class="relative">
                                <input type="text" x-model="searchTerm" @input="filteredClientes = searchTerm ? 
                                        clientes.filter(cliente => 
                                            cliente.descripcion.toLowerCase().includes(searchTerm.toLowerCase()) ||
                                            cliente.idClienteGeneral.toString().includes(searchTerm)
                                        ) : clientes" class="form-input w-full pr-10"
                                    placeholder="Buscar por nombre o ID...">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen de clientes -->
                        <div class="p-3 bg-primary/10 rounded-lg">
                            <p class="text-sm text-primary font-medium text-center">
                                <i class="fas fa-users mr-2"></i>
                                Total: <span x-text="clientes.length"></span> cliente(s) |
                                Mostrando: <span x-text="filteredClientes.length"></span>
                            </p>
                        </div>

                        <!-- Lista de clientes -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Clientes Asociados</label>

                            <template x-if="loading">
                                <div class="text-center py-8">
                                    <i class="fas fa-spinner fa-spin text-2xl text-primary mb-2"></i>
                                    <p class="text-gray-500">Cargando clientes...</p>
                                </div>
                            </template>

                            <template x-if="!loading && filteredClientes.length > 0">
                                <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-lg">
                                    <div class="divide-y divide-gray-200">
                                        <template x-for="cliente in filteredClientes" :key="cliente.idClienteGeneral">
                                            <div class="cliente-item hover:bg-gray-50 transition-colors duration-200">
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-gray-800 truncate"
                                                        x-text="cliente.descripcion" x-tooltip="cliente.descripcion">
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        ID: <span x-text="cliente.idClienteGeneral"
                                                            class="font-mono"></span>
                                                    </p>
                                                </div>
                                                <span
                                                    class="flex-shrink-0 ml-3 text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full font-medium">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Activo
                                                </span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!loading && filteredClientes.length === 0 && clientes.length === 0">
                                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                    <i class="fas fa-users-slash text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-600">No hay clientes asignados</p>
                                    <p class="text-sm text-gray-500 mt-2">Esta área no tiene clientes generales
                                        asociados</p>
                                </div>
                            </template>

                            <template x-if="!loading && filteredClientes.length === 0 && clientes.length > 0">
                                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-600">No se encontraron resultados</p>
                                    <p class="text-sm text-gray-500 mt-2">No hay clientes que coincidan con tu búsqueda
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center pt-4 border-t">
                            <button type="button" class="btn btn-outline-primary" @click="open = false">
                                <i class="fas fa-times mr-2"></i>
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        window.sessionMessages = {
            success: '{{ session('success') }}',
            error: '{{ session('error') }}',
        };
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <!-- SlimSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/slim-select@1.27.1/dist/slimselect.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        document.addEventListener('alpine:init', () => {
            // DataTable principal para Áreas
            Alpine.data('multipleTable', () => ({
                datatable1: null,

                init() {
                    this.fetchDataAndInitTable();
                    this.initSearch();
                },

                async fetchDataAndInitTable() {
                    this.datatable1 = $('#areasTable').DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: '/areas/api/areas-data',
                            type: 'GET'
                        },
                        columns: [
                            {
                                data: 'nombre',
                                className: 'text-center',
                                render: nombre => `<div class="font-medium">${nombre}</div>`
                            },
                            {
                                data: 'total_clientes',
                                className: 'text-center',
                                render: total => {
                                    if (!total || total == 0) {
                                        return '<span class="badge badge-outline-warning">Sin clientes</span>';
                                    }
                                    return `<span class="badge badge-outline-primary">${total} clientes</span>`;
                                }
                            },
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'text-center',
                                render: (_, __, row) => {
                                    return `
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Botón para ver detalles de clientes -->
                                            <button type="button" class="ltr:mr-2 rtl:ml-2" x-tooltip="Ver Clientes" 
                                                onclick="window.verClientesArea(${row.idTipoArea}, '${row.nombre.replace(/'/g, "\\'")}')">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                    <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70431C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70431C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"/>
                                                    <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"/>
                                                </svg>
                                            </button>
                                            
                                            <a href="/areas/${row.idTipoArea}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                    <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                                </svg>
                                            </a>
                                            <button type="button" class="ltr:mr-2 rtl:ml-2" x-tooltip="Eliminar" onclick="window.showDeleteConfirmation(${row.idTipoArea}, '${row.nombre.replace(/'/g, "\\'")}')">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                    <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    `;
                                }
                            }
                        ],
                        responsive: false,
                        autoWidth: false,
                        pageLength: 10,
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
                        initComplete: function () {
                            const wrapper = document.querySelector('.dataTables_wrapper');
                            const table = wrapper.querySelector('#areasTable');

                            const scrollContainer = document.createElement('div');
                            scrollContainer.className = 'overflow-x-auto border rounded-md mb-2';
                            table.parentNode.insertBefore(scrollContainer, table);
                            scrollContainer.appendChild(table);

                            const scrollTop = document.createElement('div');
                            scrollTop.className = 'overflow-x-auto mb-2';
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
                            floatingControls.className = 'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
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
                },

                initSearch() {
                    // Botón buscar
                    $('#btnSearch').off('click').on('click', () => {
                        const value = $('#searchInput').val();
                        this.datatable1.search(value).draw();
                    });

                    // Enter para buscar
                    $(document).on('keypress', '#searchInput', (e) => {
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
                        this.datatable1.search('').draw();
                    });
                }
            }));

            // Modal de creación de áreas con SlimSelect
            Alpine.data('areasModal', () => ({
                open: false,
                formData: { nombre: '', clientes_generales: [] },
                errors: {},
                loading: false,
                slimSelectInstance: null,
                clientesOptions: [],

                init() {
                    // Escuchar evento para abrir el modal
                    this.$watch('open', (value) => {
                        if (value) {
                            setTimeout(() => {
                                this.initSlimSelect();
                            }, 100);
                        } else {
                            this.cleanupSlimSelect();
                        }
                    });

                    // Escuchar evento desde fuera
                    window.addEventListener('toggle-modal-areas', () => {
                        this.open = true;
                    });
                },

                // Cargar opciones de clientes
                async loadClientesOptions() {
                    try {
                        const response = await fetch('/areas/api/clientes-generales');
                        const clientes = await response.json();

                        this.clientesOptions = clientes.map(cliente => ({
                            value: cliente.idClienteGeneral.toString(),
                            text: cliente.descripcion
                        }));

                        return this.clientesOptions;
                    } catch (error) {
                        console.error('Error cargando clientes:', error);
                        toastr.error('Error al cargar los clientes generales', 'Error');
                        return [];
                    }
                },

                // Inicializar SlimSelect
                async initSlimSelect() {
                    // Cargar opciones primero
                    const options = await this.loadClientesOptions();

                    // Obtener el elemento select
                    const selectElement = this.$refs.clientesSelect;

                    // Limpiar opciones previas
                    selectElement.innerHTML = '';

                    // Agregar opciones
                    options.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.value;
                        optionElement.textContent = option.text;
                        selectElement.appendChild(optionElement);
                    });

                    // Destruir instancia anterior si existe
                    if (this.slimSelectInstance) {
                        this.slimSelectInstance.destroy();
                    }

                    // Crear nueva instancia de SlimSelect
                    this.slimSelectInstance = new SlimSelect({
                        select: selectElement,
                        placeholder: 'Seleccione clientes generales',
                        searchPlaceholder: 'Buscar clientes...',
                        searchText: 'No se encontraron resultados',
                        searchingText: 'Buscando...',
                        allowDeselect: true,
                        closeOnSelect: false,
                        hideSelectedOption: true,
                        showSearch: true,
                        searchHighlight: true,
                        limit: 10,
                        maxValuesShown: 3,
                        maxValuesMessage: 'Seleccionados: {number}',
                        addToBody: true, // IMPORTANTE: Esto permite que el dropdown se renderice fuera del modal
                    });
                },

                // Limpiar SlimSelect
                cleanupSlimSelect() {
                    if (this.slimSelectInstance) {
                        this.slimSelectInstance.destroy();
                        this.slimSelectInstance = null;
                    }

                    // Limpiar el select
                    const selectElement = this.$refs.clientesSelect;
                    if (selectElement) {
                        selectElement.innerHTML = '';
                    }

                    this.clientesOptions = [];
                },

                // Cerrar modal
                closeModal() {
                    this.open = false;
                    this.formData = { nombre: '', clientes_generales: [] };
                    this.errors = {};
                    this.cleanupSlimSelect();
                },

                // Enviar formulario
                async submitForm() {
                    this.loading = true;
                    this.errors = {};

                    try {
                        // Validar nombre
                        if (!this.formData.nombre || this.formData.nombre.trim() === '') {
                            throw new Error('El nombre del área es requerido');
                        }

                        const formData = new FormData();
                        formData.append('nombre', this.formData.nombre.trim());

                        // Obtener valores seleccionados
                        if (this.slimSelectInstance) {
                            const valoresSeleccionados = this.slimSelectInstance.selected();
                            if (valoresSeleccionados && valoresSeleccionados.length > 0) {
                                valoresSeleccionados.forEach(valor => {
                                    formData.append('clientes_generales[]', valor.value);
                                });
                            }
                        }

                        const response = await fetch('/areas', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Éxito
                            toastr.success(data.message, '¡Éxito!');
                            this.closeModal();

                            // Recargar tabla
                            const multipleTable = Alpine.$data(document.querySelector('[x-data="multipleTable"]'));
                            if (multipleTable && multipleTable.datatable1) {
                                multipleTable.datatable1.ajax.reload();
                            }
                        } else {
                            // Error de validación
                            if (data.errors) {
                                this.errors = data.errors;
                            }
                            throw new Error(data.message || 'Error al crear el área');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        if (!this.errors.nombre) {
                            toastr.error(error.message || 'Error al crear el área', 'Error');
                        }
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });

        // ========== FUNCIONES GLOBALES ==========

        // Función para mostrar confirmación de eliminación con SweetAlert2
        window.showDeleteConfirmation = function (idTipoArea, nombreArea) {
            Swal.fire({
                title: '¿Eliminar Área?',
                html: `¿Estás seguro de que deseas eliminar el área "<b>${nombreArea}</b>"?<br><br>
                       <small class="text-gray-500">Esta acción eliminará permanentemente el área y sus asociaciones con clientes.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i> Sí, eliminar',
                cancelButtonText: '<i class="fas fa-times mr-2"></i> Cancelar',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/areas/${idTipoArea}`, {
                        method: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    })
                        .then(async response => {
                            const data = await response.json();

                            if (!response.ok) {
                                throw new Error(data.message || "Error al eliminar el área.");
                            }

                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Error: ${error.message}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar Toastr de éxito
                    toastr.success('Área eliminada correctamente.', '¡Eliminado!');

                    // Recargar la tabla
                    const multipleTable = Alpine.$data(document.querySelector('[x-data="multipleTable"]'));
                    if (multipleTable && multipleTable.datatable1) {
                        multipleTable.datatable1.ajax.reload();
                    }
                }
            });
        };

        // Función antigua para compatibilidad
        window.deleteArea = function (idTipoArea) {
            // Llamar a la nueva función con un nombre por defecto
            window.showDeleteConfirmation(idTipoArea, 'esta área');
        };

        // Función para mostrar clientes en el modal
        window.verClientesArea = function (idArea, nombreArea) {
            // Hacer petición para obtener los clientes del área
            fetch(`/areas/${idArea}/clientes-modal`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar los clientes');
                    }
                    return response.json();
                })
                .then(data => {
                    // Disparar evento para abrir el modal
                    window.dispatchEvent(new CustomEvent('toggle-modal-clientes', {
                        detail: {
                            nombre: nombreArea,
                            clientes: data.clientes || []
                        }
                    }));
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('No se pudieron cargar los clientes', 'Error');
                });
        };

        // ========== INICIALIZACIÓN ==========

        document.addEventListener('DOMContentLoaded', function () {
            // Mostrar mensajes de sesión si existen
            if (window.sessionMessages.success) {
                toastr.success(window.sessionMessages.success, '¡Éxito!');
            }

            if (window.sessionMessages.error) {
                toastr.error(window.sessionMessages.error, 'Error');
            }
        });
    </script>
</x-layout.default>