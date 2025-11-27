<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .panel {
            overflow: visible !important;
        }
        
        #myTableContactoFinal {
            min-width: 1000px;
        }
    </style>

    <div x-data="contactoFinalTable">
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

        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm flex items-center gap-2"
                        @click="$dispatch('toggle-modal')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z" stroke="currentColor" stroke-width="1.5" />
                            <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>Agregar Contacto</span>
                    </button>
                </div>
            </div>

            <div class="mb-4 flex justify-end items-center gap-3">
                <!-- Input de búsqueda -->
                <div class="relative w-64">
                    <input type="text" id="searchInputContacto" placeholder="Buscar contactos..."
                        class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                    <button type="button" id="clearInputContacto"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                <!-- Botón Buscar -->
                <button id="btnSearchContacto"
                    class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm">
                    Buscar
                </button>
            </div>

            <table id="myTableContactoFinal" class="w-full min-w-[1000px] table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Tipo Documento</th>
                        <th>Número Documento</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <form class="p-5 space-y-4" id="contactoFinalForm" method="post">
                        @csrf

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
                                <input id="numero_documento" type="text" name="numero_documento" class="form-input w-full"
                                    placeholder="Ingrese el número de documento">
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

    <!-- Scripts -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

    <script>
        // JavaScript similar al que ya tienes para clientes, adaptado para contactos finales
        document.addEventListener("alpine:init", () => {
            Alpine.data("contactoFinalTable", () => ({
                datatable: null,

                init() {
                    this.fetchDataAndInitTable();
                },

                async fetchDataAndInitTable() {
                    this.datatable = $('#myTableContactoFinal').DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: "{{ route('contactofinal.getAll') }}",
                            type: "GET"
                        },
                        columns: [
                            { data: 'tipo_documento', className: 'text-center' },
                            { data: 'numero_documento', className: 'text-center' },
                            { data: 'nombre_completo', className: 'text-center' },
                            { data: 'correo', className: 'text-center', render: email => email || 'N/A' },
                            { data: 'telefono', className: 'text-center', render: telefono => telefono || 'N/A' },
                            {
                                data: 'estado',
                                className: 'text-center',
                                render: estado => estado === 'Activo'
                                    ? '<span class="badge badge-outline-success">Activo</span>'
                                    : '<span class="badge badge-outline-danger">Inactivo</span>'
                            },
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'text-center',
                                render: (_, __, row) => {
                                    return `
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="/contactofinal/${row.idContactoFinal}/edit" x-tooltip="Editar">
                                                <svg width="24" height="24" class="w-4.5 h-4.5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path d="M15.29 3.15L14.36 4.08 5.84 12.6c-.58.58-.87.87-1.11 1.2-.29.38-.54.79-.75 1.2-.17.36-.3.73-.56 1.51L2.32 19.8l-.27.8c-.13.38-.03.8.27 1.1.3.3.72.4 1.1.27l.8-.27 3.28-1.1c.78-.26 1.15-.39 1.51-.56.41-.21.82-.46 1.2-.75.33-.24.62-.53 1.2-1.11l8.52-8.52.93-.93c1.54-1.54 1.54-4.04 0-5.58-1.54-1.54-4.04-1.54-5.58 0z" stroke-width="1.5"/>
                                                    <path d="M14.36 4.08s.12 1.97 1.85 3.74c1.73 1.77 3.74 1.79 3.74 1.79M4.2 21.68l-1.88-1.88" opacity="0.5" stroke-width="1.5"/>
                                                </svg>
                                            </a>
                                            <button type="button" x-tooltip="Eliminar" class="text-danger" onclick="deleteContactoFinal(${row.idContactoFinal})">
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
                        order: [[0, 'desc']],
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
                        }
                    });
                },
            }));
        });

        // Función para eliminar contacto final
        window.deleteContactoFinal = function(idContactoFinal) {
            Swal.fire({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                padding: '2em',
                customClass: 'sweet-alerts',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/contactofinal/${idContactoFinal}`, {
                        method: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(async response => {
                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || "Error al eliminar contacto.");
                        }

                        Swal.fire({
                            title: '¡Eliminado!',
                            text: data.message || 'Contacto eliminado correctamente.',
                            icon: 'success',
                            customClass: 'sweet-alerts',
                            timer: 1500,
                            showConfirmButton: false,
                        });

                        $('#myTableContactoFinal').DataTable().ajax.reload(null, false);
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: error.message || 'Ocurrió un error.',
                            icon: 'error',
                            customClass: 'sweet-alerts',
                        });
                    });
                }
            });
        };

       // Form submission para contacto final - VERSIÓN CORREGIDA
document.getElementById('contactoFinalForm').addEventListener('submit', function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch('/contactofinal/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => {
        // Primero verificar el estado de la respuesta
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta recibida:', data); // Para debug
        
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: data.message,
                icon: 'success',
                customClass: 'sweet-alerts',
                timer: 1500,
                showConfirmButton: false,
            });

            // Limpiar formulario
            document.getElementById('contactoFinalForm').reset();
            
            // Recargar tabla si existe
            if ($.fn.DataTable.isDataTable('#myTableContactoFinal')) {
                $('#myTableContactoFinal').DataTable().ajax.reload(null, false);
            }
            
            // Cerrar modal de Alpine.js
            const modalEvent = new CustomEvent('toggle-modal');
            window.dispatchEvent(modalEvent);
            
        } else {
            // Mostrar errores de validación si existen
            let errorMessage = data.message || 'Error desconocido';
            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join(', ');
            }
            
            Swal.fire({
                title: 'Error',
                text: errorMessage,
                icon: 'error',
                customClass: 'sweet-alerts',
            });
        }
    })
    .catch(error => {
        console.error('Error en fetch:', error);
        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Verifica tu conexión.',
            icon: 'error',
            customClass: 'sweet-alerts',
        });
    });
});
    </script>
</x-layout.default>