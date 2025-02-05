<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<span class="text-lg font-semibold mb-4 badge bg-success">Detalles de la Orden de Trabajo</span>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div>
        <label class="block text-sm font-medium">Cliente General</label>
        <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->clienteGeneral->descripcion }}"
            readonly>
    </div>

    <!-- Cliente -->
    <div>
        <label class="block text-sm font-medium">Cliente</label>
        <input type="text" class="form-input w-full bg-gray-100"
            value="{{ $orden->cliente->nombre }} - {{ $orden->cliente->documento }}" readonly>
    </div>

    <!-- Tienda -->
    <div>
        <label class="block text-sm font-medium">Tienda</label>
        <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->tienda->nombre }}" readonly>
    </div>

    <!-- Dirección -->
    <div>
        <label class="block text-sm font-medium">Dirección</label>
        <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->direccion }}" readonly>
    </div>

    <!-- Marca -->
    <div>
        <label class="block text-sm font-medium">Marca</label>
        <input type="text" class="form-input w-full bg-gray-100"
            value="{{ $orden->marca?->nombre ?? 'No asignado' }}" readonly>

    </div>

    <!-- Modelo (Editable) -->
    <div>
        <label for="idModelo" class="block text-sm font-medium">Modelos</label>
        <select id="idModelo" name="idModelo" class="select2 w-full" style="display:none">
            <option value="" disabled>Seleccionar Modelo</option>
            @foreach ($modelos as $modelo)
                <option value="{{ $modelo->idModelo }}" {{ $orden->idModelo == $modelo->idModelo ? 'selected' : '' }}>
                    {{ $modelo->nombre }}
                </option>
            @endforeach
        </select>
    </div>



    <!-- Serie (Editable) -->
    <div>
        <label class="block text-sm font-medium">N. Serie</label>
        <input id="serie" name="serie" type="text" class="form-input w-full" value="{{ $orden->serie }}">
    </div>


    <!-- Fecha de Compra -->
    <div>
        <label class="block text-sm font-medium">Fecha de Compra</label>
        <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full"
            value="{{ \Carbon\Carbon::parse($orden->fechaCompra)->format('Y-m-d') }}">
    </div>
    <!-- Falla Reportada -->
    <div class="">
        <label class="block text-sm font-medium">Falla Reportada</label>
        <textarea id="fallaReportada" name="fallaReportada" rows="1" class="form-input w-full bg-gray-100" readonly>{{ $orden->fallaReportada }}</textarea>
    </div>
    <!-- Botón de GUARDAR -->
    <div class="mt-5 w-full md:w-auto">
        <button id="guardarFallaReportada" class="btn btn-primary w-full md:w-auto">
            GUARDAR
        </button>
    </div>

</div>

<!-- Nueva Card: Historial de Estados -->
<div id="estadosCard" class="mt-4 p-4 shadow-lg rounded-lg">
    <span class="text-lg font-semibold mb-4 badge bg-success">Historial de Estados</span>
    <!-- Drop zone: tabla con scroll horizontal -->
    <div class="overflow-x-auto mt-4">
        <table class="min-w-[600px] border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-center">Estado</th>
                    <th class="px-4 py-2 text-center">Usuario</th>
                    <th class="px-4 py-2 text-center">Fecha</th>
                    <th class="px-4 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="estadosTableBody">
                <!-- Fila inicial (no se podrá eliminar) -->
                <tr class="bg-dark-dark-light border-dark-dark-light">
                    <td class="px-4 py-2 text-center">Pendiente por Coordinar</td>
                    <td class="px-4 py-2 text-center">Usuario Actual</td>
                    <td class="px-4 py-2 text-center min-w-[200px]" id="estadoInicialFecha"></td>
                    <td class="px-4 py-2 text-center">
                        <!-- Sin botón de eliminar -->
                        <span class="text-gray-500">-</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Estados disponibles (draggables) -->
    <div class="mt-3 overflow-x-auto">
        <div id="draggableContainer" class="flex space-x-2">
            <div class="draggable-state bg-primary/20 px-3 py-1 rounded cursor-move" draggable="true"
                data-state="Recojo">
                Recojo
            </div>
            <div class="draggable-state bg-secondary/20 px-3 py-1 rounded cursor-move" draggable="true"
                data-state="Coordinado">
                Coordinado
            </div>
            <div class="draggable-state bg-success/20 px-3 py-1 rounded cursor-move" draggable="true"
                data-state="Operativo">
                Operativo
            </div>
        </div>
    </div>
</div>

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
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar Flatpickr en el campo de fecha de compra
        flatpickr("#fechaCompra", {
            dateFormat: "Y-m-d", // Formato de fecha
            allowInput: true, // Permitir edición manual
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Función de formateo de fecha (si no la tienes definida)
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

        // Asigna la fecha actual al estado inicial de la tabla
        document.getElementById("estadoInicialFecha").textContent = formatDate(new Date());

        // Configurar draggables: para cada elemento con clase "draggable-state"
        const draggables = document.querySelectorAll(".draggable-state");
        draggables.forEach(draggable => {
            draggable.addEventListener("dragstart", function(e) {
                e.dataTransfer.setData("text/plain", this.dataset.state);
            });
        });

        // Drop zone: el cuerpo de la tabla
        const dropZone = document.getElementById("estadosTableBody");
        dropZone.addEventListener("dragover", function(e) {
            e.preventDefault();
        });
        dropZone.addEventListener("drop", function(e) {
            e.preventDefault();
            const state = e.dataTransfer.getData("text/plain");
            if (state) {
                // Elimina el estado de los draggables (si existe)
                const draggableEl = document.querySelector(
                    "#draggableContainer .draggable-state[data-state='" + state + "']");
                if (draggableEl) {
                    draggableEl.remove();
                }
                const usuario = "Usuario Actual"; // Ajusta según tu lógica real
                const fecha = formatDate(new Date());
                // Crea una nueva fila en la tabla con estilos contextuales
                const newRow = document.createElement("tr");
                // Determinar las clases de la fila según el estado (usa tu lógica de colores)
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
        <td class="px-4 py-2 text-center flex justify-center items-center">
          <button class="delete-state btn btn-danger btn-sm">X</button>
        </td>
      `;
                dropZone.appendChild(newRow);
            }
        });

        // Función para reconfigurar un elemento draggable (para reinserción)
        function reinitializeDraggable(element) {
            element.setAttribute("draggable", "true");
            element.addEventListener("dragstart", function(e) {
                e.dataTransfer.setData("text/plain", this.dataset.state);
            });
        }

        // Permitir eliminar una fila de estado y reinsertar el estado en el contenedor de draggables
        dropZone.addEventListener("click", function(e) {
            if (e.target.classList.contains("delete-state")) {
                const row = e.target.closest("tr");
                const state = row.querySelector("td").textContent.trim();
                row.remove();
                // Verificar si el estado ya existe en el contenedor draggables; si no, reinsertarlo.
                if (!document.querySelector("#draggableContainer .draggable-state[data-state='" +
                        state + "']")) {
                    const container = document.getElementById("draggableContainer");
                    const newDraggable = document.createElement("div");
                    // Asigna las clases de color que correspondan al estado; aquí usamos las mismas clases que en el HTML inicial
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
    });
</script>
