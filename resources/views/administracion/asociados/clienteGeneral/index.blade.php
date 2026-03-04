<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        #myTable1 {
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
        
        /* Estilos para notificaciones */
        .notificacion-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Estilos para campos con error */
        .border-red-500 {
            border-color: #ef4444 !important;
        }
        
        .text-red-500 {
            color: #ef4444 !important;
        }
    </style>
    
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Administración</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Cliente General</span>
                </li>
            </ul>
        </div>
        
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex items-center flex-wrap mb-5">
                    @if (\App\Helpers\PermisoHelper::tienePermiso('DESCARGAR EXCEL CLIENTE GENERAL'))
                        <button type="button" class="btn btn-success btn-sm m-1"
                            onclick="window.location='{{ route('clientes-general.exportExcel') }}'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2.89543 3 4 3Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Excel
                        </button>
                    @endif

                    @if (\App\Helpers\PermisoHelper::tienePermiso('DESCARGAR PDF CLIENTE GENERAL'))
                        <button id="exportPdfBtn" class="btn btn-danger btn-sm"
                            onclick="window.location='{{ route('clientes-general.exportPDF') }}'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                            PDF
                        </button>
                    @endif

                    @if (\App\Helpers\PermisoHelper::tienePermiso('AGREGAR CLIENTE GENERAL'))
                        <button type="button" class="btn btn-primary btn-sm m-1" @click="$dispatch('toggle-modal')">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Agregar
                        </button>
                    @endif
                </div>
            </div>
            
            <div class="mb-4 flex justify-end items-center gap-3">
                <div class="relative w-64">
                    <input type="text" id="searchInput" placeholder="Buscar cliente general..."
                        class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                    <button type="button" id="clearInput"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <button id="btnSearch"
                    class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm">
                    Buscar
                </button>
            </div>
            
            <table id="myTable1" class="w-full min-w-[1000px] table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Foto</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Agregar Cliente General -->
    <div x-data="{ open: false, imagenPreview: null, imagenActual: '/assets/images/file-preview.svg' }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Cliente General</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    
                    <form class="p-5 space-y-4" id="clientGeneralForm" enctype="multipart/form-data" method="post">
                        @csrf
                        <div>
                            <label for="descripcion" class="block text-sm font-medium">Nombre</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-input w-full"
                                placeholder="Ingrese la descripción" required>
                        </div>
                        
                        <div class="mb-5" x-data>
                            <label for="foto" class="block text-sm font-medium mb-2">Foto</label>
                            <input id="ctnFile" type="file" name="logo" accept="image/*" required
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                @change="imagenPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : imagenActual" />

                            <div class="flex justify-center mt-4">
                                <div class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
                                    <template x-if="imagenPreview">
                                        <img :src="imagenPreview" alt="Previsualización de la imagen"
                                            class="w-full h-full object-contain" />
                                    </template>
                                    <template x-if="!imagenPreview">
                                        <img src="/assets/images/file-preview.svg" alt="Imagen predeterminada"
                                            class="w-full h-full object-contain" />
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end items-center mb-4">
                            <button type="button" class="btn btn-outline-danger" @click="open = false">Cancelar</button>
                            @if (\App\Helpers\PermisoHelper::tienePermiso('GUARDAR CLIENTE GENERAL'))
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Usuario (FUNCIONAL CON ALERTAS MEJORADAS) -->
    <div x-data="createUserModal()" x-init="init()" class="mb-5">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl">
                    
                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg flex items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary">
                                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.5" />
                                <path d="M5 18V17C5 14.2386 7.23858 12 10 12H14C16.7614 12 19 14.2386 19 17V18"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M20 12H22M2 12H4M12 2V4M12 20V22" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" opacity="0.5" />
                            </svg>
                            Crear Nuevo Usuario
                        </div>
                        <button type="button" class="text-white-dark hover:text-dark" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="1.5" />
                                <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        <!-- Mensaje de error general -->
                        <div x-show="errorGeneral" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded flex items-center gap-2">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <circle cx="12" cy="16" r="1" fill="currentColor"/>
                            </svg>
                            <span x-text="errorGeneral"></span>
                        </div>

                        <form @submit.prevent="submitForm">
                            <!-- Campo oculto para el ID del cliente -->
                            <input type="hidden" name="idClienteGeneral" x-model="clienteId">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre Completo -->
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nombre Completo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="form.nombreCompleto" 
                                        class="form-input w-full" 
                                        :class="{ 'border-red-500': errores.nombreCompleto }"
                                        placeholder="Ej: Juan Carlos Pérez" required>
                                    <p x-show="errores.nombreCompleto" class="text-xs text-red-500 mt-1" x-text="errores.nombreCompleto"></p>
                                </div>

                                <!-- Apellido Paterno -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Apellido Paterno <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="form.apellidoPaterno" 
                                        class="form-input w-full"
                                        :class="{ 'border-red-500': errores.apellidoPaterno }"
                                        placeholder="Ej: Pérez" required>
                                    <p x-show="errores.apellidoPaterno" class="text-xs text-red-500 mt-1" x-text="errores.apellidoPaterno"></p>
                                </div>

                                <!-- Apellido Materno -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Apellido Materno
                                    </label>
                                    <input type="text" x-model="form.apellidoMaterno" 
                                        class="form-input w-full"
                                        placeholder="Ej: García">
                                </div>

                                <!-- Tipo de Documento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tipo de Documento <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="form.tipoDocumento" 
                                        class="form-select w-full"
                                        :class="{ 'border-red-500': errores.tipoDocumento }" required>
                                        <option value="">Seleccione tipo</option>
                                        <template x-for="td in tiposDocumento" :key="td.idTipoDocumento">
                                            <option :value="td.idTipoDocumento" x-text="td.nombre"></option>
                                        </template>
                                    </select>
                                    <p x-show="errores.tipoDocumento" class="text-xs text-red-500 mt-1" x-text="errores.tipoDocumento"></p>
                                </div>

                                <!-- Número de Documento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Número de Documento <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="form.numeroDocumento" 
                                        class="form-input w-full"
                                        :class="{ 'border-red-500': errores.numeroDocumento }"
                                        placeholder="Ej: 12345678" required>
                                    <p x-show="errores.numeroDocumento" class="text-xs text-red-500 mt-1" x-text="errores.numeroDocumento"></p>
                                    
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Teléfono <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" x-model="form.telefono" 
                                        class="form-input w-full"
                                        :class="{ 'border-red-500': errores.telefono }"
                                        placeholder="Ej: 987654321" required>
                                    <p x-show="errores.telefono" class="text-xs text-red-500 mt-1" x-text="errores.telefono"></p>
                                </div>

                                <!-- Correo Personal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Correo Personal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" x-model="form.correoPersonal" 
                                        class="form-input w-full"
                                        :class="{ 'border-red-500': errores.correoPersonal }"
                                        placeholder="Ej: usuario@email.com" required>
                                    <p x-show="errores.correoPersonal" class="text-xs text-red-500 mt-1" x-text="errores.correoPersonal"></p>
                                </div>

                                <!-- Rol -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Rol <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="form.rol" 
                                        class="form-select w-full"
                                        :class="{ 'border-red-500': errores.rol }" required>
                                        <option value="">Seleccione un rol</option>
                                        <template x-for="r in roles" :key="r.idRol">
                                            <option :value="r.idRol" x-text="r.nombre"></option>
                                        </template>
                                    </select>
                                    <p x-show="errores.rol" class="text-xs text-red-500 mt-1" x-text="errores.rol"></p>
                                </div>

                                <!-- Campos adicionales -->
                                <div class="col-span-2">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="enviarCredenciales" 
                                                x-model="form.enviarCredenciales" class="form-checkbox">
                                            <label for="enviarCredenciales" class="ml-2 text-sm">
                                                Enviar credenciales al correo
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="activo" 
                                                x-model="form.activo" class="form-checkbox" checked>
                                            <label for="activo" class="ml-2 text-sm">
                                                Usuario activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-8 gap-2">
                                <button type="button" class="btn btn-outline-danger" @click="toggle">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1">
                                        <circle opacity="0.5" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary" :disabled="cargando">
                                    <svg x-show="!cargando" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1">
                                        <path d="M3 10H21" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 3V21" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" opacity="0.5" />
                                        <circle cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="1.5" opacity="0.5" />
                                    </svg>
                                    <svg x-show="cargando" class="animate-spin w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="cargando ? 'Guardando...' : 'Crear Usuario'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script principal con Alpine.js CORREGIDO -->
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("createUserModal", () => ({
                open: false,
                cargando: false,
                clienteId: null,
                tiposDocumento: [],
                roles: [],
                errorGeneral: '',
                errores: {
                    nombreCompleto: '',
                    apellidoPaterno: '',
                    tipoDocumento: '',
                    numeroDocumento: '',
                    telefono: '',
                    correoPersonal: '',
                    rol: ''
                },
                form: {
                    nombreCompleto: '',
                    apellidoPaterno: '',
                    apellidoMaterno: '',
                    tipoDocumento: '',
                    numeroDocumento: '',
                    telefono: '',
                    correoPersonal: '',
                    rol: '',
                    enviarCredenciales: true,
                    activo: true,
                },

                init() {
                    // Cargar tipos de documento y roles
                    this.cargarSelectores();
                    
                    // Escuchar evento para abrir el modal
                    document.addEventListener('abrir-modal-crear-usuario', (e) => {
                        this.clienteId = e.detail.clienteId;
                        this.limpiarErrores();
                        this.open = true;
                        console.log('Modal abierto para cliente:', this.clienteId);
                    });
                },

                cargarSelectores() {
                    fetch('/usuarios-cliente-general/get-form-data')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.tiposDocumento = data.tiposDocumento;
                                this.roles = data.roles;
                            }
                        })
                        .catch(error => {
                            console.error('Error cargando selectores:', error);
                            // Datos de respaldo
                            this.tiposDocumento = [
                                { idTipoDocumento: 1, nombre: 'DNI' },
                                { idTipoDocumento: 2, nombre: 'RUC' },
                                { idTipoDocumento: 3, nombre: 'Carnet de Extranjería' },
                                { idTipoDocumento: 4, nombre: 'Pasaporte' }
                            ];
                            this.roles = [
                                { idRol: 1, nombre: 'Administrador' },
                                { idRol: 2, nombre: 'Supervisor' },
                                { idRol: 3, nombre: 'Técnico' },
                                { idRol: 4, nombre: 'Usuario' },
                                { idRol: 5, nombre: 'Invitado' }
                            ];
                        });
                },

                toggle() {
                    this.open = !this.open;
                    if (!this.open) {
                        this.resetForm();
                        this.clienteId = null;
                        this.limpiarErrores();
                    }
                },

                resetForm() {
                    this.form = {
                        nombreCompleto: '',
                        apellidoPaterno: '',
                        apellidoMaterno: '',
                        tipoDocumento: '',
                        numeroDocumento: '',
                        telefono: '',
                        correoPersonal: '',
                        rol: '',
                        enviarCredenciales: true,
                        activo: true,
                    };
                },

                limpiarErrores() {
                    this.errorGeneral = '';
                    this.errores = {
                        nombreCompleto: '',
                        apellidoPaterno: '',
                        tipoDocumento: '',
                        numeroDocumento: '',
                        telefono: '',
                        correoPersonal: '',
                        rol: ''
                    };
                },

                validarCampos() {
                    let tieneErrores = false;
                    
                    if (!this.form.nombreCompleto?.trim()) {
                        this.errores.nombreCompleto = 'El nombre completo es obligatorio';
                        tieneErrores = true;
                    }
                    if (!this.form.apellidoPaterno?.trim()) {
                        this.errores.apellidoPaterno = 'El apellido paterno es obligatorio';
                        tieneErrores = true;
                    }
                    if (!this.form.tipoDocumento) {
                        this.errores.tipoDocumento = 'Seleccione un tipo de documento';
                        tieneErrores = true;
                    }
                    if (!this.form.numeroDocumento?.trim()) {
                        this.errores.numeroDocumento = 'El número de documento es obligatorio';
                        tieneErrores = true;
                    }
                    if (!this.form.telefono?.trim()) {
                        this.errores.telefono = 'El teléfono es obligatorio';
                        tieneErrores = true;
                    }
                    if (!this.form.correoPersonal?.trim()) {
                        this.errores.correoPersonal = 'El correo personal es obligatorio';
                        tieneErrores = true;
                    } else if (!this.validarEmail(this.form.correoPersonal)) {
                        this.errores.correoPersonal = 'Ingrese un correo electrónico válido';
                        tieneErrores = true;
                    }
                    if (!this.form.rol) {
                        this.errores.rol = 'Seleccione un rol';
                        tieneErrores = true;
                    }

                    return !tieneErrores;
                },

                validarEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(email);
                },

                submitForm() {
    if (!this.clienteId) {
        this.errorGeneral = 'Error: ID de cliente no encontrado';
        return;
    }

    this.limpiarErrores();

    if (!this.validarCampos()) {
        return;
    }

    this.cargando = true;

    const datos = {
        ...this.form,
        idClienteGeneral: this.clienteId
    };

    console.log('Enviando datos:', datos); // Para debug

    fetch('/usuarios-cliente-general', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' // IMPORTANTE: Para que Laravel lo detecte como AJAX
        },
        body: JSON.stringify(datos)
    })
    .then(response => {
        console.log('Status:', response.status);
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta:', data);
        if (data.success) {
            this.mostrarNotificacion('success', '✅ Usuario creado exitosamente');
            this.toggle(); // Cerrar modal
            
            // REDIRECCIONAR a la lista de usuarios del cliente
            setTimeout(() => {
                window.location.href = `/cliente-general/${this.clienteId}/usuarios`;
            }, 1500); // Pequeño delay para mostrar la notificación
        } else {
            if (data.errors) {
                this.procesarErroresValidacion(data.errors);
            } else {
                this.errorGeneral = data.message || 'Error al crear usuario';
                this.mostrarNotificacion('error', this.errorGeneral);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            this.procesarErroresValidacion(error.errors);
        } else {
            this.errorGeneral = error.message || '❌ Error de conexión al servidor';
            this.mostrarNotificacion('error', this.errorGeneral);
        }
    })
    .finally(() => {
        this.cargando = false;
    });
},

                procesarErroresValidacion(errors) {
                    console.log('Errores recibidos:', errors);
                    
                    // Mapeo de campos considerando posibles nombres diferentes
                    const mapaCampos = {
                        'nombreCompleto': 'nombreCompleto',
                        'apellidoPaterno': 'apellidoPaterno',
                        'tipoDocumento': 'tipoDocumento',
                        'numeroDocumento': 'numeroDocumento',
                        'telefono': 'telefono',
                        'correoPersonal': 'correoPersonal',
                        'correo_personal': 'correoPersonal', // Por si viene con guión bajo
                        'rol': 'rol'
                    };

                    let tieneErrores = false;

                    for (let campo in errors) {
                        console.log('Procesando campo:', campo, '->', errors[campo][0]);
                        
                        if (mapaCampos[campo]) {
                            this.errores[mapaCampos[campo]] = errors[campo][0];
                            tieneErrores = true;
                            console.log('Error asignado a:', mapaCampos[campo], 'con mensaje:', errors[campo][0]);
                        } else {
                            this.errorGeneral = errors[campo][0];
                            console.log('Error general:', errors[campo][0]);
                        }
                    }

                    if (tieneErrores) {
                        this.mostrarNotificacion('error', 'Por favor corrija los errores en el formulario');
                    }
                },

                mostrarNotificacion(tipo, mensaje) {
                    if (typeof window.mostrarNotificacion === 'function') {
                        window.mostrarNotificacion(tipo, mensaje);
                        return;
                    }
                    
                    const notificacion = document.createElement('div');
                    notificacion.className = `notificacion-toast px-4 py-3 rounded-lg shadow-lg text-white flex items-center gap-2 ${
                        tipo === 'success' ? 'bg-green-500' : 'bg-red-500'
                    }`;
                    notificacion.innerHTML = `
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            ${tipo === 'success' 
                                ? '<path d="M20 6L9 17L4 12" stroke="currentColor" stroke-linecap="round"/>' 
                                : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r="1" fill="currentColor"/>'
                            }
                        </svg>
                        <span>${mensaje}</span>
                    `;
                    
                    document.body.appendChild(notificacion);
                    
                    setTimeout(() => {
                        notificacion.style.animation = 'slideIn 0.3s reverse';
                        setTimeout(() => notificacion.remove(), 300);
                    }, 3000);
                }
            }));
        });

        // Función global para abrir el modal
        window.abrirModalCrearUsuario = function(clienteId) {
            console.log('Abriendo modal para cliente:', clienteId);
            const event = new CustomEvent('abrir-modal-crear-usuario', {
                detail: { clienteId: clienteId }
            });
            document.dispatchEvent(event);
        };

        // Configuración global
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            routeClientStore: '{{ route('cliente-general.store') }}'
        };

        window.permisos = {
            puedeEditar: {{ \App\Helpers\PermisoHelper::tienePermiso('EDITAR CLIENTE GENERAL') ? 'true' : 'false' }},
            puedeEliminar: {{ \App\Helpers\PermisoHelper::tienePermiso('ELIMINAR CLIENTE GENERAL') ? 'true' : 'false' }}
        };

        // Inicialización de DataTable y búsqueda
        document.addEventListener('DOMContentLoaded', function() {
            $('#btnSearch').off('click').on('click', function() {
                const value = $('#searchInput').val();
                $('#myTable1').DataTable().search(value).draw();
            });

            $(document).on('keypress', '#searchInput', function(e) {
                if (e.which === 13) {
                    $('#btnSearch').click();
                }
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

    <!-- Scripts externos -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    
    <!-- Scripts personalizados -->
    <script src="{{ asset('assets/js/clientegeneral/clientegeneralvalidaciones.js') }}"></script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneralstore.js') }}"></script>
    <script src="{{ asset('assets/js/notificacion.js') }}"></script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneral.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
</x-layout.default>