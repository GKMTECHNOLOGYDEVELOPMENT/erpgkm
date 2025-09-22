<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* Estilos para la paginación */
    #paginationHistorial {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }

    #paginationHistorial li {
        margin: 0 5px;
    }

    #paginationHistorial li button {
        min-width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: 1px solid #ddd;
        background: #f8f9fa;
        color: #333;
        cursor: pointer;
        transition: all 0.2s;
    }

    #paginationHistorial li button:hover {
        background: #e9ecef;
    }

    #paginationHistorial li button.bg-blue-600 {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    #paginationHistorial li button.bg-blue-600:hover {
        background: #1d4ed8;
    }
</style>

<div x-data="{ openModal: false }"
    class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full text-center sm:text-left">
    <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
        style="background-color: {{ $colorEstado }};">
        Orden de Trabajo N° {{ $orden->idTickets }}
    </span>

    <!-- Botón Flotante -->
    <button id="botonFlotante"
        class="bg-dark text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg shadow-md transition-all duration-200
                   text-xs sm:text-sm md:text-base flex items-center justify-center gap-1 sm:gap-2 w-full sm:w-auto"
        @click="openModal = true">
        <i class="fa-solid fa-clock-rotate-left text-sm sm:text-base md:text-lg"></i>
    </button>

    <!-- Fondo oscuro -->
    <div x-show="openModal" class="fixed inset-0 bg-[black]/60 z-40 transition-opacity duration-300"
        @click="openModal = false"></div>

    <!-- Modal deslizable desde la derecha -->
    <div x-show="openModal" x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
        class="fixed top-0 right-0 w-80 sm:w-[600px] md:w-[700px] lg:w-[800px] h-full bg-white dark:bg-gray-900 shadow-lg z-50 p-6 flex flex-col rounded-l-lg">

        <!-- Encabezado del modal -->
        <div class="flex justify-between items-center border-b pb-3 border-gray-300 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Historial de Cambios</h2>
            <button @click="openModal = false"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Tabs dentro del modal -->
        <div class="mt-4">
            <div x-data="{ activeTab: 'estados' }" class="flex flex-col h-full">

                <!-- Botones de Tabs -->
                <div class="flex space-x-4 border-b border-gray-300 dark:border-gray-700">
                    <button @click="activeTab = 'estados'"
                        :class="activeTab === 'estados' ? 'border-b-2 border-red-600 text-red-600' :
                            'text-gray-500 hover:text-gray-700'"
                        class="pb-2 font-semibold text-sm uppercase">
                        Estados
                    </button>
                    <button @click="activeTab = 'historial'"
                        :class="activeTab === 'historial' ? 'border-b-2 border-red-600 text-red-600' :
                            'text-gray-500 hover:text-gray-700'"
                        class="pb-2 font-semibold text-sm uppercase">
                        Historial de Cambios
                    </button>
                </div>

                <!-- TAB: Estados -->
                <div x-show="activeTab === 'estados'" class="overflow-x-auto mt-4">
                    <table class="min-w-[600px] border-collapse w-full">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-800">
                                <th class="px-4 py-2 text-center">Estado</th>
                                <th class="px-4 py-2 text-center">Usuario</th>
                                <th class="px-4 py-2 text-center">Fecha</th>
                                <th class="px-4 py-2 text-center">Más</th>
                            </tr>
                        </thead>
                        <tbody id="estadosTableBody">
                            <!-- Aquí se llenarán los estados de flujo -->
                        </tbody>
                    </table>
                </div>

                <!-- TAB: Historial -->
                <div x-show="activeTab === 'historial'" class="overflow-y-auto mt-4 flex-1">
                    <div class="overflow-x-auto rounded-lg shadow border border-gray-300 dark:border-gray-700">
                        <table
                            class="min-w-[600px] w-full divide-y divide-gray-200 dark:divide-gray-600 text-xs sm:text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-2 sm:px-6 py-3 text-center">Campo Modificado</th>
                                    <th class="px-2 sm:px-6 py-3 text-center">Antes</th>
                                    <th class="px-2 sm:px-6 py-3 text-center">Después</th>
                                    <th class="px-2 sm:px-6 py-3 text-center">Fecha de Cambio</th>
                                    <th class="px-2 sm:px-6 py-3 text-center">Modificado Por</th>
                                </tr>
                            </thead>
                            <tbody id="historialModificaciones"
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr id="preload" style="display:none;">
                                    <td colspan="5" class="px-6 py-4 text-center">Cargando datos...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación para historial -->
                    <div class="flex justify-center mt-4">
                        <ul id="paginationHistorial"
                            class="inline-flex items-center space-x-1 rtl:space-x-reverse bg-white dark:bg-gray-900 p-2 rounded-lg shadow">
                            <!-- Generado dinámicamente -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🛠️ Formulario de Detalles -->
<div class="p-6 mt-4">
    <form id="tuFormulario" action="{{ route('ordenes.helpdesk.update', $orden->idTickets) }}"
        enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Ticket -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-ticket text-gray-500"></i>
                    Ticket
                </label>
                <input type="text" id="numero_ticket" name="numero_ticket" class="form-input w-full bg-gray-100"
                    value="{{ $orden->numero_ticket }}">
            </div>

            <!-- Cliente -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-user text-gray-500"></i>
                    Cliente
                </label>
                <select id="idCliente" name="idCliente" class="select2 w-full bg-gray-100">
                    <option value="">Seleccionar Cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->idCliente }}"
                            {{ optional($orden->cliente)->idCliente == $cliente->idCliente ? 'selected' : '' }}>
                            {{ $cliente->nombre }} - {{ $cliente->documento }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cliente General -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-users text-gray-500"></i>
                    Cliente General
                </label>
                <select id="idClienteGeneral" name="Cliente General" class="form-input w-full select2">
                    <option value="" selected>Seleccionar Cliente General</option>
                    @if ($orden->clienteGeneral)
                        <option value="{{ $orden->clienteGeneral->idClienteGeneral }}" selected>
                            {{ $orden->clienteGeneral->descripcion }}
                        </option>
                    @endif
                </select>
            </div>
            <!-- Tienda -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-store text-gray-500"></i>
                    Tienda
                </label>
                <select id="idTienda" name="idTienda" class="select2 w-full bg-gray-100">
                    <option value="" disabled>Seleccionar Tienda</option>
                    @foreach ($tiendas as $tienda)
                        <option value="{{ $tienda->idTienda }}"
                            {{ $tienda->idTienda == $orden->idTienda ? 'selected' : '' }}>
                            {{ $tienda->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-map-marker-alt text-gray-500"></i>
                    Dirección
                </label>
                <input type="text" id="direccion" class="form-input w-full bg-gray-100"
                    value="{{ $orden->tienda->direccion ?? '' }}" readonly>
            </div>

            <!-- Ejecutar -->
            @if ($existeFlujo25)
                <div>
                    <label class="text-sm font-medium">Ejecutor</label>
                    <select id="ejecutor" name="ejecutor" class="select2 w-full">
                        <option value="" disabled>Seleccionar Ejecutador</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->idUsuario }}"
                                {{ $usuario->idUsuario == $orden->ejecutor ? 'selected' : '' }}>
                                {{ $usuario->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Tipo de Servicio -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-gray-600"></i>
                    Tipo de Servicio
                </label>
                <select id="tipoServicio" name="tipoServicio" class="select2 w-full bg-gray-100">
                    <option value="" disabled>Seleccionar Tipo de Servicio</option>
                    @foreach ($tiposServicio as $tipo)
                        <option value="{{ $tipo->idTipoServicio }}"
                            {{ $tipo->idTipoServicio == $orden->tipoServicio ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
                <!-- Input oculto para mantener el valor al enviar el formulario -->
                <input type="hidden" name="tipoServicio" value="{{ $orden->tipoServicio }}">
            </div>

            <!-- Falla Reportada -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium"><i class="fa-solid fa-triangle-exclamation text-gray-500"></i>
                    Falla Reportada</label>
                <textarea id="fallaReportada" name="fallaReportada" rows="2" class="form-input w-full">{{ $orden->fallaReportada }}</textarea>
            </div>
            <!-- Nrm. Cotizacion -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-gray-500"></i>
                    Nrm. Cotización
                </label>

                <input id="nrmcotizacion" name="nrmcotizacion" type="text" class="form-input w-full bg-gray-100"
                    value="{{ $orden->nrmcotizacion }}">
            </div>


            @if ($idRol != 6)
                <!-- Botón de Guardar -->
                <div class="md:col-span-2 flex justify-end space-x-4">
                    <a href="{{ route('ordenes.helpdesk') }}"
                        class="btn btn-outline-danger w-full md:w-auto">Volver</a>
                    <button id="guardarFallaReportada" class="btn btn-primary w-full md:w-auto">Modificar</button>
                </div>
            @endif

        </div>
    </form>
</div>

@if ($idRol != 6)
    <!-- Nueva Card: Historial de Estados -->
    <div id="estadosCard" class="mt-4 p-4">
        <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
            style="background-color: {{ $colorEstado }};">Historial de Estados</span>

        <!-- Contenedor de Estados -->
        <div class="mt-3 overflow-x-auto">
            <div id="draggableContainer" class="flex space-x-2 w-max">
                @foreach ($estadosFlujo as $estado)
                    <div class="estado-button min-w-[120px] sm:min-w-[140px] px-4 py-2 rounded-lg cursor-pointer text-white text-center shadow-md"
                        style="background-color: {{ $estado->color }}; color: black;"
                        data-state-description="{{ $estado->descripcion }}">
                        {{ $estado->descripcion }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Última modificación -->
        <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:gap-2">
            <span class="text-sm sm:text-base font-medium text-gray-700 dark:text-white">
                Última modificación:
            </span>
            <span id="ultimaModificacion"
                class="bg-gray-100 dark:bg-gray-700 px-3 py-1.5 border border-gray-300 dark:border-gray-600 
                   rounded-md text-gray-800 dark:text-white text-xs sm:text-sm w-full sm:w-auto text-center sm:text-left">
            </span>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Variables globales
    const ticketId = '{{ $orden->idTickets }}';
    const estadosFlujo = @json($estadosFlujo);

    // -----------------------
    // 🔹 HISTORIAL DE CAMBIOS
    // -----------------------
    function cargarHistorialModificaciones(page = 1) {
        const tbody = $('#historialModificaciones');
        const preload = $('#preload');

        preload.show();
        tbody.hide();

        $.ajax({
            url: `/ticket/${ticketId}/historial-modificaciones`,
            method: 'GET',
            data: {
                page,
                per_page: 10
            },
            success: renderHistorial,
            error: () => {
                tbody.html(
                    '<tr><td colspan="5" class="text-center py-4 text-red-500">Error al cargar datos</td></tr>'
                );
            },
            complete: () => {
                preload.hide();
                tbody.show();
            }
        });
    }

    // Función para renderizar el historial
    function renderHistorial(response) {
        const tbody = $('#historialModificaciones');
        tbody.empty();

        if (response.data?.length) {
            const labels = {
                idCliente: 'Cliente',
                idTienda: 'Tienda',
                numero_ticket: 'Ticket',
                fallaReportada: 'Falla Reportada',
                tipoServicio: 'Tipo de Servicio',
                ejecutor: 'Ejecutor',
                estado: 'Estado',
                nrmcotizacion: 'N° Cotización'
            };

            response.data.forEach(modificacion => {
                // Formatear valores antiguos y nuevos
                const valorAntiguo = formatHistorialValue(modificacion.campo, modificacion.valor_antiguo);
                const valorNuevo = formatHistorialValue(modificacion.campo, modificacion.valor_nuevo);

                tbody.append(`
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2 whitespace-nowrap">${labels[modificacion.campo] || modificacion.campo}</td>
                    <td class="px-4 py-2">${valorAntiguo || 'N/A'}</td>
                    <td class="px-4 py-2">${valorNuevo || 'N/A'}</td>
                    <td class="px-4 py-2 whitespace-nowrap">${formatDateTime(modificacion.fecha_modificacion)}</td>
                    <td class="px-4 py-2">${modificacion.usuario || 'N/A'}</td>
                </tr>
            `);
            });


            renderPaginationHistorial(response);
        } else {
            tbody.append('<tr><td colspan="5" class="text-center py-4">No hay registros de modificaciones</td></tr>');
        }
    }

    // Función para formatear valores específicos del historial
    function formatHistorialValue(campo, valor) {
        if (!valor) return '';
        if (campo === 'estado') {
            const estado = estadosFlujo.find(e => e.idEstadflujo == valor);
            return estado ? estado.descripcion : valor;
        }
        if (campo.toLowerCase().includes('fecha')) return formatDateTime(valor);
        return valor;
    }

    // Función para formatear fecha y hora
    function formatDateTime(dateString) {
        if (!dateString) return '';

        const date = new Date(dateString);
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        };

        return date.toLocaleString('es-ES', options);
    }

    // Función para renderizar la paginación del historial
    function renderPaginationHistorial(response) {
        const pagination = $('#paginationHistorial');
        pagination.empty();

        if (!response || response.last_page <= 1) return;

        const currentPage = response.current_page;
        const lastPage = response.last_page;

        // Botón Anterior
        if (currentPage > 1) {
            pagination.append(`
            <li>
                <button data-page="${currentPage - 1}" 
                    class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </li>
        `);
        }

        // Mostrar siempre la primera página
        if (currentPage > 2) {
            pagination.append(`
            <li>
                <button data-page="1"
                    class="px-3 py-1 rounded ${1 === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600'}">
                    1
                </button>
            </li>
        `);

            if (currentPage > 3) {
                pagination.append('<li class="px-2 py-1 text-gray-500">...</li>');
            }
        }

        // Mostrar páginas cercanas a la actual
        const startPage = Math.max(1, currentPage - 1);
        const endPage = Math.min(lastPage, currentPage + 1);

        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`
            <li>
                <button data-page="${i}"
                    class="px-3 py-1 rounded ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600'}">
                    ${i}
                </button>
            </li>
        `);
        }

        // Mostrar última página si no está visible
        if (currentPage < lastPage - 1) {
            if (currentPage < lastPage - 2) {
                pagination.append('<li class="px-2 py-1 text-gray-500">...</li>');
            }

            pagination.append(`
            <li>
                <button data-page="${lastPage}"
                    class="px-3 py-1 rounded ${lastPage === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600'}">
                    ${lastPage}
                </button>
            </li>
        `);
        }

        // Botón Siguiente
        if (currentPage < lastPage) {
            pagination.append(`
            <li>
                <button data-page="${currentPage + 1}"
                    class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </li>
        `);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Variables locales
        let historialCompleto = [];
        let paginaActual = 1;
        const registrosPorPagina = 10;

        // 1. Inicialización de plugins
        function initializePlugins() {
            // Select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'Seleccionar una opción',
                allowClear: true,
                dropdownParent: $('body')
            });

            // Flatpickr
            flatpickr("#fechaCompra", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
        }

        // 2. Funciones de utilidad
        function formatDate(fecha) {
            if (!fecha) return '';
            const date = new Date(fecha);
            const año = date.getFullYear();
            const mes = String(date.getMonth() + 1).padStart(2, '0');
            const dia = String(date.getDate()).padStart(2, '0');
            let horas = date.getHours();
            const minutos = String(date.getMinutes()).padStart(2, '0');
            const ampm = horas >= 12 ? 'PM' : 'AM';
            horas = horas % 12 || 12;
            return `${año}-${mes}-${dia} ${horas}:${minutos} ${ampm}`;
        }

        function obtenerLabelsFormulario() {
            const labels = {};
            document.querySelectorAll("form label").forEach(label => {
                const inputId = label.getAttribute('for');
                if (inputId) {
                    const input = document.getElementById(inputId);
                    if (input) {
                        const name = input.getAttribute("name") || inputId;
                        labels[name] = label.textContent.trim();
                    }
                }
            });
            return labels;
        }

        // 4. Funciones para estados del ticket
        function cargarEstados() {
            fetch(`/ticket/${ticketId}/estados`)
                .then(response => response.json())
                .then(data => {
                    renderEstados(data);
                })
                .catch(error => {
                    console.error('Error cargando los estados:', error);
                    $('#estadosTableBody').html(
                        '<tr><td colspan="4" class="text-center py-4 text-red-500">Error al cargar estados</td></tr>'
                    );
                });
        }

        function renderEstados(data) {
            const estadosTableBody = document.getElementById("estadosTableBody");
            estadosTableBody.innerHTML = "";

            if (Array.isArray(data.estadosFlujo)) {
                data.estadosFlujo.forEach(ticketFlujo => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <td class="px-4 py-2 text-center" style="background-color: ${ticketFlujo.estado_color}">${ticketFlujo.estado_descripcion}</td>
                    <td class="px-4 py-2 text-center" style="background-color: ${ticketFlujo.estado_color}">${ticketFlujo.usuario_nombre || 'Sin Nombre'}</td>
                    <td class="px-4 py-2 text-center" style="background-color: ${ticketFlujo.estado_color}">${formatDate(ticketFlujo.fecha_creacion)}</td>
                    <td class="px-4 py-2 text-center space-x-2" style="background-color: ${ticketFlujo.estado_color}">
                        <button class="toggle-comment px-3 py-1 rounded bg-gray-300 hover:bg-gray-400">⋮</button>
                        <button class="save-comment px-3 py-1 rounded bg-success text-white hover:bg-green-600" 
                            data-flujo-id="${ticketFlujo.idTicketFlujo}">✔</button>
                    </td>
                `;
                    estadosTableBody.appendChild(row);
                });

                // Agregar eventos a los botones de comentarios
                document.querySelectorAll('.toggle-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        $(this).closest('tr').next('.comment-row').toggleClass('hidden');
                    });
                });

                document.querySelectorAll('.save-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        const flujoId = this.dataset.flujoId;
                        const comentario = $(this).closest('tr').next('.comment-row').find(
                            'textarea').val();

                        guardarComentario(flujoId, comentario);
                    });
                });
            }
        }

        function guardarComentario(flujoId, comentario) {
            fetch(`/ticket/${ticketId}/ticketflujo/${flujoId}/update`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        comentario
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        toastr.success("Comentario guardado correctamente.");
                    } else {
                        toastr.error("Error al guardar el comentario.");
                    }
                })
                .catch(error => {
                    console.error("Error al guardar el comentario:", error);
                    toastr.error("Error al guardar el comentario.");
                });
        }

        // 5. Funciones para selects dependientes
        function setupSelectDependientes() {
            // Cliente -> Cliente General
            $('#idCliente').on('change', function() {
                const clienteId = $(this).val();
                const $clienteGeneral = $('#idClienteGeneral');
                const $tienda = $('#idTienda');

                if (!clienteId) {
                    $clienteGeneral.val('').trigger('change').prop('disabled', true);
                    $tienda.val('').trigger('change').prop('disabled', true);
                    return;
                }

                // Cargar clientes generales
                $clienteGeneral.prop('disabled', true).empty();
                $.get(`/clientes-generales/${clienteId}`)
                    .then(data => {
                        $clienteGeneral.empty().append(
                            '<option value="">Seleccionar Cliente General</option>');
                        if (data.length > 0) {
                            data.forEach(cg => {
                                $clienteGeneral.append(new Option(cg.descripcion, cg
                                    .idClienteGeneral));
                            });
                        }
                        $clienteGeneral.val(
                                '{{ $orden->clienteGeneral ? $orden->clienteGeneral->idClienteGeneral : '' }}'
                            )
                            .trigger('change')
                            .prop('disabled', false);
                    })
                    .catch(error => {
                        console.error('Error cargando clientes generales:', error);
                        $clienteGeneral.empty().append('<option value="">Error al cargar</option>');
                    });

                // Cargar tiendas
                $tienda.prop('disabled', true).empty();
                $.get(`/api/cliente/${clienteId}`)
                    .then(clienteData => {
                        if (clienteData.idTipoDocumento == 8) {
                            $('#direccion').val(clienteData.direccion || '');
                        }

                        const endpoint = (clienteData.idTipoDocumento == 8 || clienteData
                                .esTienda == 0) ?
                            '/api/tiendas' :
                            `/api/cliente/${clienteId}/tiendas`;

                        return $.get(endpoint);
                    })
                    .then(tiendasData => {
                        $tienda.empty().append(
                            '<option value="" disabled>Seleccionar Tienda</option>');
                        if (tiendasData?.length > 0) {
                            tiendasData.forEach(tienda => {
                                $tienda.append(new Option(tienda.nombre, tienda.idTienda));
                            });
                        }
                        $tienda.val('{{ $orden->idTienda }}')
                            .trigger('change')
                            .prop('disabled', false);
                    })
                    .catch(error => {
                        console.error('Error cargando tiendas:', error);
                        $tienda.empty().append('<option value="">Error al cargar</option>');
                    });
            });

            // Tienda -> Dirección
            $('#idTienda').on('change', function() {
                const tiendaId = $(this).val();
                const clienteId = $('#idCliente').val();

                if (!tiendaId) {
                    $('#direccion').val('');
                    return;
                }

                $.get(`/api/cliente/${clienteId}`)
                    .then(clienteData => {
                        if (clienteData.idTipoDocumento != 8) {
                            return $.get(`/api/tienda/${tiendaId}`);
                        }
                        return null;
                    })
                    .then(tiendaData => {
                        if (tiendaData) {
                            $('#direccion').val(tiendaData.direccion || '');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }

        function setupEstadoButtons() {
            document.querySelectorAll(".estado-button").forEach(estadoElement => {
                estadoElement.addEventListener("click", function() {
                    const stateDescription = estadoElement.dataset.stateDescription;
                    const estado = estadosFlujo.find(e => e.descripcion === stateDescription);

                    if (estado) {
                        const usuario = "{{ auth()->user()->id }}";
                        const fecha = formatDate(new Date());

                        // Mostrar preload en el botón
                        const originalText = estadoElement.innerHTML;
                        estadoElement.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
                        estadoElement.style.pointerEvents =
                            'none'; // Deshabilitar clics adicionales

                        // Mostrar preload general en la página (opcional)
                        document.body.classList.add('waiting');

                        axios.post("{{ route('guardarEstado') }}", {
                                idTicket: ticketId,
                                idEstadflujo: estado.idEstadflujo,
                                idUsuario: usuario,
                            })
                            .then(response => {
                                toastr.success("Estado actualizado correctamente");

                                // Actualizar última modificación
                                document.getElementById('ultimaModificacion').textContent =
                                    `${fecha} por {{ auth()->user()->Nombre }}: Se modificó Estado a "${stateDescription}"`;

                                // Recargar datos (alternativa a recargar toda la página)
                                cargarEstados();
                                cargarHistorialModificaciones();
                                cargarUltimaModificacion();

                                // Opcional: Recargar toda la página después de 1 segundo
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            })
                            .catch(error => {
                                console.error("Error al guardar el estado", error);
                                toastr.error("Error al actualizar el estado");
                            })
                            .finally(() => {
                                // Restaurar estado del botón
                                estadoElement.innerHTML = originalText;
                                estadoElement.style.pointerEvents = 'auto';
                                document.body.classList.remove('waiting');
                            });
                    }
                });
            });
        }

        // 7. Configurar formulario de guardado
        function setupFormGuardado() {
            $('#guardarFallaReportada').on('click', function(e) {
                e.preventDefault();

                const formData = {
                    idCliente: $('#idCliente').val(),
                    idClienteGeneral: $('#idClienteGeneral').val(),
                    idTienda: $('#idTienda').val(),
                    numero_ticket: $('#numero_ticket').val(),
                    nrmcotizacion: $('#nrmcotizacion').val(),
                    fallaReportada: $('#fallaReportada').val(),
                    ejecutor: $('#ejecutor').val()
                };

                // Validación
                let isValid = true;
                for (const key in formData) {
                    if (formData[key] === '' || formData[key] === null) {
                        toastr.error(`El campo ${key} es requerido`);
                        isValid = false;
                        $(`[name="${key}"]`).addClass('border-red-500').focus();
                        break;
                    }
                }

                if (!isValid) return;

                // Mostrar loading
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                $.ajax({
                    url: '/actualizar-orden-ejecucion/' + ticketId,
                    method: 'PUT',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message || 'Orden actualizada con éxito');
                        // Actualizar última modificación
                        cargarUltimaModificacion();
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message ||
                            'Error al actualizar la orden';
                        toastr.error(errorMsg);
                        console.error('Error:', xhr.responseJSON);
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Modificar');
                    }
                });
            });
        }

        // 8. Cargar última modificación
        function cargarUltimaModificacion() {
            $.ajax({
                url: '/ultima-modificacion/' + ticketId,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const mod = response.ultima_modificacion;
                        const fecha = formatDate(mod.fecha_modificacion || mod.created_at);
                        document.getElementById('ultimaModificacion').textContent =
                            `${fecha} por ${mod.usuario}: Se modificó ${mod.campo} de "${mod.valor_antiguo}" a "${mod.valor_nuevo}"`;
                    }
                },
                error: function(xhr) {
                    console.error('Error al cargar última modificación:', xhr);
                }
            });
        }

        // 9. Inicialización completa
        function init() {
            initializePlugins();
            setupSelectDependientes();
            setupEstadoButtons();
            setupFormGuardado();
            cargarUltimaModificacion();

            // Si hay un cliente seleccionado al cargar, disparar el cambio
            @if ($orden->cliente)
                $('#idCliente').trigger('change');
            @endif

            // Evento para abrir modal
            document.getElementById('botonFlotante').addEventListener('click', function() {
                cargarEstados();
                cargarHistorialModificaciones(1); // Cargar siempre la página 1 al abrir
            });

            // Event delegation para paginación
            $(document).on('click', '#paginationHistorial button', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page) {
                    cargarHistorialModificaciones(page);
                }
            });

            // Inicializar campos del formulario
            initializeFieldValues();
        }

        // 10. Manejo de cambios en campos del formulario
        function initializeFieldValues() {
            $('#tuFormulario').find('input, select, textarea').each(function() {
                const field = $(this);
                field.data('old-value', field.is('select') ? field.find('option:selected').text() :
                    field.val());
            });
        }

        function handleFieldChange(e) {
            const field = $(e.target);
            const fieldName = field.attr('name') || field.attr('id');

            if (!fieldName) return;

            let oldValue = field.data('old-value') || '';
            let newValue = field.is('select') ? field.find('option:selected').text() : field.val();

            if (oldValue !== newValue) {
                const fieldLabel = $(`label[for="${field.attr('id')}"]`).text().trim() || fieldName;

                updateModificationLog(fieldLabel, oldValue, newValue);
                field.data('old-value', newValue);
            }
        }

        function updateModificationLog(field, oldValue, newValue) {
            const usuario = "{{ auth()->user()->Nombre }}";
            const fecha = formatDate(new Date());

            document.getElementById('ultimaModificacion').textContent =
                `${fecha} por ${usuario}: Se modificó ${field} de "${oldValue}" a "${newValue}"`;

            $.ajax({
                url: '/guardar-modificacion/' + ticketId,
                method: 'POST',
                data: {
                    field: field,
                    oldValue: oldValue,
                    newValue: newValue,
                    usuario: usuario,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                error: function(xhr) {
                    console.error('Error al guardar modificación:', xhr);
                }
            });
        }

        // Manejar blur en inputs de texto y textarea
        $('#tuFormulario').on('blur', 'input[type="text"], textarea', function() {
            const field = $(this);
            const fieldName = field.attr('name') || field.attr('id');
            if (!fieldName) return;

            let oldValue = field.data('old-value') || '';
            let newValue = field.val();

            if (oldValue !== newValue) {
                updateModificationLog(fieldName, oldValue, newValue);
                field.data('old-value', newValue);
            }
        });

        // Manejar cambios inmediatos en selects y otros inputs
        $('#tuFormulario').on('change', 'select, input:not([type="text"]):not([type="hidden"])', function() {
            const field = $(this);
            const fieldName = field.attr('name') || field.attr('id');
            if (!fieldName) return;

            let oldValue = field.data('old-value') || '';
            let newValue = field.is('select') ? field.find('option:selected').text() : field.val();

            if (oldValue !== newValue) {
                const fieldLabel = $(`label[for="${field.attr('id')}"]`).text().trim() || fieldName;
                updateModificationLog(fieldLabel, oldValue, newValue);
                field.data('old-value', newValue);
            }
        });
        // Iniciar todo cuando el DOM esté listo
        init();
    });
</script>
