document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        ordenesData: [],
        clienteGeneralFilter: '',
        clienteGenerales: [], // 👈 OBLIGATORIO
        startDate: '',
        endDate: '',
        isLoading: false,
        debouncedFetch: null, // ✅ para almacenar la función debounced

        init() {
            this.$nextTick(async () => {
                this.injectStyles();

                await this.fetchClientes();
                await this.fetchMarcas();

                // ✅ Crear versión debounced
                this.debouncedFetch = this.debounce(this.fetchDataAndInitTable, 300);

                this.fetchDataAndInitTable(); // Primera carga
                this.$watch('startDate', () => this.debouncedFetch());
                this.$watch('endDate', () => this.debouncedFetch());

                document.addEventListener('cliente-general-cambio', (e) => {
                    this.clienteGeneralFilter = e.detail;
                    this.isLoading = true;
                    this.debouncedFetch();
                });
            });
        },

        // ✅ Función debounce integrada
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

        async onClienteGeneralChange(clienteId) {
            this.isLoading = true;
            this.clienteGeneralFilter = clienteId;

            if (!clienteId) {
                await this.fetchMarcas();
                this.marcaFilter = '';
                this.fetchDataAndInitTable();
                return;
            }

            try {
                const res = await fetch(`/api/marcasporcliente/${clienteId}`);
                if (!res.ok) throw new Error('Error al obtener marcas por cliente');
                const data = await res.json();
                this.marcas = data;
                this.marcaFilter = '';
                this.fetchDataAndInitTable();
            } catch (error) {
                console.error('Error al cargar marcas por cliente:', error);
            }
        },

        async fetchClientes() {
            try {
                const res = await fetch('/api/clientegeneralfiltros/1');
                if (!res.ok) throw new Error('Error al obtener clientes');
                const data = await res.json();
                this.clienteGenerales = data;
            } catch (error) {
                console.error('Error al cargar cliente generales:', error);
            }
        },



        async fetchMarcas() {
            try {
                const res = await fetch('/api/marcas');
                if (!res.ok) throw new Error('Error al obtener marcas');
                const data = await res.json();
                this.marcas = data;
            } catch (error) {
                console.error('Error al cargar marcas:', error);
            }
        },

        fetchDataAndInitTable() {
            this.isLoading = true;


            if ($.fn.DataTable.isDataTable('#myTable1')) {
                $('#myTable1').DataTable().destroy();

                // 🔥 Reparar encabezado roto por Alpine.js (por si acaso)
                $('#myTable1 thead').remove();
                $('#myTable1').prepend(`
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ACCIONES</th>
                            <th>N. TICKET</th>
                            <th>F. TICKET</th>
                            <th>F. VISITA</th>
                            <th>CATEGORIA</th>
                            <th>CLIENTE GENERAL</th>
                            <th>MARCA</th>
                            <th>MODELO</th>
                            <th>SERIE</th>
                            <th>CLIENTE</th>
                            <th>DIRECCIÓN</th>
                            <th>MÁS</th>
                        </tr>
                    </thead>
                `);
            }


            this.datatable1 = $('#myTable1').DataTable({
                searchDelay: 600,
                processing: false,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "/api/ordenes",
                    type: "GET",
                    data: (d) => {
                        d.tipoTicket = 1;
                        d.clienteGeneral = this.clienteGeneralFilter;
                        d.startDate = this.startDate;
                        d.endDate = this.endDate;
                        d.marca = this.marcaFilter; // ✅ AÑADIDO PARA FILTRAR MARCA
                    },



                    beforeSend: () => {
                        this.isLoading = true; // 🔹 Muestra el preloader antes de la petición
                    },
                    complete: () => {
                        this.isLoading = false; // 🔹 Oculta el preloader después de recibir datos
                    },
                    dataSrc: (json) => {
                        console.log("👉 Datos recibidos del servidor:", json.data); // 🔥 Esto te mostrará los registros devueltos
                        this.ordenesData = json.data;
                        return json.data;
                    }

                },
                columns: [
                    { title: 'ID', data: "idTickets" }, // 👈 NUEVA COLUMNA
                    { title: 'ACCIONES', data: null, orderable: false, render: this.getEditButton },
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
                    { title: 'CATEGORIA', data: "modelo.categoria.nombre", defaultContent: "N/A" },
                    { title: 'GENERAL', data: "clientegeneral.descripcion", defaultContent: "N/A" },
                    { title: 'MARCA', data: "marca.nombre", defaultContent: "N/A" },
                    { title: 'MODELO', data: "modelo.nombre", defaultContent: "N/A" },
                    { title: 'SERIE', data: "serie", defaultContent: "N/A" },
                    { title: 'CLIENTE', data: "cliente.nombre", defaultContent: "N/A" },
                    {
                        title: 'DIRECCIÓN',
                        data: "direccion",
                        defaultContent: "N/A",
                        render: function (data) {
                            if (!data) return "N/A";
                            return `<div style="white-space: normal; text-align: center;">${data.replace(/\n/g, "<br>")}</div>`;
                        }
                    },
                    { title: 'MÁS', data: null, orderable: false, render: this.getMoreButton }
                ],

                columnDefs: [
                    { targets: 0, visible: false }, // Oculta ID
                    { targets: 6, className: 'hidden' }, // Oculta CLIENTE GENERAL sin romper nada
                    { targets: "_all", className: "text-center" },
                    { targets: 10, width: "200px", className: "text-wrap" } // DIRECCIÓN
                ],
                searching: true,
                paging: true,
                pageLength: 10,
                language: {
                    search: 'Buscar...',
                    zeroRecords: 'No se encontraron registros',
                    lengthMenu: 'Mostrar _MENU_',
                    loadingRecords: 'Cargando...',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }

                },
                dom: 'rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const scrollTopContainer = document.getElementById('scroll-top');
                    const scrollTopInner = document.getElementById('scroll-top-inner');

                    // ✅ Asegura que sea el contenedor que tiene la tabla (no todo el wrapper)
                    const tableScrollContainer = document.querySelector('.relative.overflow-x-auto.custom-scroll');

                    if (!tableScrollContainer || !scrollTopContainer || !scrollTopInner) return;

                    // Mostrar scroll superior
                    scrollTopContainer.classList.remove('hidden');

                    // Sincronizar anchos una vez que el layout esté listo
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

                    // Controles flotantes (info + paginación abajo)
                    const panel = document.querySelector('.panel.mt-6');
                    const floatingControls = document.createElement('div');
                    floatingControls.className =
                        'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                    Object.assign(floatingControls.style, {
                        position: 'sticky',
                        bottom: '0',
                        left: '0',
                        width: '100%',
                        zIndex: '10'
                    });

                    const info = wrapper.querySelector('.dataTables_info');
                    const length = wrapper.querySelector('.dataTables_length');
                    const paginate = wrapper.querySelector('.dataTables_paginate');

                    if (info && length && paginate && panel) {
                        const existingControls = panel.querySelector('.floating-controls');
                        if (existingControls) existingControls.remove();

                        floatingControls.appendChild(info);
                        floatingControls.appendChild(length);
                        floatingControls.appendChild(paginate);
                        panel.appendChild(floatingControls);
                    }
                },

                rowCallback: (row, data) => {
                    const estadoColor = data.ticketflujo?.estadoflujo?.color || '';
                    const estadoId = data.ticketflujo?.estadoflujo?.idEstadflujo;

                    // Aplicar color de fondo según el estado
                    if (estadoColor) {
                        $(row)
                            .addClass('estado-bg')
                            .attr('data-bg', estadoColor);
                    }

                    // ✅ Si el estado es ANULADO (id = 34), aplica tachado a los <td>
                    if (estadoId === 34) {
                        $(row).find('td').css({
                            'text-decoration': 'line-through',
                            'opacity': '0.7'
                        });
                    }


                },
                drawCallback: () => {
                    $('#myTable1 tbody tr.estado-bg').each(function () {
                        const bgColor = $(this).attr('data-bg');
                        // ✅ Mantener color de fondo sin borrar otros estilos
                        $(this).css('background-color', `${bgColor}`);

                        // ✅ Detectar si la fila tiene texto tachado (estadoId === 34)
                        const isTachado = $(this).find('td').css('text-decoration').includes('line-through');

                        if (!isTachado) {
                            $(this).find('td').css('color', 'black');
                        }
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
            return `
                <div class="flex justify-center items-center">
                    <a href="/ordenes/smart/${data.idTickets}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 block mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" />
                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" />
                </svg>
                    </a>

                    <a href="/ordenes/smart/informe/${data.idTickets}/pdf" target="_blank" x-tooltip="Ver PDF">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block mx-auto text-red-600 hover:text-red-800" viewBox="0 0 24 24" fill="currentColor">
        <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8.828a2 2 0 00-.586-1.414l-4.828-4.828A2 2 0 0013.172 2H6zm7 1.414L18.586 9H14a1 1 0 01-1-1V3.414zM8.75 11a.75.75 0 01.75.75v.5a.75.75 0 01-.75.75H8v1h.75a.75.75 0 010 1.5H8a.75.75 0 01-.75-.75v-4A.75.75 0 018 11h.75zM11 11.75a.75.75 0 011.5 0v.25a.75.75 0 01-1.5 0v-.25zm0 2.25a.75.75 0 011.5 0v.25a.75.75 0 01-1.5 0v-.25zm3.25-2.25h.5a.75.75 0 01.75.75v2a.75.75 0 01-1.5 0v-.25h-.25a.75.75 0 010-1.5h.25v-.25z" />
    </svg>
</a>
                </div>`;
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
                    const ultimaVisitaId = Math.max(...transiciones.map(t => t.idVisitas));
                    const justificacionItem = transiciones.find(
                        t => t.idVisitas === ultimaVisitaId && t.idEstadoots === 3
                    );

                    const justificacion = justificacionItem?.justificacion || 'N/A';
                    const estadoColor = record.ticketflujo?.estadoflujo?.color || '';
                    const estadoDescripcion = record.ticketflujo?.estadoflujo?.descripcion || 'N/A';
                    const tecnicoNombre = record.seleccionar_visita?.visita?.tecnico?.Nombre || 'N/A';
                    const estadoId = record.ticketflujo?.estadoflujo?.idEstadflujo || 0;

                    const newRow = $('<tr class="expanded-row"><td colspan="11"></td></tr>');

                    // ✅ Aplica fondo y color
                    newRow.find('td').css({
                        'background-color': estadoColor,
                        'color': 'black'
                    });

                    // ✅ Si está anulado, aplica tachado
                    if (estadoId === 34) {
                        newRow.find('td').css({
                            'text-decoration': 'line-through',
                            'opacity': '0.7'
                        });
                    }

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