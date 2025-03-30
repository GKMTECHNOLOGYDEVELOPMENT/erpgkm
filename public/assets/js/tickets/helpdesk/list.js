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
                    url: "/api/ordenes",
                    type: "GET",
                    data: (d) => {
                        d.tipoTicket = 2;
                        d.clienteGeneral = this.clienteGeneralFilter; // 👈 Agregado
                    },

                    beforeSend: () => {
                        this.isLoading = true; // 🔹 Muestra el preloader antes de la petición
                    },
                    complete: () => {
                        this.isLoading = false; // 🔹 Oculta el preloader después de recibir datos
                    },
                    dataSrc: (json) => {
                        console.log("👉 Datos recibidos del servidor:", json.data);
                        console.log("🔍 manejoEnvio del primer registro:", json.data[0].manejoEnvio); // 🔥 esto es clave
                        this.ordenesData = json.data;
                        return json.data;
                    }


                },
                columns: [
                    { title: 'ID', data: "idTickets" }, // 👈 NUEVA COLUMNA
                    { title: 'EDITAR', data: null, orderable: false, render: this.getEditButton },
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
                    { targets: 0, visible: false }, // ✅ OCULTA ID
                    { targets: 7, visible: false }, // 👈 Oculta MARCA (ajusta el índice si cambió)
                    { targets: "_all", className: "text-center" },
                    { targets: 10, width: "200px", className: "text-wrap" } // DIRECCIÓN
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
                        $(this).find('td').attr('style', 'color: black !important;');
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
            console.log("Data en getEditButton:", data); // Verifica que manejoEnvio esté presente
            const tieneEnvio = Array.isArray(data.manejoEnvio) && data.manejoEnvio.some(envio => envio.tipo === 1);

            const verEnvioBtn = tieneEnvio
                ? `<a href="/envio/${data.idTickets}" class="ltr:ml-2 rtl:mr-2" x-tooltip="Ver Envío">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-blue-500 hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 0l3-3m-3 3l3 3m6-8.25V6a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6v12a2.25 2.25 0 002.25 2.25h9.75A2.25 2.25 0 0018.75 18v-.75" />
                </svg>
            </a>`
                : '';

            return `
                <div class="flex justify-center items-center space-x-2">
                    <a href="/ordenes/helpdesk/${data.tipoServicio == 1 ? 'soporte' : 'levantamiento'}/${data.idTickets}/edit" class="ltr:mr-1 rtl:ml-1" x-tooltip="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-green-600 hover:text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" />
                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" />
                        </svg>
                    </a>
                    ${verEnvioBtn}
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
                    let newRow = $('<tr class="expanded-row"><td colspan="11"></td></tr>');
                    const estadoColor = record.ticketflujo?.estadoflujo?.color || '';
                    const estadoDescripcion = record.ticketflujo?.estadoflujo?.descripcion || 'N/A';
                    let tecnicoNombre = record.seleccionar_visita?.visita?.tecnico?.Nombre || 'N/A';
                    let justificacion = record.transicion_status_tickets?.[0]?.justificacion || 'N/A';

                    newRow.find('td').attr("style", `background-color: ${estadoColor} !important; color: black !important;`);
                    newRow.find('td').html(`
                <div class="p-2 text-sm">
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
