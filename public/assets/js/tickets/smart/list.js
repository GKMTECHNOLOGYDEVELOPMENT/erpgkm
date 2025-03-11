document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        ordenesData: [],
        marcas: [],
        marcaFilter: '',
        startDate: '',
        endDate: '',
        currentPage: 1,
        lastPage: 1,
        isLoading: false,


        init() {
            this.fetchMarcas();
            this.fetchDataAndInitTable();
            this.$watch('marcaFilter', () => this.fetchDataAndInitTable());
            this.$watch('startDate', () => this.fetchDataAndInitTable());
            this.$watch('endDate', () => this.fetchDataAndInitTable());
        },

        fetchMarcas() {
            fetch('/api/marcas')
                .then(response => response.json())
                .then(data => { this.marcas = data; })
                .catch(error => console.error('Error al cargar marcas:', error));
        },

        fetchDataAndInitTable() {
            this.isLoading = true;
            let url = `/api/ordenes?tipoTicket=1`;
        
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    this.ordenesData = data.sort((a, b) => new Date(b.fecha_creacion) - new Date(a.fecha_creacion));
        
                    if ($.fn.DataTable.isDataTable('#myTable1')) {
                        $('#myTable1').DataTable().destroy();
                    }
        
                    this.datatable1 = $('#myTable1').DataTable({
                        data: this.formatDataForTable(this.ordenesData),
                        columns: [
                            { title: 'EDITAR' },
                            { title: 'N. TICKET' },
                            { title: 'F. TICKET' },
                            { title: 'F. VISITA' },
                            { title: 'CATEGORIA' },
                            { title: 'GENERAL' },
                            { title: 'MODELO' },
                            { title: 'SERIE' },
                            { title: 'CLIENTE' },
                            { title: 'DIRECCIÓN' },
                            { title: 'MÁS' }
                        ],
                        searching: true, 
                        paging: true,
                        pageLength: 10,
                        order: [],
                        language: {
                            search: 'Buscar...',
                            zeroRecords: 'No se encontraron registros',
                            lengthMenu: 'Mostrar _MENU_ registros por página',
                            loadingRecords: 'Cargando...',
                            info: '',
                            paginate: {
                                first: 'Primero',
                                last: 'Último',
                                next: 'Siguiente',
                                previous: 'Anterior'
                            },
                        },
        
                        // 🔥 Mantiene el color en cada renderización de la tabla
                        rowCallback: (row, data, index) => {
                            let orden = this.ordenesData[index];
                            if (orden && orden.ticketflujo && orden.ticketflujo.estadoflujo) {
                                const estadoColor = orden.ticketflujo.estadoflujo.color;
                                if (estadoColor) {
                                    $(row).css('background-color', estadoColor);
                                    $(row).find('td').css('color', 'black');
                                }
                            }
                        },
        
                        // 🔥 Reagrega eventos en cada renderización
                        drawCallback: () => {
                            // Asegurar eventos para expandir detalles
                            $('#myTable1 tbody').off('click', 'button.toggle-details').on('click', 'button.toggle-details', (event) => {
                                const id = $(event.currentTarget).data('id');
                                this.toggleRowDetails(event, id);
                            });
                        },



                        initComplete: () => {

                            this.ordenesData.forEach((orden, index) => {
                                const estadoColor = orden.ticketflujo?.estadoflujo?.color;  // Asegúrate de acceder al color correcto
                                console.log('Procesando orden en índice', index, 'con color:', estadoColor);  // Aquí está el console.log
                                if (estadoColor) {
                                    const row = $('#myTable1 tbody tr').eq(index);
                                    if (row) {
                                        row.find('td').css('background-color', estadoColor);
                                    }
                                }
                            });


                            // Asegurar que el color del texto sea siempre negro
                            $('#myTable1 tbody td').css('color', 'black');

                            // Agregar evento para toggleRowDetails
                            $('#myTable1 tbody').off('click', 'button.toggle-details').on('click', 'button.toggle-details', (event) => {
                                const id = $(event.currentTarget).data('id');
                                this.toggleRowDetails(event, id);
                            });
                        }
                    });

                    this.updatePagination();
                })
                .catch(error => console.error('Error al inicializar la tabla:', error))
                .finally(() => { this.isLoading = false; });
        },

        formatDataForTable(data) {
            return data.map(orden => {
                const fechaTicket = orden.fecha_creacion
                    ? new Date(orden.fecha_creacion).toLocaleDateString('es-ES', {
                        day: '2-digit', month: '2-digit', year: 'numeric'
                    })
                    : 'N/A';

                const fechaVisita = orden.fecha_visita
                    ? new Date(orden.fecha_visita).toLocaleDateString('es-ES', {
                        day: '2-digit', month: '2-digit', year: 'numeric'
                    })
                    : 'N/A';

                const wrap = (text) =>
                    `<div style="word-wrap: break-word; white-space: normal; text-align: center;">${text}</div>`;

                return [
                    // Primera columna (Icono de editar)
                    `<div class="flex justify-center items-center">
                        <a href="/ordenes/smart/${orden.idTickets}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 block mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" />
                                <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" />
                            </svg>
                        </a>
                    </div>`,

                    // Segunda columna (Número de ticket)
                    wrap(orden.numero_ticket || 'N/A'),

                    // Tercera columna (Fecha de creación)
                    wrap(fechaTicket),

                    // Cuarta columna (Fecha de visita)
                    wrap(fechaVisita),

                    // Quinta columna (Categoría del modelo)
                    wrap(orden.modelo && orden.modelo.categoria ? orden.modelo.categoria.nombre : 'N/A'),

                    // Sexta columna (Descripción del cliente general)
                    wrap(orden.clientegeneral ? orden.clientegeneral.descripcion : 'N/A'),

                    // Séptima columna (Nombre del modelo)
                    wrap(orden.modelo ? orden.modelo.nombre : 'N/A'),

                    // Octava columna (Número de serie)
                    wrap(orden.serie ? orden.serie : 'N/A'),

                    // Novena columna (Nombre del cliente)
                    wrap(orden.cliente ? orden.cliente.nombre : 'N/A'),

                    // Décima columna (Dirección)
                    wrap(orden.direccion || 'N/A'),

                    // Última columna (Botón de detalles)
                    `<div class="flex justify-center items-center">
                    <button type="button" class="p-1 toggle-details" data-id="${orden.idTickets}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block mx-auto" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="2"/>
                            <circle cx="12" cy="12" r="2"/>
                            <circle cx="19" cy="12" r="2"/>
                        </svg>
                    </button>
                </div>`

                ];
            });
        },

        // updatePagination() {
        //     let paginationDiv = document.getElementById('pagination');
        //     paginationDiv.innerHTML = '';

        //     let maxPagesToShow = 5;
        //     let startPage = Math.max(1, this.currentPage - Math.floor(maxPagesToShow / 2));
        //     let endPage = Math.min(this.lastPage, startPage + maxPagesToShow - 1);

        //     let paginationHTML = `<ul class="flex flex-wrap justify-center items-center gap-1 md:gap-2">`;

        //     if (startPage > 1) {
        //         paginationHTML += `
        //             <li>
        //                 <button type="button" class="w-8 h-8 md:w-10 md:h-10 flex justify-center items-center font-semibold rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
        //                 @click="fetchDataAndInitTable(1)">1</button>
        //             </li>`;
        //         if (startPage > 2) paginationHTML += `<li><span class="px-2 md:px-3">...</span></li>`;
        //     }

        //     for (let i = startPage; i <= endPage; i++) {
        //         paginationHTML += `
        //             <li>
        //                 <button type="button" class="w-8 h-8 md:w-10 md:h-10 flex justify-center items-center font-semibold rounded-full transition text-sm md:text-base ${this.currentPage === i ? 'bg-primary text-white dark:text-white-light dark:bg-primary' : 'bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary'}"
        //                 @click="fetchDataAndInitTable(${i})">${i}</button>
        //             </li>`;
        //     }

        //     if (endPage < this.lastPage) {
        //         if (endPage < this.lastPage - 1) paginationHTML += `<li><span class="px-2 md:px-3">...</span></li>`;
        //         paginationHTML += `
        //             <li>
        //                 <button type="button" class="w-8 h-8 md:w-10 md:h-10 flex justify-center items-center font-semibold rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
        //                 @click="fetchDataAndInitTable(${this.lastPage})">${this.lastPage}</button>
        //             </li>`;
        //     }

        //     paginationHTML += `</ul>`;

        //     paginationHTML = `
        //         <div class="flex flex-wrap justify-center items-center gap-1 md:gap-3">
        //             <button type="button" 
        //                 class="w-8 h-8 md:w-10 md:h-10 flex justify-center items-center font-semibold rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
        //                 ${this.currentPage === 1 ? 'disabled' : ''} 
        //                 @click="fetchDataAndInitTable(${this.currentPage - 1})">
        //                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        //                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        //                 </svg>
        //             </button>
        
        //             ${paginationHTML}
        
        //             <button type="button" 
        //                 class="w-8 h-8 md:w-10 md:h-10 flex justify-center items-center font-semibold rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
        //                 ${this.currentPage === this.lastPage ? 'disabled' : ''} 
        //                 @click="fetchDataAndInitTable(${this.currentPage + 1})">
        //                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        //                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        //                 </svg>
        //             </button>
        //         </div>
        //     `;

        //     paginationDiv.innerHTML = paginationHTML;
        // },

        toggleRowDetails(event, id) {
            let button = event.currentTarget;
            let currentRow = $(button).closest('tr');
            if (currentRow.next().hasClass('expanded-row')) {
                currentRow.next().remove();
            } else {
                let record = this.ordenesData.find(r => r.idTickets == id);
                if (record) {
                    let newRow = $('<tr class="expanded-row"><td colspan="11"></td></tr>');
                    // Aplicar el mismo color de fondo que la fila padre y asegurar texto negro
                    const estadoColor = record.ticketflujo?.estadoflujo?.color;  // Asegúrate de acceder correctamente a la relación
                    const estadoDescripcion = record.ticketflujo?.estadoflujo?.descripcion;



                    // Obtener el nombre del técnico desde la visita
                    let tecnicoNombre = 'N/A';
                    let ticketSeleccionado = record; // Reemplazar con el ticket que seleccionas en el frontend

                    // Verificar si la visita tiene un técnico asignado
                    if (record.seleccionar_visita && record.seleccionar_visita.visita && record.seleccionar_visita.visita.tecnico) {
                        tecnicoNombre = record.seleccionar_visita.visita.tecnico.Nombre;
                    } else {
                        console.log('No se encontró técnico en la visita.');
                    }

                    console.log('Nombre del técnico: ', tecnicoNombre);



                    // Obtener la justificación de la transición de estado con idEstadoots = 3
                    let justificacion = 'N/A'; // Valor predeterminado
                    if (record.transicion_status_tickets && record.transicion_status_tickets.length > 0) {
                        const transicion = record.transicion_status_tickets[0]; // Tomamos la primera transición
                        if (transicion.justificacion) {
                            justificacion = transicion.justificacion; // Asignamos la justificación si existe
                        }
                    }

                    console.log('Justificación: ', justificacion);






                    console.log('Descripcion: ', estadoDescripcion);
                    newRow.find('td').css('background-color', estadoColor || '');  // Asigna el color, si existe
                    newRow.find('td').html(`
                        <div class="p-2 text-sm" style="color: black;">
                            <ul>
                                <li><strong>SOLUCIÓN:</strong> ${justificacion}</li>
                              <li><strong>ESTADO FLUJO:</strong> ${estadoDescripcion || 'N/A'}</li> <!-- Aquí está el cambio -->
                                <li><strong>TÉCNICO:</strong> ${tecnicoNombre}</li>
                            </ul>
                        </div>
                    `);
                    currentRow.after(newRow);
                }
            }
        }






    }));
});