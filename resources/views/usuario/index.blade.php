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
                    <a href="javascript:;" class="text-primary hover:underline">Administración</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Usuarios</span>
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
                    <a href="{{ route('usuario.create') }}" class="btn btn-primary btn-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21"
                            fill="none">
                            <path
                                d="M3 8H21M3 8L5 5H19L21 8M3 8V19C3 19.5523 3.44772 20 4 20H7C7.55228 20 8 19.5523 8 19V14C8 13.4477 8.44772 13 9 13H15C15.5523 13 16 13.4477 16 14V19C16 19.5523 16.4477 20 17 20H20C20.5523 20 21 19.5523 21 19V8M8 13V11C8 10.4477 8.44772 10 9 10H15C15.5523 10 16 10.4477 16 11V13"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6 11H10M14 11H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span>Agregar</span>
                    </a>
                </div>
            </div>
            <table id="myTable1" class="whitespace-nowrap"></table>
        </div>

        <!-- Modal -->
        <div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                    <div x-show="open" x-transition.duration.300
                        class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                        <!-- Header del Modal -->
                        <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                            <h5 class="font-bold text-lg">Agregar Usuario</h5>
                            <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div>
                            <!-- Formulario -->
                            <form class="p-5 space-y-4">
                                <!-- Rejilla de dos columnas -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nombre completo -->
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                        <input id="nombre" name="nombre" type="text"
                                            class="form-input w-full" placeholder="Nombre">
                                    </div>
                                    <div>
                                        <label for="apellidoPaterno" class="block text-sm font-medium">Apellido
                                            Paterno</label>
                                        <input id="apellidoPaterno" name="apellidoPaterno" type="text"
                                            class="form-input w-full" placeholder="Apellido Paterno">
                                    </div>
                                    <div>
                                        <label for="apellidoMaterno" class="block text-sm font-medium">Apellido
                                            Materno</label>
                                        <input id="apellidoMaterno" name="apellidoMaterno" type="text"
                                            class="form-input w-full" placeholder="Apellido Materno">
                                    </div>

                                    <!-- Documento y Fecha de nacimiento -->
                                    <div>
                                        <label for="idTipoDocumento" class="block text-sm font-medium">Tipo
                                            Documento</label>
                                        <select id="idTipoDocumento" name="idTipoDocumento"
                                            class="select2 w-full" style="display:none">
                                            <option value="" disabled selected>Seleccionar Tipo Documento
                                            </option>
                                            <option value="1">DNI</option>
                                            <option value="2">Pasaporte</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="documento" class="block text-sm font-medium">Nro.
                                            Documento</label>
                                        <input id="documento" name="documento" type="text"
                                            class="form-input w-full" placeholder="Número de Documento">
                                    </div>
                                    <div>
                                        <label for="fechaNacimiento" class="block text-sm font-medium">Fecha de
                                            Nacimiento</label>
                                        <input id="fechaNacimiento" name="fechaNacimiento" type="text" class="form-input w-full"
                                            placeholder="Seleccione la fecha">
                                    </div>

                                    <!-- Contacto -->
                                    <div>
                                        <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                        <input id="telefono" name="telefono" type="text"
                                            class="form-input w-full" placeholder="Ingresar Teléfono">
                                    </div>
                                    <div>
                                        <label for="correo" class="block text-sm font-medium">Correo</label>
                                        <input id="correo" name="correo" type="email"
                                            class="form-input w-full" placeholder="Correo Electrónico">
                                    </div>

                                    <!-- Dirección -->
                                    <div>
                                        <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                        <textarea id="direccion" name="direccion" class="form-input w-full" rows="1"
                                            placeholder="Ingrese la dirección"></textarea>
                                    </div>

                                    <!-- Usuario y Clave -->
                                    <div>
                                        <label for="usuario" class="block text-sm font-medium">Usuario</label>
                                        <input id="usuario" name="usuario" type="text"
                                            class="form-input w-full" placeholder="Usuario">
                                    </div>
                                    <div>
                                        <label for="clave" class="block text-sm font-medium">Clave</label>
                                        <input id="clave" name="clave" type="password"
                                            class="form-input w-full" placeholder="Clave">
                                    </div>

                                    <!-- Datos laborales -->
                                    <div>
                                        <label for="idSucursal" class="block text-sm font-medium">Sucursal</label>
                                        <select id="idSucursal" name="idSucursal" class="select2 w-full" style="display: none">
                                            <option value="" disabled selected>Seleccionar Sucursal</option>
                                            <option value="1">Sucursal 1</option>
                                            <option value="2">Sucursal 2</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="idArea" class="block text-sm font-medium">Área</label>
                                        <select id="idArea" name="idArea" class="select2 w-full" style="display: none">
                                            <option value="" disabled selected>Seleccionar Área</option>
                                            <option value="1">Recursos Humanos</option>
                                            <option value="2">Tecnología</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="idRol" class="block text-sm font-medium">Rol</label>
                                        <select id="idRol" name="idRol" class="select2 w-full" style="display: none">
                                            <option value="" disabled selected>Seleccionar Rol</option>
                                            <option value="1">Gerente</option>
                                            <option value="2">Supervisor</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="idTipoUsuario" class="block text-sm font-medium">Tipo
                                            Usuario</label>
                                        <select id="idTipoUsuario" name="idTipoUsuario"
                                            class="select2 w-full" style="display: none">
                                            <option value="" disabled selected>Seleccionar Tipo Usuario</option>
                                            <option value="1">Empleado</option>
                                            <option value="2">Administrador</option>
                                        </select>
                                    </div>

                                    <!-- Sueldo y Sexo -->
                                    <div>
                                        <label for="sueldoPorHora" class="block text-sm font-medium">Sueldo por
                                            Hora</label>
                                        <input id="sueldoPorHora" name="sueldoPorHora" type="number"
                                            class="form-input w-full" placeholder="Sueldo por Hora">
                                    </div>
                                    <div>
                                        <label for="idSexo" class="block text-sm font-medium">Sexo</label>
                                        <select id="idSexo" name="idSexo" class="select2 w-full" style="display: none">
                                            <option value="" disabled selected>Seleccionar Sexo</option>
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="flex justify-end items-center mt-4">
                                    <button type="button" class="btn btn-outline-danger">Cancelar</button>
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
                    init() {
                        this.datatable1 = new simpleDatatables.DataTable('#myTable1', {
                            data: {
                                headings: ['Name', 'Company', 'Age', 'Start Date', 'Email',
                                    'Phone No.', 'Status',
                                    '<div class="text-center">Action</div>'
                                ],
                                data: [
                                    ['Caroline Jensen', 'POLARAX', 39, '2004-05-28',
                                        'carolinejensen@zidant.com', '+1 (821) 447-3782', '', ''
                                    ],
                                    ['Celeste Grant', 'MANGLO', 32, '1989-11-19',
                                        'celestegrant@polarax.com', '+1 (838) 515-3408', '', ''
                                    ],
                                    ['Tillman Forbes', 'APPLIDECK', 26, '2016-09-05',
                                        'tillmanforbes@manglo.com', '+1 (969) 496-2892', '', ''
                                    ],
                                    ['Daisy Whitley', 'VOLAX', 21, '1987-03-23',
                                        'daisywhitley@applideck.com', '+1 (861) 564-2877', '',
                                        ''
                                    ],
                                    ['Weber Bowman', 'ORBAXTER', 26, '1983-02-24',
                                        'weberbowman@volax.com', '+1 (962) 466-3483', '', ''
                                    ],
                                    ['Buckley Townsend', 'OPPORTECH', 40, '2011-05-29',
                                        'buckleytownsend@orbaxter.com', '+1 (884) 595-2643', '',
                                        ''
                                    ],
                                    ['Latoya Bradshaw', 'GORGANIC', 24, '2010-11-23',
                                        'latoyabradshaw@opportech.com', '+1 (906) 474-3155', '',
                                        ''
                                    ],
                                    ['Kate Lindsay', 'AVIT', 24, '1987-07-02',
                                        'katelindsay@gorganic.com', '+1 (930) 546-2952', '', ''
                                    ],
                                    ['Marva Sandoval', 'QUILCH', 28, '2010-11-02',
                                        'marvasandoval@avit.com', '+1 (927) 566-3600', '', ''
                                    ],
                                    ['Decker Russell', 'MEMORA', 27, '1994-04-21',
                                        'deckerrussell@quilch.com', '+1 (846) 535-3283', '', ''
                                    ],
                                    ['Odom Mills', 'ZORROMOP', 34, '2010-01-24',
                                        'odommills@memora.com', '+1 (995) 525-3402', '', ''
                                    ],
                                    ['Sellers Walters', 'ORBOID', 28, '1975-11-12',
                                        'sellerswalters@zorromop.com', '+1 (830) 430-3157', '',
                                        ''
                                    ],
                                    ['Wendi Powers', 'SNORUS', 31, '1979-06-02',
                                        'wendipowers@orboid.com', '+1 (863) 457-2088', '', ''
                                    ],
                                    ['Sophie Horn', 'XTH', 22, '2018-09-20',
                                        'sophiehorn@snorus.com', '+1 (885) 418-3948', '', ''
                                    ],
                                    ['Levine Rodriquez', 'COMTRACT', 27, '1973-02-08',
                                        'levinerodriquez@xth.com', '+1 (999) 565-3239', '', ''
                                    ],
                                    ['Little Hatfield', 'ZIDANT', 33, '2012-01-03',
                                        'littlehatfield@comtract.com', '+1 (812) 488-3011', '',
                                        ''
                                    ],
                                    ['Larson Kelly', 'SUREPLEX', 20, '2010-06-14',
                                        'larsonkelly@zidant.com', '+1 (892) 484-2162', '', ''
                                    ],
                                    ['Kendra Molina', 'DANJA', 31, '2002-07-19',
                                        'kendramolina@sureplex.com', '+1 (920) 528-3330', '', ''
                                    ],
                                    ['Ebony Livingston', 'EURON', 33, '1994-10-18',
                                        'ebonylivingston@danja.com', '+1 (970) 591-3039', '', ''
                                    ],
                                    ['Kaufman Rush', 'ILLUMITY', 39, '2011-07-10',
                                        'kaufmanrush@euron.com', '+1 (924) 463-2934', '', ''
                                    ],
                                    ['Frank Hays', 'SYBIXTEX', 31, '2005-06-15',
                                        'frankhays@illumity.com', '+1 (930) 577-2670', '', ''
                                    ],
                                    ['Carmella Mccarty', 'ZEDALIS', 21, '1980-03-06',
                                        'carmellamccarty@sybixtex.com', '+1 (876) 456-3218', '',
                                        ''
                                    ],
                                    ['Massey Owen', 'DYNO', 40, '2012-03-01',
                                        'masseyowen@zedalis.com', '+1 (917) 567-3786', '', ''
                                    ],
                                    ['Lottie Lowery', 'MULTIFLEX', 36, '1982-10-10',
                                        'lottielowery@dyno.com', '+1 (912) 539-3498', '', ''
                                    ],
                                    ['Addie Luna', 'PHARMACON', 32, '1988-05-01',
                                        'addieluna@multiflex.com', '+1 (962) 537-2981', '', ''
                                    ],
                                ]
                            },
                            searchable: true,
                            perPage: 10,
                            perPageSelect: [10, 20, 30, 50, 100],
                            columns: [{
                                    select: 0,
                                    render: (data, cell, row) => {
                                        return `<div class="flex items-center w-max"><img class="w-9 h-9 rounded-full ltr:mr-2 rtl:ml-2 object-cover" src="/assets/images/profile-${row.dataIndex + 1}.jpeg" />${data}</div>`;
                                    },
                                    sort: "asc"
                                },
                                {
                                    select: 3,
                                    render: (data, cell, row) => {
                                        return this.formatDate(data);
                                    },
                                },
                                {
                                    select: 6,
                                    render: (data, cell, row) => {
                                        return '<span class="badge bg-' + this
                                            .randomColor() + '">' + this.randomStatus() +
                                            '</span>';
                                    },
                                },
                                {
                                    select: 7,
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
                            firstText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                            lastText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                            prevText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                            nextText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                            labels: {
                                perPage: "{select}"
                            },
                            layout: {
                                top: "{search}",
                                bottom: "{info}{select}{pager}",
                            },
                        });
                    },

                    formatDate(date) {
                        if (date) {
                            const dt = new Date(date);
                            const month = dt.getMonth() + 1 < 10 ? '0' + (dt.getMonth() + 1) : dt
                                .getMonth() + 1;
                            const day = dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate();
                            return day + '/' + month + '/' + dt.getFullYear();
                        }
                        return '';
                    },

                    randomColor() {
                        const color = ['primary', 'secondary', 'success', 'danger', 'warning', 'info'];
                        const random = Math.floor(Math.random() * color.length);
                        return color[random];
                    },

                    randomStatus() {
                        const status = ['PAID', 'APPROVED', 'FAILED', 'CANCEL', 'SUCCESS', 'PENDING',
                            'COMPLETE'
                        ];
                        const random = Math.floor(Math.random() * status.length);
                        return status[random];
                    }
                }));
            });

            document.addEventListener("alpine:init", () => {
                Alpine.data("form", () => ({
                    date1: '', // Puedes establecer un valor inicial aquí
                    init() {
                        flatpickr(document.getElementById('fechaNacimiento'), {
                            dateFormat: 'Y-m-d',
                            defaultDate: this.date1,
                        });
                    }
                }));
            });
            document.addEventListener("DOMContentLoaded", function() {
                // Inicializar todos los select con la clase "select2"
                document.querySelectorAll('.select2').forEach(function(select) {
                    NiceSelect.bind(select, {
                        searchable: true
                    });
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="/assets/js/simple-datatables.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
