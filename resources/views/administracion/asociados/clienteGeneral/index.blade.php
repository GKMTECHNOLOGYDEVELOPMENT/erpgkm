<x-layout.default>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex items-center flex-wrap mb-5">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm m-1" @click="exportTable('excel')">
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

                    <!-- Botón Exportar a PDF -->
                    <button type="button" class="btn btn-danger btn-sm m-1" @click="exportTable('pdf')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        PDF
                    </button>

                    <!-- Botón Imprimir -->
                    <button type="button" class="btn btn-warning btn-sm m-1" @click="printTable">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V9H2V5C2 3.89543 2.89543 3 4 3ZM2 9H22V15C22 16.1046 21.1046 17 20 17H4C2.89543 17 2 16.1046 2 15V9Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M9 17V21H15V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Imprimir
                    </button>

                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm m-1" @click="$dispatch('toggle-modal')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5"
                                d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        Agregar
                    </button>
                </div>
            </div>

            <table id="myTable1" class="whitespace-nowrap"></table>
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
                        <input type="text" id="descripcion" name="descripcion" class="form-input w-full" placeholder="Ingrese la descripción" required>
                    </div>
                    <!-- Foto -->
                    <div class="mb-5">
                        <label for="foto" class="block text-sm font-medium mb-2">Foto</label>
                        <!-- Campo de archivo -->
                        <input id="ctnFile" type="file" name="logo" accept="image/*"
                            class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                            @change="imagenPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : imagenActual" />
                        
                        <!-- Contenedor de previsualización -->
                        <div class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
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
                    <!-- Botones -->
                    <div class="flex justify-end items-center mb-4">
                        <button type="button" class="btn btn-outline-danger" @click="open = false">Cancelar</button>
                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Script AJAX
    document.getElementById('clientGeneralForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío del formulario tradicional

        let formData = new FormData(this); // Obtiene todos los datos del formulario, incluida la foto

        fetch("{{ route('cliente-general.store') }}", {
            method: "POST", // Asegúrate de usar el método POST
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Agrega el token CSRF
            },
            body: formData, // Envío de datos en formato multipart
        })
        .then(response => response.json()) // Espera una respuesta JSON
        .then(data => {
            if (data.success) {
                // Mostrar la alerta de éxito
                showMessage('Cliente agregado correctamente.', 'top-end');

                // Limpiar los campos del formulario
                document.getElementById('clientGeneralForm').reset();

                // Limpiar la previsualización de la imagen y volver a la imagen por defecto
                if (typeof Alpine !== 'undefined') {
                    // Limpiar el estado de imagenPreview en Alpine.js
                    Alpine.store('imagenPreview', '/assets/images/file-preview.svg'); // Restablecer a la imagen predeterminada
                    Alpine.store('imagenActual', '/assets/images/file-preview.svg'); // Actualizar la imagen actual
                }

                // Cerrar el modal (asumiendo que 'open' está vinculado al estado del modal)
                open = false; // Esto asume que `open` es el controlador del modal en Alpine.js

                // Llamar al método para actualizar la tabla (si usas Alpine.js)
                let alpineData = Alpine.store('multipleTable');
                if (alpineData && alpineData.updateTable) {
                    alpineData.updateTable(); // Llama a `updateTable` de Alpine
                }
            } else {
                // Mostrar alerta de error
                showMessage('Hubo un error al guardar el cliente.', 'top-end');
            }
        })
        .catch(error => {
            // Mostrar alerta de error
            showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end');
        });
    });

    // Función para mostrar la alerta con SweetAlert
    function showMessage(msg = 'Example notification text.', position = 'top-end', showCloseButton = true, closeButtonHtml = '', duration = 3000, type = 'success') {
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
                clientData: [], // Almacena los datos actuales de la tabla
                pollInterval: 2000, // Intervalo de polling (en ms)

                init() {
                    // console.log("Component initialized");

                    // Obtener datos iniciales e inicializar la tabla
                    this.fetchDataAndInitTable();

                    // Configurar polling para verificar actualizaciones
                    setInterval(() => {
                        this.checkForUpdates();
                    }, this.pollInterval);
                },

                fetchDataAndInitTable() {
                    fetch("/api/clientegeneral")
                        .then((response) => {
                            if (!response.ok) throw new Error("Error al obtener datos del servidor");
                            return response.json();
                        })
                        .then((data) => {
                            this.clientData = data;

                            // Inicializar DataTable
                            this.datatable1 = new simpleDatatables.DataTable("#myTable1", {
                                data: {
                                    headings: ["Descripción", "Estado", "Foto", "Acción"],
                                    data: this.formatDataForTable(data),
                                },
                                searchable: true,
                                perPage: 10,
                                labels: {
                                    placeholder: "Buscar...",
                                    perPage: "{select}",
                                    noRows: "No se encontraron registros",
                                    info: "Mostrando {start} a {end} de {rows} registros",
                                },
                            });
                        })
                        .catch((error) => {
                            // console.error("Error al inicializar la tabla:", error);
                        });
                },

                formatDataForTable(data) {
                    return data.map((cliente) => [
                        cliente.descripcion,
                        cliente.estado === 'Activo' ?
    `<span class="badge badge-outline-success">Activo</span>` :
    `<span class="badge badge-outline-danger">Inactivo</span>`,

                        `<img src="${cliente.foto}" class="w-10 h-10 rounded-full object-cover" alt="Foto" />`,
                        `<div class="flex items-center">
<a href="/cliente-general/${cliente.idClienteGeneral}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>

                                    <button type="button"  x-tooltip="Delete" @click="deleteClient(${cliente.idClienteGeneral})">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                            <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </div>`,
                    ]);
                },

                checkForUpdates() {
                    fetch("/api/clientegeneral")
                        .then((response) => {
                            if (!response.ok) throw new Error("Error al verificar actualizaciones");
                            return response.json();
                        })
                        .then((data) => {
                            // console.log("Datos actuales:", this.clientData);
                            // console.log("Datos del servidor:", data);

                            // Detectar nuevas filas
                            const newData = data.filter(
                                (newCliente) =>
                                !this.clientData.some(
                                    (existingCliente) =>
                                    existingCliente.idClienteGeneral === newCliente.idClienteGeneral
                                )
                            );

                            if (newData.length > 0) {
                                // console.log("Nuevos datos detectados:", newData);

                                // Agregar filas nuevas a la tabla
                                this.datatable1.rows().add(this.formatDataForTable(newData));
                                this.clientData.push(...newData); // Actualizar clientData
                            }
                        })
                        .catch((error) => {
                            // console.error("Error al verificar actualizaciones:", error);
                        });
                },

                deleteClient(idClienteGeneral) {
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
                            fetch(`/api/clientegeneral/${idClienteGeneral}`, {
                                    method: "DELETE",
                                })
                                .then((response) => {
                                    if (!response.ok) throw new Error("Error al eliminar cliente");
                                    return response.json();
                                })
                                .then(() => {
                                    // console.log(`Cliente ${idClienteGeneral} eliminado con éxito`);

                                    // Actualizar la tabla eliminando la fila
                                    this.clientData = this.clientData.filter(
                                        (cliente) => cliente.idClienteGeneral !== idClienteGeneral
                                    );
                                    this.datatable1.rows().remove(
                                        (row) =>
                                        row.cells[0].innerHTML === idClienteGeneral.toString() // Basado en algún identificador único
                                    );

                                    // Mostrar notificación de éxito
                                    new window.Swal({
                                        title: '¡Eliminado!',
                                        text: 'El cliente ha sido eliminado con éxito.',
                                        icon: 'success',
                                        customClass: 'sweet-alerts',
                                    });
                                })
                                .catch((error) => {
                                    // console.error("Error al eliminar cliente:", error);

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
    </script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('clientGeneralForm');
        const descripcionInput = document.getElementById('descripcion');
        const fileInput = document.getElementById('ctnFile');

        // Base URL for API requests
        const BASE_URL = 'http://127.0.0.1:8000/'; // Ajusta según tu configuración

        // Validaciones
        const validateNombreUnico = async (nombre) => {
            try {
                const response = await fetch(`${BASE_URL}api/check-nombre`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ nombre })
                });
                const data = await response.json();
                return data.unique; // true si es único, false si ya existe
            } catch (error) {
                return false;
            }
        };

        const validateDescripcion = (value) => {
            const regex = /^[a-zA-Z0-9\s]+$/; // Sin caracteres especiales
            return value.trim() !== '' && regex.test(value);
        };

        const validateFile = (file) => {
            const allowedExtensions = ['image/png', 'image/jpeg', 'image/webp'];
            return file.size <= 5 * 1024 * 1024 && allowedExtensions.includes(file.type);
        };

        // Escucha de eventos para validaciones en tiempo real
        descripcionInput.addEventListener('input', async () => {
            const nombre = descripcionInput.value;
            if (!validateDescripcion(nombre)) {
                descripcionInput.setCustomValidity('El nombre no debe estar vacío ni tener caracteres especiales.');
            } else if (!(await validateNombreUnico(nombre))) {
                descripcionInput.setCustomValidity('El nombre ya está en uso.');
            } else {
                descripcionInput.setCustomValidity('');
            }
            descripcionInput.reportValidity();
        });

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) {
                fileInput.setCustomValidity('Debes seleccionar un archivo.');
            } else if (!validateFile(file)) {
                fileInput.setCustomValidity('El archivo debe ser PNG, JPG, WEBP y no superar los 5 MB.');
            } else {
                fileInput.setCustomValidity('');
            }
            fileInput.reportValidity();
        });

        // Validaciones al enviar el formulario
        form.addEventListener('submit', async (event) => {
            const nombre = descripcionInput.value;
            const file = fileInput.files[0];

            // Validar la descripción
            if (!validateDescripcion(nombre)) {
                event.preventDefault();
                return;
            }

            if (!(await validateNombreUnico(nombre))) {
                event.preventDefault();
                return;
            }

            // Validar el archivo de imagen (que sea obligatorio y válido)
            if (!file) {
                fileInput.setCustomValidity('La imagen es obligatoria.');
                event.preventDefault(); // Detener el envío
                return;
            }

            if (!validateFile(file)) {
                fileInput.setCustomValidity('El archivo debe ser PNG, JPG, WEBP y no superar los 5 MB.');
                event.preventDefault();
                return;
            }

            // Si todo es válido, el formulario se enviará
        });
    });
</script>




    <script src="/assets/js/simple-datatables.js"></script>
    <!-- Script de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>