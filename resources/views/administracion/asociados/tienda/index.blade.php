<x-layout.default>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
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
                    onclick="window.location.href='{{ route('tiendas.exportExcel') }}'">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                        <path
                            d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2 3 4 3Z"
                            stroke="currentColor" stroke-width="1.5" />
                        <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span>Excel</span>
                </button>
                

                    <!-- Botón Exportar a PDF -->
                    <button type="button" class="btn btn-danger btn-sm flex items-center gap-2"
                        onclick="window.location.href='{{ route('reporte.tiendas') }}'">
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
                    <!-- Botón Agregar -->
                    <a href="{{ route('tienda.create') }}" class="btn btn-primary btn-sm flex items-center gap-2">
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
    </div>

<<<<<<< HEAD
<!-- Asegúrate de que SweetAlert2 está cargado -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- En tu archivo Blade -->
<script>
        window.sessionMessages = {
            success: '{{ session('success') }}',
            error: '{{ session('error') }}',
        };
    </script>
    <script src="{{ asset('assets/js/notificacion.js') }}"></script>
=======













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
                            if (!response.ok) throw new Error(
                            "Error al obtener datos del servidor");
                            return response.json();
                        })
                        .then((data) => {
                            this.tiendaData = data;

                            // Inicializar DataTable con las nuevas cabeceras
                            this.datatable1 = new simpleDatatables.DataTable("#myTable1", {
                                data: {
                                    headings: ["RUC", "Nombre", "Celular", "Email",
                                        "Dirección", "Referencia", "Acción"
                                    ], // Nuevas cabeceras
                                    data: this.formatDataForTable(
                                    data), // Asegúrate de que esta función mapee los nuevos datos
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
                                    bottom: "", // Posición de información, selector y paginador
                                },
                            });
                        })
                        .catch((error) => {
                            console.error("Error al inicializar la tabla:", error);
                        });
                },

                // Actualiza esta función para que incluya los nuevos datos
                formatDataForTable(data) {
                    return data.map((tienda) => [
                        tienda.ruc, // RUC de la tienda
                        tienda.nombre, // Nombre de la tienda
                        tienda.celular, // Celular de la tienda
                        tienda.email, // Email de la tienda
                        tienda.direccion, // Dirección de la tienda
                        tienda.referencia, // Referencia de la tienda
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
        </div>`
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
                                    if (!response.ok) throw new Error(
                                        "Error al eliminar tienda");
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


    <!-- Asegúrate de que SweetAlert2 está cargado -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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


>>>>>>> 44e92cc7de1a057b85f9409a23f56ac869174d86

<script src="{{ asset('assets/js/tienda.js') }}"></script>

    <script src="/assets/js/simple-datatables.js"></script>
    <!-- Script de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
