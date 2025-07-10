<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<!-- Estilos adicionales para el log -->


<!-- 📌 Encabezado de la Orden + Botón Historial -->
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
                    <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Campo</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Valor Antiguo</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Valor Nuevo</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Fecha de Modificación</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="historialModificaciones">
                            <!-- Preload visible mientras se cargan los datos -->
                            <tr id="preload" style="display: none;">
                                <td colspan="5" class="text-center text-gray-900 dark:text-gray-200">
                                    <span class="w-5 h-5 m-auto mb-10">
                                        <span
                                            class="animate-ping inline-flex h-full w-full rounded-full bg-info dark:bg-blue-500"></span>
                                    </span>
                                    Cargando datos...
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginación para historial -->
                    <div class="flex justify-center mt-4">
                        <ul id="pagination" class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto mb-4">
                            <!-- Se genera dinámicamente -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <!-- Contenedor de paginación -->
        <div class="flex justify-center mt-4">
            <ul id="pagination" class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto mb-4">
                <!-- Los botones de paginación se generarán dinámicamente -->
            </ul>
        </div>
    </div>
</div>




<script>
    // Suponiendo que tienes un ID de ticket disponible en tu página
    const ticketId = '{{ $id }}'; // Error aquí, ya que ticketId ya fue declarado en PHP

    function obtenerLabelsFormulario() {
        const labels = {
            horaInicioInput: 'Hora Inicio',
            horaFinInput: 'Hora Fin',
            fechaVisitaInput: 'Fecha Visita',
            nombreVisitaInput: 'Nombre de la Visita',
            // Podés seguir agregando más si querés personalizar más campos
        };

        document.querySelectorAll("form label").forEach(label => {
            const input = label.nextElementSibling || label.parentElement.querySelector(
                'input, select, textarea');
            if (input) {
                const name = input.getAttribute("name") || input.getAttribute("id");
                if (name && !labels[name]) {
                    labels[name] = label.textContent.trim(); // fallback si no está en el diccionario
                }
            }
        });

        return labels;
    }


    window.addEventListener('toggle-modal', function() {
        obtenerLabelsFormulario(); // 🔹 Asegurar que los labels se capturen antes de cargar el historial
        cargarHistorialModificaciones(ticketId);
    });

    // Variables globales para paginación
    let historialCompleto = [];
    let paginaActual = 1;
    const registrosPorPagina = 10;
    // Función para cargar el historial con paginación
    function cargarHistorialModificaciones(ticketId) {
        const labels = obtenerLabelsFormulario(); // Obtener los labels del formulario

        $.ajax({
            url: `/ticket/${ticketId}/historial-modificaciones`,
            method: 'GET',
            success: function(response) {
                console.log(response);
                historialCompleto = response; // Guardar el historial completo
                paginaActual = 1; // Reiniciar a la primera página
                mostrarPagina(labels); // Mostrar la primera página
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar el historial de modificaciones", error);
            }
        });
    }

    // Función para mostrar una página específica
    function mostrarPagina(labels) {
        const tbody = document.getElementById('historialModificaciones');
        tbody.innerHTML = '';

        const inicio = (paginaActual - 1) * registrosPorPagina;
        const fin = inicio + registrosPorPagina;
        const paginaDatos = historialCompleto.slice(inicio, fin);

        paginaDatos.forEach(modificacion => {
            const tr = document.createElement('tr');

            // Usar el label en lugar del nombre del campo
            const campoLabel = labels[modificacion.campo] || modificacion.campo;

            tr.innerHTML = `
            <td class="border border-gray-300 px-4 py-2 text-sm">${campoLabel}</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">${modificacion.valor_antiguo ?? '—'}</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">${modificacion.valor_nuevo ?? '—'}</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">${modificacion.fecha_modificacion}</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">${modificacion.usuario}</td>
        `;

            tbody.appendChild(tr);
        });

        actualizarPaginacion();
    }

    // Función para actualizar la paginación dinámica con botones numerados
    function actualizarPaginacion() {
        const totalPaginas = Math.ceil(historialCompleto.length / registrosPorPagina);
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        // Botón "Anterior"
        const prevButton = document.createElement('li');
        prevButton.innerHTML = `
        <button id="prevPage" class="flex justify-center font-semibold p-2 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" ${paginaActual === 1 ? 'disabled' : ''}>
            <i class="fa-solid fa-chevron-left"></i>
        </button>
    `;
        paginationContainer.appendChild(prevButton);

        // Números de páginas
        for (let i = 1; i <= totalPaginas; i++) {
            const pageButton = document.createElement('li');
            pageButton.innerHTML = `
            <button data-page="${i}" class="flex justify-center font-semibold px-3.5 py-2 rounded-full transition ${paginaActual === i ? 'bg-primary text-white' : 'bg-white-light text-dark hover:text-white hover:bg-primary'} dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary">
                ${i}
            </button>
        `;
            paginationContainer.appendChild(pageButton);
        }

        // Botón "Siguiente"
        const nextButton = document.createElement('li');
        nextButton.innerHTML = `
        <button id="nextPage" class="flex justify-center font-semibold p-2 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" ${paginaActual === totalPaginas ? 'disabled' : ''}>
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    `;
        paginationContainer.appendChild(nextButton);

        // Eventos de paginación
        document.getElementById('prevPage').addEventListener('click', () => {
            if (paginaActual > 1) {
                paginaActual--;
                mostrarPagina(obtenerLabelsFormulario());
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            if (paginaActual < totalPaginas) {
                paginaActual++;
                mostrarPagina(obtenerLabelsFormulario());
            }
        });

        // Evento para los números de página
        document.querySelectorAll('[data-page]').forEach(button => {
            button.addEventListener('click', (event) => {
                paginaActual = parseInt(event.target.getAttribute('data-page'));
                mostrarPagina(obtenerLabelsFormulario());
            });
        });
    }
    document.getElementById('botonFlotante').addEventListener('click', function() {
        // Mostrar el preload cuando se haga clic en el botón
        const tbody = document.getElementById('historialModificaciones');
        const preload = document.getElementById('preload');
        preload.style.display = 'table-row'; // Mostrar el preload

        // Llamar la función que carga las modificaciones
        cargarHistorialModificaciones(ticketId, tbody, preload);
    });
</script>






<!-- 🛠️ Formulario de Detalles -->
<div class="p-6 mt-4">
    <form id="tuFormulario" action="{{ route('ordenes.helpdesk.update', $orden->idTickets) }}" enctype="multipart/form-data"
        method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Ticket -->
            <div>
                <label class="text-sm font-medium">Ticket</label>
                <input type="text" id="numero_ticket" name="numero_ticket" class="form-input w-full bg-gray-100" value="{{ $orden->numero_ticket }}">
            </div>

            <!-- Cliente -->
            <div>
                <label class="text-sm font-medium">Cliente</label>
                <select id="idCliente" name="idCliente" class="select2 w-full bg-gray-100" style="display: none">
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
                <label class="text-sm font-medium">Cliente General</label>
                <select id="idClienteGeneral" name="idClienteGeneral" class="form-input w-full">
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
                <label class="text-sm font-medium">Tienda</label>
                <select id="idTienda" name="idTienda" class="select2 w-full bg-gray-100" style="display: none;">
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
                <label class="text-sm font-medium">Direción</label>
                <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->tienda->direccion ?? '' }}"
                    readonly>
            </div>

            <!-- Ejecutar -->
            @if ($existeFlujo25)
            <div>
                <label class="text-sm font-medium">Ejecutor</label>
                <select id="ejecutor" name="ejecutor" class="select2 w-full" style="display: none;">
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
                <label class="text-sm font-medium">Tipo de Servicio</label>
                <select id="tipoServicio" name="tipoServicio" class="select2 w-full bg-gray-100"
                    style="display: none" >
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
                <label class="text-sm font-medium">Falla Reportada</label>
                <textarea id="fallaReportada" name="fallaReportada" rows="2" class="form-input w-full">{{ $orden->fallaReportada }}</textarea>
            </div>

            <!-- Botón de Guardar -->
            <div class="md:col-span-2 flex justify-end space-x-4">
                <a href="{{ route('ordenes.helpdesk') }}" class="btn btn-outline-danger w-full md:w-auto">Volver</a>
                <button id="guardarFallaReportada" class="btn btn-primary w-full md:w-auto">Modificar</button>
            </div>
        </div>
    </form>
</div>


<!-- Nueva Card: Historial de Estados -->
<div id="estadosCard" class="mt-4 p-4">
    <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
        style="background-color: {{ $colorEstado }};">Historial de Estados</span>
    <!-- Tabla con scroll horizontal -->



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



    <!-- Div para mostrar la última modificación (Responsive) -->
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







<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ticketId = "{{ $ticket->idTickets }}"; // ID del ticket
        const rowsPerPage = 15; // Número de filas por página
        let currentPage = 1; // Página actual

        function cargarEstados() {
            fetch(`/ticket/${ticketId}/estados`)
                .then(response => response.json())
                .then(data => {
                    const estadosTableBody = document.getElementById("estadosTableBody");
                    estadosTableBody.innerHTML = ""; // Limpiar la tabla antes de agregar los nuevos estados

                    if (Array.isArray(data.estadosFlujo)) {
                        const estados = data.estadosFlujo;
                        renderTable(estados, currentPage);
                        setupPagination(estados.length);
                    } else {
                        console.error('La respuesta no contiene un array de estados de flujo:', data
                            .estadosFlujo);
                    }
                })
                .catch(error => {
                    console.error('Error cargando los estados:', error);
                });
        }

        function renderTable(estados, page) {
            const estadosTableBody = document.getElementById("estadosTableBody");
            estadosTableBody.innerHTML = "";

            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const estadosPaginados = estados.slice(start, end);

            estadosPaginados.forEach(ticketFlujo => {
                const estado = ticketFlujo.estado_descripcion; // Cambié a 'estado_descripcion'
                const usuario = ticketFlujo.usuario_nombre; // Cambié a 'usuario_nombre'

                // Fila principal
                const row = document.createElement("tr");

                const estadoCell = document.createElement("td");
                estadoCell.classList.add("px-4", "py-2", "text-center", "text-black");
                estadoCell.style.backgroundColor = ticketFlujo.estado_color; // Usar 'estado_color'
                estadoCell.textContent = estado;

                const usuarioCell = document.createElement("td");
                usuarioCell.classList.add("px-4", "py-2", "text-center", "text-black");
                usuarioCell.textContent = usuario ? usuario : 'Sin Nombre';
                usuarioCell.style.backgroundColor = ticketFlujo.estado_color; // Usar 'estado_color'

                const fechaCell = document.createElement("td");
                fechaCell.classList.add("px-4", "py-2", "text-center", "text-black");
                fechaCell.textContent = ticketFlujo.fecha_creacion;
                fechaCell.style.backgroundColor = ticketFlujo.estado_color; // Usar 'estado_color'

                // Botón "Más" y "Guardar" en la misma celda
                const masCell = document.createElement("td");
                masCell.classList.add("px-4", "py-2", "text-center", "space-x-2");
                masCell.style.backgroundColor = ticketFlujo.estado_color; // Aplica el color del estado

                // Botón "Más" (⋮)
                const masBtn = document.createElement("button");
                masBtn.classList.add("toggle-comment", "px-3", "py-1", "rounded", "bg-gray-300");
                masBtn.textContent = "⋮";
                masBtn.dataset.flujoId = ticketFlujo.idTicketFlujo;

                // Botón "Guardar" como icono de check ✅ verde
                const saveIconBtn = document.createElement("button");
                saveIconBtn.classList.add("save-comment", "px-3", "py-1", "rounded", "bg-success",
                    "text-white");
                saveIconBtn.dataset.flujoId = ticketFlujo.idTicketFlujo;
                saveIconBtn.innerHTML = "✔"; // Ícono de check verde

                // Agregar botones a la celda
                masCell.appendChild(masBtn);
                masCell.appendChild(saveIconBtn);

                row.appendChild(estadoCell);
                row.appendChild(usuarioCell);
                row.appendChild(fechaCell);
                row.appendChild(masCell);
                estadosTableBody.appendChild(row);

                // Fila oculta para comentario
                const commentRow = document.createElement("tr");
                commentRow.classList.add("hidden");
                const commentCell = document.createElement("td");
                commentCell.setAttribute("colspan",
                    "4"); // Ajustado el colspan a la cantidad de columnas
                commentCell.classList.add("p-4");
                commentCell.style.backgroundColor = ticketFlujo
                    .estado_color; // Aplica el color del estado

                const textArea = document.createElement("textarea");
                textArea.classList.add("w-full", "p-2", "rounded", "border",
                    "border-black"); // 🔥 Borde negro
                textArea.textContent = ticketFlujo.comentarioflujo;
                textArea.placeholder = "Escribe un comentario...";
                textArea.style.backgroundColor = ticketFlujo
                    .estado_color; // 🔥 Color de fondo del estado

                commentCell.appendChild(textArea);
                commentRow.appendChild(commentCell);

                estadosTableBody.appendChild(commentRow);
            });

            agregarEventosComentarios();
        }

        function setupPagination(totalRows) {
            const paginationContainer = document.getElementById("paginationControls");
            paginationContainer.innerHTML = ""; // Limpiar paginación previa

            const totalPages = Math.ceil(totalRows / rowsPerPage);

            if (totalPages > 1) {
                const prevBtn = document.createElement("button");
                prevBtn.textContent = "Anterior";
                prevBtn.classList.add("px-4", "py-2", "bg-gray-300", "rounded", "mx-1");
                prevBtn.disabled = currentPage === 1;
                prevBtn.addEventListener("click", () => {
                    if (currentPage > 1) {
                        currentPage--;
                        cargarEstados();
                    }
                });

                const nextBtn = document.createElement("button");
                nextBtn.textContent = "Siguiente";
                nextBtn.classList.add("px-4", "py-2", "bg-gray-300", "rounded", "mx-1");
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.addEventListener("click", () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        cargarEstados();
                    }
                });

                paginationContainer.appendChild(prevBtn);
                paginationContainer.appendChild(nextBtn);
            }
        }

        function agregarEventosComentarios() {
            document.querySelectorAll('.toggle-comment').forEach(button => {
                button.addEventListener('click', function() {
                    let parentCell = this.closest('td'); // Celda donde están los elementos
                    let row = this.closest('tr').nextElementSibling;
                    row.classList.toggle('hidden'); // Mostrar/ocultar la fila de comentario
                });
            });

            document.querySelectorAll('.save-comment').forEach(button => {
                button.addEventListener('click', function() {
                    let flujoId = this.dataset.flujoId; // Obtener idTicketFlujo
                    let row = this.closest('tr').nextElementSibling;
                    let textArea = row.querySelector("textarea");
                    let comentario = textArea.value;

                    fetch(`/ticket/${ticketId}/ticketflujo/${flujoId}/update`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute("content")
                            },
                            body: JSON.stringify({
                                comentario
                            })
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                toastr.success("Estado actualizado correctamente.");
                            } else {
                                toastr.error("Error al actualizar el estado.");
                            }
                        })
                        .catch(error => console.error("Error al actualizar el estado:", error));
                });
            });
        }

        // Cargar estados al iniciar
        cargarEstados();
        setInterval(cargarEstados, 30000);
    });
</script>



<!-- Agregar Axios desde un CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("DOM completamente cargado y analizado");

        // Inicializar NiceSelect2
        document.querySelectorAll('.select2').forEach(function(select) {
            console.log("Inicializando NiceSelect para elemento:", select);
            NiceSelect.bind(select, {
                searchable: true
            });
        });

        // Inicializar Flatpickr en "Fecha de Compra"
        console.log("Inicializando Flatpickr para fechaCompra");
        flatpickr("#fechaCompra", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Función para formatear la fecha
        function formatDate(fecha) {
            console.log("Formateando fecha:", fecha);
            const año = fecha.getFullYear();
            const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
            const dia = fecha.getDate().toString().padStart(2, "0");
            let horas = fecha.getHours();
            const minutos = fecha.getMinutes().toString().padStart(2, "0");
            const ampm = horas >= 12 ? "PM" : "AM";
            horas = horas % 12 || 12;
            return `${año}-${mes}-${dia} ${horas}:${minutos} ${ampm}`;
        }

        $(document).ready(function() {
            console.log("jQuery document ready");
            // Obtener el idTickets de la variable de Blade
            const idTickets = "{{ $orden->idTickets }}";
            console.log("idTickets:", idTickets);

            // Llamar al backend para obtener la última modificación
            console.log("Iniciando AJAX para obtener última modificación");
            $.ajax({
                url: '/ultima-modificacion/' + idTickets,
                method: 'GET',
                success: function(response) {
                    console.log("Respuesta AJAX recibida:", response);
                    if (response.success) {
                        const ultimaModificacion = response.ultima_modificacion;
                        console.log("Última modificación encontrada:", ultimaModificacion);

                        const fechaUltimaModificacion = formatDate(new Date(ultimaModificacion.created_at));
                        console.log("Fecha formateada:", fechaUltimaModificacion);

                        const usuarioUltimaModificacion = ultimaModificacion.usuario;
                        const campoUltimaModificacion = ultimaModificacion.campo;
                        const oldValueUltimaModificacion = ultimaModificacion.valor_antiguo;
                        const newValueUltimaModificacion = ultimaModificacion.valor_nuevo;

                        console.log("Actualizando UI con última modificación");
                        document.getElementById('ultimaModificacion').textContent =
                            `${fechaUltimaModificacion} por ${usuarioUltimaModificacion}: Se modificó ${campoUltimaModificacion} de "${oldValueUltimaModificacion}" a "${newValueUltimaModificacion}"`;

                    } else {
                        console.log("No se encontraron modificaciones previas");
                        document.getElementById('ultimaModificacion').textContent =
                            "No hay modificaciones previas.";
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener la última modificación:', error, xhr);
                }
            });
        });

        // Función para actualizar el log de modificación cuando se haga un cambio
        function updateModificationLog(field, oldValue, newValue) {
            console.log("Actualizando log de modificación:", {
                field,
                oldValue,
                newValue
            });
            const usuario = "{{ auth()->user()->Nombre }}";
            console.log("Usuario:", usuario);

            const fecha = formatDate(new Date());
            const idTickets = "{{ $orden->idTickets }}";
            console.log("Fecha:", fecha, "idTickets:", idTickets);

            document.getElementById('ultimaModificacion').textContent =
                `${fecha} por ${usuario}: Se modificó ${field} de "${oldValue}" a "${newValue}"`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log("CSRF Token:", csrfToken);

            const data = {
                field: field,
                oldValue: oldValue,
                newValue: newValue,
                usuario: usuario,
                _token: csrfToken
            };
            console.log("Datos a enviar:", data);

            $.ajax({
                url: '/guardar-modificacion/' + idTickets,
                method: 'POST',
                data: data,
                success: function(response) {
                    console.log('Modificación guardada correctamente:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error al guardar la modificación:', error, xhr);
                }
            });
        }

        // Pasa los estados de flujo desde Blade a JavaScript
        const estadosFlujo = @json($estadosFlujo);
        console.log("Estados de flujo cargados:", estadosFlujo);

        // Función para obtener el ID del estado a partir de la descripción
        function getStateId(stateDescription) {
            console.log("Buscando ID para estado:", stateDescription);
            const estado = estadosFlujo.find(e => e.descripcion === stateDescription);
            return estado ? estado.idEstadflujo : 0;
        }

        // Selecciona todos los botones de estado
        const estadoElements = document.querySelectorAll(".estado-button");
        console.log("Botones de estado encontrados:", estadoElements.length);

        estadoElements.forEach(function(estadoElement) {
            estadoElement.addEventListener("click", function() {
                const stateDescription = estadoElement.dataset.stateDescription;
                console.log("Botón de estado clickeado:", stateDescription);

                const estadoId = getStateId(stateDescription);
                console.log("ID del estado encontrado:", estadoId);

                if (estadoId !== 0) {
                    const usuario = "{{ auth()->user()->id }}";
                    const fecha = formatDate(new Date());
                    const ticketId = "{{ $ticket->idTickets }}";
                    console.log("Datos para actualización:", {
                        usuario,
                        fecha,
                        ticketId
                    });

                    let rowClasses = "";
                    if (estadoId === 1) {
                        rowClasses = "bg-primary/20 border-primary/20";
                    } else if (estadoId === 2) {
                        rowClasses = "bg-secondary/20 border-secondary/20";
                    } else if (estadoId === 3) {
                        rowClasses = "bg-success/20 border-success/20";
                    }
                    console.log("Clases CSS para fila:", rowClasses);

                    const newRow = document.createElement("tr");
                    newRow.className = rowClasses;
                    newRow.innerHTML = `
                <td class="px-4 py-2 text-center">${stateDescription}</td>
                <td class="px-4 py-2 text-center">${usuario}</td>
                <td class="px-4 py-2 text-center">${fecha}</td>
            `;
                    console.log("Nueva fila creada:", newRow);
                    document.getElementById("estadosTableBody").appendChild(newRow);

                    console.log("Enviando solicitud para guardar estado");
                    axios.post("{{ route('guardarEstado') }}", {
                            idTicket: ticketId,
                            idEstadflujo: estadoId,
                            idUsuario: usuario,
                        })
                        .then(response => {
                            console.log("Respuesta del servidor:", response);
                            console.log("Estado guardado exitosamente");
                            location.reload();
                            document.getElementById('ultimaModificacion').textContent =
                                `${fecha} por ${usuario}: Se modificó Estado a "${stateDescription}"`;
                        })
                        .catch(error => {
                            console.error("Error al guardar el estado", error);
                        });
                } else {
                    console.error("Estado no encontrado");
                }
            });
        });

        function reinitializeDraggable(element) {
            console.log("Reinicializando elemento draggable:", element);
            element.setAttribute("draggable", "true");
            element.addEventListener("dragstart", function(e) {
                console.log("Dragstart en elemento:", this);
                e.dataTransfer.setData("text/plain", this.dataset.state);
            });
        }

        // Solución para el error dropZone is not defined
        const dropZone = document.getElementById('dropZone');
        if (dropZone) {
            dropZone.addEventListener("click", function(e) {
                console.log("Click en dropZone:", e.target);
                if (e.target.classList.contains("delete-state")) {
                    console.log("Eliminando estado");
                    const row = e.target.closest("tr");
                    const state = row.querySelector("td").textContent.trim();
                    console.log("Estado a eliminar:", state);
                    row.remove();
                    if (!document.querySelector("#draggableContainer .draggable-state[data-state='" + state + "']")) {
                        console.log("Recreando elemento draggable para:", state);
                        const container = document.getElementById("draggableContainer");
                        const newDraggable = document.createElement("div");
                        let colorClass = "";
                        if (state === "Recojo") {
                            colorClass = "bg-primary/20";
                        } else if (state === "Coordinado") {
                            colorClass = "bg-secondary/20";
                        } else if (state === "Operativo") {
                            colorClass = "bg-success/20";
                        }
                        newDraggable.className = `draggable-state ${colorClass} px-3 py-1 rounded cursor-move`;
                        newDraggable.dataset.state = state;
                        newDraggable.textContent = state;
                        reinitializeDraggable(newDraggable);
                        container.appendChild(newDraggable);
                    }
                }
            });
        } else {
            console.warn("Elemento dropZone no encontrado en el DOM");
        }

        // Por esta versión mejorada:
        function initializeFieldValues() {
            // Selecciona solo los campos dentro del formulario principal
            const form = document.getElementById('tuFormulario'); // Cambia 'tuFormulario' por el ID real de tu formulario
            if (!form) {
                console.error("Formulario no encontrado");
                return;
            }

            form.querySelectorAll("input:not([type='hidden']):not([type='checkbox']):not([type='radio']), select, textarea").forEach(function(field) {
                // Ignora campos sin nombre o ID (como los de Alpine.js)
                if (!field.name && !field.id) {
                    console.log("Ignorando campo sin nombre/ID:", field);
                    return;
                }

                console.log("Inicializando campo:", field.id || field.name);
                if (field.tagName.toLowerCase() === "select") {
                    field.dataset.oldValue = field.options[field.selectedIndex].text;
                } else {
                    field.dataset.oldValue = field.value;
                }
                console.log("Valor inicial guardado:", field.dataset.oldValue);
            });
        }

        // Y modifica el manejador de eventos así:
        function handleFieldChange(e) {
            const field = e.target;

            // Filtra solo los campos que nos interesan
            if (!field.matches('#tuFormulario input:not([type="hidden"]), #tuFormulario select, #tuFormulario textarea')) {
                return;
            }

            // Ignora campos sin nombre o ID
            if (!field.name && !field.id) {
                return;
            }

            console.log("Evento detectado en campo:", field.id || field.name, "Tipo:", e.type);

            let oldVal = field.dataset.oldValue || '';
            let newVal;

            if (field.tagName.toLowerCase() === "select") {
                newVal = field.options[field.selectedIndex].text;
            } else {
                newVal = field.value;
            }

            console.log("Valor anterior:", oldVal, "Nuevo valor:", newVal);

            if (oldVal !== newVal) {
                let fieldLabel = "";
                if (field.id) {
                    const label = document.querySelector('label[for="' + field.id + '"]');
                    if (label) {
                        fieldLabel = label.textContent.trim();
                    }
                }
                if (!fieldLabel) {
                    fieldLabel = field.getAttribute("name") || field.getAttribute("id") || "campo desconocido";
                }
                console.log("Etiqueta del campo:", fieldLabel);

                updateModificationLog(fieldLabel, oldVal, newVal);
                field.dataset.oldValue = newVal;
                console.log("Valor antiguo actualizado a:", newVal);
            }
        }

        // Inicialización
        initializeFieldValues();

        // Escuchar eventos de cambio (versión mejorada)
        document.addEventListener('input', handleFieldChange, true);
        document.addEventListener('change', handleFieldChange, true);
    });
</script>

<script>
    document.getElementById('idCliente').addEventListener('change', function() {
        var clienteId = this.value; // Obtén el ID del cliente seleccionado
        console.log('Cliente seleccionado:', clienteId); // Para depurar

        // Si se seleccionó un cliente
        if (clienteId) {
            console.log('Haciendo la petición para obtener los clientes generales...');

            // Realizamos la petición para obtener los clientes generales asociados a este cliente
            fetch(`/get-clientes-generales/${clienteId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Datos recibidos:', data); // Para depurar

                    // Obtener el select de "Cliente General"
                    var clienteGeneralSelect = document.getElementById('idClienteGeneral');

                    // Limpiar las opciones anteriores del select de Cliente General
                    clienteGeneralSelect.innerHTML =
                        '<option value="" selected>Seleccionar Cliente General</option>';

                    // Comprobar si hay datos
                    if (data.length > 0) {
                        console.log('Hay clientes generales asociados. Agregando opciones...');
                        // Si hay clientes generales, agregarlos al select
                        data.forEach(function(clienteGeneral) {
                            var option = document.createElement('option');
                            option.value = clienteGeneral.idClienteGeneral;
                            option.textContent = clienteGeneral.descripcion;
                            clienteGeneralSelect.appendChild(option);
                        });
                        // Mostrar el select de Cliente General
                        clienteGeneralSelect.style.display = 'block';
                    } else {
                        console.log('No hay clientes generales asociados.');
                        // Si no hay clientes generales, ocultar el select
                        clienteGeneralSelect.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error al obtener los clientes generales:', error);
                    alert('Hubo un error al cargar los clientes generales.');
                });
        } else {
            console.log('No se seleccionó ningún cliente. Ocultando el select de Cliente General...');
            // Si no hay cliente seleccionado, ocultar el select de Cliente General
            document.getElementById('idClienteGeneral').style.display = 'none';
        }
    });
</script>

<script>
    document.getElementById('idMarca').addEventListener('change', function() {
        var marcaId = this.value; // Obtén el ID de la marca seleccionada
        console.log('Marca seleccionada:', marcaId); // Para depurar

        // Si se seleccionó una marca
        if (marcaId) {
            console.log('Haciendo la petición para obtener los modelos asociados a esta marca...');

            // Realizamos la petición para obtener los modelos asociados a esta marca
            fetch(`/get-modelos/${marcaId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Datos de modelos recibidos:', data); // Para depurar

                    // Obtener el select de "Modelo"
                    var modeloSelect = document.getElementById('idModelo');

                    // Limpiar las opciones anteriores del select de Modelo
                    modeloSelect.innerHTML = '<option value="" disabled>Seleccionar Modelo</option>';

                    // Comprobar si hay datos
                    if (data.length > 0) {
                        console.log('Hay modelos asociados a esta marca. Agregando opciones...');
                        // Si hay modelos, agregarlos al select
                        data.forEach(function(modelo) {
                            var option = document.createElement('option');
                            option.value = modelo.idModelo;
                            option.textContent = modelo.nombre;
                            modeloSelect.appendChild(option);
                        });
                        // Mostrar el select de Modelo
                        modeloSelect.style.display = 'block';
                    } else {
                        console.log('No hay modelos asociados a esta marca.');
                        // Si no hay modelos, ocultar el select
                        modeloSelect.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error al obtener los modelos:', error);
                    alert('Hubo un error al cargar los modelos.');
                });
        } else {
            console.log('No se seleccionó ninguna marca. Ocultando el select de Modelo...');
            // Si no hay marca seleccionada, ocultar el select de Modelo
            document.getElementById('idModelo').style.display = 'none';
        }
    });
</script>
<script>
    $(document).ready(function() {
        var idOrden = @json($orden -> idTickets);

        $('#guardarFallaReportada').on('click', function(e) {
            e.preventDefault(); // Prevenir que se recargue la página

            // Recoger los datos del formulario (sin los campos eliminados)
            var formData = {
                idCliente: $('#idCliente').val(),
                idClienteGeneral: $('#idClienteGeneral').val(),
                idTienda: $('#idTienda').val(),
                numero_ticket: $('#numero_ticket').val(),
                fallaReportada: $('textarea[name="fallaReportada"]').val(),
                ejecutor: $('#ejecutor').val(), // Capturar el valor del ejecutor

            };

            // Mostrar los datos del formulario en la consola
            console.log("Datos del formulario:", formData);

            // Verificar si algún campo obligatorio está vacío
            for (var key in formData) {
                if (formData[key] === '' || formData[key] === null) {
                    toastr.error('El campo "' + key +
                        '" está vacío. Por favor, complete todos los campos.');
                    return; // Detener el envío si algún campo está vacío
                }
            }



            // Obtener el token CSRF desde la página
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log("Token CSRF obtenido:",
                csrfToken); // Asegúrate de que el token se obtiene correctamente

            // Verificar si el token CSRF es válido
            if (!csrfToken) {
                console.error("Token CSRF no encontrado.");
                toastr.error('Hubo un error con el CSRF token.');
                return; // Detener el envío si el CSRF token no es válido
            }

            // Enviar datos por AJAX
            $.ajax({
                url: '/actualizar-orden-soporte/' +
                    idOrden, // Pasar el id de la orden en la URL
                method: 'PUT', // Usar PUT para la actualización
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Agregar el token CSRF
                },
                success: function(response) {
                    console.log("Respuesta del servidor:", response);

                    // Mostrar un mensaje de éxito con Toastr
                    toastr.success('Orden actualizada con éxito');
                },
                error: function(xhr, status, error) {
                    console.log("Error al actualizar:", error);
                    console.log("Detalles de la respuesta del error:", xhr.responseText);

                    // Mostrar un mensaje de error con Toastr
                    toastr.error('Hubo un error al actualizar la orden');
                }
            });
        });
    });
</script>
