<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }
    </style>
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Asociados</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Compras</span>
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
                            <path d="M6 2L3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6L18 2H6Z" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 6H21" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 10C16 11.1046 15.1046 12 14 12C12.8954 12 12 11.1046 12 10C12 8.89543 12.8954 8 14 8C15.1046 8 16 8.89543 16 10Z" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 12H8C6.89543 12 6 11.1046 6 10C6 8.89543 6.89543 8 8 8H10" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>                          
                        <span>Agregar</span>
                    </button>
                </div>
            </div>


            <table id="myTable1" class="whitespace-nowrap"></table>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Compra</h5>
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
                        <form class="p-5 space-y-4" id="compraForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Serie -->
                                <div>
                                    <label for="serie" class="block text-sm font-medium">Serie</label>
                                    <input id="serie" type="text" class="form-input w-full"
                                        placeholder="Ingrese la serie" required />
                                </div>
                                <!-- Número -->
                                <div>
                                    <label for="nro" class="block text-sm font-medium">Número</label>
                                    <input id="nro" type="number" class="form-input w-full"
                                        placeholder="Ingrese el número" required />
                                </div>
                                <!-- Fecha de Emisión -->
                                <div x-data="form">
                                    <label for="fechaEmision" class="block text-sm font-medium">Fecha de
                                        Emisión</label>
                                    <input id="fechaEmision" x-model="date2" class="form-input w-full"
                                        placeholder="Seleccione la fecha y hora" />
                                </div>
                                <!-- Fecha de Vencimiento -->
                                <div x-data="formVencimiento">
                                    <label for="fechaVencimiento" class="block text-sm font-medium">Fecha de
                                        Vencimiento</label>
                                    <input id="fechaVencimiento" x-model="dateVencimiento" class="form-input w-full"
                                        placeholder="Seleccione la fecha y hora" required />
                                </div>
                                <!-- Campo de Imagen con Previsualización -->
                                <div class="mb-5" x-data="{ imagenPreview: null }">
                                    <label for="imagen" class="block text-sm font-medium mb-2">Imagen</label>
                                    <!-- Input de archivo -->
                                    <input id="imagen" type="file" accept="image/*"
                                        class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                        @change="imagenPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" />

                                    <!-- Contenedor de Previsualización -->
                                    <div
                                        class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
                                        <template x-if="imagenPreview">
                                            <img :src="imagenPreview" alt="Previsualización de la imagen"
                                                class="w-40 h-40 object-cover">
                                        </template>
                                        <template x-if="!imagenPreview">
                                            <img src="/assets/images/file-preview.svg" alt="Imagen predeterminada"
                                                class="w-40 h-40 object-cover">
                                        </template>
                                    </div>
                                </div>
                                <!-- Porcentaje Sujeto -->
                                <div>
                                    <label for="sujetoporcentaje" class="block text-sm font-medium">Sujeto
                                        Porcentaje</label>
                                    <input id="sujetoporcentaje" type="number" step="0.01"
                                        class="form-input w-full" placeholder="Ingrese el porcentaje" required />
                                    <!-- Cantidad -->
                                    <div class="mt-5">
                                        <label for="cantidad" class="block text-sm font-medium">Cantidad</label>
                                        <input id="cantidad" type="number" class="form-input w-full"
                                            placeholder="Ingrese la cantidad" required />
                                    </div>
                                    <!-- Gravada -->
                                    <div class="mt-5">
                                        <label for="gravada" class="block text-sm font-medium">Gravada</label>
                                        <input id="gravada" type="number" step="0.01"
                                            class="form-input w-full" placeholder="Ingrese la gravada" required />
                                    </div>
                                </div>


                                <!-- IGV -->
                                <div>
                                    <label for="igv" class="block text-sm font-medium">IGV</label>
                                    <input id="igv" type="number" step="0.01" class="form-input w-full"
                                        placeholder="Ingrese el IGV" required />
                                </div>
                                <!-- Total -->
                                <div>
                                    <label for="total" class="block text-sm font-medium">Total</label>
                                    <input id="total" type="number" step="0.01" class="form-input w-full"
                                        placeholder="Ingrese el total" required />
                                </div>
                                <!-- Moneda -->
                                <div>
                                    <select id="idMonedas" x-model="formData.idMonedas" class="select2">
                                        <option value="" disabled selected>Seleccionar Moneda</option>
                                        <option value="1">Moneda 1</option>
                                        <option value="2">Moneda 2</option>
                                    </select>
                                </div>
                                <!-- Documento -->
                                <div>
                                    <select id="idDocumento" x-model="formData.idDocumento" class="select2">
                                        <option value="" disabled selected>Seleccionar Documento</option>
                                        <option value="1">Documento 1</option>
                                        <option value="2">Documento 2</option>
                                    </select>
                                </div>
                                <!-- Tipo de Pago -->
                                <div>
                                    <select id="idTipoPago" x-model="formData.idTipoPago" class="select2">
                                        <option value="" disabled selected>Seleccionar Tipo de Pago</option>
                                        <option value="1">Tipo Pago 1</option>
                                        <option value="2">Tipo Pago 2</option>
                                    </select>
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
                clientData: [], // Propiedad para almacenar los datos de los clientes

                init() {
                    // Llamar a la API para obtener los datos de 'clientegeneral'
                    fetch('/api/clientegeneral')
                        .then(response => response.json())
                        .then(data => {
                            this.clientData = data;
                            // Ahora que tenemos los datos, inicializamos la tabla
                            this.datatable1 = new simpleDatatables.DataTable('#myTable1', {
                                data: {
                                    headings: ['Descripción', 'Estado', 'Foto',
                                        '<div class="text-center">Action</div>'
                                    ],
                                    data: this.clientData.map(cliente => [
                                        cliente.descripcion,
                                        cliente.estado,
                                        `<img src="${cliente.foto}" class="w-10 h-10 rounded-full object-cover" />`,
                                        ''
                                    ]),
                                },
                                searchable: true,
                                perPage: 10,
                                perPageSelect: [10, 20, 30, 50, 100],
                                columns: [{
                                        select: 0,
                                        render: (data, cell, row) => {
                                            return data; // No necesitas modificar esto
                                        },
                                        sort: "asc"
                                    },
                                    {
                                        select: 2,
                                        render: (data, cell, row) => {
                                            return `<div class="flex items-center w-max"><img class="w-10 h-10 rounded-full object-cover" src="${data}" />${data}</div>`;
                                        }
                                    },
                                    {
                                        select: 3,
                                        sortable: false,
                                        render: (data, cell, row) => {
                                            return `<div class="flex items-center">
                                                <button type="button" class="ltr:mr-2 rtl:ml-2" x-tooltip="Edit">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                        <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                                    </svg>
                                                </button>
                                                <button type="button" x-tooltip="Delete">
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
                }
            }));
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar NiceSelect
            document.querySelectorAll(".select2").forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date2: '', // Este valor será actualizado dinámicamente
                init() {
                    // Obtener la fecha y hora actual
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = now.getHours();
                    const minutes = String(now.getMinutes()).padStart(2, '0');

                    // Convertir hora a formato AM/PM
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    const formattedHours = hours % 12 || 12; // Convertir hora 0 a 12 para AM/PM
                    this.date2 =
                        `${year}-${month}-${day} ${formattedHours}:${minutes} ${ampm}`; // Formato inicial

                    flatpickr(document.getElementById('fechaEmision'), {
                        defaultDate: now, // Mostrar la fecha y hora actual
                        enableTime: true, // Habilitar selector de tiempo
                        dateFormat: 'Y-m-d h:i K', // Formato con AM/PM
                        time_24hr: false, // Desactivar formato 24 horas
                        onChange: (selectedDates, dateStr) => {
                            this.date2 = dateStr; // Actualizar el modelo de Alpine.js
                        },
                    });
                },
            }));
        });
        document.addEventListener("alpine:init", () => {
            Alpine.data("formVencimiento", () => ({
                dateVencimiento: '', // Inicia vacío para que el usuario seleccione
                init() {
                    flatpickr(document.getElementById('fechaVencimiento'), {
                        enableTime: true, // Habilitar selector de tiempo
                        dateFormat: 'Y-m-d h:i K', // Formato con AM/PM
                        time_24hr: false, // Desactivar formato 24 horas
                        onChange: (selectedDates, dateStr) => {
                            this.dateVencimiento =
                                dateStr; // Actualizar el modelo de Alpine.js
                        },
                    });
                },
            }));
        });
    </script>

    <script src="/assets/js/simple-datatables.js"></script>
    <!-- Script de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</x-layout.default>
