// public/assets/js/evaluarticket/evaluarticket.js
$(document).ready(function () {
    // Datos de ejemplo actualizados con más campos (como en React)
    const ticketsData = [
        {
            id: 1,
            numeroTicket: 'TKT-2024-001',
            // Datos cliente
            nombreCompleto: 'Juan Carlos Pérez Rodríguez',
            correoElectronico: 'juan.perez@email.com',
            telefonoCelular: '987654321',
            telefonoFijo: '01-1234567',
            // Datos producto
            tipoProducto: 'laptop',
            marca: 'HP',
            modelo: 'EliteBook 840 G3',
            serie: 'HP12345678',
            // Falla
            detallesFalla: 'El equipo no enciende después de actualización',
            // Fechas
            fechaCreacion: '2024-01-15 10:30',
            fechaCompra: '2023-12-10',
            tiendaSedeCompra: 'Tienda Principal - Miraflores',
            // Estado (para evaluar)
            estado: 'pendiente'
        },
        {
            id: 2,
            numeroTicket: 'TKT-2024-002',
            nombreCompleto: 'María García López',
            correoElectronico: 'maria.garcia@email.com',
            telefonoCelular: '987654322',
            telefonoFijo: '01-1234568',
            tipoProducto: 'desktop',
            marca: 'Dell',
            modelo: 'Latitude 5420',
            serie: 'DL98765432',
            detallesFalla: 'Pantalla parpadea constantemente',
            fechaCreacion: '2024-01-15 11:45',
            fechaCompra: '2023-11-20',
            tiendaSedeCompra: 'Tienda Norte - San Miguel',
            estado: 'evaluado'
        },
        {
            id: 3,
            numeroTicket: 'TKT-2024-003',
            nombreCompleto: 'Carlos Rodríguez Torres',
            correoElectronico: 'carlos.rodriguez@email.com',
            telefonoCelular: '987654323',
            telefonoFijo: '01-1234569',
            tipoProducto: 'laptop',
            marca: 'Lenovo',
            modelo: 'ThinkPad X1',
            serie: 'LV45678901',
            detallesFalla: 'Actualización de software',
            fechaCreacion: '2024-01-14 09:15',
            fechaCompra: '2023-10-05',
            tiendaSedeCompra: 'Tienda Sur - Surco',
            estado: 'aprobado'
        },
        {
            id: 4,
            numeroTicket: 'TKT-2024-004',
            nombreCompleto: 'Ana María Flores Ríos',
            correoElectronico: 'ana.flores@email.com',
            telefonoCelular: '987654324',
            telefonoFijo: '01-1234570',
            tipoProducto: 'laptop',
            marca: 'HP',
            modelo: 'ProBook 450',
            serie: 'HP78901234',
            detallesFalla: 'No arranca el sistema, pantalla negra',
            fechaCreacion: '2024-01-14 14:20',
            fechaCompra: '2023-12-01',
            tiendaSedeCompra: 'Tienda Principal - Miraflores',
            estado: 'rechazado'
        },
        {
            id: 5,
            numeroTicket: 'TKT-2024-005',
            nombreCompleto: 'Luis Alberto Sánchez',
            correoElectronico: 'luis.sanchez@email.com',
            telefonoCelular: '987654325',
            telefonoFijo: '01-1234571',
            tipoProducto: 'laptop',
            marca: 'Apple',
            modelo: 'MacBook Pro',
            serie: 'MP34567890',
            detallesFalla: 'Batería no carga, solo funciona enchufado',
            fechaCreacion: '2024-01-13 16:00',
            fechaCompra: '2023-09-15',
            tiendaSedeCompra: 'iShop - San Isidro',
            estado: 'pendiente'
        },
        {
            id: 6,
            numeroTicket: 'TKT-2024-006',
            nombreCompleto: 'Patricia Mendoza Vargas',
            correoElectronico: 'patricia.mendoza@email.com',
            telefonoCelular: '987654326',
            telefonoFijo: '01-1234572',
            tipoProducto: 'laptop',
            marca: 'Dell',
            modelo: 'XPS 13',
            serie: 'DX56789012',
            detallesFalla: 'Sobrecalentamiento y apagados inesperados',
            fechaCreacion: '2024-01-13 08:30',
            fechaCompra: '2023-08-20',
            tiendaSedeCompra: 'Tienda Norte - San Miguel',
            estado: 'evaluado'
        },
        {
            id: 7,
            numeroTicket: 'TKT-2024-007',
            nombreCompleto: 'Jorge Luis Quispe',
            correoElectronico: 'jorge.quispe@email.com',
            telefonoCelular: '987654327',
            telefonoFijo: '01-1234573',
            tipoProducto: 'laptop',
            marca: 'Lenovo',
            modelo: 'ThinkBook',
            serie: 'LB12340987',
            detallesFalla: 'Mantenimiento preventivo',
            fechaCreacion: '2024-01-12 13:45',
            fechaCompra: '2023-07-15',
            tiendaSedeCompra: 'Tienda Centro - Lima',
            estado: 'aprobado'
        },
        {
            id: 8,
            numeroTicket: 'TKT-2024-008',
            nombreCompleto: 'Carmen Ruiz Díaz',
            correoElectronico: 'carmen.ruiz@email.com',
            telefonoCelular: '987654328',
            telefonoFijo: '01-1234574',
            tipoProducto: 'laptop',
            marca: 'HP',
            modelo: 'ZBook Studio',
            serie: 'HZ56781234',
            detallesFalla: 'Error de disco duro',
            fechaCreacion: '2024-01-12 10:10',
            fechaCompra: '2023-06-30',
            tiendaSedeCompra: 'Tienda Principal - Miraflores',
            estado: 'rechazado'
        },
        {
            id: 9,
            numeroTicket: 'TKT-2024-009',
            nombreCompleto: 'Fernando Torres',
            correoElectronico: 'fernando.torres@email.com',
            telefonoCelular: '987654329',
            telefonoFijo: '01-1234575',
            tipoProducto: 'desktop',
            marca: 'HP',
            modelo: 'Pavilion Desktop',
            serie: 'HP90123456',
            detallesFalla: 'No enciende',
            fechaCreacion: '2024-01-11 09:30',
            fechaCompra: '2023-12-05',
            tiendaSedeCompra: 'Tienda Norte - San Miguel',
            estado: 'pendiente'
        },
        {
            id: 10,
            numeroTicket: 'TKT-2024-010',
            nombreCompleto: 'Lucía Mendoza',
            correoElectronico: 'lucia.mendoza@email.com',
            telefonoCelular: '987654330',
            telefonoFijo: '01-1234576',
            tipoProducto: 'laptop',
            marca: 'Apple',
            modelo: 'MacBook Air',
            serie: 'MA78901234',
            detallesFalla: 'Pantalla rota',
            fechaCreacion: '2024-01-11 14:20',
            fechaCompra: '2023-11-18',
            tiendaSedeCompra: 'iShop - San Isidro',
            estado: 'evaluado'
        }
    ];

    let filteredData = [...ticketsData];
    let currentPage = 1;
    let perPage = 10;
    let selectedStatus = 'todos';
    let searchText = '';
    let dateRange = { start: '', end: '' };

    // Función para obtener badge de estado (con Tailwind)
    function getStatusBadge(estado) {
        const statusConfig = {
            pendiente: {
                color: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                icon: 'fa-clock',
                text: 'Pendiente'
            },
            evaluado: {
                color: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                icon: 'fa-check-circle',
                text: 'Evaluado'
            },
            aprobado: {
                color: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                icon: 'fa-thumbs-up',
                text: 'Aprobado'
            },
            rechazado: {
                color: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                icon: 'fa-thumbs-down',
                text: 'Rechazado'
            }
        };

        const config = statusConfig[estado] || statusConfig.pendiente;

        return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium ${config.color}">
            <i class="fas ${config.icon} fa-xs"></i>
            ${config.text}
        </span>`;
    }

    // Inicializar Flatpickr
    flatpickr("#startDate", {
        dateFormat: "Y-m-d",
        altFormat: "d/m/Y",
        altInput: true,
        placeholder: "Fecha inicial",
        onChange: function (selectedDates, dateStr) {
            dateRange.start = dateStr;
            updateDateFilterUI();
            filterData();
        }
    });

    flatpickr("#endDate", {
        dateFormat: "Y-m-d",
        altFormat: "d/m/Y",
        altInput: true,
        placeholder: "Fecha final",
        onChange: function (selectedDates, dateStr) {
            dateRange.end = dateStr;
            updateDateFilterUI();
            filterData();
        }
    });

    // Función para actualizar UI de filtros de fecha
    function updateDateFilterUI() {
        if (dateRange.start || dateRange.end) {
            $('#clearDates').removeClass('hidden');
            $('#dateFilterBadge').removeClass('hidden');
        } else {
            $('#clearDates').addClass('hidden');
            $('#dateFilterBadge').addClass('hidden');
        }
    }

    // Limpiar filtros de fecha
    $('#clearDates').click(function () {
        dateRange = { start: '', end: '' };
        $('#startDate').val('');
        $('#endDate').val('');
        if ($('#startDate').data('flatpickr')) {
            $('#startDate').data('flatpickr').clear();
        }
        if ($('#endDate').data('flatpickr')) {
            $('#endDate').data('flatpickr').clear();
        }
        updateDateFilterUI();
        filterData();
    });

    // Filtros de estado - activo/desactivado
    $('.filter-btn').click(function () {
        const status = $(this).data('status');

        // Resetear todos los botones a estado inactivo
        $('.filter-btn').removeClass('bg-primary text-white bg-info bg-warning bg-success bg-danger')
            .addClass('bg-gray-100 text-gray-700 hover:bg-gray-200');

        // Activar el botón seleccionado con bg-primary
        $(this).removeClass('bg-gray-100 text-gray-700 hover:bg-gray-200').addClass('bg-primary text-white');

        selectedStatus = status;
        filterData();
    });

    // Activar el filtro "Todos" por defecto al cargar la página
    $('[data-status="todos"]').removeClass('bg-gray-100').addClass('bg-primary text-white');

    // Búsqueda
    $('#searchInput').on('keyup', function () {
        searchText = $(this).val().toLowerCase();
        if (searchText.length > 0) {
            $('#clearSearch').removeClass('hidden');
        } else {
            $('#clearSearch').addClass('hidden');
        }
        filterData();
    });

    $('#clearSearch').click(function () {
        $('#searchInput').val('');
        searchText = '';
        $(this).addClass('hidden');
        filterData();
    });

    // Cambiar items por página
    $('#perPage').change(function () {
        perPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
    });

    // Función para filtrar datos
    function filterData() {
        filteredData = ticketsData.filter(item => {
            // Filtro por estado
            if (selectedStatus !== 'todos' && item.estado !== selectedStatus) {
                return false;
            }

            // Filtro por búsqueda
            if (searchText) {
                const matchesSearch =
                    item.numeroTicket.toLowerCase().includes(searchText) ||
                    item.nombreCompleto.toLowerCase().includes(searchText) ||
                    item.correoElectronico.toLowerCase().includes(searchText) ||
                    item.telefonoCelular.includes(searchText) ||
                    item.marca.toLowerCase().includes(searchText) ||
                    item.modelo.toLowerCase().includes(searchText) ||
                    item.serie.toLowerCase().includes(searchText);

                if (!matchesSearch) return false;
            }

            // Filtro por fechas
            if (dateRange.start || dateRange.end) {
                const itemDate = item.fechaCreacion.split(' ')[0];

                if (dateRange.start && dateRange.end) {
                    return itemDate >= dateRange.start && itemDate <= dateRange.end;
                } else if (dateRange.start) {
                    return itemDate >= dateRange.start;
                } else if (dateRange.end) {
                    return itemDate <= dateRange.end;
                }
            }

            return true;
        });

        currentPage = 1;
        renderTable();
    }

    // Función para renderizar tabla (CON TODOS LOS DATOS CENTRADOS)
    function renderTable() {
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const paginatedData = filteredData.slice(start, end);

        let html = '';

        if (paginatedData.length === 0) {
            html = `
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                        <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-3"></i>
                        <p>No hay tickets para mostrar</p>
                    </td>
                </tr>
            `;
        } else {
            paginatedData.forEach(ticket => {
                html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300 text-center">
                            ${ticket.numeroTicket}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="font-medium flex items-center gap-1">
                                    <i class="fas fa-user text-gray-500 w-3 h-3"></i>
                                    ${ticket.nombreCompleto}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    ${ticket.correoElectronico}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-mobile-alt text-gray-500"></i>
                                    ${ticket.telefonoCelular}
                                </span>
                                ${ticket.telefonoFijo ?
                    `<span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <i class="fas fa-phone text-gray-400"></i>
                                        ${ticket.telefonoFijo}
                                    </span>` : ''}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="flex items-center gap-1">
                                    <span class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded-full">
                                        ${ticket.tipoProducto}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">${ticket.modelo}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-hashtag"></i>
                                    Serie: ${ticket.serie}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-calendar-alt text-gray-500"></i>
                                    ${ticket.fechaCreacion.split(' ')[0]}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    ${ticket.fechaCreacion.split(' ')[1]}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex justify-center">
                                ${getStatusBadge(ticket.estado)}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Botón Ver - con tooltip nativo -->
                                <button class="w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors view-ticket flex items-center justify-center"
                                        data-id="${ticket.id}"
                                        title="Ver detalles">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>

                                <!-- Botón Crear OT - con tooltip nativo -->
                                <button class="w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition-colors create-ot flex items-center justify-center"
                                        data-id="${ticket.id}"
                                        title="Crear Orden de Trabajo">
                                    <i class="fas fa-file-alt text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        $('#evaluarTicketsTableBody').html(html);
        renderPagination();
    }

    // Función para renderizar paginación (con Tailwind)
    function renderPagination() {
        const totalPages = Math.ceil(filteredData.length / perPage);
        if (totalPages <= 1) {
            $('#pagination').html('');
            return;
        }

        let paginationHtml = '';

        // Botón anterior
        paginationHtml += `
            <li class="inline-block">
                <a href="#" onclick="changePage(${currentPage - 1}); return false;"
                class="flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-700 ${currentPage === 1 ? 'text-gray-400 cursor-not-allowed bg-gray-50 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'}">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
            </li>
        `;

        // Páginas
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                paginationHtml += `
                    <li class="inline-block">
                        <a href="#" onclick="changePage(${i}); return false;"
                        class="flex items-center justify-center w-10 h-10 rounded-lg border ${i === currentPage ? 'border-primary bg-primary text-white' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'}">
                            ${i}
                        </a>
                    </li>
                `;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                paginationHtml += `
                    <li class="inline-block">
                        <span class="flex items-center justify-center w-10 h-10 text-gray-400">...</span>
                    </li>
                `;
            }
        }

        // Botón siguiente
        paginationHtml += `
            <li class="inline-block">
                <a href="#" onclick="changePage(${currentPage + 1}); return false;"
                class="flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-700 ${currentPage === totalPages ? 'text-gray-400 cursor-not-allowed bg-gray-50 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'}">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </li>
        `;

        $('#pagination').html(paginationHtml);
    }

    // Función para cambiar página
    window.changePage = function (page) {
        const totalPages = Math.ceil(filteredData.length / perPage);
        if (page >= 1 && page <= totalPages && page !== currentPage) {
            currentPage = page;
            renderTable();
        }
    };

    // Event listeners para botones de acción
    $(document).on('click', '.view-ticket', function () {
        const id = $(this).data('id');
        const ticket = ticketsData.find(t => t.id === id);
        if (ticket) {
            toastr.info(`Mostrando detalles del ticket ${ticket.numeroTicket}`);
        }
    });

    $(document).on('click', '.create-ot', function () {
        const id = $(this).data('id');
        const ticket = ticketsData.find(t => t.id === id);
        if (ticket) {
            if (confirm(`¿Desea crear una Orden de Trabajo para el ticket ${ticket.numeroTicket}?`)) {
                window.location.href = `/ordenes-trabajo/crear?ticket_id=${id}`;
            }
        }
    });

    // Renderizar tabla inicial
    filterData();

    // Activar el filtro "Todos" por defecto
    $('[data-status="todos"]').removeClass('bg-gray-100').addClass('bg-primary text-white');
});
