<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
<style>
    /* ======== Modo Claro (Default) ======== */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border-color: #e8e8e8 !important;
        height: 36px;
        /* Tamaño más pequeño */
        line-height: 34px;
        /* Alineación */
        font-size: 14px;
        /* Texto más pequeño */
        padding: 0 10px;
        /* Espaciado más compacto */
    }

    /* Ajustar el texto dentro del select */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--tw-text-opacity) !important;
        line-height: 34px;
        /* Alineación */
        font-size: 14px;
        /* Tamaño de texto más pequeño */
        padding-left: 6px;
        /* Espacio interno */
    }

    /* Flecha de selección */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px;
        /* Alineación */
    }

    /* Opciones seleccionadas */
    .select2-container--default .select2-results__option--selected {
        font-weight: 700;
    }

    /* Dropdown */
    .select2-container--default .select2-dropdown {
        border-radius: 5px;
        box-shadow: 0 0 0 1px #4444441c;
        font-size: 14px;
        /* Ajuste del texto */
    }

    /* Input de búsqueda */
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e8e8e8;
        font-size: 13px;
        /* Texto más pequeño */
        padding: 6px;
        /* Más compacto */
    }

    /* ======== Modo Oscuro ======== */
    .dark .select2-container--default .select2-selection--single {
        background-color: #1b2e4b !important;
        border-color: #253b5c !important;
        color: #888ea8 !important;
        height: 36px;
        /* Tamaño más pequeño */
        line-height: 34px;
        /* Alineación */
        font-size: 14px;
        /* Texto más pequeño */
        padding: 0 10px;
    }

    .dark .select2-container--default .select2-dropdown {
        background-color: #1b2e4b !important;
    }

    .dark .select2-container--default .select2-results__option--highlighted,
    .dark .select2-container--default .select2-results__option--selected {
        background-color: #132136 !important;
        border-color: #253b5c !important;
    }

    /* Input de búsqueda en modo oscuro */
    .dark .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: #132136 !important;
        border-color: #253b5c !important;
        color: #fff !important;
        font-size: 13px;
        padding: 6px;
    }

    .panel {
        overflow: visible !important;
        /* Asegura que el modal no restrinja contenido */
    }
</style>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
    <!-- Sección de Detalles de los Estados -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Detalles de los Estados</span>

        <div class="grid grid-cols-1 gap-4 mt-4">
            <!-- Select de Estado con Nice Select -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <select id="estado" name="estado" class="nice-select w-full mt-2">
                    <option value="Diagnostico" selected>Diagnostico</option>
                    <option value="Observaciones">Observaciones</option>
                    <option value="Trabajo por realizar">Solución</option>
                </select>
            </div>

            <!-- Textarea de Justificación -->
            <div>
                <label for="justificacion" class="block text-sm font-medium">Justificación</label>
                <textarea id="justificacion" name="justificacion" rows="3" class="form-input w-full mt-2"></textarea>
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end">
                <button id="guardarEstado" class="btn btn-primary px-6 py-2">Guardar</button>
            </div>
        </div>

    </div>

    <!-- Sección de Herramientas -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Inventario</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addHerramienta" class="btn btn-primary">+</button>
        </div>
        <!-- Contenedor con altura definida y scroll -->
        <div id="herramientasContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
            <div class="flex items-center gap-2 mt-2 herramienta-row">
                <select class="form-input w-full herramienta-select">
                    <option value="DISCO DURO DE 2 TB">DISCO DURO DE 2 TB</option>
                    <option value="MEMORIA RAM 16GB">MEMORIA RAM 16GB</option>
                    <option value="PROCESADOR INTEL I7">PROCESADOR INTEL I7</option>
                </select>
                <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
                <button type="button" class="btn btn-danger removeHerramienta hidden">-</button>
            </div>
        </div>



        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary px-6 py-2">Guardar</button>
        </div>
    </div>
</div>

<!-- Contenedor Principal -->
<div id="cardInstalarRetirar" class="mt-4">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sección Instalar (estrecha en grandes, ocupa todo en medianas y chicas) -->
        <div class="lg:col-span-1 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4 badge bg-success mb-4">Instalar</span>
            <div class="grid grid-cols-1 gap-4">
                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-medium mt-2">Tipo</label>
                    <select id="tipoInstalar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Tipo</option>
                        <option value="Instalación">Instalación</option>
                    </select>
                </div>

                <!-- Tipo Producto -->
                <div>
                    <label class="block text-sm font-medium">Tipo Producto</label>
                    <select id="tipoProductoInstalar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Tipo de Producto</option>
                        <option value="ORDENAMIENTO DE CABLEADO">ORDENAMIENTO DE CABLEADO</option>
                    </select>
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-sm font-medium">Marca</label>
                    <select id="marcaInstalar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Marca</option>
                        <option value="ORDENAMIENTO DE CABLEADO">ORDENAMIENTO DE CABLEADO</option>
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label class="block text-sm font-medium">Modelo</label>
                    <select id="modeloInstalar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Modelo</option>
                        <option value="CABLEADO DE RED Y EQUIPOS">CABLEADO DE RED Y EQUIPOS</option>
                    </select>
                </div>

                <!-- Número de Serie -->
                <div>
                    <label class="block text-sm font-medium">Nro. de Serie</label>
                    <input type="text" id="serieInstalar" class="form-input w-full mt-2"
                        placeholder="Ingrese Nro. de Serie">
                </div>

                <!-- Botón Guardar -->
                <div class="flex justify-end">
                    <button id="guardarInstalar" class="btn btn-primary px-6 py-2">Guardar</button>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos - Instalar (más ancha en pantallas grandes) -->
        <div class="lg:col-span-2 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4">Productos Instalados</span>
            <div class="overflow-x-auto mt-2">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-center">Producto</th>
                            <th class="px-4 py-2 text-center">Marca</th>
                            <th class="px-4 py-2 text-center">Modelo</th>
                            <th class="px-4 py-2 text-center">Nro. Serie</th>
                        </tr>
                    </thead>
                    <tbody id="tablaInstalar"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sección Retirar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Sección Retirar (estrecha en grandes, ocupa todo en medianas y chicas) -->
        <div class="lg:col-span-1 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4 badge bg-success mb-4">Retirar</span>
            <div class="grid grid-cols-1 gap-4">
                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-medium mt-2">Tipo</label>
                    <select id="tipoRetirar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Tipo</option>
                        <option value="Retiro">Retiro</option>
                    </select>
                </div>

                <!-- Tipo Producto -->
                <div>
                    <label class="block text-sm font-medium">Tipo Producto</label>
                    <select id="tipoProductoRetirar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Tipo de Producto</option>
                        <option value="ADAPTADOR">ADAPTADOR</option>
                    </select>
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-sm font-medium">Marca</label>
                    <select id="marcaRetirar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Marca</option>
                        <option value="STARTECH">STARTECH</option>
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label class="block text-sm font-medium">Modelo</label>
                    <select id="modeloRetirar" class="nice-select w-full mt-2" style="display: none">
                        <option value="" disabled selected>Seleccionar Modelo</option>
                        <option value="MDP2VGDVHDW">MDP2VGDVHDW</option>
                    </select>
                </div>

                <!-- Número de Serie -->
                <div>
                    <label class="block text-sm font-medium">Nro. de Serie</label>
                    <input type="text" id="serieRetirar" class="form-input w-full mt-2"
                        placeholder="Ingrese Nro. de Serie">
                </div>

                <!-- Botón Guardar -->
                <div class="flex justify-end">
                    <button id="guardarRetirar" class="btn btn-primary px-6 py-2">Guardar</button>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos - Retirar (más ancha en pantallas grandes) -->
        <div class="lg:col-span-2 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4">Productos Retirados</span>
            <div class="overflow-x-auto mt-2">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-center">Producto</th>
                            <th class="px-4 py-2 text-center">Marca</th>
                            <th class="px-4 py-2 text-center">Modelo</th>
                            <th class="px-4 py-2 text-center">Nro. Serie</th>
                        </tr>
                    </thead>
                    <tbody id="tablaRetirar"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        NiceSelect.bind(document.getElementById("estado"));
        const estadoSelect = document.getElementById("estado");
    });

    document.addEventListener("DOMContentLoaded", function() {
        const herramientasContainer = document.getElementById("herramientasContainer");
        const addHerramientaBtn = document.getElementById("addHerramienta");

        // Inicializar Select2 en los selects existentes
        function inicializarSelects() {
            $(".herramienta-select").select2({
                width: "100%", // Asegura que el select ocupa todo el espacio disponible
                placeholder: "Seleccione una herramienta",
                allowClear: true
            });
        }

        inicializarSelects(); // Llamar a la función para inicializar el primer select

        // Agregar una nueva fila al presionar el botón "+"
        addHerramientaBtn.addEventListener("click", function() {
            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("flex", "items-center", "gap-2", "mt-2", "herramienta-row");

            nuevaFila.innerHTML = `
            <select class="form-input w-full herramienta-select">
                <option value="DISCO DURO DE 2 TB">DISCO DURO DE 2 TB</option>
                <option value="MEMORIA RAM 16GB">MEMORIA RAM 16GB</option>
                <option value="PROCESADOR INTEL I7">PROCESADOR INTEL I7</option>
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeHerramienta">-</button>
        `;

            herramientasContainer.appendChild(nuevaFila);
            herramientasContainer.scrollTop = herramientasContainer
                .scrollHeight; // Hace scroll automáticamente al final

            // Inicializar Select2 en el nuevo select
            $(nuevaFila).find(".herramienta-select").select2({
                width: "100%",
                placeholder: "Seleccione una herramienta",
                allowClear: true
            });

            // Mostrar el botón de eliminar en todas las filas menos la primera
            actualizarBotonesEliminar();
        });

        // Delegación de eventos para eliminar herramientas dinámicamente
        herramientasContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeHerramienta")) {
                event.target.parentElement.remove();
                actualizarBotonesEliminar();
            }
        });

        // Función para actualizar la visibilidad del botón "-"
        function actualizarBotonesEliminar() {
            const filas = document.querySelectorAll(".herramienta-row");
            const botonesEliminar = document.querySelectorAll(".removeHerramienta");

            botonesEliminar.forEach((btn, index) => {
                btn.classList.toggle("hidden", filas.length === 1); // Ocultar si solo hay una fila
            });
        }

        // Inicializar visibilidad de los botones eliminar
        actualizarBotonesEliminar();
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener el contenedor principal
        const cardInstalarRetirar = document.getElementById("cardInstalarRetirar");

        if (cardInstalarRetirar) {
            // Seleccionar solo los selects dentro de cardInstalarRetirar
            const selectsNice = cardInstalarRetirar.querySelectorAll(".nice-select");

            // Inicializar NiceSelect2 en estos selects con búsqueda habilitada
            selectsNice.forEach(select => {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        }
    });
</script>





<script>
    document.getElementById("guardarEstado").addEventListener("click", function() {
        const estadoSelect = document.getElementById("estado");
        const estadoId = estadoSelect.value;
        const justificacion = document.getElementById("justificacion").value;

        // Validar que se haya seleccionado un estado y se haya ingresado una justificación
        if (!estadoId || !justificacion.trim()) {
            toastr.error("Debe seleccionar un estado y escribir una justificación.");
            return;
        }

        // Enviar los datos al servidor
        fetch('/api/guardarEstadoSoporte', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    idEstadoots: estadoId,
                    justificacion: justificacion.trim(),
                    idTickets: {{ $ticket->idTickets }} // Solo se pasa el ID del ticket
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success("Estado guardado correctamente.");
                } else {
                    toastr.error("Error al guardar el estado.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                toastr.error("Hubo un error al guardar el estado.");
            });
    });
</script>




<script>
  document.getElementById("estado").addEventListener("change", function() {
    const estadoId = this.value;
    const ticketId = 51;
    const visitaId = null;

    // Log para ver el valor de estadoId cuando se cambia el estado
    console.log("Estado seleccionado:", estadoId);

    // Obtener la justificación del estado seleccionado
    fetch(`/api/obtenerJustificacionSoporte?ticketId=${ticketId}&visitaId=${visitaId}&estadoId=${estadoId}`)
        .then(response => response.json())
        .then(data => {
            // Log de la respuesta del servidor
            console.log("Respuesta del servidor:", data);

            if (data.success) {
                // Mostrar la justificación en el textarea
                document.getElementById("justificacion").value = data.justificacion || "";
            } else {
                toastr.error(data.message || "Error al obtener la justificación");
            }
        })
        .catch(error => {
            // Log del error en la llamada fetch
            console.error("Error:", error);
            toastr.error("Error al obtener la justificación.");
        });
  });

  document.addEventListener("DOMContentLoaded", function() {
    // Inicializar todos los select con la clase .selectize
    document.querySelectorAll(".selectize").forEach(function(select) {
        NiceSelect.bind(select);
    });
    console.log("NiceSelect ha sido inicializado en los selects");
  });
</script>

