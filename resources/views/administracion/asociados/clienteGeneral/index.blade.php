<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #myTable1 {
            min-width: 1000px;
            /* puedes ajustar si quieres más ancho */
        }

        .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            padding-right: 1.5rem;
            /* Ajusta espacio a la derecha para que el texto no se corte */
            background-image: none;
            /* Opcional, elimina cualquier ícono */
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
                        <!-- Botón Exportar a Excel -->
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
                        <!-- Botón Exportar a PDF -->
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
                        <!-- Botón Agregar -->
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
                <!-- Input de búsqueda -->
                <div class="relative w-64">
                    <input type="text" id="searchInput" placeholder="Buscar cliente general..."
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


    <!-- Modal -->
    <div x-data="{ open: false, imagenPreview: null, imagenActual: '/assets/images/file-preview.svg' }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
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
                    <!-- Formulario -->
                    <form class="p-5 space-y-4" id="clientGeneralForm" enctype="multipart/form-data" method="post">
                        @csrf <!-- Asegúrate de incluir el token CSRF -->
                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium">Nombre</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-input w-full"
                                placeholder="Ingrese la descripción" required>
                        </div>
                        <!-- Foto -->
                        <div class="mb-5" x-data>
                            <label for="foto" class="block text-sm font-medium mb-2">Foto</label>

                            <!-- Campo de archivo -->
                            <input id="ctnFile" type="file" name="logo" accept="image/*" required
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                @change="imagenPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : imagenActual" />

                            <!-- Contenedor de previsualización -->
                            <div class="flex justify-center mt-4">
                                <div
                                    class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
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

                        <!-- Botones -->
                        <div class="flex justify-end items-center mb-4">
                            <button type="button" class="btn btn-outline-danger"
                                @click="open = false">Cancelar</button>


                            @if (\App\Helpers\PermisoHelper::tienePermiso('GUARDAR CLIENTE GENERAL'))
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Usuario -->
    <div x-data="createUserModal" x-init="init()" class="mb-5">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg flex items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary">
                                <circle cx="12" cy="8" r="4" stroke="currentColor"
                                    stroke-width="1.5" />
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

                    <div class="p-5">
                        <form id="createUserForm" @submit.prevent="submitForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre Completo -->
                                <div class="col-span-2">
                                    <label for="nombreCompleto"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre
                                        Completo <span class="text-red-500">*</span></label>
                                    <input type="text" id="nombreCompleto" name="nombreCompleto"
                                        x-model="form.nombreCompleto" class="form-input w-full"
                                        placeholder="Ej: Juan Carlos Pérez" required>
                                </div>

                                <!-- Apellido Paterno -->
                                <div>
                                    <label for="apellidoPaterno"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido
                                        Paterno <span class="text-red-500">*</span></label>
                                    <input type="text" id="apellidoPaterno" name="apellidoPaterno"
                                        x-model="form.apellidoPaterno" class="form-input w-full"
                                        placeholder="Ej: Pérez" required>
                                </div>

                                <!-- Apellido Materno -->
                                <div>
                                    <label for="apellidoMaterno"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido
                                        Materno</label>
                                    <input type="text" id="apellidoMaterno" name="apellidoMaterno"
                                        x-model="form.apellidoMaterno" class="form-input w-full"
                                        placeholder="Ej: García">
                                </div>

                                <!-- Tipo de Documento -->
                                <div>
                                    <label for="tipoDocumento"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de
                                        Documento <span class="text-red-500">*</span></label>
                                    <select id="tipoDocumento" name="tipoDocumento" x-model="form.tipoDocumento"
                                        class="form-select w-full" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="CE">Carnet de Extranjería</option>
                                        <option value="Pasaporte">Pasaporte</option>
                                    </select>
                                </div>

                                <!-- Número de Documento -->
                                <div>
                                    <label for="numeroDocumento"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número
                                        de Documento <span class="text-red-500">*</span></label>
                                    <input type="text" id="numeroDocumento" name="numeroDocumento"
                                        x-model="form.numeroDocumento" class="form-input w-full"
                                        placeholder="Ej: 12345678" required>
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono
                                        <span class="text-red-500">*</span></label>
                                    <input type="tel" id="telefono" name="telefono" x-model="form.telefono"
                                        class="form-input w-full" placeholder="Ej: 987654321" required>
                                </div>

                                <!-- Correo Personal -->
                                <div>
                                    <label for="correoPersonal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo
                                        Personal <span class="text-red-500">*</span></label>
                                    <input type="email" id="correoPersonal" name="correoPersonal"
                                        x-model="form.correoPersonal" class="form-input w-full"
                                        placeholder="Ej: usuario@email.com" required>
                                </div>

                                <!-- Rol -->
                                <div class="col-span-2">
                                    <label for="rol"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol
                                        <span class="text-red-500">*</span></label>
                                    <select id="rol" name="rol" x-model="form.rol"
                                        class="form-select w-full" required>
                                        <option value="">Seleccione un rol</option>
                                        <option value="Administrador">Administrador</option>
                                        <option value="Supervisor">Supervisor</option>
                                        <option value="Técnico">Técnico</option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Invitado">Invitado</option>
                                    </select>
                                </div>

                                <!-- Campos adicionales -->
                                <div class="col-span-2">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="enviarCredenciales" name="enviarCredenciales"
                                                x-model="form.enviarCredenciales" class="form-checkbox">
                                            <label for="enviarCredenciales"
                                                class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                Enviar credenciales al correo
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="activo" name="activo"
                                                x-model="form.activo" class="form-checkbox" checked>
                                            <label for="activo"
                                                class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                Usuario activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                <button type="submit" class="btn btn-primary">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1">
                                        <path d="M3 10H21" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 3V21" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" opacity="0.5" />
                                        <circle cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="1.5" opacity="0.5" />
                                    </svg>
                                    Crear Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para el modal -->
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("createUserModal", () => ({
                open: false,
                clienteId: null,
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
                    // Escuchar evento para abrir el modal
                    document.addEventListener('abrir-modal', (e) => {
                        this.clienteId = e.detail.clienteId;
                        this.open = true;
                        console.log('Abriendo modal para cliente:', this.clienteId);
                    });
                },

                toggle() {
                    this.open = !this.open;
                    if (!this.open) {
                        this.resetForm();
                        this.clienteId = null;
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

                submitForm() {
                    // Validar campos requeridos
                    if (!this.form.nombreCompleto || !this.form.apellidoPaterno || !this.form
                        .tipoDocumento ||
                        !this.form.numeroDocumento || !this.form.telefono || !this.form
                        .correoPersonal || !this.form.rol) {
                        alert('Por favor complete todos los campos requeridos');
                        return;
                    }

                    // Aquí iría la lógica para enviar el formulario vía AJAX
                    console.log('Formulario enviado para cliente:', this.clienteId, this.form);

                    // Mostrar mensaje de éxito
                    alert('Usuario creado exitosamente');
                    this.toggle();
                }
            }));
        });
    </script>

    <!-- Función global para abrir el modal desde los botones de DataTable -->
    <script>
        window.abrirModalCrearUsuario = function(clienteId) {
            console.log('Abriendo modal para cliente:', clienteId);
            const event = new CustomEvent('abrir-modal', {
                detail: {
                    clienteId: clienteId
                }
            });
            document.dispatchEvent(event);
        };
    </script>

    <!-- En tu archivo Blade -->
    <script>
        window.sessionMessages = {
            success: '{{ session('success') }}',
            error: '{{ session('error') }}',
        };
    </script>
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            routeClientStore: '{{ route('cliente-general.store') }}'
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Botón buscar
            $('#btnSearch').off('click').on('click', function() {
                const value = $('#searchInput').val();
                $('#myTable1').DataTable().search(value).draw();
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

    <script>
        window.permisos = {
            puedeEditar: {{ \App\Helpers\PermisoHelper::tienePermiso('EDITAR CLIENTE GENERAL') ? 'true' : 'false' }},
            puedeEliminar: {{ \App\Helpers\PermisoHelper::tienePermiso('ELIMINAR CLIENTE GENERAL') ? 'true' : 'false' }}
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneralvalidaciones.js') }}"></script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneralstore.js') }}"></script>
    <script src="{{ asset('assets/js/notificacion.js') }}"></script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneral.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <!-- Script de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
