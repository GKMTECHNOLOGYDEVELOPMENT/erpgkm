document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        ordenesData: [],
        marcas: [],
        marcaFilter: '',
        clienteGeneralFilter: '',
        clienteGenerales: [],
        clienteGeneralesLoading: true,
        contactoFinalFilter: '',
        contactosPorCliente: [],
        contactoFinalLoading: false,
        startDate: '',
        endDate: '',
        debouncedFetch: null,
        isLoading: false,

        init() {
            console.log('🔵 [INIT] Alpine componente multipleTable inicializando');
            
            // ✅ Inicializar debouncedFetch inmediatamente
            this.debouncedFetch = this.debounce(() => {
                console.log('🔄 [DEBOUNCE] Ejecutando debouncedFetch');
                this.fetchDataAndInitTable();
            }, 300);
            
            this.$nextTick(async () => {
                this.injectStyles();
                await this.fetchMarcas();
                await this.fetchClientesGenerales();

                // ✅ Primera carga
                console.log('📥 [INIT] Realizando primera carga de datos');
                this.fetchDataAndInitTable();

                // Observadores
                this.$watch('marcaFilter', () => {
                    console.log('👁️ [WATCHER] marcaFilter cambiado:', this.marcaFilter);
                    if (this.debouncedFetch) this.debouncedFetch();
                });
                this.$watch('startDate', () => {
                    console.log('👁️ [WATCHER] startDate cambiado:', this.startDate);
                    if (this.debouncedFetch) this.debouncedFetch();
                });
                this.$watch('endDate', () => {
                    console.log('👁️ [WATCHER] endDate cambiado:', this.endDate);
                    if (this.debouncedFetch) this.debouncedFetch();
                });
                this.$watch('clienteGeneralFilter', () => {
                    console.log('👁️ [WATCHER] clienteGeneralFilter cambiado:', this.clienteGeneralFilter);
                    if (this.debouncedFetch) this.debouncedFetch();
                });
                this.$watch('contactoFinalFilter', () => {
                    console.log('👁️ [WATCHER] contactoFinalFilter cambiado:', this.contactoFinalFilter);
                    if (this.debouncedFetch) this.debouncedFetch();
                });
            });
            
            console.log('✅ [INIT] Alpine componente inicializado completamente');
        },

        // ✅ Función para resetear filtros
        resetFilters() {
            console.log('🔄 [RESET] Reseteando todos los filtros');
            
            this.startDate = '';
            this.endDate = '';
            this.marcaFilter = '';
            this.clienteGeneralFilter = '';
            this.contactoFinalFilter = '';
            this.contactosPorCliente = [];
            
            // Resetear selects
            const selectCliente = document.getElementById('clienteGeneralFilter');
            const selectContacto = document.getElementById('contactoFinalFilter');
            
            if (selectCliente) selectCliente.value = '';
            if (selectContacto) selectContacto.value = '';
            
            if (this.debouncedFetch) {
                this.debouncedFetch();
            }
        },

        // ✅ Función para cargar clientes generales
        async fetchClientesGenerales() {
            console.log('📥 [FETCH] Cargando clientes generales');
            this.clienteGeneralesLoading = true;
            try {
                const response = await fetch('/api/clientegeneralfiltros/2');
                if (!response.ok) throw new Error('Error al cargar clientes');
                this.clienteGenerales = await response.json();
                console.log('✅ [FETCH] Clientes generales cargados:', this.clienteGenerales.length);
            } catch (error) {
                console.error('❌ [FETCH] Error cargando clientes generales:', error);
                this.clienteGenerales = [];
            } finally {
                this.clienteGeneralesLoading = false;
            }
        },

        // ✅ Función para cargar contactos por cliente general
        async fetchContactosPorCliente(idClienteGeneral) {
            console.log('📥 [FETCH] Cargando contactos para cliente:', idClienteGeneral);
            this.contactoFinalLoading = true;
            try {
                const response = await fetch(`/api/contactos-por-cliente-general/${idClienteGeneral}`);
                if (!response.ok) throw new Error('Error al cargar contactos');
                this.contactosPorCliente = await response.json();
                console.log('✅ [FETCH] Contactos cargados:', this.contactosPorCliente.length);
            } catch (error) {
                console.error('❌ [FETCH] Error cargando contactos:', error);
                this.contactosPorCliente = [];
            } finally {
                this.contactoFinalLoading = false;
            }
        },

        // ✅ función debounce mejorada
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
        
                #myTable1 th {
                    white-space: nowrap;
                }
        
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
            console.log('📊 [FETCH] Iniciando fetchDataAndInitTable');
            console.log('📊 [FETCH] Parámetros actuales:', {
                clienteGeneralFilter: this.clienteGeneralFilter,
                contactoFinalFilter: this.contactoFinalFilter,
                startDate: this.startDate,
                endDate: this.endDate,
                marcaFilter: this.marcaFilter
            });
            
            this.isLoading = true;

            // 🔹 Destruir DataTable antes de inicializarlo de nuevo
            if ($.fn.DataTable.isDataTable('#myTable1')) {
                console.log('🗑️ [FETCH] Destruyendo DataTable existente');
                $('#myTable1').DataTable().destroy();
            }

            console.log('🌐 [FETCH] Configurando DataTable con AJAX');
            console.log('🌐 [FETCH] URL AJAX: /api/ordenes/helpdesk');
            
            this.datatable1 = $('#myTable1').DataTable({
                processing: false,
                serverSide: true,
                ordering: false,
                order: [[1, 'desc']],
                ajax: {
                    url: '/api/ordenes/helpdesk',
                    type: 'GET',
                    data: (d) => {
                        const params = {
                            tipoTicket: 2,
                            clienteGeneral: this.clienteGeneralFilter,
                            contactoFinal: this.contactoFinalFilter === 'sin_contacto' ? 'null' : this.contactoFinalFilter,
                            startDate: this.startDate,
                            endDate: this.endDate,
                            // Datatables parameters
                            draw: d.draw,
                            start: d.start,
                            length: d.length,
                            search: d.search
                        };
                        
                        console.log('🔍 [AJAX] Parámetros enviados al servidor:', params);
                        return params;
                    },
                    beforeSend: () => {
                        console.log('⏳ [AJAX] Enviando petición AJAX');
                        this.isLoading = true;
                    },
                    complete: () => {
                        console.log('✅ [AJAX] Petición AJAX completada');
                        this.isLoading = false;
                    },
                    dataSrc: (json) => {
                        console.log('📦 [AJAX] Respuesta recibida del servidor:', {
                            draw: json.draw,
                            recordsTotal: json.recordsTotal,
                            recordsFiltered: json.recordsFiltered,
                            dataLength: json.data ? json.data.length : 0,
                            error: json.error || 'No hay error'
                        });
                        
                        if (json.error) {
                            console.error('❌ [AJAX] Error del servidor:', json.error);
                        }
                        
                        this.ordenesData = json.data || [];
                        return json.data || [];
                    },
                    error: (xhr, error, thrown) => {
                        console.error('❌ [AJAX] Error en petición AJAX:', {
                            xhr: xhr,
                            error: error,
                            thrown: thrown,
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText
                        });
                        
                        if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.error) {
                                    alert('Error al cargar datos: ' + response.error);
                                }
                            } catch (e) {
                                console.error('Error parseando respuesta:', e);
                            }
                        }
                    }
                },
                columns: [
                    { 
                        title: 'ACCIONES', 
                        data: null, 
                        orderable: false, 
                        render: (data) => this.getEditButton(data) 
                    },
                    { title: 'OT', data: 'idTickets' },
                    { title: 'N. TICKET', data: 'numero_ticket', defaultContent: 'N/A' },
                    { 
                        title: 'F. TICKET', 
                        data: 'fecha_creacion', 
                        defaultContent: 'N/A', 
                        render: formatDate 
                    },
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
                        title: 'CLIENTE GENERAL',
                        data: 'clientegeneral.descripcion',
                        defaultContent: 'N/A',
                        visible: false
                    },
                    { 
                        title: 'CONTACTO FINAL',
                        data: 'contactofinal.nombre_completo',
                        defaultContent: 'N/A',
                        visible: false
                    },
                    {
                        title: 'TIPO TEXTO',
                        data: 'tipoServicio',
                        visible: false,
                        render: function (data) {
                            switch (data) {
                                case 1: return 'Soporte';
                                case 2: return 'Levantamiento de Información';
                                case 5: return 'Ejecución';
                                case 6: return 'Laboratorio';
                                default: return '';
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
                    { 
                        title: 'MÁS', 
                        data: null, 
                        orderable: false, 
                        render: (data) => this.getMoreButton(data) 
                    },
                ],
                columnDefs: [
                    { targets: '_all', className: 'text-center' },
                    { targets: 7, visible: false }, // CLIENTE GENERAL
                    { targets: 8, visible: false }  // CONTACTO FINAL
                ],
                searching: true,
                paging: true,
                pageLength: 10,
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
                    console.log('✅ [DATATABLE] DataTable inicializado completamente');
                    
                    setTimeout(() => {
                        const wrapper = document.querySelector('.dataTables_wrapper');
                        const scrollTopContainer = document.getElementById('scroll-top');
                        const scrollTopInner = document.getElementById('scroll-top-inner');
                        const tableScrollContainer = document.querySelector('.relative.overflow-x-auto.custom-scroll');

                        if (!wrapper || !scrollTopContainer || !scrollTopInner || !tableScrollContainer) return;

                        scrollTopContainer.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            scrollTopInner.style.width = tableScrollContainer.scrollWidth + 'px';
                        });

                        scrollTopContainer.onscroll = () => {
                            tableScrollContainer.scrollLeft = scrollTopContainer.scrollLeft;
                        };
                        tableScrollContainer.onscroll = () => {
                            scrollTopContainer.scrollLeft = tableScrollContainer.scrollLeft;
                        };

                        const panel = document.querySelector('.panel.mt-6');
                        const info = wrapper.querySelector('.dataTables_info');
                        const length = wrapper.querySelector('.dataTables_length');
                        const paginate = wrapper.querySelector('.dataTables_paginate');

                        if (info && length && paginate && panel) {
                            const existingControls = panel.querySelector('.floating-controls');
                            if (existingControls) existingControls.remove();

                            const floatingControls = document.createElement('div');
                            floatingControls.className = 'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
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
                    }, 300);
                },
                rowCallback: (row, data) => {
                    const estadoColor = data.ticketflujo?.estadoflujo?.color || '';
                    if (estadoColor) {
                        $(row).addClass('estado-bg').attr('data-bg', estadoColor);
                    }
                },
                drawCallback: () => {
                    console.log('🔄 [DATATABLE] Tabla redibujada');
                    
                    $('#myTable1 tbody tr.estado-bg').each(function () {
                        const bgColor = $(this).attr('data-bg');
                        $(this).attr('style', `background-color: ${bgColor} !important;`);
                        $(this).find('td').each(function () {
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
        },

        
        getEditButton(data) {
            // Normaliza a array
            const envios = Array.isArray(data.manejo_envio) ? data.manejo_envio : data.manejo_envio ? [data.manejo_envio] : [];
            const tieneEnvio = envios.some((envio) => envio.tipo === 1 || envio.tipo === 2);

            const verEnvioBtn = tieneEnvio
                ? `<a href="/apps/invoice/preview/${data.idTickets}" class="ltr:ml-2 rtl:mr-2" x-tooltip="Ver Envío">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-black hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5V5.25A2.25 2.25 0 015.25 3h9.5A2.25 2.25 0 0117 5.25V16.5M17 9h1.878a2.25 2.25 0 011.765.84l1.435 1.794a2.25 2.25 0 01.472 1.406V16.5M3 16.5h18M5.25 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm13.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
               </a>`
                : '';

            // Ruta PDF según tipo de servicio
            let pdfUrl = '';
            switch (data.tipoServicio) {
                case 1: pdfUrl = `/ordenes/helpdesk/pdf/soporte/${data.idTickets}`; break;
                case 2: pdfUrl = `/ordenes/helpdesk/pdf/levantamiento/${data.idTickets}`; break;
                case 5: pdfUrl = `/ordenes/helpdesk/pdf/ejecucion/${data.idTickets}`; break;
                case 6: pdfUrl = `/ordenes/helpdesk/pdf/laboratorio/${data.idTickets}`; break;
                default: pdfUrl = '';
            }

            const verPdfBtn = pdfUrl
                ? `<a href="${pdfUrl}" target="_blank" x-tooltip="Ver PDF">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block mx-auto text-gray-600 hover:text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8.828a2 2 0 00-.586-1.414l-4.828-4.828A2 2 0 0013.172 2H6zm7 1.414L18.586 9H14a1 1 0 01-1-1V3.414zM8.75 11a.75.75 0 01.75.75v.5a.75.75 0 01-.75.75H8v1h.75a.75.75 0 010 1.5H8a.75.75 0 01-.75-.75v-4A.75.75 0 018 11h.75zM11 11.75a.75.75 0 011.5 0v.25a.75.75 0 01-1.5 0v-.25zm0 2.25a.75.75 0 011.5 0v.25a.75.75 0 01-1.5 0v-.25zm3.25-2.25h.5a.75.75 0 01.75.75v2a.75.75 0 01-1.5 0v-.25h-.25a.75.75 0 010-1.5h.25v-.25z" />
                    </svg>
               </a>`
                : '';

            return `
        <div class="flex justify-center items-center space-x-2">
            <a href="/ordenes/helpdesk/${
                data.tipoServicio == 1 ? 'soporte' : 
                data.tipoServicio == 2 ? 'levantamiento' : 
                data.tipoServicio == 5 ? 'ejecucion' : 'laboratorio'
            }/${data.idTickets}/edit" class="ltr:mr-1 rtl:ml-1" x-tooltip="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-gray-600 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    const tipoServicio = record.tiposervicio?.nombre?.toLowerCase() || '';
                    const ultimaVisitaId = Math.max(...transiciones.map((t) => t.idVisitas));
                    const estadoObjetivo = tipoServicio.includes('levantamiento') ? 5 : 3;
                    const justificacionItem = transiciones.find((t) => t.idVisitas === ultimaVisitaId && t.idEstadoots === estadoObjetivo);

                    const justificacion = justificacionItem?.justificacion || 'N/A';
                    const estadoColor = record.ticketflujo?.estadoflujo?.color || '';
                    const estadoDescripcion = record.ticketflujo?.estadoflujo?.descripcion || 'N/A';
                    const tecnicoNombre = record.seleccionar_visita?.visita?.tecnico?.Nombre || 'N/A';
                    const contactoFinal = record.contactofinal?.nombre_completo || 'Sin contacto';

                    let newRow = $('<tr class="expanded-row"><td colspan="13"></td></tr>'); // 👈 Actualizado a 13 columnas
                    newRow.find('td').attr('style', `background-color: ${estadoColor} !important; color: black !important;`);
                    newRow.find('td').html(`
                        <div class="p-2" style="font-size: 13px;">
                            <ul>
                                <li><strong>SOLUCIÓN:</strong> <span class="solucion-text">${justificacion}</span></li>
                                <li><strong>ESTADO FLUJO:</strong> ${estadoDescripcion}</li>
                                <li><strong>TÉCNICO:</strong> ${tecnicoNombre}</li>
                                <li><strong>CONTACTO FINAL:</strong> ${contactoFinal}</li>
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
        console.log('📄 [DOCUMENT READY] Documento completamente cargado');
        
        setTimeout(() => {
            $('.dataTables_length select').css('background-image', 'none');
        }, 500);
    });
});