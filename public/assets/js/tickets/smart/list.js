document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        ordenesData: [], // Almacena los datos actuales de la tabla
        currentPage: 1, // Página actual
        perPage: 10, // Registros por página
        totalPages: 0, // Total de páginas
        totalRecords: 0, // Total de registros
        init() {
            // Obtener datos iniciales e inicializar la tabla
            this.fetchDataAndInitTable();
        },
        fetchDataAndInitTable() {
            fetch(`/api/ordenes?page=${this.currentPage}&perPage=${this.perPage}`)
                .then((response) => {
                    if (!response.ok) throw new Error('Error al obtener datos del servidor');
                    return response.json();
                })
                .then((data) => {
                    this.ordenesData = data.data;  // Asume que los datos vienen en el campo 'data'
                    this.totalPages = data.totalPages; // Asume que tu API devuelve totalPages
                    this.totalRecords = data.totalRecords; // Asume que tu API devuelve totalRecords
                    this.initializeDataTable();  // Inicializar la tabla
                })
                .catch((error) => {
                    console.error('Error al inicializar la tabla:', error);
                });
        },
        initializeDataTable() {
            if (this.datatable1) {
                this.datatable1.destroy();  // Destruir la tabla anterior para evitar conflictos
            }
            this.datatable1 = new simpleDatatables.DataTable('#myTable1', {
                data: {
                    headings: ['Acciones', 'Ticket', 'Marca', 'Modelo', 'Serie', 'Técnico'],
                    data: this.formatDataForTable(this.ordenesData),
                },
                searchable: true,
                perPage: this.perPage,
                labels: {
                    placeholder: 'Buscar...',
                    perPage: '{select} registros por página',
                    noRows: 'No se encontraron registros',
                    info: '',
                },
                layout: {
                    top: '{search}',
                    bottom: '{info}{select}{pager}',
                },
            });
        },

        // Función para cambiar de página
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.fetchDataAndInitTable();
            }
        },

        // Función para obtener el formato de los datos para la tabla
        formatDataForTable(data) {
            return data.map((orden) => [
                `<div style="text-align: center;" class="flex justify-center items-center accion-col">
                    <a href="/ordenes/${orden.idTickets}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </a>
                    <button type="button" x-tooltip="Eliminar" @click="deleteOrden(${orden.idTickets})">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>`, // Acciones
                `<div style="text-align: center;" class="flex justify-center items-center accion-col">${orden.numero_ticket || 'N/A'}</div>`, // Número Ticket
                `<div style="text-align: center;" class="flex justify-center items-center accion-col">${orden.marca || 'N/A'}</div>`,
                `<div style="text-align: center;" class="flex justify-center items-center accion-col">${orden.modelo || 'N/A'}</div>`,
                `<div style="text-align: center;" class="flex justify-center items-center accion-col">${orden.serie || 'N/A'}</div>`,
                `<div style="text-align: center;" class="flex justify-center items-center accion-col">${orden.tecnico || 'N/A'}</div>`, // Técnico (Nombre del técnico)
            ]);
        },

        formatDate(dateString) {
            try {
                // Descompone el formato día/mes/año hora:minuto
                const [datePart, timePart] = dateString.split(' ');
                const [day, month, year] = datePart.split('/');
                const formattedDate = new Date(`${year}-${month}-${day}T${timePart}`);
                return formattedDate.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                });
            } catch (error) {
                console.error('Error al formatear la fecha:', error);
                return 'Fecha inválida';
            }
        },

        deleteOrden(idTickets) {
            new window.Swal({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esta acción!',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                padding: '2em',
                customClass: 'sweet-alerts',
            }).then((result) => {
                if (result.value) {
                    console.log(`Iniciando eliminación de la orden con ID: ${idTickets}`);

                    // Hacer la solicitud de eliminación
                    fetch(`/ordenes/${idTickets}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Obtiene el token CSRF
                            'Content-Type': 'application/json',
                        },
                    })
                        .then((response) => {
                            if (!response.ok) throw new Error('Error al eliminar la orden');
                            return response.json();
                        })
                        .then((data) => {
                            // Mostrar notificación de éxito
                            new window.Swal({
                                title: '¡Eliminado!',
                                text: 'La orden ha sido eliminada con éxito.',
                                icon: 'success',
                                customClass: 'sweet-alerts',
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch((error) => {
                            console.error('Error al eliminar la orden:', error);
                            // Mostrar notificación de error
                            new window.Swal({
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar la orden.',
                                icon: 'error',
                                customClass: 'sweet-alerts',
                            });
                        });
                } else {
                    console.log('Eliminación cancelada.');
                }
            });
        },
    }));
});
