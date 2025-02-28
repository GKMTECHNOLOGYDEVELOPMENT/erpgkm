<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<span class="text-lg font-semibold mb-4 badge bg-success">Detalles de la Orden de Trabajo N°
    {{ $orden->idTickets }}</span>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

    <div>
        <form action="formActualizarOrden" enctype="multipart/form-data" method="POST">
            @CSRF
            <label class="block text-sm font-medium">Ticket</label>
            <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->numero_ticket }}" readonly>
    </div>



    <!-- Cliente -->
    <div>
        <label class="block text-sm font-medium">Cliente</label>
        <select id="idCliente" name="idCliente" class="select2 w-full bg-gray-100" style="display:none">
            <option value="" disabled>Seleccionar Cliente</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->idCliente }}"
                    {{ $cliente->idCliente == $orden->cliente->idCliente ? 'selected' : '' }}>
                    {{ $cliente->nombre }} - {{ $cliente->documento }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Cliente General -->
    <div>
        <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
        <select id="idClienteGeneral" name="idClienteGeneral" class="form-input w-full">
            <option value="" selected>Seleccionar Cliente General</option>
            <!-- Aquí cargaremos el cliente general por defecto usando Blade -->
            <option value="{{ $orden->clienteGeneral->idClienteGeneral }}" selected>
                {{ $orden->clienteGeneral->descripcion }}
            </option>
        </select>
    </div>




    <!-- Tienda -->
    <div>
        <label class="block text-sm font-medium">Tienda</label>
        <select id="idTienda" name="idTienda" class="select2 w-full bg-gray-100" style="display: none;">
            <option value="" disabled>Seleccionar Tienda</option>
            @foreach ($tiendas as $tienda)
                <option value="{{ $tienda->idTienda }}" {{ $tienda->idTienda == $orden->idTienda ? 'selected' : '' }}>
                    {{ $tienda->nombre }}
                </option>
            @endforeach
        </select>
    </div>


    <!-- Dirección -->
    <div>
        <label class="block text-sm font-medium">Dirección</label>
        <input id="direccion" name="direccion" type="text" class="form-input w-full "
            value="{{ $orden->direccion }}">
    </div>
    <!-- Marca -->
    <div>
        <label class="block text-sm font-medium">Marca</label>
        <select name="idMarca" id="idMarca" class="select2 w-full bg-gray-100" style="display: none;">
            <option value="" disabled>Seleccionar Marca</option>
            @foreach ($marcas as $marca)
                <option value="{{ $marca->idMarca }}" {{ $marca->idMarca == $orden->idMarca ? 'selected' : '' }}>
                    {{ $marca->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Modelo -->
    <div>
        <label for="idModelo" class="block text-sm font-medium">Modelo</label>
        <select id="idModelo" name="idModelo" class="form-input w-full">
            <option value="" selected>Seleccionar Modelo</option>
            <!-- Aquí cargaremos el modelo por defecto usando Blade -->
            <option value="{{ $orden->idModelo ?? '' }}" selected>
                {{ $orden->modelo->nombre ?? 'Sin Modelo' }}
            </option>
        </select>
    </div>


    <!-- Serie (Editable) -->
    <div>
        <label for="serie" class="block text-sm font-medium">N. Serie</label>
        <input id="serie" name="serie" type="text" class="form-input w-full" value="{{ $orden->serie }}">
    </div>

    <!-- Fecha de Compra (Editable) -->
    <div>
        <label for="fechaCompra" class="block text-sm font-medium">Fecha de Compra</label>
        <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full"
            value="{{ \Carbon\Carbon::parse($orden->fechaCompra)->format('Y-m-d') }}">
    </div>

    <!-- Falla Reportada -->
    <div>
        <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
        <textarea id="fallaReportada" name="fallaReportada" rows="1" class="form-input w-full">{{ $orden->fallaReportada }}</textarea>
    </div>

    <!-- Botón de GUARDAR -->
    <div class="md:col-span-2 flex justify-end">
        <button id="guardarFallaReportada" class="btn btn-primary w-full md:w-auto">Modificar</button>
    </div>

    </form>
</div>






<!-- Nueva Card: Historial de Estados -->
<div id="estadosCard" class="mt-4 p-4 shadow-lg rounded-lg">
    <span class="text-lg font-semibold mb-4 badge bg-success">Historial de Estados</span>
    <!-- Tabla con scroll horizontal -->
    <div class="overflow-x-auto mt-4">
        <table class="min-w-[600px] border-collapse">
            <thead>
                <tr class="bg-gray-200">
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
    <!-- Div para mostrar la última modificación -->
    <div class="mt-4">
        Última modificación: <span class="bg-gray-100 dark:bg-gray-700 p-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-white text-sm inline-block mt-2" id="ultimaModificacion"></span>
    </div>
    <!-- Estados disponibles (draggables) -->
    <div class="mt-3 overflow-x-auto">
        <div id="draggableContainer" class="flex space-x-2">
            <div class="draggable-state bg-primary/20 px-3 py-1 rounded cursor-move" draggable="true" data-state="Recojo">
                Recojo
            </div>
            <div class="draggable-state bg-secondary/20 px-3 py-1 rounded cursor-move" draggable="true" data-state="Coordinado">
                Coordinado
            </div>
            <div class="draggable-state bg-success/20 px-3 py-1 rounded cursor-move" draggable="true" data-state="Operativo">
                Operativo
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ticketId = "{{ $ticket->idTickets }}"; // ID del ticket
    const rowsPerPage = 10; // Número de filas por página
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
                    console.error('La respuesta no contiene un array de estados de flujo:', data.estadosFlujo);
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
            const estado = ticketFlujo.estado_flujo;
            const usuario = ticketFlujo.usuario;

            // Fila principal
            const row = document.createElement("tr");

            const estadoCell = document.createElement("td");
            estadoCell.classList.add("px-4", "py-2", "text-center", "text-black");
            estadoCell.style.backgroundColor = estado.color;
            estadoCell.textContent = estado.descripcion;

            const usuarioCell = document.createElement("td");
            usuarioCell.classList.add("px-4", "py-2", "text-center", "text-black");
            usuarioCell.textContent = usuario ? usuario.Nombre : 'Sin Nombre';
            usuarioCell.style.backgroundColor = estado.color;

            const fechaCell = document.createElement("td");
            fechaCell.classList.add("px-4", "py-2", "text-center", "text-black");
            fechaCell.textContent = ticketFlujo.fecha_creacion;
            fechaCell.style.backgroundColor = estado.color;

            // Botón "Más" y "Guardar" en la misma celda
            const masCell = document.createElement("td");
            masCell.classList.add("px-4", "py-2", "text-center", "flex", "items-center", "justify-center", "space-x-2");
            masCell.style.backgroundColor = estado.color; // Aplica el color del estado

            // Botón "Más" (⋮)
            const masBtn = document.createElement("button");
            masBtn.classList.add("toggle-comment", "px-3", "py-1", "rounded", "bg-gray-300");
            masBtn.textContent = "⋮";
            masBtn.dataset.flujoId = ticketFlujo.id;

            // Botón "Guardar" como icono de check ✅ verde
            const saveIconBtn = document.createElement("button");
            saveIconBtn.classList.add("save-comment", "px-3", "py-1", "rounded", "bg-success", "text-white");
            saveIconBtn.dataset.flujoId = ticketFlujo.id;
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
            commentCell.setAttribute("colspan", "4"); // Ajustado el colspan a la cantidad de columnas
            commentCell.classList.add("p-4");
            commentCell.style.backgroundColor = estado.color; // Aplica el color del estado

            const textArea = document.createElement("textarea");
            textArea.classList.add("w-full", "p-2", "rounded");
            textArea.placeholder = "Escribe un comentario...";
            textArea.style.backgroundColor = estado.color; // 🔥 Color de fondo del estado

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
            button.addEventListener('click', function () {
                let parentCell = this.closest('td'); // Celda donde están los elementos
                let row = this.closest('tr').nextElementSibling;
                row.classList.toggle('hidden'); // Mostrar/ocultar la fila de comentario
            });
        });

        document.querySelectorAll('.save-comment').forEach(button => {
            button.addEventListener('click', function () {
                let flujoId = this.dataset.flujoId;
                let row = this.closest('tr').nextElementSibling;
                let textArea = row.querySelector("textarea");
                let comentario = textArea.value;

                fetch(`/ticket/${ticketId}/estados/${flujoId}/comentario`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") // Si usas Laravel
                    },
                    body: JSON.stringify({ comentario })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert("Comentario guardado correctamente.");
                    } else {
                        alert("Error al guardar el comentario.");
                    }
                })
                .catch(error => console.error("Error al guardar el comentario:", error));
            });
        });
    }

    // Cargar estados al iniciar
    cargarEstados();

    // Actualizar cada 30 segundos
    setInterval(cargarEstados, 30000);
});
</script>






<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar NiceSelect2
        document.querySelectorAll('.select2').forEach(function(select) {
            NiceSelect.bind(select, {
                searchable: true
            });
        });

        // Inicializar Flatpickr en "Fecha de Compra"
        flatpickr("#fechaCompra", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Función para formatear la fecha
        function formatDate(fecha) {
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
            // Obtener el idTickets de la variable de Blade
            const idTickets = "{{ $orden->idTickets }}";

            // Llamar al backend para obtener la última modificación
            $.ajax({
                url: '/ultima-modificacion/' +
                idTickets, // Obtener la última modificación del ticket
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const ultimaModificacion = response.ultima_modificacion;
                        const fechaUltimaModificacion = formatDate(new Date(
                            ultimaModificacion.created_at)); // Formatear la fecha
                        const usuarioUltimaModificacion = ultimaModificacion.usuario;
                        const campoUltimaModificacion = ultimaModificacion.campo;
                        const oldValueUltimaModificacion = ultimaModificacion.valor_antiguo;
                        const newValueUltimaModificacion = ultimaModificacion.valor_nuevo;

                        // Actualizar el log de modificación con la última modificación
                        document.getElementById('ultimaModificacion').textContent =
                            `${fechaUltimaModificacion} por ${usuarioUltimaModificacion}: Se modificó ${campoUltimaModificacion} de "${oldValueUltimaModificacion}" a "${newValueUltimaModificacion}"`;

                    } else {
                        // Si no hay modificaciones previas, mostrar mensaje de no hay cambios
                        document.getElementById('ultimaModificacion').textContent =
                            "No hay modificaciones previas.";
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener la última modificación:', error);
                }
            });
        });

        // Función para actualizar el log de modificación cuando se haga un cambio
        function updateModificationLog(field, oldValue, newValue) {
            const usuario = "{{ auth()->user()->Nombre }}"; // Usuario logueado
            const fecha = formatDate(new Date());
            const idTickets =
            "{{ $orden->idTickets }}"; // Aquí asumo que el id de la orden está disponible en el Blade

            // Actualizar el log de modificación con la nueva modificación
            document.getElementById('ultimaModificacion').textContent =
                `${fecha} por ${usuario}: Se modificó ${field} de "${oldValue}" a "${newValue}"`;

            // Enviar la nueva modificación al servidor para guardarla en la base de datos
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const data = {
                field: field,
                oldValue: oldValue,
                newValue: newValue,
                usuario: usuario,
                _token: csrfToken
            };

            $.ajax({
                url: '/guardar-modificacion/' + idTickets, // Ruta para guardar la modificación
                method: 'POST',
                data: data,
                success: function(response) {
                    console.log('Modificación guardada correctamente:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error al guardar la modificación:', error);
                }
            });
        }





        /* ================================
           Registro de cambios en drag & drop
        ================================ */
        const draggables = document.querySelectorAll(".draggable-state");
        draggables.forEach(function(draggable) {
            draggable.addEventListener("dragstart", function(e) {
                e.dataTransfer.setData("text/plain", this.dataset.state);
            });
        });

        const dropZone = document.getElementById("estadosTableBody");
        dropZone.addEventListener("dragover", function(e) {
            e.preventDefault();
        });
        dropZone.addEventListener("drop", function(e) {
            e.preventDefault();
            const state = e.dataTransfer.getData("text/plain");
            if (state) {
                const draggableEl = document.querySelector(
                    "#draggableContainer .draggable-state[data-state='" + state + "']");
                if (draggableEl) {
                    draggableEl.remove();
                }
                const usuario = "{{ auth()->user()->name }}";
                const fecha = formatDate(new Date());
                const newRow = document.createElement("tr");
                let rowClasses = "";
                if (state === "Recojo") {
                    rowClasses = "bg-primary/20 border-primary/20";
                } else if (state === "Coordinado") {
                    rowClasses = "bg-secondary/20 border-secondary/20";
                } else if (state === "Operativo") {
                    rowClasses = "bg-success/20 border-success/20";
                }
                newRow.className = rowClasses;
                newRow.innerHTML = `
        <td class="px-4 py-2 text-center">${state}</td>
        <td class="px-4 py-2 text-center">${usuario}</td>
        <td class="px-4 py-2 text-center">${fecha}</td>
      `;
                dropZone.appendChild(newRow);
                // Actualizar log de modificación por cambio de estado
                document.getElementById('ultimaModificacion').textContent =
                    `${fecha} por ${usuario}: Se modificó Estado a "${state}"`;
            }
        });

        function reinitializeDraggable(element) {
            element.setAttribute("draggable", "true");
            element.addEventListener("dragstart", function(e) {
                e.dataTransfer.setData("text/plain", this.dataset.state);
            });
        }

        dropZone.addEventListener("click", function(e) {
            if (e.target.classList.contains("delete-state")) {
                const row = e.target.closest("tr");
                const state = row.querySelector("td").textContent.trim();
                row.remove();
                if (!document.querySelector("#draggableContainer .draggable-state[data-state='" +
                        state + "']")) {
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
                    newDraggable.className =
                        `draggable-state ${colorClass} px-3 py-1 rounded cursor-move`;
                    newDraggable.dataset.state = state;
                    newDraggable.textContent = state;
                    reinitializeDraggable(newDraggable);
                    container.appendChild(newDraggable);
                }
            }
        });

        /* ======================================================
           Registro global de cambios en todos los campos
           (input, select, textarea), incluso si están bloqueados
        ====================================================== */
        const allFields = document.querySelectorAll("input, select, textarea");
        allFields.forEach(function(field) {
            // Si es un select, almacena el texto de la opción seleccionada
            if (field.tagName.toLowerCase() === "select") {
                field.dataset.oldValue = field.options[field.selectedIndex].text;
            } else {
                field.dataset.oldValue = field.value;
            }
            field.addEventListener("change", function() {
                let oldVal = field.dataset.oldValue;
                let newVal;
                if (field.tagName.toLowerCase() === "select") {
                    newVal = field.options[field.selectedIndex].text;
                } else {
                    newVal = field.value;
                }
                if (oldVal !== newVal) {
                    // Se obtiene el label asociado mediante el atributo "for"
                    let fieldLabel = "";
                    if (field.id) {
                        const label = document.querySelector('label[for="' + field.id + '"]');
                        if (label) {
                            fieldLabel = label.textContent.trim();
                        }
                    }
                    // Si no se encuentra un label, se usa como fallback el id o name
                    if (!fieldLabel) {
                        fieldLabel = field.getAttribute("name") || field.getAttribute("id") ||
                            "campo desconocido";
                    }
                    updateModificationLog(fieldLabel, oldVal, newVal);
                    field.dataset.oldValue = newVal;
                }
            });
        });
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
                    // console.log('Datos recibidos:', data); // Para depurar

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
        var idOrden = @json($orden->idTickets);

        $('#guardarFallaReportada').on('click', function(e) {
            e.preventDefault(); // Prevenir que se recargue la página

            // Recoger los datos del formulario
            var formData = {
                idCliente: $('#idCliente').val(),
                idClienteGeneral: $('#idClienteGeneral').val(),
                idTienda: $('#idTienda').val(),
                direccion: $('input[name="direccion"]').val(),
                idMarca: $('#idMarca').val(),
                idModelo: $('#idModelo').val(),
                serie: $('input[name="serie"]').val(),
                fechaCompra: $('input[name="fechaCompra"]').val(),
                fallaReportada: $('textarea[name="fallaReportada"]').val(),
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

            // Validar que la fecha de compra no sea en el futuro
            var fechaCompra = new Date(formData.fechaCompra);
            var fechaActual = new Date();

            // Eliminar la hora de las fechas para compararlas correctamente
            fechaActual.setHours(0, 0, 0, 0);
            fechaCompra.setHours(0, 0, 0, 0);

            if (fechaCompra > fechaActual) {
                toastr.error('La fecha de compra no puede ser una fecha futura.');
                return; // Detener el envío si la fecha de compra es en el futuro
            }

            // Validar el campo "serie" (permitir letras y números, pero no el signo -)
            var serie = formData.serie;
            var serieRegex =
            /^[a-zA-Z0-9]+$/; // Expresión regular que permite solo letras y números, pero no el signo -

            if (!serie || !serieRegex.test(serie)) {
                toastr.error(
                    'El número de serie no puede contener caracteres especiales o un signo "-".');
                return; // Detener el envío si el número de serie no es válido
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
                url: '/actualizar-orden/' + idOrden, // Pasar el id de la orden en la URL
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
