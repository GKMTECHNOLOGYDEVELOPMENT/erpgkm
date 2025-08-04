document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        ordenesData: [],
        marcas: [],
        marcaFilter: '',
        clienteGeneralFilter: '',
        startDate: '',
        endDate: '',
        debouncedFetch: null, // 👈 nuevo

        init() {
            this.$nextTick(() => {
                this.injectStyles();
                this.fetchMarcas();

                // ✅ Crear debounce
                this.debouncedFetch = this.debounce(this.fetchDataAndInitTable, 300);

                // ✅ Primera carga
                this.fetchDataAndInitTable();

                this.$watch('marcaFilter', () => this.debouncedFetch());
                this.$watch('startDate', () => this.debouncedFetch());
                this.$watch('endDate', () => this.debouncedFetch());

                document.addEventListener('cliente-general-cambio', (e) => {
                    this.clienteGeneralFilter = e.detail;
                    this.isLoading = true;
                    this.debouncedFetch();
                });
            });
        },

        // ✅ función debounce
        debounce(func, delay) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    func.apply(this, args);
                }, delay);
            };
        },

        injectStyles() {
            const style = document.createElement('style');
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
                .then((response) => response.json())
                .then((data) => {
                    this.marcas = data;
                })
                .catch((error) => console.error('Error al cargar marcas:', error));
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
                ordering: false,
                order: [[0, 'desc']], // 👈 ORDENAR POR ID
                ajax: {
                    url: '/api/ordenes/helpdesk',
                    type: 'GET',
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
                        console.log('📦 Data completa:', json.data);
                        console.log('📦 manejoEnvio:', json.data[0]?.manejoEnvio); // 🔥 Debería llegar aquí

                        this.ordenesData = json.data;
                        return json.data;
                    },
                },
                columns: [
                    { title: 'ACCIONES', data: null, orderable: false, render: this.getEditButton },
                    { title: 'OT', data: 'idTickets' }, // 👈 NUEVA COLUMNA
                    { title: 'N. TICKET', data: 'numero_ticket', defaultContent: 'N/A' },
                    { title: 'F. TICKET', data: 'fecha_creacion', defaultContent: 'N/A', render: formatDate },
                    {
                        title: 'F. VISITA',
                        data: 'visitas',
                        defaultContent: 'N/A',
                        render: function (data) {
                            if (data && data.length > 0) {
                                return formatDate(data[0].fecha_programada);
                            }
                            return 'N/A';
                        },
                    },
                    { title: 'CLIENTE', data: 'cliente.nombre', defaultContent: 'N/A' },
                    { title: 'TIENDA', data: 'tienda.nombre', defaultContent: 'N/A' },
                    {
                        title: 'TIPO TEXTO',
                        data: 'tipoServicio',
                        visible: false,
                        render: function (data) {
                            switch (data) {
                                case 1:
                                    return 'Soporte';
                                case 2:
                                    return 'Levantamiento de Información';
                                case 5:
                                    return 'Ejecución';
                                case 6:
                                    return 'Laboratorio';
                                default:
                                    return '';
                            }
                        },
                    },

                    {
                        title: 'TIPO SERVICIO',
                        data: 'tipoServicio',
                        render: function (data) {
                            if (data == 1) {
                                return `<span x-tooltip="Soporte" class="inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 24 24">
                            <text x="6" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">S</text>
                        </svg>
                    </span>`;
                            } else if (data == 2) {
                                return `<span x-tooltip="Levantamiento de Información" class="inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 24 24">
                            <text x="6" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">L</text>
                        </svg>
                    </span>`;
                            } else if (data == 5) {
                                return `<span x-tooltip="Ejecución" class="inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" viewBox="0 0 24 24">
                            <text x="4" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">E</text>
                        </svg>
                    </span>`;
                            } else if (data == 6) {
                                return `<span x-tooltip="Laboratorio" class="inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-5 mx-auto" viewBox="0 0 32 24">
                            <text x="3" y="16" font-size="14" font-family="Arial, sans-serif" font-weight="bold">LA</text>
                        </svg>
                    </span>`;
                            }
                            return '';
                        },
                    },

                    { title: 'MÁS', data: null, orderable: false, render: this.getMoreButton },
                ],

                columnDefs: [{ targets: '_all', className: 'text-center' }],
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
                        previous: 'Anterior',
                    },
                },
                dom: 'rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    setTimeout(() => {
                        const wrapper = document.querySelector('.dataTables_wrapper');
                        const scrollTopContainer = document.getElementById('scroll-top');
                        const scrollTopInner = document.getElementById('scroll-top-inner');

                        // Este es el contenedor real con overflow horizontal
                        const tableScrollContainer = document.querySelector('.relative.overflow-x-auto.custom-scroll');

                        if (!wrapper || !scrollTopContainer || !scrollTopInner || !tableScrollContainer) return;

                        // Mostrar barra superior
                        scrollTopContainer.classList.remove('hidden');

                        // Ajustar ancho sincronizado con tabla
                        requestAnimationFrame(() => {
                            scrollTopInner.style.width = tableScrollContainer.scrollWidth + 'px';
                        });

                        // Scroll sincronizado
                        scrollTopContainer.onscroll = () => {
                            tableScrollContainer.scrollLeft = scrollTopContainer.scrollLeft;
                        };
                        tableScrollContainer.onscroll = () => {
                            scrollTopContainer.scrollLeft = tableScrollContainer.scrollLeft;
                        };

                        // Controles flotantes
                        const panel = document.querySelector('.panel.mt-6');
                        const info = wrapper.querySelector('.dataTables_info');
                        const length = wrapper.querySelector('.dataTables_length');
                        const paginate = wrapper.querySelector('.dataTables_paginate');

                        if (info && length && paginate && panel) {
                            const existingControls = panel.querySelector('.floating-controls');
                            if (existingControls) existingControls.remove();

                            const floatingControls = document.createElement('div');
                            floatingControls.className =
                                'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                            Object.assign(floatingControls.style, {
                                position: 'sticky',
                                bottom: '0',
                                left: '0',
                                width: '100%',
                                zIndex: '10',
                            });

                            floatingControls.appendChild(info);
                            floatingControls.appendChild(length);
                            floatingControls.appendChild(paginate);
                            panel.appendChild(floatingControls);
                        }
                    }, 300); // Espera para asegurar render completo
                },

                rowCallback: (row, data) => {
                    const estadoColor = data.ticketflujo?.estadoflujo?.color || '';

                    if (estadoColor) {
                        $(row).addClass('estado-bg').attr('data-bg', estadoColor); // Guarda el color en un atributo
                    }
                },

                drawCallback: () => {
                    $('#myTable1 tbody tr.estado-bg').each(function () {
                        const bgColor = $(this).attr('data-bg');

                        // 🔥 Aplica los estilos en línea con !important
                        $(this).attr('style', `background-color: ${bgColor} !important;`);
                        $(this)
                            .find('td')
                            .each(function () {
                                $(this).css({
                                    color: 'black',
                                    'background-color': bgColor,
                                });
                            });
                    });

                    $('#myTable1 tbody')
                        .off('click', '.toggle-details')
                        .on('click', '.toggle-details', (event) => {
                            const id = $(event.currentTarget).data('id');
                            this.toggleRowDetails(id);
                        });
                },
            });

            this.isLoading = false;
        },

        getEditButton(data) {
            console.log('📦 Data completa:', data);
            console.log('🚚 Manejo de envío:', data.manejo_envio);

            // Normaliza a array
            const envios = Array.isArray(data.manejo_envio) ? data.manejo_envio : data.manejo_envio ? [data.manejo_envio] : [];

            const tieneEnvio = envios.some((envio) => envio.tipo === 1 || envio.tipo === 2);

            const verEnvioBtn = tieneEnvio
                ? `<a href="/apps/invoice/preview/${data.idTickets}" class="ltr:ml-2 rtl:mr-2" x-tooltip="Ver Envío">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-green-600 hover:text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5V5.25A2.25 2.25 0 015.25 3h9.5A2.25 2.25 0 0117 5.25V16.5M17 9h1.878a2.25 2.25 0 011.765.84l1.435 1.794a2.25 2.25 0 01.472 1.406V16.5M3 16.5h18M5.25 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm13.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
           </a>`
                : '';

            // Ruta PDF según tipo de servicio
            let pdfUrl = '';
            switch (data.tipoServicio) {
                case 1:
                    pdfUrl = `/ordenes/helpdesk/pdf/soporte/${data.idTickets}`;
                    break;
                case 2:
                    pdfUrl = `/ordenes/helpdesk/pdf/levantamiento/${data.idTickets}`;
                    break;
                case 5:
                    pdfUrl = `/ordenes/helpdesk/pdf/ejecucion/${data.idTickets}`;
                    break;
                case 6:
                    pdfUrl = `/ordenes/helpdesk/pdf/laboratorio/${data.idTickets}`;
                    break;
                default:
                    pdfUrl = '';
            }

            const verPdfBtn = pdfUrl
                ? `<a href="${pdfUrl}" target="_blank" x-tooltip="Ver PDF">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block mx-auto text-red-600 hover:text-red-800" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8.828a2 2 0 00-.586-1.414l-4.828-4.828A2 2 0 0013.172 2H6zm7 1.414L18.586 9H14a1 1 0 01-1-1V3.414zM8.75 11a.75.75 0 01.75.75v.5a.75.75 0 01-.75.75H8v1h.75a.75.75 0 010 1.5H8a.75.75 0 01-.75-.75v-4A.75.75 0 018 11h.75zM11 11.75a.75.75 0 011.5 0v.25a.75.75 0 01-1.5 0v-.25zm0 2.25a.75.75 0 011.5 0v.25a.75.75 0 01-1.5 0v-.25zm3.25-2.25h.5a.75.75 0 01.75.75v2a.75.75 0 01-1.5 0v-.25h-.25a.75.75 0 010-1.5h.25v-.25z" />
                </svg>
           </a>`
                : '';

            return `
        <div class="flex justify-center items-center space-x-2">
            <a href="/ordenes/helpdesk/${
                data.tipoServicio == 1 ? 'soporte' : data.tipoServicio == 2 ? 'levantamiento' : data.tipoServicio == 5 ? 'ejecucion' : 'laboratorio'
            }/${data.idTickets}/edit" class="ltr:mr-1 rtl:ml-1" x-tooltip="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-green-600 hover:text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" />
                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" />
                </svg>
            </a>
            ${verPdfBtn}
            ${verEnvioBtn}
        </div>
    `;
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
                let record = this.ordenesData.find((r) => r.idTickets == id);
                if (record) {
                    const transiciones = record.transicion_status_tickets || [];
                    const tipoServicio = record.tiposervicio?.nombre?.toLowerCase() || ''; // asegúrate que esté bien mapeado

                    // Última visita del ticket
                    const ultimaVisitaId = Math.max(...transiciones.map((t) => t.idVisitas));

                    // Estado deseado según tipo de servicio
                    const estadoObjetivo = tipoServicio.includes('levantamiento') ? 5 : 3;

                    // Buscar justificación con ese estado en la última visita
                    const justificacionItem = transiciones.find((t) => t.idVisitas === ultimaVisitaId && t.idEstadoots === estadoObjetivo);

                    const justificacion = justificacionItem?.justificacion || 'N/A';
                    const estadoColor = record.ticketflujo?.estadoflujo?.color || '';
                    const estadoDescripcion = record.ticketflujo?.estadoflujo?.descripcion || 'N/A';
                    const tecnicoNombre = record.seleccionar_visita?.visita?.tecnico?.Nombre || 'N/A';

                    let newRow = $('<tr class="expanded-row"><td colspan="11"></td></tr>');
                    newRow.find('td').attr('style', `background-color: ${estadoColor} !important; color: black !important;`);
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
        },
    }));

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        });
    }
    $(document).ready(function () {
        setTimeout(() => {
            $('.dataTables_length select').css('background-image', 'none');
        }, 500);
    });
});
