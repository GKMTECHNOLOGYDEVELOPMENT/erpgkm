// Variables globales
let ticketsData = [];
let filteredData = [];
let currentPage = 1;
let perPage = 10;
let selectedStatus = 'todos';
let searchText = '';
let dateRange = { start: '', end: '' };
let isLoading = true;
let currentTicket = null;

const API_URL = window.API_URL || 'http://127.0.0.1:5000/api';

// Función para abrir el modal de detalles
function abrirModal(ticket) {
    console.log('📌 ===== ABRIENDO MODAL =====');
    console.log('📌 Ticket seleccionado:', ticket);
    console.log('📸 URLs de imágenes:');
    console.log('   - fotoVideoFalla:', ticket.fotoVideoFalla);
    console.log('   - fotoBoletaFactura:', ticket.fotoBoletaFactura);
    console.log('   - fotoNumeroSerie:', ticket.fotoNumeroSerie);
    console.log('📌 =========================');
    
    currentTicket = ticket;
    $('#ticketModal').removeClass('hidden').show();
    renderModalContent(ticket);
}

// Función para cerrar el modal
function cerrarModal() {
    console.log('🔚 Cerrando modal');
    $('#ticketModal').addClass('hidden').hide();
    currentTicket = null;
}

// Función para abrir modal de imagen ampliada
function abrirImageModal(src) {
    console.log('🔍 Abriendo imagen ampliada:', src);
    $('#ampliadaImagen').attr('src', src);
    $('#imageModal').removeClass('hidden').show();
}

// Función para cerrar modal de imagen ampliada
function cerrarImageModal() {
    console.log('🔚 Cerrando modal de imagen');
    $('#imageModal').addClass('hidden').hide();
    $('#ampliadaImagen').attr('src', '');
}

// Función para renderizar el contenido del modal
function renderModalContent(ticket) {
    if (!ticket) {
        console.log('❌ No hay ticket para renderizar');
        return;
    }

    console.log('🎨 Renderizando modal para ticket:', ticket.numeroTicket);

    const fechaCompra = ticket.fechaCompra ? new Date(ticket.fechaCompra).toLocaleDateString('es-PE') : 'No registrada';
    const fechaCreacion = ticket.fechaCreacion ? new Date(ticket.fechaCreacion).toLocaleString('es-PE') : 'No registrada';
    
    // Datos del cliente general
    const clienteGeneral = ticket.clienteGeneral || null;
    const clienteGeneralDescripcion = clienteGeneral ? clienteGeneral.descripcion : 'No asignado';
    const clienteGeneralFoto = clienteGeneral && clienteGeneral.foto ? clienteGeneral.foto : null;

    const html = `
        <div class="space-y-6">
            <!-- Información del Ticket y Cliente General -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-ticket-alt text-blue-600 dark:text-blue-400"></i>
                        <h4 class="font-semibold text-blue-800 dark:text-blue-300">Información del Ticket</h4>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-medium">N° Ticket:</span> ${ticket.numeroTicket || 'N/A'}</p>
                        <p><span class="font-medium">Fecha Creación:</span> ${fechaCreacion}</p>
                        <p><span class="font-medium">Estado:</span> ${getStatusBadge(ticket.estado || 'pendiente')}</p>
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex items-center gap-2">
                            ${renderClienteGeneralFoto(clienteGeneralFoto, clienteGeneralDescripcion)}
                            <h4 class="font-semibold text-indigo-800 dark:text-indigo-300">Cliente General</h4>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-medium">Empresa/Cliente:</span> ${clienteGeneralDescripcion}</p>
                        <p><span class="font-medium">ID Cliente:</span> ${ticket.idClienteGeneral || 'N/A'}</p>
                    </div>
                </div>
            </div>

            <!-- Datos del Cliente (persona que reporta) -->
            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
                    <h4 class="font-semibold text-purple-800 dark:text-purple-300">Datos del Contacto</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <p><span class="font-medium">Nombre:</span> ${ticket.nombreCompleto || 'N/A'}</p>
                        <p><span class="font-medium">Documento:</span> ${ticket.tipoDocumento || 'N/A'}: ${ticket.dni_ruc_ce || 'N/A'}</p>
                    </div>
                    <div>
                        <p><span class="font-medium">Email:</span> ${ticket.correoElectronico || 'N/A'}</p>
                        <p><span class="font-medium">Teléfonos:</span> ${ticket.telefonoCelular || 'N/A'} ${ticket.telefonoFijo ? '/ ' + ticket.telefonoFijo : ''}</p>
                    </div>
                </div>
            </div>

            <!-- Dirección -->
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-map-marker-alt text-green-600 dark:text-green-400"></i>
                    <h4 class="font-semibold text-green-800 dark:text-green-300">Dirección</h4>
                </div>
                <p><span class="font-medium">Dirección:</span> ${ticket.direccionCompleta || 'No registrada'}</p>
                <p><span class="font-medium">Referencia:</span> ${ticket.referenciaDomicilio || 'No registrada'}</p>
                <p><span class="font-medium">Ubicación:</span> ${ticket.distrito || ''}, ${ticket.provincia || ''}, ${ticket.departamento || ''}</p>
                ${ticket.ubicacionGoogleMaps ? `
                    <a href="${ticket.ubicacionGoogleMaps}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-blue-600 hover:underline">
                        <i class="fas fa-external-link-alt"></i>
                        Ver en Google Maps
                    </a>
                ` : ''}
            </div>

            <!-- Producto y Falla -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-laptop text-orange-600 dark:text-orange-400"></i>
                        <h4 class="font-semibold text-orange-800 dark:text-orange-300">Producto</h4>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-medium">Categoría:</span> ${ticket.tipoProducto || 'N/A'}</p>
                        <p><span class="font-medium">Marca:</span> ${ticket.marca || 'N/A'}</p>
                        <p><span class="font-medium">Modelo:</span> ${ticket.modelo || 'N/A'}</p>
                        <p><span class="font-medium">Serie:</span> ${ticket.serie || 'N/A'}</p>
                        <p><span class="font-medium">Fecha Compra:</span> ${fechaCompra}</p>
                        <p><span class="font-medium">Tienda:</span> ${ticket.tiendaSedeCompra || 'N/A'}</p>
                    </div>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        <h4 class="font-semibold text-red-800 dark:text-red-300">Detalles de la Falla</h4>
                    </div>
                    <p class="whitespace-pre-line">${ticket.detallesFalla || 'No especificada'}</p>
                </div>
            </div>

            <!-- Evidencias -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-camera text-gray-600 dark:text-gray-400"></i>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-300">Evidencias</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    ${renderImagen(ticket.fotoVideoFalla, 'Falla', 'video')}
                    ${renderImagen(ticket.fotoBoletaFactura, 'Boleta/Factura', 'file-invoice')}
                    ${renderImagen(ticket.fotoNumeroSerie, 'N° de Serie', 'hashtag')}
                </div>
            </div>
        </div>
    `;

    $('#modalContent').html(html);
}

// Función auxiliar para renderizar la foto del cliente general
function renderClienteGeneralFoto(foto, descripcion) {
    if (foto) {
        return `<img src="${foto}" alt="${descripcion}" 
                    class="w-8 h-8 rounded-full object-cover border border-indigo-300"
                    onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold" style="display: none;">
                    ${descripcion.charAt(0)}
                </div>`;
    } else {
        return `<div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">
                    ${descripcion.charAt(0)}
                </div>`;
    }
}

// Función auxiliar para renderizar imágenes
function renderImagen(url, titulo, icono) {
    console.log(`🖼️ Renderizando imagen ${titulo}:`, url ? url : 'URL vacía');
    
    if (!url) {
        console.log(`❌ No hay URL para ${titulo}, mostrando placeholder "Sin imagen"`);
        return `
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-${icono} text-gray-400"></i>
                    <span class="font-medium text-sm">${titulo}</span>
                </div>
                <div class="h-32 flex items-center justify-center bg-gray-100 dark:bg-gray-600 rounded">
                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                    <span class="text-xs text-gray-500 ml-2">Sin imagen</span>
                </div>
            </div>
        `;
    }

    console.log(`✅ URL encontrada para ${titulo}:`, url);
    
    return `
        <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-${icono} text-blue-600 dark:text-blue-400"></i>
                <span class="font-medium text-sm">${titulo}</span>
            </div>
            <div class="relative group cursor-pointer" onclick="abrirImageModal('${url}')">
                <img src="${url}" alt="${titulo}" 
                     class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                     onerror="this.onerror=null; this.src='https://placehold.co/300x200/cccccc/000?text=Error+al+cargar'; console.error('❌ Error cargando imagen ${titulo}:', '${url}');">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center rounded-lg">
                    <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 text-2xl"></i>
                </div>
            </div>
        </div>
    `;
}

// Función para cargar tickets desde el backend
function cargarTickets() {
    isLoading = true;
    console.log('🔄 ===== CARGANDO TICKETS =====');
    console.log('🔄 Iniciando petición AJAX a:', `${API_URL}/evaluar-ticket/tickets`);
    
    $.ajax({
        url: `${API_URL}/evaluar-ticket/tickets`,
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            console.log('✅ Respuesta del servidor recibida:');
            console.log('   - success:', response.success);
            console.log('   - cantidad de datos:', response.data ? response.data.length : 0);
            
            if (response.success && response.data) {
                ticketsData = response.data;
                console.log('✅ Tickets cargados:', ticketsData.length);
                
                // Verificar las imágenes del primer ticket (si existe)
                if (ticketsData.length > 0) {
                    console.log('📸 ===== VERIFICANDO PRIMER TICKET =====');
                    console.log('   ID:', ticketsData[0].id);
                    console.log('   Ticket:', ticketsData[0].numeroTicket);
                    console.log('   Cliente General:', ticketsData[0].clienteGeneral);
                    console.log('   fotoVideoFalla:', ticketsData[0].fotoVideoFalla);
                    console.log('   fotoBoletaFactura:', ticketsData[0].fotoBoletaFactura);
                    console.log('   fotoNumeroSerie:', ticketsData[0].fotoNumeroSerie);
                    
                    // Contar cuántos tickets tienen imágenes
                    let conFalla = ticketsData.filter(t => t.fotoVideoFalla).length;
                    let conBoleta = ticketsData.filter(t => t.fotoBoletaFactura).length;
                    let conSerie = ticketsData.filter(t => t.fotoNumeroSerie).length;
                    
                    console.log('📊 ESTADÍSTICAS DE IMÁGENES:');
                    console.log('   - Tickets con foto de falla:', conFalla);
                    console.log('   - Tickets con foto de boleta:', conBoleta);
                    console.log('   - Tickets con foto de serie:', conSerie);
                    console.log('📸 =================================');
                } else {
                    console.log('⚠️ No hay tickets cargados');
                }
                
                filterData();
                
                if (ticketsData.length > 0) {
                    toastr.success(`${ticketsData.length} tickets cargados correctamente`);
                } else {
                    toastr.info('No hay tickets disponibles para evaluar');
                }
            } else {
                console.error('❌ Error en la respuesta:', response.message || 'Respuesta inválida');
                toastr.error(response.message || 'Error al cargar los datos');
                ticketsData = [];
                renderTable();
            }
            isLoading = false;
            console.log('🔄 ===== FIN CARGA DE TICKETS =====');
        },
        error: function(xhr, status, error) {
            console.error('❌ ===== ERROR EN PETICIÓN AJAX =====');
            console.error('   Status:', status);
            console.error('   Error:', error);
            console.error('   Respuesta completa:', xhr);
            console.error('   Status code:', xhr.status);
            console.error('   Response text:', xhr.responseText);
            console.error('❌ =================================');
            
            let errorMsg = 'Error al conectar con el servidor';
            
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMsg = response.message;
                }
            } catch (e) {
                // Si no se puede parsear, usar mensaje por defecto
            }
            
            toastr.error(errorMsg);
            ticketsData = [];
            renderTable();
            isLoading = false;
        }
    });
}

// Función para obtener badge de estado
function getStatusBadge(estado) {
    const statusConfig = {
        evaluando: {
            color: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            icon: 'fa-search',
            text: 'Evaluando'
        },
        gestionando: {
            color: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            icon: 'fa-tools',
            text: 'Gestionando'
        },
        finalizado: {
            color: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            icon: 'fa-check-double',
            text: 'Finalizado'
        }
    };

    const config = statusConfig[estado] || statusConfig.evaluando;

    return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium ${config.color}">
        <i class="fas ${config.icon} fa-xs"></i>
        ${config.text}
    </span>`;
}

// Inicializar Flatpickr
if ($("#startDate").length) {
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
}

if ($("#endDate").length) {
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
}

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

// Filtros de estado
$('.filter-btn').click(function () {
    const status = $(this).data('status');

    // Resetear todos los botones a estado inactivo
    $('.filter-btn').removeClass('bg-primary text-white')
        .addClass('bg-gray-100 text-gray-700 hover:bg-gray-200');

    // Activar el botón seleccionado
    $(this).removeClass('bg-gray-100 text-gray-700 hover:bg-gray-200')
           .addClass('bg-primary text-white');

    selectedStatus = status;
    filterData();
});

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

// Botón refrescar
$('#refreshData').click(function() {
    cargarTickets();
});

// Función para filtrar datos - ACTUALIZADA
function filterData() {
    if (!ticketsData || ticketsData.length === 0) {
        filteredData = [];
        renderTable();
        return;
    }

    filteredData = ticketsData.filter(item => {
        // Filtro por estado
        if (selectedStatus !== 'todos' && item.estado !== selectedStatus) {
            return false;
        }

        // Filtro por búsqueda
        if (searchText) {
            const searchLower = searchText.toLowerCase();
            
            // Obtener descripción del cliente general para búsqueda
            const clienteGeneralDesc = item.clienteGeneral ? item.clienteGeneral.descripcion.toLowerCase() : '';
            
            const matchesSearch = 
                (item.numeroTicket && item.numeroTicket.toLowerCase().includes(searchLower)) ||
                (item.nombreCompleto && item.nombreCompleto.toLowerCase().includes(searchLower)) ||
                (clienteGeneralDesc && clienteGeneralDesc.includes(searchLower)) ||
                (item.correoElectronico && item.correoElectronico.toLowerCase().includes(searchLower)) ||
                (item.telefonoCelular && item.telefonoCelular.includes(searchText)) ||
                (item.dni_ruc_ce && item.dni_ruc_ce.includes(searchText)) ||
                (item.marca && item.marca.toLowerCase().includes(searchLower)) ||
                (item.modelo && item.modelo.toLowerCase().includes(searchLower)) ||
                (item.serie && item.serie.toLowerCase().includes(searchLower));

            if (!matchesSearch) return false;
        }

        // Filtro por fechas
        if (dateRange.start || dateRange.end) {
            const itemDate = item.fechaCreacion ? item.fechaCreacion.split(' ')[0] : '';

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

// Función para renderizar tabla - CORREGIDA (cliente general y contacto separados)
function renderTable() {
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const paginatedData = filteredData.slice(start, end);

    let html = '';

    if (!ticketsData || ticketsData.length === 0) {
        html = `
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                    <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-3"></i>
                    <p>No hay tickets disponibles</p>
                    <p class="text-sm text-gray-400 mt-2">Los tickets aparecerán aquí cuando sean creados</p>
                </td>
            </tr>
        `;
    } else if (paginatedData.length === 0) {
        html = `
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                    <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                    <p>No se encontraron tickets con los filtros aplicados</p>
                    <button class="btn btn-sm btn-primary mt-3" onclick="limpiarTodosFiltros()">
                        <i class="fas fa-times mr-1"></i>
                        Limpiar filtros
                    </button>
                </td>
            </tr>
        `;
    } else {
        paginatedData.forEach(ticket => {
            // Determinar la marca y modelo para mostrar
            const marcaTexto = ticket.marca ? ticket.marca : '';
            const modeloTexto = ticket.modelo ? ticket.modelo : 'N/A';
            
            // Obtener datos del cliente general
            const clienteGeneral = ticket.clienteGeneral || null;
            const clienteGeneralDescripcion = clienteGeneral ? clienteGeneral.descripcion : 'N/A';
            const clienteGeneralFoto = clienteGeneral && clienteGeneral.foto ? clienteGeneral.foto : null;
            
            html += `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300 text-center font-mono font-bold">
                        ${ticket.numeroTicket || 'N/A'}
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="flex items-center gap-2 mb-1">
                                ${renderClienteGeneralTableFoto(clienteGeneralFoto, clienteGeneralDescripcion)}
                                <span class="font-medium text-sm" title="${clienteGeneralDescripcion}">
                                    ${clienteGeneralDescripcion}
                                </span>
                            </div>
                           
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="font-medium text-xs text-gray-800 dark:text-gray-200">
                                <i class="fas fa-user text-gray-500 mr-1"></i>
                                ${ticket.nombreCompleto || 'N/A'}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-phone text-gray-400"></i>
                                ${ticket.telefonoCelular || 'N/A'} ${ticket.telefonoFijo ? ' / ' + ticket.telefonoFijo : ''}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-envelope text-gray-400"></i>
                                ${ticket.correoElectronico || 'N/A'}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-id-card text-gray-400"></i>
                                ${ticket.tipoDocumento || 'N/A'}: ${ticket.dni_ruc_ce || 'N/A'}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="flex items-center gap-1 mb-1">
                                <span class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded-full">
                                    ${ticket.tipoProducto || 'N/A'}
                                </span>
                                ${marcaTexto ? `
                                    <span class="text-xs bg-blue-100 dark:bg-blue-900 px-1.5 py-0.5 rounded-full text-blue-800 dark:text-blue-300">
                                        ${marcaTexto}
                                    </span>
                                ` : ''}
                            </div>
                            <span class="text-xs font-medium">${modeloTexto}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                <i class="fas fa-hashtag"></i>
                                Serie: ${ticket.serie || 'N/A'}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar-alt text-gray-500"></i>
                                ${ticket.fechaCreacion ? ticket.fechaCreacion.split(' ')[0] : 'N/A'}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                ${ticket.fechaCreacion ? ticket.fechaCreacion.split(' ')[1] : ''}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <div class="flex justify-center">
                            ${getStatusBadge(ticket.estado || 'pendiente')}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Botón Ver detalles -->
                            <button class="w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors view-ticket flex items-center justify-center"
                                    data-id="${ticket.id}"
                                    title="Ver detalles del ticket">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <!-- Botón Evaluar -->
                            <button class="w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition-colors evaluate-ticket flex items-center justify-center"
                                    data-id="${ticket.id}"
                                    title="Evaluar ticket">
                                <i class="fas fa-clipboard-check text-sm"></i>
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

// Función auxiliar para renderizar la foto del cliente general en la tabla
function renderClienteGeneralTableFoto(foto, descripcion) {
    if (foto) {
        return `<img src="${foto}" alt="${descripcion}" 
                    class="w-6 h-6 rounded-full object-cover border border-gray-300"
                    onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold" style="display: none;">
                    ${descripcion.charAt(0)}
                </div>`;
    } else {
        return `<div class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold">
                    ${descripcion.charAt(0)}
                </div>`;
    }
}

// Función para renderizar paginación
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

// Función para limpiar todos los filtros
window.limpiarTodosFiltros = function() {
    // Resetear filtros de estado
    selectedStatus = 'todos';
    $('.filter-btn').removeClass('bg-primary text-white')
        .addClass('bg-gray-100 text-gray-700 hover:bg-gray-200');
    $('#filterTodos').removeClass('bg-gray-100').addClass('bg-primary text-white');
    
    // Resetear búsqueda
    $('#searchInput').val('');
    searchText = '';
    $('#clearSearch').addClass('hidden');
    
    // Resetear fechas
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
    
    // Aplicar filtros
    filterData();
};

// Event listeners para botones de acción
$(document).on('click', '.view-ticket', function () {
    const id = $(this).data('id');
    const ticket = ticketsData.find(t => t.id == id);
    
    if (ticket) {
        abrirModal(ticket);
    } else {
        console.error('❌ Ticket no encontrado con ID:', id);
        toastr.error('No se encontraron los detalles del ticket');
    }
});

$(document).on('click', '.evaluate-ticket', function () {
    const id = $(this).data('id');
    toastr.info(`Funcionalidad de evaluación para ticket #${id} - Próximamente disponible`);
});

// Activar el filtro "Todos" por defecto
$('#filterTodos').addClass('bg-primary text-white');

// Cargar los datos al iniciar
cargarTickets();