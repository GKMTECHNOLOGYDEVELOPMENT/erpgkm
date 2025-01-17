<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <span>Clientes</span>
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
                        @click="window.location.href = '{{ route('reporte.clientes') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>PDF</span>
                    </button>


                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm flex items-center gap-2"
                        @click="$dispatch('toggle-modal')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5"
                                d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
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
                        <h5 class="font-bold text-lg">Agregar Cliente</h5>
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
                        <form class="p-5 space-y-4" id="clienteForm" enctype="multipart/form-data" method="post">
                            @csrf <!-- Asegúrate de incluir el token CSRF -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- ClienteGeneral -->

                                <div>
                                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Cliente General</option>
                                        @foreach ($clientesGenerales as $clienteGeneral)
                                            <option value="{{ $clienteGeneral->idClienteGeneral }}">
                                                {{ $clienteGeneral->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                    <input id="nombre" type="text" name="nombre" class="form-input w-full"
                                        placeholder="Ingrese el nombre">
                                </div>
                                <!-- Tipo Documento -->
                                <div>
                                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Tipo Documento</option>
                                        @foreach ($tiposDocumento as $tipoDocumento)
                                            <option value="{{ $tipoDocumento->idTipoDocumento }}">
                                                {{ $tipoDocumento->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Documento -->
                                <div>
                                    <label for="documento" class="block text-sm font-medium">Documento</label>
                                    <input id="documento" type="text" name="documento" class="form-input w-full"
                                        placeholder="Ingrese el documento">
                                </div>
                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                    <input id="telefono" type="text" name="telefono" class="form-input w-full"
                                        placeholder="Ingrese el teléfono">
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" class="form-input w-full" name="email"
                                        placeholder="Ingrese el email">
                                </div>
                                <!-- departamento -->
                                <div>
                                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                                    <select id="departamento" name="departamento" class="form-input w-full">
                                        <option value="" disabled selected>Seleccionar Departamento</option>
                                        @foreach ($departamentos as $departamento)
                                            <option value="{{ $departamento['id_ubigeo'] }}">
                                                {{ $departamento['nombre_ubigeo'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Provincia -->
                                <div>
                                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                                    <select id="provincia" name="provincia" class="form-input w-full" disabled>
                                        <option value="" disabled selected>Seleccionar Provincia</option>
                                    </select>
                                </div>

                                <!-- Distrito -->
                                <div>
                                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                                    <select id="distrito" name="distrito" class="form-input w-full" disabled>
                                        <option value="" disabled selected>Seleccionar Distrito</option>
                                    </select>
                                </div>
                                <!-- Dirección (Ocupa 2 columnas) -->
                                <div>
                                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                                        placeholder="Ingrese el direccion">
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
        // Script AJAX para el formulario de cliente
        document.getElementById('clienteForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario tradicional

            let formData = new FormData(
            this); // Obtiene todos los datos del formulario, incluidos archivos si los hay

            // Mostrar en consola los datos antes de enviarlos (esto es solo para depuración)
            console.log("Formulario enviado:", this);
            console.log("Datos del formulario:", Array.from(formData.entries()));

            // Hacer la solicitud AJAX
            fetch("{{ route('cliente.store') }}", {
                    method: "POST", // Asegúrate de usar el método POST
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Agrega el token CSRF
                    },
                    body: formData, // Enviar los datos del formulario (incluso archivos si los hay)
                })
                .then(response => {
                    console.log("Respuesta del servidor:", response);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json(); // Intentar convertir la respuesta en JSON
                })
                .then(data => {
                    console.log("Datos recibidos del servidor:", data);

                    if (data && data.success) { // Asegurarte de que `data` y `data.success` existen
                        showMessage('Cliente agregado correctamente.', 'top-end');
                        document.getElementById('clienteForm').reset();
                        // document.querySelector('[x-data]').__x.$data.open = false;
                        let alpineData = Alpine.store('multipleTable');
                        if (alpineData && alpineData.updateTable) {
                            alpineData.updateTable();
                        }
                    } else {
                        showMessage('Hubo un error al guardar el cliente.', 'top-end');
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                    showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end');
                });
        });

        // Función para mostrar la alerta con SweetAlert
        function showMessage(msg = 'Example notification text.', position = 'top-end', showCloseButton = true,
            closeButtonHtml = '', duration = 3000, type = 'success') {
            const toast = window.Swal.mixin({
                toast: true,
                position: position || 'top-end',
                showConfirmButton: false,
                timer: duration,
                showCloseButton: showCloseButton,
                icon: type === 'success' ? 'success' : 'error', // Cambia el icono según el tipo
                background: type === 'success' ? '#28a745' : '#dc3545', // Verde para éxito, Rojo para error
                iconColor: 'white', // Color del icono
                customClass: {
                    title: 'text-white', // Asegura que el texto sea blanco
                },
            });

            toast.fire({
                title: msg,
            });
        };
    </script>


    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("multipleTable", () => ({
                datatable1: null,
                clienteData: [], // Almacena los datos actuales de la tabla de clientes
                pollInterval: 2000, // Intervalo de polling (en ms)

                init() {
                    console.log("Component initialized for Cliente");

                    // Obtener datos iniciales e inicializar la tabla
                    this.fetchDataAndInitTable();

                    // Configurar polling para verificar actualizaciones
                    setInterval(() => {
                        this.checkForUpdates();
                    }, this.pollInterval);
                },

                fetchDataAndInitTable() {
                    fetch("/api/clientes") // Cambia la URL de la API a /api/clientes
                        .then((response) => {
                            if (!response.ok) throw new Error(
                                "Error al obtener datos del servidor");
                            return response.json();
                        })
                        .then((data) => {
                            console.log("Datos de los clientes:", data);
                            this.clienteData = data;

                            // Inicializar DataTable con las nuevas cabeceras
                            this.datatable1 = new simpleDatatables.DataTable("#myTable1", {
                                data: {
                                    headings: ["Tipo Documento", "Documento", "Nombre",
                                        "Teléfono", "Email", "Cliente General",
                                        "Dirección", "Estado", "Acción"
                                    ], // Nuevas cabeceras
                                    data: this.formatDataForTable(
                                        data
                                    ), // Asegúrate de que esta función mapee los nuevos datos
                                },
                                searchable: true,
                                perPage: 10,
                                labels: {
                                    placeholder: "Buscar...", // Placeholder de búsqueda
                                    perPage: "{select} registros por página", // Selección de registros por página
                                    noRows: "No se encontraron registros", // Mensaje cuando no hay registros
                                    info: "", // Información de la tabla
                                },
                                layout: {
                                    top: "{search}", // Posición del campo de búsqueda
                                    bottom: "{info}{select}{pager}", // Posición de información, selector y paginador
                                },
                            });
                        })
                        .catch((error) => {
                            console.error("Error al inicializar la tabla:", error);
                        });
                },

                // Actualiza esta función para que incluya los nuevos datos de cliente
                formatDataForTable(data) {
                    return data.map((cliente) => [
                        cliente.idTipoDocumento, // Tipo de Documento (ahora el nombre del tipo)
                        cliente.documento, // Documento
                        cliente.nombre, // Nombre
                        cliente.telefono, // Teléfono
                        cliente.email, // Email
                        cliente.clienteGeneral, // Cliente General (ahora la descripción)
                        cliente.direccion, // Dirección
                        cliente.estado === 'Activo' ? 'Activo' : 'Inactivo', // Estado
                        `<div class="flex items-center">
                    <a href="/cliente/${cliente.idCliente}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </a>
                    <button type="button" x-tooltip="Eliminar" @click="deleteCliente(${cliente.idCliente})">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>`
                    ]);
                },

                checkForUpdates() {
                    fetch("/api/clientes") // Cambiar la URL de la API a /api/clientes
                        .then((response) => {
                            if (!response.ok) throw new Error("Error al verificar actualizaciones");
                            return response.json();
                        })
                        .then((data) => {
                            console.log("Datos actuales:", this.clienteData);
                            console.log("Datos del servidor:", data);

                            // Detectar nuevas filas
                            const newData = data.filter(
                                (newCliente) =>
                                !this.clienteData.some(
                                    (existingCliente) =>
                                    existingCliente.idCliente === newCliente.idCliente
                                )
                            );

                            if (newData.length > 0) {
                                console.log("Nuevos datos detectados:", newData);

                                // Agregar filas nuevas a la tabla
                                this.datatable1.rows().add(this.formatDataForTable(newData));
                                this.clienteData.push(...newData); // Actualizar clienteData
                            }
                        })
                        .catch((error) => {
                            console.error("Error al verificar actualizaciones:", error);
                        });
                },

                deleteCliente(idCliente) {
                    console.log(`Intentando eliminar el cliente con ID: ${idCliente}`);

                    new window.Swal({
                        icon: 'warning',
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esta acción!",
                        showCancelButton: true,
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar',
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    }).then((result) => {
                        if (result.value) {
                            // Hacer la solicitud de eliminación
                            fetch(`/api/clientes/${idCliente}`, {
                                    method: "DELETE",
                                })
                                .then((response) => {
                                    if (!response.ok) throw new Error(
                                        "Error al eliminar cliente");
                                    return response.json();
                                })
                                .then((data) => {
                                    console.log(
                                        `Respuesta del servidor al eliminar cliente:`,
                                        data);

                                    // Verificar que el cliente ha sido eliminado correctamente
                                    if (data.message) {
                                        console.log(
                                            `Cliente ${idCliente} eliminado con éxito`);

                                        // Actualizar la lista de clientes en el frontend
                                        this.clienteData = this.clienteData.filter(
                                            (cliente) => cliente.idCliente !== idCliente
                                        );

                                        // Obtener todas las filas de la tabla
                                        const rows = this.datatable1.rows();

                                        console.log("Filas actuales en la tabla:", rows);

                                        // Verifica la estructura de rows
                                        console.log("Tipo de 'rows':", typeof rows);

                                        // Si rows es un objeto con un método 'get' para obtener las filas, entonces
                                        // debes utilizar ese método para obtener las filas de forma correcta.

                                        // Iterar sobre las filas de la tabla
                                        Array.from(rows).forEach((row, index) => {
                                            // Depurar el contenido de cada fila
                                            console.log(`Fila ${index}:`, row);

                                            if (row.cells[0].innerText == idCliente
                                                .toString()) {
                                                console.log(
                                                    `Eliminando fila con ID ${idCliente}`
                                                    );
                                                this.datatable1.rows().remove(
                                                index); // Eliminar la fila
                                            }
                                        });

                                        // Mostrar notificación de éxito
                                        new window.Swal({
                                            title: '¡Eliminado!',
                                            text: 'El cliente ha sido eliminado con éxito.',
                                            icon: 'success',
                                            customClass: 'sweet-alerts',
                                        });
                                    } else {
                                        throw new Error('No se pudo eliminar el cliente.');
                                    }
                                })
                                .catch((error) => {
                                    console.error("Error al eliminar cliente:", error);

                                    // Mostrar notificación de error
                                    new window.Swal({
                                        title: 'Error',
                                        text: 'Ocurrió un error al eliminar el cliente.',
                                        icon: 'error',
                                        customClass: 'sweet-alerts',
                                    });
                                });
                        }
                    });
                }



            }));
        });
        // Inicializar Select2
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
    </script>




    <script>
        $(document).ready(function() {
            // Cuando se selecciona un departamento, obtener las provincias relacionadas
            $('#departamento').change(function() {
                var departamentoId = $(this).val();

                if (departamentoId) {
                    $.get('/ubigeo/provincias/' + departamentoId, function(data) {
                        var provinciaSelect = $('#provincia');
                        provinciaSelect.empty().prop('disabled', false);
                        provinciaSelect.append(
                            '<option value="" disabled selected>Seleccionar Provincia</option>');

                        data.forEach(function(provincia) {
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo +
                                '">' + provincia.nombre_ubigeo + '</option>');
                        });
                    });
                } else {
                    $('#provincia').empty().prop('disabled', true);
                    $('#distrito').empty().prop('disabled', true);
                }
            });

            // Cuando se selecciona una provincia, obtener los distritos relacionados
            $('#provincia').change(function() {
                var provinciaId = $(this).val();

                if (provinciaId) {
                    $.get('/ubigeo/distritos/' + provinciaId, function(data) {
                        var distritoSelect = $('#distrito');
                        distritoSelect.empty().prop('disabled', false);
                        distritoSelect.append(
                            '<option value="" disabled selected>Seleccionar Distrito</option>');

                        data.forEach(function(distrito) {
                            distritoSelect.append('<option value="' + distrito.id_ubigeo +
                                '">' + distrito.nombre_ubigeo + '</option>');
                        });
                    });
                } else {
                    $('#distrito').empty().prop('disabled', true);
                }
            });
        });
    </script>

    <script>
        // Función para mostrar la alerta con SweetAlert
        function showMessage(msg = 'Example notification text.', position = 'top-end', showCloseButton = true,
            closeButtonHtml = '', duration = 3000, type = 'success') {
            const toast = window.Swal.mixin({
                toast: true,
                position: position || 'top-end',
                showConfirmButton: false,
                timer: duration,
                showCloseButton: showCloseButton,
                icon: type === 'success' ? 'success' : 'error', // Cambia el icono según el tipo
                background: type === 'success' ? '#28a745' : '#dc3545', // Verde para éxito, Rojo para error
                iconColor: 'white', // Color del icono
                customClass: {
                    title: 'text-white', // Asegura que el texto sea blanco
                },
            });

            toast.fire({
                title: msg,
            });
        }

        // Mostrar mensaje de éxito o error si hay algún mensaje en la sesión
        @if (session('success'))
            showMessage('{{ session('success') }}', 'top-end', true, '', 3000, 'success');
        @elseif (session('error'))
            showMessage('{{ session('error') }}', 'top-end', true, '', 3000, 'error');
        @endif
    </script>



    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
