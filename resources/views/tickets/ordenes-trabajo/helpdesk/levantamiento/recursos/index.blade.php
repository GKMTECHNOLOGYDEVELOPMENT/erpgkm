<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
</style>



<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

    <!-- Sección de Herramientas (Izquierda) -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Articulos</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addHerramienta" class="btn btn-primary">+</button>
        </div>

        <!-- Contenedor de Herramientas -->
        <div id="herramientasContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
            <div class="flex items-center gap-2 mt-2 herramienta-row">
                <select class="form-input w-full herramienta-select">
                    @foreach ($articulosTipo1 as $articulo)
                        <option value="{{ $articulo->idArticulos }}">{{ $articulo->nombre }}</option>
                    @endforeach
                </select>
                <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
                <button type="button" class="btn btn-danger removeHerramienta hidden">-</button>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary px-6 py-2 guardarHerramientas">Guardar</button>
        </div>
    </div>

    <!-- Tabla de Resumen (Derecha) -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Resumen de Articulos</span>
        <table class="min-w-full text-sm mt-4">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="px-2 py-1 text-left">Herramienta</th>
                    <th class="px-2 py-1 text-center">Cantidad</th>
                </tr>
            </thead>
            <tbody id="tablaResumenHerramientas" class="divide-y divide-gray-200 dark:divide-gray-600">
                <!-- Se llenará dinámicamente -->
            </tbody>
        </table>
    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>




<script>
    document.addEventListener("DOMContentLoaded", function() {

        // ================= HERRAMIENTAS =================
        const herramientasContainer = document.getElementById("herramientasContainer");
        const addHerramientaBtn = document.getElementById("addHerramienta");

        function inicializarHerramientas() {
            $(".herramienta-select").select2({
                width: "100%",
                placeholder: "Seleccione una herramienta",
                allowClear: true
            });
        }

        function actualizarBotonesEliminar(containerId, removeClass) {
            const rows = document.querySelectorAll(`#${containerId} .${removeClass}`);
            rows.forEach((btn, index) => {
                btn.classList.toggle("hidden", rows.length === 1);
            });
        }

        function actualizarTablaResumen() {
            const tablaBody = document.getElementById("tablaResumenHerramientas");
            tablaBody.innerHTML = "";

            document.querySelectorAll("#herramientasContainer .herramienta-row").forEach(row => {
                const select = row.querySelector("select");
                const cantidad = row.querySelector("input[type='number']").value;
                const nombre = select.options[select.selectedIndex].text;

                const fila = document.createElement("tr");
                fila.innerHTML = `
            <td class="px-2 py-1">${nombre}</td>
            <td class="px-2 py-1 text-center">${cantidad}</td>
        `;
                tablaBody.appendChild(fila);
            });
        }

        inicializarHerramientas();
        actualizarTablaResumen(); // Inicial

        addHerramientaBtn.addEventListener("click", function() {
            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("flex", "items-center", "gap-2", "mt-2", "herramienta-row");

            nuevaFila.innerHTML = `
        <select class="form-input w-full herramienta-select">
            @foreach ($articulosTipo1 as $articulo)
                <option value="{{ $articulo->idArticulos }}">{{ $articulo->nombre }}</option>
            @endforeach
        </select>
        <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
        <button type="button" class="btn btn-danger removeHerramienta">-</button>
    `;

            herramientasContainer.appendChild(nuevaFila);
            inicializarHerramientas();
            actualizarBotonesEliminar("herramientasContainer", "removeHerramienta");
            actualizarTablaResumen();
        });

        herramientasContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeHerramienta")) {
                event.target.parentElement.remove();
                actualizarBotonesEliminar("herramientasContainer", "removeHerramienta");
                actualizarTablaResumen();
            }
        });

        herramientasContainer.addEventListener("change", function() {
            actualizarTablaResumen(); // Detecta cambios en selects o inputs
        });

    });

    // ================= FUNCIONES GENERALES =================
    function actualizarBotonesEliminar(containerId, removeClass) {
        const rows = document.querySelectorAll(`#${containerId} .${removeClass}`);
        rows.forEach((btn, index) => {
            btn.classList.toggle("hidden", rows.length === 1);
        });
    }

    function cargarOpcionesArticulo(tipoArticulo) {
        let opciones = [];
        switch (tipoArticulo) {
            case 1:
                opciones = <?php echo json_encode($articulosTipo1->toArray()); ?>;
                break;
            case 2:
                opciones = <?php echo json_encode($articulosTipo2->toArray()); ?>;
                break;
            case 3:
                opciones = <?php echo json_encode($articulosTipo3->toArray()); ?>;
                break;
            case 4:
                opciones = <?php echo json_encode($articulosTipo4->toArray()); ?>;
                break;
        }
        console.log("Opciones para tipoArticulo " + tipoArticulo + ": ",
            opciones); // Asegúrate de ver el resultado en la consola

        // Asignar las opciones a los selects correspondientes
        document.querySelectorAll(".producto-select").forEach(select => {
            select.innerHTML = ""; // Limpiar las opciones anteriores
            opciones.forEach(opcion => {
                const option = document.createElement("option");
                option.value = opcion.idArticulo;
                option.text = opcion.nombreArticulo;
                select.appendChild(option);
            });
            $(select).trigger("change"); // Re-activar el select2
        });
    }


    // Llamar la función para cargar las opciones de acuerdo al tipo de artículo seleccionado
    // Asumiendo que tienes un select de tipo de artículo con id 'tipoArticuloSelect'
    const tipoArticuloSelect = document.getElementById('tipoArticuloSelect');
    tipoArticuloSelect.addEventListener('change', function() {
    cargarOpcionesArticulo(this.value);
    });
    });
</script>








<script>
    document.addEventListener("DOMContentLoaded", function() {

        // IDs de Tickets y Visitas (Obtenidos dinámicamente desde el servidor)
        const ticketId = "{{ $ticketId }}"; // Este es el idTickets que ya tienes en el controlador
        const visitaId = "{{ $VisitaIdd }}"; // Este es el idVisitas que ya tienes en el controlador

        console.log("Ticket ID:", ticketId); // Esto es solo para asegurarte que tienes los valores correctos
        console.log("Visita ID:", visitaId); // Lo mismo para visitaId

        function guardarSuministros(tipo) {
            let suministrosData = {
                herramientas: [],
                productos: [],
                repuestos: [],
                insumos: []
            };

            // Solo se recorre la sección del tipo especificado (herramientas, productos, repuestos o insumos)
            const contenedores = [{
                    containerId: 'herramientasContainer',
                    tipo: 'herramientas'
                },
                {
                    containerId: 'productosContainer',
                    tipo: 'productos'
                },
                {
                    containerId: 'repuestosContainer',
                    tipo: 'repuestos'
                },
                {
                    containerId: 'insumosContainer',
                    tipo: 'insumos'
                }
            ];

            // Filtrar el contenedor correspondiente al tipo
            const contenedorActivo = contenedores.find(c => c.tipo === tipo);

            if (contenedorActivo) {
                const rows = document.querySelectorAll(`#${contenedorActivo.containerId} .flex`);

                rows.forEach(row => {
                    const select = row.querySelector("select");
                    const cantidad = row.querySelector("input[type='number']").value;

                    // Agregar el suministro correspondiente a su sección
                    suministrosData[tipo].push({
                        idTickets: ticketId,
                        idVisitas: visitaId,
                        idArticulos: select.value,
                        cantidad: cantidad
                    });
                });
            }

            fetch("/suministros/store", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(suministrosData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Datos guardados exitosamente.');
                    } else {
                        // Si hay errores, los mostramos en una alerta
                        alert(data.errores.join("\n"));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar los datos.');
                });
        }

        // Eventos de guardado para cada sección
        const guardarHerramientasBtn = document.querySelector(".guardarHerramientas");
        if (guardarHerramientasBtn) {
            guardarHerramientasBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar que se recargue la página
                guardarSuministros('herramientas'); // Llamar la función para guardar solo herramientas
            });
        }

        const guardarProductosBtn = document.querySelector(".guardarProductos");
        if (guardarProductosBtn) {
            guardarProductosBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar que se recargue la página
                guardarSuministros('productos'); // Llamar la función para guardar solo productos
            });
        }

        const guardarRepuestosBtn = document.querySelector(".guardarRepuestos");
        if (guardarRepuestosBtn) {
            guardarRepuestosBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar que se recargue la página
                guardarSuministros('repuestos'); // Llamar la función para guardar solo repuestos
            });
        }

        const guardarInsumosBtn = document.querySelector(".guardarInsumos");
        if (guardarInsumosBtn) {
            guardarInsumosBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar que se recargue la página
                guardarSuministros('insumos'); // Llamar la función para guardar solo insumos
            });
        }
    });
</script>
<!--

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const ticketId = "{{ $ticketId }}"; // ID del ticket
        const visitaId = "{{ $VisitaIdd }}"; // ID de la visita seleccionada

        console.log("Ticket ID:", ticketId); // Verifica que el ticketId es correcto
        console.log("Visita ID:", visitaId); // Verifica que el visitaId es correcto

        // Función para verificar si el repuesto ya existe
        async function verificarRepuestoExistente(idArticulos, idTickets) {
            console.log("Verificando repuesto: ", idArticulos, "para el ticket:", idTickets); // Depuración
            try {
                const response = await fetch("/verificarRepuesto", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        idArticulos,
                        idTickets
                    })
                });
                const data = await response.json();
                console.log("Respuesta de verificación:", data); // Depuración
                return data.exists; // Devuelve si el repuesto ya existe
            } catch (error) {
                console.error("Error al verificar repuesto:", error);
                return false; // En caso de error, asumimos que no existe
            }
        }

        // Función para guardar los suministros
        async function guardarSuministros(tipo) {
            let suministrosData = {
                herramientas: [],
                productos: [],
                repuestos: [],
                insumos: []
            };

            // Contenedores de datos de las secciones
            const contenedores = [{
                    containerId: 'herramientasContainer',
                    tipo: 'herramientas'
                },
                {
                    containerId: 'productosContainer',
                    tipo: 'productos'
                },
                {
                    containerId: 'repuestosContainer',
                    tipo: 'repuestos'
                },
                {
                    containerId: 'insumosContainer',
                    tipo: 'insumos'
                }
            ];

            // Filtrar el contenedor correspondiente al tipo
            const contenedorActivo = contenedores.find(c => c.tipo === tipo);

            if (contenedorActivo) {
                const rows = document.querySelectorAll(`#${contenedorActivo.containerId} .flex`);

                // Recorrer las filas de cada contenedor
                for (let row of rows) {
                    const select = row.querySelector("select");
                    const cantidad = row.querySelector("input[type='number']").value;

                    if (tipo === 'repuestos') {
                        const idArticulos = select.value;

                        // Verificar si el repuesto ya existe
                        const exists = await verificarRepuestoExistente(idArticulos, ticketId);

                        if (exists) {
                            alert(
                                `El repuesto ${select.options[select.selectedIndex].text} ya está registrado para esta visita.`
                                );
                        } else {
                            // Si no existe, agregar el repuesto a los datos de suministros
                            suministrosData[tipo].push({
                                idTickets: ticketId,
                                idVisitas: visitaId,
                                idArticulos: idArticulos,
                                cantidad: cantidad
                            });
                        }
                    } else {
                        // Para herramientas, productos e insumos, simplemente agregar el suministro
                        suministrosData[tipo].push({
                            idTickets: ticketId,
                            idVisitas: visitaId,
                            idArticulos: select.value,
                            cantidad: cantidad
                        });
                    }
                }
            }

            // Si hay datos para guardar, hacer la solicitud de guardado
            if (suministrosData[tipo].length > 0) {
                fetch("/suministros/store", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(suministrosData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Datos guardados exitosamente.');
                        } else {
                            alert('Hubo un error al guardar.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al enviar los datos.');
                    });
            }
        }

        // Eventos para cada botón de guardar
        const guardarHerramientasBtn = document.querySelector(".guardarHerramientas");
        if (guardarHerramientasBtn) {
            guardarHerramientasBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar recarga de página
                guardarSuministros('herramientas'); // Guardar solo herramientas
            });
        }

        const guardarProductosBtn = document.querySelector(".guardarProductos");
        if (guardarProductosBtn) {
            guardarProductosBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar recarga de página
                guardarSuministros('productos'); // Guardar solo productos
            });
        }

        const guardarRepuestosBtn = document.querySelector(".guardarRepuestos");
        if (guardarRepuestosBtn) {
            guardarRepuestosBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar recarga de página
                guardarSuministros('repuestos'); // Guardar solo repuestos, con verificación
            });
        }

        const guardarInsumosBtn = document.querySelector(".guardarInsumos");
        if (guardarInsumosBtn) {
            guardarInsumosBtn.addEventListener("click", function(event) {
                event.preventDefault(); // Evitar recarga de página
                guardarSuministros('insumos'); // Guardar solo insumos
            });
        }
    });
</script>

 -->


<script>
    const idseleccionvisita = "{{ $VisitaIdd }}"; // Ejemplo de ID de visita seleccionada
    console.log(idseleccionvisita); // Agrega esto para verificar el valor

    fetch(`/api/suministros/${idseleccionvisita}`)
        .then(response => response.json())
        .then(suministros => {
            console.log(suministros); // Ver los datos que se reciben
            suministros.forEach(suministro => {
                const herramientaSelect = document.querySelector(
                    `.herramienta-select[value="${suministro.idArticulos}"]`);
                if (herramientaSelect) {
                    const cantidadInput = herramientaSelect.closest('.herramienta-row').querySelector(
                        '.cantidad-input');
                    cantidadInput.value = suministro.cantidad;
                }
            });
        })
        .catch(error => {
            console.error('Error al obtener los suministros:', error);
        });
</script>
