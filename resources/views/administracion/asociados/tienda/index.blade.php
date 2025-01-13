<x-layout.default>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <style>
        .modal-scroll {
            overflow-y: auto;
            max-height: 70vh;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.5) transparent;
        }

        .modal-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .modal-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 4px;
        }

        .modal-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Asociados</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Tienda</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" viewBox="0 0 21 21">
                            <path d="M5 13V20C5 20.5523 5.44772 21 6 21H9C9.55228 21 10 20.5523 10 20V16H14V20C14 20.5523 14.4477 21 15 21H18C18.5523 21 19 20.5523 19 20V13" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3 9.5L4 5C4.1538 4.3481 4.6075 3.7825 5.2287 3.41421C5.8499 3.04591 6.5966 2.89999 7.333 3H16.667C17.4034 2.89999 18.1501 3.04591 18.7713 3.41421C19.3925 3.7825 19.8462 4.3481 20 5L21 9.5M3 9.5H21M3 9.5C3 10.0304 3.21071 10.5391 3.58579 10.9142C3.96086 11.2893 4.46957 11.5 5 11.5C5.53043 11.5 6.03914 11.2893 6.41421 10.9142C6.78929 10.5391 7 10.0304 7 9.5M7 9.5C7 10.0304 7.21071 10.5391 7.58579 10.9142C7.96086 11.2893 8.46957 11.5 9 11.5C9.53043 11.5 10.0391 11.2893 10.4142 10.9142C10.7893 10.5391 11 10.0304 11 9.5M11 9.5C11 10.0304 11.2107 10.5391 11.5858 10.9142C11.9609 11.2893 12.4696 11.5 13 11.5C13.5304 11.5 14.0391 11.2893 14.4142 10.9142C14.7893 10.5391 15 10.0304 15 9.5M15 9.5C15 10.0304 15.2107 10.5391 15.5858 10.9142C15.9609 11.2893 16.4696 11.5 17 11.5C17.5304 11.5 18.0391 11.2893 18.4142 10.9142C18.7893 10.5391 19 10.0304 19 9.5" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
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
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Tienda</h5>
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
                    <div class="modal-scroll">
                        <form class="p-5 space-y-4" id="tiendaForm">
                            <!-- ID Tienda -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                <input id="nombre" type="text" class="form-input w-full"
                                    placeholder="Ingrese el nombre de la tienda">
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
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('tiendaGeneralForm');
        const nombreInput = document.getElementById('nombre');

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
                console.error('Error al verificar el nombre:', error);
                return false;
            }
        };

        const validateNombre = (value) => {
            const regex = /^[a-zA-Z0-9\s]+$/; // Sin caracteres especiales
            return value.trim() !== '' && regex.test(value);
        };

        // Escucha de eventos para validaciones en tiempo real
        nombreInput.addEventListener('input', async () => {
            const nombre = nombreInput.value;
            if (!validateNombre(nombre)) {
                nombreInput.setCustomValidity('El nombre no debe estar vacío ni tener caracteres especiales.');
            } else if (!(await validateNombreUnico(nombre))) {
                nombreInput.setCustomValidity('El nombre ya está en uso.');
            } else {
                nombreInput.setCustomValidity(''); // Todo está correcto
            }
            nombreInput.reportValidity();
        });

        // Validaciones al enviar el formulario
        form.addEventListener('submit', async (event) => {
            const nombre = nombreInput.value;

            if (!validateNombre(nombre)) {
                alert('El nombre no debe estar vacío ni tener caracteres especiales.');
                event.preventDefault();
                return;
            }

            if (!(await validateNombreUnico(nombre))) {
                alert('El nombre ya está en uso.');
                event.preventDefault();
                return;
            }

            // Si todo es válido, el formulario se envía
        });
    });
</script>

<!-- Script AJAX para enviar los datos -->
<script>
// Script AJAX
document.getElementById('tiendaGeneralForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario tradicional
    
    let formData = new FormData(this); // Obtiene los datos del formulario
    console.log("Enviando datos:", formData); // Log de los datos del formulario

    fetch("{{ route('tienda.store') }}", {
        method: "POST", // Asegúrate de usar el método POST
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Agrega el token CSRF
        },
        body: formData, // Envío de datos en formato multipart
    })
    .then(response => response.json())  // Espera una respuesta JSON
    .then(data => {
        console.log("Respuesta del servidor:", data); // Log de la respuesta

        if (data.success) {
            // Mostrar la alerta de éxito
            showMessage('Tienda agregada correctamente.', 'top-end');
            
            // Limpiar los campos del formulario
            document.getElementById('tiendaGeneralForm').reset();
            
            // Cerrar el modal
            open = false; // Esto asume que `open` es el controlador del modal en Alpine.js

            // Llamar al método para actualizar la tabla
            let alpineData = Alpine.store('multipleTable');
            if (alpineData && alpineData.updateTable) {
                alpineData.updateTable();  // Llama a `updateTable` de Alpine
            } else {
                console.error('Método updateTable no encontrado en Alpine');
            }

        } else {
            // Mostrar alerta de error
            showMessage('Hubo un error al guardar la tienda.', 'top-end');
        }
    })
    .catch(error => {
        console.error("Error al enviar el formulario:", error);
        // Mostrar alerta de error
        showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end');
    });
});

// Función para mostrar la alerta con SweetAlert
showMessage = (msg = 'Example notification text.', position = 'top-end', showCloseButton = true, closeButtonHtml = '', duration = 3000, type = 'success') => {
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
        tiendaData: [], // Almacena los datos actuales de la tabla
        pollInterval: 2000, // Intervalo de polling (en ms)

        init() {
            console.log("Component initialized for Tienda");

            // Obtener datos iniciales e inicializar la tabla
            this.fetchDataAndInitTable();

            // Configurar polling para verificar actualizaciones
            setInterval(() => {
                this.checkForUpdates();
            }, this.pollInterval);
        },

        fetchDataAndInitTable() {
            fetch("/api/tiendas")
                .then((response) => {
                    if (!response.ok) throw new Error("Error al obtener datos del servidor");
                    return response.json();
                })
                .then((data) => {
                    this.tiendaData = data;

                    // Inicializar DataTable
                    this.datatable1 = new simpleDatatables.DataTable("#myTable1", {
                        data: {
                            headings: ["ID", "Nombre", "Acción"],
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
                    console.error("Error al inicializar la tabla:", error);
                });
        },

        formatDataForTable(data) {
            return data.map((tienda) => [
                tienda.idTienda,
                tienda.nombre,
                `<div class="flex items-center">
                    <a href="/tienda/${tienda.idTienda}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                    </a>
                    <button type="button" x-tooltip="Eliminar" @click="deleteTienda(${tienda.idTienda})">
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
            fetch("/api/tiendas")
                .then((response) => {
                    if (!response.ok) throw new Error("Error al verificar actualizaciones");
                    return response.json();
                })
                .then((data) => {
                    console.log("Datos actuales:", this.tiendaData);
                    console.log("Datos del servidor:", data);

                    // Detectar nuevas filas
                    const newData = data.filter(
                        (newTienda) =>
                            !this.tiendaData.some(
                                (existingTienda) =>
                                    existingTienda.idTienda === newTienda.idTienda
                            )
                    );

                    if (newData.length > 0) {
                        console.log("Nuevos datos detectados:", newData);

                        // Agregar filas nuevas a la tabla
                        this.datatable1.rows().add(this.formatDataForTable(newData));
                        this.tiendaData.push(...newData); // Actualizar tiendaData
                    }
                })
                .catch((error) => {
                    console.error("Error al verificar actualizaciones:", error);
                });
        },

        deleteTienda(idTienda) {
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
                    fetch(`/api/tiendas/${idTienda}`, {
                        method: "DELETE",
                    })
                        .then((response) => {
                            if (!response.ok) throw new Error("Error al eliminar tienda");
                            return response.json();
                        })
                        .then(() => {
                            console.log(`Tienda ${idTienda} eliminada con éxito`);

                            // Actualizar la tabla eliminando la fila
                            this.tiendaData = this.tiendaData.filter(
                                (tienda) => tienda.idTienda !== idTienda
                            );
                            this.datatable1.rows().remove(
                                (row) =>
                                    row.cells[0].innerHTML === idTienda.toString()
                            );

                            // Mostrar notificación de éxito
                            new window.Swal({
                                title: '¡Eliminado!',
                                text: 'La tienda ha sido eliminada con éxito.',
                                icon: 'success',
                                customClass: 'sweet-alerts',
                            });
                        })
                        .catch((error) => {
                            console.error("Error al eliminar tienda:", error);

                            // Mostrar notificación de error
                            new window.Swal({
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar la tienda.',
                                icon: 'error',
                                customClass: 'sweet-alerts',
                            });
                        });
                }
            });
        },
    }));
});
</script>


    <script src="/assets/js/simple-datatables.js"></script>
    <!-- Script de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
