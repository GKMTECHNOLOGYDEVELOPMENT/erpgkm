<x-layout.default>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Asociados</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Proveedores</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm flex items-center gap-2"
                        @click="exportTable('excel')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2 3 4 3Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Excel</span>
                    </button>

                    <!-- Botón Exportar a PDF -->
                    <button type="button" class="btn btn-danger btn-sm flex items-center gap-2"
                        @click="exportTable('pdf')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>PDF</span>
                    </button>

                    <!-- Botón Imprimir -->
                    <button type="button" class="btn btn-warning btn-sm flex items-center gap-2" @click="printTable">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V9H2V5C2 3.89543 2 3 4 3ZM2 9H22V15C22 16.1046 21.1046 17 20 17H4C2.89543 17 2 16.1046 2 15V9Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M9 17V21H15V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>Imprimir</span>
                    </button>

                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm flex items-center gap-2"
                        @click="$dispatch('toggle-modal')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M5 8C5 6.89543 5.89543 6 7 6H17C18.1046 6 19 6.89543 19 8V16C19 17.1046 18.1046 18 17 18H7C5.89543 18 5 17.1046 5 16V8Z" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 12H15" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 9V15" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 5H21" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 19H21" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                                                   
                        <span>Agregar</span>
                    </button>
                </div>
            </div>

            <table id="myTable1" class="whitespace-nowrap"></table>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Proveedor</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario -->
                        <form class="p-5 space-y-4" id="proveedorForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                    <input id="nombre" type="text" class="form-input w-full"
                                        placeholder="Ingrese el nombre">
                                </div>
                                <!-- Tipo de Documento -->
                                <div>
                                    <select id="idTipoDocumento" class="select2 w-full">
                                        <option value="" disabled selected>Seleccione un tipo de Documento
                                        </option>
                                        <option value="1">DNI</option>
                                        <option value="2">RUC</option>
                                    </select>
                                </div>
                                <!-- Número de Documento -->
                                <div>
                                    <label for="numeroDocumento" class="block text-sm font-medium">Número de
                                        Documento</label>
                                    <input id="numeroDocumento" type="text" class="form-input w-full"
                                        placeholder="Ingrese el número de documento">
                                </div>
                                <!-- País -->
                                <div>

                                    <select id="pais" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar País</option>
                                        <option value="1">País 1</option>
                                        <option value="2">País 2</option>
                                        <option value="3">País 3</option>
                                    </select>
                                </div>
                                <!-- Departamento -->
                                <div>

                                    <select id="departamento" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Departamento</option>
                                        <option value="1">Departamento 1</option>
                                        <option value="2">Departamento 2</option>
                                    </select>
                                </div>
                                <!-- Provincia -->
                                <div>

                                    <select id="provincia" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Provincia</option>
                                        <option value="1">Provincia 1</option>
                                        <option value="2">Provincia 2</option>
                                    </select>
                                </div>
                                <!-- Distrito -->
                                <div>

                                    <select id="distrito" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Distrito</option>
                                        <option value="1">Distrito 1</option>
                                        <option value="2">Distrito 2</option>
                                    </select>
                                </div>
                                <!-- Compra -->
                                <div>

                                    <select id="idCompra" class="select2 w-full">
                                        <option value="" disabled selected>Seleccione una Compra</option>
                                        <option value="1">Compra 1</option>
                                        <option value="2">Compra 2</option>
                                    </select>
                                </div>
                                <!-- Sucursal -->
                                <div>

                                    <select id="idSucursal" class="select2 w-full">
                                        <option value="" disabled selected>Seleccione una Sucursal</option>
                                        <option value="1">Sucursal 1</option>
                                        <option value="2">Sucursal 2</option>
                                    </select>
                                </div>
                                <!-- Código Postal -->
                                <div>
                                    <label for="codigoPostal" class="block text-sm font-medium">Código Postal</label>
                                    <input id="codigoPostal" type="text" class="form-input w-full"
                                        placeholder="Ingrese el código postal">
                                </div>
                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                    <input id="telefono" type="text" class="form-input w-full"
                                        placeholder="Ingrese el teléfono">
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" class="form-input w-full"
                                        placeholder="Ingrese el email">
                                </div>
                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                    <textarea id="direccion" class="form-input w-full" rows="3" placeholder="Ingrese la dirección"></textarea>
                                </div>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="open = false">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("multipleTable", () => ({
                datatable1: null,
                subsidiariosData: [], // Agrega una propiedad para almacenar los datos

                init() {
                    // Llamar a la API para obtener los datos de 'subsidiarios'
                    fetch('/api/subsidiarios') // Ajusta la URL si es necesario
                        .then(response => response.json())
                        .then(data => {
                            this.subsidiariosData = data;
                            // Ahora que tenemos los datos, inicializamos la tabla
                            this.datatable1 = new simpleDatatables.DataTable('#myTable1', {
                                data: {
                                    headings: ['ID', 'RUC', 'Nombre',
                                        'Nombre Contacto', 'Celular', 'Email',
                                        'Dirección', 'Referencia', 'ID Tienda',
                                        '<div class="text-center">Acciones</div>'
                                    ],
                                    data: this.subsidiariosData.map(subsidiario => [
                                        subsidiario.idSubsidiarios,
                                        subsidiario.ruc,
                                        subsidiario.nombre,
                                        subsidiario.nombre_contacto,
                                        subsidiario.celular,
                                        subsidiario.email,
                                        subsidiario.direccion,
                                        subsidiario.referencia,
                                        subsidiario.idTienda,
                                        ''
                                    ]),
                                },
                                searchable: true,
                                perPage: 10,
                                perPageSelect: [10, 20, 30, 50, 100],
                                columns: [{
                                        select: 0,
                                        render: (data, cell, row) => {
                                            return `<div class="flex items-center w-max">${data}</div>`;
                                        },
                                        sort: "asc"
                                    },
                                    {
                                        select: 8,
                                        sortable: false,
                                        render: (data, cell, row) => {
                                            return `<div class="flex items-center">
                                                <button type="button" x-tooltip="Editar">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                        <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                                        <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                                    </svg>
                                                </button>
                                                <button type="button" x-tooltip="Eliminar">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                        <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                        <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                        <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                        <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                        <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </button>
                                            </div>`;
                                        },
                                    }
                                ],
                                firstLast: true,
                                firstText: '<<',
                                lastText: '>>',
                                prevText: '<',
                                nextText: '>',
                                labels: {
                                    perPage: "{select}"
                                },
                                layout: {
                                    top: "{search}",
                                    bottom: "{info}{select}{pager}",
                                },
                            });
                        })
                        .catch(error => {
                            console.error('Error al obtener los datos:', error);
                        });
                },
            }));
        });

        // Inicializar Select2
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar todos los select con la clase "select2"
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
        // document.addEventListener("DOMContentLoaded", function() {
        //     // Inicializar Flatpickr en el campo de Fecha de Registro
        //     flatpickr("#fechaRegistro", {
        //         dateFormat: "Y-m-d", // Formato de fecha (Año-Mes-Día)
        //         enableTime: false, // Desactivar selección de hora
        //         locale: "es" // Cambiar idioma a español
        //     });
        // });
    </script>
    <script src="/assets/js/simple-datatables.js"></script>
    <!-- Script de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
