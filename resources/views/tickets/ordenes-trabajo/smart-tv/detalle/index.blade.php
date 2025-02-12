<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Estilos adicionales para el log -->
<style>
  #ultimaModificacion {
    background: #f7f7f7;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #333;
    font-size: 0.9em;
    margin-top: 8px;
    display: inline-block;
  }
</style>

<span class="text-lg font-semibold mb-4 badge bg-success">Detalles de la Orden de Trabajo N° {{ $orden->idTickets}}</span>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">




<div>
  <form action="">
    <label class="block text-sm font-medium">TICKET</label>
    <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->numero_ticket }}" readonly>
  </div>

  <!-- Cliente -->
  <!-- <div>
    <label class="block text-sm font-medium">Cliente</label>
    <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->cliente->nombre }} - {{ $orden->cliente->documento }}" >
  </div> -->

  <!-- Cliente -->
<div>
  <label class="block text-sm font-medium">Cliente</label>
  <select name="idCliente" class="select2 w-full bg-gray-100" style="display:none">
    <option value="" disabled >Seleccionar Cliente</option>
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
  <label class="block text-sm font-medium">Cliente General</label>
  <select name="idClienteGeneral" class="select2 w-full bg-gray-100" style="display: none;">
    <option value="" disabled>Seleccionar Cliente General</option>
    @foreach ($clientesGenerales as $clienteGeneral)
      <option value="{{ $clienteGeneral->idClienteGeneral }}" 
        {{ $clienteGeneral->idClienteGeneral == $orden->clienteGeneral->idClienteGeneral ? 'selected' : '' }}>
        {{ $clienteGeneral->descripcion }}
      </option>
    @endforeach
  </select>
</div>

  

<!-- Tienda -->
<div>
  <label class="block text-sm font-medium">Tienda</label>
  <select name="idTienda" class="select2 w-full bg-gray-100" style="display: none;">
    <option value="" disabled>Seleccionar Tienda</option>
    @foreach ($tiendas as $tienda)
      <option value="{{ $tienda->idTienda }}" 
        {{ $tienda->idTienda == $orden->idTienda ? 'selected' : '' }}>
        {{ $tienda->nombre }}
      </option>
    @endforeach
  </select>
</div>


  <!-- Dirección -->
  <div>
    <label class="block text-sm font-medium">Dirección</label>
    <input type="text" class="form-input w-full " value="{{ $orden->direccion }}" >
  </div>

 <!-- Marca -->
<div>
  <label class="block text-sm font-medium">Marca</label>
  <select name="idMarca" class="select2 w-full bg-gray-100" style="display: none;">
    <option value="" disabled>Seleccionar Marca</option>
    @foreach ($marcas as $marca)
      <option value="{{ $marca->idMarca }}" 
        {{ $marca->idMarca == $orden->idMarca ? 'selected' : '' }}>
        {{ $marca->nombre }}
      </option>
    @endforeach
  </select>
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
    <label for="serie" class="block text-sm font-medium">N. Serie</label>
    <input id="serie" name="serie" type="text" class="form-input w-full" value="{{ $orden->serie }}">
  </div>

  <!-- Fecha de Compra (Editable) -->
  <div>
    <label for="fechaCompra" class="block text-sm font-medium">Fecha de Compra</label>
    <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full" value="{{ \Carbon\Carbon::parse($orden->fechaCompra)->format('Y-m-d') }}">
  </div>
  
  <!-- Falla Reportada -->
  <div>
    <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
    <textarea id="fallaReportada" name="fallaReportada" rows="1" class="form-input w-full bg-gray-100" >{{ $orden->fallaReportada }}</textarea>
  </div>
  
  <!-- Botón de GUARDAR -->
  <div class="mt-5 w-full md:w-auto">
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
          <th class="px-4 py-2 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody id="estadosTableBody">
        <!-- Fila inicial (no eliminable) -->
        <tr class="bg-dark-dark-light border-dark-dark-light">
          <td class="px-4 py-2 text-center">{{ $orden->estadoflujo->descripcion ?? 'Sin estado' }}</td>
          <td class="px-4 py-2 text-center">{{ $orden->usuario->Nombre ?? 'Sin Nombre' }}</td>
          <td class="px-4 py-2 text-center min-w-[200px]">{{ $orden->fecha_creacion ?? 'sin fecha' }}</td>
          <td class="px-4 py-2 text-center">
            <span class="text-gray-500">-</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- Div para mostrar la última modificación -->
  <div class="mt-4">
    Última modificación: <span id="ultimaModificacion"></span>
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

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  // Inicializar NiceSelect2
  document.querySelectorAll('.select2').forEach(function(select) {
    NiceSelect.bind(select, { searchable: true });
  });

  // Inicializar Flatpickr en "Fecha de Compra"
  flatpickr("#fechaCompra", { dateFormat: "Y-m-d", allowInput: true });

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

  // Función para actualizar el log de modificación
  function updateModificationLog(field, oldValue, newValue) {
    const usuario = "{{ auth()->user()->name }}";
    const fecha = formatDate(new Date());
    document.getElementById('ultimaModificacion').textContent =
      `${fecha} por ${usuario}: Se modificó ${field} de "${oldValue}" a "${newValue}"`;
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
  dropZone.addEventListener("dragover", function(e) { e.preventDefault(); });
  dropZone.addEventListener("drop", function(e) {
    e.preventDefault();
    const state = e.dataTransfer.getData("text/plain");
    if (state) {
      const draggableEl = document.querySelector("#draggableContainer .draggable-state[data-state='" + state + "']");
      if (draggableEl) { draggableEl.remove(); }
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
        <td class="px-4 py-2 text-center flex justify-center items-center">
          <button class="delete-state btn btn-danger btn-sm">X</button>
        </td>
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
      if (!document.querySelector("#draggableContainer .draggable-state[data-state='" + state + "']")) {
        const container = document.getElementById("draggableContainer");
        const newDraggable = document.createElement("div");
        let colorClass = "";
        if (state === "Recojo") { colorClass = "bg-primary/20"; }
        else if (state === "Coordinado") { colorClass = "bg-secondary/20"; }
        else if (state === "Operativo") { colorClass = "bg-success/20"; }
        newDraggable.className = `draggable-state ${colorClass} px-3 py-1 rounded cursor-move`;
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
          fieldLabel = field.getAttribute("name") || field.getAttribute("id") || "campo desconocido";
        }
        updateModificationLog(fieldLabel, oldVal, newVal);
        field.dataset.oldValue = newVal;
      }
    });
  });
});
</script>
