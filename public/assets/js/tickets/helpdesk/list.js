document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        ordenesData: [],
        marcas: [],
        marcaFilter: '',
        clienteGeneralFilter: '',
        startDate: '',
        endDate: '',
        isLoading: false,

        init() {
            this.$nextTick(() => {
                this.injectStyles();
                this.fetchMarcas();
                this.fetchDataAndInitTable();
                this.$watch('marcaFilter', () => this.fetchDataAndInitTable());
                this.$watch('startDate', () => this.fetchDataAndInitTable());
                this.$watch('endDate', () => this.fetchDataAndInitTable());
                // ✅ Escuchar filtro de cliente general
                document.addEventListener('cliente-general-cambio', (e) => {
                    this.clienteGeneralFilter = e.detail;
                    this.isLoading = true; // ✅ Mostrar preloader
                    this.fetchDataAndInitTable();
                });


            });
        },

        injectStyles() {
            const style = document.createElement("style");
            style.innerHTML = `
                .solucion-text {
                    white-space: pre-wrap;
                    word-wrap: break-word;
                    max-width: 1500px;
                }
        
                /* Ajusta fuente y paddings */
                #myTable1_wrapper {
                    font-size: 13px;
                    width: 100%;
                }
        
                #myTable1 {
                    table-layout: auto !important;
                    width: 100% !important;
                }
        
                #myTable1 th, #myTable1 td {
                    padding: 6px 10px !important;
                }
        
                /* Evita el encogimiento innecesario de columnas */
                #myTable1 th {
                    white-space: nowrap;
                }
        
                /* Opcional: define un ancho mínimo para columnas importantes */
                #myTable1 td:nth-child(3),
                #myTable1 th:nth-child(3) {
                    min-width: 120px;
                }
            `;
            document.head.appendChild(style);
        },

        fetchMarcas() {
            fetch('/api/marcas')
                .then(response => response.json())
                .then(data => { this.marcas = data; })
                .catch(error => console.error('Error al cargar marcas:', error));
        },

        fetchDataAndInitTable() {
            this.isLoading = true;


            // 🔹 Destruir DataTable antes de inicializarlo de nuevo
            if ($.fn.DataTable.isDataTable('#myTable1')) {
                $('#myTable1').DataTable().destroy();
            }

            this.datatable1 = $('#myTable1').DataTable({
                processing: false,
                serverSide: true,
                order: [[0, 'desc']], // 👈 ORDENAR POR ID
                ajax: {
                    url: "/api/ordenes/helpdesk",
                    type: "GET",
                    data: (d) => {
                        d.tipoTicket = 2;
                        d.clienteGeneral = this.clienteGeneralFilter;
                        d.startDate = this.startDate;
                        d.endDate = this.endDate;
                    },


                    beforeSend: () => {
                        this.isLoading = true; // 🔹 Muestra el preloader antes de la petición
                    },
                    complete: () => {
                        this.isLoading = false; // 🔹 Oculta el preloader después de recibir datos
                    },
                    dataSrc: (json) => {
                        console.log("📦 Data completa:", json.data);
                        console.log("📦 manejoEnvio:", json.data[0]?.manejoEnvio); // 🔥 Debería llegar aquí

                        this.ordenesData = json.data;
                        return json.data;
                    }


                },
                columns: [
                    { title: 'ACCIONES', data: null, orderable: false, render: this.getEditButton },
                    { title: 'OT', data: "idTickets" }, // 👈 NUEVA COLUMNA
                    { title: 'N. TICKET', data: "numero_ticket", defaultContent: "N/A" },
                    { title: 'F. TICKET', data: "fecha_creacion", defaultContent: "N/A", render: formatDate },
                    {
                        title: 'F. VISITA',
                        data: "visitas",
                        defaultContent: "N/A",
                        render: function (data) {
                            if (data && data.length > 0) {
                                return formatDate(data[0].fecha_programada);
                            }
                            return "N/A";
                        }
                    },
                    { title: 'CLIENTE', data: "cliente.nombre", defaultContent: "N/A" },
                    { title: 'TIENDA', data: "tienda.nombre", defaultContent: "N/A" },
                    {
                        title: 'TIPO TEXTO', // o puede no tener título
                        data: "tipoServicio",
                        visible: false, // 👈 oculta esta columna
                        render: function (data) {
                            return data == 1 ? 'Soporte' : (data == 2 ? 'Levantamiento' : (data == 6 ? 'Laboratorio' : ''));
                        }
                    },
                    {
                        title: 'TIPO SERVICIO',
                        data: "tipoServicio",
                        render: function (data) {
                            if (data == 1) {
                                // 🔧 Soporte: SVG con letra S
                                return `
                                    <span x-tooltip="Soporte" class="inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 24 24">
                                            <text x="6" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">S</text>
                                        </svg>
                                    </span>`;
                            } else if (data == 2) {
                                // 📍 Levantamiento: SVG con letra L
                                return `
                                    <span x-tooltip="Levantamiento" class="inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 24 24">
                                            <text x="6" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">L</text>
                                        </svg>
                                    </span>`;
                            } else if (data == 5) {
                                // 📦 Evaluación (por ejemplo): SVG con letra E
                                return `
                                    <span x-tooltip="Evaluación" class="inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 24 24">
                                            <text x="6" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">E</text>
                                        </svg>
                                    </span>`;
                            } else if (data == 6) {
                                // ⚗️ Laboratorio: SVG con letra LA
                                return `
                                    <span x-tooltip="Laboratorio" class="inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-5 mx-auto" viewBox="0 0 32 24">
                                            <text x="3" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">LA</text>
                                        </svg>
                                    </span>`;
                            }
                            return '';
                        }
                    },
                    
                    { title: 'MÁS', data: null, orderable: false, render: this.getMoreButton }
                ],

                columnDefs: [
                    { targets: "_all", className: "text-center" },
                ],
                searching: true,
                paging: true,
                pageLength: 10,
                order: [[0, 'desc']], // ✅ ORDENA POR ID
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
                },

                rowCallback: (row, data) => {
                    const estadoColor = data.ticketflujo?.estadoflujo?.color || '';

                    if (estadoColor) {
                        $(row)
                            .addClass('estado-bg')
                            .attr('data-bg', estadoColor); // Guarda el color en un atributo
                    }
                },

                drawCallback: () => {
                    $('#myTable1 tbody tr.estado-bg').each(function () {
                        const bgColor = $(this).attr('data-bg');

                        // 🔥 Aplica los estilos en línea con !important
                        $(this).attr('style', `background-color: ${bgColor} !important;`);
                        $(this).find('td').each(function () {
                            $(this).css({
                                'color': 'black',
                                'background-color': bgColor
                            });
                        });
                    });

                    $('#myTable1 tbody').off('click', '.toggle-details').on('click', '.toggle-details', (event) => {
                        const id = $(event.currentTarget).data('id');
                        this.toggleRowDetails(id);
                    });
                }

            });

            this.isLoading = false;
        },



        getEditButton(data) {
            console.log("📦 Data completa:", data);
            console.log("🚚 Manejo de envío:", data.manejo_envio);

            // Normaliza a array
            const envios = Array.isArray(data.manejo_envio)
                ? data.manejo_envio
                : data.manejo_envio
                    ? [data.manejo_envio]
                    : [];

            const tieneEnvio = envios.some(envio => envio.tipo === 1 || envio.tipo === 2);

            const verEnvioBtn = tieneEnvio
                ? `<a href="/apps/invoice/preview/${data.idTickets}" class="ltr:ml-2 rtl:mr-2" x-tooltip="Ver Envío">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-green-600 hover:text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5V5.25A2.25 2.25 0 015.25 3h9.5A2.25 2.25 0 0117 5.25V16.5M17 9h1.878a2.25 2.25 0 011.765.84l1.435 1.794a2.25 2.25 0 01.472 1.406V16.5M3 16.5h18M5.25 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm13.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                   </a>`
                : '';

                return `
                <div class="flex justify-center items-center space-x-2">
                    <a href="/ordenes/helpdesk/${data.tipoServicio == 1
                                    ? 'soporte'
                                    : data.tipoServicio == 2
                                        ? 'levantamiento'
                                        : data.tipoServicio == 5
                                            ? 'ejecucion'
                                            : 'laboratorio'
                                }/${data.idTickets}/edit" class="ltr:mr-1 rtl:ml-1" x-tooltip="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-green-600 hover:text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" />
                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" />
                        </svg>
                    </a>
                    ${verEnvioBtn}
                </div>
                `;
                
            ;
        },






        getMoreButton(data) {
            return `
                <div class="flex justify-center items-center">
                    <button type="button" class="p-1 toggle-details" data-id="${data.idTickets}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block mx-auto" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="2"/>
                            <circle cx="12" cy="12" r="2"/>
                            <circle cx="19" cy="12" r="2"/>
                        </svg>
                    </button>
                </div>`;
        },

        toggleRowDetails(id) {
            let currentRow = $(`#myTable1 tbody button[data-id="${id}"]`).closest('tr');
        
            if (currentRow.next().hasClass('expanded-row')) {
                currentRow.next().remove();
            } else {
                let record = this.ordenesData.find(r => r.idTickets == id);
                if (record) {
                    const transiciones = record.transicion_status_tickets || [];
                    const tipoServicio = record.tiposervicio?.nombre?.toLowerCase() || ''; // asegúrate que esté bien mapeado
        
                    // Última visita del ticket
                    const ultimaVisitaId = Math.max(...transiciones.map(t => t.idVisitas));
        
                    // Estado deseado según tipo de servicio
                    const estadoObjetivo = tipoServicio.includes('levantamiento') ? 5 : 3;
        
                    // Buscar justificación con ese estado en la última visita
                    const justificacionItem = transiciones.find(
                        t => t.idVisitas === ultimaVisitaId && t.idEstadoots === estadoObjetivo
                    );
        
                    const justificacion = justificacionItem?.justificacion || 'N/A';
                    const estadoColor = record.ticketflujo?.estadoflujo?.color || '';
                    const estadoDescripcion = record.ticketflujo?.estadoflujo?.descripcion || 'N/A';
                    const tecnicoNombre = record.seleccionar_visita?.visita?.tecnico?.Nombre || 'N/A';
        
                    let newRow = $('<tr class="expanded-row"><td colspan="11"></td></tr>');
                    newRow.find('td').attr("style", `background-color: ${estadoColor} !important; color: black !important;`);
                    newRow.find('td').html(`
                        <div class="p-2" style="font-size: 13px;">
                            <ul>
                                <li><strong>SOLUCIÓN:</strong> <span class="solucion-text">${justificacion}</span></li>
                                <li><strong>ESTADO FLUJO:</strong> ${estadoDescripcion}</li>
                                <li><strong>TÉCNICO:</strong> ${tecnicoNombre}</li>
                            </ul>
                        </div>
                    `);
                    currentRow.after(newRow);
                }
            }
        }        
    }));

    function formatDate(dateString) {
        if (!dateString) return "N/A";
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
    $(document).ready(function () {
        setTimeout(() => {
            $('.dataTables_length select').css('background-image', 'none');
        }, 500);
    });


});
